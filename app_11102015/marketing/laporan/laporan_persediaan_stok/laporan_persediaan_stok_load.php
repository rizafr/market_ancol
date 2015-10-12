<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M25');
$conn = conn($sess_db);
die_conn($conn);


$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$jenis_unit			= (isset($_REQUEST['jenis_unit'])) ? clean($_REQUEST['jenis_unit']) : '';
$lokasi				= (isset($_REQUEST['lokasi'])) ? clean($_REQUEST['lokasi']) : '';
$status				= (isset($_REQUEST['status'])) ? clean($_REQUEST['status']) : '';

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$query_search = " WHERE s.KODE_BLOK != '' ";

if($lokasi!=''){
	$query_search.= " AND s.KODE_LOKASI = '$lokasi' ";
}
if($jenis_unit!=''){
	$query_search.= " AND s.KODE_UNIT = '$jenis_unit'";
}
if($status!=''){
	if($status=='1'){
		$query_search.= " AND s.STATUS_STOK = '0' AND TERJUAL = '0' ";		
	}
	else if($status=='2'){
		$query_search.= " AND s.STATUS_STOK = '1' AND TERJUAL = '0' ";		
	}
	else if($status=='3'){
		$query_search.= " AND s.STATUS_STOK = '1' AND TERJUAL = '1' ";		
	}
	else if($status=='4'){
		$query_search.= " AND s.STATUS_STOK = '1' AND TERJUAL = '2' ";		
	}
}
$query = "
SELECT  
	COUNT(s.KODE_BLOK) AS TOTAL
FROM 
	STOK s
	LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
	LEFT JOIN HARGA_BANGUNAN hb ON s.KODE_SK_BANGUNAN = hb.KODE_SK

	$query_search
";
$total_data = $conn->Execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w90">
<tr>
	<td>
		<input type="button" id="excel" value=" Excel ">
		<input type="button" id="print" value=" Print ">
	</td>
	<td class="text-right">
		<input type="button" id="prev_page" value=" < ">
		Hal : <input type="text" name="page_num" size="5" class="page_num apply text-center" value="<?php echo $page_num; ?>">
		Dari <?php echo $total_page ?> 
		<input type="hidden" id="total_page" value="<?php echo $total_page; ?>">
		<input type="button" id="next_page" value=" > ">
	</td>
</tr>
</table>

<table class="t-data w90">
<tr>
	<th rowspan="2">KODE BLOK</th>
	<th colspan="2">LUAS (M&sup2;)</th>
	<th rowspan="2">DESA</th>
	<th rowspan="2">LOKASI</th>
	<th rowspan="2">JENIS UNIT</th>	
	<th rowspan="2">TIPE</th>
	<th rowspan="2">TOTAL HARGA <br> (Rp)</th>
	<th rowspan="2">PROGRES</th>
	<th rowspan="2">STATUS</th>
</tr>
<tr>
	<th>TANAH</th>
	<th>BANGUNAN</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT  
		s.KODE_BLOK,
		s.LUAS_TANAH,
		s.LUAS_BANGUNAN,
		s.STATUS_STOK,
		s.TERJUAL,
		t.TIPE_BANGUNAN,
		hb.JENIS_BANGUNAN,
		
		(
			(
				(s.LUAS_TANAH * ht.HARGA_TANAH) + 
				((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
				((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
			)
			-
			(
				(
					(s.LUAS_TANAH * ht.HARGA_TANAH) + 
					((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
					((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
				)
				* s.DISC_TANAH / 100
			)
			+
			(
				(
					(
						(s.LUAS_TANAH * ht.HARGA_TANAH) + 
						((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
						((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
					)
					-
					(
						(
							(s.LUAS_TANAH * ht.HARGA_TANAH) + 
							((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
							((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
						)
						* s.DISC_TANAH / 100
					)
				) * s.PPN_TANAH / 100
			)
		) AS HARGA_TANAH,
		
		(
			(s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN)
			-
			((s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) * s.DISC_BANGUNAN / 100) 
			+
			(
				(s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) -
				((s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) * s.DISC_BANGUNAN / 100) 
			) 
			* s.PPN_BANGUNAN / 100
		) AS HARGA_BANGUNAN,
		
		PROGRESS, NAMA_DESA, LOKASI, JENIS_UNIT
	FROM 
		STOK s
		LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
		LEFT JOIN HARGA_BANGUNAN hb ON s.KODE_SK_BANGUNAN = hb.KODE_SK
		LEFT JOIN HARGA_TANAH ht ON s.KODE_SK_TANAH = ht.KODE_SK
		LEFT JOIN FAKTOR f ON s.KODE_FAKTOR = f.KODE_FAKTOR
		LEFT JOIN DESA g ON s.KODE_DESA = g.KODE_DESA
		LEFT JOIN LOKASI h ON s.KODE_LOKASI = h.KODE_LOKASI
		LEFT JOIN JENIS_UNIT i ON s.KODE_UNIT = i.KODE_UNIT
		$query_search
	ORDER BY s.KODE_BLOK ASC
	";
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		if ($obj->fields['STATUS_STOK'] == '0' AND $obj->fields['TERJUAL'] == '0'){
			$status = 'STOK BELUM SIAP JUAL';
		}else
		if ($obj->fields['STATUS_STOK'] == '1' AND $obj->fields['TERJUAL'] == '0'){
			$status = 'STOK SUDAH SIAP JUAL';
		}else 
		if ($obj->fields['STATUS_STOK'] == '1' AND $obj->fields['TERJUAL'] == '1'){
			$status = 'STOK SUDAH DI RESERVE';
		}else 
		if ($obj->fields['STATUS_STOK'] == '1' AND $obj->fields['TERJUAL'] == '2'){
			$status = 'STOK SUDAH TERJUAL';
		}

		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td><?php echo $id; ?></td>
			<td class="text-right"><?php echo to_decimal($obj->fields['LUAS_TANAH']); ?></td>
			<td class="text-right"><?php echo to_decimal($obj->fields['LUAS_BANGUNAN']); ?></td>
			<td><?php echo $obj->fields['NAMA_DESA']; ?></td>
			<td><?php echo $obj->fields['LOKASI']; ?></td>
			<td><?php echo $obj->fields['JENIS_UNIT']; ?></td>
			<td><?php echo $obj->fields['TIPE_BANGUNAN']; ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['HARGA_TANAH'] + $obj->fields['HARGA_BANGUNAN']); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['PROGRESS']); ?></td>
			<td class="text-left"><?php echo $status ?></td>
		</tr>
		<?php
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control w90"></table>

<script type="text/javascript">
jQuery(function($) {	
	/* -- BUTTON -- */
	
	$('#pagging-2').html($('#pagging-1').html());
	$('#total-data').html('<?php echo $total_data; ?>');
	$('#per_page').val('<?php echo $per_page; ?>');
	$('.page_num').inputmask('integer');
	t_strip('.t-data');
	
	
});
</script>

<?php
close($conn);
exit;
?>
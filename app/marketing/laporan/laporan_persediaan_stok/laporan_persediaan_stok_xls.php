<?php
require_once('../../../../config/config.php');
die_login();
//die_app('A01');
//die_mod('JB10');
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
$total_data = $conn->execute($query)->fields['TOTAL'];
$i = 1;
$filename = "LAPORAN PERSEDIAAN STOK.xls";

header("Content-type: application/msexcel");
header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h1>LAPORAN PERSEDIAAN STOK</h1>

<table border="1">
	<tr>
		<th rowspan="2" >VIRTUAL ACCOUNT</th>
		<th rowspan="2">KODE BLOK</th>
		<th rowspan="2">LUAS <br /> SEMI GROSS (M&sup2;)</th>	
		<th rowspan="2">TOWER</th>
		<th rowspan="2">JENIS UNIT</th>	
		<th rowspan="2">TIPE</th>
		<th rowspan="2">CASH KERAS</th>
		<th colspan="4">HARGA (Incl PPN)</th>
		<th rowspan="2">STATUS</th>
	</tr>
	<tr>

		<th>CB 36X</th>
		<th>CB 48X</th>
		<th>KPA 24X 40%</th>	
		<th>KPA 36X 40%</th>
		
	</tr>

	<?php
	if ($total_data > 0)
	{
		$query = "
		SELECT  
			s.NO_VA,
			s.KODE_BLOK,
			s.LUAS_BANGUNAN,
			s.STATUS_STOK,
			s.TERJUAL,
			t.TIPE_BANGUNAN,
			hs.HARGA_CASH_KERAS,
			hs.CB36X,
			hs.CB48X,
			hs.KPA24X,
			hs.KPA36X, 
			LOKASI, 
			JENIS_UNIT
		FROM 
		STOK s
		LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
		LEFT JOIN HARGA_SK hs ON s.KODE_SK = hs.KODE_SK AND s.KODE_BLOK = hs.KODE_BLOK
		LEFT JOIN LOKASI h ON s.KODE_LOKASI = h.KODE_LOKASI
		LEFT JOIN JENIS_UNIT i ON s.KODE_UNIT = i.KODE_UNIT
		$query_search
		ORDER BY s.NO_VA ASC
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
				<td><?php echo $obj->fields['NO_VA']; ?></td>
				<td class="text-center"><?php echo $id; ?></td>
				<td class="text-center"><?php echo to_decimal($obj->fields['LUAS_BANGUNAN']); ?></td>
				<td><?php echo $obj->fields['LOKASI']; ?></td>
				<td><?php echo $obj->fields['JENIS_UNIT']; ?></td>
				<td><?php echo $obj->fields['TIPE_BANGUNAN']; ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['HARGA_CASH_KERAS']); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['CB36X']); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['CB48X']); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['KPA24X']); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['KPA36X']); ?></td>
				<td class="text-left"><?php echo $status ?></td>
			</tr>
			<?php
			$obj->movenext();
		}
	}
	?>
</table>
<script type="text/javascript">
jQuery(function($) {
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
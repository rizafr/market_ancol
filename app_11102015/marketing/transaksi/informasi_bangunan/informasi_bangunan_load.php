<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M20');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$query_search = '';
if ($s_opv1 != '')
{
	$query_search .= " AND $s_opf1 LIKE '%$s_opv1%' ";
}

# Pagination
$query = "
SELECT  
	COUNT(s.KODE_BLOK) AS TOTAL
FROM 
	STOK s
	LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
	LEFT JOIN HARGA_BANGUNAN hb ON s.KODE_SK_BANGUNAN = hb.KODE_SK
	WHERE STATUS_STOK = '1' AND TERJUAL = '2'
";
$total_data = $conn->Execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w100">
<tr>
	<td class="text-right">
		<input type="button" id="prev_page" value=" < ">
		Hal : <input type="text" name="page_num" size="5" class="page_num apply text-center" value="<?php echo $page_num; ?>">
		Dari <?php echo $total_page ?> 
		<input type="hidden" id="total_page" value="<?php echo $total_page; ?>">
		<input type="button" id="next_page" value=" > ">
	</td>
</tr>
</table>

<table class="t-data w100" >
<tr>
	<th rowspan="2">NO</th>
	<th rowspan="2">BLOK/NO</th>
	<th rowspan="2">TIPE</th>
	<th colspan="2">LUAS (M&sup2;)</th>
	<th colspan="2">MEMO MARKETING</th>	
	<th rowspan="2">NOMOR SPK</th>
	<th colspan="3">KWH PLN</th>
	<th rowspan="2">PAM/Pompa Terpasang</th>
	<th rowspan="2">No. Telepon Terpasang</th>
	<th rowspan="2">Tgl. Serah Kontraktor - Proyek</th>
	<th rowspan="2">Tgl. Serah terima Proyek - purna Jual</th>
	<th colspan="3">Jadwal Pelaksanaan</th>
	<th colspan="3">Progres(%)</th>
	<th rowspan="2">Siap CL Purna Jual</th>
	<th colspan="2">Ceklis Purna Jual</th>
	<th rowspan="2">Target Selesai Proyek</th>
	<th rowspan="2">Serah terima Pemilik</th>
	<th rowspan="2">Keterangan</th>
</tr>
<tr>
	<th>BANGUNAN</th>
	<th>TANAH</th>
	<th>TANGGAL</th>
	<th>NOMOR</th>
	<th>SLO</th>
	<th>NO. KONTRAK</th>
	<th>No. PELANGGAN</th>
	<th>MULAI</th>
	<th>SELESAI</th>
	<th>DURASI</th>
	<th>RENCANA</th>
	<th>REALISASI</th>
	<th>DEVIASI</th>
	<th>SUDAH</th>
	<th>TANGGAL</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT  
		s.KODE_BLOK,
		s.LUAS_TANAH,
		s.LUAS_BANGUNAN,
		s.MEMO_MARKETING_TANGGAL,
		s.MEMO_MARKETING_NO,
		s.NOMOR_SPK,
		s.TGL_BANGUNAN,
		s.TGL_SELESAI,
		s.RENCANA,
		s.REALISASI,
		
		(s.RENCANA-s.REALISASI) AS DEVIASI,
		s.SIAP_CHECKLIST_PURNAJUAL,
		s.CHECKLIST_PURNAJUAL,
		s.CHECKLIST_PURNAJUAL_TGL,
		s.TARGET_SELESAI_PROYEK,
		t.TIPE_BANGUNAN,
		
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
		WHERE STATUS_STOK = '1' AND TERJUAL = '2'
	ORDER BY s.KODE_BLOK ASC
	";
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
		<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['TIPE_BANGUNAN']; ?></td>
			<td class="text-right"><?php echo to_decimal($obj->fields['LUAS_BANGUNAN']); ?></td>
			<td class="text-right"><?php echo to_decimal($obj->fields['LUAS_TANAH']); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['MEMO_MARKETING_TANGGAL']))); ?></td>
			<td><?php echo $obj->fields['MEMO_MARKETING_NO']; ?></td>
			<td><?php echo $obj->fields['NOMOR_SPK']; ?></td>
			<td><?php echo $obj->fields['KWH_SLO']; ?></td>
			<td><?php echo $obj->fields['KWH_NO_KONTRAK']; ?></td>
			<td><?php echo $obj->fields['KWH_NO_PELANGGAN']; ?></td>
			<td><?php echo $obj->fields['KWH_NO_KONTRAK']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['PAM_TERPASANG']))); ?></td>
			<td><?php echo $obj->fields['NO_TLP_TERPASANG']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TGL_SERAH_KONTRAKTOR']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TGL_SERAH_PROYEK']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['JADWAL_MULAI']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['JADWAL_SELESAI']))); ?></td>
			<td><?php echo $obj->fields['DURASI']; ?></td>
			<td><?php echo $obj->fields['RENCANA']; ?></td>
			<td><?php echo $obj->fields['REALISASI']; ?></td>
			<td><?php echo $obj->fields['DEVIASI']; ?></td>
			<td><?php echo $obj->fields['SIAP_CHECKLIST_PURNAJUAL']; ?></td>
			<td><?php echo $obj->fields['CHECKLIST_PURNAJUAL']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['CHECKLIST_PURNAJUAL_TGL']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TARGET_SELESAI_PROYEK']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TGL_SERAH_TERIMA']))); ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control w100"></table>

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
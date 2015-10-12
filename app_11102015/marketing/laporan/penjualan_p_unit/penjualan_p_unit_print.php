<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M21');
$conn = conn($sess_db);
die_conn($conn);

$jenis_unit			= (isset($_REQUEST['jenis_unit'])) ? clean($_REQUEST['jenis_unit']) : '';
$lokasi				= (isset($_REQUEST['lokasi'])) ? clean($_REQUEST['lokasi']) : '';

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search = " WHERE a.KODE_BLOK != '' ";

if($lokasi!=''){
	$query_search.= " AND a.KODE_LOKASI = '$lokasi' ";
}
if($jenis_unit!=''){
	$query_search.= " AND a.KODE_UNIT = '$jenis_unit'";
}
if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= " AND b.TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND b.TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105)";
}


# Pagination
$query = "
SELECT  
	COUNT(a.KODE_BLOK) AS TOTAL
FROM 
	SPP b join
	STOK a ON b.KODE_BLOK = a.KODE_BLOK
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$i = 1;
?>

<link type="text/css" href="../../../../config/css/style.css" rel="stylesheet">
<style type="text/css">
	@media print{
		thead{display: table-header-group;}
	}
</style>
<body onload="window.print()">
<table class="t-data w100">
	<thead>
		<tr>
			<th>NO.</th>
			<th>BLOK / NOMOR</th>
			<th>NO SPP</th>
			<th>NAMA PEMBELI</th>
			<th>TANGGAL</th>
			<th>TIPE </th>
			<th>SPECS</th>
		</tr>
	</thead>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT a.KODE_BLOK, b.NOMOR_SPP, b.NAMA_PEMBELI, b.TANGGAL_SPP, c.TIPE_BANGUNAN, d.JENIS_UNIT
	FROM SPP b
	LEFT JOIN STOK a ON b.KODE_BLOK = a.KODE_BLOK         
	LEFT JOIN TIPE c ON a.KODE_TIPE = c.KODE_TIPE
	LEFT JOIN JENIS_UNIT d ON a.KODE_UNIT = d.KODE_UNIT
	$query_search
	ORDER BY a.KODE_BLOK
	";
	$obj = $conn->Execute($query);
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NOMOR_SPP']; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP']))); ?></td>
			<td><?php echo $obj->fields['TIPE_BANGUNAN']; ?></td>
			<td><?php echo $obj->fields['JENIS_UNIT']; ?></td>
		</tr>
		<?php
		$i++;
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
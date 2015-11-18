<?php
require_once('../../../../config/config.php');
die_login();
//die_app('A01');
//die_mod('JB10');
$conn = conn($sess_db);
die_conn($conn);


$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search = " WHERE KODE_BLOK != '' ";
if ($s_opv1 != '')
{
	$query_search .= " AND $s_opf1 LIKE '%$s_opv1%' ";
}
if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= " AND TANGGAL_RESERVE >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL_RESERVE <= CONVERT(DATETIME,'$periode_akhir',105)";
}

/* Pagination */

$query = "
SELECT  
	COUNT(KODE_BLOK) AS TOTAL
FROM 
	RESERVE
$query_search
";

$total_data = $conn->execute($query)->fields['TOTAL'];
$i = 1;
$filename = "LAPORAN RESERVE.xls";

header("Content-type: application/msexcel");
header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
header("Pragma: no-cache");
header("Expires: 0");
/* End Pagination */
?>

<h1>LAPORAN RESERVE</h1>
<?php echo '<h2>Periode ' .kontgl(date("d M Y", strtotime($periode_awal))). ' s/d ' .kontgl(date("d M Y", strtotime($periode_akhir))).'</h2>' ?>
<table border="1">
<tr>
	<thead>
		<th>KODE BLOK</th>
		<th>NAMA CALON PEMBELI</th>
		<th>TANGGAL RESERVE</th>
		<th>BERLAKU SAMPAI</th>
		<th>ALAMAT</th>
		<th>TELEPON</th>
		<th>AGEN</th>
		<th>KOORDINATOR</th>	
	</thead>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT  
		KODE_BLOK,
		NAMA_CALON_PEMBELI,
		TANGGAL_RESERVE,
		BERLAKU_SAMPAI,
		ALAMAT,
		TELEPON,
		AGEN,
		KOORDINATOR
	FROM
		RESERVE
		$query_search
	ORDER BY KODE_BLOK, TANGGAL_RESERVE
	";
	$obj = $conn->Execute($query);
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		$nama = $obj->fields['NAMA_CALON_PEMBELI'];
		?>
		<tr  id="<?php echo $id; ?>" nama="<?php echo $nama; ?>"> 
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_CALON_PEMBELI']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_RESERVE']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['BERLAKU_SAMPAI']))); ?></td>
			<td><?php echo $obj->fields['ALAMAT']; ?></td>
			<td><?php echo $obj->fields['TELEPON']; ?></td>
			<td><?php echo $obj->fields['AGEN']; ?></td>
			<td><?php echo $obj->fields['KOORDINATOR']; ?></td>
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
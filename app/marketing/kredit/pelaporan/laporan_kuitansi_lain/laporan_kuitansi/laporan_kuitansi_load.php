<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search = '';
if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= "WHERE TANGGAL >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL <= CONVERT(DATETIME,'$periode_akhir',105)";
}

/* Pagination */

$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	KWITANSI
$query_search
";

$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
?>

<table id="pagging-1" class="t-control">
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

<table class="t-nowrap t-data wm100">
<tr>
	<th rowspan="2">NO.</th>
	<th rowspan="2">NOMOR KUITANSI</th>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">NAMA PEMBAYAR</th>
	<th rowspan="2">TANGGAL</th>
	<th rowspan="2">NILAI</th>
	<th colspan="2">PARAF</th>
</tr>
<tr>
	<th colspan="1">Col.</th>
	<th colspan="1">Keu.</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT *
	FROM 
		KWITANSI
	$query_search
	ORDER BY NOMOR_KWITANSI
	";

	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_KWITANSI'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBAYAR']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>
			<td class="text-center"><?php echo status_check($obj->fields['VER_COLLECTION']); ?></td>
			<td class="text-center"><?php echo status_check($obj->fields['VER_KEUANGAN']); ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}	
}
?>
</table>

<table id="pagging-2" class="t-control"></table>

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
<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C02');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$search1	= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$search2	= (isset($_REQUEST['tahun'])) ? clean($_REQUEST['tahun']) : '';

$query_search1 = '';
$query_search2 = '';
if ($search1 != '')
{
	$query_search1 .= " WHERE KETERANGAN LIKE '%$search1%'";
}
if ($search2 != '')
{
	$query_search2 .= " WHERE YEAR(TANGGAL_AWAL) = '$search2'";
}

# Pagination
$query = "
SELECT 
	COUNT(TANGGAL_AWAL) AS TOTAL
FROM 
	CS_HARI_LIBUR
$query_search2
$query_search1
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w50">
<tr>
	<td>
		<input type="button" id="tambah" value=" Tambah ">
		<input type="button" id="hapus" value=" Hapus ">
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

<table class="t-data w50">
<tr>
	<th class="w5"><input type="checkbox" id="cb_all"></th>
	<th class="w20">TANGGAL AWAL</th>
	<th class="w20">TANGGAL AKHIR</th>
	<th class="w70">KETERANGAN</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		TANGGAL_AWAL, TANGGAL_AKHIR, KETERANGAN
	FROM 
		CS_HARI_LIBUR
	$query_search2
	$query_search1
	ORDER BY TANGGAL_AWAL
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);

	while( ! $obj->EOF)
	{
		$id = tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_AWAL'])));
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_AWAL']))); ?></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_AKHIR']))); ?></td>
			<td><?php echo $obj->fields['KETERANGAN']; ?></td>
		</tr>
		<?php
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control w50"></table>

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
<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C06');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$search		= (isset($_REQUEST['no_va'])) ? clean($_REQUEST['no_va']) : '';

$query_search = '';
if ($search != '')
{
	$query_search .= " AND NOMOR_MEMO LIKE '%$search%'";
}

# Pagination
$query = "
SELECT 
	COUNT(NOMOR_MEMO) AS TOTAL_DATA
	FROM CS_MEMO_PEMBATALAN
	WHERE NOMOR_MEMO != ' ' $query_search
	GROUP BY NOMOR_MEMO	
";
$total_data = $conn->execute($query)->fields['TOTAL_DATA'];
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
	<th class="w10">NOMOR MEMO</th>
	<th class="w20">TANGGAL MEMO</th>
	<th class="w20">JUMLAH DATA PEMBATALAN</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT NOMOR_MEMO, MAX(TANGGAL_MEMO) AS TGL_MEMO, COUNT(NOMOR_MEMO) AS TOTAL_DATA
	FROM CS_MEMO_PEMBATALAN
	WHERE NOMOR_MEMO != ' ' $query_search
	GROUP BY NOMOR_MEMO	
	ORDER BY NOMOR_MEMO
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);

	while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_MEMO'];

		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
			<td class="text-center"><?php echo $obj->fields['NOMOR_MEMO']; ?></td>
			<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TGL_MEMO'])))); ?></td>
			<td class="text-center"><?php echo $obj->fields['TOTAL_DATA'].' pelanggan'; ?></td>
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
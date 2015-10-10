<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M18');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1		= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1	= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';
$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : date('d-m-Y');
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : date('d-m-Y');

$query_search = '';
if ($search1 != '')
{
	$query_search .= " AND $field1 LIKE '%$search1%' ";
}

if ($periode_awal != '' && $periode_akhir != '')
{
	$query_search .= " AND TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105)";
}

/* Pagination */
$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	SPP
WHERE STATUS_SPP = 1
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
?>

<table id="pagging-1" class="t-control w100">
<tr>
	<td>
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

<table class="t-data w100">
<tr>
	<th class="w1"><input type="checkbox" id="cb_all"></th>
	<th class="w15">BLOK / NOMOR</th>
	<th class="w20">NAMA PEMBELI</th>
	<th class="w10">NOMOR SPP</th>
	<th class="w10">TANGGAL SPP</th>
	<th class="w45">ALAMAT RUMAH</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT *
	FROM 
		SPP
	WHERE STATUS_SPP = 1
	$query_search
	ORDER BY TANGGAL_SPP DESC
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);

	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];		
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI'];  ?></td>
			<td class="text-center"><?php echo to_money($obj->fields['NOMOR_SPP']);  ?></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP'])));  ?></td>
			<td><?php echo $obj->fields['ALAMAT_RUMAH'];  ?></td>
		</tr>
		<?php
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
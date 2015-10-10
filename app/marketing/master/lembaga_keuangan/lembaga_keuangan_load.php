<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M08');
$conn = conn($sess_db);
die_conn($conn);


$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1	= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$query_search = '';
if ($s_opv1 != '')
{
	$query_search .= " WHERE $s_opf1 LIKE '%$s_opv1%' ";
}

# Pagination
$query = "
SELECT 
	COUNT(KODE_BANK) AS TOTAL
FROM 
	BANK 
$query_search
";
$total_data = $conn->Execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w70">
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

<table class="t-data w70">
<tr>
	<th class="w5"><input type="checkbox" id="cb_all"></th>
	<th class="w5">KODE</th>
	<th class="w20">NAMA BANK</th>
	<th class="w40">ALAMAT</th>
	<th class="w15">NPWP</th>
	<th class="w20">NOMOR VA UNIT</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		KODE_BANK, 
		NAMA_BANK,
		ISNULL(ALAMAT_BANK,'') AS ALAMAT_BANK,
		ISNULL(NPWP,'') AS NPWP,
		ISNULL(KODE_VA_UNIT,'') AS KODE_VA_UNIT
	FROM 
		BANK
	$query_search
	ORDER BY KODE_BANK ASC
	";
	
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BANK'];
		$nm = $obj->fields['NAMA_BANK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_BANK']; ?></td>
			<td><?php echo $obj->fields['ALAMAT_BANK']; ?></td>
			<td><?php echo $obj->fields['NPWP']; ?></td>
			<td><?php echo $obj->fields['KODE_VA_UNIT']; ?></td>
		</tr>
		<?php
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control w70"></table>

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
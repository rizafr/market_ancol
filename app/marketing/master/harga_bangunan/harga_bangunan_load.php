<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M10');
$conn = conn($sess_db);
die_conn($conn);


$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1	= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$status_otorisasi	= (isset($_REQUEST['status_otorisasi'])) ? clean($_REQUEST['status_otorisasi']) : '';

$query_search = '';

if ($status_otorisasi == 0)
	{
		$query_search .= "WHERE STATUS = '0' ";
	}
else if ($status_otorisasi == 1)
	{
		$query_search .= "WHERE STATUS = '1' ";
	}
if ($s_opv1 != '')
{
	$query_search .= " AND $s_opf1 LIKE '%$s_opv1%' ";
}	

# Pagination
$query = "
SELECT 
	COUNT(hb.KODE_SK) AS TOTAL
FROM 
	HARGA_SK hb
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
		<input type="button" id="upload" value=" Upload Harga SK">
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
	<th class="w5">KODE SK</th>
	<th class="w10">KODE BLOK</th>
	<th class="w10">CASH KERAS</th>
	<th class="w10">CB 36X</th>
	<th class="w10">CB 48X</th>
	<th class="w10">KPA 24X 40%</th>
	<th class="w10">KPA 36X 40%</th>
	<th class="w10">TANGGAL</th>
	<th class="w5">STATUS</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		hb.KODE_SK,
		hb.KODE_BLOK,
		hb.TANGGAL,
		hb.HARGA_CASH_KERAS,
		hb.CB36X,
		hb.CB48X,
		hb.KPA24X,
		hb.KPA36X,
		hb.STATUS		
	FROM 
		HARGA_SK hb
	$query_search
	ORDER BY hb.KODE_SK
	";
	
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_SK'];
		$kode_blok = $obj->fields['KODE_BLOK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>" blok="<?php echo $kode_blok; ?>">
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['HARGA_CASH_KERAS']); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['CB36X']); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['CB48X']); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['KPA24X']); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['KPA36X']); ?></td>
			<td><?php echo tgltgl(f_tgl($obj->fields['TANGGAL'])); ?></td>
			<td class="text-center"><?php echo status_check($obj->fields['STATUS']); ?></td>
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
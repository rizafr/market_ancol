<?php
require_once('../../../../config/config.php');
die_login();
die_app('A01');
die_mod('PT02');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$query_search = '';
if ($s_opv1 != '')
{
	$query_search .= "WHERE $s_opf1 LIKE '%$s_opv1%' ";
}

# Pagination
$query = "
SELECT  
	COUNT(KODE_BLOK) AS TOTAL
FROM 
	RESERVE
$query_search
";
$total_data = $conn->Execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control">
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

<table class="t-data">
<tr>
	<th>KODE BLOK</th>
	<th>NAMA CALON PEMBELI</th>
	<th>TANGGAL RESERVE</th>
	<th>BERLAKU SAMPAI</th>
	<th>ALAMAT</th>
	<th>TELEPON</th>
	<th>AGEN</th>
	<th>KOORDINATOR</th>	
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
	
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		$nama = $obj->fields['NAMA_CALON_PEMBELI'];
		$alamat = $obj->fields['ALAMAT'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>" nama="<?php echo $nama; ?>"> 
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
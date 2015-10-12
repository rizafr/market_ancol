<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('P');
die_mod('P07');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1		= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1	= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$status_verifikasi	= (isset($_REQUEST['status_verifikasi'])) ? clean($_REQUEST['status_verifikasi']) : '';
$tombol				= (isset($_REQUEST['tombol'])) ? clean($_REQUEST['tombol']) : '';
$nama_tombol		= (isset($_REQUEST['nama_tombol'])) ? clean($_REQUEST['nama_tombol']) : '';

$query_search = '';
if ($status_verifikasi == 0)
	{
		$query_search .= "WHERE STATUS_OTORISASI IS NULL ";
	}
else if ($status_verifikasi == 1)
	{
		$query_search .= "WHERE STATUS_OTORISASI = '1' ";
	}		
	
if ($search1 != '')
{
	$query_search .= " AND $field1 LIKE '%$search1%' ";
}

# Pagination 
$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	CS_PPJB z
	JOIN SPP a ON z.KODE_BLOK = a.KODE_BLOK
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination 
?>

<table id="pagging-1" class="t-control w60">
<tr>	
	<td>
		<input type="button" id="<?php echo $tombol; ?>" value=" <?php echo $nama_tombol; ?> ">
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

<table class="t-data w60">
<tr>
	<th class="w5">NO.</th>
	<th class="w15">BLOK / NOMOR</th>
	<th class="w20">NOMOR PPJB</th>
	<th class="w15">TANGGAL</th>
	<th class="w70">NAMA PEMBELI</th>
	<th class="w5"><input type="checkbox" id="cb_all"></th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT *
	FROM 
		CS_PPJB z
	JOIN SPP a ON z.KODE_BLOK = a.KODE_BLOK
	$query_search
	ORDER BY z.KODE_BLOK
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	while( ! $obj->EOF)
	{
		$status = $obj->fields['STATUS_OTORISASI'];
		
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td class="text-center"><?php echo $obj->fields['NOMOR']; ?></td>
			<td class="text-center"><?php echo date("d-m-Y", strtotime($obj->fields['TANGGAL'])); ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td width="30" class="notclick text-center">
				<?php echo "<input type='checkbox' name='cb_data[]' class='cb_data' value='$id'"; ?>
			</td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control w60"></table>

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
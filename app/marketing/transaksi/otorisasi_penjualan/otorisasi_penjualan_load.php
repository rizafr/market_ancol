<?php
require_once('../../../../config/config.php');
die_login();
die_app('A01');
die_mod('PT04');
$conn = conn($sess_db);
die_conn($conn);


$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$status_otorisasi	= (isset($_REQUEST['status_otorisasi'])) ? clean($_REQUEST['status_otorisasi']) : '';
$tombol				= (isset($_REQUEST['tombol'])) ? clean($_REQUEST['tombol']) : '';
$nama_tombol		= (isset($_REQUEST['nama_tombol'])) ? clean($_REQUEST['nama_tombol']) : '';

$query_search = '';
if ($status_otorisasi == 0)
	{
		$query_search .= "WHERE OTORISASI = '0' ";
	}
else if ($status_otorisasi == 1)
	{
		$query_search .= "WHERE OTORISASI = '1' ";
	}		
	
if ($s_opv1 != '')
{
	$query_search .= " AND $s_opf1 LIKE '%$s_opv1%' ";
}
$query = "
SELECT  
	COUNT(KODE_BLOK) AS TOTAL
FROM 
    SPP
	$query_search
";
$total_data = $conn->Execute($query)->fields['TOTAL'];
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
	<th>KODE BLOK</th>
	<th>NAMA PEMBELI</th>
	<th>TGL. SPP</th>
	<th>KODE AGEN</th>
	<th>KODE KOORDINATOR</th>
	<th>OTORISASI<br><input type="checkbox" id="cb_all"></th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		s.KODE_BLOK, 
		s.NAMA_PEMBELI, 
		s.TANGGAL_SPP, 
		c.NAMA AS KODE_AGEN, 
		cb.NAMA AS KODE_KOORDINATOR
	FROM SPP s 
		LEFT JOIN CLUB_PERSONAL cb ON s.KODE_KOORDINATOR = cb.NOMOR_ID 
		LEFT JOIN CLUB_PERSONAL c ON s.KODE_AGEN = c.NOMOR_ID
		$query_search
	ORDER BY s.KODE_BLOK ASC
	";
	
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP']))); ?></td>
			<td><?php echo $obj->fields['KODE_AGEN']; ?></td>
			<td><?php echo $obj->fields['KODE_KOORDINATOR']; ?></td>
			<td class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
		</tr>
		<?php
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
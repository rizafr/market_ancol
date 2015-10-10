<?php
require_once('../../../../../../config/config.php');
die_login();
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$status_otorisasi	= (isset($_REQUEST['status_otorisasi'])) ? clean($_REQUEST['status_otorisasi']) : '';
$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1			= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';
$nama_tombol		= (isset($_REQUEST['nama_tombol'])) ? clean($_REQUEST['nama_tombol']) : '';
$tombol				= (isset($_REQUEST['tombol'])) ? clean($_REQUEST['tombol']) : '';

$query_search = '';
if ($status_otorisasi == 0)
	{
		$query_search .= "WHERE OTORISASI = '0' ";
	}
else if ($status_otorisasi == 1)
	{
		$query_search .= "WHERE OTORISASI = '1' ";
	}	
	

if ($search1 != '')
{
	$query_search .= " AND $field1 LIKE '%$search1%' ";
}

/* Pagination */
$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	CS_INFORMASI_DENDA
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
?>

<table id="pagging-1" class="t-control w60">
<tr>
	<td>
		<input type="button" id="hapus" value=" Hapus ">	
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
	<th rowspan="2"><input type="checkbox" id="cb_all"></th>
	<th rowspan="2">NO.</th>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">TANGGAL</th>
	<th rowspan="2">KETERLAMBATAN</th>
	<th rowspan="2">NILAI</th>
	<th colspan="2">PERHITUNGAN DENDA</th>
	
</tr>
<tr>
	<th colspan="1">AWAL</th>
	<th colspan="1">DISETUJUI</th>
</tr>
<?php

if ($total_data > 0)
{

	$query = "
	SELECT *, DISETUJUI = CASE WHEN denda_disetujui IS null 
	THEN '0.00' ELSE DENDA_DISETUJUI END
	FROM CS_INFORMASI_DENDA
	$query_search
	ORDER BY TANGGAL desc
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1;
	while( ! $obj->EOF)
	{

		$id = $obj->fields['KODE_BLOK'];
		$tgl = $obj->fields['TANGGAL'];
		
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id.','.$tgl; ?>"></td>
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td><?php echo $obj->fields['HARI_TUNGGAKAN']; ?></td>
			<td class="text-center"><?php echo to_money($obj->fields['NILAI']); ?></td>
			<td><?php echo to_money($obj->fields['DENDA']); ?></td>
			<td><?php echo to_money($obj->fields['DISETUJUI']); ?></td>		
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
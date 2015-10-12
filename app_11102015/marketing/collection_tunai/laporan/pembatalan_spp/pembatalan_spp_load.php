<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C30');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search = '';	

if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= "AND TANGGAL_MEMO >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL_MEMO <= CONVERT(DATETIME,'$periode_akhir',105)";
}

/* Pagination */
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
/* End Pagination */
?>

<table id="pagging-1" class="t-control w60">
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


<table class="t-data w100">
<tr>
	<th class="w5">NO</th>
	<th class="w10">BLOK / NOMOR</th>
	<th class="w20">NAMA PEMBELI</th>
	<th class="w10">TANGGAL SPP</th>
	<th class="w10">TANGGAL BATAL</th>
	<th class="w10">NILAI TRANSAKSI</th>
	<th class="w10">NILAI PEMBAYARAN</th>
	<th class="w20">ALASAN</th>
</tr>
<?php

if ($total_data > 0)
{

	$query = "
	SELECT *
	FROM CS_MEMO_PEMBATALAN
	WHERE NOMOR_MEMO != ' ' $query_search
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1;
	while( ! $obj->EOF)
	{

		$id = $obj->fields['KODE_BLOK'];
		
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SPP'])))); ?></td>
			<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_MEMO'])))); ?></td>
			<td class="text-center"><?php echo to_money($obj->fields['NILAI_TRANSAKSI']); ?></td>
			<td class="text-center"><?php echo to_money($obj->fields['TOTAL_PEMBAYARAN']); ?></td>
			<td></td>
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
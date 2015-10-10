<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('C01');
//die_mod('COF02');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$search1	= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$query_search = '';

if ($search1 != '')
{
	$query_search .= "AND KODE_BLOK LIKE '%$search1%'";
}

# Pagination
$query = "
SELECT 
	COUNT (*) AS TOTAL
FROM 
	SPP
WHERE
	STATUS_SPP IS NULL
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w100">
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

<table class="t-data w100">
<tr>
	<th class="w1">NO</th>
	<th class="w2">BLOK/NOMOR</th>
	<th class="w10">NAMA</th>
	<th class="w6">TANGGAL SPP</th>
	<th class="w2">TUNAI/KPR</th>
	<th class="w2">DISTRIBUSI SPP</th>
	<th class="w10">KETERANGAN</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		KODE_BLOK, NAMA_PEMBELI, TANGGAL_SPP, STATUS_SPP, STATUS_KOMPENSASI
	FROM 
		SPP
	WHERE
		STATUS_SPP IS NULL
	$query_search
	ORDER BY TANGGAL_SPP
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
		
		
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		if ($obj->fields['STATUS_KOMPENSASI'] == NULL){
			$kompensasi = '-';
		}else
		if ($obj->fields['STATUS_KOMPENSASI']== '1'){
			$kompensasi = 'KPR';
		}else 
		if ($obj->fields['STATUS_KOMPENSASI'] == '2'){
			$kompensasi = 'TUNAI';
		}
		
		if ($obj->fields['STATUS_SPP'] == NULL){
			$distribusi = '-';
		}
		
		if ($obj->fields['STATUS_SPP'] == NULL){
			$keterangan = 'STATUS DISTRIBUSI BELUM ADA';
		}
		
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-left"><?php echo $id; ?></td>
			<td class="text-left"><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td class="text-left"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP']))); ?></td>
			<td class="text-left"><?php echo $kompensasi ?></td>
			<td class="text-left"><?php echo $distribusi ?></td>
			<td class="text-left"><?php echo $keterangan ?></td>
			
		</tr>
		<?php
		$i++;
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
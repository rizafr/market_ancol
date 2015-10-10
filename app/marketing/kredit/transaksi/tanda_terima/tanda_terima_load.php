<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
die_mod('K03');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1		= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1	= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$status		= (isset($_REQUEST['status'])) ? clean($_REQUEST['status']) : '';
$query_search = '';
if($status=='1'){
	$query_search .= " AND STATUS_KWT = '1'";
}
else if($status=='2'){
	$query_search .= " AND STATUS_KWT = '0' OR STATUS_KWT IS NULL ";
}
else if($status=='3'){
	$query_search .= " AND STATUS_KWT = '3'";
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
	KWITANSI_TANDA_TERIMA
WHERE KODE_BLOK IS NOT NULL
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w70">
<tr>
	<td>
		<input type="button" id="tambah" value=" Tambah ">
		<input type="button" id="hapus" value=" Batal ">
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
	<th class="w1"><input type="checkbox" id="cb_all"></th>
	<th class="w15">NOMOR TTS</th>
	<th class="w10">KODE BLOK</th>
	<th class="w20">NAMA PEMBAYAR</th>
	<th class="w10">TANGGAL</th>
	<th class="w10" >STATUS / NOMOR KWITANSI</th>	
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT NOMOR_KWITANSI, KODE_BLOK, TANGGAL, NAMA_PEMBELI, BAYAR_UNTUK,CASE WHEN STATUS_KWT IS NULL THEN '-' ELSE STATUS_KWT END AS STATUS_KWT  FROM KWITANSI_TANDA_TERIMA WHERE KODE_BLOK IS NOT NULL
	$query_search
	ORDER BY TANGGAL DESC
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);

	while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_KWITANSI'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<?php
				$query  = "SELECT NOMOR_KWITANSI FROM KWITANSI WHERE NOMOR_TANDA_TERIMA ='$id'";
				$data	= $conn->Execute($query)->fields['NOMOR_KWITANSI'];
				if(!isset($data)){
					$data = '-';
					if($obj->fields['STATUS_KWT']=='3'){
						$data = 'Batal';
					}

				}
			?>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td class = 'text-center'><?php echo $data;?></td>
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
<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('C');
die_mod('C03');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$search		= (isset($_REQUEST['no_va'])) ? clean($_REQUEST['no_va']) : '';

$query_search = '';
if ($search != '')
{
	$query_search .= " WHERE b.NOMOR_VA LIKE '%$search%'";
}

# Pagination
$query = "
SELECT NOMOR_VA
FROM 
	CS_VIRTUAL_ACCOUNT $query_search
group by NOMOR_VA
";
$n = 0;
$obj = $conn->execute($query);
while( ! $obj->EOF)
{
	$n++;
	$obj->movenext();
}
$total_data = $n;
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w50">
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

<table class="t-nowrap t-data">
<tr>
	<th class="w5">NO.</th>
	<th class="w10">KODE BLOK</th>
	<th class="w20">NAMA PEMESAN</th>
	<th class="w20">VIRTUAL ACCOUNT</th>
	<th class="w10">TANGGAL TRANSAKSI TERAKHIR</th>
	<th class="w20">NILAI TRANSAKSI TERAKHIR</th>
	<th class="w20">NILAI BELUM DIIDENTIFIKASI</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT a.KODE_BLOK, a.NAMA_PEMBELI, b.NOMOR_VA, MAX(b.TANGGAL) AS TGL ,MAX(b.NILAI) as MAX_NILAI, SUM(b.SISA) as JML_SISA 
	FROM SPP a RIGHT JOIN CS_VIRTUAL_ACCOUNT b 
	ON a.NOMOR_CUSTOMER = b.NOMOR_VA
	$query_search
	GROUP BY b.NOMOR_VA, a.KODE_BLOK, a.NAMA_PEMBELI
	ORDER BY a.KODE_BLOK
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-left"><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td class="text-left"><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td class="text-left"><?php echo $obj->fields['NOMOR_VA']; ?></td>
			<td class="text-left"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TGL']))); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['MAX_NILAI']); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['JML_SISA']); ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
}
?>
</table>

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
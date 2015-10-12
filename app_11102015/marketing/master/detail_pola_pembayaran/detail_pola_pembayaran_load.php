<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M27');
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
	COUNT(a.KODE_BLOK) AS TOTAL
FROM 
	DETAIL_POLA_BAYAR a
	LEFT JOIN POLA_BAYAR b ON a.KODE_POLA_BAYAR = b.KODE_POLA_BAYAR
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
		<input type="button" id="tambah" value=" Tambah Detail">
		<input type="button" id="hapus" value=" Hapus ">
		<input type="button" id="upload" value=" Upload Detail Harga ">
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
	<th class="w5"><input type="checkbox" id="cb_all"></th>
	<th class="w10">KODE BLOK</th>
	<th class="w20">NAMA POLA BAYAR</th>
	<th class="w20">HARGA TANAH / M&sup2;</th>
	<th class="w20">HARGA BANGUNAN / M&sup2;</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		a.KODE_BLOK,
		ISNULL(b.NAMA_POLA_BAYAR,'-') AS NAMA_POLA_BAYAR,
		ISNULL(b.KODE_POLA_BAYAR,0) AS KODE_POLA_BAYAR,
		a.HARGA_TANAH,
		a.HARGA_BANGUNAN
	FROM 
		DETAIL_POLA_BAYAR a
		LEFT JOIN POLA_BAYAR b ON a.KODE_POLA_BAYAR = b.KODE_POLA_BAYAR
	$query_search
	ORDER BY a.KODE_POLA_BAYAR
	";
	
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	$id=1;
	while( ! $obj->EOF)
	{
		?>
		<tr class="onclick" id="<?php echo $obj->fields['KODE_BLOK'];?>" pola = "<?php echo $obj->fields['KODE_POLA_BAYAR'];?>">
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $obj->fields['KODE_BLOK'].':'.$obj->fields['KODE_POLA_BAYAR'];?>"></td>
			<td class="text-center"><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td><?php echo $obj->fields['NAMA_POLA_BAYAR']; ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['HARGA_TANAH']); ?></td>	
			<td class="text-right"><?php echo to_money($obj->fields['HARGA_BANGUNAN']); ?></td>	
				
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
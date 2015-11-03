<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
die_mod('K03');
$conn = conn($sess_db);
die_conn($conn);

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';
$per_page			= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num			= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1			= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$query_search = '';
if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= "WHERE TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105)";
}

if ($search1 != '')
{
	$query_search .= " WHERE $field1 LIKE '%$search1%' ";
}

/* Pagination */

$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	SPP
$query_search
";

$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
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
	<th class="w20">TANDA JADI</th>
	<th class="w10">TANGGAL</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT *
	FROM 
		SPP
	$query_search
	ORDER BY TANGGAL_SPP
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		$jumlah = $obj->fields['TANDA_JADI'];;
		$jumlah=explode(".",$jumlah) ;
		$jumlah = $jumlah[0];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"
		data-kode_blok="<?php echo $obj->fields['KODE_BLOK']; ?>"
		data-nama_pembayar="<?php echo $obj->fields['NAMA_PEMBELI']; ?>"
		data-tanda_jadi="<?php echo to_money($jumlah); ?>"
		data-alamat="<?php echo $obj->fields['ALAMAT_RUMAH']; ?>"
		data-telepon="<?php echo $obj->fields['TELP_LAIN']; ?>"
		data-keterangan="<?php echo $obj->fields['POLA_BAYAR']; ?>"
		data-agen="<?php echo $obj->fields['AGEN']; ?>"
		data-koordinator="<?php echo $obj->fields['KOORDINATOR']; ?>"
		> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo to_money($jumlah) ?></td>
			<td><?php echo f_tgl($obj->fields['TANGGAL_SPP']); ?></td>
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
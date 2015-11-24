<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C27');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1			= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$tanggal_awl		= explode("-",$periode_awal);
$tgl_awl			= $tanggal_awl[0];
$bln_awl			= $tanggal_awl[1];
$thn_awl			= $tanggal_awl[2];

$tanggal 			= f_tgl (date("Y-m-d"));
$tanggal_skg		= explode("-",$tanggal);
$tgl_skg			= $tanggal_skg[0];
$bln_skg			= $tanggal_skg[1];
$thn_skg			= $tanggal_skg[2];
$array_bulan 		= array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember'); 
	

$query_search = '';

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

<table id="pagging-1" class="t-control">
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

<table class="t-nowrap t-data wm100">
<tr>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">NAMA PEMBELI</th>
	<th rowspan="2">TOTAL TRANSAKSI</th>
	<th rowspan="2">TOTAL BELUM JATUH TEMPO</th>
	<th rowspan="2">TOTAL SUDAH JATUH TEMPO</th>
	<th rowspan="2">PEMBAYARAN</th>
	<th rowspan="2">PIUTANG</th>
	<th colspan="7">UMUR TAGIHAN SUDAH JATUH TEMPO</th>
	<th rowspan="2">CARA PEMBAYARAN</th>
	<th rowspan="2">CATATAN</th>
</tr>
<tr>
	<th colspan="1">0 to 30</th>
	<th colspan="1">30 to 60 Hari</th>
	<th colspan="1">60 to 90</th>
	<th colspan="1">90 to 120</th>
	<th colspan="1">120 to 360</th>
	<th colspan="1">360 to 720</th>
	<th colspan="1">OV 720</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT KODE_BLOK,NAMA_PEMBELI,HARGA_TOTAL,TANDA_JADI,(SELECT SUM(NILAI) FROM RENCANA WHERE TANGGAL > CONVERT(DATETIME,'$periode_awal',105)) AS BELUM_JATUH_TEMPO,
		(SELECT SUM(NILAI) FROM RENCANA WHERE TANGGAL <= CONVERT(DATETIME,'$periode_awal',105)) AS JATUH_TEMPO,
		(SELECT SUM(NILAI) FROM REALISASI WHERE TANGGAL <= CONVERT(DATETIME,'$periode_awal',105)) AS BAYARAN
	FROM SPP 
	$query_search
	ORDER BY KODE_BLOK ASC
	";
	
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	

	while( ! $obj->EOF)
	{
		$id 				= $obj->fields['KODE_BLOK'];		
		$total_harga 		= $obj->fields['HARGA_TOTAL'];
		$tanda_jadi 		= $obj->fields['TANDA_JADI'];
		$belum_jatuh_tempo	= $obj->fields['BELUM_JATUH_TEMPO'];
		$jatuh_tempo		= $obj->fields['JATUH_TEMPO'];
		$pembayaran			= $obj->fields['BAYARAN'];
		?>
			<tr class="onclick" id="<?php echo $id; ?>"> 
				
				<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
				<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
				<td class="text-center"><?php echo to_money($total_harga); ?></td>
				<td class="text-center"><?php echo to_money($belum_jatuh_tempo); ?></td>
				<td class="text-center"><?php echo to_money($jatuh_tempo+$tanda_jadi); ?></td>
				<td class="text-center"><?php echo to_money($pembayaran); ?></td>
				<td class="text-center"><?php echo to_money(($jatuh_tempo+$tanda_jadi)-$pembayaran); ?></td>
		
			</tr>
			
		
		<?php
		$i++;
		$obj->movenext();
	}	
	?>
	
<?php	
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
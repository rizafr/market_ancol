<?php
require_once('informasi_pembeli_proses.php');
require_once('../../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<script type="text/javascript">
jQuery(function($) {
	
	
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
	
});

//function rencana_pembayaran() {
	//var url = base_marketing_transaksi + 'spp/rencana_popup.php'; 
	//setPopup('Rencana Pembayaran', url, 600, 300); 
	//return false; 
//}
</script>

<table class="t-data w100">
<tr>
	<th class="w5">NO.</th>
	<th class="w10">NO.KWITANSI</th>
	<th class="w10">TANGGAL</th>
	<th class="w20">NAMA</th>
	<th class="50">KETERANGAN</th>
	<th class="15">NILAI</th>
</tr>

<?php
	$query = "
	SELECT a.NOMOR_KWITANSI, a.TANGGAL, a.NAMA_PEMBAYAR, a.KETERANGAN, a.NILAI FROM 
	KWITANSI_LAIN_LAIN a LEFT JOIN SPP s ON a.KODE_BLOK = s.KODE_BLOK

	WHERE a.KODE_BLOK = '$id'
	";
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_KWITANSI'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>">		
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $id; ?></td>
			<td><?php echo date("d-m-Y", strtotime($obj->fields['TANGGAL']));  ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBAYAR'];  ?></td>
			<td><?php echo $obj->fields['KETERANGAN'];  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
			
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
?>
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
</table>

<?php
close($conn);
exit;
?>
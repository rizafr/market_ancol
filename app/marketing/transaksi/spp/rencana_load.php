<?php
require_once('../../../../config/config.php');
//require_once('spp_proses.php');
$conn = conn($sess_db);
die_conn($conn);

$blok				= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
//$act				= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';

// $query = "SELECT POLA_BAYAR	FROM SPP WHERE KODE_BLOK = '$blok'";
// $obj = $conn->execute($query);

$query = "SELECT JUMLAH_KPR,STATUS_KOMPENSASI,HARGA_TOTAL, POLA_BAYAR,TANDA_JADI,TANGGAL_TANDA_JADI FROM SPP WHERE KODE_BLOK = '$blok'";
$obj = $conn->execute($query);
$tanda_jadi = $obj->fields['TANDA_JADI'];
$tgl_jadi 	= $obj->fields['TANGGAL_TANDA_JADI'];
$pola_bayar = $obj->fields['POLA_BAYAR'];
$jumlah_kpr = $obj->fields['JUMLAH_KPR'];
$harga_pemesanan = $obj->fields['HARGA_TOTAL'];
$status_kompensasi = $obj->fields['STATUS_KOMPENSASI'];
?>

<div class="title-page">POLA PEMBAYARAN <?php echo $pola_bayar ?> 
</div>
<div class="title-page">HARGA PEMESANAN : Rp. <?php echo to_money($harga_pemesanan) ?></div>
<table class="t-data w100">
<tr>
	<th class="w10">NO.</th>
	<th class="w30">TANGGAL</th>
	<th class="w30">JENIS PEMBAYARAN</th>
	<th class="w30">NILAI (RP)</th>
</tr>
<tr>
	<td class="text-center">1</td>
	<td><?php echo date("d-m-Y", strtotime($tgl_jadi)); ?></td>
	<td>TANDA JADI</td>
	<td class="text-right"><?php echo to_money($tanda_jadi);  ?></td>
</tr>
<?php
	$query = "
	SELECT *
	FROM 
		RENCANA a
	LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE a.KODE_BLOK = '$blok'
	ORDER BY a.TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 2;
	$total = 0;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr>
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo tgltgl(f_tgl($obj->fields['TANGGAL'])); ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);$total+=($obj->fields['NILAI']);  ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
	if($status_kompensasi == 1){
		?>
		<tr>
			<td class="text-left" colspan="3">KPA</td>
			<td class="text-right"><?php echo to_money($jumlah_kpr);  ?></td>
		</tr>
		<?php
	}
	
?>
<tr>
	<td class="text-left" colspan="3">TOTAL</td>
	<td class="text-right"><?php echo to_money($total+$tanda_jadi+$jumlah_kpr);?></td>
	<td></td>
</tr>
</table>

<script type="text/javascript">
jQuery(function($) {
	t_strip('.t-data');
});

</script>

<?php
close($conn);
exit;
?>
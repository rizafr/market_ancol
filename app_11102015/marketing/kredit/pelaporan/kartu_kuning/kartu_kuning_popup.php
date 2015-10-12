<?php
require_once('kartu_kuning_proses.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->
<link type="text/css" href="../../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../../plugin/css/zebra/default.css" rel="stylesheet">

<link type="text/css" href="../../../../../plugin/window/themes/default.css" rel="stylesheet">
<link type="text/css" href="../../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="../../../../../plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/keymaster.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="../../../../../config/js/main.js"></script>

<script type="text/javascript" src="../../../../../plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="../../../../../plugin/window/javascripts/window.js"></script>

<script type="text/javascript">
jQuery(function($) {
	
	/* -- BUTTON -- */
	$(document).on('click', '#cetak', function(e) {
		e.preventDefault();
		
		return false;
	});
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	loadData();
});
</script>

</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup w35 f-left" style="margin-right:15px">
<tr>
	<td width="100">Blok / Nomor</td><td width="10">:</td>
	<td colspan="2"><?php echo $kode_blok; ?></td>
</tr>
<tr>
	<td>Luas Tanah</td></td><td>:</td>
	<td colspan="2"><?php echo $luas_tanah.' m&sup2;'; ?></td>
</tr>
<tr>
	<td>Luas Bangunan</td></td><td>:</td>
	<td colspan="2"><?php echo $luas_bangunan.' m&sup2;'; ?></td>
</tr>
<tr>
	<td>Tipe Rumah</td></td><td>:</td>
	<td colspan="2"><?php echo $tipe_bangunan; ?></td>
</tr>
<tr>
	<td>Harga Tanah & Bangunan</td></td><td>: </td><td>Rp.</td>
	<td class="text-right"><?php echo $total_harga; ?></td>
</tr>
<tr>
	<td>P.P.N.</td></td><td>: </td><td>Rp.</td>
	<td class="text-right"><?php echo $total_ppn; ?></td>
</tr>
<tr>
	<td></td></td><td></td>
	<td colspan="2"><hr></td>
</tr>
<tr>
	<td>Total Harga</td></td><td>: </td><td>Rp.</td>
	<td class="text-right"><?php echo to_money($sisa_pembayaran); ?></td>
</tr>
</table>

<table class="t-popup w60 f-right">
<tr>
	<td width="100">No. Customer</td></td><td width="10">:</td>
	<td><?php echo $no_cust; ?></td>
</tr>
<tr>
	<td width="100">Nama</td></td><td>:</td>
	<td><?php echo $nama_pembeli; ?></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<td><?php echo $alamat; ?></td>
</tr>
<tr>
	<td>Telp. / HP.</td></td><td>:</td>
	<td><?php echo $tlp1.' / '.$tlp2.' / '.$tlp3; ?></td>
</tr>
<tr>
	<td>No. SPP / Tgl.</td></td><td>:</td>
	<td><?php echo $nomor_spp.' / '.$tanggal_spp; ?></td>
</tr>
<tr>
	<td colspan="3"><br>
		<input type="button" id="cetak" value=" Cetak ">
		<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
</table>

<div class="clear"><br></div>

<table class="t-data w25 f-left">
<tr>
	<th colspan=4>RENCANA PENERIMAAN</th>
</tr>
<tr>
	<th class="w5">NO.</th>
	<th class="w20">TANGGAL</th>
	<th class="w20">ANGSURAN</th>
	<th class="w55">KETERANGAN</th>
</tr>
<tr>
	<td class="text-center">1</td>
	<td><?php echo date("d-m-Y", strtotime($tgl_jadi)); ?></td>
	<td class="text-right"><?php echo to_money($tanda_jadi);  ?></td>
	<td>TANDA JADI</td>
</tr>

<?php
	$query = "
	SELECT *
	FROM 
		RENCANA a
	LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE KODE_BLOK = '$kode_blok'
	ORDER BY TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 2;

	while( ! $obj->EOF)
	{
		?>
		<tr>
			<td class="text-center"><?php echo $i;  ?></td>
			<td><?php echo date("d-m-Y", strtotime($obj->fields['TANGGAL'])); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
if ($jml_kpr > 0) {	
?>
<tr>
	<td class="text-center"><?php echo $i; ?></td>
	<td class="text-right"></td>
	<td class="text-right"><?php echo to_money($jml_kpr);  ?></td>
	<td>K.P.R</td>
</tr>
</table>
<?php } ?>

<table class="t-data w70">
<tr>
	<th colspan=8>REALISASI PENERIMAAN</th>
</tr>
<tr>
	<th>NO.</th>
	<th>TANGGAL</th>
	<th>ANGSURAN</th>
	<th>OFFICER COL.</th>
	<th>TGL. VER COL.</th>
	<th>OFFICER KEU.</th>
	<th>TGL. VER KEU.</th>
	<th>KETERANGAN</th>
</tr>

<?php
	$query = "
	SELECT a.*, b.FULL_NAME AS COL, c.FULL_NAME AS KEU
	FROM 
		KWITANSI a
	LEFT JOIN USER_APPLICATIONS b ON a.VER_COLLECTION_OFFICER = b.USER_ID	
	LEFT JOIN USER_APPLICATIONS c ON a.VER_KEUANGAN_OFFICER = c.USER_ID
	WHERE KODE_BLOK = '$kode_blok'
	ORDER BY TANGGAL
	";

	$obj = $conn->execute($query);
	$i = 1;
	$nilai = 0;
	while( ! $obj->EOF)
	{
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>
			<td><?php echo $obj->fields['COL']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['VER_COLLECTION_TANGGAL']))); ?></td>
			<td><?php echo $obj->fields['KEU']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['VER_KEUANGAN_TANGGAL']))); ?></td>
			<td class="text-center"><?php echo $obj->fields['CATATAN']; ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}	
	$query = "
	SELECT SUM(NILAI) AS TOTAL FROM KWITANSI WHERE KODE_BLOK = '$kode_blok'
	";
	$obj = $conn->execute($query);
?>
<tr>
	<th colspan=2 lass="text-center">TOTAL</th>
	<td class="text-right"><?php echo to_money($obj->fields['TOTAL']);  ?></td>
</tr>
<tr>
	<th colspan=2 lass="text-center">SISA</th>
	<td class="text-right"><?php echo to_money($sisa_pembayaran - $obj->fields['TOTAL']);  ?></td>
</tr>
</table>

<div class="clear"></div>

<div id="t-detail"></div>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
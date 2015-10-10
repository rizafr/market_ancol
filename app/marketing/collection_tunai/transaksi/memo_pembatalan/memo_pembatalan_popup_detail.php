<?php
require('memo_pembatalan_proses.php');
require_once('../../../../../config/terbilang.php');
$terbilang = new Terbilang;

$nilai	= (isset($_REQUEST['nilai'])) ? clean($_REQUEST['nilai']) : '';
$sisa	= (isset($_REQUEST['sisa'])) ? clean($_REQUEST['sisa']) : '';
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
function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

jQuery(function($) {
	// if ('<?php echo $act; ?>' == 'Tambah') {
		// $('#nama_pembayar, #nomor, #sejumlah, #keterangan, #diposting, #tanggal, #tgl_terima, #via').hide();	
	// }	
	// if ('<?php echo $act; ?>' == 'Ubah') {
		// $('#nama_pembayar, #nomor, #sejumlah, #keterangan, #diposting, #tanggal, #tgl_terima, #via').hide();	
	// }	
	$('#nama_pembayar').inputmask('varchar', { repeat: '60' });
	$('#jumlah, #diposting').inputmask('numeric', { repeat: '16' });	
	$('#catatan').inputmask('varchar', { repeat: '20' });
	
	$('#jumlah').on('keyup', function(e) {
		e.preventDefault();
		jumlah = jQuery('#jumlah').val();		
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
		sejumlah = terbilang(jumlah);
		jQuery('#sejumlah').val(sejumlah);
		jQuery('#diposting').val(jumlah);
		return false;
	});

	$('#jenis_pembayaran').on('change', function(e) {
		e.preventDefault();
		calculate();
		return false;
	});
	
	$('#jumlah').on('keyup', function(e) {
		e.preventDefault();
		calculate();
		return false;
	});
	
//==============================================
	$('#via').on('change', function(e) {
		e.preventDefault();
		cal2();
		return false;
	});
	
	$('#diposting, #nama_pembayar, #catatan, #keterangan').on('keyup', function(e) {
		e.preventDefault();
		cal2();
		return false;
	});
	
	$('#tanggal, #tgl_terima').on('focus', function(e) {
		e.preventDefault();
		cal2();
		return false;
	});
//==============================================	
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'collection_tunai/transaksi/memo_pembatalan/memo_pembatalan_proses.php',
			data	= $('#form').serialize();
			
		if (confirm("Apakah data telah terisi dengan benar ?") == false)
		{
			return false;
		}			
		
		$.post(url, data, function(data) {
			if (data.error == true)
			{
				alert(data.msg);
			}
				else if (data.act == 'Ubah')
				{
					alert(data.msg);
					parent.loadData();
				}
				else if (data.act == 'Tambah')
				{
					alert(data.msg);
					parent.loadData();
				}
		}, 'json');
		return false;
	});
	
	$('#rr').on('click', function(e) {
		e.preventDefault();
		var id = $(this).parent().attr('id');
		showPopup('Detail', id);
		return false;
	});
	
	
});

</script>
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup">
<tr>
	<td width="100">Kode Blok</td><td>:</td>
	<td><input type="text" name="kode_blok" id="kode_blok" size="15" value="<?php echo $kode_blok; ?>"></td>
</tr>

<tr>
	<td></td><td></td>
	<td colspan="2"><input type="hidden" name="nomor_memo" id="nomor_memo" rows="6" cols="100" value="<?php echo $nomor_memo; ?>"></td>
</tr>
<tr>
	<td></td><td></td>
	<td colspan="2"><input type="hidden" name="tanggal_spp" id="tanggal_spp" rows="6" cols="100" value="<?php echo $tanggal_spp; ?>"></td>
</tr>
</table>
<table class="t-popup">
<tr>
		
	<td> <input type="hidden" name="diposting" id="diposting" size="15" value="<?php echo to_money($diposting); ?>"></td>
	<td> <input type="hidden" name="tanggal" id="tanggal" size="15" value="<?php echo $tanggal; ?>"></td>
</tr>
</table>

<div class="clear"><br><br></div>

<table class="t-popup w90 f-left">
<tr>
	<td><input type="hidden" name="tgl_terima" id="tgl_terima" size="15" value="<?php echo $tgl_terima; ?>"></td>
	
</tr>

<tr>
	<td class="" colspan="3">
		<input type="submit" id="save" value=" <?php echo $act; ?> ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="subtotal" id="subtotal" value="<?php echo $subtotal; ?>">
<input type="hidden" name="ppn" id="ppn" value="<?php echo $ppn; ?>">

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
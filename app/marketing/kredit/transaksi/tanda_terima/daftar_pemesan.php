<?php
require_once('../../../../../config/config.php');
$conn = conn($sess_db);
ex_conn($conn);
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
<script type="text/javascript" src="../../../../../plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="../../../../../plugin/window/javascripts/window.js"></script>
<script type="text/javascript" src="../../../../../config/js/main.js"></script>
<script type="text/javascript">
jQuery(function($) {
	/* -- FILTER -- */
	$(document).on('keypress', '.apply', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { $('#apply').trigger('click'); return false; }
	});
	
	/* -- BUTTON -- */
	$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		// if (jQuery('#periode_awal').val() == '') {
			// alert('Masukkan periode laporan!');
			// jQuery('#periode_awal').focus();
			// return false;
		// }
		// else if (jQuery('#periode_akhir').val() == '') {
			// alert('Masukkan periode laporan!');
			// jQuery('#periode_akhir').focus();
			// return false;
		// }
		loadData();
		return false;
	});
	$(document).on('click', '#next_page', function(e) {
		e.preventDefault();
		var total_page = parseInt($('#total_page').val()), page_num = parseInt($('.page_num').val()) + 1;
		if (page_num <= total_page) { $('.page_num').val(page_num); $('#apply').trigger('click'); }
		return false;
	});

	$(document).on('click', '#prev_page', function(e) {
		e.preventDefault();
		var page_num = parseInt($('.page_num').val()) - 1;
		if (page_num > 0) { $('.page_num').val(page_num); $('#apply').trigger('click'); }
		return false;
	});
	
	$(document).on('click', '#close', function(e) {
		e.preventDefault();
		parent.window.focus();
		parent.window.popup.close();
	});
	
	$(document).on('click', 'tr.onclick', function(e) {
		e.preventDefault();
		var kode_blok = $(this).data('kode_blok'),
			nama_pembayar = $(this).data('nama_pembayar');
			tanda_jadi = $(this).data('tanda_jadi');
			keterangan = $(this).data('keterangan');
			alamat = $(this).data('alamat');
			telepon = $(this).data('telepon');
			agen = $(this).data('agen');
			koordinator = $(this).data('koordinator');
		
		parent.jQuery('#kode_blok').val(kode_blok);
		parent.jQuery('#nama_pembayar').val(nama_pembayar);
		parent.jQuery('#jumlah').val(tanda_jadi);
		parent.jQuery('#keterangan').val(keterangan);
		parent.jQuery('#alamat').val(alamat);
		parent.jQuery('#no_tlp').val(telepon);
		parent.jQuery('#penerima').val(agen);
		parent.jQuery('#koordinator').val(koordinator);
		
		parent.window.focus();
		parent.window.popup.close();
		
		return false;
	});
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'kredit/transaksi/tanda_terima/daftar_pemesan_load.php', data);	
	return false;
}
</script>
</head>
<body class="">

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>	
	<td>Periode</td><td>:</td>
	<td><input type="text" name="periode_awal" id="periode_awal" class="apply dd-mm-yyyy" size="15" value=""> s/d
	<input type="text" name="periode_akhir" id="periode_akhir" class="apply dd-mm-yyyy" size="15" value=""></td>
	<td width="150" class="text-right">
	
	</td>
</tr>

<tr>
	<td width="100">Pencarian</td><td width="10">:</td>
	<td>
		<select name="field1" id="field1" class="wauto">
			<option value="NAMA_PEMBELI"> NAMA </option>
			<option value="KODE_BLOK"> BLOK / NOMOR </option>
		</select>
		<input type="text" name="search1" id="search1" class="apply" value="">
	</td>
</tr>
<tr>
	<td>Jumlah Baris</td><td>:</td>
	<td>
		<input type="text" name="per_page" size="3" id="per_page" class=" apply text-center" value="20">
		<input type="button" name="apply" id="apply" value=" Apply ">
		<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
<tr>
	<td>Total Data</td><td>:</td>
	<td id="total-data"></td>
</tr>
</table>

<div id="t-detail"></div>
</form>
</body>
</html>
<?php close($conn); ?>
<?php
require_once('harga_bangunan_proses.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->
<link type="text/css" href="../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../plugin/css/zebra/default.css" rel="stylesheet">
<link type="text/css" href="../../../../plugin/window/themes/default.css" rel="stylesheet">
<link type="text/css" href="../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="../../../../plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../../../plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="../../../../plugin/js/keymaster.js"></script>
<script type="text/javascript" src="../../../../plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="../../../../plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="../../../../plugin/window/javascripts/window.js"></script>
<script type="text/javascript" src="../../../../config/js/main.js"></script>
<script type="text/javascript">
var this_base = base_marketing + 'master/harga_bangunan/';

jQuery(function($) {
	
	$('#kode_sk').inputmask('varchar', { repeat: '15' });
	$('#kode_blok').inputmask('varchar', { repeat: '15' });
	$('#cash_keras').inputmask('numeric', { repeat: '12' });
	$('#cb36x').inputmask('numeric', { repeat: '12' });
	$('#cb48x').inputmask('numeric', { repeat: '12' });
	$('#kpa24x').inputmask('numeric', { repeat: '12' });
	$('#kpa36x').inputmask('numeric', { repeat: '12' });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		if (confirm('Anda yakin akan menyimpan data ini !?') == false) {
			return false;
		}
		var url		= this_base + 'harga_bangunan_proses.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				if (result.act == 'Tambah') {
					$('#reset').click();
					parent.loadData();
				} else if (result.act == 'Ubah') {
					parent.loadData();
				}
			}
		}, 'json');
		
		return false;
	});

	$('#delete').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'harga_bangunan_proses_delete.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				parent.loadData();
				}
		}, 'json');
		
		return false;
	});
});
</script>
</head>
<body class="popup">
<form name="form" id="form" method="post">
<table>

<tr>
	<td>Kode SK</td><td>:</td>
	<td><input type="text" name="kode_sk" id="kode_sk" size="15" value="<?php echo $kode_sk; ?>"></td>
</tr>

<tr>
	<td>Kode Blok</td><td>:</td>
	<td><input type="text" name="kode_blok" id="kode_blok" size="15" value="<?php echo $kode_blok; ?>"></td>
</tr>

<tr>
	<td>Harga Cash Keras</td><td>:</td>
	<td><input type="text" class="text-right" name="cash_keras" id="cash_keras" value="<?php if(!isset($cash_keras)){$cash_keras = 0;}echo (($cash_keras)); ?>" size="20"></td>
</tr>

<tr>
	<td>Harga CB36x</td><td>:</td>
	<td><input type="text" name="cb36x" id="cb36x" value="<?php if(!isset($cb36x)){$cb36x = 0;}echo (($cb36x)); ?>" size="20"></td>
</tr>

<tr>
	<td>Harga CB48x</td><td>:</td>
	<td><input type="text" name="cb48x" id="cb48x" value="<?php if(!isset($cb48x)){$cb48x = 0;}echo (($cb48x)); ?>" size="20"></td>
</tr>

<tr>
	<td>Harga KPA24x</td><td>:</td>
	<td><input type="text" name="kpa24x" id="kpa24x" value="<?php if(!isset($kpa24x)){$kpa24x = 0;}echo (($kpa24x)); ?>" size="20"></td>
</tr>

<tr>
	<td>Harga KPA36x</td><td>:</td>
	<td><input type="text" name="kpa36x" id="kpa36x" value="<?php if(!isset($kpa36x)){$kpa36x = 0;}echo (($kpa36x)); ?>" size="20"></td>
</tr>

<tr>
	<td>Tanggal</td><td>:</td>
	<td><input type="text" name="tanggal" id="tanggal" value="<?php echo $tanggal; ?>" class="apply dd-mm-yyyy" size="12"></td>
</tr>

<tr>
	<td>Status</td><td>:</td>
	<td><input type="checkbox" name="status" id="status" value="1" <?php echo is_checked($status, '1'); ?>></td>
</tr>

<tr>
	<td colspan="3"><br>
		<input type="submit" id="simpan" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="tutup" value=" Tutup ">
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php 

close($conn); 
?>
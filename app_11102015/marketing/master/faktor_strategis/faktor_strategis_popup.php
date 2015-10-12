<?php
require_once('faktor_strategis_proses.php');
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
var this_base = base_marketing + 'master/faktor_strategis/';

jQuery(function($) {
	
	$('#kode_faktor').inputmask('integer', { repeat: 3 });
	$('#faktor_strategis').inputmask('varchar', { repeat: 30 });
	$('#nilai_tambah').inputmask('numericDesc', {iMax:5, dMax:16});
	$('#nilai_kurang').inputmask('numericDesc', {iMax:5, dMax:16});
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'faktor_strategis_proses.php',
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
		if (confirm('Anda yakin akan hapus data ini !?') == false) {
			return false;
		}
		var url		= this_base + 'faktor_strategis_proses_delete.php',
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
<input type="hidden" name="kode_faktor" id="kode_faktor" size="3" value="<?php echo $kode_faktor; ?>">
<tr>
	<td>Faktor Strategis</td><td>:</td>
	<td><input type="text" name="faktor_strategis" id="faktor_strategis" size="50" value="<?php echo $faktor_strategis; ?>"></td>
</tr>
<tr>
	<td>Tambah</td><td>:</td>
	<td><input type="text" name="nilai_tambah" id="nilai_tambah" size="30" value="<?php echo to_decimal($nilai_tambah); ?>"></td>
</tr>
<tr>
	<td>Kurang</td><td>:</td>
	<td><input type="text" name="nilai_kurang" id="nilai_kurang" size="30" value="<?php echo to_decimal($nilai_kurang); ?>"></td>
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
<?php close($conn); ?>
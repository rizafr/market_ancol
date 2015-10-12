<?php
require_once('jenis_pembayaran_proses.php');
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
var this_base = base_marketing + 'master/jenis_pembayaran/';

jQuery(function($) {
	
	$('#kode_bayar').inputmask('integer', { repeat: 3 });
	$('#jenis_bayar').inputmask('varchar', { repeat: 30 });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'jenis_pembayaran_proses.php',
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
		var url		= this_base + 'jenis_pembayaran_proses_delete.php',
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
<input type="hidden" name="kode_bayar" id="kode_bayar" size="3" value="<?php echo $kode_bayar; ?>">
<tr>
	<td>Pembayaran</td><td>:</td>
	<td><input type="text" name="jenis_bayar" id="jenis_bayar" size="30" value="<?php echo $jenis_bayar; ?>"></td>
</tr>
<tr>
	<td>Kelompok</td><td>:</td>
	<td>
	<select name="kelompok" id="kelompok" value="<?php echo $kelompok; ?>">
			<option value=""> -- Pilih -- </option>
			<option value="1" <?php echo is_selected('1', $kelompok);?>> 1 </option>"
			<option value="2" <?php echo is_selected('2', $kelompok);?>> 2 </option>"
	</select>
	</td>
	<!-- <td><input type="text" name="kelompok" id="kelompok" size="30" value="<?php echo $kelompok; ?>"></td>-->
</tr>
<tr>
	<td>Inisial</td><td>:</td>
	<td><input type="text" name="inisial" id="inisial" size="30" value="<?php echo $inisial; ?>"></td>
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
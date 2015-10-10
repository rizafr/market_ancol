<?php
require_once('lembaga_keuangan_proses.php');
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
var this_base = base_marketing + 'master/lembaga_keuangan/';

jQuery(function($) {
	
	$('#kode_bank').inputmask('integer', { repeat: 3 });
	$('#nama_bank').inputmask('varchar', { repeat: 30 });
	$('#alamat_bank').inputmask('varchar', { repeat: 100 });
	$('#npwp').inputmask('varchar', { repeat: 15 });
	$('#kode_va_unit').inputmask('varchar', { repeat: 15 });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'lembaga_keuangan_proses.php',
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
		var url		= this_base + 'lembaga_keuangan_proses_delete.php',
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
<input type="hidden" name="kode_bank" id="kode_bank" size="3" value="<?php echo $kode_bank; ?>">
<tr>
	<td>Nama Bank</td><td>:</td>
	<td><input type="text" name="nama_bank" id="nama_bank" size="50" value="<?php echo $nama_bank; ?>"></td>
</tr>
<tr>
	<td>Alamat</td><td>:</td>
	<td><input type="text" name="alamat_bank" id="nama_bank" size="50" value="<?php echo $alamat_bank; ?>"></td>
</tr>
<tr>
	<td>NPWP</td><td>:</td>
	<td><input type="text" name="npwp" id="npwp" size="25" value="<?php echo $npwp; ?>"></td>
</tr>
<tr>
	<td>Kode VA Unit</td><td>:</td>
	<td><input type="text" name="kode_va_unit" id="kode_va_unit" size="25" value="<?php echo $kode_va_unit; ?>"></td>
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
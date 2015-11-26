<?php
require_once('agen_proses.php');
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
var this_base = base_marketing + 'master/agen/';

jQuery(function($) {
	
	$('#nomor_id').inputmask('varchar', { repeat: 20 });
	$('#nama').inputmask('varchar', { repeat: 40 });
	$('#alamat').inputmask('varchar', { repeat: 40 });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		if (confirm('Anda yakin akan menyimpan data ini !?') == false) {
			return false;
		}
		var url		= this_base + 'agen_proses.php',
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
		var url		= this_base + 'agen_proses_delete.php',
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
	<!--<td width="">Nomor ID</td><td>:</td>-->
	<td><input type="hidden" name="nomor_id" id="nomor_id" size="15" value="<?php echo $nomor_id; ?>"></td>
	<td><input type="hidden" name="nomor_id" id="nomor_id" size="15" value="<?php echo $nomor_id; ?>"></td>
</tr>
<tr>
	<td>Nama</td><td>:</td>
	<td><input type="text" name="nama" id="nama" size="40" value="<?php echo $nama; ?>"></td>
</tr>
<tr>
	<td>No Telp</td><td>:</td>
	<td><input type="text" name="no_telp" id="no_telp" size="40" value="<?php echo $no_telp; ?>"></td>
</tr>
<tr>
	<td>Email</td><td>:</td>
	<td><input type="text" name="email" id="email" size="40" value="<?php echo $email; ?>"></td>
</tr>
<tr>
	<td>NPWP</td><td>:</td>
	<td><input type="text" name="npwp" id="npwp" size="40" value="<?php echo $npwp; ?>"></td>
</tr>
<tr>
	<td>No KTP</td><td>:</td>
	<td><input type="text" name="nomor_kartu_identitas" id="nomor_kartu_identitas" size="40" value="<?php echo $nomor_kartu_identitas; ?>"></td>
</tr>
<tr>
	<td>Alamat</td><td>:</td>
	<td><textarea name="alamat" id="alamat" ><?php echo $alamat; ?></textarea></td>
</tr>
<!--<tr>
	<td>Jabatan</td><td>:</td>
	<td><input type="hidden" name="jabatan" id="jabatan" value="5"></td>
</tr>
-->
<tr>
	<td>Jabatan</td><td>:</td>
	<td>
		<select name="jabatan" id="jabatan">
			<?php for($i=0;$i<5;$i++) { ?>
  				<option value= '<?php echo $i+1;?>' <?php if($i+1=='5'){echo 'selected';}?>> <?php echo $i+1; ?>. <?php echo $Jabatan[$i]; ?> </option>
  			<?php } ?>		
		</select>
	</td>
</tr>

<tr>
	<td colspan="3" class="td-action"><br>
		<input type="submit" id="simpan" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="tutup" value=" Tutup ">
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
<input type="hidden" name="nm" id="nm" value="<?php echo $nm; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
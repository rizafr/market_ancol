<?php
require_once('kelurahan_proses.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->
<link type="text/css" href="../../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../../plugin/css/zebra/default.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="../../../../../plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/keymaster.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="../../../../../config/js/main.js"></script>
<script type="text/javascript">
$(function() {
	$('#kode_kelurahan').inputmask('numeric', { repeat: '3' });
	$('#nama_kelurahan').inputmask('varchar', { repeat: '40' });
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'ppjb/master/kelurahan/kelurahan_proses.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(data) {
			if (data.error == true)
			{
				alert(data.msg);
			}
			else
			{
				if (data.act == 'Tambah')
				{
					alert(data.msg);
					$('#reset').click();
					parent.loadData();
				}
				else if (data.act == 'Ubah')
				{
					alert(data.msg);
					parent.loadData();
				}
			}
		}, 'json');
		return false;
	});
});
</script>
</head>
<body class="popup">

<form name="form" id="form" method="post">
<table class="t-popup">
<input type="hidden" name="kode_kelurahan" id="kode_kelurahan" size="3" value="<?php echo $kode_kelurahan; ?>">
<tr>
	<td>Nama</td><td>:</td>
	<td><input type="text" name="nama_kelurahan" id="nama_kelurahan" size="40" value="<?php echo $nama_kelurahan; ?>"></td>
</tr>
<tr>
	<td colspan="3" class="td-action text-center">
		<input type="submit" id="save" value=" <?php echo $act; ?> ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
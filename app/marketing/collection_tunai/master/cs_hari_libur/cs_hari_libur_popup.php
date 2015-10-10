<?php
require_once('cs_hari_libur_proses.php');
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
	$('#keterangan').inputmask('varchar', { repeat: '30' });
	$('#tanggal_awal').inputmask('date');
	$('#tanggal_akhir').inputmask('date');
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'collection_tunai/master/cs_hari_libur/cs_hari_libur_proses.php',
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
					parent.loadData();
					$('#reset').click();
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
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup">
<tr>	
	<td>Tanggal</td><td>:</td>
	<td><input type="text" name="tanggal_awal" id="tanggal_awal" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal_awal; ?>"> s/d
	<input type="text" name="tanggal_akhir" id="tanggal_akhir" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal_akhir; ?>"></td>
</tr>
<tr>
	<td>Keterangan</td><td>:</td>
	<td><input type="text" name="keterangan" id="keterangan" size="43" value="<?php echo $keterangan; ?>"></td>
</tr>
<tr>
	<td colspan="3" class="td-action"> <br>
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
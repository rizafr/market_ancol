<?php
require_once('virtual_account_proses.php');
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

	$('#no_va').inputmask('varchar', { repeat: '20' });
	$('#tanggal').inputmask('date');
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'collection_tunai/transaksi/virtual_account/virtual_account_proses.php',
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
	<td>Nomor Virtual Account</td><td>:</td>
	<td><input type="text" name="no_va" id="no_va" size="20" value="<?php echo $no_va; ?>"></td>
</tr>
<tr>
	<td>Tanggal</td><td>:</td>
	<td><input type="text" name="tanggal" id="tanggal" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal; ?>"></td>
</tr>
<tr>
	<td>Nilai</td><td>:</td>
	<td><input type="text" name="nilai" id="nilai" size="20" value="<?php echo to_money($nilai); ?>"></td>
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




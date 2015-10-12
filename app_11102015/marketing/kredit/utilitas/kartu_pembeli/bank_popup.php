<?php
require_once('kartu_pembeli_proses.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->
<link type="text/css" href="../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../plugin/css/zebra/default.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="../../../../plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../../../plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="../../../../plugin/js/keymaster.js"></script>
<script type="text/javascript" src="../../../../plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="../../../../config/js/main.js"></script>
<script type="text/javascript">
$(function() {
	$('#rekening').inputmask('varchar', { repeat: '30' });
	$('#nama').inputmask('varchar', { repeat: '50' });
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_kredit_utilitas + 'kartu_pembeli/kartu_pembeli_proses.php',
			data	= $('#form').serialize();
			
		if (confirm("Apakah anda yakin mengubah data parameter ?") == false)
		{
			return false;
		}	
		
		$.post(url, data, function(data) {
			if (data.error == true)
			{
				alert(data.msg);
			}
				else if (data.act == 'Bank')
				{
					alert(data.msg);
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
<tr>
	<td width="100">Nama Bank</td><td>:</td>
	<td><input type="text" name="bank" id="bank" size="40" value="<?php echo $bank; ?>"></td>
</tr>
<tr>
	<td>Nomor Rekening</td><td>:</td>
	<td><input type="text" name="rekening" id="rekening" size="20" value="<?php echo $rekening; ?>"></td>
</tr>
<tr>
	<td colspan="3" class="td-action"><br>
		<input type="submit" id="save" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
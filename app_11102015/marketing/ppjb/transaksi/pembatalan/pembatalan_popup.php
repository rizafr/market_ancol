<?php
require_once('pembatalan_proses.php');
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
	$('#blok_nomor').inputmask('varchar', { repeat: '15' });
	$('#nama_pembeli').inputmask('varchar', { repeat: '40' });
	$('#nomor_ppjb').inputmask('varchar', { repeat: '20' });
	$('#alasan').inputmask('varchar', { repeat: '40' });
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'ppjb/transaksi/pembatalan/pembatalan_proses.php',
			data	= $('#form').serialize();
			
		if (confirm("Apakah anda yakin membatalkan PPJB ini ?") == false)
		{
			return false;
		}	
			
		$.post(url, data, function(data) {
			
			if (data.error == true)
			{
				alert(data.msg);
			}
				else if (data.act == 'Pembatalan')
				{
					alert(data.msg);
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
<table class="t-popup">
<tr>
	<td width="100">Blok / Nomor</td>
	<td><input readonly="readonly" type="text" name="blok_nomor" id="blok_nomor" size="15" value="<?php echo $blok_nomor; ?>"></td>
</tr>
<tr>
	<td>Nama Pembeli</td>
	<td><input readonly="readonly" type="text" name="nama_pembeli" id="nama_pembeli" size="40" value="<?php echo $nama_pembeli; ?>"></td>
</tr>
<tr>
	<td>Nomor PPJB</td>
	<td><input readonly="readonly"  type="text" name="nomor_ppjb" id="nomor_ppjb" size="20" value="<?php echo $nomor_ppjb; ?>"></td>
</tr>
<tr>
	<td>Alasan</td>
	<td><input type="text" name="alasan" id="alasan" size="40" value=""></td>
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
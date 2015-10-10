<?php
require_once('penomoran_fp_proses.php');
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
	$('#pejabat, #jabatan').inputmask('varchar', { repeat: '30' });
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'kredit/utilitas/penomoran_fp/penomoran_fp_proses.php',
			data	= $('#form').serialize();
		
		if (confirm("Nama pejabat akan disimpan ?") == false)
		{
			return false;
		}	
		
		$.post(url, data, function(data) {
			
			if (data.error == true)
			{
				alert(data.msg);
			}
				else if (data.act == 'Edit')
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
	<td width="100">No Kuitansi</td><td>:</td>
	<td><input readonly="readonly" type="text" name="no_kuitansi" id="no_kuitansi" size="15" value="<?php echo $no_kuitansi; ?>"></td>
</tr>
<tr>
	<td>Blok / Nomor</td><td>:</td>
	<td><input readonly="readonly" type="text" name="blok_nomor" id="blok_nomor" size="7" value="<?php echo $blok_nomor; ?>"></td>
</tr>
<tr>
	<td>Nama Pembeli</td><td>:</td>
	<td><input readonly="readonly" type="text" name="nama_pembeli" id="nama_pembeli" size="40" value="<?php echo $nama_pembeli; ?>"></td>
</tr>
<tr>
	<td>Nama</td><td>:</td>
	<td><input type="text" name="pejabat" id="pejabat" size="30" value="<?php echo $pejabat; ?>"></td>
</tr>
<tr>
	<td>Jabatan</td><td>:</td>
	<td><input type="text" name="jabatan" id="jabatan" size="30" value="<?php echo $jabatan; ?>"></td>
</tr>
<tr>
	<td colspan="3" class="td-action"><br>
		<input type="submit" id="save" value=" Simpan ">
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
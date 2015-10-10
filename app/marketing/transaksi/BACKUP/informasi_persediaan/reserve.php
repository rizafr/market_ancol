<?php
require_once('informasi_persediaan_proses.php');
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
jQuery(function($) {
	$('#nama_calon_pembeli, #telepon, #agen, #koordinator').inputmask('varchar', { repeat: '30' });
	$('#alamat').inputmask('varchar', { repeat: '100' });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.showPopup2('Detail', '<?php echo $id; ?>');
		return false;
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing_transaksi + 'informasi_persediaan/informasi_persediaan_proses.php',
			data	= $('#form').serialize();
			
		if (confirm("Apakah data telah terisi dengan benar ?") == false)
		{
			return false;
		}			
		
		$.post(url, data, function(data) {
			if (data.error == true)
			{
				alert(data.msg);				
			}
				else if (data.act == 'Reserve')
				{
					alert(data.msg);
					parent.loadData();
				}
		}, 'json');
		
		return false;
	});
	
	loadData();
});
</script>
</head>
<body class="popup2">
<form name="form" id="form" method="post">
<table class="t-popup wauto f-left">
<tr>
<td width="140">Kode Blok</td><td width="10">:</td>
	<td><input type="text" name="kode_blok" id="kode_blok" size="10" readonly="readonly" value="<?php echo $id; ?>"></td>
</tr>
<tr>
	<td>Nama Calon Pembeli</td></td><td>:</td>
	<td><input type="text" name="nama_calon_pembeli" id="nama_calon_pembeli" size="40" value=""></td>
</tr>
<tr>
	<td>Tanggal Reserve</td><td>:</td>
	<td><input type="text" name="tanggal_reserve" id="tanggal_reserve" value="<?php echo $tanggal_reserve; ?>" class="apply dd-mm-yyyy" size="10"></td>
</tr>
<tr>
	<td>Berlaku sampai dengan</td><td>:</td>
	<td><input type="text" name="berlaku_sampai" id="berlaku_sampai" value="<?php echo $berlaku_sampai; ?>" class="apply dd-mm-yyyy" size="10"></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<td><input type="text" name="alamat" id="alamat" size="40" value=""></td>
</tr>
<tr>
	<td>Telepon</td></td><td>:</td>
	<td><input type="text" name="telepon" id="telepon" size="20" value=""></td>
</tr>
<tr>
	<td>Agen</td></td><td>:</td>
	<td><input type="text" name="agen" id="agen" size="30" value=""></td>
</tr>
<tr>
	<td>Sales Koordinator</td></td><td>:</td>
	<td><input type="text" name="koordinator" id="koordinator" size="30" value=""></td>
</tr>
</table>
<table>
<tr>
	<td class="td-action" colspan="3"><br>
		<input type="button" id="save" value=" <?php echo $act; ?> ">
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
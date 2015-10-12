<?php
require_once('tipe_bangunan_proses.php');
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
	$('#kode_tipe').inputmask('numeric', { repeat: '3' });
	$('#tipe_bangunan').inputmask('varchar', { repeat: '30' });
	$('#daya_listrik').inputmask('numeric', { repeat: '4' });
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'ppjb/master/tipe_bangunan/tipe_bangunan_proses.php',
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
<tr>
	<td width="100">Kode</td><td>:</td>
	<td><input readonly="readonly" type="text" name="kode_tipe" id="kode_tipe" size="3" value="<?php echo $kode_tipe; ?>"></td>
</tr>
<tr>
	<td>Tipe Bangunan</td><td>:</td>
	<td><input readonly="readonly" type="text" name="tipe_bangunan" id="tipe_bangunan" size="30" value="<?php echo $tipe_bangunan; ?>"></td>
</tr>
<tr>
	<td>Daya Listrik</td><td>:</td>
	<td><input type="text" name="daya_listrik" id="daya_listrik" size="4" value="<?php echo $daya_listrik; ?>"> Watt</td>
</tr>
<tr><td>Masa Bangun</td><td>:</td><td>
<select name="masa_bangun" id="masa_bangun">
	<option value="0" <?php echo is_selected('0', $masa_bangun); ?>> 0 </option>
	<option value="1" <?php echo is_selected('1', $masa_bangun); ?>> 6 </option>
	<option value="2" <?php echo is_selected('2', $masa_bangun); ?>> 9 </option>
	<option value="3" <?php echo is_selected('3', $masa_bangun); ?>> 12 </option>
	<option value="4" <?php echo is_selected('4', $masa_bangun); ?>> 15 </option>
	<option value="5" <?php echo is_selected('5', $masa_bangun); ?>> 18 </option>
	<option value="6" <?php echo is_selected('6', $masa_bangun); ?>> 21 </option>
	<option value="7" <?php echo is_selected('7', $masa_bangun); ?>> 24 </option>
</select> Bulan
</td></tr>
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
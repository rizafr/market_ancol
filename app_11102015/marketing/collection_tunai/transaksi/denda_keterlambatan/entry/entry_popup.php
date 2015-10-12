<?php
require_once('entry_proses.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->

<link type="text/css" href="../../../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../../../plugin/css/zebra/default.css" rel="stylesheet">
<link type="text/css" href="../../../../../../plugin/window/themes/default.css" rel="stylesheet">
<link type="text/css" href="../../../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="../../../../../../plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../../../../../plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="../../../../../../plugin/js/keymaster.js"></script>
<script type="text/javascript" src="../../../../../../plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="../../../../../../plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="../../../../../../plugin/window/javascripts/window.js"></script>
<script type="text/javascript" src="../../../../../../config/js/main.js"></script>
<script type="text/javascript">

var this_base = base_marketing + 'collection_tunai/transaksi/denda_keterlambatan/entry/';


jQuery(function($) {
	
	$('#kode_lokasi').inputmask('integer', { repeat: 3 });
	$('#lokasi').inputmask('varchar', { repeat: 30 });

	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#disetujui').on('keyup', function(e) {
		e.preventDefault();
		disetujui 	= jQuery('#disetujui').val();		
		disetujui	= disetujui.replace(/[^0-9.]/g, '');
		return false;
	});

	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'entry_proses.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				if (result.act == 'Tambah') {
					$('#reset').click();
				} else if (result.act == 'Ubah') {
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
	<td width="100" height="30">Kode Blok</td><td>:</td>
	<td><input type="text" name="kode_blok" id="kode_blok" readonly="readonly" size="15" value="<?php echo $kode_blok; ?>"></td>
</tr>
<tr>
	<td width="100" height="30">Tanggal Tempo</td><td>:</td>
	<td><input type="text" name="tgl_tempo" id="tgl_tempo" readonly="readonly" size="15" value="<?php echo date("d-m-Y", strtotime($tgl_tempo)); ?>"></td>
</tr>
<tr>
	<td width="100" height="30">Tanggal Transaksi</td><td>:</td>
	<td><input type="text" name="tgl_transaksi" id="tgl_transaksi" readonly="readonly" size="15" value="<?php echo date("d-m-Y", strtotime($tgl_trans)); ?>"></td>
</tr>
<tr>
	<td width="100" height="30">Keterlambatan</td><td>:</td>
	<td><input type="text" name="hari_tunggakan" id="hari_tunggakan" readonly="readonly" align="right" size="25" value="<?php echo $hari_tunggakan. " HARI"; ?>"></td>
</tr>
<tr>
	<td width="100" height="30">Nilai</td><td>:</td>
	<td><input type="text" name="nilai" id="nilai" readonly="readonly" size="25" value="<?php echo to_money($nilai); ?>"></td>
</tr>
<tr>
	<td width="100" height="30">Awal</td><td>:</td>
	<td><input type="text" name="denda" id="denda" readonly="readonly" size="25" value="<?php echo to_money($denda); ?>"></td>
</tr>
<tr>
	<td width="100" height="30">Disetujui</td><td>:</td>
	<td><input type="text" name="disetujui" id="disetujui" size="25" value="<?php echo to_money($disetujui); ?>"></td>
</tr>
<tr>
	<td colspan="3"><br>
		<input type="submit" id="simpan" value=" Simpan ">
		<input type="button" id="tutup" value=" Tutup ">
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
<input type="hidden" name="id_" id="id_" value="<?php echo $id_; ?>">
<input type="hidden" name="tgl_" id="tgl_" value="<?php echo $tgl_; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
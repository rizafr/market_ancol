<?php
require_once('kuitansi_lain_proses.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->
<link type="text/css" href="../../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../../plugin/css/zebra/default.css" rel="stylesheet">

<link type="text/css" href="../../../../../plugin/window/themes/default.css" rel="stylesheet">
<link type="text/css" href="../../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="../../../../../plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/keymaster.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="../../../../../config/js/main.js"></script>

<script type="text/javascript" src="../../../../../plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="../../../../../plugin/window/javascripts/window.js"></script>

<script type="text/javascript">
jQuery(function($) {
	
	/* -- BUTTON -- */
	$(document).on('click', '#tambah', function(e) {
		e.preventDefault();
		showPopup('Tambah', '<?php echo $id; ?>');
		return false;
	});
	
	$(document).on('click', 'tr.onclick td:not(.notclick)', function(e) {
		e.preventDefault();
		var id = $(this).parent().attr('id');
		showPopup('Ubah', id);
		return false;
	});
	
	$(document).on('click', '#hapus', function(e) {
		e.preventDefault();
		if (confirm('Apa anda yakin akan menghapus data ini?'))
		{
			deleteData();
		}
	});
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'kredit/transaksi/kuitansi_lain/kuitansi_lain_load_detail.php', data);	
	return false;
}

function showPopup(act, id)
{
	var url =	base_marketing + 'kredit/transaksi/kuitansi_lain/kuitansi_lain_popup_detail.php' +	'?act=' + act +	'&id=' + id,
		title	= (act == 'Simpan') ? 'Tambah' : act;	
	setPopup(title + ' Kuitansi Lain-lain', url, 800, 400);	
	return false;
}

function deleteData()
{
	var checked = jQuery(".cb_data:checked").length;
	if (checked < 1)
	{
		alert('Pilih data yang akan dihapus.');
		return false;
	}
	
	var url		= base_marketing + 'kredit/transaksi/kuitansi_lain/kuitansi_lain_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'Hapus' });
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);		
	}, 'json');
	
	loadData();
	return false;
}
</script>

</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup w50 f-left" style="margin-right:35px">
<tr>
	<td width="100">Blok / Nomor</td><td width="10">:</td>
	<td><?php echo $kode_blok; ?></td>
</tr>
<tr>
	<td>Nomor SPP</td></td><td>:</td>
	<td><?php echo $no_spp; ?></td>
</tr>
<tr>
	<td>Nama Pemilik</td></td><td>:</td>
	<td><?php echo $nama_pembeli; ?><input type="hidden" name="nama_pembeli" id="nama_pembeli" value="<?php echo $nama_pembeli; ?>"></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<td><?php echo $alamat; ?></td>
</tr>
<tr>
	<td>Telepon</td></td><td>:</td>
	<td><?php echo $tlp1; ?></td>
</tr>
</table>
<table class="t-popup wauto" style="margin-right:35px">
<tr>
	<td width="100">Tanggal SPP</td></td><td width="10">:</td>
	<td><?php echo $tanggal_spp; ?></td>
</tr>
<tr>
	<td>No. Identitas</td></td><td>:</td>
	<td><?php echo $no_identitas; ?></td>
</tr>
<tr>
	<td>NPWP</td></td><td>:</td>
	<td><?php echo $npwp; ?></td>
</tr>
</table>

<div class="clear"><br></div>

<table id="pagging-1" class="t-control w100">
<tr>
	<td>
		<!--
		<input type="button" id="tambah" value=" Tambah ">
		<input type="button" id="hapus" value=" Hapus ">
		-->
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<div id="t-detail"></div>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
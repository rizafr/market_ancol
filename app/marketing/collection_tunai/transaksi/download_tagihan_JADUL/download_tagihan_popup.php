<?php
require_once('download_tagihan_proses.php');
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
		var bulan = jQuery('#bulan').val();
		showPopup('Tambah', '<?php echo $id; ?>', '', bulan);
		return false;
	});
	
	
	$(document).on('click', 'tr.onclick td:not(.notclick)', function(e) {
		e.preventDefault();
		var bulan = jQuery('#bulan').val();
		var id = $(this).parent().attr('id');
		showPopup('Ubah','<?php echo $id; ?>',id,bulan);
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
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/transaksi/download_tagihan/download_tagihan_load_detail.php', data);	
	return false;
}

function showPopup(act, kode_blok, id, bulan)
{
	var url =	base_marketing + 'collection_tunai/transaksi/download_tagihan/download_tagihan_popup_detail.php' +	'?act=' + act +	'&kode_blok=' + kode_blok + '&id=' + id + '&bulan=' + bulan 
		title	= (act == 'Simpan') ? 'Tambah' : act;	
	setPopup(title + ' Tagihan Kuitansi Lain-lain', url, 500, 200);	
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
	
	var kode = '<?php echo $id; ?>';
	
	var url		= base_marketing + 'collection_tunai/transaksi/download_tagihan/download_tagihan_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'Hapus' },{ name: 'kode', value: kode });
	
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
<div class="clear"><br></div>

<table id="pagging-1" class="t-popup w100">
<tr>
	<td>
		<input type="button" id="tambah" value=" Tambah ">
		<input type="button" id="hapus" value=" Hapus ">
		<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
</table>
<div class="clear"><br></div>
<div class="clear"><br></div>
<div id="t-detail"></div>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
<input type="hidden" name="bulan" id="bulan" value="<?php echo $bulan; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
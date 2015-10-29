<?php
//require_once('rencana_proses.php');
require_once('spp_proses.php');
require_once('../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<!DOCTYPE html>
<html>
<head>

<script type="text/javascript">
jQuery(function($) {
	
	$(document).on('click', '#tambah', function(e) {
		e.preventDefault();
		showPopup('Rencana', '<?php echo $id; ?>');
		return false;
	});

	$(document).on('click', '#insert_row', function(e) {
		e.preventDefault();
		showPopup2('Ubah', '<?php echo $id; ?>');
		return false;
	});

	$(document).on('click', '#insert_row', function(e) {
		e.preventDefault();
		
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
	jQuery('#t-detail').load(base_marketing_transaksi + 'spp/rencana_load.php', data);	
	return false;
}

function showPopup(act,id)
{
	var url =	base_marketing_transaksi + 'spp/rencana_popup.php' + '?act=' + act + '&id=' + id;	
	setPopup('Rencana Pembayaran', url, 511, 350);	
	return false;
}

function showPopup2(act,id)
{
	var url =	base_marketing_transaksi + 'spp/insert_row_popup.php' + '?act=' + act + '&id=' + id;	
	setPopup('Rencana Pembayaran', url, 816, 450);	
	return false;
}

function delete_rencana(id, tgl)
{
	if (confirm("Apakah anda yakin data rencana tanggal "+tgl+" akan dihapus ?") == false)
		{
			return false;
		}
	jQuery('#t-detail').load("rencana_hapus.php?kode_blok="+id+"&tgl="+tgl);
	loadData();
}
</script>

</head>
<body>

<form name="form" id="form" method="post">
<table id="pagging-1" class="t-popup w100">
<tr>
	<td>
		<input type="button" id="tambah" value=" Rencana ">
		<input type="button" id="insert_row" value=" Insert Per Row ">
		<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
</table>
<div class="clear"><br></div>

<div id="t-detail"></div>

<input type="hidden" name="kode_blok" id="kode_blok" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>

<?php close($conn); ?>

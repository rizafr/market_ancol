<?php
require_once('spp_proses.php');
require_once('../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<script type="text/javascript">
jQuery(function($) {
	t_strip('.t-data');
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
	
	$(document).on('click', '#Hapus', function(e) {
		e.preventDefault();
		if (confirm('Apa data SPP ini akan dihapus?')) {
			hapusData("Hapus");
		}
		return false;
	});
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'spp/rencana_load.php', data);
	return false;
}

function rencana_pembayaran(act) {
	var id = '<?php echo $id; ?>';
	var url = base_marketing_transaksi + 'spp/rencana_popup.php' + '?act=' + act + '&id=' + id;
	setPopup( 'RENCANA PEMBAYARAN', url, 700, 300);
	return false; 
}

function hapusData(act)
{	
	// var id = document.getElementById("kode_blok").value;
	alert(id);
	var url		= base_marketing_transaksi + 'spp/rencana_proses.php?act=Hapus',
	data	= jQuery('#form').serializeArray();
	return false;
}
</script>

<form name="form" id="form" method="post">
<button onclick="return rencana_pembayaran('Ubah')"> Rencana </button>
<input type="button" id="Hapus" value=" Hapus ">

<div id="t-detail"></div>

<input type="text" name="id" id="id" value="<?php echo $id; ?>">
<input type="text" name="act" id="act" value="<?php echo $act; ?>">
</form>

<?php close($conn); ?>
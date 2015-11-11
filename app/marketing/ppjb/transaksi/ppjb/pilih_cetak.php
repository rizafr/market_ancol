<?php
require_once('../../../../../config/config.php');
require_once('ppjb_proses.php');
die_login();
// die_app('P');
die_mod('P06');
$conn = conn($sess_db);
die_conn($conn);

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$kode_jenis_ppjb = (isset($_REQUEST['kode_jenis_ppjb'])) ? clean($_REQUEST['kode_jenis_ppjb']) : '';
$jenis_ppjb = (isset($_REQUEST['jenis_ppjb'])) ? clean($_REQUEST['jenis_ppjb']) : '';
$telah_bayar = (isset($_REQUEST['telah_bayar'])) ? clean($_REQUEST['telah_bayar']) : '';
$persentase_paijb = (isset($_REQUEST['persentase_paijb'])) ? clean($_REQUEST['persentase_paijb']) : '';
$persentase_ppjb = (isset($_REQUEST['persentase_ppjb'])) ? clean($_REQUEST['persentase_ppjb']) : '';
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
	$('#close').on('click', function(e) {
		e.preventDefault();
		parent.window.focus();
		parent.window.popup.close();	
	});

	$('#paijb').on('click', function(e) {
		e.preventDefault();		
		var jenis = "paijb";
		var kode_jenis_ppjb = <?php echo $kode_jenis_ppjb; ?>;
		var jenis_ppjb = <?php echo $jenis_ppjb; ?>;
		var telah_bayar = <?php echo bigintval($telah_bayar); ?>;
		var persentase_ppjb = <?php echo $persentase_ppjb; ?>;
		if(telah_bayar >= persentase_ppjb){
			window.open(base_marketing + 'ppjb/transaksi/ppjb/ppjb_cetak.php?id=<?php echo $id; ?>&act=Ubah&kode_jenis_ppjb='+kode_jenis_ppjb+'&jenis_ppjb='+jenis_ppjb+'&jenis='+jenis);		
			parent.loadData(jenis);
			return false;
		}else{
			alert("Anda telah membayar "+<?php echo $telah_bayar;?> +". Maaf Pembayaran Kurang dari 10%");
			return false;			
		}
	});

	$('#ppjb').on('click', function(e) {
		e.preventDefault();		
		var jenis = "ppjb";
		var kode_jenis_ppjb = <?php echo $kode_jenis_ppjb; ?>;
		var jenis_ppjb = <?php echo $jenis_ppjb; ?>;
		var telah_bayar = <?php echo bigintval($telah_bayar); ?>;
		var persentase_ppjb = <?php echo $persentase_ppjb; ?>;
		if(telah_bayar >= persentase_ppjb){
			window.open(base_marketing + 'ppjb/transaksi/ppjb/ppjb_cetak.php?id=<?php echo $id; ?>&act=Ubah&kode_jenis_ppjb='+kode_jenis_ppjb+'&jenis_ppjb='+jenis_ppjb+'&jenis='+jenis);		
			parent.loadData(jenis);
			return false;
		}else{
			alert("Anda telah membayar "+<?php echo $telah_bayar;?> +". Maaf Pembayaran Kurang dari 20%");
			return false;			
		}
		
	});
});
</script>
</head>
<body class="popup">
	<form name="form" id="form" method="post">

	<table class="t-popup">
	<tr>
		
	</tr>
	<tr>
		<td colspan="3" class="td-action text-center">
			<input type="button" id="paijb" value=" PAIJB ">
			<input type="button" id="ppjb" value=" PPJB ">		
			<input type="button" id="close" value=" Tutup ">
		</td>
	</tr>
	</table>

	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
	<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
	</form>
</body>
</html>
<?php close($conn); ?>
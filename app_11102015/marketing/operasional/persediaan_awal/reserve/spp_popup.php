<?php
require_once('spp_proses.php');
require_once('../../../../../config/terbilang.php');
$terbilang = new Terbilang;
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
<script type="text/javascript" src="../../../../../plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="../../../../../plugin/window/javascripts/window.js"></script>
<script type="text/javascript" src="../../../../../config/js/main.js"></script>
<script type="text/javascript" src="../../../../../config/js/terbilang_js.js"></script>

<!-- TAB -->
<link rel="stylesheet" type="text/css" href="../../../../../tab/css/screen.css" media="screen" />
<script type="text/javascript" src="../../../../../tab/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript"> var jQuery142 = $.noConflict(true); </script>

<style type="text/css">
html { height:100%; }
body {
	height:100%;
	position:relative;
	background:#666;
	margin:0;
}
</style>

<script type="text/javascript">
jQuery142(document).ready(function() {
	jQuery142('#tab1').fadeIn('slow'); //tab pertama ditampilkan
	jQuery142('ul#nav_tab li a').click(function() { // jika link tab di klik
		jQuery142('ul#nav_tab li a').removeClass('active'); //menghilangkan class active (yang tampil)
		jQuery142(this).addClass("active"); // menambahkan class active pada link yang diklik
		jQuery142('.tab_konten').hide(); // menutup semua konten tab
		var aktif = jQuery142(this).attr('href'); // mencari mana tab yang harus ditampilkan
		jQuery142(aktif).fadeIn('slow'); // tab yang dipilih, ditampilkan
		return false;
	});
});
</script>
<script type="text/javascript">
jQuery(function($) {
	jQuery('#nama').val('dd');
	jQuery('#alamat_rumah').val('dd');
	
	$(document).on('click', '#close', function(e) {		
		e.preventDefault();
		return parent.loadData3();
	});
	
	$(document).on('click', '#save', function(e) {
		e.preventDefault();
		var url = base_marketing_operasional + 'persediaan_awal/reserve/spp_proses.php',
			data = $('#form').serialize();
			
		if (confirm("Apakah data SPP ini akan disimpan?") == false)
					{
					return false;
					}
			
		$.post(url, data, function(data) {
			if (data.error == true)
			{
				alert(data.msg);
			}
			else if (data.act == 'Simpan')
			{
			alert(data.msg);
			var url =	base_marketing + 'transaksi/spp/spp_popup.php' + '?act=Ubah&id=<?php echo $id;?>&status=0';
			setPopup(act + ' SPP', url, 903,505);	
			parent.loadData3();
			
			}
		}, 'json');
		return false;
	});
	
	$(document).ready(function(){
		$('a').click(function(){ 
		jQuery('#reset').click();
		});
    });
	
	loadData1();
	loadData2();
	if(<?php echo $sudah;?>){
		loadData3();
		loadData4();
		loadData5();	
	}
	
});

function loadData1()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab1').load(base_marketing_operasional + 'persediaan_awal/reserve/spp_spp.php', data);	
	return false;
}

function loadData2()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab2').load(base_marketing_transaksi + 'spp/spp_rincian_harga.php', data);	
	return false;
}

function loadData3()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab3').load(base_marketing_transaksi + 'spp/spp_rencana.php', data);	
	return false;
}
function loadData4()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab4').load(base_marketing_transaksi + 'spp/spp_realisasi.php', data);	
	return false;
}

function loadData5()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab5').load(base_marketing_transaksi + 'spp/spp_info_kpr.php', data);	
	return false;
}
</script>
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<div id="container">
    <ul id="nav_tab">
        <li><a href="#tab1" class="active">SPP</a></li>
        <li><a href="#tab2">Rincian Harga</a></li>
		<li><a href="#tab3">Rencana Pembayaran</a></li>
        <li><a href="#tab4">Realisasi Pembayaran</a></li>
		<li><a href="#tab5">Informasi KPR</a></li>
    </ul>
    <div class="clear"></div>
    <div id="konten">
    	<div style="display: none;" id="tab1" class="tab_konten"></div>
        <div style="display: none;" id="tab2" class="tab_konten"></div>
        <div style="display: none;" id="tab3" class="tab_konten"></div>
        <div style="display: none;" id="tab4" class="tab_konten"></div>
        <div style="display: none;" id="tab5" class="tab_konten"></div>
    </div>
</div>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
<input type="hidden" name="nm" id="nm" value="<?php echo $nm; ?>">
<input type="hidden" name="alamat" id="alamat" value="<?php echo $alamat; ?>">
<input type="hidden" name="no_va" id="no_va" value="<?php echo $no_va; ?>">
<input type="hidden" name="telepon" id="telepon" value="<?php echo $telepon; ?>">
<input type="hidden" name="no_ktp" id="no_ktp" value="<?php echo $no_ktp; ?>">
<input type="hidden" name="no_npwp" id="no_npwp" value="<?php echo $no_npwp; ?>">
<input type="hidden" name="agen" id="agen" value="<?php echo $agen; ?>">
<input type="hidden" name="koordinator" id="koordinator" value="<?php echo $koordinator; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
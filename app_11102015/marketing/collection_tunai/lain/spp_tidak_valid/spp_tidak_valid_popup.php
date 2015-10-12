<?php
require_once('spp_tidak_valid_proses.php');
//require_once('informasi_pembeli_rencana_proses.php');
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
	$(document).on('click', '#close', function(e) {		
		e.preventDefault();
		id = '<?php echo $id; ?>';
		return parent.loadData();
		parent.popup.close();
	});
	
	$(document).on('click', '#save', function(e) {
		e.preventDefault();
		var url = base_marketing + 'collection_tunai/lain/spp_tidak_valid/spp_tidak_valid_proses.php',
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
				else if (data.act == 'Ubah')
				{
					alert(data.msg);
					loadData1();
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
});

function loadData1()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab1').load(base_marketing + 'collection_tunai/lain/spp_tidak_valid/spp_tidak_valid_spp.php', data);	
	return false;
}

function loadData2()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab2').load(base_marketing + 'collection_tunai/lain/spp_tidak_valid/spp_tidak_valid_rencana.php', data);	
	return false;
}

</script>
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<div id="container">
    <ul id="nav_tab">
        <li><a href="#tab1" class="active">SPP</a></li>
        <li><a href="#tab2">Rencana & Pembayaran</a></li>
    </ul>
    <div class="clear"></div>
    <div id="konten">
    	<div style="display: none;" id="tab1" class="tab_konten"></div>
        <div style="display: none;" id="tab2" class="tab_konten"></div>
    </div>
</div>
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>
</body>
</html>
<?php close($conn); ?>
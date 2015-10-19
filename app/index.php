<?php 
require_once('../config/config.php');
die_login();
$conn = conn($sess_db);
die_conn($conn);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>PT Pembangunan Jaya Ancol</title>
	<link type="image/x-icon" rel="icon" href="../images/favicon.ico">

	<!-- CSS -->
	<link type="text/css" href="../config/css/style.css" rel="stylesheet">
	<link type="text/css" href="../config/css/menu.css" rel="stylesheet">
	<link type="text/css" href="../plugin/css/zebra/default.css" rel="stylesheet">
	<link type="text/css" href="../plugin/window/themes/default.css" rel="stylesheet">
	<link type="text/css" href="../plugin/window/themes/mac_os_x.css" rel="stylesheet">

	<!-- JS -->
	<script type="text/javascript" src="../plugin/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="../plugin/js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="../config/js/menu.js"></script>
	<script type="text/javascript" src="../plugin/js/jquery.inputmask.custom.js"></script>
	<script type="text/javascript" src="../plugin/js/keymaster.js"></script>
	<script type="text/javascript" src="../plugin/js/zebra_datepicker.js"></script>
	<script type="text/javascript" src="../plugin/js/jquery.ajaxfileupload.js"></script>
	<script type="text/javascript" src="../plugin/window/javascripts/prototype.js"></script>
	<script type="text/javascript" src="../plugin/window/javascripts/window.js"></script>
	<script type="text/javascript" src="../config/js/main.js"></script>

	<!-- TAB -->
	<link rel="stylesheet" type="text/css" href="../tab/css/screen.css" media="screen" />
	<script type="text/javascript" src="../tab/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript"> var jQuery142 = $.noConflict(true); </script>


</head>
<body>
	<div id="wrapper">
		<div id="header">
			<span class="market">
				<?php 
				echo '<span class="big">S</span>istem Administrasi Penjualan Apartemen';
				
				?>
			</span>
			<span class="unit">PT Pembangunan Jaya Ancol</span>
		</div>
		<div id="menu">
			<script type="text/javascript">
				jQuery(function($) {
					$('#nav a').each(function() {
						var link = $(this).attr('href');
						link = (link == '') ? 'javascript:void(0)' : base_app + '?cmd=' + link;
						$(this).attr('href', link);
					});
				});
			</script>
			<div class="clear"></div>
			<ul id="nav">
				<?php 
				switch ($sess_app_id)
				{
					case 'M' : die_app($sess_app_id); include('marketing/menu.php'); break;
					case 'C01' : die_app($sess_app_id); include('collection_tunai/menu.php'); break;
					case 'C02' : die_app($sess_app_id); include('collection_kpr/menu.php'); break;
					case 'C03' : die_app($sess_app_id); include('ppjb/menu.php'); break;
					case 'C04' : die_app($sess_app_id); include('kredit/menu.php'); break;
				}
				?>
			</ul>
			<div id="profil">
				<a href="index.php"><?php echo $_SESSION['FULL_NAME']; ?></a> | 
				<a href="<?php echo BASE_URL; ?>app/authentic.php?act=logout">Logout</a>
			</div>
			<div class="clear"></div>
		</div>
		
		<div id="content">
			<div class="clear"></div>
			
			<?php 
			$cmd = (isset($_REQUEST['cmd'])) ? strip_tags($_REQUEST['cmd']) : '';
			switch ($sess_app_id)
			{
				case 'M' : include('marketing/module.php'); break;
				case 'C01' : include('collection_tunai/module.php'); break;
				case 'C02' : include('collection_kpr/module.php'); break;
				case 'C03' : include('ppjb/module.php'); break;
				case 'C04' : include('kredit/module.php'); break;
			}
			?>
			
			<div class="clear"></div>
		</div>
	</div>
	<div id="footer">&copy; 2015 - PT. PEMBANGUNAN JAYA ANCOL, Tbk<br>Built By ASYS IT Consultant</div>

</body>
</html>
<?php close($conn); ?>

<script type="text/javascript">
	jQuery(function($) {
		var navpos = $('#menu').offset();
		console.log(navpos.top);
		$(window).bind('scroll', function() {
			if ($(window).scrollTop() > navpos.top) {
				$('#menu').addClass('fixed');
			}
			else {
				$('#menu').removeClass('fixed');
			}
		});
	});
</script>

<script src="../config/js/highcharts.js" type="text/javascript"></script>
<script src="../config/js/exporting.js" type="text/javascript"></script>

<div class="title-page">BERANDA</div>

<form name="form" id="form" method="post">
	
	<script type="text/javascript">
		jQuery(function($) {		
		
			
			/* -- BUTTON -- */
			$(document).on('click', '#detail_distribusi', function(e) {
				e.preventDefault();
				return false;
			});
			
			$(document).on('click', '#detail_ppjb', function(e) {
				if (popup) { popup.close(); }
				jQuery('#t-detail').load(base_marketing + 'ppjb/laporan/daftar_belum_ppjb/daftar_belum_ppjb_setup.php', data);	
				var data = jQuery('#form').serialize();
				return false;
			});
			
			$(document).on('click', '#detail_otorisasi', function(e) {
				if (popup) { popup.close(); }
				jQuery('#t-detail').load(base_marketing + 'transaksi/spp/spp_setup.php', data);	
				var data = jQuery('#form').serialize();
				return false;
			});
			
			$(document).on('click', '#detail_distribusi', function(e) {
				if (popup) { popup.close(); }
				jQuery('#t-detail').load(base_marketing + 'transaksi/spp/spp_setup.php', data);	
				var data = jQuery('#form').serialize();
				return false;
			});
			
			$(document).on('click', '#detail_identifikasi', function(e) {
				if (popup) { popup.close(); }
				jQuery('#t-detail').load(base_marketing + 'collection_tunai/transaksi/pembayaran/pembayaran_setup.php', data);	
				var data = jQuery('#form').serialize();
				return false;
			});
			
			loadData();
			loadDataSomasi();
		});
		function loadData()
		{
			if (popup) { popup.close(); }
			jQuery('#t-detail').load(base_marketing + 'home/home_load.php', data);	
			var data = jQuery('#form').serialize();
			return false;
		}
		
		function loadDataSomasi()
		{
			if (popup) { popup.close(); }
			jQuery('#t-somasi').load(base_marketing + 'home/somasi_satu_load.php', data);	
			var data = jQuery('#form').serialize();
			return false;
		}
		
	</script>
</form>

<div id="t-detail"></div>
<div class="clear"><br></div>
<div class="title-page">DAFTAR JATUH TEMPO</div>
<div id="t-somasi"></div>






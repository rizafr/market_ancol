<?php
require_once('edit_stok_penjualan_proses.php');
require_once('../../../../config/config.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!-- CSS -->
	<link type="text/css" href="../../../../config/css/style.css" rel="stylesheet">
	<link type="text/css" href="../../../../plugin/css/zebra/default.css" rel="stylesheet">
	<link type="text/css" href="../../../../plugin/window/themes/default.css" rel="stylesheet">
	<link type="text/css" href="../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">

	<!-- JS -->
	<script type="text/javascript" src="../../../../plugin/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/jquery.inputmask.custom.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/keymaster.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/zebra_datepicker.js"></script>
	<script type="text/javascript" src="../../../../plugin/window/javascripts/prototype.js"></script>
	<script type="text/javascript" src="../../../../plugin/window/javascripts/window.js"></script>
	<script type="text/javascript" src="../../../../config/js/main.js"></script>
	<script type="text/javascript">
		var this_base = base_marketing + 'transaksi/edit_stok_penjualan/';
		var get_base = base_marketing + 'operasional/get/';

		jQuery(function($) {
			
			$('#kode_blok').inputmask('varchar', { repeat: '15' });
			$('#luas_tanah, #luas_bangunan').inputmask('numericDesc', {iMax:10, dMax:2});
			$('#disc_tanah, #disc_bangunan').inputmask('numericDesc', {iMax:4, dMax:12});
			$('#ppn_tanah, #ppn_bangunan').inputmask('numericDesc', {iMax:3, dMax:2});
			
			$('#tutup').on('click', function(e) {
				e.preventDefault();
				parent.loadData();
			});
			
			$('#simpan').on('click', function(e) {
				e.preventDefault();
				var url		= this_base + 'edit_stok_penjualan_proses.php',
				data	= $('#form').serialize();
				
				if (confirm("Apakah data telah terisi dengan benar ?") == false)
				{
					return false;
				}		
				
				$.post(url, data, function(result) {
					alert(result.msg);
					if (result.error == false) {
						if (result.act == 'Ubah') {
							location.reload();
						}
					}
				}, 'json');
				
				return false;
			});
		});

		function get_kode_desa() {
			var url = get_base + 'kode_desa.php'; 
			setPopup('Daftar Desa', url, 300, winHeight-100); 
			return false; 
		}
		function get_kode_lokasi() {
			var url = get_base + 'kode_lokasi.php'; 
			setPopup('Daftar Lokasi', url, 300, winHeight-100); 
			return false; 
		}
		function get_kode_unit() {
			var url = get_base + 'kode_unit.php'; 
			setPopup('Daftar Jenis Unit', url, 300, winHeight-100); 
			return false; 
		}
		function get_kode_sk_tanah() {
			var url = get_base + 'kode_sk_tanah.php'; 
			setPopup('Daftar SK Tanah', url, 500, winHeight-100); 
			return false; 
		}
		function get_kode_faktor() {
			var url = get_base + 'kode_faktor.php'; 
			setPopup('Daftar Faktor Strategis', url, 300, winHeight-100); 
			return false; 
		}
		function get_kode_tipe() {
			var url = get_base + 'kode_tipe.php'; 
			setPopup('Daftar Tipe', url, 300, winHeight-100); 
			return false; 
		}
		function get_kode_sk_bangunan() {
			var url = get_base + 'kode_sk_bangunan_setup.php'; 
			setPopup('Daftar SK Bangunan', url, 700, winHeight-100); 
			return false; 
		}
		function get_kode_penjualan() {
			var url = get_base + 'kode_penjualan.php'; 
			setPopup('Daftar Jenis Penjualan', url, 300, winHeight-100); 
			return false; 
		}
	</script>
</head>

<body class="popup2">
	<form name="form" id="form" method="post">
		<table class="t-popup wauto f-left">
			<tr>
				<td width="120"><b>Kode Blok</td><td>:</b></td>
				<td><input type="text" name="kode_blok" id="kode_blok" size="25" value="<?php if(isset($kode_blok)){echo $kode_blok;}else{echo '-';} ?>"></td>
			</tr>
			<tr>
				<td width="120"><b>Nomor VA</td><td>:</b></td>
				<td><input type="text" name="no_va" id="no_va" size="25" value="<?php if(isset($no_va)){echo $no_va;}else{echo '-';} ?>"></td>
			</tr>
			<tr>
				<td>Tower</td><td>:</td>
				<td>
					<input type="hidden" name="kode_lokasi" id="kode_lokasi" size="1" value="<?php if(isset($kode_lokasi)){echo $kode_lokasi;}else{echo '-';} ?>">
					<button onclick="return get_kode_lokasi()"> > </button>
					<input type="text" id="lokasi" size="25" value="<?php if(isset($lokasi)){echo $lokasi;}else{echo '-';} ?>">
				</td>
			</tr>
			<tr>
				<td>Jenis Unit</td><td>:</td>
				<td>
					<input type="hidden" name="kode_unit" id="kode_unit" size="1" value="<?php if(isset($kode_unit)){echo $kode_unit;}else{echo '-';} ?>">
					<button onclick="return get_kode_unit()"> > </button>
					<input type="text" id="jenis_unit" size="25" value="<?php if(isset($jenis_unit)){echo $jenis_unit;}else{echo '-';} ?>">
				</td>
			</tr>

			<tr>
				<td>Tipe</td><td>:</td>
				<td>
					<input type="hidden" name="kode_tipe" id="kode_tipe" size="1" value="<?php if(isset($kode_tipe)){echo $kode_tipe;}else{echo '-';} ?>">
					<button onclick="return get_kode_tipe()"> > </button>
					<input type="text" id="tipe_bangunan" size="25" value="<?php if(isset($tipe_bangunan)){echo $tipe_bangunan;}else{echo '-';} ?>">
				</td>
			</tr>

			<tr>
				<td><b>Luas Semi Gross</b></td><td>:</td>
				<td><input type="text" name="luas_bangunan" id="luas_bangunan" size="5" value="<?php echo to_decimal($luas_bangunan); ?>"> M&sup2;</td>
			</tr>

			<tr>
				<td>SK. Harga</td><td>:</td>
				<td>
					<input type="text" name="kode_sk" id="kode_sk" size="10" value="<?php if(isset($kode_sk)){echo $kode_sk;}else{echo '-';} ?>">
					<button onclick="return get_kode_sk_bangunan()"> > </button>
				</td>
			</tr>
			<tr>
				<td>Cash Keras</td><td>:</td>
				<td>
					<input type="text" id="harga_cash_keras" class="text-right" size="15" value="<?php if(isset($harga_cash_keras)){echo $harga_cash_keras;}else{echo '0';} ?>">
				</td>
			</tr>
			<tr>
				<td>CB36X</td><td>:</td>
				<td>
					<input type="text" id="harga_CB36X" class="text-right" size="15" value="<?php if(isset($CB36X)){echo $CB36X;}else{echo '0';} ?>">
				</td>
			</tr>
			<tr>
				<td>CB48X</td><td>:</td>
				<td>
					<input type="text" id="harga_CB48X" class="text-right" size="15" value="<?php if(isset($CB48X)){echo $CB48X;}else{echo '0';} ?>">
				</td>
			</tr>
			<tr>
				<td>KPA24X</td><td>:</td>
				<td>
					<input type="text" id="harga_KPA24X" class="text-right" size="15" value="<?php if(isset($KPA24X)){echo $KPA24X;}else{echo '0';} ?>">
				</td>
			</tr>
			<tr>
				<td>KPA36X</td><td>:</td>
				<td>
					<input type="text" id="harga_KPA36X" class="text-right" size="15" value="<?php if(isset($KPA36X)){echo $KPA36X;}else{echo '0';} ?>">
				</td>
			</tr>
			<tr>
				<td>Jenis Penjualan</td><td>:</td>
				<td>
					<input type="hidden" name="kode_penjualan" id="kode_penjualan" size="1" value="<?php if(isset($kode_penjualan)){echo $kode_penjualan;}else{echo '-';} ?>">
					<button onclick="return get_kode_penjualan()"> > </button>
					<input type="text" id="jenis_penjualan" size="25" value="<?php if(isset($jenis_penjualan)){echo $jenis_penjualan;}else{echo '-';} ?>">
				</td>
			</tr>

		</table>

		<div class="clear"><br></div>
		<div class="clear"><br></div>

		<table class="t-popup">
			<tr>
				<td>
					<input type="submit" id="simpan" value=" <?php echo $act; ?> ">
					<input type="reset" id="reset" value=" Reset ">
					<input type="button" id="tutup" value=" Tutup ">
				</td>
			</tr>
		</table>

		<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
		<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
	</form>

</body>
</html>
<?php close($conn); ?>
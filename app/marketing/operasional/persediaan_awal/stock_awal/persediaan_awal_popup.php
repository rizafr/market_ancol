<?php
require_once('persediaan_awal_proses.php');
require_once('../../../../../config/config.php');
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
	<script type="text/javascript">
		var this_base = base_marketing + 'operasional/persediaan_awal/';
		var get_base = base_marketing + 'operasional/get/';

		jQuery(function($) {

			$('#kode_blok').inputmask('varchar', { repeat: '15' });
			$('#no_va').inputmask('mask', { repeat: '15', mask : '9', groupSeparator : '', placeholder : '' });
			$('#luas_tanah, #luas_bangunan').inputmask('numericDesc', {iMax:10, dMax:2});
			$('#harga_tanah_tmp, #harga_tanah_total,#harga_ppn_tanah, #harga_disc_tanah, #harga_fs_tanah,#total_harga').inputmask('numericDesc', {iMax:10, dMax:16});
			$('#harga_bangunan_tmp, #harga_bangunan_total,#harga_ppn_bangunan, #harga_disc_bangunan, #harga_fs_bangunan').inputmask('numericDesc', {iMax:10, dMax:16});
			$('#disc_tanah, #disc_bangunan').inputmask('numericDesc', {iMax:4, dMax:12});
			$('#ppn_tanah, #ppn_bangunan').inputmask('numericDesc', {iMax:3, dMax:2});

			$('#tutup').on('click', function(e) {
				e.preventDefault();
				parent.loadData1();	
			});

			$('#simpan').on('click', function(e) {
				e.preventDefault();
				var url		= this_base + 'stock_awal/persediaan_awal_proses.php',
				data	= $('#form').serialize();

				if (confirm("Apakah data telah terisi dengan benar ?") == false)
				{
					return false;
				}		

				$.post(url, data, function(result) {
					alert(result.msg);
					if (result.error == false) {
						if (result.act == 'Tambah') {
					// $('#reset').click();
					parent.loadData1();		
					location.reload();
				} else if(result.act == 'Ubah') {
					parent.loadData1();	
					location.reload();
				}
			}
			
		}, 'json');

				return false;
			});

			$('#harga_disc_tanah, #harga_disc_bangunan').on('change', function(e) {
				hitung_persen();
				return false;
			});

			$('#harga_disc_tanah,#harga_tanah_sk,#luas_tanah, #nilai_kurang, #nilai_tambah, #ppn_tanah, #disc_tanah').on('change', function(e) {
				hitung_tanah();
				return false;
			});

			$('#harga_disc_bangunan,#harga_bangunan_sk,#luas_bangunan,#nilai_tambah,#nilai_kurang, #ppn_bangunan,#disc_bangunan').on('change', function(e) {
				hitung_bangunan();
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
	setPopup('Daftar Faktor Strategis', url, 800, winHeight-100); 
	return false; 
}
function get_kode_tipe() {
	var url = get_base + 'kode_tipe.php'; 
	setPopup('Daftar Tipe', url, 300, winHeight-100); 
	return false; 
}
function get_kode_sk_bangunan() {
	var url = get_base + 'kode_sk_bangunan.php'; 
	setPopup('Daftar SK Bangunan', url, 600, winHeight-100); 
	return false; 
}
function get_kode_penjualan() {
	var url = get_base + 'kode_penjualan.php'; 
	setPopup('Daftar Jenis Penjualan', url, 300, winHeight-100); 
	return false; 
}
function get_kode_bayar() {
	var url = get_base + 'kode_bayar.php'; 
	setPopup('Daftar Jenis Pembayaran', url, 300, winHeight-100); 
	return false; 
}
function conv(x){
	if(x==''){
		return 0;
	}
	return parseFloat(x.replace(',','').replace(',','').replace(',',''));
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
					<input type="text" name="kode_lokasi" id="kode_lokasi" size="1" value="<?php if(isset($kode_lokasi)){echo $kode_lokasi;}else{echo '-';} ?>">
					<button onclick="return get_kode_lokasi()"> > </button>
					<input type="text" id="lokasi" size="25" value="<?php if(isset($lokasi)){echo $lokasi;}else{echo '-';} ?>">
				</td>
			</tr>
			<tr>
				<td>Jenis Unit</td><td>:</td>
				<td>
					<input type="text" name="kode_unit" id="kode_unit" size="1" value="<?php if(isset($kode_unit)){echo $kode_unit;}else{echo '-';} ?>">
					<button onclick="return get_kode_unit()"> > </button>
					<input type="text" id="jenis_unit" size="25" value="<?php if(isset($jenis_unit)){echo $jenis_unit;}else{echo '-';} ?>">
				</td>
			</tr>

			<tr>
				<td>Tipe</td><td>:</td>
				<td>
					<input type="text" name="kode_tipe" id="kode_tipe" size="1" value="<?php if(isset($kode_tipe)){echo $kode_tipe;}else{echo '-';} ?>">
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
					<input type="text" name="kode_sk_bangunan" id="kode_sk_bangunan" size="1" value="<?php if(isset($kode_sk_bangunan)){echo $kode_sk_bangunan;}else{echo '-';} ?>">
					<button onclick="return get_kode_sk_bangunan()"> > </button>
					<input type="text" id="harga_bangunan_sk" class="text-right" size="15" value="<?php if(isset($harga_bangunan_sk)){echo $harga_bangunan_sk;}else{echo '0';} ?>"> / M&sup2;
				</td>
			</tr>
			<tr>
				<td>Jenis Penjualan</td><td>:</td>
				<td>
					<input type="text" name="kode_penjualan" id="kode_penjualan" size="1" value="<?php if(isset($kode_penjualan)){echo $kode_penjualan;}else{echo '-';} ?>">
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
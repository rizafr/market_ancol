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
	var url = get_base + 'kode_sk_bangunan.php'; 
	setPopup('Daftar SK Bangunan', url, 600, winHeight-100); 
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
	<td><input type="text" name="kode_blok" id="kode_blok" size="10" value="<?php echo $kode_blok; ?>"></td>
</tr>
<tr>
	<td>Desa</td><td>:</td>
	<td>
		<input type="text" name="kode_desa" id="kode_desa" size="1" value="<?php echo $kode_desa; ?>">
		<button onclick="return get_kode_desa()"> > </button>
		<input type="text" id="nama_desa" size="25" value="<?php echo $nama_desa; ?>">
	</td>
</tr>
<tr>
	<td>Lokasi</td><td>:</td>
	<td>
		<input type="text" name="kode_lokasi" id="kode_lokasi" size="1" value="<?php echo $kode_lokasi; ?>">
		<button onclick="return get_kode_lokasi()"> > </button>
		<input type="text" id="lokasi" size="25" value="<?php echo $lokasi; ?>">
	</td>
</tr>
<tr>
	<td>Jenis Unit</td><td>:</td>
	<td>
		<input type="text" name="kode_unit" id="kode_unit" size="1" value="<?php echo $kode_unit; ?>">
		<button onclick="return get_kode_unit()"> > </button>
		<input type="text" id="jenis_unit" size="25" value="<?php echo $jenis_unit; ?>">
	</td>
</tr>
<tr>
	<td>SK. Tanah</td><td>:</td>
	<td>
		<input type="text" name="kode_sk_tanah" id="kode_sk_tanah" size="1" value="<?php echo $kode_sk_tanah; ?>">
		<button onclick="return get_kode_sk_tanah()"> > </button>
		<input type="text" id="harga_tanah_sk" class="text-right" size="15" value="<?php echo to_money($harga_tanah_sk); ?>"> / M&sup2;
	</td>
</tr>
<tr>
	<td>Faktor Strategis</td><td>:</td>
	<td>
		<input type="text" name="kode_faktor" id="kode_faktor" size="1" value="<?php echo $kode_faktor; ?>">
		<button onclick="return get_kode_faktor()"> > </button>
		<input type="text" id="faktor_strategis" size="25" value="<?php echo $faktor_strategis; ?>">
	</td>
</tr>
<tr>
	<td>Tipe</td><td>:</td>
	<td>
		<input type="text" name="kode_tipe" id="kode_tipe" size="1" value="<?php echo $kode_tipe; ?>">
		<button onclick="return get_kode_tipe()"> > </button>
		<input type="text" id="tipe_bangunan" size="25" value="<?php echo $tipe_bangunan; ?>">
	</td>
</tr>
<tr>
	<td>SK. Bangunan</td><td>:</td>
	<td>
		<input type="text" name="kode_sk_bangunan" id="kode_sk_bangunan" size="1" value="<?php echo $kode_sk_bangunan; ?>">
		<button onclick="return get_kode_sk_bangunan()"> > </button>
		<input type="text" id="harga_bangunan_sk" class="text-right" size="15" value="<?php echo to_money($harga_bangunan_sk); ?>"> / M&sup2;
	</td>
</tr>
<tr>
	<td>Jenis Penjualan</td><td>:</td>
	<td>
		<input type="text" name="kode_penjualan" id="kode_penjualan" size="1" value="<?php echo $kode_penjualan; ?>">
		<button onclick="return get_kode_penjualan()"> > </button>
		<input type="text" id="jenis_penjualan" size="25" value="<?php echo $jenis_penjualan; ?>">
	</td>
</tr>
</table>

<table class="t-popup wauto f-right">
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td><b>TOTAL HARGA</b></td><td>:</b></td>
	<td><b>Rp. </b><input type="text" class="bold text-right" value="<?php echo to_money($harga_tanah + $harga_bangunan); ?>"></td>
</tr>
<tr>
	<td colspan = 3><hr><br><br></td>
</tr>
<tr>
	<td width="120">Tanggal Dibangun</td><td>:</td>
	<td><?php echo $tgl_bangunan; ?></td>
</tr>
<tr>
	<td>Tanggal Selesai</td><td>:</td>
	<td><?php echo $tgl_selesai; ?></td>
</tr>
<tr>
	<td>Progres</td><td>:</td>
	<td><?php echo to_decimal($progress); ?> %</td>
</tr>
<tr>
	<td>Class</td><td>:</td>
	<td>
		<label for="class_l"><u>L</u></label><input type="radio" name="class" id="class_l" value="1" <?php echo is_checked('1', $class); ?>>&nbsp;&nbsp;
		<label for="class_m"><u>M</u></label><input type="radio" name="class" id="class_m" value="2" <?php echo is_checked('2', $class); ?>>&nbsp;&nbsp;
		<label for="class_mu"><u>MU</u></label><input type="radio" name="class" id="class_mu" value="3" <?php echo is_checked('3', $class); ?>>&nbsp;&nbsp;
		<label for="class_h"><u>H</u></label><input type="radio" name="class" id="class_h" value="4" <?php echo is_checked('4', $class); ?>>&nbsp;&nbsp;
		<label for="class_lain"><u>Lain</u></label><input type="radio" name="class" id="class_lain" value="5" <?php echo is_checked('5', $class); ?>>
	</td>
</tr>
<tr>
	<td>Gambar Ukur</td><td>:</td>
	<td>
		<input type="checkbox" name="status_gambar_siteplan" id="status_gambar_siteplan" value="1" <?php echo is_checked('1', $status_gambar_siteplan); ?> onclick="return false"><label for="status_gambar_siteplan">Siteplan</label>&nbsp;&nbsp;
		<input type="checkbox" name="status_gambar_lapangan" id="status_gambar_lapangan" value="1" <?php echo is_checked('1', $status_gambar_lapangan); ?> onclick="return false"><label for="status_gambar_lapangan">Lapangan</label>&nbsp;&nbsp;
		<input type="checkbox" name="status_gambar_gs" id="status_gambar_gs" value="1" <?php echo is_checked('1', $status_gambar_gs); ?>><label for="status_gambar_gs">GS</label>&nbsp;
	</td>
</tr>
<tr>
	<td>Program Khusus</td><td>:</td>
	<td>
		<select name="program" id="program">
			<option> -- Program Khusus -- </option>
			<option value="1" <?php echo is_selected('1', $program); ?>> JRP/Normal </option>
			<option value="2" <?php echo is_selected('2', $program); ?>> Prog. BTN01 </option>
		</select>
	</td>
</tr>
</table>

<div class="clear"><br></div>
<div class="clear"><br></div>

<table class="t-popup w100 f-left" border="3">
<tr>
	<td width="85" rowspan="2"></td>
	<td width="85" class="text-center" rowspan="2"><b>Luas</b></td>
	<td colspan="2" class="text-center"><b>Faktor Strategis</b></td>
	<td class="text-center" rowspan="2" width="127"><b>Discount</b></td>
	<td class="text-center" rowspan="2" width="127"><b>PPN</b></td>
	<td class="text-center" rowspan="2"><b>Harga</b></td>
</tr>
<tr>
	<td width="80" class="text-center"><b>(+)</b></td>
	<td width="80" class="text-center"><b>(-)</b></td>
</tr>
<tr>
	<td><b>Tanah</b></td>
	<td><input type="text" name="luas_tanah" id="luas_tanah" size="5" value="<?php echo to_decimal($luas_tanah); ?>"> M&sup2;</td>
	<td class="text-center"><input type="text" readonly="readonly" size="5" class="text-right" value="<?php echo to_decimal($nilai_tambah); ?>"> %</td>
	<td class="text-center"><input type="text" readonly="readonly" size="5" class="text-right" value="<?php echo to_decimal($nilai_kurang); ?>"> %</td>
	<td><input type="text" name="disc_tanah" id="disc_tanah" size="12" value="<?php echo to_decimal($disc_tanah); ?>"> %</td>
	<td><input type="text" name="ppn_tanah" id="ppn_tanah" size="12" value="<?php echo to_decimal($ppn_tanah); ?>"> %</td>
	<td rowspan="2">Rp. <input readonly="readonly" type="text" class="bold text-right" value="<?php echo to_money($harga_tanah); ?>"></td>
</tr>
<tr>
	<td colspan="2" class="text-right">Rp. <input readonly="readonly" type="text" size="15" class="text-right" value="<?php echo to_money($base_harga_tanah); ?>"></td>
	<td colspan="2" class="text-center">Rp. <input readonly="readonly" type="text" size="15" class="text-right" value="<?php echo to_money($fs_harga_tanah); ?>"></td>
	<td>Rp. <input readonly="readonly" type="text" size="12" class="text-right" value="<?php echo to_money($disc_harga_tanah); ?>"></td>
	<td>Rp. <input readonly="readonly" type="text" size="12" class="text-right" value="<?php echo to_money($ppn_harga_tanah); ?>"></td>
</tr>
<tr>
	<td><b>Bangunan</b></td>
	<td><input type="text" name="luas_bangunan" id="luas_bangunan" size="5" value="<?php echo to_decimal($luas_bangunan); ?>"> M&sup2;</td>
	<td colspan="2" rowspan="2"></td>
	<td><input type="text" name="disc_bangunan" id="disc_bangunan" size="12" value="<?php echo to_decimal($disc_bangunan); ?>"> %</td>
	<td><input type="text" name="ppn_bangunan" id="ppn_bangunan" size="12" value="<?php echo to_decimal($ppn_bangunan); ?>"> %</td>
	<td rowspan="2">Rp. <input readonly="readonly" type="text" class="bold text-right" value="<?php echo to_money($harga_bangunan); ?>"></td>
</tr>
<tr>
	<td colspan="2" class="text-right">Rp. <input readonly="readonly" type="text" size="15" class="text-right" value="<?php echo to_money($base_harga_bangunan); ?>"></td>
	<td>Rp. <input readonly="readonly" type="text" size="12" class="text-right" value="<?php echo to_money($disc_harga_bangunan); ?>"></td>
	<td>Rp. <input readonly="readonly" type="text" size="12" class="text-right" value="<?php echo to_money($ppn_harga_bangunan); ?>"></td>
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
<?php
require_once('informasi_persediaan_proses.php');
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
	$('#luas_tanah, #luas_bangunan').inputmask('numericDesc', {iMax:10, dMax:2});
	$('#harga_tanah_tmp, #harga_tanah_total,#harga_ppn_tanah, #harga_disc_tanah, #harga_fs_tanah,#total_harga').inputmask('numericDesc', {iMax:10, dMax:16});
	$('#harga_bangunan_tmp, #harga_bangunan_total,#harga_ppn_bangunan, #harga_disc_bangunan, #harga_fs_bangunan').inputmask('numericDesc', {iMax:10, dMax:16});
	$('#disc_tanah, #disc_bangunan').inputmask('numericDesc', {iMax:4, dMax:12});
	$('#ppn_tanah, #ppn_bangunan').inputmask('numericDesc', {iMax:3, dMax:2});
	
	$('#reserve').on('click', function(e) {
		e.preventDefault();		
		parent.showPopup('Reserve', '<?php echo $id; ?>');
		return false;
	});
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
					
					location.reload();
				} else if (result.act == 'Ubah') {
					location.reload();
				}
			}
			parent.loadData1();
		}, 'json');
		
		return false;
	});

	$('#harga_tanah_sk,#luas_tanah, #nilai_kurang, #nilai_tambah, #ppn_tanah, #disc_tanah').on('change', function(e) {
		hitung_tanah();
		return false;
	});

	$('#harga_bangunan_sk,#luas_bangunan,#nilai_tambah,#nilai_kurang, #ppn_bangunan,#disc_bangunan').on('change', function(e) {
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
function hitung_tanah(){
	var harga_tanah = conv(jQuery('#harga_tanah_sk').val()),
		luas_tanah = conv(jQuery('#luas_tanah').val()),
		nilai_tambah = conv(jQuery('#nilai_tambah').val()),
		nilai_kurang = conv(jQuery('#nilai_kurang').val()),
		disc = conv(jQuery('#disc_tanah').val()),
		ppn = conv(jQuery('#ppn_tanah').val()),
		harga_bangunan = conv(jQuery('#harga_bangunan_total').val());
	var total = harga_tanah * luas_tanah;
	var total_p_fs = total + (total * nilai_tambah/100) - (total*nilai_kurang/100);
	var total_disc = total_p_fs * disc / 100;
	var total_all_disc = total_p_fs - total_disc;
	var total_ppn = total_all_disc * ppn / 100 ;
	var total_all = total_all_disc + total_ppn;
	var harga_total = harga_bangunan + total_all;

	jQuery('#harga_disc_tanah').val(total_disc);
	jQuery('#harga_ppn_tanah').val(total_ppn)
	jQuery('#harga_tanah_tmp').val(total);
	jQuery('#harga_tanah_total').val(total_all);
	jQuery('#total_harga').val(harga_total);
}

function hitung_bangunan(){
	var harga_bangunan = conv(jQuery('#harga_bangunan_sk').val()),
		luas_bangunan = conv(jQuery('#luas_bangunan').val()),
		nilai_tambah = conv(jQuery('#nilai_tambah').val()),
		nilai_kurang = conv(jQuery('#nilai_kurang').val()),
		disc = conv(jQuery('#disc_bangunan').val()),
		ppn = conv(jQuery('#ppn_bangunan').val())
		harga_tanah = conv(jQuery('#harga_tanah_total').val());
	var total = harga_bangunan * luas_bangunan;
	var total_p_fs = total + (total * nilai_tambah/100) - (total*nilai_kurang/100);
	var total_disc = total_p_fs * disc / 100;
	var total_all_disc = total_p_fs - total_disc;
	var total_ppn = total_all_disc * ppn / 100 ;
	var total_all = total_all_disc + total_ppn;

	var harga_total = harga_tanah + total_all;
	jQuery('#harga_disc_bangunan').val(total_disc);
	jQuery('#harga_ppn_bangunan').val(total_ppn)
	jQuery('#harga_bangunan_tmp').val(total);
	jQuery('#harga_bangunan_total').val(total_all);
	jQuery('#total_harga').val(harga_total);
}

</script>
</head>

<body class="popup2">
<form name="form" id="form" method="post">
<table class="t-popup wauto f-left">
<tr>
	<td width="120"><b>Kode Blok</td><td>:</b></td>
	<td><input type="text" name="kode_blok" id="kode_blok" size="10" value="<?php if(isset($kode_blok)){echo $kode_blok;}else{echo '-';} ?>"></td>
</tr>
<tr>
	<td width="120"><b>Nomor VA</td><td>:</b></td>
	<td><input type="text" name="no_va" id="no_va" size="10" value="<?php if(isset($no_va)){echo $no_va;}else{echo '-';} ?>"></td>
</tr>
<tr>
	<td>Desa</td><td>:</td>
	<td>
		<input type="text" name="kode_desa" id="kode_desa" size="1" value="<?php if(isset($kode_desa)){echo $kode_desa;}else{echo '-';} ?>">
		<button onclick="return get_kode_desa()"> > </button>
		<input type="text" id="nama_desa" size="25" value="<?php if(isset($nama_desa)){echo $nama_desa;}else{echo '-';} ?>">
	</td>
</tr>
<tr>
	<td>Lokasi</td><td>:</td>
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
	<td>SK. Tanah</td><td>:</td>
	<td>
		<input type="text" name="kode_sk_tanah" id="kode_sk_tanah" size="1" value="<?php if(isset($kode_sk_tanah)){echo $kode_sk_tanah;}else{echo '-';} ?>">
		<button onclick="return get_kode_sk_tanah()"> > </button>
		<input type="text" id="harga_tanah_sk" class="text-right" size="15" value="<?php if(isset($harga_tanah_sk)){echo $harga_tanah_sk;}else{echo '0';} ?>"> / M&sup2;
	</td>
</tr>
<tr>
	<td>Faktor Strategis</td><td>:</td>
	<td>
		<input type="text" name="kode_faktor" id="kode_faktor" size="1" value="<?php if(isset($kode_faktor)){echo $kode_faktor;}else{echo '-';} ?>">
		<button onclick="return get_kode_faktor()"> > </button>
		<input type="text" id="faktor_strategis" size="25" value="<?php if(isset($faktor_strategis)){echo $faktor_strategis;}else{echo '-';} ?>">
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
	<td>SK. Bangunan</td><td>:</td>
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

<table class="t-popup wauto f-right">
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td><b>TOTAL HARGA</b></td><td>:</b></td>
	<td><b>Rp. </b><input readonly="readonly" type="text" name="total_harga" id="total_harga" class="bold text-right" value="<?php echo to_money($harga_tanah + $harga_bangunan); ?>"></td>
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
		<label for="class_mu"><u>MU</u></label><input type="radio" name="class" id="class_mu" checked="true" value="3" <?php echo is_checked('3', $class); ?>>&nbsp;&nbsp;
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
	<td class="text-center"><input type="text" readonly="readonly" id="nilai_tambah" name="nilai_tambah"  size="5" class="text-right" value="<?php echo to_decimal($nilai_tambah); ?>"> %</td>
	<td class="text-center"><input type="text" readonly="readonly" id="nilai_kurang" name="nilai_kurang" size="5" class="text-right" value="<?php echo to_decimal($nilai_kurang); ?>"> %</td>
	<td><input type="text" name="disc_tanah" id="disc_tanah" size="12" value="<?php echo to_decimal($disc_tanah); ?>"> %</td>
	<td><input type="text" name="ppn_tanah" id="ppn_tanah" size="12" value="<?php echo to_decimal($ppn_tanah); ?>"> %</td>
	<td rowspan="2">Rp. <input readonly="readonly" type="text" class="bold text-right" name = "harga_tanah_total" id = "harga_tanah_total" value="<?php echo to_money($harga_tanah); ?>"></td>
</tr>
<tr>
	<td colspan="2" class="text-right">Rp. <input readonly="readonly" type="text" size="15" name = "harga_tanah_tmp" id = "harga_tanah_tmp" class="text-right" value="<?php echo to_money($base_harga_tanah); ?>"></td>
	<td colspan="2" class="text-center">Rp. <input readonly="readonly" type="text" size="15" name="harga_fs_tanah" id="harga_fs_tanah" class="text-right" value="<?php echo to_money($fs_harga_tanah); ?>"></td>
	<td>Rp. <input readonly="readonly" type="text" name="harga_disc_tanah" id="harga_disc_tanah" size="12" class="text-right" value="<?php echo to_money($disc_harga_tanah); ?>"></td>
	<td>Rp. <input readonly="readonly" type="text" size="12" class="text-right" name="harga_ppn_tanah" id="harga_ppn_tanah" value="<?php echo to_money($ppn_harga_tanah); ?>"></td>
</tr>
<tr>
	<td><b>Bangunan</b></td>
	<td><input type="text" name="luas_bangunan" id="luas_bangunan" size="5" value="<?php echo to_decimal($luas_bangunan); ?>"> M&sup2;</td>
	<td colspan="2" rowspan="2"></td>
	<td><input type="text" name="disc_bangunan" id="disc_bangunan" size="12" value="<?php echo to_decimal($disc_bangunan); ?>"> %</td>
	<td><input type="text" name="ppn_bangunan" id="ppn_bangunan" size="12" value="<?php echo to_decimal($ppn_bangunan); ?>"> %</td>
	<td rowspan="2">Rp. <input readonly="readonly" type="text" class="bold text-right" name = "harga_bangunan_total" id = "harga_bangunan_total"value="<?php echo to_money($harga_bangunan); ?>"></td>
</tr>
<tr>
	<td colspan="2" class="text-right">Rp. <input readonly="readonly" name = "harga_bangunan_tmp" id = "harga_bangunan_tmp" type="text" size="15" class="text-right" value="<?php echo to_money($base_harga_bangunan); ?>"></td>
	<td>Rp. <input readonly="readonly" name  = "harga_disc_bangunan" id ="harga_disc_bangunan" type="text" size="12" class="text-right" value="<?php echo to_money($disc_harga_bangunan); ?>"></td>
	<td>Rp. <input readonly="readonly" name  = "harga_ppn_bangunan" id ="harga_ppn_bangunan" type="text" size="12" class="text-right" value="<?php echo to_money($ppn_harga_bangunan); ?>"></td>
</tr>
</table>

<div class="clear"><br></div>
<div class="clear"><br></div>

<table class="t-popup">
<tr>
	<td>
		<input type="button" id="reserve" value=" reserve ">
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
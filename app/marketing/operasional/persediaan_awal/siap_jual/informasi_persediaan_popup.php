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
				parent.loadData2();	
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

function get_kode_sk_bangunan() {
	var id = jQuery('#kode_blok').val();
	var act='Kode Blok';
	var url = get_base + 'kode_sk_bangunan_setup.php?act=' + act + '&id=' + id; 
	setPopup('Daftar Harga SK', url, 750, winHeight-100); 
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
				<td><input type="text" name="kode_blok" id="kode_blok" size="25" value="<?php if(isset($kode_blok)){echo $kode_blok;}else{echo '-';} ?>" readonly></td>
			</tr>
			<tr>
				<td width="120"><b>Nomor VA</td><td>:</b></td>
				<td><input type="text" name="no_va" id="no_va" size="25" value="<?php if(isset($no_va)){echo $no_va;}else{echo '-';} ?>" readonly></td>
			</tr>
			<tr>
				<td>Tower</td><td>:</td>
				<td>
					<input type="hidden" name="kode_lokasi" id="kode_lokasi" size="1" value="<?php if(isset($kode_lokasi)){echo $kode_lokasi;}else{echo '-';} ?>" readonly>
					<input type="text" id="lokasi" size="25" value="<?php if(isset($lokasi)){echo $lokasi;}else{echo '-';} ?>" readonly>
				</td>
			</tr>
			<tr>
				<td>Jenis Unit</td><td>:</td>
				<td>
					<input type="hidden" name="kode_unit" id="kode_unit" size="1" value="<?php if(isset($kode_unit)){echo $kode_unit;}else{echo '-';} ?>">
					<input type="text" id="jenis_unit" size="25" value="<?php if(isset($jenis_unit)){echo $jenis_unit;}else{echo '-';} ?>" readonly>
				</td>
			</tr>

			<tr>
				<td>Tipe</td><td>:</td>
				<td>
					<input type="hidden" name="kode_tipe" id="kode_tipe" size="1" value="<?php if(isset($kode_tipe)){echo $kode_tipe;}else{echo '-';} ?>">
					<input type="text" id="tipe_bangunan" size="25" value="<?php if(isset($tipe_bangunan)){echo $tipe_bangunan;}else{echo '-';} ?>" readonly>
				</td>
			</tr>

			<tr>
				<td><b>Luas Semi Gross</b></td><td>:</td>
				<td><input type="text" name="luas_bangunan" id="luas_bangunan" size="5" value="<?php echo to_decimal($luas_bangunan); ?>" readonly> M&sup2;</td>
			</tr>

			<tr>
				<td>SK Harga</td><td>:</td>
				<td>
					<input type="text" name="kode_sk" id="kode_sk" size="10" value="<?php if(isset($kode_sk)){echo $kode_sk;}else{echo '-';} ?>" readonly>
					</td>
			</tr>
			<tr>
				<td>Cash Keras</td><td>:</td>
				<td>
					<input type="text" id="harga_cash_keras" class="text-right" size="15" value="<?php if(isset($harga_cash_keras)){echo to_money($harga_cash_keras);}else{echo '0';} ?>" readonly>
				</td>
			</tr>
			<tr>
				<td>CB36X</td><td>:</td>
				<td>
					<input type="text" id="harga_CB36X" class="text-right" size="15" value="<?php if(isset($CB36X)){echo to_money($CB36X);}else{echo '0';} ?>" readonly>
				</td>
			</tr>
			<tr>
				<td>CB48X</td><td>:</td>
				<td>
					<input type="text" id="harga_CB48X" class="text-right" size="15" value="<?php if(isset($CB48X)){echo to_money($CB48X);}else{echo '0';} ?>" readonly>
				</td>
			</tr>
			<tr>
				<td>KPA24X</td><td>:</td>
				<td>
					<input type="text" id="harga_KPA24X" class="text-right" size="15" value="<?php if(isset($KPA24X)){echo to_money($KPA24X);}else{echo '0';} ?>" readonly>
				</td>
			</tr>
			<tr>
				<td>KPA36X</td><td>:</td>
				<td>
					<input type="text" id="harga_KPA36X" class="text-right" size="15" value="<?php if(isset($KPA36X)){echo to_money($KPA36X);}else{echo '0';} ?>" readonly>
				</td>
			</tr>
			<tr>
				<td>Jenis Penjualan</td><td>:</td>
				<td>
					<input type="hidden" name="kode_penjualan" id="kode_penjualan" size="1" value="<?php if(isset($kode_penjualan)){echo $kode_penjualan;}else{echo '-';} ?>">
					<input type="text" id="jenis_penjualan" size="25" value="<?php if(isset($jenis_penjualan)){echo $jenis_penjualan;}else{echo '-';} ?>" readonly>
				</td>
			</tr>
		</table>


		<div class="clear"><br></div>
		<div class="clear"><br></div>

		<table class="t-popup">
			<tr>
				<td>
					<input type="button" id="reserve" value=" reserve ">
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
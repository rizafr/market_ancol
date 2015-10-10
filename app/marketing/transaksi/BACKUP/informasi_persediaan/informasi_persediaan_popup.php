<?php
require_once('informasi_persediaan_proses.php');
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
jQuery(function($) {
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#reserve').on('click', function(e) {
		e.preventDefault();		
		parent.showPopup2('Reserve', '<?php echo $id; ?>');
		return false;
	});
});
</script>
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup pad2 wauto f-left">
<tr>
	<td width="100">Kode Blok</td><td width="10">:</td>
	<td width=""><b><?php echo $kode_blok; ?></b></td>
</tr>
<tr>
	<td>Jenis Unit</td><td>:</td>
	<td><?php echo $jenis_unit; ?></td>
</tr>
<tr>
	<td>Desa</td><td>:</td>
	<td><?php echo $nama_desa; ?></td>
</tr>
<tr>
	<td>Lokasi</td><td>:</td>
	<td><?php echo $lokasi; ?></td>
</tr>
<tr>
	<td>SK. Tanah</td><td>:</td>
	<td>Rp. <?php echo to_money($harga_tanah_sk); ?> / M&sup2;</td>
</tr>
<tr>
	<td>Faktor Strategis</td><td>:</td>
	<td><?php echo $faktor_strategis; ?></td>
</tr>
<tr>
	<td>Tipe</td><td>:</td>
	<td><?php echo $tipe_bangunan; ?></td>
</tr>
<tr>
	<td>Jenis Penjualan</td><td>:</td>
	<td><?php echo $jenis_penjualan; ?></td>
</tr>
<tr>
	<td>SK. Bangunan</td><td>:</td>
	<td>Rp. <?php echo to_money($harga_bangunan_sk); ?> / M&sup2;</td>
</tr>
</table>

<table class="t-popup pad2 wauto f-right">
<tr>	
	<td width="100">Tgl. Bangunan</td><td width="10">:</td>
	<td><?php echo $tgl_bangunan; ?></td>
</tr>
<tr>
	<td>Tgl. Selesai</td><td>:</td>
	<td><?php echo $tgl_selesai; ?></td>
</tr>
<tr>
	<td>Progres</td><td>:</td>
	<td><?php echo to_decimal($progress); ?> %</td>
</tr>
<tr>
	<td>Class</td><td>:</td>
	<td><input type="radio" name="class" id="class_l" value="1" onclick="return false" <?php echo is_checked('1', $class); ?>> <label for="class_l">Low</label></td>
	<td><input type="radio" name="class" id="class_h" value="4" onclick="return false" <?php echo is_checked('4', $class); ?>> <label for="class_h">High</label></td>
</tr>
<tr>
	<td colspan="2"></td>
	<td><input type="radio" name="class" id="class_m" value="2" onclick="return false" <?php echo is_checked('2', $class); ?>> <label for="class_m">Middle</label></td>
	<td><input type="radio" name="class" id="class_lain" value="5" onclick="return false" <?php echo is_checked('5', $class); ?>> <label for="class_lain">Lain</label></td>
</tr>
<tr>
	<td colspan="2"></td>
	<td><input type="radio" name="class" id="class_mu" value="3" onclick="return false" <?php echo is_checked('3', $class); ?>> <label for="class_mu">Middle-Up</label></td>
</tr>
</table>

<div class="clear"><br></div>

<table class="t-popup pad2 w50 f-left">
<tr>
	<td width="120">Luas Tanah</td><td width="10">:</td>
	<td><?php echo to_decimal($luas_tanah); ?> M&sup2;</td>
	<td class="text-right">Rp. <?php echo to_money($base_harga_tanah); ?></td>
<tr>
<tr>
	<td>Discount Tanah</td><td>:</td>
	<td><?php echo to_decimal($disc_tanah); ?> %</td>
	<td class="text-right">Rp. <?php echo to_money($disc_harga_tanah); ?></td>
<tr>
<tr>
	<td>PPN Tanah</td><td>:</td>
	<td><?php echo to_decimal($ppn_tanah); ?> %</td>
	<td class="text-right">Rp. <?php echo to_money($ppn_harga_tanah); ?></td>
<tr>
<tr>
	<td>Luas Bangunan</td><td>:</td>
	<td><?php echo to_decimal($luas_tanah); ?> M&sup2;</td>
	<td class="text-right">Rp. <?php echo to_money($base_harga_bangunan); ?></td>
<tr>
<tr>
	<td>Discount Bangunan</td><td>:</td>
	<td><?php echo to_decimal($disc_tanah); ?> %</td>
	<td class="text-right">Rp. <?php echo to_money($disc_harga_bangunan); ?></td>
<tr>
<tr>
	<td>PPN Bangunan</td><td>:</td>
	<td><?php echo to_decimal($ppn_tanah); ?> %</td>
	<td class="text-right">Rp. <?php echo to_money($ppn_harga_bangunan); ?></td>
<tr>
</table>

<table class="t-popup pad2 wauto f-right">
<tr>	
	<td width="120">(+) <?php echo to_decimal($nilai_tambah); ?> % &nbsp;&nbsp; (-) <?php echo to_decimal($nilai_kurang); ?> %</td><td width="10">:</td>
	<td class="text-right">Rp. <?php echo to_money($fs_harga_tanah); ?></td>
</tr>
<tr><td><br></td></tr>
<tr>
	<td>Harga Tanah</td><td>:</td>
	<td class="text-right">Rp. <?php echo to_money($harga_tanah); ?></td>
</tr>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
<tr>
	<td>Harga Bangunan</td><td>:</td>
	<td class="text-right">Rp. <?php echo to_money($harga_bangunan); ?></td>
</tr>
<tr>
	<td colspan="3"><hr></td>
<tr>
<tr>
	<td><b>TOTAL HARGA</b></td><td width="10">:</td>
	<td class="text-right"><b>Rp. <?php echo to_money($harga_tanah + $harga_bangunan); ?></b></td>
<tr>
</table>

<div class="clear"><br></div>
<div class="clear"><br></div>

<table class="t-popup">
<tr>
	<td>
		<input type="button" id="reserve" value=" Reserve ">
		<input type="button" id="print" value=" Print ">
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
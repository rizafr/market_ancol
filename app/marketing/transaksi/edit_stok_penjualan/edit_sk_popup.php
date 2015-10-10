<?php
require_once('edit_stok_penjualan_proses.php');
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
	
	$('#kode_desa').inputmask('integer', { repeat: 3 });
	$('#nama_desa').inputmask('varchar', { repeat: 30 });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'edit_stok_penjualan_proses.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				if (result.act == 'Tambah') {
					$('#reset').click();
					parent.loadData();
				} else if (result.act == 'Ubah_SK') {
					parent.loadData();
				}
			}
		}, 'json');
		
		return false;
	});
});

function get_kode_sk_tanah() {
	var url = get_base + 'kode_sk_tanah.php'; 
	setPopup('Daftar SK Tanah', url, 500, winHeight-100); 
	return false; 
}

function get_kode_sk_bangunan() {
	var url = get_base + 'kode_sk_bangunan.php'; 
	setPopup('Daftar SK Bangunan', url, 500, winHeight-100); 
	return false; 
}
</script>
</head>
<body class="popup">
<form name="form" id="form" method="post">
<table>
<tr>
	<td>SK Sebelumnya</td><td>:</td>
	<td><input type="text" name="kode_sk_sebelumnya" id="kode_sk_sebelumnya"  readonly="readonly" size="20" value="<?php echo $id; ?>"></td>
</tr>
<tr>
	<td>SK Baru</td><td>:</td>
<?php
if($jenis == 'Tanah')
{
?>
	<td>
		<input type="text" name="kode_sk_tanah" id="kode_sk_tanah" size="10" value="<?php echo $kode_sk_tanah; ?>">
		<button onclick="return get_kode_sk_tanah()" name="btn_sk_tanah" id="btn_sk_tanah"> > </button>
	</td>

<?php
}
else
{
?>
	<td>
		<input type="text" name="kode_sk_bangunan" id="kode_sk_bangunan" size="10" value="<?php echo $kode_sk_bangunan; ?>">
		<button onclick="return get_kode_sk_bangunan()"  name="btn_sk_bangunan" id="btn_sk_bangunan"> > </button>
	</td>
<?php
}
?>
</tr>
<tr>
	<td colspan="3" class="td-action"><br>
		<input type="submit" id="simpan" value=" Ubah SK">
		<input type="button" id="tutup" value=" Tutup ">
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
<input type="hidden" name="jenis" id="jenis" value="<?php echo $jenis; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
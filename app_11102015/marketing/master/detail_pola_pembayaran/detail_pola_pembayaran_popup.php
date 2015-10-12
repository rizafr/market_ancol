<?php
require_once('detail_pola_pembayaran_proses.php');
?>

<!DOCTYPE html>
<html>
<body class="popup">
<form name="form" id="form" method="post">
<table>
<tr>
	<td>KODE BLOK</td>
	<td><input type="text" name="kode_blok" id="kode_blok" size="30" value="<?php if(!isset($kode_blok)){$kode_blok = '';} echo $kode_blok; ?>" <?php if($act=='Ubah'){echo 'readonly';}?>></td>
</tr>
	<?php
	$obj1 = $conn->execute("		
		SELECT * FROM POLA_BAYAR
		ORDER BY KODE_POLA_BAYAR
	");
	
	while(!$obj1->EOF)
	{
		$tp = $obj1->fields['KODE_JENIS'];
		$ov = $obj1->fields['KODE_POLA_BAYAR'];
		$oj = $obj1->fields['NAMA_POLA_BAYAR'];
		?>
		<tr>
			<td><?php echo $oj;?></td>
			<td><input type="text" name="harga_tanah_<?php echo $ov;?>" id="harga_tanah_<?php echo $ov;?>" class ="harga"	 placeholder = "Harga Tanah"> &nbsp;
			<input type="text" name="harga_bangunan_<?php echo $ov;?>" id="harga_bangunan_<?php echo $ov;?>" class ="harga"  placeholder = "Harga Bangunan"></td>
		</tr>
		<?php
		$obj1->movenext();
	}
	?>
<tr>
	<td colspan="3" class="td-action"><br>
		<input type="submit" id="simpan" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="tutup" value=" Tutup ">
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
<div  id="data">
</div>
</form>

</body>
</html>
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
var this_base = base_marketing + 'master/detail_pola_pembayaran/';

jQuery(function($) {

	$('.harga').inputmask('numericDesc', {iMax:10, dMax:16});
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});

	$('#kode_blok').on('change', function(e) {
		e.preventDefault();
		var kode_blok = jQuery('#kode_blok').val();
		$('#data').load("kode_cek.php?id="+kode_blok+"");
		return false;
	});

	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		if (confirm('Anda yakin akan menyimpan data ini !?') == false) {
			return false;
		}
		var url		= this_base + 'detail_pola_pembayaran_proses.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				if (result.act == 'Tambah') {
					$('#reset').click();
					parent.loadData();
				} else if (result.act == 'Ubah') {
					parent.loadData();
				}
			}
		}, 'json');
		
		return false;
	});

});
</script>
</head>

<?php close($conn); ?>
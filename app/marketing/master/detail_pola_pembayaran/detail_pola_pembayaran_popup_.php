<?php
require_once('detail_pola_pembayaran_proses.php');
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
var this_base = base_marketing + 'master/detail_pola_pembayaran/';

jQuery(function($) {
	
	jQuery('#harga_tanah').inputmask('decimal', { repeat: '18', decimal: '.',negative:false, scale: '2', groupSize:3, groupSeparator: ',',autoGroup: true});
	jQuery('#harga_bangunan').inputmask('decimal', { repeat: '18', decimal: '.',negative:false, scale: '2', groupSize:3, groupSeparator: ',',autoGroup: true});
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
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
<body class="popup">
<form name="form" id="form" method="post">
<table>
<tr>
	<td>KODE BLOK</td><td>:</td>
	<td><input type="text" name="kode_blok" id="kode_blok" size="30" value="<?php if(!isset($kode_blok)){$kode_blok = '';} echo $kode_blok; ?>" <?php if($act=='Ubah'){echo 'readonly';}?>></td>
</tr>
<tr>
	<td>POLA BAYAR</td><td>:</td>
	<td>
		<table>
			<select name="pola_bayar" id="pola_bayar">
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

				$check = '';
				if($ov==$pola_bayar){
					$check = 'selected';
				}
				echo "<option value = '$ov' $check>$oj</option>";
				$obj1->movenext();
			}
			?>
			</select>
		</table>
		<input type="hidden" name="sebelumnya" value="<?php echo $pola_bayar;?>">
	</td>
</tr>
<tr>
	<td>HARGA TANAH</td><td>:</td>
	<td><input type="text" name="harga_tanah" id="harga_tanah" size="30" value="<?php if(!isset($harga_tanah)){$harga_tanah = '';} echo $harga_tanah; ?>"></td>
</tr>
<tr>
	<td>HARGA BANGUNAN</td><td>:</td>
	<td><input type="text" name="harga_bangunan" id="harga_bangunan" size="30" value="<?php if(!isset($harga_bangunan)){$harga_bangunan = '';} echo $harga_bangunan; ?>"></td>
</tr>
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
</form>

</body>
</html>
<?php close($conn); ?>
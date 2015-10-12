<?php
require_once('informasi_persediaan_proses.php');
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
var base_ = base_marketing + 'operasional/persediaan_awal/';
jQuery(function($) {
	$('#nama_calon_pembeli').inputmask('varchar', { repeat: '30' });
	$('#telepon').inputmask('mask', { repeat: '15', mask : '9', groupSeparator : '', placeholder : '' });
	$('#no_npwp').inputmask('numeric', { repeat: '15', groupSeparator : '' });
	$('#no_ktp').inputmask('numeric', { repeat: '30', groupSeparator : '' });
	$('#alamat').inputmask('varchar', { repeat: '100' });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData2('Detail', '<?php echo $id; ?>');
		//parent.showPopup2('Detail', '<?php echo $id; ?>');
		return false;
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing_operasional + 'persediaan_awal/siap_jual/informasi_persediaan_proses.php',
			data	= $('#form').serialize();			
		
		$.post(url, data, function(data) {
			if(data.error == true)
			{
				alert(data.msg);				
			}
			else if (confirm("Apakah data telah terisi dengan benar ?") == false)
			{
				return false;
			}
			else if (data.act == 'Reserve')
			{
				alert(data.msg);
				var pembeli = jQuery('#nama_calon_pembeli').val();
				var alamat = jQuery('#alamat').val();
				var no_va = jQuery('#nama_calon_pembeli').val();
				var no_ktp = jQuery('#no_ktp').val();
				var no_npwp = jQuery('#no_npwp').val();
				var agen = jQuery('#agen').val();
				var telepon = jQuery('#telepon').val();
				var koordinator = jQuery('#koordinator').val();
				parent.showPopup7('Simpan', '<?php if(!isset($id)){$id='';} echo $id; ?>',pembeli,alamat,'<?php  if(!isset($no_va)){$no_va='';} echo $no_va; ?>',telepon,no_ktp,no_npwp,agen,koordinator);
			}
		}, 'json');
		
		return false;
	});	
});
</script>
</head>
<body class="popup2">
<form name="form" id="form" method="post">
<table class="t-popup wauto f-left">
<tr>
<td width="140">Kode Blok</td><td width="10">:</td>
	<td><input type="text" name="kode_blok" id="kode_blok" size="10" readonly="readonly" value="<?php echo $id; ?>"></td>
</tr>
<tr>
	<td>Nama Calon Pembeli</td></td><td>:</td>
	<td><input type="text" name="nama_calon_pembeli" id="nama_calon_pembeli" size="40" value=""></td>
</tr>
<tr>
	<td>No KTP</td><td width="10">:</td>
	<td><input type="text" name="no_ktp" id="no_ktp" value=""></td>
</tr>
<tr>
	<td>No NPWP</td><td width="10">:</td>
	<td><input type="text" name="no_npwp" id="no_npwp" value=""></td>
</tr>
<tr>
	<td>Tanggal Reserve</td><td>:</td>
	<td><input readonly="readonly" type="text" name="tanggal_reserve" id="tanggal_reserve" value="<?php echo $tanggal_reserve; ?>" size="10"></td>
</tr>
<tr>
	<td>Berlaku sampai dengan</td><td>:</td>
	<td><input readonly="readonly" type="text" name="berlaku_sampai" id="berlaku_sampai" value="<?php echo $berlaku_sampai; ?>" size="10"></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<!--<td><input type="text" name="alamat" id="alamat" size="40" value=""></td>-->
	<td><textarea name="alamat" id="alamat" rows="6" cols="50" placeholder="jumlah alamat 100 karakter"></textarea></td>
</tr>
<tr>
	<td>Telepon</td></td><td>:</td>
	<td><input type="text" name="telepon" id="telepon" size="20" value=""></td>
</tr>
<tr>
	<td>Agen</td></td><td>:</td>
	<td>
		<select name="agen" id="agen">
			<option value="-">-</option>
			<?php
				$query = "SELECT * FROM CLUB_PERSONAL WHERE JABATAN_KLUB = '5'";
				$koordinator = $conn->Execute($query);
				while (!$koordinator->EOF) {
					echo "<option value = '".$koordinator->fields['NOMOR_ID']."'>".$koordinator->fields['NAMA']."</option>";
					$koordinator->movenext();
				}
			?>	
		</select>

	</td>
</tr>
<tr>
	<td>Sales Koordinator</td></td><td>:</td>
	<td>
		<select name="koordinator" id="koordinator">
			<?php
				$query = "SELECT * FROM CLUB_PERSONAL WHERE JABATAN_KLUB = '4'";
				$koordinator = $conn->Execute($query);
				while (!$koordinator->EOF) {
					echo "<option value = '".$koordinator->fields['NOMOR_ID']."'>".$koordinator->fields['NAMA']."</option>";
					$koordinator->movenext();
				}
			?>	

		</select>
	</td>
</tr>
</table>
<table>
<tr>
	<td class="td-action" colspan="3"><br>
		<input type="button" id="save" value=" Simpan ">
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
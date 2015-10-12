<?php
require_once('reserve_persediaan_proses.php');
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
	$('#nama_calon_pembeli, #telepon, #agen, #koordinator').inputmask('varchar', { repeat: '30' });
	$('#alamat').inputmask('varchar', { repeat: '100' });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
		return false;
	});
	
	$('#spp').on('click', function(e) {
		e.preventDefault();
		parent.showPopup2('Tambah', '<?php echo $id; ?>','<?php echo $nama_calon_pembeli; ?>');
		return false;
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing_transaksi + 'reserve_persediaan/reserve_persediaan_proses.php',
			data	= $('#form').serialize();
			
		if (confirm("Apakah data telah terisi dengan benar ?") == false)
		{
			return false;
		}			
		
		$.post(url, data, function(data) {
			if (data.error == true)
			{
				alert(data.msg);				
			}
				else if (data.act == 'Ubah')
				{
					alert(data.msg);
					location.reload();
				}
		}, 'json');
		
		return false;
	});
	
	$('#hapus').on('click', function(e) {
		e.preventDefault();
		jQuery('#act').val('Hapus');
		
		var url		= base_marketing_transaksi + 'reserve_persediaan/reserve_persediaan_proses.php',
			data	= $('#form').serialize();
			
		if (confirm("Apakah data ini akan dihapus ?") == false)
		{
			jQuery('#act').val('Ubah');
			return false;
		}			
		
		$.post(url, data, function(data) {
			if (data.error == true)
			{
				alert(data.msg);				
			}
				else if (data.act == 'Hapus')
				{
					alert(data.msg);
					parent.loadData();
				}
		}, 'json');
		
		return false;
	});
	
	//loadData();
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
	<td><input type="text" name="nama_calon_pembeli" id="nama_calon_pembeli" size="40" value="<?php echo $nama_calon_pembeli; ?>"></td>
</tr>
<tr>
	<td>Tanggal Reserve</td><td>:</td>
	<td><input type="text" name="tanggal_reserve" id="tanggal_reserve" value="<?php echo $tanggal_reserve; ?>" class="apply dd-mm-yyyy" size="10"></td>
</tr>
<tr>
	<td>Berlaku sampai dengan</td><td>:</td>
	<td><input type="text" name="berlaku_sampai" id="berlaku_sampai" value="<?php echo $berlaku_sampai; ?>" class="apply dd-mm-yyyy" size="10"></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<td><input type="text" name="alamat" id="alamat" size="40" value="<?php echo $alamat; ?>"></td>
</tr>
<tr>
	<td>Telepon</td></td><td>:</td>
	<td><input type="text" name="telepon" id="telepon" size="20" value="<?php echo $telepon; ?>"></td>
</tr>
<tr>
	<td>Agen</td></td><td>:</td>
	<td>
	<select name="agen" id="agen">
		<option value=""> -- Agen -- </option>
		<?php
		$obj = $conn->execute("		
			SELECT * FROM CLUB_PERSONAL
			WHERE JABATAN_KLUB = 5
			ORDER BY NAMA 
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['NOMOR_ID'];
			$oj = $obj->fields['NAMA'];
			echo "<option value='$ov'".is_selected($ov, $agen)."> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	<td>
</tr>
<tr>
	<td>Sales Koordinator</td></td><td>:</td>
	<td>
	<select name="koordinator" id="koordinator">
		<option value=""> -- Koordinator -- </option>
		<?php
		$obj = $conn->execute("		
			SELECT * FROM CLUB_PERSONAL
			WHERE JABATAN_KLUB = 4
			ORDER BY NAMA 
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['NOMOR_ID'];
			$oj = $obj->fields['NAMA'];
			echo "<option value='$ov'".is_selected($ov, $koordinator)."> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	</td>
	</tr>
</table>
<table>
<tr>
	<td class="td-action"><br>
		<input type="button" id="spp" value=" SPP ">
		<input type="button" id="print" value=" Print ">
	</td>
</tr>
<tr>
	<td>
		<input type="button" id="save" value=" <?php echo $act; ?> ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="hapus" value=" Hapus ">
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
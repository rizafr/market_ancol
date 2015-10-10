<?php
require_once('reserve_persediaan_proses.php');
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
jQuery(function($) {
	$('#nama_calon_pembeli, #telepon').inputmask('varchar', { repeat: '30' });
	$('#alamat').inputmask('varchar', { repeat: '100' });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData3();
		return false;
	});

	//showPopup7 ada di folder persediaan_awal/persediaan_awal_setup
	$('#spp').on('click', function(e) {
		e.preventDefault();
		
		parent.showPopup7('Simpan', '<?php echo $id; ?>','<?php echo $nama_calon_pembeli; ?>','<?php echo $alamat; ?>','<?php echo $no_va; ?>','<?php echo $telepon; ?>','<?php echo $no_ktp; ?>','<?php echo $no_npwp; ?>','<?php echo $agen; ?>','<?php echo $koordinator; ?>');
		// parent.showPopup7('Simpan', '<?php echo $id; ?>');
		return false;
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing_operasional + 'persediaan_awal/reserve/reserve_persediaan_proses.php',
			data	= $('#form').serialize();
		
		if (confirm("Apakah anda yakin data ini akan diubah ?") == false)
		{
			jQuery('#act').val('Ubah');
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
		
		var url		= base_marketing_operasional + 'persediaan_awal/reserve/reserve_persediaan_proses.php',
			data	= $('#form').serialize();
			
		if (confirm("Apakah blok '<?php echo $id; ?>' ini akan dibatalkan reserve ?") == false)
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
					parent.loadData3();
				}
		}, 'json');
		
		return false;
	});
	
	$(document).on('click', '#print', function(e) {
			e.preventDefault();
				var cb_data = $('#cb_data').val();
				var act = 'Surat';
				
			 if (confirm('Apa anda yakin akan mencetak surat untuk data ini?')) {
				e.preventDefault();
				location.href = base_marketing_operasional + 'persediaan_awal/reserve/surat_reserve_persediaan_popup.php' + '?act=' + act + '&cb_data=' + cb_data;
				// location.href = base_marketing_operasional + 'persediaan_awal/reserve/surat_reserve_persediaan.php?' + $('#form').serialize();
				//cetakSurat();
			}
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
	<td><input type="text" name="kode_blok" id="kode_blok" size="10" readonly value="<?php echo $id; ?>"></td>
	<td><input type="hidden" name="cb_data[]" id="cb_data" size="10"  value="<?php echo $id; ?>"></td>
</tr>
<tr>
	<td>Nama Calon Pembeli</td></td><td>:</td>
	<td><input type="text" name="nama_calon_pembeli" id="nama_calon_pembeli" size="40" value="<?php echo $nama_calon_pembeli; ?>"></td>
</tr>
<tr>
	<td>No KTP</td><td width="10">:</td>
	<td><input type="text" name="no_ktp" id="no_ktp" value="<?php if(!isset($no_ktp)){echo '-';}else{echo $no_ktp;}?>"></td>
</tr>
<tr>
	<td>No NPWP</td><td width="10">:</td>
	<td><input type="text" name="no_npwp" id="no_npwp" value="<?php if(!isset($no_npwp)){echo '-';}else{echo $no_npwp;}?>"></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<!--<td><input type="text" name="alamat" id="alamat" size="40" value="<?php echo $alamat; ?>"></td>-->
	<td><textarea name="alamat" id="alamat" rows="6" cols="50"><?php echo $alamat; ?></textarea></td>
</tr>
<tr>
	<td>Tanggal Reserve</td><td>:</td>
	<td><input readonly="readonly" type="text" name="tanggal_reserve" id="tanggal_reserve" value="<?php echo $tanggal_reserve; ?>" size="10"></td>
</tr>
<tr>
	<td>Berlaku sampai dengan</td><td>:</td>
	<td><input readonly="readonly" type="text" name="berlaku_sampai" id="berlaku_sampai" value="<?php echo $berlaku_sampai; ?>"  size="10"></td>
</tr>

<tr>
	<td>Telepon</td></td><td>:</td>
	<td><input type="text" name="telepon" id="telepon" size="20" value="<?php echo $telepon; ?>"></td>
</tr>
<tr>
	<td>Agen</td></td><td>:</td>
	<td>
		<select name="agen" id="agen">
			<option value="-">-</option>
			<?php
				$query = "SELECT * FROM CLUB_PERSONAL WHERE JABATAN_KLUB = '5'";
				$koor = $conn->Execute($query);
				$select = '';
				while (!$koor->EOF) {
					if($koor->fields['NAMA']==$agen){
						$select = 'selected';
					}
					else{
						$select = '';
					}
					echo "<option value = '".$koor->fields['NAMA']."' $select>".$koor->fields['NAMA']."</option>";
					$koor->movenext();
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
				$koor = $conn->Execute($query);
				$select ='';
				while (!$koor->EOF) {
					if($koor->fields['NAMA']==$agen){
						$select = 'selected';
					}
					else{
						$select = '';
					}
					echo "<option value = '".$koor->fields['NAMA']."'>".$koor->fields['NAMA']."</option>";
					$koor->movenext();
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
		<input type="button" id="hapus" value=" Batal Reserve ">
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
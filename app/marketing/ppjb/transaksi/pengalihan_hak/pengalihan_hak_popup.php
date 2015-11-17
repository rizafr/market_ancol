<?php
require_once('pengalihan_hak_proses.php');
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
<script type="text/javascript" src="../../../../../config/js/main.js"></script>

<script type="text/javascript" src="../../../../../plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="../../../../../plugin/window/javascripts/window.js"></script>
<script type="text/javascript">
jQuery(function($) {
	if ('<?php echo $act; ?>' == 'Ubah') {
		$('#print').show();	
		$('#cari').hide();	
		
	}
	else {
		$('#print').hide();
		$('#histori').hide();	
	}

	$('#no_ppjb_hak, #no_id_hak').inputmask('varchar', { repeat: '30' });
	$('#harga_hak, #biaya, #harga_awal').inputmask('numeric', { repeat: '16' });
	$('#masa_bangun').inputmask('numeric', { repeat: '2' });
	$('#keterangan, #pihak_kedua, #suami_istri_hak').inputmask('varchar', { repeat: '60' });
	$('#alamat_hak, #email').inputmask('varchar', { repeat: '100' });

	//$('#tlp1_hak, #tlp3_hak, #no_fax_hak').inputmask('mask', { repeat: '15', mask : '15', groupSeparator : '', placeholder : '' });
	
	$(document).on('click', '#histori', function(e) {
		e.preventDefault();
		showPopup('Histori Pengalihan Hak');
		return false;
	});
	
	function showPopup(act)
	{
		kode = $('#kode').val();
		var url =	base_marketing + 'ppjb/transaksi/pengalihan_hak/pengalihan_hak_histori_setup.php' + '?kode=' + kode;	
		setPopup('Pengalihan Hak Histori', url, 800, 540);
		return false;
	}
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	//ADD PRINT
	$('#print').on('click', function(e) {
		e.preventDefault();	
		if (confirm("Apakah data ini akan dicetak ?") == false)
		{
			return false;
		}	
		var no_ppjb_hak = $('#no_ppjb_hak').val();
		window.open(base_marketing + 'ppjb/transaksi/pengalihan_hak/pengalihan_cetak.php?id=<?php echo $id; ?>&act=Ubah&no_ppjb_hak='+no_ppjb_hak);		
		return false;
	});
	// END PRINT
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'ppjb/transaksi/pengalihan_hak/pengalihan_hak_proses.php',
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
				else if (data.act == 'Pengalihan Hak')
				{
					alert(data.msg);
					parent.loadData();
				}
				else if (data.act == 'Ubah')
				{
					alert(data.msg);
					parent.loadData();
				}
		}, 'json');		
		return false;
	});
});
function daftar_ppjb() {
	var url = base_marketing + 'ppjb/transaksi/pengalihan_hak/pengalihan_daftar_setup.php'; 
	setPopup('Daftar PPJB', url, 700, 500); 
	return false; 
}
</script>
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup w45 f-left">
<tr>
	<td colspan = 3><b>PPJB Awal</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
<tr id="tr-blok1">
	<td width="100">Blok / Nomor</td><td>:</td>
	<td><input readonly="readonly" type="text" name="kode" id="kode" size="15" value="<?php echo $kode; ?>"> <button id="cari" onclick="return daftar_ppjb()"> Cari </button></td>
</tr>
<tr>
	<td>Nomor VA</td><td>:</td>
	<td><input readonly="readonly" type="text" name="no_va" id="no_va" size="20" value="<?php echo $no_va; ?>"></td>
</tr>
<tr>
	<td>No. PPJB</td><td>:</td>
	<td><input readonly="readonly" type="text" name="no_ppjb_awal" id="no_ppjb_awal" size="20" value="<?php echo $nomor; ?>"></td>
</tr>
<tr>
	<td>Tanggal</td><td>:</td>
	<td><input readonly="readonly" type="text" name="tanggal_awal" id="tanggal_awal" size="15" value="<?php echo $tanggal_awal; ?>"></td>
</tr>
<tr>
	<td>Harga</td><td>:</td>
	<td>Rp. <input readonly="readonly" type="text" name="harga_awal" id="harga_awal" size="20" value="<?php echo to_money($harga_awal); ?>"></td>
</tr>
</table>

<table class="t-popup w45 f-left" style="margin-left:30px">
<tr>
	<td colspan = 3><b>PPJB Pengalihan Hak</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
</tr>
	<td width="130">No. PPJB</td><td>:</td>
	<td><input type="text" name="no_ppjb_hak" id="no_ppjb_hak" size="20" value="<?php echo $no_ppjb_hak; ?>" readonly></td>
</tr>

</tr>
	<td width="130">No. VA </td><td>:</td>
	<td><input type="text" name="no_va_ph" id="no_va_ph" size="20" value="<?php echo $no_va_ph; ?>" readonly></td>
</tr>
<tr>
	<td>Tanggal</td><td>:</td>
	<td><input type="text" name="tanggal" id="tanggal" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal; ?>"></td>
</tr>
<tr>
	<td>Tanggal Permohonan</td><td>:</td>
	<td><input type="text" name="tanggal_permohonan" id="tanggal_permohonan" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal_permohonan; ?>"></td>
</tr>
<tr>
	<td>Tanggal Persetujuan</td><td>:</td>
	<td><input type="text" name="tanggal_persetujuan" id="tanggal_persetujuan" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal_persetujuan; ?>"></td>
</tr>
<tr>
	<td>Harga</td><td>:</td>
	<td>Rp. <input type="text" name="harga_hak" id="harga_hak" size="20" value="<?php echo to_money($harga_hak); ?>"></td>
</tr>
	<td>Biaya</td><td>:</td>
	<td>Rp. <input type="text" name="biaya" id="biaya" size="20" value="<?php echo to_money($biaya); ?>"></td>
</tr>
<tr>
	<td>Masa Bangun</td><td>:</td>
	<td><input type="text" name="masa_bangun" id="masa_bangun" size="1" value="<?php echo $masa_bangun; ?>"> Bulan</td>
</tr>
<tr>
	<td>Keterangan</td><td>:</td>
	<td><input type="text" name="keterangan" id="keterangan" size="40" value="<?php echo $keterangan; ?>"></td>
</tr>
</table>

<div class="clear"><br></div>
<div class="clear"><br></div>

<table class="t-popup w45 f-left" style="margin-right:35px">
<tr>
	<td colspan = 3><b>Pihak Pertama</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td width="130">Nama Pembeli</td></td><td>:</td>
	<td><input readonly="readonly" type="text" name="pihak_pertama" id="pihak_pertama" size="35" value="<?php echo $pihak_pertama; ?>"></td>
</tr>
<tr>
	<td>No. Identitas</td></td><td>:</td>
	<td><input readonly="readonly" type="text" name="no_id" id="no_id" size="20" value="<?php echo $no_id; ?>"></td>
</tr>

<tr>
	<td>Alamat</td></td><td>:</td>
	<td><textarea readonly="readonly" name="alamat" id="alamat" rows="3" cols="50"><?php echo $alamat; ?></textarea></td>
</tr>
<tr>
	<td>No. Telp</td></td><td>:</td>
	<td><input readonly="readonly" type="text" name="tlp1" id="tlp1" size="15" value="<?php echo $tlp1; ?>"></td>
</tr>
<tr>
	<td>No. HP</td></td><td>:</td>
	<td><input readonly="readonly" type="text" name="tlp3" id="tlp3" size="15" value="<?php echo $tlp3; ?>"></td>
</tr>
<tr>
	<td>Email</td></td><td>:</td>
	<td><input readonly="readonly" type="text" name="email" id="email" size="30" value="<?php echo $email; ?>"></td>
</tr>
<tr>
	<td>Nama Suami / Istri</td></td><td>:</td>
	<td><input readonly="readonly" type="text" name="suami_istri" id="suami_istri" size="35" value="<?php echo $suami_istri; ?>"></td>
</tr>
<tr>
	<td>No. Fax</td></td><td>:</td>
	<td><input readonly="readonly" type="text" name="no_fax" id="no_fax" size="15" value="<?php echo $no_fax; ?>"></td>
</tr>
</table>

<table class="t-popup w45 f-left">
<tr>
	<td colspan = 3><b>Pihak Kedua</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td width="100">Nama Pembeli</td></td><td>:</td>
	<td><input type="text" name="pihak_kedua" id="pihak_kedua" size="35" value="<?php echo $pihak_kedua; ?>"></td>
</tr>
<tr>
	<td>NPWP</td></td><td>:</td>
	<td><input type="text" name="npwp" id="npwp" size="20" value="<?php echo $npwp; ?>"></td>
</tr>
<tr>
	<td>No. Identitas</td></td><td>:</td>
	<td><input type="text" name="no_id_hak" id="no_id_hak" size="20" value="<?php echo $no_id_hak; ?>"></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<td><textarea name="alamat_hak" id="alamat_hak" rows="3" cols="50"><?php echo $alamat_hak; ?></textarea></td>
</tr>
<tr>
	<td>No. Telp.</td></td><td>:</td>
	<td><input type="text" name="tlp1_hak" id="tlp1_hak" size="15" value="<?php echo $tlp1_hak; ?>"></td>
</tr>
<tr>
	<td>No. HP</td></td><td>:</td>
	<td><input type="text" name="tlp3_hak" id="tlp3_hak" size="15" value="<?php echo $tlp3_hak; ?>"></td>
</tr>
<tr>
	<td>Email</td></td><td>:</td>
	<td><input type="text" name="email_hak" id="email_hak" size="30" value="<?php echo $email_hak; ?>"></td>
</tr>
<tr>
	<td>Nama Suami / Istri</td></td><td>:</td>
	<td><input type="text" name="suami_istri_hak" id="suami_istri_hak" size="35" value="<?php echo $suami_istri_hak; ?>"></td>
</tr>
<tr>
	<td>No. Fax</td></td><td>:</td>
	<td><input type="text" name="no_fax_hak" id="no_fax_hak" size="15" value="<?php echo $no_fax_hak; ?>"></td>
</tr>
<table>
<tr>
<td class="td-action">
	<input type="submit" id="save" value=" <?php echo $act; ?> ">
	<input type="reset" id="print" value=" Print ">
	<input type="reset" id="reset" value=" Reset ">
	<input type="button" id="close" value=" Tutup ">
	<input type="button" id="histori" value=" Histori ">
</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
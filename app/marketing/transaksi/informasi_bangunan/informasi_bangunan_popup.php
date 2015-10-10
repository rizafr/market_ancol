<?php
require_once('informasi_bangunan_proses.php');
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
var this_base = base_marketing + 'transaksi/informasi_bangunan/';

jQuery(function($) {
	
	
	$('#nomor_memo').inputmask('numeric', { repeat: 3 });
	$('#nomor_spk').inputmask('varchar', { repeat: 30 });

	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'informasi_bangunan_proses.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				if (result.act == 'Tambah') {
					$('#reset').click();
				} else if (result.act == 'Ubah') {
					parent.loadData();
				}else if (result.act == 'Tambah') {
					parent.loadData();
				}
			}
		}, 'json');
		
		return false;
	});

	$('#delete').on('click', function(e) {
		e.preventDefault();
		if (confirm('Anda yakin akan hapus data ini !?') == false) {
			return false;
		}
		var url		= this_base + 'agen_proses_delete.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				parent.loadData();
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
	<td><input type="text" readonly name="kode_blok" id="kode_blok" size="40" value="<?php if(!isset($kode_blok)){$kode_blok='';} echo $kode_blok; ?>"></td>
</tr>
<tr>
	<td>TIPE</td><td>:</td>
	<td><input type="text" name="kode_tipe" id="kode_tipe" size="40" value="<?php if(!isset($kode_tipe)){$kode_tipe='';} echo $kode_tipe; ?>" readonly ></td>
</tr>
<tr>
	<td>TANGGAL MEMO</td><td>:</td>
	<td><input type="text" name="tgl_memo" id="tanggal_memo" class="apply dd-mm-yyyy" value="<?php echo $tanggal_memo; ?>" size="20"></td>
</tr>
<tr>
	<td>NOMOR MEMO</td><td>:</td>
	<td><input type="text" name="no_memo" id="nomor_memo" size="20" value="<?php if(!isset($nomor_memo)){$nomor_memo='';} echo $nomor_memo; ?>"></td>
</tr>
<tr>
	<td>NOMOR SPK</td><td>:</td>
	<td><input type="text" name="no_spk" id="nomor_spk" size="20" value="<?php if(!isset($nomor_spk)){$nomor_spk='';} echo $nomor_spk; ?>"></td>
</tr>
<tr>
	<td><b>PLN/LISTRIK</b></td>
</tr>
<tr>
	<td>SLO</td><td>:</td>
	<td><input type="text" name="kwh_slo" id="kwh_slo" size="20" value="<?php if(!isset($kwh_slo)){$kwh_slo='';} echo $kwh_slo; ?>"></td>
</tr>
<tr>
	<td>NO Kontrak</td><td>:</td>
	<td><input type="text" name="kwh_no_kontrak" id="kwh_no_kontrak" size="20" value="<?php if(!isset($kwh_no_kontrak)){$kwh_no_kontrak='';} echo $kwh_no_kontrak; ?>"></td>
</tr>
<tr>
	<td>NO PELANGGAN</td><td>:</td>
	<td><input type="text" name="kwh_no_pelanggan" id="kwh_no_pelanggan" size="20" value="<?php if(!isset($kwh_no_pelanggan)){$kwh_no_pelanggan='';} echo $kwh_no_pelanggan; ?>"></td>
</tr>
<tr>
	<td>NO TELEPON TERPASANG</td><td>:</td>
	<td><input type="text" name="no_tlp_terpasang" id="no_tlp_terpasang" size="20" value="<?php if(!isset($no_tlp_terpasang)){$no_tlp_terpasang='';} echo $no_tlp_terpasang; ?>"></td>
</tr>
<tr>
	<td>PAM TERPASANG</td><td>:</td>
	<td><input type="text" name="pam_terpasang" id="pam_terpasang" value="<?php echo $pam_terpasang; ?>" size="20" class="apply dd-mm-yyyy"></td>
</tr>
<tr>
	<td>TANGGAL SERAH KONTRAKTOR</td><td>:</td>
	<td><input  type="text" name="tgl_serah_kontraktor" id="tgl_serah_kontraktor" value="<?php echo $tgl_serah_kontraktor; ?>" size="20" class="apply dd-mm-yyyy"></td>
</tr>
<tr>
	<td>TANGGAL SERAH PROYEK</td><td>:</td>
	<td><input type="text" name="tgl_serah_proyek" id="tgl_serah_proyek" value="<?php echo $tgl_serah_proyek; ?>" size="20" class="apply dd-mm-yyyy"></td>
</tr>
<tr>
	<td><b>JADWAL PELAKSANAAN</b></td>
</tr>
<tr>
	<td>MULAI</td><td>:</td>
	<td><input type="text" name="jadwal_mulai" id="jadwal_mulai" value="<?php echo $jadwal_mulai; ?>" size="20" class="apply dd-mm-yyyy"></td>
</tr>
<tr>
	<td>SELESAI</td><td>:</td>
	<td><input type="text" name="jadwal_selesai" id="jadwal_selesai" value="<?php echo $jadwal_selesai; ?>" size="20" class="apply dd-mm-yyyy"></td>
</tr>
<tr>
	<td>RENCANA</td><td>:</td>
	<td><input type="text" name="rencana" id="rencana" size="20" value="<?php if(!isset($rencana)){$rencana='';} echo $rencana; ?>"></td>
</tr>
<tr>
	<td>REALISASI</td><td>:</td>
	<td><input type="text" name="realisasi" id="realisasi" size="20" value="<?php if(!isset($realisasi)){$realisasi='';} echo $realisasi; ?>"></td>
</tr>
<tr>
	<td> <input type="checkbox" name="siap_checklist_purnajual" id="siap_checklist_purnajual"> SIAP </td>
</tr>
<tr>
	<td> <input type="checkbox" name="checklist_purnajual" id="checklist_purnajual"> CEKLIS PURNA JUAL </td>
	<td>:</td>
	<td>TANGGAL <input type="text" name="checklist_purnajual_tanggal" id="checklist_purnajual_tanggal" value="<?php echo $checklist_purnajual_tanggal; ?>" size="20" class="apply dd-mm-yyyy"></td>
</tr>
<tr>
	<td>TANGGAL SELESAI PROYEK</td><td>:</td>
	<td><input type="text" name="tanggal_selesai_proyek" id="tanggal_selesai_proyek" value="<?php echo $tanggal_selesai_proyek; ?>" size="20" class="apply dd-mm-yyyy"></td>
</tr>
<tr>
	<td>TANGGAL SERAH TERIMA PEMILIK<td>:</td>
	<td><input type="text" name="tgl_serah_terima" id="tgl_serah_terima" value="<?php echo $tgl_serah_terima; ?>" size="20" class="apply dd-mm-yyyy"></td>
</tr>
<tr>
	<td>KETERANGAN</td><td>:</td>
	<td><input  type="text" name="keterangan" id="keterangan" value="<?php echo $keterangan; ?>" size="20"></td>
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
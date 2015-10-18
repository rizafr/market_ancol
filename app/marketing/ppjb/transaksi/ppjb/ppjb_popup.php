<?php
require_once('ppjb_proses.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->
<link type="text/css" href="../../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../../plugin/css/zebra/default.css" rel="stylesheet">

<link type="text/css" href="../../../../../plugin/window/themes/default.css" rel="stylesheet">
<link type="text/css" href="../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">

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
	var tanggal_ver;
	tanggal_ver = $('#tanggal_ver').val();
	
	//disable jika sudah diverifikasi
	//if(tanggal_ver!=''){
		// readonly select
		//$("select :selected").each(function(){
		//	$(this).parent().data("default", this);
		//});

		//$("select").change(function(e) {
		//	$($(this).data("default")).prop("selected", true);
		//});
		
		
		//$('#tanggal').attr('readonly', 'readonly');
		//$('#pembangunan').attr('readonly', 'readonly');
		//$('#prosentase').attr('readonly', 'readonly');
		//$('#daya_listrik').attr('readonly', 'readonly');
		//$('#jenis_ppjb').attr('readonly', 'readonly');
		//$('#addendum').attr('readonly', 'readonly');
		//$('#kelurahan').attr('readonly', 'readonly');
		//$('#kecamatan').attr('readonly', 'readonly');
		//$('#catatan').attr('readonly', 'readonly');
		//$('#tgl1').attr('readonly', 'readonly');
		//$('#tgl2').attr('readonly', 'readonly');
		//$('#tgl3').attr('readonly', 'readonly');
		//$('#tgl4').attr('readonly', 'readonly');
		//$('#tercetak_belum').attr('readonly', 'readonly');
		//$('#tercetak_sudah').attr('readonly', 'readonly');
		//$('#no_arsip').attr('readonly', 'readonly');
	//}
	
	
	if (<?php echo $jml; ?> == 0) {
		if (confirm("Data PPJB tidak ditemukan ! Proses dilanjutkan ?") == false)
		{
			return parent.loadData();
		}	
		jQuery('#act').val('Tambah');
		jQuery('#save').val('Tambah');
		jQuery('#reset, #delete, #ttd, #ppjb, #addendum2, #lampiran, #alamat, #spp').hide();
	}

	$('#tipe_bangunan').inputmask('varchar', { repeat: '30' });
	$('#daya_listrik').inputmask('numeric', { repeat: '4' });
	$('#no_arsip').inputmask('varchar', { repeat: '15' });

	$('#close').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'ppjb/transaksi/ppjb/ppjb_proses.php',
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
					$('#reset').click();
					parent.loadData();
				}
				else if (data.act == 'Tambah')
				{
					alert(data.msg);
					parent.loadData();
				}
		}, 'json');		
		return false;
	});
	
	$('#delete').on('click', function(e) {
		e.preventDefault();
		jQuery('#act').val('Hapus');
		
		var url		= base_marketing + 'ppjb/transaksi/ppjb/ppjb_proses.php',
			data	= $('#form').serialize();
			
		if (confirm("Data akan dihapus permanen. Jangan digunakan untuk pembatalan ppjb! Proses hapus akan dilanjutkan ?") == false)
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
	
	$('#ttd').on('click', function(e) {
		e.preventDefault();		
		var url = base_marketing + 'ppjb/transaksi/ppjb/ttd.php?id=<?php echo $id; ?>&act=Ttd';		
		setPopup('Penandatangan PPJB', url, 450, 200);
		return false;
	});
	
	$('#ppjb').on('click', function(e) {
		e.preventDefault();		
		var kode_jenis_ppjb = $('#kode_jenis_ppjb').val();
		var jenis_ppjb = $('#jenis_ppjb').val();
		window.open(base_marketing + 'ppjb/transaksi/ppjb/ppjb_cetak.php?id=<?php echo $id; ?>&act=Ubah&kode_jenis_ppjb='+kode_jenis_ppjb+'&jenis_ppjb='+jenis_ppjb);		
		return false;
	});
	//addendum
	var addendum = $('#addendum').val();
	if(addendum == ''){	
		$('#addendum2').hide();
	}
	
	// $('#addendum').on('change', function(e) {
	// var addendum = $('#addendum').val();
	//alert(addendum);
		// if(addendum == ''){	
			// $('#addendum2').hide();
		// }else{
			// $('#addendum2').show();
		// }
	// });
	
	
		$('#addendum2').on('click', function(e) {
			e.preventDefault();		
			var kode_jenis_addendum = $('#kode_jenis_addendum').val();
			var jenis_ppjb = $('#jenis_ppjb').val();
			window.open(base_marketing + 'ppjb/transaksi/ppjb/addendum_cetak.php?id=<?php echo $id; ?>&act=Ubah&kode_jenis_addendum='+kode_jenis_addendum);		
			return false;
		});
	
	
	$('#alamat').on('click', function(e) {
		e.preventDefault();		
		window.open(base_marketing + 'ppjb/transaksi/ppjb/alamat_cetak.php?id=<?php echo $id; ?>&act=Ubah');		
		return false;
	});
	
	$('#lampiran').on('click', function(e) {
		e.preventDefault();		
		window.open(base_marketing + 'ppjb/transaksi/ppjb/lampiran.php?id=<?php echo base64_encode($id); ?>');		
		return false;
	});
	
	$('#spp').on('click', function(e) {
		e.preventDefault();		
		window.open(base_marketing + 'ppjb/transaksi/ppjb/spp.php?id=<?php echo base64_encode($id); ?>');		
		return false;
	});
});
</script>
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup w50 f-left" style="margin-right:35px">
<tr>
	<td colspan = 3><b>DATA PEMBELI</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td width="100">Blok / Nomor</td><td>:</td>
	<td><?php echo $kode_blok; ?></td>
</tr>
<tr>
	<td>Nama Pembeli</td></td><td>:</td>
	<td><?php echo $nama_pembeli; ?><input type="hidden" name="nama_pembeli" id="nama_pembeli" value="<?php echo $nama_pembeli; ?>"></td>
</tr>
<tr>
	<td>No. Kartu</td></td><td>:</td>
	<td><?php echo $no_kartu; ?></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<td><?php echo $alamat; ?></td>
</tr>
<tr>
	<td>Telepon 1</td></td><td>:</td>
	<td><?php echo $tlp1; ?></td>
</tr>
<tr>
	<td>Telepon 2</td></td><td>:</td>
	<td><?php echo $tlp2; ?></td>
</tr>
<tr>
	<td>Telepon 3</td></td><td>:</td>
	<td><?php echo $tlp3; ?></td>
</tr>
</table>

<table class="t-popup wauto f-right">
<tr>
	<td colspan = 3><b>DATA SPP</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td width="130">Tanggal SPP</td></td><td>:</td>
	<td><?php echo $tanggal_spp; ?></td>
</tr>
<tr>
	<td>Sistem Pembayaran</td></td><td>:</td>
	<td><?php echo $sistem_pembayaran; ?></td>
</tr>
<tr>
	<td>Tipe Bangunan</td></td><td>:</td>
	<td><?php echo $tipe_bangunan; ?></td>
</tr>

<tr>
	<td>Luas Semi Gross</td></td><td>:</td>
	<td><?php echo $luas_bangunan.' m&sup2;'; ?></td>
</tr>
<tr>
	<td>Total Harga</td></td><td>:</td>
	<td><?php echo 'Rp. '.to_money($total_harga); ?></td>
</tr>
<tr>
	<td>Nilai Tanda Jadi</td></td><td>:</td>
	<td><?php echo 'Rp. '.$nilai_tanda_jadi; ?></td>
</tr>
</table>

<div class="clear"><br></div>
<div class="clear"><br></div>

<table class="t-popup w50 f-left">
<tr>
	<td colspan = 3><b>DATA PPJB</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td width="130">Nomor</td><td>:</td>
	<td><input type="text" readonly name="nomor" id="nomor" size="20" value="<?php echo $nomor; ?>"></td>
</tr>
<tr>
	<td>Tanggal</td><td>:</td>
	<td><input type="text" name="tanggal" id="tanggal" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal; ?>"></td>
</tr>
<tr>
	<td>Pembangunan</td><td>:</td>
	<td>
	<select name="pembangunan" id="pembangunan">
		<option value="0" <?php echo is_selected('0', $pembangunan1); ?>> 0 </option>
		<option value="1" <?php echo is_selected('1', $pembangunan1); ?>> 6 </option>
		<option value="2" <?php echo is_selected('2', $pembangunan1); ?>> 9 </option>
		<option value="3" <?php echo is_selected('3', $pembangunan1); ?>> 12 </option>
		<option value="9" <?php echo is_selected('9', $pembangunan1); ?>> 14 </option>
		<option value="4" <?php echo is_selected('4', $pembangunan1); ?>> 15 </option>
		<option value="10" <?php echo is_selected('10', $pembangunan1); ?>> 17 </option>
		<option value="5" <?php echo is_selected('5', $pembangunan1); ?>> 18 </option>
		<option value="6" <?php echo is_selected('6', $pembangunan1); ?>> 20 </option>
		<option value="7" <?php echo is_selected('7', $pembangunan1); ?>> 21 </option>
		<option value="8" <?php echo is_selected('8', $pembangunan1); ?>> 24 </option>
	</select> Bulan
	</td>
</tr>
<tr>
	<td>Prosentase P. Hak</td><td>:</td>
	<td>
	<select name="prosentase" id="prosentase">
		<option value="0" <?php echo is_selected('0', $prosentase1); ?>> 0% </option>
		<option value="1" <?php echo is_selected('1', $prosentase1); ?>> 5% </option>
		<option value="2" <?php echo is_selected('2', $prosentase1); ?>> 7.5% </option>
		<option value="3" <?php echo is_selected('3', $prosentase1); ?>> 10% </option>
		<option value="4" <?php echo is_selected('4', $prosentase1); ?>> 12.5% </option>
		<option value="5" <?php echo is_selected('5', $prosentase1); ?>> 15% </option>
		<option value="6" <?php echo is_selected('6', $prosentase1); ?>> 17.5% </option>
		<option value="7" <?php echo is_selected('7', $prosentase1); ?>> 20% </option>
	</select>
	</td>
</tr>
<tr>
	<td>Daya Listrik</td><td>:</td>
	<td><input type="text" name="daya_listrik" id="daya_listrik" size="4" value="<?php echo $daya_listrik; ?>"> Watt</td>
</tr>
<tr>
	<td>Jenis PPJB</td><td>: <input type="hidden" id="kode_jenis_ppjb" value="<?php echo $jenis_ppjb; ?>"></td>
	<td>
	<select name="jenis_ppjb" id="jenis_ppjb">
		<option value=""> -- Jenis PPJB -- </option>
		<?php
		$obj = $conn->execute("
		SELECT *
		FROM 
			CS_JENIS_PPJB
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['KODE_JENIS'];
			$on = $obj->fields['NAMA_JENIS'];
			echo "<option value='$ov'" . is_selected($ov, $jenis_ppjb) . "> $on ($ov) </option>";
			$obj->movenext();
		}
	?>
	</select>
	</td>
</tr>
<tr>
	<td>Addendum</td><td><input type="hidden" id="kode_jenis_addendum" value="<?php echo $addendum; ?>">:</td>
	<td>
	<select name="addendum" id="addendum">
		<option value=""> -- Addendum -- </option>
		<?php
		$obj = $conn->execute("
		SELECT *
		FROM 
			CS_JENIS_PPJB_ADDENDUM
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['KODE_JENIS'];
			$on = $obj->fields['NAMA_JENIS'];
			echo "<option value='$ov'" . is_selected($ov, $addendum) . "> $on </option>";
			$obj->movenext();
		}
	?>
	</select>
	</td>
</tr>
<tr>
	<td>Catatan</td><td>:</td>
</tr>
<tr>
	<td colspan=3><textarea name="catatan" id="catatan" rows="3" cols="75"><?php echo $catatan; ?></textarea></td>
</tr>
</table>

<table class="t-popup wauto f-right" style="margin-bottom:20px">
<tr>
	<td colspan = 3><b>VERIFIKASI</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td width="70">Tanggal</td><td>:</td>
	<td><input readonly="readonly" type="text" name="tanggal_ver" id="tanggal_ver" size="15" value="<?php echo $tanggal_ver; ?>"></td>
</tr>
<tr>
	<td>Oleh</td><td>:</td>
	<td><input readonly="readonly" type="text" name="oleh" id="oleh" size="20" value="<?php echo $oleh; ?>"></td>
</tr>
<tr>
	<td>   </td><td> </td>
	<td> </td>
</tr>
</table>

<table class="t-popup wauto f-right">
<tr>
	<td colspan = 3><b>TANDA TANGAN DAN PENYERAHAN PPJB</b></td>
</tr>
<tr>
	<td colspan = 3><hr></td>
</tr>
<tr>
	<td width="200">Tgl. dipinjam oleh Pembeli</td></td><td>:</td>
	<td><input type="text" name="tgl1" id="tgl1" size="15" class="apply dd-mm-yyyy" value="<?php echo $tgl1; ?>"></td>
</tr>
<tr>
	<td>Tgl. tanda tangan oleh Pembeli</td></td><td>:</td>
	<td><input type="text" name="tgl2" id="tgl2" size="15" class="apply dd-mm-yyyy" value="<?php echo $tgl2; ?>"></td>
</tr>
<tr>
	<td>Tgl. tanda tangan oleh ANCOL</td></td><td>:</td>
	<td><input type="text" name="tgl3" id="tgl3" size="15" class="apply dd-mm-yyyy" value="<?php echo $tgl3; ?>"></td>
</tr>
<tr>
	<td>Tgl. diserahkan ke Pembeli</td></td><td>:</td>
	<td><input type="text" name="tgl4" id="tgl4" size="15" class="apply dd-mm-yyyy" value="<?php echo $tgl4; ?>"></td>
</tr>
<tr>
	<td>Tercetak</td></td><td>:</td>
	<td>
		<input type="radio" name="tercetak" id="tercetak_belum" value="0" <?php echo is_checked('0', $status_cetak); ?>> <label for="tercetak_belum">Belum</label>
		<input type="radio" name="tercetak" id="tercetak_sudah" value="1" <?php echo is_checked('1', $status_cetak); ?>> <label for="tercetak_belum">Sudah</label>
	</td>
</tr>
<tr>
	<td>No. Arsip</td></td><td>:</td>
	<td><input type="text" name="no_arsip" id="no_arsip" size="15" value="<?php echo $nomor_arsip; ?>"></td>
</tr>
<tr>
	<td class="td-action" colspan=3>
		<input type="button" id="ttd" value=" Ttd ">
		<input type="button" id="ppjb" value=" PPJB ">
		<input type="button" id="addendum2" value=" Addendum ">
		<input type="button" id="lampiran" value=" Lampiran ">
		<input type="button" id="alamat" value=" Alamat ">
	</td>
</tr>
<tr>
	<td colspan=3>
		<input type="submit" id="save" value=" <?php echo $act; ?> ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="delete" value=" Hapus ">
		<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
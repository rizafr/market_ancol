<?php
require_once('tanda_terima_proses.php');
require_once('../../../../../config/terbilang.php');
$terbilang = new Terbilang;
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
<script type="text/javascript" src="../../../../../config/js/terbilang_js.js"></script>
<script type="text/javascript">
jQuery(function($) {
	if ('<?php echo $act; ?>' == 'Tambah') {
		$('#print').hide();	
	}else{
		$('#daftar').hide();	
	}	
	

	$('#nomor, #penerima').inputmask('varchar', { repeat: '30' });	
	$('#no_customer, #no_tlp').inputmask('mask', { repeat: '15', mask : '9', groupSeparator : '', placeholder : '' });
	$('#kode_blok').inputmask('varchar', { repeat: '15' });	
	$('#nama_pembayar').inputmask('varchar', { repeat: '40' });
	$('#alamat').inputmask('varchar', { repeat: '60' });
	$('#keterangan').inputmask('varchar', { repeat: '480' });
	$('#jumlah').inputmask('numeric', { repeat: '16' });	
	
	$('#jumlah').on('keyup', function(e) {
		e.preventDefault();
		jumlah = jQuery('#jumlah').val();		
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
		sejumlah = terbilang(jumlah);
		$('#terbilang').val(sejumlah);
		return false;
	});

	$('#jumlah').on('focus', function(e) {
		e.preventDefault();
		jumlah = jQuery('#jumlah').val();		
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
		sejumlah = terbilang(jumlah);
		$('#terbilang').val(sejumlah);
		return false;
	});


	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});

	$('#kwitansi').on('click', function(e) {
		e.preventDefault();
		
		var url		= base_marketing + 'kredit/transaksi/tanda_terima/tanda_terima_proses_kwitansi.php',
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
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'kredit/transaksi/tanda_terima/tanda_terima_proses.php',
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
	
	$('#print').on('click', function(e) {
		e.preventDefault();		
		window.open(base_marketing + 'kredit/transaksi/tanda_terima/tanda_terima_print.php?id=<?php echo base64_encode($id); ?>');		
		return false;
	});	
});

function daftar_pemesan() {
	var url = base_marketing + 'kredit/transaksi/tanda_terima/daftar_pemesan.php'; 
	setPopup('Daftar Pemesan', url, 700, 300); 
	return false; 
}
</script>
</head>
<body class="popup2">

<button id="daftar" onclick="return daftar_pemesan()"> Daftar Pemesan </button>
<table class="t-popup wauto f-right">
<tr>
	<td>No. Dok.</td>
	<td>:</td> 
	<td>007/F/KEU/JRP/06</td>
</tr>
<tr>
	<td>Rev.</td>
	<td>:</td>
	<td>0</td>
</tr>
</table>

<div class="clear"></div>

<form name="form" id="form" method="post">
<table class="t-popup wauto">
<tr>
	<td colspan="4"><hr></td>
</tr>
<tr>
	<td width="100">No. Tanda Terima</td><td>:</td>
	<td><input type="text" name="nomor" id="nomor" size="20" value="<?php echo $nomor; ?>"></td>
	<td class="text-right">Tanggal : <input type="text" name="tanggal" id="tanggal" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal; ?>"></td>
</tr>
<tr>
	<td>Kode Blok</td><td>:</td>
	<td><input type="text" name="kode_blok" id="kode_blok" size="10" value="<?php echo $kode_blok; ?>"></td>
	<td class="text-right">Pembayaran : 
	<select name="pembayaran" id="pembayaran">
		<option value=""> -- Pembayaran -- </option>
		<?php
		$obj = $conn->execute("
		SELECT *
		FROM 
			JENIS_PEMBAYARAN
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['KODE_BAYAR'];
			$oj = $obj->fields['JENIS_BAYAR'];
			$kel = $obj->fields['KELOMPOK'];
			echo "<option value='$ov#$kel' data-jenis='$oj'".is_selected($ov, $kode_bayar)."> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	</td>
</tr>
<tr>
	<td>Nama Pembayar</td><td>:</td>
	<td colspan="2"><input type="text" name="nama_pembayar" id="nama_pembayar" size="50" value="<?php echo $nama_pembayar; ?>"></td>
</tr>
<tr>
	<td>Nomor Telepon</td><td>:</td>
	<td colspan="2"><input type="text" name="no_tlp" id="no_tlp" size="15" value="<?php echo $no_tlp; ?>"></td>
</tr>
<tr>
	<td>Alamat Pembayar</td><td>:</td>
	<td colspan="2"><input type="text" name="alamat" id="alamat" size="98" value="<?php echo $alamat; ?>"></td>
</tr>
<tr>
	<td>Pembayaran Secara</td><td>:</td>
	<td> 
		<input type="radio" name="bayar_secara" id="tunai" class="status" value="1" <?php echo is_checked('1', $bayar_secara); ?>>Tunai
		<input type="radio" name="bayar_secara" id="cek" class="status" value="2" <?php echo is_checked('2', $bayar_secara); ?>>Giro / Cek   
		<input type="radio" name="bayar_secara" id="bilyet" class="status" value="3" <?php echo is_checked('3', $bayar_secara); ?>>Bank O
		<input type="radio" name="bayar_secara" id="lain" class="status" value="4" <?php echo is_checked('4', $bayar_secara); ?>> Lain
		<input type="radio" name="bayar_secara" id="virtual_account" class="status" value="5" <?php echo is_checked('5', $bayar_secara); ?>> Virtual Account
	</td>
	<td> Bank : 
		<select name="bank" id="bank">
			<option value="0"> -- Bank -- </option>
				<?php
				$obj = $conn->execute("
					SELECT *
					FROM 
					BANK
					");
					while( ! $obj->EOF)
					{
					$ov = $obj->fields['KODE_BANK'];
					$oj = $obj->fields['NAMA_BANK'];
					echo "<option value='$ov'".is_selected($oj, $bank)."> $oj </option>";
					$obj->movenext();
					}
				?>
		</select>
	</td>
</tr>
<tr>
	<td>Keterangan</td><td>:</td>
	<td colspan="2"><input type="text" name="keterangan" id="keterangan" size="98" value="<?php echo $keterangan; ?>"></td>
</tr>
<tr>
<?php 


?>
	<td>Jumlah Diterima</td><td>:</td>
	<td>Rp. <input type="text" name="jumlah" id="jumlah" size="15" value="<?php echo to_money($jumlah); ?>"></td>
</tr>
<tr>
	<td>Terbilang </td><td>:</td>
	<td colspan="2"><input type="text" name="terbilang" id="terbilang"  size="98" readonly="readonly" style="text-transform:uppercase" value="<?php echo ucfirst($terbilang->eja(bigintval($jumlah))); ?> rupiah"></td>
</tr>
<tr>
	<td>Koordinator</td><td>:</td>
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
	<td>Penerima : <input type="text" readonly = "readonly" name="penerima" id="penerima" size="30" value="<?php echo $penerima; ?>"></td>
</tr>
</table >
<table class="t-popup w100">
<tr>
	<td colspan="3"><br>
		<input type="submit" id="save" value=" <?php echo $act; ?> ">
		<input type="button" id="print" value=" Print ">
		<input type="button" id="kwitansi" value=" Create Kwitansi ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
<input type="hidden" name="no" id="no" value="<?php echo $no; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
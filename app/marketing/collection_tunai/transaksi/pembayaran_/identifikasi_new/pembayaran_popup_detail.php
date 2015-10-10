<?php
require('pembayaran_proses.php');
require_once('../../../../../config/terbilang.php');
$terbilang = new Terbilang;
$status_otorisasi	= (isset($_REQUEST['status_otorisasi'])) ? clean($_REQUEST['status_otorisasi']) : '';
$nilai	= (isset($_REQUEST['nilai'])) ? clean($_REQUEST['nilai']) : '';
$sisa	= (isset($_REQUEST['sisa'])) ? clean($_REQUEST['sisa']) : '';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->
<link type="text/css" href="../../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../../plugin/css/zebra/default.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="../../../../../plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/keymaster.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="../../../../../config/js/main.js"></script>
<script type="text/javascript" src="../../../../../config/js/terbilang_js.js"></script>
<script type="text/javascript">
function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}
function calculate(){
		
		var sel_jenis_pembayaran	= jQuery('#jenis_pembayaran option:selected');
			jenis_pembayaran		= sel_jenis_pembayaran.data('jenis');
			kode_bayar				= jQuery('#jenis_pembayaran').val();
		nilai				= '<?php echo $nilai; ?>';
		sisa				= '<?php echo $sisa; ?>';
		tanah_bangunan		= '<?php echo tanah_bangunan($luas_bangunan); ?>';
		lokasi				= '<?php echo $lokasi; ?>';		
		kode_blok			= '<?php echo $kode_blok; ?>';
		tipe				= '<?php echo $tipe; ?>';
		angsuran_ke			= '<?php echo $angsuran_ke; ?>';
		
		awal				= kode_blok.search("/");
		akhir				= kode_blok.search("-");
		blok				= kode_blok.slice(awal+1,akhir);
		nomor				= kode_blok.slice(akhir+1);
		
		if (kode_bayar == 24) {
		jumlah	= <?php echo $kpr; ?>;
		
		subtotal = jQuery('#jumlah').val();
		subtotal	= subtotal.replace(/[^0-9.]/g, '');
		subtotal	= (subtotal == '') ? 0 : parseFloat(subtotal);
		subtotal2= Math.round((100/110) * jumlah);
		ppn		 = Math.round(jumlah-subtotal2);
			if(subtotal > sisa){
				alert("Ma'af jumlah melebihi nilai transaksi");
			}	
		}	
		else if ((kode_bayar == 25) || (kode_bayar == 26) || (kode_bayar == 27) || (kode_bayar == 28)) {
		subtotal = jQuery('#jumlah').val();
		subtotal	= subtotal.replace(/[^0-9.]/g, '');
		subtotal	= (subtotal == '') ? 0 : parseFloat(subtotal);
		ppn		 = 0;	
			if(subtotal > sisa){
				alert("Ma'af jumlah melebihi nilai transaksi");
			}
		} 
		else {
		jumlah	= jQuery('#jumlah').val();
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
			if(jumlah > sisa){
				alert("Ma'af jumlah melebihi nilai transaksi");
			}	
		subtotal = Math.round((100/110) * jumlah);
		ppn		 = Math.round(jumlah-subtotal);	
		} 
		if ((kode_bayar == 1) || (kode_bayar == 2) || (kode_bayar == 3) || (kode_bayar == 4) || (kode_bayar == 5) || (kode_bayar == 6) ||
				(kode_bayar == 10) || (kode_bayar == 14) || (kode_bayar == 15) || (kode_bayar == 21) || (kode_bayar == 22) || (kode_bayar == 23)||
				(kode_bayar == 24)){
		jp = 'Pembayaran '+ jenis_pembayaran + ' ke-' + angsuran_ke +' atas pembelian ' + tanah_bangunan +
			 '\ndi ' + lokasi + ' Blok ' + blok + ' Nomor ' + nomor + ' (TYPE ' + tipe + ') \n' +
			 jenis_pembayaran + ' : Rp. ' + formatNumber(subtotal) + ',-' +
			 '\nPPN : Rp. ' + formatNumber(ppn) + ',-' ;
		}
		else{
		jp = 'Pembayaran '+ jenis_pembayaran + ' atas pembelian ' + tanah_bangunan +
			 '\ndi ' + lokasi + ' Blok ' + blok + ' Nomor ' + nomor + ' (TYPE ' + tipe + ') \n' ;
		}
			
		$('#keterangan').val(jp);
		$('#subtotal').val(subtotal);
		$('#ppn').val(ppn);
		
}
function cal2(){
		if ('<?php echo $act; ?>' == 'Tambah'){
			var sel_jenis_pembayaran	= jQuery('#jenis_pembayaran option:selected');
			jenis_pembayaran	= sel_jenis_pembayaran.data('jenis');
			kode_bayar			= jQuery('#jenis_pembayaran').val();
		}
		else {
			jenis_pembayaran = '<?php echo $jenis_pembayaran; ?>';
			kode_bayar 		 = '<?php echo $kode_bayar; ?>';
		} 
		
		if (kode_bayar == 24) {
		jumlah	= <?php echo $kpr; ?>;
		subtotal = Math.round((100/110) * jumlah);
		ppn		 = Math.round(jumlah-subtotal);		
		}	
		else if ((kode_bayar == 1) || (kode_bayar == 2) || (kode_bayar == 3) || (kode_bayar == 4) || (kode_bayar == 5) || (kode_bayar == 6) ||
				(kode_bayar == 10) || (kode_bayar == 14) || (kode_bayar == 15) || (kode_bayar == 21) || (kode_bayar == 22) || (kode_bayar == 23)){
		jumlah	= jQuery('#jumlah').val();
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
		subtotal = Math.round((100/110) * jumlah);
		ppn		 = Math.round(jumlah-subtotal);		
		} 
		else {
		subtotal = jQuery('#jumlah').val();
		ppn		 = 0;	
		} 
		
		$('#subtotal').val(subtotal);
		$('#ppn').val(ppn);
}	
jQuery(function($) {
	// if ('<?php echo $act; ?>' == 'Tambah') {
		// $('#nama_pembayar, #nomor, #sejumlah, #keterangan, #diposting, #tanggal, #tgl_terima, #via').hide();	
	// }	
	// if ('<?php echo $act; ?>' == 'Ubah') {
		// $('#nama_pembayar, #nomor, #sejumlah, #keterangan, #diposting, #tanggal, #tgl_terima, #via').hide();	
	// }	
	
	if ('<?php echo $act; ?>' == 'Ubah') {
		if($(jenis_pembayaran).val() == '4'){
			$('#jumlah').prop('disabled', true);
		} 
		$('#catatan').prop('disabled', true);
	}
	
	$('#nama_pembayar').inputmask('varchar', { repeat: '60' });
	$('#jumlah, #diposting').inputmask('numeric', { repeat: '16' });	
	$('#catatan').inputmask('varchar', { repeat: '20' });
	
	$('#jumlah').on('keyup', function(e) {
		e.preventDefault();
		jumlah = jQuery('#jumlah').val();		
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
		sejumlah = terbilang(jumlah);
		jQuery('#sejumlah').val(sejumlah);
		jQuery('#diposting').val(jumlah);
		return false;
	});
	$('#jenis_pembayaran').on('change', function(e) {
		e.preventDefault();
		if($(this).val() == '4'){
			$('#jumlah').prop('disabled', true);
			
			<?php
				$query = "
				select TOP 1 * from TAGIHAN where kode_blok = '$kode_blok'
				AND STATUS_BAYAR = 0;
				";
				
				$obj = $conn->execute($query);
				$jumlah_angsuran	= to_money($obj->fields['NILAI']);
			
			?>
			jumlah_angsuran = '<?php echo $jumlah_angsuran; ?>';
			$('#jumlah').val(jumlah_angsuran);
			$('#jumlah_angsuran').val(jumlah_angsuran);
		} 
		else if($(this).val() == '9'){
			$('#jumlah').prop('disabled', true);		
			jumlah_denda = $('#jumlah_denda').val();
			$('#jumlah').val(jumlah_denda);
		}
		else{
			$('#jumlah').prop('disabled', false);		
			$('#jumlah').val(0);
		}
		calculate();
		return false;
	});
	
	$('#jumlah').on('keyup', function(e) {
		e.preventDefault();
		calculate();
		return false;
	});
	
//==============================================
	$('#via').on('change', function(e) {
		e.preventDefault();
		cal2();
		return false;
	});
	
	$('#diposting, #nama_pembayar, #catatan, #keterangan').on('keyup', function(e) {
		e.preventDefault();
		cal2();
		return false;
	});
	
	$('#tanggal, #tgl_terima').on('focus', function(e) {
		e.preventDefault();
		cal2();
		return false;
	});
//==============================================	
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var id			= '<?php echo $id; ?>';
		var kode		= '<?php echo $kode_blok; ?>';
		var url		= base_marketing + 'collection_tunai/transaksi/pembayaran/pembayaran_proses.php',
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
					parent.load(kode);
				}
				else if (data.act == 'Tambah')
				{
					alert(data.msg);
					parent.load(id);
				}
		}, 'json');
		return false;
	});
	
	// $(document).on('click', '#rr', function(e) {
		// e.preventDefault();
		// showPopup('RR', '<?php echo $id; ?>');
		// return false;
	// });
	
	$('#rr').on('click', function(e) {
		e.preventDefault();
		var id = $(this).parent().attr('id');
		showPopup('Detail', id);
		return false;
	});
	
	$('#bon').on('click', function(e) {
		e.preventDefault();		
		window.open(base_kredit_transaksi + 'kuitansi/kuitansi_bon.php?id=<?php echo base64_encode($id); ?>');		
		return false;
	});
	
	$('#print').on('click', function(e) {
		e.preventDefault();		
		window.open(base_kredit_transaksi + 'kuitansi/kuitansi_print.php?id=<?php echo base64_encode($id); ?>');		
		return false;
	});	
});
</script>
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup">
<tr>
	<td width="100">Nilai Transaksi</td><td>:</td>
	<td><input type="text" name="nilai" id="nilai" size="15" readonly="readonly" value="<?php echo to_money($sisa); ?>"></td>
</tr>
<tr id="tr-jp">
	<td>Jenis Pembayaran</td><td>:</td>
	<td>
	<select name="jenis_pembayaran" id="jenis_pembayaran">
		<option value=""> -- Jenis Pembayaran -- </option>
		<?php
		$obj = $conn->execute("
		SELECT *
		FROM 
			JENIS_PEMBAYARAN
		WHERE KELOMPOK = '$status_otorisasi'
		ORDER BY KELOMPOK
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['KODE_BAYAR'];
			$oj = $obj->fields['JENIS_BAYAR'];
			echo "<option value='$ov' data-jenis='$oj'".is_selected($ov, $kode_bayar)."> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	</td>
</tr>
<tr>
	<td width="100">Jumlah Rp.</td><td>:</td>
	<td><input type="text" name="jumlah" id="jumlah" size="15" onkeyUp="javascript:autoCek();" value="<?php echo to_money($jumlah); ?>"></td>
</tr>
<tr>
	<td width="100">Catatan</td><td>:</td>
	<td colspan="2"><textarea name="catatan" id="catatan" rows="6" cols="100"><?php echo $catatan; ?></textarea></td>
</tr>
<tr>
	<td width="100"> </td><td></td>
	<td><input type="hidden" name="nomor" id="nomor" size="20" readonly="readonly" value="<?php echo $nomor; ?>"></td>
	
</tr>

<tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="max_tgl" id="max_tgl" size="70" value="<?php echo $max_tgl; ?>"></td>
</tr>
<tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="jumlah_awal" id="jumlah_awal" size="70" value="<?php echo $jumlah; ?>"></td>
</tr>
<tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="nomor_customer" id="nomor_customer" size="70" value="<?php echo $nomor_customer; ?>"></td>
</tr>
<tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="nama_pembayar" id="nama_pembayar" size="50" value="<?php echo $nama_pembayar; ?>"></td>
</tr>
<tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="sejumlah" id="sejumlah" size="98" readonly="readonly" style="text-transform:uppercase" value="<?php echo ucfirst($terbilang->eja($jumlah)); ?> rupiah"></td>
</tr>
<tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="jumlah_angsuran" id="jumlah_angsuran" size="50" value="<?php echo $jumlah_angsuran; ?>"></td>
</tr><tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="jumlah_denda" id="jumlah_denda" size="50" value="<?php echo $jumlah_denda; ?>"></td>
</tr>
<tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="jumlah_lain" id="jumlah_lain" size="50" value="<?php echo $jumlah_lain; ?>"></td>
</tr>
<tr>
	<td> </td><td></td>
	<td colspan="2"><input type="hidden" name="angsuran_ke" id="angsuran_ke" size="50" value="<?php echo $angsuran_ke; ?>"></td>
</tr>
<tr>
	<td></td><td></td>
	<td colspan="2"><input type="hidden" name="keterangan" id="keterangan" rows="6" cols="100" value="<?php echo $keterangan; ?>"></td>
</tr>
</table>
<table class="t-popup">
<tr>
		
	<td> <input type="hidden" name="diposting" id="diposting" size="15" value="<?php echo to_money($diposting); ?>"></td>
	<td> <input type="hidden" name="tanggal" id="tanggal" size="15" value="<?php echo $tanggal; ?>"></td>
	<td> <input type="hidden" name="status_otorisasi" id="status_otorisasi" size="15" value="<?php echo $status_otorisasi; ?>"></td>
</tr>
</table>

<div class="clear"><br><br></div>

<table class="t-popup w90 f-left">
<tr>
	<td><input type="hidden" name="tgl_terima" id="tgl_terima" size="15" value="<?php echo $tgl_terima; ?>"></td>
	
</tr>

<tr>
	<td class="" colspan="3">
		<input type="submit" id="save" value=" <?php echo $act; ?> ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="subtotal" id="subtotal" value="<?php echo $subtotal; ?>">
<input type="hidden" name="ppn" id="ppn" value="<?php echo $ppn; ?>">

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
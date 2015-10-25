<?php
require_once('kuitansi_proses.php');
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
		
		tanah_bangunan		= '<?php echo tanah_bangunan($luas_bangunan); ?>';
		lokasi				= '<?php echo $lokasi; ?>';		
		kode_blok			= '<?php echo $kode_blok; ?>';
		tipe				= '<?php echo $tipe; ?>';
		
		awal				= kode_blok.search("/");
		akhir				= kode_blok.search("-");
		blok				= kode_blok.slice(awal+1,akhir);
		nomor				= kode_blok.slice(akhir+1);
		
		if (kode_bayar == 24) {
		jumlah	= <?php echo $kpr; ?>;
		subtotal = Math.round((100/110) * jumlah);
		ppn		 = Math.round(jumlah-subtotal);		
		}	
		else if ((kode_bayar == 25) || (kode_bayar == 26) || (kode_bayar == 27) || (kode_bayar == 28)) {
		subtotal = jQuery('#jumlah').val();
		ppn		 = 0;	
		} 
		else {
		jumlah	= jQuery('#jumlah').val();
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
		subtotal = Math.round((100/110) * jumlah);
		ppn		 = Math.round(jumlah-subtotal);	
		} 

		if ((kode_bayar == 25) || (kode_bayar == 26) || (kode_bayar == 27) || (kode_bayar == 28)) {
		jp = 'Pembayaran '+ jenis_pembayaran + ' atas pembelian ' + tanah_bangunan +
			 '\ndi ' + lokasi + ' Lantai ' + blok + ' Nomor ' + nomor + ' (TYPE ' + tipe + ') \n' ;
		}
		else {
		jp = 'Pembayaran '+ jenis_pembayaran + ' atas pembelian ' + tanah_bangunan +
			 '\ndi ' + lokasi + ' Lantai ' + blok + ' Nomor ' + nomor + ' (TYPE ' + tipe + ') \n' +
			 jenis_pembayaran + ' : Rp. ' + formatNumber(subtotal) + ',-' +
			 '\nPPN : Rp. ' + formatNumber(ppn) + ',-' ;
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
		else if ((kode_bayar == 25) || (kode_bayar == 26) || (kode_bayar == 27) || (kode_bayar == 28)) {
		subtotal = jQuery('#jumlah').val();
		ppn		 = 0;	
		} 
		else {
		jumlah	= jQuery('#jumlah').val();
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
		subtotal = Math.round((100/110) * jumlah);
		ppn		 = Math.round(jumlah-subtotal);		
		} 
		
		$('#subtotal').val(subtotal);
		$('#ppn').val(ppn);
}	

jQuery(function($) {
	if ('<?php echo $act; ?>' == 'Tambah') {
		$('#post, #bon, #print, #rr').hide();	
	}	
	var tgl_terima = jQuery('#tgl_terima').val();
	if(tgl_terima==''){
		alert('Tanggal Pembayaran Diterima Harus Diisi');
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
		var url		= base_marketing + 'kredit/transaksi/kuitansi/kuitansi_proses.php',
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
				// else if (data.act == 'Tambah')
				// {
					// alert(data.msg);
					// parent.loadData();
				// }
		}, 'json');
		return false;
	});
	
	$('#bon').on('click', function(e) {
		e.preventDefault();
		window.open(base_marketing + 'kredit/transaksi/kuitansi/kuitansi_bon.php?id=<?php echo base64_encode($id); ?>');		
		return false;
	});
	
	$('#print').on('click', function(e) {
		var tgl_terima = jQuery('#tgl_terima').val();
		if(tgl_terima==''){
			alert('Tanggal Pembayaran Diterima Harus Diisi');
			return false;
		}
		e.preventDefault	
		var cat_kwt = jQuery('#catatan_kwt').val();
		window.open(base_marketing + 'kredit/transaksi/kuitansi/kuitansi_print.php?id=<?php echo base64_encode($id); ?>&catatan_kwt=' + cat_kwt);
		return false;
	});	
});
</script>
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup">
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
		WHERE
			KELOMPOK = '1'
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
	<td width="100">Nomor</td><td>:</td>
	<td><input type="text" name="nomor" id="nomor" size="20" readonly="readonly" value="<?php echo $nomor; ?>"></td>
</tr>
<tr>
	<td>Telah Terima Dari</td><td>:</td>
	<td colspan="2"><input type="text" name="nama_pembayar" id="nama_pembayar" size="50" value="<?php echo $nama_pembayar; ?>" readonly="readonly" ></td>
</tr>
<tr>
	<td>Sejumlah Uang</td><td>:</td>
	<td colspan="2"><input type="text" name="sejumlah" id="sejumlah" size="98" readonly="readonly" style="text-transform:uppercase" value="<?php echo ucfirst($terbilang->eja($jumlah)); ?> rupiah"></td>
</tr>
<tr>
	<td>Untuk Pembayaran</td><td>:</td>
	<td colspan="2"><textarea name="keterangan" id="keterangan" readonly="readonly" rows="6" cols="100"><?php echo $keterangan; ?></textarea></td>
</tr>
</table>
<table class="t-popup">
<tr>
	<td>Jumlah Rp. : <input <?php if($act!='Tambah'){echo "readonly";}?> type="text" name="jumlah" id="jumlah" size="15" value="<?php echo to_money($jumlah); ?>"></td>	
	<td>Diposting Rp. : <input <?php if($act!='Tambah'){echo "readonly";}?> type="text" name="diposting" id="diposting" size="15" value="<?php echo to_money($diposting); ?>"></td>
	<td>Jakarta, <input readonly="readonly" type="text" name="tanggal" id="tanggal" size="15" class="apply dd-mm-yyyy" value="<?php echo $tanggal; ?>"></td>
</tr>
</table>

<div class="clear"><br><br></div>

<table class="t-popup w90 f-left">
<tr>
	<td colspan ="3"><b><u>Informasi Pembayaran</u></b></td>
</tr>
<tr>
	<td>Pembayaran Diterima Tanggal : <input type="text" name="tgl_terima" id="tgl_terima" size="15" class="apply dd-mm-yyyy" value=""></td>
</tr>
<tr>
	<td colspan ="2">Catatan Collection : <textarea type="text" name="catatan" id="catatan" readonly="readonly" rows="3" cols="100"><?php echo $catatan; ?></textarea></td>
</tr>
<tr>
	<td colspan ="2">Catatan Kwitansi : <textarea type="text" name="catatan_kwt" id="catatan_kwt" rows="6" cols="100"><?php echo $catatan_kwt; ?></textarea></td></tr>
</tr>
<tr>
	<td class="td-action" colspan="3">
		<!--
		<input type="button" id="post" value=" Post ">
		<input type="button" id="bon" value=" Bon ">
		<input type="button" id="rr" value=" R-R "> -->
		<input type="button" id="print" value=" Print ">	
		<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
<tr>
	<td class="" colspan="3">
		<input type="submit" id="save" value=" <?php echo $act; ?> ">
		<!--<input type="reset" id="reset" value=" Reset ">-->
		
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
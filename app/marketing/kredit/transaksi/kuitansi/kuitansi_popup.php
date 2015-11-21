<?php
require_once('kuitansi_proses.php');
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
	
	/* -- BUTTON -- */
	$(document).on('click', '#tambah', function(e) {
		e.preventDefault();
		showPopup('Tambah', '<?php echo $id; ?>');
		return false;
	});
	
	$(document).on('click', 'tr.onclick td:not(.notclick)', function(e) {
		e.preventDefault();
		var id = $(this).parent().attr('id');
		var type = $(this).parent().attr('tipe');
		showPopup('Ubah', id,type);
		return false;
	});
	
	$('#pindah').on('click', function(e) {
		e.preventDefault();		
		var url = base_marketing + 'kredit/transaksi/kuitansi/pindah_blok.php?id=<?php echo $id; ?>&act=Pindah';		
		setPopup('Pindah Blok', url, 500, 250);
		return false;
	});
	
	$(document).on('click', '#hapus', function(e) {
		e.preventDefault();
		if (confirm('Apa anda yakin akan menghapus data ini?'))
		{
			deleteData();
		}
	});
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'kredit/transaksi/kuitansi/kuitansi_load_detail.php', data);	
	return false;
}

function showPopup(act, id)
{
		var url =	base_marketing + 'kredit/transaksi/kuitansi/kuitansi_popup_detail.php' +	'?act=' + act +	'&id=' + id,
		title	= (act == 'Simpan') ? 'Tambah' : act;
		setPopup(title + ' Kwitansi Penjualan Unit Apartemen', url, 800, 400);	
	return false;	
	
	
}

function deleteData()
{
	var checked = jQuery(".cb_data:checked").length;
	if (checked < 1)
	{
		alert('Pilih data yang akan dihapus.');
		return false;
	}
	
	var url		= base_marketing + 'kredit/transaksi/kuitansi/kuitansi_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'Hapus' });
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);		
	}, 'json');
	
	loadData();
	return false;
}
</script>

</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup w50 f-left" style="margin-right:35px">
<tr>
	<td width="100">Blok / Nomor</td><td width="10">:</td>
	<td><?php echo $kode_blok; ?></td>
</tr>
<tr>
	<td>Nomor SPP</td></td><td>:</td>
	<td><?php echo $no_spp; ?></td>
</tr>
<tr>
	<td>Nama Pemilik</td></td><td>:</td>
	<td><?php echo $nama_pembeli; ?><input type="hidden" name="nama_pembeli" id="nama_pembeli" value="<?php echo $nama_pembeli; ?>"></td>
</tr>
<tr>
	<td>Alamat</td></td><td>:</td>
	<td><?php echo $alamat; ?></td>
</tr>
<tr>
	<td>Telepon</td></td><td>:</td>
	<td><?php echo $tlp1; ?></td>
</tr>
<tr>
	<td>Pola Bayar</td></td><td>:</td>
	<td><?php echo $pola_bayar; ?></td>
</tr>
</table>
<table class="t-popup wauto" style="margin-right:35px">
<tr>
	<td width="100">Tanggal SPP</td></td><td width="10">:</td>
	<td><?php echo $tanggal_spp; ?></td>
</tr>
<tr>
	<td>No. Identitas</td></td><td>:</td>
	<td><?php echo $no_identitas; ?></td>
</tr>
<tr>
	<td>NPWP</td></td><td>:</td>
	<td><?php echo $npwp; ?></td>
</tr>
</table>

<div class="clear"><br></div>

<table class="t-data w25 f-left" style = "margin-right: 5px" >
<tr>
	<th colspan=4>RENCANA PENERIMAAN</th>
</tr>
<tr>
	<th class="w2">NO.</th>
	<th class="w10">TANGGAL</th>
	<th class="w7">ANGSURAN</th>
	<th class="w6">KETERANGAN</th>
</tr>
<tr>
	<td class="text-center">1</td>
	<td><?php echo date("d-m-Y", strtotime($tgl_jadi)); ?></td>
	<td class="text-right"><?php echo to_money($tanda_jadi);  ?></td>
	<td>TANDA JADI</td>
</tr>

<?php
	$query = "
	SELECT *
	FROM 
		RENCANA a
	LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE KODE_BLOK = '$kode_blok'
	ORDER BY TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 2;
	$j=1;
	$total = 0;
	while( ! $obj->EOF)
	{
		$nilai = $obj->fields['NILAI'];
		$total+= $nilai;
		?>
		<tr>
			<td class="text-center"><?php echo $i;  ?></td>
			<td><?php echo date("d-m-Y", strtotime($obj->fields['TANGGAL'])); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'] ." ".$j ?> </td>
		</tr>
		<?php
		$i++;
		$j++;
		$obj->movenext();
	}
if ($jml_kpr > 0) {	
?>
<tr>
	<td class="text-center"><?php echo $i; ?></td>
	<td class="text-right"></td>
	<td class="text-right"><?php echo to_money($jml_kpr);  ?></td>
	<td>K.P.R</td>
</tr>
</table>
<?php } ?>

<tr>
	<td class="text-center" colspan="2">TOTAL</td>
	<td><?php echo to_money($total+$tanda_jadi);?></td>

</tr>
</table>
<table class="t-data w70">
<tr>
	<th colspan=8>REALISASI PENERIMAAN</th>
</tr>
<tr>
	<th>NO.</th>
	<th>TANGGAL</th>
	<th>ANGSURAN</th>
	<th>TGL. IDENTIFIKASI</th>
	<th>OFFICER KEU.</th>
	<th>TGL. VER KEU.</th>
	<th>KETERANGAN</th>
</tr>

<?php
	$query = "
	SELECT K.TANGGAL, R.NILAI, K.VER_COLLECTION AS COL, K.VER_KEUANGAN AS KEU, K.BAYAR_VIA AS KETERANGAN, K.VER_COLLECTION_TANGGAL, K.VER_KEUANGAN_TANGGAL from REALISASI R JOIN KWITANSI K ON R.KODE_BLOK = K.KODE_BLOK 
	WHERE K.KODE_BLOK = '$kode_blok' AND K.KODE_BLOK = R.KODE_BLOK AND K.NOMOR_KWITANSI = R.NOMOR_KWITANSI
	ORDER BY TANGGAL
	";

	$obj = $conn->execute($query);
	$i = 1;
	$nilai = 0;
	while( ! $obj->EOF)
	{
			$bayar_via  = array('1' =>"Tunai",'2' =>"Giro / Cek",'3' =>"Bank O",'4' =>"Lain",'5' =>"Virtual Account" );
			$keterangan= $bayar_via[$obj->fields['KETERANGAN']];
		?>
		<tr> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['VER_COLLECTION_TANGGAL']))); ?></td>
			<td class="text-center"><?php echo status_check($obj->fields['KEU']); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['VER_KEUANGAN_TANGGAL']))); ?></td>
			<td class="text-center"><?php echo $keterangan ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}	
	$query = "
	SELECT SUM(NILAI) AS TOTAL FROM REALISASI WHERE KODE_BLOK = '$kode_blok'
	";
	$obj = $conn->execute($query);
?>
<tr>
	<th colspan=2 lass="text-center">TOTAL</th>
	<td class="text-right"><?php echo to_money($obj->fields['TOTAL']);  ?></td>
</tr>
<tr>
	<th colspan=2 lass="text-center">SISA</th>
	<td class="text-right"><?php echo to_money($sisa_pembayaran - $obj->fields['TOTAL']);  ?></td>
</tr>
</table>

<div class="clear"><br></div>

<table id="pagging-1" class="t-control w100">
<tr>
	<td>
		<input type="button" id="tambah" value=" Tambah ">
		<!--<input type="button" id="hapus" value=" Hapus ">
		<input type="button" id="rr" value=" R-R ">-->
		<input type="button" id="pindah" value=" Pindah Blok ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<div id="t-detail"></div>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
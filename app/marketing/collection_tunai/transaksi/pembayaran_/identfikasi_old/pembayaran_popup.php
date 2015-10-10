<?php
require_once('pembayaran_proses.php');
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
		var jenis_kwt = $('input[name=status_otorisasi]:checked').val();
		var nilai = <?php echo $nilai; ?>;
		var sisa = <?php echo $sisa; ?>;
		e.preventDefault();
		showPopup('Tambah', '<?php echo $id; ?>',jenis_kwt,nilai,sisa);
		return false;
	});
	
	
	$(document).on('click', 'tr.onclick td:not(.notclick)', function(e) {
		var jenis_kwt = $('input[name=status_otorisasi]:checked').val();
		e.preventDefault();
		var id = $(this).parent().attr('id');
		var nilai = <?php echo $nilai; ?>;
		var sisa = <?php echo $sisa; ?>;
		showPopup('Ubah',id,jenis_kwt,nilai,sisa);
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
	
	$('input:radio[name="status_otorisasi"]').change(function(e){
		e.preventDefault();
		loadData();
		return false;
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/transaksi/pembayaran/pembayaran_load_detail.php', data);	
	return false;
}

function load(id)
{
	if (popup) { popup.close(); }
	parent.load(id);
	return false;
}

function showPopup(act,id,jenis_kwt,nilai,sisa)
{
	
	var url =	base_marketing + 'collection_tunai/transaksi/pembayaran/pembayaran_popup_detail.php' +	'?act=' + act +	'&id=' + id + '&status_otorisasi=' + jenis_kwt + '&nilai=' + nilai + '&sisa=' + sisa
		title	= (act == 'Simpan') ? 'Tambah' : act;	
	setPopup(title + ' Kuitansi Penjualan Unit Kaveling / Bangunan', url, 800, 400);	
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
	
	var nomor_c	= <?php echo $nomor_customer; ?>;
	
	var url		= base_marketing + 'collection_tunai/transaksi/pembayaran/pembayaran_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'Hapus' },{ name: 'nomor_customer', value: nomor_c });
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);		
	}, 'json');
	
	parent.load('<?php echo $id; ?>');
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
<tr>
	<td>NOMOR VA</td></td><td>:</td>
	<td><?php echo $nomor_va; ?></td>
</tr>
<tr>
	<td>SISA</td></td><td>:</td>
	<td><b><?php echo to_money($sisa); ?></b></td>
</tr>
<tr>
	<td><input type="hidden" name="max_tgl" id="max_tgl" size="70" value="<?php echo $max_tgl; ?>"></td>
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

	while( ! $obj->EOF)
	{
		?>
		<tr>
			<td class="text-center"><?php echo $i;  ?></td>
			<td><?php echo date("d-m-Y", strtotime($obj->fields['TANGGAL'])); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
		</tr>
		<?php
		$i++;
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

<table class="t-data w70">
<tr>
	<th colspan=8>REALISASI PENERIMAAN</th>
</tr>
<tr>
	<th>NO.</th>
	<th>TANGGAL</th>
	<th>ANGSURAN</th>
	<th>OFFICER COL.</th>
	<th>TGL. VER COL.</th>
	<th>OFFICER KEU.</th>
	<th>TGL. VER KEU.</th>
	<th>KETERANGAN</th>
</tr>

<?php
	$query = "
	SELECT a.*, COL = CASE WHEN b.FULL_NAME IS null 
	THEN '-' ELSE b.FULL_NAME END, 
	KEU =
	CASE WHEN c.FULL_NAME IS null 
	THEN '-' ELSE c.FULL_NAME END
	FROM 
		KWITANSI a
	LEFT JOIN USER_APPLICATIONS b ON a.VER_COLLECTION_OFFICER = b.USER_ID	
	LEFT JOIN USER_APPLICATIONS c ON a.VER_KEUANGAN_OFFICER = c.USER_ID
	WHERE KODE_BLOK = '$kode_blok'
	ORDER BY TANGGAL
	";

	$obj = $conn->execute($query);
	$i = 1;
	$nilai = 0;
	while( ! $obj->EOF)
	{
		?>
		<tr> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>
			<td><?php echo $obj->fields['COL']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['VER_COLLECTION_TANGGAL']))); ?></td>
			<td><?php echo $obj->fields['KEU']; ?></td>
			<?php
			if($obj->fields['VER_KEUANGAN'] == '0')
			{
				?><td>-</td>
			<?php
			}else
			{
			?>
				<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['VER_KEUANGAN_TANGGAL']))); ?></td>
			<?php
			}
			?>
			<td class="text-center"><?php echo $obj->fields['CATATAN']; ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}	
	$query = "
	SELECT SUM(NILAI) AS TOTAL FROM KWITANSI WHERE KODE_BLOK = '$kode_blok'
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

<table id="pagging-1" class="t-popup w100">
<tr>
	<td>
		<input type="button" id="tambah" value=" Tambah ">
		<input type="button" id="hapus" value=" Hapus ">
		<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
<br>
<tr>
	<td>
		<input type="radio" name="status_otorisasi" class="sbb" value="1" checked="true"> <label for="sbb">Kwitansi</label>
		<input type="radio" name="status_otorisasi" class="sbb" value="2"> <label for="sbb">Kwitansi Lain-Lain</label>
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
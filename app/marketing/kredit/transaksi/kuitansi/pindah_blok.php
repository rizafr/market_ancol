<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
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
<script type="text/javascript">
jQuery(function($) {	
	$('#close').on('click', function(e) {
	e.preventDefault();
		parent.window.focus();
		parent.window.popup.close();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'kredit/transaksi/kuitansi/kuitansi_proses.php',
			data	= $('#form').serialize();
		
		if (confirm("Apakah anda yakin menyimpan data ini ?") == false)
		{
			return false;
		}	
		
		$.post(url, data, function(data) {			
			if (data.error == true)
			{
				alert(data.msg);
			}
				else if (data.act == 'Pindah')
				{
					alert(data.msg);
					parent.window.focus();
					parent.window.popup.close();
				}
		}, 'json');		
		return false;
	});

});
</script>
</head>
<body class="popup2">

<?php
$query = "
	SELECT *
	FROM
		SPP
	WHERE 
		KODE_BLOK = '$id'";
		
$obj = $conn->execute($query);
$nama 			= $obj->fields['NAMA_PEMBELI'];
$tanggal 			= $obj->fields['TANGGAL_SPP'];
?>

<form name="form" id="form" method="post">

<table class="t-popup">
<tr>
	<td width="100">Blok Asal</td><td>:</td>
	<td><input readonly="readonly" type="text" name="blok_asal" id="blok_asal" size="10" value="<?php echo $id; ?>"></td>
</tr>
<tr>
	<td>Nama Pemilik</td><td>:</td>
	<td><input readonly="readonly" type="text" name="nama" id="nama" size="30" value="<?php echo $nama; ?>"></td>
</tr>
<tr>
	<td>Tanggal SPP</td><td>:</td>
	<td><input readonly="readonly" type="text" name="tanggal_spp" id="tanggal_spp" size="15" value="<?php echo f_tgl($tanggal); ?>"></td>
</tr>
<tr>
	<td>Blok Baru</td><td>:</td>
	<td>
		<select name="blok_baru" id="blok_baru">
		<option value=""> -- Blok Baru -- </option>
		<?php
		$query = "SELECT * FROM SPP WHERE KODE_BLOK = '$id'";
		$obj = $conn->execute($query);
		$nama_pembeli = $obj->fields['NAMA_PEMBELI'];
		
		$obj = $conn->execute("
			SELECT * 
			FROM SPP 
			WHERE NAMA_PEMBELI = '$nama_pembeli' 
			AND KODE_BLOK <> '$id'
			AND KODE_BLOK NOT IN (SELECT DISTINCT KODE_BLOK FROM KWITANSI WHERE NAMA_PEMBAYAR = '$nama_pembeli') 
			ORDER BY TANGGAL_SPP DESC
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['KODE_BLOK'];
			$tanggal = tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP'])));
			echo "<option value='$ov'> $ov ($tanggal)</option>";
			$obj->movenext();
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td colspan="3" class="td-action">
		<input type="submit" id="save" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
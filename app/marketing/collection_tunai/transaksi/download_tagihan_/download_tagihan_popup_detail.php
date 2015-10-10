<?php
require('download_tagihan_proses.php');
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

jQuery(function($) {
	if ('<?php echo $act; ?>' == 'Ubah') {
		$('#jenis_pembayaran').prop('disabled', true);
		
	}
	
	$('#jumlah').on('keyup', function(e) {
		e.preventDefault();
		jumlah = jQuery('#jumlah').val();		
		jumlah	= jumlah.replace(/[^0-9.]/g, '');
		jumlah	= (jumlah == '') ? 0 : parseFloat(jumlah);
		return false;
	});
	
	$('#jenis_pembayaran').on('change', function(e) {
		e.preventDefault();
		return false;
	});
	
	$('#jumlah').on('keyup', function(e) {
		e.preventDefault();
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
		
		var url		= base_marketing + 'collection_tunai/transaksi/download_tagihan/download_tagihan_proses.php',
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
		WHERE KELOMPOK = 2
		AND KODE_BAYAR != 9
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
	<td>Jumlah Rp.</td><td>:</td>
	<td><input type="text" name="jumlah" id="jumlah" size="25" onkeyUp="javascript:autoCek();" value="<?php echo to_money($jumlah); ?>"></td>
</tr>

</table>

<div class="clear"><br><br></div>

<table class="t-popup w90 f-left">
<tr>
	<td class="" colspan="3">
		<input type="submit" id="save" value=" <?php echo $act; ?> ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="kode_blok" id="kode_blok" value="<?php echo $kode_blok; ?>">
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
<input type="hidden" name="bulan" id="bulan" value="<?php echo $bulan; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
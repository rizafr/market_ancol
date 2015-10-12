<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('P');
die_mod('P06');
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
	$('#nama').inputmask('varchar', { repeat: '30' });
	$('#jabatan').inputmask('varchar', { repeat: '30' });
	
	$('#close').on('click', function(e) {
	e.preventDefault();
		parent.window.focus();
		parent.window.popup.close();
	});
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url		= base_marketing + 'ppjb/transaksi/ppjb/ppjb_proses.php',
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
				else if (data.act == 'Ttd')
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
<body class="popup">

<?php
$query = "
	SELECT *
	FROM
		CS_PPJB
	WHERE 
		KODE_BLOK = '$id'";
		
$obj = $conn->execute($query);
$nama 			= $obj->fields['NAMA_PENANDATANGAN'];
$jabatan		= $obj->fields['JABATAN'];
?>

<form name="form" id="form" method="post">

<table class="t-popup">
<tr>
	<td width="100">Nama Penandatangan</td><td>:</td>
	<td><input type="text" name="nama" id="nama" size="30" value="<?php echo $nama; ?>"></td>
</tr>
<tr>
	<td>Jabatan</td><td>:</td>
	<td><input type="text" name="jabatan" id="jabatan" size="30" value="<?php echo $jabatan; ?>"></td>
</tr>
<tr>
	<td colspan="3" class="td-action text-center">
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
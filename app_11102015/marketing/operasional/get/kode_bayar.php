<?php
require_once('../../../../config/config.php');
$conn = conn($sess_db);
ex_conn($conn);
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
jQuery(function($) {
	
	$(document).on('click', 'tr.onclick', function(e) {
		e.preventDefault();
		var kode_bayar = $(this).data('kode_bayar'),
			jenis_bayar = $(this).data('jenis_bayar');
		
		parent.jQuery('#kode_bayar').val(kode_bayar);
		parent.jQuery('#jenis_bayar').val(jenis_bayar);
		parent.window.focus();
		parent.window.popup.close();
		return false;
	});
	
	t_strip('.t-data');
});
</script>
</head>
<body class="popup">
<form name="form" id="form" method="post">

<table class="t-data">

<?php
$query = "
SELECT 
	KODE_BAYAR, 
	JENIS_BAYAR 
FROM JENIS_PEMBAYARAN
ORDER BY KODE_BAYAR ASC
";

$obj = $conn->Execute($query);
while( ! $obj->EOF)
{
	?>
	<tr class="onclick" 
		data-kode_bayar="<?php echo $obj->fields['KODE_BAYAR']; ?>"
		data-jenis_bayar="<?php echo $obj->fields['JENIS_BAYAR']; ?>">
		<td><?php echo $obj->fields['JENIS_BAYAR']; ?></td>
	</tr>
	<?php
	$obj->movenext();
}
?>
</table>

</form>
</body>
</html>
<?php close($conn); ?>
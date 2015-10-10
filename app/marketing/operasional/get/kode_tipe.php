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
		var kode_tipe = $(this).data('kode_tipe'),
			tipe_bangunan = $(this).data('tipe_bangunan');
		
		parent.jQuery('#kode_tipe').val(kode_tipe);
		parent.jQuery('#tipe_bangunan').val(tipe_bangunan);
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
	KODE_TIPE, 
	TIPE_BANGUNAN 
FROM TIPE 
ORDER BY KODE_TIPE ASC
";

$obj = $conn->Execute($query);
while( ! $obj->EOF)
{
	?>
	<tr class="onclick" 
		data-kode_tipe="<?php echo $obj->fields['KODE_TIPE']; ?>"
		data-tipe_bangunan="<?php echo $obj->fields['TIPE_BANGUNAN']; ?>">
		<td><?php echo $obj->fields['TIPE_BANGUNAN']; ?></td>
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
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
		var kode_sk = $(this).data('kode_sk'),
			harga_cash_keras = $(this).data('harga_cash_keras'),
			CB36X = $(this).data('harga'),
			CB48X = $(this).data('harga2'),
			KPA24X = $(this).data('harga3'),
			KPA36X = $(this).data('harga4');
		
		parent.jQuery('#kode_sk').val(kode_sk);
		parent.jQuery('#harga_cash_keras').val(harga_cash_keras);
		parent.jQuery('#harga_CB36X').val(CB36X);
		parent.jQuery('#harga_CB48X').val(CB48X);
		parent.jQuery('#harga_KPA24X').val(KPA24X);
		parent.jQuery('#harga_KPA36X').val(KPA36X);
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

<tr>
	<th>KODE SK</th>
</tr>

<?php
$query = "
SELECT KODE_SK
FROM HARGA_SK
WHERE STATUS='1'
GROUP BY KODE_SK

";

$obj = $conn->Execute($query);
while( ! $obj->EOF)
{
	?>
	<tr class="onclick" 
		data-kode_sk="<?php echo $obj->fields['KODE_SK']; ?>"
		>
		<td><?php echo $obj->fields['KODE_SK']?></td>
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
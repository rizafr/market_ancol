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
		var nomor_id = $(this).data('nomor_id'),
			nama = $(this).data('nama');
		
		parent.jQuery('#nomor_id_agen').val(nomor_id);
		parent.jQuery('#nama').val(nama);
		parent.window.focus();
		parent.window.popup.close();
		
		return false;
	});
	
	//t_strip('.t-data');
});
</script>
</head>
<body class="popup">
<form name="form" id="form" method="post">

<table class="t-data">

<tr>
	<th>NOMOR_ID</th>
	<th>NAMA</th>
	<th>JABATAN</th>
</tr>

<?php
$query = "
SELECT
	*
FROM
	CLUB_PERSONAL
WHERE JABATAN_KLUB = '5'
ORDER BY NOMOR_ID ASC
";

$obj = $conn->Execute($query);
while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_ID'];
		if ($obj->fields['JABATAN_KLUB'] == '5') {
			$jabatan = 'AGEN';
		}
	?>
	<tr class="onclick" 
		data-nomor_id="<?php echo $obj->fields['NOMOR_ID']; ?>"
		data-nama="<?php echo $obj->fields['NAMA']; ?>">
		<td><?php echo $obj->fields['NOMOR_ID']; ?></td>
		<td><?php echo $obj->fields['NAMA']; ?></td>
		<td class="text-left"><?php echo $jabatan ?></td>
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
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
		var kode_faktor = $(this).data('kode_faktor'),
			faktor_strategis = $(this).data('faktor_strategis'),
			nilai_tambah = $(this).data('nilai_tambah'),
			nilai_kurang = $(this).data('nilai_kurang');
		
		parent.jQuery('#kode_faktor').val(kode_faktor);
		parent.jQuery('#faktor_strategis').val(faktor_strategis);
		parent.jQuery('#nilai_tambah').val(nilai_tambah);
		parent.jQuery('#nilai_kurang').val(nilai_kurang);
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
	<th class="w20">Faktor Strategis</th>
	<th class="w5">Nilai Tambah</th>
	<th class="w5">Nilai Kurang</th>
</tr>

<?php
$query = "
SELECT *
FROM FAKTOR
WHERE STATUS = '1'
ORDER BY KODE_FAKTOR ASC
";

$obj = $conn->Execute($query);
while( ! $obj->EOF)
{
	?>
	<tr class="onclick" 
		data-kode_faktor ="<?php echo $obj->fields['KODE_FAKTOR']; ?>"
		data-faktor_strategis ="<?php echo $obj->fields['FAKTOR_STRATEGIS']; ?>"
		data-nilai_tambah ="<?php echo $obj->fields['NILAI_TAMBAH']; ?>"
		data-nilai_kurang ="<?php echo $obj->fields['NILAI_KURANG']; ?>"
		>
		<td><?php echo $obj->fields['FAKTOR_STRATEGIS']; ?></td>
		<td><?php echo $obj->fields['NILAI_TAMBAH']; ?></td>
		<td><?php echo $obj->fields['NILAI_KURANG']; ?></td>
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
<?php
require_once('rights_proses.php');
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
<script type="text/javascript" src="../../../../../plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="../../../../../plugin/window/javascripts/window.js"></script>
<script type="text/javascript" src="../../../../../config/js/main.js"></script>
<script type="text/javascript">
var this_base = base_marketing + 'utilitas/security_management/rights/';

jQuery(function($) {
	
	$('#modul_id').inputmask('varchar', { repeat: 5 });
	$('#modul_name').inputmask('varchar', { repeat: 30 });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'rights_proses.php',
			data	= $('#form').serialize();
			
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				if (result.act == 'Tambah') {
					$('#reset').click();
				} else if (result.act == 'Edit') {
					parent.loadData();
				}
			}
		}, 'json');
		
		return false;
	});
});
</script>
</head>
<body class="popup">
<form name="form" id="form" method="post">
<table>
<tr>
	<td width="100" class="va-top">Login Id</td><td width="10" class="va-top">:</td>
	<td class="va-top"><?php echo $login_id; ?><br><br></td>
</tr>
<tr>
	<td class="va-top">App</td><td class="va-top">:</td>
	<td class="va-top"><?php echo $app_name; ?><br><br></td>
</tr>
<tr>
	<td class="va-top">Modul</td><td class="va-top">:</td>
	<td class="va-top"><?php echo $modul_name; ?><br><br></td>
</tr>
<tr>
	<td>Read Only</td><td>:</td>
	<td>
		<input type="checkbox" name="r_ronly" id="r_ronly" value="Y" <?php echo is_checked($r_ronly, 'Y'); ?>>
	</td>
</tr>
<tr>
	<td>Edit</td><td>:</td>
	<td>
		<input type="checkbox" name="r_edit" id="r_edit" value="Y" <?php echo is_checked($r_edit, 'Y'); ?>>
	</td>
</tr>
<tr>
	<td>Insert</td><td>:</td>
	<td>
		<input type="checkbox" name="r_insert" id="r_insert" value="Y" <?php echo is_checked($r_insert, 'Y'); ?>>
	</td>
</tr>
<tr>
	<td>Delete</td><td>:</td>
	<td>
		<input type="checkbox" name="r_delete" id="r_delete" value="Y" <?php echo is_checked($r_delete, 'Y'); ?>>
	</td>
</tr>
<tr>
	<td colspan="2"></td>
	<td>
		<input type="submit" id="simpan" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="tutup" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
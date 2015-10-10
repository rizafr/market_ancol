<?php
require_once('modules_proses.php');
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
var this_base = base_marketing + 'utilitas/security_management/modules/';

jQuery(function($) {
	
	$('#modul_id').inputmask('varchar', { repeat: 5 });
	$('#modul_name').inputmask('varchar', { repeat: 30 });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'modules_proses.php',
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
	<td width="50">ID</td><td width="10">:</td>
	<td><?php echo $id; ?></td>
</tr>
<tr>
	<td>App</td><td>:</td>
	<td>
		<select name="app_id" id="app_id">
			<option value=""> -- Pilih -- </option>
			<?php
			$obj = $conn->Execute("SELECT APP_ID, APP_NAME FROM APPLICATIONS ORDER BY APP_ID ASC");
			
			while( ! $obj->EOF)
			{
				$ov = $obj->fields['APP_ID'];
				$on = $obj->fields['APP_NAME'];
				echo "<option value='$ov' " . is_selected($ov, $app_id) . "> $on ($ov) </option>";
				$obj->movenext();
			}
			?>
		</select>
	</td>
</tr>
<tr>
	<td>Modul</td><td>:</td>
	<td><input type="text" name="modul_name" id="modul_name" size="50" value="<?php echo $modul_name; ?>"></td>
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
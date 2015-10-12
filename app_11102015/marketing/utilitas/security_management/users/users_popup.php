<?php
require_once('users_proses.php');
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
var this_base = base_marketing + 'utilitas/security_management/users/';

jQuery(function($) {
	
	$('#user_id').inputmask('varchar', { repeat: 5 });
	$('#login_id').inputmask('varchar', { repeat: 10 });
	$('#password_id').inputmask('varchar', { repeat: 20 });
	$('#full_name').inputmask('varchar', { repeat: 40 });
	
	$('#tutup').on('click', function(e) {
		e.preventDefault();
		parent.loadData();
	});
	
	$('#simpan').on('click', function(e) {
		e.preventDefault();
		var url		= this_base + 'users_proses.php',
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
	<td width="50">User ID</td><td width="10">:</td>
	<td><input type="text" name="user_id" id="user_id" size="15" value="<?php echo $user_id; ?>"></td>
</tr>
<tr>
	<td>Login ID</td><td>:</td>
	<td><input type="text" name="login_id" id="login_id" size="25" value="<?php echo $login_id; ?>"></td>
</tr>
<tr>
	<td>Password</td><td>:</td>
	<td><input type="password" name="passowrd_id" id="passowrd_id" size="25" value="<?php echo $passowrd_id; ?>"></td>
</tr>
<tr>
	<td>Nama</td><td>:</td>
	<td><input type="text" name="full_name" id="full_name" size="50" value="<?php echo $full_name; ?>"></td>
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
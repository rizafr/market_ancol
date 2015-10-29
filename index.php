<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PT JAYA ANCOL PEMBANGUNAN Tbk.</title>
<link type="image/x-icon" rel="icon" href="../images/ancol.png">

<!-- CSS -->
<link type="text/css" href="config/css/style.css" rel="stylesheet">
<link type="text/css" href="config/css/menu.css" rel="stylesheet">
<link type="text/css" href="plugin/css/zebra/default.css" rel="stylesheet">
<link type="text/css" href="plugin/window/themes/default.css" rel="stylesheet">
<link type="text/css" href="plugin/window/themes/mac_os_x.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="config/js/menu.js"></script>
<script type="text/javascript" src="plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="plugin/js/keymaster.js"></script>
<script type="text/javascript" src="plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="plugin/js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="plugin/window/javascripts/prototype.js"></script>
<script type="text/javascript" src="plugin/window/javascripts/window.js"></script>
<script type="text/javascript" src="config/js/main.js"></script>
<script type="text/javascript">
jQuery(function($) {
	//$('#app_id').focus();
	
	$('#login').on('click', function(e) {
		e.preventDefault();
		
		var //app_id = $('#app_id').val(),
			//db = $('#db').val(),
			login_id = $('#login_id').val(),
			password_id = $('#password_id').val();
			
		//if (app_id == '') { alert('Pilih App.'); $('#app_id').focus(); return false; } 
		if (db == '') { alert('Pilih Database.'); $('#db').focus(); return false; } 
		else if (login_id == '') { alert('Masukkan kode user.'); $('#login_id').focus(); return false; } 
		else if (password_id == '') { alert('Masukkan Password.'); $('#password_id').focus(); return false; }
		
		var url		= base_app + 'authentic.php?act=login',
			data	= $('#form-login').serialize();
		
		$.post(url, data, function(result) {
			/*alert(result.msg);*/
			if (result.error == false) {
				location.href = base_app;
			}
		}, 'json');
		
		return false;
	});
});
</script>

</head>
<body>
<div id="wrapper">
	<div id="header">
		<span class="market">
			<span class="big">S</span>istem Administrasi Penjualan Properti
		</span>
	</div>
	<div id="content">
		<div class="clear"></div>
		<form id="form-login" method="post">
			<div class="title-page text-center">Login Form</div>
			<table class="t-control w100">
			<input type="hidden" name="app_id" id="app_id" value="M">		
			<tr>
				<td><label for="db">Database</label></td>
				<td>
					<select name="db" id="db" class="w100">
						<option value=""> -- Database -- </option>
						<option value="ANCOL"> ANCOL </option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td><label for="login_id">Kode Anda</label></td>
				<td><input type="text" name="login_id" id="login_id" class="w95" autocomplete="off"></td>
			</tr>
			
			<tr>
				<td><label for="password_id">Sandi Anda</label></td>
				<td><input type="password" name="password_id" id="password_id" class="w95"></td>
			</tr>
			
			<tr>
				<td colspan="2"><br><button type="submit" id="login" style="height:30px;" class="w100"><b>Login</b></button></td>
			</tr>
			</table>
		</form>
		<div class="clear"></div>
	</div>
</div>
<div id="footer">&copy; 2015 - PT. PEMBANGUNAN JAYA ANCOL, Tbk<br>Built By ASYS IT Consultant</div>
</body>
</html>
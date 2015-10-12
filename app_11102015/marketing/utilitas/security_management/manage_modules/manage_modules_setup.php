<script type="text/javascript">
var this_base = base_marketing + 'utilitas/security_management/manage_modules/';

jQuery(function($) {
	
	/* -- BUTTON -- */
	$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		loadData();
		return false;
	});
	
	$(document).on('change', '#s_user_id, #s_app_id', function(e) {
		e.preventDefault();
		$('#data-load').html('');
		return false;
	});
	
	
	$(document).on('click', '#tambah', function(e) {
		e.preventDefault();
		
		var url		= this_base + 'manage_modules_proses.php',
			data	= $('#form').serialize();
		
		$.post(url, data, function(result) {
			
			alert(result.msg);
			if (result.error == false) {
				loadData();
			}
		}, 'json');

		return false;
	});

});

function loadData() {
	if (popup) { popup.close(); }
	
	var s_user_id = jQuery('#s_user_id').val(),
		s_app_id = jQuery('#s_app_id').val();
	
	if (s_user_id == '') {
		alert('Pilih user.');
		jQuery('#s_user_id').focus();
		return false;
	} else if (s_app_id == '') {
		alert('Pilih app.');
		jQuery('#s_app_id').focus();
		return false;
	}
	
	var data = jQuery('#form').serialize();
	jQuery('#data-load').load(this_base + 'manage_modules_load.php', data);
	return false;
}


</script>

<div class="title-page">SECURITY MANAGEMENT (MANAGE MODULS)</div>

<form name="form" id="form" method="post">
<table class="t-control">
<tr>
	<td width="100">User</td><td width="10">:</td>
	<td>
		<select name="s_user_id" id="s_user_id">
			<option value=""> -- Pilih -- </option>
			<?php
			$obj = $conn->Execute("SELECT USER_ID, LOGIN_ID, FULL_NAME FROM USER_APPLICATIONS ORDER BY FULL_NAME ASC");
			
			while( ! $obj->EOF)
			{
				$ov = $obj->fields['USER_ID'];
				$on = $obj->fields['FULL_NAME'];
				$ot = $obj->fields['LOGIN_ID'];
				echo "<option value='$ov'> $on ($ot) </option>";
				$obj->movenext();
			}
			?>
		</select>
	</td>
</tr>

<tr>
	<td>App</td><td>:</td>
	<td>
		<select name="s_app_id" id="s_app_id">
			<option value=""> -- Pilih -- </option>
			<?php
			$obj = $conn->Execute("SELECT APP_ID, APP_NAME FROM APPLICATIONS ORDER BY APP_ID ASC");
			
			while( ! $obj->EOF)
			{
				$ov = $obj->fields['APP_ID'];
				$on = $obj->fields['APP_NAME'];
				echo "<option value='$ov'> $on ($ov) </option>";
				$obj->movenext();
			}
			?>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2"></td>
	<td>
		<input type="button" id="apply" value=" Apply ">
	</td>
</tr>
</table>

<div id="data-load"></div>
</form>
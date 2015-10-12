<div class="title-page">PARAMETER PROGRAM</div>

<form name="form" id="form" method="post">

<script type="text/javascript">
jQuery(function($) {
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url = base_marketing + 'utilitas/parameter/parameter_proses.php',
			data = $('#form').serialize();
				
		if (confirm("Apakah data parameter akan dirubah?") == false)
		{
			return false;
		}	
		
		$.post(url, data, function(data) {
			alert(data.msg);
			loadData();
		}, 'json');
		
		return false;
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#main_parameter').load(base_marketing + 'utilitas/parameter/parameter_load.php', data);	
	return false;
}
</script>

	<div class="t-control" id="main_parameter"></div>
	
	<div>
		<table class="t-form">
		<tr><td><br></td></tr>
		<tr>
			<td>
				<input type="submit" id="save" value=" Ubah ">
				<input type="reset" id="reset" value=" Reset ">
			</td>
		</tr>
		</table>
	</div>
</form>

<?php close($conn); ?>
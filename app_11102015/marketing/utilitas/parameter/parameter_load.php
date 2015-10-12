<?php
require_once('../../../../config/config.php');
die_login();
//die_app('M');
die_mod('M26');
$conn = conn($sess_db);
die_conn($conn);

$query = "SELECT * FROM CS_PARAMETER_MARK";
$obj = $conn->Execute($query);
?>

<script type="text/javascript">
jQuery(function($) {
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
});
</script>

<table class="t-form">
	<tr>
		<td width="150">Batas Distribusi</td><td width="1">:</td>
		<td><input type="text" name="batas_distribusi" id="batas_distribusi" size="20" value="<?php echo $obj->fields['BATAS_DISTRIBUSI']; ?>"></td>
	</tr>
	<tr>
		<td width="150">Tenggang Distribusi</td><td width="1">:</td>
		<td><input type="text" name="tenggang_distribusi" id="tenggang_distribusi" size="20" value="<?php echo $obj->fields['TENGGANG_DISTRIBUSI']; ?>"></td></td>
	</tr>
	<tr>
		<td width="150">Batas Reserve</td><td width="1">:</td>
		<td><input type="text" name="batas_reserve" id="batas_reserve" size="20" value="<?php echo $obj->fields['BATAS_RESERVE']; ?>"></td></td>
	</tr>
	<tr>
		<td class="td-action" colspan="3"><br>
		<input type="hidden" id="act" name = 'act' value="">
		<input type="submit" id="save" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
		</td>
	</tr>	
</table>

<?php
close($conn);
exit;
?>
<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('A');
die_mod('A05');
$conn = conn($sess_db);
die_conn($conn);

$s_user_id = (isset($_REQUEST['s_user_id'])) ? clean($_REQUEST['s_user_id']) : '';
$s_app_id	= (isset($_REQUEST['s_app_id'])) ? clean($_REQUEST['s_app_id']) : '';

$query_search = '';
$and = '';
if ($s_user_id != '') {
	$query_search .= " $and r.USER_ID = '$s_user_id' "; $and = " AND ";
}
if ($s_app_id != '') {
	$query_search .= " $and m.APP_ID = '$s_app_id' ";
}
if ($query_search != '') {
	$query_search = " WHERE " . $query_search;
}

?>

<table id="pagging-1" class="t-control">
<tr>
	<td><input type="submit" id="simpan" value=" Simpan "></td>
</tr>
</table>

<table class="t-data">
<tr>
	<th>ID</th>
	<th>MODUL</th>
	<th>READ ONLY <input type="checkbox" id="cb_ronly"></th>
	<th>EDIT <input type="checkbox" id="cb_edit"></th>
	<th>INSERT <input type="checkbox" id="cb_insert"></th>
	<th>DELETE <input type="checkbox" id="cb_delete"></th>
</tr>

<?php
	$query = "
	SELECT 
		r.MODUL_ID, 
		
		m.MODUL_NAME, 
		r.R_RONLY,
		r.R_EDIT,
		r.R_INSERT,
		r.R_DELETE
	FROM 
		APPLICATION_RIGHTS r
		LEFT JOIN USER_APPLICATIONS u ON r.USER_ID = u.USER_ID
		LEFT JOIN APPLICATION_MODULS m ON r.MODUL_ID = m.MODUL_ID
	$query_search
	ORDER BY m.MODUL_ID ASC
	";
	
	$obj = $conn->Execute($query);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['MODUL_ID'];
		?>
		<tr> 
			<td>
				<?php echo $obj->fields['MODUL_ID']; ?>
				<input type="hidden" name="ar_modul_id[]" value="<?php echo $id; ?>">
			</td>
			<td><?php echo $obj->fields['MODUL_NAME']; ?></td>
			<td class="text-center">
				<input type="checkbox" name="r_ronly[<?php echo $id; ?>]" value="Y" class="cb_ronly" <?php echo is_checked('Y', $obj->fields['R_RONLY']); ?>>
			</td>
			<td class="text-center">
				<input type="checkbox" name="r_edit[<?php echo $id; ?>]" value="Y" class="cb_edit" <?php echo is_checked('Y', $obj->fields['R_EDIT']); ?>>
			</td>
			<td class="text-center">
				<input type="checkbox" name="r_insert[<?php echo $id; ?>]" value="Y" class="cb_insert" <?php echo is_checked('Y', $obj->fields['R_INSERT']); ?>>
			</td>
			<td class="text-center">
				<input type="checkbox" name="r_delete[<?php echo $id; ?>]" value="Y" class="cb_delete" <?php echo is_checked('Y', $obj->fields['R_DELETE']); ?>>
			</td>
		</tr>
		<?php
		$obj->movenext();
	}
?>
</table>

<table id="pagging-2" class="t-control"></table>

<script type="text/javascript">
jQuery(function($) {
	$('#pagging-2').html($('#pagging-1').html());
	t_strip('.t-data');
});
</script>

<?php
close($conn);
exit;
?>
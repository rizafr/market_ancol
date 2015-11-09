<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('A');
die_mod('A04');
$conn = conn($sess_db);
die_conn($conn);


$s_user_id 	= (isset($_REQUEST['s_user_id'])) ? clean($_REQUEST['s_user_id']) : '';
$s_app_id	= (isset($_REQUEST['s_app_id'])) ? clean($_REQUEST['s_app_id']) : '';

$query_search = '';
if ($s_app_id != '') {
	$query_search .= " WHERE m.APP_ID = '$s_app_id' ";
}
?>

<table id="pagging-1" class="t-control">
<tr>
	<td>
		<input type="button" id="tambah" value=" Simpan ">
	</td>
</tr>
</table>

<table class="t-data">
<tr>
	<th>ID</th>
	<th>APP</th>
	<th>MENUS</th>
	<th>STATUS</th>
</tr>

<?php

	$query = "
	
	SELECT 
		m.MENU_ID, 
		a.APP_NAME,
		m.MENU_NAME
	FROM 
		MENUS m
		LEFT JOIN APPLICATIONS a ON a.APP_ID = m.APP_ID
	$query_search
	ORDER BY m.APP_ID, m.MENU_ID ASC
	";
	
	$obj = $conn->Execute($query);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['MENU_ID'];
		?>
		<tr> 
			<td>
				<?php echo $obj->fields['MENU_ID']; ?>
				<input type="hidden" name="ar_menu_id[]" value="<?php echo $id; ?>">
			</td>
			<td><?php echo $obj->fields['APP_NAME']; ?></td>
			<td><?php echo $obj->fields['MENU_NAME']; ?></td>
			
			<?php
			$obj2 = $conn->Execute("SELECT COUNT(MENU_ID) AS TOTAL FROM APPLICATION_MENU WHERE MENU_ID = '$id' AND USER_ID = '$s_user_id'");
			$total	= $obj2->fields['TOTAL'];
			?>
			<td class="text-center">
				<input type="checkbox" name="akses_modul[<?php echo $id; ?>]" value="<?php echo $id; ?>" class="akses_modul" <?php echo is_checked(1, $total); ?>>
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
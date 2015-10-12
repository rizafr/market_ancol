<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('A');
die_mod('A02');
$conn = conn($sess_db);
die_conn($conn);


$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1 = (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1	= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';
$s_app_id	= (isset($_REQUEST['s_app_id'])) ? clean($_REQUEST['s_app_id']) : '';

$query_search = '';
$and = '';
if ($s_opv1 != '') {
	$query_search .= " $s_opf1 LIKE '%$s_opv1%' "; $and = " AND ";
}
if ($s_app_id != '') {
	$query_search .= " $and a.APP_ID LIKE '%$s_app_id%' "; $and = " AND ";
}
if ($query_search != '') {
	$query_search = " WHERE " . $query_search;
}

# Pagination
$query = "
SELECT 
	COUNT(m.MODUL_ID) AS TOTAL
FROM 
	APPLICATION_MODULS m
	LEFT JOIN APPLICATIONS a ON a.APP_ID = m.APP_ID
$query_search
";
$total_data = $conn->Execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control">
<tr>
	<td class="text-right">
		<input type="button" id="prev_page" value=" < ">
		Hal : <input type="text" name="page_num" size="5" class="page_num apply text-center" value="<?php echo $page_num; ?>">
		Dari <?php echo $total_page ?> 
		<input type="hidden" id="total_page" value="<?php echo $total_page; ?>">
		<input type="button" id="next_page" value=" > ">
	</td>
</tr>
</table>

<table class="t-data">
<tr>
	<th>ID</th>
	<th>APP</th>
	<th>MODUL</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		m.MODUL_ID, 
		a.APP_NAME,
		m.MODUL_NAME
	FROM 
		APPLICATION_MODULS m
		LEFT JOIN APPLICATIONS a ON a.APP_ID = m.APP_ID
	$query_search
	ORDER BY m.APP_ID, m.MODUL_ID ASC
	";
	
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['MODUL_ID'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['APP_NAME']; ?></td>
			<td><?php echo $obj->fields['MODUL_NAME']; ?></td>
		</tr>
		<?php
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control"></table>

<script type="text/javascript">
jQuery(function($) {
	$('#pagging-2').html($('#pagging-1').html());
	
	$('#total-data').html('<?php echo $total_data; ?>');
	$('#per_page').val('<?php echo $per_page; ?>');
	$('.page_num').inputmask('integer');
	t_strip('.t-data');
});
</script>

<?php
close($conn);
exit;
?>
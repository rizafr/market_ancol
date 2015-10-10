<?php
require_once('../../../../config/config.php');
$conn = conn($sess_db);
die_conn($conn);

$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
?>

<table class="t-data w100">
<tr>
	<th>NO.</th>
	<th>KODE BLOK</th>
	<th>NAMA CALON PEMBELI</th>
	<th>TANGGAL RESERVE</th>
	<th>BERLAKU SAMPAI</th>
</tr>

<?php
	$query = "
	SELECT *
	FROM 
		RESERVE
	WHERE KODE_BLOK = '$id'
	ORDER BY TANGGAL_RESERVE
	";
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_CALON_PEMBELI'];  ?></td>
			<td><?php echo tgltgl(f_tgl($obj->fields['TANGGAL_RESERVE']));  ?></td>			
			<td><?php echo tgltgl(f_tgl($obj->fields['BERLAKU_SAMPAI']));  ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
?>
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
</table>

<script type="text/javascript">
jQuery(function($) {
	t_strip('.t-data');
});
</script>

<?php
close($conn);
exit;
?>
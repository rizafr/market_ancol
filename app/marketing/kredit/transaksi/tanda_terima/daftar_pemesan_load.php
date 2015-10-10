<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
die_mod('K03');
$conn = conn($sess_db);
die_conn($conn);

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search = '';
if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= "WHERE TANGGAL_RESERVE >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL_RESERVE <= CONVERT(DATETIME,'$periode_akhir',105)";
}

/* Pagination */

$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	RESERVE
$query_search
";

$total_data = $conn->execute($query)->fields['TOTAL'];
/* End Pagination */
?>

<table class="t-nowrap t-data">
<tr>
	<th class="w5">NO.</th>
	<th class="w10">KODE BLOK</th>
	<th class="w20">NAMA PEMESAN</th>
	<th class="w10">TANGGAL</th>
	<th class="w10">AGEN</th>
	<th class="w10">KOORDINATOR</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT *
	FROM 
		RESERVE
	$query_search
	ORDER BY TANGGAL_RESERVE
	";

	$obj = $conn->execute($query);
	$i = 1;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"
		data-kode_blok="<?php echo $obj->fields['KODE_BLOK']; ?>"
		data-nama_pembayar="<?php echo $obj->fields['NAMA_CALON_PEMBELI']; ?>"
		data-alamat="<?php echo $obj->fields['ALAMAT']; ?>"
		data-telepon="<?php echo $obj->fields['TELEPON']; ?>"
		data-agen="<?php echo $obj->fields['AGEN']; ?>"
		data-koordinator="<?php echo $obj->fields['KOORDINATOR']; ?>"
		> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_CALON_PEMBELI']; ?></td>
			<td><?php echo f_tgl($obj->fields['TANGGAL_RESERVE']); ?></td>
			<td><?php echo $obj->fields['AGEN']; ?></td>
			<td><?php echo $obj->fields['KOORDINATOR']; ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}	
}
?>
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
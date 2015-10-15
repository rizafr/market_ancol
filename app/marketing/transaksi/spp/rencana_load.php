<?php
require_once('../../../../config/config.php');
//require_once('spp_proses.php');
$conn = conn($sess_db);
die_conn($conn);

$blok				= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
//$act				= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';

$query = "SELECT POLA_BAYAR	FROM SPP WHERE KODE_BLOK = '$blok'";
$obj = $conn->execute($query);
?>

<div class="title-page">POLA PEMBAYARAN <?php echo $obj->fields['POLA_BAYAR'];  ?></div>
<table class="t-data w100">
<tr>
	<th class="w10">NO.</th>
	<th class="w30">TANGGAL</th>
	<th class="w30">JENIS PEMBAYARAN</th>
	<th class="w30">NILAI (RP)</th>
</tr>

<?php
	$query = "
	SELECT *
	FROM 
		RENCANA a
	LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE a.KODE_BLOK = '$blok'
	ORDER BY a.TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 1;
	$total = 0;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr>
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo tgltgl(f_tgl($obj->fields['TANGGAL'])); ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);$total+=intval($obj->fields['NILAI']);  ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}

	
?>
<tr>
	<td class="text-left" colspan="3">TOTAL</td>
	<td class="text-right"><?php echo to_money($total);?></td>
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
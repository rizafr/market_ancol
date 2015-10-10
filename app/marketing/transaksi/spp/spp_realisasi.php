<?php
require_once('spp_proses.php');
require_once('../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<script type="text/javascript">
jQuery(function($) {
	t_strip('.t-data');
	
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});	
});

</script>

<div class="clear"><br></div>

<table class="t-data w100">
<tr>
	<th class="w5">NO.</th>
	<th class="w15">TANGGAL</th>
	<th class="w15">PEMBAYARAN</th>
	<th class="w15">NILAI (RP)</th>
</tr>

<?php
	$query = "
	SELECT *
	FROM 
		REALISASI a
	LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE a.KODE_BLOK = '$id'
	ORDER BY a.TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		$id = tgltgl(f_tgl($obj->fields['TANGGAL']));
		?>
		<tr class="onclick" id="<?php echo $id; ?>">			
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
?>
</table>

<?php
close($conn);
exit;
?>
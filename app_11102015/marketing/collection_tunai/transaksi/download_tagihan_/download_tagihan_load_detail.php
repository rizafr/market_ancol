<?php
require_once('../../../../../config/config.php');
$conn = conn($sess_db);
die_conn($conn);

$id			= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$act		= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$bulan		= (isset($_REQUEST['bulan'])) ? clean($_REQUEST['bulan']) : '';

$pecah_tanggal		= explode("-",$bulan);
$bln 				= $pecah_tanggal[0];
$thn 				= $pecah_tanggal[1];

//bulan depan
$next_bln	= $bln + 1;
$next_thn	= $thn;
if($bln > 12)
{
	$next_bln	= 1;
	$next_thn	= $thn + 1;
}
?>

<table class="t-data w100">
<tr>
	<th class="w5"><input type="checkbox" id="cb_all"></th>
	<th class="w5">No.</th>
	<th class="w30">JENIS PEMBAYARAN</th>
	<th class="w25">JUMLAH (Rp)</th>
</tr>

<?php

	$query = "SELECT * FROM TAGIHAN_LAIN_LAIN a LEFT JOIN JENIS_PEMBAYARAN b
	on a.KODE_BAYAR = b.KODE_BAYAR where A.KODE_BLOK = '$id'
	and TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105) 
	AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
	AND a.KODE_BAYAR != 9";

	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		$id 	= $obj->fields['KODE_BAYAR'];
		
		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td> 			
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
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
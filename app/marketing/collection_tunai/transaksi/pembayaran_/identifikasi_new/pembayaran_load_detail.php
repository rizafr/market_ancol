<?php
require_once('../../../../../config/config.php');
$conn = conn($sess_db);
die_conn($conn);

$id				= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$tanggal_bayar	= (isset($_REQUEST['tanggal_bayar'])) ? clean($_REQUEST['tanggal_bayar']) : '';
?>

<table class="t-data w100">
<tr>
	<th class="w5">NO.</th>
	<th class="w10">TANGGAL PEMBAYARAN</th>
	<th class="w20">JENIS PEMBAYARAN</th>
	<th class="w30">JUMLAH (Rp)</th>
</tr>

<?php

	$pecah		= explode("-",f_tgl (date("Y-m-d")));
	$tgl		= $pecah[0];
	$bln 		= $pecah[1];
	$thn 		= $pecah[2];
	
	//bulan depan
	$next_bln	= $bln + 1;
	$next_thn	= $thn;
	if($bln > 12)
	{
		$next_bln	= 1;
		$next_thn	= $thn + 1;
	}
	
	$jumlah 	= 0;

	//menampilkan angsuran
	$query = "	SELECT * FROM TAGIHAN WHERE KODE_BLOK = '$id' 
	AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105) AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
	ORDER BY TANGGAL";
	
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		?>
		<tr>
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo date("d M Y", strtotime($tanggal_bayar));  ?></td>
			<td class="text-left">ANGSURAN</td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
		</tr>
		<?php
		$jumlah  = $jumlah + $obj->fields['NILAI'];
		$i++;
		$obj->movenext();
	}
	
	//menampilkan denda dan pembayaran lain-lain
	$query = "SELECT * FROM TAGIHAN_LAIN_LAIN a
	JOIN JENIS_PEMBAYARAN b
	ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE a.KODE_BLOK = '$id' AND a.TANGGAL = CONVERT(DATETIME,'01-$bln-$thn',105)";
	
	$obj = $conn->execute($query);

	while( ! $obj->EOF)
	{
		?>
		<tr>
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo date("d M Y", strtotime($tanggal_bayar));  ?></td>
			<td class="text-left"><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
		</tr>
		<?php
		$jumlah  = $jumlah + $obj->fields['NILAI'];
		$i++;
		$obj->movenext();
	}
	?>
	
	<tr>
		<td></td>
		<td></td>
		<td>TOTAL TERIDENTIFIKASI</td>
		<td class="text-right"><?php echo to_money($jumlah);  ?></td>
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
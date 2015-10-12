<?php
require_once('spp_tidak_valid_proses.php');
require_once('../../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
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
</head>
<body class="popup2">

<form name="form" id="form" method="post">
<table class="t-popup w35 f-left" style="margin-right:15px">
<tr>
	<td width="100">Blok / Nomor</td><td width="10">:</td>
	<td colspan="2"><?php echo $id; ?></td>
</tr>
<br>
<tr>
	<td>Luas Tanah</td></td><td>:</td>
	<td colspan="2"><?php echo $luas_tanah.' m&sup2;'; ?></td>
</tr>
<br>
	<td>Luas Bangunan</td></td><td>:</td>
	<td colspan="2"><?php echo $luas_bangunan.' m&sup2;'; ?></td>
</tr>
<br>
<tr>
	<td>Tipe Rumah</td></td><td>:</td>
	<<td colspan="2"><?php echo $tipe_bangunan; ?></td>
</tr>
<tr>
	<td>Harga Tanah</td></td><td>: </td><td>Rp.</td>
	<td class="text-right"><?php echo to_money($total_tanah,2); ?></td>
</tr>
<tr>
	<td>Harga Bangunan</td></td><td>: </td><td>Rp.</td>
	<td class="text-right"><?php echo to_money($total_bangunan,2); ?></td>
</tr>
<tr>
	<td>P.P.N. Tanah</td></td><td>: </td><td>Rp.</td>
	<td class="text-right"><?php echo to_money($ppn_tanah,2); ?></td>
</tr>
<tr>
	<td>P.P.N. Bangunan</td></td><td>: </td><td>Rp.</td>
	<td class="text-right"><?php echo to_money($ppn_bangunan,2); ?></td>
</tr>
<tr>
	<td></td></td><td></td>
	<td colspan="2"><hr></td>
</tr>
<tr>
	<td>Total Harga</td></td><td>: </td><td>Rp.</td>
	<td class="text-right"><?php echo to_money($sisa_pembayaran,2); ?></td>
</tr>
</table>

<table class="t-popup w60 f-right">
<tr>
	<td width="100">Nama</td></td><td width="10">:</td>
	<td><?php echo $nama; ?></td>
</tr>
<tr>
	<td>Alamat</td><td>:</td>
	<td><?php echo $alamat_rumah; ?></td>
</tr>
<tr>
	<td>Telp. / HP.</td><td>:</td>
	<td><?php echo $tlp_rumah.' / '.$tlp_kantor.' / '.$tlp_lain; ?></td>
</tr>
<tr>
	<td>No. SPP / Tgl.</td></td><td>:</td>
	<td><?php echo $no_spp.' / '.$tgl_spp; ?></td>
</tr>

</table>
<div class="clear"><br></div>

<table class="t-data w45 f-left">
<tr>
	<th colspan=3>RENCANA PENERIMAAN</th>	
</tr>

	<th class="w25">Tgl/Bln/Thn</th>
	<th class="w20">Angsuran</th>
	<th class="w55">Keterangan</th>
</tr>
<tr>

	<td><?php echo date("d-m-Y", strtotime($tgl_jadi)); ?></td>
	<td class="text-right"><?php echo to_money($tanda_jadi);  ?></td>
	<td>TANDA JADI</td>
</tr>

<?php
	$query = "
	SELECT *
	FROM 
		RENCANA a
	LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE KODE_BLOK = '$id'
	ORDER BY TANGGAL
	";
	$obj = $conn->execute($query);


	while( ! $obj->EOF)
	{
		?>
		<tr>

			<td><?php echo date("d-m-Y", strtotime($obj->fields['TANGGAL'])); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
		</tr>
		<?php

		$obj->movenext();
	}

?>
</table>
<?php ?>

<table class="t-data w55 f-right">
<tr>
	<th colspan=2>REALISASI PENERIMAAN</th>
</tr>
<tr>
	<th class="w25">Tgl/Bln/Thn</th>
	<th class="w20">Angsuran</th>
</tr>

<?php
	$query = "
	SELECT * 
		FROM 
	REALISASI 
		WHERE 
	KODE_BLOK = '$id'
	ORDER BY TANGGAL
	";

	$obj = $conn->execute($query);

	$nilai = 0;
	while( ! $obj->EOF)
	{
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>
			</tr>
		<?php
		$obj->movenext();
	}	
	$query = "
	SELECT SUM(NILAI) AS TOTAL FROM REALISASI WHERE KODE_BLOK = '$id'
	";
	$obj = $conn->execute($query);
?>

<tr>
	<th lass="text-center">Jumlah</th>
	<td class="text-right"><?php echo to_money($obj->fields['TOTAL']);  ?></td>
</tr>
<tr>
	<th lass="text-center">SISA</th>
	<td class="text-right"><?php echo to_money($sisa_pembayaran - $obj->fields['TOTAL']);  ?></td>
</tr>
</table>

<div class="clear"></div>

<div id="t-detail"></div>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>
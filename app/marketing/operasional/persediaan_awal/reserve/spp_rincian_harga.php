<?php
require_once('spp_proses.php');
require_once('../../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>


<table class="t-popup pad2 w80">
	<tr>
		<td width="100">Kode Blok</td><td width="10">:</td>
		<td width=""><b><?php echo $id; ?></b></td>
	</tr>
	<tr>
		<td>Jenis Unit</td><td>:</td>
		<td><?php echo $r_jenis_unit; ?></td>
	</tr>
	<tr>
		<td>Tower</td><td>:</td>
		<td><?php echo $r_lokasi; ?></td>
	</tr>

	<tr>
		<td>Tipe</td><td>:</td>
		<td><?php echo $r_tipe_bangunan; ?></td>
	</tr>
</table>

<table class="t-popup pad2 w80">

	<tr>
		<td>Luas Semigross</td><td>:</td>
		<td><?php echo to_decimal($r_luas_bangunan); ?> M&sup2;</td>
	</tr>
	<tr>
		<td>Cash Keras</td><td>:</td>
		<td>Rp. <?php echo to_money($harga_cash_keras); ?></td>
	</tr>
	<tr>
		<td>CB 36X</td><td>:</td>
		<td>Rp. <?php echo to_money($cb36x); ?></td>
	</tr>
	<tr>
		<td>CB 48X</td><td>:</td>
		<td>Rp. <?php echo to_money($cb48x); ?></td>
	</tr>
	<tr>
		<td>KPA 24X</td><td>:</td>
		<td>Rp. <?php echo to_money($kpa24x); ?></td>
	</tr>
	<tr>
		<td>KPA 36X</td><td>:</td>
		<td>Rp. <?php echo to_money($kpa36x); ?></td>
	</tr>

	<tr>
		<td class="td-action" colspan="10">
			<input type="button" id="close" value=" Tutup ">
		</td>
	</tr>
</table>

<?php
close($conn);
exit;
?>
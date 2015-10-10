<?php
require_once('spp_proses.php');
require_once('../../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<table class="t-popup pad2 w60">
<tr>
	<td width="100">Kode Blok</td><td width="10">:</td>
	<td width=""><b><?php echo $id; ?></b></td>
</tr>
<tr>
	<td>Jenis Unit</td><td>:</td>
	<td><?php echo $r_jenis_unit; ?></td>
</tr>
<tr>
	<td>Lokasi</td><td>:</td>
	<td><?php echo $r_lokasi; ?></td>
</tr>
<tr>
	<td>SK. Tanah</td><td>:</td>
	<td>Rp. <?php echo to_money($r_harga_tanah_sk); ?> / M&sup2;</td>
</tr>
<tr>
	<td>Faktor Strategis</td><td>:</td>
	<td><?php echo $r_faktor_strategis; ?></td>
	<td width="30"></td>
	<td width="100">Progres Bangunan</td><td>:</td>
	<td><?php echo to_decimal($r_progres); ?> %</td>
</tr>
<tr>
	<td>Tipe</td><td>:</td>
	<td><?php echo $r_tipe_bangunan; ?></td>
</tr>
<tr>
	<td>SK. Bangunan</td><td>:</td>
	<td>Rp. <?php echo to_money($r_harga_bangunan_sk); ?> / M&sup2;</td>
</tr>
</table>

<table class="t-popup pad2 w80">
<tr>
	<td width="120">Luas Tanah</td><td width="10">:</td>
	<td><?php echo to_decimal($r_luas_tanah); ?> M&sup2;</td>
	<td class="text-right">Rp. <?php echo to_money($r_base_harga_tanah); ?></td>
	<td width="40"></td>
	<td width="100">(+) <?php echo to_decimal($r_nilai_tambah); ?> % &nbsp;&nbsp; (-) <?php echo to_decimal($r_nilai_kurang); ?> %</td><td width="10">:</td>
	<td class="text-right">Rp. <?php echo to_money($r_fs_harga_tanah); ?></td>
<tr>
<tr>
	<td>Discount Tanah</td><td>:</td>
	<td><?php echo to_decimal($r_disc_tanah); ?> %</td>
	<td class="text-right">Rp. <?php echo to_money($r_disc_harga_tanah); ?></td>
<tr>
<tr>
	<td>PPN Tanah</td><td>:</td>
	<td><?php echo to_decimal($r_ppn_tanah); ?> %</td>
	<td class="text-right">Rp. <?php echo to_money($r_ppn_harga_tanah); ?></td>
	<td width="40"></td>
	<td>Harga Tanah</td><td>:</td>
	<td class="text-right">Rp. <?php echo to_money($r_harga_tanah); ?></td>
<tr>
<tr>
	<td>Luas Bangunan</td><td>:</td>
	<td><?php echo to_decimal($r_luas_tanah); ?> M&sup2;</td>
	<td class="text-right">Rp. <?php echo to_money($r_base_harga_bangunan); ?></td>
<tr>
<tr>
	<td>Discount Bangunan</td><td>:</td>
	<td><?php echo to_decimal($r_disc_tanah); ?> %</td>
	<td class="text-right">Rp. <?php echo to_money($r_disc_harga_bangunan); ?></td>
<tr>
<tr>
	<td>PPN Bangunan</td><td>:</td>
	<td><?php echo to_decimal($r_ppn_tanah); ?> %</td>
	<td class="text-right">Rp. <?php echo to_money($r_ppn_harga_bangunan); ?></td>
	<td width="40"></td>
	<td>Harga Bangunan</td><td>:</td>
	<td class="text-right">Rp. <?php echo to_money($r_harga_bangunan); ?></td>
<tr>
<tr>
	<td colspan="5"></td>
	<td colspan="3"><hr></td>
<tr>
<tr>
	<td colspan="5"></td>
	<td><b>TOTAL HARGA</b></td><td width="10">:</td>
	<td class="text-right"><b>Rp. <?php echo to_money($r_harga_tanah + $r_harga_bangunan); ?></b></td>
<tr>
<tr>
	<td colspan="5"></td>
	<td colspan="3"><hr></td>
<tr>
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
<?php
require_once('serah_terima_proses.php');
require_once('../../../../config/config.php');
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
	$('#nama').inputmask('varchar', { repeat: '60' });
	$('#no_bast').inputmask('varchar', { repeat: '30' });
	$('#email').inputmask('varchar', { repeat: '50' });
	$('#tlp_rumah, #tlp_kantor, #tlp_lain, #no_identitas').inputmask('varchar', { repeat: '30' });
	$('#npwp').inputmask('varchar', { repeat: '15' });
	$('#jml_kunci').inputmask('numeric', { repeat: '3' });
	$('#keterangan').inputmask('varchar', { repeat: '200' });
	$('#kontraktor').inputmask('varchar', { repeat: '40' });
});
</script>

<table>
<tr>
	<td class="text-left">Kode Blok</td>
    <td><b><input readonly="readonly" type="text" name="kode_blok" id="kode_blok" size="10" value="<?php echo $id; ?>"></b></td>
<tr>
	<td class="text-left">Diserahkan Kepada  </td>
	<td><input type="text" name="namapemilik" id="namapemilik" size="30" value="<?php echo $namapemilik; ?>"></td>
</tr>
<tr>
	<td class="text-left"><b>1. Berita Acara Serah Terima</b></td>
</tr>
<tr>
	<td width="280">No BAST :<td><input type="text" name="no_bast" id="no_bast" size="20" value="<?php echo $no_bast; ?>"></td>
<tr>
	<td>Tanggal :</td>
	<td><input type="text" name="tgl_bast" id="tgl_bast" size="10" class ="apply dd-mm-yyyy" value="<?php echo $tgl_bast; ?>"></td>
<tr>
    <td>Nama Pembeli :<td><input readonly="readonly" type="text" name="nama" id="nama" size="30" value="<?php echo $namapembeli; ?>">
   </td>
</tr>
<tr>
	<td class="text-left"><b>2. Tanggal Penyerahan</b></td>
</tr>
<tr>
	<td width="280">Kontraktor-proyek <td><input type="text" name="tglkonpro" id="tglkonpro" size="10" class="apply dd-mm-yyyy" value="<?php echo $tglkonpro; ?>">
<tr><td>Proyek-Purnajual <td><input type="text" name="tgl_propur" id="tgl_propur" size="10" class ="apply dd-mm-yyyy" value="<?php echo $tgl_propur; ?>">
<tr><td>Prgress Bangunan <td><input readonly="readonly" type="text" name="progress" id="progress" size="15" value="<?php echo $progress; ?>">
    </td>
</tr>
<tr>
	<td class="text-left"><b>3. Masa Berlaku Purnajual</b></td>
	<td><input type="text" name="masaberlaku" id="masaberlaku" size="10" class="apply dd-mm-yyyy" value="<?php echo $masaberlaku; ?>"></td>
</tr>
<tr>
	<td class="text-left"><b>4. Kunci</b></td>
</tr>
<tr>
	<td width="280" class="text-left">Jumlah Kunci <td><input type="text" name="jml_kunci" id="jml_kunci" size="5" value="<?php echo $jml_kunci; ?>"> Set</td>
<tr>
	<td>Anak Kunci <td>
	<input type="text" name="anak_kunci" id="anak_kunci" size="5" value="<?php echo $anak_kunci; ?>"></td>
<tr>
	<td width="280" class="text-left">Tanggal Serah Kunci <td><input type="text" name="tgl_serah_kunci" id="tgl_serah_kunci" class ="apply dd-mm-yyyy" size="10" value="<?php echo $tgl_serah_kunci; ?>"></td>
</tr>
<tr>
	<td class="text-left"><b>5. Lain-lain</b></td>
</tr>
<tr>
	<td class="text-left">Kontraktor </td>
	<td colspan="2"><input readonly="readonly" type="text" name="kontraktor" id="kontraktor" size="40" value="<?php echo $kontraktor; ?>"></td>	
</tr>
<tr>
	<td class="text-left">Keterangan </td>
	<td colspan="2"> <input readonly="readonly" type="text" name="keterangan" id="keterangan" size="100" value="<?php echo $keterangan; ?>"></td>	
</tr>
<tr>
	<td class="text-left"><b>6. Kelengkapan</b></td>
</tr>    
<tr>
	<td class="text-left"><input readonly="readonly" type="checkbox" name="sertifikatrayap" id="sertifikatrayap" class="status" value="1" <?php echo is_checked('1', $sertifikat_rayap); ?>> Sertifikat Rayap  	
	<input readonly="readonly" type="text" name="serrayaptgl" id="serrayaptgl" size="10" class ="apply dd-mm-yyyy" value="<?php echo $sertifikat_rayap_tgl; ?>"></td> 
	<td class="text-left"><input readonly="readonly" type="checkbox" name="asbuild" id="asbuild" class="status" value="1" <?php echo is_checked('1', $as_build); ?>> As Build Drawing 	
	<input readonly="readonly" type="text" name="asbuildtgl" id="asbuildtgl" size="10" class ="apply dd-mm-yyyy" value="<?php echo $as_build_drawing_tgl; ?>"></td>
	<td class="text-left"><input readonly="readonly" type="checkbox" name="imb" id="imb" class="status" value="1" <?php echo is_checked('1', $imb); ?>> IMB  	
	<input readonly="readonly" type="text" name="imb_tgl" id="imb_tgl" size="10" class ="apply dd-mm-yyyy" value="<?php echo $imb_tgl; ?>"></td>
</tr>
<tr>
	<td class="text-left"><b>7. Listrik, Pompa/PAM dan Telp</b></td>
</tr>    
<tr>
	<td width="280" class="text-left">No Kontrak : <td><input readonly="readonly" type="text" name="nokontrak" id="nokontrak" size="18" value="<?php echo $no_kontrak; ?>"></td>
<tr>
	<td>Watt :
	<td><input readonly="readonly" type="text" name="watt" id="watt" size="5" value="<?php echo $watt; ?>"></td>
<tr><td>No Kontrol :<td><input readonly="readonly" type="text" name="nokontrol" id="nokontrol" size="10" value="<?php echo $no_kontrol; ?>"></td>
</tr>
<tr>
	<td width="280" class="text-left">Inkaso <td><input readonly="readonly" type="text" name="inkaso" id="inkaso" size="20" value="<?php echo $inkaso; ?>"></td>
<tr>
	<td>Pompa/PAM Terpasang :
	<td><input readonly="readonly" type="text" name="pompapam" id="pompapam" size="10" class="apply dd-mm-yyyy" value="<?php echo $pompapam; ?>"></td>
<tr>
	<td>No Telepon <td><input readonly="readonly" type="text" name="notlp" id="notlp" size="15" value="<?php echo $no_tlp; ?>"></td>
</tr>
<tr>
	<td class="text-left"><b>8. Total Harga</b></td>
</tr>    
<tr>
	<td width="280" class="text-left"><b>Total Harga Bangunan : Rp. </b></td><td><input readonly="readonly" type="text" name="totalharga" id="totalharga" size="20" value="<?php echo to_money($r_harga_tanah + $r_harga_bangunan); ?>"></b></td>
</tr>
<tr>
	<td class="td-action" colspan="10"><br>
	<input type="submit" id="save" value=" <?php echo $act; ?> ">tr
	<input type="reset" id="reset" value=" Reset ">
	<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
</table>

<?php
close($conn);
exit;
?>
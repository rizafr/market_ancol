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
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
	$('#nama').inputmask('varchar', { repeat: '60' });
	$('#alamat_rumah, #alamat_surat, #alamat_npwp').inputmask('varchar', { repeat: '110' });
	$('#email').inputmask('varchar', { repeat: '50' });
	$('#tlp_rumah, #tlp_kantor, #tlp_lain, #no_identitas').inputmask('varchar', { repeat: '30' });
	$('#npwp').inputmask('varchar', { repeat: '15' });
	$('#jumlah_kpr, #tanda_jadi').inputmask('numeric', { repeat: '16' });
	$('#keterangan').inputmask('varchar', { repeat: '150' });
});
</script>

<table class="t-popup pad2 w100">
<tr>
	<td width="200"></td>
	<td class="text-right">No. Customer : <input readonly="readonly" type="text" name="no_customer" id="no_customer" size="10" value=""></td>
	<td class="text-right">Tgl / No. SPP : <input type="text" name="tgl_spp" id="tgl_spp" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_spp; ?>"> / <input readonly="readonly" type="text" name="no_spp" id="no_spp" size="5" value="<?php echo to_money($no_spp); ?>"></td>
</tr>
</table>

<table class="t-popup pad2 w100">
<tr>
	<td><b>Kode Blok</b></td><td>:</td>
	<td><input readonly="readonly" type="text" name="kode_blok" id="kode_blok" size="10" value="<?php echo $id; ?>"></td>
	<td> Nama : <input type="text" name="nama" id="nama" size="60" value="<?php echo $nama; ?>"></td>
</tr>
<tr>
	<td>Alamat Rumah</td><td>:</td>
	<td colspan="2"><input type="text" name="alamat_rumah" id="alamat_rumah" size="110" value="<?php echo $alamat_rumah; ?>"></td>
</tr>
<tr>
	<td>Alamat Surat</td><td>:</td>
	<td colspan="2"><input type="text" name="alamat_surat" id="alamat_surat" size="110" value="<?php echo $alamat_surat; ?>"></td>
</tr>
<tr>
	<td>Alamat NPWP</td><td>:</td>
	<td colspan="2"><input type="text" name="alamat_npwp" id="alamat_npwp" size="110" value="<?php echo $alamat_npwp; ?>"></td>
</tr>
<tr>
	<td>Alamat Email</td><td>:</td>
	<td colspan="2"><input type="text" name="email" id="email" size="50" value="<?php echo $email; ?>"></td>
</tr>
</table>

<table class="t-popup pad2 w100">
<tr>
	<td>Telepon Rumah :<td>
	<td>Telepon Kantor :<td>
	<td>Telepon Lain :</td>
</tr>
<tr>
	<td><input type="text" name="tlp_rumah" id="tlp_rumah" size="30" value="<?php echo $tlp_rumah; ?>"><td>
	<td><input type="text" name="tlp_kantor" id="tlp_kantor" size="30" value="<?php echo $tlp_kantor; ?>"><td>
	<td><input type="text" name="tlp_lain" id="tlp_lain" size="30" value="<?php echo $tlp_lain; ?>"></td>
</tr>
</table>

<table class="t-popup pad2 w100">
<tr>
	<td width="290">Identitas :
		<input type="radio" name="identitas" id="ktp" value="1" <?php echo is_checked('1', $identitas); ?>>KTP
		<input type="radio" name="identitas" id="sim" value="2" <?php echo is_checked('2', $identitas); ?>>SIM   
		<input type="radio" name="identitas" id="pasport" value="3" <?php echo is_checked('3', $identitas); ?>>Pasport
		<input type="radio" name="identitas" id="kims" value="4" <?php echo is_checked('4', $identitas); ?>> KIMS
	</td>
	<td>No. : <input type="text" name="no_identitas" id="no_identitas" size="20" value="<?php echo $no_identitas; ?>"><td>
</tr>
</table>

<table class="t-popup pad2 w100">
<tr>
	<td width="280">NPWP : <input type="text" name="npwp" id="npwp" size="20" value="<?php echo $npwp; ?>"><td>
	<td>Jenis : 
	<select name="jenis_npwp" id="jenis_npwp">
		<option value="">   -- Jenis --   </option>
		<option value="1" <?php echo is_selected('1', $jenis_npwp); ?>>Non PKP</option>
		<option value="2" <?php echo is_selected('2', $jenis_npwp); ?>>PKP</option>
	</select>
	</td>
	<td class="text-right">Bank : 
	<select name="bank" id="bank">
		<option value=""> -- Bank -- </option>
		<?php
		$obj = $conn->execute("
		SELECT *
		FROM 
			BANK
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['KODE_BANK'];
			$oj = $obj->fields['NAMA_BANK'];
			echo "<option value='$ov'".is_selected($ov, $bank)."> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	</td>
</tr>
</table>

<table class="t-popup pad2 w100">
<tr>
	<td>Agen : <td>
	<td>Koordinator : <td>
	<td class="text-right">Jumlah KPR : <input type="text" name="jumlah_kpr" id="jumlah_kpr" size="20" value="<?php echo $jumlah_kpr; ?>"></td>
</tr>
<tr>
	<td>
	<select name="agen" id="agen">
		<option value=""> -- Agen -- </option>
		<?php
		$obj = $conn->execute("		
			SELECT * FROM CLUB_PERSONAL
			WHERE JABATAN_KLUB = 5
			ORDER BY NAMA 
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['NOMOR_ID'];
			$oj = $obj->fields['NAMA'];
			echo "<option value='$ov'".is_selected($ov, $agen)."> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	<td>
	<td>
	<select name="koordinator" id="koordinator">
		<option value=""> -- Koordinator -- </option>
		<?php
		$obj = $conn->execute("		
			SELECT * FROM CLUB_PERSONAL
			WHERE JABATAN_KLUB = 4
			ORDER BY NAMA 
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['NOMOR_ID'];
			$oj = $obj->fields['NAMA'];
			echo "<option value='$ov'".is_selected($ov, $koordinator)."> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	<td>
	<td class="text-right">Tgl. Rencana Akad : <input type="text" name="tgl_akad" id="tgl_akad" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_akad; ?>"></td>
</tr>
</table>

<table class="t-popup pad2 w100">
<tr>
	<td>Status SPP : 
	<select name="status_kompensasi" id="status_kompensasi">
		<option value="">   -- Status SPP --   </option>
		<option value="1" <?php echo is_selected('1', $status_kompensasi); ?>>KPR</option>
		<option value="2" <?php echo is_selected('2', $status_kompensasi); ?>>TUNAI</option>
		<option value="3" <?php echo is_selected('3', $status_kompensasi); ?>>KOMPENSASI</option>
		<option value="4" <?php echo is_selected('4', $status_kompensasi); ?>>ASSET SETTLEMENT</option>
		<option value="5" <?php echo is_selected('5', $status_kompensasi); ?>>KPR JAYA</option>
	</select>
	</td>
	<td colspan="2" class="text-right">Tanda Jadi : <input type="text" name="tanda_jadi" id="tanda_jadi" size="20" value="<?php echo $tanda_jadi; ?>"></td>
</tr>
<tr>
	<td width="230">Distribusi SPP : 
		<input type="radio" name="status_spp" id="sudah" class="status" value="1" <?php echo is_checked('1', $status_spp); ?>>Sudah
		<input type="radio" name="status_spp" id="belum" class="status" value="2" <?php echo is_checked('2', $status_spp); ?>>Belum  
	</td>
	<td><input type="text" name="tgl_proses" id="tgl_proses" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_proses; ?>"></td>
	<td class="text-right">Tgl. Tanda Jadi : <input type="text" name="tgl_tanda_jadi" id="tgl_tanda_jadi" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_tanda_jadi; ?>"></td>
</tr>
<tr>
	<td colspan="3">Redistribusi SPP : 
	<select name="redistribusi" id="redistribusi">
		<option value="">   -- Redistribusi SPP --   </option>
		<option value="1" <?php echo is_selected('1', $redistribusi); ?>>Tidak</option>
		<option value="2" <?php echo is_selected('2', $redistribusi); ?>>Dalam Proses</option>
		<option value="3" <?php echo is_selected('3', $redistribusi); ?>>Selesai</option>
	</select>
	<input type="text" name="tgl_redistribusi" id="tgl_redistribusi" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_redistribusi; ?>">
	</td>
</tr>
</table>

<table class="t-popup pad2 w100">
<tr>
	<td width="100" class="text-right">Keterangan </td><td>:</td>
	<td><textarea name="keterangan" id="keterangan" rows="2" cols="100"><?php echo $keterangan; ?></textarea></td>
	
</tr>
<tr>
	<td class="td-action" colspan="10"><br>
	<input type="submit" id="save" value=" <?php echo $act; ?> ">
	<input type="reset" id="reset" value=" Reset ">
	<input type="button" id="close" value=" Tutup ">
	</td>
</tr>
</table>

<?php
close($conn);
exit;
?>
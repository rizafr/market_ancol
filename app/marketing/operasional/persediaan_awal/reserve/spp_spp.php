<?php
require_once('spp_proses.php');
require_once('../../../../../config/config.php');
require_once('../../../../../config/terbilang.php');
$terbilang = new Terbilang;
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);

$kode_blok	= $id;
$blok = explode("-", $kode_blok);
$no_unit = $blok[1];
$jml= strlen($blok[0]);
if($jml>2){
	$tower = substr($blok[0], 0,1);
	$tower = $terbilang->tower($tower);
	$lantai = substr($blok[0], 1,2);
}else{
	$tower = substr($blok[0], 0,1);
	$tower = $terbilang->tower($tower);
	$lantai = "0".substr($blok[0], 1,3);	
}
$costumer_id="SF".$tower.$lantai.$no_unit;

?>
<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

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
	$('#no_customer').inputmask('mask', { repeat: '15', mask : '9', groupSeparator : '', placeholder : '' });
	$('#tlp_rumah, #tlp_kantor, #tlp_lain, #no_identitas').inputmask('mask', { repeat: '30', mask : '9', groupSeparator : '', placeholder : '' });
	$('#npwp').inputmask('varchar', { repeat: '15' });
	$('#jumlah_kpr, #tanda_jadi').inputmask('numeric', { repeat: '16' });
	$('#keterangan').inputmask('varchar', { repeat: '150' });

});
</script>
<form name="form" id="form" method="post">
<table class="t-popup pad2 w100">
<tr>
	<td> Costumer ID </td><td>: <input readonly="readonly" type="text" name="costumer_id" id="costumer_id" size="15" value="<?php echo $costumer_id; ?>"></td>
</tr>
<tr>
	<td class="text-left"><b>No VA <td>: <input type="text" name="no_customer" id="no_customer" size="25" value="<?php echo $no_va; ?>"></td>
	<td class="text-right">Tgl / No. SPP : <input readonly="readonly" type="text" name="tgl_spp" id="tgl_spp" size="10" value="<?php echo date('d-m-Y') ;?>"> / <input readonly="readonly" type="text" name="no_spp" id="no_spp" size="5" value=""></td>
</tr>
<tr>
	<td><b>Kode Blok</b></td>
	<td>: <input readonly="readonly" type="text" name="kode_blok" id="kode_blok" size="25" value="<?php echo $id; ?>"></td>
</tr>
<tr>
	<td> Nama <td>: <input type="text" name="nama" id="nama" size="60" value="<?php echo $nm; ?>"></td>
</tr>
<tr>
	<td>Alamat Rumah<span class="error">*</span></td>
	<td colspan="2"> : <input type="text" name="alamat_rumah" id="alamat_rumah" size="110" value="<?php echo $alamat; ?>"></td>
</tr>
<tr>
	<td>Alamat Surat</td>
	<td colspan="2">: <input type="text" name="alamat_surat" id="alamat_surat" size="110" value="<?php echo $alamat; ?>"></td>
</tr>
<tr>
	<td>Alamat NPWP</td>
	<td colspan="2">: <input type="text" name="alamat_npwp" id="alamat_npwp" size="110" value="<?php echo $alamat; ?>"></td>
</tr>
<tr>
	<td>Alamat Email</td>
	<td colspan="2">: <input type="text" name="email" id="email" size="50" value="<?php echo $email; ?>"></td>
</tr>
<tr>
	<td>
		Telepon Rumah <td colspan="2">: <input type="text" name="tlp_rumah" id="tlp_rumah" size="30" value="<?php echo $tlp_rumah; ?>">
		Telepon Kantor : <input type="text" name="tlp_kantor" id="tlp_kantor" size="30" value="<?php echo $tlp_kantor; ?>">
		Hp<span class="error">*</span> : <input type="text" name="tlp_lain" id="tlp_lain" size="30" value="<?php echo $telepon; ?>">
	</td>
</tr>
<tr>
	<td width="100">Identitas<span class="error">*</span>
		<td><input type="radio" name="identitas" id="ktp" value="1" <?php echo is_checked('1', $identitas); ?> checked>KTP
		<input type="radio" name="identitas" id="sim" value="2" <?php echo is_checked('2', $identitas); ?>>SIM   
		<input type="radio" name="identitas" id="pasport" value="3" <?php echo is_checked('3', $identitas); ?>>Pasport
		<input type="radio" name="identitas" id="kims" value="4" <?php echo is_checked('4', $identitas); ?>> KIMS
	No.<span class="error">*</span> : <input type="text" name="no_identitas" id="no_identitas" size="20" value="<?php echo $no_ktp; ?>"><td>
</tr>
<tr>
	<td>NPWP<span class="error">*</span>  <td> : <input type="text" name="npwp" id="npwp" size="20" value="<?php echo $no_npwp; ?>">
	Jenis<span class="error">*</span> : 
	<select name="jenis_npwp" id="jenis_npwp">
		<option value="">   -- Jenis --   </option>
		<option value="1" <?php echo is_selected('1', $jenis_npwp); ?> selected >Non PKP</option>
		<option value="2" <?php echo is_selected('2', $jenis_npwp); ?>>PKP</option>
	</select>
	<td>Bank : 
	<select name="bank" id="bank">
		<option value="0"> -- Bank -- </option>
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
<tr>
	<td>Agen  <td> 
	: <select name="agen" id="agen">
		<option value=""> -- Agen -- </option>
		<?php
		$obj = $conn->execute("		
			SELECT * FROM CLUB_PERSONAL
			WHERE JABATAN_KLUB = 5
			ORDER BY NAMA 
		");
		while( ! $obj->EOF)
		{
			$selected = '';
			if($obj->fields['NOMOR_ID']==urldecode($agen)){
				$selected = 'selected';
			}
			$ov = $obj->fields['NOMOR_ID'];
			$oj = $obj->fields['NAMA'];
			echo "<option value='$ov' $selected> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	Koordinator<span class="error">*</span> :
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
			$selected= '';
			if($obj->fields['NOMOR_ID']==urldecode($koordinator)){
				$selected = 'selected';
			}
			$ov = $obj->fields['NOMOR_ID'];
			$oj = $obj->fields['NAMA'];
			echo "<option value='$ov' $selected> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>

	<td>Jumlah KPR : <input type="text" name="jumlah_kpr" id="jumlah_kpr" size="20" value="<?php echo $jumlah_kpr; ?>"></td>
</tr>
<tr>
	<td>Status SPP 
	<td> : 
	<select name="status_kompensasi" id="status_kompensasi">
		<option value="">   -- Status SPP --   </option>
		<option value="1" <?php echo is_selected('1', $status_kompensasi); ?>>KPR</option>
		<option value="2" <?php echo is_selected('2', $status_kompensasi); ?>>TUNAI</option>
		<option value="3" <?php echo is_selected('3', $status_kompensasi); ?>>KOMPENSASI</option>
		<option value="4" <?php echo is_selected('4', $status_kompensasi); ?>>ASSET SETTLEMENT</option>
		<option value="5" <?php echo is_selected('5', $status_kompensasi); ?>>KPR JAYA</option>
	</select>
	<td>Tgl. Rencana Akad : <input type="text" name="tgl_akad" id="tgl_akad" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_akad; ?>"></td>
</tr>
<tr>
	<td>Distribusi SPP
	<td>
		<input type="radio" name="status_spp" id="sudah" class="status" value="1" <?php echo is_checked('1', $status_spp); ?>>Sudah
		<input type="radio" name="status_spp" id="belum" class="status" value="2" <?php echo is_checked('2', true); ?>>Belum 
		<input readonly="readonly" type="text" name="tgl_proses" id="tgl_proses" size="10" value=""> 
	</td>
	
	</td>
	<td colspan="2">Tanda Jadi<span class="error">*</span> : <input type="text" name="tanda_jadi" id="tanda_jadi" size="20" value="<?php echo 25000000; ?>"></td>
</tr>
<tr>
	<td>Redistribusi SPP <td>:  
	<select name="redistribusi" id="redistribusi">
		<option value="">   -- Redistribusi SPP --   </option>
		<option value="1" <?php echo is_selected('1', $redistribusi); ?>>Tidak</option>
		<option value="2" <?php echo is_selected('2', $redistribusi); ?>>Dalam Proses</option>
		<option value="3" <?php echo is_selected('3', $redistribusi); ?>>Selesai</option>
	</select>
	<input type="text" name="tgl_redistribusi" id="tgl_redistribusi" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_redistribusi; ?>">
	</td>
	<td >Tgl. Tanda Jadi : <input type="text" name="tgl_tanda_jadi" id="tgl_tanda_jadi" size="10" class="apply dd-mm-yyyy" value="<?php echo date('d-m-Y') ;?>"></td>
</tr>
<tr>
	
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

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php
close($conn);
exit;
?>
<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
die_mod('K09');
$conn = conn($sess_db);
die_conn($conn);


$query = "SELECT * FROM CS_PARAMETER_PPJB";
$obj = $conn->Execute($query);
?>


</script>

<table class="t-popup pad wauto">
<tr>
			<td>Pejabat</td><td>:</td>
			<td><input type="text" name="pejabat_ppjb" id="pejabat_ppjb" size="30" value="<?php if(isset($obj->fields['PEJABAT_PPJB'])){echo $obj->fields['PEJABAT_PPJB'];} ?>"></td>
</tr>
<tr>
			<td>Tanggal SK</td><td>:</td>
			<td><input type="text" name="tanggal_sk" id="tanggal_sk" size="15" class="apply dd-mm-yyyy" value="<?php if(isset($obj->fields['TANGGAL_SK'])){echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SK'])));} ?>"></td>
</tr>
<tr>
			<td>Jabatan</td><td>:</td>
			<td><input type="text" name="jabatan_ppjb" id="jabatan_ppjb" size="30" value="<?php if(isset($obj->fields['JABATAN_PPJB'])){echo $obj->fields['JABATAN_PPJB'];} ?>"></td>
</tr>
<tr>
			<td>Jml. Hari Kerja</td><td>:</td>
			<td><input type="text" name="jumlah_hari" id="jumlah_hari" size="2" value="<?php if(isset($obj->fields['JUMLAH_HARI'])){echo $obj->fields['JUMLAH_HARI'];} ?>"> hari</td>
</tr>
<tr>
			<td>SK No.</td><td>:</td>
			<td><input type="text" name="nomor_sk" id="nomor_sk" size="30" value="<?php if(isset($obj->fields['NOMOR_SK'])){echo $obj->fields['NOMOR_SK'];} ?>"></td>
</tr>
<tr>
			<td width="100">No. PPJB Akhir</td><td>:</td>
			<td>
			<input type="text" name="nomor_ppjb" id="nomor_ppjb" size="4" value="<?php if(isset($obj->fields['NOMOR_PPJB'])){echo $obj->fields['NOMOR_PPJB'];} ?>">
			<input type="text" name="reg_ppjb" id="reg_ppjb" size="20" value="<?php if(isset($obj->fields['REG_PPJB'])){echo $obj->fields['REG_PPJB'];} ?>">
			</td>			
</tr>
<tr>
			<td width="100">No. PPJB Pengalihan Hak Akhir</td><td>:</td>
			<td>
			<input type="text" name="nomor_ppjb_ph" id="nomor_ppjb_ph" size="4" value="<?php if(isset($obj->fields['NOMOR_PPJB_PH'])){echo $obj->fields['NOMOR_PPJB_PH'];} ?>">
			<input type="text" name="reg_ppjb_ph" id="reg_ppjb_ph" size="20" value="<?php if(isset($obj->fields['REG_PPJB_PH'])){echo $obj->fields['REG_PPJB_PH'];} ?>">
			</td>
</tr>
		<td class="td-action" colspan="3"><br>
		<input type="submit" id="ubah2" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
</td>

</table>

<?php
close($conn);
exit;
?>
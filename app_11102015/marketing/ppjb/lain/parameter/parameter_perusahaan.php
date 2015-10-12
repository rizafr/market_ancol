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

<table class="t-popup pad wauto">
<tr>
			<td width="70">Nama PT</td><td width="1">:</td>
			<td><input type="text" name="nama_pt" id="nama_pt" size="40" value="<?php if(isset($obj->fields['NAMA_PT'])){echo $obj->fields['NAMA_PT'];} ?>"></td>
</tr>
<tr>
			<td width="80">Pejabat</td><td width="1">:</td>
			<td><input type="text" name="nama_pejabat" id="nama_pejabat" size="30" value="<?php if(isset($obj->fields['NAMA_PEJABAT'])){echo $obj->fields['NAMA_PEJABAT'];} ?>"></td></td>
</tr>
<tr>
			<td>Unit</td><td>:</td>
			<td><input type="text" name="unit" id="unit" size="30" value="<?php if(isset($obj->fields['UNIT'])){echo $obj->fields['UNIT'];} ?>"></td>
</tr>
<tr>
			<td>Jabatan</td><td>:</td>
			<td><input type="text" name="nama_jabatan" id="nama_jabatan" size="30" value="<?php if(isset($obj->fields['NAMA_JABATAN'])) {echo $obj->fields['NAMA_JABATAN'];} ?>"></td>
</tr>
<tr>
			<td>Departemen</td><td>:</td>
			<td><input type="text" name="nama_dep" id="nama_dep" size="40" value="<?php if(isset($obj->fields['NAMA_DEP'])){ echo $obj->fields['NAMA_DEP'];} ?>"></td>
</tr>
<tr>
			<td>Kota</td><td>:</td>
			<td><input type="text" name="kota" id="kota" size="20" class="dd" value="<?php if(isset($obj->fields['KOTA'])){echo $obj->fields['KOTA'];} ?>"></td>
</tr>
		<td class="td-action" colspan="3"><br>
		<input type="hidden" id="act" name = 'act'>
		<input type="submit" id="ubah1" value=" Simpan ">
		<input type="reset" id="reset" value=" Reset ">
</td>

</table>

<?php
close($conn);
exit;
?>
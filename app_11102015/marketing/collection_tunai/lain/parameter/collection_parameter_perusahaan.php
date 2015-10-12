<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C37');
$conn = conn($sess_db);
die_conn($conn);

$query = "SELECT * FROM CS_PARAMETER_COL";
$obj = $conn->Execute($query);

?>

<table class="t-popup pad wauto">
<tr class="input_label">
  <td >Nama PT</td>
  <td><input type="text" name="nama_pt" id="nama_pt" size="40" value="<?php echo $obj->fields['NAMA_PT']; ?>"></td>
</tr>
<tr>
  <td>Departemen</td>
  <td><input type="text" name="nama_dep" id="nama_dep" size="40" value="<?php echo $obj->fields['NAMA_DEP']; ?>"></td>
</tr>
<tr>
  <td>Pejabat</td>
  <td><input type="text" name="nama_pejabat" id="nama_pejabat" size="40" value="<?php echo $obj->fields['NAMA_PEJABAT']; ?>"></td>
</tr>
<tr>
  <td>Jabatan</td>
  <td><input type="text" name="nama_jabatan" id="nama_jabatan" size="40" value="<?php echo $obj->fields['NAMA_JABATAN']; ?>"></td>
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
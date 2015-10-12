<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C37');
$conn = conn($sess_db);
die_conn($conn);

$query2 = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE";
$obj2 = $conn->Execute($query2);

?>

<table class="t-popup pad wauto">
<tr class="input_label">
<tr>
      <td>Nomor Surat Akhir Tunai</td>
      <td><input type="text" class="text-right" name="no_surat_akhir_tunai" id="no_surat_akhir_tunai" size="10" value="<?php echo $obj2->fields['NOMOR_SURAT_TUNAI']; ?>"></td></td>
</tr>
<tr>
      <td>Registtrasi Tunai</td>
      <td><input type="text" name="registrasi_tunai" id="registrasi_tunai" size="40" value="<?php echo $obj2->fields['REG_SURAT_TUNAI']; ?>"></td>
</tr>
<tr>
      <td>Nomor Surat Akhir KPR</td>
      <td><input type="text" class="text-right" name="no_surat_akhir_kpr" id="no_surat_akhir_kpr" size="10" value="<?php echo $obj2->fields['NOMOR_SURAT_KPR']; ?>"></td>
</tr>
<tr>
      <td>Registtrasi KPR</td>
      <td><input type="text" name="registrasi_kpr" id="registrasi_kpr" size="40" value="<?php echo $obj2->fields['REG_SURAT_KPR']; ?>"></td>
</tr>
	<td class="td-action" colspan="3"><br>
		  <input type="submit" id="ubah4" value=" Simpan ">
		  <input type="reset" id="reset" value=" Reset ">
      </td>
</table>

<?php
close($conn);
exit;
?>
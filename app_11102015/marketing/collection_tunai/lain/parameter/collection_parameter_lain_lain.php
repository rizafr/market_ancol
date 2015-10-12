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
<tr>
      <td>Tanggal Efektif Program (Cut Off) </td>
      <td><input class="text-right" type="text" name="tanggal_efektif_prog" id="tanggal_efektif_prog" size="15" class="apply dd-mm-yyyy" value="<?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_EFEKTIF_PROG']))); ?>"></td></td>
</tr>
<tr>
      <td>Nilai Sisa Tagihan dianggap Lunas s/d </td>
      <td><input type="text" class="text-right" name="nilai_sisa_tagihan" id="nilai_sisa_tagihan" size="10" value="<?php echo to_money($obj->fields['NILAI_SISA_TAGIHAN']); ?>"></td></td>
</tr>
<tr>
      <td>Masa Berlaku Denda </td>
      <td><input type="text" class="text-right" name="masa_berlaku_denda" id="masa_berlaku_denda" size="10" value="<?php echo $obj->fields['MASA_BERLAKU_DENDA']; ?>">
      Hari Kerja</td>
</tr>
	    <td class="td-action" colspan="3"><br>
		  <input type="submit" id="ubah3" value=" Simpan ">
		  <input type="reset" id="reset" value=" Reset ">
      </td>
</table>

<?php
close($conn);
exit;
?>
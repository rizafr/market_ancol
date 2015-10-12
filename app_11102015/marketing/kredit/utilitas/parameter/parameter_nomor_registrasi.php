<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
die_mod('K09');
$conn = conn($sess_db);
die_conn($conn);

$query = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE";
$obj = $conn->Execute($query);
?>

<table class="t-popup pad wauto">
<tr>
	<td width="150">No. Kuitansi Uang Muka</td><td>:</td>
	<td>
	<input type="text" name="uang_no" id="uang_no" size="2" value="<?php echo $obj->fields['NOMOR_KWITANSI']; ?>">
	<input type="text" name="uang_reg" id="uang_reg" size="20" value="<?php echo $obj->fields['REG_KWITANSI']; ?>">
	</td>
</tr>
<tr>
	<td>No. Kuitansi Lain-lain</td><td>:</td>
	<td>
	<input type="text" name="lain_no" id="lain_no" size="2" value="<?php echo $obj->fields['NOMOR_KWITANSI_LAIN']; ?>">
	<input type="text" name="lain_reg" id="lain_reg" size="20" value="<?php echo $obj->fields['REG_KWITANSI_LAIN']; ?>">
	</td>
</tr>
<tr>
	<td>No. Faktur Pajak</td><td>:</td>
	<td>
	<input type="text" name="faktur_reg" id="faktur_reg" size="12" value="<?php echo $obj->fields['NO_REG_FAKTUR_PAJAK']; ?>">
	<input type="text" name="faktur_no" id="faktur_no" size="2" value="<?php echo $obj->fields['NO_FAKTUR_PAJAK_STANDAR']; ?>">
	</td>
</tr>
<tr>
	<td>No. Tanda Terima</td><td>:</td>
	<td>
	<input type="text" name="tanda_no" id="tanda_no" size="2" value="<?php echo $obj->fields['NOMOR_KWITANSI_TTS']; ?>">
	<input type="text" name="tanda_reg" id="tanda_reg" size="20" value="<?php echo $obj->fields['REG_KWITANSI_TTS']; ?>">
	</td>
	</tr>
<tr>
	<td class="td-action" colspan="3"><br>
	<input type="submit" id="ubah2" value=" Simpan ">
	<input type="reset" id="reset" value=" Reset ">
	</td>
</tr>
</table>

<?php
close($conn);
exit;
?>
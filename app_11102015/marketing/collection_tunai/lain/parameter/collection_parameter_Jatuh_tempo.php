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
      <td>Pemberitahuan jatuh Tempo</td>
      <td><input type="text" class="text-right" name="pemb_jatuh_tempo" id="pemb_jatuh_tempo" size="5" value="<?php echo $obj->fields['PEMB_JATUH_TEMPO']; ?>"></td>
    </tr>
    <tr>
      <td>Somasi Pertama (I)</td>
      <td><input type="text" class="text-right" name="somasi_satu" id="somasi_satu" size="5" value="<?php echo $obj->fields['SOMASI_SATU']; ?>"></td>
    </tr>
    <tr>
      <td>Somasi Kedua (II)</td>
      <td><input type="text" class="text-right" name="somasi_dua" id="somasi_dua" size="5" value="<?php echo $obj->fields['SOMASI_DUA']; ?>"></td>
    </tr>
    <tr>
      <td>Somasi Ketiga (III)</td>
      <td><input type="text" class="text-right" name="somasi_tiga" id="somasi_tiga" size="5" value="<?php echo $obj->fields['SOMASI_TIGA']; ?>"></td>
    </tr>
    <tr> 
      <td>Wanprestasi</td>
      <td><input type="text" class="text-right" name="wanprestasi" id="wanprestasi" size="5" value="<?php echo $obj->fields['WANPRESTASI']; ?>"></td></td>
    </tr>  
    <tr> 
    <td>Undangan Pembatalan</td>
      <td><input type="text" class="text-right" name="undangan_pembatalan" id="undangan_pembatalan" size="5" value="<?php echo $obj->fields['UNDANGAN_PEMBATALAN']; ?>"></td></td>
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
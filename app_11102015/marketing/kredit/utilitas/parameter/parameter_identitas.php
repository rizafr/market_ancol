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

<script type="text/javascript">
jQuery(function($) {
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
});
</script>

<table class="t-popup pad wauto">
<tr>
	<td width="100">Nama PT</td><td>:</td>
	<td><input type="text" name="nama" id="nama" size="40" value="<?php echo $obj->fields['NAMA_PT']; ?>"></td>
</tr>
<tr>
	<td>Alamat</td><td>:</td>
	<td><input type="text" name="alamat" id="alamat" size="80" value="<?php echo $obj->fields['ALAMAT']; ?>"></td>
</tr>
<tr>
	<td>NPWP</td><td>:</td>
	<td><input type="text" name="npwp" id="npwp" size="20" value="<?php echo $obj->fields['NPWP']; ?>"></td>
</tr>
<tr>
	<td>Tgl PKP</td><td>:</td>
	<td><input type="text" name="tanggal" id="tanggal" size="15" class="apply dd-mm-yyyy" value="<?php echo tgltgl(f_tgl($obj->fields['TGL_PKP'])); ?>"></td>
</tr>
<tr>
	<td class="td-action" colspan="3"><br>
	<input type="hidden" id="act" name = 'act'>
	<input type="submit" id="ubah1" value=" Simpan ">
	<input type="reset" id="reset" value=" Reset ">
	</td>
</tr>
</table>

<?php
close($conn);
exit;
?>
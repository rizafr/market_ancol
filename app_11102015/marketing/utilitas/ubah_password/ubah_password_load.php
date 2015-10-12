<?php
require_once('../../../../config/config.php');
die_login();
//die_app('M');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);

$user_id = $_SESSION['USER_ID'];
$query = "SELECT * FROM USER_APPLICATIONS WHERE USER_ID = '$user_id'";
$obj = $conn->Execute($query);
?>

<script type="text/javascript">
jQuery(function($) {
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
	
	$('#nama_pt').inputmask('varchar', { repeat: '40' }); 
	$('#unit').inputmask('varchar', { repeat: '30' }); 
	$('#nama_dep').inputmask('varchar', { repeat: '40' });
	$('#nama_pejabat').inputmask('varchar', { repeat: '30' }); 
	$('#nama_jabatan').inputmask('varchar', { repeat: '30' });
	$('#kota').inputmask('varchar', { repeat: '20' }); 
	
	$('#pejabat_ppjb').inputmask('varchar', { repeat: '30' });
	$('#jabatan_ppjb').inputmask('varchar', { repeat: '30' }); 
	$('#nomor_sk').inputmask('varchar', { repeat: '25' });
	$('#jumlah_hari').inputmask('numeric', { repeat: '2' }); 
	$('#nomor_ppjb').inputmask('numeric', { repeat: '4' });
	$('#reg_ppjb').inputmask('varchar', { repeat: '20' });
});
</script>

	<table class="t-form">
		<tr><td colspan="6"><br></td></tr>
		<tr>
			<td colspan="6" class="text-center"><br><b>D A T A U S E R</b><hr></td>
		</tr>
		
		<tr>
			<td width="200">Nama User</td><td width="1">:</td>
			<td><input type="text" name="nama_user" id="nama_user" readonly="readonly" size="30" value="<?php echo $_SESSION['FULL_NAME']; ?>"></td>
		</tr>
		<tr>
			<td width="200">Password Dahulu</td><td width="1">:</td>
			<td><input type="password" name="old_pass" id="old_pass" readonly="readonly" size="50" value="<?php echo $obj->fields['PASSOWRD_ID']; ?>"></td></td>
		</tr>
		<tr>
			<td width="200">Password Baru</td><td width="1">:</td>
			<td><input type="password" name="new_pass" id="new_pass" size="50" value=""></td></td>
		</tr>
		<input type="hidden" name="user_id" id="user_id" size="20" value="<?php echo $user_id; ?>">
	</table>

<?php
close($conn);
exit;
?>
<?php
require_once('../../../../config/config.php');
die_login();
//die_app('M');
die_mod('M26');
$conn = conn($sess_db);
die_conn($conn);

$query = "SELECT * FROM CS_PARAMETER_MARK";
$obj = $conn->Execute($query);
$nama_pejabat			= $obj->fields['NAMA_PEJABAT'];
$nama_jabatan			= $obj->fields['NAMA_JABATAN'];
$pejabat_spp			= $obj->fields['PEJABAT_SPP'];
$jabatan_spp			= $obj->fields['JABATAN_SPP'];
$nama_sales				= $obj->fields['NAMA_SALES'];
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

<table class="t-form">

	<tr>
		<td>Nama Pejabat </td><td width="1">:</td>
		<td><input type="text" name="nama_pejabat" id="nama_pejabat" size="30" value="<?php echo $nama_pejabat; ?>">
		</td>
	</tr>
	<tr>
		<td>  Jabatan </td><td width="1">:</td>
		<td><input type="text" name="nama_jabatan" id="nama_jabatan" size="30" value="<?php echo $nama_jabatan; ?>">
		</td>
	</tr>
	<tr>
		<td>Nama Pejabat </td><td width="1">:</td>
		<td><input type="text" name="pejabat_spp" id="pejabat_spp" size="30" value="<?php echo $pejabat_spp; ?>">
		</td>
	</tr>
	<tr>
		<td>    Jabatan </td><td width="1">:</td>
		<td><input type="text" name="jabatan_spp" id="jabatan_spp" size="30" value="<?php echo $jabatan_spp; ?>">
		</td>
	</tr>
	<tr>
		<td>Nama Sales </td><td width="1">:</td>
		<td><input type="text" name="nama_sales" id="nama_sales" size="30" value="<?php echo $nama_sales; ?>">
		</td>
	</tr>
	<tr>
		<td width="150">Batas Distribusi</td><td width="1">:</td>
		<td><input type="text" name="batas_distribusi" id="batas_distribusi" size="20" value="<?php echo $obj->fields['BATAS_DISTRIBUSI']; ?>"></td>
	</tr>
	<tr>
		<td width="150">Tenggang Distribusi</td><td width="1">:</td>
		<td><input type="text" name="tenggang_distribusi" id="tenggang_distribusi" size="20" value="<?php echo $obj->fields['TENGGANG_DISTRIBUSI']; ?>"></td></td>
	</tr>
	<tr>
		<td width="150">Batas Reserve</td><td width="1">:</td>
		<td><input type="text" name="batas_reserve" id="batas_reserve" size="20" value="<?php echo $obj->fields['BATAS_RESERVE']; ?>"></td></td>
	</tr>
	<tr>
		<td class="td-action" colspan="3"><br>
			<input type="hidden" id="act" name = 'act' value="">
			<input type="submit" id="save" value=" Simpan ">
			<input type="reset" id="reset" value=" Reset ">
		</td>
	</tr>	
</table>

<?php
close($conn);
exit;
?>
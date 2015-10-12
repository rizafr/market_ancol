<?php
die_login();
// die_app('C01');
// die_mod('COL03');
$conn = conn($sess_db);
die_conn($conn);
?>

<div class="title-page">REGISTRASI SURAT</div>

<form name="form" id="form" method="post">

<script type="text/javascript">
jQuery(function($) {

	$('#no_surat_akhir').inputmask('varchar', { repeat: '3' }); 
	$('#registrasi').inputmask('varchar', { repeat: '30' });
	
	$('#save').on('click', function(e) {
		e.preventDefault();
		var url = base_collection_tunai_lain + 'nomor_surat/nomor_surat_proses.php',
			data = $('#form').serialize();
				
		if (confirm("Apakah anda yakin mengubah data parameter ?") == false)
		{
			return false;
		}	
		
		$.post(url, data, function(data) {
			alert(data.msg);
		}, 'json');
		
		return false;
	});
});
</script>
	<?php
	$query = "SELECT * FROM REGISTRASI_SURAT";
	$obj = $conn->Execute($query);
	?>

	<div class="t-control" style="margin-left:auto;margin-right:auto;width:700px">

	  <table width="613" border="0" cellspacing="1" cellpadding="0.5" align="center">
		<tr class="input_label">
		  <td>&nbsp;</td>
		  <td colspan="4">&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>

		<tr class="input_label">
		  <td>No Surat Akhir</td>
		  <td><input type="text" class="text-right" name="no_surat_akhir" id="no_surat_akhir" size="10" value="<?php echo $obj->fields['NO_SURAT_AKHIR']; ?>"></td>
		  <td width="35">&nbsp;</td>  
		</tr>
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td>Registrasi</td>
		  <td><input type="text" name="registrasi" id="registrasi" size="40" value="<?php echo $obj->fields['REGISTRASI']; ?>"></td>
		  <td>&nbsp;</td>
		</tr>
		
		<tr>
		  <td colspan="4">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4"><span class="table-coll" style="border:none;width:30%">
			<input type="submit" id="save" value=" Ubah ">
			<input type="reset" id="reset" value=" Reset ">
		  </span></td>
		</tr>
	  </table>
	</div>
		
</form>

<?php close($conn); ?>
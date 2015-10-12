<?php
require_once('spp_proses.php');
require_once('../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<script type="text/javascript">

jQuery(function($) {
t_strip('.t-data');
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
});

</script>

<table class="t-popup pad2 50">
<tr>
	<td class="text-left">Bank</td><td>:</td>
	<td><select name="bank" id="bank">
		<option value=""> -- Bank -- </option>
		<?php
		$obj = $conn->execute("
		SELECT *
		FROM 
			BANK
		");
		while( ! $obj->EOF)
		{
			$ov = $obj->fields['KODE_BANK'];
			$oj = $obj->fields['NAMA_BANK'];
			echo "<option value='$ov'".is_selected($ov, $bank)."> $oj </option>";
			$obj->movenext();
		}
		?>
	</select>
	</td>
</tr>
<tr>
	<td> No SPK Bank</td><td>:</td>
	<td><input type="text" name="nospk" id="nospk" size="35" value="<?php echo $nospk; ?>"></td>	
</tr>
<tr>
	<td> Plafon KPR</td><td>:</td>	
	<td>Rp.<input type="text" name="plafonkpr" id="plafonkpr" size="25" value="<?php echo to_money($plafonkpr); ?>"></td>
</tr>
<tr>
	<td> KPR Disetujui</td><td>:</td>	
	<td>Rp.<input type="text" name="plafonkpr" id="plafonkpr" size="25" value="<?php echo to_money($jumlah_kpr); ?>"></td>
</tr>
<tr>
	<td> Retensi</td><td>:</td>	
	<td>Rp.<input type="text" name="retensi" id="retensi" size="25" value="<?php echo to_money($retensi); ?>"></td>
</tr>
<tr>
	<td> Tgl SPK Bank</td><td>:</td>	
	<td><input type="text" name="tgl_spk" id="tgl_spk" size="10" value="<?php echo $tgl_akad; ?>"></td>
</tr>
<tr>
	<td> Tgl Akad Kredit</td><td>:</td>	
	<td><input type="text" name="tgl_akad_kredit" id="tgl_akad_kredit" size="10" value="<?php echo $tgl_akad_kredit; ?>"></td>
</tr>
<tr>
	<td> Tgl Cair KPR</td><td>:</td>	
	<td><input type="text" name="tgl_cair_kpr" id="tgl_cair_kpr" size="10" value="<?php echo $tgl_cair_kpr; ?>"></td>
</tr>
<tr>
	<td> Tgl Retensi</td><td>:</td>	
	<td><input type="text" name="tgl_retensi" id="tgl_retensi" size="10" value="<?php echo $tgl_retensi; ?>"></td>
</tr>
</table>


<?php
close($conn);
exit;
?>
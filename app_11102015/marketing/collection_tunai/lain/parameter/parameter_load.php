<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C37');
$conn = conn($sess_db);
die_conn($conn);

$query = "SELECT * FROM CS_PARAMETER_COL";
$obj = $conn->Execute($query);

$query2 = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE";
$obj2 = $conn->Execute($query2);
?>

<script type="text/javascript">
jQuery(function($) {

	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
	
	$('#nama_pt').inputmask('varchar', { repeat: '40' }); 
	$('#nama_dep').inputmask('varchar', { repeat: '40' });
	$('#nama_pejabat').inputmask('varchar', { repeat: '30' }); 
	$('#nama_jabatan').inputmask('varchar', { repeat: '30' });	
	$('#pemb_jatuh_tempo').inputmask('numeric', { repeat: '2' });
	$('#somasi_satu').inputmask('numeric', { repeat: '3' });
	$('#somasi_dua').inputmask('numeric', { repeat: '3' });	
	$('#somasi_tiga').inputmask('numeric', { repeat: '3' });
	$('#wanprestasi').inputmask('numeric', { repeat: '3' });
	$('#undangan_pembatalan').inputmask('numeric', { repeat: '3' });
	
	$('#nilai_sisa_tagihan').inputmask('varchar', { repeat: '5' }); 
	$('#masa_berlaku_denda').inputmask('numeric', { repeat: '2' });
});
</script>
	
	  <table width="613" border="0" cellspacing="1" cellpadding="0.5" align="center">
		<tr class="input_label">
		  <td>&nbsp;</td>
		  <td colspan="4">&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="2">Perusahaan<hr></td>
		  <td width="164">&nbsp;</td>
		  <td width="132">&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr class="input_label">
		  <td width="282">Nama PT</td>
		  <td><input type="text" name="nama_pt" id="nama_pt" size="40" value="<?php echo $obj->fields['NAMA_PT']; ?>"></td>
		  <td width="35">&nbsp;</td>
		</tr>
		<tr>
		  <td>Departemen</td>
		  <td><input type="text" name="nama_dep" id="nama_dep" size="40" value="<?php echo $obj->fields['NAMA_DEP']; ?>"></td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Pejabat</td>
		  <td><input type="text" name="nama_pejabat" id="nama_pejabat" size="40" value="<?php echo $obj->fields['NAMA_PEJABAT']; ?>"></td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Jabatan</td>
		  <td><input type="text" name="nama_jabatan" id="nama_jabatan" size="40" value="<?php echo $obj->fields['NAMA_JABATAN']; ?>"></td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		
		<tr>
		  <td colspan="2">Tenggang Waktu Penyuratan Dari Jatuh Tempo (Hari Kerja)<hr></td>
		  <td width="164">&nbsp;</td>
		  <td width="132">&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr class="input_label">
		  <td>Pemberitahuan jatuh Tempo</td>
		  <td><input type="text" class="text-right" name="pemb_jatuh_tempo" id="pemb_jatuh_tempo" size="5" value="<?php echo $obj->fields['PEMB_JATUH_TEMPO']; ?>"></td>
		  <td width="35">&nbsp;</td>
		</tr>
		<tr>
		  <td>Somasi Pertama (I)</td>
		  <td><input type="text" class="text-right" name="somasi_satu" id="somasi_satu" size="5" value="<?php echo $obj->fields['SOMASI_SATU']; ?>"></td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Somasi Kedua (II)</td>
		  <td><input type="text" class="text-right" name="somasi_dua" id="somasi_dua" size="5" value="<?php echo $obj->fields['SOMASI_DUA']; ?>"></td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Somasi Ketiga (III)</td>
		  <td><input type="text" class="text-right" name="somasi_tiga" id="somasi_tiga" size="5" value="<?php echo $obj->fields['SOMASI_TIGA']; ?>"></td>
		  <td>&nbsp;</td>
		</tr>
		<tr> 
		  <td>Wanprestasi</td>
		  <td><input type="text" class="text-right" name="wanprestasi" id="wanprestasi" size="5" value="<?php echo $obj->fields['WANPRESTASI']; ?>"></td></td>
		  <td>&nbsp;</td>
		</tr>  
		<tr> 
		<td>Undangan Pembatalan</td>
			<td><input type="text" class="text-right" name="undangan_pembatalan" id="undangan_pembatalan" size="5" value="<?php echo $obj->fields['UNDANGAN_PEMBATALAN']; ?>"></td></td>
			<td>&nbsp;</td>	
		</tr>
		
		</tr>
		<tr>
		  <td colspan="3">&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		
		<tr>
		  <td colspan="2">Lain-Lain<hr></td>
		  <td width="164">&nbsp;</td>
		  <td width="132">&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Tanggal Efektif Program (Cut Off) </td>
		  <td><input class="text-right" type="text" name="tanggal_efektif_prog" id="tanggal_efektif_prog" size="15" class="apply dd-mm-yyyy" value="<?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_EFEKTIF_PROG']))); ?>"></td></td>
		  <td width="35">&nbsp;</td>
		</tr>
		<tr>
		  <td>Nilai Sisa Tagihan dianggap Lunas s/d </td>
		  <td><input type="text" class="text-right" name="nilai_sisa_tagihan" id="nilai_sisa_tagihan" size="10" value="<?php echo to_money($obj->fields['NILAI_SISA_TAGIHAN']); ?>"></td></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Masa Berlaku Denda </td>
		  <td><input type="text" class="text-right" name="masa_berlaku_denda" id="masa_berlaku_denda" size="10" value="<?php echo $obj->fields['MASA_BERLAKU_DENDA']; ?>">
		  Hari Kerja</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		
		<tr>
		  <td colspan="2">Nomor Surat<hr></td>
		  <td width="164">&nbsp;</td>
		  <td width="132">&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Nomor Surat Akhir Tunai</td>
		  <td><input type="text" class="text-right" name="no_surat_akhir_tunai" id="no_surat_akhir_tunai" size="10" value="<?php echo $obj2->fields['NOMOR_SURAT_TUNAI']; ?>"></td></td>
		  <td width="35">&nbsp;</td>
		</tr>
		<tr>
		  <td>Registtrasi Tunai</td>
		  <td><input type="text" name="registrasi_tunai" id="registrasi_tunai" size="40" value="<?php echo $obj2->fields['REG_SURAT_TUNAI']; ?>"></td></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>Nomor Surat Akhir KPR</td>
		  <td><input type="text" class="text-right" name="no_surat_akhir_kpr" id="no_surat_akhir_kpr" size="10" value="<?php echo $obj2->fields['NOMOR_SURAT_KPR']; ?>"></td></td>
		  <td width="35">&nbsp;</td>
		</tr>
		<tr>
		  <td>Registtrasi KPR</td>
		  <td><input type="text" name="registrasi_kpr" id="registrasi_kpr" size="40" value="<?php echo $obj2->fields['REG_SURAT_KPR']; ?>"></td></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
	  </table>

<?php
close($conn);
exit;
?>
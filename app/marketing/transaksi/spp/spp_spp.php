<?php
require_once('spp_proses.php');
require_once('../../../../config/config.php');
die_login();
	//die_app('');
	//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
if($status_otorisasi=='0'){
	$tombol				= 'otorisasi';
	$nama_tombol		= 'Otorisasi';
}else{
	$tombol				= 'batal_otorisasi';
	$nama_tombol		= 'Batal Otorisasi';
}

	// FIX STATUS DISTRIBUSI
	// 24-6-2015
	//cek dlu isi status_spp
$status_spp			= (!empty($status_spp)) ?($status_spp) : "3";
// echo "Status SPP: ".$status_spp;



?>


<script type="text/javascript">
	// FIX STATUS DISTRIBUSI
	// 24-6-2015
	//disable ketika diload
	jQuery( document ).ready(function() {

		jQuery('#cek').change(function() {
			if(jQuery(this).is(":checked")) {
				var alamat_rumah =	jQuery('#alamat_rumah').val();
				jQuery('#alamat_surat').val(alamat_rumah);
				jQuery('#alamat_npwp').val(alamat_rumah);
			}else{
				jQuery('#alamat_surat').val("");
				jQuery('#alamat_npwp').val("");
			}
		});

		var status_spp = "<?php echo $status_spp ?>";
		//alert(status_spp);
		if(status_spp== 1){
				// readonly select
				jQuery("select :selected").each(function(){
					jQuery(this).parent().data("default", this);
				});
				
				jQuery("select").change(function(e) {
					jQuery(jQuery(this).data("default")).prop("selected", true);
				});
				
				var d = new Date();
				var curr_date = d.getDate();
				var curr_month = d.getMonth()+1;
				var curr_year = d.getFullYear();
				
				
				var tgl_proses = curr_date + "-0" + curr_month
				+ "-" + curr_year;
				jQuery('#tgl_proses').val(tgl_proses);
				jQuery('#no_customer').attr('readonly', 'readonly');
				jQuery('#tgl_spp').attr('readonly', 'readonly');
				jQuery('#nama').attr('readonly', 'readonly');
				jQuery('#alamat_rumah').attr('readonly', 'readonly');
				jQuery('#alamat_surat').attr('readonly', 'readonly');
				jQuery('#alamat_npwp').attr('readonly', 'readonly');
				jQuery('#email').attr('readonly', 'readonly');
				jQuery('#tlp_rumah').attr('readonly', 'readonly');
				jQuery('#tlp_kantor').attr('readonly', 'readonly');
				jQuery('#tlp_lain').attr('readonly', 'readonly');
				jQuery('#identitas').attr('readonly', 'readonly');
				jQuery('#no_identitas').attr('readonly', 'readonly');
				jQuery('#ktp').attr('readonly', 'readonly');
				jQuery('#sim').attr('readonly', 'readonly');
				jQuery('#kims').attr('readonly', 'readonly');
				jQuery('#npwp').attr('readonly', 'readonly');
				jQuery('#jenis_npwp').attr('readonly', 'readonly');
				jQuery('#bank').attr('readonly', 'readonly');
				jQuery('#jumlah_kpr').attr('readonly', 'readonly');
				jQuery('#agen').attr('readonly', 'readonly');
				jQuery('#koordinator').attr('readonly', 'readonly');
				jQuery('#tgl_akad').attr('readonly', 'readonly');
				jQuery('#status_spp').attr('readonly', 'readonly');
				jQuery('#tanda_jadi').attr('readonly', 'readonly');
				jQuery('#status_kompensasi').attr('readonly', 'readonly');
				jQuery('#tgl_tanda_jadi').attr('readonly', 'readonly');
				jQuery('#redistribusi').attr('readonly', 'readonly');
				jQuery('#tgl_redistribusi').attr('readonly', 'readonly');
				jQuery('#keterangan').attr('readonly', 'readonly');
				jQuery('#tgl_proses').attr('readonly', 'readonly');
			}
			else if(status_spp== 0){
				var tgl_proses = '';
				jQuery('#tgl_proses').val(tgl_proses);
				jQuery('#tgl_spp').removeAttr('readonly');
				jQuery('#nama').removeAttr('readonly');
				jQuery('#alamat_rumah').removeAttr('readonly');
				jQuery('#alamat_surat').removeAttr('readonly');
				jQuery('#alamat_npwp').removeAttr('readonly');
				jQuery('#email').removeAttr('readonly');
				jQuery('#tlp_rumah').removeAttr('readonly');
				jQuery('#tlp_kantor').removeAttr('readonly');
				jQuery('#tlp_lain').removeAttr('readonly');
				jQuery('#identitas').removeAttr('readonly');
				jQuery('#no_identitas').removeAttr('readonly');
				jQuery('#ktp').removeAttr('readonly');
				jQuery('#sim').removeAttr('readonly');
				jQuery('#kims').removeAttr('readonly');
				jQuery('#npwp').removeAttr('readonly');
				jQuery('#jenis_npwp').removeAttr('readonly');
				jQuery('#bank').removeAttr('readonly');
				jQuery('#jumlah_kpr').removeAttr('readonly');
				jQuery('#agen').removeAttr('readonly');
				jQuery('#koordinator').removeAttr('readonly');
				jQuery('#tgl_akad').removeAttr('readonly');
				jQuery('#status_spp').removeAttr('readonly');
				jQuery('#tanda_jadi').removeAttr('readonly');
				jQuery('#status_kompensasi').removeAttr('readonly');
				jQuery('#tgl_tanda_jadi').removeAttr('readonly');
				jQuery('#redistribusi').removeAttr('readonly');
				jQuery('#tgl_redistribusi').removeAttr('readonly');
				jQuery('#keterangan').removeAttr('readonly');
				jQuery('#tgl_proses').removeAttr('readonly');
			}
			//console.log( "ready!" );
		});
	// END FIX STATUS Distribusi
	jQuery(function($) {
		$('.dd-mm-yyyy').Zebra_DatePicker({
			format: 'd-m-Y',
			readonly_element : false,
			inside: true
		});
		$('#nama').inputmask('varchar', { repeat: '60' });
		$('#alamat_rumah, #alamat_surat, #alamat_npwp').inputmask('varchar', { repeat: '100' });
		$('#email').inputmask('varchar', { repeat: '50' });
		$('#tlp_rumah, #tlp_kantor, #tlp_lain, #no_identitas').inputmask('varchar', { repeat: '30' });
		$('#npwp').inputmask('varchar', { repeat: '15' });
		$('#jumlah_kpr, #tanda_jadi').inputmask('numeric', { repeat: '16' });
		$('#keterangan').inputmask('varchar', { repeat: '150' });
		
		//tombol status otoritas;
		var status_otorisasi = <?php echo $status_otorisasi; ?>;
		//alert(status_otorisasi) ;
		if(status_otorisasi == '0'){
			status = 0;
			$('#otorisasi').show();
			$('#batal_otorisasi').hide();
			$('#nama_tombol').val('Otorisasi');
			$('#tombol').val('otorisasi');
		} else if(status_otorisasi == '1'){
			status = 1;
			$('#otorisasi').hide();
			$('#batal_otorisasi').show();
			$('#nama_tombol').val('Batal Otorisasi');
			$('#tombol').val('batal_otorisasi');
		}

		// var status_spp = "<?php echo $status_spp;?>";
		// if(status_spp == '1'){
		// 	$('#otorisasi').show();
		// } else if (status_spp == '2'){
		// 	$('#otorisasi').hide();
		// }
		
		$(document).on('click', '#otorisasi', function(e) {
			e.preventDefault();
			var status_spp = "<?php echo $status_spp;?>";
			if(status_spp=='2'){
				alert('Status SPP belum di distribusikan');					
			} else if (status_spp=='1') {
				if (confirm('Apakah anda yakin akan mengotorisasi data ini ?')) 
				{
					otorisasiData();					
				}
				loadData1();
				return false;
			} 						
		});

		$(document).on('click', '#batal_otorisasi', function(e) {
			e.preventDefault();
			var checked = $(".cb_data:checked").length;
			if (checked < 1) {
				alert('Pilih data yang akan dibatalkan otorisasi.');
			} else if (confirm('Apakah anda yakin akan membatalkan otorisasi data ini ?')) 
			{
				unotorisasiData();
				
			}
			loadData1();
			return false;
		});

		function otorisasiData()
		{	
			var url		= base_marketing_transaksi + 'spp/spp_proses.php',
			data	= jQuery('#form').serializeArray();
			data.push({ name: 'act', value: 'Otorisasi' });
			
			jQuery.post(url, data, function(result) {
				var list_id = result.act.join(', #');
				alert(result.msg);		
			}, 'json');	
			// loadData1();
			return false;
		}
		
		function unotorisasiData()
		{
			var url		= base_marketing_transaksi + 'spp/spp_proses.php',
			data	= jQuery('#form').serializeArray();
			data.push({ name: 'act', value: 'Batal_Otorisasi' });
			
			jQuery.post(url, data, function(result) {
				var list_id = result.act.join(', #');
				alert(result.msg);		
			}, 'json');	
			// loadData1();
			return false;
		}

		//disable jika sudah diotoritas
		$('input:radio[name="status_spp"]').change(function(e){
			e.preventDefault();
			var status_spp = $('input[name=status_spp]:checked', '#form').val() ;
			
			if(status_spp== 1){
				// readonly select
				$("select :selected").each(function(){
					$(this).parent().data("default", this);
				});
				
				$("select").change(function(e) {
					$($(this).data("default")).prop("selected", true);
				});
				
				var d = new Date();
				var curr_date = d.getDate();
				var curr_month = d.getMonth()+1;
				var curr_year = d.getFullYear();
				
				
				var tgl_proses = curr_date + "-0" + curr_month
				+ "-" + curr_year;
				$('#tgl_proses').val(tgl_proses);
				$('#no_customer').attr('readonly', 'readonly');
				$('#tgl_spp').attr('readonly', 'readonly');
				$('#nama').attr('readonly', 'readonly');
				$('#alamat_rumah').attr('readonly', 'readonly');
				$('#alamat_surat').attr('readonly', 'readonly');
				$('#alamat_npwp').attr('readonly', 'readonly');
				$('#email').attr('readonly', 'readonly');
				$('#tlp_rumah').attr('readonly', 'readonly');
				$('#tlp_kantor').attr('readonly', 'readonly');
				$('#tlp_lain').attr('readonly', 'readonly');
				$('#identitas').attr('readonly', 'readonly');
				$('#no_identitas').attr('readonly', 'readonly');
				$('#ktp').attr('readonly', 'readonly');
				$('#sim').attr('readonly', 'readonly');
				$('#kims').attr('readonly', 'readonly');
				$('#npwp').attr('readonly', 'readonly');
				$('#jenis_npwp').attr('readonly', 'readonly');
				$('#bank').attr('readonly', 'readonly');
				$('#jumlah_kpr').attr('readonly', 'readonly');
				$('#agen').attr('readonly', 'readonly');
				$('#koordinator').attr('readonly', 'readonly');
				$('#tgl_akad').attr('readonly', 'readonly');
				$('#status_spp').attr('readonly', 'readonly');
				$('#tanda_jadi').attr('readonly', 'readonly');
				$('#status_kompensasi').attr('readonly', 'readonly');
				$('#tgl_tanda_jadi').attr('readonly', 'readonly');
				$('#redistribusi').attr('readonly', 'readonly');
				$('#tgl_redistribusi').attr('readonly', 'readonly');
				$('#keterangan').attr('readonly', 'readonly');
				$('#tgl_proses').attr('readonly', 'readonly');
			}
			else{
				var tgl_proses = '';
				$('#tgl_proses').val(tgl_proses);
				$('#tgl_spp').removeAttr('readonly');
				$('#nama').removeAttr('readonly');
				$('#alamat_rumah').removeAttr('readonly');
				$('#alamat_surat').removeAttr('readonly');
				$('#alamat_npwp').removeAttr('readonly');
				$('#email').removeAttr('readonly');
				$('#tlp_rumah').removeAttr('readonly');
				$('#tlp_kantor').removeAttr('readonly');
				$('#tlp_lain').removeAttr('readonly');
				$('#identitas').removeAttr('readonly');
				$('#no_identitas').removeAttr('readonly');
				$('#ktp').removeAttr('readonly');
				$('#sim').removeAttr('readonly');
				$('#kims').removeAttr('readonly');
				$('#npwp').removeAttr('readonly');
				$('#jenis_npwp').removeAttr('readonly');
				$('#bank').removeAttr('readonly');
				$('#jumlah_kpr').removeAttr('readonly');
				$('#agen').removeAttr('readonly');
				$('#koordinator').removeAttr('readonly');
				$('#tgl_akad').removeAttr('readonly');
				$('#status_spp').removeAttr('readonly');
				$('#tanda_jadi').removeAttr('readonly');
				$('#status_kompensasi').removeAttr('readonly');
				$('#tgl_tanda_jadi').removeAttr('readonly');
				$('#redistribusi').removeAttr('readonly');
				$('#tgl_redistribusi').removeAttr('readonly');
				$('#keterangan').removeAttr('readonly');
				$('#tgl_proses').removeAttr('readonly');
			}
		});

	//dinamis jabatan
	var count = 0;

	$("#add_btn").click(function(){
		count += 1;
		$('#ttd').append(
			'<tr class="records">'
			+ '<td><input id="nama_' + count + '" name="nama_' + count + '" type="text" size="20"></td>'
			+ '<td><input id="jabatan_' + count + '" name="jabatan_' + count + '" type="text" size="30"></td>'
			+ '<td><input type="button" name="del_btn" value="Hapus" class="remove_item">'
			+ '<input id="rows_' + count + '" name="rows[]" value="'+ count +'" type="hidden"></td></tr>'
			);
	});

	$( document ).on( "click", ".remove_item", function(ev) {
		if (ev.type == 'click') {
			$(this).parents(".records").fadeOut();
			$(this).parents(".records").remove();
		}
	});

});


</script>

<table>
	<tr>
		<td> Costumer ID </td><td> : <input readonly="readonly" type="text" name="costumer_id" id="costumer_id" size="11" value="<?php echo $costumer_id; ?>"></td>
	</tr>
	<tr>
		<td width="20"><b>No VA</b> </td><td> : <input type="text" name="no_customer" id="no_customer" size="10" value="<?php echo $no_customer; ?>"></td>
		<td colspan="2">Tgl / No. SPP : <input type="text" name="tgl_spp" id="tgl_spp" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_spp; ?>"> / <input readonly="readonly" type="text" name="no_spp" id="no_spp" size="5" value="<?php echo to_money($no_spp); ?>"></td>
	</tr>
	<tr>	 
		<td width="100"> Kode Blok</td><td>  :  <input readonly="readonly" type="text" name="kode_blok" id="kode_blok" size="10" value="<?php echo $id; ?>"></td>
	</tr>
	<tr>
		<td> Nama</td><td> :  <input type="text" name="nama" id="nama" size="50" value="<?php echo $nama; ?>"></td>
	</tr>
	<tr>
		<td>Alamat Rumah </td><td colspan="2"> : <input type="text" name="alamat_rumah" id="alamat_rumah" size="100" value="<?php echo $alamat_rumah; ?>"><input type="checkbox" name="cek" id="cek" checked></td>
	</tr>
	<tr>
		<td>Alamat Surat </td><td colspan="2"> : <input type="text" name="alamat_surat" id="alamat_surat" size="100" value="<?php echo $alamat_surat; ?>"></td>
	</tr>
	<tr>
		<td>Alamat NPWP </td><td colspan="2"> : <input type="text" name="alamat_npwp" id="alamat_npwp" size="100" value="<?php echo $alamat_npwp; ?>"></td>
	</tr>
	<tr>
		<td>Alamat Email </td><td> : <input type="text" name="email" id="email" size="50" value="<?php echo $email; ?>"></td>
	</tr>

	<tr>
		<td> Telepon Rumah   
			<td colspan="2"> : <input type="text" name="tlp_rumah" id="tlp_rumah" size="15" value="<?php echo $tlp_rumah; ?>">
				Telepon Kantor : <input type="text" name="tlp_kantor" id="tlp_kantor" size="15" value="<?php echo $tlp_kantor; ?>">
				Telepon Lain   :<input type="text" name="tlp_lain" id="tlp_lain" size="15" value="<?php echo $tlp_lain; ?>"></td>
			</tr>
			<tr>
				<td>Identitas </td>
				<td>:
					<input type="radio" name="identitas" id="ktp" value="1" <?php echo is_checked('1', $identitas); ?>>KTP
					<input type="radio" name="identitas" id="sim" value="2" <?php echo is_checked('2', $identitas); ?>>SIM   
					<input type="radio" name="identitas" id="pasport" value="3" <?php echo is_checked('3', $identitas); ?>>Pasport
					<input type="radio" name="identitas" id="kims" value="4" <?php echo is_checked('4', $identitas); ?>> KIMS

					No : <input type="text" name="no_identitas" id="no_identitas" size="20" value="<?php echo $no_identitas; ?>">
				</td>
			</tr>
			<tr>
				<td> NPWP </td><td> : <input type="text" name="npwp" id="npwp" size="20" value="<?php echo $npwp; ?>">
				Jenis : 
				<select name="jenis_npwp" id="jenis_npwp">
					<option value="">   -- Jenis --   </option>
					<option value="1" <?php echo is_selected('1', $jenis_npwp); ?>>Non PKP</option>
					<option value="2" <?php echo is_selected('2', $jenis_npwp); ?>>PKP</option>
				</select>
			</td>

			<td align="left">Bank 
				<select name="kbank" id="kbank">
					<option value="0"> -- Bank -- </option>
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
						echo "<option value='$ov'".is_selected($ov, $kbank)."> $oj </option>";
						$obj->movenext();
					}
					?>
				</select>
			</td>
		</tr>
		<input type="hidden" name="jumlah_kpr" id="jumlah_kpr" size="20" value="<?php echo to_money($jumlah_kpr); ?>">
		<tr>
			<td>Status SPP <td>: 
				<select name="status_kompensasi" id="status_kompensasi">
					<option value="">   -- Status SPP --   </option>
					<option value="1" <?php echo is_selected('1', $status_kompensasi); ?>>KPR</option>
					<option value="2" <?php echo is_selected('2', $status_kompensasi); ?>>TUNAI</option>
					<option value="3" <?php echo is_selected('3', $status_kompensasi); ?>>KOMPENSASI</option>
					<option value="4" <?php echo is_selected('4', $status_kompensasi); ?>>ASSET SETTLEMENT</option>
					<option value="5" <?php echo is_selected('5', $status_kompensasi); ?>>KPR JAYA</option>
				</select>
			</td>
			<td align="left"> Tgl. Rencana Akad : <input type="text" name="tgl_akad" class = "apply dd-mm-yyyy " id="tgl_akad" size="20" value="<?php echo $tgl_akad; ?>"></td>
		</tr>


		<tr>
			<td> Distribusi SPP </td><td> : 
			<input type="radio" name="status_spp" id="sudah" class="status" value="1" <?php echo is_checked('1', $status_spp); ?>>Sudah
			<input type="radio" name="status_spp" id="belum" class="status" value="2" <?php echo is_checked('2', $status_spp); ?>>Belum  

			<input type="text" name="tgl_proses" id="tgl_proses" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_proses; ?>">
		</td>
		<td align="left"> Tanda Jadi : <input type="text" name="tanda_jadi" id="tanda_jadi" size="20" value="<?php if(!isset($tanda_jadi)){$tanda_jadi = 25000000;}echo ((to_money($tanda_jadi))); ?>"></td>
	</tr>


	<tr>
		<td>Redistribusi SPP </td><td>: 
		<select name="redistribusi" id="redistribusi">
			<option value="">   -- Redistribusi SPP --   </option>
			<option value="1" <?php echo is_selected('1', $redistribusi); ?>>Tidak</option>
			<option value="2" <?php echo is_selected('2', $redistribusi); ?>>Dalam Proses</option>
			<option value="3" <?php echo is_selected('3', $redistribusi); ?>>Selesai</option>
		</select>
		<input type="text" name="tgl_redistribusi" id="tgl_redistribusi" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_redistribusi; ?>">
	</td>
	<td align="left">Tgl. Tanda Jadi : <input type="text" name="tgl_tanda_jadi" id="tgl_tanda_jadi" size="10" class="apply dd-mm-yyyy" value="<?php echo $tgl_tanda_jadi; ?>"></td>
</tr>

<tr>
	<td>Nama Pejabat </td><td>: 	
	<input type="text" name="nama_pejabat" id="nama_pejabat" size="30" value="<?php echo $nama_pejabat; ?>" readonly>
</td>
</tr>
<tr>
	<td>    Jabatan </td><td>: 	
	<input type="text" name="nama_jabatan" id="nama_jabatan" size="30" value="<?php echo $nama_jabatan; ?>" readonly>
</td>
</tr>
<tr>
	<td>Nama Pejabat </td><td>: 	
	<input type="text" name="pejabat_spp" id="pejabat_spp" size="30" value="<?php echo $pejabat_spp; ?>" readonly>
</td>
</tr>
<tr>
	<td>    Jabatan </td><td>: 	
	<input type="text" name="jabatan_spp" id="jabatan_spp" size="30" value="<?php echo $jabatan_spp; ?>" readonly>
</td>
</tr>
<tr>
	<td>Nama Sales </td>
	<td>: 	
		<input type="text" name="nama_sales" id="nama_sales" size="30" value="<?php echo $nama_sales; ?>" readonly>
	</td>
</tr>

</table>
<table>
	<tr>
		<td>Form Tanda Tangan</td>
		<td><input type="button" name="add_btn" value="Tambah" id="add_btn"></td>
	</tr>
	<tr>
		<td>Nama</td><td>Jabatan</td><td>&nbsp;</td>
	</tr>
	<tbody id="ttd">
	<?php
		$obj = $conn->execute("
			SELECT *
			FROM 
			SPP_TANDA_TANGAN
			WHERE KODE_BLOK='$id';
			");
		$count=1;
		while( ! $obj->EOF)
		{	

			$kode_blok = $obj->fields['KODE_BLOK'];
			$nama = $obj->fields['NAMA'];
			$jabatan = $obj->fields['JABATAN'];
			
			echo "<tr class='records'>";
			echo "<input type='hidden' name='ttd[]' value='$count'>";
			echo "<td><input type='text' name='ttd_nama_$count' value='$nama' size='20'></td>";
			echo "<td><input type='text' name='ttd_jabatan_$count' value='$jabatan' size='30'></td>";
			echo "<td><input type='button' name='del_btn' value='Hapus' class='remove_item'></td>";
			echo "</tr>";
			$obj->movenext();
			$count++;
		}
		
		?>
		<!-- nanti rows nya muncul di sini -->
	</tbody>
</table>

<table class="t-popup pad2 w100">
	<tr>
		<td width="100">Keterangan </td><td>:</td>
		<td><textarea name="keterangan" id="keterangan" rows="2" cols="100"><?php echo $keterangan; ?></textarea></td>
	</tr>
	<tr>
		<td class="td-action" colspan="10"><br>
			<input type="button" id="<?php echo $tombol; ?>" value=" <?php echo $nama_tombol; ?> ">
			<input type="submit" id="save" value=" <?php echo $act; ?> ">
				<?php /*if($status_otorisasi== 1){
				echo '<input type="submit" id="print" value="Print">';
			}*/ ?>
			<input type="submit" id="print" value="Print">
			<input type="reset" id="reset" value=" Reset ">
			<input type="button" id="close" value=" Tutup ">
			<!-- PENAMBAHAN CHECKBOX UNTUK OTORISASI-->
			<input type="checkbox" name="cb_data[]" class="cb_data" style="opacity:0; position:absolute; left:9999px;" value="<?php echo $id; ?>" checked>
			<!-- END PENAMBAHAN CHECKBOX UNTUK OTORISASI-->
		</td>
	</tr>
</table>

<?php
close($conn);
exit;
?>
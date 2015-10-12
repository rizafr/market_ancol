<?php
	require_once('rencana_proses.php');
	require_once('../../../../config/config.php');
	$conn = conn($sess_db);
	ex_conn($conn);
	
	$id				= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
	$act			= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<!-- CSS -->
		<link type="text/css" href="../../../../config/css/style.css" rel="stylesheet">
		<link type="text/css" href="../../../../plugin/css/zebra/default.css" rel="stylesheet">
		<link type="text/css" href="../../../../plugin/window/themes/default.css" rel="stylesheet">
		<link type="text/css" href="../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">
		
		<!-- JS -->
		<script type="text/javascript" src="../../../../plugin/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="../../../../plugin/js/jquery.inputmask.custom.js"></script>
		<script type="text/javascript" src="../../../../plugin/js/keymaster.js"></script>
		<script type="text/javascript" src="../../../../plugin/js/zebra_datepicker.js"></script>
		<script type="text/javascript" src="../../../../plugin/window/javascripts/prototype.js"></script>
		<script type="text/javascript" src="../../../../plugin/window/javascripts/window.js"></script>
		<script type="text/javascript" src="../../../../config/js/main.js"></script>
		
	</head>
	
	<script type="text/javascript">
	jQuery(function($) {

		$('.cek_auto_akad').on('change', function(e) {
			if(jQuery("#akad_on:checked").val()=='yes'){
				$('#tanggal_akad').attr('readonly',true);
				jQuery("#tanggal_akad").val('<?php echo $tgl_akad;?>');	
			}
			else{
				$('#tanggal_akad').attr('readonly',false);
				
			}
		});

		$('.pola_bayar').on('change', function(e) {
			jQuery('#keterangan').val(jQuery('.pola_bayar:checked').attr('ket'));
			
			var tipe = jQuery('.pola_bayar:checked').attr('class');
			var hasil = tipe.split(" ");
			jQuery('#type').val(hasil[0]);
		});

		$('.cek_auto_awal').on('change', function(e) {
			if(jQuery("#awal_on:checked").val()=='yes'){
				$('#tgl_spp').attr('readonly',true);
				jQuery("#tgl_spp").val('<?php echo $tgl_spp;?>');
			}
			else{
				$('#tgl_spp').attr('readonly',false);
					
			}
		});

		
		$('#save').on('click', function(e) {
			e.preventDefault();

			var id		= '<?php echo $id; ?>';
			var url		= base_marketing_transaksi + 'spp/rencana_proses.php',
			data		= $('#form').serialize();
			
			
			if (confirm("Apakah data telah terisi dengan benar ?") == false)
			{
				return false;
			}			

			$.post(url, data, function(data) {
				if (data.error == true)
				{
					alert(data.msg);
				}				
				else
				{
					alert(data.msg);
					parent.loadData();

				}
			}, 'json');
			return false;
		});
		
	});
	
	</script>
	<body class="popup2">
		
		<form name="form" id="form" method="POST">
			<table class="t-popup">
				<tr>
					<td><b>Kode Blok</b></td><td>:</td>
					<td><input readonly="readonly" type="text" name="kode_blok" id="kode_blok" size="10" value="<?php echo $id; ?>"></td>
				</tr>
				<!--<tr>
					<td><b>Harga</b></td><td>:</td>
					<td><b>Rp. <input readonly="readonly" class="text-right" type="text" name="total_h" id="total_h" size="20" value="<?php echo to_money($r_harga_tanah + $r_harga_bangunan); ?>"></b></td>
					<input class="text-right" type="hidden" name="total" id="total" size="20" value="<?php echo ($r_harga_tanah + $r_harga_bangunan); ?>">
				<tr>
				-->
				<tr>
					<td><b>Tanda Jadi</b></td><td>:</td>
					<td><b>Rp. <input readonly="readonly" class="text-right" type="text" name="tanda_jadi" id="tanda_jadi" size="20" value="<?php echo to_money($tanda_jadi); ?>"></b></td>
				</tr>
				
				<tr>	
					<td>Jenis Transaksi</td><td>:</td>
					<td>
						<table>
						<?php
						$obj1 = $conn->execute("		
							SELECT * FROM POLA_BAYAR P JOIN DETAIL_POLA_BAYAR D ON P.KODE_POLA_BAYAR = D.KODE_POLA_BAYAR
							WHERE P.KODE_JENIS = '2' AND P.AKTIF = '1' AND D.KODE_BLOK = '$id'
							ORDER BY P.KODE_POLA_BAYAR
						");

						$obj2 = $conn->execute("		
							SELECT * FROM POLA_BAYAR P JOIN DETAIL_POLA_BAYAR D ON P.KODE_POLA_BAYAR = D.KODE_POLA_BAYAR
							WHERE P.KODE_JENIS = '1' AND P.AKTIF = '1' AND D.KODE_BLOK = '$id'
							ORDER BY P.KODE_POLA_BAYAR
						");

						$data1 = true;
						$data2 = true;

						while($data1||$data2)
						{
							echo '<tr><td>';
							if(! $obj1->EOF){
								$tp = $obj1->fields['KODE_JENIS'];
								$ov = $obj1->fields['KODE_POLA_BAYAR'];
								$oj = $obj1->fields['NAMA_POLA_BAYAR'];
								echo "<input type = 'radio' value = '$ov' name='pola_bayar' id='pola_bayar' class = 'cash pola_bayar' tipe = 'cash' ket = '$oj'> $oj";
								$obj1->movenext();
							}
							else{
								$data1 = false;
							}
							echo "<td>";
							if(!$obj2->EOF){
								$tp = $obj2->fields['KODE_JENIS'];
								$ov = $obj2->fields['KODE_POLA_BAYAR'];
								$oj = $obj2->fields['NAMA_POLA_BAYAR'];
								echo "<input type = 'radio' value = '$ov' name='pola_bayar' id='pola_bayar' class= 'kpr pola_bayar' tipe = 'kpr' ket = '$oj'> $oj";
								$obj2->movenext();	
							}
							else{
								$data2 = false;
							}
							echo "</tr>";
						}
						?>
						</table>
					<td>
				</tr>
				<!--	
				<tr>	
					<td>Bank </td>
					<td>:</td>					
					<td><select name="kbank" id="kbank">
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
				</tr>-->
				<tr>
					<td>Tanggal Bayar Awal</td>
					<td>:</td>
					<td><input type="radio" name = 'cek_auto_awal' id = "awal_on" class='cek_auto_awal' value = 'yes' checked> 1 Bulan setelah tgl. jadi </td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td><input type="radio" name = 'cek_auto_awal' id = "awal_off" class='cek_auto_awal' value = 'no'>Tentukan Tanggal : <input type="text" readonly name = "tgl_spp" id="tgl_spp" class = 'dd-mm-yyyy' value="<?php echo $tgl_spp ?>"></td>
				</tr>
				<tr>
					<td>Tanggal Akad</td>
					<td>:</td>
					<td><input type="radio" name = 'cek_auto_akad' id = 'akad_on' class='cek_auto_akad' value = 'yes' checked>  1 Bulan setelah pelunasan UM</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td><input type="radio" name = 'cek_auto_akad' class ='cek_auto_akad' value = 'no' >Tentukan Tanggal : <input type="text" readonly name = "tanggal_akad" id="tanggal_akad" class = 'dd-mm-yyyy' value="<?php echo date('d-m-Y'); ?>"></td>
				</tr>
				<td width="150" class="text-right"></td>
				<tr>
					<td><input type="submit" id="save" value="Apply"> <input type="button" id="close" value=" Tutup ">
					</td>
				</tr>
				<tr>
					<td colspan="3"><br></td>
				</tr>
				

			</table>
				<input type="hidden" name = "keterangan" id = "keterangan">
				<input type="hidden" name = "type" id = "type">
				<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
				<input type="hidden" name="act" id="act" value="Apply">
				<input type="hidden" name="kode_bayar" id="kode_bayar" value="4">
		</form>
	</body>
</html>
<?php close($conn); ?>
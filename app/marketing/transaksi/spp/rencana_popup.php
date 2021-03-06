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
	<link type="text/css" href="../../../../plugin/css/slider/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="../../../../config/css/style.css" rel="stylesheet">
	<link type="text/css" href="../../../../plugin/css/zebra/default.css" rel="stylesheet">
	<link type="text/css" href="../../../../plugin/window/themes/default.css" rel="stylesheet">
	<link type="text/css" href="../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">
	<link type="text/css" href="../../../../plugin/css/slider/bootstrap-slider.css" rel="stylesheet">


	<!-- JS -->
	<script type="text/javascript" src="../../../../plugin/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/jquery.inputmask.custom.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/keymaster.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/zebra_datepicker.js"></script>
	<script type="text/javascript" src="../../../../plugin/js/bootstrap-slider.js"></script>
	<script type="text/javascript" src="../../../../plugin/window/javascripts/prototype.js"></script>
	<script type="text/javascript" src="../../../../plugin/window/javascripts/window.js"></script>
	<script type="text/javascript" src="../../../../config/js/main.js"></script>

</head>

<script type="text/javascript">
	jQuery(function($) {

		$('#label_persentase').hide();
		$('#label_nilai_persentase').hide();

		$("#persentase").val(30);

		// With JQuery
		$("#ex1").slider();
		$("#ex1").on("slide", function(slideEvt) {
			$("#persentase").val(slideEvt.value);
		});

		// $('#kode_pola_bayar').on('change', function(e) {
			// e.preventDefault();
			// var kode_pola_bayar = $('#kode_pola_bayar').val();
			// var kode_pola = kode_pola_bayar.split("X");
			// alert(kode_pola[1]);
			// if(kode_pola[1]=='1'){
				// $('#label_persentase').show();
				// $('#label_nilai_persentase').show();

			// }else{
				// $('#label_persentase').hide();
				// $('#label_nilai_persentase').hide();

			// }
			// return false;
		// });


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

		$('#close').on('click', function(e) {
			e.preventDefault();
			return parent.loadData();
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
				<td>Kode Blok</td><td>:</td>
				<td><b><input readonly="readonly" type="text" name="kode_blok" id="kode_blok" size="10" value="<?php echo $id; ?>"></b></td>
			</tr>
				<!--<tr>
					<td><b>Harga</b></td><td>:</td>
					<td><b>Rp. <input readonly="readonly" class="text-right" type="text" name="total_h" id="total_h" size="20" value="<?php echo to_money($r_harga_tanah + $r_harga_bangunan); ?>"></b></td>
					<input class="text-right" type="hidden" name="total" id="total" size="20" value="<?php echo ($r_harga_tanah + $r_harga_bangunan); ?>">
				<tr>
				-->
				<tr>
					<td>Tanda Jadi</td><td>:</td>
					<td><b>Rp. <input readonly="readonly" class="text-right" type="text" name="tanda_jadi" id="tanda_jadi" size="20" value="<?php echo to_money($tanda_jadi); ?>"></b></td>
				</tr>
				
				<tr>	
					<td>Jenis Transaksi</td><td>:</td>
					<td>
						<select name="kode_pola_bayar" id="kode_pola_bayar" class="wauto">
							<option value=""> ---PILIH POLA BAYAR--- </option>
							<?php
							$obj = $conn->execute("
								SELECT * FROM POLA_BAYAR
								WHERE AKTIF = '1'							
								");
							while( ! $obj->EOF)
							{
								$kj = $obj->fields['KODE_JENIS'];
								$ov = $obj->fields['KODE_POLA_BAYAR'];
								$oj = $obj->fields['NAMA_POLA_BAYAR'];
								$value = $obj->fields['KODE_POLA'];?>
								<option value="<?= $ov."X".$kj ?>" <?php echo is_selected($ov)?> > <?php echo $oj ?> </option>
							<?php	$obj->movenext();
							
						}
							?>
						</select>
						<td>
						</tr>
						<!--<tr id="label_persentase">
							<td>Persentase KPA</td>
							<td>:</td>
							<td><input id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="30"/></td>
						</tr>
						<tr id="label_nilai_persentase">
							<td></td>
							<td>:</td>
							<td><input id="persentase" type="text" name="persentase" value="" size="3" readonly/> %</td>
						</tr>-->
						</div>
						<tr>
							<td>Tanggal Bayar Awal</td>
							<td>:</td>
							<td><input type="text" name = "tgl_spp" id="tgl_spp" class = 'dd-mm-yyyy' value="<?php echo date('d-m-Y') ?>"></td>
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
					<!--<input type="hidden" name = "type" id = "type">-->
					<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
					<input type="hidden" name="act" id="act" value="Apply">
					<input type="hidden" name="kode_bayar" id="kode_bayar" value="4">
				</form>
			</body>
			</html>
			<?php close($conn); ?>
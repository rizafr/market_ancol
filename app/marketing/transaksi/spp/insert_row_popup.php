<?php
	require_once('insert_row_proses.php');
	require_once('../../../../config/config.php');
	$conn = conn($sess_db);
	ex_conn($conn);
	
	$id				= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
	$act			= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';

	$query = "SELECT * FROM JENIS_PEMBAYARAN ORDER BY JENIS_BAYAR, KODE_BAYAR";
	$data = $conn->Execute($query);
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
		
		//menghitung sisa_bayar
		var sisa_bayar = 0;
		var total =maul_conv(jQuery('#total_bayar').val());
		var current = maul_conv(jQuery('#total_current').val());
		 sisa_bayar = total-current;
		 jQuery('#sisa_bayar').val(sisa_bayar);
		 
		$.fn.digits = function(){ 
		    return this.each(function(){ 
		        $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
		    })
		}
		jQuery('#tanggal_bayar_1').Zebra_DatePicker({
			format: 'm-d-Y'
		});

		$("#nilai_1").digits();

		jQuery('.nilai').inputmask('decimal', { repeat: '30', decimal: '.',negative:false, scale: '19', groupSize:3, groupSeparator: ',',autoGroup: true});
		jQuery('#total_current').inputmask('decimal', { repeat: '30', decimal: '.',negative:false, scale: '19', groupSize:3, groupSeparator: ',',autoGroup: true});
		jQuery('#total_bayar').inputmask('decimal', { repeat: '30', decimal: '.',negative:false, scale: '19', groupSize:3, groupSeparator: ',',autoGroup: true});
		jQuery('#sisa_bayar').inputmask('decimal', { repeat: '30', decimal: '.',negative:false, scale: '19', groupSize:3, groupSeparator: ',',autoGroup: true});

		$('#tutup').on('click', function(e) {
			e.preventDefault();
			return parent.loadData();
		});

		$('#save').on('click', function(e) {
			e.preventDefault();

			var id		= '<?php echo $id; ?>';
			var url		= base_marketing_transaksi + 'spp/insert_row_proses.php',
			data		= $('#form').serialize();
			
			var total = maul_conv(jQuery('#total_current').val());
			var total_bayar = maul_conv(jQuery('#total_bayar').val());
			
			
			if(cek_tanggal()){
				return false;
			}

			if(cek_total()){
				return false;
			}

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
	
	function cek_total(){
		var total_current = maul_conv(jQuery('#total_current').val());
		var total_bayar = maul_conv(jQuery('#total_bayar').val());		
		var sisa_bayar = maul_conv(jQuery('#sisa_bayar').val());		
		if (total_current > total_bayar) {
			alert('TOTAL CURRENT TIDAK BOLEH MELEBIHI TOTAL BAYAR.');
			return	true;
		}
		if (sisa_bayar > 0) {
			alert('MASIH ADA KEKURANGAN SISA PEMBAYARAN.');
			if (sisa_bayar > 0) {
			document.getElementById("sisa_bayar").style.color = "red";
			}
			else{
				document.getElementById("sisa_bayar").style.color = "black";
			}
			return	true;
		}
		
		
		
	
	}
	
	function cek_tanggal(){
		var max = Number(jQuery('#max').val());	
		for(var a=1;a<=max;a++){
			var tanggal = jQuery('#tanggal_bayar_'+a).val();
			if (tanggal=='') {
				alert('Ada tanggal yang kosong');
				return	true;
			}
		}
	}
	function add_blok()
	{
		var max = Number(jQuery('#max').val());
		id = max + 1;
		jQuery('#max').val(id);
		jQuery('' + 
		'<tr id="tr-ref-'+id+'">' +
			'<td>'+id+'</td>'+
			'<td><input type="text" name="tanggal_bayar_'+id+'" size="15" id="tanggal_bayar_'+id+'" class="tanggal_bayar" value=""></td>'+
			'<td><select name = "jenis_bayar_'+id+'" id = "jenis_bayar_'+id+'">'+
			 <?php while (!$data->EOF) {
			 	echo "'<option value = \"".$data->fields['KODE_BAYAR']."\">".$data->fields['JENIS_BAYAR']."</option>'+";
			 	$data->movenext();
			 }
			 ?>
			'</select></td>'+
			'<td><input type="text" name="nilai_'+id+'" size="15" id="nilai_'+id+'" class="nilai" value="" onchange = "hitung_all()">'+
				'<input type="button" value=" X " onclick="del_blok(\''+id+'\')"> ' +
				'<input type="hidden" value="'+id+'" class="ini_id">'+
			'</td>' +
		'</tr>' +
		'').insertAfter('#tr-ref-'+ max);
		
		// jQuery('#nilai_'+id).inputmask('decimal', { repeat: '30', decimal: '.',negative:false, scale: '19', groupSize:3, groupSeparator: ',',autoGroup: true});
		jQuery('#nilai_'+id).inputmask('numeric', { repeat: '12' });
		
		jQuery('#tanggal_bayar_'+id).Zebra_DatePicker({
			format: 'd-m-Y'
		});
		return false;
	}

	function del_blok(id)
	{
		id = parseInt(id);
		var max = parseInt(jQuery('#max').val());
		if(max==1){
			jQuery('#tanggal_bayar_1').val('');
			jQuery('#jenis_bayar_1').val('');
			jQuery('#nilai_1').val('');
		}else{
			
			if(id!=max){
				var total = max-id;
				var run = id;
				for(var a=0;a<total;a++){
					var tanggal = jQuery('#tanggal_bayar_'+(run+1)).val();
					var jenis_bayar = jQuery('#jenis_bayar_'+(run+1)).val();
					var nilai = jQuery('#nilai_'+(run+1)).val();
					
					jQuery('#tanggal_bayar_'+run).val(tanggal);
					jQuery('#jenis_bayar_'+run).val(jenis_bayar);
					jQuery('#nilai_'+run).val(nilai);
					run++;
				}
			}
			jQuery('#max').val(max - 1);
			jQuery('#tr-ref-' + max).remove();			
		}
		hitung_all();
		return false;
	}
	function maul_conv(x){
			if(x==''){
				return 0;
			}
			 return parseFloat(x.replace(',','').replace(',','').replace(',',''));
	}
	function hitung_all(){
		var max = Number(jQuery('#max').val());
		var total = 0;
		
		for(var a = 1;a<=max;a++){
			total += maul_conv(jQuery('#nilai_'+a).val());
		}
		jQuery('#total_current').val(total);
		var total_bayar = maul_conv(jQuery('#total_bayar').val());
		
		//menghitung sisa_bayar
		var sisa_bayar = 0;
		var total =maul_conv(jQuery('#total_bayar').val());
		var current = maul_conv(jQuery('#total_current').val());
		 sisa_bayar = total-current;
		 jQuery('#sisa_bayar').val(sisa_bayar);
		
		if(total!=total_bayar){
			document.getElementById("total_current").style.color = "red";
		}
		else{
			document.getElementById("total_current").style.color = "black";
		}
		return false;
		
		
	}

	
	</script>
		<body class="popup2">
		
		<form name="form" id="form" method="POST">
			<table class="t-data w100">
				<tr>
					<th class="w5">NO.</th>
					<th class="w15">TANGGAL</th>
					<th class="w15">JENIS PEMBAYARAN</th>
					<th class="w15">NILAI (RP)</th>
				</tr>
				<?php
					$run = 1;
					if($total>0){
						while(!$data_->EOF){
						?>
							<tr id="tr-ref-<?php echo $run;?>">

								<td><?php echo $run;?></td>
								<td><input type="text" name="tanggal_bayar_<?php echo $run;?>" id="tanggal_bayar_<?php echo $run;?>" class="tanggal_bayar" size="15" value="<?php echo date('d-m-Y', strtotime($data_->fields['TANGGAL']));?>"></td>
								<td>
									<select name="jenis_bayar_<?php echo $run;?>" id="jenis_bayar_<?php echo $run;?>">
									<?php
										$query = "SELECT * FROM JENIS_PEMBAYARAN ORDER BY JENIS_BAYAR, KODE_BAYAR";
										$data = $conn->Execute($query);
										while (!$data->EOF) {
											$select = '';
											if($data->fields['KODE_BAYAR']==$data_->fields['KODE_BAYAR']){
												$select = 'selected';
											}
										 	echo "<option value = '".$data->fields['KODE_BAYAR']."' $select>".$data->fields['JENIS_BAYAR']."</option>";
										 	$data->movenext();
										}
										?>
									</select>
								</td>
								<td><input type="text" name="nilai_<?php echo $run;?>" id="nilai_<?php echo $run;?>" class="nilai" size="15" value="<?php echo $data_->fields['NILAI'];?>" onchange="hitung_all()">
									<?php if($run==1){?>
									<input type="button" value=" X " onclick="del_blok(1)">
									<input type="button" value=" + " onclick="add_blok()">
									<?php }else{?>
									<input type="button" value=" X " onclick="del_blok('<?php echo $run;?>')">
									<?php
									}
									?>
									<input type="hidden" value="<?php echo $run;?>" class="ini_id">
								</td>
							</tr>
							<script type="text/javascript">
									jQuery('#tanggal_bayar_<?php echo $run;?>').Zebra_DatePicker({
										format: 'd-m-Y'
									});
							</script>
						<?php
						$run++;
						$data_->movenext();
						}
					}else{
				?>
				<tr id="tr-ref-1">
					<td>1</td>
					<td><input type="text" name="tanggal_bayar_1" id="tanggal_bayar_1" class="tanggal_bayar" size="15" value=""></td>
					<td>
						<select name="jenis_bayar_1" id="jenis_bayar_1">
							<?php
							$query = "SELECT * FROM JENIS_PEMBAYARAN ORDER BY JENIS_BAYAR, KODE_BAYAR";
							$data = $conn->Execute($query);
							while (!$data->EOF) {
							 	echo "<option value = '".$data->fields['KODE_BAYAR']."'>".$data->fields['JENIS_BAYAR']."</option>";
							 	$data->movenext();
							}
							?>
						</select>
					</td>
					<td><input type="text" name="nilai_1" id="nilai_1" class="nilai" size="15" value="0" onchange="hitung_all()">
						<input type="button" value=" + " onclick="add_blok()">
						<input type="hidden" value="1" class="ini_id">
					</td>
				</tr>
				<?php
					}
				?>
				<tr>
					<td colspan="2">Total Running : </td>
					<td><input type="text" name="total_current" id = "total_current" readonly value="<?php echo $nilai_total;?>"></td>
				</tr>
				<tr>
					<td colspan="2">Total Bayar : </td>
					<td><input type="text" name="total_bayar" id="total_bayar" readonly value="<?php echo $r_total;?>"></td>
				</tr>
				<tr>
					<td colspan="2">Sisa Bayar : </td>
					<td><input type="text" name="sisa_bayar" id="sisa_bayar" readonly value=""></td>
				</tr>
			</table>
			<input type="submit" id="save" value="Apply"> <input type="button" id="tutup" value=" Tutup ">
			<input type="hidden" name="max" id="max" value="<?php echo $total;?>">
				<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

				<input type="hidden" name="act" id="act" value="Apply">
				<input type="hidden" name="kode_bayar" id="kode_bayar" value="4">
		</form>
	</body>
</html>
<?php close($conn); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<!-- CSS -->
		<link type="text/css" href="../../../../../config/css/style.css" rel="stylesheet">
		<link type="text/css" href="../../../../../plugin/css/zebra/default.css" rel="stylesheet">
		<link type="text/css" href="../../../../../plugin/window/themes/default.css" rel="stylesheet">
		<link type="text/css" href="../../../../../plugin/window/themes/mac_os_x.css" rel="stylesheet">
		
		<!-- JS -->
		<script type="text/javascript" src="../../../../../plugin/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="../../../../../plugin/js/jquery.ajaxfileupload.js"></script>
		<script type="text/javascript" src="../../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="../../../../../plugin/js/jquery.inputmask.custom.js"></script>
		<script type="text/javascript" src="../../../../../plugin/js/keymaster.js"></script>
		<script type="text/javascript" src="../../../../../plugin/js/zebra_datepicker.js"></script>
		<script type="text/javascript" src="../../../../../plugin/window/javascripts/prototype.js"></script>
		<script type="text/javascript" src="../../../../../plugin/window/javascripts/window.js"></script>
		<script type="text/javascript" src="../../../../../config/js/main.js"></script>
		
		<script type="text/javascript">
			var this_base = base_marketing + 'collection_tunai/lain/upload_penerimaan_va';
			var get_base = base_marketing + 'operasional/get/';
			
			var itvl;
			
			jQuery(function($) {
				
				$('#close').on('click', function(e) {
					e.preventDefault();
					parent.loadData();	
				});
				
				
				
				$('#import').on('click', function(e) {
					e.preventDefault();
					
					$('#t-detail').html('');
					var nama_bank = $("#nama_bank").val();
					
					$.ajaxFileUpload({
						url : base_marketing + 'collection_tunai/lain/upload_penerimaan_va/import_upload.php', 
						secureuri : false,
						fileElementId : 'file_import',
						data : { nama_bank : nama_bank },
						dataType : 'json',
						beforeSend : function() {
							
						},
						success: function(data, status) {
							
							if (data.error == false) {
								$('#t-detail').load(base_marketing + 'collection_tunai/lain/upload_penerimaan_va/import_load.php?nama_bank=' + nama_bank);
								} else {
								alert(data.msg);
							}
						}
					});
				});
			});
			
			
		</script>
	</head>
	
	<body class="popup2">
		
		<div class="title-page">IMPORT DATA BANK</div>
		<form method="post" enctype="multipart/form-data" name="form" id="form">
			<table class="t-control wauto-center" style="color:#333;">
				<tr>
					<td width="50">BANK</td>
					<td>
						<?php
							$list_bank = array(
							'8' => 'BCA (*.txt) [BC]',
							// 'BUKOPIN' => 'BUKOPIN (*.xls) [BK]',
							#'BUKOPIN_AD' => 'BUKOPIN AUTODEBET (*.xls) [XX]',
							// 'BUMIPUTERA' => 'BUMIPUTERA (*.xls) [BB]',
							'12' => 'MANDIRI (*.csv) [BM]',
							// 'NIAGA' => 'NIAGA (*.txt) [BN]',
							// 'NIAGA_AD' => 'NIAGA AUTODEBET (*.txt) [BN]',
							// 'PERMATA' => 'PERMATA (*.txt) [BP]',
							);
						?>
						<select name="nama_bank" id="nama_bank">
							<?php 
								foreach ($list_bank AS $kb => $nb)
								{
									echo "<option value='$kb'> $nb </option>";
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>FILE</td>
					<td>
						<input type="file" name="file_import" id="file_import">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
				</tr>
				<tr>
					<td>
					<td>
						<input type="submit" id="import" value=" Import ">&nbsp;&nbsp;
						<input type="button" id="close" value=" Tutup ">&nbsp;&nbsp;
					</td>
				</tr>
				
			</table>
		</form>
		
		<br>
	<div id="t-detail"></div>		
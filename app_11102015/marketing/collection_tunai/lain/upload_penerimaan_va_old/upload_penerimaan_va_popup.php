<?php
	require_once('upload_penerimaan_va_proses.php');
	require_once('../../../../../config/config.php');
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
		<script type="text/javascript" src="../../../../../plugin/js/jquery-1.10.2.min.js"></script>
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
			var url		= this_base + '/upload_penerimaan_va_proses.php';
			jQuery(function($) {
				// loadData() ;
				$('#kode_blok').inputmask('varchar', { repeat: '15' });
				$('#luas_tanah, #luas_bangunan').inputmask('numericDesc', {iMax:10, dMax:2});
				$('#disc_tanah, #disc_bangunan').inputmask('numericDesc', {iMax:4, dMax:12});
				$('#ppn_tanah, #ppn_bangunan').inputmask('numericDesc', {iMax:3, dMax:2});
				
				$('#tutup').on('click', function(e) {
					e.preventDefault();
					parent.loadData();	
				});
				
				// $('#simpan').on('click', function(e) {
					// e.preventDefault();
					$("#form").submit(function(){

					var formData = new FormData($(this)[0]);

					$.ajax({
					
						url: url,
						type: 'POST',
						data: formData,
						async: false,
						success: function (data) {
							alert(data);
							// location.reload();
							parent.loadData();
							
						},
						cache: false,
						contentType: false,
						processData: false
					});

					return false;
					});
					
			});
			
			
		</script>
	</head>
	
	<body class="popup2">
		<div class="title-page">Upload Penerimaan Virtual Account</div>
		<form name="form" id="form" method="post" enctype="multipart/form-data" action="">
			<table class="t-popup wauto f-left">
				<tr>
					<td width="120"><b>Pilih File CSV:</td><td>:</b></td>
					<td  width="320">
						<!--<input type="file" name="file" id="file" onChange="setvalueuplfile(this.value,'file','xls')" required />-->
						<input type="file" name="data_upload" id="data_upload" onChange="setvalueuplfile(this.value,'data_upload', 'csv')" required="true">
						<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
						<div id="info"></div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td width="120"><i>*Hanya files dengan ekstensi csv</i></td>
				</tr>
			</table>
			
			
			<div class="clear"><br></div>
			<div class="clear"><br></div>
			
			<table class="t-popup">
				<tr>
					<td>
						<input type="submit" id="simpan" value=" Upload ">
						<input type="reset" id="reset" value=" Reset ">
						<input type="button" id="tutup" value=" Tutup ">
					</td>
				</tr>
			</table>
			
			<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
			<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
		</form>
		 <div id = 'data-load'></div>
	</body>
</html>

<?php close($conn); ?>
<script>
	function setvalueuplfile(v,n,f) { 
		if (f!="") {
			var pass=false;
			var af=f.split("/");
			var nval=eval("document.forms[0]."+n);
			var ext=v.substring(v.lastIndexOf(".")+1,v.length);
			if ((ext==f)||(ext=='csv')||(ext=='CSV')){ 
				document.forms[0].a_file.value=v; 
			}
			else{
				alert ("Hanya untuk file berekstensi '"+f+"'");
				nval.value="";
				document.forms[0].a_file.value=""; 
				return;	  
			}
		}
	}
</script>


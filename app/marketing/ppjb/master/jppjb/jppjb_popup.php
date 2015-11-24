<?php
require_once('jppjb_proses.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<!-- CSS -->
<link type="text/css" href="../../../../../config/css/style.css" rel="stylesheet">
<link type="text/css" href="../../../../../plugin/css/zebra/default.css" rel="stylesheet">

<!-- JS -->
<script type="text/javascript" src="../../../../../plugin/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/jquery.inputmask.custom.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/keymaster.js"></script>
<script type="text/javascript" src="../../../../../plugin/js/zebra_datepicker.js"></script>
<script type="text/javascript" src="../../../../../config/js/main.js"></script>
<script type="text/javascript">

	function setvalueuplfile(v,n,f) { 
		if (f!="") {
			var pass=false;
			var af=f.split("/");
			var nval=eval("document.forms[0]."+n);
			var ext=v.substring(v.lastIndexOf(".")+1,v.length);
			if ((ext==f)||(ext=='doc')||(ext=='docx')||(ext=='DOC')||(ext=='DOCX')){ 
				//document.forms[0].a_file.value=v; 
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
<script type="text/javascript">
$(function() {
	$('#kode_jenis').inputmask('numeric', { repeat: '3' });
	$('#nama_jenis').inputmask('varchar', { repeat: '30' });
	$('#nama_file').inputmask('varchar', { repeat: '40' });
	
	$('#close').on('click', function(e) {
		e.preventDefault();
		return parent.loadData();
	});

	$('#data_upload').change(function() {
				var filename = $('#data_upload').val();
				if (filename.substring(3,11) == 'fakepath' )    { 
					filename = filename.substring(12); 
			        }// remove c:\fake at beginning from localhost chrome
			        $('#nama_file').val(filename);
			 });

	
	// $('#save').on('click', function(e) {
	// 	e.preventDefault();
	// 	var url		= base_marketing + 'ppjb/master/jppjb/jppjb_proses.php',
	// 		data	= $('#form').serialize();
			
	// 	$.post(url, data, function(data) {			
	// 		if (data.error == true)
	// 		{
	// 			alert(data.msg);
	// 		}
	// 		else
	// 		{
	// 			if (data.act == 'Tambah')
	// 			{
	// 				alert(data.msg);
	// 				$('#reset').click();
	// 			}
	// 			else if (data.act == 'Ubah')
	// 			{
	// 				alert(data.msg);
	// 				parent.loadData();
	// 			}
	// 		}
	// 	}, 'json');		
	// 	return false;
	// });

	$("#form").submit(function(){

		var formData = new FormData($(this)[0]);
		var data	= $('#form').serialize();
		var url		= base_marketing + 'ppjb/master/jppjb/jppjb_proses.php';
		$.ajax({
			url: url,
			type: 'POST',
			//data: data,
			data: new FormData(this),
			 dataType: "json",
	        contentType: "application/json; charset=utf-8",
			  async: true,
			success: function (data) {
				//alert(data);
				if (data.error == true)
				{	

					alert(data.msg);
				}
				else
				{
					if (data.act == 'Tambah')
					{
						alert(data.msg);
						$('#reset').click();
						parent.loadData();
					}
					else if (data.act == 'Ubah')
					{
						alert(data.msg);
						parent.loadData();
					}
				}				
			},
			error: function(data) {
				alert(data.error);
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
<body class="popup">

<form name="form" id="form" method="post" enctype="multipart/form-data">
<table class="t-popup">
<tr>
	<td width="100">Kode</td><td>:</td>
	<td><input type="text" name="kode_jenis" id="kode_jenis" size="3" value="<?php echo $kode_jenis; ?>"></td>
</tr>
<tr>
	<td>Jenis PPJB</td><td>:</td>
	<td><input type="text" name="nama_jenis" id="nama_jenis" size="30" value="<?php echo $nama_jenis; ?>"></td>
</tr>
<tr>
	<td>Nama File</td><td>:</td>
	<td><input type="text" name="nama_file" id="nama_file" size="40" value="<?php echo $nama_file; ?>" readonly></td>
</tr>
<tr>
	<td>Pilih File Template:</td><td>:</td>
	<td  width="420">
		<!--<input type="file" name="file" id="file" onChange="setvalueuplfile(this.value,'file','xls')" required />-->
		<input type="file" name="data_upload" id="data_upload" onChange="setvalueuplfile(this.value,'data_upload', 'doc')" required="true">
	</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td width="120"><i>*Hanya files dengan ekstensi doc atau docx</i></td>
</tr>
<tr>
	<td colspan="3" class="td-action text-center">
		<input type="submit" id="save" value=" <?php echo $act; ?> ">
		<input type="reset" id="reset" value=" Reset ">
		<input type="button" id="close" value=" Tutup "></td>
	</td>
</tr>
</table>

<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
<input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
</form>

</body>
</html>
<?php close($conn); ?>

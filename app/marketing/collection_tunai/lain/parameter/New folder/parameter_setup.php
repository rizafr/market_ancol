<?php
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<div class="title-page">PARAMETER COLLECTION</div>

<form name="form" id="form" method="post">

<script type="text/javascript">
jQuery142(document).ready(function() {
	jQuery142('#tab1').fadeIn('slow'); //tab pertama ditampilkan
	jQuery142('ul#nav_tab li a').click(function() { // jika link tab di klik
		jQuery142('ul#nav_tab li a').removeClass('active'); //menghilangkan class active (yang tampil)
		jQuery142(this).addClass("active"); // menambahkan class active pada link yang diklik
		jQuery142('.tab_konten').hide(); // menutup semua konten tab
		var aktif = jQuery142(this).attr('href'); // mencari mana tab yang harus ditampilkan
		jQuery142(aktif).fadeIn('slow'); // tab yang dipilih, ditampilkan
		return false;
	});
});
</script>
<script type="text/javascript">


jQuery(function($) {

	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});

	$('#save').on('click', function(e) {
		e.preventDefault();
		var url = base_marketing + 'collection_tunai/lain/parameter/parameter_proses.php',
			data = $('#form').serialize();
				
		if (confirm("Apakah data parameter akan dirubah?") == false)
		{
			return false;
		}	
		
		$.post(url, data, function(data) {
			alert(data.msg);
			loadData();
		}, 'json');
		
		return false;
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#main_parameter').load(base_marketing + 'collection_tunai/lain/parameter/parameter_load.php', data);	
	return false;
}
</script>

		<div class="t-control" id="main_parameter"></div>
	<div>
		<table class="t-form">
		<tr><td><br></td></tr>
		<tr>
			<td>
				<input type="submit" id="save" value=" Ubah ">
				<input type="reset" id="reset" value=" Reset ">
			</td>
		</tr>
		</table>
	</div>
</form>

<?php close($conn); ?>
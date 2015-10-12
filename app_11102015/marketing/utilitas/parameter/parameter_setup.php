<?php
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<div class="title-page">PARAMETER MARKETING</div>

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

	$('#batas_distribusi, #tenggang_distribusi, #batas reserve').inputmask('numeric', { repeat: '10' });

	$(document).on('click', '#save', function(e) {
	
		e.preventDefault
		jQuery('#act').val('ubah');
		var url = base_marketing + 'utilitas/parameter/parameter_proses.php',
			data = $('#form').serialize();
	
		if (confirm("Apakah anda yakin mengubah data parameter ?") == false)
		{
			return false;
		}	
		
		$.post(url, data, function(data) {
			if (data.error == true)
			{
				alert(data.msg);
			}
				else if (data.act == 'ubah')
				{
					alert(data.msg);
					loadData();
				}
		}, 'json');
		return false;
	});

	$(document).ready(function(){
	$('a').click(function(){ 
	jQuery('#reset').click();
	});
    });

	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab1').load(base_marketing + 'utilitas/parameter/parameter_load.php', data);	
	return false;
}

</script>

<div id="container">
    <ul id="nav_tab">
        <li><a href="#tab1" class="active">Distribusi</a></li>
    </ul>
    <div class="clear"></div>
    <div id="konten">
    	<div style="display: none;" id="tab1" class="tab_konten"></div>
    </div>
</div>
</form>

<?php close($conn); ?>
<?php
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<div class="title-page">PARAMETER PPJB</div>

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
	
	$('#nama_pt').inputmask('varchar', { repeat: '40' }); 
	$('#unit').inputmask('varchar', { repeat: '30' }); 
	$('#nama_dep').inputmask('varchar', { repeat: '40' });
	$('#nama_pejabat').inputmask('varchar', { repeat: '30' }); 
	$('#nama_jabatan').inputmask('varchar', { repeat: '30' });
	$('#kota').inputmask('varchar', { repeat: '20' }); 
	
	$('#pejabat_ppjb').inputmask('varchar', { repeat: '30' });
	$('#jabatan_ppjb').inputmask('varchar', { repeat: '30' }); 
	$('#nomor_sk').inputmask('varchar', { repeat: '25' });
	$('#jumlah_hari').inputmask('numeric', { repeat: '2' }); 
	$('#nomor_ppjb').inputmask('numeric', { repeat: '4' });
	$('#reg_ppjb').inputmask('varchar', { repeat: '20' });
	$('#nomor_ppjb_ph').inputmask('numeric', { repeat: '4' });
	$('#reg_ppjb_ph').inputmask('varchar', { repeat: '20' });

	$(document).on('click', '#ubah1', function(e) {
	
		e.preventDefault
		jQuery('#act').val('save1');
		var url = base_marketing + 'ppjb/lain/parameter/parameter_proses.php',
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
				else if (data.act == 'save1')
				{
					alert(data.msg);
					loadData1();
				}
		}, 'json');
		return false;
	});

	$(document).on('click', '#ubah2', function(e) {
	
		e.preventDefault
		jQuery('#act').val('save2');
		var url = base_marketing + 'ppjb/lain/parameter/parameter_proses.php',
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
				else if (data.act == 'save2')
				{
					alert(data.msg);
					loadData2();
				}
		}, 'json');
		return false;
	});
	
	$(document).ready(function(){
	$('a').click(function(){ 
	jQuery('#reset').click();
	});
    });
	
	loadData1();
	loadData2();
});

function loadData1()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab1').load(base_marketing + 'ppjb/lain/parameter/parameter_perusahaan.php', data);	
	return false;
}

function loadData2()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab2').load(base_marketing + 'ppjb/lain/parameter/parameter_ppjb.php', data);	
	return false;
}

</script>

	<div id="container">
    <ul id="nav_tab">
        <li><a href="#tab1" class="active">PERUSAHAAN</a></li>
        <li><a href="#tab2" >PPJB</a></li>
    </ul>
    <div class="clear"></div>
    <div id="konten">
    	<div style="display: none;" id="tab1" class="tab_konten"></div>
    	<div style="display: none;" id="tab2" class="tab_konten"></div>
    </div>
</div>
</form>

<?php close($conn); ?>
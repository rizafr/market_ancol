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

	$('#nama_pt').inputmask('varchar', { repeat: '40' }); 
	$('#nama_dep').inputmask('varchar', { repeat: '40' });
	$('#nama_pejabat').inputmask('varchar', { repeat: '30' }); 
	$('#nama_jabatan').inputmask('varchar', { repeat: '30' });	
	$('#pemb_jatuh_tempo').inputmask('numeric', { repeat: '2' });
	$('#somasi_satu').inputmask('numeric', { repeat: '3' });
	$('#somasi_dua').inputmask('numeric', { repeat: '3' });	
	$('#somasi_tiga').inputmask('numeric', { repeat: '3' });
	$('#wanprestasi').inputmask('numeric', { repeat: '3' });
	$('#undangan_pembatalan').inputmask('numeric', { repeat: '3' });
	
	$('#nilai_sisa_tagihan').inputmask('varchar', { repeat: '5' }); 
	$('#masa_berlaku_denda').inputmask('numeric', { repeat: '2' });

	
	$(document).on('click', '#ubah1', function(e) {
	
		e.preventDefault
		jQuery('#act').val('save1');
		var url = base_marketing + 'collection_tunai/lain/parameter/parameter_proses.php',
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
		var url = base_marketing + 'collection_tunai/lain/parameter/parameter_proses.php',
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

	$(document).on('click', '#ubah3', function(e) {
	
		e.preventDefault
		jQuery('#act').val('save3');
		var url = base_marketing + 'collection_tunai/lain/parameter/parameter_proses.php',
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
				else if (data.act == 'save3')
				{
					alert(data.msg);
					loadData3();
				}
		}, 'json');
		return false;
	});

	$(document).on('click', '#ubah4', function(e) {
	
		e.preventDefault
		jQuery('#act').val('save4');
		var url = base_marketing + 'collection_tunai/lain/parameter/parameter_proses.php',
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
				else if (data.act == 'save4')
				{
					alert(data.msg);
					loadData4();
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
	loadData3();
	loadData4();
});


function loadData1()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab1').load(base_marketing + 'collection_tunai/lain/parameter/collection_parameter_perusahaan.php', data);	
	return false;
}

function loadData2()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab2').load(base_marketing + 'collection_tunai/lain/parameter/collection_parameter_Jatuh_tempo.php', data);	
	return false;
}

function loadData3()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab3').load(base_marketing + 'collection_tunai/lain/parameter/collection_parameter_lain_lain.php', data);	
	return false;
}

function loadData4()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#tab4').load(base_marketing + 'collection_tunai/lain/parameter/collection_parameter_nomor_surat.php', data);	
	return false;
}

</script>

<div id="container">
    <ul id="nav_tab">
        <li><a href="#tab1" class="active">PERUSAHAAN</a></li>
        <li><a href="#tab2" >TENGGANG WAKTU PENYURATAN DARI JATUH TEMPO</a></li>
        <li><a href="#tab3" >LAIN-LAIN</a></li>
        <li><a href="#tab4" >NOMOR SURAT</a></li>
    </ul>
    <div class="clear"></div>
    <div id="konten">
    	<div style="display: none;" id="tab1" class="tab_konten"></div>
    	<div style="display: none;" id="tab2" class="tab_konten"></div>
    	<div style="display: none;" id="tab3" class="tab_konten"></div>
    	<div style="display: none;" id="tab4" class="tab_konten"></div>
    </div>
</div>
</form>

<?php close($conn); ?>
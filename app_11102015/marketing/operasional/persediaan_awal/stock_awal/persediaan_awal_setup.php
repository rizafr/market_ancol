<script type="text/javascript">
var this_base = base_marketing + 'operasional/persediaan_awal/';

jQuery(function($) {
	
	var stat = 0;
	
	$(document).on('keypress', '.apply', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { $('#apply').trigger('click'); }
	});	
	
	/* -- BUTTON -- */

	$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		if(stat == 0){
			loadData1();
		} else if(stat == 1){
			loadData2();
		} else if(stat == 2){
			loadData3();
		} else if(stat == 3){
			loadData4();
		}
		
		return false;
	});	

	$(document).on('click', '#next_page', function(e) {
		e.preventDefault();
		var total_page = parseInt($('#total_page').val()), page_num = parseInt($('.page_num').val()) + 1;
		if (page_num <= total_page) { $('.page_num').val(page_num); $('#apply').trigger('click'); }
		return false;
	});
	
	$(document).on('click', '#prev_page', function(e) {
		e.preventDefault();
		var page_num = parseInt($('.page_num').val()) - 1;
		if (page_num > 0) { $('.page_num').val(page_num); $('#apply').trigger('click'); }
		return false;
	});
	
	$(document).on('click', '#tambah', function(e) {
		e.preventDefault();
		showPopup1('Tambah', '');
		return false;
	});
	
	$(document).on('click', '#upload', function(e) {
		e.preventDefault();
		showPopupUpload('Upload', '');
		return false;
	});

	$(document).on('click', '#hapus', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data yang akan dihapus.');
		} else if (confirm('Data akan dihapus secara permanent!')) {
			hapusData();
		}
		return false;
	});
	
	$('input:radio[name="status_otorisasi"]').change(function(e){
		e.preventDefault();
		if($(this).val() == '0'){
			stat = 0;
			jQuery('#s_opv1').val('');
			$('#otorisasi').show();
			$('#batal_otorisasi').hide();
			jQuery('#nama_tombol').val('Otorisasi');
			jQuery('#tombol').val('otorisasi');
			loadData1();
		} else if($(this).val() == '1'){
			stat = 1;
			jQuery('#s_opv1').val('');
			$('#otorisasi').hide();
			$('#batal_otorisasi').show();
			jQuery('#nama_tombol').val('Batal Otorisasi');
			jQuery('#tombol').val('batal_otorisasi');
			loadData2();
		} else if($(this).val() == '2'){
			stat = 2;
			jQuery('#s_opv1').val('');
			$('#otorisasi').hide();
			$('#batal_otorisasi').hide();
			loadData3();
		} else if($(this).val() == '3'){
			stat = 3;
			jQuery('#s_opv1').val('');
			$('#otorisasi').hide();
			$('#batal_otorisasi').hide();
			loadData4();
		}
		return false;
	});
	
	$(document).on('click', '#otorisasi', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data yang akan diotorisasi.');
		} else if (confirm('Apakah data ini akan diotorisasi?')) 
		{
			otorisasiData();
		}
		return false;
	});
	
	$(document).on('click', '#batal_otorisasi', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data yang akan dibatalkan otorisasi.');
		} else if (confirm('Apakah data ini akan dibatalkan otorisasinya?')) 
		{
			unotorisasiData();
		}
		return false;
	});
	
	loadData1();
});

function loadData1() {
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#data-load').load(this_base + 'stock_awal/persediaan_awal_load.php', data);
	return false;
}

function loadData2() {
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#data-load').load(this_base + 'siap_jual/informasi_persediaan_load.php', data);
	return false;
}

function loadData3() {
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#data-load').load(this_base + 'reserve/reserve_persediaan_load.php', data);
	return false;
}

function loadData4() {
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#data-load').load(this_base + 'terjual/persediaan_terjual_load.php', data);
	return false;
}

function showPopup(act, id) {
	var url = base_marketing_operasional + 'persediaan_awal/siap_jual/reserve.php?act=' + act + '&id=' + id;
	setPopup('Reserve', url, 850, 500);
	return false;
}

function showPopupUpload(act, id) {
	var url = this_base + 'stock_awal/persediaan_awal_upload_popup.php?act=' + act + '&id=' + id;
	setPopup(act + ' Data Stock', url, 635, 550);
	return false;
}

function showPopup1(act, id) {
	var url = this_base + 'stock_awal/persediaan_awal_popup.php?act=' + act + '&id=' + id;
	setPopup(act + ' Persediaan Awal', url, 850, 550);
	return false;
}

//Button popup & Tutup reserve
function showPopup2(act, id) {
	var url = this_base + 'siap_jual/informasi_persediaan_popup.php?act=' + act + '&id=' + id;
	setPopup('Informasi Persediaan', url, 850, 670);
	return false;
}

//spp reserve
function showPopup7(act, id, nm, alamat, no_va, telepon, no_ktp, npwp, agen, koordinator)
{
	var url = this_base + 'reserve/spp_popup.php' + '?act=' + act + '&id=' + id + '&nm=' + nm + '&alamat=' + alamat + '&no_va=' + no_va + '&telepon=' + telepon+ '&no_ktp=' + no_ktp+ '&no_npwp=' + npwp+ '&agen=' + agen+ '&koordinator=' + koordinator;
	setPopup(act + ' SPP', url, 987, 679);	
	return false;
}

function hapusData() {
	var url		= this_base + 'stock_awal/persediaan_awal_proses.php?act=Hapus',
		data	= jQuery('#form').serializeArray();
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);
		loadData1();
	}, 'json');
	return false;
}

function otorisasiData()
{	
	var url		= this_base + 'stock_awal/persediaan_awal_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'Otorisasi' });
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);	
		loadData1();
	}, 'json');	
	return false;
}

function unotorisasiData()
{
	var url		= this_base + 'stock_awal/persediaan_awal_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'Batal_Otorisasi' });
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);	
		loadData2();		
	}, 'json');
	return false;
}
</script>

<div class="title-page">PERSEDIAAN STOK</div>

<form name="form" id="form" method="post" enctype="multipart/form-data">
<table class="t-control wauto">
<tr>
	<td width="100">Pencarian</td><td width="10">:</td>
	<td>
		<select name="s_opf1" id="s_opf1" class="auto">
			<option value="KODE_BLOK"> KODE BLOK </option>
		</select>
		<input type="text" name="s_opv1" id="s_opv1" class="apply" value="">
	</td>
</tr>
<tr>
	<td>Status Stok</td><td>:</td>
	<td>
		<input type="radio" name="status_otorisasi" id="sbb" class="status" value="0" checked="true"> <label for="sbb">Stok Awal</label>&nbsp;&nbsp;
		<input type="radio" name="status_otorisasi" id="sbs" class="status" value="1"> <label for="sbs">Siap Jual</label>
		<input type="radio" name="status_otorisasi" id="sbs" class="status" value="2"> <label for="sbs">Reserve</label>
		<input type="radio" name="status_otorisasi" id="sbs" class="status" value="3"> <label for="sbs">Terjual</label>
	</td>
</tr>
<tr>
	<td>Jumlah Baris</td><td>:</td>
	<td>
		<input type="text" name="per_page" size="3" id="per_page" class="apply text-center" value="20">
		<input type="button" id="apply" value=" Apply ">
	</td>
</tr>

<tr>
	<td>Total Data</td><td>:</td>
	<td id="total-data"></td>
</tr>
</table>

<input type="hidden" name="tombol" id="tombol" value="otorisasi">
<input type="hidden" name="nama_tombol" id="nama_tombol" value="Otorisasi">

<div id="data-load"></div>
</form>
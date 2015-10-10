<div class="title-page">OTORISASI PERSETUJUAN DENDA</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>
	<td width="100">Pencarian</td><td width="10">:</td>
	<td>
		<select name="field1" id="field1" class="wauto">
			<option value="KODE_BLOK"> BLOK / NOMOR </option>
			<option value="NAMA_PEMBELI"> NAMA PEMBELI </option>>
		</select>
		<input type="text" name="search1" id="search1" class="apply" value="">
	</td>
</tr>
<tr>
	<td>Otorisasi Denda</td><td>:</td>
	<td>
		<input type="radio" name="status_otorisasi" id="sbb" class="status" value="0" checked="true"> <label for="sbb">Belum</label>
		<input type="radio" name="status_otorisasi" id="sbs" class="status" value="1"> <label for="sbs">Sudah</label>
	</td>
</tr>
<tr>
	<td>Jumlah Baris</td><td>:</td>
	<td>
		<input type="text" name="per_page" size="3" id="per_page" class=" apply text-center" value="20">
		<input type="button" name="apply" id="apply" value=" Apply ">
	</td>
</tr>
<tr>
	<td>Total Data</td><td>:</td>
	<td id="total-data"></td>
</tr>
</table>

<script type="text/javascript">
jQuery(function($) {
	
	/* -- FILTER -- */
	$(document).on('keypress', '.apply', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { $('#apply').trigger('click'); return false; }
	});
	
	/* -- BUTTON -- */
	$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		loadData();
		return false;
	});
	
	$(document).on('keyup', '#search1', function(e) {
		e.preventDefault();
		loadData();
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
		showPopup('Tambah', '');
		return false;
	});
	
	$(document).on('click', 'tr.onclick td:not(.notclick)', function(e) {
		e.preventDefault();
		var id = $(this).parent().attr('id');
		showPopup('Ubah', id);
		return false;
	});
	
	$(document).on('click', '#hapus', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data SPP yang akan dihapus.');
		} else if (confirm('Apa data SPP ini akan dihapus?')) {
			hapusData();
		}
		return false;
	});
	
	$('input:radio[name="status_otorisasi"]').change(function(e){
		e.preventDefault();
		if($(this).val() == '0'){
			$('#otorisasi').show();
			$('#batal_otorisasi').hide();
			jQuery('#nama_tombol').val('Otorisasi');
			jQuery('#tombol').val('otorisasi');
		} else if($(this).val() == '1'){
			$('#otorisasi').hide();
			$('#batal_otorisasi').show();
			jQuery('#nama_tombol').val('Batal Otorisasi');
			jQuery('#tombol').val('batal_otorisasi');
		}
		loadData();
		return false;
	});
	
		$(document).on('click', '#otorisasi', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data yang akan diotorisasi.');
		} else if (confirm('Apakah anda yakin akan mengotorisasi data ini ?')) 
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
		} else if (confirm('Apakah anda yakin akan membatalkan otorisasi data ini ?')) 
		{
			unotorisasiData();
		}
		return false;
	});

		
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/transaksi/denda_keterlambatan/otorisasi/otorisasi_load.php', data);	
	return false;
}

function showPopup(act, id)
{
	var url =	base_collection_tunai_transaksi + 'pemulihan_wanprestasi/pemulihan_wanprestasi_popup.php' + '?act=' + act + '&id=' + id;
	setPopup(act + ' SPP', url, 850, 550);	
	return false;
}

function hapusData()
{	
	var url		= base_collection_tunai_transaksi + 'pemulihan_wanprestasi/pemulihan_wanprestasi_proses.php?act=Hapus',
		data	= jQuery('#form').serializeArray();
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);
		loadData();
	}, 'json');
	return false;
}
</script>

<div id="t-detail"></div>
</form>
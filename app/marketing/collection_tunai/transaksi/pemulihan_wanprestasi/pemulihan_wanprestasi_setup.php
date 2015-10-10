<div class="title-page">PEMULIHAN STATUS WANPRESTASI</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>
	<td width="100">Pencarian</td><td width="10">:</td>
	<td>
		<select name="field1" id="field1" class="wauto">
			<option value="KODE_BLOK"> BLOK / NOMOR </option>
			<option value="NAMA_PEMBELI"> NAMA PEMBELI </option>
			<option value="TANGGAL_SPP"> TANGGAL SPP </option>
		</select>
		<input type="text" name="search1" id="search1" class="apply" value="">
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

<input type="hidden" name="tombol" id="tombol" value="otorisasi">
<input type="hidden" name="nama_tombol" id="nama_tombol" value="Otorisasi">

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
	
	$(document).on('click', '#hapus', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data yang akan dihapus.');
		} else if (confirm('Apa data ini akan dihapus?')) {
			hapusData();
		}
		return false;
	});
	
	$(document).on('click', '#pemulihan', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data yang akan dipulihkan.');
		} else if (confirm('Apakah anda yakin akan memulihkan data ini ?')) 
		{
			pemulihanData();
		}
		return false;
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/transaksi/pemulihan_wanprestasi/pemulihan_wanprestasi_load.php', data);	
	return false;
}

function hapusData()
{	

	var url		= base_marketing + 'collection_tunai/transaksi/pemulihan_wanprestasi/pemulihan_wanprestasi_proses.php?act=Hapus',
		data	= jQuery('#form').serializeArray();
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);
		loadData();
	}, 'json');
	return false;
}

function pemulihanData()
{	
	var url		= base_marketing + 'collection_tunai/transaksi/pemulihan_wanprestasi/pemulihan_wanprestasi_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'Pemulihan' });
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);		
	}, 'json');	
	loadData();
	return false;
}

</script>

<div id="t-detail"></div>
</form>
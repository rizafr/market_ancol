<div class="title-page">SPP TIDAK VALID</div>

<form name="form" id="form" method="post">
<table class="t-control">
<tr>
	<td width="100">KODE BLOK/NOMOR</td><td width="10">:</td>
	<td>
		<input type="text" size="20" name="search1" id="search1" class="apply" value="">
	</td>
</tr>
<tr>
	<td>Jumlah Baris</td><td width="10">:</td>
	<td>
		<input type="text" name="per_page" size="3" id="per_page" class=" apply text-center" value="20">
		<input type="button" name="apply" id="apply" value=" Apply ">
	</td>
</tr>
<tr>
	<td>Total Data</td><td width="10">:</td>
	<td id="total-data"></td>
	
</tr>
</table>

<script type="text/javascript">
jQuery(function($) {
	 /*-- FILTER --*/ 
	$(document).on('keypress', '.apply', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { $('#apply').trigger('click'); }
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

	$(document).on('click', 'tr.onclick td:not(.notclick)', function(e) {
		e.preventDefault();
		var id = $(this).parent().attr('id');
		showPopup('Ubah', id);
		return false;
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/lain/spp_tidak_valid/spp_tidak_valid_load.php', data);
	return false;
}

function showPopup(act, id)
{
	var url =	base_marketing + 'collection_tunai/lain/spp_tidak_valid/spp_tidak_valid_popup.php' + '?act=' + act + '&id=' + id;
	setPopup(act + ' SPP', url, 850, 550);
	return false;
}
/*
function hapusData()
{	
	var url		= base_collection_tunai_master + 'cs_hari_libur/cs_hari_libur_proses.php?act=Hapus',
		data	= jQuery('#form').serializeArray();
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);
		loadData();
	}, 'json');
	
	return false;
}*/
</script>

<div id="t-detail"></div>
</form>
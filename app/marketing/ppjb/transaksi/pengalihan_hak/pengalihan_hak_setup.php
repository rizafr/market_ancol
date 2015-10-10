<div class="title-page">PENGALIHAN HAK</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>
	<td width="100">Pencarian</td><td width="10">:</td>
	<td>
		<select name="field1" id="field1" class="wauto">
			<option value="KODE_BLOK"> BLOK / NOMOR </option>
			<option value="NO_VA_PH"> NO VA </option>
			<option value="NO_PPJB_PH"> NO PPJB </option>
			<option value="PIHAK_PERTAMA"> PIHAK PERTAMA </option>
			<option value="PIHAK_KEDUA"> PIHAK KEDUA </option>
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
		showPopup('Pengalihan Hak', '');
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
	jQuery('#t-detail').load(base_marketing + 'ppjb/transaksi/pengalihan_hak/pengalihan_hak_load.php', data);
	return false;
}

function showPopup(act, id)
{
	var url =	base_marketing + 'ppjb/transaksi/pengalihan_hak/pengalihan_hak_popup.php' + '?act=' + act + '&id=' + id;	
	if (act == 'Ubah') { setPopup(act + ' Pengalihan Hak', url, 1000, 540); } else { setPopup('Pengalihan Hak', url, 1000, 540); }
	return false;
}
</script>

<div id="t-detail"></div>
</form>
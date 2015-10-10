<script type="text/javascript">
var this_base = base_marketing + 'master/lembaga_keuangan/';

jQuery(function($) {
	
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
	
	$(document).on('keyup', '#s_opv1', function(e) {
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
		var nm = $(this).parent().attr('nm');
		showPopup('Ubah', id, nm);
		return false;
	});

	$(document).on('click', '#hapus', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data yang akan dihapus.');
		} else if (confirm('Apa anda yakin akan menghapus data ini?')) {
			hapusData();
		}
		return false;
	});

	loadData();
});

function loadData() {
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#data-load').load(this_base + 'lembaga_keuangan_load.php', data);
	return false;
}

function showPopup(act, id, nm) {
	var url = this_base + 'lembaga_keuangan_popup.php?act=' + act + '&id=' + id + '&nm=' + nm;
	setPopup(act + ' Lembaga Keuangan', url, 500, 210);
	return false;
}

function hapusData()
{	
	var url		= base_marketing + 'master/lembaga_keuangan/lembaga_keuangan_proses.php?act=Hapus',
		data	= jQuery('#form').serializeArray();
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);
	}, 'json');
	loadData();
	return false;
}
</script>

<div class="title-page">DAFTAR LEMBAGA KEUANGAN</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>
	<td width="100">Pencarian</td><td width="10">:</td>
	<td>
		<select name="s_opf1" id="s_opf1" class="auto">
			<option value="KODE_BANK"> KODE </option>
			<option value="NAMA_BANK"> NAMA BANK </option>
		</select>
		<input type="text" name="s_opv1" id="s_opv1" class="apply" value="">
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

<div id="data-load"></div>
</form>
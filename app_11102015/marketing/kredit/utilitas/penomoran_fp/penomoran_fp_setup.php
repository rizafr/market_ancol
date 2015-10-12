<script type="text/javascript">
var this_base = base_marketing + 'kredit/utilitas/penomoran_fp/';

jQuery(function($) {
	
	/* -- FILTER -- */
	$(document).on('keypress', '.apply', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { $('#apply').trigger('click'); return false; }
	});
	
	/* -- BUTTON -- */
	$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		if (jQuery('#periode_awal').val() == '') {
			alert('Masukkan periode!');
			jQuery('#periode_awal').focus();
			return false;
		}
		else if (jQuery('#periode_akhir').val() == '') {
			alert('Masukkan periode!');
			jQuery('#periode_akhir').focus();
			return false;
		}
		loadData();
		return false;
	});
	
	$(document).on('click', '#tambah', function(e) {
		e.preventDefault();
		if (confirm('Apa anda yakin akan generate nomor faktur pajak?'))
		{	
			var url		= this_base + 'penomoran_fp_proses.php',
			data		= $('#form').serialize();
			
			$.post(url, data, function(result) {
				alert(result.msg);
				if (result.error == false) {
					parent.loadData();
				}
			}, 'json');
			return false;
		}
		
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
		showPopup('Edit', id);
		return false;
	});
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'kredit/utilitas/penomoran_fp/penomoran_fp_load.php', data);	
	return false;
}

function showPopup(act, id)
{
	var url =	base_marketing + 'kredit/utilitas/penomoran_fp/penomoran_fp_popup.php' + '?act=' + act + '&id=' + id;	
	setPopup(act + ' Faktur Pajak', url, 500, 230);	
	return false;
}
</script>

<div class="title-page">PENOMORAN FAKTUR PAJAK</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>	
	<td width="100">Periode</td><td width="10">:</td>
	<td><input type="text" name="periode_awal" id="periode_awal" class="apply dd-mm-yyyy" size="15" value=""> s/d
	<input type="text" name="periode_akhir" id="periode_akhir" class="apply dd-mm-yyyy" size="15" value=""></td>
</tr>
<tr>
	<td>Jumlah Baris</td><td>:</td>
	<td>
		<input type="text" name="per_page" size="3" id="per_page" class=" apply text-center" value="20">
		<input type="button" name="apply" id="apply" value=" Apply ">
		<input type="hidden" name="act" id="act" value="Tambah">
	</td>
</tr>
<tr>
	<td>Total Data</td><td>:</td>
	<td id="total-data"></td>
</tr>
</table>

<div id="t-detail"></div>
</form>
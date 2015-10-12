<div class="title-page">LAPORAN SPP LUNAS TAHUNAN</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>	
	<td width="100">Tahun SPP</td><td width="10">:</td>
	<td><input type="text" name="tahun" id="tahun" class="apply yyyy" size="15" value=""></td>
	<td><input type="button" name="apply" id="apply" value=" Apply "></td>
</tr>
<tr>
	<td>Bulan</td><td>:</td>
	<td><input type="text" disabled="true" name="nama_bulan" id="nama_bulan" size="15" value="Januari"></td>
</tr>
<tr>
	<td>Total Data</td><td>:</td>
	<td id="total-data"></td>
</tr>
</table>

<script type="text/javascript">
jQuery(function($) {
	/* -- FILTER -- */
	$('#search1').hide();
	var jenis = 0;
	
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
	
	$(document).on('click', '#excel', function(e) {
		e.preventDefault();
		location.href = base_marketing + 'collection_tunai/laporan/spp_lunas_tahunan/excel_spp_lunas_tahunan.php?' + $('#form').serialize();
		return false;
	});
	
	$(document).on('click', '#print', function(e) {
		e.preventDefault();
		window.open(base_marketing + 'collection_tunai/laporan/spp_lunas_tahunan/print_spp_lunas_tahunan.php?' + $('#form').serialize());
		return false;
	});
	
	$(document).on('click', '#next_page', function(e) {
		e.preventDefault();
		var total_page = parseInt($('#total_page').val()),
			page_num = parseInt($('.page_num').val()) + 1;

		if (page_num <= total_page)
		{
			$('.page_num').val(page_num);
			$('#apply').trigger('click');
		}
	});
	
	$(document).on('click', '#prev_page', function(e) {
		e.preventDefault();
		var page_num = parseInt($('.page_num').val()) - 1;
		
		if (page_num > 0)
		{
			$('.page_num').val(page_num);
			$('#apply').trigger('click');
		}
	});
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/laporan/spp_lunas_tahunan/spp_lunas_tahunan_load.php', data);	
	return false;
}

</script>

<div id="t-detail"></div>
</form>
<div class="title-page">LAPORAN CASH IN</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>
<td width="100">Kriteria</td><td width="10">:</td>
	<td>
		<select name="field1" id="field1" class="wauto">
			<option value="all"> Keseluruhan </option>
			<option value="kpa24x"> KPA 24X </option>
			<option value="kpa36x"> KPA 36X </option>
			<option value="cb36x"> CB 36X </option>
			<option value="cb48x"> CB 48X </option>
		</select>
		<input type="text" name="search1" id="search1" class="apply" value="">
	</td>
</tr>
<tr>	
	<td width="100">Bulan Tagihan</td><td width="10">:</td>
	<td><input type="text" name="bulan_awal" id="bulan_awal" class="apply mm-yyyy" size="15" value=""> s/d
		<input type="text" name="bulan_akhir" id="bulan_akhir" class="apply mm-yyyy" size="15" value="">
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
	$('#search1').hide();
	var jenis = 0;
	
	$(document).on('keypress', '.apply', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { $('#apply').trigger('click'); return false; }
	});
	
	/* -- BUTTON -- */
	$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		if (jQuery('#bulan_awal').val() == '') {
			alert('Masukkan bulan kriteria !');
			jQuery('#bulan_awal').focus();
		}
		else if (jQuery('#bulan_akhir').val() == '') {
			alert('Masukkan bulan kriteria !');
			jQuery('#bulan_akhir').focus();
		}
		else {
			if(jenis == '0'){
				loadData();
			}
			else if (jenis == '1'){
				loadDataRekap();
			}
		}	
		return false;
	});
	
	$(document).on('click', '#field1', function(e) {
		e.preventDefault();
		if($(this).val() == 'kode_blok'){
			$('#search1').show();
			$('#bulan_awal').prop('disabled', true);
			$('#bulan_akhir').prop('disabled', true);
		} else {
			$('#search1').hide();
			$('#bulan_awal').prop('disabled', false);
			$('#bulan_akhir').prop('disabled', false);
		}
		return false;
	});
	
	$(document).on('click', '#excel', function(e) {
		e.preventDefault();
		location.href = base_marketing + 'collection_tunai/laporan/cash_in/excel_cash_in.php?' + $('#form').serialize();
		return false;
	});
	
	$(document).on('click', '#print', function(e) {
		e.preventDefault();
		var url = base_marketing + 'collection_tunai/laporan/cash_in/print_cash_in.php?' + $('#form').serialize();
		open_print(url)
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
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/laporan/cash_in/cash_in_load.php', data);	
	return false;
}

</script>

<div id="t-detail"></div>
</form>
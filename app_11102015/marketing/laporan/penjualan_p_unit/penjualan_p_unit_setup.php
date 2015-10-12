<div class="title-page">LAPORAN PENJUALAN</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>	
	<td>Periode</td><td>:</td>
	<td><input type="text" name="periode_awal" id="periode_awal" class="apply dd-mm-yyyy" size="15" value=""> s/d
	<input type="text" name="periode_akhir" id="periode_akhir" class="apply dd-mm-yyyy" size="15" value=""></td>
</tr>
<tr>
	<td>Lokasi</td><td>:</td>
	<td>
		<select id="lokasi" name="lokasi">
			<option value="">-- SEMUA --</option>
			<?php
				$query = "SELECT KODE_LOKASI, LOKASI from LOKASI";
				$data  = $conn->Execute($query);
				while(!$data->EOF){
					echo "<option value = '".$data->fields['KODE_LOKASI']."'>".$data->fields['LOKASI']."</option>";
					$data->movenext();
				}
			?>
		</select>
	</td>
</tr>
<tr>
	<td>Jenis Unit</td><td>:</td>
	<td>
		<select id="jenis_unit" name="jenis_unit">
			<option value="">-- SEMUA --</option>
			<?php
				$query = "SELECT KODE_UNIT, JENIS_UNIT from JENIS_UNIT";
				$data  = $conn->Execute($query);
				while(!$data->EOF){
					echo "<option value = '".$data->fields['KODE_UNIT']."'>".$data->fields['JENIS_UNIT']."</option>";
					$data->movenext();
				}
			?>
		</select>
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
//var this_base = base_marketing + 'laporan/penjualan_p_unit/';
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
	
	$(document).on('click', '#excel', function(e) {
		e.preventDefault();
		location.href = base_marketing + 'laporan/penjualan_p_unit/penjualan_p_unit_xls.php?' + $('#form').serialize();
		return false;
	});
	
	$(document).on('click', '#print', function(e) {
		e.preventDefault();
		var url = base_marketing + 'laporan/penjualan_p_unit/penjualan_p_unit_print.php?' + $('#form').serialize();
		open_print(url);
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
	jQuery('#t-data').load(base_marketing + 'laporan/penjualan_p_unit/penjualan_p_unit_load.php', data);	
	return false;
}
</script>

<div id="t-data"></div>
</form>
<script type="text/javascript">
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
		
		
		
		$(document).on('click', '#excel', function(e) {
			e.preventDefault();
			location.href = base_marketing + 'laporan/laporan_persediaan_stok/laporan_persediaan_stok_xls.php?' + $('#form').serialize();
			return false;
		});
		
		$(document).on('click', '#print', function(e) {
			e.preventDefault();
			var url = base_marketing + 'laporan/laporan_persediaan_stok/laporan_persediaan_stok_print.php?' + $('#form').serialize();
			open_print(url)
			return false;
		});
		loadData();

	});

function loadData() {
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#data-load').load( base_marketing + 'laporan/laporan_persediaan_stok/laporan_persediaan_stok_load.php', data);
	return false;
}
</script>

<div class="title-page">LAPORAN PERSEDIAAN STOK</div>

<form name="form" id="form" method="post">
	<table class="t-control wauto">
		<tr>
			<td width="100">Pencarian</td><td width="10">:</td>
			<td>
				<select name="s_opf1" id="s_opf1" class="auto">
					<option value="s.KODE_BLOK"> KODE BLOK </option>
				</select>
				<input type="text" name="s_opv1" id="s_opv1" class="apply" value="">
			</td>
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
			<td>Status</td><td>:</td>
			<td>
				<input	type = "radio" name="status" id ="status" value = "" checked>SEMUA
				<input	type = "radio" name="status" id ="status" value = "1">STOK AWAL
				<input	type = "radio" name="status" id ="status" value = "2">SIAP JUAL
				<input	type = "radio" name="status" id ="status" value = "3">RESERVE
				<input	type = "radio" name="status" id ="status" value = "4">TERJUAL
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
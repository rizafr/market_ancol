<div class="title-page">VERIFIKASI KWITANSI (DIV KEUANGAN)</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>
	<td width="100">Pilih / Pencarian</td><td width="10">:</td>
	<td>
		<select name="field1" id="field1" class="wauto">
			<option value="1"> KODE BLOK</option>
			<option value="2"> LAIN-LAIN </option>
		</select>
		<input type="text" name="search1" id="search1" class="apply" value="">
	</td>
</tr>

<tr>
		<td>Status Verifikasi</td><td>:</td>
		<td>
			<input type="radio" name="ver_keuangan" id="ver_keuangan" class="ver_keuangan" value="1" checked="true"><label for="ver_keuangan">Sudah</label>
			<input type="radio" name="ver_keuangan" id="ver_keuangan" class="ver_keuangan" value="0"> <label for="ver_keuangan">Belum</label>
		</td>
	</tr>	

<tr>	
	<td>Periode</td><td>:</td>
	<td><input type="text" name="periode_awal" id="periode_awal" class="apply dd-mm-yyyy" size="15" value=""> s/d
	<input type="text" name="periode_akhir" id="periode_akhir" class="apply dd-mm-yyyy" size="15" value=""></td>
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
	
	$(document).on('keyup', '#search1', function(e) {
		e.preventDefault();
		loadData();
		return false;
	});
	
	$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		if (jQuery('#periode_awal').val() == '') {
			alert('Masukkan periode awal!');
			jQuery('#periode_awal').focus();
			return false;
		}
		else if (jQuery('#periode_akhir').val() == '') {
			alert('Masukkan periode akhir!');
			jQuery('#periode_akhir').focus();
			return false;
		}
		loadData();
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
	
	$(document).on('click', '#verifikasi', function(e) {
		e.preventDefault();
		if (confirm('Apa anda yakin akan memverifikasi data ini?'))
		{
			verData();
		}
	});
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/transaksi/ver_kwitansi_keu/ver_kwitansi_keu_load.php', data);	
	return false;
}

function verData()
{
	var checked = jQuery(".cb_data:checked").length;
	if (checked < 1)
	{
		alert('Pilih data yang akan diverifikasi.');
		return false;
	}
	
	var url		= base_marketing + 'collection_tunai/transaksi/ver_kwitansi_keu/ver_kwitansi_keu_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'verifikasi' });
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
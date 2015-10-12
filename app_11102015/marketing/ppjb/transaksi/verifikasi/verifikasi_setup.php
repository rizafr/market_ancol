<div class="title-page">VERIFIKASI PPJB</div>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>
	<td width="100">Pencarian</td><td width="10">:</td>
	<td>
		<select name="field1" id="field1" class="wauto">
			<option value="z.KODE_BLOK"> BLOK / NOMOR </option>
			<option value="z.NOMOR"> NOMOR PPJB </option>
			<option value="z.NAMA_PEMBELI"> NAMA PEMBELI </option>
		</select>
		<input type="text" name="search1" id="search1" class="apply" value="">
	</td>
</tr>
<tr>
	<td>Kriteria Verifikasi</td><td>:</td>
	<td>
		<input type="radio" name="status_verifikasi" id="sbb" class="status" value="0" checked="true"> <label for="sbb">Belum</label>
		<input type="radio" name="status_verifikasi" id="sbs" class="status" value="1"> <label for="sbs">Sudah</label>
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

<input type="hidden" name="tombol" id="tombol" value="verifikasi">
<input type="hidden" name="nama_tombol" id="nama_tombol" value="Verifikasi">

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
	
	$(document).on('keyup', '#search1', function(e) {
		e.preventDefault();
		loadData();
		return false;
	});
	
	$('input:radio[name="status_verifikasi"]').change(function(e){
		e.preventDefault();
		if($(this).val() == '0'){
			$('#verifikasi').show();
			$('#batal_verifikasi').hide();
			jQuery('#nama_tombol').val('Verifikasi');
			jQuery('#tombol').val('verifikasi');
		} else if($(this).val() == '1'){
			$('#verifikasi').hide();
			$('#batal_verifikasi').show();
			jQuery('#nama_tombol').val('Batal Verifikasi');
			jQuery('#tombol').val('batal_verifikasi');
		}
		loadData();
		return false;
	}); 
	
	$(document).on('click', '#verifikasi', function(e) {
		e.preventDefault();
		if (confirm('Apa anda yakin akan memverifikasi data ini?'))
		{
			verData();
		}
	});
	
	$(document).on('click', '#batal_verifikasi', function(e) {
		e.preventDefault();
		if (confirm('Apa anda yakin akan membatalkan verifikasi data ini?'))
		{
			unVerData();
		}
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'ppjb/transaksi/verifikasi/verifikasi_load.php', data);
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
	
	var url		= base_marketing + 'ppjb/transaksi/verifikasi/verifikasi_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'verifikasi' });
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);		
	}, 'json');	
	loadData();
	return false;
}

function unVerData()
{
	var checked = jQuery(".cb_data:checked").length;
	if (checked < 1)
	{
		alert('Pilih data yang akan dibatalkan verifikasi.');
		return false;
	}
	
	var url		= base_marketing + 'ppjb/transaksi/verifikasi/verifikasi_proses.php',
		data	= jQuery('#form').serializeArray();
	data.push({ name: 'act', value: 'batal_verifikasi' });
	
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
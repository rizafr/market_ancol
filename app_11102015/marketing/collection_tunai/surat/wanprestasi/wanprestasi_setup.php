<form name="form" id="form" method="post">
<table class="t-control2">
    <tr>
      <td style="font-size:20px;text-align:center;font-weight:bold" >Daftar Peringatan Wanprestasi</td>
    </tr>
</table>	
<table class="t-control">
	<tr>
      <td>Tanggal</td><td>:</td>
	  <td><?php echo fm_date(date("Y-m-d"));  ?></td>
    </tr>
    <tr>
		<td width="100">Jumlah Baris</td><td width="10">:</td>
		<td>
			<input type="text" name="per_page" size="3" id="per_page" class=" apply text-center" value="20">
			<input type="button" name="apply" id="apply" value=" Apply ">
			<input type="hidden" name="act" id="act" value="Surat">
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
		if (code == 13) { $('#apply').trigger('click'); }
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

	$(document).on('click', '#surat', function(e) {
		e.preventDefault();
		var checked = $(".cb_data:checked").length;
		if (checked < 1) {
			alert('Pilih data yang akan dicetak surat.');
		} else if (confirm('Apa anda yakin akan mencetak surat untuk data ini?')) {
			e.preventDefault();
			location.href = base_marketing + 'collection_tunai/surat/wanprestasi/surat_wanprestasi.php?' + $('#form').serialize();	
			//cetakSurat();
		}
		return false;
	});
	
	$(document).on('click', '#print', function(e) {
		e.preventDefault();
		window.open(base_marketing + 'collection_tunai/surat/wanprestasi/print_wanprestasi.php?' + $('#form').serialize());
		return false;
	});
	
	loadData();
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/surat/wanprestasi/wanprestasi_load.php', data);
	return false;
}

function cetakSurat()
{	
	var url		='pdf/rptprint-surat-jatuh-tempo.php';
		data	= jQuery('#form').serializeArray();
	
	jQuery.post(url, data, function(result) {
		var list_id = result.act.join(', #');
		alert(result.msg);
		loadData();
	}, 'json');
	
	return false;
}
</script>

<div id="t-detail"></div>
</form>
<form name="form" id="form" method="post">
<table class="t-control2">
    <tr>
      <td style="font-size:20px;text-align:center;font-weight:bold" >CREATE TAGIHAN VIRTUAL ACCOUNT</td>
    </tr>
</table>	
<table class="t-control">
	<tr>	
		<td width="100">Bulan Tagihan</td><td width="10">:</td>
		<td><input type="text" name="bulan" id="bulan" class="apply mm-yyyy" size="15" value="">
			<input type="button" name="apply" id="apply" value=" Apply ">
		</td>
	</tr>
	<tr>
	<td>Status Distribusi</td><td>:</td>
		<td>
			<input type="radio" name="status_distribusi" id="sbb" class="status" value="1" checked="true"> <label for="sbb">Sudah</label>
			<input type="radio" name="status_distribusi" id="sbs" class="status" value="0"> <label for="sbs">Belum</label>
		</td>
	</tr>
	<tr>
		<td>Status Nomor VA</td><td>:</td>
		<td>
			<input type="radio" name="status_va" id="va_a" class="status_va" value="1" checked="true"><label for="va_a">Sudah</label>
			<input type="radio" name="status_va" id="va_0" class="status_va" value="0"> <label for="va_0">Belum</label>
		</td>
	</tr>	
    <tr>
		<td width="100">Jumlah Baris</td><td width="10">:</td>
		<td>
			<input type="text" name="per_page" size="3" id="per_page" class=" apply text-center" value="20">
			<input type="hidden" name="act" id="act" value="Surat">
		</td>
	</tr>
	<tr>
		<td>Total Data</td><td>:</td>
		<td id="total-data"></td>
	</tr>
	<tr>
	<td width="100">Create Tagihan</td><td width="10">:</td>
		<td>
			<select name="pilih" id="pilih" class="wauto">
				<option value="bca"> Bank BCA </option>
				<option value="mandiri"> Bank Mandiri </option>
			</select>
			<input type="button" id="download" value=" Create ">		
		</td>
	</tr>
	
</table>

<script type="text/javascript">
jQuery(function($) {
	/* -- FILTER -- */
	var distribusi = 1;
	var bank = 'bca';
	
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
	
	$(document).on('click', '#pilih', function(e) {
		e.preventDefault();
		if($(this).val() == 'bca'){
			bank = 'bca';
		} else {
			bank = 'mandiri';
		}
		return false;
	});
	
	$(document).on('click', 'tr.onclick td:not(.notclick)', function(e) {
		var id = $(this).parent().attr('id');
		var bulan = jQuery('#bulan').val();
		showPopup('Detail', id, bulan);
		return false;
		
	});
	
	

	$(document).on('click', '#download', function(e) {
		e.preventDefault();
		if(distribusi == 0){
			alert('Maaf datfar tagihan ini belum didistribusi');
		}
		else{
			if(bank == 'bca'){
			location.href = base_marketing + 'collection_tunai/transaksi/download_tagihan/download_tagihan_bca_excel.php?' + $('#form').serialize();
			}
			else{
			location.href = base_marketing + 'collection_tunai/transaksi/download_tagihan/download_tagihan_mandiri_excel.php?' + $('#form').serialize();
			}
		}
		
		return false;
	});
	
	$('input:radio[name="status_distribusi"]').change(function(e){
		e.preventDefault();
		if($(this).val() == 0){
			distribusi = 0;
		}
		else if($(this).val() == 1){
			distribusi = 1;
		}
		loadData();
		return false;
	});
	
});

function loadData()
{
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#t-detail').load(base_marketing + 'collection_tunai/transaksi/download_tagihan/download_tagihan_load.php', data);
	return false;
}

function showPopup(act, id, bulan)
{
	var url = base_marketing + 'collection_tunai/transaksi/download_tagihan/download_tagihan_popup.php' + '?act=' + act + '&id=' + id + '&bulan=' + bulan 	
	setPopup(act + ' Tagihan Lain-lain', url, 600, 500);
	return false;
}

</script>

<div id="t-detail"></div>
</form>
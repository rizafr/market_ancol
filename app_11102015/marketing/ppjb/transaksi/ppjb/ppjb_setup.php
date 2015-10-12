<div class="title-page">PERJANJIAN PENGIKATAN JUAL BELI (PPJB)</div>

<form name="form" id="form" method="post">
	<table class="t-control wauto">
		<tr>
			<td width="100">Pencarian</td><td width="10">:</td>
			<td>
				<select name="field1" id="field1" class="wauto">
					<option value="a.KODE_BLOK"> BLOK / NOMOR </option>
					<option value="a.NAMA_PEMBELI"> NAMA PEMBELI </option>
				</select>
				<input type="text" name="search1" id="search1" class="apply" value="">
			</td>
		</tr>
		<tr>
			<td>Status PPJB</td><td>:</td>
			<td>
				<input type="radio" name="status_ppjb" id="all" class="status" value="2" checked="true"> <label for="sbs">All</label>
				<input type="radio" name="status_ppjb" id="sudah" class="status" value="0" > <label for="sbb">Sudah</label>&nbsp;&nbsp;
				<input type="radio" name="status_ppjb" id="belum" class="status" value="1"> <label for="sbs">Belum</label>
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
			
			$(document).on('keyup', '#search1', function(e) {
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
			
			
			
			$('input:radio[name="status_ppjb"]').change(function(e){
				e.preventDefault();
				
				if($(this).val() == '0'){		
					$('.sudah').show();
					$('.belum').hide();
				} else if($(this).val() == '1'){
					$('.sudah').hide();
					$('.belum').show();
				} else if($(this).val() == '2'){
					$('.sudah').show();
					$('.belum').show();
				}
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
				showPopup('Ubah', id);
				return false;
			});
			
			loadData();
		});
		
		function loadData()
		{
			if (popup) { popup.close(); }
			var data = jQuery('#form').serialize();
			jQuery('#t-detail').load(base_marketing + 'ppjb/transaksi/ppjb/ppjb_load.php', data);
			
			cekStatus();
			return false;
		}
		
		function cekStatus()
		{			
			document.getElementById("all").checked = true;
			// if (document.getElementById('sudah').checked) {
			// alert("Silakan pilih kembali blok yang akan di ppjb");
				// jQuery('.sudah').show();
				// jQuery('.belum').hide();
			// }
			// if (document.getElementById('belum').checked) {
			// alert("Silakan pilih kembali blok yang akan di ppjb");
				// jQuery('.sudah').hide();
				// jQuery('.belum').show();
			// }
			// if (document.getElementById('all').checked) {
			// alert("Silakan pilih kembali blok yang akan di ppjb");
				// jQuery('.sudah').show();
				// jQuery('.belum').show();
			// }
		}
		
		function showPopup(act, id)
		{
			var url =	base_marketing + 'ppjb/transaksi/ppjb/ppjb_popup.php' + '?act=' + act + '&id=' + id;	
			setPopup('Perjanjian Pengikatan Jual Beli (PPJB)', url, 1000, 580);	
			return false;
		}
		
		
	</script>
	
	<div id="t-detail"></div>
</form>
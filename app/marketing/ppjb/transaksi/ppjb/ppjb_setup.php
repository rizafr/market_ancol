<!-- <div class="title-page">PERJANJIAN AWAL IKATAN JUAL BELI / PERJANJIAN PENGIKATAN JUAL BELI</div> -->
<div class="title-page">PAIJB / PPJB</div>

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
			<td>Status PAIJB</td><td>:</td>
			<td>
				<input type="radio" name="status_paijb" id="all_paijb" class="status" value="0" checked="true"> <label for="sbs">All</label>
				<input type="radio" name="status_paijb" id="sudah_paijb" class="status" value="1" > <label for="sbb">Sudah</label>&nbsp;&nbsp;
				<input type="radio" name="status_paijb" id="belum_paijb" class="status" value="2"> <label for="sbs">Belum</label>
			</td>
		</tr>
		<tr>
			<td>Status PPJB</td><td>:</td>
			<td>
				<input type="radio" name="status_ppjb" id="all_ppjb" class="status" value="0" checked="true"> <label for="sbs">All</label>
				<input type="radio" name="status_ppjb" id="sudah_ppjb" class="status" value="1" > <label for="sbb">Sudah</label>&nbsp;&nbsp;
				<input type="radio" name="status_ppjb" id="belum_ppjb" class="status" value="2"> <label for="sbs">Belum</label>
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
				
				if($(this).val() == '0'){	//all	
					$('.sudah_ppjb').show();
					$('.belum_ppjb').show();
				} else if($(this).val() == '1'){ //sudah
					$('.sudah_ppjb').show();
					$('.belum_ppjb').hide();
				} else if($(this).val() == '2'){ //belum
					$('.sudah_ppjb').hide();
					$('.belum_ppjb').show();
				}
				return false;
			});

			$('input:radio[name="status_paijb"]').change(function(e){
				e.preventDefault();
				
				if($(this).val() == '0'){	//all	
					$('.sudah_paijb').show();
					$('.belum_paijb').show();
				} else if($(this).val() == '1'){ //sudah
					$('.sudah_paijb').show();
					$('.belum_paijb').hide();
				} else if($(this).val() == '2'){ //belum
					$('.sudah_paijb').hide();
					$('.belum_paijb').show();
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
			setPopup('Perjanjian Awal Ikatan Jual Beli (PAIJB) / Perjanjian Pengikatan Jual Beli (PPJB)', url, 1000, 580);	
			return false;
		}
		
		
	</script>
	
	<div id="t-detail"></div>
</form>
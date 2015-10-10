<div class="title-page">STOK SIAP JUAL</div>
<?php
	$kode_sk_tanah	= (isset($_REQUEST['kode_sk_tanah'])) ? clean($_REQUEST['kode_sk_tanah']) : '';
	$kode_sk_bangunan = (isset($_REQUEST['kode_sk_bangunan'])) ? clean($_REQUEST['kode_sk_bangunan']) : '';

?>

<form name="form" id="form" method="post">
<table class="t-control wauto">
<tr>
	<td width="100">Pencarian</td><td width="10">:</td>
	<td>
		<select name="s_opf1" id="s_opf1" class="auto">
			<option value="s.KODE_BLOK"> KODE BLOK </option>
			<option value="s.KODE_SK_TANAH"> SK TANAH </option>
			<option value="s.KODE_SK_BANGUNAN"> SK BANGUNAN </option>
		</select>
		<input type="text" name="s_opv1" id="s_opv1" class="apply" value="">
	</td>
</tr>
<tr>
	<td width="100" name="label_tanah" id="label_tanah">SK Tanah</td><td name="t1" id="t1" width="10">:</td>
	<td>
		<input type="text" name="kode_sk_tanah" id="kode_sk_tanah" size="10" value="<?php echo $kode_sk_tanah; ?>">
		<button onclick="return get_kode_sk_tanah()" name="btn_sk_tanah" id="btn_sk_tanah"> > </button>
	</td>
</tr>
<tr>
	<td width="100" name="label_bangunan" id="label_bangunan">SK Bangunan</td><td name="b1" id="b1" width="10">:</td>
	<td>
		<input type="text" name="kode_sk_bangunan" id="kode_sk_bangunan" size="10" value="<?php echo $kode_sk_bangunan; ?>">
		<button onclick="return get_kode_sk_bangunan()"  name="btn_sk_bangunan" id="btn_sk_bangunan"> > </button>
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

<table>
<tr></tr>
<tr>
	<td>
		<input type="button" name="update_all" id="update_all" value=" Update SK Untuk Data Tersortir ">
	</td>
</tr>
</table>

<input type="hidden" name="tombol" id="tombol" value="otorisasi">
<input type="hidden" name="nama_tombol" id="nama_tombol" value="Otorisasi">

<script type="text/javascript">
var this_base = base_marketing + 'transaksi/edit_stok_penjualan/';
var get_base = base_marketing + 'operasional/get/';

jQuery(function($) {	


	$('#label_bangunan').hide();
	$('#kode_sk_bangunan').hide();
	$('#btn_sk_bangunan').hide();
	$('#b1').hide();
	$('#label_tanah').hide();
	$('#kode_sk_tanah').hide();
	$('#btn_sk_tanah').hide();
	$('#t1').hide();
	$('#update_all').hide();

	$(document).on('keypress', '.apply', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { $('#apply').trigger('click'); }
	});
	
	/* -- BUTTON -- */
	$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		if($('#s_opf1').val() == 's.KODE_BLOK'){
			loadData();
		}
		else if($('#s_opf1').val() == 's.KODE_SK_TANAH'){
			if($('#kode_sk_tanah').val() == ''){
				alert('Pilih SK Tanah Yang Akan Ditampilkan');
			}
			else{
			loadData();
			}
		}
		else if($('#s_opf1').val() == 's.KODE_SK_BANGUNAN'){
			if($('#kode_sk_bangunan').val() == ''){
				alert('Pilih SK Bangunan Yang Akan Ditampilkan');
			}
			else{
			loadData();
			}
		}
		
		return false;
	});
	
	$(document).on('keyup', '#s_opv1', function(e) {
		e.preventDefault();
		loadData();
		return false;
	});
	
	$(document).on('click', '#s_opf1', function(e) {
		e.preventDefault();
		if($(this).val() == 's.KODE_BLOK'){
			$('#label_bangunan').hide();
			$('#kode_sk_bangunan').hide();
			$('#btn_sk_bangunan').hide();
			$('#b1').hide();
			$('#label_tanah').hide();
			$('#kode_sk_tanah').hide();
			$('#btn_sk_tanah').hide();
			$('#t1').hide();
			$('#update_all').hide();
			$('#s_opv1').show();
		} else if($(this).val() == 's.KODE_SK_TANAH') {
			$('#label_bangunan').hide();
			$('#kode_sk_bangunan').hide();
			$('#btn_sk_bangunan').hide();
			$('#b1').hide();
			$('#label_tanah').show();
			$('#kode_sk_tanah').show();
			$('#btn_sk_tanah').show();
			$('#t1').show();
			$('#update_all').show();
			$('#s_opv1').hide();
		} else if($(this).val() == 's.KODE_SK_BANGUNAN') {
			$('#label_bangunan').show();
			$('#kode_sk_bangunan').show();
			$('#btn_sk_bangunan').show();
			$('#b1').show();
			$('#label_tanah').hide();
			$('#kode_sk_tanah').hide();
			$('#btn_sk_tanah').hide();
			$('#t1').hide();
			$('#update_all').show();
			$('#s_opv1').hide();
		}
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
	
	$(document).on('click', '#update_all', function(e) {
		e.preventDefault();
		if($('#s_opf1').val() == 's.KODE_SK_TANAH'){
			if($('#kode_sk_tanah').val() == ''){
				alert('Pilih SK Tanah Terlebih Dahulu');
			}
			else{
				var id;
				var jenis;
				if($('#s_opf1').val() == 's.KODE_SK_TANAH') {
					id = $('#kode_sk_tanah').val();
					jenis = 'Tanah';
				}
				else{
					id = $('#kode_sk_bangunan').val();
					jenis = 'Bangunan';
				}
				
				var act = 'Ubah_SK';
				var url = this_base + 'edit_sk_popup.php?act=' + act + '&id=' + id + '&jenis=' + jenis;
				setPopup('Edit SK Stok Belum Terjual', url, 600, 400);
				
			}
		}
		else if($('#s_opf1').val() == 's.KODE_SK_BANGUNAN'){
			if($('#kode_sk_bangunan').val() == ''){
				alert('Pilih SK Bangunan Terlebih Dahulu');
			}
			else{
				var id;
				var jenis;
				if($('#s_opf1').val() == 's.KODE_SK_TANAH') {
					id = $('#kode_sk_tanah').val();
					jenis = 'Tanah';
				}
				else{
					id = $('#kode_sk_bangunan').val();
					jenis = 'Bangunan';
				}
				
				var act = 'Ubah_SK';
				var url = this_base + 'edit_sk_popup.php?act=' + act + '&id=' + id + '&jenis=' + jenis;
				setPopup('Edit SK Stok Belum Terjual', url, 600, 400);
				
			}
		}
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

function loadData() {
	if (popup) { popup.close(); }
	var data = jQuery('#form').serialize();
	jQuery('#data-load').load(this_base + 'edit_stok_penjualan_load.php', data);
	return false;
}

function get_kode_sk_tanah() {
	var url = get_base + 'kode_sk_tanah.php'; 
	setPopup('Daftar SK Tanah', url, 500, winHeight-100); 
	return false; 
}

function get_kode_sk_bangunan() {
	var url = get_base + 'kode_sk_bangunan.php'; 
	setPopup('Daftar SK Bangunan', url, 600, winHeight-100); 
	return false; 
}

function showPopup(act, id) {
	var url = this_base + 'edit_stok_penjualan_popup.php?act=' + act + '&id=' + id;
	setPopup(act + ' Persediaan Siap Jual', url, 835, 450);
	return false;
}
</script>



<div id="data-load"></div>
</form>
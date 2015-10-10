function qalert(q) { jQuery('html').prepend(q); }

var path				= window.location.href.split('/'),
	base_url			= path[0] + '//' + path[2] + '/' + path[3] + '/',
	base_app			= base_url + 'app/',
	
	base_marketing				= base_app + 'marketing/', 
	base_marketing_operasional	= base_marketing + 'operasional/',
	base_marketing_transaksi	= base_marketing + 'transaksi/', 
	base_marketing_collection_tunai = base_marketing + 'collection_tunai/',
	base_marketing_collection_tunai_surat = base_marketing_collection_tunai + 'surat/',
	
	base_marketing_ppjb = base_marketing + 'ppjb/',
	base_marketing_ppjb_master = base_marketing_ppjb + 'master/', 
	base_marketing_ppjb_transaksi = base_marketing_ppjb + 'transaksi/', 
	
	
	base_ppjb				= base_app + 'ppjb/', 
	base_ppjb_master		= base_ppjb + 'master/',
	base_ppjb_transaksi		= base_ppjb + 'transaksi/',
	base_ppjb_laporan		= base_ppjb + 'laporan/',
	base_ppjb_lain			= base_ppjb + 'lain/',
	
	base_kredit				= base_app + 'kredit/',
	base_kredit_transaksi	= base_kredit + 'transaksi/',	
	base_kredit_pelaporan	= base_kredit + 'pelaporan/',
	base_kredit_utilitas	= base_kredit + 'utilitas/',
	
	base_collection_tunai			= base_app + 'collection_tunai/',
	base_collection_tunai_master	= base_collection_tunai + 'master/',
	base_collection_tunai_transaksi	= base_collection_tunai + 'transaksi/',
	base_collection_tunai_lain		= base_collection_tunai + 'lain/',
	base_collection_tunai_surat		= base_collection_tunai + 'surat/',
	base_collection_kpr				= base_app + 'collection_kpr/',
	
	
	winWidth = jQuery(window).width(),
	winHeight = jQuery(window).height(),
	popup

function ajax_start() { jQuery('<div id="wait"><span>Mohon tunggu...</span></div>').prependTo('body'); }
function ajax_stop() { jQuery('#wait').remove(); }

jQuery(document).ajaxStart(function(){ ajax_start(); });
jQuery(document).ajaxStop(function(){ ajax_stop(); });

jQuery(function($) {

	$(document).on('click', '#cb_all', function() {
		$('.cb_data').prop('checked', this.checked);
	});
	
	/* dd-mm-yyyy */
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
	
	/* mm-yyyy */
	$('.mm-yyyy').Zebra_DatePicker({
		format: 'm-Y',
		readonly_element : false,
		inside: true
	});
	
	/* yyyy */
	$('.yyyy').Zebra_DatePicker({
		format: 'Y',
		readonly_element : false,
		inside: true
	});
	
	$(document).on('keyup', '.page_num', function(e) {
		e.preventDefault();
		$('.page_num').val($(this).val());
	});
	
	$('.dd-mm-yyyy').inputmask({  mask: 'd-m-y', placeholder: 'dd/mm/yyyy', clearIncomplete: true });
	$('.mm-yyyy').inputmask({ mask: 'm-y', placeholder: 'mm/yyyy', clearIncomplete: true });
	$('.yyyy').inputmask({ mask: 'y', placeholder: 'yyyy', clearIncomplete: true });
	$('#per_page').inputmask('integer', { repeat: '3' });
});

function to_decimal(s) {
	s = s.replace(/[^0-9.]/g, '');
	s = (s == '') ? '0' : s
	return parseFloat(s);
}

function t_strip(obj) {
	jQuery(obj + ' tbody tr').each(function(index) {
		if (index % 2 != 0){ jQuery(this).addClass('strip'); }
	});
}

function t_scroll(obj) {
	jQuery(obj).addClass('t-scroll').height(jQuery(window).height()-100);
}

/* POPUP */
function setPopup(title, url, width, height)
{
	if (popup) { popup.close(); }
	
	popup = new Window('popup', {
		url				: url,
		title			: title,
		className		: 'mac_os_x',
		width			: width,
		height			: height,
		destroyOnClose	: true,
		zIndex			: 150,
		recenterAuto	: false
		//showEffect	: Effect.BlindDown
	});
	
	popup.showCenter();
	popup.toFront();
}

/* POPUP PRINT */
function open_print(url, prt) {
	
	if(typeof(prt) === 'undefined') { prt = ''; }
	
	var win,
		trg = '_blank',
		sheight = screen.height,
		swidth = screen.width,
		trg = '_blank',
		set = [
			'height=' + (sheight - 100),
			'width=' + (swidth - 100),
			'top=0',
			'left=' + ((swidth/2) - ((swidth - 100)/2)),
			'fullscreen=yes',
			'location=no',
			'titlebar=no',
			'menubar=no',
			'scrollbars=yes',
			'resizable=yes'
		].join(',');

	if (prt == '') {
		win = window.open(url, trg, set);
	} else if (prt == '1') {
		win = parent.window.open(url, trg, set);
	} else if (prt == '2') {
		win = parent.parent.window.open(url, trg, set);
	}
	
}

/*
	varchar
	integer
	numericDesc
		iMax:15,
		dMax:2
	percent
		iMax:2,
		dMax:2
	percent100
*/
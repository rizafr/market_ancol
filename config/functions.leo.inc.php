<?php
function masabangun($v, $b = '')
{
	if ($b != '') { $b = " ($b)"; }
	switch ($v)
	{
		case '0': return '0'; break;
		case '1': return '6'; break;
		case '2': return '9'; break;
		case '3': return '12'; break;
		case '4': return '15'; break;
		case '5': return '18'; break;
		case '6': return '21'; break;
		case '7': return '24'; break;
		default : return '0'; break;
	}
}

function sistem_pembayaran($v, $b = '')
{
	if ($b != '') { $b = " ($b)"; }
	switch ($v)
	{
		case '1': return 'KPR'; break;
		case '2': return 'TUNAI'; break;
		case '3': return 'KOMPENSASI'; break;
		case '4': return 'ASSET SETTLEMENT'; break;
		case '5': return 'KPR JAYA'; break;
		default : return '-'; break;
	}
}

function pembangunan($v, $b = '')
{
	if ($b != '') { $b = " ($b)"; }
	switch ($v)
	{
		case '0': return '0'; break;
		case '1': return '6'; break;
		case '2': return '9'; break;
		case '3': return '12'; break;
		case '4': return '15'; break;
		case '5': return '18'; break;
		case '6': return '20'; break;
		case '7': return '21'; break;
		case '8': return '24'; break;
		case '9': return '14'; break;
		case '10': return '17'; break;
		default : return '0'; break;
	}
}

function prosentase($v, $b = '')
{
	if ($b != '') { $b = " ($b)"; }
	switch ($v)
	{
		case '0': return '0%'; break;
		case '1': return '5%'; break;
		case '2': return '7.5%'; break;
		case '3': return '10%'; break;
		case '4': return '12.5%'; break;
		case '5': return '15%'; break;
		case '6': return '17.5%'; break;
		case '7': return '20%'; break;
		default : return '0'; break;
	}
}

function tgltgl($v)
{
	if ($v == '01-01-1970') return '';
	else return $v;
}

function tanah_bangunan($v)
{
	if ($v == 0) return 'Tanah';
	else return 'Tanah dan Bangunan';
}

function ajb($v)
{
	if ($v < 550000) return 550000;
	else return $v;
}

function laporan($v, $b = '')
{
	if ($b != '') { $b = " ($b)"; }
	switch ($v)
	{
		case '1': return 'Keseluruhan'; break;
		case '2': return 'Tanda tangan oleh Pemilik'; break;
		case '3': return 'Tanda tangan oleh Perusahaan'; break;
		case '4': return 'Penyerahan ke Pemilik'; break;
		case '5': return 'Status Batal'; break;
		default : return '-'; break;
	}
}

function kontgl($tanggal)
{
    $format = array(
        'Sun' => 'Minggu',
        'Mon' => 'Senin',
        'Tue' => 'Selasa',
        'Wed' => 'Rabu',
        'Thu' => 'Kamis',
        'Fri' => 'Jumat',
        'Sat' => 'Sabtu',
        'Jan' => 'Januari',
        'Feb' => 'Februari',
        'Mar' => 'Maret',
        'Apr' => 'April',
        'May' => 'Mei',
        'Jun' => 'Juni',
        'Jul' => 'Juli',
        'Aug' => 'Agustus',
        'Sep' => 'September',
        'Oct' => 'Oktober',
        'Nov' => 'November',
        'Dec' => 'Desember'
    );
 
    return strtr($tanggal, $format);
}

function f_tgl($d, $f = '%d-%m-%Y')
{
	if ($d == '') { return ''; }
	return strftime($f, strtotime($d));
}

function cek($d)
{
	if ($d == NULL) { return '-'; }
	return $d;
}

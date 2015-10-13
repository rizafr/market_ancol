<?php
/* ======== FILE ======== */
function read_file($p)
{
	$f = fopen($p, 'r');
	$r = trim(fgets($f));
	fclose($f);
	
	return $r;
}

function write_file($p, $c)
{
	$f = fopen($p,'w');
	fwrite($f, $c);
	fclose($f);
}

function read_lines_file($p)
{
	$r = implode('<br>', file($p));
	
	return $r;
}

/* ====== CLEAN STRING ====== */
function clean($v, $r = '')
{
	$v = str_replace(array("'","''"),array("`","``"),strip_tags(trim($v)));
	return ($v == '') ? $r : $v;
}

# ====== CLEAN NUMBER & PERIODE ======
function xls_to_number($v) 
{
	if (strpos($v, ']* ')) {
		$r = explode(']* ', $v);
		return to_number($r[1]);
	}
	return $v;
}

function xls_to_decimal($v) 
{
	if (strpos($v, ']* ')) {
		$r = explode(']* ', $v);
		return to_decimal($r[1]);
	}
	return $v;
}

function to_number($v, $r = 0) # Old Regex'/\D/'
{
	$v = intval(preg_replace('/[^0-9\.]/', '', trim($v)));
	return ($v == 0) ? $r : $v;
}

function clean_comma($value) # Old Regex'/\D/'
{
	$value=str_replace(array('.', ','), '' , $string);
	return $value;
}

function bigintval($value) {
  $value = trim($value);
  if (ctype_digit($value)) {
    return $value;
  }
  $value = preg_replace("/[^0-9\.]/", '', $value);
  if (ctype_digit($value)) {
    return $value;
  }
  return 0;
}

function to_decimal($v, $l = 20, $r = 0)
{
	$v = round(floatval(preg_replace('/[^0-9\.]/', '', trim($v))), $l);
	return ($v == 0) ? $r : $v;
}

function to_periode($v, $r = '')
{
	$v = preg_replace('/\D/', '', trim($v));
	$p = substr($v,2,4) . substr($v,0,2);
	return (strlen($p) == 6) ? $p : $r;
}

function to_date($v, $r = '')
{
	$v = preg_replace('/\D/', '', trim($v));
	$p = substr($v,4,4) . substr($v,2,2) . substr($v,0,2);
	return (strlen($p) == 8) ? $p : $r;
}

function to_money($v, $d = 0)
{
	return number_format($v, $d);
}

# ====== GET DATEPART ======
function get_int_bulan($v)
{
	switch (strtoupper($v))
	{
		case 'JAN' : return '01'; break; case 'FEB' : return '02'; break; case 'MAR' : return '03'; break;
		case 'APR' : return '04'; break; case 'MEI' : return '05'; break; case 'JUN' : return '06'; break;
		case 'JUL' : return '07'; break; case 'AGS' : return '08'; break; case 'SEP' : return '09'; break;
		case 'OKT' : return '10'; break; case 'NOV' : return '11'; break; case 'DES' : return '12'; break;
		default : return ''; break;
	}
}

# ====== FORMAT PERIODE ======
function periode_mod($m, $p)
{
	return date('Ym', strtotime($m . " months", strtotime($p.'01')));
}

function fm_periode($p, $f = '%B %Y') 
{
	if ($p == '') { return '-'; }
	return strftime($f, strtotime($p.'01'));
}

function fm_periode_first($p, $f = '%d %B %Y')
{
	if ($p == '') { return '-'; }
	return strftime($f, strtotime($p.'01'));
}

function fm_periode_last($p, $f = '%d %B %Y')
{
	if ($p == '') { return '-'; }
	return strftime($f, strtotime(date('Ymt', strtotime($p.'01'))));
}

# ====== FORMAT DATE ======
function fm_date($d, $f = '%d %B %Y')
{
	if ($d == '') { return '-'; }
	return strftime($f, strtotime($d));
}

# ====== CHECKING ====== 
function is_selected($a, $b)
{
	if ($a == $b)
	{
		return ' selected ';
	}
	return '';
}

function is_checked($a, $b)
{
	if ($a == $b)
	{
		return 'checked="checked"';
	}
	return '';
}

# ====== STATUS ====== 
function status_check($v)
{
	return ($v == '1') ? '<i class="t"></i>' : '<i class="f"></i>';
}

function jenis_bangunan($v)
{
	if ($v == '1') { return 'STANDARD'; }
	elseif ($v == '2') { return 'SUDUT'; }
	elseif ($v == '3') { return 'KHUSUS'; }
	elseif ($v == '4') { return 'LAIN-LAIN'; }
	else { return '-'; }
}

function jenis_bangunan_array($v)
{
	return array(
		'1'=>'STANDARD', 
		'2'=>'SUDUT', 
		'3'=>'KHUSUS', 
		'4'=>'LAIN-LAIN' 
	);
}

# ====== EXCEPTION ====== 
function ex_false($v, $m = '')
{
	if ( ! $v)
	{
		throw new Exception($m);
	}
}

function ex_true($v, $m = '')
{
	if ($v)
	{
		throw new Exception($m);
	}
}

function ex_found($v, $m = '')
{
	if ($v > 0)
	{
		throw new Exception($m);
	}
}

function ex_not_found($v, $m = '')
{
	if ($v < 1)
	{
		throw new Exception($m);
	}
}

function ex_more($v, $x, $m = '')
{
	if ($v > $x)
	{
		throw new Exception($m);
	}
}

function ex_less($v, $x, $m = '')
{
	if ($v < $x)
	{
		throw new Exception($m);
	}
}

function ex_equal($p, $v, $m = '')
{
	if ($p == $v)
	{
		throw new Exception($m);
	}
}

function ex_empty($v, $m = '')
{
	if ($v == '')
	{
		throw new Exception($m);
	}
}


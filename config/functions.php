<?php
require_once('functions.dito.inc.php');
require_once('functions.leo.inc.php');

/* ====== DATA BASE ====== */
function unit($v)
{
	switch ($v) {
		case 'JAYA': return 'JRP Pusat (Bintaro Jaya)';
		default: return '';
	}
}

function conn($db)
{
	if (DNS) {
		$conn =&ADONewConnection('odbc_'.DRIVER);
		$conn->SetFetchMode(ADODB_FETCH_BOTH);
		$dsn = 'Driver={SQL Server};Server='.HOST.';Database='.$db.';';
		if (! $conn->Connect($dsn, USR, PWD)) {
			return FALSE;
		}
	} else {
		$conn =&ADONewConnection(DRIVER);
		$conn->SetFetchMode(ADODB_FETCH_BOTH);
		if (! $conn->Connect(HOST, USR, PWD, $db)) {
			return FALSE;
		}
	}
	
	return $conn;
}

function die_conn($conn, $m = 'Failed Connected to Database!')
{
	if (! $conn)
	{
		echo '
		<script type="text/javascript">
		alert("' . $m . '");
		location.href = "' . BASE_URL . '";
		</script>';
		exit;
	}
}

function ex_conn($conn, $m = 'Failed Connected to Database!')
{
	if (! $conn)
	{
		throw new Exception($m);
	}
}

function close($conn = FALSE)
{
	if ($conn) { $conn->close(); }
}


# ====== AUTHENTIC ====== 
function die_login($m = 'Anda belum melakukan proses login!')
{
	if ( ! isset($_SESSION['USER_ID']))
	{
		echo '
		<script type="text/javascript">
		alert("' . $m . '");
		location.href = "' . BASE_URL . '";
		</script>';
		exit;
	}
}
function ex_login($m = 'Anda belum melakukan proses login!')
{
	if ( ! isset($_SESSION['USER_ID'])) { throw new Exception($m); }
}

# ====== APP ====== 
function die_app($v, $m = 'Login kembali sesuai App!')
{
	/*$app_id = (isset($_SESSION['APP_ID'])) ? $_SESSION['APP_ID'] : '';
	
	if ($app_id != $v) 
	{
		echo '
		<script type="text/javascript">
		alert("' . $m . '");
		location.href = "' . BASE_URL . '";
		</script>';
		exit;
	}*/
}

function ex_app($v, $m = 'Login kembali sesuai App!')
{
	/*$app_id = (isset($_SESSION['APP_ID'])) ? $_SESSION['APP_ID'] : '';
	
	if ($app_id != $v) 
	{
		throw new Exception($m);
	}*/
}

# ====== MODULE ====== 
function die_mod($v, $m = 'Anda tidak memiliki hak akses modul ini!')
{
	/*$modul_id = (isset($_SESSION['MODUL_ID'])) ? $_SESSION['MODUL_ID'] : array();
	
	if ( ! in_array($v, $modul_id)) 
	{
		echo '
		<script type="text/javascript">
		alert("' . $m . '");
		location.href = "' . BASE_APP . '";
		</script>';
		exit;
	}*/
}

function ex_mod($v, $m = 'Anda tidak memiliki hak akses modul ini!')
{
	/*
	$modul_id = (isset($_SESSION['MODUL_ID'])) ? $_SESSION['MODUL_ID'] : array();
	
	if ( ! in_array($v, $modul_id)) 
	{
		throw new Exception($m);
	}*/
}

# ====== HAK AKSES ====== 
function die_ha($d, $h, $m = 'Anda tidak memiliki hak akses untuk proses ini!')
{
	/*
	$modul_ha = (isset($_SESSION['MODUL_HA'][$d][$h])) ? $_SESSION['MODUL_HA'][$d][$h] : '';
	
	if ($modul_ha != 'Y') 
	{
		echo '
		<script type="text/javascript">
		alert("' . $m . '");
		location.href = "' . BASE_APP . '";
		</script>';
		exit;
	}*/
}

function ex_ha($d, $h, $m = 'Anda tidak memiliki hak akses untuk proses ini!')
{
	/*
	$modul_ha = (isset($_SESSION['MODUL_HA'][$d][$h])) ? $_SESSION['MODUL_HA'][$d][$h] : '';
	
	if ($modul_ha != 'Y') 
	{
		throw new Exception($m);
	}*/
}

function date_get($m, $y){
	$m++;
	if($m==13){
		$y++;
		$m=1;
	}
	return $m.'-1-'.$y;
}
<?php
error_reporting(0);
ob_start();
@session_start();

setlocale(LC_TIME, 'Indonesian');
ini_set('date.timezone', 'Asia/Jakarta');
set_time_limit(0);


$conn = FALSE;
$readonly = 'readonly="readonly"';
$sess_user_id = (isset($_SESSION['USER_ID'])) ? $_SESSION['USER_ID'] : '';
$sess_db = (isset($_SESSION['DB'])) ? $_SESSION['DB'] : '';
$sess_app_id = (isset($_SESSION['APP_ID'])) ? $_SESSION['APP_ID'] : '';
$Jabatan[0]='';
$Jabatan[1]='';
$Jabatan[2]='';
$Jabatan[3]='koordinator';
$Jabatan[4]='agen';
#================ INCLUDE ================
require_once('adodb/adodb.inc.php');
require_once('functions.php');

#============== APPLICATION ==============
define('BASE_URL', 'http://localhost:81/market_ancol/');
define('BASE_APP', BASE_URL . 'app/');
define('APP_ROOT', 'C:\\uwamp\\www\\market_ancol\\');

#=============== DATABASE ================
define('DNS', TRUE);

define('DRIVER', 'mssql');
define('HOST', 'SYSTEM\SQLEXPRESS');
define('USR', '');
define('PWD', '');
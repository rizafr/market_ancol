<?php 
require_once('../config/config.php');
$msg = '';
$error = FALSE;

$app_id			= (isset($_REQUEST['app_id'])) ? clean($_REQUEST['app_id']) : '';
$login_id		= (isset($_REQUEST['login_id'])) ? clean($_REQUEST['login_id']) : '';
$password_id	= (isset($_REQUEST['password_id'])) ? clean($_REQUEST['password_id']) : '';
$db				= (isset($_REQUEST['db'])) ? clean($_REQUEST['db']) : '';
$act			= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' AND $act == 'login')
{
	try
	{
		ex_empty($app_id, 'Pilih APP.');
		ex_empty($login_id, 'Masukkan kode user..');
		ex_empty($password_id, 'Masukkan Password.');
		ex_empty($db, 'Pilih database.');
		
		$conn = conn($db);
		ex_conn($conn);
		if ($conn) { $conn->begintrans(); } 
		
		$user_id = '';
		$login_id = strtoupper($login_id);
		$password_id = $password_id;
		$full_name = '';
		$role = '';
		$div = '';
		$modul_id = array();
		$modul_ha = array();
		
		$obj = $conn->Execute("
		SELECT 
			UPPER(USER_ID) AS USER_ID, 
			(PASSOWRD_ID) AS PASSOWRD_ID,  
			UPPER(LOGIN_ID) AS LOGIN_ID,  
			UPPER(FULL_NAME) AS FULL_NAME,
			UPPER(ROLE) AS ROLE,
			UPPER(DIV) AS DIV
		FROM
			USER_APPLICATIONS
		WHERE
			LOGIN_ID = '$login_id'
		");
		
		$dbpass = $obj->fields['PASSOWRD_ID'];
		if ($obj->fields['LOGIN_ID'] == $login_id) {
			if ($obj->fields['PASSOWRD_ID'] != $password_id) { 
				throw new Exception("Kesalahan\nPassword yang anda masukan salah...");
			}
			
			$user_id	= $obj->fields['USER_ID'];
			$full_name	= $obj->fields['FULL_NAME'];
			$role		= $obj->fields['ROLE'];
			$div		= $obj->fields['DIV'];
			
		} else {
			throw new Exception("Kesalahan\nMaaf, Nama anda tidak terdaftar...");
		}
		
		$obj = $conn->Execute("
		SELECT 
			r.USER_ID, 
			r.MODUL_ID, 
			r.R_RONLY, 
			r.R_EDIT, 
			r.R_INSERT, 
			r.R_DELETE
		FROM 
			APPLICATION_RIGHTS r 
			
		WHERE
			r.USER_ID = '$user_id' 
			
		");
		//LEFT JOIN APPLICATION_MODULS m ON r.MODUL_ID = m.MODUL_ID 
		//AND m.APP_ID = '$app_id' 
		while( ! $obj->EOF) {
			$modul_id[] = $obj->fields['MODUL_ID'];
			$modul_ha[$obj->fields['MODUL_ID']] = array(
				'R' => $obj->fields['R_RONLY'],
				'I' => $obj->fields['R_INSERT'],
				'U' => $obj->fields['R_EDIT'],
				'D' => $obj->fields['R_DELETE']
			);
			
			$obj->movenext();
		}
		
		$conn->committrans();
		
		$_SESSION['APP_ID']		= $app_id;
		$_SESSION['DB']			= $db;
		$_SESSION['USER_ID']	= $user_id;
		$_SESSION['LOGIN_ID']	= $login_id;
		$_SESSION['FULL_NAME']	= $full_name;
		$_SESSION['ROLE']		= $role;
		$_SESSION['DIV']		= $div;
		$_SESSION['MODUL_ID']	= $modul_id;
		$_SESSION['MODUL_HA']	= $modul_ha;
		$_SESSION['HOME']	= 'home';
		
		$msg = '';
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		if ($conn) { $conn->rollbacktrans(); }
	}
	
	close($conn);
	$json = array('msg' => $msg, 'error'=> $error);
	echo json_encode($json);
	exit;
}
elseif ($act == 'logout') {
	echo "<script>alert(test) </script>";
	session_destroy();
	header('location: ' . BASE_URL);
}


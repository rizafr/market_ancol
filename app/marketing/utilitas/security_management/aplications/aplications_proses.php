<?php
require_once('../../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$app_name = (isset($_REQUEST['app_name'])) ? clean($_REQUEST['app_name']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('A');
		ex_mod('A01');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Edit') # Proses Ubah
		{
			ex_ha('A01', 'U');
			
			ex_empty($app_name, 'App harus diisi.');
			
			$ols_app_name = $conn->Execute("SELECT APP_NAME FROM APPLICATIONS WHERE APP_ID = '$id'")->fields['APP_NAME'];
			
			if ($app_name != $ols_app_name)
			{
				$query = "SELECT COUNT(APP_NAME) AS TOTAL FROM APPLICATIONS WHERE APP_NAME = '$app_name'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "login id \"$app_name\" telah terdaftar.");
			}
					
			$query = "
			UPDATE APPLICATIONS 
			SET APP_NAME = '$app_name'
			WHERE
				APP_ID = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data app berhasil diubah.';
		}
		$conn->committrans(); 
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		if ($conn) { $conn->rollbacktrans(); } 
	}

	close($conn);
	$json = array('act' => $act, 'error'=> $error, 'msg' => $msg);
	echo json_encode($json);
	exit;
}

die_login();
//die_app('A');
die_mod('A01');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Edit')
{
	$obj = $conn->Execute("SELECT * FROM APPLICATIONS WHERE APP_ID = '$id'");
	$app_name = $obj->fields['APP_NAME'];
}
?>
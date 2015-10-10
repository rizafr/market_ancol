<?php
require_once('../../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$app_id = (isset($_REQUEST['app_id'])) ? clean($_REQUEST['app_id']) : '';
$modul_name = (isset($_REQUEST['modul_name'])) ? clean($_REQUEST['modul_name']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('A01');
		ex_mod('PU05');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Edit') # Proses Ubah
		{
			ex_ha('PU05', 'U');
			
			ex_empty($app_id, 'Pilih app.');
			ex_empty($modul_name, 'Modul harus diisi.');
			
			$ols_modul_name = $conn->Execute("SELECT MODUL_NAME FROM APPLICATION_MODULS WHERE MODUL_ID = '$id'")->fields['MODUL_NAME'];
			
			if ($modul_name != $ols_modul_name)
			{
				$query = "SELECT COUNT(MODUL_NAME) AS TOTAL FROM APPLICATION_MODULS WHERE MODUL_NAME = '$modul_name'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "login id \"$modul_name\" telah terdaftar.");
			}
					
			$query = "
			UPDATE APPLICATION_MODULS 
			SET APP_ID = '$app_id',
				MODUL_NAME = '$modul_name'
			WHERE
				MODUL_ID = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data modul berhasil diubah.';
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
die_app('A01');
die_mod('PU05');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Edit')
{	
	$obj = $conn->Execute("SELECT * FROM APPLICATION_MODULS WHERE MODUL_ID = '$id'");
	$app_id = $obj->fields['APP_ID'];
	$modul_name = $obj->fields['MODUL_NAME'];
}
?>
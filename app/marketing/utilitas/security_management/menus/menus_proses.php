<?php
require_once('../../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$app_id = (isset($_REQUEST['app_id'])) ? clean($_REQUEST['app_id']) : '';
$menu_name = (isset($_REQUEST['menu_name'])) ? clean($_REQUEST['menu_name']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('A');
		ex_mod('A02');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Edit') # Proses Ubah
		{
			ex_ha('A02', 'U');
			
			ex_empty($app_id, 'Pilih app.');
			ex_empty($menu_name, 'menu harus diisi.');
			
			$ols_menu_name = $conn->Execute("SELECT MENU_NAME FROM MENUS WHERE MENU_ID = '$id'")->fields['MENU_NAME'];
			
			if ($menu_name != $ols_menu_name)
			{
				$query = "SELECT COUNT(MENU_NAME) AS TOTAL FROM MENUS WHERE MENU_NAME = '$menu_name'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "login id \"$menu_name\" telah terdaftar.");
			}
					
			$query = "
			UPDATE APPLICATION_MENU
			SET APP_ID = '$app_id',
				MENU_NAME = '$menu_name'
			WHERE
				menu_ID = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data menu berhasil diubah.';
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
die_mod('A02');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Edit')
{	
	$obj = $conn->Execute("SELECT * FROM APPLICATION_MENU WHERE MENU_ID = '$id'");
	$app_id = $obj->fields['APP_ID'];
	$menu_name = $obj->fields['MENU_NAME'];
}
?>
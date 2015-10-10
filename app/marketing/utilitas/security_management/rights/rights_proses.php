<?php
require_once('../../../../../config/config.php');

$msg = '';
$error = FALSE;

$user_id = (isset($_REQUEST['s_user_id'])) ? clean($_REQUEST['s_user_id']) : '';
$app_id	= (isset($_REQUEST['s_app_id'])) ? clean($_REQUEST['s_app_id']) : '';

$ar_modul_id = (isset($_REQUEST['ar_modul_id'])) ? $_REQUEST['ar_modul_id'] : array();
$ar_ronly = (isset($_REQUEST['r_ronly'])) ? $_REQUEST['r_ronly'] : array();
$ar_edit = (isset($_REQUEST['r_edit'])) ? $_REQUEST['r_edit'] : array();
$ar_insert = (isset($_REQUEST['r_insert'])) ? $_REQUEST['r_insert'] : array();
$ar_delete = (isset($_REQUEST['r_delete'])) ? $_REQUEST['r_delete'] : array();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('A');
		ex_mod('A05');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
		
		ex_ha('A05', 'U');
			
		ex_empty($user_id, 'Pilih user ID.');
		ex_empty($app_id, 'Pilih App ID.');
		
		foreach ($ar_modul_id as $i => $modul_id) {
			$conn->Execute("DELETE FROM APPLICATION_RIGHTS WHERE USER_ID = '$user_id' AND MODUL_ID = '$modul_id'");
			
			$r_ronly = (isset($ar_ronly[$modul_id])) ? $ar_ronly[$modul_id] : 'T';
			$r_edit = (isset($ar_edit[$modul_id])) ? $ar_edit[$modul_id] : 'T';
			$r_insert = (isset($ar_insert[$modul_id])) ? $ar_insert[$modul_id] : 'T';
			$r_delete = (isset($ar_delete[$modul_id])) ? $ar_delete[$modul_id] : 'T';
				
			$query = "INSERT INTO APPLICATION_RIGHTS (USER_ID, MODUL_ID, R_RONLY, R_EDIT, R_INSERT, R_DELETE) VALUES 
			(
				'$user_id',
				'$modul_id',
				
				'$r_ronly',
				'$r_edit',
				'$r_insert',
				'$r_delete'
			)";
			ex_false($conn->Execute($query), $query);
		}
		
		$msg = "Hak akses modul berhasil diubah.";
		
		$conn->committrans(); 
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		if ($conn) { $conn->rollbacktrans(); } 
	}

	close($conn);
	$json = array('act' => '', 'error'=> $error, 'msg' => $msg);
	echo json_encode($json);
	exit;
}
?>
<?php
require_once('../../../../config/config.php');
die_login();
$conn = conn($sess_db);
die_conn($conn);
$msg = '';
$error = FALSE;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('M');
		//ex_mod('');
		$conn = conn($sess_db);
		ex_conn($conn);
		
		//ex_ha('', 'U');
		
		$conn->begintrans();
		
		$user_id 		= $_SESSION['USER_ID'];
		$new_pass		= (isset($_REQUEST['new_pass'])) ? clean($_REQUEST['new_pass']) : '';
		
		$query = "UPDATE USER_APPLICATIONS set PASSOWRD_ID = '$new_pass' WHERE USER_ID = '$user_id'";
		ex_false($conn->execute($query), $query);
		
		$conn->committrans();
		
		$msg = 'Password berhasil diubah.';
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		$conn->rollbacktrans();
	}

	close($conn);
	$json = array('msg' => $msg, 'error'=> $error);
	echo json_encode($json);
	exit;
}
?>
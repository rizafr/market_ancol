<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;
		
		$batas_distribusi		= (isset($_REQUEST['batas_distribusi'])) ? clean($_REQUEST['batas_distribusi']) : '';
		$tenggang_distribusi	= (isset($_REQUEST['tenggang_distribusi'])) ? clean($_REQUEST['tenggang_distribusi']) : '';
		$batas_reserve			= (isset($_REQUEST['batas_reserve'])) ? clean($_REQUEST['batas_reserve']) : '';
		$act				= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('M');
		ex_mod('M26');
		$conn = conn($sess_db);
		ex_conn($conn);
		
		$conn->begintrans();

		if ($act == 'ubah') # Proses Ubah Identitas
		{
		
			//ex_ha('M26', 'U');

			$query = "SELECT * FROM CS_PARAMETER_MARK WHERE BATAS_DISTRIBUSI= '$batas_distribusi' AND TENGGANG_DISTRIBUSI = '$tenggang_distribusi' 
			AND BATAS_RESERVE = '$batas_reserve'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			$conn->Execute("DELETE FROM CS_PARAMETER_MARK");
			$query = "
			INSERT INTO CS_PARAMETER_MARK(BATAS_DISTRIBUSI, TENGGANG_DISTRIBUSI, BATAS_RESERVE) VALUES('$batas_distribusi','$tenggang_distribusi','$batas_reserve')
		";
		
		ex_false($conn->Execute($query), $query);
		
		$msg = 'Data Parameter berhasil diubah.';
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
?>
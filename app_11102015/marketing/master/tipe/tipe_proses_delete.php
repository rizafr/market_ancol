<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_tipe = (isset($_REQUEST['kode_tipe'])) ? to_number($_REQUEST['kode_tipe']) : '';
$tipe_bangunan= (isset($_REQUEST['tipe_bangunan'])) ? clean($_REQUEST['tipe_bangunan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M04');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 	
		
			ex_ha('M04', 'U');
			
			ex_empty($kode_tipe, 'Kode tipe harus diisi.');
			ex_empty($tipe_bangunan, 'Nama tipe bangunan harus diisi.');
					
			$query = "
			DELETE FROM TIPE
			WHERE
				KODE_TIPE = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Tipe berhasil diubah.';
		
		$conn->committrans(); 
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		if ($conn) { $conn->rollbacktrans(); } 
	}

	close($conn);
	$json = array('error'=> $error, 'msg' => $msg);
	echo json_encode($json);
	exit;
}

die_login();
die_app('M');
die_mod('M04');
$conn = conn($sess_db);
die_conn($conn);

?>
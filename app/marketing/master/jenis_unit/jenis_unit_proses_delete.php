<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_unit = (isset($_REQUEST['kode_unit'])) ? to_number($_REQUEST['kode_unit']) : '';
$jenis_unit = (isset($_REQUEST['jenis_unit'])) ? clean($_REQUEST['jenis_unit']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M06');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			ex_ha('M06', 'U');
			
			ex_empty($kode_unit, 'Kode jenis unit harus diisi.');
			ex_empty($jenis_unit, 'Nama jenis unit harus diisi.');
		
			$query = "
			DELETE FROM JENIS_UNIT 
			WHERE
				KODE_UNIT = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data jenis unit berhasil dihapus';
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
die_mod('M06');
$conn = conn($sess_db);
die_conn($conn);
?>
<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_lokasi = (isset($_REQUEST['kode_lokasi'])) ? to_number($_REQUEST['kode_lokasi']) : '';
$lokasi = (isset($_REQUEST['lokasi'])) ? clean($_REQUEST['lokasi']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M01');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			ex_ha('M01', 'U');
			
			ex_empty($kode_lokasi, 'Kode lokasi harus diisi.');
			ex_empty($lokasi, 'Nama lokasi harus diisi.');

			$query = "
			DELETE FROM LOKASI 
			WHERE
				KODE_LOKASI = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Lokasi berhasil dihapus.';
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
die_mod('M01');
$conn = conn($sess_db);
die_conn($conn);
?>
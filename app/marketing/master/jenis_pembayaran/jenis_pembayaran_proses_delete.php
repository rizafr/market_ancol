<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_bayar = (isset($_REQUEST['kode_bayar'])) ? to_number($_REQUEST['kode_bayar']) : '';
$jenis_bayar = (isset($_REQUEST['jenis_bayar'])) ? clean($_REQUEST['jenis_bayar']) : '';


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M11');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			ex_ha('M11', 'U');
			
			ex_empty($kode_bayar, 'Kode pembayaran harus diisi.');
			ex_empty($jenis_bayar, 'Nama jenis pembayaran harus diisi.');

			$query = "
			DELETE FROM JENIS_PEMBAYARAN 
			WHERE
				KODE_BAYAR = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data jenis pembayaran berhasil dihapus.';
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
die_mod('M11');
$conn = conn($sess_db);
die_conn($conn);
	
?>
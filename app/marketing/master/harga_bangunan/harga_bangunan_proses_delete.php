<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_sk = (isset($_REQUEST['kode_sk'])) ? to_number($_REQUEST['kode_sk']) : '';
$kode_lokasi = (isset($_REQUEST['kode_lokasi'])) ? to_number($_REQUEST['kode_lokasi']) : '';
$jenis_bangunan = (isset($_REQUEST['jenis_bangunan'])) ? to_number($_REQUEST['jenis_bangunan']) : '';
$harga_bangunan = (isset($_REQUEST['harga_bangunan'])) ? to_number($_REQUEST['harga_bangunan']) : '';
$status = (isset($_REQUEST['status'])) ? to_number($_REQUEST['status']) : '0';
$tanggal = (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M10');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			ex_ha('M10', 'U');
			
			ex_empty($kode_sk, 'Kode sk harus diisi.');
			ex_empty($kode_lokasi, 'Kode lokasi harus diisi.');
			ex_empty($jenis_bangunan, 'Pilih jenis bangunan.');
			ex_empty($harga_bangunan, 1, 'Harga bangunan > 0');
			ex_empty($tanggal, 'Pilih tanggal.');
	
			$query = "
			DELETE FROM HARGA_BANGUNAN 
			WHERE
				KODE_SK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data harga bangunan berhasil dihapus.';
		
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
die_mod('M10');
$conn = conn($sess_db);
die_conn($conn);

?>
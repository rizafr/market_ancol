<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_sk = (isset($_REQUEST['kode_sk'])) ? to_number($_REQUEST['kode_sk']) : '';
$kode_lokasi = (isset($_REQUEST['kode_lokasi'])) ? to_number($_REQUEST['kode_lokasi']) : '';
$harga_tanah = (isset($_REQUEST['harga_tanah'])) ? to_number($_REQUEST['harga_tanah']) : '';
$tanggal = (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$status = (isset($_REQUEST['status'])) ? to_number($_REQUEST['status']) : '0';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M09');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			ex_ha('M09', 'U');
			
			ex_empty($kode_sk, 'Kode SK harus diisi.');
			ex_empty($kode_lokasi, 'Lokasi harus diisi.');
			ex_empty($harga_tanah, 'Harga tanah tidak boleh 0');
			ex_empty($tanggal, 'Pilih tanggal.');
		
			$query = "
			DELETE FROM HARGA_TANAH 
			WHERE
				KODE_SK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data harga tanah berhasil diubah.';
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
die_mod('M09');
$conn = conn($sess_db);
die_conn($conn);
?>
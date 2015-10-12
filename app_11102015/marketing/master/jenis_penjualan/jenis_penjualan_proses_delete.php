<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$nm = (isset($_REQUEST['nm'])) ? clean($_REQUEST['nm']) : '';

$kode_jenis	= (isset($_REQUEST['kode_jenis'])) ? to_number($_REQUEST['kode_jenis']) : '';
$jenis_penjualan = (isset($_REQUEST['jenis_penjualan'])) ? clean($_REQUEST['jenis_penjualan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M07');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			ex_ha('M07', 'U');
			
			ex_empty($kode_jenis, 'Kode penjualan harus diisi.');
			ex_empty($jenis_penjualan, 'Nama penjualan harus diisi.');
		
			$query = "
			DELETE FROM JENIS_PENJUALAN 
			WHERE
				KODE_JENIS = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data jenis penjualan berhasil dihapus.';
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
die_app('M');
die_mod('M07');
$conn = conn($sess_db);
die_conn($conn);
	
?>
<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$nm = (isset($_REQUEST['nm'])) ? clean($_REQUEST['nm']) : '';

$kode_desa = (isset($_REQUEST['kode_desa'])) ? to_number($_REQUEST['kode_desa']) : '';
$nama_desa = (isset($_REQUEST['nama_desa'])) ? clean($_REQUEST['nama_desa']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M02');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 	
			ex_ha('M02', 'U');
			
			ex_empty($kode_desa, 'Kode desa harus diisi.');
			ex_empty($nama_desa, 'Nama desa harus diisi.');
					
			$query = "
			DELETE FROM DESA
			WHERE
				KODE_DESA = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Desa berhasil dihapus.';
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
die_mod('M02');
$conn = conn($sess_db);
die_conn($conn);
	
?>
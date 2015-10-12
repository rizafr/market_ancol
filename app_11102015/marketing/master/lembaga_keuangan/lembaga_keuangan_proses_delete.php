<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_bank	= (isset($_REQUEST['kode_bank'])) ? to_number($_REQUEST['kode_bank']) : '';
$nama_bank	= (isset($_REQUEST['nama_bank'])) ? clean($_REQUEST['nama_bank']) : '';
$alamat_bank = (isset($_REQUEST['alamat_bank'])) ? clean($_REQUEST['alamat_bank']) : '';
$npwp = (isset($_REQUEST['npwp'])) ? clean($_REQUEST['npwp']) : '';
$kode_va_unit = (isset($_REQUEST['kode_va_unit'])) ? clean($_REQUEST['kode_va_unit']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M08');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			ex_ha('M08', 'U');
			
			ex_empty($kode_bank, 'Kode bank harus diisi.');
			ex_empty($nama_bank, 'Nama bank harus diisi.');
						
			$query = "
			DELETE FROM BANK 
			WHERE
				KODE_BANK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data lembaga keuangan berhasil dihapus.';		
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
die_mod('M08');
$conn = conn($sess_db);
die_conn($conn);

?>
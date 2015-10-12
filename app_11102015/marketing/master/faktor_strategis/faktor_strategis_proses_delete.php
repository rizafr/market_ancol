<?php
require_once('../../../../config/config.php');

$msg	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_faktor 		= (isset($_REQUEST['kode_faktor'])) ? to_number($_REQUEST['kode_faktor']) : '';
$faktor_strategis	= (isset($_REQUEST['faktor_strategis'])) ? clean($_REQUEST['faktor_strategis']) : '';
$nilai_tambah		= (isset($_REQUEST['nilai_tambah'])) ? to_decimal($_REQUEST['nilai_tambah']) : '';
$nilai_kurang		= (isset($_REQUEST['nilai_kurang'])) ? to_decimal($_REQUEST['nilai_kurang']) : '';
$status				= (isset($_REQUEST['status'])) ? to_number($_REQUEST['status']) : '0';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M05');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
		
			ex_ha('PM03', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				if ($conn->Execute("DELETE FROM FAKTOR WHERE KODE_FAKTOR = '$id_del'")) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data faktor strategis berhasil dihapus.';
		$conn->committrans(); 
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		if ($conn) { $conn->rollbacktrans(); } 
	}

	close($conn);
	$json = array( 'error'=> $error, 'msg' => $msg);
	echo json_encode($json);
	exit;
}

die_login();
die_app('M');
die_mod('M05');
$conn = conn($sess_db);
die_conn($conn);

?>
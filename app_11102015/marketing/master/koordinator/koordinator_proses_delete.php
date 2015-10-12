<?php
require_once('../../../../config/config.php');

$msg 	= '';
$error 	= FALSE;

$id 	= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$nm 	= (isset($_REQUEST['nm'])) ? clean($_REQUEST['nm']) : '';

$nomor_id	 	= (isset($_REQUEST['nomor_id'])) ? clean($_REQUEST['nomor_id']) : '';
$nama 			= (isset($_REQUEST['nama'])) ? clean($_REQUEST['nama']) : '';
$alamat 		= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';
$jabatan		= (isset($_REQUEST['jabatan'])) ? to_number($_REQUEST['jabatan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M14');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			ex_ha('M14', 'U');
			
			ex_empty($nomor_id, 'Nomor id harus diisi.');			
			
			$query = "
			DELETE FROM CLUB_PERSONAL
			WHERE
				NOMOR_ID = '$id' AND JABATAN_KLUB = '4'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Club personal berhasil dihapus.';
		
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
die_mod('M14');
$conn = conn($sess_db);
die_conn($conn);

?>
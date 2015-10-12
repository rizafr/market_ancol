<?php
require_once('../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('A');
		ex_mod('A06');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Pemulihan') # Proses Otorisasi
		{
			ex_ha('A06', 'U');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dipulihkan.');
			
			foreach ($cb_data as $id_del)
			{	
				if ($conn->Execute("UPDATE SPP SET TANGGAL_SPP = CONVERT(DATETIME,GETDATE(),105), TANGGAL_PROSES = CONVERT(DATETIME,GETDATE(),105)
				WHERE KODE_BLOK = '$id_del'")) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data gagal dipulihkan.' : 'Data berhasil dipulihkan.';
		}
				
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


?>
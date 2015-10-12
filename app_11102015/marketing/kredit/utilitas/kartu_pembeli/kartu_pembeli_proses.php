<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';

$bank			= (isset($_REQUEST['bank'])) ? clean($_REQUEST['bank']) : '';
$rekening		= (isset($_REQUEST['rekening'])) ? clean($_REQUEST['rekening']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('');
		ex_mod('K11');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Bank') # Proses Edit Bank dan Nomor Rekening
		{
			//ex_ha('', 'I');
			
			$query = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE WHERE NAMA_BANK = '$bank' AND NOMOR_REKENING = '$rekening'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE CS_REGISTER_CUSTOMER_SERVICE 
			SET
				NAMA_BANK = '$bank', 
				NOMOR_REKENING = '$rekening'
			";
			ex_false($conn->execute($query), $query);
			
			$msg = "Bank dan Nomor Rekening telah diubah.";
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

die_login();
//die_app('');
die_mod('K11');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Bank')
{
	$query = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE";
	$obj = $conn->execute($query);
	
	$bank			= $obj->fields['NAMA_BANK'];
	$rekening		= $obj->fields['NOMOR_REKENING'];
}
?>
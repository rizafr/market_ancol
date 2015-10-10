<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$daya_listrik	= (isset($_REQUEST['daya_listrik'])) ? to_number($_REQUEST['daya_listrik']) : '';
$masa_bangun	= (isset($_REQUEST['masa_bangun'])) ? to_number($_REQUEST['masa_bangun']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('P');
		ex_mod('P05');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('JB05', 'I');
			
			$msg = '';
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('JB05', 'U');
			
			$query = "
			UPDATE TIPE
			SET 
				DAYA_LISTRIK = '$daya_listrik',
				MASA_BANGUN  = '$masa_bangun'
			WHERE
				KODE_TIPE = '$id'
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data Tipe Bangunan berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('JBF05', 'D');
			
			$msg = '';
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
// die_app('P');
die_mod('P05');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$query = "SELECT * FROM TIPE WHERE KODE_TIPE = '$id'";
	$obj = $conn->execute($query);
	$kode_tipe = $obj->fields['KODE_TIPE'];
	$tipe_bangunan = $obj->fields['TIPE_BANGUNAN'];
	$daya_listrik = $obj->fields['DAYA_LISTRIK'];
	$masa_bangun = $obj->fields['MASA_BANGUN'];
}
?>
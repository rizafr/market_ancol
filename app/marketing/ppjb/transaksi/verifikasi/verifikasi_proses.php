<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$officer= $_SESSION['USER_ID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('P');
		ex_mod('P07');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'verifikasi') /* Proses Verifikasi */
		{
			ex_ha('JB07', 'U');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan diverifikasi.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "UPDATE CS_PPJB SET 
				STATUS_OTORISASI = 1,
				TANGGAL_OTORISASI = getdate(),
				OFFICER_OTORISASI = '$officer'
				WHERE KODE_BLOK = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data PPJB gagal diverifikasi.' : 'Data PPJB berhasil diverifikasi.';
		}
		elseif ($act == 'batal_verifikasi') /* Proses Batal Verifikasi */
		{
			ex_ha('JB07', 'U');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dibatalkan verifikasi.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "UPDATE CS_PPJB SET 
				STATUS_OTORISASI = NULL,
				TANGGAL_OTORISASI = NULL,
				OFFICER_OTORISASI = NULL
				WHERE KODE_BLOK = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data PPJB gagal batal verifikasi.' : 'Data PPJB yang telah diverifikasi berhasil dibatalkan verifikasinya.';
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
// die_app('A01');
die_mod('P07');
$conn = conn($sess_db);
die_conn($conn);
?>
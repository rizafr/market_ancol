<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$field1	= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';

$tgl = f_tgl (date("Y-m-d"));
$officer= $_SESSION['USER_ID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('');
		//ex_mod('');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
		
		if ($act == 'verifikasi') /* Proses Verifikasi */
		{
			if ($field1 == 1)
				{
				$act = array();
				$cb_data = $_REQUEST['cb_data'];
				ex_empty($cb_data, 'Pilih data yang akan diverifikasi.');
				
				foreach ($cb_data as $id_del)
				{
					$query = "UPDATE KWITANSI SET 
					VER_COLLECTION = 1,
					VER_COLLECTION_TANGGAL = CONVERT(DATETIME,'$tgl',105),
					VER_COLLECTION_OFFICER = '$officer'
					WHERE NOMOR_KWITANSI = '$id_del'";
					if ($conn->Execute($query)) {
						$act[] = $id_del;
					} else {
						$error = TRUE;
					}
				}
				
				$msg = ($error) ? 'Sebagian data kwitansi gagal diverifikasi.' : 'Data kwitansi berhasil diverifikasi.';
				}
				
				else if ($field1 == 2)
				{
		
				$act = array();
				$cb_data = $_REQUEST['cb_data'];
				ex_empty($cb_data, 'Pilih data yang akan diverifikasi.');
				
				foreach ($cb_data as $id_del)
				{
					$query = "UPDATE KWITANSI_LAIN_LAIN SET 
					VER_COLLECTION = 1,
					VER_COLLECTION_TANGGAL = getdate(),
					VER_COLLECTION_OFFICER = '$officer'
					WHERE NOMOR_KWITANSI = '$id_del'";
					if ($conn->Execute($query)) {
						$act[] = $id_del;
					} else {
						$error = TRUE;
					}
				}
				
				$msg = ($error) ? 'Sebagian data kwitansi gagal diverifikasi.' : 'Data kwitansi berhasil diverifikasi.';
				}
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
die_app('');
die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>
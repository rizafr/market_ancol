<?php
require_once('../../../../config/config.php');
die_login();
die_app('C01');
die_mod('COL03');
$conn = conn($sess_db);
die_conn($conn);
$msg = '';
$error = FALSE;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		$conn->begintrans();
		
		$no_surat_akhir	= (isset($_REQUEST['no_surat_akhir'])) ? clean($_REQUEST['no_surat_akhir']) : '';
		$registrasi		= (isset($_REQUEST['registrasi'])) ? clean($_REQUEST['registrasi']) : '';
		
		$conn->Execute("DELETE FROM REGISTRASI_SURAT");
		
		$query = "
		INSERT INTO REGISTRASI_SURAT (
		NO_SURAT_AKHIR, REGISTRASI
		)
		VALUES (
		'$no_surat_akhir', '$registrasi'
		)
		";
		
		ex_false($conn->Execute($query), $query);
		
		$conn->committrans();
		
		$msg = 'Parameter berhasil diubah.';
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		$conn->rollbacktrans();
	}

	close($conn);
	$json = array('msg' => $msg, 'error'=> $error);
	echo json_encode($json);
	exit;
}
?>
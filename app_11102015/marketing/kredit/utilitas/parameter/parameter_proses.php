<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';

$nama		= (isset($_REQUEST['nama'])) ? clean($_REQUEST['nama']) : '';
$alamat		= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';
$npwp		= (isset($_REQUEST['npwp'])) ? clean($_REQUEST['npwp']) : '';
$tanggal	= (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$uang_no		= (isset($_REQUEST['uang_no'])) ? to_number($_REQUEST['uang_no']) : '';
$lain_no		= (isset($_REQUEST['lain_no'])) ? to_number($_REQUEST['lain_no']) : '';
$faktur_no		= (isset($_REQUEST['faktur_no'])) ? to_number($_REQUEST['faktur_no']) : '';
$tanda_no		= (isset($_REQUEST['tanda_no'])) ? to_number($_REQUEST['tanda_no']) : '';
$uang_reg		= (isset($_REQUEST['uang_reg'])) ? clean($_REQUEST['uang_reg']) : '';
$lain_reg		= (isset($_REQUEST['lain_reg'])) ? clean($_REQUEST['lain_reg']) : '';
$faktur_reg		= (isset($_REQUEST['faktur_reg'])) ? clean($_REQUEST['faktur_reg']) : '';
$tanda_reg		= (isset($_REQUEST['tanda_reg'])) ? clean($_REQUEST['tanda_reg']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('');
		die_mod('K09');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'save1') # Proses Ubah Identitas
		{
			//ex_ha('', 'U');
			
			$query = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE WHERE NAMA_PT = '$nama' AND TGL_PKP = CONVERT(DATETIME,'$tanggal',105) AND 
			ALAMAT = '$alamat' AND NPWP = '$npwp'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "UPDATE CS_REGISTER_CUSTOMER_SERVICE 
			SET
				NAMA_PT = '$nama', 
				TGL_PKP = CONVERT(DATETIME,'$tanggal',105), 
				ALAMAT = '$alamat', 
				NPWP = '$npwp'
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data parameter berhasil diubah.';
		}
		else if ($act == 'save2') # Proses Ubah Nomor & Register
		{
			//ex_ha('', 'U');
			
			$query = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE WHERE REG_KWITANSI = '$uang_reg' AND REG_KWITANSI_LAIN = '$lain_reg' AND NO_REG_FAKTUR_PAJAK = '$faktur_reg' AND REG_KWITANSI_TTS = '$tanda_reg' AND
			NOMOR_KWITANSI = $uang_no AND NOMOR_KWITANSI_LAIN = $lain_no AND NO_FAKTUR_PAJAK_STANDAR = $faktur_no AND NOMOR_KWITANSI_TTS = $tanda_no
			";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE CS_REGISTER_CUSTOMER_SERVICE 
			SET
				REG_KWITANSI = '$uang_reg', NOMOR_KWITANSI = $uang_no,
				REG_KWITANSI_LAIN = '$lain_reg', NOMOR_KWITANSI_LAIN = $lain_no,
				NO_REG_FAKTUR_PAJAK = '$faktur_reg', NO_FAKTUR_PAJAK_STANDAR = $faktur_no,
				REG_KWITANSI_TTS = '$tanda_reg', NOMOR_KWITANSI_TTS = $tanda_no
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data parameter berhasil diubah.';
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
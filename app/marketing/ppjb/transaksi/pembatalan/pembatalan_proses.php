<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$alasan	= (isset($_REQUEST['alasan'])) ? clean($_REQUEST['alasan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('P');
		ex_mod('P08');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Pembatalan') # Proses Pembatalan
		{
			ex_ha('JB08', 'U');
			
			$query = "
			INSERT INTO CS_PPJB_PEMBATALAN (
				KODE_BLOK, NOMOR, TANGGAL, JENIS, ADDENDUM, MASA_BANGUN, DAYA_LISTRIK, KODE_KELURAHAN, KODE_KECAMATAN, TANGGAL_PINJAM_PEMBELI, TANGGAL_TT_PEMBELI, TANGGAL_TT_PEJABAT, TANGGAL_PENYERAHAN, STATUS_CETAK, NAMA_PEMBELI, PROSEN_P_HAK, NOMOR_ARSIP
			)
			SELECT 
				KODE_BLOK, NOMOR, TANGGAL, JENIS, ADDENDUM, MASA_BANGUN, DAYA_LISTRIK, KODE_KELURAHAN, KODE_KECAMATAN, TANGGAL_PINJAM_PEMBELI, TANGGAL_TT_PEMBELI, TANGGAL_TT_PEJABAT, TANGGAL_PENYERAHAN, STATUS_CETAK, NAMA_PEMBELI, PROSEN_P_HAK, NOMOR_ARSIP
			FROM CS_PPJB
			WHERE KODE_BLOK = '$id'
			";
			ex_false($conn->execute($query), $query);
			
			$query = "UPDATE CS_PPJB_PEMBATALAN SET ALASAN = '$alasan' WHERE KODE_BLOK = '$id'";
			ex_false($conn->execute($query), $query);	
			
			$query = "DELETE FROM CS_PPJB WHERE KODE_BLOK = '$id'";
			ex_false($conn->execute($query), $query);
			
			$msg = "Pembatalan PPJB berhasil diproses.";
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
die_mod('P08');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Pembatalan')
{
	$query = "SELECT * FROM CS_PPJB WHERE KODE_BLOK = '$id'";
	$obj = $conn->execute($query);
	$blok_nomor = $obj->fields['KODE_BLOK'];
	$nama_pembeli = $obj->fields['NAMA_PEMBELI'];
	$nomor_ppjb = $obj->fields['NOMOR'];
}
?>
<?php
require_once('../../../../../config/config.php');

$msg = '';
$error = FALSE;

		$nama_pt		= (isset($_REQUEST['nama_pt'])) ? clean($_REQUEST['nama_pt']) : '';
		$unit			= (isset($_REQUEST['unit'])) ? clean($_REQUEST['unit']) : '';
		$nama_dep		= (isset($_REQUEST['nama_dep'])) ? clean($_REQUEST['nama_dep']) : '';
		$nama_pejabat	= (isset($_REQUEST['nama_pejabat'])) ? clean($_REQUEST['nama_pejabat']) : '';
		$nama_jabatan	= (isset($_REQUEST['nama_jabatan'])) ? clean($_REQUEST['nama_jabatan']) : '';
		$kota			= (isset($_REQUEST['kota'])) ? clean($_REQUEST['kota']) : '';

		$pejabat_ppjb	= (isset($_REQUEST['pejabat_ppjb'])) ? clean($_REQUEST['pejabat_ppjb']) : '';
		$jabatan_ppjb	= (isset($_REQUEST['jabatan_ppjb'])) ? clean($_REQUEST['jabatan_ppjb']) : '';
		$nomor_sk		= (isset($_REQUEST['nomor_sk'])) ? clean($_REQUEST['nomor_sk']) : '';
		$jumlah_hari	= (isset($_REQUEST['jumlah_hari'])) ? clean($_REQUEST['jumlah_hari']) : '';
		$nomor_ppjb		= (isset($_REQUEST['nomor_ppjb'])) ? to_number($_REQUEST['nomor_ppjb']) : '';
		$reg_ppjb		= (isset($_REQUEST['reg_ppjb'])) ? clean($_REQUEST['reg_ppjb']) : '';
		$tanggal_sk		= (isset($_REQUEST['tanggal_sk'])) ? clean($_REQUEST['tanggal_sk']) : '';
		$nomor_ppjb_ph	= (isset($_REQUEST['nomor_ppjb_ph'])) ? clean($_REQUEST['nomor_ppjb_ph']) : '';
		$reg_ppjb_ph	= (isset($_REQUEST['reg_ppjb_ph'])) ? clean($_REQUEST['reg_ppjb_ph']) : '';	

		$act			= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('A01');
		ex_mod('JB12');
		$conn = conn($sess_db);
		ex_conn($conn);
		
		$conn->begintrans();
		
		if ($act == 'save1') # Proses Ubah Identitas
		{

		//ex_ha('JB12', 'U');

		$query = "SELECT * FROM CS_PARAMETER_PPJB WHERE NAMA_PT = '$nama_pt' AND NAMA_DEP = '$nama_dep' AND NAMA_PEJABAT = '$nama_pejabat' AND NAMA_JABATAN = '$nama_jabatan'
		AND UNIT = '$unit' AND KOTA = '$kota'  ";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");

		$query = " UPDATE CS_PARAMETER_PPJB
		SET 
		NAMA_PT='$nama_pt',
		NAMA_DEP='$nama_dep',
		NAMA_PEJABAT='$nama_pejabat',
		NAMA_JABATAN='$nama_jabatan', 
		UNIT='$unit',
		KOTA='$kota'
		";

		ex_false($conn->Execute($query), $query);
		
		$msg = 'Data Parameter berhasil diubah.';
		}

		else if ($act == 'save2') # Proses Ubah Nomor & Register
		{
		//ex_ha('JB12', 'U');

		$query = "SELECT * FROM CS_PARAMETER_PPJB WHERE PEJABAT_PPJB = '$pejabat_ppjb' and JABATAN_PPJB = '$jabatan_ppjb' AND NOMOR_SK = '$nomor_sk' AND TANGGAL_SK = CONVERT(DATETIME,'$tanggal_sk',105) AND NOMOR_PPJB = '$nomor_ppjb'
		AND REG_PPJB = '$reg_ppjb'  AND JUMLAH_HARI = '$jumlah_hari' AND NOMOR_PPJB_PH = '$nomor_ppjb_ph' AND REG_PPJB_PH = '$reg_ppjb_ph' ";
		ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");

		$query = " UPDATE CS_PARAMETER_PPJB
				SET
				PEJABAT_PPJB = '$pejabat_ppjb', 
				JABATAN_PPJB = '$jabatan_ppjb', 
				NOMOR_SK = '$nomor_sk', 
				TANGGAL_SK = CONVERT(DATETIME,'$tanggal_sk',105), 
				NOMOR_PPJB = '$nomor_ppjb', 
				REG_PPJB = '$reg_ppjb', 
				JUMLAH_HARI = '$jumlah_hari', 
				NOMOR_PPJB_PH = '$nomor_ppjb_ph', 
				REG_PPJB_PH = '$reg_ppjb_ph'";

		ex_false($conn->Execute($query), $query);
			
		$msg = 'Data Parameter berhasil diubah.';
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
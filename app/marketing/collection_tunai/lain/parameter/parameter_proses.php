<?php
require_once('../../../../../config/config.php');

$msg = '';
$error = FALSE;
		
		$nama_pt				= (isset($_REQUEST['nama_pt'])) ? clean($_REQUEST['nama_pt']) : '';
		$nama_dep				= (isset($_REQUEST['nama_dep'])) ? clean($_REQUEST['nama_dep']) : '';
		$nama_pejabat			= (isset($_REQUEST['nama_pejabat'])) ? clean($_REQUEST['nama_pejabat']) : '';
		$nama_jabatan			= (isset($_REQUEST['nama_jabatan'])) ? clean($_REQUEST['nama_jabatan']) : '';
		$pemb_jatuh_tempo		= (isset($_REQUEST['pemb_jatuh_tempo'])) ? clean($_REQUEST['pemb_jatuh_tempo']) : '';
		$somasi_satu			= (isset($_REQUEST['somasi_satu'])) ? clean($_REQUEST['somasi_satu']) : '';
		$somasi_dua				= (isset($_REQUEST['somasi_dua'])) ? clean($_REQUEST['somasi_dua']) : '';
		$somasi_tiga			= (isset($_REQUEST['somasi_tiga'])) ? clean($_REQUEST['somasi_tiga']) : '';
		$wanprestasi			= (isset($_REQUEST['wanprestasi'])) ? clean($_REQUEST['wanprestasi']) : '';
		$undangan_pembatalan	= (isset($_REQUEST['undangan_pembatalan'])) ? clean($_REQUEST['undangan_pembatalan']) : '';
		$tanggal_efektif_prog	= (isset($_REQUEST['tanggal_efektif_prog'])) ? clean($_REQUEST['tanggal_efektif_prog']) : '';
		$nilai_sisa_tagihan		= (isset($_REQUEST['nilai_sisa_tagihan'])) ? to_number($_REQUEST['nilai_sisa_tagihan']) : '';
		$masa_berlaku_denda		= (isset($_REQUEST['masa_berlaku_denda'])) ? clean($_REQUEST['masa_berlaku_denda']) : '';
		
		$no_surat_akhir_tunai	= (isset($_REQUEST['no_surat_akhir_tunai'])) ? clean($_REQUEST['no_surat_akhir_tunai']) : '';
		$registrasi_tunai		= (isset($_REQUEST['registrasi_tunai'])) ? clean($_REQUEST['registrasi_tunai']) : '';
		$no_surat_akhir_kpr		= (isset($_REQUEST['no_surat_akhir_kpr'])) ? clean($_REQUEST['no_surat_akhir_kpr']) : '';
		$registrasi_kpr			= (isset($_REQUEST['registrasi_kpr'])) ? clean($_REQUEST['registrasi_kpr']) : '';

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

		if ($act == 'save1')
		{
		$query = "SELECT * FROM CS_PARAMETER_COL WHERE NAMA_PT = '$nama_pt' AND NAMA_DEP = '$nama_dep' AND NAMA_PEJABAT = '$nama_pejabat' AND NAMA_JABATAN = '$nama_jabatan'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");

		$query = " UPDATE CS_PARAMETER_COL
		SET 
		NAMA_PT='$nama_pt',
		NAMA_DEP='$nama_dep',
		NAMA_PEJABAT='$nama_pejabat',
		NAMA_JABATAN='$nama_jabatan'
		";
		ex_false($conn->Execute($query), $query);
		
		$msg = 'Data Parameter berhasil diubah.';
		}
		else if ($act == 'save2') 
		{
			$query = "SELECT * FROM CS_PARAMETER_COL WHERE PEMB_JATUH_TEMPO = '$pemb_jatuh_tempo' AND SOMASI_SATU = '$somasi_satu' AND SOMASI_DUA = '$somasi_dua' AND SOMASI_TIGA = '$somasi_tiga' AND WANPRESTASI = '$wanprestasi'AND UNDANGAN_PEMBATALAN = '$undangan_pembatalan'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");

		$query = "UPDATE CS_PARAMETER_COL
		SET 
		PEMB_JATUH_TEMPO = '$pemb_jatuh_tempo',
		SOMASI_SATU='$somasi_satu',
		SOMASI_DUA='$somasi_dua',
		SOMASI_TIGA='$somasi_tiga',
		WANPRESTASI='$wanprestasi',
		UNDANGAN_PEMBATALAN='$undangan_pembatalan'
		";
		ex_false($conn->Execute($query), $query);
		
		$msg = 'Data Parameter berhasil diubah.';
		}
		else if ($act == 'save3') 
		{
			$query = "SELECT * FROM CS_PARAMETER_COL WHERE TANGGAL_EFEKTIF_PROG = CONVERT(DATETIME,'$tanggal_efektif_prog',105) AND NILAI_SISA_TAGIHAN = '$nilai_sisa_tagihan' AND MASA_BERLAKU_DENDA = '$masa_berlaku_denda'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");

		$query = " UPDATE CS_PARAMETER_COL
		SET 
		TANGGAL_EFEKTIF_PROG = CONVERT(DATETIME,'$tanggal_efektif_prog',105),
		NILAI_SISA_TAGIHAN='$nilai_sisa_tagihan',
		MASA_BERLAKU_DENDA='$masa_berlaku_denda'
		";
		ex_false($conn->Execute($query), $query);
		
		$msg = 'Data Parameter berhasil diubah.';
		}

		else if ($act == 'save4') 
		{
			$query = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE WHERE NOMOR_SURAT_TUNAI = '$no_surat_akhir_tunai' AND REG_SURAT_TUNAI = '$registrasi_tunai' AND NOMOR_SURAT_KPR = '$no_surat_akhir_kpr' AND REG_SURAT_KPR = '$registrasi_kpr'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");

		$query = " UPDATE CS_REGISTER_CUSTOMER_SERVICE
		SET 
		NOMOR_SURAT_TUNAI = '$no_surat_akhir_tunai',
		REG_SURAT_TUNAI	='$registrasi_tunai',
		NOMOR_SURAT_KPR ='$no_surat_akhir_kpr',
		REG_SURAT_KPR ='$registrasi_kpr'
		";
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
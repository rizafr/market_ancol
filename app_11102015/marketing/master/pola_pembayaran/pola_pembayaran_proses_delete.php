<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_pola_bayar 	= (isset($_REQUEST['kode_pola_bayar'])) ? to_number($_REQUEST['kode_pola_bayar']) : '';
$kode_jenis 		= (isset($_REQUEST['kode_jenis'])) ? clean($_REQUEST['kode_jenis']) : '';
$nama_pola_bayar 	= (isset($_REQUEST['nama_pola_bayar'])) ? clean($_REQUEST['nama_pola_bayar']) : '';
$nilai1			 	= (isset($_REQUEST['nilai1'])) ? clean($_REQUEST['nilai1']) : 0;
$kali1			 	= (isset($_REQUEST['kali1'])) ? clean($_REQUEST['kali1']) : 0;
$nilai2			 	= (isset($_REQUEST['nilai2'])) ? clean($_REQUEST['nilai2']) : 0;
$kali2			 	= (isset($_REQUEST['kali2'])) ? clean($_REQUEST['kali2']) : 0;
$nilai3			 	= (isset($_REQUEST['nilai3'])) ? clean($_REQUEST['nilai3']) : 0;
$kali3			 	= (isset($_REQUEST['kali3'])) ? clean($_REQUEST['kali3']) : 0;
$nilai4			 	= (isset($_REQUEST['nilai4'])) ? clean($_REQUEST['nilai4']) : 0;
$kali4			 	= (isset($_REQUEST['kali4'])) ? clean($_REQUEST['kali4']) : 0;
$nilai5			 	= (isset($_REQUEST['nilai5'])) ? clean($_REQUEST['nilai5']) : 0;
$kali5			 	= (isset($_REQUEST['kali5'])) ? clean($_REQUEST['kali5']) : 0;
$nilai_jenis	 	= (isset($_REQUEST['nilai_jenis'])) ? clean($_REQUEST['nilai_jenis']) : 0;
$non2			 	= (isset($_REQUEST['non2'])) ? clean($_REQUEST['non2']) : '';
$non3			 	= (isset($_REQUEST['non3'])) ? clean($_REQUEST['non3']) : '';
$non4			 	= (isset($_REQUEST['non4'])) ? clean($_REQUEST['non4']) : '';
$non5			 	= (isset($_REQUEST['non5'])) ? clean($_REQUEST['non5']) : '';
$non_jenis		 	= (isset($_REQUEST['non_jenis'])) ? clean($_REQUEST['non_jenis']) : '';


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M12');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			//ex_ha('M12', 'U');
			ex_empty($nama_pola_bayar, 'Nama pola pembayaran harus diisi.');
			ex_empty($nilai1, 'Rumus pola pembayaran harus diisi.');
			ex_empty($kali1, 'Rumus pola pembayaran harus diisi.');
			
			$query = "
			DELETE FROM POLA_BAYAR
			WHERE
				KODE_POLA_BAYAR = $kode_pola_bayar
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data pola pembayaran berhasil dihapus.';
		
		$conn->committrans(); 
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		if ($conn) { $conn->rollbacktrans(); } 
	}

	close($conn);
	$json = array( 'error'=> $error, 'msg' => $msg);
	echo json_encode($json);
	exit;
}

die_login();
die_app('M');
die_mod('M12');
$conn = conn($sess_db);
die_conn($conn);
	
?>
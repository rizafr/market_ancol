<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_tipe = (isset($_REQUEST['kode_tipe'])) ? to_number($_REQUEST['kode_tipe']) : '';
$tipe_bangunan= (isset($_REQUEST['tipe_bangunan'])) ? clean($_REQUEST['tipe_bangunan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M04');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M04', 'I');
			
			ex_empty($kode_tipe, 'Kode tipe bangunan harus diisi.');
			ex_empty($tipe_bangunan, 'Nama tipe bangunan harus diisi.');
		
			$query = "SELECT COUNT(KODE_TIPE) AS TOTAL FROM TIPE WHERE KODE_TIPE = '$kode_tipe'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode tipe \"$kode_tipe\" telah terdaftar.");
			
			$query = "SELECT COUNT(TIPE_BANGUNAN) AS TOTAL FROM TIPE WHERE TIPE_BANGUNAN = '$tipe_bangunan'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Nama tipe \"$tipe_bangunan\" telah terdaftar.");
			
			$query = "INSERT INTO TIPE (KODE_TIPE, TIPE_BANGUNAN, DAYA_LISTRIK, MASA_BANGUN)
			VALUES('$kode_tipe', '$tipe_bangunan', '0', '0')";
			ex_false($conn->Execute($query), $query);
					
			$msg = "Data Tipe berhasil ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M04', 'U');
			
			ex_empty($kode_tipe, 'Kode tipe harus diisi.');
			ex_empty($tipe_bangunan, 'Nama tipe bangunan harus diisi.');
			
			if ($kode_tipe != $id)
			{
				$query = "SELECT COUNT(KODE_TIPE) AS TOTAL FROM TIPE WHERE KODE_TIPE = '$kode_tipe'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode tipe \"$kode_tipe\" telah terdaftar.");
			}
				$obj = $conn->Execute("SELECT * FROM TIPE WHERE KODE_TIPE = '$id'");
					$nm	= $obj->fields['TIPE_BANGUNAN'];
					
			if ($kode_tipe != $nm)
			{
				$query = "SELECT COUNT(TIPE_BANGUNAN) AS TOTAL FROM TIPE WHERE TIPE_BANGUNAN = '$tipe_bangunan'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Nama tipe \"$tipe_bangunan\" telah terdaftar.");
			}
					
			$query = "SELECT * FROM TIPE WHERE KODE_TIPE = '$kode_tipe' AND TIPE_BANGUNAN = '$tipe_bangunan'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
					
			$query = "
			UPDATE TIPE
			SET KODE_TIPE = '$kode_tipe',
				TIPE_BANGUNAN = '$tipe_bangunan'
			WHERE
				KODE_TIPE = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Tipe berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{

				$querySrc= "SELECT COUNT(KODE_TIPE) AS TOTAL FROM stok WHERE KODE_TIPE ='$id_del'";
				ex_found($conn->Execute($querySrc)->fields['TOTAL'], "Kode Tipe \'$id_del\' telah terdaftar.");

				echo "querySrc";

				$query = "DELETE FROM TIPE WHERE KODE_TIPE = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Tipe Bangunan berhasil dihapus.';
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
die_app('M');
die_mod('M04');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_TIPE) AS MAX_KODE FROM TIPE");
	$kode_tipe	= 1 + $obj->fields['MAX_KODE'];
}

if ($act == 'Ubah')
{
	$obj = $conn->Execute("SELECT * FROM TIPE WHERE KODE_TIPE = '$id'");
	$kode_tipe	= $obj->fields['KODE_TIPE'];
	$tipe_bangunan	= $obj->fields['TIPE_BANGUNAN'];
}
?>
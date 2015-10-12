<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_kecamatan	= (isset($_REQUEST['kode_kecamatan'])) ? clean($_REQUEST['kode_kecamatan']) : '';
$nama_kecamatan	= (isset($_REQUEST['nama_kecamatan'])) ? clean($_REQUEST['nama_kecamatan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('P');
		ex_mod('P02');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('JB02', 'I');
			
			ex_empty($kode_kecamatan, 'Kode harus diisi.');
			ex_empty($nama_kecamatan, 'Nama harus diisi.');
			
			$query = "SELECT KODE_KECAMATAN FROM KECAMATAN WHERE KODE_KECAMATAN = '$kode_kecamatan'";
			ex_found($conn->Execute($query)->recordcount(), "Kode \"$kode_kecamatan\" telah terdaftar.");
			
			$query = "INSERT INTO KECAMATAN (KODE_KECAMATAN, NAMA_KECAMATAN)
			VALUES(
				'$kode_kecamatan',
				'$nama_kecamatan'
			)";
			ex_false($conn->execute($query), $query);
			
			$msg = "Data Kecamatan \"$nama_kecamatan\" berhasil disimpan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('JB02', 'U');
			
			ex_empty($kode_kecamatan, 'Kode harus diisi.');
			ex_empty($nama_kecamatan, 'Nama harus diisi.');
			
			if ($kode_kecamatan != $id)
			{
				$query = "SELECT KODE_KECAMATAN FROM KECAMATAN WHERE KODE_KECAMATAN = '$kode_kecamatan'";
				ex_found($conn->Execute($query)->recordcount(), "Kode \"$kode_kecamatan\" telah terdaftar.");
			}
			
			$query = "
			UPDATE KECAMATAN 
			SET 
				KODE_KECAMATAN = '$kode_kecamatan',
				NAMA_KECAMATAN = '$nama_kecamatan'
			WHERE
				KODE_KECAMATAN = '$id'
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data Kecamatan berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('JB02', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "DELETE FROM KECAMATAN WHERE KODE_KECAMATAN = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Kecamatan berhasil dihapus.';
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
die_mod('P02');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_KECAMATAN) AS MAX_KODE FROM KECAMATAN");
	$kode_kecamatan	= 1 + $obj->fields['MAX_KODE'];
}

if ($act == 'Ubah')
{
	$query = "SELECT * FROM KECAMATAN WHERE KODE_KECAMATAN = '$id'";
	$obj = $conn->execute($query);
	$kode_kecamatan = $obj->fields['KODE_KECAMATAN'];
	$nama_kecamatan = $obj->fields['NAMA_KECAMATAN'];
}
?>
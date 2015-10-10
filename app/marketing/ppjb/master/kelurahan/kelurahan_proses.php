<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_kelurahan	= (isset($_REQUEST['kode_kelurahan'])) ? clean($_REQUEST['kode_kelurahan']) : '';
$nama_kelurahan	= (isset($_REQUEST['nama_kelurahan'])) ? clean($_REQUEST['nama_kelurahan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('P');
		ex_mod('P01');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('JB01', 'I');
			
			ex_empty($kode_kelurahan, 'Kode harus diisi.');
			ex_empty($nama_kelurahan, 'Nama harus diisi.');
			
			$query = "SELECT KODE_KELURAHAN FROM KELURAHAN WHERE KODE_KELURAHAN = '$kode_kelurahan'";
			ex_found($conn->Execute($query)->recordcount(), "Kode \"$kode_kelurahan\" telah terdaftar.");
			
			$query = "INSERT INTO KELURAHAN (KODE_KELURAHAN, NAMA_KELURAHAN)
			VALUES(
				'$kode_kelurahan',
				'$nama_kelurahan'
			)";
			ex_false($conn->execute($query), $query);
						
			$msg = "Data Kelurahan \"$nama_kelurahan\" berhasil disimpan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('JB01', 'U');
			
			ex_empty($kode_kelurahan, 'Kode harus diisi.');
			ex_empty($nama_kelurahan, 'Nama harus diisi.');
			
			if ($kode_kelurahan != $id)
			{
				$query = "SELECT KODE_KELURAHAN FROM KELURAHAN WHERE KODE_KELURAHAN = '$kode_kelurahan'";
				ex_found($conn->Execute($query)->recordcount(), "Kode \"$kode_kelurahan\" telah terdaftar.");
			}
			
			$query = "
			UPDATE KELURAHAN 
			SET 
				KODE_KELURAHAN = '$kode_kelurahan',
				NAMA_KELURAHAN = '$nama_kelurahan'
			WHERE
				KODE_KELURAHAN = '$id'
			";			
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data Kelurahan berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('JB01', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "DELETE FROM KELURAHAN WHERE KODE_KELURAHAN = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Kelurahan berhasil dihapus.';
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
die_mod('P01');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_KELURAHAN) AS MAX_KODE FROM KELURAHAN");
	$kode_kelurahan	= 1 + $obj->fields['MAX_KODE'];
}

if ($act == 'Ubah')
{
	$query = "SELECT * FROM KELURAHAN WHERE KODE_KELURAHAN = '$id'";
	$obj = $conn->execute($query);
	$kode_kelurahan = $obj->fields['KODE_KELURAHAN'];
	$nama_kelurahan = $obj->fields['NAMA_KELURAHAN'];
}

?>
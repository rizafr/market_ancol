<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_jenis	= (isset($_REQUEST['kode_jenis'])) ? clean($_REQUEST['kode_jenis']) : '';
$nama_jenis	= (isset($_REQUEST['nama_jenis'])) ? clean($_REQUEST['nama_jenis']) : '';
$nama_file	= (isset($_REQUEST['nama_file'])) ? clean($_REQUEST['nama_file']) : '';


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('P');
		ex_mod('P04');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('JB04', 'I');
			
			ex_empty($kode_jenis, 'Kode harus diisi.');
			ex_empty($nama_jenis, 'Jenis Addendum PPJB harus diisi.');
			ex_empty($nama_file, 'Nama File harus diisi.');
			
			$query = "SELECT KODE_JENIS FROM CS_JENIS_PPJB_ADDENDUM WHERE KODE_JENIS = '$kode_jenis'";
			ex_found($conn->Execute($query)->recordcount(), "Kode \"$kode_jenis\" telah terdaftar.");
			
			$query = "INSERT INTO CS_JENIS_PPJB_ADDENDUM (KODE_JENIS, NAMA_JENIS, NAMA_FILE)
			VALUES(
				'$kode_jenis',
				'$nama_jenis',
				'$nama_file'
			)";
			ex_false($conn->execute($query), $query);
			
			$msg = "Data Jenis Addendum PPJB  \"$nama_jenis\" berhasil disimpan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('JB04', 'U');
			
			ex_empty($kode_jenis, 'Kode harus diisi.');
			ex_empty($nama_jenis, 'Jenis Addendum PPJB harus diisi.');
			ex_empty($nama_file, 'Nama File harus diisi.');
			
			if ($kode_jenis != $id)
			{
				$query = "SELECT KODE_JENIS FROM CS_JENIS_PPJB_ADDENDUM WHERE KODE_JENIS = '$kode_jenis'";
				ex_found($conn->Execute($query)->recordcount(), "Kode \"$kode_jenis\" telah terdaftar.");
			}
				$query = "SELECT * FROM CS_JENIS_PPJB_ADDENDUM WHERE KODE_JENIS = '$kode_jenis' AND NAMA_JENIS = '$nama_jenis' AND NAMA_FILE = '$nama_file'";
				ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE CS_JENIS_PPJB_ADDENDUM 
			SET 
				KODE_JENIS = '$kode_jenis',
				NAMA_JENIS = '$nama_jenis',
				NAMA_FILE  = '$nama_file'
			WHERE
				KODE_JENIS = '$id'
			";			
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data Jenis Addendum PPJB berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('JB04', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "DELETE FROM CS_JENIS_PPJB_ADDENDUM WHERE KODE_JENIS = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Jenis Addendum PPJB berhasil dihapus.';
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
die_mod('P04');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$query = "SELECT * FROM CS_JENIS_PPJB_ADDENDUM WHERE KODE_JENIS = '$id'";
	$obj = $conn->execute($query);
	$kode_jenis = $obj->fields['KODE_JENIS'];
	$nama_jenis = $obj->fields['NAMA_JENIS'];
	$nama_file  = $obj->fields['NAMA_FILE'];
}
?>
<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_lokasi = (isset($_REQUEST['kode_lokasi'])) ? to_number($_REQUEST['kode_lokasi']) : '';
$lokasi = (isset($_REQUEST['lokasi'])) ? clean($_REQUEST['lokasi']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M01');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M01', 'I');
			
			ex_empty($kode_lokasi, 'Kode lokasi harus diisi.');
			ex_empty($lokasi, 'Nama lokasi harus diisi.');
		
			$query = "SELECT COUNT(KODE_LOKASI) AS TOTAL FROM LOKASI WHERE KODE_LOKASI = '$kode_lokasi'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode lokasi \"$kode_lokasi\" telah terdaftar.");
			
			$query = "SELECT COUNT(LOKASI) AS TOTAL FROM LOKASI WHERE LOKASI = '$lokasi'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Nama lokasi \"$lokasi\" telah terdaftar.");
			
			$query = "INSERT INTO LOKASI (KODE_LOKASI, LOKASI)
			VALUES('$kode_lokasi', '$lokasi')";
			ex_false($conn->Execute($query), $query);
					
			$msg = "Data Lokasi \"$lokasi\" berhasil ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M01', 'U');
			
			ex_empty($kode_lokasi, 'Kode lokasi harus diisi.');
			ex_empty($lokasi, 'Nama lokasi harus diisi.');
			
			if ($kode_lokasi != $id)
			{
				$query = "SELECT COUNT(KODE_LOKASI) AS TOTAL FROM LOKASI WHERE KODE_LOKASI = '$kode_lokasi'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode lokasi \"$kode_lokasi\" telah terdaftar.");
			}
			
			$obj = $conn->Execute("SELECT * FROM LOKASI WHERE KODE_LOKASI = '$id'");
				$nm	= $obj->fields['LOKASI'];
				
			if ($lokasi != $nm)
			{
				$query = "SELECT COUNT(LOKASI) AS TOTAL FROM LOKASI WHERE LOKASI = '$lokasi'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Nama lokasi \"$lokasi\" telah terdaftar.");
			}
			
			$query = "SELECT * FROM LOKASI WHERE KODE_LOKASI = '$kode_lokasi' AND LOKASI = '$lokasi'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE LOKASI 
			SET KODE_LOKASI = '$kode_lokasi',
				LOKASI = '$lokasi'
			WHERE
				KODE_LOKASI = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Lokasi berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$querySrc= "SELECT COUNT(KODE_Lokasi) AS TOTAL FROM stok WHERE KODE_LOKASI ='$id_del'";
				ex_found($conn->Execute($querySrc)->fields['TOTAL'], "Kode Lokasi \'$id_del\' telah terdaftar.");

				echo "querySrc";

				$query = "DELETE FROM LOKASI WHERE KODE_LOKASI = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Lokasi berhasil dihapus.';
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
die_mod('M01');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_LOKASI) AS MAX_KODE FROM LOKASI");
	$kode_lokasi	= 1 + $obj->fields['MAX_KODE'];

}

if ($act == 'Ubah')
{
	$obj = $conn->Execute("SELECT * FROM LOKASI WHERE KODE_LOKASI = '$id'");
	$kode_lokasi	= $obj->fields['KODE_LOKASI'];
	$lokasi	= $obj->fields['LOKASI'];
}
?>
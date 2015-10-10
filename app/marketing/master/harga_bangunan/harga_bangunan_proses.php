<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_sk = (isset($_REQUEST['kode_sk'])) ? to_number($_REQUEST['kode_sk']) : '';
$kode_lokasi = (isset($_REQUEST['kode_lokasi'])) ? to_number($_REQUEST['kode_lokasi']) : '';
$jenis_bangunan = (isset($_REQUEST['jenis_bangunan'])) ? to_number($_REQUEST['jenis_bangunan']) : '';
$harga_bangunan = (isset($_REQUEST['harga_bangunan'])) ? to_number($_REQUEST['harga_bangunan']) : '';
$status = (isset($_REQUEST['status'])) ? to_number($_REQUEST['status']) : '0';
$tanggal = (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M10');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M10', 'I');
			
			ex_empty($kode_sk, 'Kode sk harus diisi.');
			ex_empty($kode_lokasi, 'Kode lokasi harus diisi.');
			ex_empty($jenis_bangunan, 'Pilih jenis bangunan.');
			ex_empty($harga_bangunan, 'Harga bangunan > 0');
			ex_empty($tanggal, 'Pilih tanggal.');
		
			$query = "SELECT COUNT(KODE_SK) AS TOTAL FROM HARGA_BANGUNAN WHERE KODE_SK = '$kode_sk'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode sk \"$kode_sk\" telah terdaftar.");
			
			$query = "INSERT INTO HARGA_BANGUNAN (KODE_SK, KODE_LOKASI, JENIS_BANGUNAN, HARGA_BANGUNAN, TANGGAL, STATUS) VALUES 
			(
				'$kode_sk',
				'$kode_lokasi',
				'$jenis_bangunan',
				'$harga_bangunan',
				CONVERT(DATETIME,'$tanggal',105),
				'$status'
			)";
			ex_false($conn->Execute($query), $query);
			
			$msg = "Data harga bangunan berhasil ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M10', 'U');
			
			ex_empty($kode_sk, 'Kode sk harus diisi.');
			ex_empty($kode_lokasi, 'Kode lokasi harus diisi.');
			ex_empty($jenis_bangunan, 'Pilih jenis bangunan.');
			ex_empty($harga_bangunan, 1, 'Harga bangunan > 0');
			ex_empty($tanggal, 'Pilih tanggal.');
			
			if ($kode_sk != $id)
			{
				$query = "SELECT COUNT(KODE_SK) AS TOTAL FROM HARGA_BANGUNAN WHERE KODE_SK = '$kode_sk'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode sk \"$kode_sk\" telah terdaftar.");
			}
			
			$query = "SELECT * FROM HARGA_BANGUNAN WHERE KODE_SK = '$kode_sk' AND KODE_LOKASI = '$kode_lokasi' AND JENIS_BANGUNAN = '$jenis_bangunan' AND HARGA_BANGUNAN = '$harga_bangunan' AND TANGGAL = CONVERT(DATETIME,'$tanggal',105) AND STATUS = '$status'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
					
			$query = "
			UPDATE HARGA_BANGUNAN 
			SET KODE_SK = '$kode_sk',
				KODE_LOKASI = '$kode_lokasi',
				JENIS_BANGUNAN = '$jenis_bangunan',
				HARGA_BANGUNAN = '$harga_bangunan',
				TANGGAL = CONVERT(DATETIME,'$tanggal',105),
				STATUS = '$status'
			WHERE
				KODE_SK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data harga bangunan berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{

				$querySrc= "SELECT COUNT(KODE_SK_BANGUNAN) AS TOTAL FROM stok WHERE KODE_SK_BANGUNAN ='$id_del'";
				ex_found($conn->Execute($querySrc)->fields['TOTAL'], "Kode Lokasi \'$id_del\' telah terdaftar.");

				echo "querySrc";

				$query = "DELETE FROM HARGA_BANGUNAN WHERE KODE_SK = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data harga bangunan berhasil dihapus.';
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
die_mod('M10');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_SK) AS MAX_KODE FROM HARGA_BANGUNAN");
	$kode_sk	= 1 + $obj->fields['MAX_KODE'];
}

	
if ($act == 'Ubah')
{
	$obj = $conn->Execute("
	SELECT *
		
	FROM HARGA_BANGUNAN
	WHERE KODE_SK = '$id'
	");
	$kode_sk = $obj->fields['KODE_SK'];
	$kode_lokasi = $obj->fields['KODE_LOKASI'];
	$jenis_bangunan = $obj->fields['JENIS_BANGUNAN'];
	$harga_bangunan = $obj->fields['HARGA_BANGUNAN'];
	$tanggal = tgltgl(date("d-m-Y",strtotime($obj->fields['TANGGAL'])));
	$status = $obj->fields['STATUS'];
}
?>
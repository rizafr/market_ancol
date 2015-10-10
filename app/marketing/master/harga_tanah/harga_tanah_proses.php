<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_sk = (isset($_REQUEST['kode_sk'])) ? to_number($_REQUEST['kode_sk']) : '';
$kode_lokasi = (isset($_REQUEST['kode_lokasi'])) ? to_number($_REQUEST['kode_lokasi']) : '';
$harga_tanah = (isset($_REQUEST['harga_tanah'])) ? to_number($_REQUEST['harga_tanah']) : '';
$tanggal = (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$status = (isset($_REQUEST['status'])) ? to_number($_REQUEST['status']) : '0';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M09');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M09', 'I');
			
			ex_empty($kode_sk, 'Kode SK harus diisi.');
			ex_empty($kode_lokasi, 'Lokasi harus diisi.');
			ex_empty($harga_tanah, 'Harga tanah tidak boleh 0');
			ex_empty($tanggal, 'Pilih tanggal.');
		
			$query = "SELECT COUNT(KODE_SK) AS TOTAL FROM HARGA_TANAH WHERE KODE_SK = '$kode_sk'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode sk \"$kode_sk\" telah terdaftar.");
			
			$query = "INSERT INTO HARGA_TANAH (KODE_SK, KODE_LOKASI, HARGA_TANAH, TANGGAL, STATUS) VALUES 
			(
				'$kode_sk',
				'$kode_lokasi',
				'$harga_tanah',
				CONVERT(DATETIME,'$tanggal',105),
				'$status'
			)";
			ex_false($conn->Execute($query), $query);
					
			$msg = "Harga tanah \"$kode_sk\" berhasil ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M09', 'U');
			
			ex_empty($kode_sk, 'Kode SK harus diisi.');
			ex_empty($kode_lokasi, 'Lokasi harus diisi.');
			ex_empty($harga_tanah, 'Harga tanah tidak boleh 0');
			ex_empty($tanggal, 'Pilih tanggal.');
			
			if ($kode_sk != $id)
			{
				$query = "SELECT COUNT(KODE_SK) AS TOTAL FROM HARGA_TANAH WHERE KODE_SK = '$kode_sk'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode sk \"$kode_sk\" telah terdaftar.");
			}
			//
			$query = "SELECT * FROM HARGA_TANAH WHERE KODE_SK = '$kode_sk' AND KODE_LOKASI = '$kode_lokasi' AND HARGA_TANAH = '$harga_tanah' AND TANGGAL = CONVERT(DATETIME,'$tanggal',105) AND STATUS = '$status'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
					
			$query = "
			UPDATE HARGA_TANAH 
			SET KODE_SK = '$kode_sk',
				KODE_LOKASI = '$kode_lokasi',
				HARGA_TANAH = '$harga_tanah',
				TANGGAL = CONVERT(DATETIME,'$tanggal',105),
				STATUS = '$status'
			WHERE
				KODE_SK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data harga tanah berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{

				$querySrc= "SELECT COUNT(KODE_SK_TANAH) AS TOTAL FROM stok WHERE KODE_SK_TANAH ='$id_del'";
				ex_found($conn->Execute($querySrc)->fields['TOTAL'], "Harga Tanah \'$id_del\' telah terdaftar.");

				echo "querySrc";

				$query = "DELETE FROM HARGA_TANAH WHERE KODE_SK = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Harga Tanah berhasil dihapus.';
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
die_mod('M09');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_SK) AS MAX_KODE FROM HARGA_TANAH");
	$kode_sk	= 1 + $obj->fields['MAX_KODE'];
}

if ($act == 'Ubah')
{
	$obj = $conn->Execute("
	SELECT *
	FROM HARGA_TANAH 
	WHERE KODE_SK = '$id'
	");
	$kode_sk = $obj->fields['KODE_SK'];
	$kode_lokasi = $obj->fields['KODE_LOKASI'];
	$harga_tanah = $obj->fields['HARGA_TANAH'];
	$tanggal = tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));	
	$status = $obj->fields['STATUS'];
}
?>
<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$blok = (isset($_REQUEST['blok'])) ? clean($_REQUEST['blok']) : '';

$kode_sk = (isset($_REQUEST['kode_sk'])) ? clean($_REQUEST['kode_sk']) : '';
$kode_blok = (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$cash_keras = (isset($_REQUEST['cash_keras'])) ? bigintval($_REQUEST['cash_keras']) : '';
$cb36x = (isset($_REQUEST['cb36x'])) ? bigintval($_REQUEST['cb36x']) : '';
$cb48x = (isset($_REQUEST['cb48x'])) ? bigintval($_REQUEST['cb48x']) : '';
$kpa24x = (isset($_REQUEST['kpa24x'])) ? bigintval($_REQUEST['kpa24x']) : '';
$kpa36x = (isset($_REQUEST['kpa36x'])) ? bigintval($_REQUEST['kpa36x']) : '';
$tanggal = (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$status = (isset($_REQUEST['status'])) ? to_number($_REQUEST['status']) : '0';
$cb_data = $_REQUEST['cb_data'];

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
			
			// ex_empty($kode_sk, 'Kode sk harus diisi.');
			// ex_empty($kode_lokasi, 'Kode lokasi harus diisi.');
			// ex_empty($jenis_bangunan, 'Pilih jenis bangunan.');
			// ex_empty($harga_bangunan, 'Harga bangunan > 0');
			// ex_empty($tanggal, 'Pilih tanggal.');
			
			$query = "SELECT COUNT(KODE_SK) AS TOTAL FROM HARGA_SK WHERE KODE_SK = '$kode_sk'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode sk \"$kode_sk\" telah terdaftar.");
			
			$query = "INSERT INTO HARGA_SK (KODE_SK, KODE_BLOK, CASH_KERAS, CB36X, CB48X, KPA24X, KPA36X, TANGGAL, STATUS) VALUES 
			(
				'$kode_sk',
				'$kode_blok',
				$cash_keras,
				$cb36x,
				$cb48x,
				$kpa24x,
				$kpa36x,
				CONVERT(DATETIME,'$tanggal',105),
				'$status'
				)";
ex_false($conn->Execute($query), $query);

$msg = "Data harga sk berhasil ditambahkan.";
}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M10', 'U');
			
			ex_empty($kode_sk, 'Kode sk harus diisi.');
			ex_empty($kode_blok, 'Kode blok harus diisi.');
			ex_empty($cash_keras, 1, 'Harga Cash Keras > 0');
			ex_empty($tanggal, 'Pilih tanggal.');
			
			if ($kode_sk != $id)
			{
				$query = "SELECT COUNT(KODE_SK) AS TOTAL FROM HARGA_SK WHERE KODE_SK = '$kode_sk' AND KODE_BLOK='$kode_blok'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode sk \"$kode_sk\" telah terdaftar.");
			}
			
			$query = "SELECT * FROM HARGA_SK WHERE KODE_SK = '$kode_sk' AND kode_blok = '$kode_blok' AND CASH_KERAS = '$cash_keras' AND CB48X = '$cb48x' AND CB36X = '$cb36x' AND KPA36X = '$kpa36x' AND KPA24X = '$kpa24x' AND TANGGAL = CONVERT(DATETIME,'$tanggal',105) AND STATUS = '$status'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE HARGA_SK 
			SET 
				CASH_KERAS = '$cash_keras',
				CB36X = '$cb36x',
				CB48X = '$cb48x',
				KPA24X = '$kpa24x',
				KPA36X = '$kpa36x',
				TANGGAL = CONVERT(DATETIME,'$tanggal',105),
				STATUS = '$status'
			WHERE
				KODE_SK = '$id' 
				AND KODE_BLOK = '$kode_blok'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data harga sk berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			// $cb_data = $_REQUEST['cb_data'];
			// ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$querySrc= "SELECT COUNT(KODE_SK) AS TOTAL FROM stok WHERE KODE_SK ='$id_del'";
				ex_found($conn->Execute($querySrc)->fields['TOTAL'], "Kode SK \'$id_del\' telah terdaftar di stok.");

				echo "querySrc";

				$query = "DELETE FROM HARGA_SK WHERE KODE_SK = '$id_del' AND KODE_BLOK='$kode_blok'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data harga sk berhasil dihapus.';
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

// if ($act == 'Tambah')
// {
	// $obj = $conn->Execute("SELECT MAX(KODE_SK) AS MAX_KODE FROM HARGA_BANGUNAN");
	// $kode_sk	= 1 + $obj->fields['MAX_KODE'];
// }


if ($act == 'Ubah')
{
	$obj = $conn->Execute("
	SELECT
		hb.KODE_SK,
		hb.KODE_BLOK,
		hb.TANGGAL,
		hb.CASH_KERAS,
		hb.CB36X,
		hb.CB48X,
		hb.KPA24X,
		hb.KPA36X,
		hb.STATUS		
	FROM 
		HARGA_SK hb
	WHERE hb.KODE_SK = '$id' AND hb.KODE_BLOK='$blok'
		");
	$kode_sk = $id;
	$kode_blok = $obj->fields['KODE_BLOK'];
	$cash_keras = $obj->fields['CASH_KERAS'];
	$cb36x = $obj->fields['CB36X'];
	$cb48x = $obj->fields['CB48X'];
	$kpa24x = $obj->fields['KPA24X'];
	$kpa36x = $obj->fields['KPA36X'];
	$tanggal = tgltgl(date("d-m-Y",strtotime($obj->fields['TANGGAL'])));
	$status = $obj->fields['STATUS'];
}


?>
<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$tanggal_awal	= (isset($_REQUEST['tanggal_awal'])) ? clean($_REQUEST['tanggal_awal']) : '';
$tanggal_akhir	= (isset($_REQUEST['tanggal_akhir'])) ? clean($_REQUEST['tanggal_akhir']) : '';
$keterangan		= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('C');
		ex_mod('C02');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('C02', 'I');
			
			ex_empty($tanggal_awal, 'tangga awal harus diisi.');
			ex_empty($tanggal_akhir, 'tanggal akhir harus diisi.');
			ex_empty($keterangan, 'keterangan harus diisi.');
			
			$query1 = "SELECT TANGGAL_AWAL FROM CS_HARI_LIBUR WHERE TANGGAL_AWAL = CONVERT(DATETIME,'$tanggal_awal',105)";
			ex_found($conn->Execute($query1)->recordcount(), "Tanggal \"$tanggal_awal\" telah terdaftar.");
			
			$query1 = "SELECT TANGGAL_AWAL FROM CS_HARI_LIBUR WHERE TANGGAL_AKHIR = CONVERT(DATETIME,'$tanggal_awal',105)";
			ex_found($conn->Execute($query1)->recordcount(), "Tanggal \"$tanggal_awal\" telah terdaftar.");
			
			if ($tanggal_awal <= $tanggal_akhir) 
			{
			$query = "INSERT INTO CS_HARI_LIBUR (TANGGAL_AWAL, TANGGAL_AKHIR, KETERANGAN)
			VALUES(
				CONVERT(DATETIME,'$tanggal_awal',105),
				CONVERT(DATETIME,'$tanggal_akhir',105),
				'$keterangan'
			)";
			ex_false($conn->execute($query), $query);
						
			$msg = "Data hari libur berhasil disimpan.";
			}
			else {
				$msg = "Tanggal awal harus lebih besar dari tanggal akhir.";
			}
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('C02', 'U');
			
			ex_empty($tanggal_awal, 'Tanggal harus diisi.');
			ex_empty($tanggal_akhir, 'Tanggal harus diisi.');
			ex_empty($keterangan, 'Keterangan harus diisi.');
			
			if ($tanggal_awal != $id)
			{
				$query = "SELECT TANGGAL_AWAL FROM CS_HARI_LIBUR WHERE TANGGAL_AWAL = CONVERT(DATETIME,'$tanggal_awal',105)";
				ex_found($conn->Execute($query)->recordcount(), "Kode \"$tanggal_awal\" telah terdaftar.");
			}
			
			$query = "
			UPDATE CS_HARI_LIBUR 
			SET 
				TANGGAL_AWAL = CONVERT(DATETIME,'$tanggal_awal',105),
				TANGGAL_AKHIR = CONVERT(DATETIME,'$tanggal_akhir',105),
				KETERANGAN = '$keterangan'
			WHERE
				TANGGAL_AWAL = CONVERT(DATETIME,'$id',105)
			";			
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data hari libur berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('C02', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "DELETE FROM CS_HARI_LIBUR WHERE TANGGAL_AWAL = CONVERT(DATETIME,'$id_del',105)";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data hari libur berhasil dihapus.';
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
// die_app('C');
die_mod('C02');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$query = "SELECT * FROM CS_HARI_LIBUR WHERE TANGGAL_AWAL = CONVERT(DATETIME,'$id',105)";
	$obj = $conn->execute($query);
	$tanggal_awal = tgltgl(date("d-m-Y", strtotime ($obj->fields['TANGGAL_AWAL'])));
	$tanggal_akhir = tgltgl(date("d-m-Y", strtotime ($obj->fields['TANGGAL_AKHIR'])));
	$keterangan = $obj->fields['KETERANGAN'];
}
?>
<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$coba	= (isset($_REQUEST['coba'])) ? clean($_REQUEST['coba']) : '';
$sisa	= (isset($_REQUEST['sisa'])) ? clean($_REQUEST['sisa']) : '';

$no_va		= (isset($_REQUEST['no_va'])) ? clean($_REQUEST['no_va']) : '';
$tanggal	= (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$nilai		= (isset($_REQUEST['nilai'])) ? to_number($_REQUEST['nilai']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('C01');
		//ex_mod('COF02');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			//ex_ha('COF02', 'I');
			
			ex_empty($no_va, 'nomor_va harus diisi.');
			ex_empty($tanggal, 'tanggal harus diisi.');
			ex_empty($nilai, 'nilai harus diisi.');
			
			$query = "SELECT NOMOR_VA FROM PEMBAYARAN WHERE NOMOR_VA = '$no_va'";
			ex_found($conn->Execute($query)->recordcount(), "Nomor_VA \"$no_va\" telah terdaftar.");

			$query = "INSERT INTO PEMBAYARAN (NOMOR_VA, TANGGAL, NILAI, STATUS)
			VALUES(
				'$no_va',
				CONVERT(DATETIME,'$tanggal',105),
				'$nilai','0'
			)";
			ex_false($conn->execute($query), $query);
						
			$msg = "Data virtual account berhasil disimpan.";
			
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			//ex_ha('COF02', 'U');
			
			ex_empty($no_va, 'nomor_va harus diisi.');
			ex_empty($tanggal, 'tanggal harus diisi.');
			ex_empty($nilai, 'nilai harus diisi.');
			
			if ($no_va != $id)
			{
				$query = "SELECT NOMOR_VA FROM PEMBAYARAN WHERE NOMOR_VA = '$no_va'";
				ex_found($conn->Execute($query)->recordcount(), "nomor va \"$no_va\" telah terdaftar.");
			}
			
			$query = "
			UPDATE PEMBAYARAN
			SET 
				NOMOR_VA = '$no_va',
				TANGGAL = CONVERT(DATETIME,'$tanggal',105),
				NILAI = '$nilai'
			WHERE
				NOMOR_VA = $no_va
			";			
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data virtual account berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			//ex_ha('COF02', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "DELETE FROM PEMBAYARAN WHERE NOMOR_VA = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data virtual account berhasil dihapus.';
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
//die_app('C01');
//die_mod('COF02');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$query = "SELECT * FROM PEMBAYARAN WHERE NOMOR_VA = '$id'";
	$obj = $conn->execute($query);
	$no_va = $obj->fields['NOMOR_VA'];
	$tanggal = tgltgl(date("d-m-Y", strtotime ($obj->fields['TANGGAL'])));
	$nilai = $obj->fields['NILAI'];
	
}
else if ($act == 'Tambah')
{
	$nilai = '0';
}

?>


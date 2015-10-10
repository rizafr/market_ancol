<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$nm = (isset($_REQUEST['nm'])) ? clean($_REQUEST['nm']) : '';

$kode_desa = (isset($_REQUEST['kode_desa'])) ? to_number($_REQUEST['kode_desa']) : '';
$nama_desa = (isset($_REQUEST['nama_desa'])) ? clean($_REQUEST['nama_desa']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M02');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M02', 'I');
			
			ex_empty($kode_desa, 'Kode desa harus diisi.');
			ex_empty($nama_desa, 'Nama desa harus diisi.');
		
			$query = "SELECT COUNT(KODE_DESA) AS TOTAL FROM DESA WHERE KODE_DESA = '$kode_desa'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode desa \"$kode_desa\" telah terdaftar.");
			
			$query = "SELECT COUNT(NAMA_DESA) AS TOTAL FROM DESA WHERE NAMA_DESA = '$nama_desa'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Nama desa '$nama_desa' telah terdaftar.");
			
			$query = "INSERT INTO DESA (KODE_DESA, NAMA_DESA)
			VALUES('$kode_desa', '$nama_desa')";
			ex_false($conn->Execute($query), $query);
					
			$msg = "Data Desa telah ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M02', 'U');
			
			ex_empty($kode_desa, 'Kode desa harus diisi.');
			ex_empty($nama_desa, 'Nama desa harus diisi.');
			
			if ($kode_desa != $id)
			{
				$query = "SELECT COUNT(KODE_DESA) AS TOTAL FROM DESA WHERE KODE_DESA = '$kode_desa'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode desa \"$kode_desa\" telah terdaftar.");
			}
			
			if ($nama_desa != $nm)
			{
				$query = "SELECT COUNT(NAMA_DESA) AS TOTAL FROM DESA WHERE NAMA_DESA = '$nama_desa'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Nama desa \"$nama_desa\" telah terdaftar.");
			}
			
			$query = "SELECT * FROM DESA WHERE KODE_DESA = '$kode_desa' AND NAMA_DESA = '$nama_desa'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
					
			$query = "
			UPDATE DESA
			SET KODE_DESA = '$kode_desa',
				NAMA_DESA = '$nama_desa'
			WHERE
				KODE_DESA = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Desa berhasil diubah.';
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
die_mod('M02');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_DESA) AS MAX_KODE FROM DESA");
	$kode_desa	= 1 + $obj->fields['MAX_KODE'];
}

if ($act == 'Ubah')
{
	$obj = $conn->Execute("SELECT * FROM DESA WHERE KODE_DESA = '$id'");
	$kode_desa	= $obj->fields['KODE_DESA'];
	$nama_desa	= $obj->fields['NAMA_DESA'];
}
?>
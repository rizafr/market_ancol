<?php
require_once('../../../../config/config.php');

$msg 	= '';
$error 	= FALSE;

$act 	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id 	= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$nomor_id	 	= (isset($_REQUEST['nomor_id'])) ? clean($_REQUEST['nomor_id']) : '';
$nama 			= (isset($_REQUEST['nama'])) ? clean($_REQUEST['nama']) : '';
$alamat 		= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('A01');
		ex_mod('PM11');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('PM11', 'I');
			
			ex_empty($nomor_id, 'No ID harus diisi.');
			ex_empty($nama, 'Nama harus diisi.');
			ex_empty($alamat, 'Alamat harus diisi.');
		
			$query = "SELECT COUNT(NOMOR_ID) AS TOTAL FROM CLUB_PERSONAL WHERE NOMOR_ID = '$id'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Nomor ID \"$id\" telah terdaftar.");
			
			$query = "INSERT INTO CLUB_PERSONAL (NOMOR_ID, NAMA, ALAMAT)
			VALUES('$nomor_id', '$nama', '$alamat')";
			ex_false($conn->Execute($query), $query);
					
			$msg = "Data Club Personal telah ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('PM11', 'U');
			
			ex_empty($nomor_id, 'Nomor id harus diisi.');
			ex_empty($nama, 'Nama harus diisi.');
			ex_empty($alamat, 'Alamat harus diisi.');
			
			if ($nomor_id != $id)
			{
				$query = "SELECT COUNT(NOMOR_ID) AS TOTAL FROM CLUB_PERSONAL WHERE NOMOR_ID = '$id'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Nomor ID \"$id\" telah terdaftar.");
			}
			
			$query = "SELECT * FROM CLUB_PERSONAL WHERE NOMOR_ID = '$id' AND NAMA = '$nama'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
					
			$query = "
			UPDATE CLUB_PERSONAL
			SET NOMOR_ID 	= '$id',
				NAMA	 	= '$nama',
				ALAMAT		= '$alamat'			
			WHERE
				NOMOR_ID = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Club personal berhasil diubah.';
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
die_app('A01');
die_mod('PM11');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$obj = $conn->Execute("SELECT * FROM CLUB_PERSONAL WHERE NOMOR_ID = '$id'");
	$nomor_id	= $obj->fields['NOMOR_ID'];
	$nama		= $obj->fields['NAMA'];
	$alamat		= $obj->fields['ALAMAT'];

}
?>
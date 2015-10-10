<?php
require_once('../../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$nama_calon_pembeli		= (isset($_REQUEST['nama_calon_pembeli'])) ? clean($_REQUEST['nama_calon_pembeli']) : '';
$tanggal_reserve		= (isset($_REQUEST['tanggal_reserve'])) ? clean($_REQUEST['tanggal_reserve']) : '';
$berlaku_sampai			= (isset($_REQUEST['berlaku_sampai'])) ? clean($_REQUEST['berlaku_sampai']) : '';
$alamat					= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';
$telepon				= (isset($_REQUEST['telepon'])) ? clean($_REQUEST['telepon']) : '';
$agen					= (isset($_REQUEST['agen'])) ? clean($_REQUEST['agen']) : '';
$koordinator			= (isset($_REQUEST['koordinator'])) ? clean($_REQUEST['koordinator']) : '';
$no_ktp			= (isset($_REQUEST['no_ktp'])) ? clean($_REQUEST['no_ktp']) : '';
$no_npwp			= (isset($_REQUEST['no_npwp'])) ? clean($_REQUEST['no_npwp']) : '';
	
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M30');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Ubah') # Proses Ubah Reserve
		{
			ex_ha('M30', 'I');
			
			ex_empty($nama_calon_pembeli, 'Nama calon pembeli harus diisi.');
			ex_empty($no_ktp, 'No KTP harus diisi.');			
			ex_empty($tanggal_reserve, 'Tanggal reserve harus diisi.');
			ex_empty($berlaku_sampai, 'Tanggal berlaku sampai harus diisi.');
			ex_empty($alamat, 'Alamat harus diisi.');
			ex_empty($telepon, 'No Telepon harus diisi.');
			ex_empty($agen, 'Agen harus diisi.');
			ex_empty($koordinator, 'Koordinator harus diisi.');
			
			$query = "SELECT * FROM RESERVE WHERE KODE_BLOK = '$id' AND NAMA_CALON_PEMBELI = '$nama_calon_pembeli' AND
					 TANGGAL_RESERVE = CONVERT(DATETIME,'$tanggal_reserve',105) AND
					 BERLAKU_SAMPAI = CONVERT(DATETIME,'$berlaku_sampai',105) AND
					 ALAMAT = '$alamat' AND TELEPON = '$telepon' AND AGEN = '$agen' AND KOORDINATOR = '$koordinator' AND NO_IDENTITAS = '$no_ktp' AND NPWP = '$no_npwp'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE RESERVE
			SET
				NAMA_CALON_PEMBELI = '$nama_calon_pembeli', 
				TANGGAL_RESERVE = CONVERT(DATETIME,'$tanggal_reserve',105), 
				BERLAKU_SAMPAI = CONVERT(DATETIME,'$berlaku_sampai',105), 
				ALAMAT = '$alamat', 
				TELEPON = '$telepon',
				AGEN = '$agen',
				KOORDINATOR = '$koordinator',
				NO_IDENTITAS = '$no_ktp',
				NPWP = '$no_npwp'
			WHERE
				KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data reserve berhasil diubah.';
		}
		else if ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('M30', 'U');
			
			$query = "
			DELETE FROM RESERVE 
			WHERE
				KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$query = "
			UPDATE STOK SET TERJUAL = '0' 
			WHERE
				KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data Reserve berhasil dibatalkan.';
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
//die_app('A01');
//die_mod('PO01');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Ubah')
{
	$obj = $conn->Execute("SELECT * FROM STOK WHERE KODE_BLOK = '$id'");
	$no_va					= $obj->fields['NO_VA'];
	
	$obj = $conn->Execute("SELECT * FROM RESERVE WHERE KODE_BLOK = '$id'");	
	
	$nama_calon_pembeli		= $obj->fields['NAMA_CALON_PEMBELI'];
	
	$tanggal_reserve		= tgltgl(date("d-m-Y",strtotime($obj->fields['TANGGAL_RESERVE'])));
	$berlaku_sampai			= tgltgl(date("d-m-Y",strtotime($obj->fields['BERLAKU_SAMPAI'])));
	$alamat 				= $obj->fields['ALAMAT'];
	$telepon 				= $obj->fields['TELEPON'];
	$agen 					= $obj->fields['AGEN'];
	$koordinator 			= $obj->fields['KOORDINATOR'];
	$no_ktp		 			= $obj->fields['NO_IDENTITAS'];
	$no_npwp 				= $obj->fields['NPWP'];
}
?>
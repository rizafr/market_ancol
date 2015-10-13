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
$no_npwp				= (isset($_REQUEST['no_npwp'])) ? clean($_REQUEST['no_npwp']) : '';
$no_ktp					= (isset($_REQUEST['no_ktp'])) ? clean($_REQUEST['no_ktp']) : '';
$koordinator			= (isset($_REQUEST['koordinator'])) ? clean($_REQUEST['koordinator']) : '';
	
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M29');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Reserve') # Proses Reserve
		{
			ex_ha('M29', 'I');
			
			ex_empty($nama_calon_pembeli, 'Nama calon pembeli harus diisi.');			
			//ex_empty($tanggal_reserve, 'Tanggal reserve harus diisi.');
			//ex_empty($berlaku_sampai, 'Tanggal berlaku sampai harus diisi.');
			ex_empty($alamat, 'Alamat harus diisi.');
			ex_empty($telepon, 'No Telepon harus diisi.');
			//ex_empty($agen, 'Agen harus diisi.');
			ex_empty($no_ktp, 'Identitas KTP harus diisi.');
			ex_empty($koordinator, 'Koordinator harus diisi.');
			
			$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM RESERVE WHERE KODE_BLOK = '$id'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode blok \'$id\' telah terdaftar.");
								
			$query = "
			INSERT INTO RESERVE
			(
				KODE_BLOK, NAMA_CALON_PEMBELI, TANGGAL_RESERVE, BERLAKU_SAMPAI, 
				ALAMAT, TELEPON, AGEN, KOORDINATOR, NO_IDENTITAS, NPWP
			)
			VALUES
			(
				'$id', '$nama_calon_pembeli', CONVERT(DATETIME,'$tanggal_reserve',105), 
				CONVERT(DATETIME,'$berlaku_sampai',105), '$alamat', '$telepon', '$agen', '$koordinator', '$no_ktp', '$no_npwp'
			)
			";
			ex_false($conn->Execute($query), $query);
			
			$query = "
			UPDATE STOK SET
				TERJUAL = '1' 
			WHERE 
				KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = "Blok \'$id\' berhasil direserve.";
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
die_mod('M29');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Detail')
{
	
	$obj = $conn->Execute("
	SELECT  
		s.NO_VA,
		s.KODE_BLOK,
		s.LUAS_BANGUNAN,
		s.STATUS_STOK,
		s.TERJUAL,
		s.KODE_PENJUALAN,
		t.TIPE_BANGUNAN,
		hs.KODE_SK,
		hs.HARGA_CASH_KERAS,
		hs.CB36X,
		hs.CB48X,
		hs.KPA24X,
		hs.KPA36X,
		h.*,
		ju.*,
		t.*,
		p.*
		FROM 
		STOK s
		LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
		LEFT JOIN HARGA_SK hs ON s.KODE_SK = hs.KODE_SK
		LEFT JOIN LOKASI h ON s.KODE_LOKASI = h.KODE_LOKASI
		LEFT JOIN JENIS_UNIT ju ON s.KODE_UNIT = ju.KODE_UNIT
		LEFT JOIN JENIS_PENJUALAN p ON s.KODE_PENJUALAN = p.KODE_JENIS
		WHERE		
		s.KODE_BLOK = '$id'");
	
	$no_va			    = $obj->fields['NO_VA'];
	$kode_blok			= $obj->fields['KODE_BLOK'];
	$kode_lokasi		= $obj->fields['KODE_LOKASI'];
	$kode_unit			= $obj->fields['KODE_UNIT'];
	$kode_tipe			= $obj->fields['KODE_TIPE'];
	$kode_penjualan		= $obj->fields['KODE_PENJUALAN'];
	$kode_sk			= $obj->fields['KODE_SK'];
	
	$lokasi				= $obj->fields['LOKASI'];
	$jenis_unit			= $obj->fields['JENIS_UNIT'];
	$tipe_bangunan		= $obj->fields['TIPE_BANGUNAN'];
	$jenis_penjualan	= $obj->fields['JENIS_PENJUALAN'];
	
	$luas_bangunan		= $obj->fields['LUAS_BANGUNAN'];

	$harga_cash_keras	= $obj->fields['HARGA_CASH_KERAS'];
	$CB36X	= $obj->fields['CB36X'];
	$CB48X	= $obj->fields['CB48X'];
	$KPA24X	= $obj->fields['KPA24X'];
	$KPA36X	= $obj->fields['KPA36X'];
}

if ($act == 'Reserve')
{
	$obj = $conn->Execute("SELECT BATAS_RESERVE FROM CS_PARAMETER_MARK");
	$batas_reserve	= $obj->fields['BATAS_RESERVE'];

	$obj = $conn->Execute("SELECT GETDATE() AS TANGGAL_RESERVE, GETDATE()+$batas_reserve AS BERLAKU_SAMPAI");	
	$tanggal_reserve		= tgltgl(date("d-m-Y",strtotime($obj->fields['TANGGAL_RESERVE'])));
	$berlaku_sampai			= tgltgl(date("d-m-Y",strtotime($obj->fields['BERLAKU_SAMPAI'])));

	$obj = $conn->Execute("SELECT NO_VA FROM STOK where KODE_BLOK = '$id'");
	$no_va = $obj->fields['NO_VA'];
	if(!isset($no_va)){
		$no_va = '';
	}
}
?>
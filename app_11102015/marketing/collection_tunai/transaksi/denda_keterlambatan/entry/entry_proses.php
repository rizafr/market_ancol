<?php
require_once('../../../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$disetujui = (isset($_REQUEST['disetujui'])) ? clean($_REQUEST['disetujui']) : '';

$isi	= explode(" ", $id);
$id_	= $isi[0];
$tgl_	= $isi[1];

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	
	{
		ex_login();
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Ubah') # Proses Ubah
		{
			$query = "update CS_INFORMASI_DENDA set DENDA = '$disetujui' 
			WHERE KODE_BLOK = '$id_' AND convert(varchar,TANGGAL,23) = '$tgl_'";
			ex_false($conn->execute($query), $query);
					
			$msg = 'Data denda pembayaran telah diubah.';
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
die_mod('PM01');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	
	$obj = $conn->Execute("SELECT *, DATEADD(dd,HARI_TUNGGAKAN,TANGGAL) as TGL_TEMPO, DENDA_DISETUJUI,
	CASE WHEN DENDA_DISETUJUI IS NULL THEN 0.00 ELSE DENDA_DISETUJUI END AS DISETUJUI
	FROM CS_INFORMASI_DENDA 
	WHERE KODE_BLOK = '$id_' AND convert(varchar,TANGGAL,23) = '$tgl_'");
	$kode_blok	= $obj->fields['KODE_BLOK'];
	$tgl_trans	= $obj->fields['TANGGAL'];
	$tgl_tempo	= $obj->fields['TGL_TEMPO'];
	$nilai	= $obj->fields['NILAI'];
	$denda	= $obj->fields['DENDA'];
	$disetujui	= $obj->fields['DISETUJUI'];
	$hari_tunggakan	= $obj->fields['HARI_TUNGGAKAN'];
	
}
?>
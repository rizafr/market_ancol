<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$pejabat		= (isset($_REQUEST['pejabat'])) ? clean($_REQUEST['pejabat']) : '';
$jabatan		= (isset($_REQUEST['jabatan'])) ? clean($_REQUEST['jabatan']) : '';


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('K');
		ex_mod('K10');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Penomoran
		{
			ex_ha('K10', 'U');
			
			
			$obj 	= $conn->Execute("SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE");
			$reg	= $obj->fields['NO_REG_FAKTUR_PAJAK'];
			$no		= 1 + $obj->fields['NO_FAKTUR_PAJAK_STANDAR'];
			
			$query = "SELECT * FROM FAKTUR_PAJAK
			WHERE TGL_FAKTUR >= CONVERT(DATETIME,'$periode_awal',105) AND TGL_FAKTUR <= CONVERT(DATETIME,'$periode_akhir',105)
			";
			$obj = $conn->execute($query);
			
			while( ! $obj->EOF)
			{
				$id 	= $obj->fields['NO_KWITANSI'];
				$faktur = $reg.$no;
				
				$query2 = "
				UPDATE FAKTUR_PAJAK SET
					NOMOR_SERI_FAKTUR = '$faktur'
				WHERE
					NO_KWITANSI = '$id'
				";
				ex_false($conn->Execute($query2), $query2);
				
				$obj->movenext();
				$no++;
			}
			
			$query = "UPDATE CS_REGISTER_CUSTOMER_SERVICE set NO_FAKTUR_PAJAK_STANDAR = $no-1";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Nomor Faktur Berhasil Dibuat';
		}
		else if ($act == 'Edit')
		{
			ex_ha('K10', 'U');
									
			$query = "
			UPDATE FAKTUR_PAJAK SET
				PEJABAT = '$pejabat',
				JABATAN = '$jabatan'
			WHERE
				NO_KWITANSI = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data faktur berhasil diubah';
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
//die_app('K');
die_mod('K10');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Edit')
{
	$query = "SELECT *,ISNULL(PEJABAT,'') AS PEJABAT, ISNULL(JABATAN, '') AS JABATAN FROM FAKTUR_PAJAK WHERE NO_KWITANSI = '$id'";
	$obj = $conn->execute($query);
	$no_kuitansi	= $obj->fields['NO_KWITANSI'];
	$blok_nomor 	= $obj->fields['KODE_BLOK'];
	$nama_pembeli 	= $obj->fields['NAMA'];
	$pejabat 		= $obj->fields['PEJABAT'];
	$jabatan 		= $obj->fields['JABATAN'];

}
?>
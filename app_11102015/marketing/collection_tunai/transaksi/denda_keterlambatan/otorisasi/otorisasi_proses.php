<?php
require_once('../../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('');
		//ex_mod('');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Hapus') # Proses Hapus
		{
			//ex_ha('', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$isi	= split('[,]', $id_del);
				$id		= $isi[0];
				$tgl	= $isi[1];
				
				$query = "DELETE FROM CS_INFORMASI_DENDA WHERE KODE_BLOK = '$id' AND TANGGAL = '$tgl'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data denda pembayaran gagal dihapus.' : 'Data denda pembayaran berhasil dihapus.'; 	
		}
		
		elseif ($act == 'Otorisasi') # Proses Otorisasi
		{
			//ex_ha('PT05', 'U');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];	
			ex_empty($cb_data, 'Pilih data yang akan diotorisasi.');
			
			foreach ($cb_data as $id_del)
			{	
				$isi	= split('[,]', $id_del);
				$id		= $isi[0];
				$tgl	= $isi[1];
				
				if ($conn->Execute("UPDATE CS_INFORMASI_DENDA SET OTORISASI = '1', TANGGAL_OTORISASI = GETDATE() WHERE KODE_BLOK = '$id' AND TANGGAL = '$tgl' ")) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data gagal diotorisasi.' : 'Data berhasil diotorisasi.';
		}
		
		elseif ($act == 'Batal_Otorisasi') # Proses Batal Otorisasi
		{
			//ex_ha('PT05', 'U');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dibatalkan diotorisasi.');
			
			foreach ($cb_data as $id_del)
			{	
				$isi	= split('[,]', $id_del);
				$id		= $isi[0];
				$tgl	= $isi[1];
				
				if ($conn->Execute("UPDATE CS_INFORMASI_DENDA SET OTORISASI = '0', TANGGAL_OTORISASI = NULL WHERE KODE_BLOK = '$id' AND TANGGAL = '$tgl'")) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data gagal dibatalkan otorisasi.' : 'Data berhasil dibatalkan otorisasi.';
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
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Tambah')
{
	$query = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE";
	$obj = $conn->execute($query);
	
	$no_spp		= 1 + $obj->fields['NOMOR_SPP'];
}
?>
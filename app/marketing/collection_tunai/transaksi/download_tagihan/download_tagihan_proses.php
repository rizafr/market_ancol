<?php
require_once('../../../../../config/config.php');
$msg 	= '';
$error	= FALSE;

$kode_blok			= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$act				= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id					= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$bulan				= (isset($_REQUEST['bulan'])) ? clean($_REQUEST['bulan']) : '';

$jenis_pembayaran	= (isset($_REQUEST['jenis_pembayaran'])) ? clean($_REQUEST['jenis_pembayaran']) : '';
$jumlah				= (isset($_REQUEST['jumlah'])) ? clean($_REQUEST['jumlah']) : '';
$kode				= (isset($_REQUEST['kode'])) ? clean($_REQUEST['kode']) : '';


$pecah_tanggal		= explode("-",$bulan);
$bln 				= $pecah_tanggal[0];
$thn 				= $pecah_tanggal[1];

//bulan depan
$next_bln	= $bln + 1;
$next_thn	= $thn;
if($bln > 12)
{
	$next_bln	= 1;
	$next_thn	= $thn + 1;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('C');
		ex_mod('C04');
		$conn = conn($sess_db);
		ex_conn($conn);
		$conn->begintrans(); 
		
		if ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('C04', 'U');
			
			ex_empty($jumlah, 'Jumlah harus diisi.');
			
			$query = "
			UPDATE TAGIHAN_LAIN_LAIN SET TANGGAL = CONVERT(DATETIME,'01-$bln-$thn',105), NILAI = $jumlah 
			WHERE KODE_BAYAR = $id
			";			
			ex_false($conn->execute($query), $query);
						
			$msg = 'Data Tagihan berhasil diubah.';
			
		}
		elseif ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('C04', 'I');
			
			ex_empty($jenis_pembayaran, 'Jenis Pembayaran harus diisi.');
			ex_empty($jumlah, 'Jumlah harus diisi.');
						
			$query = "INSERT INTO TAGIHAN_LAIN_LAIN (KODE_BLOK, TANGGAL, KODE_BAYAR, NILAI, STATUS_BAYAR) 
			VALUES('$kode_blok', CONVERT(DATETIME,'01-$bln-$thn',105), $jenis_pembayaran, $jumlah, 0)";
			ex_false($conn->Execute($query), $query);
				
			$msg = 'Data Tagihan telah ditambah.';
		}
		else if($act == 'Hapus') #Proses Hapus
		{			
			ex_ha('C04', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				if($id_del == 9)
				{
					$msg = 'Maaf Tagihan denda tidak bisa dihapus';
				}
				else
				{
					$query = "DELETE FROM TAGIHAN_LAIN_LAIN WHERE TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
					AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)				
					AND KODE_BAYAR = $id_del AND KODE_BLOK = '$kode'";
					if ($conn->Execute($query)) {
						$act[] = $id_del;
					} else {
						$error = TRUE;
					}
					
					$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Tagihan berhasil dihapus.';
				}	
			}
			
			
		}
		elseif ($act == 'Pindah') # Proses Pindah
		{
			ex_ha('C04', 'U');
		
			ex_empty($blok_baru, 'Blok baru harus diisi.');
			
			$query = "
			UPDATE KWITANSI 
			SET
				KODE_BLOK = '$blok_baru', 
				STATUS_PINDAH_BLOK = '1'
			WHERE
				KODE_BLOK = '$id'
			";
			ex_false($conn->execute($query), $query);
			
			$query = "
			UPDATE KWITANSI_LAIN_LAIN
			SET
				KODE_BLOK = '$blok_baru'
			WHERE
				KODE_BLOK = '$id'
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Blok berhasil dipindahkan.';
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
die_mod('C01');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Detail')
{
	
}
if ($act == 'Ubah')
{
	$query = "SELECT * FROM TAGIHAN_LAIN_LAIN a LEFT JOIN JENIS_PEMBAYARAN b
	on a.KODE_BAYAR = b.KODE_BAYAR where A.KODE_BLOK = '$kode_blok'
	and TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105) 
	AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
	AND a.KODE_BAYAR = '$id'
	";

	$obj = $conn->execute($query);
	$jumlah 	= $obj->fields['NILAI'];
	$kode_bayar	= $id;
}
if ($act == 'Tambah')
{
	$jumlah = 0;
}
?>

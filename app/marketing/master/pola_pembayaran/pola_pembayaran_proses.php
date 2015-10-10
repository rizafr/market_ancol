<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_pola_bayar 	= (isset($_REQUEST['kode_pola_bayar'])) ? to_number($_REQUEST['kode_pola_bayar']) : '';
$kode_jenis 		= (isset($_REQUEST['kode_jenis'])) ? clean($_REQUEST['kode_jenis']) : '';
$nama_pola_bayar 	= (isset($_REQUEST['nama_pola_bayar'])) ? clean($_REQUEST['nama_pola_bayar']) : '';
$aktif			 	= (isset($_REQUEST['aktif'])) ? clean($_REQUEST['aktif']) : '';
$nilai1			 	= (isset($_REQUEST['nilai1'])) ? clean($_REQUEST['nilai1']) : 0;
$kali1			 	= (isset($_REQUEST['kali1'])) ? clean($_REQUEST['kali1']) : 0;
$nilai2			 	= (isset($_REQUEST['nilai2'])) ? clean($_REQUEST['nilai2']) : 0;
$kali2			 	= (isset($_REQUEST['kali2'])) ? clean($_REQUEST['kali2']) : 0;
$nilai3			 	= (isset($_REQUEST['nilai3'])) ? clean($_REQUEST['nilai3']) : 0;
$kali3			 	= (isset($_REQUEST['kali3'])) ? clean($_REQUEST['kali3']) : 0;
$nilai4			 	= (isset($_REQUEST['nilai4'])) ? clean($_REQUEST['nilai4']) : 0;
$kali4			 	= (isset($_REQUEST['kali4'])) ? clean($_REQUEST['kali4']) : 0;
$nilai5			 	= (isset($_REQUEST['nilai5'])) ? clean($_REQUEST['nilai5']) : 0;
$kali5			 	= (isset($_REQUEST['kali5'])) ? clean($_REQUEST['kali5']) : 0;
$nilai_jenis	 	= (isset($_REQUEST['nilai_jenis'])) ? clean($_REQUEST['nilai_jenis']) : 0;
$non2			 	= (isset($_REQUEST['non2'])) ? clean($_REQUEST['non2']) : '';
$non3			 	= (isset($_REQUEST['non3'])) ? clean($_REQUEST['non3']) : '';
$non4			 	= (isset($_REQUEST['non4'])) ? clean($_REQUEST['non4']) : '';
$non5			 	= (isset($_REQUEST['non5'])) ? clean($_REQUEST['non5']) : '';
$non_jenis		 	= (isset($_REQUEST['non_jenis'])) ? clean($_REQUEST['non_jenis']) : '';


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M12');
		$conn = conn($sess_db);
		ex_conn($conn);

		if($aktif=='on'){
			$aktif = '1';
		}else{
			$aktif = '0';
		}
		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M12', 'I');
			
			ex_empty($nama_pola_bayar, 'Nama pola pembayaran harus diisi.');
			ex_empty($nilai1, 'Rumus pola pembayaran harus diisi.');
			ex_empty($kali1, 'Rumus pola pembayaran harus diisi.');

			$query = "INSERT INTO POLA_BAYAR (AKTIF,KODE_POLA_BAYAR, KODE_JENIS, NAMA_POLA_BAYAR, NILAI1, KALI1, NILAI2, KALI2, NILAI3, KALI3, NILAI4, KALI4, NILAI5, KALI5, NILAI_JENIS) 
			VALUES($aktif,$kode_pola_bayar, '$kode_jenis', '$nama_pola_bayar', $nilai1, $kali1, $nilai2, $kali2, $nilai3, $kali3, $nilai4, $kali4, $nilai5, $kali5, $nilai_jenis)";
			ex_false($conn->Execute($query), $query);
					
			$msg = "Data pola pembayaran berhasil ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			//ex_ha('M12', 'U');
			ex_empty($nama_pola_bayar, 'Nama pola pembayaran harus diisi.');
			ex_empty($nilai1, 'Rumus pola pembayaran harus diisi.');
			ex_empty($kali1, 'Rumus pola pembayaran harus diisi.');
			
			$query = "
			UPDATE POLA_BAYAR
			SET KODE_JENIS = '$kode_jenis',
				NAMA_POLA_BAYAR = '$nama_pola_bayar',
				NILAI1 = $nilai1,
				KALI1 = $kali1,
				NILAI2 = $nilai2,
				KALI2 = $kali2,
				NILAI3 = $nilai3,
				KALI3 = $kali3,
				NILAI4 = $nilai4,
				KALI4 = $kali4,
				NILAI5 = $nilai5,
				KALI5 = $kali5,
				NILAI_JENIS = $nilai_jenis,
				AKTIF = '$aktif'
			WHERE
				KODE_POLA_BAYAR = $kode_pola_bayar
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data pola pembayaran berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "DELETE FROM POLA_BAYAR WHERE KODE_POLA_BAYAR = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data pola pembayaran berhasil dihapus.';
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
die_mod('M12');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_POLA_BAYAR) AS MAX_KODE FROM POLA_BAYAR");
	$kode_pola_bayar	= 1 + $obj->fields['MAX_KODE'];
	$kode_jenis			= 1;
	$nama_pola_bayar	= '';
	$rumus_pola_bayar	= '';
	$nilai1				= '';
	$kali1				= '';
	$nilai2				= '0';
	$kali2				= '0';
	$nilai3				= '0';
	$kali3				= '0';
	$nilai4				= '0';
	$kali4				= '0';
	$nilai5				= '0';
	$kali5				= '0';
	$nilai_jenis		= '0';
	
	$non2 = 0;
	$non3 = 0;
	$non4 = 0;
	$non5 = 0;
	$non_jenis = 0;

}

if ($act == 'Ubah')
{
	$obj = $conn->Execute("SELECT * FROM POLA_BAYAR WHERE KODE_POLA_BAYAR = '$id'");
	$kode_pola_bayar	= $obj->fields['KODE_POLA_BAYAR'];
	$kode_jenis			= $obj->fields['KODE_JENIS'];
	$nama_pola_bayar	= $obj->fields['NAMA_POLA_BAYAR'];
	$aktif				= $obj->fields['AKTIF'];
	$nilai1				= $obj->fields['NILAI1'];
	$kali1				= $obj->fields['KALI1'];
	$nilai2				= $obj->fields['NILAI2'];
	$kali2				= $obj->fields['KALI2'];
	$nilai3				= $obj->fields['NILAI3'];
	$kali3				= $obj->fields['KALI3'];
	$nilai4				= $obj->fields['NILAI4'];
	$kali4				= $obj->fields['KALI4'];
	$nilai5				= $obj->fields['NILAI5'];
	$kali5				= $obj->fields['KALI5'];
	$nilai_jenis		= $obj->fields['NILAI_JENIS'];
	
	$non2 = 1;
	$non3 = 1;
	$non4 = 1;
	$non5 = 1;
	$non_jenis = 1;
	
	if($nilai2 == 0){
		$non2 = 0;
	}
	if($nilai3 == 0){
		$non3 = 0;
	}
	if($nilai4 == 0){
		$non4 = 0;
	}
	if($nilai5 == 0){
		$non5 = 0;
	}
	if($nilai_jenis == 0){
		$non_jenis = 0;
	}
	
}
?>
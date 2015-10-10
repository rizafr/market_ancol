<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_blok = (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$pola_bayar = (isset($_REQUEST['pola_bayar'])) ? to_number($_REQUEST['pola_bayar']) : 0;
$sebelumnya = (isset($_REQUEST['sebelumnya'])) ? to_number($_REQUEST['sebelumnya']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M09');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M09', 'I');
			
			ex_empty($kode_blok, 'Kode Blok harus diisi');
			
			$query = "DELETE FROM DETAIL_POLA_BAYAR WHERE KODE_BLOK = '$kode_blok'";
			$conn->Execute($query);
			
			$data = $conn->Execute("SELECT KODE_POLA_BAYAR AS POLA FROM POLA_BAYAR");
			while(!$data->EOF){
				$pola_bayar = $data->fields['POLA'];
				$harga_tanah = (isset($_REQUEST['harga_tanah_'.$pola_bayar])) ? to_number($_REQUEST['harga_tanah_'.$pola_bayar]) : 0;
				$harga_bangunan = (isset($_REQUEST['harga_bangunan_'.$pola_bayar])) ? to_number($_REQUEST['harga_bangunan_'.$pola_bayar]) : 0;
				if($harga_bangunan==0&&$harga_tanah==0){
					$data->movenext();
					continue;
				}
				$query = "INSERT INTO DETAIL_POLA_BAYAR(KODE_BLOK, KODE_POLA_BAYAR, HARGA_TANAH, HARGA_BANGUNAN) VALUES 
				(
					'$kode_blok',
					'$pola_bayar',
					'$harga_tanah',
					'$harga_bangunan'
				)";
			ex_false($conn->Execute($query), $query);	
				$data->movenext();
			}
					
			$msg = "Detail Pola Bayar berhasil ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M09', 'U');
			ex_empty($kode_blok, 'Kode Blok harus diisi');

			if($sebelumnya!=$pola_bayar){
				$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM DETAIL_POLA_BAYAR WHERE KODE_BLOK = '$kode_blok' AND KODE_POLA_BAYAR = '$pola_bayar'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Detail Pola Bayar Sudah Terdaftar");
			}

			$query  = "UPDATE DETAIL_POLA_BAYAR SET HARGA_TANAH = '$harga_tanah', 
								HARGA_BANGUNAN = '$harga_bangunan' ,
								KODE_POLA_BAYAR = '$pola_bayar'
								WHERE KODE_BLOK = '$kode_blok' AND KODE_POLA_BAYAR = '$sebelumnya'";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Detail Pola Bayar berhasil diupdate.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$id_del = explode(':', $id_del);
				$query = "DELETE FROM DETAIL_POLA_BAYAR WHERE KODE_BLOK = '$id_del[0]' AND KODE_POLA_BAYAR ='$id_del[1]'  ";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Detail Pola berhasil dihapus.';
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
die_mod('M09');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Ubah')
{
	$id = explode(':', $id);
	$obj = $conn->Execute("	SELECT *
	FROM DETAIL_POLA_BAYAR 
	WHERE KODE_BLOK = '$id[0]'");
	$kode_blok = $obj->fields['KODE_BLOK'];
	$pola_bayar = $obj->fields['KODE_POLA_BAYAR'];

	$query = "SELECT * FROM DETAIL_POLA_BAYAR WHERE KODE_BLOK = '$id[0]'";
	$harga = $conn->Execute($query);
	while (!$harga->EOF) {
		$h_tanah = $harga->fields['HARGA_TANAH'];
		if(!isset($h_tanah)){
			$h_tanah = '0';
		}
		$h_bangunan = $harga->fields['HARGA_BANGUNAN'];
		if(!isset($h_tanah)){
			$h_bangunan = '0';
		}
		$hrg_tanah[$harga->fields['KODE_POLA_BAYAR']] = $h_tanah;
		$hrg_bangunan[$harga->fields['KODE_POLA_BAYAR']] = $h_bangunan;

		$harga->movenext();
	}
}
?>
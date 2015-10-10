<?php
require_once('../../../../config/config.php');

$msg	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_faktor 		= (isset($_REQUEST['kode_faktor'])) ? to_number($_REQUEST['kode_faktor']) : '';
$faktor_strategis	= (isset($_REQUEST['faktor_strategis'])) ? clean($_REQUEST['faktor_strategis']) : '';
$nilai_tambah		= (isset($_REQUEST['nilai_tambah'])) ? to_decimal($_REQUEST['nilai_tambah']) : '';
$nilai_kurang		= (isset($_REQUEST['nilai_kurang'])) ? to_decimal($_REQUEST['nilai_kurang']) : '';
$status				= (isset($_REQUEST['status'])) ? to_number($_REQUEST['status']) : '0';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M05');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M05', 'I');
			
			ex_empty($kode_faktor, 'Kode faktor strategis harus diisi.');
			ex_empty($faktor_strategis, 'Nama faktor strategis harus diisi.');
		
			$query = "SELECT COUNT(KODE_FAKTOR) AS TOTAL FROM FAKTOR WHERE KODE_FAKTOR = '$kode_faktor'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode faktor strategis \"$kode_faktor\" telah terdaftar.");
			
			$query = "SELECT COUNT(FAKTOR_STRATEGIS) AS TOTAL FROM FAKTOR WHERE FAKTOR_STRATEGIS = '$faktor_strategis'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Nama faktor strategis \"$faktor_strategis\" telah terdaftar.");
			
			$query = "INSERT INTO FAKTOR (KODE_FAKTOR, FAKTOR_STRATEGIS, NILAI_TAMBAH, NILAI_KURANG, STATUS) VALUES
			(
				'$kode_faktor', 
				'$faktor_strategis',
				'$nilai_tambah',
				'$nilai_kurang',
				'$status'
			)";
			ex_false($conn->Execute($query), $query);
					
			$msg = "Data Faktor strategis berhasil ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M05', 'U');
			
			ex_empty($kode_faktor, 'Kode faktor strategis harus diisi.');
			ex_empty($faktor_strategis, 'Nama faktor strategis harus diisi.');
			
			if ($kode_faktor != $id)
			{
				$query = "SELECT COUNT(KODE_FAKTOR) AS TOTAL FROM FAKTOR WHERE KODE_FAKTOR = '$kode_faktor'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode faktor strategis \"$kode_faktor\" telah terdaftar.");
			}
				
			$obj = $conn->Execute("SELECT * FROM FAKTOR WHERE KODE_FAKTOR = '$id'");
				$nm	= $obj->fields['FAKTOR_STRATEGIS'];	
				
			if ($faktor_strategis != $nm)
			{
				$query = "SELECT COUNT(FAKTOR_STRATEGIS) AS TOTAL FROM FAKTOR WHERE FAKTOR_STRATEGIS = '$faktor_strategis'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Nama faktor strategis \"$faktor_strategis\" telah terdaftar.");
			}
					
			$query = "SELECT * FROM FAKTOR WHERE KODE_FAKTOR = '$kode_faktor' AND FAKTOR_STRATEGIS = '$faktor_strategis' AND
			NILAI_TAMBAH = '$nilai_tambah' AND NILAI_KURANG = '$nilai_kurang' AND STATUS = '$status'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");		
					
			$query = "
			UPDATE FAKTOR 
			SET KODE_FAKTOR = '$kode_faktor',
				FAKTOR_STRATEGIS = '$faktor_strategis',
				NILAI_TAMBAH = '$nilai_tambah',
				NILAI_KURANG = '$nilai_kurang',
				STATUS = '$status'
			WHERE
				KODE_FAKTOR = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data faktor strategis berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{

				$querySrc= "SELECT COUNT(KODE_FAKTOR) AS TOTAL FROM stok WHERE KODE_FAKTOR ='$id_del'";
				ex_found($conn->Execute($querySrc)->fields['TOTAL'], "Kode Faktor Strategis \'$id_del\' telah terdaftar.");

				echo "querySrc";
				$query = "DELETE FROM FAKTOR WHERE KODE_FAKTOR = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Faktor Strategis berhasil dihapus.';
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
die_mod('M05');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Tambah')
{
	$obj = $conn->Execute("SELECT MAX(KODE_FAKTOR) AS MAX_KODE FROM FAKTOR");
	$kode_faktor		= 1 + $obj->fields['MAX_KODE'];
}

if ($act == 'Ubah')
{
	$obj = $conn->Execute("SELECT * FROM FAKTOR WHERE KODE_FAKTOR = '$id'");
	$kode_faktor		= $obj->fields['KODE_FAKTOR'];
	$faktor_strategis	= $obj->fields['FAKTOR_STRATEGIS'];
	$nilai_tambah		= $obj->fields['NILAI_TAMBAH'];
	$nilai_kurang		= $obj->fields['NILAI_KURANG'];
	$status				= $obj->fields['STATUS'];
}
?>
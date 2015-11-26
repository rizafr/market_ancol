<?php
require_once('../../../../config/config.php');

$msg 	= '';
$error 	= FALSE;

$act 	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id 	= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$nm 	= (isset($_REQUEST['nm'])) ? clean($_REQUEST['nm']) : '';

$nomor_id	 	= (isset($_REQUEST['nomor_id'])) ? clean($_REQUEST['nomor_id']) : '';
$nama 			= (isset($_REQUEST['nama'])) ? clean($_REQUEST['nama']) : '';
$no_telp 			= (isset($_REQUEST['no_telp'])) ? clean($_REQUEST['no_telp']) : '';
$email 			= (isset($_REQUEST['email'])) ? clean($_REQUEST['email']) : '';
$npwp 			= (isset($_REQUEST['npwp'])) ? clean($_REQUEST['npwp']) : '';
$nomor_kartu_identitas		= (isset($_REQUEST['nomor_kartu_identitas'])) ? clean($_REQUEST['nomor_kartu_identitas']) : '';
$alamat 		= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';
$jabatan		= (isset($_REQUEST['jabatan'])) ? to_number($_REQUEST['jabatan']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M14');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('M14', 'I');
			
			//ex_empty($nomor_id, 'No ID harus diisi.');
			ex_empty($nama, 'Nama harus diisi.');
			ex_empty($alamat, 'Alamat harus diisi.');
		
			//$query = "SELECT COUNT(NOMOR_ID) AS TOTAL FROM CLUB_PERSONAL WHERE NOMOR_ID = '$nomor_id'";
			//ex_found($conn->Execute($query)->fields['TOTAL'], "Nomor ID '$nomor_id' telah terdaftar.");
			
			$query = "SELECT COUNT(NAMA) AS TOTAL FROM CLUB_PERSONAL WHERE NAMA = '$nama' AND JABATAN_KLUB = '5'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Nama '$nama' telah terdaftar.");
			
			$query	= "(SELECT MAX(NOMOR_ID)+ 1 AS ID  FROM CLUB_PERSONAL)";
			$data = $conn->Execute($query);
			$no = intval($data->fields['ID']);
			if($no<10){
				$hasil = '0000'.$no;
			}else if($no<100){
				$hasil = '000'.$no;
			}else if($no<1000){
				$hasil = '00'.$no;
			}else if($no<10000){
				$hasil = '0'.$no;
			}else if($no<100){
				$hasil = $no;
			}


			$query = "INSERT INTO CLUB_PERSONAL (NOMOR_ID, NAMA,NOMOR_TELEPON,EMAIL,NPWP, NOMOR_KARTU_IDENTITAS,ALAMAT, JABATAN_KLUB)
			VALUES('$hasil', '$nama', '$no_telp', '$email', '$npwp', '$nomor_kartu_identitas', '$alamat', '$jabatan')";
			ex_false($conn->Execute($query), $query);		
			$msg = "Data Koordinator telah ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M14', 'U');
			
			ex_empty($nomor_id, 'Nomor id harus diisi.');
			ex_empty($nama, 'Nama harus diisi.');
			ex_empty($alamat, 'Alamat harus diisi.');
			
			if ($nomor_id != $id)
			{
				$query = "SELECT COUNT(NOMOR_ID) AS TOTAL FROM CLUB_PERSONAL WHERE NOMOR_ID = '$id'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Nomor ID \"$id\" telah terdaftar.");
			}
			
			if ($nama != $nm)
			{
				$query = "SELECT COUNT(NAMA) AS TOTAL FROM CLUB_PERSONAL WHERE NAMA = '$nama' AND NOMOR_ID != '$id'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Nama\"$nama\" telah terdaftar.");
			}
			
					
			$query = "
			UPDATE CLUB_PERSONAL
			SET NOMOR_ID 	= '$nomor_id',
				NAMA	 	= '$nama',				
				NOMOR_TELEPON	 	= '$no_telp',
				EMAIL	 			= '$email',
				NPWP	 			= '$npwp',
				NOMOR_KARTU_IDENTITAS	 	= '$nomor_kartu_identitas',
				ALAMAT		= '$alamat',	
				JABATAN_KLUB= '$jabatan'
			WHERE
				NOMOR_ID = '$id'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Koordinator berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{

				$querySrc= "SELECT COUNT(KODE_KOORDINATOR) AS TOTAL FROM SPP WHERE KODE_KOORDINATOR ='$id_del'";
				ex_found($conn->Execute($querySrc)->fields['TOTAL'], "Kode koordinator \'$id_del\' telah terdaftar.");

				echo "querySrc";
				$query = "DELETE FROM CLUB_PERSONAL WHERE NOMOR_ID = $id_del";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data koordinator berhasil dihapus.';
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
die_mod('M14');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$obj = $conn->Execute("SELECT * FROM CLUB_PERSONAL WHERE NOMOR_ID = '$id'");
	$nomor_id	= $obj->fields['NOMOR_ID'];
	$nama		= $obj->fields['NAMA'];	
	$no_telp	= $obj->fields['NOMOR_TELEPON'];
	$email		= $obj->fields['EMAIL'];
	$npwp		= $obj->fields['NPWP'];
	$nomor_kartu_identitas		= $obj->fields['NOMOR_KARTU_IDENTITAS'];
	$alamat		= $obj->fields['ALAMAT'];
	$jabatan	= $obj->fields['JABATAN_KLUB'];

}
?>
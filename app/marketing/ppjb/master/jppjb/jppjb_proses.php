<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_jenis	= (isset($_REQUEST['kode_jenis'])) ? clean($_REQUEST['kode_jenis']) : '';
$nama_jenis	= (isset($_REQUEST['nama_jenis'])) ? clean($_REQUEST['nama_jenis']) : '';
$nama_file	= (isset($_REQUEST['nama_file'])) ? clean($_REQUEST['nama_file']) : '';

//Mulai memorises data
$file_name  = $_FILES['data_upload']['name'];
$file_size  = $_FILES['data_upload']['size'];
$folder = '../../../../../config/Template/';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('P');
		ex_mod('P03');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 

		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('JB03', 'I');
			
			ex_empty($kode_jenis, 'Kode harus diisi.');
			ex_empty($nama_jenis, 'Jenis PPJB harus diisi.');
			ex_empty($nama_file, 'Nama File harus diisi.');

			//cari extensi file dengan menggunakan fungsi explode
			$explode    = explode('.',$file_name);
			$extensi    = $explode[count($explode)-1];
			
			
			//check apakah type file sudah sesuai
			if(!in_array($extensi,$file_type)){
				$eror   = true;
				$msg .= '- Type file yang anda upload tidak sesuai<br />';
			}
			if($file_size > $max_size){
				$eror   = true;
				$msg .= '- Ukuran file melebihi batas maximum<br />';
			}
			
			// Path file upload
			 //mulai memproses upload file
			
			if(move_uploaded_file($_FILES['data_upload']['tmp_name'], $folder.$file_name)){
	            //catat nama file ke database

				$query = "SELECT KODE_JENIS FROM CS_JENIS_PPJB WHERE KODE_JENIS = '$kode_jenis'";
				ex_found($conn->Execute($query)->recordcount(), "Kode \"$kode_jenis\" telah terdaftar.");

				$query = "INSERT INTO CS_JENIS_PPJB (KODE_JENIS, NAMA_JENIS, NAMA_FILE)
				VALUES(
					'$kode_jenis',
					'$nama_jenis',
					'$nama_file'
					)";
			ex_false($conn->execute($query), $query);

			$msg = "Data Jenis PPJB \"$nama_jenis\" berhasil disimpan.";
			} else{
				$err  = $_FILES['data_upload']['error'];
				$msg =  "Proses upload eror \"$err\" ";
			}
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('JB03', 'U');
			
			ex_empty($kode_jenis, 'Kode harus diisi.');
			ex_empty($nama_jenis, 'Jenis PPJB harus diisi.');
			ex_empty($nama_file, 'Nama File harus diisi.');
			
			if ($kode_jenis != $id)
			{
				$query = "SELECT KODE_JENIS FROM CS_JENIS_PPJB WHERE KODE_JENIS = '$kode_jenis'";
				ex_found($conn->Execute($query)->recordcount(), "Kode \"$kode_jenis\" telah terdaftar.");
			}


				//ubah file
				if(move_uploaded_file($_FILES['data_upload']['tmp_name'], $folder.$file_name)){
		            //catat nama file ke database
		            $msg =  "Proses upload \"$file_name\" berhasil";
				} else{
					$err  = $_FILES['data_upload']['error'];
					$msg =  "Proses upload eror";
				}
								

			$query = "SELECT * FROM CS_JENIS_PPJB WHERE KODE_JENIS = '$kode_jenis' AND NAMA_JENIS = '$nama_jenis' AND NAMA_FILE = '$nama_file'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");

			//proses ubah 			
			$query = "
			UPDATE CS_JENIS_PPJB 
			SET 
			KODE_JENIS = '$kode_jenis',
			NAMA_JENIS = '$nama_jenis',
			NAMA_FILE  = '$nama_file'
			WHERE
			KODE_JENIS = '$id'
			";			
			ex_false($conn->execute($query), $query);
			
			
			$msg = 'Data Jenis PPJB berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('JB03', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');

			foreach ($cb_data as $id_del)
			{

				$query = "SELECT JENIS FROM CS_PPJB WHERE JENIS = '$id_del'";
				ex_found($conn->Execute($query)->recordcount(), "Kode \"$id_del\" telah terdaftar. Tidak bisa dihapus");

				$select = "SELECT * FROM CS_JENIS_PPJB WHERE KODE_JENIS = '$id_del'";
				$obj = $conn->execute($select);
				$kode_jenis = $obj->fields['KODE_JENIS'];
				$nama_jenis = $obj->fields['NAMA_JENIS'];
				$nama_file  = $obj->fields['NAMA_FILE'];
				
				//delete file
				$path=$folder.$nama_file;
				if(@unlink($path)) {$msg  = "Deleted file "; 
			}else{
				$msg =   "File can't be deleted";
			} 

			$query = "DELETE FROM CS_JENIS_PPJB WHERE KODE_JENIS = $id_del";
			if ($conn->Execute($query)) {
				$act[] = $id_del;
			} else {
				$error = TRUE;
			}
		}

		$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Jenis PPJB berhasil dihapus.';
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
// die_app('P');
die_mod('P03');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Ubah')
{
	$query = "SELECT * FROM CS_JENIS_PPJB WHERE KODE_JENIS = '$id'";
	$obj = $conn->execute($query);
	$kode_jenis = $obj->fields['KODE_JENIS'];
	$nama_jenis = $obj->fields['NAMA_JENIS'];
	$nama_file  = $obj->fields['NAMA_FILE'];
}
?>
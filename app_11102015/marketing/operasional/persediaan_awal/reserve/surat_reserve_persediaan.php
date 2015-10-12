<?php
	require_once('../../../../../config/config.php');
	include '../../../../../plugin/PHPWord.php';
	ob_clean();
	die_login();
	$conn = conn($sess_db);
	die_conn($conn);
	
	//Format Tanggal Berbahasa Indonesia 
	
	// Array Hari
	$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
	$hari = $array_hari[date('N')];
	
	//Format Tanggal 
	$tanggal = date ('j');
	
	//Array Bulan 
	$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember'); 
	$bulan = $array_bulan[date('n')];
	
	//Format Tahun 
	$tahun = date('Y');
	
	
	
	$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
	$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';	
	$cb_data = array();
	$cb_data= (isset($_REQUEST['cb_data'])) ? ($_REQUEST['cb_data']) : '';	
	
	if($act == 'Surat') #Proses Hapus
	{
		$act = array();
		
		foreach ((array) $cb_data as $id)
		{	
			
			$query = "SELECT * FROM RESERVE WHERE KODE_BLOK = '$id'";
			$obj = $conn->execute($query);
			
			$kode_blok		= $obj->fields['KODE_BLOK'];
			$nama_calon_pembeli		= $obj->fields['NAMA_CALON_PEMBELI'];
			$tanggal_reserve		= tgltgl(date("d-m-Y",strtotime($obj->fields['TANGGAL_RESERVE'])));
			$berlaku_sampai			= tgltgl(date("d-m-Y",strtotime($obj->fields['BERLAKU_SAMPAI'])));
			$alamat 				= $obj->fields['ALAMAT'];
			$telepon 				= $obj->fields['TELEPON'];
			$agen 					= $obj->fields['AGEN'];
			$koordinator 			= $obj->fields['KOORDINATOR'];
			$tanggal_cetak 			= date('d-m-Y H:i:s');
			
			
			// Include the PHPWord.php, all other classes were loaded by an autoloader
			
			
			// Create a new PHPWord Object
			$PHPWord = new PHPWord();	
			
			$document = $PHPWord->loadTemplate('../../../../../config/Template/surat/spp/Surat_Reserve.doc');
			//header
			$document->setValue('tanggal_cetak', $tanggal_cetak);
			$document->setValue('kode_blok', $kode_blok);
			$document->setValue('nama_calon_pembeli', $nama_calon_pembeli);
			$document->setValue('alamat', $alamat);
			$document->setValue('telepon', $telepon);
			$document->setValue('tanggal_reserve', $tanggal_reserve);
			$document->setValue('berlaku_sampai', $berlaku_sampai);
			$document->setValue('agen', $agen);
			$document->setValue('koordinator', $koordinator);
			
			$kode_blok = explode("/",$kode_blok);
			$kode_blok = implode("",$kode_blok);
			
			$nama_calon_pembeli = explode("/",$nama_calon_pembeli);
			$nama_calon_pembeli = implode("",$nama_calon_pembeli);
			
			$nama_file= "SURAT RESERVE ".trim($kode_blok)." ".$nama_calon_pembeli." ". $tanggal . " " . $bulan . " " . $tahun .".doc";
			
			// At least write the document to webspace:
			$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
			
			// buat pathnya di C..jika belum ada, sistem langsung dibikin
			$path = 'C:SURAT_RESERVE/';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			
			//simpan file di komputer
			$document->save($path ."/". $nama_file );
			
			//simpan ke dalam array
			$file_names[]= $nama_file;
			
						
		}
			//persiapan file zip dan memanggil fungsi generate zip
			$zip_file_name="SURAT_RESERVE ". $tanggal . " " . $bulan . " " . $tahun.".zip";
			$file_path= getcwd(). '/SURAT_RESERVE/';
			zipFilesAndDownload($file_names,$zip_file_name,$path);
		
	}
	
	//fungsi untuk membuat zip
	function zipFilesAndDownload($file_names,$archive_file_name,$file_path){
		$zip = new ZipArchive();
		//create the file and throw the error if unsuccessful
		if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
			exit("cannot open <$archive_file_name>\n");
		}
		//add each files of $file_name array to archive
		foreach((array)$file_names as $files)  {
			$zip->addFile($file_path.$files,$files);     
		}
		$zip->close();
		$zipped_size = filesize($archive_file_name);
		header("Content-Description: File Transfer");
		header("Content-type: application/zip"); 
		header("Content-Type: application/force-download");// some browsers need this
		header("Content-Disposition: attachment; filename=\"" . basename($archive_file_name) . "\"");
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header("Content-Length:". " $zipped_size");
		ob_clean();
		flush();
		readfile("$archive_file_name");
		unlink("$archive_file_name"); // Now delete the temp file (some servers need this option)
		exit;   
	}
		
		
	?>	
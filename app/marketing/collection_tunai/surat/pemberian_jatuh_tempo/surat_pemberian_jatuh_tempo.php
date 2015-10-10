<?php

	require_once('../../../../../config/config.php');
	include '../../../../../plugin/PHPWord.php';
	
	ob_clean();
	die_login();
	$conn = conn($sess_db);
	die_conn($conn);

	$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
	$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';	
	$cb_data = array();
	$cb_data= (isset($_REQUEST['cb_data'])) ? ($_REQUEST['cb_data']) : '';	
	
	$tanggal_tempo		= (isset($_REQUEST['tanggal_tempo'])) ? clean($_REQUEST['tanggal_tempo']) : '';	
	$tgl_tempo 			= fm_date(date("Y-m-d", strtotime($tanggal_tempo)));
	
	if($act == 'Surat') #Proses Hapus
	{
		$act = array();
		
		foreach ((array) $cb_data as $id)
		{
			
			$query = "SELECT *, B.TANGGAL AS TGL_TEMPO FROM SPP A JOIN RENCANA B ON A.KODE_BLOK = B.KODE_BLOK
				WHERE A.KODE_BLOK = '$id'";
			$obj = $conn->execute($query);
			
			$nama_pembeli	= $obj->fields['NAMA_PEMBELI'];
			$alamat			= $obj->fields['ALAMAT_SURAT'];
			
			$TELP_KANTOR=(trim($obj->fields["TELP_KANTOR"])!="")?trim(strtoupper($obj->fields["TELP_KANTOR"])):"";
			$TELP_LAIN=(trim($obj->fields["TELP_LAIN"])!="")?",".trim(strtoupper($obj->fields["TELP_LAIN"])):"";
			$TELP_RUMAH=(trim($obj->fields["TELP_RUMAH"])!="")?",".trim(strtoupper($obj->fields["TELP_RUMAH"])):"";
			
			$telepon		= $TELP_KANTOR.$TELP_LAIN.$TELP_RUMAH;
			$tanggal_spp	= fm_date(date("Y-m-d", strtotime($obj->fields['TANGGAL_SPP'])));
			$nilai			= to_money($obj->fields['NILAI']);
			
			$query = "select STATUS_KOMPENSASI FROM SPP WHERE KODE_BLOK = '$id'";
			$obj = $conn->execute($query);
			$status			= $obj->fields['STATUS_KOMPENSASI'];
			
			$query = "select * from CS_REGISTER_CUSTOMER_SERVICE";
			$obj = $conn->execute($query);
			
			if($status == 2)
			{
				$no			= 1 + $obj->fields['NOMOR_SURAT_TUNAI'];
				$reg		= $obj->fields['REG_SURAT_TUNAI'];
			}
			else if($status == 1)
			{
				$no			= 1 + $obj->fields['NOMOR_SURAT_KPR'];
				$reg		= $obj->fields['REG_SURAT_KPR'];
			}
			
			$nomor_surat	= $no.$reg;
			$tanggal_cetak 	= kontgl(tgltgl(date("d M Y")));
			$kode_blok		= $id;
			
			$query = "select * from CS_PARAMETER_COL";
			$obj = $conn->execute($query);
			
			$nama_pejabat	= $obj->fields['NAMA_PEJABAT'];
			$nama_jabatan	= $obj->fields['NAMA_JABATAN'];
			$nama_pt		= $obj->fields['NAMA_PT'];
			
			$pecah_tanggal	= explode("-",$tanggal_tempo);
			$tgl 			= $pecah_tanggal[0];
			$bln 			= $pecah_tanggal[1];
			$thn			= $pecah_tanggal[2];
			
			if(($bln + 1) > 12)
			{
				$next_bln 	= $bln % 12;
				$next_thn 	= $thn + 1; 
			}
			else
			{
				$next_bln 	= $bln + 1;
				$next_thn 	= $thn;
			}
			
			
			if($status == 2)
			{
				$query = "update CS_REGISTER_CUSTOMER_SERVICE set NOMOR_SURAT_TUNAI = NOMOR_SURAT_TUNAI + 1";
				ex_false($conn->execute($query), $query);
			}
			else if($status == 1)
			{
				$query = "update CS_REGISTER_CUSTOMER_SERVICE set NOMOR_SURAT_KPR = NOMOR_SURAT_KPR + 1";
				ex_false($conn->execute($query), $query);
			}
			
			
			$query = "update RENCANA set NO_SURAT1 = '$nomor_surat', TANGGAL_SURAT1 = CONVERT(DATETIME,GETDATE(),105) 
			WHERE KODE_BLOK = '$id'
			AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105) 
			AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)";
			ex_false($conn->execute($query), $query);
			
	
			// Include the PHPWord.php, all other classes were loaded by an autoloader
			

			// Create a new PHPWord Object
			$PHPWord = new PHPWord();	
				
			$document = $PHPWord->loadTemplate('../../../../../surat/Surat_Pemberitahuan_Jatuh_Tempo.docx');
			//header
			$document->setValue('tanggal_cetak', $tanggal_cetak);
			$document->setValue('nomor_surat', $nomor_surat);
			$document->setValue('nama_pembeli', $nama_pembeli);
			$document->setValue('alamat', $alamat);
			$document->setValue('telepon', $telepon);
			$document->setValue('tanggal_spp', $tanggal_spp);
			$document->setValue('tanggal_tempo', $tgl_tempo);
			$document->setValue('kode_blok', $kode_blok);
			$document->setValue('nilai', $nilai);
			$document->setValue('nama_pejabat', $nama_pejabat);
			$document->setValue('nama_jabatan', $nama_jabatan);
			$document->setValue('nama_pt', $nama_pt);
			
			$kode_blok = explode("/",$kode_blok);
			$kode_blok = implode("",$kode_blok);
			
			
			$nama_file = "Surat Pemberitahuan Jatuh Tempo_".trim($kode_blok)."_".date('d F Y').".doc";
			
			// At least write the document to webspace:
			$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
			
			
			// buat pathnya di C..jika belum ada, sistem langsung dibikin
			$path = 'C:Surat_Pemberitahuan_Jatuh_Tempo/';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			
			//simpan file di komputer
			$document->save($path ."/". $nama_file );
			
			//simpan ke dalam array
			$file_names[]= $nama_file;
		}
		
		//persiapan file zip dan memanggil fungsi generate zip
		$zip_file_name="Surat_Pemberitahuan_Jatuh_Tempo_".date('d F Y').".zip";
		$file_path= getcwd(). '/Surat_Pemberitahuan_Jatuh_Tempo/';
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

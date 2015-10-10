<?php

	require_once('../../../../../config/config.php');
	ex_login();

	$conn = conn($sess_db);
	ex_conn($conn);

	$conn->begintrans(); 

	$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
	$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';	
	$tanggal_tempo		= (isset($_REQUEST['tanggal_tempo'])) ? clean($_REQUEST['tanggal_tempo']) : '';	
	
	if($act == 'Surat') #Proses Hapus
	{
		$act = array();
		$cb_data = $_REQUEST['cb_data'];
		
		foreach ($cb_data as $id_del)
		{		
			$query = "SELECT *, B.TANGGAL AS TGL_TEMPO FROM SPP A JOIN RENCANA B ON A.KODE_BLOK = B.KODE_BLOK
				WHERE A.KODE_BLOK = '$id_del'";
			$obj = $conn->execute($query);
			
			$nama_pembeli	= $obj->fields['NAMA_PEMBELI'];
			$alamat			= $obj->fields['ALAMAT_SURAT'];
			
			$TELP_KANTOR=(trim($obj->fields["TELP_KANTOR"])!="")?trim(strtoupper($obj->fields["TELP_KANTOR"])):"";
			$TELP_LAIN=(trim($obj->fields["TELP_LAIN"])!="")?",".trim(strtoupper($obj->fields["TELP_LAIN"])):"";
			$TELP_RUMAH=(trim($obj->fields["TELP_RUMAH"])!="")?",".trim(strtoupper($obj->fields["TELP_RUMAH"])):"";
			
			$telepon		= $TELP_KANTOR.$TELP_LAIN.$TELP_RUMAH;
			$tanggal_spp	= fm_date(date("Y-m-d", strtotime($obj->fields['TANGGAL_SPP'])));
			$nilai			= $obj->fields['NILAI'];
			
			$query = "select NOMOR_SURAT_JATUH_TEMPO,REG_SURAT_JATUH_TEMPO from CS_REGISTER_CUSTOMER_SERVICE";
			$obj = $conn->execute($query);
			
			$no				= 1 + $obj->fields['NOMOR_SURAT_JATUH_TEMPO'];
			$reg			= $obj->fields['REG_SURAT_JATUH_TEMPO'];
			$tahun 			= date('Y');
			$nomor_surat	= $no.$reg.$tahun;
			$tanggal_cetak 	= kontgl(tgltgl(date("d M Y")));
			$kode_blok		= $id_del;
			
			// Include the PHPWord.php, all other classes were loaded by an autoloader
			include '../../../../../plugin/PHPWord.php';

			// Create a new PHPWord Object
			$PHPWord = new PHPWord();	
				
			$document = $PHPWord->loadTemplate('../../../../../surat/Surat_Jatuh_Tempo.docx');
			//header
			$document->setValue('tanggal_cetak', $tanggal_cetak);
			$document->setValue('nomor_surat', $nomor_surat);
			$document->setValue('nama_pembeli', $nama_pembeli);
			$document->setValue('alamat', $alamat);
			$document->setValue('telepon', $telepon);
			$document->setValue('tanggal_spp', $tanggal_spp);
			$document->setValue('tanggal_tempo', $tanggal_tempo);
			$document->setValue('kode_blok', $kode_blok);
			$document->setValue('nilai', $nilai);
			
			$namafile = "Surat Jatuh Tempo"."_".date('d F Y').".doc";
			
			// At least write the document to webspace:
			$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
			
			// // save as a random file in temp file
			$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
			$document->save($temp_file);
			
			header('Content-Disposition: attachment; filename="' .rawurlencode($namafile) . '"');
			header('Content-Transfer-Encoding: binary');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Expires: 0');
			header('Pragma: public');
			flush();
			readfile($temp_file); // or echo file_get_contents($temp_file);
			unlink($temp_file);  // remove temp file
		}
		
	}		
	
	exit;


?>

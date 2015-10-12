<?php

	include('pemberian_jatuh_tempo_proses.php');

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

	 
	// Include the PHPWord.php, all other classes were loaded by an autoloader
	include '../../../../../plugin/PHPWord.php';

	// Create a new PHPWord Object
	$PHPWord = new PHPWord();
		
		
	$document = $PHPWord->loadTemplate('../../../../../surat/Surat_Pemberitahuan_Jatuh_Tempo.docx');
	//header
	$document->setValue('nama_pembeli', $nama);
	
	
	$path='E:\\';
	
	$namafile = "Surat_Pemberitahuan_Jatuh_Tempo_"."_"."_".date('d F Y').".docx";
	// $document->save('E:\\andonnikahTemplate.docx');
	//$document->save('E:\\'.$nama_file);
	
	
	// At least write the document to webspace:
	$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
	
	// // save as a random file in temp file
	$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
	$document->save($temp_file);
	
	// Your browser will name the file "myFile.docx"
	// regardless of what it's named on the server 
	
	header('Content-Disposition: attachment; filename="' .rawurlencode($namafile) . '"');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	header('Pragma: public');
	flush();
	readfile($temp_file); // or echo file_get_contents($temp_file);
	unlink($temp_file);  // remove temp file

	
	
	exit;


?>

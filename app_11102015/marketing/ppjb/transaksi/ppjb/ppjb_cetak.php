<?php
	require_once('../../../../../config/config.php');
	require_once('../../../../../config/terbilang.php');
	require_once('ppjb_proses.php');
	
	$kode_jenis_ppjb	= (isset($_REQUEST['kode_jenis_ppjb'])) ? $_REQUEST['kode_jenis_ppjb'] : '';
	$jenis_ppjb	= (isset($_REQUEST['jenis_ppjb'])) ? $_REQUEST['jenis_ppjb'] : '';
	
	//	query data pembeli
	$query = "
	SELECT *
	FROM
	CS_PARAMETER_PPJB";
	$obj = $conn->execute($query);
	
	//DATA PEMBELI
	$NAMA_PT 			= $obj->fields['NAMA_PT'];
	$NAMA_DEP 			= $obj->fields['NAMA_DEP'];
	$NAMA_PEJABAT 		= $obj->fields['NAMA_PEJABAT'];
	$NAMA_JABATAN 		= $obj->fields['NAMA_JABATAN'];
	$PEJABAT_PPJB 		= $obj->fields['PEJABAT_PPJB'];
	$JABATAN_PPJB 		= $obj->fields['JABATAN_PPJB'];
	$NOMOR_SK 			= $obj->fields['NOMOR_SK'];
	$TANGGAL_SK 		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SK'])));
	
	//query JENIS PPJB
	$query = "
	SELECT KODE_JENIS
	,NAMA_JENIS
	,NAMA_FILE
	FROM CS_JENIS_PPJB
	WHERE KODE_JENIS = '$kode_jenis_ppjb'
	";
	$obj = $conn->execute($query);
	
	//DATA JENIS PPJB
	$KODE_JENIS 			= $obj->fields['KODE_JENIS'];
	$NAMA_JENIS 			= $obj->fields['NAMA_JENIS'];
	$NAMA_FILE 				= $obj->fields['NAMA_FILE'];
	
	
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
	
	$bilangan = new Terbilang;
	
	// Include the PHPWord.php, all other classes were loaded by an autoloader
	include '../../../../../plugin/PHPWord.php';
	
	// Create a new PHPWord Object
	$PHPWord = new PHPWord();
	
	
	$template = '../../../../../config/Template/'.$NAMA_FILE;
	$nama_template = $NAMA_JENIS;
	
	if(file_exists($template)) {
		$template = $template;
		
		$document = $PHPWord->loadTemplate($template);
		
		$document->setValue('nomor_ppjb',$nomor);
		$document->setValue('hari',$hari);
		$document->setValue('tanggal',$tanggal);
		$document->setValue('bulan',$bulan);
		$document->setValue('tahun',$tahun);
		$document->setValue('tahun_terbilang',$bilangan -> eja($tahun));
		$document->setValue('nama_pembeli',$nama_pembeli);
		$document->setValue('alamat',$alamat);
		$document->setValue('NAMA_PEJABAT',$PEJABAT_PPJB);
		$document->setValue('PEJABAT_PPJB',$PEJABAT_PPJB);
		$document->setValue('JABATAN_PPJB',$JABATAN_PPJB);
		$document->setValue('NAMA_PT',$NAMA_PT);
		$document->setValue('NOMOR_SK',$NOMOR_SK);
		$document->setValue('TANGGAL_SK',$TANGGAL_SK);
		$document->setValue('luas_tanah',$luas_tanah);
		$document->setValue('luas_tanah_terbilang',$bilangan -> eja($luas_tanah));
		$document->setValue('kelurahan',$nama_kelurahan);
		$document->setValue('kecamatan',$nama_kecamatan);
		$document->setValue('luas_bangunan',$luas_bangunan);
		$document->setValue('luas_bangunan_terbilang',$bilangan -> eja($luas_bangunan));
		$document->setValue('kode_blok',$kode_blok);
		$document->setValue('tipe_bangunan',$tipe_bangunan);
		$document->setValue('harga_tanah',$harga_tanah);
		$document->setValue('harga_tanah_terbilang',$bilangan -> eja($harga_tanah));
		$document->setValue('total_harga',$total_harga);
		$document->setValue('total_harga_terbilang',$bilangan -> eja($total_harga));
		$document->setValue('total_ppn',$total_ppn);
		$document->setValue('total_ppn_terbilang',$bilangan -> eja($total_ppn));
		$document->setValue('nilai_tanda_jadi',$nilai_tanda_jadi);
		$document->setValue('nilai_tanda_jadi_terbilang',$bilangan -> eja($nilai_tanda_jadi));
		$document->setValue('sisa_pembayaran',$sisa_pembayaran);
		$document->setValue('sisa_pembayaran_terbilang',$bilangan -> eja($sisa_pembayaran));
		$document->setValue('masa_bangun',$masa_bangun);
		$document->setValue('watt',$daya_listrik);
		$document->setValue('prosen_p_hak',$prosen_p_hak);
		$document->setValue('prosen_p_hak_terbilang',$bilangan -> eja($prosen_p_hak));
		$document->setValue('prosen_p_hak',$prosen_p_hak);
		$document->setValue('prosen_p_hak_terbilang',$bilangan -> eja($prosen_p_hak));
		
		$path='E:\\';
		
		$nama_file= "PPJB ".$nama_template." ".$nama_pembeli." ". $tanggal . " " . $bulan . " " . $tahun .".doc";
		// $document->save('E:\\andonnikahTemplate.docx');
		//$document->save('E:\\'.$nama_file);
		
		
		// At least write the document to webspace:
		$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
		
		// // save as a random file in temp file
		$temp_file = tempnam(sys_get_temp_dir(), 'PHPWord');
		$document->save($temp_file);
		
		// Your browser will name the file "myFile.docx"
		// regardless of what it's named on the server 
		
		header("Content-Disposition: attachment; filename=\"" . basename($nama_file) . "\"");
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');
		header('Pragma: public');
		flush();
		readfile($temp_file); // or echo file_get_contents($temp_file);
		unlink($temp_file);  // remove temp file
		
		
		
		exit;
		
		} else {
			echo "<script>
			alert('Template not found');
			close();
			</script>";
		}
		
		
	
?>

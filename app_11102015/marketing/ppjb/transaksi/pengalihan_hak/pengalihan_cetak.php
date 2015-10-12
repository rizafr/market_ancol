<?php
	require_once('../../../../../config/config.php');
	require_once('../../../../../config/terbilang.php');
	require_once('pengalihan_hak_proses.php');
	
	$no_ppjb_hak	= (isset($_REQUEST['no_ppjb_hak'])) ? $_REQUEST['no_ppjb_hak'] : '';
	
		
	//query pengalihan hak
	$query = "
	SELECT *
	FROM
		CS_PENGALIHAN_HAK a
		LEFT JOIN CS_PPJB z ON a.KODE_BLOK = z.KODE_BLOK
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
		LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
		LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
		LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
		LEFT JOIN KELURAHAN g ON z.KODE_KELURAHAN = g.KODE_KELURAHAN
		LEFT JOIN KECAMATAN h ON z.KODE_KECAMATAN = h.KODE_KECAMATAN
	WHERE a.NO_PPJB_PH = '$no_ppjb_hak'
	";
	$obj = $conn->execute($query);
	
	//DATA pengalihan hak
	$kode_blok	 			= $obj->fields['KODE_BLOK'];
	$no_ppjb_ph	 			= $obj->fields['NO_PPJB_PH'];
	$no_ppjb_awal 			= $obj->fields['NO_PPJB_AWAL'];
	$tanggal_ppjb_awal		= f_tgl($obj->fields['TANGGAL_PPJB_AWAL']);
	$harga_awal				= $obj->fields['HARGA_AWAL'];
	$pihak_pertama			= $obj->fields['PIHAK_PERTAMA'];
	$no_id					= $obj->fields['NO_ID_PIHAK_PERTAMA'];
	$alamat_pihak_pertama	= $obj->fields['ALAMAT_PIHAK_PERTAMA'];
	$no_telp_pihak_pertama	= $obj->fields['NO_TELP_PIHAK_PERTAMA'];
	$no_hp_pihak_pertama	= $obj->fields['NO_HP_PIHAK_PERTAMA'];
	$email_pihak_pertama	= $obj->fields['EMAIL_PIHAK_PERTAMA'];
	$suami_istri			= $obj->fields['SUAMI_ISTRI'];
	$no_fax_pihak_pertama	= $obj->fields['NO_FAX_PIHAK_PERTAMA'];
	
	$tanggal				= tgltgl(f_tgl($obj->fields['TANGGAL']));
	$tanggal_permohonan		= tgltgl(f_tgl($obj->fields['TANGGAL_PERMOHONAN']));
	$tanggal_persetujuan	= tgltgl(f_tgl($obj->fields['TANGGAL_PERSETUJUAN']));
	$harga_pengalihan_hak	= $obj->fields['HARGA_PENGALIHAN_HAK'];
	$biaya_pengalihan_hak	= $obj->fields['BIAYA_PENGALIHAN_HAK'];
	$masa_bangun			= $obj->fields['MASA_BANGUN'];
	$keterangan				= $obj->fields['KETERANGAN'];
	
	$pihak_kedua			= $obj->fields['PIHAK_KEDUA'];
	$no_id_pihak_kedua		= $obj->fields['NO_ID_PIHAK_KEDUA'];
	$alamat_pihak_kedua		= $obj->fields['ALAMAT_PIHAK_KEDUA'];
	$no_telp_pihak_kedua	= $obj->fields['NO_TELP_PIHAK_KEDUA'];
	$no_hp_pihak_kedua		= $obj->fields['NO_HP_PIHAK_KEDUA'];
	$email_pihak_kedua		= $obj->fields['EMAIL_PIHAK_KEDUA'];
	$suami_istri_hak		= $obj->fields['NAMA_SUAMI_ISTRI'];
	$no_fax_pihak_kedua		= $obj->fields['NO_FAX_PIHAK_KEDUA'];
	$tipe_bangunan 		= $obj->fields['TIPE_BANGUNAN'];
	$luas_tanah 		= $obj->fields['LUAS_TANAH'];
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	
	$kode_kelurahan		= $obj->fields['KODE_KELURAHAN'];
	$nama_kelurahan		= $obj->fields['NAMA_KELURAHAN'];
	$kode_kecamatan		= $obj->fields['KODE_KECAMATAN'];
	$nama_kecamatan		= $obj->fields['NAMA_KECAMATAN'];
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
	
	
	$template = '../../../../../config/Template/PPJB_Pengalihan_Hak.docx';
	
	if(file_exists($template)) {
		$template = $template;
		
		$document = $PHPWord->loadTemplate($template);
		
		$document->setValue('kode_blok',$kode_blok);
		$pecah =explode("/",$kode_blok);
		$pecah2=explode("-",$pecah[1]);
		$nomor_unit = $pecah2[1];
		$document->setValue('nomor_unit',$nomor_unit);
		$document->setValue('no_ppjb_ph',$no_ppjb_ph);
		$document->setValue('no_ppjb_awal',$no_ppjb_awal);
		$document->setValue('hari',$hari);
		$document->setValue('bulan',$bulan);
		$document->setValue('tahun',$tahun);
		$document->setValue('tahun_terbilang',$bilangan -> eja($tahun));
		
		$document->setValue('tanggal_ppjb_awal',$tanggal_ppjb_awal);
		$document->setValue('harga_awal',to_money($harga_awal));
		
		$document->setValue('tanggal',$tanggal);
		$document->setValue('tanggal_permohonan',$tanggal_permohonan);
		$document->setValue('tanggal_persetujuan',$tanggal_persetujuan);
		$document->setValue('harga_pengalihan_hak',to_money($harga_pengalihan_hak));
		$document->setValue('harga_pengalihan_hak_terbilang',$bilangan -> eja($harga_pengalihan_hak));
		$document->setValue('kelurahan',$nama_kelurahan);
		$document->setValue('kecamatan',$nama_kecamatan);
		$document->setValue('tipe_bangunan',$tipe_bangunan);
		$document->setValue('masa_bangun',$masa_bangun);
		$document->setValue('masa_bangun_terbilang',$bilangan -> eja($masa_bangun));
		$document->setValue('biaya_pengalihan_hak',$biaya_pengalihan_hak);
		$document->setValue('luas_bangunan',$luas_bangunan);
		$document->setValue('luas_bangunan_terbilang',$bilangan -> eja($luas_bangunan));
		$document->setValue('luas_tanah',$luas_tanah);
		$document->setValue('luas_tanah_terbilang',$bilangan -> eja($luas_tanah));
		
		$document->setValue('pihak_pertama',$pihak_pertama);
		$document->setValue('alamat_pihak_pertama',$alamat_pihak_pertama);
		$document->setValue('no_telp_pihak_pertama',$no_telp_pihak_pertama);
		$document->setValue('no_hp_pihak_pertama',$no_hp_pihak_pertama);
		$document->setValue('no_fax_pihak_pertama',$no_fax_pihak_pertama);
		$document->setValue('email_pihak_pertama',$email_pihak_pertama);
		$document->setValue('suami_istri',$suami_istri);
		
		$document->setValue('pihak_kedua',$pihak_kedua);
		$document->setValue('alamat_pihak_kedua',$alamat_pihak_kedua);
		$document->setValue('no_telp_pihak_kedua',$no_telp_pihak_kedua);
		$document->setValue('no_hp_pihak_kedua',$no_hp_pihak_kedua);
		$document->setValue('email_pihak_kedua',$email_pihak_kedua);
		$document->setValue('no_fax_pihak_kedua',$no_fax_pihak_kedua);
		
		
		
		
	
		$path='E:\\';
		
		$nama_file= "PENGALIHAK HAK ".$pihak_pertama."-".$pihak_kedua." (". $tanggal . " " . $bulan . " " . $tahun .").doc";
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

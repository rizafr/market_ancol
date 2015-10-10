<?php

	require_once('../../../../../../config/config.php');
	include '../../../../../../plugin/PHPWord.php';
	
	die_login();
	$conn = conn($sess_db);
	die_conn($conn);

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
			$nilai			= to_money($obj->fields['NILAI']);
			
			$query = "select NOMOR_SURAT_TUNAI,REG_SURAT_TUNAI from CS_REGISTER_CUSTOMER_SERVICE";
			$obj = $conn->execute($query);
			
			$no				= 1 + $obj->fields['NOMOR_SURAT_TUNAI'];
			$reg			= $obj->fields['REG_SURAT_TUNAI'];
			$nomor_surat	= $no.$reg;
			$tanggal_cetak 	= kontgl(tgltgl(date("d M Y")));
			$kode_blok		= $id_del;
			
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
			
			$query = "update CS_REGISTER_CUSTOMER_SERVICE set NOMOR_SURAT_TUNAI = NOMOR_SURAT_TUNAI + 1";
			ex_false($conn->execute($query), $query);
			
			$query = "update RENCANA set NO_SURAT1 = '$nomor_surat', TANGGAL_SURAT1 = CONVERT(DATETIME,GETDATE(),105) 
			WHERE KODE_BLOK = '$id_del'
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
			$document->setValue('tanggal_tempo', $tanggal_tempo);
			$document->setValue('kode_blok', $kode_blok);
			$document->setValue('nilai', $nilai);
			
			$namafile = "Surat Pemberitahuan Jatuh Tempo "."_".date('d F Y').".doc";
			
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

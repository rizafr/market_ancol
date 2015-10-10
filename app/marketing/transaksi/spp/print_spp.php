<?php

	require_once('../../../../config/config.php');
	require_once('../../../../config/terbilang.php');
	require_once('spp_proses.php');
	$terbilang = new Terbilang;
	
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
			include '../../../../plugin/PHPWord.php';

			// Create a new PHPWord Object
			$PHPWord = new PHPWord();	
			
			if($sess_db == 'JAYA'){
				$document = $PHPWord->loadTemplate('../../../../config/Template/surat/spp/SPP_Bintaro.docx');
			}else
			if($sess_db == 'SERPONG_BETA'){
				if($lokasi == 1){
					$document = $PHPWord->loadTemplate('../../../../config/Template/surat/spp/SPP_SJ_De_Garden.docx');
				}else
				if($lokasi == 2){
					$document = $PHPWord->loadTemplate('../../../../config/Template/surat/spp/SPP_SJ_De_View.docx');
				}else
				if($lokasi == 4){
					$document = $PHPWord->loadTemplate('../../../../config/Template/surat/spp/SPP_SJ_The_Spring.docx');
				}
			}
			

			//header
			if($nup == ''){
				$document->setValue('nup', '-');
			}else{
				$document->setValue('nup', $nup);
			}
			$document->setValue('nomor_spp', $no_spp);
			$document->setValue('nama', $nama);
			$document->setValue('alamat_ktp_1', $alamat_rumah);
			if($alamat_surat == ''){
				$document->setValue('alamat_surat_2', '-');
			}else{
				$document->setValue('alamat_surat_2', $alamat_surat);
			}
			
			$document->setValue('alamat_email', $email);
			if($tlp_lain == ''){
				$document->setValue('telepon', '-');
			}else{
				$document->setValue('telepon', $tlp_lain);
			}
			
			if($npwp == ''){
				$document->setValue('npwp', '-');
			}else{
				$document->setValue('npwp', $npwp);
			}
			
			if($tgl_cair_kpr == ''){
				$document->setValue('tgl_rencana_kpr', '-');
			}else{
				$document->setValue('tgl_rencana_kpr', $tgl_cair_kpr);
			}
			
			if($no_identitas == ''){
				$document->setValue('nomor_id', '-');
			}else{
				$document->setValue('nomor_id', $no_identitas);
			}
			if ($jenis_npwp == '1'){
				$document->setValue('status_pkp', '-');
				$document->setValue('status_non_pkp', 'X');
			}else 
			if ($jenis_npwp == '2'){
				$document->setValue('status_pkp', 'X');
				$document->setValue('status_non_pkp', '-');
			}else{
				$document->setValue('status_pkp', '-');
				$document->setValue('status_non_pkp', '-');
			}
			$document->setValue('kode_blok', $id);
			
			if($no_customer == ''){
				$document->setValue('no_va', '-');
			}else{
				$document->setValue('no_va', $no_customer);
			}
			
			$document->setValue('tipe', $r_tipe_bangunan);
			$document->setValue('luas_tanah', $r_luas_tanah);
			$document->setValue('luas_bangunan', $r_luas_bangunan);
			$document->setValue('harga', to_money($r_base_total_harga).".00");
			$document->setValue('prosen_potongan', $r_base_potongan);
			$document->setValue('nilai_potongan', to_money($r_base_nilai_potongan).".00");
			$document->setValue('harga_net', to_money($r_harga_net).".00");
			$document->setValue('nilai_ppn', to_money($r_base_nilai_ppn).".00");
			$document->setValue('harga_setelah_ppn', to_money($r_harga_setelah_ppn).".00");
			$document->setValue('nilai_kpr', to_money($jumlah_kpr).".00");
			$document->setValue('sisa_1', to_money($r_base_sisa_1).".00");
			$document->setValue('tanda_jadi', to_money($tanda_jadi).".00");
			$document->setValue('sisa_2', to_money($r_base_sisa_2).".00");
			$document->setValue('tgl_tanda_jadi',$tgl_tanda_jadi);
			$document->setValue('tanggal_spp',date('d M o'));
			$document->setValue('keterangan_1',$keterangan);
			//$document->setValue('terbilang', $n_terbilang." rupiah");
			
			$query = "
			SELECT *
			FROM 
				RENCANA a
			LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
			WHERE a.KODE_BLOK = '$id'
			ORDER BY a.TANGGAL
			";
			$obj = $conn->execute($query);
			$i = 1;
			$tanggal = array();
			$nilai = array();
			$counter = array();
			while( ! $obj->EOF)
			{
				$tanggal[] = tgltgl(f_tgl($obj->fields['TANGGAL']));
				$nilai[] = to_money($obj->fields['NILAI']);
				$counter[] = $i;
				$data1 = array(
					'tanggal' =>$tanggal,
					'counter' => $counter,
					'nilai' => $nilai,
				);	
				
				$i++;
				$obj->movenext();
			}
			
			while($i <= 24)
			{
				$tanggal[] = '';
				$nilai[] = '';
				$counter[] = $i;
				$data1 = array(
					'tanggal' =>$tanggal,
					'counter' => $counter,
					'nilai' => $nilai,
				);	
				$i++;
			}
			$document->cloneRow('TGL',$data1);
			
			$namafile = "SPP"."_".$id." ".date('d F Y').".doc";
			
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
		
	
	exit;


?>

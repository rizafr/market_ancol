<?php
require_once('../../../../../config/config.php');

	// menggunakan class phpExcelReader
require('../../../../../config/PHPExcel.php');
require('../../../../../config/PHPExcel/IOFactory.php');


$msg = '';
$error = FALSE;
$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$path = (isset($_FILES['file']['name'])) ? clean($_FILES['file']['name']) : '';

$lokasi				= '';
$jenis_unit			= '';
$tipe_bangunan		= '';
$jenis_penjualan	= '';


$eror       = false;
$folder     = 'upload/';
	//type file yang bisa diupload
$file_type  = array('xls','xlsx');
	//tukuran maximum file yang dapat diupload
	$max_size   = 100000000; // 100MB
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		try
		{
			ex_login();
			//ex_app('A01');
			//ex_mod('PO01');
			$conn = conn($sess_db);
			ex_conn($conn);
			
			$conn->begintrans();
			
			//Mulai memorises data
			$file_name  = $_FILES['data_upload']['name'];
			$file_size  = $_FILES['data_upload']['size'];
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
			move_uploaded_file($_FILES['data_upload']['tmp_name'], './' . $_FILES['data_upload']['name']);
			
			
			// Load PHPExcel
			$objPHPExcel = PHPExcel_IOFactory::load($file_name);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle = $worksheet->getTitle();
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
				// echo "<br>Worksheet " . $worksheetTitle . " memiliki ";
				// echo $nrColumns . ' kolom (A-' . $highestColumn . ') ';
				// echo ' dan ' . $highestRow . ' baris.';
				// echo '<br>Data: <table border="1"><tr>';
				for ($row = 1; $row <= $highestRow; ++$row) {
					// echo '<tr>';
					for ($col = 0; $col < $highestColumnIndex; ++$col) {
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						$val = $cell->getValue();
						$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
						// echo '<td>' . $val . '<br>(Typ ' . $dataType . ')</td>';
					}
					// echo '</tr>';
				}
				// echo '</table>';
			}
			
			//penambahan status jumlah
			$jumlah_berhasil = 0;
			$jumlah_gagal = 0;
			$kode_blok_gagal = array();
			
			
			// Proses perulangan baris file excel yang diupload
			for ($row = 2; $row <= $highestRow; ++$row) {
				$val = array();
				for ($col = 0; $col < $highestColumnIndex; ++$col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val[] = $cell->getValue();
				}
				
				
				
				// Skip data jika kode_blok dan va sudah ada
				$kode_blok		= $val[0];
				$kode_blok		= (!empty($kode_blok)) ? clean($kode_blok) : '';
				$virtual_account		= $val[1];
				$virtual_account = (!empty($virtual_account)) ? clean($virtual_account) : '';
				
				$query = "
				SELECT COUNT(KODE_BLOK) AS TOTAL FROM STOK WHERE KODE_BLOK = '$kode_blok' OR NO_VA = '$virtual_account'
				";
				$total_data = $conn->Execute($query)->fields['TOTAL'];
				
				
				$jumBaris = $row -1;
				$jumData = $highestRow -1;
				
				
				if ($total_data == 0) {
					
					$kode_unit 				= $val[2];
					$kode_desa 				= $val[3];
					$kode_lokasi 			= $val[4];
					$kode_sk_tanah 			= $val[5];
					$kode_faktor 			= $val[6];
					$kode_tipe 				= $val[7];
					$kode_sk_bangunan 		= $val[8];
					$kode_penjualan 		= $val[9];
					$luas_tanah 			= $val[10];
					$luas_bangunan 			= $val[11];
					$ppn_tanah 				= $val[12];
					$ppn_bangunan 			= $val[13];
					$disc_tanah 			= $val[14];
					$disc_bangunan 			= $val[15];
					$progress 				= $val[16];
					$class					= $val[17];
					$status_stok 			= $val[18];
					$terjual 				= $val[19];
					$program 				= $val[20];
					$status_gambar_siteplan = $val[21];
					$status_gambar_lapangan = $val[22];
					$status_gambar_gs 		= $val[23];
					
					
					
					$kode_desa		= (!empty($kode_desa)) ? clean($kode_desa) : '';
					$kode_lokasi	= (!empty($kode_lokasi)) ? clean($kode_lokasi) : '';
					$kode_unit		= (!empty($kode_unit)) ? clean($kode_unit) : '';
					$kode_sk_tanah	= (!empty($kode_sk_tanah)) ? clean($kode_sk_tanah) : '';
					$kode_faktor	= (!empty($kode_faktor)) ? clean($kode_faktor) : '';
					$kode_tipe		= (!empty($kode_tipe)) ? clean($kode_tipe) : '';
					$kode_sk_bangunan = (!empty($kode_sk_bangunan)) ? clean($kode_sk_bangunan) : '';
					$kode_penjualan	= (!empty($kode_penjualan)) ? clean($kode_penjualan) : '';
					
					$class					= (!empty($class)) ? clean($class) : '';
					$status_gambar_siteplan	= (!empty($status_gambar_siteplan)) ? to_number($status_gambar_siteplan) : '0';
					$status_gambar_lapangan	= (!empty($status_gambar_lapangan)) ? to_number($status_gambar_lapangan) : '0';
					$status_gambar_gs		= (!empty($status_gambar_gs)) ? to_number($status_gambar_gs) : '0';
					$program				= (!empty($program)) ? to_number($program) : '0';
					
					$luas_tanah			= (!empty($luas_tanah)) ? to_decimal($luas_tanah) : '0';
					$disc_tanah			= (!empty($disc_tanah)) ? to_decimal($disc_tanah, 16) : '0';
					$harga_disc_tanah	= (!empty($harga_disc_tanah)) ? to_number($harga_disc_tanah) : '0';
					$ppn_tanah			= (!empty($ppn_tanah)) ? to_decimal($ppn_tanah) : '0';
					
					$luas_bangunan	= (!empty($luas_bangunan)) ? to_decimal($luas_bangunan) : '0';
					$disc_bangunan	= (!empty($disc_bangunan)) ? to_decimal($disc_bangunan, 16) : '0';
					$ppn_bangunan	= (!empty($ppn_bangunan)) ? to_decimal($ppn_bangunan) : '0';
					
					$query = "
					INSERT INTO STOK 
					(
						NO_VA,KODE_BLOK, KODE_UNIT, KODE_DESA, KODE_LOKASI, KODE_SK_TANAH, 
						KODE_FAKTOR, KODE_TIPE, KODE_SK_BANGUNAN, KODE_PENJUALAN, 
						
						LUAS_TANAH, LUAS_BANGUNAN, 
						PPN_TANAH, PPN_BANGUNAN, 
						DISC_TANAH, DISC_BANGUNAN, 
						
						PROGRESS, 
						
						CLASS, STATUS_STOK, TERJUAL, PROGRAM,
						
						STATUS_GAMBAR_SITEPLAN, 
						STATUS_GAMBAR_LAPANGAN, 
						STATUS_GAMBAR_GS
						)
VALUES
(
	'$virtual_account','$kode_blok', $kode_unit, $kode_desa, $kode_lokasi, $kode_sk_tanah, 
	$kode_faktor, $kode_tipe, $kode_sk_bangunan, $kode_penjualan, 
	
	$luas_tanah, $luas_bangunan, 
	$ppn_tanah, $ppn_bangunan, 
	$disc_tanah, $disc_bangunan, 
	
	0, 
	
	'$class', '0', '0', '$program', 
	
	'$status_gambar_siteplan', 
	'$status_gambar_lapangan', 
	'$status_gambar_gs'
	)					
";							

ex_false($conn->Execute($query), $query);		

				} else{ //cek data yang gagal
					
					
					$kode_blok_gagal[]= $kode_blok;
				}
				
				$conn->committrans(); 
				//hitung jumlah gagal
				$jumlah_gagal+=$total_data;
				//hitung jumlah berhasil
				$jumlah_berhasil = $jumData - $jumlah_gagal;	
			}
			
			// Hapus file excel ketika data sudah masuk ke tabel
			@unlink($file_name);
			$msg = " Data berhasil diupload \n ". $jumlah_berhasil." data sukses \n ". $jumlah_gagal." data Gagal \n \n Kode Blok yang Gagal:\n "
			;
			
			
			
			
		}
		catch(Exception $e)
		{
			$msg = $e->getmessage();
			$error = TRUE;
			if ($conn) { $conn->rollbacktrans(); } 
		}
		
		close($conn);
		$json = array('act' => $act, 'error'=> $error, 'msg' => $msg);
		// echo json_encode($val);	
		echo $msg;
		
		//kode blok yang gagal
		$jum = count($kode_blok_gagal);
		if($jum>0){
			foreach($kode_blok_gagal as $kode_blok_gagal){
				$kode_blok_gagal = $kode_blok_gagal;
				echo $kode_blok_gagal ."\n";
			};
			echo "\nKet: Duplikasi Kode Blok atau Virtual Account"	;
		}
		
		exit;
	}
	
	die_login();
	
	?>							
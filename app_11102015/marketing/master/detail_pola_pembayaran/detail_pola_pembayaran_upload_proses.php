<?php

	require_once('../../../../config/config.php');
	
	// menggunakan class phpExcelReader
	require('../../../../config/PHPExcel.php');
	require('../../../../config/PHPExcel/IOFactory.php');
	
	
	$msg = '';
	$error = FALSE;
	$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
	$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
	$pola_bayar = (isset($_REQUEST['pola_bayar'])) ? clean($_REQUEST['pola_bayar']) : '';
	$path = (isset($_FILES['file']['name'])) ? clean($_FILES['file']['name']) : '';

	$harga_tanah			= 0;
	$harga_bangunan			= 0;
	
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
			ex_app('M');
			ex_mod('M27');
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

			if(file_exists($file_name)){
				echo 'tidak ada';
			}
			$objPHPExcel = PHPExcel_IOFactory::load($file_name);

			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle = $worksheet->getTitle();
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
				//echo "<br>Worksheet " . $worksheetTitle . " memiliki ";
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
			// Proses perulangan baris file excel yang diupload
			for ($row = 2; $row <= $highestRow; ++$row) {
				$val = array();
				for ($col = 0; $col < $highestColumnIndex; ++$col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val[] = $cell->getValue();
				}
								
				// Skip data jika kode_blok dan va sudah ada
				// $kode_blok		= $val[0];
				// $kode_blok		= (!empty($kode_blok)) ? clean($kode_blok) : '';
				// $virtual_account		= $val[1];
				// $virtual_account = (!empty($virtual_account)) ? clean($virtual_account) : '';
				
				$kode_blok		 	= $val[0];
				$kode_pola_bayar	= $pola_bayar;
				
				$query = "
				SELECT COUNT(KODE_BLOK) AS TOTAL FROM DETAIL_POLA_BAYAR WHERE KODE_BLOK = '$kode_blok' 
				AND KODE_POLA_BAYAR = $kode_pola_bayar
				";
				$total_data = $conn->Execute($query)->fields['TOTAL'];
				
				$jumBaris = $row -1;
				$jumData = $highestRow -1;
			
				//bila data telah ada maka hapus dahulu data sebelumnya
				if ($total_data > 0) {
					$conn->Execute("DELETE FROM DETAIL_POLA_BAYAR WHERE KODE_BLOK = '$kode_blok' 
					AND KODE_POLA_BAYAR = $kode_pola_bayar
					");
				}
				
				$harga_tanah		= $val[1];
				$harga_bangunan		= $val[2];
				
				$harga_tanah		= (!empty($harga_tanah)) ? to_number($harga_tanah) : '0';
				$harga_bangunan		= (!empty($harga_bangunan)) ? to_number($harga_bangunan) : '0';
				
				$query = "
				INSERT INTO DETAIL_POLA_BAYAR 
				(
				KODE_BLOK, KODE_POLA_BAYAR, HARGA_TANAH, HARGA_BANGUNAN 
				)
				VALUES
				(
				'$kode_blok', $kode_pola_bayar, $harga_tanah, $harga_bangunan
				)					
				";					
				ex_false($conn->Execute($query), $query);

				$query = "SELECT count(*) AS TOTAL from HARGA_TANAH WHERE HARGA_TANAH = '$harga_tanah' and STATUS = '1'";
				$qty_sk = $conn->Execute($query)->fields['TOTAL'];
				if($qty_sk<1){
					$query = "INSERT INTO HARGA_TANAH(KODE_SK,KODE_LOKASI,TANGGAL,HARGA_TANAH,STATUS) VALUES((SELECT MAX(KODE_SK)+1 FROM HARGA_TANAH),0,GETDATE(),$harga_tanah,'1')";
					ex_false($conn->Execute($query), $query);					
				}				
				
				$query = "SELECT count(*) AS TOTAL from HARGA_BANGUNAN WHERE HARGA_BANGUNAN = '$harga_bangunan' and STATUS = '1'";
				$qty_sk = $conn->Execute($query)->fields['TOTAL'];
				if($qty_sk<1){
					$query = "INSERT INTO HARGA_BANGUNAN(KODE_SK,KODE_LOKASI,TANGGAL,JENIS_BANGUNAN,HARGA_BANGUNAN,STATUS) VALUES((SELECT MAX(KODE_SK)+1 FROM HARGA_BANGUNAN),0,GETDATE(),'0',$harga_bangunan,'1')";
					ex_false($conn->Execute($query), $query);					
				}

				$conn->committrans(); 
				//hitung jumlah gagal
				$jumlah_gagal += $total_data;
				//hitung jumlah berhasil
				$jumlah_berhasil = $jumData - $jumlah_gagal;	
			}
			
			// Hapus file excel ketika data sudah masuk ke tabel
			//@unlink($file_name);
			$msg = " Data berhasil diupload \n ". $jumlah_berhasil." data baru \n ". $jumlah_gagal." data replace " ;
			
			
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
		exit;
	}
	
	die_login();
	
?>							
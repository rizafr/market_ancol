<?php

	require_once('../../../../config/config.php');
	
	// menggunakan class phpExcelReader
	require('../../../../config/PHPExcel.php');
	require('../../../../config/PHPExcel/IOFactory.php');
	
	
	$msg = '';
	$error = FALSE;
	$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
	$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
	$path = (isset($_FILES['file']['name'])) ? clean($_FILES['file']['name']) : '';

	$harga_cash_keras		= 0;
	$CB36x					= 0;
	$CB48x					= 0;
	$KPA24x					= 0;
	$KPA36x					= 0;
	
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
				
				$kode_sk		 	= $val[0];		
				$kode_blok  		= $val[1];	
				$query = "
				SELECT COUNT(KODE_BLOK) AS TOTAL FROM HARGA_SK WHERE KODE_SK = '$kode_sk' AND KODE_BLOK= '$kode_blok'
				";
				$total_data = $conn->Execute($query)->fields['TOTAL'];
				
				$jumBaris = $row -1;
				$jumData = $highestRow -1;
			
				//bila data telah ada maka hapus dahulu data sebelumnya
				if ($total_data > 0) {
					$conn->Execute("DELETE FROM HARGA_SK WHERE KODE_SK = '$kode_sk' AND KODE_BLOK= '$kode_blok'
					");
				}
			
      			
      			$tanggal = date("d-m-Y H:i:s");
				$harga_cash_keras		=  $val[2];
				$CB36x					=  $val[3];
				$CB48x					=  $val[4];
				$KPA24x					=  $val[5];
				$KPA36x					=  $val[6];
				$status					=	'1';

				
				
				$query = "
				INSERT INTO HARGA_SK (KODE_SK, KODE_BLOK, TANGGAL, STATUS, HARGA_CASH_KERAS, CB36X, CB48X, KPA24X, KPA36X)
					VALUES (
						'$kode_sk',
					 	'$kode_blok', 
						CONVERT(DATETIME,'$tanggal',105), 
						$status, $harga_cash_keras, $CB36x, $CB48x, $KPA24x, $KPA36x)		
				";					
				ex_false($conn->Execute($query), $query);

				
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
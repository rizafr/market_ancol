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
				
			// Proses perulangan baris file excel yang diupload
			for ($row = 2; $row <= $highestRow; ++$row) {
				$val = array();
				for ($col = 0; $col < $highestColumnIndex; ++$col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val[] = $cell->getValue();
				}
				
				$jumBaris = $row -1;
				$jumData = $highestRow -1;
								
				// Skip data jika nomor_va dan tanggal sudah ada
				$nomor_va		= $val[0];
				$tanggal		= $val[1];
				$nilai			= $val[2];
				
				
				$nomor_va		= (!empty($nomor_va)) ? clean($nomor_va) : '';
				$tanggal		= (!empty($tanggal)) ? clean($tanggal) : '';
				$nilai			= (!empty($nilai)) ? clean($nilai) : '';
				
				
				$query = "
				SELECT COUNT(TANGGAL) AS TOTAL FROM PEMBAYARAN WHERE NOMOR_VA = '$nomor_va'
				";
				$total_data = $conn->Execute($query)->fields['TOTAL'];
				
			
				
				
				if ($total_data == 0) {
								
					
					$query = "INSERT INTO PEMBAYARAN (NOMOR_VA, TANGGAL, NILAI, STATUS)
					VALUES(
						'$nomor_va',
						CONVERT(DATETIME,'$tanggal',105),
						'$nilai','0'
					)";
					
					
					ex_false($conn->Execute($query), $query);
					
					
				} 
				$conn->committrans(); 
				
				//hitung jumlah gagal
				$jumlah_gagal+=$total_data;
				//hitung jumlah berhasil
				$jumlah_berhasil = $jumData - $jumlah_gagal;	
			}
			
			// Hapus file excel ketika data sudah masuk ke tabel
			@unlink($file_name);
			$msg = " Data berhasil diupload \n ". $jumlah_berhasil." data sukses \n ". $jumlah_gagal." data Gagal " ;
			
			if ($act == 'Hapus') # Proses Hapus
			{
				//ex_ha('COF02', 'D');
				
				$act = array();
				$cb_data = $_REQUEST['cb_data'];
				ex_empty($cb_data, 'Pilih data yang akan dihapus.');
				
				foreach ($cb_data as $id_del)
				{
					$query = "DELETE FROM CS_VIRTUAL_ACCOUNT WHERE NOMOR_VA = '$id_del'";
					if ($conn->Execute($query)) {
						$act[] = $id_del;
					} else {
						$error = TRUE;
					}
				}		
				
				$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data virtual account berhasil dihapus.';
				
				$conn->committrans(); 
			}
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
//die_app('C01');
//die_mod('COF02');
$conn = conn($sess_db);
die_conn($conn);
	
?>							
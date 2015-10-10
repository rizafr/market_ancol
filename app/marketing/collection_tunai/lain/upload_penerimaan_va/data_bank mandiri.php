<?php
	require_once('../../../../../config/config.php');

	// menggunakan class phpExcelReader
	require('../../../../../config/PHPExcel.php');
	require('../../../../../config/PHPExcel/IOFactory.php');
	
	$inputFileType = 'CSV';
	$inputFileName = 'testFile.csv';
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($uploaded_file);
	// Load PHPExcel
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
	$i=1;
	$j=1;
	$k=1;
	$l=1;
	$m=1;
	
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
		$nilai			= $val[8];
		
		
		$nomor_va				= (!empty($nomor_va)) ? clean($nomor_va) : '';
		$tanggal_transaksi		= (!empty($tanggal)) ? clean ($tanggal) : '';
		$tmp 					= explode('/', $tanggal_transaksi);
		$a = $tmp[2];
		$tmp[2] = $tmp[0];
		$tmp[0] = $a;
		$tanggal_transaksi 		= implode('-', $tmp);

		$tanggal_transaksi 		= date("d/m/Y", strtotime($tanggal_transaksi));
		$nilai					= (!empty($nilai)) ? clean($nilai) : '';
		$nilai_out				= str_replace(',', '#', $nilai);
		$nilai_out 				=str_replace('.', ',', $nilai_out);
		$nilai_out				= str_replace('#', '.',$nilai_out);
		$nilai 					= str_replace(',', '', $nilai);
		
		//PEMOTONGAN 7 KARAKTER TERAHIR
		
	?>
	<tr>
		<td><?php echo $i++ ?></td>
		<td><input type="checkbox" name="cb_data[<?php echo $j++; ?>]" value="<?php echo $nomor_va; ?>" class="cb_data"></td>
		<td><input type="hidden" name="nomor_va[<?php echo $k++; ?>]" value="<?php echo $nomor_va; ?>"><?php echo $nomor_va ?></td>
		<td><input type="hidden" name="tanggal_transaksi[<?php echo $l++; ?>]" value="<?php echo $tanggal_transaksi ?>"> <?php echo ($tanggal_transaksi)  ;  ?> </td>			
		<td><input type="hidden" name="nilai[<?php echo $m++; ?>]" value="<?php echo $nilai; ?>"><?php echo $nilai_out ?></td>
	</tr>
	
	<?php
		
		
	}
	
	
	
	
?>								
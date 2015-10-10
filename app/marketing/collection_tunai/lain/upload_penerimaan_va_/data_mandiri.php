<?php
	require_once('../../../../../config/config.php');
	
	// menggunakan class phpExcelReader
	require('../../../../../config/PHPExcel.php');
	require('../../../../../config/PHPExcel/IOFactory.php');
	
	
	// Load PHPExcel
	$objPHPExcel = PHPExcel_IOFactory::load($uploaded_file);
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
		$nilai			= $val[2];
		
		
		$nomor_va				= (!empty($nomor_va)) ? clean($nomor_va) : '';
		$tanggal_transaksi		= (!empty($tanggal)) ? clean ($tanggal) : '';	
		$UNIX_DATE 				= ($tanggal_transaksi - 25569) * 86400;
		$tanggal_transaksi 		= gmdate("d/m/Y", $UNIX_DATE);
		
		$nilai					= (!empty($nilai)) ? to_decimal($nilai) : '';
		
		//PEMOTONGAN 7 KARAKTER TERAHIR
		$nomor_va 				= substr($nomor_va, -7);
		
	?>
	<tr>
		<td><?php echo $i++ ?></td>
		<td><input type="checkbox" name="cb_data[<?php echo $j++; ?>]" value="<?php echo $nomor_va; ?>" class="cb_data"></td>
		<td><input type="hidden" name="nomor_va[<?php echo $k++; ?>]" value="<?php echo $nomor_va; ?>"><?php echo $nomor_va ?></td>
		<td><input type="hidden" name="tanggal_transaksi[<?php echo $l++; ?>]" value="<?php echo $tanggal_transaksi ?>"> <?php echo ($tanggal_transaksi)  ;  ?> </td>			
		<td><input type="hidden" name="nilai[<?php echo $m++; ?>]" value="<?php echo $nilai; ?>"><?php echo to_money($nilai) ?></td>
	</tr>
	
	<?php
		
		
	}
	
	
	
	
?>								
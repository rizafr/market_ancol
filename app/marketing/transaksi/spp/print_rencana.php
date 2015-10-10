<?php

	require_once('../../../../config/config.php');
	require_once('../../../../config/terbilang.php');
	require_once('spp_proses.php');
	//$terbilang = new Terbilang;
	
	
	

			// Include the PHPWord.php, all other classes were loaded by an autoloader
			require_once '../../../../plugin/PHPWord.php';

			// Create a new PHPWord Object
			$PHPWord = new PHPWord();	
			
// New portrait section
$section = $PHPWord->createSection();

// Define table style arrays
$styleTable = array('borderSize'=>6, 'borderColor'=>'006699', 'cellMargin'=>80);
$styleFirstRow = array('borderBottomSize'=>18, 'borderBottomColor'=>'0000FF', 'bgColor'=>'66BBFF');

// Define cell style arrays
$styleCell = array('valign'=>'center');

// Define font style for first row
$fontStyle = array('bold'=>true, 'align'=>'center');

// Add table style
$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

// Add table
$table = $section->addTable('myOwnTableStyle');

// Add row
$table->addRow(900);

// Add cells
$table->addCell(2000, $styleCell)->addText('Tanggal', $fontStyle);
$table->addCell(2000, $styleCell)->addText('Angsuran', $fontStyle);
$table->addCell(2000, $styleCell)->addText('Jumlah Rupiah', $fontStyle);

for($i = 1; $i <= 10; $i++) {
	$table->addRow();
	$table->addCell(2000)->addText("Cell $i");
	$table->addCell(2000)->addText("Cell $i");
	$table->addCell(2000)->addText("Cell $i");
	
}




/*
$query="
		SELECT TANGGAL,NILAI
		FROM RENCANA
		WHERE KODE_BLOK = $id
	";
$obj = $conn->execute($query);

// $tanggal = $obj->fields['TANGGAL'];
// $nilai = $obj->fields['NILAI'];
$i = 1;
// Add more rows / cells
while(!$obj->EOF){
	$table->addRow();
	$table->addCell(2000)->addText("TANGGAL"]);
	$table->addCell(2000)->addText("Ke-$i");
	$table->addCell(2000)->addText("NILAI"]);
	
	i++;
	}	
*/
			
			$namafile = "Rencana"."_".$id." ".date('d F Y').".doc";
			
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

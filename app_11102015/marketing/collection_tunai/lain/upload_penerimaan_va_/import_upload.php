<?php
	require_once('../../../../../config/config.php');
	$nama_bank = (isset($_REQUEST['nama_bank'])) ? clean($_REQUEST['nama_bank']) : '';
	
	$error = FALSE;
	$msg = '';
	$path = '';
	
	switch ($nama_bank)
	{
		case 'BCA'			: $ext = 'txt'; $path = 'bca'; break;
		case 'BUKOPIN'		: $ext = 'xls'; $path = 'bukopin'; break;
		#case 'BUKOPIN_AD'	: $ext = 'xls'; $path = 'bukopin_ad'; break;
		case 'BUMIPUTERA'	: $ext = 'xls'; $path = 'bumiputera'; break;
		case 'MANDIRI'		: $ext = 'xls'; $path = 'mandiri'; break;
		case 'NIAGA'		: $ext = 'txt'; $path = 'niaga'; break;
		case 'NIAGA_AD'		: $ext = 'txt'; $path = 'niaga_ad'; break;
		case 'PERMATA'		: $ext = 'txt'; $path = 'permata'; break;
	}
	
	try
	{
		ex_empty($nama_bank, 'Pilih Kode Bank.');
		ex_empty($path, 'Aplikasi export bank yang anda pilih belum tersedia');
		
		// buat pathnya di C..jika belum ada, sistem langsung dibikin
		$path = 'C:BANK/'. $path;
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		
		
		if ( ! isset($_FILES) AND ! isset($_FILES['file_import']))
		{
			throw new Exception('Error upload file.');
		}
		
		$tmp_name	= $_FILES['file_import']['tmp_name'];
		$file_name	= $_FILES['file_import']['name'];
		$file_type	= pathinfo(basename($file_name), PATHINFO_EXTENSION);
		$error_upload = $_FILES['file_import']['error'];
		
		if ($error_upload > 0)
		{
			throw new Exception('Silakan pilih file.');
		}
		
		if (strtoupper($file_type) != strtoupper($ext))
		{
			throw new Exception("Tipe file import harus ($ext)");
		}
		
		if ( ! copy($tmp_name, $path . "upload.$ext"))
		{
			throw new Exception('Error upload file import.');
		}
		
	}
	catch (Exception $e)
	{
		$msg = $e->getMessage();
		$error = TRUE;
	}
	
	echo json_encode(array('error' => $error, 'msg' => $msg));
	exit;
?>	
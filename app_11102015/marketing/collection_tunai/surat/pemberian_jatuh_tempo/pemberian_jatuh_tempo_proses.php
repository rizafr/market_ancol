<?php
	require_once('../../../../../config/config.php');

	$msg 	= '';
	$error	= FALSE;

	$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
	$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		try
		{
			ex_login();

			$conn = conn($sess_db);
			ex_conn($conn);

			$conn->begintrans(); 
				
			if ($act == 'Cetak') # Proses Hapus
			{
				//ex_ha('', 'D');
				
				$act = array();
				$cb_data = $_REQUEST['cb_data'];
				ex_empty($cb_data, 'Pilih data yang akan dihapus.');
				
				foreach ($cb_data as $id_del)
				{
					$id = $id_del;
					$query = "SELECT *, B.TANGGAL AS TGL_TEMPO FROM SPP A JOIN RENCANA B ON A.KODE_BLOK = B.KODE_BLOK
						WHERE A.KODE_BLOK = '$id'";
					$obj = $conn->execute($query);
					
					$nama			= $obj->fields['NAMA_PEMBELI'];
					$alamat_surat	= $obj->fields['ALAMAT_SURAT'];
					
					$TELP_KANTOR=(trim($obj->fields["TELP_KANTOR"])!="")?trim(strtoupper($obj->fields["TELP_KANTOR"])):"";
					$TELP_LAIN=(trim($obj->fields["TELP_LAIN"])!="")?",".trim(strtoupper($obj->fields["TELP_LAIN"])):"";
					$TELP_RUMAH=(trim($obj->fields["TELP_RUMAH"])!="")?",".trim(strtoupper($obj->fields["TELP_RUMAH"])):"";
					$telp=$TELP_KANTOR.$TELP_LAIN.$TELP_RUMAH;

					$tanggal_spp	= fm_date(date("Y-m-d", strtotime($obj->fields['TANGGAL_SPP'])));
					$tanggal_tempo	= fm_date(date("Y-m-d", strtotime($obj->fields['TGL_TEMPO'])));
					$nilai			= $obj->fields['NILAI'];
					
					$query = "select NOMOR_SURAT_PEMBERITAHUAN,REG_SURAT_PEMBERITAHUAN from CS_REGISTER_CUSTOMER_SERVICE";
					$obj = $conn->execute($query);
					
					$no				= $obj->fields['NOMOR_SURAT_PEMBERITAHUAN'];
					$reg			= $obj->fields['REG_SURAT_PEMBERITAHUAN'];
				}	
	
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
		exit;
	}
?>



		
		
	
	
	
	
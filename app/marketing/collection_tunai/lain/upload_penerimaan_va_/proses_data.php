<?php
	require_once('../../../../../config/config.php');
	
	$msg = '';
	$error = FALSE;
	
	$user_bayar	= (isset($_REQUEST['user_bayar'])) ? clean($_REQUEST['user_bayar']) : '';
	$bayar_via	= (isset($_REQUEST['bayar_via'])) ? clean($_REQUEST['bayar_via']) : '';
	$cb_data	= (isset($_REQUEST['cb_data'])) ? $_REQUEST['cb_data'] : array();
	$vb_tgl		= (isset($_REQUEST['vb_tgl'])) ? $_REQUEST['vb_tgl'] : array();
	$ket_bayar	= (isset($_REQUEST['ket_bayar'])) ? clean($_REQUEST['ket_bayar']) : '';
	
	$nomor_va	= (isset($_REQUEST['nomor_va'])) ? ($_REQUEST['nomor_va']) : '';
	$tanggal_transaksi	= (isset($_REQUEST['tanggal_transaksi'])) ? ($_REQUEST['tanggal_transaksi']) : '';
	$nilai	= (isset($_REQUEST['nilai'])) ? ($_REQUEST['nilai']) : '';
	$bank 	= (isset($_REQUEST['nama_bank'])) ? ($_REQUEST['nama_bank']) : '';
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$list_id_sukses = array();
		$list_id_error = array();
		
		try
		{
			ex_login();
			//ex_app('A01');
			//ex_mod('PO01');
			$conn = conn($sess_db);
			ex_conn($conn);
			$conn->begintrans();
			
			ex_empty($cb_data, 'Pilih data yang akan disimpan.');
			ex_empty($bayar_via, 'Error. Kode bank, please contact developer.');
			ex_empty($tanggal_transaksi, 'Error. Tanggal bayar, please contact developer.');
			
			$error_update = FALSE;
			
			foreach ($cb_data AS $x => $id_save)
			{
				
				
				$spl = $tanggal_transaksi[$x];
				$a = explode(' ',$tanggal_transaksi[$x]);
				$b = explode('/',$tanggal_transaksi[$x]);
				$tgl_bayar_bank = $b[2].'-'.$b[1].'-'.$b[0];
				$tgl_bayar_bank = date('Y-d-m', strtotime($tgl_bayar_bank));
				$list_tanggal[] = $tgl_bayar_bank;
				
				
				$query = "
				SELECT COUNT(TANGGAL) AS TOTAL FROM PEMBAYARAN WHERE NOMOR_VA = '$nomor_va[$x]' and  TANGGAL = CONVERT(DATETIME,'$tgl_bayar_bank',105) AND KODE_BANK = '$nama_bank'
				";
				
				$total_data = $conn->Execute($query)->fields['TOTAL'];
				// print_r($query);
				if ($total_data == 0) {
					$query = "INSERT INTO PEMBAYARAN (NOMOR_VA, TANGGAL, NILAI, STATUS, KODE_BANK)
					VALUES(
					'$nomor_va[$x]',
					CONVERT(DATETIME,'$tgl_bayar_bank',105),
					'$nilai[$x]',
					'0',
					'$bank'
					)";
					
					if ($conn->Execute($query)) {
						$list_id_sukses[] = $id_save;
						} else {
						$error_update = TRUE;
						$list_id_error[] = $id_save;
					}
					
				}else{ //cek data yang gagal
					$list_id_error[]= $nomor_va[$x];
					} 
			}
			$conn->committrans();
			
			$msg = ($error_update || $total_data > 0) ? 'Sebagian data gagal disimpan.' : 'Data pembayaran berhasil disimpan.';
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			$error = TRUE;
			$list_id_sukses = array();
			$list_id_error = array();
			$conn->rollbacktrans();
		}
		
		close($conn);
		
		$json = array(
		'list_id_sukses' => $list_id_sukses,
		'jumlah_sukses' => count($list_id_sukses),
		'list_id_error' => $list_id_error,
		'jumlah_eror' => count($list_id_error),
		'msg' => $msg, 
		'error'=> $error
		);
		echo json_encode($json);
		exit;
	}			
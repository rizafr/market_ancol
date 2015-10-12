<?php
	require_once('../../../../../config/config.php');
	
	$msg 	= '';
	$error	= FALSE;
	
	$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
	$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
	
	$kode					= (isset($_REQUEST['kode'])) ? clean($_REQUEST['kode']) : '';
	$kode_blok				= (isset($_REQUEST['kode_blok'])) ? to_number($_REQUEST['kode_blok']) : '';
	$no_va					= (isset($_REQUEST['no_va'])) ? to_number(trim($_REQUEST['no_va'])) : '';
	$no_va_ph				= (isset($_REQUEST['no_va_ph'])) ? to_number($_REQUEST['no_va_ph']) : '';
	$harga_awal				= (isset($_REQUEST['harga_awal'])) ? to_number($_REQUEST['harga_awal']) : '';
	$no_ppjb_awal			= (isset($_REQUEST['no_ppjb_awal'])) ? clean($_REQUEST['no_ppjb_awal']) : '';
	$no_ppjb_hak			= (isset($_REQUEST['no_ppjb_hak'])) ? clean($_REQUEST['no_ppjb_hak']) : '';
	$tanggal_awal			= (isset($_REQUEST['tanggal_awal'])) ? clean($_REQUEST['tanggal_awal']) : '';
	
	$tanggal_sekarang = date("Y-m-d H:i:s");
	
	$tanggal				= (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
	$tanggal_permohonan		= (isset($_REQUEST['tanggal_permohonan'])) ? clean($_REQUEST['tanggal_permohonan']) : '';
	$tanggal_persetujuan	= (isset($_REQUEST['tanggal_persetujuan'])) ? clean($_REQUEST['tanggal_persetujuan']) : '';
	$harga_hak				= (isset($_REQUEST['harga_hak'])) ? to_number($_REQUEST['harga_hak']) : '';
	$biaya					= (isset($_REQUEST['biaya'])) ? to_number($_REQUEST['biaya']) : '';
	$masa_bangun			= (isset($_REQUEST['masa_bangun'])) ? to_number($_REQUEST['masa_bangun']) : '';
	$keterangan				= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
	
	$pihak_pertama			= (isset($_REQUEST['pihak_pertama'])) ? clean($_REQUEST['pihak_pertama']) : '';
	$no_id					= (isset($_REQUEST['no_id'])) ? clean($_REQUEST['no_id']) : '';
	$alamat					= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';
	$tlp1					= (isset($_REQUEST['tlp1'])) ? clean($_REQUEST['tlp1']) : '';
	$tlp3					= (isset($_REQUEST['tlp3'])) ? clean($_REQUEST['tlp3']) : '';
	$email					= (isset($_REQUEST['email'])) ? clean($_REQUEST['email']) : '';
	$suami_istri			= (isset($_REQUEST['suami_istri'])) ? clean($_REQUEST['suami_istri']) : '';
	$no_fax					= (isset($_REQUEST['no_fax'])) ? clean($_REQUEST['no_fax']) : '';
	
	$pihak_kedua			= (isset($_REQUEST['pihak_kedua'])) ? clean($_REQUEST['pihak_kedua']) : '';
	$no_id_hak				= (isset($_REQUEST['no_id_hak'])) ? clean($_REQUEST['no_id_hak']) : '';
	$alamat_hak				= (isset($_REQUEST['alamat_hak'])) ? clean($_REQUEST['alamat_hak']) : '';
	$tlp1_hak				= (isset($_REQUEST['tlp1_hak'])) ? clean($_REQUEST['tlp1_hak']) : '';
	$tlp3_hak				= (isset($_REQUEST['tlp3_hak'])) ? clean($_REQUEST['tlp3_hak']) : '';
	$email_hak				= (isset($_REQUEST['email_hak'])) ? clean($_REQUEST['email_hak']) : '';
	$suami_istri_hak		= (isset($_REQUEST['suami_istri_hak'])) ? clean($_REQUEST['suami_istri_hak']) : '';
	$no_fax_hak				= (isset($_REQUEST['no_fax_hak'])) ? clean($_REQUEST['no_fax_hak']) : '';
	$npwp				= (isset($_REQUEST['npwp'])) ? clean($_REQUEST['npwp']) : '';
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		try
		{
			ex_login();
			// ex_app('P');
			// ex_mod('P09');
			$conn = conn($sess_db);
			ex_conn($conn);
			
			$conn->begintrans(); 
			
			if ($act == 'Pengalihan Hak') # Proses Pengalihan Hak
			{
				// ex_ha('JB09', 'I');
				
				//penambahan jumlah pengalihan hak
				$no_va_kiri = substr($no_va,0,6);
				$nomor_va_kanan = substr($no_va, -1)+1;
				$no_va_ph = trim($no_va_kiri.$nomor_va_kanan);
				
				ex_empty($tanggal, 'Tanggal harus diisi.');
				ex_empty($harga_hak, 'Harga harus diisi.');
				ex_empty($biaya, 'Biaya harus diisi.');
				ex_empty($masa_bangun, 'Masa bangun harus diisi.');
				
				ex_empty($pihak_kedua, 'Nama Pembeli Pihak Kedua harus diisi.');
				ex_empty($no_id_hak, 'No. Identitas Pihak Kedua harus diisi.');
				ex_empty($alamat_hak, 'Alamat Pihak Kedua harus diisi.');
				ex_empty($tlp1_hak, 'No. Telp. Pihak Kedua harus diisi.');
				ex_empty($tlp3_hak, 'No. HP Pihak Kedua harus diisi.');
				ex_empty($email_hak, 'Email Pihak Kedua harus diisi.');
				ex_empty($suami_istri_hak, 'Nama Suami/Istri Pihak Kedua harus diisi.');
				ex_empty($no_fax_hak, 'No. Fax Pihak Kedua harus diisi.');
				
				//cek no ppjb sudah ada atau tidak
				$query = "SELECT * FROM CS_PENGALIHAN_HAK WHERE NO_PPJB_PH = '$no_ppjb_hak'";
				ex_found($conn->Execute($query)->recordcount(), "No. PPJB Pengalihan Hak tidak boleh sama.");
				
				//masukan data ke tabel cs pengalihan hak
				$query = "
				INSERT INTO CS_PENGALIHAN_HAK (KODE_BLOK, NO_VA_PH, NO_PPJB_PH, TANGGAL, 
				PIHAK_PERTAMA, ALAMAT_PIHAK_PERTAMA, NO_ID_PIHAK_PERTAMA, NO_TELP_PIHAK_PERTAMA, NO_HP_PIHAK_PERTAMA, NO_FAX_PIHAK_PERTAMA, EMAIL_PIHAK_PERTAMA, SUAMI_ISTRI,
				PIHAK_KEDUA,NPWP, ALAMAT_PIHAK_KEDUA, NO_ID_PIHAK_KEDUA, NO_TELP_PIHAK_KEDUA, NO_HP_PIHAK_KEDUA, NO_FAX_PIHAK_KEDUA, EMAIL_PIHAK_KEDUA, NAMA_SUAMI_ISTRI, 
				HARGA_AWAL, NO_PPJB_AWAL, TANGGAL_PPJB_AWAL, TANGGAL_PERMOHONAN, TANGGAL_PERSETUJUAN, BIAYA_PENGALIHAN_HAK, MASA_BANGUN, HARGA_PENGALIHAN_HAK, KETERANGAN)
				VALUES ('$kode','$no_va_ph', 'XXX', CONVERT(DATETIME,'$tanggal',105), 
				'$pihak_pertama', '$alamat', '$no_id', '$tlp1', '$tlp3', '$no_fax', '$email', '$suami_istri',
				'$pihak_kedua','$npwp', '$alamat_hak', '$no_id_hak', '$tlp1_hak', '$tlp3_hak', '$no_fax_hak', '$email_hak', '$suami_istri_hak', 
				$harga_awal, '$no_ppjb_awal', CONVERT(DATETIME,'$tanggal_awal',105), CONVERT(DATETIME,'$tanggal_permohonan',105), CONVERT(DATETIME,'$tanggal_persetujuan',105), $biaya, '$masa_bangun', $harga_hak, '$keterangan')
				";
				ex_false($conn->execute($query), $query);
				
				//ambil no ppjb berdasarkan kode
				$query 					= "SELECT NO_PPJB_PH FROM CS_PENGALIHAN_HAK WHERE KODE_BLOK = '$kode'";
				$obj 					= $conn->execute($query);	
				$no_ppjb_hak	 		= $obj->fields['NO_PPJB_PH'];
				
				// simpan ke dalam tabel histori			
				
				
				$query = "
				INSERT INTO CS_HISTORY_PENGALIHAN_HAK
				([KODE_BLOK]
				,[NO_VA_PH]
				,[NO_PPJB_PH]
				,[TANGGAL]
				,[PIHAK_PERTAMA]
				,[ALAMAT_PIHAK_PERTAMA]
				,[NO_ID_PIHAK_PERTAMA]
				,[NO_TELP_PIHAK_PERTAMA]
				,[NO_HP_PIHAK_PERTAMA]
				,[NO_FAX_PIHAK_PERTAMA]
				,[EMAIL_PIHAK_PERTAMA]
				,[SUAMI_ISTRI])
				VALUES ('$kode','$no_va_ph', '$no_ppjb_hak', CONVERT(DATETIME,'$tanggal_sekarang',105), 
				'$pihak_pertama', '$alamat', '$no_id', '$tlp1', '$tlp3', '$no_fax', '$email', '$suami_istri')
				";
				
				ex_false($conn->execute($query), $query);
				
				//update spp dengan data yang baru
				$query = "
				UPDATE SPP SET 
				NOMOR_CUSTOMER = '$no_va_ph',
				NAMA_PEMBELI = '$pihak_kedua',
				ALAMAT_RUMAH = '$alamat_hak',
				ALAMAT_SURAT = '$alamat_hak',
				NO_IDENTITAS = '$no_id_hak',
				NPWP = '$npwp',
				TELP_RUMAH = '$tlp1_hak',
				TELP_KANTOR = '',
				TELP_LAIN = '$tlp3_hak',
				ALAMAT_EMAIL = '$email_hak',
				NAMA_SUAMI_ISTRI = '$suami_istri_hak',
				NO_FAX = '$no_fax_hak'
				WHERE KODE_BLOK = '$kode'
				";
				ex_false($conn->execute($query), $query);
				
				//update STOK dengan data yang baru
				$query = "
				UPDATE STOK SET 
				NO_VA = '$no_va_ph'
				WHERE KODE_BLOK = '$kode'
				";
				ex_false($conn->execute($query), $query);
				
				$msg = 'Pengalihan Hak berhasil diproses, data telah disimpan.';
			}
			else if ($act == 'Ubah') # Proses Ubah
			{
				// ex_ha('JB09', 'U');
				ex_empty($no_ppjb_hak, 'No. PPJB Pengalihan Hak harus diisi.');
				ex_empty($tanggal, 'Tanggal harus diisi.');
				ex_empty($harga_hak, 'Harga harus diisi.');
				ex_empty($biaya, 'Biaya harus diisi.');
				ex_empty($masa_bangun, 'Masa bangun harus diisi.');
				
				ex_empty($pihak_kedua, 'Nama Pembeli Pihak Kedua harus diisi.');
				ex_empty($no_id_hak, 'No. Identitas Pihak Kedua harus diisi.');
				ex_empty($alamat_hak, 'Alamat Pihak Kedua harus diisi.');
				ex_empty($tlp1_hak, 'No. Telp. Pihak Kedua harus diisi.');
				ex_empty($tlp3_hak, 'No. HP Pihak Kedua harus diisi.');
				ex_empty($email_hak, 'Email Pihak Kedua harus diisi.');
				ex_empty($suami_istri_hak, 'Nama Suami/Istri Pihak Kedua harus diisi.');
				ex_empty($no_fax_hak, 'No. Fax Pihak Kedua harus diisi.');
				
				$query = "
				UPDATE CS_PENGALIHAN_HAK SET 
				NO_PPJB_PH = '$no_ppjb_hak',
				NPWP = '$npwp',
				TANGGAL = CONVERT(DATETIME,'$tanggal',105),
				TANGGAL_PERMOHONAN = CONVERT(DATETIME,'$tanggal_permohonan',105),
				TANGGAL_PERSETUJUAN = CONVERT(DATETIME,'$tanggal_persetujuan',105),
				HARGA_PENGALIHAN_HAK = $harga_hak,
				BIAYA_PENGALIHAN_HAK = $biaya,
				MASA_BANGUN = '$masa_bangun',
				KETERANGAN = '$keterangan',	
				PIHAK_KEDUA = '$pihak_kedua',
				ALAMAT_PIHAK_KEDUA = '$alamat_hak',
				NO_ID_PIHAK_KEDUA = '$no_id_hak',
				NO_TELP_PIHAK_KEDUA = '$tlp1_hak',
				NO_HP_PIHAK_KEDUA = '$tlp3_hak',
				NO_FAX_PIHAK_KEDUA = '$no_fax_hak',
				EMAIL_PIHAK_KEDUA = '$email_hak',
				NAMA_SUAMI_ISTRI = '$suami_istri_hak'
				WHERE NO_PPJB_PH = '$no_ppjb_hak'
				";
				ex_false($conn->execute($query), $query);
				
				
				
				//update spp dengan data yang baru
				$query = "
				UPDATE SPP SET 
				NAMA_PEMBELI = '$pihak_kedua',
				ALAMAT_RUMAH = '$alamat_hak',
				ALAMAT_SURAT = '$alamat_hak',
				NO_IDENTITAS = '$no_id_hak',
				NPWP = '$npwp',
				TELP_RUMAH = '$tlp1_hak',
				TELP_KANTOR = '',
				TELP_LAIN = '$tlp3_hak',
				ALAMAT_EMAIL = '$email_hak',
				NAMA_SUAMI_ISTRI = '$suami_istri_hak',
				NO_FAX = '$no_fax_hak'
				WHERE KODE_BLOK = '$kode'
				";
				
				ex_false($conn->execute($query), $query);
				
				$msg = 'Data Pengalihan Hak berhasil diubah.';
			}
			
			$conn->committrans(); 
		}
		catch(Exception $e)
		{
			$msg = $e->getmessage();
			$error = TRUE;
			if ($conn) { $conn->rollbacktrans(); } 
		}
		
		close($conn);
		$json = array('act' => $act, 'error'=> $error, 'msg' => $msg);
		echo json_encode($json);
		exit;
	}
	
	die_login();
	// die_app('P');
	// die_mod('P09');
	$conn = conn($sess_db);
	die_conn($conn);
	
	if ($act == 'Pengalihan Hak')
	{
		$kode			= '';
		$nomor 			= '';
		$no_va_ph		= '';
		$tanggal_awal	= '';
		$harga_awal		= '';
		$pihak_pertama	= '';
		$no_id			= '';
		$alamat			= '';
		$tlp1			= '';
		$tlp3			= '';
		$email			= '';
		$suami_istri	= '';
		$no_fax			= '';
		
		$no_ppjb_hak			= '';
		$tanggal				= '';
		$tanggal_permohonan		= '';
		$tanggal_persetujuan	= '';
		$harga_hak				= '';
		$biaya					= '';
		$masa_bangun			= '';
		$keterangan				= '';
		
		$pihak_kedua		= '';
		$no_id_hak			= '';
		$alamat_hak			= '';
		$tlp1_hak			= '';
		$tlp3_hak			= '';
		$email_hak			= '';
		$suami_istri_hak	= '';
		$no_fax_hak			= '';
	}
	
	if ($act == 'Ubah')
	{
		$query = "
		SELECT *
		FROM
		CS_PENGALIHAN_HAK
		WHERE NO_PPJB_PH = '$id'";
		$obj = $conn->execute($query);
		
		$kode	 		= $obj->fields['KODE_BLOK'];
		$no_va_ph	 	= $obj->fields['NO_VA_PH'];
		$nomor 			= $obj->fields['NO_PPJB_AWAL'];
		$tanggal_awal	= f_tgl($obj->fields['TANGGAL_PPJB_AWAL']);
		$harga_awal		= $obj->fields['HARGA_AWAL'];
		$pihak_pertama	= $obj->fields['PIHAK_PERTAMA'];
		$no_id			= $obj->fields['NO_ID_PIHAK_PERTAMA'];
		$npwp			= $obj->fields['NPWP'];
		$alamat			= $obj->fields['ALAMAT_PIHAK_PERTAMA'];
		$tlp1			= $obj->fields['NO_TELP_PIHAK_PERTAMA'];
		$tlp3			= $obj->fields['NO_HP_PIHAK_PERTAMA'];
		$email			= $obj->fields['EMAIL_PIHAK_PERTAMA'];
		$suami_istri	= $obj->fields['SUAMI_ISTRI'];
		$no_fax			= $obj->fields['NO_FAX_PIHAK_PERTAMA'];
		
		$no_ppjb_hak			= $obj->fields['NO_PPJB_PH'];
		$tanggal				= tgltgl(f_tgl($obj->fields['TANGGAL']));
		$tanggal_permohonan		= tgltgl(f_tgl($obj->fields['TANGGAL_PERMOHONAN']));
		$tanggal_persetujuan	= tgltgl(f_tgl($obj->fields['TANGGAL_PERSETUJUAN']));
		$harga_hak				= $obj->fields['HARGA_PENGALIHAN_HAK'];
		$biaya					= $obj->fields['BIAYA_PENGALIHAN_HAK'];
		$masa_bangun			= $obj->fields['MASA_BANGUN'];
		$keterangan				= $obj->fields['KETERANGAN'];
		
		$pihak_kedua		= $obj->fields['PIHAK_KEDUA'];
		$no_id_hak			= $obj->fields['NO_ID_PIHAK_KEDUA'];
		$alamat_hak			= $obj->fields['ALAMAT_PIHAK_KEDUA'];
		$tlp1_hak			= $obj->fields['NO_TELP_PIHAK_KEDUA'];
		$tlp3_hak			= $obj->fields['NO_HP_PIHAK_KEDUA'];
		$email_hak			= $obj->fields['EMAIL_PIHAK_KEDUA'];
		$suami_istri_hak	= $obj->fields['NAMA_SUAMI_ISTRI'];
		$no_fax_hak			= $obj->fields['NO_FAX_PIHAK_KEDUA'];
	}
	
	
	
?>
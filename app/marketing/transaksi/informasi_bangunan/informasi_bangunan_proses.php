<?php
require_once('../../../../config/config.php');

$msg 	= '';
$error 	= FALSE;

$act 	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id 	= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M13');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M13', 'U');		
			
			$kode_blok 					= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
			$kode_tipe					= (isset($_REQUEST['kode_tipe'])) ? clean($_REQUEST['kode_tipe']) : '';
			$tanggal_memo 				= (isset($_REQUEST['tanggal_memo'])) ? clean($_REQUEST['tanggal_memo']) : '';
			$nomor_memo 				= (isset($_REQUEST['nomor_memo'])) ? clean($_REQUEST['nomor_memo']) : '';
			$nomor_spk 					= (isset($_REQUEST['nomor_spk'])) ? clean($_REQUEST['nomor_spk']) : '';
			$kwh_slo					= (isset($_REQUEST['kwh_slo'])) ? clean($_REQUEST['kwh_slo']) : '';
			$kwh_no_kontrak 			= (isset($_REQUEST['kwh_no_kontrak'])) ? clean($_REQUEST['kwh_no_kontrak']) : '';
			$kwh_no_pelanggan			= (isset($_REQUEST['kwh_no_pelanggan'])) ? clean($_REQUEST['kwh_no_pelanggan']) : '';
			$pam_terpasang				= (isset($_REQUEST['pam_terpasang'])) ? clean($_REQUEST['pam_terpasang']) : '';
			$no_tlp_terpasang			= (isset($_REQUEST['no_tlp_terpasang'])) ? clean($_REQUEST['no_tlp_terpasang']) : '';
			$tgl_serah_kontraktor		= (isset($_REQUEST['tgl_serah_kontraktor'])) ? clean($_REQUEST['tgl_serah_kontraktor']) : '';
			$tgl_serah_proyek			= (isset($_REQUEST['tgl_serah_proyek'])) ? clean($_REQUEST['tgl_serah_proyek']) : '';
			$jadwal_mulai				= (isset($_REQUEST['jadwal_mulai'])) ? clean($_REQUEST['jadwal_mulai']) : '';
			$jadwal_selesai				= (isset($_REQUEST['jadwal_selesai'])) ? clean($_REQUEST['jadwal_selesai']) : '';
			$rencana 					= (isset($_REQUEST['rencana'])) ? intval($_REQUEST['rencana']) : '';
			$realisasi 					= (isset($_REQUEST['realisasi'])) ? intval($_REQUEST['realisasi']) : '';

			$siap_checklist_purnajual	= (isset($_REQUEST['siap_checklist_purnajual'])) ? clean($_REQUEST['siap_checklist_purnajual']) : '';
			$checklist_purnajual 		= (isset($_REQUEST['checklist_purnajual'])) ? clean($_REQUEST['checklist_purnajual']) : '';
			$tanggal_selesai_proyek 	= (isset($_REQUEST['tanggal_selesai_proyek'])) ? clean($_REQUEST['tanggal_selesai_proyek']) : '';
			$tgl_serah_terima		 	= (isset($_REQUEST['tgl_serah_terima'])) ? clean($_REQUEST['tgl_serah_terima']) : '';
			$keterangan				 	= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';

			$query = "
			UPDATE STOK
			SET MEMO_MARKETING_TANGGAL 			= CONVERT(DATE,'$tanggal_memo',105),
				MEMO_MARKETING_NO	 			= '$nomor_memo',
				NOMOR_SPK						= '$nomor_spk',	
				RENCANA 						= '$rencana',
				REALISASI 						= '$realisasi',
				SIAP_CHECKLIST_PURNAJUAL		= '$siap_checklist_purnajual',
				CHECKLIST_PURNAJUAL				= '$checklist_purnajual',
				TARGET_SELESAI_PROYEK			= CONVERT(DATE,'$tanggal_selesai_proyek',105),
					
				KODE_TIPE						= '$kode_tipe',					
				KWH_SLO							= '$kwh_slo',
				KWH_NO_KONTRAK 					= '$kwh_no_kontrak',
				KWH_NO_PELANGGAN 				= '$kwh_no_pelanggan',
				PAM_TERPASANG 					= CONVERT(DATE,'$pam_terpasang',105),
				NO_TLP_TERPASANG 				= '$no_tlp_terpasang',
				TGL_SERAH_KONTRAKTOR 			= CONVERT(DATE,'$tgl_serah_kontraktor',105),
				TGL_SERAH_PROYEK 				= CONVERT(DATE,'$tgl_serah_proyek',105),
				JADWAL_MULAI 					= CONVERT(DATE,'$jadwal_mulai',105),
				JADWAL_SELESAI 					= CONVERT(DATE,'$jadwal_selesai',105),
				RENCANA 						= '$rencana',
				REALISASI 						= '$realisasi',
				TGL_SERAH_TERIMA 				= CONVERT(DATE,'$tgl_serah_terima',105),
				KETERANGAN 						= '$keterangan'

			WHERE
				KODE_BLOK = '$kode_blok'
			";
			ex_false($conn->Execute($query), $query);
					
			$msg = 'Data Informasi Bangunan berhasil diubah.';
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
die_app('M');
die_mod('M13');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$obj = $conn->Execute("
	SELECT  
		s.KODE_BLOK,
		s.LUAS_TANAH,
		s.LUAS_BANGUNAN,
		s.MEMO_MARKETING_TANGGAL,
		s.MEMO_MARKETING_NO,
		s.NOMOR_SPK,
		s.TGL_BANGUNAN,
		s.TGL_SELESAI,
		s.RENCANA,
		s.REALISASI,
		s.KWH_SLO,
		s.KWH_NO_KONTRAK,
		s.KWH_NO_PELANGGAN,
		s.PAM_TERPASANG,
		s.NO_TLP_TERPASANG,
		s.TGL_SERAH_KONTRAKTOR,
		s.TGL_SERAH_PROYEK,
		s.JADWAL_MULAI,
		s.JADWAL_SELESAI,
		s.TGL_SERAH_TERIMA,
		DATEDIFF(day,s.JADWAL_MULAI,s.JADWAL_SELESAI) as DURASI,
		s.KETERANGAN,
		(s.RENCANA-s.REALISASI) AS DEVIASI,
		s.SIAP_CHECKLIST_PURNAJUAL,
		s.CHECKLIST_PURNAJUAL,
		s.CHECKLIST_PURNAJUAL_TGL,
		s.TARGET_SELESAI_PROYEK,
		t.TIPE_BANGUNAN,
		
		(
			(
				(s.LUAS_TANAH * ht.HARGA_TANAH) + 
				((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
				((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
			)
			-
			(
				(
					(s.LUAS_TANAH * ht.HARGA_TANAH) + 
					((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
					((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
				)
				* s.DISC_TANAH / 100
			)
			+
			(
				(
					(
						(s.LUAS_TANAH * ht.HARGA_TANAH) + 
						((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
						((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
					)
					-
					(
						(
							(s.LUAS_TANAH * ht.HARGA_TANAH) + 
							((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
							((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
						)
						* s.DISC_TANAH / 100
					)
				) * s.PPN_TANAH / 100
			)
		) AS HARGA_TANAH,
		
		(
			(s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN)
			-
			((s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) * s.DISC_BANGUNAN / 100) 
			+
			(
				(s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) -
				((s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) * s.DISC_BANGUNAN / 100) 
			) 
			* s.PPN_BANGUNAN / 100
		) AS HARGA_BANGUNAN,
		
		PROGRESS, NAMA_DESA, LOKASI, JENIS_UNIT
	FROM 
		STOK s
		LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
		LEFT JOIN HARGA_BANGUNAN hb ON s.KODE_SK_BANGUNAN = hb.KODE_SK
		LEFT JOIN HARGA_TANAH ht ON s.KODE_SK_TANAH = ht.KODE_SK
		LEFT JOIN FAKTOR f ON s.KODE_FAKTOR = f.KODE_FAKTOR
		LEFT JOIN DESA g ON s.KODE_DESA = g.KODE_DESA
		LEFT JOIN LOKASI h ON s.KODE_LOKASI = h.KODE_LOKASI
		LEFT JOIN JENIS_UNIT i ON s.KODE_UNIT = i.KODE_UNIT
		WHERE STATUS_STOK = '1' AND TERJUAL = '2' AND KODE_BLOK = '$id'
	ORDER BY s.KODE_BLOK ASC
	");


	$kode_blok					= $obj->fields['KODE_BLOK'];
	$kode_tipe					= $obj->fields['TIPE_BANGUNAN'];
	$luas_tanah					= $obj->fields['LUAS_TANAH'];
	$luas_bangunan				= $obj->fields['LUAS_BANGUNAN'];
	$memo_marketing_tanggal		= $obj->fields['MEMO_MARKETING_TANGGAL'];
	$memo_marketing_no			= $obj->fields['MEMO_MARKETING_NO'];
	$nomor_spk 					= $obj->fields['NOMOR_SPK'];
	$tgl_bangunan				= $obj->fields['TGL_BANGUNAN'];
	$tgl_selesai				= $obj->fields['TGL_SELESAI'];

	$rencana					= $obj->fields['RENCANA'];
	$realisasi					= $obj->fields['REALISASI'];
	$tipe_bangunan				= $obj->fields['TIPE_BANGUNAN'];

	$harga_tanah 				= $obj->fields['HARGA_TANAH'];
	$harga_bangunan				= $obj->fields['HARGA_BANGUNAN'];
	$keterangan				= $obj->fields['KETERANGAN'];

	$progress 					= $obj->fields['PROGRESS'];
	$nama_desa 					= $obj->fields['NAMA_DESA'];
	$lokasi 					= $obj->fields['LOKASI'];
	$jenis_unit 				= $obj->fields['JENIS_UNIT'];
	$rencana 				= $obj->fields['RENCANA'];
	$realisasi 				= $obj->fields['REALISASI'];


	$pam_terpasang				= tgltgl(date("d-m-Y", strtotime($obj->fields['PAM_TERPASANG'])));
	$tgl_serah_kontraktor 		= tgltgl(date("d-m-Y", strtotime($obj->fields['TGL_SERAH_KONTRAKTOR'])));
	$tgl_serah_proyek 			= tgltgl(date("d-m-Y", strtotime($obj->fields['TGL_SERAH_PROYEK'])));

	$jadwal_mulai 				= tgltgl(date("d-m-Y", strtotime($obj->fields['JADWAL_MULAI'])));
	$jadwal_selesai 			= tgltgl(date("d-m-Y", strtotime($obj->fields['JADWAL_SELESAI'])));

	$checklist_purnajual_tanggal 		= tgltgl(date("d-m-Y", strtotime($obj->fields['CHECKLIST_PURNAJUAL_TGL'])));
	$tanggal_selesai_proyek 			= tgltgl(date("d-m-Y", strtotime($obj->fields['TARGET_SELESAI_PROYEK'])));

	$tgl_serah_terima 			= tgltgl(date("d-m-Y", strtotime($obj->fields['TGL_SERAH_TERIMA'])));
	$tanggal_memo	 			= tgltgl(date("d-m-Y", strtotime($obj->fields['MEMO_MARKETING_TANGGAL'])));


}
?>
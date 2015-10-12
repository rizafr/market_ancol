<?php
require_once('../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$namapemilik		= (isset($_REQUEST['namapemilik'])) ? clean($_REQUEST['namapemilik']) : '';
$namapembeli		= (isset($_REQUEST['namapembeli'])) ? clean($_REQUEST['namapembeli']) : '';
$no_bast			= (isset($_REQUEST['no_bast'])) ? clean($_REQUEST['no_bast']) : '';
$tgl_bast			= (isset($_REQUEST['tgl_bast'])) ? clean($_REQUEST['tgl_bast']) : '';
$no_tlp				= (isset($_REQUEST['no_tlp'])) ? clean($_REQUEST['no_tlp']) : '';
$no_spp				= (isset($_REQUEST['no_spp'])) ? to_number($_REQUEST['no_spp']) : '';
$progress			= (isset($_REQUEST['progress'])) ? to_number($_REQUEST['progress']) : '0';
$jml_kunci			= (isset($_REQUEST['jml_kunci'])) ? clean($_REQUEST['jml_kunci']) : '';
$anak_kunci			= (isset($_REQUEST['anak_kunci'])) ? clean($_REQUEST['anak_kunci']) : '';
$tgl_serah_kunci	= (isset($_REQUEST['tgl_serah_kunci'])) ? clean($_REQUEST['tgl_serah_kunci']) : '';
$tglkonpro			= (isset($_REQUEST['tglkonpro'])) ? clean($_REQUEST['tglkonpro']) : '';
$tgl_propur			= (isset($_REQUEST['tgl_propur'])) ? clean($_REQUEST['tgl_propur']) : '';
$masaberlaku		= (isset($_REQUEST['masaberlaku'])) ? clean($_REQUEST['masaberlaku']) : '';
$keterangan			= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$kontraktor			= (isset($_REQUEST['kontraktor'])) ? clean($_REQUEST['kontraktor']) : '';

$r_nama_desa			= '';
$r_lokasi				= '';
$r_jenis_unit			= '';
$r_harga_tanah_sk		= '';
$r_faktor_strategis		= '';
$r_tipe_bangunan		= '';
$r_harga_bangunan_sk	= '';
$r_jenis_penjualan		= '';
$r_progres				= '';
$r_luas_tanah			= '';
$r_base_harga_tanah		= '';
$r_nilai_tambah			= '';
$r_nilai_kurang			= '';
$r_fs_harga_tanah		= '';
$r_disc_tanah			= '';
$r_disc_harga_tanah		= '';
$r_ppn_tanah			= '';
$r_ppn_harga_tanah		= '';
$r_harga_tanah			= '';
$r_luas_bangunan		= '';
$r_base_harga_bangunan	= '';
$r_disc_bangunan		= '';
$r_disc_harga_bangunan	= '';
$r_ppn_bangunan			= '';
$r_ppn_harga_bangunan	= '';
$r_harga_bangunan		= '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M19');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
		
		if ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M19', 'U');

			$query = "
			UPDATE SERAH_TERIMA
			SET 
				NAMA_PEMILIK				= '$namapemilik',
				NOMOR_SERAH_TERIMA			= '$no_bast',
				TANGGAL_SERAH_TERIMA		= CONVERT(DATETIME,'$tgl_bast',105),
				TANGGAL_BAST_I				= CONVERT(DATETIME,'$tglkonpro',105),
				TGL_SERAH_TERIMA_KAWASAN	= CONVERT(DATETIME,'$tgl_propur',105),
				TANGGAL_AMBIL_IMB			= CONVERT(DATETIME,'$masaberlaku',105),
				JUMLAH_KUNCI				= $jml_kunci,
				JUMLAH_ANAK_KUNCI			= $anak_kunci,
				TANGGAL_SERAH_KUNCI			= CONVERT(DATETIME,'$tgl_serah_kunci',105)
			WHERE
				KODE_BLOK = '$id'
			";			
			ex_false($conn->execute($query), $query);
		
			$msg = 'Data Serah Terima berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('M19', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "DELETE FROM SERAH_TERIMA WHERE KODE_BLOK = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		
			
			$msg = ($error) ? 'Sebagian data serah terima gagal dihapus.' : 'Data serah terima berhasil dihapus.'; 
			
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
die_mod('M19');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$query = "SELECT * FROM SERAH_TERIMA 
			WHERE KODE_BLOK = '$id'";
	
	$obj = $conn->execute($query);
	
	$tgl_bast				= tgltgl(f_tgl($obj->fields['TANGGAL_SERAH_TERIMA']));	
	$no_bast				= $obj->fields['NOMOR_SERAH_TERIMA'];
	$namapemilik			= $obj->fields['NAMA_PEMILIK'];
	$no_kontrak				= $obj->fields['NO_KONTRAK_LISTRIK'];
	$no_tlp					= $obj->fields['NOMOR_TELEPON'];
	$keterangan				= $obj->fields['KETERANGAN'];
	$kontraktor				= $obj->fields['KONTRAKTOR'];
	$jml_kunci				= $obj->fields['JUMLAH_KUNCI'];	
	$anak_kunci				= $obj->fields['JUMLAH_ANAK_KUNCI'];	
	$tgl_serah_kunci		= tgltgl(f_tgl($obj->fields['TANGGAL_SERAH_KUNCI']));
	$tglkonpro				= tgltgl(f_tgl($obj->fields['TANGGAL_BAST_I']));
	$tgl_propur				= tgltgl(f_tgl($obj->fields['TGL_SERAH_TERIMA_KAWASAN']));
	$masaberlaku			= tgltgl(f_tgl($obj->fields['TANGGAL_AMBIL_IMB']));
	$inkaso					= $obj->fields['INKASO'];		
	$watt					= $obj->fields['JUMLAH_WATT_TERPASANG'];
	$no_kontrol				= $obj->fields['NOMOR_KONTROL'];
	$pompapam				= tgltgl(f_tgl($obj->fields['AJU_PASANG_POMPA']));
	$sertifikat_rayap		= $obj->fields['SERTIFIKAT_RAYAP'];
	$sertifikat_rayap_tgl	= tgltgl(f_tgl($obj->fields['SERTIFIKAT_RAYAP_TGL']));
	$as_build				= $obj->fields['AS_BUILD_DRAWING'];
	$as_build_drawing_tgl	= tgltgl(f_tgl($obj->fields['AS_BUILD_DRAWING_TGL']));
	$imb					= $obj->fields['IMB'];
	$imb_tgl				= tgltgl(f_tgl($obj->fields['IMB_TGL']));
}

if ($act == 'Ubah')
{
	$query = "SELECT a.NAMA_PEMBELI, b.PROGRESS FROM SPP a 
			  LEFT JOIN STOK B ON a.KODE_BLOK = b.KODE_BLOK
			 WHERE a.KODE_BLOK = '$id'";
	
	$obj = $conn->execute($query);
	$namapembeli		= $obj->fields['NAMA_PEMBELI'];
	$progress			= $obj->fields['PROGRESS'];
	
}

if ($act == 'Ubah')
{
	
	$obj = $conn->Execute("
	SELECT  
		s.*,
		f.NILAI_TAMBAH, 
		f.NILAI_KURANG, 
		
		(s.LUAS_TANAH * ht.HARGA_TANAH) AS BASE_HARGA_TANAH, 
		(
			((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
			((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
		) AS FS_HARGA_TANAH, 
		
		(
			(
				(s.LUAS_TANAH * ht.HARGA_TANAH) + 
				((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
				((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100)
			)
			* s.DISC_TANAH / 100
		) AS DISC_HARGA_TANAH, 
		
		(
			(
				((s.LUAS_TANAH * ht.HARGA_TANAH) + 
				((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
				((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100))
				-
				(
					((s.LUAS_TANAH * ht.HARGA_TANAH) + 
					((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_TAMBAH / 100) - 
					((s.LUAS_TANAH * ht.HARGA_TANAH) * f.NILAI_KURANG / 100))
					* s.DISC_TANAH / 100
				)
			) * s.PPN_TANAH / 100
		) AS PPN_HARGA_TANAH, 
		
		
		(s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) AS BASE_HARGA_BANGUNAN, 
		((s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) * s.DISC_BANGUNAN / 100) AS DISC_HARGA_BANGUNAN, 
		(
			(
				(s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) -
				((s.LUAS_BANGUNAN * hb.HARGA_BANGUNAN) * s.DISC_BANGUNAN / 100)
			) * s.PPN_BANGUNAN / 100
		) AS PPN_HARGA_BANGUNAN, 
		
		d.NAMA_DESA,
		l.LOKASI,
		ju.JENIS_UNIT,
		ht.HARGA_TANAH AS HARGA_TANAH_SK,
		f.FAKTOR_STRATEGIS,
		t.TIPE_BANGUNAN,
		hb.HARGA_BANGUNAN AS HARGA_BANGUNAN_SK,
		p.JENIS_PENJUALAN
	FROM 
		STOK s
		
		LEFT JOIN HARGA_BANGUNAN hb ON s.KODE_SK_BANGUNAN = hb.KODE_SK
		LEFT JOIN HARGA_TANAH ht ON s.KODE_SK_TANAH = ht.KODE_SK
		
		LEFT JOIN DESA d ON s.KODE_DESA = d.KODE_DESA
		LEFT JOIN LOKASI l ON s.KODE_LOKASI = l.KODE_LOKASI
		LEFT JOIN JENIS_UNIT ju ON s.KODE_UNIT = ju.KODE_UNIT
		LEFT JOIN FAKTOR f ON s.KODE_FAKTOR = f.KODE_FAKTOR
		LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
		LEFT JOIN JENIS_PENJUALAN p ON s.KODE_PENJUALAN = p.KODE_JENIS
	WHERE
		KODE_BLOK = '$id'");
	$r_kode_desa			= $obj->fields['KODE_DESA'];
	$r_kode_lokasi			= $obj->fields['KODE_LOKASI'];
	$r_kode_unit			= $obj->fields['KODE_UNIT'];
	$r_kode_sk_tanah		= $obj->fields['KODE_SK_TANAH'];
	$r_kode_faktor			= $obj->fields['KODE_FAKTOR'];
	$r_kode_tipe			= $obj->fields['KODE_TIPE'];
	$r_kode_sk_bangunan		= $obj->fields['KODE_SK_BANGUNAN'];
	$r_kode_penjualan		= $obj->fields['KODE_PENJUALAN'];
	
	$r_nama_desa			= $obj->fields['NAMA_DESA'];
	$r_lokasi				= $obj->fields['LOKASI'];
	$r_jenis_unit			= $obj->fields['JENIS_UNIT'];
	$r_harga_tanah_sk		= $obj->fields['HARGA_TANAH_SK'];
	$r_faktor_strategis		= $obj->fields['FAKTOR_STRATEGIS'];
	$r_tipe_bangunan		= $obj->fields['TIPE_BANGUNAN'];
	$r_harga_bangunan_sk	= $obj->fields['HARGA_BANGUNAN_SK'];
	$r_jenis_penjualan		= $obj->fields['JENIS_PENJUALAN'];
	
	$r_tgl_bangunan			= tgltgl(f_tgl($obj->fields['TGL_BANGUNAN']));
	$r_tgl_selesai			= tgltgl(f_tgl($obj->fields['TGL_SELESAI']));
	$r_progress				= $obj->fields['PROGRESS'];
	$r_class				= $obj->fields['CLASS'];
	$r_status_gambar_siteplan	= $obj->fields['STATUS_GAMBAR_SITEPLAN'];
	$r_status_gambar_lapangan	= $obj->fields['STATUS_GAMBAR_LAPANGAN'];
	$r_status_gambar_gs		= $obj->fields['STATUS_GAMBAR_GS'];
	$r_program				= $obj->fields['PROGRAM'];
	
	$r_luas_tanah			= $obj->fields['LUAS_TANAH'];
	$r_base_harga_tanah		= $obj->fields['BASE_HARGA_TANAH'];
	$r_nilai_tambah			= $obj->fields['NILAI_TAMBAH'];
	$r_nilai_kurang			= $obj->fields['NILAI_KURANG'];
	$r_fs_harga_tanah		= $obj->fields['FS_HARGA_TANAH'];
	$r_disc_tanah			= $obj->fields['DISC_TANAH'];
	$r_disc_harga_tanah		= $obj->fields['DISC_HARGA_TANAH'];
	$r_ppn_tanah			= $obj->fields['PPN_TANAH'];
	$r_ppn_harga_tanah		= $obj->fields['PPN_HARGA_TANAH'];
	$r_harga_tanah			= $r_base_harga_tanah + $r_fs_harga_tanah - $r_disc_harga_tanah + $r_ppn_harga_tanah;
	
	$r_luas_bangunan		= $obj->fields['LUAS_BANGUNAN'];
	$r_base_harga_bangunan	= $obj->fields['BASE_HARGA_BANGUNAN'];
	$r_fs_harga_bangunan	= 0;
	$r_disc_bangunan		= $obj->fields['DISC_BANGUNAN'];
	$r_disc_harga_bangunan	= $obj->fields['DISC_HARGA_BANGUNAN'];
	$r_ppn_bangunan			= $obj->fields['PPN_BANGUNAN'];
	$r_ppn_harga_bangunan	= $obj->fields['PPN_HARGA_BANGUNAN'];
	$r_harga_bangunan		= $r_base_harga_bangunan + $r_fs_harga_bangunan - $r_disc_harga_bangunan + $r_ppn_harga_bangunan;
	
	$r_progres				= $obj->fields['PROGRESS'];
}
?>
<?php
require_once('../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$nm		= (isset($_REQUEST['nm'])) ? clean($_REQUEST['nm']) : '';
$alamat		= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';

$no_customer		= (isset($_REQUEST['no_customer'])) ? clean($_REQUEST['no_customer']) : '';
$tgl_spp			= (isset($_REQUEST['tgl_spp'])) ? clean($_REQUEST['tgl_spp']) : '';
$no_spp				= (isset($_REQUEST['no_spp'])) ? to_number($_REQUEST['no_spp']) : '';
$nama				= (isset($_REQUEST['nama'])) ? clean($_REQUEST['nama']) : '';
$alamat_rumah		= (isset($_REQUEST['alamat_rumah'])) ? clean($_REQUEST['alamat_rumah']) : '';
$alamat_surat		= (isset($_REQUEST['alamat_surat'])) ? clean($_REQUEST['alamat_surat']) : '';
$alamat_npwp		= (isset($_REQUEST['alamat_npwp'])) ? clean($_REQUEST['alamat_npwp']) : '';
$email				= (isset($_REQUEST['email'])) ? clean($_REQUEST['email']) : '';
$tlp_rumah			= (isset($_REQUEST['tlp_rumah'])) ? clean($_REQUEST['tlp_rumah']) : '';
$tlp_kantor			= (isset($_REQUEST['tlp_kantor'])) ? clean($_REQUEST['tlp_kantor']) : '';
$tlp_lain			= (isset($_REQUEST['tlp_lain'])) ? clean($_REQUEST['tlp_lain']) : '';
$identitas			= (isset($_REQUEST['identitas'])) ? clean($_REQUEST['identitas']) : '';
$no_identitas		= (isset($_REQUEST['no_identitas'])) ? clean($_REQUEST['no_identitas']) : '';
$npwp				= (isset($_REQUEST['npwp'])) ? clean($_REQUEST['npwp']) : '';
$jenis_npwp			= (isset($_REQUEST['jenis_npwp'])) ? clean($_REQUEST['jenis_npwp']) : '';
$bank				= (isset($_REQUEST['bank'])) ? clean($_REQUEST['bank']) : '';
$jumlah_kpr			= (isset($_REQUEST['jumlah_kpr'])) ? to_number($_REQUEST['jumlah_kpr']) : '0';
$agen				= (isset($_REQUEST['agen'])) ? clean($_REQUEST['agen']) : '';
$koordinator		= (isset($_REQUEST['koordinator'])) ? clean($_REQUEST['koordinator']) : '';
$tgl_akad			= (isset($_REQUEST['tgl_akad'])) ? clean($_REQUEST['tgl_akad']) : '';
$status_kompensasi	= (isset($_REQUEST['status_kompensasi'])) ? clean($_REQUEST['status_kompensasi']) : '';
$tanda_jadi			= (isset($_REQUEST['tanda_jadi'])) ? to_number($_REQUEST['tanda_jadi']) : '0';
$status_spp			= (isset($_REQUEST['status_spp'])) ? clean($_REQUEST['status_spp']) : '';
$tgl_proses			= (isset($_REQUEST['tgl_proses'])) ? clean($_REQUEST['tgl_proses']) : '';
$tgl_tanda_jadi		= (isset($_REQUEST['tgl_tanda_jadi'])) ? clean($_REQUEST['tgl_tanda_jadi']) : '';
$redistribusi		= (isset($_REQUEST['redistribusi'])) ? clean($_REQUEST['redistribusi']) : '';
$tgl_redistribusi	= (isset($_REQUEST['tgl_redistribusi'])) ? clean($_REQUEST['tgl_redistribusi']) : '';
$keterangan			= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$otorisasi			= (isset($_REQUEST['otorisasi'])) ? clean($_REQUEST['otorisasi']) : '';

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
		//ex_app('');
		//ex_mod('');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
		if ($act == 'Tambah') # Proses Tambah
		{
			//ex_ha('', 'I');
		
			ex_empty($alamat_rumah, 'Alamat rumah harus diisi.');
			ex_empty($identitas, 'Identitas harus diisi.');
			ex_empty($no_identitas, 'No identitas harus diisi.');
			ex_empty($tgl_spp, 'Tanggal SPP harus diisi.');
			ex_empty($koordinator, 'Koordinator harus diisi.');
			ex_empty($jumlah_kpr, 'Jumlah KPR harus diisi.');
			ex_empty($tanda_jadi, 'Tanda jadi harus diisi.');
			
			$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM SPP WHERE KODE_BLOK = '$kode_blok'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode blok \"$kode_blok\" telah terdaftar.");
			
			$query = "
			INSERT INTO SPP 
			(				 
							 KODE_BLOK,
							 NOMOR_CUSTOMER,
							 NOMOR_SPP,
							 NAMA_PEMBELI,
							 ALAMAT_RUMAH,
							 ALAMAT_SURAT,
							 ALAMAT_NPWP,
							 ALAMAT_EMAIL,
							 IDENTITAS,
							 NO_IDENTITAS,
							 NPWP,
							 JENIS_NPWP,
							 TELP_RUMAH,
							 TELP_KANTOR,
							 TELP_LAIN,
							 KODE_BANK,
							 TANGGAL_SPP,
							 KODE_AGEN,
							 KODE_KOORDINATOR,
							 JUMLAH_KPR,
							 STATUS_KOMPENSASI,
							 TANGGAL_AKAD,
							 TANDA_JADI,
							 TANGGAL_TANDA_JADI,
							 STATUS_SPP,
							 TANGGAL_PROSES,
							 SPP_REDISTRIBUSI,
							 SPP_REDISTRIBUSI_TANGGAL,
							 KETERANGAN
							 
			)
			VALUES
			(
							 '$id',
							 '$nama',
							 '$no_customer',
							 '$alamat_rumah',
							 '$alamat_surat',
							 '$alamat_npwp',
							 '$alamat_email',
							 '$identitas',
							 '$no_identitas',
							 '$npwp',
							 '$jenis_npwp',
							 '$tlp_rumah',
							 '$tlp_kantor',
							 '$tlp_lain',
							 '$bank',
							 CONVERT(DATETIME,$tgl_spp,105),
							 $jumlah_kpr,
							 '$agen',
							 '$koordinator',
							 CONVERT(DATETIME,'$tgl_akad',105),
							 '$status_kompensasi',
							 $tanda_jadi,
							 CONVERT(DATETIME,'$tgl_tanda_jadi',105),
							 '$status_spp',
							 CONVERT(DATETIME,'$tgl_proses',105),
							 '$redistribusi',
							 CONVERT(DATETIME,'$tgl_redistribusi',105),
							 '$keterangan'
			)				 
			";
			ex_false($conn->execute($query), $query);
			$query = "
			UPDATE SPP SET OTORISASI <> '1' WHERE KODE_BLOK = '$id'";		
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data SPP berhasil ditambahkan.';
		}
	/*
		else if ($act == 'Ubah') # Proses Ubah
		{
			//ex_ha('', 'U');
		
			ex_empty($alamat_rumah, 'Alamat rumah harus diisi.');
			ex_empty($identitas, 'Identitas harus diisi.');
			ex_empty($no_identitas, 'No identitas harus diisi.');
			ex_empty($tgl_spp, 'Tanggal SPP harus diisi.');
			ex_empty($koordinator, 'Koordinator harus diisi.');
			ex_empty($jumlah_kpr, 'Jumlah KPR harus diisi.');
			ex_empty($tanda_jadi, 'Tanda jadi harus diisi.');
			/*
			$query = "SELECT * FROM KWITANSI_TANDA_TERIMA WHERE KODE_BLOK = '$kode_blok' AND TANGGAL = CONVERT(DATETIME,'$tanggal',105) AND NAMA_PEMBELI = '$nama_pembayar' AND
			NOMOR_TELEPON = '$no_tlp' AND ALAMAT_PEMBELI = '$alamat' AND KETERANGAN = '$keterangan' AND JUMLAH_DITERIMA = $jumlah AND KOORDINATOR = '$koordinator' AND
			KASIR = '$penerima' AND BAYAR_UNTUK = '$pembayaran' AND BAYAR_SECARA = '$bayar_secara' AND BANK_GIRO = '$bank'
			";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE SPP
			SET 
				NAMA_PEMBELI 		= '$nama',
				TANGGAL_SPP			= CONVERT(DATETIME,'$tgl_spp',105),
				ALAMAT_RUMAH 		= '$alamat_rumah',
				ALAMAT_SURAT 		= '$alamat_surat',
				ALAMAT_NPWP  		= '$alamat_npwp',
				ALAMAT_EMAIL	 	= '$email', 
				TELP_RUMAH			= '$tlp_rumah',
				TELP_KANTOR			= '$tlp_kantor',
				TELP_LAIN			= '$tlp_lain',
				IDENTITAS			= '$identitas',
				NO_IDENTITAS		= '$no_identitas',
				NPWP				= '$npwp',
				JENIS_NPWP			= '$jenis_npwp',
				KODE_BANK			= '$bank',
				JUMLAH_KPR			= '$jumlah_kpr',
				KODE_AGEN			= '$agen',
				KODE_KOORDINATOR	= '$koordinator',
				TANGGAL_AKAD		= CONVERT(DATETIME,'$tgl_akad',105),
				STATUS_KOMPENSASI	= '$status_kompensasi',
				TANDA_JADI			= '$tanda_jadi',
				TANGGAL_TANDA_JADI	= CONVERT(DATETIME,'$tgl_tanda_jadi',105),
				STATUS_SPP			= '$status_spp',
				TANGGAL_PROSES		= CONVERT(DATETIME,'$tgl_proses',105),
				SPP_REDISTRIBUSI	= '$redistribusi',
				SPP_REDISTRIBUSI_TANGGAL = CONVERT(DATETIME,'$tgl_redistribusi',105),
				KETERANGAN			= '$keterangan'
				
			WHERE
				KODE_BLOK = '$id'
			";			
			ex_false($conn->execute($query), $query);
		
			$msg = 'Data SPP berhasil diubah.';
		}
	*/
		elseif ($act == 'Hapus') # Proses Hapus
		{
			//ex_ha('', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{/*
				$query = "DELETE FROM KWITANSI_TANDA_TERIMA WHERE NOMOR_KWITANSI = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}*/
			}		
			
			$msg = ($error) ? 'Sebagian data SPP gagal dihapus.' : 'Data SPP berhasil dihapus.'; 
			
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
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$query = "SELECT * FROM SPP WHERE KODE_BLOK = '$id'";
	$obj = $conn->execute($query);
	
	$tgl_spp			= tgltgl(f_tgl($obj->fields['TANGGAL_SPP']));	
	$no_spp				= $obj->fields['NOMOR_SPP'];
	$nama				= $obj->fields['NAMA_PEMBELI'];
	$alamat_rumah		= $obj->fields['ALAMAT_RUMAH'];
	$alamat_surat		= $obj->fields['ALAMAT_SURAT'];	
	$alamat_npwp		= $obj->fields['ALAMAT_NPWP'];
	$email				= $obj->fields['ALAMAT_EMAIL'];
	$tlp_rumah			= $obj->fields['TELP_RUMAH'];
	$tlp_kantor			= $obj->fields['TELP_KANTOR'];
	$tlp_lain			= $obj->fields['TELP_LAIN'];
	$identitas			= $obj->fields['IDENTITAS'];
	$no_identitas		= $obj->fields['NO_IDENTITAS'];
	$npwp				= $obj->fields['NPWP'];
	$jenis_npwp			= $obj->fields['JENIS_NPWP'];
	$bank				= $obj->fields['KODE_BANK'];
	$jumlah_kpr			= $obj->fields['JUMLAH_KPR'];
	$agen				= $obj->fields['KODE_AGEN'];
	$koordinator		= $obj->fields['KODE_KOORDINATOR'];	
	$tgl_akad			= tgltgl(f_tgl($obj->fields['TANGGAL_AKAD']));
	$status_kompensasi	= $obj->fields['STATUS_KOMPENSASI'];
	$tanda_jadi			= $obj->fields['TANDA_JADI'];
	$status_spp			= $obj->fields['STATUS_SPP'];
	$tgl_proses			= tgltgl(f_tgl($obj->fields['TANGGAL_PROSES']));
	$tgl_tanda_jadi		= tgltgl(f_tgl($obj->fields['TANGGAL_TANDA_JADI']));
	$redistribusi		= $obj->fields['SPP_REDISTRIBUSI'];
	$tgl_redistribusi	= tgltgl(f_tgl($obj->fields['SPP_REDISTRIBUSI_TANGGAL']));
	$keterangan			= $obj->fields['KETERANGAN'];	
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
	
	$r_kode_blok				= $obj->fields['KODE_BLOK'];
	
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
if ($act == 'Tambah')
{
	$query = "SELECT * FROM CS_REGISTER_CUSTOMER_SERVICE";
	$obj = $conn->execute($query);
	
	$no_spp		= 1 + $obj->fields['NOMOR_SPP'];
}
?>
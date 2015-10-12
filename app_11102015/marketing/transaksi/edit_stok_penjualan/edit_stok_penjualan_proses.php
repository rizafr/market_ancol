<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$jenis = (isset($_REQUEST['jenis'])) ? clean($_REQUEST['jenis']) : '';
$kode_sk_sebelumnya = (isset($_REQUEST['kode_sk_sebelumnya'])) ? clean($_REQUEST['kode_sk_sebelumnya']) : '';

$kode_blok		= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$kode_desa		= (isset($_REQUEST['kode_desa'])) ? clean($_REQUEST['kode_desa']) : '';
$kode_lokasi	= (isset($_REQUEST['kode_lokasi'])) ? clean($_REQUEST['kode_lokasi']) : '';
$kode_unit		= (isset($_REQUEST['kode_unit'])) ? clean($_REQUEST['kode_unit']) : '';
$kode_sk_tanah	= (isset($_REQUEST['kode_sk_tanah'])) ? clean($_REQUEST['kode_sk_tanah']) : '';
$kode_faktor	= (isset($_REQUEST['kode_faktor'])) ? clean($_REQUEST['kode_faktor']) : '';
$kode_tipe		= (isset($_REQUEST['kode_tipe'])) ? clean($_REQUEST['kode_tipe']) : '';
$kode_sk_bangunan = (isset($_REQUEST['kode_sk_bangunan'])) ? clean($_REQUEST['kode_sk_bangunan']) : '';
$kode_penjualan	= (isset($_REQUEST['kode_penjualan'])) ? clean($_REQUEST['kode_penjualan']) : '';

$class					= (isset($_REQUEST['class'])) ? clean($_REQUEST['class']) : '';
$status_gambar_siteplan	= (isset($_REQUEST['status_gambar_siteplan'])) ? to_number($_REQUEST['status_gambar_siteplan']) : '0';
$status_gambar_lapangan	= (isset($_REQUEST['status_gambar_lapangan'])) ? to_number($_REQUEST['status_gambar_lapangan']) : '0';
$status_gambar_gs		= (isset($_REQUEST['status_gambar_gs'])) ? to_number($_REQUEST['status_gambar_gs']) : '0';
$program				= (isset($_REQUEST['program'])) ? to_number($_REQUEST['program']) : '0';

$luas_tanah		= (isset($_REQUEST['luas_tanah'])) ? to_decimal($_REQUEST['luas_tanah']) : '0';
$disc_tanah		= (isset($_REQUEST['disc_tanah'])) ? to_decimal($_REQUEST['disc_tanah'], 16) : '0';
$ppn_tanah		= (isset($_REQUEST['ppn_tanah'])) ? to_decimal($_REQUEST['ppn_tanah']) : '0';

$luas_bangunan	= (isset($_REQUEST['luas_bangunan'])) ? to_decimal($_REQUEST['luas_bangunan']) : '0';
$disc_bangunan	= (isset($_REQUEST['disc_bangunan'])) ? to_decimal($_REQUEST['disc_bangunan'], 16) : '0';
$ppn_bangunan	= (isset($_REQUEST['ppn_bangunan'])) ? to_decimal($_REQUEST['ppn_bangunan']) : '0';

$nama_desa			= '';
$lokasi				= '';
$jenis_unit			= '';
$harga_tanah_sk		= '';
$faktor_strategis	= '';
$tipe_bangunan		= '';
$harga_bangunan_sk	= '';
$jenis_penjualan	= '';

$tgl_bangunan		= '';
$tgl_selesai		= '';
$progress			= '';
	
$base_harga_tanah		= 0;
$nilai_tambah			= 0;
$nilai_kurang			= 0;
$fs_harga_tanah			= 0;
$disc_harga_tanah		= 0;
$ppn_harga_tanah		= 0;
$harga_tanah			= 0;

$base_harga_bangunan	= 0;
$fs_harga_bangunan		= 0;
$disc_harga_bangunan	= 0;
$ppn_harga_bangunan		= 0;
$harga_bangunan			= 0;
	
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M17');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M17', 'U');
			
			ex_empty($kode_blok, 'Kode Blok harus diisi.');
			ex_empty($kode_desa, 'Desa harus diisi.');
			ex_empty($kode_lokasi, 'Lokasi harus diisi.');
			ex_empty($kode_unit, 'Jenis unit harus diisi.');
			ex_empty($kode_sk_tanah, 'SK tanah harus diisi.');
			ex_empty($kode_faktor, 'Faktor strategis harus diisi.');
			ex_empty($kode_tipe, 'Tipe harus diisi.');
			ex_empty($kode_sk_bangunan, 'SK bangunan harus diisi.');
			ex_empty($kode_penjualan, 'Jenis penjualan harus diisi.');
			ex_empty($class, 'Pilih class.');
			
			if ($kode_blok != $id)
			{
				$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM STOK WHERE KODE_BLOK = '$kode_blok'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode blok \"$kode_blok\" telah terdaftar.");
			}
			
			$query ="SELECT * FROM STOK WHERE KODE_BLOK = '$kode_blok' AND KODE_UNIT = '$kode_unit' AND KODE_DESA = '$kode_desa' AND KODE_LOKASI = '$kode_lokasi' AND 
					KODE_SK_TANAH = '$kode_sk_tanah' AND KODE_FAKTOR = '$kode_faktor' AND KODE_TIPE = '$kode_tipe' AND 
					KODE_SK_BANGUNAN = '$kode_sk_bangunan' AND KODE_PENJUALAN = '$kode_penjualan' AND LUAS_TANAH = '$luas_tanah' AND 
					LUAS_BANGUNAN = '$luas_bangunan' AND PPN_TANAH = '$ppn_tanah' AND PPN_BANGUNAN = '$ppn_bangunan' AND 
					DISC_TANAH = '$disc_tanah' AND DISC_BANGUNAN = '$disc_bangunan' AND CLASS = '$class' AND PROGRAM = '$program' AND 
					STATUS_GAMBAR_SITEPLAN = '$status_gambar_siteplan' AND STATUS_GAMBAR_LAPANGAN = '$status_gambar_lapangan' AND 
					STATUS_GAMBAR_GS = '$status_gambar_gs'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
					
			$query = "
			UPDATE STOK 
			SET KODE_SK_TANAH = '$kode_sk_tanah', 
				
			WHERE
				
				KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data persediaan awal berhasil diubah.';
		}
		elseif ($act == 'Ubah_SK') # Proses Ubah
		{
			ex_ha('M17', 'U');
			
			if($jenis == 'Tanah')
			{
				ex_empty($kode_sk_tanah, 'SK Tanah baru harus diisi.');
			}
			if($jenis == 'Bangunan')
			{
				ex_empty($kode_sk_bangunan, 'SK Bangunan baru harus diisi.');
			}
			
			if($jenis == 'Tanah')
			{
				$query = "
				UPDATE STOK 
				SET KODE_SK_TANAH = '$kode_sk_tanah' 
				WHERE
					KODE_SK_TANAH = '$kode_sk_sebelumnya' AND TERJUAL = '0'
				";
			}
			if($jenis == 'Bangunan')
			{
				$query = "
				UPDATE STOK 
				SET KODE_SK_BANGUNAN = '$kode_sk_bangunan' 
				WHERE
					KODE_SK_BANGUNAN = '$kode_sk_sebelumnya' AND TERJUAL = '0'
				";
			}
			
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data SK berhasil diubah.';
		}
		elseif ($act == 'Hapus-Status-Reserve') # Proses Hapus Status Reserve
		{
			ex_ha('M17', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				if ($conn->Execute("UPDATE STOK SET TERJUAL = '0' WHERE KODE_BLOK = '$id_del'")) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data stok gagal dihapus status reserve-nya.' : 'Data stok berhasil dihapus status reserve-nya.';
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
die_mod('M17');
$conn = conn($sess_db);
die_conn($conn);

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
	
	$kode_blok			= $obj->fields['KODE_BLOK'];
	
	$kode_desa			= $obj->fields['KODE_DESA'];
	$kode_lokasi		= $obj->fields['KODE_LOKASI'];
	$kode_unit			= $obj->fields['KODE_UNIT'];
	$kode_sk_tanah		= $obj->fields['KODE_SK_TANAH'];
	$kode_faktor		= $obj->fields['KODE_FAKTOR'];
	$kode_tipe			= $obj->fields['KODE_TIPE'];
	$kode_sk_bangunan	= $obj->fields['KODE_SK_BANGUNAN'];
	$kode_penjualan		= $obj->fields['KODE_PENJUALAN'];
	
	$nama_desa			= $obj->fields['NAMA_DESA'];
	$lokasi				= $obj->fields['LOKASI'];
	$jenis_unit			= $obj->fields['JENIS_UNIT'];
	$harga_tanah_sk		= $obj->fields['HARGA_TANAH_SK'];
	$faktor_strategis	= $obj->fields['FAKTOR_STRATEGIS'];
	$tipe_bangunan		= $obj->fields['TIPE_BANGUNAN'];
	$harga_bangunan_sk	= $obj->fields['HARGA_BANGUNAN_SK'];
	$jenis_penjualan	= $obj->fields['JENIS_PENJUALAN'];
	
	$tgl_bangunan		= tgltgl(f_tgl($obj->fields['TGL_BANGUNAN']));
	$tgl_selesai		= tgltgl(f_tgl($obj->fields['TGL_SELESAI']));
	$progress			= $obj->fields['PROGRESS'];
	$class				= $obj->fields['CLASS'];
	$status_gambar_siteplan	= $obj->fields['STATUS_GAMBAR_SITEPLAN'];
	$status_gambar_lapangan	= $obj->fields['STATUS_GAMBAR_LAPANGAN'];
	$status_gambar_gs		= $obj->fields['STATUS_GAMBAR_GS'];
	$program				= $obj->fields['PROGRAM'];
	
	$luas_tanah			= $obj->fields['LUAS_TANAH'];
	$base_harga_tanah	= $obj->fields['BASE_HARGA_TANAH'];
	$nilai_tambah		= $obj->fields['NILAI_TAMBAH'];
	$nilai_kurang		= $obj->fields['NILAI_KURANG'];
	$fs_harga_tanah		= $obj->fields['FS_HARGA_TANAH'];
	$disc_tanah			= $obj->fields['DISC_TANAH'];
	$disc_harga_tanah	= $obj->fields['DISC_HARGA_TANAH'];
	$ppn_tanah			= $obj->fields['PPN_TANAH'];
	$ppn_harga_tanah	= $obj->fields['PPN_HARGA_TANAH'];
	$harga_tanah		= $base_harga_tanah + $fs_harga_tanah - $disc_harga_tanah + $ppn_harga_tanah;
	
	$luas_bangunan			= $obj->fields['LUAS_BANGUNAN'];
	$base_harga_bangunan	= $obj->fields['BASE_HARGA_BANGUNAN'];
	$fs_harga_bangunan		= 0;
	$disc_bangunan			= $obj->fields['DISC_BANGUNAN'];
	$disc_harga_bangunan	= $obj->fields['DISC_HARGA_BANGUNAN'];
	$ppn_bangunan			= $obj->fields['PPN_BANGUNAN'];
	$ppn_harga_bangunan		= $obj->fields['PPN_HARGA_BANGUNAN'];
	$harga_bangunan			= $base_harga_bangunan + $fs_harga_bangunan - $disc_harga_bangunan + $ppn_harga_bangunan;
}
?>
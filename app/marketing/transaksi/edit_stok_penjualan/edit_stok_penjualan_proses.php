<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$jenis = (isset($_REQUEST['jenis'])) ? clean($_REQUEST['jenis']) : '';
$kode_sk_sebelumnya = (isset($_REQUEST['kode_sk_sebelumnya'])) ? clean($_REQUEST['kode_sk_sebelumnya']) : '';

$no_va			= (isset($_REQUEST['no_va'])) ? clean($_REQUEST['no_va']) : '';
$kode_blok		= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$kode_desa		= (isset($_REQUEST['kode_desa'])) ? clean($_REQUEST['kode_desa']) : '';
$kode_lokasi	= (isset($_REQUEST['kode_lokasi'])) ? clean($_REQUEST['kode_lokasi']) : '';
$kode_unit		= (isset($_REQUEST['kode_unit'])) ? clean($_REQUEST['kode_unit']) : '';
$kode_sk_tanah	= (isset($_REQUEST['kode_sk_tanah'])) ? clean($_REQUEST['kode_sk_tanah']) : '';
$kode_faktor	= (isset($_REQUEST['kode_faktor'])) ? clean($_REQUEST['kode_faktor']) : '';
$kode_tipe		= (isset($_REQUEST['kode_tipe'])) ? clean($_REQUEST['kode_tipe']) : '';
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

// HARGA DI TABEL SK
$kode_sk	= (isset($_REQUEST['kode_sk'])) ? ($_REQUEST['kode_sk']) : '';;
$harga_cash_keras	= (isset($_REQUEST['harga_cash_keras'])) ? bigintval($_REQUEST['harga_cash_keras']) : '0';;
$CB36X	=(isset($_REQUEST['harga_CB36X'])) ? bigintval($_REQUEST['harga_CB36X']) : '0';;
$CB48X	= (isset($_REQUEST['harga_CB48X'])) ? bigintval($_REQUEST['harga_CB48X']) : '0';;
$KPA24X	=(isset($_REQUEST['harga_KPA24X'])) ? bigintval($_REQUEST['harga_KPA24X']) : '0';;
$KPA36X	=(isset($_REQUEST['harga_KPA36X'])) ? bigintval($_REQUEST['harga_KPA36X']) : '0';;

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
			ex_empty($kode_lokasi, 'Lokasi harus diisi.');
			ex_empty($kode_unit, 'Jenis unit harus diisi.');
			ex_empty($kode_tipe, 'Tipe harus diisi.');
			ex_empty($kode_sk, 'SK bangunan harus diisi.');
			ex_empty($kode_penjualan, 'Jenis penjualan harus diisi.');
			
			if ($kode_blok != $id)
			{
				$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM STOK WHERE KODE_BLOK = '$kode_blok'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "Kode blok \"$kode_blok\" telah terdaftar.");
			}
			
			$query ="SELECT * FROM STOK WHERE KODE_BLOK = '$kode_blok' AND KODE_UNIT = '$kode_unit' AND KODE_DESA = '$kode_desa' AND KODE_LOKASI = '$kode_lokasi' AND 
			KODE_SK_TANAH = '$kode_sk_tanah' AND KODE_FAKTOR = '$kode_faktor' AND KODE_TIPE = '$kode_tipe' AND 
			KODE_SK = '$kode_sk' AND KODE_PENJUALAN = '$kode_penjualan' AND LUAS_TANAH = '$luas_tanah' AND 
			LUAS_BANGUNAN = '$luas_bangunan' AND PPN_TANAH = '$ppn_tanah' AND PPN_BANGUNAN = '$ppn_bangunan' AND 
			DISC_TANAH = '$disc_tanah' AND DISC_BANGUNAN = '$disc_bangunan' AND CLASS = '$class' AND PROGRAM = '$program' AND 
			STATUS_GAMBAR_SITEPLAN = '$status_gambar_siteplan' AND STATUS_GAMBAR_LAPANGAN = '$status_gambar_lapangan' AND 
			STATUS_GAMBAR_GS = '$status_gambar_gs'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE STOK 
			SET NO_VA = '$no_va',
			KODE_BLOK = '$kode_blok', 
			KODE_UNIT = '$kode_unit', 
			KODE_LOKASI = '$kode_lokasi', 
			KODE_TIPE = '$kode_tipe', 
			KODE_SK = '$kode_sk', 
			KODE_PENJUALAN = '$kode_penjualan', 				
			LUAS_BANGUNAN = '$luas_bangunan'
			WHERE			
			KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);

			$query = "
			UPDATE HARGA_SK 
			SET 
				HARGA_CASH_KERAS = '$harga_cash_keras',
				CB36X = '$CB36X',
				CB48X = '$CB48X',
				KPA24X = '$KPA24X',
				KPA36X = '$KPA36X'
			WHERE
				KODE_SK = '$kode_sk' 
				AND KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data siap jual berhasil diubah.';
		}
		elseif ($act == 'Ubah_SK') # Proses Ubah
		{
			ex_ha('M17', 'U');
			
			if($jenis == 'Harga_SK')
			{
				ex_empty($kode_sk, 'SK Harga baru harus diisi.');
			}
						
			if($jenis == 'Harga_SK')
			{
				$query = "
				UPDATE STOK 
				SET KODE_SK = '$kode_sk' 
				WHERE
				KODE_SK = '$kode_sk_sebelumnya' AND TERJUAL = '0'
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
		s.NO_VA,
		s.KODE_BLOK,
		s.LUAS_BANGUNAN,
		s.STATUS_STOK,
		s.TERJUAL,
		s.KODE_PENJUALAN,
		t.TIPE_BANGUNAN,
		hs.KODE_SK,
		hs.HARGA_CASH_KERAS,
		hs.CB36X,
		hs.CB48X,
		hs.KPA24X,
		hs.KPA36X,
		h.*,
		ju.*,
		t.*,
		p.*
		FROM 
		STOK s
		LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
		LEFT JOIN HARGA_SK hs ON s.KODE_SK = hs.KODE_SK
		LEFT JOIN LOKASI h ON s.KODE_LOKASI = h.KODE_LOKASI
		LEFT JOIN JENIS_UNIT ju ON s.KODE_UNIT = ju.KODE_UNIT
		LEFT JOIN JENIS_PENJUALAN p ON s.KODE_PENJUALAN = p.KODE_JENIS
		WHERE		
		s.KODE_BLOK = '$id'");
	
	$no_va			    = $obj->fields['NO_VA'];
	$kode_blok			= $obj->fields['KODE_BLOK'];
	$kode_lokasi		= $obj->fields['KODE_LOKASI'];
	$kode_unit			= $obj->fields['KODE_UNIT'];
	$kode_tipe			= $obj->fields['KODE_TIPE'];
	$kode_penjualan		= $obj->fields['KODE_PENJUALAN'];
	$kode_sk			= $obj->fields['KODE_SK'];
	
	$lokasi				= $obj->fields['LOKASI'];
	$jenis_unit			= $obj->fields['JENIS_UNIT'];
	$tipe_bangunan		= $obj->fields['TIPE_BANGUNAN'];
	$jenis_penjualan	= $obj->fields['JENIS_PENJUALAN'];
	
	$luas_bangunan		= $obj->fields['LUAS_BANGUNAN'];

	$harga_cash_keras	= $obj->fields['HARGA_CASH_KERAS'];
	$CB36X	= $obj->fields['CB36X'];
	$CB48X	= $obj->fields['CB48X'];
	$KPA24X	= $obj->fields['KPA24X'];
	$KPA36X	= $obj->fields['KPA36X'];
}
?>
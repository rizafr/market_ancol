<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$nama_calon_pembeli		= (isset($_REQUEST['nama_calon_pembeli'])) ? clean($_REQUEST['nama_calon_pembeli']) : '';
$tanggal_reserve		= (isset($_REQUEST['tanggal_reserve'])) ? clean($_REQUEST['tanggal_reserve']) : '';
$berlaku_sampai			= (isset($_REQUEST['berlaku_sampai'])) ? clean($_REQUEST['berlaku_sampai']) : '';
$alamat					= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';
$telepon				= (isset($_REQUEST['telepon'])) ? clean($_REQUEST['telepon']) : '';
$agen					= (isset($_REQUEST['agen'])) ? clean($_REQUEST['agen']) : '';
$koordinator			= (isset($_REQUEST['koordinator'])) ? clean($_REQUEST['koordinator']) : '';
	
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('A01');
		//ex_mod('PO01');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Reserve') # Proses Reserve
		{
			//ex_ha('', '');
			
			ex_empty($nama_calon_pembeli, 'Nama calon pembeli harus diisi.');			
			ex_empty($tanggal_reserve, 'Tanggal reserve harus diisi.');
			ex_empty($berlaku_sampai, 'Tanggal berlaku sampai harus diisi.');
			ex_empty($alamat, 'Alamat harus diisi.');
			ex_empty($telepon, 'No Telepon harus diisi.');
			ex_empty($agen, 'Agen harus diisi.');
			ex_empty($koordinator, 'Koordinator harus diisi.');
								
			$query = "
			INSERT INTO RESERVE
			(
				KODE_BLOK, NAMA_CALON_PEMBELI, TANGGAL_RESERVE, BERLAKU_SAMPAI, 
				ALAMAT, TELEPON, AGEN, KOORDINATOR
			)
			VALUES
			(
				'$id', '$nama_calon_pembeli', CONVERT(DATETIME,'$tanggal_reserve',105), 
				CONVERT(DATETIME,'$berlaku_sampai',105), '$alamat', '$telepon', '$agen', '$koordinator'
			)
			";
			ex_false($conn->Execute($query), $query);
			
			$query = "
			UPDATE STOK SET
				TERJUAL = '1' 
			WHERE 
				KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data reserve berhasil disimpan.';
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
//die_app('A01');
//die_mod('PO01');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Detail')
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

if ($act == 'Reserve')
{
	$obj = $conn->Execute("SELECT GETDATE() AS TANGGAL_RESERVE, GETDATE()+3 AS BERLAKU_SAMPAI");	
	$tanggal_reserve		= tgltgl(date("d-m-Y",strtotime($obj->fields['TANGGAL_RESERVE'])));
	$berlaku_sampai			= tgltgl(date("d-m-Y",strtotime($obj->fields['BERLAKU_SAMPAI'])));
}
?>
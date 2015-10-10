<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$jenis_pembayaran	= (isset($_REQUEST['jenis_pembayaran'])) ? clean($_REQUEST['jenis_pembayaran']) : '';
$nama_pembayar		= (isset($_REQUEST['nama_pembayar'])) ? clean($_REQUEST['nama_pembayar']) : '';
$keterangan			= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$jumlah				= (isset($_REQUEST['jumlah'])) ? to_number($_REQUEST['jumlah']) : '';
$diposting			= (isset($_REQUEST['diposting'])) ? to_number($_REQUEST['diposting']) : '';
$tanggal			= (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$tgl_terima			= (isset($_REQUEST['tgl_terima'])) ? clean($_REQUEST['tgl_terima']) : '';
$via				= (isset($_REQUEST['via'])) ? clean($_REQUEST['via']) : '';
$catatan			= (isset($_REQUEST['catatan'])) ? clean($_REQUEST['catatan']) : '';
$catatan_kwt		= (isset($_REQUEST['catatan_kwt'])) ? clean($_REQUEST['catatan_kwt']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('');
		ex_mod('K02');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Ubah') # Proses Ubah
		{
			//ex_ha('', 'U');
		
			ex_empty($nama_pembayar, 'Telah Terima Dari harus diisi.');
			ex_empty($keterangan, 'Untuk Pembayaran harus diisi.');
			ex_empty($jumlah, 'Jumlah harus diisi.');
			ex_empty($diposting, 'Diposting harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			ex_empty($via, 'Via harus diisi.');
			
			$query = "SELECT * FROM KWITANSI_LAIN_LAIN WHERE NAMA_PEMBAYAR = '$nama_pembayar' AND TANGGAL = CONVERT(DATETIME,'$tanggal',105) AND 
			NILAI = $jumlah AND KETERANGAN = '$keterangan' AND NILAI_DIPOSTING = $diposting AND TANGGAL_BAYAR = CONVERT(DATETIME,'$tgl_terima',105) AND
			BAYAR_VIA = '$via' AND CATATAN = '$catatan'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE KWITANSI_LAIN_LAIN 
			SET
				NAMA_PEMBAYAR = '$nama_pembayar', 
				TANGGAL = CONVERT(DATETIME,'$tanggal',105), 
				NILAI = $jumlah, 
				KETERANGAN = '$keterangan', 
				NILAI_DIPOSTING = $diposting, 
				TANGGAL_BAYAR = CONVERT(DATETIME,'$tgl_terima',105), 
				BAYAR_VIA = '$via', 
				CATATAN = '$catatan'
			WHERE
				NOMOR_KWITANSI = '$id'
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data Kuitansi berhasil diubah.';
		}
		elseif ($act == 'Tambah') # Proses Tambah
		{
			//ex_ha('', 'I');
		
			ex_empty($jenis_pembayaran, 'Jenis Pembayaran harus diisi.');
			ex_empty($nama_pembayar, 'Telah Terima Dari harus diisi.');
			ex_empty($keterangan, 'Untuk Pembayaran harus diisi.');
			ex_empty($jumlah, 'Jumlah harus diisi.');
			ex_empty($diposting, 'Diposting harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			ex_empty($via, 'Via harus diisi.');
			
			$query = "
			INSERT INTO KWITANSI_LAIN_LAIN (
				KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, NILAI, KETERANGAN, KODE_PEMBAYARAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, VER_COLLECTION, VER_KEUANGAN
			)
			VALUES(
				'$id', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal',105), $jumlah, '$keterangan', '$jenis_pembayaran', $diposting, CONVERT(DATETIME,'$tgl_terima',105), '$via', '$catatan', '0', '0'
			)
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data Kuitansi telah ditambah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{			
			//ex_ha('', 'D');
		
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "DELETE FROM KWITANSI_LAIN_LAIN WHERE NOMOR_KWITANSI = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Kuitansi berhasil dihapus.';
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
die_mod('K02');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Detail')
{
	$query = "
	SELECT *
	FROM
		SPP a 
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
		LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
		LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
		LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
	WHERE 
		a.KODE_BLOK = '$id'";
	$obj = $conn->execute($query);
	
	$kode_blok 			= $obj->fields['KODE_BLOK'];
	$no_spp 			= $obj->fields['NOMOR_SPP'];
	$nama_pembeli 		= $obj->fields['NAMA_PEMBELI'];
	$alamat 			= $obj->fields['ALAMAT_RUMAH'];
	$tlp1 				= $obj->fields['TELP_RUMAH'];
	$tanggal_spp		= kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SPP']))));
	$no_identitas		= $obj->fields['NO_IDENTITAS'];	
	$npwp 				= $obj->fields['NPWP'];
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
}

if ($act == 'Ubah')
{
	$query = "
		SELECT * FROM KWITANSI_LAIN_LAIN a
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI		
		LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
		LEFT JOIN JENIS_PEMBAYARAN e ON a.KODE_PEMBAYARAN = e.KODE_BAYAR
		WHERE NOMOR_KWITANSI = '$id'
	";
	$obj = $conn->execute($query);
	
	$nomor			= $obj->fields['NOMOR_KWITANSI'];
	$nama_pembayar 	= $obj->fields['NAMA_PEMBAYAR'];
	$keterangan 	= $obj->fields['KETERANGAN'];
	$jumlah 		= round($obj->fields['NILAI']);
	$diposting 		= $obj->fields['NILAI_DIPOSTING'];
	$tanggal		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));
	$tgl_terima		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_BAYAR'])));
	$via			= $obj->fields['BAYAR_VIA'];
	$catatan		= $obj->fields['CATATAN'];
	$catatan_kwt	= $obj->fields['CATATAN_KWT'];
	$biro 				= $obj->fields['VER_COLLECTION'];
	$keuangan			= $obj->fields['VER_KEUANGAN'];
		
	$jenis_pembayaran	= $obj->fields['JENIS_BAYAR'];	
	$kode_bayar			= $obj->fields['KODE_BAYAR'];	
	
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	$lokasi 			= $obj->fields['LOKASI'];
	$kode_blok 			= $obj->fields['KODE_BLOK'];	
	$tipe	 			= $obj->fields['TIPE_BANGUNAN'];
}

if ($act == 'Tambah')
{
	$query = "
		SELECT * FROM SPP a
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI		
		LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
		WHERE a.KODE_BLOK = '$id'
	";
	$obj = $conn->execute($query);
	
	$nama_pembayar	= $obj->fields['NAMA_PEMBELI'];
	$nomor			= '';	
	$keterangan 	= '';
	$jumlah 		= '';
	$diposting 		= '';
	$tanggal		= '';
	$tgl_terima		= '';
	$via			= '';
	$catatan		= '';
	$biro 				= 0;
	$keuangan			= 0;
	
	$jenis_pembayaran	= 0;	
	$kode_bayar			= 0;	

	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	$lokasi 			= $obj->fields['LOKASI'];
	$kode_blok 			= $obj->fields['KODE_BLOK'];	
	$tipe	 			= $obj->fields['TIPE_BANGUNAN'];
}
?>
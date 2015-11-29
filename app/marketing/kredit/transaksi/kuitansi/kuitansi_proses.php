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
$subtotal			= (isset($_REQUEST['subtotal'])) ? to_number($_REQUEST['subtotal']) : '';
$ppn				= (isset($_REQUEST['ppn'])) ? to_number($_REQUEST['ppn']) : '';
//$via				= (isset($_REQUEST['bayar_secara'])) ? to_number($_REQUEST['bayar_secara']) : '';

$blok_baru			= (isset($_REQUEST['blok_baru'])) ? clean($_REQUEST['blok_baru']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('');
		ex_mod('K01');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Ubah') # Proses Ubah
		{
			//ex_ha('', 'U');
			
			ex_empty($tgl_terima, 'Tanggal Terima harus diisi.');
			ex_empty($jenis_pembayaran, 'Jenis Pembayaran harus diisi.');
			ex_empty($nama_pembayar, 'Telah Terima Dari harus diisi.');
			ex_empty($keterangan, 'Untuk Pembayaran harus diisi.');
			ex_empty($jumlah, 'Jumlah harus diisi.');
			ex_empty($diposting, 'Diposting harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			ex_empty($via, 'Via harus diisi.');
			
			$query = "SELECT * FROM KWITANSI WHERE NAMA_PEMBAYAR = '$nama_pembayar' AND TANGGAL = CONVERT(DATETIME,'$tanggal',105) AND 
			NILAI = $jumlah AND KETERANGAN = '$keterangan' AND NILAI_DIPOSTING = $diposting AND TANGGAL_BAYAR = CONVERT(DATETIME,'$tgl_terima',105) AND
			BAYAR_VIA = '$via' AND CATATAN = '$catatan'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE KWITANSI 
			SET
				NAMA_PEMBAYAR = '$nama_pembayar', 
				TANGGAL = CONVERT(DATETIME,'$tanggal',105), 
				KODE_BAYAR = $jenis_pembayaran,
				NILAI = $jumlah, 
				KETERANGAN = '$keterangan', 
				NILAI_DIPOSTING = $diposting, 
				TANGGAL_BAYAR = CONVERT(DATETIME,'$tgl_terima',105), 
				BAYAR_VIA = '$via', 
				CATATAN = '$catatan',
				CATATAN_KWT = '$catatan_kwt',
				PPN = $ppn, 
				NILAI_NETT = $subtotal
			WHERE
				NOMOR_KWITANSI = '$id'
			";			
			ex_false($conn->execute($query), $query);
			
			$query = "
			UPDATE FAKTUR_PAJAK 
			SET
				NAMA = '$nama_pembayar', 
				TGL_FAKTUR = CONVERT(DATETIME,'$tanggal',105), 
				NILAI = $jumlah, 
				KETERANGAN = '$keterangan', 
				NILAI_PPN = $ppn, 
				NILAI_DASAR_PENGENAAN = $subtotal
			WHERE
				NO_KWITANSI = '$id'
			";			
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data Kuitansi berhasil diubah.';
		}
		elseif ($act == 'Tambah') # Proses Tambah
		{
			//ex_ha('', 'I');
			
			ex_empty($tgl_terima, 'Tanggal Diterima harus diisi.');
			ex_empty($jenis_pembayaran, 'Jenis Pembayaran harus diisi.');
			ex_empty($nama_pembayar, 'Telah Terima Dari harus diisi.');
			ex_empty($keterangan, 'Untuk Pembayaran harus diisi.');
			ex_empty($jumlah, 'Jumlah harus diisi.');
			ex_empty($diposting, 'Diposting harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			ex_empty($via, 'Via harus diisi.');
			
			$query = "
			INSERT INTO KWITANSI (
				KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, KODE_BAYAR, NILAI, KETERANGAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, CATATAN_KWT, PPN, NILAI_NETT, VER_COLLECTION, VER_KEUANGAN
			)
			VALUES(
				'$id', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal',105), $jenis_pembayaran, $jumlah, '$keterangan', $diposting, CONVERT(DATETIME,'$tgl_terima',105), '$via', '$catatan', '$catatan_kwt', $ppn, $subtotal, '0', '0'
			)
			";
			ex_false($conn->execute($query), $query);
			
			$query = " SELECT * FROM SPP WHERE KODE_BLOK = '$id' ";
			$obj 	= $conn->execute($query);
			$alamat_1	= $obj->fields['ALAMAT_RUMAH'];
			$npwp 		= $obj->fields['NPWP'];
			$jenis 		= $obj->fields['IDENTITAS'];
			
			$query = "
			INSERT INTO FAKTUR_PAJAK (
				KODE_BLOK, NO_KWITANSI, NAMA, ALAMAT_1, NPWP, JENIS, TGL_FAKTUR, KETERANGAN, NILAI, NILAI_DASAR_PENGENAAN, NILAI_PPN 
			)
			VALUES(
				'$id', 'XXX', '$nama_pembayar', '$alamat_1', '$npwp', '$jenis', CONVERT(DATETIME,'$tanggal',105), '$keterangan', $jumlah, $subtotal, $ppn
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
				$query = "DELETE FROM KWITANSI WHERE NOMOR_KWITANSI = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
				
				$query = "DELETE FROM FAKTUR_PAJAK WHERE NO_KWITANSI = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Kuitansi berhasil dihapus.';
		}
		elseif ($act == 'Pindah') # Proses Pindah
		{
			//ex_ha('', 'U');
		
			ex_empty($blok_baru, 'Blok baru harus diisi.');
			
			$query = "
			UPDATE KWITANSI 
			SET
				KODE_BLOK = '$blok_baru', 
				STATUS_PINDAH_BLOK = '1'
			WHERE
				KODE_BLOK = '$id'
			";
			ex_false($conn->execute($query), $query);
			
			$query = "
			UPDATE KWITANSI_LAIN_LAIN
			SET
				KODE_BLOK = '$blok_baru'
			WHERE
				KODE_BLOK = '$id'
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Blok berhasil dipindahkan.';
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
die_mod('K01');
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
		LEFT JOIN CS_VIRTUAL_ACCOUNT g ON a.NOMOR_CUSTOMER = g.NOMOR_VA		
	WHERE 
		a.KODE_BLOK = '$id'";
	$obj = $conn->execute($query);
	
	$customer_id 		= $obj->fields['COSTUMER_ID'];
	$kode_blok 			= $obj->fields['KODE_BLOK'];
	$nama_pembeli 		= $obj->fields['NAMA_PEMBELI'];
	$alamat 			= $obj->fields['ALAMAT_RUMAH'];
	$tlp1 				= $obj->fields['TELP_RUMAH'];
	$tanggal_spp		= kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SPP']))));
	$no_identitas		= $obj->fields['NO_IDENTITAS'];	
	$npwp 				= $obj->fields['NPWP'];
	$pola_bayar 		= $obj->fields['POLA_BAYAR'];
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	$nomor_va			= $obj->fields['NOMOR_VA'];
	$nilai				= $obj->fields['NILAI'];
	
	
	
	$sisa_pembayaran	=   $obj->fields['HARGA_TOTAL'];		
	$tanda_jadi 		= $obj->fields['TANDA_JADI'];	
	$tgl_jadi	 		= $obj->fields['TANGGAL_TANDA_JADI'];
	$jml_kpr	 		= $obj->fields['JUMLAH_KPR'];
	$no_spp 			= $obj->fields['NOMOR_SPP'];	
}

if ($act == 'Ubah')
{
	$query 			= "SELECT COUNT(*)+1 AS COUNT_RENCANA FROM RENCANA R JOIN KWITANSI K ON R.KODE_BLOK = K.KODE_BLOK WHERE R.KODE_BLOK = '$id' AND MONTH(K.TANGGAL) = MONTH(R.TANGGAL) AND YEAR(K.TANGGAL) = YEAR(R.TANGGAL) AND K.KODE_BAYAR = '4'";
	$count_rencana  = $conn->Execute($query)->fields['COUNT_RENCANA']; 
	
	$query = "
		SELECT * FROM KWITANSI a
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI		
		LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
		LEFT JOIN JENIS_PEMBAYARAN e ON a.KODE_BAYAR = e.KODE_BAYAR
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
	$biro 			= $obj->fields['VER_COLLECTION'];
	$keuangan		= $obj->fields['VER_KEUANGAN'];
	$pindah 		= $obj->fields['STATUS_PINDAH_BLOK'];
	$posting 		= $obj->fields['STATUS_POSTING'];

	$jenis_bayar		= $obj->fields['JENIS_BAYAR'];	
	if ($jenis_bayar == NULL) {
		$jenis_pembayaran	= 'JENIS_BAYAR';
		$kode_bayar			= 0;
	}
	else {
		$jenis_pembayaran	= $jenis_bayar;
		$kode_bayar			= $obj->fields['KODE_BAYAR'];
	}	
	
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	$lokasi 			= $obj->fields['LOKASI'];
	$kode_blok 			= $obj->fields['KODE_BLOK'];	
	$tipe	 			= $obj->fields['TIPE_BANGUNAN'];
	
	$query = " SELECT * FROM SPP WHERE KODE_BLOK = '$kode_blok' ";
	$obj 	= $conn->execute($query);
	$kpr	= $obj->fields['JUMLAH_KPR'];
}

if ($act == 'Tambah')
{
	$query 			= "SELECT COUNT(*)+1 AS COUNT_RENCANA FROM RENCANA R JOIN KWITANSI K ON R.KODE_BLOK = K.KODE_BLOK WHERE R.KODE_BLOK = '$id' AND MONTH(K.TANGGAL) = MONTH(R.TANGGAL) AND YEAR(K.TANGGAL) = YEAR(R.TANGGAL) AND K.KODE_BAYAR = '4'";
	$count_rencana  = $conn->Execute($query)->fields['COUNT_RENCANA']; 

	$query = "
		SELECT * FROM SPP a
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI		
		LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
		WHERE a.KODE_BLOK = '$id'
	";
	$obj = $conn->execute($query);
	
	$nama_pembayar	= $obj->fields['NAMA_PEMBELI'];
	$kpr	 		= $obj->fields['JUMLAH_KPR'];

	$nomor			= '';	
	$keterangan 	= '';
	$jumlah 		= '';
	$diposting 		= '';
	$tanggal		= '';
	$tgl_terima		= '';
	$via			= '';
	$catatan		= '';
	$catatan_kwt	= '';
	$biro 				= 0;
	$keuangan			= 0;
	$pindah 			= 0;
	$posting 			= 0;
	
	$jenis_pembayaran	= 0;	
	$kode_bayar			= 0;	

	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	$lokasi 			= $obj->fields['LOKASI'];
	$kode_blok 			= $obj->fields['KODE_BLOK'];	
	$tipe	 			= $obj->fields['TIPE_BANGUNAN'];

	$tanggal = date('d-m-Y');
}

?>
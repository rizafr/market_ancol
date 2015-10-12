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
		
			ex_empty($jenis_pembayaran, 'Jenis Pembayaran harus diisi.');
			ex_empty($nama_pembayar, 'Telah Terima Dari harus diisi.');
			ex_empty($keterangan, 'Untuk Pembayaran harus diisi.');
			ex_empty($jumlah, 'Jumlah harus diisi.');
			ex_empty($diposting, 'Diposting harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			ex_empty($via, 'Via harus diisi.');
			
			$query = "
			INSERT INTO KWITANSI (
				KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, KODE_BAYAR, NILAI, KETERANGAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, CATATAN_KWT PPN, NILAI_NETT, VER_COLLECTION, VER_KEUANGAN
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
	
	$kode_blok 			= $obj->fields['KODE_BLOK'];
	$nama_pembeli 		= $obj->fields['NAMA_PEMBELI'];
	$alamat 			= $obj->fields['ALAMAT_RUMAH'];
	$tlp1 				= $obj->fields['TELP_RUMAH'];
	$tanggal_spp		= kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SPP']))));
	$no_identitas		= $obj->fields['NO_IDENTITAS'];	
	$npwp 				= $obj->fields['NPWP'];
	$luas_tanah 		= $obj->fields['LUAS_TANAH'];
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	$nomor_va			= $obj->fields['NOMOR_VA'];
	$nilai				= $obj->fields['NILAI'];
	
	$tanah 				= $luas_tanah * ($obj->fields['HARGA_TANAH']) ;
	$disc_tanah 		= round($tanah * ($obj->fields['DISC_TANAH'])/100,0) ;
	$nilai_tambah		= round(($tanah - $disc_tanah) * ($obj->fields['NILAI_TAMBAH'])/100,0) ;
	$nilai_kurang		= round(($tanah - $disc_tanah) * ($obj->fields['NILAI_KURANG'])/100,0) ;
	$faktor				= $nilai_tambah - $nilai_kurang;
	$total_tanah		= $tanah - $disc_tanah + $faktor;
	$ppn_tanah 			= round($total_tanah * ($obj->fields['PPN_TANAH'])/100,0) ;
	
	$bangunan 			= $luas_bangunan * ($obj->fields['HARGA_BANGUNAN']) ;
	$disc_bangunan 		= round($bangunan * ($obj->fields['DISC_BANGUNAN'])/100,0) ;
	$total_bangunan		= $bangunan - $disc_bangunan;
	$ppn_bangunan 		= round($total_bangunan * ($obj->fields['PPN_BANGUNAN'])/100,0) ;
	
	$total_harga 		= to_money($total_tanah + $total_bangunan);
	$total_ppn			= to_money($ppn_tanah + $ppn_bangunan);
	
	$sisa_pembayaran	= ($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan);	
	$tanda_jadi 		= $obj->fields['TANDA_JADI'];	
	$tgl_jadi	 		= $obj->fields['TANGGAL_TANDA_JADI'];
	$jml_kpr	 		= $obj->fields['JUMLAH_KPR'];
	$no_spp 			= $obj->fields['NOMOR_SPP'];	
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
	$diposting 		= $obj->fields['NILAI'];
	$tanggal		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));
	$tgl_terima		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));
	$via			= $obj->fields['BANK_GIRO'];
	$catatan		= $obj->fields['KETERANGAN'];
	$catatan_kwt	= $obj->fields['KETERANGAN'];

	$jenis_bayar	= $obj->fields['JENIS_BAYAR'];
	$kasir			= $obj->fields['KASIR'];	
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
}

?>
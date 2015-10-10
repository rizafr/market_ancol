<?php
require_once('../../../../../config/config.php');
$msg 	= '';
$error	= FALSE;
$act				= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id					= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$status_otorisasi	= (isset($_REQUEST['status_otorisasi'])) ? clean($_REQUEST['status_otorisasi']) : '';
$kode_bayar			= (isset($_REQUEST['jenis_pembayaran'])) ? clean($_REQUEST['jenis_pembayaran']) : '';
$kode_lain			= (isset($_REQUEST['kode_lain'])) ? clean($_REQUEST['kode_lain']) : '';
$jenis_pembayaran	= (isset($_REQUEST['jenis_pembayaran'])) ? clean($_REQUEST['jenis_pembayaran']) : '';
$nama_pembayar		= (isset($_REQUEST['nama_pembayar'])) ? clean($_REQUEST['nama_pembayar']) : '';
$keterangan			= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$jumlah				= (isset($_REQUEST['jumlah'])) ? to_number($_REQUEST['jumlah']) : '';
$jumlah_angsuran	= (isset($_REQUEST['jumlah_angsuran'])) ? to_number($_REQUEST['jumlah_angsuran']) : '';
$jumlah_denda		= (isset($_REQUEST['jumlah_denda'])) ? to_number($_REQUEST['jumlah_denda']) : '';
$jumlah_lain		= (isset($_REQUEST['jumlah_lain'])) ? to_number($_REQUEST['jumlah_lain']) : '';
$diposting			= (isset($_REQUEST['diposting'])) ? to_number($_REQUEST['diposting']) : '';
$tanggal			= (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$tgl_terima			= (isset($_REQUEST['tgl_terima'])) ? clean($_REQUEST['tgl_terima']) : '';
$via				= (isset($_REQUEST['via'])) ? clean($_REQUEST['via']) : '';
$catatan			= (isset($_REQUEST['catatan'])) ? clean($_REQUEST['catatan']) : '';
$subtotal			= (isset($_REQUEST['subtotal'])) ? to_number($_REQUEST['subtotal']) : '';
$ppn				= (isset($_REQUEST['ppn'])) ? to_number($_REQUEST['ppn']) : '';
$blok_baru			= (isset($_REQUEST['blok_baru'])) ? clean($_REQUEST['blok_baru']) : '';
$nomor_va			= (isset($_REQUEST['nomor_va'])) ? clean($_REQUEST['nomor_va']) : '';
$nomor_customer		= (isset($_REQUEST['nomor_customer'])) ? clean($_REQUEST['nomor_customer']) : '';
$max_tgl			= (isset($_REQUEST['max_tgl'])) ? clean($_REQUEST['max_tgl']) : '';
$jumlah_awal		= (isset($_REQUEST['jumlah_awal'])) ? clean($_REQUEST['jumlah_awal']) : '';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('C');
		ex_mod('C03');
		$conn = conn($sess_db);
		ex_conn($conn);
		$conn->begintrans(); 
		
		if ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('C03', 'U');
		
			ex_empty($jenis_pembayaran, 'Jenis Pembayaran harus diisi.');
			ex_empty($nama_pembayar, 'Telah Terima Dari harus diisi.');
			ex_empty($keterangan, 'Untuk Pembayaran harus diisi.');
			ex_empty($jumlah, 'Jumlah harus diisi.');
			ex_empty($diposting, 'Diposting harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			
			$user = $_SESSION['USER_ID']; 
			
			$query = "SELECT * FROM KWITANSI WHERE NAMA_PEMBAYAR = '$nama_pembayar' AND TANGGAL = CONVERT(DATETIME,'$tanggal',105) AND 
			NILAI = $jumlah AND KETERANGAN = '$keterangan' AND NILAI_DIPOSTING = $diposting AND TANGGAL_BAYAR = CONVERT(DATETIME,'$tgl_terima',105) AND
			BAYAR_VIA = '$via' AND CATATAN = '$catatan'";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			if ($status_otorisasi == 1)
			{
			
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
					PPN = $ppn, 
					NILAI_NETT = $subtotal,
					VER_COLLECTION_OFFICER = '$user', 
					VER_COLLECTION_TANGGAL = CONVERT(DATETIME,GETDATE(),105) 
				WHERE
					NOMOR_KWITANSI = '$id'
				";			
				
			}
			else if ($status_otorisasi == 2)
			{
				$query = "
				UPDATE KWITANSI_LAIN_LAIN 
				SET
					NAMA_PEMBAYAR = '$nama_pembayar', 
					TANGGAL = CONVERT(DATETIME,'$tanggal',105), 
					KODE_PEMBAYARAN = $jenis_pembayaran,
					NILAI = $jumlah, 
					KETERANGAN = '$keterangan', 
					NILAI_DIPOSTING = $diposting, 
					TANGGAL_BAYAR = CONVERT(DATETIME,'$tgl_terima',105), 
					BAYAR_VIA = '$via', 
					CATATAN = '$catatan',
					VER_COLLECTION_OFFICER = '$user', 
					VER_COLLECTION_TANGGAL = CONVERT(DATETIME,GETDATE(),105) 
				WHERE
					NOMOR_KWITANSI = '$id'
				";
			}
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
						
			$query = "update CS_VIRTUAL_ACCOUNT set SISA = SISA + '$jumlah_awal' - '$jumlah'  where NOMOR_VA = '$nomor_customer' AND TANGGAL = '$max_tgl'";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data Kuitansi berhasil diubah.';
			
			
		}
		elseif ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('C03', 'I');
			ex_empty($jenis_pembayaran, 'Jenis Pembayaran harus diisi.');
			ex_empty($nama_pembayar, 'Telah Terima Dari harus diisi.');
			ex_empty($keterangan, 'Untuk Pembayaran harus diisi.');
			if($kode_bayar == 4)
			{
				$jumlah = $jumlah_angsuran;
			}
			else if($kode_bayar == 9)
			{
				$jumlah = $jumlah_lain;
			}
			else
			{
				ex_empty($jumlah, 'Jumlah harus diisi.');
			}
			ex_empty($diposting, 'Diposting harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			
			$user = $_SESSION['USER_ID']; 	
			
			//if (($kode_bayar == 1) || ($kode_bayar == 2) || ($kode_bayar == 3) || ($kode_bayar == 4) || ($kode_bayar == 5) || ($kode_bayar == 6) ||
				//($kode_bayar == 10) || ($kode_bayar == 14) || ($kode_bayar == 15) || ($kode_bayar == 21) || ($kode_bayar == 22) || ($kode_bayar == 23)||
				//($kode_bayar == 24)||($kode_bayar == 25) || ($kode_bayar == 26) || ($kode_bayar == 27) || ($kode_bayar == 28)){
			if($status_otorisasi == 1){
				$query = "
				INSERT INTO KWITANSI (
					KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, KODE_BAYAR, NILAI, KETERANGAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, PPN, NILAI_NETT, VER_COLLECTION, VER_KEUANGAN, STATUS_KWT, VER_COLLECTION_OFFICER, VER_COLLECTION_TANGGAL 
				)
				VALUES(
					'$id', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal',105), $jenis_pembayaran, $jumlah, '$keterangan', $diposting, CONVERT(DATETIME,'$tgl_terima',105), '$via', '$catatan', $ppn, $subtotal, '1', '0', '0','$user', CONVERT(DATETIME,GETDATE(),105)
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
			}
			else{
				
				$query = "
				INSERT INTO KWITANSI_LAIN_LAIN (
					KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, NILAI, KETERANGAN, KODE_PEMBAYARAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, VER_COLLECTION, VER_KEUANGAN, STATUS_KWT, VER_COLLECTION_OFFICER, VER_COLLECTION_TANGGAL
				)
				VALUES(
					'$id', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal',105), $jumlah, '$keterangan', '$jenis_pembayaran', $diposting, CONVERT(DATETIME,'$tgl_terima',105), '$via', '$catatan', '1', '0', '0','$user', CONVERT(DATETIME,GETDATE(),105)
				)
				";
			}
			
			ex_false($conn->execute($query), $query);
			
			$query = "update CS_VIRTUAL_ACCOUNT set SISA = SISA - '$jumlah' where NOMOR_VA = '$nomor_customer' AND TANGGAL = '$max_tgl'";
			ex_false($conn->execute($query), $query);
			
			$query = "
			select TOP 1 * from TAGIHAN where kode_blok = '$id'
			AND STATUS_BAYAR = 0;
			";
			
			$obj = $conn->execute($query);
			$tgl_angsuran	= $obj->fields['TANGGAL'];
			
			$query = "update TAGIHAN set STATUS_BAYAR = '1' where KODE_BLOK = '$id' AND TANGGAL = '$tgl_angsuran'";
			ex_false($conn->execute($query), $query);
				
			$msg = 'Data Kuitansi telah ditambah.';
		}
		else if($act == 'Hapus') #Proses Hapus
		{			
			ex_ha('C03', 'D');
		
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				if ($status_otorisasi == 1)
				{
					$query = "	SELECT * FROM KWITANSI WHERE NOMOR_KWITANSI = '$id_del'";
					$obj = $conn->execute($query);
					$banyak	= $obj->fields['NILAI'];
					
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
				else if ($status_otorisasi == 2)
				{
					$query = "SELECT * FROM KWITANSI_LAIN_LAIN WHERE NOMOR_KWITANSI = '$id_del'";
					$obj = $conn->execute($query);
					$banyak	= $obj->fields['NILAI'];
					
					$query = "DELETE FROM KWITANSI_LAIN_LAIN WHERE NOMOR_KWITANSI = '$id_del'";
					if ($conn->Execute($query)) {
						$act[] = $id_del;
					} else {
						$error = TRUE;
					}
					
				}
							
				$query = "update CS_VIRTUAL_ACCOUNT set SISA = SISA + '$banyak' where NOMOR_VA = '$nomor_customer' AND TANGGAL = '$max_tgl'";
				ex_false($conn->execute($query), $query);
			
			}
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Kuitansi berhasil dihapus.';
		}
		elseif ($act == 'Pindah') # Proses Pindah
		{
			ex_ha('C03', 'U');
		
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
// die_app('C');
die_mod('C03');
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
	
	$nomor_va			= $obj->fields['NOMOR_VA'];
	$kode_blok 			= $obj->fields['KODE_BLOK'];
	$nama_pembeli 		= $obj->fields['NAMA_PEMBELI'];
	$alamat 			= $obj->fields['ALAMAT_RUMAH'];
	$tlp1 				= $obj->fields['TELP_RUMAH'];
	$tanggal_spp		= kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SPP']))));
	$no_identitas		= $obj->fields['NO_IDENTITAS'];	
	$npwp 				= $obj->fields['NPWP'];
	$luas_tanah 		= $obj->fields['LUAS_TANAH'];
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	$nomor_customer		= $obj->fields['NOMOR_CUSTOMER'];
	
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
	
	$query2 = "
		SELECT SUM(SISA) AS JML_SISA, MAX(TANGGAL) AS MAX_TGL FROM CS_VIRTUAL_ACCOUNT WHERE NOMOR_VA = '$nomor_customer'
	";
	$obj2 = $conn->execute($query2);
	
	$sisa				= $obj2->fields['JML_SISA'];
	$max_tgl			= $obj2->fields['MAX_TGL'];
		
}
if ($act == 'Ubah')
{
	if ($status_otorisasi == 1)
	{
		$query = "
		SELECT * FROM KWITANSI a
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI		
		LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
		LEFT JOIN JENIS_PEMBAYARAN e ON a.KODE_BAYAR = e.KODE_BAYAR
		WHERE NOMOR_KWITANSI = '$id'
		";
		
	}
	else if ($status_otorisasi == 2)
	{
		$query = "
		SELECT * FROM KWITANSI_LAIN_LAIN a
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI		
		LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
		LEFT JOIN JENIS_PEMBAYARAN e ON a.KODE_PEMBAYARAN = e.KODE_BAYAR
		WHERE NOMOR_KWITANSI = '$id'
		";
	}
	
	$obj = $conn->execute($query);
	
	$nomor			= $obj->fields['NOMOR_KWITANSI'];
	$nama_pembayar 	= $obj->fields['NAMA_PEMBAYAR'];
	$keterangan 	= $obj->fields['KETERANGAN'];
	//$jumlah 		= round($obj->fields['NILAI']);
	$jumlah 		= ($obj->fields['NILAI']);
	
	$diposting 		= $obj->fields['NILAI_DIPOSTING'];
	$tanggal		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));
	$tgl_terima		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_BAYAR'])));
	$via			= $obj->fields['BAYAR_VIA'];
	$catatan		= $obj->fields['CATATAN'];
	$biro 				= $obj->fields['VER_COLLECTION'];
	$keuangan			= $obj->fields['VER_KEUANGAN'];
	//$pindah 			= $obj->fields['STATUS_PINDAH_BLOK'];
	//$posting 			= $obj->fields['STATUS_POSTING'];
	
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
	
	$query = "
		SELECT  a.NOMOR_VA, MAX(a.TANGGAL) AS MAX_TGL FROM CS_VIRTUAL_ACCOUNT a join SPP b 
		on a.NOMOR_VA = b.NOMOR_CUSTOMER where b.KODE_BLOK ='$kode_blok' group by a.NOMOR_VA
	";
	$obj = $conn->execute($query);
	$nomor_customer		= $obj->fields['NOMOR_VA'];
	$max_tgl			= $obj->fields['MAX_TGL'];
	
	$tanggal_rencana	= f_tgl (date("Y-m-d"));
	$pecah_tanggal		= explode("-",$tanggal_rencana);
	$tgl 				= $pecah_tanggal[0];
	$bln 				= $pecah_tanggal[1];
	$thn 				= $pecah_tanggal[2];
	
	$bulan_depan	 	= $bln + 1;
	$tahun_depan		= $thn;
	if($bulan_depan > 12)
	{
		$bulan_depan 	= $bulan_depan % 12;
		$tahun_depan 	= $thn + 1; 
	}
	
	$query = "
	select count(*) as TOTAL_ANGSURAN from kwitansi where kode_blok = '$id' 
	";
	
	$obj = $conn->execute($query);
	$angsuran_ke	= 1 + $obj->fields['TOTAL_ANGSURAN'];
	
}
if ($act == 'Tambah')
{
	$query = "
		SELECT * FROM SPP a
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI		
		LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
		LEFT JOIN CS_VIRTUAL_ACCOUNT e ON a.NOMOR_CUSTOMER = e.NOMOR_VA
		WHERE a.KODE_BLOK = '$id'
	";
	$obj = $conn->execute($query);
	
	$nama_pembayar	= $obj->fields['NAMA_PEMBELI'];
	$kpr	 		= $obj->fields['JUMLAH_KPR'];
	$nomor			= '';	
	$keterangan 	= '';
	$jumlah 		= 0;
	$diposting 		= '';
	$tanggal		= f_tgl(date("Y-m-d"));
	$tgl_terima		= f_tgl(date("Y-m-d"));
	$via			= '';
	$catatan		= '';
	$biro 				= 0;
	$keuangan			= 0;
	$pindah 			= 0;
	$posting 			= 0;
	$jumlah_awal		= 0;
	
	$jenis_pembayaran	= 0;	
	$kode_bayar			= 0;	
	$kode_lain			= 5;	
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	$lokasi 			= $obj->fields['LOKASI'];
	$kode_blok 			= $obj->fields['KODE_BLOK'];	
	$tipe	 			= $obj->fields['TIPE_BANGUNAN'];
	$nomor_customer		= $obj->fields['NOMOR_CUSTOMER'];	
	
	$query = "
		SELECT MAX(TANGGAL) AS MAX_TGL FROM CS_VIRTUAL_ACCOUNT WHERE NOMOR_VA = '$nomor_customer'
	";
	$obj = $conn->execute($query);
	
	$max_tgl	= $obj->fields['MAX_TGL'];
	
	$tanggal_rencana	= f_tgl (date("Y-m-d"));
	$pecah_tanggal		= explode("-",$tanggal_rencana);
	$tgl 				= $pecah_tanggal[0];
	$bln 				= $pecah_tanggal[1];
	$thn 				= $pecah_tanggal[2];
	
	$bulan_depan	 	= $bln + 1;
	$tahun_depan		= $thn;
	if($bulan_depan > 12)
	{
		$bulan_depan 	= $bulan_depan % 12;
		$tahun_depan 	= $thn + 1; 
	}
		
	$query = "
	select count(*) as TOTAL_ANGSURAN from kwitansi where kode_blok = '$id' 
	";
	
	$obj = $conn->execute($query);
	$angsuran_ke	= 1 + $obj->fields['TOTAL_ANGSURAN'];
	
	
	$query = "
	select * from TAGIHAN_LAIN_LAIN where kode_bayar = '9' and kode_blok = '$id'
	AND STATUS_BAYAR = 0;
	";
	$obj = $conn->execute($query);
	$jumlah_denda	= to_money($obj->fields['NILAI']);
}
?>

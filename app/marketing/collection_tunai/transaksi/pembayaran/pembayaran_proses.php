<?php
require_once('../../../../../config/config.php');
$msg 	= '';
$error	= FALSE;
$act			= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id				= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$no_va				= (isset($_REQUEST['no_va'])) ? clean($_REQUEST['no_va']) : '';
$tanggal_bayar	= (isset($_REQUEST['tanggal_bayar'])) ? clean($_REQUEST['tanggal_bayar']) : '';
$catatan		= (isset($_REQUEST['catatan'])) ? clean($_REQUEST['catatan']) : '';
$kode_bank		= (isset($_REQUEST['kode_bank'])) ? clean($_REQUEST['kode_bank']) : '';
/*$pecah		= explode("-",f_tgl (date("Y-m-d")));*/
$pecah = explode('-', $tanggal_bayar);
$tgl		= $pecah[0];
$bln 		= $pecah[1];
$thn 		= $pecah[2];
//bulan depan
$next_bln	= $bln + 1;
$next_thn	= $thn;
if($bln > 12)
{
	$next_bln	= 1;
	$next_thn	= $thn + 1;
}
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
		
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('C03', 'I');
			//tanggal sekarang
			
			$user = $_SESSION['USER_ID']; 	
			
			$query 			= " SELECT *, a.KODE_BLOK as KODE FROM SPP a LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
							LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI
							LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
							WHERE a.KODE_BLOK = '$id' ";
			$obj 			= $conn->execute($query);
			$nama_pembayar	= $obj->fields['NAMA_PEMBELI'];
			$nomor_va		= $obj->fields['NOMOR_CUSTOMER'];
			$alamat_1		= $obj->fields['ALAMAT_RUMAH'];
			$npwp 			= $obj->fields['NPWP'];
			$jenis 			= $obj->fields['IDENTITAS'];
			$luas_bangunan 	= $obj->fields['LUAS_BANGUNAN']; 
			$lokasi 		= $obj->fields['LOKASI'];
			$kode_blok 		= $obj->fields['KODE'];	
			$tipe	 		= $obj->fields['TIPE_BANGUNAN'];
			
			if($luas_bangunan > 0)
				$bangunan = 'Tanah dan Bangunan';
			else
				$bangunan = 'Bangunan';
			
			$pecah_kode		= explode("/",$kode_blok);
			$blok_nomor		= $pecah_kode[1];
			$pecah_blok		= explode("-",$blok_nomor);
			$blok			= $pecah_blok[0];
			$nomor			= $pecah_blok[1];
			
			$query = "SELECT SUM(NILAI) AS TOTAL_BAYAR FROM PEMBAYARAN WHERE NOMOR_VA = '$nomor_va' AND STATUS = 0 AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn AND KODE_BANK = '$kode_bank'";
			$total_uang = floatval($conn->Execute($query)->fields['TOTAL_BAYAR']);
			$query_total = $query;

			$query = "	SELECT SUM(NILAI) AS TOTAL_TAGIHAN FROM TAGIHAN WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn AND STATUS_BAYAR = '0' ";
			$total_tagihan = floatval($conn->Execute($query)->fields['TOTAL_TAGIHAN']);

			$query = "	SELECT SUM(NILAI) AS TOTAL_TAGIHAN FROM TAGIHAN_LAIN_LAIN WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn AND STATUS_BAYAR = '0'";
			$total_tagihan += floatval($conn->Execute($query)->fields['TOTAL_TAGIHAN']);

			if($total_uang!=$total_tagihan){
				ex_false(false, 'Data Pembayaran Dan Tagihan Tidak Sesuai, Cek Kembali File Upload, Total uang : '.to_money($total_uang).',  Total tagihan : '.to_money($total_tagihan));
			}

			$query = "	UPDATE TAGIHAN SET STATUS_BAYAR = '2' WHERE KODE_BLOK = '$id' 
			AND KODE_BLOK = '$id' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn";
			
			$obj = $conn->execute($query);

			$query = "	UPDATE TAGIHAN_LAIN_LAIN SET STATUS_BAYAR = '2' WHERE KODE_BLOK = '$id' 
			AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn";
			
			$obj = $conn->execute($query);

			$query = "	SELECT * FROM TAGIHAN WHERE KODE_BLOK = '$id' 
			AND KODE_BLOK = '$id' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn";
			
			$obj = $conn->execute($query);

			while( ! $obj->EOF)
			{
				$jumlah			= $obj->fields['NILAI'];
				$subtotal		= round($jumlah * (100/110));
				$ppn			= round($jumlah - $subtotal);

				$query 			= "SELECT COUNT(*)+1 AS COUNT_RENCANA FROM RENCANA R JOIN KWITANSI K ON R.KODE_BLOK = K.KODE_BLOK WHERE R.KODE_BLOK = '$id' AND MONTH(K.TANGGAL) = MONTH(R.TANGGAL) AND YEAR(K.TANGGAL) = YEAR(R.TANGGAL) AND K.KODE_BAYAR = '4'";
				$count_rencana  = $conn->Execute($query)->fields['COUNT_RENCANA']; 
				$keterangan		= 'Pembayaran ANGSURAN ke '.$count_rencana.' atas pembelian '.$bangunan.' di '.$lokasi.' Blok '.$blok.' Nomor '.$nomor.' (TYPE '.$tipe.')'.' ANGSURAN : Rp. '.to_money($jumlah).',-'.' PPN : Rp. '.to_money($ppn).',-';
			
				$query2 = "
				INSERT INTO KWITANSI (
					KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, KODE_BAYAR, NILAI, KETERANGAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, PPN, NILAI_NETT, VER_COLLECTION, VER_KEUANGAN, STATUS_KWT, VER_COLLECTION_OFFICER, VER_COLLECTION_TANGGAL 
				)
				VALUES(
					'$id', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal_bayar',105), 4, $jumlah, '$keterangan', $jumlah, CONVERT(DATETIME,'$tanggal_bayar',105), '', '$catatan', $ppn, $subtotal, '1', '0', '0','$user', CONVERT(DATETIME,GETDATE(),105)
				)
				";
				ex_false($conn->execute($query2), $query2);

				
				$query2 = "
				INSERT INTO FAKTUR_PAJAK (
					KODE_BLOK, NO_KWITANSI, NAMA, ALAMAT_1, NPWP, JENIS, TGL_FAKTUR, KETERANGAN, NILAI, NILAI_DASAR_PENGENAAN, NILAI_PPN
				)
				VALUES(
					'$id', 'XXX', '$nama_pembayar', '$alamat_1', '$npwp', '$jenis', CONVERT(DATETIME,'$tanggal_bayar',105), '$keterangan', $jumlah, $subtotal, $ppn
				)
				";
				ex_false($conn->execute($query2), $query2);
	
				$obj->movenext();
			}
			
			
			$query = "SELECT *, a.KODE_BAYAR AS K_BAYAR FROM TAGIHAN_LAIN_LAIN a
			JOIN JENIS_PEMBAYARAN b
			ON a.KODE_BAYAR = b.KODE_BAYAR
			WHERE a.KODE_BLOK = '$id' AND MONTH(a.TANGGAL) = $bln AND YEAR(a.TANGGAL) = $thn";

			$obj = $conn->execute($query);

			while( ! $obj->EOF)
			{
			
				$jumlah				= $obj->fields['NILAI'];
				$jenis_pembayaran	= $obj->fields['K_BAYAR'];
				$pembayaran			= $obj->fields['JENIS_BAYAR'];
				$keterangan			= 'Pembayaran '.$pembayaran.' atas pembelian '.$bangunan.' di '.$lokasi.' Blok '.$blok.' Nomor '.$nomor.' (TYPE '.$tipe.')';
							
				$query2 = "
				INSERT INTO KWITANSI_LAIN_LAIN (
					KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, NILAI, KETERANGAN, KODE_PEMBAYARAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, VER_COLLECTION, VER_KEUANGAN, STATUS_KWT, VER_COLLECTION_OFFICER, VER_COLLECTION_TANGGAL
				)
				VALUES(
					'$id', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal_bayar',105), $jumlah, '$keterangan', '$jenis_pembayaran', $jumlah, CONVERT(DATETIME,'$tanggal_bayar',105), '', '$catatan', '1', '0', '0','$user', CONVERT(DATETIME,GETDATE(),105)
				)
				";
				ex_false($conn->execute($query2), $query2);
			
				$obj->movenext();
			}		
			
			$query = "update PEMBAYARAN set STATUS = '1' where NOMOR_VA = '$nomor_va' AND STATUS = '0' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn";
			ex_false($conn->execute($query), $query);
				
			$msg = 'Identifikasi Berhasil Dilakukan.';
		}
		else if($act=='Pengembalian'){
			$query 			= " SELECT *, a.KODE_BLOK as KODE FROM SPP a LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
							LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI
							LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
							WHERE a.KODE_BLOK = '$id' ";
			$obj 			= $conn->execute($query);
			$nama_pembayar	= $obj->fields['NAMA_PEMBELI'];
			$nomor_va		= $obj->fields['NOMOR_CUSTOMER'];
			$alamat_1		= $obj->fields['ALAMAT_RUMAH'];
			$npwp 			= $obj->fields['NPWP'];
			$jenis 			= $obj->fields['IDENTITAS'];
			$luas_bangunan 	= $obj->fields['LUAS_BANGUNAN']; 
			$lokasi 		= $obj->fields['LOKASI'];
			$kode_blok 		= $obj->fields['KODE'];	
			$tipe	 		= $obj->fields['TIPE_BANGUNAN'];

			$query = "SELECT SUM(NILAI) AS TOTAL_BAYAR FROM PEMBAYARAN WHERE NOMOR_VA = '$no_va' AND STATUS = 0 AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn AND KODE_BANK = '$kode_bank'";
			$total_uang = floatval($conn->Execute($query)->fields['TOTAL_BAYAR']);
			$jumlah				= $total_uang;
			$jenis_pembayaran	= '17';
			$pembayaran			= $obj->fields['JENIS_BAYAR'];

			$query = "SELECT NAMA_BANK FROM BANK WHERE KODE_BANK = '".$kode_bank."'";
			$bank = $conn->Execute($query)->fields['NAMA_BANK'];
			$keterangan			= 'Pengembalian uang atas pembayaran dengan nomor va : '.$no_va.', tanggal bayar : '.$tanggal_bayar.', di bank : '.$bank;
						
			
			$query2 = "
			INSERT INTO KWITANSI_LAIN_LAIN (
				KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, NILAI, KETERANGAN, KODE_PEMBAYARAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, VER_COLLECTION, VER_KEUANGAN, STATUS_KWT, VER_COLLECTION_OFFICER, VER_COLLECTION_TANGGAL
			)
			VALUES(
				'$id', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal_bayar',105), $jumlah, '$keterangan', '$jenis_pembayaran', $jumlah, CONVERT(DATETIME,'$tanggal_bayar',105), '', '$catatan', '1', '0', '0','$user', CONVERT(DATETIME,GETDATE(),105)
			)
			";

			ex_false($conn->execute($query2), $query2);
			
			$query = "update PEMBAYARAN set STATUS = '1' where NOMOR_VA = '$nomor_va' AND STATUS = '0' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Pembatalan Berhasil Dilakukan.';	
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
	$nomor_customer		= $obj->fields['NOMOR_CUSTOMER'];
	
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
	
	$sisa_pembayaran	= $total_tanah + $total_bangunan + $ppn_tanah + $ppn_bangunan;	
	$tanda_jadi 		= $obj->fields['TANDA_JADI'];	
	$tgl_jadi	 		= $obj->fields['TANGGAL_TANDA_JADI'];
	$jml_kpr	 		= $obj->fields['JUMLAH_KPR'];
	
	$query2 = "
		SELECT * FROM PEMBAYARAN a LEFT JOIN BANK b on a.KODE_BANK = b.KODE_BANK WHERE NOMOR_VA = '$nomor_customer' AND STATUS = '0' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn
	";
	$obj2 = $conn->execute($query2);
	
	$nomor_va			= $obj2->fields['NOMOR_VA'];
	$nilai				= $obj2->fields['NILAI'];
	$nama_bank			= $obj2->fields['NAMA_BANK'];
	$kode_bank			= $obj2->fields['KODE_BANK'];
	$tanggal			= f_tgl(date("Y-m-d"));
	
	$catatan 			= '';
}
?>

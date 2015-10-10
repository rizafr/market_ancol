<?php
require_once('../../../../../config/config.php');
$msg 	= '';
$error	= FALSE;
$act			= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id				= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$tanggal_bayar	= (isset($_REQUEST['tanggal_bayar'])) ? clean($_REQUEST['tanggal_bayar']) : '';

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
			
			$pecah		= explode("-",f_tgl (date("Y-m-d")));
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
			
			$user = $_SESSION['USER_ID']; 	
			
			$query 			= " SELECT *, a.KODE_BLOK as KODE FROM SPP a LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
							LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI
							LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
							WHERE a.KODE_BLOK = '$id' ";
			$obj 			= $conn->execute($query);
			$nama_pembayar	= $obj->fields['NAMA_PEMBELI'];
			$alamat_1		= $obj->fields['ALAMAT_RUMAH'];
			$npwp 			= $obj->fields['NPWP'];
			$jenis 			= $obj->fields['IDENTITAS'];
			$luas_bangunan 	= $obj->fields['LUAS_BANGUNAN']; 
			$lokasi 		= $obj->fields['LOKASI'];
			$kode_blok 		= $obj->fields['KODE'];	
			$tipe	 		= $obj->fields['TIPE_BANGUNAN'];
			
			$pecah_kode		= explode("/",$kode_blok);
			$blok_nomor		= $pecah_kode[1];
			$pecah_blok		= explode("-",$blok_nomor);
			$blok			= $pecah_blok[0];
			$nomor			= $pecah_blok[1];
			
			$query = "	SELECT * FROM TAGIHAN WHERE KODE_BLOK = '$id' 
			AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105) AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
			ORDER BY TANGGAL";
			
			$obj = $conn->execute($query);

			while( ! $obj->EOF)
			{
				$jumlah			= $obj->fields['NILAI'];
				$subtotal		= round($jumlah * (100/110));
				$ppn			= round($jumlah - $subtotal);
				$keterangan		= 'Pembayaran ANGSURAN atas pembelian '.$luas_bangunan.' di '.$lokasi.' Blok '.$blok.' Nomor '.$nomor.' (TYPE '.$tipe.')'.' ANGSURAN : Rp. '.to_money($jumlah).',-'.' PPN : Rp. '.to_money($ppn).',-';
			
				$query2 = "
				INSERT INTO KWITANSI (
					KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, NILAI, NILAI_DIPOSTING
				)
				VALUES(
					'$id', 'XXX', 'ANGGA', CONVERT(DATETIME,'$tanggal_bayar',105), 123456, 123456
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
			
			/*
			$query = "SELECT * FROM TAGIHAN_LAIN_LAIN a
			JOIN JENIS_PEMBAYARAN b
			ON a.KODE_BAYAR = b.KODE_BAYAR
			WHERE a.KODE_BLOK = '$id' AND a.TANGGAL = CONVERT(DATETIME,'01-$bln-$thn',105)";

			$obj = $conn->execute($query);

			while( ! $obj->EOF)
			{
				$jumlah			= $obj->fields['NILAI'];
				$keterangan		= 'Pembayaran ANGSURAN atas pembelian '.$luas_bangunan.' di '.$lokasi.' Blok '.$blok.' Nomor '.$nomor.' (TYPE '.$tipe.')';
							
				$query = "
				INSERT INTO KWITANSI_LAIN_LAIN (
					KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, NILAI, KETERANGAN, KODE_PEMBAYARAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, VER_COLLECTION, VER_KEUANGAN, STATUS_KWT, VER_COLLECTION_OFFICER, VER_COLLECTION_TANGGAL
				)
				VALUES(
					'$id', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal_bayar',105), $jumlah, '$keterangan', '$jenis_pembayaran', $jumlah, CONVERT(DATETIME,'$tanggal_bayar',105), '', '', '1', '0', '0','$user', CONVERT(DATETIME,GETDATE(),105)
				)
				";
				ex_false($conn->execute($query), $query);
			
				$obj->movenext();
			}
		
						
			$query = "
			select TOP 1 * from TAGIHAN where kode_blok = '$id'
			AND STATUS_BAYAR = 0;
			";
			
			$obj = $conn->execute($query);
			$tgl_angsuran	= $obj->fields['TANGGAL'];
			
			$query = "update TAGIHAN set STATUS_BAYAR = '1' where KODE_BLOK = '$id' AND TANGGAL = '$tgl_angsuran'";
			ex_false($conn->execute($query), $query);
			*/
				
			$msg = 'Data Kuitansi telah ditambah.';
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
	
	$sisa_pembayaran	= ($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan);	
	$tanda_jadi 		= $obj->fields['TANDA_JADI'];	
	$tgl_jadi	 		= $obj->fields['TANGGAL_TANDA_JADI'];
	$jml_kpr	 		= $obj->fields['JUMLAH_KPR'];
	
	$query2 = "
		SELECT * FROM PEMBAYARAN WHERE NOMOR_VA = '$nomor_customer' AND STATUS = '0'
	";
	$obj2 = $conn->execute($query2);
	
	$nomor_va			= $obj2->fields['NOMOR_VA'];
	$nilai				= $obj2->fields['NILAI'];
	$tanggal_bayar		= tgltgl(date("d-m-Y", strtotime($obj2->fields['TANGGAL'])));
	$tanggal			= f_tgl(date("Y-m-d"));
	
}
?>

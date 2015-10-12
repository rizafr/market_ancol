<?php
require_once('../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
//DATA SPP
$no_customer		= (isset($_REQUEST['no_customer'])) ? clean($_REQUEST['no_customer']) : '';
$tgl_spp			= (isset($_REQUEST['tgl_spp'])) ? clean($_REQUEST['tgl_spp']) : '';
$no_spp				= (isset($_REQUEST['no_spp'])) ? clean($_REQUEST['no_spp']) : '';
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
$jumlah_kpr			= (isset($_REQUEST['jumlah_kpr'])) ? to_number($_REQUEST['jumlah_kpr']) : '';
$agen				= (isset($_REQUEST['agen'])) ? clean($_REQUEST['agen']) : '';
$koordinator		= (isset($_REQUEST['koordinator'])) ? clean($_REQUEST['koordinator']) : '';
$tgl_akad			= (isset($_REQUEST['tgl_akad'])) ? clean($_REQUEST['tgl_akad']) : '';
$status_kompensasi	= (isset($_REQUEST['status_kompensasi'])) ? clean($_REQUEST['status_kompensasi']) : '';
$tanda_jadi			= (isset($_REQUEST['tanda_jadi'])) ? to_number($_REQUEST['tanda_jadi']) : '';
$status_spp			= (isset($_REQUEST['status_spp'])) ? clean($_REQUEST['status_spp']) : '';
$tgl_proses			= (isset($_REQUEST['tgl_proses'])) ? clean($_REQUEST['tgl_proses']) : '';
$tgl_tanda_jadi		= (isset($_REQUEST['tgl_tanda_jadi'])) ? clean($_REQUEST['tgl_tanda_jadi']) : '';
$redistribusi		= (isset($_REQUEST['redistribusi'])) ? clean($_REQUEST['redistribusi']) : '';
$tgl_redistribusi	= (isset($_REQUEST['tgl_redistribusi'])) ? clean($_REQUEST['tgl_redistribusi']) : '';
$keterangan			= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$kelengkapan		= (isset($_REQUEST['kelengkapan'])) ? clean($_REQUEST['kelengkapan']) : '';
$catatan_col		= (isset($_REQUEST['catatan_col'])) ? clean($_REQUEST['catatan_col']) : '';

//DATA RENCANA & REALISASI
$luas_tanah			= (isset($_REQUEST['luas_tanah'])) ? clean($_REQUEST['luas_tanah']) : '';
$luas_bangunan		= (isset($_REQUEST['luas_bangunan'])) ? clean($_REQUEST['luas_bangunan']) : '';
$tipe_bangunan		= (isset($_REQUEST['tipe_bangunan'])) ? clean($_REQUEST['tipe_bangunan']) : '';
$total_tanah		= (isset($_REQUEST['total_tanah'])) ? to_clean($_REQUEST['total_tanah']) : '';
$total_bangunan		= (isset($_REQUEST['total_bangunan'])) ? to_clean($_REQUEST['total_bangunan']) : '';
$nomor_kwitansi		= (isset($_REQUEST['nomor_kwitansi'])) ? clean($_REQUEST['nomor_kwitansi']) : '';


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
			/*
			ex_empty($nomor, 'No. Tanda Terima harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			ex_empty($kode_blok, 'Kode Blok harus diisi.');
			ex_empty($pembayaran, 'Pembayaran harus diisi.');
			ex_empty($nama_pembayar, 'Nama pembayar harus diisi.');
			ex_empty($bayar_secara, 'Pembayaran secara harus diisi.');
			ex_empty($jumlah, 'Jumlah diterima harus diisi.');
			
			$query = "SELECT * FROM KWITANSI_TANDA_TERIMA WHERE NOMOR_KWITANSI = '$nomor'";
			ex_found($conn->Execute($query)->recordcount(), "No. Tanda Terima tidak boleh sama.");
			
			$query = "
			INSERT INTO KWITANSI_TANDA_TERIMA (NOMOR_KWITANSI, KODE_BLOK, TANGGAL, NAMA_PEMBELI, NOMOR_TELEPON, ALAMAT_PEMBELI, KETERANGAN, JUMLAH_DITERIMA, KOORDINATOR, KASIR, BAYAR_UNTUK, BAYAR_SECARA, BANK_GIRO)
				VALUES ('$nomor', '$kode_blok', CONVERT(DATETIME,'$tanggal',105), '$nama_pembayar', '$no_tlp', '$alamat', '$keterangan', $jumlah, '$koordinator', '$penerima', '$pembayaran', '$bayar_secara', '$bank')
			";
			ex_false($conn->execute($query), $query);
			
			$query = "
			UPDATE CS_REGISTER_CUSTOMER_SERVICE SET NOMOR_KWITANSI_TTS = '$no'";			
			ex_false($conn->execute($query), $query);
			*/
			$msg = "Data SPP berhasil ditambahkan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			//ex_ha('', 'U');
			/*
			ex_empty($nomor, 'No. Tanda Terima harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			
			$query = "SELECT * FROM KWITANSI_TANDA_TERIMA WHERE KODE_BLOK = '$kode_blok' AND TANGGAL = CONVERT(DATETIME,'$tanggal',105) AND NAMA_PEMBELI = '$nama_pembayar' AND
			NOMOR_TELEPON = '$no_tlp' AND ALAMAT_PEMBELI = '$alamat' AND KETERANGAN = '$keterangan' AND JUMLAH_DITERIMA = $jumlah AND KOORDINATOR = '$koordinator' AND
			KASIR = '$penerima' AND BAYAR_UNTUK = '$pembayaran' AND BAYAR_SECARA = '$bayar_secara' AND BANK_GIRO = '$bank'
			";
			ex_found($conn->Execute($query)->recordcount(), "Tidak ada data yang berubah.");
			
			$query = "
			UPDATE KWITANSI_TANDA_TERIMA 
			SET
				KODE_BLOK = '$kode_blok', 
				TANGGAL = CONVERT(DATETIME,'$tanggal',105), 
				NAMA_PEMBELI = '$nama_pembayar', 
				NOMOR_TELEPON = '$no_tlp',
				ALAMAT_PEMBELI = '$alamat',
				KETERANGAN = '$keterangan',
				JUMLAH_DITERIMA = $jumlah, 
				KOORDINATOR = '$koordinator',
				KASIR = '$penerima',
				BAYAR_UNTUK = '$pembayaran',
				BAYAR_SECARA = '$bayar_secara',
				BANK_GIRO = '$bank'
			WHERE
				NOMOR_KWITANSI = '$id'
			";			
			ex_false($conn->execute($query), $query);
			*/
			$msg = 'Data SPP berhasil diubah.';
		}
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
	//$query = "SELECT * FROM SPP WHERE KODE_BLOK = '$id'";
	//$obj = $conn->execute($query);
	$query = "
	SELECT *
	FROM
		SPP a
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
		LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
		LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
		LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
	WHERE a.KODE_BLOK = '$id'";
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
	$kode_agen			= $obj->fields['KODE_AGEN'];
	$kode_koordinator	= $obj->fields['KODE_KOORDINATOR'];
	$npwp				= $obj->fields['NPWP'];
	$jenis_npwp			= $obj->fields['JENIS_NPWP'];
	$bank				= $obj->fields['KODE_BANK'];
	$jumlah_kpr			= $obj->fields['JUMLAH_KPR'];
	$tgl_akad			= tgltgl(f_tgl($obj->fields['TANGGAL_AKAD']));
	$status_kompensasi	= $obj->fields['STATUS_KOMPENSASI'];
	$tanda_jadi			= $obj->fields['TANDA_JADI'];
	$status_spp			= $obj->fields['STATUS_SPP'];
	$tgl_proses			= tgltgl(f_tgl($obj->fields['TANGGAL_PROSES']));
	$tgl_tanda_jadi		= tgltgl(f_tgl($obj->fields['TANGGAL_TANDA_JADI']));
	$redistribusi		= $obj->fields['SPP_REDISTRIBUSI'];
	$tgl_redistribusi	= tgltgl(f_tgl($obj->fields['SPP_REDISTRIBUSI_TANGGAL']));
	$kelengkapan		= $obj->fields['KELENGKAPAN'];	
	$keterangan			= $obj->fields['KETERANGAN'];
	$catatan_col		= $obj->fields['CATATAN_COL'];	
	
//DATA RENCANA & REALISASI	
	$luas_tanah			= $obj->fields['LUAS_TANAH'];
	$luas_bangunan		= $obj->fields['LUAS_BANGUNAN'];	
	$tipe_bangunan		= $obj->fields['TIPE_BANGUNAN'];
	
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
}
if ($act == 'Ubah')
{
	$query = "SELECT * FROM KWITANSI_TANDA_TERIMA WHERE NOMOR_KWITANSI LIKE '%$id%'";
	$obj = $conn->execute($query);
	
	$nomor			= $obj->fields['NOMOR_KWITANSI'];
	$tanggal		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));
	$kode_blok		= $obj->fields['KODE_BLOK'];
	$nama_pembayar	= $obj->fields['NAMA_PEMBELI'];
	$kode_bayar		= $obj->fields['BAYAR_UNTUK'];
	$no_tlp			= $obj->fields['NOMOR_TELEPON'];
	$alamat			= $obj->fields['ALAMAT_PEMBELI'];
	$bank			= $obj->fields['BANK_GIRO'];
	$keterangan		= $obj->fields['KETERANGAN'];
	$jumlah			= $obj->fields['JUMLAH_DITERIMA'];
	$koordinator	= $obj->fields['KOORDINATOR'];
	$penerima		= $obj->fields['KASIR'];
	$bayar_secara	= $obj->fields['BAYAR_SECARA'];
}
?>
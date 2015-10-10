<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$nomor					= (isset($_REQUEST['nomor'])) ? clean($_REQUEST['nomor']) : '';
$tanggal				= (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$harga_tanah_per_meter	= (isset($_REQUEST['harga_tanah'])) ? to_number($_REQUEST['harga_tanah']) : '';
$masa_bangun			= (isset($_REQUEST['pembangunan'])) ? clean($_REQUEST['pembangunan']) : '';
$prosen_p_hak			= (isset($_REQUEST['prosentase'])) ? clean($_REQUEST['prosentase']) : '';
$daya_listrik			= (isset($_REQUEST['daya_listrik'])) ? to_number($_REQUEST['daya_listrik']) : '';
$jenis_ppjb				= (isset($_REQUEST['jenis_ppjb'])) ? clean($_REQUEST['jenis_ppjb']) : '';
$addendum				= (isset($_REQUEST['addendum'])) ? clean($_REQUEST['addendum']) : '';
$kode_kelurahan			= (isset($_REQUEST['kelurahan'])) ? to_number($_REQUEST['kelurahan']) : '';
$kode_kecamatan			= (isset($_REQUEST['kecamatan'])) ? to_number($_REQUEST['kecamatan']) : '';
$catatan				= (isset($_REQUEST['catatan'])) ? clean($_REQUEST['catatan']) : '';
$tanggal_pinjam_pembeli	= (isset($_REQUEST['tgl1'])) ? clean($_REQUEST['tgl1']) : '';
$tanggal_tt_pembeli		= (isset($_REQUEST['tgl2'])) ? clean($_REQUEST['tgl2']) : '';
$tanggal_tt_pejabat		= (isset($_REQUEST['tgl3'])) ? clean($_REQUEST['tgl3']) : '';
$tanggal_penyerahan		= (isset($_REQUEST['tgl4'])) ? clean($_REQUEST['tgl4']) : '';
$nama_pembeli			= (isset($_REQUEST['nama_pembeli'])) ? clean($_REQUEST['nama_pembeli']) : '';
$nomor_arsip			= (isset($_REQUEST['no_arsip'])) ? clean($_REQUEST['no_arsip']) : '';
$status_cetak			= (isset($_REQUEST['tercetak'])) ? clean($_REQUEST['tercetak']) : '0';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('P');
		ex_mod('P06');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('P06', 'I');
			
			ex_empty($tanggal, 'Tanggal PPJB harus diisi.');
			ex_empty($jenis_ppjb, 'Jenis PPJB harus diisi.');
			ex_empty($kode_kelurahan, 'Kelurahan harus diisi.');
			ex_empty($kode_kecamatan, 'Kecamatan harus diisi.');
		
			$conn->begintrans();

			$query = "
			INSERT INTO CS_PPJB (KODE_BLOK, TANGGAL, HARGA_TANAH_PER_METER, 
			MASA_BANGUN, PROSEN_P_HAK, DAYA_LISTRIK, JENIS, ADDENDUM, KODE_KELURAHAN, KODE_KECAMATAN, CATATAN,
			TANGGAL_PINJAM_PEMBELI, TANGGAL_TT_PEMBELI, TANGGAL_TT_PEJABAT, TANGGAL_PENYERAHAN,
			STATUS_CETAK, NAMA_PEMBELI, NOMOR_ARSIP)
			
			VALUES ('$id', CONVERT(DATETIME,'$tanggal',105), $harga_tanah_per_meter, 
			'$masa_bangun', '$prosen_p_hak', '$daya_listrik', '$jenis_ppjb', '$addendum', $kode_kelurahan, $kode_kecamatan, '$catatan',
			CONVERT(DATETIME,'$tanggal_pinjam_pembeli',105), CONVERT(DATETIME,'$tanggal_tt_pembeli',105), CONVERT(DATETIME,'$tanggal_tt_pejabat',105), CONVERT(DATETIME,'$tanggal_penyerahan',105),
			'$status_cetak', '$nama_pembeli', '$nomor_arsip')
			";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data PPJB telah ditambah.';
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('P06', 'U');
			
			ex_empty($tanggal, 'Tanggal PPJB harus diisi.');
			ex_empty($jenis_ppjb, 'Jenis PPJB harus diisi.');
			ex_empty($kode_kelurahan, 'Kelurahan harus diisi.');
			ex_empty($kode_kecamatan, 'Kecamatan harus diisi.');
			
			$conn->begintrans();

			$query = "
			UPDATE CS_PPJB SET
			TANGGAL = CONVERT(DATETIME,'$tanggal',105), HARGA_TANAH_PER_METER = $harga_tanah_per_meter, 
			MASA_BANGUN = '$masa_bangun', PROSEN_P_HAK = '$prosen_p_hak', DAYA_LISTRIK = '$daya_listrik', JENIS = '$jenis_ppjb', ADDENDUM = '$addendum', 
			KODE_KELURAHAN = $kode_kelurahan, KODE_KECAMATAN = $kode_kecamatan, CATATAN = '$catatan',
			TANGGAL_PINJAM_PEMBELI = CONVERT(DATETIME,'$tanggal_pinjam_pembeli',105), TANGGAL_TT_PEMBELI = CONVERT(DATETIME,'$tanggal_tt_pembeli',105), TANGGAL_TT_PEJABAT = CONVERT(DATETIME,'$tanggal_tt_pejabat',105), TANGGAL_PENYERAHAN = CONVERT(DATETIME,'$tanggal_penyerahan',105),
			STATUS_CETAK = '$status_cetak', NOMOR_ARSIP = '$nomor_arsip'			
			WHERE KODE_BLOK = '$id'
			";			
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data PPJB berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			ex_ha('P06', 'D');
			
			$query = "DELETE FROM CS_PPJB WHERE KODE_BLOK = '$id'";
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data PPJB telah dihapus.';
		}
		elseif ($act == 'Ttd') # Proses Ttd
		{
			ex_ha('P06', 'U');
			
			$nama		= (isset($_REQUEST['nama'])) ? clean($_REQUEST['nama']) : '';
			$jabatan	= (isset($_REQUEST['jabatan'])) ? clean($_REQUEST['jabatan']) : '';
			
			$query = "
			UPDATE CS_PPJB SET
				NAMA_PENANDATANGAN = '$nama',
	 			JABATAN = '$jabatan'
			WHERE
				KODE_BLOK = '$id'
			";			
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data penandatangan PPJB telah disimpan.';
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
// die_app('P');
die_mod('P06');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Ubah')
{
	$query = "
	SELECT *, z.TANGGAL, z.MASA_BANGUN, z.DAYA_LISTRIK, z.KODE_KELURAHAN, z.KODE_KECAMATAN, z.TANGGAL_OTORISASI
	FROM
		CS_PPJB z
		JOIN SPP a ON z.KODE_BLOK = a.KODE_BLOK
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
		LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
		LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
		LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
		LEFT JOIN KELURAHAN g ON z.KODE_KELURAHAN = g.KODE_KELURAHAN
		LEFT JOIN KECAMATAN h ON z.KODE_KECAMATAN = h.KODE_KECAMATAN
		LEFT JOIN USER_APPLICATIONS i ON z.OFFICER_OTORISASI = i.USER_ID
	WHERE a.KODE_BLOK = '$id'";
	$obj = $conn->execute($query);
	
	//DATA PEMBELI
	$kode_blok 			= $obj->fields['KODE_BLOK'];
	$nama_pembeli 		= $obj->fields['NAMA_PEMBELI'];
	$no_kartu 			= $obj->fields['NOMOR_CUSTOMER'];
	$alamat 			= $obj->fields['ALAMAT_RUMAH'];
	$tlp1 				= $obj->fields['TELP_RUMAH'];
	$tlp2 				= $obj->fields['TELP_KANTOR'];
	$tlp3 				= $obj->fields['TELP_LAIN'];
	
	//DATA SPP
	$tanggal_spp		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP'])));
	$sistem_pembayaran 	= sistem_pembayaran($obj->fields['STATUS_KOMPENSASI']);
	$tipe_bangunan 		= $obj->fields['TIPE_BANGUNAN'];
	$luas_tanah 		= $obj->fields['LUAS_TANAH'];
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	
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
	$nilai_tanda_jadi	= to_money($obj->fields['TANDA_JADI']);
	$sisa_pembayaran	= to_money(($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan) - $obj->fields['TANDA_JADI']);
	
	//DATA PPJB
	$nomor 				= $obj->fields['NOMOR'];
	$tanggal			= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));
	$harga_tanah		= to_money($obj->fields['HARGA_TANAH_PER_METER']);
	$pembangunan1		= $obj->fields['MASA_BANGUN'];
	$pembangunan2		= pembangunan($obj->fields['MASA_BANGUN']);
	$prosentase1		= $obj->fields['PROSEN_P_HAK'];
	$prosentase2		= prosentase($obj->fields['PROSEN_P_HAK']);
	$daya_listrik		= $obj->fields['DAYA_LISTRIK'];
	$jenis_ppjb			= $obj->fields['JENIS'];
	$addendum			= $obj->fields['ADDENDUM'];
	$kode_kelurahan		= $obj->fields['KODE_KELURAHAN'];
	$nama_kelurahan		= $obj->fields['NAMA_KELURAHAN'];
	$kode_kecamatan		= $obj->fields['KODE_KECAMATAN'];
	$nama_kecamatan		= $obj->fields['NAMA_KECAMATAN'];
	$catatan			= $obj->fields['CATATAN'];
	
	//VERIFIKASI
	$tanggal_ver		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_OTORISASI'])));
	$oleh				= $obj->fields['LOGIN_ID'];
	
	//TANDA TANGAN DAN PENYERAHAN PPJB
	$tgl1				= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_PINJAM_PEMBELI'])));
	$tgl2				= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_TT_PEMBELI'])));
	$tgl3				= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_TT_PEJABAT'])));
	$tgl4				= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_PENYERAHAN'])));
	$status_cetak		= $obj->fields['STATUS_CETAK'];
	$nomor_arsip		= $obj->fields['NOMOR_ARSIP'];
	
	$query = "SELECT COUNT(*) AS JML FROM CS_PPJB WHERE KODE_BLOK = '$id'";
	$obj = $conn->execute($query);
	$jml = $obj->fields['JML'];
	
}

if ($act == 'Ubah')
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
	WHERE a.KODE_BLOK = '$id'";
	$obj = $conn->execute($query);
	
	//DATA PEMBELI
	$kode_blok 			= $obj->fields['KODE_BLOK'];
	$nama_pembeli 		= $obj->fields['NAMA_PEMBELI'];
	$no_kartu 			= $obj->fields['NOMOR_CUSTOMER'];
	$alamat 			= $obj->fields['ALAMAT_RUMAH'];
	$tlp1 				= $obj->fields['TELP_RUMAH'];
	$tlp2 				= $obj->fields['TELP_KANTOR'];
	$tlp3 				= $obj->fields['TELP_LAIN'];
	
	//DATA SPP
	$tanggal_spp		= date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP']));
	$sistem_pembayaran 	= sistem_pembayaran($obj->fields['STATUS_KOMPENSASI']);
	$tipe_bangunan 		= $obj->fields['TIPE_BANGUNAN'];
	$luas_tanah 		= $obj->fields['LUAS_TANAH'];
	$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
	
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
	$nilai_tanda_jadi	= to_money($obj->fields['TANDA_JADI']);
	$sisa_pembayaran	= to_money(($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan) - $obj->fields['TANDA_JADI']);	
} 
?>
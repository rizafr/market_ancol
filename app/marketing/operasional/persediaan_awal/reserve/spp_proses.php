<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act				= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id					= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$nm					= (isset($_REQUEST['nm'])) ? clean($_REQUEST['nm']) : '';
$alamat				= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';
$no_va				= (isset($_REQUEST['no_va'])) ? clean($_REQUEST['no_va']) : '';
$telepon			= (isset($_REQUEST['telepon'])) ? clean($_REQUEST['telepon']) : '';

$kode_blok			= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$no_customer		= (isset($_REQUEST['no_customer'])) ? clean($_REQUEST['no_customer']) : '';
$tgl_spp			= (isset($_REQUEST['tgl_spp'])) ? clean($_REQUEST['tgl_spp']) : '';
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
$jumlah_kpr			= (isset($_REQUEST['jumlah_kpr'])) ? to_number($_REQUEST['jumlah_kpr']) : '0';
$agen				= (isset($_REQUEST['agen'])) ? clean($_REQUEST['agen']) : '';
$koordinator		= (isset($_REQUEST['koordinator'])) ? clean($_REQUEST['koordinator']) : '';
$tgl_akad			= (isset($_REQUEST['tgl_akad'])) ? clean($_REQUEST['tgl_akad']) : '';
$status_kompensasi	= (isset($_REQUEST['status_kompensasi'])) ? clean($_REQUEST['status_kompensasi']) : '';
$tanda_jadi			= (isset($_REQUEST['tanda_jadi'])) ? to_number($_REQUEST['tanda_jadi']) : '0';
$status_spp			= (isset($_REQUEST['status_spp'])) ? clean($_REQUEST['status_spp']) : '';
$tgl_proses			= (isset($_REQUEST['tgl_proses'])) ? clean($_REQUEST['tgl_proses']) : '';
$tgl_tanda_jadi		= (isset($_REQUEST['tgl_tanda_jadi'])) ? clean($_REQUEST['tgl_tanda_jadi']) : '';
$redistribusi		= (isset($_REQUEST['redistribusi'])) ? clean($_REQUEST['redistribusi']) : '';
$tgl_redistribusi	= (isset($_REQUEST['tgl_redistribusi'])) ? clean($_REQUEST['tgl_redistribusi']) : '';
$keterangan			= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$otorisasi			= (isset($_REQUEST['otorisasi'])) ? clean($_REQUEST['otorisasi']) : '';
$no_npwp			= (isset($_REQUEST['no_npwp'])) ? clean($_REQUEST['no_npwp']) : '';
$no_ktp				= (isset($_REQUEST['no_ktp'])) ? clean($_REQUEST['no_ktp']) : '';

$r_nama_desa			= '';
$r_lokasi				= '';
$r_jenis_unit			= '';
$r_harga_tanah_sk		= '';
$r_faktor_strategis		= '';
$r_tipe_bangunan		= '';
$r_harga_bangunan_sk	= '';
$r_jenis_penjualan		= '';
$r_progres				= '';
$r_luas_tanah			= '';
$r_base_harga_tanah		= '';
$r_nilai_tambah			= '';
$r_nilai_kurang			= '';
$r_fs_harga_tanah		= '';
$r_disc_tanah			= '';
$r_disc_harga_tanah		= '';
$r_ppn_tanah			= '';
$r_ppn_harga_tanah		= '';
$r_harga_tanah			= '';
$r_luas_bangunan		= '';
$r_base_harga_bangunan	= '';
$r_disc_bangunan		= '';
$r_disc_harga_bangunan	= '';
$r_ppn_bangunan			= '';
$r_ppn_harga_bangunan	= '';
$r_harga_bangunan		= '';

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
		if ($act == 'Simpan') # Proses Tambah
		{
			//ex_ha('', 'I');
		
			ex_empty($alamat_rumah, 'Alamat rumah harus diisi.');
			ex_empty($identitas, 'Identitas harus diisi.');
			ex_empty($no_identitas, 'No identitas harus diisi.');
			ex_empty($npwp, 'No npwp harus diisi.');
			ex_empty($no_customer, 'No virtual account harus diisi.');
			ex_empty($koordinator, 'Koordinator harus diisi.');
			ex_empty($bank, 'Bank harus diisi.');
			ex_empty($tanda_jadi, 'Tanda jadi harus diisi.');

			
			$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM SPP WHERE KODE_BLOK = '$id'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "Kode blok \'$id\' telah terdaftar.");

			$query = "SELECT MAX(NOMOR_SPP) AS NOMOR_SPP FROM SPP";
			$obj = $conn->execute($query);
			$no_spp		= 1 + $obj->fields['NOMOR_SPP'];
			
			$query = "
			INSERT INTO SPP 
			(KODE_BLOK,
							 NOMOR_CUSTOMER,
							 NOMOR_SPP,
							 NAMA_PEMBELI,
							 ALAMAT_RUMAH,
							 ALAMAT_SURAT,
							 ALAMAT_NPWP,
							 ALAMAT_EMAIL,
							 IDENTITAS,
							 NO_IDENTITAS,
							 NPWP,
							 JENIS_NPWP,
							 TELP_RUMAH,
							 TELP_KANTOR,
							 TELP_LAIN,
							 KODE_BANK,
							 TANGGAL_SPP,
							 KODE_AGEN,
							 KODE_KOORDINATOR,
							 JUMLAH_KPR,
							 STATUS_KOMPENSASI,
							 TANGGAL_AKAD,
							 TANDA_JADI,
							 TANGGAL_TANDA_JADI,
							 STATUS_SPP,
							 TANGGAL_PROSES,
							 SPP_REDISTRIBUSI,
							 SPP_REDISTRIBUSI_TANGGAL,
							 KETERANGAN)
			VALUES('$id',
				   '$no_customer',
				   '$no_spp',
				   '$nama',
				   '$alamat_rumah',
				   '$alamat_surat',
				   '$alamat_npwp',
				   '$email',
				   '$identitas',
				   '$no_identitas',
				   '$npwp',
			       '$jenis_npwp',
				   '$tlp_rumah',
				   '$tlp_kantor',
				   '$tlp_lain',
				   '$bank',
				   CONVERT(DATETIME,'$tgl_spp',105),
				   '$agen',
				   '$koordinator',
				   '$jumlah_kpr',
				   '$status_kompensasi',
				   CONVERT(DATETIME,'$tgl_akad',105),
				   '$tanda_jadi',
				   CONVERT(DATETIME,'$tgl_tanda_jadi',105),
				   '$status_spp',
				   CONVERT(DATETIME,'$tgl_proses',105),
				   '$redistribusi',
				   CONVERT(DATETIME,'$tgl_redistribusi',105),
				   '$keterangan')";
			ex_false($conn->execute($query), $query);
			$query = "UPDATE SPP SET OTORISASI = '0' WHERE KODE_BLOK = '$id'";		
			ex_false($conn->execute($query), $query);
			
			$query = "DELETE FROM RESERVE WHERE KODE_BLOK = '$id'";		
			ex_false($conn->execute($query), $query);
			
			$query = "
			UPDATE STOK SET TERJUAL = '2' WHERE KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);

			$msg = 'Data SPP berhasil disimpan';
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
	$query = "SELECT * FROM SPP WHERE KODE_BLOK = '$id'";
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
	$npwp				= $obj->fields['NPWP'];
	$jenis_npwp			= $obj->fields['JENIS_NPWP'];
	$bank				= $obj->fields['KODE_BANK'];
	$jumlah_kpr			= $obj->fields['JUMLAH_KPR'];
	$agen				= $obj->fields['KODE_AGEN'];
	$koordinator		= $obj->fields['KODE_KOORDINATOR'];	
	$tgl_akad			= tgltgl(f_tgl($obj->fields['TANGGAL_AKAD']));
	$status_kompensasi	= $obj->fields['STATUS_KOMPENSASI'];
	$tanda_jadi			= $obj->fields['TANDA_JADI'];
	$status_spp			= $obj->fields['STATUS_SPP'];
	$tgl_proses			= tgltgl(f_tgl($obj->fields['TANGGAL_PROSES']));
	$tgl_tanda_jadi		= tgltgl(f_tgl($obj->fields['TANGGAL_TANDA_JADI']));
	$redistribusi		= $obj->fields['SPP_REDISTRIBUSI'];
	$tgl_redistribusi	= tgltgl(f_tgl($obj->fields['SPP_REDISTRIBUSI_TANGGAL']));
	$keterangan			= $obj->fields['KETERANGAN'];	



}
if ($act == 'Ubah')
{
	$obj = $conn->Execute("
	SELECT  
		s.KODE_BLOK,
		l.LOKASI,
		s.LUAS_BANGUNAN,
		ju.JENIS_UNIT,
		t.TIPE_BANGUNAN,
		hb.HARGA_CASH_KERAS,
		hb.CB36X,
		hb.CB48X,
		hb.KPA24X,
		hb.KPA36X,
		p.JENIS_PENJUALAN
	FROM 
		STOK s		
		LEFT JOIN HARGA_SK hb ON s.KODE_SK = hb.KODE_SK	AND s.KODE_BLOK = hb.KODE_BLOK
		LEFT JOIN LOKASI l ON s.KODE_LOKASI = l.KODE_LOKASI
		LEFT JOIN JENIS_UNIT ju ON s.KODE_UNIT = ju.KODE_UNIT
		LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
		LEFT JOIN JENIS_PENJUALAN p ON s.KODE_PENJUALAN = p.KODE_JENIS
	WHERE
		s.KODE_BLOK = '$id'");
	
	$r_kode_lokasi			= $obj->fields['KODE_LOKASI'];
	$r_kode_unit			= $obj->fields['KODE_UNIT'];
	$r_kode_tipe			= $obj->fields['KODE_TIPE'];
	$r_kode_sk				= $obj->fields['KODE_SK'];
	$r_kode_penjualan		= $obj->fields['KODE_PENJUALAN'];
	
	$r_lokasi				= $obj->fields['LOKASI'];
	$r_jenis_unit			= $obj->fields['JENIS_UNIT'];
	$r_tipe_bangunan		= $obj->fields['TIPE_BANGUNAN'];
	$harga_cash_keras		= $obj->fields['HARGA_CASH_KERAS'];
	$cb36x  				= $obj->fields['CB36X'];
	$cb48x  				= $obj->fields['CB48X'];
	$kpa24x  				= $obj->fields['KPA24X'];
	$kpa36x   				= $obj->fields['KPA36X'];
	$r_jenis_penjualan		= $obj->fields['JENIS_PENJUALAN'];	
	$r_luas_bangunan		= $obj->fields['LUAS_BANGUNAN'];


}
if ($act == 'Simpan')
{
	
	$query= "SELECT COUNT(*) as TOTAL from SPP where KODE_BLOK = '$id'";
	$obj = $conn->Execute($query);
	if($obj->fields['TOTAL']=='0'){
		$sudah = '0';
	}
	else{
		$sudah = '1';
	}
}
?>
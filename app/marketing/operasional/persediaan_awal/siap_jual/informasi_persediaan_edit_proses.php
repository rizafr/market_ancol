<?php
require_once('../../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = 'Ubah';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$kode_blok		= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$kode_desa		= (isset($_REQUEST['kode_desa'])) ? clean($_REQUEST['kode_desa']) : '';
$kode_lokasi	= (isset($_REQUEST['kode_lokasi'])) ? clean($_REQUEST['kode_lokasi']) : '';
$kode_unit		= (isset($_REQUEST['kode_unit'])) ? clean($_REQUEST['kode_unit']) : '';
$kode_sk_tanah	= (isset($_REQUEST['kode_sk_tanah'])) ? clean($_REQUEST['kode_sk_tanah']) : '';
$kode_faktor	= (isset($_REQUEST['kode_faktor'])) ? clean($_REQUEST['kode_faktor']) : '';
$kode_tipe		= (isset($_REQUEST['kode_tipe'])) ? clean($_REQUEST['kode_tipe']) : '';
$kode_sk_bangunan = (isset($_REQUEST['kode_sk_bangunan'])) ? clean($_REQUEST['kode_sk_bangunan']) : '';
$kode_penjualan	= (isset($_REQUEST['kode_penjualan'])) ? clean($_REQUEST['kode_penjualan']) : '';

$class					= (isset($_REQUEST['class'])) ? clean($_REQUEST['class']) : '';
$status_gambar_siteplan	= (isset($_REQUEST['status_gambar_siteplan'])) ? to_number($_REQUEST['status_gambar_siteplan']) : '0';
$status_gambar_lapangan	= (isset($_REQUEST['status_gambar_lapangan'])) ? to_number($_REQUEST['status_gambar_lapangan']) : '0';
$status_gambar_gs		= (isset($_REQUEST['status_gambar_gs'])) ? to_number($_REQUEST['status_gambar_gs']) : '0';
$program				= (isset($_REQUEST['program'])) ? to_number($_REQUEST['program']) : '0';

$luas_tanah			= (isset($_REQUEST['luas_tanah'])) ? to_decimal($_REQUEST['luas_tanah']) : '0';
$disc_tanah			= (isset($_REQUEST['disc_tanah'])) ? to_decimal($_REQUEST['disc_tanah'], 16) : '0';
$harga_disc_tanah	= (isset($_REQUEST['harga_disc_tanah'])) ? to_number($_REQUEST['harga_disc_tanah']) : '0';
$ppn_tanah			= (isset($_REQUEST['ppn_tanah'])) ? to_decimal($_REQUEST['ppn_tanah']) : '0';

$luas_bangunan	= (isset($_REQUEST['luas_bangunan'])) ? to_decimal($_REQUEST['luas_bangunan']) : '0';
$disc_bangunan	= (isset($_REQUEST['disc_bangunan'])) ? to_decimal($_REQUEST['disc_bangunan'], 16) : '0';
$ppn_bangunan	= (isset($_REQUEST['ppn_bangunan'])) ? to_decimal($_REQUEST['ppn_bangunan']) : '0';

$nama_desa			= '';
$lokasi				= '';
$jenis_unit			= '';
$harga_tanah_sk		= '';
$faktor_strategis	= '';
$tipe_bangunan		= '';
$harga_bangunan_sk	= '';
$jenis_penjualan	= '';

$tgl_bangunan		= '';
$tgl_selesai		= '';
$progress			= '';
	
$base_harga_tanah		= 0;
$nilai_tambah			= 0;
$nilai_kurang			= 0;
$fs_harga_tanah			= 0;
$disc_harga_tanah		= 0;
$ppn_harga_tanah		= 0;
$harga_tanah			= 0;

$base_harga_bangunan	= 0;
$fs_harga_bangunan		= 0;
$disc_harga_bangunan	= 0;
$ppn_harga_bangunan		= 0;
$harga_bangunan			= 0;
	
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		ex_mod('M28');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if  ($act == 'Ubah') # Proses Ubah
		{
			ex_ha('M28', 'I');
			
			ex_empty($kode_blok, 'Kode Blok harus diisi.');
			ex_empty($kode_desa, 'Desa harus diisi.');
			ex_empty($kode_lokasi, 'Lokasi harus diisi.');
			ex_empty($kode_unit, 'Jenis unit harus diisi.');
			ex_empty($kode_sk_tanah, 'SK tanah harus diisi.');
			ex_empty($kode_faktor, 'Faktor strategis harus diisi.');
			ex_empty($kode_tipe, 'Tipe harus diisi.');
			ex_empty($kode_sk_bangunan, 'SK bangunan harus diisi.');
			ex_empty($kode_penjualan, 'Jenis penjualan harus diisi.');
			ex_empty($class, 'Pilih class.');
			
					
			$query = "
			UPDATE STOK 
			SET KODE_BLOK = '$kode_blok', 
				KODE_UNIT = '$kode_unit', 
				KODE_DESA = '$kode_desa', 
				KODE_LOKASI = '$kode_lokasi', 
				KODE_SK_TANAH = '$kode_sk_tanah', 
				KODE_FAKTOR = '$kode_faktor', 
				KODE_TIPE = '$kode_tipe', 
				KODE_SK_BANGUNAN = '$kode_sk_bangunan', 
				KODE_PENJUALAN = '$kode_penjualan', 
				
				LUAS_TANAH = '$luas_tanah', 
				LUAS_BANGUNAN = '$luas_bangunan', 
				PPN_TANAH = '$ppn_tanah', 
				PPN_BANGUNAN = '$ppn_bangunan', 
				DISC_TANAH = '$disc_tanah', 
				DISC_BANGUNAN = '$disc_bangunan', 
				
				CLASS = '$class',
				PROGRAM = '$program',
				
				STATUS_GAMBAR_SITEPLAN = '$status_gambar_siteplan', 
				STATUS_GAMBAR_LAPANGAN = '$status_gambar_lapangan', 
				STATUS_GAMBAR_GS = '$status_gambar_gs'
			WHERE
				KODE_BLOK = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data stok berhasil diubah.';
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
	$json = array('act' => $act, 'error'=> 'error', 'msg' => $msg);
	echo json_encode($json);
	exit;
}

?>
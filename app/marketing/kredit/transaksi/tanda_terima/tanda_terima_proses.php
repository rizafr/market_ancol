<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act	= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$nomor			= (isset($_REQUEST['nomor'])) ? clean($_REQUEST['nomor']) : '';
$kode_blok		= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$tanggal		= (isset($_REQUEST['tanggal'])) ? clean($_REQUEST['tanggal']) : '';
$nama_pembayar	= (isset($_REQUEST['nama_pembayar'])) ? clean($_REQUEST['nama_pembayar']) : '';
$no_tlp			= (isset($_REQUEST['no_tlp'])) ? clean($_REQUEST['no_tlp']) : '';
$alamat			= (isset($_REQUEST['alamat'])) ? clean($_REQUEST['alamat']) : '';
$keterangan		= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$jumlah			= (isset($_REQUEST['jumlah'])) ? to_number($_REQUEST['jumlah']) : '';
$koordinator	= (isset($_REQUEST['koordinator'])) ? clean($_REQUEST['koordinator']) : '';
$penerima		= (isset($_REQUEST['penerima'])) ? clean($_REQUEST['penerima']) : '';
$pembayaran		= (isset($_REQUEST['pembayaran'])) ? clean($_REQUEST['pembayaran']) : '';
$bayar_secara	= (isset($_REQUEST['bayar_secara'])) ? clean($_REQUEST['bayar_secara']) : '';
$bank			= (isset($_REQUEST['bank'])) ? clean($_REQUEST['bank']) : '';
$no				= (isset($_REQUEST['no'])) ? to_number($_REQUEST['no']) : '';

$tmp = explode('#', $pembayaran);
$pembayaran = $tmp[0];
$kelompok 	= $tmp[1];

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		//ex_app('');
		ex_mod('K03');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			//ex_ha('', 'I');
			
			ex_empty($nomor, 'No. Tanda Terima harus diisi.');
			ex_empty($tanggal, 'Tanggal harus diisi.');
			ex_empty($kode_blok, 'Kode Blok harus diisi.');
			ex_empty($pembayaran, 'Pembayaran harus diisi.');
			ex_empty($nama_pembayar, 'Nama pembayar harus diisi.');
			ex_empty($bayar_secara, 'Pembayaran secara harus diisi.');
			ex_empty($jumlah, 'Jumlah diterima harus diisi.');
			
			$query = "
			INSERT INTO KWITANSI_TANDA_TERIMA (NOMOR_KWITANSI, KODE_BLOK, TANGGAL, NAMA_PEMBELI, NOMOR_TELEPON, ALAMAT_PEMBELI, KETERANGAN, JUMLAH_DITERIMA, KOORDINATOR, KASIR, BAYAR_UNTUK, BAYAR_SECARA, BANK_GIRO, STATUS_KWT)
				VALUES ('$nomor', '$kode_blok', CONVERT(DATETIME,'$tanggal',105), '$nama_pembayar', '$no_tlp', '$alamat', '$keterangan', $jumlah, '$koordinator', '$penerima', '$pembayaran', '$bayar_secara', '$bank', '0')
			";
			ex_false($conn->execute($query), $query);
			
			$query = "
			UPDATE CS_REGISTER_CUSTOMER_SERVICE SET NOMOR_KWITANSI_TTS = '$no'";			
			ex_false($conn->execute($query), $query);
			
			$msg = "Tanda terima berhasil disimpan.";
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			//ex_ha('', 'U');
			
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
			
			$msg = 'Data tanda terima berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Hapus
		{
			//ex_ha('', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				$query = "UPDATE KWITANSI_TANDA_TERIMA SET STATUS_KWT = '3' WHERE NOMOR_KWITANSI = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}		

			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data Kuitansi Tanda Terima berhasil dihapus.';
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
die_mod('K03');
$conn = conn($sess_db);
die_conn($conn);
	
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
if ($act == 'Tambah')
{
	$query = "SELECT RIGHT(replicate('0',4)+cast((SELECT CAST((NOMOR_KWITANSI_TTS + 1) AS VARCHAR(10)) FROM CS_REGISTER_CUSTOMER_SERVICE) as varchar(10)),4) AS NO, 
			(SELECT TOP 1 REG_KWITANSI_TTS FROM CS_REGISTER_CUSTOMER_SERVICE) AS REG, 
			(SELECT SUBSTRING(CONVERT(VARCHAR(10),(SELECT GETDATE()),10),1,2)) AS BULAN,
			(SELECT SUBSTRING(CONVERT(VARCHAR(10),(SELECT GETDATE()),10),7,2)) AS TAHUN,
			(SELECT GETDATE()) AS TANGGAL,
			(SELECT NOMOR_KWITANSI_TTS + 1 FROM CS_REGISTER_CUSTOMER_SERVICE) AS NOMOR_KWITANSI_TTS
			";
	$obj = $conn->execute($query);
	
	$no				= $obj->fields['NOMOR_KWITANSI_TTS'];
	$nomor			= $obj->fields['NO'].$obj->fields['REG'].'/'.$obj->fields['BULAN'].$obj->fields['TAHUN'];
	$tanggal		= f_tgl($obj->fields['TANGGAL']);
	$kode_blok		= '';
	$penerima		= $_SESSION['FULL_NAME'];
	$nama_pembayar	= '';
	$kode_bayar		= '';
	$no_tlp			= '';
	$alamat			= '';
	$bank			= '';
	$keterangan		= '';
	$jumlah			= '';
	$koordinator	= '';
	$bayar_secara	= '';
}
?>
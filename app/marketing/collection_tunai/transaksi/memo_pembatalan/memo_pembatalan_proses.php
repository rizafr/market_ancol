<?php
require_once('../../../../../config/config.php');

$msg 	= '';
$error	= FALSE;

$act				= (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id					= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$status_otorisasi	= (isset($_REQUEST['status_otorisasi'])) ? clean($_REQUEST['status_otorisasi']) : '';

$kode_blok		= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$nomor_memo		= (isset($_REQUEST['nomor_memo'])) ? clean($_REQUEST['nomor_memo']) : '';
$tanggal		= f_tgl (date("Y-m-d"));



if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		// ex_app('C');
		ex_mod('C06');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
		
		if ($act == 'Tambah') # Proses Tambah
		{
			ex_ha('C06', 'I');
			
			ex_empty($kode_blok, 'Kode blok harus diisi.');
			
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
				a.KODE_BLOK = '$kode_blok'";
			$obj = $conn->execute($query);
			
			
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
			
			$jumlah_harga		= ($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan);
			
			$query = "
			SELECT SUM(NILAI) AS TOTAL FROM KWITANSI WHERE KODE_BLOK = '$kode_blok'
			";
			$obj = $conn->execute($query);
			$total_pembayaran	= $obj->fields['TOTAL'];
			$total_pengembalian	= $jumlah_harga - $total_pembayaran;
			
			$query = "
				SELECT * FROM SPP WHERE KODE_BLOK = '$kode_blok'
			";
			$obj = $conn->execute($query);
			$tanggal_spp		= $obj->fields['TANGGAL_SPP'];
			$nama_pembeli		= $obj->fields['NAMA_PEMBELI'];

			$query = "
			INSERT INTO CS_MEMO_PEMBATALAN (
				KODE_BLOK, TANGGAL_SPP, NAMA_PEMBELI, TANGGAL_MEMO, NILAI_TRANSAKSI, TOTAL_PEMBAYARAN, TOTAL_PENGEMBALIAN, NOMOR_MEMO
			)
			VALUES(
				'$kode_blok', '$tanggal_spp', '$nama_pembeli', CONVERT(DATETIME,'$tanggal',105), $jumlah_harga, $total_pembayaran, $total_pengembalian, $nomor_memo
			)
			";
		
			ex_false($conn->execute($query), $query);
			
			$msg = 'Data memo pembatalan telah ditambah.';
		}
		else if($act == 'Hapus') #Proses Hapus
		{			
			ex_ha('C06', 'D');
		
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{		
				$query = "DELETE FROM CS_MEMO_PEMBATALAN WHERE KODE_BLOK = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
					
			}
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data memo pembatalan berhasil dihapus.';
		}
		
		else if($act == 'HapusMemo') #Proses Hapus
		{			
			ex_ha('C06', 'D');
		
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{		
				$query = "DELETE FROM CS_MEMO_PEMBATALAN WHERE NOMOR_MEMO = '$id_del'";
				if ($conn->Execute($query)) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
					
			}
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data memo pembatalan berhasil dihapus.';
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
die_mod('C06');
$conn = conn($sess_db);
die_conn($conn);
	
if ($act == 'Detail')
{
	$query = "
		SELECT NOMOR_MEMO, MAX(TANGGAL_MEMO) AS TGL_MEMO FROM
		CS_MEMO_PEMBATALAN
		WHERE NOMOR_MEMO = '$id'
		GROUP BY NOMOR_MEMO
	";
	$obj = $conn->execute($query);
	$nomor_memo			= $obj->fields['NOMOR_MEMO'];
	$tanggal_memo		= kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TGL_MEMO']))));	
}

if ($act == 'Ubah')
{
	
	$query = "
		SELECT * FROM CS_MEMO_PEMBATALAN WHERE NOMOR_MEMO = '$id'
	";
	$obj = $conn->execute($query);
	$nomor_memo			= $obj->fields['NOMOR_MEMO'];
	$tanggal_memo		= $obj->fields['TANGGAL_MEMO'];
}

if ($act == 'TambahMemo')
{
	$query = "
		SELECT MAX(NOMOR_MEMO) AS NO_MEMO FROM CS_MEMO_PEMBATALAN
	";
	$obj = $conn->execute($query);
	
	$kode_blok 		= '';
	$nomor_memo 	= 1 + $obj->fields['NO_MEMO'];
	$tanggal_memo 	= kontgl(tgltgl(date("d M Y")));
	$id 			= $nomor_memo;
}

if ($act == 'Tambah')
{
	$query = "
		SELECT MAX(NOMOR_MEMO) AS NO_MEMO FROM CS_MEMO_PEMBATALAN
	";
	$obj = $conn->execute($query);
	
	$kode_blok 		= '';
	$tanggal_memo 	= kontgl(tgltgl(date("d M Y")));
	$id 			= $nomor_memo;
}

?>
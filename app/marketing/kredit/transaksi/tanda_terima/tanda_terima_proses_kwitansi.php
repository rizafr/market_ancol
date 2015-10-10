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
$catatan		= (isset($_REQUEST['keterangan'])) ? clean($_REQUEST['keterangan']) : '';
$jumlah			= (isset($_REQUEST['jumlah'])) ? to_number($_REQUEST['jumlah']) : '';
$koordinator	= (isset($_REQUEST['koordinator'])) ? clean($_REQUEST['koordinator']) : '';
$penerima		= (isset($_REQUEST['penerima'])) ? clean($_REQUEST['penerima']) : '';
$pembayaran		= (isset($_REQUEST['pembayaran'])) ? clean($_REQUEST['pembayaran']) : '';
$bayar_secara	= (isset($_REQUEST['bayar_secara'])) ? clean($_REQUEST['bayar_secara']) : '';
$bank			= (isset($_REQUEST['bank'])) ? clean($_REQUEST['bank']) : '';
$no				= (isset($_REQUEST['no'])) ? to_number($_REQUEST['no']) : '';

$kata 			= '';
$tmp 			= explode('#', $pembayaran);
$pembayaran 	= $tmp[0];
$grup		 	= $tmp[1];

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
		$user = $_SESSION['USER_ID'];	
		if ($act == 'Tambah') # Proses Tambah
		{
			//ex_ha('', 'I');
			ex_false(false, 'Simpan data terlebih dahulu');
		}
		elseif ($act == 'Ubah') # Proses Ubah
		{
			//ex_ha('', 'U');

			$query 			= " SELECT *, a.KODE_BLOK as KODE FROM SPP a LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
							LEFT JOIN LOKASI c ON b.KODE_LOKASI = c.KODE_LOKASI
							LEFT JOIN TIPE d ON b.KODE_TIPE = d.KODE_TIPE
							WHERE a.KODE_BLOK = '$kode_blok' ";

			$obj 			= $conn->execute($query);
			$nama_pembeli	= $obj->fields['NAMA_PEMBELI'];
			$nomor_va		= $obj->fields['NOMOR_CUSTOMER'];
			$alamat_1		= $obj->fields['ALAMAT_RUMAH'];
			$npwp 			= $obj->fields['NPWP'];
			$jenis 			= $obj->fields['IDENTITAS'];
			$luas_bangunan 	= $obj->fields['LUAS_BANGUNAN']; 
			$lokasi 		= $obj->fields['LOKASI'];
			$kode_blok 		= $obj->fields['KODE'];	
			$tipe	 		= $obj->fields['TIPE_BANGUNAN'];
			$tanda_jadi     = intval($obj->fields['TANDA_JADI']);
			
			if($grup=='1'){
				
				$subtotal		= round($tanda_jadi * (100/110));
				$ppn			= round($tanda_jadi - $subtotal);
				
				if($pembayaran=='29'){					
					
					$query = "SELECT SUM(JUMLAH_DITERIMA) AS TOTAL_BAYAR FROM KWITANSI_TANDA_TERIMA WHERE KODE_BLOK = '$kode_blok' AND BAYAR_UNTUK = '29'";
					$tanda_jadi_terbayar = intval($conn->Execute($query)->fields['TOTAL_BAYAR']);
					$selisih = intval($tanda_jadi_terbayar) - intval($tanda_jadi);	

					if($selisih<0){
						ex_false(false,'Data gagal dibuat kwitansi, tanda jadi kurang sebesar Rp.'.to_money(-1*$selisih));
					}
					else if($selisih>0){
						ex_false(false,'Data gagal dibuat kwitansi, tanda jadi bayar lebih sebesar Rp.'.to_money($selisih));
					}
					$jumlah =  intval($tanda_jadi);
				}
				$keterangan		= 'Pembayaran TANDA JADI atas pembelian '.$bangunan.' di '.$lokasi.' Blok '.$blok.' Nomor '.$nomor.' (TYPE '.$tipe.')'.' nilai : Rp. '.to_money($jumlah).',-'.' PPN : Rp. '.to_money($ppn).',-';

				$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM KWITANSI_TANDA_TERIMA WHERE KODE_BLOK = '$kode_blok' AND STATUS_KWT = '1'";
				$total = $conn->Execute($query)->fields['TOTAL'];
				ex_found($total, "Kwitansi Sudah Ada");


				$query2 = "
					INSERT INTO KWITANSI (
						KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, KODE_BAYAR, NILAI, KETERANGAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, PPN, NILAI_NETT, VER_COLLECTION, VER_KEUANGAN, STATUS_KWT, VER_COLLECTION_OFFICER, VER_COLLECTION_TANGGAL, NOMOR_TANDA_TERIMA
					)
					VALUES(
						'$kode_blok', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal',105), '$pembayaran', $jumlah, '$keterangan', $jumlah, CONVERT(DATETIME,'$tanggal',105), '$bank', '$catatan', $ppn, $subtotal, '1', '0', '0','$user', CONVERT(DATETIME,GETDATE(),105), '$id'
					)
				";
				ex_false($conn->execute($query2), $query2);
					
				$query2 = "
					INSERT INTO FAKTUR_PAJAK (
						KODE_BLOK, NO_KWITANSI, NAMA, ALAMAT_1, NPWP, JENIS, TGL_FAKTUR, KETERANGAN, NILAI, NILAI_DASAR_PENGENAAN, NILAI_PPN
					)
					VALUES(
						'$kode_blok', 'XXX', '$nama_pembayar', '$alamat_1', '$npwp', '$jenis', CONVERT(DATETIME,'$tanggal',105), '$keterangan', $jumlah, $subtotal, $ppn
					)
				";
				ex_false($conn->execute($query2), $query2);	

				$query2 = "
					UPDATE KWITANSI_TANDA_TERIMA SET STATUS_KWT = '1' WHERE NOMOR_KWITANSI = '$id';
				";
				ex_false($conn->execute($query2), $query2);	
			}
			else if($grup=='2'){
				$total = $conn->Execute("SELECT COUNT(KODE_BLOK) AS TOTAL FROM KWITANSI_LAIN_LAIN WHERE NOMOR_KWITANSI = '$id' ")->fields['TOTAL'];
				ex_found($total, "Kwitansi Sudah Ada");
	
				$keterangan			= 'Pembayaran '.$pembayaran.' atas pembelian '.$bangunan.' di '.$lokasi.' Blok '.$blok.' Nomor '.$nomor.' (TYPE '.$tipe.')';
								
				$query2 = "
				INSERT INTO KWITANSI_LAIN_LAIN (
					KODE_BLOK, NOMOR_KWITANSI, NAMA_PEMBAYAR, TANGGAL, NILAI, KETERANGAN, KODE_PEMBAYARAN, NILAI_DIPOSTING, TANGGAL_BAYAR, BAYAR_VIA, CATATAN, VER_COLLECTION, VER_KEUANGAN, STATUS_KWT, VER_COLLECTION_OFFICER, VER_COLLECTION_TANGGAL, NOMOR_TANDA_TERIMA
				)
				VALUES(
					'$kode_blok', 'XXX', '$nama_pembayar', CONVERT(DATETIME,'$tanggal',105), $jumlah, '$keterangan', '$bayar_secara', $jumlah, CONVERT(DATETIME,'$tanggal',105), '', '$catatan', '1', '0', '0','$user', CONVERT(DATETIME,GETDATE(),105), '$id'
				)
				";
				
				ex_false($conn->execute($query2), $query2);
				
				$query2 = "
					UPDATE KWITANSI_TANDA_TERIMA SET STATUS_KWT = '1' WHERE NOMOR_KWITANSI = '$id';
				";
				ex_false($conn->execute($query2), $query2);	

				$obj->movenext();
			}
			$msg = 'Kwitansi Berhasil dibuat.'.$kata;
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
?>
<?php
require_once('../../../../../config/config.php');
require_once('../../../../../config/terbilang.php');
die_login();
// die_app('P');
die_mod('P06');
$conn = conn($sess_db);
die_conn($conn);

//Format Tanggal Berbahasa Indonesia 

	// Array Hari
	$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
	$hari = $array_hari[date('N')];

	//Format Tanggal 
	$tgl = date ('j');

	//Array Bulan 
	$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember'); 
	$bulan = $array_bulan[date('n')];
	 
	//Format Tahun 
	$tahun = date('Y');
	
$terbilang 	= new Terbilang;
$id			= (isset($_REQUEST['id'])) ? base64_decode(clean($_REQUEST['id'])) : '';

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
	
	//DATA SPP
	$tanggal_spp		= date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP']));
	$sistem_pembayaran 	= $obj->fields['STATUS_KOMPENSASI'];
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
	
	$total_harga 		= $total_tanah + $total_bangunan;
	$total_ppn			= $ppn_tanah + $ppn_bangunan;
	$nilai_tanda_jadi	= $obj->fields['TANDA_JADI'];
	
	$tanggal_tj			= date("d-M-Y", strtotime($obj->fields['TANGGAL_TANDA_JADI']));
	$bphtb				= ($total_harga - 60000000) * 0.05;
	$biaya_akte			= $total_harga * 0.01;
	$tanggal_akad		= date("d-M-Y", strtotime($obj->fields['TANGGAL_AKAD']));
	$jumlah_kpr 		= $obj->fields['JUMLAH_KPR'];

	if ($sistem_pembayaran == 1){
		$sisa_kpr			= ($total_tanah + $total_bangunan) + $total_ppn - $jumlah_kpr;
	}
	else {
		$sisa_kpr			= ($total_tanah + $total_bangunan) + $total_ppn;
	}
	$sisa_pembayaran	= $sisa_kpr - $nilai_tanda_jadi;
	
	//DATA PPJB
	$nomor 				= $obj->fields['NOMOR'];
	$tanggal			= tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL'])));
	$jabatan			= $obj->fields['JABATAN'];
	$nama_ttd			= $obj->fields['NAMA_PENANDATANGAN'];

$query = "
	SELECT * FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL IN
	(
	SELECT MIN(TANGGAL) FROM RENCANA WHERE KODE_BLOK = '$id'
	)
	";
	$obj = $conn->execute($query);
	$tanggal_um		= tgltgl(date("d-M-Y", strtotime($obj->fields['TANGGAL'])));
	$nilai_um		= to_money($obj->fields['NILAI']);
	
	$sisa_pembayaran2	= to_money($sisa_pembayaran - ($obj->fields['NILAI']));
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style type="text/css">
	@media print {
		@page {
			size: A4;
			margin: 0;
		}
		.newpage {
			page-break-before: always;
		}
	}
	
	}
	.line-sum {
		border:none;
		border-top:1px solid #000;
		margin:0;
		padding:0 0 2px 0;
	}
	
	.wrap {
		position: relative;
		margin: 0px 80px 0px 80px;
	}
	
	.left {
		float: left;
		width: 438px;
		padding:0 1px 0 1px;
		margin: 56px 0 0 0;
	}
	
	.mid {
		float: left;
		width: 24px;
	}
	
	#right {
		float: left;
		width: 334px;
		padding: 0 1px 0 1px;
		margin: 56px 0 0 0;
	}
	
	.clear { clear: both; }
	.text-left { text-align: left; }
	.text-right { text-align: right; }
	.text-center { text-align: center; }
	.va-top { vertical-align:top; }
	body{
	font-family : Tahoma;
	font-size: 14px;
	
	}
	h4{
	 text-align:center;
	}
</style>
</head>

<body>
<div class="wrap">
	<div class="clear"></div>
Lampiran : 1
<h4>

PERJANJIAN PENGIKATAN JUAL BELI<br>
TANAH<br>
BINTARO JAYA
</h4>
<div class="text-center">	
Nomor : <?php echo $nomor; ?><br>
</div>
<br>

Daftar perincian harga dan cara pembayaran atas pembelian <?php echo tanah_bangunan($luas_bangunan); ?> di Perumahan<br>
Bintaro Jaya Blok : <?php echo $kode_blok; ?> Tipe <?php echo $tipe_bangunan; ?><br>
<br>
<center> --- <b><?php echo $nama_pembeli; ?></b> --- </center>
<br>
	
	<table  class="t-nowrap t-data wm100">	
	<tr>
		<td width="300">Harga <?php echo tanah_bangunan($luas_bangunan); ?></td>
		<td width="70"></td><td width="130"></td>
		<td>:</td><td>Rp.</td>
		<td width="120" class="text-right"><?php echo to_money($tanah + $bangunan); ?></td>
	</tr>
	<tr>
		<td>Potongan Harga <?php echo tanah_bangunan($luas_bangunan); ?></td>
		<td></td><td></td>
		<td width="20">:</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($disc_tanah + $disc_bangunan); ?></td>
	</tr>
	<tr>
		<td>PPN <?php echo tanah_bangunan($luas_bangunan); ?></td>
		<td></td><td></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($total_ppn); ?></td>
	</tr>
	<tr>
		<td></td><td></td><td></td><td></td>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td></td>
		<td>Jumlah</td><td></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($total_tanah + $total_bangunan + $ppn_tanah + $ppn_bangunan); ?></td>
	</tr>	
<?php 	if ($sistem_pembayaran == 1){ ?>	
	<tr>
		<td>Pembayaran oleh KPR</td><td></td>
		<td><?php echo $tanggal_akad; ?></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($jumlah_kpr); ?></td>
	</tr>
	<tr>
		<td></td><td></td><td></td><td></td>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td></td>
		<td>SISA</td><td></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($sisa_kpr); ?></td>
	</tr>
<?php } ?>	
	<tr>
		<td>Pembayaran Tanda Jadi</td><td></td>
		<td><?php echo $tanggal_tj; ?></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($nilai_tanda_jadi); ?></td>
	</tr>
	<tr>
		<td></td><td></td><td></td><td></td>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td></td>
		<td>SISA</td><td></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($sisa_pembayaran); ?></td>
	</tr>
	<tr>
		<td>Pembayaran Uang Muka</td><td></td>
		<td><?php echo $tanggal_um; ?></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo $nilai_um; ?></td>
	</tr>
	<tr>
		<td></td><td></td><td></td><td></td>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td></td>
		<td>SISA</td><td></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo $sisa_pembayaran2; ?></td>
	</tr>
	<tr>
		<td></td><td></td><td></td><td></td>
		<td colspan="2"><hr><hr></td>
	</tr>
<?php 	if ($sisa_pembayaran2 <> 0){ ?>	
	<tr>
		<td colspan="6">Cara Pembayaran : Sisa pembayaran tersebut dilunasi pada tanggal :</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="5"><hr><hr></td>
	</tr>
	
<?php
$query = "
	SELECT * FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL NOT IN
	(
	SELECT MIN(TANGGAL) FROM RENCANA WHERE KODE_BLOK = '$id'
	)
	ORDER BY TANGGAL 
	";
$obj = $conn->selectlimit($query);
while( ! $obj->EOF)
	{
?>	
	<tr>
		<td></td><td></td>
		<td><?php echo tgltgl(date("d-M-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
		<td>=</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>
	</tr>
<?php
$obj->movenext();
	}	
?>	
	<tr>
		<td></td>
		<td colspan="5"><hr><hr></td>
	</tr>
<?php } ?>	
</table>
<br>
<?php
$query = "SELECT * FROM CS_PARAMETER_PPJB";
$obj = $conn->execute($query);
?>	

<div class="text-right">
<?php echo $obj->fields['KOTA']; ?>, <?php echo $tgl. " " . $bulan . " " . $tahun; ?>
</div>
<table>
	<tr>
		<td align="center" width="300">PIHAK KEDUA <br> PEMBELI</td>
		<td align="center" width="300">PIHAK PERTAMA <br> JAYA</td>
	</tr>
		<td><br><br><br><br></td>
		<td><br><br><br><br></td>
	</tr>
	</tr>
		<td align="center"><u><b><?php echo $nama_pembeli; ?></b></u></td>
		<td align="center"><u><b><?php echo $obj->fields['PEJABAT_PPJB']; ?></b></u></td>
	</tr>
	</tr>
		<td align="center"><u><b><?php echo $nama_ttd.'<br>'.$jabatan; ?></b></u></td>
		<td align="center"><b><?php echo $obj->fields['JABATAN_PPJB']; ?></b></td>
	</tr>
</table>

<br>

<table  class="t-nowrap t-data wm100">
	<tr>
		<td colspan="6"><u>Catatan</u> :</td>
	</tr>
	<tr>
		<td colspan="3">- Perkiraan Bea Perolehan Hak atas Tanah dan Bangunan (BPHTB) *</td>
		<td>:</td><td>Rp.</td>
		<td width="120" class="text-right"><?php echo to_money($bphtb); ?></td>
	</tr>
	<tr>
		<td colspan="3">- Perkiraan Biaya Akte Jual Beli dan Balik Nama Sertifikat</td>
		<td>:</td><td>Rp.</td>
		<td width="120" class="text-right"><?php echo to_money(ajb($biaya_akte)); ?></td>
	</tr>
		<tr>
		<td></td><td></td><td></td><td></td>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td width="300"></td>
		<td width="70">Jumlah</td><td width="130"></td>
		<td>:</td><td>Rp.</td>
		<td class="text-right"><?php echo to_money($bphtb+$biaya_akte); ?></td>
	</tr>
	<tr>
		<td></td><td></td><td></td><td></td>
		<td colspan="2"><hr><hr></td>
	</tr>
	<tr>
		<td colspan="3">*= Perkiraan AJB dan Bea Perolehan Hak atas Tanah dan Bangunan (BPHTB) yang berlaku adalah sesuai dengan peraturan pemerintah pada saat proses Akta Jual Beli </td>
	</tr>
</table>
</center>
</body>
</html>

<?php 
	
	
	$nama_file= "LAMPIRAN ".$nama_pembeli." (". $tgl . " " . $bulan . " " . $tahun .").doc";
	
	header("Content-Disposition: attachment; filename=\"" . basename($nama_file) . "\"");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');
	header('Pragma: public');
	flush();
	
		
	exit;
	close($conn); 
?>

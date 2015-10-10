<?php
require_once('../../../../../config/config.php');
require_once('../../../../../config/terbilang.php');
die_login();
// die_app('P');
die_mod('P06');
$conn = conn($sess_db);
die_conn($conn);

$terbilang = new Terbilang;
$id		= (isset($_REQUEST['id'])) ? base64_decode(clean($_REQUEST['id'])) : '';

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
	$no_spp				= $obj->fields['NOMOR_SPP'];
	$alamat_rumah		= $obj->fields['ALAMAT_RUMAH'];
	$alamat_surat		= $obj->fields['ALAMAT_SURAT'];
	
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
	$sisa_pemb			= ($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan) - $obj->fields['TANDA_JADI'];
	
	$tanggal_tj		= date("d-M-Y", strtotime($obj->fields['TANGGAL_TANDA_JADI']));
	
	//DATA PPJB
	//$nomor 				= $obj->fields['NOMOR'];
	$tanggal			= tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL'])));
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
	
	$jabatan			= $obj->fields['JABATAN'];
	$nama_ttd			= $obj->fields['NAMA_PENANDATANGAN'];
	
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


$query = "
	SELECT * FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL IN
	(
	SELECT MIN(TANGGAL) FROM RENCANA WHERE KODE_BLOK = '$id'
	)
	";
	$obj = $conn->execute($query);
	$tanggal_um		= tgltgl(date("d-M-Y", strtotime($obj->fields['TANGGAL'])));
	$nilai_um		= to_money($obj->fields['NILAI']);
	
	$sisa_pembayaran2	= to_money($sisa_pemb - ($obj->fields['NILAI']));
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style type="text/css">
	@media print {
		@page {
			size: 8.5in 4in portrait;
			margin: 0;
		}
		.newpage {
			page-break-before: always;
		}
	}
	
	.garis {
    border: 1px solid black;
    border-collapse: collapse;
	}
	
	.line-sum {
		border:none;
		border-top:1px solid #000;
		margin:0;
		padding:0 0 2px 0;
	}
	
	.wrap {
		font-family: "Times New Roman", Times, serif;
		position: relative;
		margin: 20px 80px 80px 80px;
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
	.va-top { vertical-align:top; }
</style>
</head>

<body onload="window.print()">
<div class="wrap">
	<div class="clear"></div>
	
<font size="1">


<table align="right">
<tr>
	<td colspan="2">PARAF</td>
</tr>
<tr>
	<td align="center" width="70">Pembeli</td>
	<td width="20"></td>
	<td align="center" width="70">Koordinator</td>
</tr>
<tr>
	<td class="garis" align="center"><br><br></td>
	<td width="20"></td>
	<td class="garis" align="center"><br><br></td>
</tr>
<tr>
	<td align="center">Manager</td>
	<td width="20"></td>
	<td align="center">Pimpinan</td>
</tr>
<tr>
	<td class="garis" align="center"><br><br></td>
	<td width="20"></td>
	<td class="garis" align="center"><br><br></td>
</tr>
</table>

<div class="clear"></div>
<div class="clear"></div>

<br>
<center>
<?php echo $no_spp; ?>
<br><br><br>
</center>

<table align="right">
<tr>
	<td><?php echo $nama_pembeli; ?></td>
</tr>
<tr>
	<td><?php echo $alamat_rumah; ?></td>
</tr>
<tr>
	<td><?php echo $alamat_surat; ?></td>
</tr>
<tr>
	<td><?php echo $tlp1 . ', ' . $tlp2 . ', ' . $tlp3; ?></td>
</tr>
<tr>
	<td><br><br><br><br></td>
</tr>
<tr>
	<td><?php echo $kode_blok; ?></td>
</tr>
<tr>
	<td><?php echo $tipe_bangunan; ?></td>
</tr>
</table>

</body>
</html>

<?php close($conn); ?>

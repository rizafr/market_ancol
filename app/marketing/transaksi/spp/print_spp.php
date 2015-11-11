<?php

require_once('../../../../config/config.php');
require_once('../../../../config/terbilang.php');
require_once('spp_proses.php');
$terbilang = new Terbilang;

	//Format Tanggal Berbahasa Indonesia 

	// Array Hari
$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
$hari = $array_hari[date('N')];

	//Format Tanggal 
$tanggal = date ('j');

	//Array Bulan 
$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember'); 
$bulan = $array_bulan[date('n')];

	//Format Tahun 
$tahun = date('Y');

$query 		= "SELECT *,s.NPWP AS CS_NPWP FROM SPP S
LEFT JOIN BANK B ON S.KODE_BANK = B.KODE_BANK
WHERE S.KODE_BLOK = '$id'";
$obj 		= $conn->execute($query);

$tgl_spp			= tgltgl(f_tgl($obj->fields['TANGGAL_SPP']));	
$no_customer		= $obj->fields['NOMOR_CUSTOMER'];
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
$npwp				= $obj->fields['CS_NPWP'];
$jenis_npwp			= $obj->fields['JENIS_NPWP'];
$kbank				= $obj->fields['KODE_BANK'];
$nospk				= $obj->fields['NOMOR_SPK_BANK'];
$plafonkpr			= $obj->fields['PLAFON_KPR_DISETUJUI'];
$retensi			= $obj->fields['NILAI_RETENSI'];
$jumlah_kpr			= $obj->fields['JUMLAH_KPR'];
$agen				= $obj->fields['KODE_AGEN'];
$koordinator		= $obj->fields['KODE_KOORDINATOR'];	
$tgl_akad			= tgltgl(f_tgl($obj->fields['TANGGAL_AKAD'])); 
$tgl_akad1			= tgltgl(f_tgl($obj->fields['TANGGAL_REALISASI_AKAD_KREDIT']));
$tgl_spk			= tgltgl(f_tgl($obj->fields['TANGGAL_SPK_BANK']));
$tgl_cair_kpr		= tgltgl(f_tgl($obj->fields['TANGGAL_CAIR_KPR'])); 
$tgl_retensi		= tgltgl(f_tgl($obj->fields['TANGGAL_RETENSI'])); 
$status_kompensasi	= $obj->fields['STATUS_KOMPENSASI'];
$tanda_jadi			= $obj->fields['TANDA_JADI'];
$status_spp			= $obj->fields['STATUS_SPP'];
$tgl_proses			= tgltgl(f_tgl($obj->fields['TANGGAL_PROSES']));
$tgl_tanda_jadi		= tgltgl(f_tgl($obj->fields['TANGGAL_TANDA_JADI']));
$redistribusi		= $obj->fields['SPP_REDISTRIBUSI'];
$tgl_redistribusi	= tgltgl(f_tgl($obj->fields['SPP_REDISTRIBUSI_TANGGAL']));
$keterangan			= $obj->fields['KETERANGAN'];	
$status_otorisasi	= $obj->fields['OTORISASI'];	
$nup				= $obj->fields['NUP'];	


$obj = $conn->Execute("
	SELECT  
	s.KODE_BLOK,
	s.LUAS_BANGUNAN,
	SPP.NAMA_PEMBELI,
	SPP.ALAMAT_RUMAH,
	SPP.ALAMAT_SURAT,
	SPP.NO_IDENTITAS,
	SPP.NPWP,
	SPP.TELP_RUMAH,
	SPP.TELP_KANTOR,
	SPP.TELP_LAIN,
	SPP.TANDA_JADI,
	SPP.ALAMAT_NPWP,
	SPP.ALAMAT_EMAIL,
	SPP.NO_FAX,
	SPP.NUP,
	SPP.TEMPAT_LAHIR,
	SPP.TANGGAL_LAHIR,
	SPP.HARGA_TOTAL,
	SPP.POLA_BAYAR,
	l.LOKASI,
	ju.JENIS_UNIT,
	t.TIPE_BANGUNAN,
	p.JENIS_PENJUALAN
	FROM 
	STOK s

	LEFT JOIN SPP spp ON s.KODE_BLOK = spp.KODE_BLOK
	LEFT JOIN LOKASI l ON s.KODE_LOKASI = l.KODE_LOKASI
	LEFT JOIN JENIS_UNIT ju ON s.KODE_UNIT = ju.KODE_UNIT
	LEFT JOIN FAKTOR f ON s.KODE_FAKTOR = f.KODE_FAKTOR
	LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
	LEFT JOIN JENIS_PENJUALAN p ON s.KODE_PENJUALAN = p.KODE_JENIS
	WHERE
	s.KODE_BLOK  = '$id'");

$r_kode_lokasi			= $obj->fields['KODE_LOKASI'];
$r_kode_unit			= $obj->fields['KODE_UNIT'];
$r_kode_sk_tanah		= $obj->fields['KODE_SK_TANAH'];
$r_kode_faktor			= $obj->fields['KODE_FAKTOR'];
$r_kode_tipe			= $obj->fields['KODE_TIPE'];
$r_kode_sk_bangunan		= $obj->fields['KODE_SK_BANGUNAN'];
$r_kode_penjualan		= $obj->fields['KODE_PENJUALAN'];

$r_lokasi				= $obj->fields['LOKASI'];
$r_jenis_unit			= $obj->fields['JENIS_UNIT'];
$harga_total    		= $obj->fields['HARGA_TOTAL'];
$r_tipe_bangunan		= $obj->fields['TIPE_BANGUNAN'];
$r_harga_bangunan_sk	= $obj->fields['HARGA_BANGUNAN_SK'];
$r_jenis_penjualan		= $obj->fields['JENIS_PENJUALAN'];



$luas_bangunan		= $obj->fields['LUAS_BANGUNAN'];


$query 		= "SELECT 
[NAMA_PT]
,[NAMA_DEP]
,[NAMA_PEJABAT]
,[NAMA_JABATAN]
,[PEJABAT_SPP]
,[JABATAN_SPP]
,[NAMA_SALES]
FROM [CS_PARAMETER_MARK]";
$obj 		= $conn->execute($query);

$nama_pejabat			= $obj->fields['NAMA_PEJABAT'];
$nama_jabatan			= $obj->fields['NAMA_JABATAN'];
$pejabat_spp			= $obj->fields['PEJABAT_SPP'];
$jabatan_spp			= $obj->fields['JABATAN_SPP'];
$nama_sales				= $obj->fields['NAMA_SALES'];

$query_rencana 		= "SELECT 
S.KODE_BLOK,
S.POLA_BAYAR,
S.TANDA_JADI,
S.HARGA_TOTAL,
R.TANGGAL,
R.NILAI,
J.JENIS_BAYAR
FROM RENCANA R,SPP S, JENIS_PEMBAYARAN J
WHERE S.KODE_BLOK= R.KODE_BLOK 
AND  J.KODE_BAYAR = R.KODE_BAYAR
AND R.KODE_BLOK = '$id'";
$obj 		= $conn->execute($query_rencana);

$tanggal_rencana		= tgltgl(f_tgl($obj->fields['TANGGAL']));	
$nilai 					= $obj->fields['NILAI'];
$pola_bayar 			= $obj->fields['POLA_BAYAR'];
$harga_total 			= $obj->fields['HARGA_TOTAL'];
$tanda_jadi 			= $obj->fields['TANDA_JADI'];
$jenis_bayar 			= $obj->fields['JENIS_BAYAR'];

$blok = explode("-", $id);
$no_unit = $blok[1];
$tower = substr($blok[0],-2,1); //A1
$lantai = substr($blok[0], 1);



$ttd = $conn->execute("
	SELECT *
	FROM 
	SPP_TANDA_TANGAN
	WHERE KODE_BLOK='$id';
");

$tt = $conn->execute("
	SELECT *
	FROM 
	SPP_TANDA_TANGAN
	WHERE KODE_BLOK='$id';
");

$t = $conn->execute("
	SELECT *
	FROM 
	SPP_TANDA_TANGAN
	WHERE KODE_BLOK='$id';
");

?>

<!DOCTYPE html>
<html>
<head>
	<title>Print_SP</title>
	<link type="text/css" href="../../../../config/css/sp.css" rel="stylesheet">
</head>
<body onload="window.print()">
	<div id = "spp">
		<table width="100%" border="0" class = 'pad'>
			<tr>
				<td width="70%">
					<div>
						<a id ="kata_spp">SURAT PERSETUJUAN PEMBELIAN (SPP)</a><br/>
						<a id ="kata_no">No.</a>
					</div>		
				</td>
				<td width="10%" align = "right"><img src="../../../../images/header_oseana.jpg" id = 'logo_seafront_gambar'/></td>
				<td width="1%" align="right"><img src="../../../../images/header_ancol.jpg" id = 'logo_ancol_gambar'/></td>
			</tr>
		</table>
		<table border="0" width="100%" class = 'pad'>
			<tr>
				<td class = "blok_persetujuan color">
					<a class="kata_persetujuan">LEMBAR PRSETUJUAN PEMBELIAN * WAJIB DIISI DENGAN HURUF BESAR DAN LENGKAP</a><br/>
					<a class="kata_persetujuan">SECTION A : YANG BERTANDA TANGAN DIBAWAH INI :</a>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" class="pad kata_profil">
			<tr>
				<td width="70%" class = "header_pembeli"><a>Nama Pembeli</a></td>
				<td width="10%"></td>
				<td width="20%"><a>No Urut Pembeli</a></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td class="garis_isian"><a><?= $nama ?></a></td>
				<td></td>
				<td class="garis_isian"><a><?= $nup ?></a></td>
			</tr>
		</table>

		<table width="70%" border="0" cellspacing="0" class="pad kata_profil">
			<tr>
				<td width="50%"><a>Alamat Sesuai KTP</a></td>
				<td width="20%"><a>No NPWP</a></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td class="garis_isian"><a><?= $alamat_rumah ?></a></td>
				<td class="garis_isian"><a><?= $npwp ?></a></td>
			</tr>
		</table>

		<table width="70%" border="0" cellspacing="0" class="pad kata_profil">
			<tr>
				<td width="23%"><a>Telepon(R)</a></td>
				<td width="22%"><a>(HP)</a></td>
				<td width="23%"><a>(K)</a></td>
				<td width="22%"><a>(F)</a></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td class="garis_isian"><a><?= $tlp_rumah ?></a></td>
				<td class="garis_isian"><a><?= $npwp ?> </a></td>
				<td class="garis_isian"><a><?= $npwp ?></a></td>
				<td class="garis_isian"><a><?= $npwp ?></a></td>
			</tr>
		</table>

		<table width="70%" border="0" cellspacing="0" class="pad kata_profil">
			<tr>
				<td width="25%"><a>No Identitas (KTP/SIM/KIMS)</a></td>
				<td width="25%"><a>Tanggal Lahir :(Tgl/Bln/Thn)</a></td>
				<td width="20%"><a>Email</a></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td class="garis_isian"><a><?= $no_identitas ?> </a></td>
				<td class="garis_isian"><a></a></td>
				<td class="garis_isian"><a><?= $email ?> </a></td>
			</tr>
		</table>

		<table width="70%" border="0" cellspacing="0" class="pad kata_profil">
			<tr>
				<td><a>Alamat Korespondensi</a></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td class="garis_isian"><a></a></td>
			</tr>
		</table>

		<table width="100%" border="0" cellspacing="0" class="pad kata_profil">
			<tr>
				<td class="garis_pembatas">&nbsp;</td>
			</tr>
			<tr>
				<td><a>Selanjutnya disebut "PEMBELI" dengan hal ini sepakat untuk membeli Satuan Unit dari "DEVELOPER" dan "PEMBELI" tunduk pada ketentuan-ketentuan pembelian sebagai tercantum di balik Surat Persetujuan Pembelian ini dan di halaman ini.</a></td>
			</tr>
		</table>

		<table border="0" width="100%" class = 'pad'>
			<tr>
				<td class = "blok_persetujuan color" >
					<a class="kata_persetujuan">SECTION B : SATUAN UNIT YANG DIBELI ("UNIT PEMBELIAN") PERHITUNGAN "HARGA JUAL" UNIT PEMBELIAN ADALAH SEBAGAI BERIKUT :</a>
				</td>
			</tr>
		</table>
		
		<table width="100%" border="0" cellspacing="0" class="pad kata_profil">
			<tr>
				<td width="15%"><a>1. Tower</a></td>
				<td width="2%"></td>
				<td width="18%" class="garis_isian"><a><?= $tower ?></a></td>
				<td width="2%"></td>
				<td width="20%"><a>Harga Sarusun Bersih</a></td>
				<td width="2%"></td>
				<td width="19%" class="garis_isian"><a><?php echo to_money($harga_total/1.1) ?></a></td>
				<td width="2%"></td>
				<td width="20%"><a>Cara Bayar</a></td>
			</tr>
			<tr>
				<td><a>2. No Unit</a></td>
				<td></td>
				<td class="garis_isian"><a><?= $no_unit ?></a></td>
				<td></td>
				<td><a>PPN 10%</a></td>
				<td></td>
				<td class="garis_isian"><a><?php echo to_money($harga_total-($harga_total/1.1)) ?></a></td>
				<td></td>
				<td class="garis_isian"><a><?= $keterangan ?></a></td>
			</tr>
			<tr>
				<td>3. Lantai</td>
				<td></td>
				<td class="garis_isian"><a><?= $lantai ?></a></td>
				<td></td>
				<td><a>Jumlah yang harus dibayarkan</a></td>
				<td></td>
				<td class="garis_isian"><a><?= to_money($harga_total) ?></a></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td><a>4. Luas Unit Semigross</a></td>
				<td></td>
				<td class="garis_isian"><a><?= $luas_bangunan ?></a></td>
				<td></td>
				<td><a>Terbilang</a></td>
				<td></td>
				<td class="garis_isian"><i><?php echo $terbilang->eja($harga_total)?></i></td>
				<td></td>
				<td></td>
			</tr>
		</table>

		<table border="0" width="100%" class = 'pad'>
			<tr>
				<td class = "blok_persetujuan color">
					<a class="kata_persetujuan">SECTION C : CARA PEMBAYARAN UNIT PEMBELIAN & SELANJUTNYA PEMBELI  BERJANJI MENYELESAIKAN PEMBAYARAN</a>
				</td>
			</tr>
		</table>
		<table border="0" width="100%" class="pad kata_profil perjanjian" cellspacing="0">
			<tr>
				<td valign="top">1. </td>
				<td>Selanjutnya saya menyatakan bahwa uang sebesar Rp. <?= to_money($tanda_jadi) ?> (<?php echo $terbilang->eja($tanda_jadi)?>) menjadi booking fee untuk pembelian unit diatas dan menjadi NON REFUNDABLE</td>
			</tr>
			<tr>
				<td valign="top">2. </td>
				<td>Apabila Booking Fee tersebut belum diterima manajemen selambat-lambatnya pada Rabu Juni 2015, Surat Persetujuan Pembelian ini akan BATAL dengan sendirinya dan unit yang dipilihakan dijual kembali. 
				</td>
			</tr>
			<tr>
				<td valign="top">3. </td>
				<td>Pembayaran untuk Booking Fee, DP (Down Payment), Cicilan, Pelunasan, Denda dan semua biaya lainnya dapat dibayar kan ke : 
				</td>
			</tr>
		</table>

		<table border="0" class="pad kata_profil perjanjian" cellspacing="0">
			<tr>
				<td>VIRTUAL ACCOUNT BCA  : 01686888 </td>
				<td> [No.Lantai]</td>
				<td> [No Unit]</td>
			</tr>
			<tr>
				<td></td>
				<td align="center">2 Digit</td>
				<td align="center">2 Digit</td>
			</tr>
			<tr>
				<td colspan="3">Jika ingin melakukan pembayaran untuk Unit 1 di Lantai 1</td>
			</tr>
			<tr>
				<td colspan="3">Contoh : 016868880101</td>
			</tr>
			<tr>
				<td colspan="3">Semua pembayaran hanya dapat dilakukan secara transfer ke rekening diatas</td>
			</tr>
		</table>

		<table border="0" width="100%" class="pad kata_profil perjanjian" cellspacing="0">
			<tr>
				<td valign="top">4. </td>
				<td>Serah terima unit : </td>
			</tr>
			<tr>
				<td valign="top">5. </td>
				<td>Dengan ditandatangani oleh Pembeli, maka Pembeli menyatakan sudah membaca, mengetahui, mengerti dan menyetujui seluruh ketentuan dihalaman ini dan halaman belakang berikut semua lampirannya.</td>
			</tr>
		</table>
		<br/>
		<table cellspacing="0" width="100%" border="0" class="kata_profil pad ttd">
			<tr>
				<td width="25%" class="border_table"><a>Pembeli</a></td>
				<?php
		
				while( ! $ttd->EOF)
				{	

					$jabatan = $ttd->fields['JABATAN'];
					
					echo "<td width='15%' class='border_table' ><a>$jabatan</a></td>";
					$ttd->movenext();
				}
		
				?>
				<td width="15%" class="border_table" ><a>Sales</a></td>
				<td width="30%" class="border_table" colspan="2"><a>PT. PEMBANGUNAN JAYA ANCOL TBK</a></td>
			</tr>
			<tr>
				<td height="50" class="border_table"></td>
				<?php
		
				while( ! $tt->EOF)
				{	

					echo "<td class='border_table'></td>";
					$tt->movenext();
				}
				
				?>
				<td class="border_table"></td>
				<td class="border_table"></td>
				<td class="border_table"></td>
			</tr>
			<tr>
				<td class="border_table"><?= $nama?></td>
				<?php 
				while( ! $t->EOF)
				{	

					$nama_ttd = $t->fields['NAMA'];
					$jabatan = $t->fields['JABATAN'];
					
					echo "<td class='border_table' >&nbsp;<br/>&nbsp;<br/>$nama_ttd<br /> <a>$jabatan</a></td>";
					$t->movenext();
				}

				?>
				<td class="border_table">&nbsp;<br/>&nbsp;<br/><?= $nama_sales ?> <br /> Sales / Agent</td>
				<td class="border_table">&nbsp;<br/>&nbsp;<br/><?= $nama_pejabat ?><br/><?= $nama_jabatan?></td>
				<td class="border_table">&nbsp;<br/><?= $pejabat_spp ?><br/><?= $jabatan_spp?></td>
			</tr>
		</table>
		<table width="100%" cellspacing="0" class="kata_profil pad">
			<tr>
				<td><a>PERHATIAN :</a></td>
			</tr>
			<tr>
				<td><a>Seluruh Informasi Pemesanan di Surat Pemesanan Unit wajib diisi dengan lengkap, jelas dan benar jika tidak, maka surat pemesanan tidak dapat diproses lebih lanjut dan dikembalikan. Apabila ada kesalahan data diatas mohon segera sampaikan kepada</a></td>
			</tr>
		</table>
		<br/>
		<div class = 'pad kata_footer'>
			<a class = 'kata_footer'>PT. PEMBANGUNAN JAYA ANCOL, TBK</a><br/>
			<a class='kata_footer'>DEPARTEMEN PROPERTI 1</a><br/>
			<a class = 'kata_footer'>Jaya Ancol Seafront</a><br/>
			<a class='kata_footer'>Marina Coast Boulevard Kav. C1-H</a><br/>
			<a class='kata_footer'>Ancol Barat - Jakarta Utara 14430</a><br/>
		</div>
		<br/>
	</div>
	<br/>
	<div id="table">
		<div class = "blok_persetujuan pad color">
			<a class="kata_persetujuan">Rincian Pembayaran</a>
		</div>
		<a class = "keterangan pad_kpr"> 

		</a>
	</table>
	Pembayaran : <?= $pola_bayar ?><br />
	<table class="kata_profil pad_kpr ttd" id = 'table_kpr' width="55%" cellspacing="0" >
		<tr>
			<td width="15%" class="border_table">Keterangan</td>
			<td width="15%" class="border_table">Tanggal JT</td>
			<td width="15%" class="border_table">Rupiah</td>
			<td width="10%" class="kata_profil">Catatan :</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td class="border_table">TANDA JADI</td>
			<td class="border_table"><?php echo date("d-M-Y", strtotime($tgl_tanda_jadi)); ?></td>
			<td class="border_table text-right"><?php echo to_money($tanda_jadi);  ?></td>
		</tr>

		<?php

		$i=1;
		$total = 0;
		while( ! $obj->EOF)
		{
			$nilai = $obj->fields['NILAI'];
			$total+= $nilai;
			
			?>
			<tr>

				<td class="border_table"><?php echo $obj->fields['JENIS_BAYAR'];  ?> <?php echo  $i++; ?></td>
				<td class="border_table"><?php echo date("d-M-Y", strtotime($obj->fields['TANGGAL'])); ?></td>
				<td class="border_table text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
			</tr>
			<?php
			$obj->movenext();
		}

		?>
		<tr>
			<td class="border_table">TOTAL</td>
			<td class="border_table"></td>
			<td class="border_table"><?php echo to_money($total+$tanda_jadi);?></td>

		</tr>
	</tr>

	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</div>
<div id= "halaman_belakang" class="kata_profil">
	<div class = "blok_persetujuan pad color">
		<a class="kata_persetujuan">Surat Perjanjian</a>
	</div>
	<div id = 'kiri'>
		<ol>
			<li>Pembayaran harga Satuan Rumah susun diatas dibayarkan melalui rekening sebagai berikut: 
				<table>
					<tr>
						<td>VIRTUAL ACCOUNT BCA  : 01686888</td>
						<td>[No.Lantai]</td>
						<td>[No Unit]</td>
					</tr>
					<tr>
						<td></td>
						<td>2 Digit</td>
						<td>2 Digit</td>
					</tr>
				</table>
				<a>Jika ingin melakukan pembayaran untuk Unit 1 di Lantai 1</a><br/>
				<a>Contoh : 016868800101</a><br/><br/>
				<a>Atau rekening bank lain yang akan diberitahukan kemudian melalui surat tertulis oleh PT. PEMBANGUNAN JAYA ANCOL TBK. (PJA).</a>
			</li>
			<br/>
			<li>Pembayaran dengan cek dan giro baru berlaku sah apabila dana telah efektif diterima oleh rekening PJA dan PJA telah menerima tanda bukti transfer dari PEMBELI dan telah menerbitkan kuitansi yang sah atas penerimaan uang atas nama PEMBELI yang tercantum dalam Surat Persetujuan Pembelian ini. Pembayaran ke rekening PJA dengan menyebutkan Nomor Unit satuan rumah susun.<br/>Segala pembayaran tunai atau ke nomor rekening lain yang bukan atas nama PJA adalah tidak diakui dan tidak berharga. Oleh karena itu segala akibat dari pembayaran yang dilakukan secara tunai atau dilakukan ke nomor rekening yang bukan atas nama PJA dan/atau pembayaran yang tidak menyebutkan nama PEMBELI dan Nomor Unit, dinyatakan tidak berlaku dan tidak mengikat terhadap PJA serta menjadi tanggungan PEMBELI sepenuhnya.
			</li>
			<br/>
			<li>Setiap keterlambatan pembayaran atas butir 1 di atas, maka atas tiap-tiap hari keterlambatan, PEMBELI setuju untuk dikenakan denda 1‰ (satu permil) per hari dari jumlah kewajiban yang harus dibayar, denda mana akan dihitung dari setiap pembayaran yang telah jatuh tempo hingga denda maksimal mencapai 3% (tiga persen). Dalam hal keterlambatan pembayaran telah mencapai denda maksimal dan PEMBELI belum melakukan kewajiban pembayaran berikut denda-dendanya, maka Surat Persetujuan Pembelian ini menjadi batal demi hukum.
			</li>
			<br/>
			<li>PEMBELI bersedia dan mengikatkan diri kepada PJA untuk menandatangani Perjanjian Pengikatan Jual Beli (PPJB) Satuan Rumah Susun selambat lambatnya dalam waktu 14 (empat belas) hari setelah tanggal undangan penandatanganan PPJB. Dalam hal PEMBELI tidak atau belum menandatangani Perjanjian Pengikatan Jual Beli Satuan Rumah Susun dalam waktu yang ditetapkan, maka PJA dapat membatalkan Surat Persetujuan Pembelian ini.</li>
			<br/>
			<li>Surat Persetujuan Pembelian ini bukan merupakan surat berharga ataupun surat bukti kepemilikan atas Satuan Rumah Susun, sehingga PEMBELI dilarang untuk mengalihkan, menghibahkan, memperjualbelikan, dan/atau menjaminkan Surat Persetujuan Pembelian ini atau hak atas Satuan Rumah Susun ini kepada pihak manapun dan dengan cara apapun. Dalam hal PEMBELI melakukannya, maka Surat Persetujuan Pembelian ini menjadi batal demi hukum.</li>
			<br/>
			<li>Apabila PEMBELI dalam membayar harga Satuan Rumah Susun tersebut mempergunakan fasilitas Kredit Pemilikan Apartemen (KPA), maka wajib menggunakan fasilitas KPA dari Bank rekanan PJA dan untuk itu PEMBELI wajib melengkapi persyaratan KPA dan memperoleh Surat Persetujuan Kredit (SPK) dari Bank tersebut selambat-lambatnya 30 (tiga puluh) hari sebelum tanggal jatuh tempo penandatanganan KPA. Bila PEMBELI gagal melengkapi persyaratan KPA dalam waktu yang telah ditetapkan yang berakibat tidak diperolehnya Surat Persetujuan Kredit (SPK) dari Bank dan/atau proses permohonan KPA tersebut ditolak oleh pihak Bank maka PEMBELI wajib melanjutkan pembayaran secara tunai ke PJA. Adapun PEMBELI melakukan batal sepihak, maka akan dikenakan sesuai dengan Pasal 11 Surat Persetujuan Pembelian. Pajak dan bea yang sudah dibayarkan tidak dapat dikembalikan dan PEMBELI membebaskan PJA dari segala macam kewajiban pembayaran ganti rugi, bunga ataupun biaya-biaya lainnya.</li>
			<br/>
			<li>Apabila kemudian terjadi perubahan nilai KPA, maka PEMBELI bersedia membayar seluruh kekurangan nilai KPA selambat-lambatnya 14 (empat belas) hari sejak saat penyerahan SPK baru. Keterlambatan dalam pembayaran tersebut akan dikenakan denda 1‰ (satu permil) perhari hingga denda maksimal mencapai 3% (tiga persen). Dalam hal keterlambatan pembayaran telah mencapai denda maksimal dan PEMBELI belum melakukan kewajiban pembayaran berikut denda-dendanya, maka Surat Persetujuan Pembelian ini menjadi batal demi hukum.</li>
			<br/>
			<li>Luas unit Satuan Rumah Susun yang tercantum pada Surat Persetujuan ini dihitung dari garis sumbu dinding batas unit. Apabila terjadi perbedaan luas unit yang tercantum dalam sertipikat sarusun yang dibuat oleh Kantor Badan Pertanahan Nasional wilayah Tangerang akibat adanya perbedaan metode perhitungan luas, maka PEMBELI tidak akan memperhitungkan kembali harga jual beli dan tidak akan saling mengadakan tuntutan dalam bentuk apapun kepada PJA.</li>
			<br/>
		</ol>
	</div>
	<div id='kanan'>
		<ol start="9">
			<li>Perubahan ke tipe dan nomor unit lain hanya diperkenankan selambat lambatnya 1 (satu) bulan sejak pembayaran tanda jadi dan hanya untuk perubahan kepada tipe dengan nilai yang lebih besar. Harga transaksi baru berlaku sesuai dengan harga pada saat perubahan tersebut dilaksanakan pajak dan bea yang sudah dibayarkan untuk transaksi terdahulu tidak dapat diperhitungkan dan harus dibayarkan kembali.</li>	
			<br/>
			<li>Penyelesaian Unit Satuan Rumah Susun selambat-lambatnya empat buluh delapan bulan (48) sejak ditandatanganinya Perjanjian Awal Ikatan Jual Beli (PAIB) dan masa grace period selama dua belas (12) bulan , kecuali karena hal-hal yang disebabkan oleh peristiwa Force Majeur.
			</li>	
			<br/>
			<li>Dalam hal Surat Persetujuan Pembelian menjadi batal atau dibatalkan sebagaimana diatur dalam Surat Persetujuan Pembelian ini, maka PJA dan PEMBELI sepakat untuk mengesampingkan pasal 1266 dan 1267 Kitab Undang-Undang Hukum Perdata (KUHPerdata) dan atas pembayaran yang telah dilakukan oleh PEMBELI akan dikenakan biaya administrasi sebesar 20% (dua puluh persen) dari nilai transaksi ditambah kewajiban-kewajiban terhutang (jika ada) atau jika pembayaran belum mencapai 20% (dua puluh persen) maka seluruh pembayaran yang telah dilakukan tidak dapat dikembalikan. Proses pengembalian dilakukan selambat-lambatnya dalam waktu 30 (Tiga puluh) hari sejak Para Pihak menyelesaikan dokumen pembatalan. Pajak dan bea yang sudah dibayarkan tidak dapat dikembalikan dan PEMBELI membebaskan PJA dari segala macam kewajiban pembayaran ganti rugi, bunga ataupun biaya-biaya lainnya.
			</li>	
			<br/>
			<li>Alamat PEMBELI di atas adalah benar dan segala administrasi surat-menyurat mempergunakan alamat dan nomor telepon di atas.Perubahan alamat wajib diinformasikan kepada PJA melalui Bagian Penagihan Unit Pemasaran, selambat-lambatnya 3 (tiga) hari sejak perubahan alamat tersebut dilakukan. Segala akibat yang timbul atas kelalaian memberikan informasi tentang perubahan alamat tersebut menjadi tanggung jawab PEMBELI termasuk tapi tidak terbatas pada dapat dibatalkannya secara sepihak Surat Persetujuan Pembelian ini oleh PJA.</li>
			<br/>
			<li>Transaksi pada butir 1 di atas belum termasuk biaya Akta Jual Beli (AJB), Biaya Balik Nama (BBN), Bea Perolehan Hak Atas Tanah dan Bangunan BPHTB) serta biaya lainnya sesuai dengan peraturan yang berlaku. Segala kekurangan biaya antara lain, AJB, BBN dan BPHTB serta pajak-pajak dan biaya lainnya yang mengalami perubahan dan/atau penyesuaian akibat peraturan yang saat ini berlaku maupun yang timbul di kemudian hari harus dilunasi selambat-lambatnya 30 (tiga puluh) hari sebelum AJB ditandatangani.
			</li>	
			<br/>	
			<li>Transaksi pada butir 1 di atas belum termasuk biaya Akta Jual Beli (AJB), Biaya Balik Nama (BBN), Bea Perolehan Hak Atas Tanah dan Bangunan BPHTB) serta biaya lainnya sesuai dengan peraturan yang berlaku. Segala kekurangan biaya antara lain, AJB, BBN dan BPHTB serta pajak-pajak dan biaya lainnya yang mengalami perubahan dan/atau penyesuaian akibat peraturan yang saat ini berlaku maupun yang timbul di kemudian hari harus dilunasi selambat-lambatnya 30 (tiga puluh) hari sebelum AJB ditandatangani.</li>
			<br/>
			<li>Terhitung sejak tanggal dilakukannya penandatanganan Surat Persetujuan Pembelian, maka segala pajak, iuran, dan beban lain yang terhutang atas Satuan Rumah Susun yang dipungut oleh instansi yang berwenang dan/atau oleh PJA, termasuk tapi tidak terbatas pada Pajak Bumi dan Bangunan (PBB) menjadi beban dan tanggung jawab PEMBELI sepenuhnya. Khusus Iuran Pengelolaan Gedung/Service Charge dan Shinking Fund, sebelum terbentuknya Perhimpunan Pemilik dan Penghuni Satuan Rumah Susun (PPPSRS), maka akan ditentukan dan dipungut oleh PJA dan wajib dibayarkan oleh PEMBELI kepada PJA sejak serah terima unit Satuan Rumah Susun antara PJA dengan PEMBELI.
			</li>
			<br/>
			<li>Terhitung sejak tanggal penandatanganan Surat Persetujuan Pembelian ini, PEMBELI bersedia memenuhi biaya dan kewajiban sesuai dengan peraturan yang dikeluarkan oleh PJA berkaitan dengan unit Satuan Rumah Susun yang dibeli oleh PEMBELI.

			</li>
			<br/>	
		</ol>
	</div>
</div>
</body>
</html>


<?php
require_once('../../../../../config/config.php');
require_once('../../../../../config/terbilang.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);

$terbilang 	= new Terbilang;
$id			= (isset($_REQUEST['id'])) ? base64_decode(clean($_REQUEST['id'])) : '';

$query = "
SELECT * 
FROM KWITANSI_TANDA_TERIMA a
LEFT JOIN JENIS_PEMBAYARAN b ON a.BAYAR_UNTUK = b.KODE_BAYAR
WHERE NOMOR_KWITANSI LIKE '%$id%'
";
$obj = $conn->execute($query);

$nomor			= $obj->fields['NOMOR_KWITANSI'];
$tanggal		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));
$kode_blok		= $obj->fields['KODE_BLOK'];
$nama_pembayar	= $obj->fields['NAMA_PEMBELI'];
$jenis_bayar	= $obj->fields['JENIS_BAYAR'];
$no_tlp			= $obj->fields['NOMOR_TELEPON'];
$alamat			= $obj->fields['ALAMAT_PEMBELI'];
$bank			= $obj->fields['BANK_GIRO'];
$keterangan		= $obj->fields['KETERANGAN'];
$jumlah			= $obj->fields['JUMLAH_DITERIMA'];
$koordinator	= $obj->fields['KOORDINATOR'];
$penerima		= $obj->fields['KASIR'];
$bayar_secara	= $obj->fields['BAYAR_SECARA'];
$bayar = array("1"=>"Tunai","2"=>"Cek","3"=>"bilyet","4"=>"Lain");
$bayar_secara = $bayar[$bayar_secara];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<style type="text/css">
		@page {
			size: A5 landscape ;
			margin: 0;
		}
		@media screen, print{

			body{
				font-family: 'Century Gothic';
			}
			#logo_ancol {

			}
			#logo_seafront{
				float: right;
				right: 0px;
			}
			#logo_ancol_gambar {
				width: 160px;
				height: 40px;
			}
			#logo_seafront_gambar {
				width: 220px;
				height: 40px;
			}

			#id_kwitansi, #tts{
				text-align: center;
			}
			#id_kwitansi{
				font-size: 11px;
			}
			.kata{
				font-size: 11px;
				line-height: 20px;
			}

			.garis_bawah{
				height: 17px;
				border-bottom: 1px #aaaaaa solid;
			}

			.atas_nama{
				font-weight: bold;
			}
			.garis_atas_nama{
				height: 1px; 
				border-bottom: 1px solid;
			}
			.table_header{
				border : 1px solid;
				padding: 4px;
				text-align: center;
			}
			.kolom{
				border-right: 1px solid;
				border-left:1px solid;
				text-align: center;
				padding-bottom: 3px;
				padding-top: 5px;
			}
			.kolom_bawah{
				border-bottom: 1px solid;
			}
			#tanggal{
				margin-top: 10px;
				float: right;
			}
			.footer{
				text-align: center;
			}
			#footer{
				text-align: center;
			}
			#footer_contact{
				text-align: center;
				font-size: 8px;
			}

			pre {
				font-family: 'Century Gothic';
			}
		}

	</style>

</head>
<body onload="window.print()">
	<div id = 'page'>
		<table width="100%" border="0">

			<tr>
				<td colspan="2"></td>
				<td><div id = 'id_kwitansi'><a>No. <?php echo $id; ?></a></div></td>
			</tr>
			<tr height = '20'>
				<td colspan ="3"><div id='tts'><a>KUITANSI</a></div></td>
			</tr>
			<tr>
				<td colspan ="3"><div class="kata"><a>Telah terima uang tunai / Cek Cash / Bilyet Giro / EDC untuk pembayaran :</a></div></td>
			</tr>
			<tr>
				<td colspan="3"><div class = 'garis_bawah'></div></td>
			</tr>
			<tr>
				<td colspan="3"><div class = 'kata garis_bawah'><a><?php echo $keterangan; ?></a></div></td>
			</tr>
			<tr>
				<td colspan="3"><div class = 'garis_bawah'></div></td>
			</tr>

		</table>
		<table border='0' width="100%">
			<tr>
				<td><a class="kata">Atas Nama</a></td>
				<td>:</td>
				<td width="80%" class="garis_atas_nama"><a class="kata atas_nama"><?php echo $nama_pembayar; ?> </a></td>
			</tr>
			<tr>
				<td><a class="kata">Sebesar</a></td>
				<td>:</td>
				<td width="80%" class="garis_atas_nama"><a class="kata atas_nama"><?php echo to_money($jumlah); ?></a></td>
			</tr>
		</table>
		<a class="kata">dengan perincian sebagai berikut : </a>
		<table width="100%"  cellspacing="0" class="kata">
			<tr>
				<td width="10%" class="table_header">NO.</td>
				<td width="40%" class="table_header">CEK CASH / BILYET GIRO / EDC</td>
				<td width="10%" class="table_header">NOMOR</td>
				<td width="20%" class="table_header">TANGGAL</td>
				<td width="20%" class="table_header">RUPIAH</td>
			</tr>
			<tr>
				<td class="kolom kolom_bawah">1</td>
				<td class="kolom kolom_bawah"><?php echo $bayar_secara ?></td>
				<td class="kolom kolom_bawah"></td>
				<td class="kolom kolom_bawah"><?php echo $tanggal; ?></td>
				<td class="kolom kolom_bawah"><?php echo to_money($jumlah); ?>,-</td>
			</tr>
		</table>
		<div id="tanggal" class="kata">Jakarta, <?php echo $tanggal; ?></div>	
		<table width="100%" class="kata footer">
			<tr>
				<td width="25%">&nbsp;</td>
				<td width="50%"></td>
				<td width="25%">Yang Menerima,</td>
			</tr>
			<tr height = "40">
				<td>&nbsp;</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td></td>
				<td>( <?php echo $penerima; ?> )</td>
			</tr>
		</table>
		<div id = "footer">PT. PEMBANGUNAN JAYA ANCOL Tbk.</div>
		<div id = "footer_contact">Ecovention Building - Ecopark Jl. Lodan Timur No. 7, Taman Impian Jaya ancol, Jakarta 14430 - INDONESIA, Telp. (021) 6458899, 6454567, Fax. (021) 29381811, 64710502</div>
	</div>
</body>
</html>

<?php close($conn); ?>

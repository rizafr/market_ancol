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
$catatan_kwt= (isset($_REQUEST['catatan_kwt'])) ? (clean($_REQUEST['catatan_kwt'])) : '';

$query = "
SELECT * 
FROM KWITANSI
WHERE NOMOR_KWITANSI = '$id'
";
$obj = $conn->execute($query);

$kode_blok	 	= $obj->fields['KODE_BLOK'];	
$nama_pembayar 	= $obj->fields['NAMA_PEMBAYAR'];	
$keterangan 	= $obj->fields['KETERANGAN'];
$nilai 			= $obj->fields['NILAI'];
$kode_bayar		= (isset($obj->fields['KODE_BAYAR']))? clean($obj->fields['KODE_BAYAR']): '';
$tanggal		= kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL']))));
$tgl_bayar		= tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));

$query = "
UPDATE KWITANSI SET 
STATUS_KWT = '1',
CATATAN_KWT = '$catatan_kwt'
WHERE NOMOR_KWITANSI = '$id'
";
ex_false($conn->Execute($query), $query);


$query = " SELECT COUNT(*) AS TOTAL FROM REALISASI WHERE NOMOR_KWITANSI = '$id' ";
$obj = $conn->execute($query);
$total	 = $obj->fields['TOTAL'];	

if($total == 0)
{
	$query = "
	INSERT INTO REALISASI (
		KODE_BLOK, TANGGAL, NILAI, NOMOR_KWITANSI, KODE_BAYAR, KETERANGAN
		)
	VALUES(
	'$kode_blok', CONVERT(DATETIME,'$tgl_bayar',105), $nilai, '$id', '$kode_bayar', '$keterangan'
	)
";
ex_false($conn->execute($query), $query);
}

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
				<td width="80%" class="garis_atas_nama"><a class="kata atas_nama"><?php echo to_money($nilai); ?></a></td>
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
				<td class="kolom kolom_bawah">Uang Tunai</td>
				<td class="kolom kolom_bawah"></td>
				<td class="kolom kolom_bawah"><?php echo $tgl_bayar; ?></td>
				<td class="kolom kolom_bawah"><?php echo to_money($nilai); ?>,-</td>
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

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
$jenis_bayar		= $obj->fields['JENIS_BAYAR'];
$no_tlp			= $obj->fields['NOMOR_TELEPON'];
$alamat			= $obj->fields['ALAMAT_PEMBELI'];
$bank			= $obj->fields['BANK_GIRO'];
$keterangan		= $obj->fields['KETERANGAN'];
$jumlah			= $obj->fields['JUMLAH_DITERIMA'];
$koordinator	= $obj->fields['KOORDINATOR'];
$penerima		= $obj->fields['KASIR'];
$bayar_secara	= $obj->fields['BAYAR_SECARA'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style type="text/css">
@page {
  size: A4;
  margin: 0;
}
@media screen, print{
    body {
        font-family: verdana,sans-serif;
        font-size: 14px;
		margin-right: 200px;
    }
	pre {
        font-family: verdana,sans-serif;
        font-size: 14px;
    }
	.garis {
    border: 1px solid black;
    border-collapse: collapse;
	}
}

</style>
</head>

<body onload="window.print()">
<table align="right">
<tr>
	<td>No. Dok.</td>
	<td>:</td> 
	<td>007/F/KEU/JRP/06</td>
</tr>
<tr>
	<td>Rev.</td>
	<td>:</td>
	<td>0</td>
</tr>
</table>

<br>

<table width="700">
<tr>
	<td colspan=4><hr></td>
</tr>
<tr>
	<td width="150">No. Tanda Terima</td><td>:</td>
	<td><?php echo $nomor; ?></td>
	<td align="right">Tanggal : <?php echo $tanggal; ?></td>
</tr>
<tr>
	<td>Kode Blok</td><td>:</td>
	<td><?php echo $kode_blok; ?></td>
	<td align="right">Pembayaran : <?php echo $jenis_bayar; ?></td>
</tr>
<tr>
	<td>Nama Pembayar</td><td>:</td>
	<td colspan="2"><?php echo $nama_pembayar; ?></td>
</tr>
<tr>
	<td>Nomor Telepon</td><td>:</td>
	<td colspan="2"><?php echo $no_tlp; ?></td>
</tr>
<tr>
	<td>Alamat Pembayar</td><td>:</td>
	<td colspan="2"><?php echo $alamat; ?></td>
</tr>
<tr>
	<td>Pembayaran Secara</td><td>:</td>
	<td>
		<input type="radio" name="bayar_secara" id="tunai" class="status" value="1" <?php echo is_checked('1', $bayar_secara); ?>>Tunai
		<input type="radio" name="bayar_secara" id="cek" class="status" value="2" <?php echo is_checked('2', $bayar_secara); ?>>Cek   
		<input type="radio" name="bayar_secara" id="bilyet" class="status" value="3" <?php echo is_checked('3', $bayar_secara); ?>>Bilyet
		<input type="radio" name="bayar_secara" id="lain" class="status" value="4" <?php echo is_checked('4', $bayar_secara); ?>> Lain
	</td>
	<td> Bank : <?php echo $bank; ?></td>
</tr>
<tr>
	<td>Keterangan</td><td>:</td>
	<td colspan="2"><?php echo $keterangan; ?></td>
</tr>
<tr>
	<td>Jumlah Diterima</td><td>:</td>
	<td>Rp. <?php echo to_money($jumlah); ?></td>
</tr>
<tr>
	<td>Terbilang</td><td>:</td>
	<td colspan="2">## <?php echo ucfirst($terbilang->eja($jumlah)); ?> rupiah ##</td>
</tr>
<tr>
	<td>Koordinator</td><td>:</td>
	<td><?php echo $koordinator; ?></td>
	<td>Penerima : <?php echo $penerima; ?></td>
</tr>
<tr>
	<td colspan=4><hr></td>
</tr>
</table>

<table width="700">
<tr>
	<td width="200" class="garis" align="center">KASIR<br><br><br></td>
	<td> </td>
	<td width="200" class="garis" align="center"><br><br><br></td>
</tr>
</table>

</body>
</html>

<?php close($conn); ?>

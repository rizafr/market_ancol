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
	FROM KWITANSI
	WHERE NOMOR_KWITANSI = '$id'
";
$obj = $conn->execute($query);

$nama_pembayar 	= $obj->fields['NAMA_PEMBAYAR'];	
$keterangan 	= $obj->fields['KETERANGAN'];
$nilai 			= to_money($obj->fields['NILAI']);
$tanggal		= tgltgl(f_tgl($obj->fields['TANGGAL']));
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
    }
	pre {
        font-family: verdana,sans-serif;
        font-size: 14px;
    }
}

</style>
</head>

<body onload="window.print()">
<table width="500">
<tr>
	<td align="center" colspan=6><h2><u>BON PENERIMAAN</u><h2></td><td>
</tr>
<tr>
	<td width="130">No</td><td>:</td>
	<td><?php echo $id; ?></td>
</tr>
<tr>
	<td>Penerimaan untuk</td><td>:</td>
</tr>
<tr>
	<td colspan=6><pre><?php echo $keterangan; ?></pre></td>
</tr>
<tr>
	<td>Nilai</td><td>:</td>
	<td>Rp. <?php echo $nilai; ?></td>
	<td>Tanggal</td><td>:</td>
	<td><?php echo $tanggal; ?></td>
</tr>
</table>
</body>
</html>

<?php close($conn); ?>

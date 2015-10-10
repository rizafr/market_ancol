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
	FROM KWITANSI_LAIN_LAIN
	WHERE NOMOR_KWITANSI = '$id'
";
$obj = $conn->execute($query);

$nama_pembayar 	= $obj->fields['NAMA_PEMBAYAR'];	
$keterangan 	= $obj->fields['KETERANGAN'];
$nilai 			= $obj->fields['NILAI'];
$tanggal		= kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL']))));

$query = "
		UPDATE KWITANSI_LAIN_LAIN SET 
			STATUS_KWT = '1',
			CATATAN_KWT = '$catatan_kwt'
		WHERE NOMOR_KWITANSI = '$id'
		";
ex_false($conn->Execute($query), $query);
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
		margin-right: 400px;
    }
	pre {
        font-family: verdana,sans-serif;
        font-size: 14px;
    }
}

</style>
</head>

<body onload="window.print()">
<table align="right">
<tr>
	<td>No. Dok.</td><td>: 005/F/KEU/JRP/06</td>
</tr>
<tr>
	<td>Rev.</td><td>: 0</td>
</tr>
</table>
<br><br>
<table width=500>
<tr>
	<td colspan=2><?php echo $id; ?><br><br></td>
</tr>
<tr>
	<td colspan=2><?php echo $nama_pembayar; ?><br><br></td>
</tr>
<tr>
	<td colspan=2>## <?php echo ucfirst($terbilang->eja($nilai)); ?> rupiah ##</td>
</tr>
<tr>
	<td colspan=2><pre><?php echo $keterangan; ?></pre></td>
</tr>
<tr>
	<td></td><td align="right"><?php echo $tanggal; ?></td>
</tr>
<tr>
	<td>## <?php echo to_money($nilai); ?> ##<br><br><br><br><td align="right"><br><br><br><br><?php echo 'pejabat_penandatangan'; ?></td>
</tr>
</table>
</body>
</html>

<?php close($conn); ?>

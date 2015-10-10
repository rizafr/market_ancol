<?php
require_once('../../../../../config/config.php');
die_login();
die_app('A01');
die_mod('JB11');
$conn = conn($sess_db);
die_conn($conn);

// $per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$per_page	= 5500;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$query = "
SELECT count(*) as TOTAL
FROM SPP WHERE KODE_BLOK NOT IN 
(
SELECT DISTINCT a.KODE_BLOK FROM CS_PPJB a
JOIN SPP b on a.KODE_BLOK = b.KODE_BLOK
)
";
$total_data = $conn->Execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$query = "SELECT * FROM CS_PARAMETER_PPJB";
$obj = $conn->Execute($query);
$set_jrp = '
<tr><td colspan="6" class="nb"><b>' . $obj->fields['NAMA_PT'] . '</b></td></tr>
<tr><td colspan="6" class="nb"><b><u>' . $obj->fields['NAMA_DEP'] . '</u></b></td></tr>
<tr><td colspan="6" class="nb text-center"><h3><b> DAFTAR SPP BELUM PPJB </b></h3></td></tr>
<tr>
	<td colspan="4" class="nb">
	</td>
	<td colspan="2" align="right" class="nb text-right va-bottom">Halaman 
';

$set_th = '
	dari ' . $total_page . '</td>
</tr>

<tr>
	<th rowspan="2">NO.</th>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">NAMA PEMBELI</th>
	<th rowspan="2">JENIS</th>
	<th colspan="2">SPP</th>
</tr>
<tr>
	<th colspan="1">TANGGAL</th>
	<th colspan="1">DISTRIBUSI</th>
</tr>
';

$set_ttd = '
<tr>
	<td colspan="3" class="nb text-center"></td>
	<td colspan="3" class="nb text-center"><br><br><br><br></td>
</tr>
<tr>
	<td colspan="3" class="nb text-center"></td>
	<td colspan="3" class="nb text-center">' . $obj->fields['KOTA'] . ', ' .kontgl(date('d M Y')). '</td>
</tr>
<tr>
	<td colspan="3" class="nb text-center"></td>
	<td colspan="3" class="nb text-center">Mengetahui,</td>
</tr>
<tr>
	<td colspan="3" class="nb text-center"></td>
	<td colspan="3" class="nb text-center"><br><br><br><br></td>
</tr>
<tr>
	<td colspan="3" class="nb text-center"></td>
	<td colspan="3" class="nb text-center">(------------------)</td>
</tr>
';

$p = 1;
function th_print() {
	Global $p, $set_jrp, $set_th;
	echo $set_jrp . $p . $set_th;
	$p++;
}

$filename = "DAFTAR SPP BELUM PPJB.xls";

header("Content-type: application/msexcel");
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header("Pragma: no-cache");
header("Expires: 0");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>DAFTAR SPP BELUM PPJB</title>
<style type="text/css">
@media print {
	@page {
		size: 8.5in 4in landscape;
	}
	.newpage {page-break-before:always;}
}

.newpage {margin-top:25px;}

table {
	font-family:Arial, Helvetica, sans-serif;
	width:100%;
	border-spacing:0;
	border-collapse:collapse;
}
table tr {
	font-size:11px;
	padding:2px;
}
table td {
	padding:2px;
	vertical-align:top;
}
table th.nb,
table td.nb {
	border:none !important;
}
table.data th {
	border:1px solid #000000;
}
table.data td {
	border-right:1px solid #000000;
	border-left:1px solid #000000;
}
tfoot tr {
	font-weight:bold;
	text-align:right;
	border:1px solid #000000;
}
.break { word-wrap:break-word; }
.nowrap { white-space:nowrap; }
.va-top { vertical-align:top; }
.va-bottom { vertical-align:bottom; }
.text-left { text-align:left; }
.text-center { text-align:center; }
.text-right { text-align:right; }
</style>
</head>
<body>

<table class="data">

<?php
echo th_print();

if ($total_data > 0)
{
	$query = "
	SELECT * FROM SPP WHERE KODE_BLOK NOT IN 
	(
	SELECT DISTINCT a.KODE_BLOK FROM CS_PPJB a
	JOIN SPP b on a.KODE_BLOK = b.KODE_BLOK
	)
	";
	$obj = $conn->Execute($query);
	
	$i = 1;
	
	$total_rows = $obj->RecordCount();
	
	while( ! $obj->EOF)
	{		
		?>
		<tr>
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo sistem_pembayaran($obj->fields['STATUS_KOMPENSASI']); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_TANDA_JADI']))); ?></td>
		</tr>
		<?php
		
		if ($i % $per_page === 0)
		{
			echo '<tr><td class="nb"><div class="newpage"></div></td></tr>';
			th_print();
		}
		$i++;
		
		$obj->movenext();
	}
	echo $set_ttd;
}
?>
</table>
</body>
</html>
<?php
close($conn);
exit;
?>
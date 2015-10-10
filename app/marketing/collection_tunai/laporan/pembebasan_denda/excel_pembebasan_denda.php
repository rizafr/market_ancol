<?php
require_once('../../../../../config/config.php');
die_login();
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search = '';	

if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= "AND TANGGAL_OTORISASI >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL_OTORISASI <= CONVERT(DATETIME,'$periode_akhir',105)";
}

/* Pagination */
$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	CS_INFORMASI_DENDA
WHERE OTORISASI = '1'
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$set_jrp = '
<tr><td colspan="8" class="nb text-center"><b> LAPORAN DAFTAR PEMBEBASAN DENDA </b></td></tr>
<tr><td colspan="8" class="nb text-center"> Periode ' .kontgl(date("d M Y", strtotime($periode_awal))). ' s/d ' .kontgl(date("d M Y", strtotime($periode_akhir))). ' </td></tr>
<tr>
	<td colspan="6" class="nb">
	</td>
	<td colspan="2" class="nb">Halaman 
';

$set_th = '
	dari ' . $total_page . '</td>
</tr>
<tr>
	<th rowspan="2">NO.</th>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">TANGGAL</th>
	<th rowspan="2">KETERLAMBATAN</th>
	<th rowspan="2">NILAI</th>
	<th colspan="2">PERHITUNGAN DENDA</th>
	
</tr>
<tr>
	<th colspan="1">AWAL</th>
	<th colspan="1">DISETUJUI</th>
</tr>
';

$set_ttd = '
<tr>
	<td colspan="7" class="nb text-center"></td>
	<td colspan="3" class="nb text-center"><br><br><br><br></td>
</tr>
<tr>
	<td colspan="7" class="nb text-center"></td>
	<td colspan="3" class="nb text-center">' . 'Tangerang, ' .kontgl(date('d M Y')). '</td>
</tr>
<tr>
	<td colspan="7" class="nb text-center"></td>
	<td colspan="3" class="nb text-center">Mengetahui,</td>
</tr>
<tr>
	<td colspan="7" class="nb text-center"></td>
	<td colspan="3" class="nb text-center"><br><br><br><br></td>
</tr>
<tr>
	<td colspan="7" class="nb text-center"></td>
	<td colspan="3" class="nb text-center">(------------------)</td>
</tr>
';

$filename = "Laporan Daftar Pembebasan Denda " .kontgl(date("d M Y", strtotime($periode_awal))). " s/d " .kontgl(date("d M Y", strtotime($periode_akhir)));

header("Content-type: application/msexcel");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache");
header("Expires: 0");

$p = 1;
function th_print() {
	Global $p, $set_jrp, $set_th;
	echo $set_jrp . $p . $set_th;
	$p++;
}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>LAPORAN DAFTAR PEMBEBASAN DENDA</title>
<style type="text/css">
@media print {
	@page {
		size: 8.5in 4in portrait;
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
<body onload="window.print()">

<table class="data">

<?php
echo th_print();

if ($total_data > 0)
{
	$query = "
	SELECT *, DISETUJUI = CASE WHEN denda_disetujui IS null 
	THEN '0.00' ELSE DENDA_DISETUJUI END
	FROM CS_INFORMASI_DENDA
	WHERE OTORISASI = '1'
	$query_search
	ORDER BY TANGGAL desc
	";

	$obj = $conn->Execute($query);
	
	$i = 1;
	
	$total_rows = $obj->RecordCount();
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		$tgl = $obj->fields['TANGGAL'];
		
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_OTORISASI']))); ?></td>
			<td><?php echo $obj->fields['HARI_TUNGGAKAN']; ?></td>
			<td class="text-center"><?php echo to_money($obj->fields['NILAI']); ?></td>
			<td><?php echo to_money($obj->fields['DENDA']); ?></td>
			<td><?php echo to_money($obj->fields['DISETUJUI']); ?></td>		
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
	
}
?>
</table>
</body>
</html>
<?php
close($conn);
exit;
?>
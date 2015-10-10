<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('A01');
//die_mod('JB10');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$periode_awal	= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir	= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';
$field1			= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';

$query_search = '';	

if($field1 != 'all')
{
	$query_search .= "AND KODE_PEMBAYARAN = '$field1'";
}

if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= "AND TANGGAL >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL <= CONVERT(DATETIME,'$periode_akhir',105)";
}

/* Pagination */
$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	KWITANSI_LAIN_LAIN 
WHERE KODE_BLOK IS NOT NULL 
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$set_jrp = '
<tr><td colspan="8" class="nb text-center"><b> LAPORAN PENERIMAAN LAIN-LAIN </b></td></tr>
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
	<th>NO.</th>
	<th>NO. KWITANSI</th>
	<th>TANGGAL</th>
	<th>BLOK / NOMOR</th>
	<th>NAMA PEMBELI</th>
	<th>NILAI</th>	
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

$filename = "Laporan Penerimaan Lain-lain Periode " .kontgl(date("d M Y", strtotime($periode_awal))). " s/d " .kontgl(date("d M Y", strtotime($periode_akhir)));

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
<title>LAPORAN PENERIMAAN LAIN-LAIN</title>
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
	SELECT * FROM
	KWITANSI_LAIN_LAIN 
	WHERE KODE_BLOK IS NOT NULL 
	$query_search
	ORDER BY TANGGAL
	";

	$obj = $conn->Execute($query);
	
	$i = 1;
	
	$total_rows = $obj->RecordCount();
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_KWITANSI'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td class="text-center"><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBAYAR']; ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>		
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
<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';
$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1			= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$query_search = '';

if($field1 == 'all')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT1 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT1 <= CONVERT(DATETIME,'$periode_akhir',105)
		AND TANGGAL_SURAT2 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT2 <= CONVERT(DATETIME,'$periode_akhir',105)
		AND TANGGAL_SURAT3 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT3 <= CONVERT(DATETIME,'$periode_akhir',105)
		AND TANGGAL_SURAT4 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT4 <= CONVERT(DATETIME,'$periode_akhir',105)
		AND TANGGAL_SURAT5 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT5 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'tempo')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT1 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT1 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'somasi1')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT2 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT2 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'somasi2')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT3 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT3 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'somasi3')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT4 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT4 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'wanprestasi')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT5 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT5 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

/* Pagination */

$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM
	RENCANA
$query_search
";

$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$set_jrp = '
<tr><td colspan="8" class="nb text-center"><b> DAFTAR SURAT PENAGIHAN </b></td></tr>
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
	<th rowspan="2">NAMA</th>
	<th colspan="2">PEMBERITAHUAN</th>
	<th colspan="2">SOMASI I</th>
	<th colspan="2">SOMASI II</th>
	<th colspan="2">SOMASI III</th>
	<th colspan="2">WANPRESTASI</th>
	
</tr>
<tr>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
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

$filename = "Daftar Surat Penagihan " .kontgl(date("d M Y", strtotime($periode_awal))). " s/d " .kontgl(date("d M Y", strtotime($periode_akhir)));

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
<title>DAFTAR SURAT PENAGIHAN</title>
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
	SELECT *
	FROM RENCANA a LEFT JOIN SPP b
	ON a.KODE_BLOK = b.KODE_BLOK
	$query_search
	";

	$obj = $conn->Execute($query);
	
	$i = 1;
	
	$total_rows = $obj->RecordCount();
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			
			<?php
			if($field1 == 'all')
			{
			?>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT1'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT1']; ?></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT2'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT2']; ?></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT3'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT3']; ?></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT4'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT4']; ?></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT5'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT5']; ?></td>
				
			<?php
			}
			if($field1 == 'tempo')
			{
			?>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT1'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT1']; ?></td>
				<td></td><td></td><td></td><td></td>
				<td></td><td></td><td></td><td></td>
			<?php
			}
			else if($field1 == 'somasi1')
			{
			?>
				<td></td><td></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT2'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT2']; ?></td>
				<td></td><td></td><td></td>
				<td></td><td></td><td></td>
			<?php
			}
			else if($field1 == 'somasi2')
			{
			?>
				<td></td><td></td><td></td><td></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT3'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT3']; ?></td>
				<td></td><td></td><td></td><td></td>
			<?php
			}
			else if($field1 == 'somasi3')
			{
			?>
				<td></td><td></td><td></td>
				<td></td><td></td><td></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT4'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT4']; ?></td>
				<td></td><td></td>
			<?php
			}
			else if($field1 == 'wanprestasi')
			{
			?>
				<td></td><td></td><td></td><td></td>
				<td></td><td></td><td></td><td></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT5'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT5']; ?></td>
			<?php
			}
			?>
			
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
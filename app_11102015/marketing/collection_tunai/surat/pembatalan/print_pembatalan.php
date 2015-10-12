<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('C01');
//die_mod('COS01');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$tgl 		= f_tgl (date("Y-m-d"));

$tanggal_bulan		= $tgl;
$pecah				= explode("-",$tanggal_bulan);
$sekarang_tgl		= $pecah[0];
$sekarang_bln 		= $pecah[1];
$sekarang_thn 		= $pecah[2];

//bulan kemarin
$kemarin_bln	= $sekarang_bln - 1;
$kemarin_thn	= $sekarang_thn;
if($sekarang_bln == 1)
{
	$kemarin_bln	= 12;
	$kemarin_thn	= $sekarang_thn - 1;
}

$query_blok_lunas_bayar = '';
$query_pemb_jt = '';
$query_tglmerah = '';
$query_libur = '';
$query_blok_lunas_bayar = "SELECT C.KODE_BLOK FROM ( SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM 
( SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from ( select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) 
AS NILAI from spp A where A.KODE_BLOK is not null UNION ALL SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL
)a GROUP BY a.KODE_BLOK ) A LEFT JOIN (SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B 
ON A.KODE_BLOK=B.KODE_BLOK where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0 )C LEFT JOIN SPP D ON C.KODE_BLOK = D.KODE_BLOK
WHERE C.REMAIN - (ISNULL(D.NILAI_CAIR_KPR,0)) >= 0 UNION ALL SELECT KODE_BLOK FROM KWITANSI WHERE TANGGAL >= CONVERT(DATETIME,'01-$kemarin_bln-$kemarin_thn',105)
AND TANGGAL < CONVERT(DATETIME,'01-$sekarang_bln-$sekarang_thn',105)
";

$query_pemb_jt .= "(SELECT UNDANGAN_PEMBATALAN FROM CS_PARAMETER_COL)";

# Pagination
$query = "
SELECT 
	A.KODE_BLOK
	FROM 
		SPP A JOIN RENCANA B ON A.KODE_BLOK = B.KODE_BLOK
	WHERE
		(select dbo.tambah_tgl(B.TANGGAL,$query_pemb_jt)) = CONVERT(DATETIME,'$tgl',105)AND 
	b.KODE_BLOK NOT IN($query_blok_lunas_bayar)
	ORDER BY A.KODE_BLOK
";
		
$n = 0;
$obj = $conn->execute($query);
while( ! $obj->EOF)
{
	$n++;
	$obj->movenext();
}
$total_data = $n;
$total_page = ceil($total_data/$per_page);

$set_jrp = '
<tr><td colspan="8" class="nb text-center"><b> LAPORAN DAFTAR SURAT ADMNISTRASI PEMBATALAN </b></td></tr>
<tr><td colspan="8" class="nb text-center"> Tanggal Cetak : '.fm_date(date("Y-m-d")).' </td></tr>
<tr>
	<td colspan="6" class="nb">
	</td>
	<td colspan="2" class="nb">Halaman 
';

$set_th = '
	dari ' . $total_page . '</td>
</tr>
<tr>
	<th class="w5">BLOK / NOMOR </th>
	<th class="w20">NAMA</th>
	<th class="w40">ALAMAT SURAT</th>
	<th class="w10">TELEPON</th>
	<th class="w10">TANGGAL JATUH TEMPO</th>
	<th class="w10">NILAI JATUH TEMPO</th>
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
<title>LAPORAN DAFTAR SURAT ADMINISTRASI PEMBATALAN</title>
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
	SELECT 
		A.KODE_BLOK,A.NAMA_PEMBELI,A.ALAMAT_SURAT,A.TELP_KANTOR,A.TELP_LAIN,A.TELP_RUMAH,B.TANGGAL,B.NILAI
	FROM 
		SPP A JOIN RENCANA B ON A.KODE_BLOK = B.KODE_BLOK
	WHERE
		(select dbo.tambah_tgl(B.TANGGAL,$query_pemb_jt)) = CONVERT(DATETIME,'$tgl',105)AND 
	b.KODE_BLOK NOT IN($query_blok_lunas_bayar)
	ORDER BY A.KODE_BLOK
	";

	$obj = $conn->Execute($query);
	
	$i = 1;
	
	$total_rows = $obj->RecordCount();
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		$tanggal_tempo = tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL'])));
		$TELP_KANTOR=(trim($obj->fields["TELP_KANTOR"])!="")?trim(strtoupper($obj->fields["TELP_KANTOR"])):"";
		$TELP_LAIN=(trim($obj->fields["TELP_LAIN"])!="")?",".trim(strtoupper($obj->fields["TELP_LAIN"])):"";
		$TELP_RUMAH=(trim($obj->fields["TELP_RUMAH"])!="")?",".trim(strtoupper($obj->fields["TELP_RUMAH"])):"";
		$TELP=$TELP_KANTOR.$TELP_LAIN.$TELP_RUMAH;
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo $obj->fields['ALAMAT_SURAT']; ?></td>
			<td><?php echo $TELP; ?></td>
			<td class="text-center"><input type="hidden" name="tanggal_tempo" id="tanggal_tempo" value="<?php echo $tanggal_tempo; ?>"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
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
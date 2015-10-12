<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C28');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$nama_bulan			= (isset($_REQUEST['nama_bulan'])) ? clean($_REQUEST['nama_bulan']) : '';
$page_num			= (isset($_REQUEST['page_num'])) ? clean($_REQUEST['page_num']) : 1;

$tahun				= (isset($_REQUEST['tahun'])) ? clean($_REQUEST['tahun']) : '';

$array_bulan 		= array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember'); 
	
$query_blok_lunas = "SELECT C.KODE_BLOK FROM ( SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM 
( SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from ( select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) 
AS NILAI from spp A where A.KODE_BLOK is not null UNION ALL SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL
)a GROUP BY a.KODE_BLOK ) A LEFT JOIN (SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B 
ON A.KODE_BLOK=B.KODE_BLOK where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0 )C LEFT JOIN SPP D ON C.KODE_BLOK = D.KODE_BLOK
WHERE C.REMAIN - (ISNULL(D.NILAI_CAIR_KPR,0)) >= 0
";

	
$tahun_depan = $tahun + 1;

/* Pagination */

$query = "
SELECT COUNT(*) AS TOTAL
FROM 
	SPP a JOIN REALISASI b 
	ON a.KODE_BLOK = b.KODE_BLOK
WHERE
	a.KODE_BLOK IN ($query_blok_lunas) 
	AND b.TANGGAL >= CONVERT(DATETIME,'$tahun',105) 
	AND b.TANGGAL < CONVERT(DATETIME,'$tahun_depan',105)
";

$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);


$set_jrp = '
<tr><td colspan="8" class="nb text-center"><b> LAPORAN DAFTAR SPP LUNAS TAHUNAN </b></td></tr>
<tr><td colspan="8" class="nb text-center"> Periode Lunas Tahun ' .$tahun. ' </td></tr>
<tr>
	<td colspan="6" class="nb">
	</td>
	<td colspan="2" class="nb">Halaman 
';

$set_th = '
	dari ' . $total_page . '</td>
</tr>
<tr>
	<th class="w3">NO</th>
	<th class="w5">BLOK/NO.</th>
	<th class="w20">NAMA</th>
	<th class="w5">JENIS</th>
	<th class="w10">TANGGAL SPP</th>
	<th class="w15">NILAI TRANSAKSI SEBELUM PPN</th>
	<th class="w15">PPN</th>
	<th class="w15">TOTAL TRANSAKSI</th>
	<th class="w15">TOTAL PEMBAYARAN</th>
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

$filename = "Laporan Daftar SPP Lunas Tahunan " .kontgl(date("d M Y", strtotime($periode_awal))). " s/d " .kontgl(date("d M Y", strtotime($periode_akhir)));

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
<title>LAPORAN DAFTAR SPP LUNAS TAHUNAN</title>
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
	$total_harga_rekap	= 0;
	$total_ppn_rekap	= 0;
	$total_all_rekap	= 0;
	$pembayaran_rekap	= 0;
	
	$query = "
	SELECT *
	FROM
		SPP a 
		LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
		LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
		LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
		LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
		LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
		LEFT JOIN CS_VIRTUAL_ACCOUNT g ON a.NOMOR_CUSTOMER = g.NOMOR_VA	
		LEFT JOIN REALISASI h ON a.KODE_BLOK = h.KODE_BLOK
	WHERE
		a.KODE_BLOK IN ($query_blok_lunas) 
		AND h.TANGGAL >= CONVERT(DATETIME,'$tahun',105) 
		AND h.TANGGAL < CONVERT(DATETIME,'$tahun_depan',105)
	";

	$obj = $conn->Execute($query);
	
	$i = 1;
	
	$total_rows = $obj->RecordCount();
	
	while( ! $obj->EOF)
	{
		$id 				= $obj->fields['KODE_BLOK'];
		$luas_tanah 		= $obj->fields['LUAS_TANAH'];
		$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
		
		$tanah 				= $luas_tanah * ($obj->fields['HARGA_TANAH']) ;
		$disc_tanah 		= round($tanah * ($obj->fields['DISC_TANAH'])/100,0) ;
		$nilai_tambah		= round(($tanah - $disc_tanah) * ($obj->fields['NILAI_TAMBAH'])/100,0) ;
		$nilai_kurang		= round(($tanah - $disc_tanah) * ($obj->fields['NILAI_KURANG'])/100,0) ;
		$faktor				= $nilai_tambah - $nilai_kurang;
		$total_tanah		= $tanah - $disc_tanah + $faktor;
		$ppn_tanah 			= round($total_tanah * ($obj->fields['PPN_TANAH'])/100,0) ;
		
		$bangunan 			= $luas_bangunan * ($obj->fields['HARGA_BANGUNAN']) ;
		$disc_bangunan 		= round($bangunan * ($obj->fields['DISC_BANGUNAN'])/100,0) ;
		$total_bangunan		= $bangunan - $disc_bangunan;
		$ppn_bangunan 		= round($total_bangunan * ($obj->fields['PPN_BANGUNAN'])/100,0) ;
		
		$total_harga 		= ($total_tanah + $total_bangunan);
		$total_ppn			= ($ppn_tanah + $ppn_bangunan);
		
		$total_harga_all	= ($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan);	
		
		$status_kompensasi	= $obj->fields['STATUS_KOMPENSASI'];
		if($status_kompensasi == 1)
			$jenis = 'KPR';
		else if($status_kompensasi == 2)
			$jenis = 'Tunai';
		else
			$jenis = '-';
			
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td class="text-center"><?php echo $jenis; ?></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP']))); ?></td>
			<td class="text-center"><?php echo to_money($total_harga); ?></td>
			<td class="text-center"><?php echo to_money($total_ppn); ?></td>
			<td class="text-center"><?php echo to_money($total_harga_all); ?></td>
			
			<?php 
			$query2 = "
			SELECT TOTAL = CASE WHEN sum(nilai) IS null 
			THEN 0 ELSE sum(nilai) END
			FROM REALISASI WHERE KODE_BLOK = '$id'
			";
			$obj2 			= $conn->execute($query2);
			$pembayaran		= $obj2->fields['TOTAL'];
			?>
	
			<td class="text-center"><?php echo to_money($pembayaran); ?></td>
			
		</tr>
		<?php
		
		$total_harga_rekap	= $total_harga_rekap + $total_harga;
		$total_ppn_rekap	= $total_ppn_rekap + $total_ppn;
		$total_all_rekap	= $total_all_rekap + $total_harga_all;
		$pembayaran_rekap	= $pembayaran_rekap + $pembayaran;
			
		if ($i % $per_page === 0)
		{
			echo '<tr><td class="nb"><div class="newpage"></div></td></tr>';
			th_print();
		}
		$i++;
		
		$obj->movenext();
	}
	?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td>Total</td>
		<td class="text-center"><?php echo to_money($total_harga_rekap); ?></td>
		<td class="text-center"><?php echo to_money($total_ppn_rekap); ?></td>
		<td class="text-center"><?php echo to_money($total_all_rekap); ?></td>
		<td class="text-center"><?php echo to_money($pembayaran_rekap); ?></td>
	</tr>
	<?php
}
?>
</table>
</body>
</html>
<?php
close($conn);
exit;
?>
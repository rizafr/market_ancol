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

if($field1 == 'periode')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE a.TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) 
		AND a.TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}


/* Pagination */

$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM
	SPP a 
	LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
	LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
	LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
	LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
	LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
	LEFT JOIN CS_VIRTUAL_ACCOUNT g ON a.NOMOR_CUSTOMER = g.NOMOR_VA	
$query_search
";

$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$set_jrp = '
<tr><td colspan="8" class="nb text-center"><b> LAPORAN RENCANA REALISASI SELURUH BLOK </b></td></tr>
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
	<th rowspan="2">BLOK</th>
	<th rowspan="2">NAMA PEMBELI</th>
	<th colspan="2">SPP</th>
	<th rowspan="2">HARGA</th>
	<th colspan="3">RENCANA</th>
	<th colspan="3">REALISASI</th>
	<th rowspan="2">SALDO</th>
</tr>
<tr>
	<th colspan="1">Nomor</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">Keterangan</th>
	<th colspan="1">Nilai</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">Keterangan</th>
	<th colspan="1">Nilai</th>
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
<title>Laporan Rencana Realisasi Seluruh Blok</title>
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
	FROM
	SPP a 
	LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
	LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
	LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
	LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
	LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
	LEFT JOIN CS_VIRTUAL_ACCOUNT g ON a.NOMOR_CUSTOMER = g.NOMOR_VA		
		$query_search
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
		
		$total_harga 		= $total_tanah + $total_bangunan;
		$total_ppn			= $ppn_tanah + $ppn_bangunan;
		
		$total_harga_ppn	= ($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan);	
		$total_rencana		= 0;
		
		$query2 = "
		SELECT * FROM 
		RENCANA a
		LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
		WHERE KODE_BLOK = '$id'
		ORDER BY TANGGAL";
		$obj2 = $conn->execute($query2);
		$isi_rencana	= 0;

		while( ! $obj2->EOF)
		{
			$tgl_rencana[$isi_rencana]		= $obj2->fields['TANGGAL'];
			$ket_rencana[$isi_rencana]		= $obj2->fields['JENIS_BAYAR'];
			$nilai_rencana[$isi_rencana]	= $obj2->fields['NILAI'];
			$total_rencana					= $total_rencana + $nilai_rencana[$isi_rencana];
			$obj2->movenext();
			$isi_rencana++;
		}
		
		$query2 = "
		SELECT TANGGAL, NILAI, ISNULL(NOMOR_KWITANSI, '-') AS KWITANSI
		FROM REALISASI
		WHERE KODE_BLOK = '$id'
		ORDER BY TANGGAL";
		$obj2 = $conn->execute($query2);
		$isi_realisasi	= 0;

		while( ! $obj2->EOF)
		{
			$tgl_realisasi[$isi_realisasi]		= $obj2->fields['TANGGAL'];
			$kwt_realisasi[$isi_realisasi]		= $obj2->fields['KWITANSI'];
			$nilai_realisasi[$isi_realisasi]	= $obj2->fields['NILAI'];
			$obj2->movenext();
			$isi_realisasi++;
		}
		
		while($isi_realisasi < $isi_rencana)
		{
			$tgl_realisasi[$isi_realisasi]		= '-';
			$kwt_realisasi[$isi_realisasi]		= '-';
			$nilai_realisasi[$isi_realisasi]	= 0;
			$isi_realisasi++;
		}
		
		$iterasi = 0;
		while($iterasi < $isi_rencana)
		{
			if($iterasi == 0)
			{
			?>
				<tr class="onclick" id="<?php echo $id; ?>"> 
				<td class="text-center"><?php echo $i; ?></td>
				<td class="text-center"><?php echo $id; ?></td>
				<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
				<td class="text-center"><?php echo $obj->fields['NOMOR_SPP']; ?></td>
				<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP']))); ?></td>
				<td class="text-right"><?php echo to_money($total_harga_ppn); ?></td>
			<?php	
			}
			else
			{
			?>
				<td></td><td></td><td></td><td></td><td></td><td></td>
			<?php
			}
			?>
			
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($tgl_rencana[$iterasi]))); ?></td>
			<td class="text-center"><?php echo $ket_rencana[$iterasi]; ?></td>
			<td class="text-right"><?php echo to_money($nilai_rencana[$iterasi]); ?></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($tgl_realisasi[$iterasi]))); ?></td>
			<td class="text-center"><?php echo $kwt_realisasi[$iterasi]; ?></td>
			<td class="text-right"><?php echo to_money($nilai_realisasi[$iterasi]); ?></td>
			
			<?php
			if($iterasi == 0)
			{
			?>
				<td class="text-right"><?php echo to_money($total_rencana); ?></td>
			<?php	
			}
			else
			{
			?>
				<td></td>
			<?php
			}
			?>
			
			</tr>
			<?php
			$iterasi++;
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
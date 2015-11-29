<link type="text/css" href="../../../config/css/style.css" rel="stylesheet">
<?php
require_once('../../../config/config.php');
die_login();
//die_app('A01');
//die_mod('JB10');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search = '';
if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= "WHERE TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105)";
}

/* Pagination */
$query = "
SELECT 
COUNT(*) AS TOTAL
FROM 
SPP 
$query_search
";
$total_data = $conn->Execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$set_jrp = '
<tr><td colspan="8" class="nb text-center"><b> LAPORAN AKUTANSI</b></td></tr>
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
	<th rowspan="2">NO. KWITANSI</th>
	<th rowspan="2">TANGGAL</th>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">NAMA PEMBELI</th>
	<th colspan="3">PENERIMAAN</th>
	
</tr>
<tr>
	<th colspan="1">Angsuran</th>
	<th colspan="1">Nilai DPP</th>
	<th colspan="1">PPN</th>
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
<title>LAPORAN AKUTANSI</title>
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


<div class="title-page">LAPORAN AKUNTING</div>
<table class="data">

<?php



if ($total_data > 0)
{
	$query_pembeli ="SELECT * FROM SPP ";
	$obj_pembeli = $conn->selectlimit($query_pembeli, $per_page, $page_start);
	while( ! $obj_pembeli->EOF)
	{

		$kode_blok = $obj_pembeli->fields['KODE_BLOK'];
		$customer_id = $obj_pembeli->fields['COSTUMER_ID'];
		$nama_pembeli = $obj_pembeli->fields['NAMA_PEMBELI'];
		$alamat = $obj_pembeli->fields['ALAMAT_RUMAH'];
		$pola_pembayaran = $obj_pembeli->fields['POLA_BAYAR'];
		$harga_pemesanan = $obj_pembeli->fields['HARGA_TOTAL'];
		$tanda_jadi 		= $obj_pembeli->fields['TANDA_JADI'];	
		$tgl_jadi	 		= $obj_pembeli->fields['TANGGAL_TANDA_JADI'];
		$jml_kpr	 		= $obj_pembeli->fields['JUMLAH_KPR'];
		$no_identitas		= $obj_pembeli->fields['NO_IDENTITAS'];	
		$npwp 				= $obj_pembeli->fields['NPWP'];
		
		?>
		<table border="1">
			<tr>
				<td><?= $customer_id ?></td>
				<td><?= $nama_pembeli ?></td>
				<td><?= $alamat ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?= $pola_pembayaran?></td>
				<td>Rp. <?= to_money($harga_pemesanan);?></td>
			</tr>
		</table>
		
		<table class="t-data w50 f-left" border="1" >
			<tr>
				<th class="w2">NO.</th>
				<th class="w6">TERMIN KONTRAK</th>
				<th class="w7">ANGSURAN</th>
				<th class="w10">JATUH TEMPO</th>
			</tr>
			<tr>
				<td class="text-center">1</td>
				<td>TANDA JADI</td>
				<td class="text-right"><?php echo to_money($tanda_jadi);  ?></td>
				<td><?php echo date("d M Y", strtotime($tgl_jadi)); ?></td>
			</tr>

			<?php
			$query = "
			SELECT *
			FROM 
			RENCANA a
			LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
			WHERE KODE_BLOK = '$kode_blok'
			ORDER BY TANGGAL
			";
			$obj = $conn->execute($query);
			$i = 2;
			$j=1;
			$total = 0;
			while( ! $obj->EOF)
			{
				$nilai = $obj->fields['NILAI'];
				$total+= $nilai;
				?>
				<tr>
					<td class="text-center"><?php echo $i;  ?></td>
					<td><?php echo $obj->fields['JENIS_BAYAR'] ." ".$j ?> </td>
					<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
					<td><?php echo date("d M Y", strtotime($obj->fields['TANGGAL'])); ?></td>
				</tr>
				<?php
				$i++;
				$j++;
				$obj->movenext();
			}
			if ($jml_kpr > 0) {	
				?>
				<tr>
					<td class="text-center"><?php echo $i; ?></td>
					<td class="text-right"></td>
					<td class="text-right"><?php echo to_money($jml_kpr);  ?></td>
					<td>KPA</td>
				</tr>
				<?php } ?>

				<tr>
					<td class="text-center" colspan="2">HARGA PEMESANAN</td>
					<td><?php $harga= $total+$tanda_jadi;echo to_money($harga);?></td>

				</tr>
			</table> 

		<!-- 	<div class="clear"><br></div> -->

			<table class="t-data w50" border="1">
				<tr>
					<th>NO. KWITANSI</th>
					<th>TGL. KUITANSI</th>
					<th>NILAI</th>
				</tr>

				<?php
				$query = "
				SELECT K.TANGGAL, R.NILAI, K.NOMOR_KWITANSI,K.VER_KEUANGAN AS KEU, K.BAYAR_VIA AS KETERANGAN, K.VER_COLLECTION_TANGGAL, K.VER_KEUANGAN_TANGGAL from REALISASI R JOIN KWITANSI K ON R.KODE_BLOK = K.KODE_BLOK 
				WHERE K.KODE_BLOK = '$kode_blok' AND K.KODE_BLOK = R.KODE_BLOK AND K.NOMOR_KWITANSI = R.NOMOR_KWITANSI
				ORDER BY TANGGAL
				";

				$obj = $conn->execute($query);
				$i = 1;
				$nilai = 0;
				while( ! $obj->EOF)
				{
					$bayar_via  = array('1' =>"Tunai",'2' =>"Giro / Cek",'3' =>"Bank O",'4' =>"Lain",'5' =>"Virtual Account" );
					$keterangan= $bayar_via[$obj->fields['KETERANGAN']];
					?>
					<tr> 
						<td><?php echo $obj->fields['NOMOR_KWITANSI']; ?></td>
						<td><?php echo tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL']))); ?></td>
						<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>
					</tr>
					<?php
					$obj->movenext();
				}	
				
				?>
			</table>
			<div class="clear"><br></div>

			<!-- REALISASI -->


			<?php 
			$obj_pembeli->movenext();
		}
	}

	?>
</body>
</html>
<?php
close($conn);
exit;
?>
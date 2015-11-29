<link type="text/css" href="../../../config/css/style.css" rel="stylesheet">
<?php
require_once('../../../config/config.php');
die_login();
//die_app('A01');
//die_mod('JB10');
$conn = conn($sess_db);
die_conn($conn);

// $per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$per_page	= 1000000;
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
<tr><td colspan="8" class="nb text-center"><b> LAPORAN PENERIMAAN ANGSURAN </b></td></tr>
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
	<th rowspan="2">TOTAL</th>
	
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

$filename = "Laporan Akutansi Periode " .kontgl(date("d M Y", strtotime($periode_awal))). " s.d " .kontgl(date("d M Y", strtotime($periode_akhir)).'.xls');

header("Content-type: application/msexcel");
header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
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

/* ============ T-DATA ============ */
.t-data{margin:0 auto;}
.t-data tr {background: #FFFFF2 url(../../images/content-bg.png) repeat scroll 0% 0%; }
.t-data tr:nth-of-type(odd) { 
	background: #BFCFFF ; 
}
.t-data tr:hover { color:#003040; background-color:#FFFF73;
	transition: all 0.1s ease 0s; }
.t-data tr:hover td { color:#003040; background-color:#FFFF73;
	transition: all 0.1s ease 0s;cursor:pointer; }

.t-data th {
	background: #007D9F;
	color: #FFFFF2; 
	font-weight: bold; 
	letter-spacing:1px;
	text-transform:uppercase;
	text-align:center;

	/* padding:6px 3px;
	border:1px solid #CCCCCC; */
}

/* .t-data td {
	color:#333333;
	padding:3px 4px;
	border:1px solid #CCCCCC;
} */

.t-data tfoot td {
	background:#AAAAAA;
	color:#333333;
	font-weight:bold;
	text-transform:uppercase;
	letter-spacing:1px;
	padding:3px 5px;
	border:1px solid #CCCCCC;
	text-align:right;
}

.f-left { float:left !important; }
.f-right { float:right !important; }
/* Width */
.wauto{width:auto;}
.w1{width:1%;}.w2{width:2%;}.w3{width:3%;}.w4{width:4%;}.w5{width:5%;}.w6{width:6%;}.w7{width:7%;}.w8{width:8%;}.w9{width:9%;}.w10{width:10%;}.w11{width:11%;}.w12{width:12%;}.w13{width:13%;}.w14{width:14%;}.w15{width:15%;}.w16{width:16%;}.w17{width:17%;}.w18{width:18%;}.w19{width:19%;}.w20{width:20%;}.w21{width:21%;}.w22{width:22%;}.w23{width:23%;}.w24{width:24%;}.w25{width:25%;}.w26{width:26%;}.w27{width:27%;}.w28{width:28%;}.w29{width:29%;}.w30{width:30%;}.w31{width:31%;}.w32{width:32%;}.w33{width:33%;}.w34{width:34%;}.w35{width:35%;}.w36{width:36%;}.w37{width:37%;}.w38{width:38%;}.w39{width:39%;}.w40{width:40%; margin:0 auto;}.w41{width:41%;}.w42{width:42%;}.w43{width:43%;}.w44{width:44%;}.w45{width:45%;}.w46{width:46%;}.w47{width:47%;}.w48{width:48%;}.w49{width:49%;}.w50{width:50%; margin:0 auto;}.w51{width:51%;}.w52{width:52%;}.w53{width:53%;}.w54{width:54%;}.w55{width:55%;}.w56{width:56%;}.w57{width:57%;}.w58{width:58%;}.w59{width:59%;}.w60{width:60%; margin:0 auto;}.w61{width:61%;}.w62{width:62%;}.w63{width:63%;}.w64{width:64%;}.w65{width:65%;}.w66{width:66%;}.w67{width:67%;}.w68{width:68%;}.w69{width:69%;}.w70{width:70%; margin:0 auto }.w71{width:71%;}.w72{width:72%;}.w73{width:73%;}.w74{width:74%;}.w75{width:75%;}.w76{width:76%;}.w77{width:77%;}.w78{width:78%;}.w79{width:79%;}.w80{width:80%;}.w81{width:81%;}.w82{width:82%;}.w83{width:83%;}.w84{width:84%;}.w85{width:85%;}.w86{width:86%;}.w87{width:87%;}.w88{width:88%;}.w89{width:89%;}.w90{width:90%; margin:0 auto;}.w91{width:91%;}.w92{width:92%;}.w93{width:93%;}.w94{width:94%;}.w95{width:95%;}.w96{width:96%;}.w97{width:97%;}.w98{width:98%;}.w99{width:99%;}.w100{width:100%;}
.wm100{min-width:100%;}
.wauto-center { width:auto !important; margin:0 auto !important;}

.text-left { text-align:left !important; }
.text-right { text-align:right !important; }
.text-center { text-align:center !important; }
.va-top {vertical-align:top !important; }
.va-bottom {vertical-align:bottom !important; }
.nowrap { white-space:nowrap !important; }

/* GLOBAL TABLE */


.f-left {
	float:left !important;
}
.f-right {
	float:right !important;
}

table{
	/* border: 1px solid #bdbdbd;  */
	border:none;
	background-color: #F1F1F1 ;
}
/* Zebra striping */

tr:nth-of-type(odd) { 
	background: #f5f5f5; 
	
}
th { 
	background: #333; 
	color: white; 
	font-weight: bold; 
}
td, th { 
	padding: 5px; 
	text-align: left; 
}

tr.hover:hover {
	background-color:#FFFF73;
	transition: all 0.1s ease 0s;
}
/* ============ T-CONTROL ============ */
.t-control {
	color:#444;
	margin:10px auto;
	background: #f5f5f5; 
	
}
.t-control2 {
	color:#FF9900;
	margin:10px 0;
}
.t-control label {
	
}


</style>
</head>
<body onload="window.print()">

<table class="data">

<?php

if ($total_data > 0)
{
	$query_pembeli ="SELECT * FROM SPP";
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
		<table>
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
		
		<table class="t-data w50 f-left" >
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

			<table class="t-data w50">
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
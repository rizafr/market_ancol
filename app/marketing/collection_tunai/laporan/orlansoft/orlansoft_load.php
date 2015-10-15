<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C39');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search = '';	

if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search .= "AND TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105)";
}

$query_blok_lunas = "SELECT C.KODE_BLOK FROM (SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM (
	SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from( 
	select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) AS NILAI from spp A where A.KODE_BLOK is not null
	UNION ALL
	SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL)a GROUP BY a.KODE_BLOK) A LEFT
	JOIN (
	SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B ON A.KODE_BLOK=B.KODE_BLOK
	where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0)C";

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
	LEFT JOIN RENCANA g ON g.KODE_BLOK = a.KODE_BLOK
	WHERE STATUS_KOMPENSASI IS NOT NULL
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
?>

<table id="pagging-1" class="t-control w60">
<tr>
	<td>
		<input type="button" id="excel" value=" Excel ">
		<input type="button" id="print" value=" Print ">
	</td>
	<td class="text-right">
		<input type="button" id="prev_page" value=" < ">
		Hal : <input type="text" name="page_num" size="5" class="page_num apply text-center" value="<?php echo $page_num; ?>">
		Dari <?php echo $total_page ?> 
		<input type="hidden" id="total_page" value="<?php echo $total_page; ?>">
		<input type="button" id="next_page" value=" > ">
	</td>
</tr>
</table>

<?php

if ($total_data > 0)
{
	//Start Edited by Kurniawan
	//Bandung 14 Oktober 2015
	$query = "
		SELECT 	a.NAMA_PEMBELI, a.KODE_BLOK, a.ALAMAT_RUMAH, a.NOMOR_CUSTOMER,
				a.ALAMAT_SURAT, a.NOMOR_SPP, a.TELP_RUMAH,
				a.TELP_KANTOR, a.TELP_LAIN, a.NO_IDENTITAS, a.TANGGAL_SPP,
				a.NPWP, a.STATUS_KOMPENSASI, a.TANDA_JADI,
				b.LUAS_TANAH, b.LUAS_BANGUNAN, b.DISC_TANAH, 
				b.DISC_BANGUNAN,
				b.PPN_TANAH, b.PPN_BANGUNAN,
				c.TIPE_BANGUNAN,
				d.HARGA_TANAH, 
				e.HARGA_BANGUNAN, 
				f.NILAI_TAMBAH, f.NILAI_KURANG,
				g.TANGGAL AS TANGGAL_RENCANA, g.NILAI AS NILAI_BAYAR
		FROM	SPP a 
				LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
				LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
				LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
				LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
				LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
				LEFT JOIN RENCANA g ON g.KODE_BLOK = a.KODE_BLOK
		WHERE 	STATUS_KOMPENSASI IS NOT NULL
		$query_search
	"; ?>

	<!-- <div style='overflow-x:auto;'> -->
	<table class="t-nowrap t-data wm100">
	<tr>
		<th>NO.</th>
		<th>NAMA KONSUMEN</th>
		<th>BLOK</th>
		<th>ALAMAT</th>
		<th>NO.TELP</th>
		<th>TANGGAL SPP</th>
		<th>TIPE</th>
		<th>SEMI GROSS</th>
		<th>NO. KTP</th>
		<th>NO. NPWP</th>
		<th>LANTAI</th>
		<th>NO. UNIT</th>
		<th>NO. VIRTUAL ACCOUNT</th>
		<th>POLA PEMBAYARAN</th>
		<th>HARGA INCLUDE PPN</th>
		<th>HARGA NET</th>
		<th>PPN 10%</th>
		<th>UANG PESANAN</th>
		<?php 

			$k = 1; 
			while ( $k <= 48) {
				# code...
				?>
				<th>TANGGAL <?php echo $k; ?></th>
				<?php
				$k++;
			}

		?>	
	</tr>

	<?php
	//End Edited

	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1;
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
		
		$total_harga_ppn	= ($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan);
		
		$TELP_KANTOR	=(trim($obj->fields["TELP_KANTOR"])!="")?trim(strtoupper($obj->fields["TELP_KANTOR"])):"";
		$TELP_LAIN		=(trim($obj->fields["TELP_LAIN"])!="")?",".trim(strtoupper($obj->fields["TELP_LAIN"])):"";
		$TELP_RUMAH		=(trim($obj->fields["TELP_RUMAH"])!="")?",".trim(strtoupper($obj->fields["TELP_RUMAH"])):"";
		$TELP			=$TELP_KANTOR.$TELP_LAIN.$TELP_RUMAH;
		
		$status_kompensasi	= $obj->fields['STATUS_KOMPENSASI'];

		//start edited kurniawan
		//Bandung 12 Oktober 2015
		$no_ktp			= $obj->fields['NO_IDENTITAS'];
		$no_npwp		= $obj->fields['NPWP'];		
		$blok			= explode("-", $id);
		$lantai			= $blok[0];
		$no_unit		= $blok[1];
		$no_va 			= $obj->fields['NOMOR_CUSTOMER'];
		$uang_pesanan	= $obj->fields['TANDA_JADI'];
		$tgl_rencana 	= $obj->fields['TANGGAL_RENCANA'];
		$nilai 			= $obj->fields['NILAI_BAYAR'];
		//end edited kurniawan
			
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td class="text-center"><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td><?php echo $obj->fields['ALAMAT_RUMAH']; ?></td>
			<td><?php echo $TELP; ?></td>
			<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SPP'])))); ?></td>
			<td><?php echo $obj->fields['TIPE_BANGUNAN']; ?></td>
			<td class="text-right"><?php echo $luas_bangunan; ?></td>
			<td class="text-center"><?php echo $no_ktp; ?></td>
			<td class="text-center"><?php echo $no_npwp; ?></td>
			<td class="text-center"><?php echo $lantai; ?></td>
			<td class="text-center"><?php echo $no_unit; ?></td>
			<td class="text-center"><?php echo $no_va; ?></td>
			<td class="text-center"></td>
			<td class="text-center"><?php echo number_format($total_harga_ppn); ?></td>
			<td class="text-center"><?php echo number_format($total_harga)?></td>
			<td class="text-center"><?php echo number_format($total_ppn); ?></td>
			<td class="text-center"><?php echo number_format($uang_pesanan); ?></td>
			<!-- <td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($tgl_rencana)))); ?></td> -->	
			<td class="text-center"><?php echo number_format($nilai)?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
} else { ?>
	<table class="t-nowrap t-data wm100">
	<tr>
		<th>NO.</th>
		<th>NAMA KONSUMEN</th>
		<th>BLOK</th>
		<th>ALAMAT</th>
		<th>NO.TELP</th>
		<th>TANGGAL SPP</th>
		<th>TIPE</th>
		<th>SEMI GROSS</th>
		<th>NO. KTP</th>
		<th>NO. NPWP</th>
		<th>LANTAI</th>
		<th>NO. UNIT</th>
		<th>NO. VIRTUAL ACCOUNT</th>
		<th>POLA PEMBAYARAN</th>
		<th>HARGA INCLUDE PPN</th>
		<th>HARGA NET</th>
		<th>PPN 10%</th>
		<th>UANG PESANAN</th>
		<th>TANGGAL</th>
	</tr>
<?php
}

?>
</table>
<!-- </div> -->

<table id="pagging-2" class="t-control w60"></table>

<script type="text/javascript">
jQuery(function($) {
	
	$('#pagging-2').html($('#pagging-1').html());	
	$('#total-data').html('<?php echo $total_data; ?>');
	$('#per_page').val('<?php echo $per_page; ?>');
	$('.page_num').inputmask('integer');
	t_strip('.t-data');
});
</script>

<?php
close($conn);
exit;
?>
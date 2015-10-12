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

$tanggal 			= f_tgl (date("Y-m-d"));
$pecah_tanggal		= explode("-",$tanggal);
$tgl 				= $pecah_tanggal[0];
$bln 				= $pecah_tanggal[1];
$thn 				= $pecah_tanggal[2];
$array_bulan 		= array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember'); 
	

$query_search = '';

if($field1 == 'all')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE a.TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND a.TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105)";
	}
}

if($field1 == 'kode_blok')
{
	$query_search .= "WHERE a.KODE_BLOK = '$search1'";

}

if($field1 == 'spp_distribusi')
{
	$query_search .= "WHERE a.TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND a.TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105) AND a.STATUS_SPP = '1'";

}

if($field1 == 'spp_belum')
{
	$query_search .= "WHERE a.TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND a.TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105) AND a.STATUS_SPP = '2'";

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

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
?>

<table id="pagging-1" class="t-control">
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

<table class="t-nowrap t-data wm100">
<tr>
	<th rowspan="2">NO.</th>
	<th rowspan="2">NAMA PEMBELI</th>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">TOTAL HARGA</th>
	<th rowspan="2">PEMBAYARAN s/d BULAN LALU</th>
	<th rowspan="2">PEMBAYARAN BULAN INI (s/d <?php echo $tanggal;?>)</th>
	<th colspan="3">SISA TAGIHAN</th>
	<th colspan="8">PROYEKSI PENAGIHAN</th>
</tr>
<tr>
	<th colspan="1">Sudah JT</th>
	<th colspan="1">Belum JT</th>
	<th colspan="1">Total</th>
	<th colspan="1">Bulan ini</th>
	<th colspan="1"><?php echo $array_bulan[$bln+1].' '.$thn;?></th>
	<th colspan="1"><?php echo $array_bulan[$bln+2].' '.$thn;?></th>
	<th colspan="1"><?php echo $array_bulan[$bln+3].' '.$thn;?></th>
	<th colspan="1"><?php echo $array_bulan[$bln+4].' '.$thn;?></th>
	<th colspan="1"><?php echo $array_bulan[$bln+5].' '.$thn;?></th>
	<th colspan="1"><?php echo $array_bulan[$bln+6].' '.$thn;?></th>
	<th colspan="1">Total</th>
</tr>

<?php
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
	
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	$sub_unit					= 1;
	$sub_total_harga 			= 0;
	$sub_pembayaran_lalu 		= 0;
	$sub_pembayaran_sekarang 	= 0;
	$sub_sudah_jt				= 0;
	$sub_belum_jt				= 0;
	$sub_total_jt				= 0;
	$sub_bulan_ini				= 0;
	$sub_bulan_lanjut			= 0;
	$sub_nilai_proyeksi[0]		= 0;
	$sub_nilai_proyeksi[1]		= 0;
	$sub_nilai_proyeksi[2]		= 0;
	$sub_nilai_proyeksi[3]		= 0;
	$sub_nilai_proyeksi[4]		= 0;
	$sub_nilai_proyeksi[5]		= 0;
	$sub_nilai_proyeksi[6]		= 0;
	$sub_total_proyeksi			= 0;
	

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
		
		$total_harga 		= to_money($total_tanah + $total_bangunan);
		$total_ppn			= to_money($ppn_tanah + $ppn_bangunan);
		
		$total_harga		= ($total_tanah + $total_bangunan) + ($ppn_tanah + $ppn_bangunan);	
		
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td class="text-center"><?php echo to_money($total_harga); ?></td>
			
			<?php $sub_total_harga 			= $sub_total_harga + $total_harga;?>
			
			<?php 
			$query2 = "
			SELECT TOTAL = CASE WHEN sum(nilai) IS null 
			THEN 0 ELSE sum(nilai) END
			FROM KWITANSI WHERE KODE_BLOK = '$id' AND TANGGAL_BAYAR < CONVERT(DATETIME,'01-$bln-$thn',105)
			";
			$obj2 = $conn->execute($query2);
			$pembayaran_lalu		= $obj2->fields['TOTAL'];
			$sub_pembayaran_lalu	= $sub_pembayaran_lalu + $pembayaran_lalu;
			?>
	
			<td class="text-center"><?php echo to_money($pembayaran_lalu); ?></td>
			
			<?php 
			$query2 = "
			SELECT TOTAL = CASE WHEN sum(nilai) IS null 
			THEN 0 ELSE sum(nilai) END
			FROM KWITANSI WHERE KODE_BLOK = '$id' AND TANGGAL_BAYAR >= CONVERT(DATETIME,'01-$bln-$thn',105) AND TANGGAL_BAYAR <= CONVERT(DATETIME,'$tanggal',105)
			";
			$obj2 = $conn->execute($query2);
			$pembayaran_sekarang	= $obj2->fields['TOTAL'];
			$sub_pembayaran_sekarang= $sub_pembayaran_sekarang + $pembayaran_sekarang;
			?>		
			
			<td class="text-center"><?php echo to_money($pembayaran_sekarang); ?></td>
			
			<?php 
			$query2 = "
			select TOTAL = CASE WHEN sum(nilai) IS null 
			THEN 0 ELSE sum(nilai) END
			from RENCANA where kode_blok = '$id' 
			AND TANGGAL < CONVERT(DATETIME,'$tanggal',105)";
			$obj2 = $conn->execute($query2);
			$sudah_jt				= $obj2->fields['TOTAL'];
			$sub_sudah_jt			= $sub_sudah_jt + $sudah_jt;
			?>		
			
			<td class="text-center"><?php echo to_money($sudah_jt); ?></td>
			
			<?php 
			$query2 = "
			select TOTAL = CASE WHEN sum(nilai) IS null 
			THEN 0 ELSE sum(nilai) END
			from RENCANA where kode_blok = '$id' 
			AND TANGGAL > CONVERT(DATETIME,'$tanggal',105)";
			$obj2 = $conn->execute($query2);
			$belum_jt				= $obj2->fields['TOTAL'];
			$sub_belum_jt			= $sub_belum_jt + $belum_jt;
			
			$total_jt				= $belum_jt+$sudah_jt;
			$sub_total_jt			= $sub_total_jt + $total_jt;
			?>		
			
			<td class="text-center"><?php echo to_money($belum_jt); ?></td>
			
			<td class="text-center"><?php echo to_money($total_jt); ?></td>
			
			<?php 
			
			$enam_bulan 	= $bln + 6;
			$tahun_lanjut	= $thn;
			if($enam_bulan > 12)
			{
				$enam_bulan 	= $enam_bulan % 12;
				$tahun_lanjut 	= $thn + 1; 
			}
			
			
			$query2 = "
			select * from RENCANA where kode_blok = '$id' 
			AND TANGGAL > CONVERT(DATETIME,'01-$bln-$thn',105) AND TANGGAL < CONVERT(DATETIME,'01-$enam_bulan-$tahun_lanjut',105)
			order BY TANGGAL";
			
			$obj2 = $conn->execute($query2);
			$isi 			= 1;
			$total_proyeksi = 0;
			
			while( ! $obj2->EOF)
			{
				$nilai_proyeksi		= $obj2->fields['NILAI'];
				$sub_nilai_proyeksi[$isi-1] = $sub_nilai_proyeksi[$isi-1] + $nilai_proyeksi;
								
				$total_proyeksi  	= $total_proyeksi + $nilai_proyeksi;
				$nilai_proyeksi		= (isset($nilai_proyeksi)) ? clean($nilai_proyeksi) : '0';
				?>
				<td class="text-center"><?php echo to_money($nilai_proyeksi); ?></td>
				
				<?php
				$obj2->movenext();
				$isi++;
			}
			
			while($isi < 8)
			{	
			?>
				<td class="text-center"><?php echo to_money('0'); ?></td>
				
				<?php
				$isi++;
			}
			?>
			
			<td class="text-center"><?php echo to_money($total_proyeksi); ?></td>
			<?php $sub_total_proyeksi			= $sub_total_proyeksi + $total_proyeksi; ?>
			
		</tr>
		<?php
		$sub_unit++;
		$i++;
		$obj->movenext();
	}	
	?>
	
	<tr> 
		<td class="text-center"></td>
		<td class="text-center"><b>SUB TOTAL</b></td>
		<td class="text-center"><b><?php echo ($sub_unit-1). ' unit'; ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_total_harga); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_pembayaran_lalu); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_pembayaran_sekarang); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_sudah_jt); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_belum_jt); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_total_jt); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_nilai_proyeksi[0]); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_nilai_proyeksi[1]); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_nilai_proyeksi[2]); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_nilai_proyeksi[3]); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_nilai_proyeksi[4]); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_nilai_proyeksi[5]); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_nilai_proyeksi[6]); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sub_total_proyeksi); ?></b></td>
		

	
<?php	
}
?>


</table>

<table id="pagging-2" class="t-control"></table>

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
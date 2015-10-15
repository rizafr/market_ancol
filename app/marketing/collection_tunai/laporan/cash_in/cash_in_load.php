<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C40');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';

//Edited by Kurniawan 15 Oktober 2015
$bulan_awal			= (isset($_REQUEST['bulan_awal'])) ? clean($_REQUEST['bulan_awal']) : '' ;
$bulan_akhir		= (isset($_REQUEST['bulan_akhir'])) ? clean($_REQUEST['bulan_akhir']) : '' ;

$tanggal1 			= $bulan_awal;
$pecah_tanggal1		= explode('-', $tanggal1);
$bln1				= $pecah_tanggal1[0];
$thn1				= $pecah_tanggal1[1];

$tanggal2 			= $bulan_akhir;
$pecah_tanggal2		= explode('-', $tanggal2);
$bln2				= $pecah_tanggal2[0];
$thn2				= $pecah_tanggal2[1];

$array_bulan 		= array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember'); 
$array_bulan[0]		= 'Desember';

$selisih = $bulan_akhir-$bulan_awal;

$a	= 0;
while($a < 12)
{
	$next_bulan 	= $bln2 + 1 + $a;
	$next_tahun		= $thn2;
	if($next_bulan > $selisih)
	{
		$next_bulan 	= $next_bulan % $selisih;
		$next_tahun 	= $thn2 + 1; 
	}
	
	$proyeksi_bulan[$a] = $array_bulan[$next_bulan].' '.$next_tahun;
	$a++;
}

$query_search = '';

if($field1 == 'all')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE a.TANGGAL_SPP >= CONVERT(DATETIME,'$periode_awal',105) AND a.TANGGAL_SPP <= CONVERT(DATETIME,'$periode_akhir',105)";
	}
}

if($field1 == 'harga_cash_keras')
{
	$query_search .= "WHERE a.POLA_PEMBAYARAN = 'HARGA_CASH_KERAS'";

}

if($field1 == 'kpa24x')
{
	$query_search .= "WHERE a.POLA_PEMBAYARAN = 'KPA24X'";

}

if($field1 == 'kpa36x')
{
	$query_search .= "WHERE a.POLA_PEMBAYARAN = 'KPA36X'";

}

if($field1 == 'cb36x')
{
	$query_search .= "WHERE a.POLA_PEMBAYARAN = 'CB36X'";

}

if($field1 == 'cb48x')
{
	$query_search .= "WHERE a.POLA_PEMBAYARAN = 'CB48X'";

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
	<th rowspan="2">POLA PEMBAYARAN</th>
</tr>
<tr>
	<th colspan="1">Bulan ini</th>
	<?php for($i=0; $i<12; $i++) { ?>
	<th colspan="1"><?php echo $proyeksi_bulan[$i];?></th>
	<?php }?>
	<th colspan="1">Total</th>
</tr>

<?php
if ($total_data > 0)
{
	$sum_nilai=0;
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
			<td><?php echo $field1; ?></td>
			<?php for($j=0; $j<=12; $j++) { 
				$nilai = 100000000;
				$sum_nilai += $nilai ;
				?>
			<td><?= $nilai ?></td>
			<?php }?>
			<td class="text-center"><?php echo to_money($sum_nilai); ?></td>
			<?php $sub_total_proyeksi			= $sub_total_proyeksi + $total_proyeksi; ?>
			
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}	
	?>
	
	<tr> 
		<td class="text-center"></td>
		<td class="text-center"><b>SUB TOTAL</b></td>
		<td class="text-center"><b><?php echo ($sub_unit-1). ' unit'; ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
		<td class="text-center"><b><?php echo to_money($sum_nilai); ?></b></td>
	</tr>
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
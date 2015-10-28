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

function monthDif($bulan_awal, $bulan_akhir){

	$splitStart = explode('-', $bulan_awal);
	$splitEnd = explode('-', $bulan_akhir);

	if (is_array($splitStart) && is_array($splitEnd)) {
		$startYear = $splitStart[1];
		$startMonth = $splitStart[0];
		$endYear = $splitEnd[1];
		$endMonth = $splitEnd[0];

		$difYear = $endYear - $startYear;
		$difMonth = $endMonth - $startMonth;

		if (0 == $difYear && 0 == $difMonth) {
			return 0;
		} else if (0 == $difYear && $difMonth > 0){
			return $difMonth;
		} else if (1 == $difYear){
			$startToEnd = 13 - $startMonth;
			return ($startToEnd + $endMonth);
		} else if ($difYear > 1){
			$startToEnd = 13 - $startMonth; // months remaining in start year 
	        $yearsRemaining = $difYear - 2;  // minus the years of the start and the end year
	        $remainingMonths = 12 * $yearsRemaining; // tally up remaining months
	        $totalMonths = $startToEnd + $remainingMonths + $endMonth; // Monthsleft + full years in between + months of last year
	        return $totalMonths;
		}
	} else {
		return "false";
	}
}

$selisih = monthDif($bulan_awal,$bulan_akhir);

// Untuk Tahun
$splitStart = explode('-', $bulan_awal);
$splitEnd = explode('-', $bulan_akhir);

$startYear = $splitStart[1];
$startMonth = $splitStart[0];
$endYear = $splitEnd[1];
$endMonth = $splitEnd[0];

$difYear = $endYear - $startYear;
$difMonth = $endMonth - $startMonth;

$array_bulan 		= array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember'); 
$array_bulan_reverse 		= array('Januari'=>1,'Februari'=>2,'Maret'=>3, 'April'=>4, 'Mei'=>5, 'Juni'=>6,'Juli'=>7,'Agustus'=>8,'September'=>9,'Oktober'=>10, 'November'=>11,'Desember'=>12); 
$array_bulan[0]		= 'Desember';

$a	= 0;
while($a <= $selisih)
{
	$next_bulan 	= $startMonth + $a;
	$next_tahun		= $startYear;
	if($next_bulan > 12)
	{
		$next_bulan 	= $next_bulan % 12;
		$next_tahun 	= $startYear + 1; 
	}
	
	$proyeksi_bulan[$a] = $array_bulan[$next_bulan].' '.$next_tahun;
	$a++;
}

$query_search = '';

if($field1 == 'all')
{
	if ($bulan_awal <> '' || $bulan_akhir <> '')
	{
		$query_search .= "AND (MONTH(R.TANGGAL) >= $startMonth AND MONTH(R.TANGGAL) <= $endMonth AND YEAR(R.TANGGAL) >= $startYear AND YEAR(R.TANGGAL) <= $endYear)";
	}
}

if($field1 == 'harga_cash_keras')
{
	$query_search .= "AND (MONTH(R.TANGGAL) >= $startMonth AND MONTH(R.TANGGAL) <= $endMonth AND YEAR(R.TANGGAL) >= $startYear AND YEAR(R.TANGGAL) <= $endYear) AND S.POLA_BAYAR = 'HARGA_CASH_KERAS'";
}

if($field1 == 'kpa24x')
{
	$query_search .= "AND (MONTH(R.TANGGAL) >= $startMonth AND MONTH(R.TANGGAL) <= $endMonth AND YEAR(R.TANGGAL) >= $startYear AND YEAR(R.TANGGAL) <= $endYear) AND S.POLA_BAYAR = 'KPA24X'";
}

if($field1 == 'kpa36x')
{
	$query_search .= "AND (MONTH(R.TANGGAL) >= $startMonth AND MONTH(R.TANGGAL) <= $endMonth AND YEAR(R.TANGGAL) >= $startYear AND YEAR(R.TANGGAL) <= $endYear) AND S.POLA_BAYAR = 'KPA36X'";
}

if($field1 == 'cb36x')
{
	$query_search .= "AND (MONTH(R.TANGGAL) >= $startMonth AND MONTH(R.TANGGAL) <= $endMonth AND YEAR(R.TANGGAL) >= $startYear AND YEAR(R.TANGGAL) <= $endYear AND S.POLA_BAYAR = 'CB36X')";
}

if($field1 == 'cb48x')
{
	$query_search .= "AND (MONTH(R.TANGGAL) >= $startMonth AND MONTH(R.TANGGAL) <= $endMonth AND YEAR(R.TANGGAL) >= $startYear AND YEAR(R.TANGGAL) <= $endYear) AND S.POLA_BAYAR = 'CB48X'";

}

/* Pagination */

$query = "
	SELECT S.POLA_BAYAR, SUM(R.NILAI) AS JUMLAH, DATENAME(MONTH,R.TANGGAL) AS BULAN, MONTH(R.TANGGAL) AS ANGKA_BULAN, DATENAME(YEAR,R.TANGGAL) AS TAHUN, YEAR(R.TANGGAL) AS ANGKA_TAHUN
	FROM REALISASI R, SPP S 
	WHERE 
	R.KODE_BLOK = S.KODE_BLOK AND
	S.POLA_BAYAR IS NOT NULL
	$query_search
	GROUP BY S.POLA_BAYAR, DATENAME(MONTH,R.TANGGAL), DATENAME(YEAR,R.TANGGAL), MONTH(R.TANGGAL), YEAR(R.TANGGAL)
	";

	$total_data = $conn->Execute($query);
	$total_data = $total_data->RecordCount();
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

<?php 
$data_cash_in = array();
if ($total_data > 0)
{	
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$index = 0;
	while( ! $obj->EOF)
	{
		$data_cash_in[$index] = new stdClass();
		$data_cash_in[$index]->POLA_BAYAR = $obj->fields['POLA_BAYAR'];
		$data_cash_in[$index]->BULAN = array();
		for($i=0; $i<=$selisih; $i++) { 
			list($bulan,$tahun) = explode(' ',$proyeksi_bulan[$i]);
			$angka_bulan = $array_bulan_reverse[$bulan];
			if(($angka_bulan.' '.$tahun)==($obj->fields['ANGKA_BULAN'].' '.$obj->fields['TAHUN'])){
				$data_cash_in[$index]->BULAN[$i] = $obj->fields['JUMLAH'];
			}else{
				$data_cash_in[$index]->BULAN[$i] = '-';
			}
		}
		$index++;
		$obj->movenext();
	}
}
?>

<table class="t-nowrap t-data wm100">
<tr>
	<th>NO.</th>
	<th>POLA PEMBAYARAN</th>
	<?php 
		for($i=0; $i<=$selisih; $i++) { 

		?>
	<th><?php echo $proyeksi_bulan[$i];?></th>
	<?php }?>
	<th>Total</th>
</tr>

<?php

if ($total_data > 0)
{
	$sum_nilai=array();
	$sum_kolom = array();
	
	$i = 1 + $page_start;
	$sub_unit					= 1;
	$sub_total_harga 			= 0;
	$sub_bulan_ini				= 0;
	$sub_bulan_lanjut			= 0;
	$sub_nilai_proyeksi[0]		= 0;
	$sub_total_proyeksi			= 0;

	foreach($data_cash_in as $index=>$data){
	$sum_baris = 0;

		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $data->POLA_BAYAR; ?></td>

			<?php foreach($data->BULAN as $key=>$jumlah) { 
				$nilai = ($jumlah!='')?$jumlah:0;
				$sum_kolom[$index][$key] = ($jumlah!='-')?$jumlah:0;
				$sum_baris += $nilai ;
				?>
			<td class="text-center"><?= ($jumlah!='-')?to_money($jumlah):'-'; ?></td>
			<?php }
			array_push($sum_nilai,$sum_baris);
			?>
			
			<td class="text-center"><?php echo to_money($sum_baris); ?></td>
		</tr>
		<?php
		$i++;
	}
	?>
	
	<tr> 
		<td class="text-center"></td>
		<td class="text-center"><b>TOTAL</b></td>
		<?php 

			for($k=0; $k<=$selisih; $k++){
				$sum_total_kolom = 0;
				foreach($sum_kolom as $key=>$val){
					$sum_total_kolom+= $val[$k];
				}
				?>
				<td class="text-center"><b><?php echo to_money($sum_total_kolom); ?></b></td>
			<?php }
		?>
		<td class="text-center"><b><?php echo to_money(array_sum($sum_nilai)); ?></b></td>
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
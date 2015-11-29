<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C27');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$periode			= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1			= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$tanggal_awl		= explode("-",$periode);
$bln_awl			= $tanggal_awl[0];
$thn_awl			= $tanggal_awl[1];
if ($bln_awl==2)
$periode_awal		= '28-'.$periode;
else
$periode_awal		= '30-'.$periode;

$tanggal 			= f_tgl (date("Y-m-d"));
$tanggal_skg		= explode("-",$tanggal);
$tgl_skg			= $tanggal_skg[0];
$bln_skg			= $tanggal_skg[1];
$thn_skg			= $tanggal_skg[2];
	

$query_search = '';

if ($search1 != '')
{
	$query_search .= " WHERE $field1 LIKE '%$search1%' ";
}


/* Pagination */

$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM
	SPP
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
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">NAMA PEMBELI</th>
	<th rowspan="2">TOTAL TRANSAKSI</th>
	<th rowspan="2">TOTAL BELUM JATUH TEMPO</th>
	<th rowspan="2">TOTAL SUDAH JATUH TEMPO</th>
	<th rowspan="2">PEMBAYARAN</th>
	<th rowspan="2">PIUTANG</th>
	<th colspan="7">UMUR TAGIHAN SUDAH JATUH TEMPO</th>
	<th rowspan="2">CARA PEMBAYARAN</th>
	<th rowspan="2">CATATAN</th>
</tr>
<tr>
	<th colspan="1">0 to 30 Hari</th>
	<th colspan="1">30 to 60 Hari</th>
	<th colspan="1">60 to 90 Hari</th>
	<th colspan="1">90 to 120 Hari</th>
	<th colspan="1">120 to 360 Hari</th>
	<th colspan="1">360 to 720 Hari</th>
	<th colspan="1">OV 720 Hari</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT KODE_BLOK,NAMA_PEMBELI,HARGA_TOTAL,TANDA_JADI,(SELECT SUM(NILAI) FROM RENCANA WHERE TANGGAL > CONVERT(DATETIME,'$periode_awal',105)) AS BELUM_JATUH_TEMPO,
		(SELECT SUM(NILAI) FROM RENCANA WHERE TANGGAL <= CONVERT(DATETIME,'$periode_awal',105)) AS JATUH_TEMPO,
		(SELECT SUM(NILAI) FROM REALISASI WHERE TANGGAL <= CONVERT(DATETIME,'$periode_awal',105) AND KODE_BAYAR = '4' OR KODE_BAYAR = '5') AS BAYARAN
	FROM SPP 
	$query_search
	ORDER BY KODE_BLOK ASC
	";
	
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	
	$cek_piutang = 0;
	$nilai_piutang = 0;
	while( ! $obj->EOF)
	{
		$id 				= $obj->fields['KODE_BLOK'];		
		$total_harga 		= $obj->fields['HARGA_TOTAL'];
		$tanda_jadi 		= $obj->fields['TANDA_JADI'];
		$belum_jatuh_tempo	= $obj->fields['BELUM_JATUH_TEMPO'];
		$jatuh_tempo		= $obj->fields['JATUH_TEMPO'];
		$pembayaran			= $obj->fields['BAYARAN'];
		$piutang			= $jatuh_tempo-$pembayaran;
		?>
			<tr class="onclick" id="<?php echo $id; ?>"> 
				
				<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
				<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
				<td class="text-center"><?php echo to_money($total_harga); ?></td>
				<td class="text-center"><?php echo to_money($belum_jatuh_tempo); ?></td>
				<td class="text-center"><?php echo to_money($jatuh_tempo); ?></td>
				<td class="text-center"><?php echo to_money($pembayaran); ?></td>
				<td class="text-center"><?php echo to_money($piutang); ?></td>
			
			
			<?php
			if($piutang > 0){
				$counter = 0;
				while($counter < 4){
					$bln = $bln_awl-$counter;
					if($bln<1){
						$bln = 12+$bln;
						$thn = $thn_awl--;
					}else{
						$bln = $bln_awl-$counter;
						$thn = $thn_awl;
					}
					$query1 = "SELECT SUM(NILAI) AS NILAI_RENCANA FROM RENCANA WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn";					
					$obj1 	= $conn->execute($query1);
				
					$query2 = "SELECT SUM(NILAI) AS NILAI_REALISASI FROM REALISASI WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";					
					$obj2 	= $conn->execute($query2);
					
					$nilai_rencana = $obj1->fields['NILAI_RENCANA'];
					$nilai_realisasi = $obj2->fields['NILAI_REALISASI'];
					
					$cek_piutang = $nilai_rencana-$nilai_realisasi;
					$piutang = $piutang-$cek_piutang;
					if($piutang>=0)
						$nilai_piutang = $cek_piutang;
					else
						$nilai_piutang = 0;
			?>
			
				<td class="text-center"><?php echo to_money($nilai_piutang); ?></td>
			
			<?php
					$counter++;
				}
				
				$query3 = "SELECT SUM(NILAI) AS NILAI_REALISASI FROM REALISASI WHERE KODE_BLOK = '$id' AND TANGGAL >= DATEADD(MONTH,-11,CONVERT(DATETIME,'$periode_awal',105)) AND TANGGAL < DATEADD(MONTH,-4,CONVERT(DATETIME,'$periode_awal',105)) AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";					
				$obj3 	= $conn->execute($query3);
				$nilai_realisasi = $obj3->fields['NILAI_REALISASI'];
				$query4 = "SELECT SUM(NILAI) AS NILAI_RENCANA FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL >= DATEADD(MONTH,-11,CONVERT(DATETIME,'$periode_awal',105)) AND TANGGAL < DATEADD(MONTH,-4,CONVERT(DATETIME,'$periode_awal',105))";					
				$obj4 	= $conn->execute($query4);
				$nilai_rencana = $obj4->fields['NILAI_RENCANA'];
				$cek_piutang = $nilai_rencana - $nilai_realisasi;
				$piutang = $piutang-$cek_piutang;
				if($piutang>=0)
					$nilai_piutang = $cek_piutang;
				else
					$nilai_piutang = 0;
			?>
			
				<td class="text-center"><?php echo to_money($nilai_piutang); ?></td>
			
			<?php
				$query5 = "SELECT SUM(NILAI) AS NILAI_REALISASI FROM REALISASI WHERE KODE_BLOK = '$id' AND TANGGAL >= DATEADD(MONTH,-23,CONVERT(DATETIME,'$periode_awal',105)) AND TANGGAL <= DATEADD(MONTH,-12,CONVERT(DATETIME,'$periode_awal',105)) AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";					
				$obj5 	= $conn->execute($query5);
				$nilai_realisasi = $obj5->fields['NILAI_REALISASI'];
				$query6 = "SELECT SUM(NILAI) AS NILAI_RENCANA FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL >= DATEADD(MONTH,-23,CONVERT(DATETIME,'$periode_awal',105)) AND TANGGAL <= DATEADD(MONTH,-12,CONVERT(DATETIME,'$periode_awal',105))";					
				$obj6 	= $conn->execute($query6);
				$nilai_rencana = $obj6->fields['NILAI_RENCANA'];
				$cek_piutang = $nilai_rencana - $nilai_realisasi;
				$piutang = $piutang-$cek_piutang;
				if($piutang>=0)
					$nilai_piutang = $cek_piutang;
				else
					$nilai_piutang = 0;
			?>
				<td class="text-center"><?php echo to_money($nilai_piutang); ?></td>
			<?php
				$query7 = "SELECT SUM(NILAI) AS NILAI_REALISASI FROM REALISASI WHERE KODE_BLOK = '$id' AND TANGGAL <= DATEADD(MONTH,-24,CONVERT(DATETIME,'$periode_awal',105)) AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";					
				$obj7 	= $conn->execute($query7);
				$nilai_realisasi = $obj7->fields['NILAI_REALISASI'];
				$query8 = "SELECT SUM(NILAI) AS NILAI_RENCANA FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL <= DATEADD(MONTH,-24,CONVERT(DATETIME,'$periode_awal',105))";					
				$obj8 	= $conn->execute($query8);
				$nilai_rencana = $obj8->fields['NILAI_RENCANA'];
				$cek_piutang = $nilai_rencana - $nilai_realisasi;
				$piutang = $piutang-$cek_piutang;
				if($piutang>=0)
					$nilai_piutang = $cek_piutang;
				else
					$nilai_piutang = 0;
			?>
				<td class="text-center"><?php echo to_money($nilai_piutang); ?></td>
			<?php
			}else{
			?>
				<td class="text-center"><?php echo '-'; ?></td>	
				<td class="text-center"><?php echo '-'; ?></td>	
				<td class="text-center"><?php echo '-'; ?></td>
				<td class="text-center"><?php echo '-'; ?></td>
				<td class="text-center"><?php echo '-'; ?></td>
				<td class="text-center"><?php echo '-'; ?></td>
				<td class="text-center"><?php echo '-'; ?></td>
			
			<?php	
			}
			
			?>
			</tr>
		<?php
		$i++;
		$obj->movenext();
	}	
	?>
	
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
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

$query_blok_lunas = "SELECT C.KODE_BLOK FROM ( SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM 
( SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from ( select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) 
AS NILAI from spp A where A.KODE_BLOK is not null UNION ALL SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL
)a GROUP BY a.KODE_BLOK ) A LEFT JOIN (SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B 
ON A.KODE_BLOK=B.KODE_BLOK where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0 )C LEFT JOIN SPP D ON C.KODE_BLOK = D.KODE_BLOK
";

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
	<th rowspan="2">DENDA</th>
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
	SELECT KODE_BLOK,NAMA_PEMBELI,HARGA_TOTAL,TANDA_JADI
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
		$nama 				= $obj->fields['NAMA_PEMBELI']; 		
		$total_harga 		= $obj->fields['HARGA_TOTAL'];
		$tanda_jadi 		= $obj->fields['TANDA_JADI'];
	
		$query_jatuh_tempo = "SELECT SUM (NILAI) AS JATUH_TEMPO FROM RENCANA WHERE TANGGAL <= CONVERT(DATETIME,'$periode_awal',105) AND KODE_BLOK = '$id'";
		$query_belum_jatuh_tempo = "SELECT SUM (NILAI) AS BELUM_JATUH_TEMPO FROM RENCANA WHERE TANGGAL > CONVERT(DATETIME,'$periode_awal',105) AND KODE_BLOK = '$id'";
		$query_bayaran = "SELECT SUM (NILAI) AS BAYARAN FROM REALISASI WHERE TANGGAL <= CONVERT(DATETIME,'$periode_awal',105) AND KODE_BLOK = '$id' AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";
		$obj_jatuh_tempo 	= $conn->execute($query_jatuh_tempo);
		$obj_belum_jt	 	= $conn->execute($query_belum_jatuh_tempo);
		$obj_bayaran	 	= $conn->execute($query_bayaran);
		
		$belum_jatuh_tempo	= $obj_belum_jt ->fields['BELUM_JATUH_TEMPO'];
		$jatuh_tempo		= $obj_jatuh_tempo ->fields['JATUH_TEMPO'];
		$pembayaran			= $obj_bayaran ->fields['BAYARAN'];
		$piutang			= $jatuh_tempo-$pembayaran;
		?>
			<tr> 				
				<td><?php echo $id ?></td>
				<td><?php echo $nama ?></td>
				<td class="text-center"><?php echo to_money($total_harga-$tanda_jadi); ?></td>
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
						$thn = $thn_awl-1;
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
					if($piutang>=0)
						$nilai_piutang = $cek_piutang;
					else
						$nilai_piutang = 0;
						
					$piutang = $piutang-$cek_piutang;
					
			?>
			
				<td class="text-center"><?php echo ($nilai_piutang == 0) ? '-':to_money($nilai_piutang) ?></td>
			
			<?php
					$counter++;
				}
				
				$query3 = "SELECT SUM(NILAI) AS NILAI_REALISASI FROM REALISASI WHERE KODE_BLOK = '$id' AND TANGGAL <= DATEADD(MONTH,-4,CONVERT(DATETIME,'$periode_awal',105)) AND TANGGAL > DATEADD(MONTH,-12,CONVERT(DATETIME,'$periode_awal',105)) AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";					
				$obj3 	= $conn->execute($query3);
				$nilai_realisasi = $obj3->fields['NILAI_REALISASI'];
				$query4 = "SELECT SUM(NILAI) AS NILAI_RENCANA FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL <= DATEADD(MONTH,-4,CONVERT(DATETIME,'$periode_awal',105)) AND TANGGAL > DATEADD(MONTH,-12,CONVERT(DATETIME,'$periode_awal',105))";					
				$obj4 	= $conn->execute($query4);
				$nilai_rencana = $obj4->fields['NILAI_RENCANA'];
				$cek_piutang = $nilai_rencana - $nilai_realisasi;
				if($piutang>=0)
					$nilai_piutang = $cek_piutang;
				else
					$nilai_piutang = 0;
				$piutang = $piutang-$cek_piutang;
			?>
			
				<td class="text-center"><?php echo ($nilai_piutang == 0) ? '-':to_money($nilai_piutang); ?></td>
			
			<?php
				$query5 = "SELECT SUM(NILAI) AS NILAI_REALISASI FROM REALISASI WHERE KODE_BLOK = '$id' AND TANGGAL <= DATEADD(MONTH,-12,CONVERT(DATETIME,'$periode_awal',105)) AND TANGGAL > DATEADD(MONTH,-24,CONVERT(DATETIME,'$periode_awal',105)) AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";					
				$obj5 	= $conn->execute($query5);
				$nilai_realisasi = $obj5->fields['NILAI_REALISASI'];
				$query6 = "SELECT SUM(NILAI) AS NILAI_RENCANA FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL <= DATEADD(MONTH,-12,CONVERT(DATETIME,'$periode_awal',105)) AND TANGGAL > DATEADD(MONTH,-24,CONVERT(DATETIME,'$periode_awal',105))";					
				$obj6 	= $conn->execute($query6);
				$nilai_rencana = $obj6->fields['NILAI_RENCANA'];
				$cek_piutang = $nilai_rencana - $nilai_realisasi;
				if($piutang>=0)
					$nilai_piutang = $cek_piutang;
				else
					$nilai_piutang = 0;
				$piutang = $piutang-$cek_piutang;
			?>
				<td class="text-center"><?php echo ($nilai_piutang == 0) ? '-':to_money($nilai_piutang) ?></td>
			<?php
				$query7 = "SELECT SUM(NILAI) AS NILAI_REALISASI FROM REALISASI WHERE KODE_BLOK = '$id' AND TANGGAL <= DATEADD(MONTH,-24,CONVERT(DATETIME,'$periode_awal',105)) AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";					
				$obj7 	= $conn->execute($query7);
				$nilai_realisasi = $obj7->fields['NILAI_REALISASI'];
				$query8 = "SELECT SUM(NILAI) AS NILAI_RENCANA FROM RENCANA WHERE KODE_BLOK = '$id' AND TANGGAL <= DATEADD(MONTH,-24,CONVERT(DATETIME,'$periode_awal',105))";					
				$obj8 	= $conn->execute($query8);
				$nilai_rencana = $obj8->fields['NILAI_RENCANA'];
				$cek_piutang = $nilai_rencana - $nilai_realisasi;
				if($piutang>=0)
					$nilai_piutang = $cek_piutang;
				else
					$nilai_piutang = 0;
				$piutang = $piutang-$cek_piutang;
			?>
				<td class="text-center"><?php echo ($nilai_piutang == 0) ? '-':to_money($nilai_piutang) ?></td>
			<?php
			
				$query_countR = "SELECT COUNT(*) AS TRENCANA FROM RENCANA WHERE TANGGAL <= CONVERT(DATETIME,'$periode_awal',105) AND KODE_BLOK = '$id'";
				$obj_countR = $conn->execute($query_countR);
				$Trencana = $obj_countR->fields['TRENCANA'];
					$bln=0; $thn=0; 
					$nilai_denda = 0;
					for($counterR=0;$counterR<48;$counterR++){
						$bln = $bln_awl-$counterR;	//cek mundur bulan 
						if($bln<1){					//cek jika pindah tahun
							$b = ($bln*-1);
							$cB = $b%12;
							$bln = 12-$cB;
							$cT = ceil($counterR/12);
							$thn = $thn_awl-$cT;
						}else{						//jika tidak pindah tahun
							$bln = $bln_awl-$counterR;
							$thn = $thn_awl;
						}
						//ambil nilai rencana
						$query_cekPlan = "SELECT NILAI AS CURRENT_PLAN,TANGGAL AS TANGGAL_PLAN FROM RENCANA WHERE MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn AND KODE_BLOK = '$id'";
						$obj_cekPlan = $conn->execute($query_cekPlan);
						$nilaiPlan = $obj_cekPlan->fields['CURRENT_PLAN'];
						$tanggalPlan = $obj_cekPlan->fields['TANGGAL_PLAN'];
						
						//ambil nilai realisasi
						$query_cekReal = "SELECT NILAI AS CURRENT_REAL FROM REALISASI WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn AND KODE_BAYAR = '4' OR KODE_BAYAR = '5'";
						$obj_cekReal = $conn->execute($query_cekReal);
						$nilaiReal = $obj_cekReal->fields['CURRENT_REAL'];
						
						//cek selisih rencana-realisasi
						$selisih = $nilaiPlan-$nilaiReal;
						if($selisih>0){
							//cek grace periode
							$query_cekGraceP = "SELECT DATEDIFF(DAY, (DATEADD(DAY,(SELECT MASA_BERLAKU_DENDA FROM CS_PARAMETER_COL),'$tanggalPlan')),(SELECT GETDATE())) as TOTAL_HARI";
							$obj_cekGraceP = $conn->execute($query_cekGraceP);
							$total_hari = $obj_cekGraceP->fields['TOTAL_HARI'];
							if($total_hari>0){ 		//cek denda
								$denda = $selisih*$total_hari*0.001;
							}	
						}
						$nilai_denda = $nilai_denda + $denda;
					}
			?>
				<td class="text-center"><?php echo ($nilai_denda == 0) ? '-':to_money($nilai_denda) ?></td>
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
				<td class="text-center"><?php echo '-'; ?></td>
			
			<?php	
			}
			
			$query_kwitansi = "SELECT TOP 1 BAYAR_VIA,CATATAN_KWT FROM KWITANSI WHERE KODE_BLOK = '$id'";
			$obj_kwt 	= $conn->execute($query_kwitansi);
			
			$bayar_via  = array('1' =>"Tunai",'2' =>"Giro / Cek",'3' =>"Bank O",'4' =>"Lain",'5' =>"Virtual Account" );
			$bayar_via = $bayar_via[$obj_kwt->fields['BAYAR_VIA']];
			$catatan = $obj_kwt->fields['CATATAN_KWT'];			
			?>
				<td class="text-center"><?php echo (empty($bayar_via)) ? '-': $bayar_via ?></td>
				<td class="text-center"><?php echo (empty($catatan)) ? '-': $catatan ?></td>
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
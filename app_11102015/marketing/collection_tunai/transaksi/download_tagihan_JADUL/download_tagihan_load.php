<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C04');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$search		= (isset($_REQUEST['no_va'])) ? clean($_REQUEST['no_va']) : '';

$status_distribusi	= (isset($_REQUEST['status_distribusi'])) ? clean($_REQUEST['status_distribusi']) : '';
$bulan				= (isset($_REQUEST['bulan'])) ? clean($_REQUEST['bulan']) : '';

$tanggal 			= $bulan;
$pecah_tanggal		= explode("-",$tanggal);
$bln 				= $pecah_tanggal[0];
$thn 				= $pecah_tanggal[1];

//bulan depan
$next_bln	= $bln + 1;
$next_thn	= $thn;
if($bln > 12)
{
	$next_bln	= 1;
	$next_thn	= $thn + 1;
}

//bulan kemarin
$last_bln	= $bln - 1;
$last_thn	= $thn;
if($bln == 1)
{
	$last_bln	= 12;
	$last_thn	= $thn - 1;
}

//bulan kemarin kemarin
$last2_bln	= $last_bln - 1;
$last2_thn	= $last_thn;
if($last_bln == 1)
{
	$last2_bln	= 12;
	$last2_thn	= $last_thn - 1;
}

//bulan kemarin kemarin kemarin
$last3_bln	= $last2_bln - 1;
$last3_thn	= $last2_thn;
if($last2_bln == 1)
{
	$last3_bln	= 12;
	$last3_thn	= $last2_thn - 1;
}

$query_search = '';
if ($status_distribusi == 1)
{
	$query_search .= "AND STATUS_SPP = 1 ";
}
else if ($status_distribusi == 0)
{
	$query_search .= "AND STATUS_SPP != 1 ";
}
	
$query_blok_lunas = "SELECT C.KODE_BLOK FROM ( SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM 
( SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from ( select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) 
AS NILAI from spp A where A.KODE_BLOK is not null UNION ALL SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL
)a GROUP BY a.KODE_BLOK ) A LEFT JOIN (SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B 
ON A.KODE_BLOK=B.KODE_BLOK where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0 )C LEFT JOIN SPP D ON C.KODE_BLOK = D.KODE_BLOK
WHERE C.REMAIN - (ISNULL(D.NILAI_CAIR_KPR,0)) >= 0
";


# Pagination
$query = "
select count(*) as TOTAL
from RENCANA a LEFT JOIN SPP b
ON a.KODE_BLOK = b.KODE_BLOK
where TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105) 
AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)	
AND STATUS_WANPRESTASI IS NULL
AND a.KODE_BLOK NOT IN ($query_blok_lunas)
$query_search	
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w50">
<tr>
	<td class="text-right">
		<input type="button" id="prev_page" value=" < ">
		Hal : <input type="text" name="page_num" size="5" class="page_num apply text-center" value="<?php echo $page_num; ?>">
		Dari <?php echo $total_page ?> 
		<input type="hidden" id="total_page" value="<?php echo $total_page; ?>">
		<input type="button" id="next_page" value=" > ">
	</td>
</tr>
</table>

<table class="t-data w100">
<tr>
	<th class="w10">KODE BLOK</th>
	<th class="w10">NO PELANGGAN</th>
	<th class="w20">NAMA PELANGGAN</th>
	<th class="w15">ANGSURAN</th>
	<th class="w15">DENDA</th>
	<th class="w15">LAIN-LAIN</th>
	<th class="w15">TOTAL</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT a.KODE_BLOK, b.NAMA_PEMBELI, a.NILAI, a.TANGGAL, ISNULL(b.NOMOR_CUSTOMER,'-') AS NO_CUSTOMER
	from RENCANA a LEFT JOIN SPP b
	ON a.KODE_BLOK = b.KODE_BLOK
	where TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105) 
	AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
	AND STATUS_WANPRESTASI IS NULL
	AND a.KODE_BLOK NOT IN ($query_blok_lunas)
	$query_search
	order BY a.KODE_BLOK

	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);

	while( ! $obj->EOF)
	{
		$id 			= $obj->fields['KODE_BLOK'];
		$total_rencana 	= 0;
		$total_denda	= 0;
		$total_lain		= 0;

		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td class="text-center"><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td class="text-center"><?php echo $obj->fields['NO_CUSTOMER']; ?></td>
			<td class="text-left"><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			
			<?php			
			
			//cek bulan ini merupakan rencana pertama atau bukan
			$query2 = "
			SELECT COUNT(*) AS TOTAL
			FROM RENCANA WHERE KODE_BLOK = '$id' 
			AND TANGGAL < CONVERT(DATETIME,'01-$bln-$thn',105)
			";
			$obj2 			= $conn->execute($query2);				
			$n_rencana		= $obj2->fields['TOTAL'];
			
			//jika bulan ini merupakan rancana pertama
			if($n_rencana == 0)
			{
				//tagihan bulan ini
				$query2 = "
				SELECT *
				FROM RENCANA WHERE KODE_BLOK = '$id' 
				AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
				AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
				";
				$obj2 				= $conn->execute($query2);				
				$rencana_sekarang	= $obj2->fields['NILAI'];
				
				$total_rencana 		= $total_rencana + $rencana_sekarang;
			}
			else
			{
				//pengecekkan, apakah bulan kemarin ada pembayaran atau tidak
				$query2 = "
				SELECT COUNT(*) AS TOTAL
				FROM REALISASI WHERE KODE_BLOK = '$id' 
				AND TANGGAL >= CONVERT(DATETIME,'01-$last_bln-$last_thn',105)
				AND TANGGAL < CONVERT(DATETIME,'01-$bln-$thn',105)
				";
				$obj2 			= $conn->execute($query2);
				$bayar			= $obj2->fields['TOTAL'];
				
				//bila ada pembayaran
				if($bayar > 0)
				{
					//ambil nilai pembayaran bulan lalu
					$query2 = "
					SELECT *
					FROM REALISASI WHERE KODE_BLOK = '$id' 
					AND TANGGAL >= CONVERT(DATETIME,'01-$last_bln-$last_thn',105)
					AND TANGGAL < CONVERT(DATETIME,'01-$bln-$thn',105)
					";
					$obj2 			= $conn->execute($query2);				
					$pemb_lalu		= $obj2->fields['NILAI'];
					$tanggal_bayar	= tgltgl(date("d-m-Y", strtotime($obj2->fields['TANGGAL'])));

					
					//ambil nilai rencana bulan lalu
					$query2 = "
					SELECT *
					FROM RENCANA WHERE KODE_BLOK = '$id' 
					AND TANGGAL >= CONVERT(DATETIME,'01-$last_bln-$last_thn',105)
					AND TANGGAL < CONVERT(DATETIME,'01-$bln-$thn',105)
					";
					$obj2 			= $conn->execute($query2);				
					$rencana_lalu	= $obj2->fields['NILAI'];
					
					//ambil nilai total rencana bulan lalu lalu lalu hingga bulan lalu (3 bulan)
					$query2 = "
					SELECT SUM(NILAI) AS TOTAL
					FROM RENCANA WHERE KODE_BLOK = '$id' 
					AND TANGGAL >= CONVERT(DATETIME,'01-$last3_bln-$last3_thn',105)
					AND TANGGAL < CONVERT(DATETIME,'01-$bln-$thn',105)
					";
					$obj2 			= $conn->execute($query2);				
					$total_3bulan	= $obj2->fields['TOTAL'];

					//jika pembayaran tidak sebesar angsuran bulan lalu (adanya denda di dalamnya)
					if($pemb_lalu != $rencana_lalu)
					{
						//ambil nilai rencana 2 bulan kebelakang
						$query2 = "
						SELECT *
						FROM RENCANA WHERE KODE_BLOK = '$id' 
						AND TANGGAL >= CONVERT(DATETIME,'01-$last2_bln-$last2_thn',105)
						AND TANGGAL < CONVERT(DATETIME,'01-$last_bln-$last_thn',105)
						";
						$obj2 			= $conn->execute($query2);				
						$rencana_lalu2	= $obj2->fields['NILAI'];
					
						//jika pembayaran merupakan telat 3 bulan
						if($pemb_lalu > $total_3bulan)
						{
							//ambil nilai rencana 3 bulan kebelakang
							$query2 = "
							SELECT *
							FROM RENCANA WHERE KODE_BLOK = '$id' 
							AND TANGGAL >= CONVERT(DATETIME,'01-$last3_bln-$last3_thn',105)
							AND TANGGAL < CONVERT(DATETIME,'01-$last2_bln-$last2_thn',105)
							";
							$obj2 			= $conn->execute($query2);				
							$rencana_lalu3	= $obj2->fields['NILAI'];
							
							//perhitungan selisih tanggal dari tanggal bayar, sebagai denda 2 bulan lalu			
							$obj3 = $conn->Execute("select dbo.selisih_tgl('01-$last_bln-$last_thn','$tanggal_bayar') AS SELISIH");
							$selisih_hari	= $obj3->fields['SELISIH'];
							
							$denda_tersisa  = $rencana_lalu2 * 0.001 * $selisih_hari;
							$total_denda 	= $total_denda + $denda_tersisa;
													
							//perhitungan selisih tanggal dari tanggal bayar, sebagai denda 3 bulan lalu							
							$denda_tersisa  = $rencana_lalu3 * 0.001 * $selisih_hari;
							$total_denda 	= $total_denda + $denda_tersisa;
						}
						
						//jika pembayaran merupakan telat 2 bulan
						else
						{
							//perhitungan selisih tanggal dari tanggal bayar, yang belum dibayar sebagai denda			
							$obj3 = $conn->Execute("select dbo.selisih_tgl('01-$last_bln-$last_thn','$tanggal_bayar') AS SELISIH");
							$selisih_hari	= $obj3->fields['SELISIH'];
						
							$denda_tersisa  = $rencana_lalu2 * 0.001 * $selisih_hari;
							$total_denda 	= $total_denda + $denda_tersisa;
						}
					}
				
					
				}
				
				//bila tidak ada pembayaran
				if($bayar == 0)
				{	
					//pengecekkan, apakah bulan lalu lalu ada pembayaran atau tidak
					$query2 = "
					SELECT COUNT(*) AS TOTAL
					FROM REALISASI WHERE KODE_BLOK = '$id' 
					AND TANGGAL >= CONVERT(DATETIME,'01-$last2_bln-$last2_thn',105)
					AND TANGGAL < CONVERT(DATETIME,'01-$last_bln-$last_thn',105)
					";
					$obj2 			= $conn->execute($query2);
					$bayar_lalu		= $obj2->fields['TOTAL'];
					
					//bila tidak ada pembayaran bulan lalu lalu
					if($bayar_lalu == 0)
					{
						//tagihan 2 bulan yang lalu ditambah 30 hari
						$query2 = "
						SELECT *
						FROM RENCANA WHERE KODE_BLOK = '$id' 
						AND TANGGAL >= CONVERT(DATETIME,'01-$last2_bln-$last2_thn',105)
						AND TANGGAL < CONVERT(DATETIME,'01-$last_bln-$last_thn',105)
						";
						$obj2 				= $conn->execute($query2);				
						$rencana_kemarin2	= $obj2->fields['NILAI'];
						
						$obj3 = $conn->Execute("select dbo.selisih_tgl('01-$last_bln-$last_thn','01-$bln-$thn') AS SELISIH");
						$selisih_hari		= $obj3->fields['SELISIH'] - 1;
						
						$denda_kemarin2	 	= $rencana_kemarin2 * 0.001 * $selisih_hari;
							
						$total_rencana 		= $total_rencana + $rencana_kemarin2;
						$total_denda 		= $total_denda + $denda_kemarin2;
					
					}
				
					//tagihan bulan lalu ditambah denda 5 hari
					$query2 = "
					SELECT *
					FROM RENCANA WHERE KODE_BLOK = '$id' 
					AND TANGGAL >= CONVERT(DATETIME,'01-$last_bln-$last_thn',105)
					AND TANGGAL < CONVERT(DATETIME,'01-$bln-$thn',105)
					";
					$obj2 			 	= $conn->execute($query2);				
					$rencana_kemarin 	= $obj2->fields['NILAI'];
					
					$obj3 = $conn->Execute("select dbo.selisih_tgl('25-$last_bln-$last_thn','01-$bln-$thn') AS SELISIH");
					$selisih_hari		= $obj3->fields['SELISIH'] - 1;
					
					$denda_kemarin	 	= $rencana_kemarin * 0.001 * $selisih_hari;	

					$total_rencana 		= $total_rencana + $rencana_kemarin;
					$total_denda 		= $total_denda + $denda_kemarin;
					
				}
				
				//tagihan bulan ini
				$query2 = "
				SELECT *
				FROM RENCANA WHERE KODE_BLOK = '$id' 
				AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
				AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
				";
				$obj2 				= $conn->execute($query2);				
				$rencana_sekarang	= $obj2->fields['NILAI'];
				
				$total_rencana 		= $total_rencana + $rencana_sekarang;
				
				//mengambil nilai total tagihan lain-lain yang ada di dalam database
				$query2 = "SELECT ISNULL(SUM(NILAI),0) AS TOTAL_LAIN FROM TAGIHAN_LAIN_LAIN where KODE_BLOK = '$id'
				AND KODE_BAYAR != 9 AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105) 
				AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)";

				$obj2 = $conn->execute($query2);
				$total_lain			= $obj2->fields['TOTAL_LAIN'];
			}
			?>
			
			<td class="text-center"><?php echo to_money($total_rencana); ?></td>	
			<td class="text-center"><?php echo to_money($total_denda); ?></td>	
			<td class="text-center"><?php echo to_money($total_lain); ?></td>	
			<td class="text-center"><?php echo to_money($total_rencana + $total_denda + $total_lain); ?></td>
				
		</tr>
		<?php
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control w50"></table>

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
<?php
	require_once('../../../../../config/config.php');
	die_login();
	$conn = conn($sess_db);
	die_conn($conn);
	
	$bulan				= (isset($_REQUEST['bulan'])) ? clean($_REQUEST['bulan']) : '';
	
	$namafile = "[BCA]Daftar_Penagihan_"."(".$bulan.").xls";
	
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$namafile");//ganti nama sesuai keperluan
	header("Cache-Control:  must-revalidate, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$query_blok_lunas = "SELECT C.KODE_BLOK FROM ( SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM 
	( SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from ( select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) 
	AS NILAI from spp A where A.KODE_BLOK is not null UNION ALL SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL
	)a GROUP BY a.KODE_BLOK ) A LEFT JOIN (SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B 
	ON A.KODE_BLOK=B.KODE_BLOK where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0 )C LEFT JOIN SPP D ON C.KODE_BLOK = D.KODE_BLOK
	WHERE C.REMAIN - (ISNULL(D.NILAI_CAIR_KPR,0)) >= 0
	";
	
	$query_export = "SELECT TANGGAL_EXPORT FROM CS_PARAMETER_COL";
	$tanggal_export = $conn->Execute($query_export)->fields['TANGGAL_EXPORT'];


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
	
	$query = "DELETE FROM TAGIHAN WHERE TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
				AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)";
	ex_false($conn->Execute($query), $query);
			
	$query = "DELETE FROM TAGIHAN_LAIN_LAIN WHERE TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
				AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105) AND KODE_BAYAR = 9";
	ex_false($conn->Execute($query), $query);

	$query = "DELETE FROM TAGIHAN_LAIN_LAIN WHERE TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
				AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105) AND KODE_BAYAR = 10";
	ex_false($conn->Execute($query), $query);
	
?>

<html>
<body>

<table class="t-data w50" border="1">
<tr>
	<th class="w10">NO PELANGGAN</th>
	<th class="w20">NAMA PELANGGAN</th>
	<th class="w10">TAGIHAN</th>
</tr>

<?php

	$query = "
	SELECT a.KODE_BLOK, b.NAMA_PEMBELI, a.NILAI, a.TANGGAL, ISNULL(b.NOMOR_CUSTOMER,'-') AS NO_CUSTOMER, b.TANDA_JADI
	from RENCANA a JOIN SPP b
	ON a.KODE_BLOK = b.KODE_BLOK
	WHERE MONTH(TANGGAL) = $bln
	AND YEAR(TANGGAL) = $thn
	AND STATUS_WANPRESTASI IS NULL
	AND b.NOMOR_CUSTOMER IS NOT NULL
	AND a.KODE_BLOK NOT IN ($query_blok_lunas)
	order BY a.KODE_BLOK
	";

	$obj 			= $conn->execute($query);

	while( ! $obj->EOF)
	{

		$id 			= $obj->fields['KODE_BLOK'];
		$tanda_jadi = $obj->fields['TANDA_JADI'];
		$query 		= "SELECT CASE WHEN SUM(NILAI) IS NULL THEN 0 ELSE SUM(NILAI) END AS BAYAR FROM KWITANSI WHERE KODE_BLOK = '$id'";
		$bayar 		= $conn->Execute($query)->fields['BAYAR'];

		if(intval($bayar) >= intval($tanda_jadi)){
			$status_tj = 'Sudah Bayar';
			
		}
		else{
			$status_tj = 'Belum Bayar';
			$obj->movenext();
			continue;
		}

		
		$total_rencana 	= 0;
		$total_denda	= 0;
		$total_lain		= 0;

		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td class="text-center"><?php echo $obj->fields['NO_CUSTOMER']; ?></td>
			<td class="text-left"><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			
			<?php			
			//mengambil nilai tanda jadi
			$tanda_jadi = $obj->fields['TANDA_JADI'];

			//cek bulan ini merupakan rencana pertama atau bukan
			$query2 = "
			SELECT COUNT(*) AS TOTAL
			FROM RENCANA WHERE KODE_BLOK = '$id' 
			AND TANGGAL < '".date_get($bln,$thn)."'
			";
			$obj2 			= $conn->execute($query2);				
			$n_rencana		= $obj2->fields['TOTAL'];

			//jika bulan ini merupakan rancana pertama
			if($n_rencana < 0)
			{
				//tagihan bulan ini
				$query2 = "
				SELECT SUM(NILAI) AS NILAI
				FROM RENCANA WHERE KODE_BLOK = '$id'
				AND MONTH(TANGGAL) = $bln
				AND YEAR(TANGGAL)  = $thn
				";

				$obj2 				= $conn->execute($query2);				
				$rencana 			= $obj2->fields['NILAI'];

				$query2 = "
				SELECT SUM(NILAI) AS NILAI
				FROM REALISASI WHERE KODE_BLOK = '$id'
				AND MONTH(TANGGAL) = $bln
				AND YEAR(TANGGAL)  = $thn

				";

				$obj2 				= $conn->execute($query2);				
				$realisasi			= $obj2->fields['NILAI'];
				
				$total_rencana 		= $total_rencana + $rencana-$realisasi+$tanda_jadi;

			}
			else
			{
				//menghitung nilai tagihan total
				$query2 = "
				SELECT SUM(NILAI) AS NILAI
				FROM RENCANA WHERE KODE_BLOK = '$id'
				AND TANGGAL < '".date_get($bln,$thn)."'
				";

				$obj2 				= $conn->execute($query2);				
				$rencana 			= $obj2->fields['NILAI']+$tanda_jadi;

				//menghitung total realisasi
				$query2 = "
				SELECT SUM(NILAI) AS NILAI
				FROM REALISASI WHERE KODE_BLOK = '$id'
				";

				$obj2 				= $conn->execute($query2);				
				$realisasi			= $obj2->fields['NILAI'];
				$tunggakan 			= $rencana - $realisasi;

				$tanggal_telat= 0;
				if($tunggakan>0){
					$m = $bln;
					$y = $thn;
					$req = false;
					$nilai_total = $tunggakan;
					
					while($req==false){
						
						$query2 = "SELECT NILAI FROM RENCANA WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $m AND YEAR(TANGGAL) = $y";

						$rencana_berjalan = $conn->Execute($query2)->fields['NILAI'];

						$tmp = $nilai_total - $rencana_berjalan;
						$n = $bln + ($thn*12);
						$d = $m + ($y*12);
						$x = $n - $d;

						if($tmp<=0){
							$tmp *= -1;
							if($tmp==0){
								$total_rencana+= $rencana_berjalan;

								$query = "INSERT INTO TAGIHAN (KODE_BLOK, TANGGAL, NILAI, STATUS_BAYAR) 
											VALUES('$id', CONVERT(DATETIME,'".$tanggal_export."-$bln-$thn',105), $rencana_berjalan, 0)";
								$conn->Execute($query);	
								
							}else if($tmp<$rencana_berjalan){
								$total_lain = $total_lain + $nilai_total;	
							} 
							$req = true;
						}else{
							$nilai_total -= $rencana_berjalan;
							if($nilai_total>0){
								$total_rencana += $rencana_berjalan;

								$query = "INSERT INTO TAGIHAN (KODE_BLOK, TANGGAL, NILAI, STATUS_BAYAR) 
											VALUES('$id', CONVERT(DATETIME,'".$tanggal_export."-$bln-$thn',105), $rencana_berjalan, 0)";
								$conn->Execute($query);	
							}
							else{
								$total_lain += $nilai_total;
							}
							
							$m--;
							if($m==0){
								$m=12;
								$y--;
							}
							$cek_query = "SELECT COUNT(*) AS TOTAL FROM RENCANA WHERE TANGGAL < '".date_get($m,$y)."' AND KODE_BLOK = '$id'";
		
							$cek = $conn->Execute($cek_query)->fields['TOTAL'];
							if($cek==0){
								break;
							}
						}
						if($x>0){
							$query_tanggal_rencana = "SELECT TANGGAL FROM RENCANA WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $m AND YEAR(TANGGAL) = $y";
							$tanggal_rencana = date("d-m-Y", strtotime($conn->Execute($query_tanggal_rencana)->fields['TANGGAL']));
							
							if($x==1){
								$query_tanggal = "SELECT DATEDIFF(DAY, CONVERT(DATETIME,'$tanggal_rencana',105),CONVERT(DATETIME,'".$tanggal_export."-".$bln."-".$thn."',105)) as TOTAL_HARI";
								$telat = $conn->Execute($query_tanggal)->fields['TOTAL_HARI'];
								
								$query_tanggal_rencana_telat = "SELECT DATEADD(DAY,-1,TANGGAL) AS TANGGAL FROM RENCANA WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $m AND YEAR(TANGGAL) = $y";
								$tanggal_telat = date("d-m-Y", strtotime($conn->Execute($query_tanggal_rencana_telat)->fields['TANGGAL']));
								
								$total_denda = $total_denda +  (0.001*$telat*$rencana_berjalan);
							
							}
							else{

								$query_tanggal = "SELECT DATEDIFF(DAY, CONVERT(DATETIME,'$tanggal_rencana',105),CONVERT(DATETIME,'".$tanggal_telat."',105)) as TOTAL_HARI";
								$telat = $conn->Execute($query_tanggal)->fields['TOTAL_HARI'];

								$query_tanggal_rencana_telat = "SELECT DATEADD(DAY,-1,TANGGAL) AS TANGGAL FROM RENCANA WHERE KODE_BLOK = '$id' AND MONTH(TANGGAL) = $m AND YEAR(TANGGAL) = $y";
								$tanggal_telat = date("d-m-Y", strtotime($conn->Execute($query_tanggal_rencana_telat)->fields['TANGGAL']));
								
								$total_denda = $total_denda +  (0.001*$telat*$rencana_berjalan);
							}			
						}
					}
				}
				else{
					$total_rencana = 0;
				}

				//mengambil nilai total tagihan lain-lain yang ada di dalam database
				$query2 = "SELECT ISNULL(SUM(NILAI),0) AS TOTAL_LAIN FROM TAGIHAN_LAIN_LAIN where KODE_BLOK = '$id'
				AND KODE_BAYAR != 9 AND MONTH(TANGGAL) = $bln AND YEAR(TANGGAL) = $thn";

				$obj2 = $conn->execute($query2);
				$total_lain		= $obj2->fields['TOTAL_LAIN']+$total_lain;

				if($total_denda>0){
					$query = "INSERT INTO TAGIHAN_LAIN_LAIN(KODE_BLOK, TANGGAL, KODE_BAYAR, NILAI, STATUS_BAYAR) 
								VALUES('$id', CONVERT(DATETIME,'".$tanggal_export."-$bln-$thn',105), 9, $total_denda, 0)";
					$conn->Execute($query);
				}
				if($total_lain>0){
					$query = "INSERT INTO TAGIHAN_LAIN_LAIN(KODE_BLOK, TANGGAL, KODE_BAYAR, NILAI, STATUS_BAYAR) 
							VALUES('$id', CONVERT(DATETIME,'".$tanggal_export."-$bln-$thn',105), 10, $total_lain, 0)";
					$conn->Execute($query);	
				}

				
			}
			

			?>
			
			<td class="text-center"><?php echo to_money($total_rencana + $total_denda + $total_lain); ?></td>
				
		</tr>
		<?php
		$obj->movenext();
	}

?>
</table>

</body>
</html>

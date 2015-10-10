<?php
	require_once('../../../../../config/config.php');
	die_login();
	$conn = conn($sess_db);
	die_conn($conn);

	$namafile = "Daftar Penagihan "."(".substr(fm_date(date("Y-m-d")),3).").xls";
	
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$namafile");//ganti nama sesuai keperluan
	header("Cache-Control:  must-revalidate, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<html>
<body>

<table class="t-data w50">
<tr>
	<th class="w10">KODE BLOK</th>
	<th class="w10">NO PELANGGAN</th>
	<th class="w20">NAMA PELANGGAN</th>
	<th class="w10">TAGIHAN</th>
</tr>

<?php

	$query = "
	SELECT a.KODE_BLOK, b.NAMA_PEMBELI, a.NILAI, a.TANGGAL, NOMOR_CUSTOMER = CASE WHEN b.NOMOR_CUSTOMER IS NULL
	THEN '-' ELSE b.NOMOR_CUSTOMER END
	from RENCANA a LEFT JOIN SPP b
	ON a.KODE_BLOK = b.KODE_BLOK
	where TANGGAL >= CONVERT(DATETIME,'01-01-2015',105) 
	AND TANGGAL < CONVERT(DATETIME,'01-02-2015',105)
	AND STATUS_WANPRESTASI IS NULL
	AND a.KODE_BLOK NOT IN ($query_blok_lunas)
	$query_search
	order BY a.TANGGAL

	";
	$obj 			= $conn->execute($query);

	while( ! $obj->EOF)
	{
		$id 		= $obj->fields['KODE_BLOK'];
		
		?>
		<tr> 
			<td class="text-center"><?php echo $obj->fields['KODE_BLOK']; ?></td>
			<td class="text-center"><?php echo $obj->fields['NOMOR_CUSTOMER']; ?></td>
			<td class="text-left"><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			
			<?php
			$tanggal 			= f_tgl (date("Y-m-d"));
			$pecah_tanggal		= explode("-",$tanggal);
			$tgl 				= $pecah_tanggal[0];
			$bln 				= $pecah_tanggal[1];
			$thn 				= $pecah_tanggal[2];
			
			$next_bln	= $bln + 1;
			$next_thn	= $thn;
			if($bln > 12)
			{
				$next_bln	= 1;
				$next_thn	= $thn + 1;
			}
			
			//pengecekkan, apakah bulan ini ada pembayaran atau tidak
			$query2 = "
			SELECT COUNT(*) AS TOTAL
			FROM REALISASI WHERE KODE_BLOK = '$id' 
			AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
			AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
			";
			$obj2 			= $conn->execute($query2);
			$bayar			= $obj2->fields['TOTAL'];
			$total_nilai 	= 0;
			
			//bila tidak ada pembayaran
			if($bayar == 0)
			{			
				//tagihan bulan ini ditambah denda 5 hari
				$query2 = "
				SELECT *
				FROM RENCANA WHERE KODE_BLOK = '$id' 
				AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
				AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
				";
				$obj2 			= $conn->execute($query2);				
				$nilai			= $obj2->fields['NILAI'];
				$denda			= $nilai * 0.005;					
				$total_nilai	= $nilai + $denda;
					
			}
			
			//tagihan bulan depan
			$bln 		= $bln + 1;
			$next_bln	= $bln + 1;
			$next_thn	= $thn;
			if($bln > 12)
			{
				$next_bln	= 1;
				$next_thn	= $thn + 1;
			}
			
			$query2 = "
			SELECT *
			FROM RENCANA WHERE KODE_BLOK = '$id' 
			AND TANGGAL >= CONVERT(DATETIME,'01-$bln-$thn',105)
			AND TANGGAL < CONVERT(DATETIME,'01-$next_bln-$next_thn',105)
			";
			$obj2 			= $conn->execute($query2);				
			$nilai			= $obj2->fields['NILAI'];
			$total_nilai	= $total_nilai + $nilai;
			
			?>
				
			<td class="text-center"><?php echo to_money($total_nilai); ?></td>	
				
		</tr>
		<?php
		$obj->movenext();
	}

?>
</table>



</body>
</html>

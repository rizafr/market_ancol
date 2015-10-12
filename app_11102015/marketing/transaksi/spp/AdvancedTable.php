<?php
	require_once('../../../../config/config.php');
	require_once('../../../../config/terbilang.php');
	require_once('spp_proses.php');

	$query = "
	SELECT *
	FROM 
		RENCANA a
	LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE a.KODE_BLOK = 'A2'
	ORDER BY a.TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 1;
	$tanggal = array();
	$nilai = array();
	
	while( ! $obj->EOF)
	{
		$tanggal[] = tgltgl(f_tgl($obj->fields['TANGGAL']));
		$nilai[] = to_money($obj->fields['NILAI']);
		
		$data1 = array(
			'tanggal' =>$tanggal,
			'nilai' => $nilai,
		);	
		
		$i++;
		$obj->movenext();
	}

	
	echo "<pre>";
	print_r($data1);
	echo"</pre>";
?>
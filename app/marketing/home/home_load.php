<?php
	
	require_once('../../../config/config.php');
	require_once('../../../config/terbilang.php');
	$terbilang = new Terbilang();
	die_login();
	$conn = conn($sess_db);
	die_conn($conn);
	
	//pengembalian stok yang lewat masa reserve
	$tgl = f_tgl (date("Y-m-d"));
	$query = "
	SELECT *
	FROM 
	RESERVE
	WHERE
	BERLAKU_SAMPAI < CONVERT(DATETIME,'$tgl',105)
	";
	$obj = $conn->execute($query);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		
		$query2 = "
		UPDATE STOK
		SET STATUS_STOK = 1, TERJUAL = 0
		WHERE
		KODE_BLOK = '$id'
		";
		$obj2 = $conn->execute($query2);
		
		$obj->movenext();
	}
	
	//penghapusan reserve yang telah lewat masa pembuatan SPP
	$conn->Execute("DELETE FROM RESERVE
	WHERE
	BERLAKU_SAMPAI < CONVERT(DATETIME,'$tgl',105)
	");
	
	
	//pengembalian stok yang spp lewat masa tenggang
	$tgl = f_tgl (date("Y-m-d"));
	$obj = $conn->Execute("SELECT BATAS_DISTRIBUSI FROM CS_PARAMETER_MARK");
	$query_batas	= $obj->fields['BATAS_DISTRIBUSI'];
	$obj = $conn->Execute("SELECT TENGGANG_DISTRIBUSI FROM CS_PARAMETER_MARK");
	$query_tenggang	= $obj->fields['TENGGANG_DISTRIBUSI'];
	$total_hari = $query_batas + $query_tenggang;
	
	$query = "
	SELECT *
	FROM 
	SPP
	WHERE
	DATEADD(dd,$total_hari,TANGGAL_SPP) < CONVERT(DATETIME,'$tgl',105)
	AND STATUS_SPP = 2
	";
	$obj = $conn->execute($query);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		
		$query2 = "
		UPDATE STOK
		SET STATUS_STOK = 1, TERJUAL = 0
		WHERE
		KODE_BLOK = '$id'
		";
		$obj2 = $conn->execute($query2);
		
		$obj->movenext();
	}
	
	//penghapusan spp yang telah lewat tenggang
	$conn->Execute("DELETE FROM SPP 
	WHERE
	CONVERT(DATETIME,'$tgl',105) > DATEADD(dd,$total_hari,TANGGAL_SPP)
	AND STATUS_SPP = 2
	");
	
	//belum distribusi
	$now = explode('-', $tgl);
	$now[1] = intval($now[1]);
	$query = "select COUNT(STATUS_SPP) AS TOTAL_BELUM_DISTRIBUSI FROM SPP WHERE STATUS_SPP IS NULL OR STATUS_SPP != 1";
	$obj = $conn->execute($query);
	
	$belum_distribusi		= $obj->fields['TOTAL_BELUM_DISTRIBUSI'];
	
	//belum otorisasi
	$query = "select COUNT(OTORISASI) AS TOTAL_BELUM_OTORISASI FROM SPP WHERE OTORISASI != 1";
	$obj = $conn->execute($query);
	
	$belum_otorisasi		= $obj->fields['TOTAL_BELUM_OTORISASI'];
	
	//belum identifikasi
	$query = "select COUNT(SISA) AS TOTAL_BELUM_IDENTIFIKASI FROM CS_VIRTUAL_ACCOUNT WHERE SISA != 0";
	$obj = $conn->execute($query);
	
	$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM STOK WHERE STATUS_STOK = '0' AND TERJUAL = '0' ";
	$total_stok_awal = $conn->execute($query)->fields['TOTAL'];
	
	$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM STOK WHERE STATUS_STOK = '1' AND TERJUAL = '0' ";
	$total_stok = $conn->execute($query)->fields['TOTAL'];
	
	$query = "SELECT COUNT(s.KODE_BLOK) AS TOTAL FROM STOK s JOIN RESERVE r ON s.KODE_BLOK = r.KODE_BLOK  WHERE s.STATUS_STOK = '1' AND s.TERJUAL = '1' AND MONTH(r.TANGGAL_RESERVE) = '$now[1]' AND YEAR(r.TANGGAL_RESERVE) = '$now[2]' ";
	$total_stok_reserve = $conn->execute($query)->fields['TOTAL'];
	
	$query = "SELECT COUNT(s.KODE_BLOK) AS TOTAL FROM STOK s JOIN SPP p ON s.KODE_BLOK = p.KODE_BLOK WHERE s.STATUS_STOK = '1' AND s.TERJUAL = '2' AND MONTH(p.TANGGAL_SPP) = '$now[1]' AND YEAR(p.TANGGAL_SPP)= '$now[2]' ";
	$total_stok_terjual = $conn->execute($query)->fields['TOTAL'];
	
	$belum_identifikasi		= $obj->fields['TOTAL_BELUM_IDENTIFIKASI'];
	
	//belum ppjb
	$query = "SELECT count(*) as TOTAL_BELUM_PPJB
	FROM SPP WHERE KODE_BLOK NOT IN 
	(
	SELECT DISTINCT a.KODE_BLOK FROM CS_PPJB a
	JOIN SPP b on a.KODE_BLOK = b.KODE_BLOK
	)";
	$obj = $conn->execute($query);
	
	$belum_ppjb		= $obj->fields['TOTAL_BELUM_PPJB'];
	/* End Pagination */
?>
<script type="text/javascript">
	jQuery(function($) {
		jQuery('#grafik').highcharts({
			chart: {
            type: 'column'
        },
        title: {
            text: 'Data SPP Periode '
        },
        subtitle: {
            text: '<?php echo $terbilang->nama_bln_thn($tgl);?>'
        },
        xAxis: {
            categories: [
                '<?php echo $terbilang->nama_bln_thn($tgl);?>'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Data'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Jumlah SPP belum Distribusi',
            data: [<?php echo $belum_distribusi; ?>]

        }, {
            name: 'Jumlah SPP belum PPJB',
            data: [<?php echo $belum_ppjb; ?>]

        }, {
            name: 'Jumlah SPP belum Otorisasi',
            data: [<?php echo $belum_otorisasi; ?>]

        }, {
            name: 'Jumlah SPP belum Identifikasi',
            data: [<?php echo $belum_identifikasi; ?>]

        }]
		});
		
		jQuery('#grafikStok').highcharts({
			chart: {
            type: 'column'
        },
        title: {
            text: 'Data Stok Periode'
        },
        subtitle: {
            text: '<?php echo $terbilang->nama_bln_thn($tgl);?>'
        },
        xAxis: {
            categories: [
                '<?php echo $terbilang->nama_bln_thn($tgl);?>'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Data'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Jumlah Stok Belum Otorisasi',
            data: [<?php echo $total_stok_awal; ?>]

        }, {
            name: 'Jumlah Stok Siap Jual',
            data: [<?php echo $total_stok; ?>]

        }, {
            name: 'Jumlah Stok Reserve',
            data: [<?php echo $total_stok_reserve; ?>]

        }, {
            name: 'Jumlah Stok Terjual',
            data: [<?php echo $total_stok_terjual; ?>]

        }]
		});
	});
</script>
<div id="grafik" class="w48 f-left" data-wow-duration="2s" ></div>
<div id="grafikStok" class="w48 f-right" data-wow-duration="2s" ></div>


<?php
	close($conn);
	exit;
?>
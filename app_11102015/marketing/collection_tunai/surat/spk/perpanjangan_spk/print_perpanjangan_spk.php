<?php
	require_once('../../../../../../config/config.php');
	die_login();
	$conn = conn($sess_db);
	die_conn($conn);

	$namafile = "Daftar Perpanjangan SPK "."(".date('d F Y').").doc";
	header("Content-Type: application/vnd.ms-word");
	header("Expires: 0");
	header("Cache-Control:  must-revalidate, post-check=0, pre-check=0");
	header("Content-disposition: attachment; filename=$namafile");
	
?>

<html>
<body>

<table class="t-data">
<tr>
	<th class="w5">BLOK/NO.</th>
	<th class="w20">NAMA</th>
	<th class="w40">ALAMAT SURAT</th>
	<th class="w10">TELEPON</th>
	<th class="w10">TANGGAL PEMBY TERAKHIR</th>

</tr>

<?php
$tgl 		= f_tgl (date("Y-m-d"));
$query_blok_lunas = '';
$query_pemb_jt = '';
$query_tglmerah = '';
$query_libur = '';
$query_blok_lunas = "SELECT C.KODE_BLOK FROM ( SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM 
( SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from ( select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) 
AS NILAI from spp A where A.KODE_BLOK is not null UNION ALL SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL
)a GROUP BY a.KODE_BLOK ) A LEFT JOIN (SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B 
ON A.KODE_BLOK=B.KODE_BLOK where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0 )C LEFT JOIN SPP D ON C.KODE_BLOK = D.KODE_BLOK
WHERE C.REMAIN - (ISNULL(D.NILAI_CAIR_KPR,0)) >= 0
";
$query_pemb_jt .= "(SELECT PEMB_JATUH_TEMPO FROM CS_PARAMETER_COL)";
$query_tglmerah = "SELECT COUNT(*) FROM CS_HARI_LIBUR a WHERE a.tanggal_awal<=@CUR_DATE AND @CUR_DATE<=a.tanggal_akhir)";
$query_pemby_terakhir = "(SELECT KODE_BLOK, MAX(TANGGAL) AS TGL FROM RENCANA GROUP BY KODE_BLOK ) c";



	$query = "
	SELECT * FROM $query_pemby_terakhir LEFT JOIN SPP d
	ON C.KODE_BLOK = d.KODE_BLOK
	where 
	DATEADD(dd,0,c.TGL) = CONVERT(DATETIME,'$tgl',105) AND 
	c.KODE_BLOK NOT IN($query_blok_lunas)
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);

	while( !$obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		$tanggal_tempo = tgltgl(date("d-m-Y", strtotime($obj->fields['TGL'])));
		$TELP_KANTOR=(trim($obj->fields["TELP_KANTOR"])!="")?trim(strtoupper($obj->fields["TELP_KANTOR"])):"";
		$TELP_LAIN=(trim($obj->fields["TELP_LAIN"])!="")?",".trim(strtoupper($obj->fields["TELP_LAIN"])):"";
		$TELP_RUMAH=(trim($obj->fields["TELP_RUMAH"])!="")?",".trim(strtoupper($obj->fields["TELP_RUMAH"])):"";
		$TELP=$TELP_KANTOR.$TELP_LAIN.$TELP_RUMAH;
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo $obj->fields['ALAMAT_SURAT']; ?></td>
			<td><?php echo $TELP; ?></td>
			<td class="text-center"><input type="hidden" name="tanggal_tempo" id="tanggal_tempo" value="<?php echo $tanggal_tempo; ?>"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TGL']))); ?></td>
		</tr>
		<?php
		$obj->movenext();
	}
?>
</table>
</body>
</html>

<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C13');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$tgl 		= f_tgl (date("Y-m-d"));

$tanggal_bulan		= $tgl;
$pecah				= explode("-",$tanggal_bulan);
$sekarang_tgl		= $pecah[0];
$sekarang_bln 		= $pecah[1];
$sekarang_thn 		= $pecah[2];

//bulan kemarin
$kemarin_bln	= $sekarang_bln - 1;
$kemarin_thn	= $sekarang_thn;
if($sekarang_bln == 1)
{
	$kemarin_bln	= 12;
	$kemarin_thn	= $sekarang_thn - 1;
}

$query_blok_lunas_bayar = '';
$query_pemb_jt = '';
$query_tglmerah = '';
$query_libur = '';
$query_blok_lunas_bayar = "SELECT C.KODE_BLOK FROM ( SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM 
( SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from ( select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) 
AS NILAI from spp A where A.KODE_BLOK is not null UNION ALL SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL
)a GROUP BY a.KODE_BLOK ) A LEFT JOIN (SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B 
ON A.KODE_BLOK=B.KODE_BLOK where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0 )C LEFT JOIN SPP D ON C.KODE_BLOK = D.KODE_BLOK
WHERE C.REMAIN - (ISNULL(D.NILAI_CAIR_KPR,0)) >= 0 UNION ALL SELECT KODE_BLOK FROM KWITANSI WHERE TANGGAL >= CONVERT(DATETIME,'01-$kemarin_bln-$kemarin_thn',105)
AND TANGGAL < CONVERT(DATETIME,'01-$sekarang_bln-$sekarang_thn',105)
";
$query_pemb_jt .= "(SELECT UNDANGAN_PEMBATALAN FROM CS_PARAMETER_COL)";
$query_tglmerah = "SELECT COUNT(*) FROM CS_HARI_LIBUR a WHERE a.tanggal_awal<=@CUR_DATE AND @CUR_DATE<=a.tanggal_akhir)";


# Pagination
$query = "
SELECT 
	A.KODE_BLOK
	FROM 
		SPP A JOIN RENCANA B ON A.KODE_BLOK = B.KODE_BLOK
	WHERE
		(select dbo.tambah_tgl(B.TANGGAL,$query_pemb_jt)) = CONVERT(DATETIME,'$tgl',105)AND 
	b.KODE_BLOK NOT IN($query_blok_lunas_bayar)
	ORDER BY A.KODE_BLOK
";
$n = 0;
$obj = $conn->execute($query);
while( ! $obj->EOF)
{
	$n++;
	$obj->movenext();
}
$total_data = $n;
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control">
<tr>
	<td>
		<input type="button" id="print" value=" Cetak Data ">
		<input type="button" id="surat" value=" Cetak Surat ">
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

<table class="t-data">
<tr>
	<th class="w5"><input type="checkbox" id="cb_all"></th>
	<th class="w5">BLOK/NO.</th>
	<th class="w20">NAMA</th>
	<th class="w40">ALAMAT SURAT</th>
	<th class="w10">TELEPON</th>
	<th class="w10">TANGGAL JATUH TEMPO</th>
	<th class="w10">NILAI JATUH TEMPO</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		A.KODE_BLOK,A.NAMA_PEMBELI,A.ALAMAT_SURAT,A.TELP_KANTOR,A.TELP_LAIN,A.TELP_RUMAH,B.TANGGAL,B.NILAI
	FROM 
		SPP A JOIN RENCANA B ON A.KODE_BLOK = B.KODE_BLOK
	WHERE
		(select dbo.tambah_tgl(B.TANGGAL,$query_pemb_jt)) = CONVERT(DATETIME,'$tgl',105)AND 
	B.KODE_BLOK NOT IN($query_blok_lunas_bayar)
	ORDER BY A.KODE_BLOK
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);

	while( !$obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		$tanggal_tempo = $obj->fields['TANGGAL'];
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
			<td class="text-center"><input type="hidden" name="tanggal_tempo" id="tanggal_tempo" value="<?php echo $tanggal_tempo; ?>"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td><?php echo $obj->fields['NILAI']; ?></td>
		</tr>
		<?php
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control "></table>

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
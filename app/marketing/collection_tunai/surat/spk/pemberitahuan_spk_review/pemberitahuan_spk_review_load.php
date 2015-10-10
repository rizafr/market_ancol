<?php
require_once('../../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C20');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
$tgl = '29-03-2015';
$query_blok_lunas = '';
$query_pemb_jt = '';
$query_tglmerah = '';
$query_libur = '';
$query_blok_lunas .= "(SELECT A.KODE_BLOK,B.SUMOFREALISASI,A.SUMOFPLAN,(B.SUMOFREALISASI-A.SUMOFPLAN) AS REMAIN FROM (
	SELECT SUM (A.NILAI) as SUMOFPLAN, A.KODE_BLOK from( 
	select A.KODE_BLOK,A.TANGGAL_TANDA_JADI AS TANGGAL,ISNULL(A.TANDA_JADI,0) AS NILAI from spp A where A.KODE_BLOK is not null
	UNION ALL
	SELECT A.KODE_BLOK,A.TANGGAL,ISNULL(A.NILAI,0) FROM RENCANA A WHERE A.KODE_BLOK IS NOT NULL)a GROUP BY a.KODE_BLOK) A LEFT
	JOIN (
	SELECT SUM(A.NILAI) AS SUMOFREALISASI,A.KODE_BLOK FROM REALISASI A GROUP BY  A.KODE_BLOK)B ON A.KODE_BLOK=B.KODE_BLOK
	where (B.SUMOFREALISASI-A.SUMOFPLAN)>=0)C";
$query_pemb_jt .= "(SELECT PEMB_JATUH_TEMPO FROM CS_PARAMETER_COL)";
$query_tglmerah = "SELECT COUNT(*) FROM CS_HARI_LIBUR a WHERE a.tanggal_awal<=@CUR_DATE AND @CUR_DATE<=a.tanggal_akhir)";
$query_pemby_terakhir = "(SELECT KODE_BLOK, MAX(TANGGAL) AS TGL FROM RENCANA GROUP BY KODE_BLOK ) c";
# Pagination
$query = "
SELECT COUNT(*) AS TOTAL FROM SPP 
WHERE STATUS_SPK = 5
";
$total_data = $conn->execute($query)->fields['TOTAL'];
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
	<th class="w10">NOMOR SPK BANK</th>

</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT * FROM SPP 
	WHERE STATUS_SPK = 5
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);

	while( !$obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
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
			<td class="text-center"><?php echo $obj->fields['NOMOR_SPK_BANK']; ?></td>
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
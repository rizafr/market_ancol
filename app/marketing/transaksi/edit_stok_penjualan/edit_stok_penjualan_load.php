<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M17');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';
$kode_sk = (isset($_REQUEST['kode_sk'])) ? clean($_REQUEST['kode_sk']) : '';


$query_search = '';
if($s_opf1 == 's.KODE_BLOK')
{
	if ($s_opv1 != '')
	{
		$query_search .= " AND $s_opf1 LIKE '%$s_opv1%' ";
	}
}

if($s_opf1 == 's.KODE_SK')
{
	$query_search .= " AND $s_opf1 = '$kode_sk' ";
}
# Pagination
$query = "
SELECT  
s.NO_VA,
s.KODE_BLOK,
s.LUAS_BANGUNAN,
s.KODE_SK,
s.STATUS_STOK,
s.TERJUAL,
t.TIPE_BANGUNAN,
hs.HARGA_CASH_KERAS,
hs.CB36X,
hs.CB48X,
hs.KPA24X,
hs.KPA36X, 
LOKASI, 
JENIS_UNIT
FROM 
STOK s
LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
LEFT JOIN HARGA_SK hs ON s.KODE_SK = hs.KODE_SK AND s.KODE_BLOK = hs.KODE_BLOK
LEFT JOIN LOKASI h ON s.KODE_LOKASI = h.KODE_LOKASI
LEFT JOIN JENIS_UNIT i ON s.KODE_UNIT = i.KODE_UNIT
WHERE TERJUAL = '0'
$query_search
ORDER BY s.KODE_BLOK ASC
";
$total_data = $conn->Execute($query);
$total_data = $total_data->RecordCount();
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w90">
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

<table class="t-data w90">
	<tr>
		<th rowspan="2"><input type="checkbox" id="cb_all"></th>
		<th rowspan="2" >VIRTUAL ACCOUNT</th>
		<th rowspan="2">KODE BLOK</th>
		<th rowspan="2">LUAS <br /> SEMI GROSS (M&sup2;)</th>	
		<th rowspan="2">TOWER</th>
		<th rowspan="2">JENIS UNIT</th>	
		<th rowspan="2">TIPE</th>
		<th rowspan="2">CASH KERAS</th>
		<th colspan="4">HARGA (Incl PPN)</th>
		<th rowspan="2">KODE SK</th>
	</tr>
	<tr>

		<th>CB 36X</th>
		<th>CB 48X</th>
		<th>KPA 24X 40%</th>	
		<th>KPA 36X 40%</th>
		
	</tr>

	<?php
	if ($total_data > 0)
	{

		$obj = $conn->SelectLimit($query, $per_page, $page_start);

		while( ! $obj->EOF)
		{
			$id = $obj->fields['KODE_BLOK'];
			?>
			<tr class="onclick" id="<?php echo $id; ?>"> 
				<td class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
				<td><?php echo $obj->fields['NO_VA']; ?></td>
				<td class="text-center"><?php echo $id; ?></td>
				<td class="text-center"><?php echo to_decimal($obj->fields['LUAS_BANGUNAN']); ?></td>
				<td><?php echo $obj->fields['LOKASI']; ?></td>
				<td><?php echo $obj->fields['JENIS_UNIT']; ?></td>
				<td><?php echo $obj->fields['TIPE_BANGUNAN']; ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['HARGA_CASH_KERAS']); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['CB36X']); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['CB48X']); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['KPA24X']); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['KPA36X']); ?></td>
				<td><?php echo $obj->fields['KODE_SK']; ?></td>
			</tr>
			<?php
			$obj->movenext();
		}
	}
	?>
</table>

<script type="text/javascript">
	jQuery(function($) {
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
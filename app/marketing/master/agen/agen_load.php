<?php
require_once('../../../../config/config.php');
die_login();
//die_app('M');
//die_mod('M13');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
$s_opv1	= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';

$query_search = '';
if ($s_opv1 != '')
{
	$query_search .= " AND $s_opf1 LIKE '%$s_opv1%' ";
}

# Pagination
$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	CLUB_PERSONAL
WHERE
	JABATAN_KLUB = '5'
$query_search
";

$total_data = $conn->Execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
# End Pagination
?>

<table id="pagging-1" class="t-control w90">
<tr>
	<td>
		<input type="button" id="tambah" value=" Tambah ">
		<input type="button" id="hapus" value=" Hapus ">
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

<table class="t-data w90">
<tr>
	<th class="w5"><input type="checkbox" id="cb_all"></th>
	<th class="w8">ID</th>
	<th class="w20">NAMA</th>
	<th class="w15">NO TELP</th>
	<th class="w10">EMAIL</th>
	<th class="w10">NPWP</th>
	<th class="w10">NO KTP</th>
	<th class="w50">ALAMAT</th>
	<th class="w10">JABATAN</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT 
		NOMOR_ID, case When ALAMAT is null then '' else ALAMAT end as ALAMAT, NAMA,NOMOR_TELEPON, EMAIL, NPWP,NOMOR_KARTU_IDENTITAS ,JABATAN_KLUB
	FROM 
		CLUB_PERSONAL
	WHERE	
		JABATAN_KLUB = '5'
	$query_search
	ORDER BY NOMOR_ID ASC
	";
	
	$obj = $conn->SelectLimit($query, $per_page, $page_start);
	
	while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_ID'];
		$nm = $obj->fields['NAMA'];
		if ($obj->fields['JABATAN_KLUB'] == '5') {
			$jabatan = 'AGEN';
		}
		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA']; ?></td>
			<td><?php echo $obj->fields['NOMOR_TELEPON']; ?></td>
			<td><?php echo $obj->fields['EMAIL']; ?></td>
			<td><?php echo $obj->fields['NPWP']; ?></td>
			<td><?php echo $obj->fields['NOMOR_KARTU_IDENTITAS']; ?></td>
			<td><?php echo $obj->fields['ALAMAT']; ?></td>
			<td class="text-left"><?php echo $jabatan ?></td>
		</tr>
		<?php
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control w90"></table>

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
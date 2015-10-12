<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('C');
die_mod('C31');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';
$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1			= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$query_search = '';

if($field1 == 'all')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT1 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT1 <= CONVERT(DATETIME,'$periode_akhir',105)
		AND TANGGAL_SURAT2 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT2 <= CONVERT(DATETIME,'$periode_akhir',105)
		AND TANGGAL_SURAT3 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT3 <= CONVERT(DATETIME,'$periode_akhir',105)
		AND TANGGAL_SURAT4 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT4 <= CONVERT(DATETIME,'$periode_akhir',105)
		AND TANGGAL_SURAT5 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT5 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'tempo')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT1 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT1 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'somasi1')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT2 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT2 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'somasi2')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT3 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT3 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'somasi3')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT4 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT4 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

if($field1 == 'wanprestasi')
{
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL_SURAT5 >= CONVERT(DATETIME,'$periode_awal',105) 
		AND TANGGAL_SURAT5 <= CONVERT(DATETIME,'$periode_akhir',105)
		";
	}
}

/* Pagination */

$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM
	RENCANA
$query_search
";

$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
?>

<table id="pagging-1" class="t-control">
<tr>
	<td>
		<input type="button" id="excel" value=" Excel ">
		<input type="button" id="print" value=" Print ">
	</td>
	<td class="text-right">
		<input type="button" id="prev_page" value=" < ">
		Hal : <input type="text" name="page_num" size="5" class="page_num apply text-center" value="<?php echo $page_num; ?>">
		Dari <?php echo $total_page; ?> 
		<input type="hidden" id="total_page" value="<?php echo $total_page; ?>">
		<input type="button" id="next_page" value=" > ">
	</td>
</tr>
</table>

<table class="t-nowrap t-data wm100">
<tr>
	<th rowspan="2">NO.</th>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">NAMA</th>
	<th colspan="2">PEMBERITAHUAN</th>
	<th colspan="2">SOMASI I</th>
	<th colspan="2">SOMASI II</th>
	<th colspan="2">SOMASI III</th>
	<th colspan="2">WANPRESTASI</th>
	
</tr>
<tr>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
	<th colspan="1">Tanggal</th>
	<th colspan="1">No.Surat</th>
</tr>

<?php
if ($total_data > 0)
{

	$query = "
	SELECT *
	FROM RENCANA a LEFT JOIN SPP b
	ON a.KODE_BLOK = b.KODE_BLOK
	$query_search
	";
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1;
	while( ! $obj->EOF)
	{

		$id = $obj->fields['KODE_BLOK'];
		
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			
			<?php
			if($field1 == 'all')
			{
			?>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT1'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT1']; ?></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT2'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT2']; ?></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT3'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT3']; ?></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT4'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT4']; ?></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT5'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT5']; ?></td>
				
			<?php
			}
			if($field1 == 'tempo')
			{
			?>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT1'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT1']; ?></td>
				<td></td><td></td><td></td><td></td>
				<td></td><td></td><td></td><td></td>
			<?php
			}
			else if($field1 == 'somasi1')
			{
			?>
				<td></td><td></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT2'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT2']; ?></td>
				<td></td><td></td><td></td>
				<td></td><td></td><td></td>
			<?php
			}
			else if($field1 == 'somasi2')
			{
			?>
				<td></td><td></td><td></td><td></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT3'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT3']; ?></td>
				<td></td><td></td><td></td><td></td>
			<?php
			}
			else if($field1 == 'somasi3')
			{
			?>
				<td></td><td></td><td></td>
				<td></td><td></td><td></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT4'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT4']; ?></td>
				<td></td><td></td>
			<?php
			}
			else if($field1 == 'wanprestasi')
			{
			?>
				<td></td><td></td><td></td><td></td>
				<td></td><td></td><td></td><td></td>
				<td class="text-center"><?php echo kontgl(tgltgl(date("d M Y", strtotime($obj->fields['TANGGAL_SURAT5'])))); ?></td>
				<td class="text-center"><?php echo $obj->fields['NO_SURAT5']; ?></td>
			<?php
			}
			?>
			
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
}
?>


</table>

<table id="pagging-2" class="t-control"></table>

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
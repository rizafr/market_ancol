<?php
require_once('../../../../../config/config.php');
die_login();
// die_app('P');
die_mod('P10');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : date('d-m-Y');
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : date('d-m-Y');

$query_search = '';
// if ($periode_awal <> '' || $periode_akhir <> '')
// {
	$query_search .= "WHERE TANGGAL >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL <= CONVERT(DATETIME,'$periode_akhir',105)";
	
	if ($field1 == 1)
	{
		$query_search .= "";
	}
	else if ($field1 == 2)
	{
		$query_search .= "
		AND
		TANGGAL_TT_PEMBELI <> '1/1/1900 12:00:00 AM' AND 
		TANGGAL_TT_PEJABAT ='1/1/1900 12:00:00 AM' AND 
		TANGGAL_PENYERAHAN ='1/1/1900 12:00:00 AM'
		";
	}
	else if ($field1 == 3)
	{
		$query_search .= "
		AND 
		TANGGAL_TT_PEMBELI <> '1/1/1900 12:00:00 AM' AND 
		TANGGAL_TT_PEJABAT <>'1/1/1900 12:00:00 AM' AND 
		TANGGAL_PENYERAHAN ='1/1/1900 12:00:00 AM'
		";
	}
	else if ($field1 == 4)
	{
		$query_search .= "
		AND 
		TANGGAL_TT_PEMBELI <> '1/1/1900 12:00:00 AM' AND 
		TANGGAL_TT_PEJABAT <>'1/1/1900 12:00:00 AM' AND 
		TANGGAL_PENYERAHAN <>'1/1/1900 12:00:00 AM'
		";
	}
	else if ($field1 == 5)
	{
		$query_search .= "";
	}
// }

/* Pagination */
if ($field1 < 5)
{
	$query = "
	SELECT 
		COUNT(*) AS TOTAL
	FROM 
		CS_PPJB a
	JOIN SPP b ON a.KODE_BLOK = b.KODE_BLOK
	$query_search
	";
}
else if ($field1 == 5)
{
	$query = "
	SELECT 
		COUNT(*) AS TOTAL
	FROM 
		CS_PPJB_PEMBATALAN
	$query_search
	";
}

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
		Dari <?php echo $total_page ?> 
		<input type="hidden" id="total_page" value="<?php echo $total_page; ?>">
		<input type="button" id="next_page" value=" > ">
	</td>
</tr>
</table>

<?php
if ($field1 < 5)
{
?>
<table class="t-nowrap t-data wm100">
<tr>
	<th rowspan="2">NO.</th>
	<th rowspan="2">BLOK / NOMOR</th>
	<th rowspan="2">NAMA PEMILIK</th>
	<th rowspan="2">TGL. PPJB</th>
	<th rowspan="2">JENIS PPJB</th>
	<th rowspan="2">NOMOR</th>
	<th colspan="2">TGL. TANDA TANGAN</th>
	<th rowspan="2">PENYERAHAN</th>
	<th rowspan="2">NO. ARSIP</th>
</tr>
<tr>
	<th colspan="1">PEMILIK</th>
	<th colspan="1">PERUSAHAAN</th>
</tr>
<?php
}
else if ($field1 == 5)
{
?>
<table class="t-nowrap t-data wm100">
<tr>
	<th rowspan="1">NO.</th>
	<th rowspan="1">BLOK / NOMOR</th>
	<th rowspan="1">NAMA PEMILIK</th>
	<th rowspan="1">TGL. PPJB</th>
	<th rowspan="1">JENIS PPJB</th>
	<th rowspan="1">NOMOR PPJB</th>
	<th rowspan="1">ALASAN</th>
</tr>
<?php
}
?>

<?php
if ($total_data > 0)
{

if ($field1 < 5)
{
	$query = "
	SELECT *
	FROM 
		CS_PPJB  a
	JOIN SPP b ON a.KODE_BLOK = b.KODE_BLOK
	JOIN CS_JENIS_PPJB c ON a.JENIS = c.KODE_JENIS
	$query_search
	ORDER BY a.KODE_BLOK
	";
}
else if ($field1 == 5)
{
	$query = "
	SELECT *
	FROM 
		CS_PPJB_PEMBATALAN a
	JOIN CS_JENIS_PPJB c ON a.JENIS = c.KODE_JENIS
	$query_search
	ORDER BY KODE_BLOK
	";
}

if ($field1 < 5)
{
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td><?php echo $obj->fields['NAMA_JENIS']; ?></td>
			<td class="text-center"><?php echo $obj->fields['NOMOR']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_TT_PEMBELI']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_TT_PEJABAT']))); ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_PENYERAHAN']))); ?></td>
			<td><?php echo $obj->fields['NOMOR_ARSIP']; ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
}

else if ($field1 == 5)
{
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	$i = 1 + $page_start;
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $i; ?></td>
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
			<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td><?php echo $obj->fields['NAMA_JENIS']; ?></td>
			<td class="text-center"><?php echo $obj->fields['NOMOR']; ?></td>
			<td><?php echo $obj->fields['ALASAN']; ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
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
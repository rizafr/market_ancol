<?php
require_once('../../../../config/config.php');
die_login();
die_app('M');
die_mod('M16');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1		= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1	= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';
$field2		= (isset($_REQUEST['field2'])) ? clean($_REQUEST['field2']) : '';
$periode	= (isset($_REQUEST['periode'])) ? clean($_REQUEST['periode']) : date('d-m-Y');

$status_otorisasi	= (isset($_REQUEST['status_otorisasi'])) ? clean($_REQUEST['status_otorisasi']) : '';
$tombol				= (isset($_REQUEST['tombol'])) ? clean($_REQUEST['tombol']) : '';
$nama_tombol		= (isset($_REQUEST['nama_tombol'])) ? clean($_REQUEST['nama_tombol']) : '';

$query_search = '';
$query_batas = "(SELECT BATAS_DISTRIBUSI FROM CS_PARAMETER_MARK)";
if ($status_otorisasi == 0)
	{
		$query_search .= "WHERE OTORISASI = '0' ";
	}
else if ($status_otorisasi == 1)
	{
		$query_search .= "WHERE OTORISASI = '1' ";
	}		
else if ($status_otorisasi == 2)
	{
		$query_search.="WHERE KODE_BLOK != '' " ;
	}	

if ($search1 != '')
{
	$query_search .= " AND $field1 LIKE '%$search1%' ";
}

if ($field2 != ''){
	$query_search .= "AND STATUS_SPP = $field2";
}
if ($periode != ''){
	$periode  = explode('-', $periode);
	$periode[0] = intval($periode[0]);
	$query_search .= "AND MONTH(TANGGAL_SPP) = $periode[0] AND YEAR(TANGGAL_SPP) = $periode[1]";
}

$tgl = date('d-m-Y');

/* Pagination */
$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	SPP

$query_search

";

//AND CONVERT(DATETIME,'$tgl',105) < DATEADD(dd,$query_batas,TANGGAL_SPP)
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
?>

<table id="pagging-1" class="t-control w100">
<tr>
	<td>
		<!--<input type="button" id="hapus" value=" Hapus ">-->	
		<!--<input type="button" id="<?php echo $tombol; ?>" value=" <?php echo $nama_tombol; ?> ">-->
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

<table class="t-data w100">
<tr>

	<th class="w10">BLOK / NOMOR</th>
	<th class="w10">COSTUMER ID</th>
	<th class="w10">VIRTUAL ACCOUNT</th>
	<th class="w20">NAMA PEMBELI</th>
	<th class="w8">TANGGAL SPP</th>
	<th class="w8">TANGGAL BATAS</th>
	<th class="w10">JUMLAH HARI</th>
	<th class="w45">ALAMAT RUMAH</th>
</tr>

<?php
if ($total_data > 0)
{
	$query = "
	SELECT *, DATEADD(dd,$query_batas,TANGGAL_SPP) AS TGL_BATAS
	FROM 
		SPP
	$query_search
	
	ORDER BY TANGGAL_SPP DESC
	";
	//AND CONVERT(DATETIME,'$tgl',105) < DATEADD(dd,$query_batas,TANGGAL_SPP)
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		$no_va= $obj->fields['NOMOR_CUSTOMER'];
		$costumer_id = $obj->fields['COSTUMER_ID'];
		$tgl1 = tgltgl(date("d-m-Y", strtotime($tgl)));
		$tgl2 = tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP'])));
		$diff = strtotime($tgl1) - strtotime($tgl2);
		$hari = $diff/(60*60*24);
			
		?>
		<tr class="onclick" id="<?php echo $id; ?>"> 
			<td class="text-center"><?php echo $id; ?></td>
			<td class="text-center"><?php echo $costumer_id; ?></td>
			<td class="text-center"><?php echo $no_va; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI'];  ?></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_SPP'])));  ?></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TGL_BATAS'])));  ?></td>
			<td class="text-center"><?php echo $hari;  ?></td>
			<td><?php echo $obj->fields['ALAMAT_RUMAH'];  ?></td>
	</tr>
		<?php
		
		$obj->movenext();
	}
	
}
?>
</table>

<table id="pagging-2" class="t-control w100"></table>

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
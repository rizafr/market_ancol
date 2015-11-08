<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('P');
die_mod('P06');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1		= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$search1	= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';

$query_search = '';
if ($search1 != '')
{
	$query_search .= " WHERE $field1 LIKE '%$search1%' ";
}

/* Pagination */
$query = "
SELECT 
	COUNT(*) AS TOTAL
FROM 
	SPP a
$query_search
";
$total_data = $conn->execute($query)->fields['TOTAL'];
$total_page = ceil($total_data/$per_page);

$page_num = ($page_num > $total_page) ? $total_page : $page_num;
$page_start = (($page_num-1) * $per_page);
/* End Pagination */
?>

<table id="pagging-1" class="t-control w60 someclass">
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

<table class="t-data w60">
<tr>
	<th class="w10">BLOK / NOMOR</th>
	<th class="w25">NAMA PEMBELI</th>
	<th class="w15">TGL. PAIJB / PPJB</th>
	<th>JENIS PAIJB / PPJB</th>
	<th >NOMOR</th>
	<th >STATUS CETAK PPJB</th>
	<th >STATUS CETAK PAIJB</th>
</tr>

<?php
if ($total_data > 0)
{	
	$query = "
			SELECT a.KODE_BLOK, a.NAMA_PEMBELI,b.NOMOR, b.TANGGAL, c.NAMA_JENIS, b.STATUS_CETAK, b.STATUS_CETAK_PAIJB
			FROM 
				  SPP a
			LEFT OUTER  JOIN CS_PPJB b ON a.KODE_BLOK = b.KODE_BLOK
			LEFT OUTER  JOIN CS_JENIS_PPJB c ON b.JENIS = c.KODE_JENIS 
			$query_search
			ORDER BY a.KODE_BLOK
		";
	$obj = $conn->selectlimit($query, $per_page, $page_start);
	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];		
		$NAMA_PEMBELI = (isset($obj->fields['NAMA_PEMBELI'])) ? ($obj->fields['NAMA_PEMBELI']) : '';
		$NAMA_JENIS = (!isset($obj->fields['NAMA_JENIS']) || is_null($obj->fields['NAMA_JENIS'])) ? '' : $obj->fields['NAMA_JENIS']; 
		$NOMOR = (!isset($obj->fields['NOMOR']) || is_null($obj->fields['NOMOR'])) ? '' : $obj->fields['NOMOR']; 
		$STATUS_CETAK = $obj->fields['STATUS_CETAK'];
		// if($NOMOR!=''){
		// 	$STATUS="SUDAH PPJB";
		// 	$cek="sudah";
		// }else{
		// 	$STATUS="BELUM PPJB";
		// 	$cek="belum";
		// }
		if($STATUS_CETAK==NULL || $STATUS_CETAK=='0'){
			$STATUS_PPJB="BELUM CETAK PPJB";
			$cek="belum";
		}else{
			$STATUS_PPJB="SUDAH CETAK PPJB";
			$cek="sudah";
		}
		if($STATUS_CETAK_PAIJB==NULL || $STATUS_CETAK_PAIJB=='0'){
			$STATUS_PAIJB="BELUM CETAK PAIJB";
			$cek="belum";
		}else{
			$STATUS_PAIJB="SUDAH CETAK PAIJB";
			$cek="sudah";
		}
		?>
		<tr class="onclick <?php echo $cek; ?>" id="<?php echo $id; ?>" > 
			<td class="text-center"><?php echo $id; ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI'];  ?></td>
			<td class="text-center"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
			<td><?php echo $NAMA_JENIS ?></td>
			<td class="text-center"><?php echo $NOMOR; ?></td>
			<td class="text-center"><?php echo $STATUS_PPJB; ?></td>
			<td class="text-center"><?php echo $STATUS_PAIJB; ?></td>
		</tr>
		<?php
		$obj->movenext();
	}
}
?>
</table>

<table id="pagging-2" class="t-control w60"></table>

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
<?php
	require_once('../../../../../config/config.php');
	die_login();
	// die_app('C');
	die_mod('C38');
	$conn = conn($sess_db);
	die_conn($conn);
	
	$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
	$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
	$search		= (isset($_REQUEST['no_va'])) ? clean($_REQUEST['no_va']) : '';
	$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
	$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) :'';
	
	$query_search = '';
	if ($periode_awal <> '' || $periode_akhir <> '')
	{
		$query_search .= "WHERE TANGGAL >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL <= CONVERT(DATETIME,'$periode_akhir',105)";
		if ($search != '')
		{
			$query_search .= " AND NOMOR_VA LIKE '%$search%'";
		}
	}
	# Pagination
	$query = "
	SELECT 
	COUNT(NOMOR_VA) AS TOTAL
	FROM 
	PEMBAYARAN
	$query_search 
	";
	$total_data = $conn->execute($query)->fields['TOTAL'];
	$total_page = ceil($total_data/$per_page);
	
	$page_num = ($page_num > $total_page) ? $total_page : $page_num;
	$page_start = (($page_num-1) * $per_page);
	# End Pagination
?>

<table id="pagging-1" class="t-control w50">
	<tr>
		<td>
			<input type="button" id="upload" value=" Upload ">
			
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

<table class="t-data w50">
	<tr>
		
		<th class="w20">VIRTUAL ACCOUNT</th>
		<th class="w10">TANGGAL TRANSAKSI</th>
		<th class="w20">NILAI</th>
	</tr>
	
	<?php
		if ($total_data > 0)
		{
			$query = "
			SELECT NOMOR_VA,TANGGAL,NILAI
			FROM 
			PEMBAYARAN
			$query_search 
			ORDER BY NOMOR_VA
			";
			$obj = $conn->selectlimit($query, $per_page, $page_start);
			
			while( ! $obj->EOF)
			{
				$id = $obj->fields['NOMOR_VA'];
				$tgl = $obj->fields['TANGGAL'];
				
			?>
			<tr id="<?php echo $id; ?>"> 
				<!--<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td> -->
				<td class="text-left"><?php echo $obj->fields['NOMOR_VA']; ?></td>
				<td class="text-left"><input type="hidden" name="coba" id="coba" value="<?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?>"><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
				<td class="text-right"><?php echo to_money($obj->fields['NILAI']); ?></td>
			</tr>
			<?php
				$obj->movenext();
			}
		}
	?>
</table>

<table id="pagging-2" class="t-control w50"></table>

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
<?php
	require_once('../../../../config/config.php');
	die_login();
	// die_app('P');
	die_mod('P09');
	$conn = conn($sess_db);
	ex_conn($conn);
	
	$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
	$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
	
	$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
	$field1		= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
	$search1	= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';
	
	$query_search = '';
	if ($search1 != '')
	{
		$query_search .= " AND $field1 LIKE '%$search1%' ";
	}
	
	$query_blok = '';
	if ($search1 != '')
	{
		$query_blok .= " AND KODE_BLOK LIKE '%$id%' ";
	}
	
	/* Pagination */
	$query = "
	SELECT 
	COUNT(*) AS TOTAL
	FROM 
	HARGA_SK
	WHERE STATUS='1'
	$query_search
	$query_blok
	";
	$total_data = $conn->execute($query)->fields['TOTAL'];
	$total_page = ceil($total_data/$per_page);
	
	$page_num = ($page_num > $total_page) ? $total_page : $page_num;
	$page_start = (($page_num-1) * $per_page);
	/* End Pagination */
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">	
		
		<script type="text/javascript">
			jQuery(function($) {
				
				$(document).on('click', 'tr.onclick', function(e) {
					e.preventDefault();
					var kode_sk = $(this).data('kode_sk'),
					harga_cash_keras = $(this).data('harga_cash_keras'),
					CB36X = $(this).data('harga'),
					CB48X = $(this).data('harga2'),
					KPA24X = $(this).data('harga3'),
					KPA36X = $(this).data('harga4');
					
					parent.jQuery('#kode_sk').val(kode_sk);
					parent.jQuery('#harga_cash_keras').val(harga_cash_keras);
					parent.jQuery('#harga_CB36X').val(CB36X);
					parent.jQuery('#harga_CB48X').val(CB48X);
					parent.jQuery('#harga_KPA24X').val(KPA24X);
					parent.jQuery('#harga_KPA36X').val(KPA36X);
					parent.window.focus();
					parent.window.popup.close();
					
					return false;
				});
				
				t_strip('.t-data');
			});
		</script>
		
		<table id="pagging-1" class="t-control w50">
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
		<table class="t-data w75">
			<tr>
				<th>KODE SK</th>
				<th>TGL SK</th>
				<th>KODE</th>
				<th>CASH KERAS</th>
				<th>CB36X</th>
				<th>CB48X</th>
				<th>KPA24X</th>
				<th>KPA36X</th>
			</tr>
			<?php
				if ($total_data > 0)
				{
					$query = "
					SELECT KODE_SK, KODE_BLOK, TANGGAL, STATUS, HARGA_CASH_KERAS, CB36X, CB48X, KPA24X, KPA36X
					FROM HARGA_SK
					WHERE STATUS='1'
					$query_blok
					$query_search
					ORDER BY KODE_BLOK ASC
					";
					$obj = $conn->selectlimit($query, $per_page, $page_start);
					
					
					while( ! $obj->EOF)
					{
						
					?>
					<tr class="onclick" 
					data-kode_sk="<?php echo $obj->fields['KODE_SK']; ?>"
					data-harga_cash_keras="<?php echo bigintval($obj->fields['HARGA_CASH_KERAS']); ?>"
					data-harga="<?php echo bigintval($obj->fields['CB36X']); ?>"
					data-harga2="<?php echo bigintval($obj->fields['CB48X']); ?>"
					data-harga3="<?php echo bigintval($obj->fields['KPA24X']); ?>"
					data-harga4="<?php echo bigintval($obj->fields['KPA36X']); ?>"
					>
						<td><?php echo $obj->fields['KODE_SK']?></td>
						<td><?php echo date("d-m-Y", strtotime($obj->fields['TANGGAL'])); ?></td>
						<td class="text-right"><?php echo $obj->fields['KODE_BLOK']; ?></td>
						<td class="text-right"><?php echo to_money($obj->fields['HARGA_CASH_KERAS'],2); ?></td>
						<td class="text-right"><?php echo to_money($obj->fields['CB36X'],2); ?></td>
						<td class="text-right"><?php echo to_money($obj->fields['CB48X'],2); ?></td>
						<td class="text-right"><?php echo to_money($obj->fields['KPA24X'],2); ?></td>
						<td class="text-right"><?php echo to_money($obj->fields['KPA36X'],2); ?></td>
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
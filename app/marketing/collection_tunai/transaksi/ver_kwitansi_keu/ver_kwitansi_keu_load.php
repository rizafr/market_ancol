<?php
require_once('../../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);

$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;

$field1				= (isset($_REQUEST['field1'])) ? clean($_REQUEST['field1']) : '';
$ver_keuangan		= (isset($_REQUEST['ver_keuangan'])) ? clean($_REQUEST['ver_keuangan']) : '';
$search1			= (isset($_REQUEST['search1'])) ? clean($_REQUEST['search1']) : '';
$periode_awal		= (isset($_REQUEST['periode_awal'])) ? clean($_REQUEST['periode_awal']) : '';
$periode_akhir		= (isset($_REQUEST['periode_akhir'])) ? clean($_REQUEST['periode_akhir']) : '';

$query_search1 = '';
$query_search2 = '';


if ($periode_awal <> '' || $periode_akhir <> '')
{
	$query_search1 .= "WHERE TANGGAL >= CONVERT(DATETIME,'$periode_awal',105) AND TANGGAL <= CONVERT(DATETIME,'$periode_akhir',105)";
	
	if ($field1 <> '')
	{
		$query_search2 .= "AND KODE_BLOK LIKE '%$search1%' ";
	}
	
}


	$query_search .= " AND VER_KEUANGAN = $ver_keuangan ";


/* Pagination */
if ($field1 == 1)
{
	$query = "
	SELECT 
	COUNT(*) AS TOTAL
	FROM 
	KWITANSI

	$query_search
	$query_search1
	$query_search2
	";
}
else if ($field1 == 2)
{
	$query = "
	SELECT 
	COUNT(*) AS TOTAL
	FROM 
	KWITANSI_LAIN_LAIN

	$query_search
	$query_search1
	$query_search2
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
			<input type="button" id="verifikasi" value="Verifikasi">
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
if ($field1 == 1)
{
	?>
	<table class="t-data w100">
		<tr>
			<th class="w5">NO.</th>
			<th class="w10">NO. KWITANSI</th>
			<th class="w10">TANGGAL</th>
			<th class="w15">KODE BLOK</th>
			<th class="w25">NAMA</th>
			<th class="w40">KETERANGAN</th>
			<th class="w15">NILAI</th>
			<th class="w25">NILAI DIPOSTING</th>
			<th class="w5">KEUANGAN<input type="checkbox" id="cb_all"></th>
		</tr>
		<?php
	}

	else if ($field1 == 2)
	{
		?>
		<table class="t-data w100">
			<tr>
				<th class="w5">NO.</th>
				<th class="w10">NO. KWITANSI</th>
				<th class="w10">TANGGAL</th>
				<th class="w15">KODE BLOK</th>
				<th class="w25">NAMA</th>
				<th class="w40">KETERANGAN</th>
				<th class="w15">NILAI</th>
				<th class="w5">KEUANGAN<input type="checkbox" id="cb_all"></th></tr>
				<?php
			}
			?>

			<?php
			if ($total_data > 0)
			{

				if ($field1 == 1)
				{
					$query = "
					SELECT *
					FROM 
					KWITANSI

					$query_search1
					$query_search2
					ORDER BY TANGGAL DESC
					";
				}
				else if ($field1 == 2)
				{
					$query = "
					SELECT *
					FROM KWITANSI_LAIN_LAIN $query_search1 $query_search2
					ORDER BY TANGGAL DESC
					";
				}

				if ($field1 == 1)
				{
					$obj = $conn->selectlimit($query, $per_page, $page_start);
					$i = 1 + $page_start;
					while( ! $obj->EOF)
					{
						$id = $obj->fields['NOMOR_KWITANSI'];
						?>
						<tr class="onclick" id="<?php echo $id; ?>"> 
							<td class="text-center"><?php echo $i; ?></td>
							<td class="text-center"><?php echo $id; ?></td>
							<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
							<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
							<td><?php echo $obj->fields['NAMA_PEMBAYAR']; ?></td>
							<td><?php echo $obj->fields['KETERANGAN']; ?></td>
							<td><?php echo to_money($obj->fields['NILAI']); ?></td>
							<td><?php echo to_money($obj->fields['NILAI_DIPOSTING']); ?></td>
							<td class="text-center">
								<input type="checkbox" name="cb_data[]" value=<?php echo $id; ?> class="cb_data" <?php echo is_checked('1', $obj->fields['VER_KEUANGAN']); ?>>
							</td>
						</tr>
						<?php
						$i++;
						$obj->movenext();
					}
				}

				else if ($field1 == 2)
				{
					$obj = $conn->selectlimit($query, $per_page, $page_start);
					$i = 1 + $page_start;
					while( ! $obj->EOF)
					{
						$id = $obj->fields['NOMOR_KWITANSI'];
						?>
						<tr class="onclick" id="<?php echo $id; ?>"> 
							<td class="text-center"><?php echo $i; ?></td>
							<td class="text-center"><?php echo $id; ?></td>
							<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL']))); ?></td>
							<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
							<td><?php echo $obj->fields['NAMA_PEMBAYAR']; ?></td>
							<td><?php echo $obj->fields['KETERANGAN']; ?></td>
							<td><?php echo to_money($obj->fields['NILAI']); ?></td>
							<td class="text-center"><?php echo status_check($obj->fields['VER_COLLECTION']); ?></td>
							<td class="text-center">
								<input type="checkbox" name="cb_data[]" value=<?php echo $id; ?> class="cb_data" <?php echo is_checked('1', $obj->fields['VER_KEUANGAN']); ?>>
							</td>
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
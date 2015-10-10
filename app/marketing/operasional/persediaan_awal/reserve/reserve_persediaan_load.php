<?php
	require_once('../../../../../config/config.php');
	die_login();
	die_app('M');
	die_mod('M30');
	die_ha('M30','R');
	$conn = conn($sess_db);
	die_conn($conn);
	
	$per_page	= (isset($_REQUEST['per_page'])) ? max(1, $_REQUEST['per_page']) : 20;
	$page_num	= (isset($_REQUEST['page_num'])) ? max(1, $_REQUEST['page_num']) : 1;
	
	$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
	$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';
	
	$query_search = '';
	if ($s_opv1 != '')
	{
		$query_search .= " WHERE $s_opf1 LIKE '%$s_opv1%'";
	}
	
	# Pagination
	$query = "
	SELECT  
	COUNT(s.KODE_BLOK) AS TOTAL
	FROM 
	RESERVE s
	$query_search
	";
	$total_data = $conn->Execute($query)->fields['TOTAL'];
	$total_page = ceil($total_data/$per_page);
	
	$page_num = ($page_num > $total_page) ? $total_page : $page_num;
	$page_start = (($page_num-1) * $per_page);
	# End Pagination
?>

<table id="pagging-1" class="t-control">
	<tr>
		<td>
			<button type="button"  id="surat" > Cetak Surat</button>
		</td>
		<td class="text-right">
			<input type="button" id="prev_page" value=" < ">
			Hal : <input type="text" name="page_num" size="5" class="page_num apply text-center" value="<?php echo $page_num; ?>">
			Dari <?php echo $total_page ?> 
			<input type="hidden" id="total_page" value="<?php echo $total_page; ?>">
			<input type="button" id="next_page" value=" > ">
			<input type="hidden" name="act" id="act" value="Surat">
		</td>
	</tr>
</table>

<table class="t-data">
	<tr>
		<th class="w5"><input type="checkbox" id="cb_all"></th>
		<th>KODE BLOK</th>
		<th>NAMA CALON PEMBELI</th>
		<th>TANGGAL RESERVE</th>
		<th>BERLAKU SAMPAI</th>
		<th>ALAMAT</th>
		<th>TELEPON</th>
		<th>AGEN</th>
		<th>KOORDINATOR</th>
	</tr>
	
	<?php
		if ($total_data > 0)
		{
			$query = "
			SELECT  
			KODE_BLOK,
			NAMA_CALON_PEMBELI,
			TANGGAL_RESERVE,
			BERLAKU_SAMPAI,
			ALAMAT,
			TELEPON,
			AGEN,
			KOORDINATOR
			FROM
			RESERVE 
			$query_search
			ORDER BY KODE_BLOK, TANGGAL_RESERVE
			";
			
			$obj = $conn->SelectLimit($query, $per_page, $page_start);
			
			while( ! $obj->EOF)
			{
				$id = $obj->fields['KODE_BLOK'];
				$nama = $obj->fields['NAMA_CALON_PEMBELI'];
				$alamat = $obj->fields['ALAMAT'];
			?>
			<tr class="onclick" id="<?php echo $id; ?>" nama="<?php echo $nama; ?>"> 
				<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td>
				<td><?php echo $id; ?></td>
				<td><?php echo $obj->fields['NAMA_CALON_PEMBELI']; ?></td>
				<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_RESERVE']))); ?></td>
				<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['BERLAKU_SAMPAI']))); ?></td>
				<td><?php echo $obj->fields['ALAMAT']; ?></td>
				<td><?php echo $obj->fields['TELEPON']; ?></td>
				<td><?php echo $obj->fields['AGEN']; ?></td>
				<td><?php echo $obj->fields['KOORDINATOR']; ?></td>
				
			</tr>
			<?php
				$obj->movenext();
			}
		}
	?>
</table>

<table id="pagging-2" class="t-control"></table>

<script type="text/javascript">
	jQuery(function($) {
		
		$(document).on('keypress', '.apply', function(e) {
			var code = (e.keyCode ? e.keyCode : e.which);
			if (code == 13) { $('#apply').trigger('click'); }
		});
		
		/* -- BUTTON -- */
		/*
		$(document).on('click', '#apply', function(e) {
		e.preventDefault();
		loadData3();
		return false;
		});
		*/
		/*
			$(document).on('keyup', '#s_opv1', function(e) {
			e.preventDefault();
			loadData3();
			return false;
			});
		*/
		$(document).on('click', 'tr.onclick td:not(.notclick)', function(e) {
			e.preventDefault();
			var id = $(this).parent().attr('id');
			showPopup3('Ubah', id);
			return false;
		});
		
		$('#pagging-2').html($('#pagging-1').html());
		$('#total-data').html('<?php echo $total_data; ?>');
		$('#per_page').val('<?php echo $per_page; ?>');
		$('.page_num').inputmask('integer');
		t_strip('.t-data');
		
		function showPopup3(act, id) {
			var url = base_marketing_operasional + 'persediaan_awal/reserve/reserve_persediaan_popup.php?act=' + act + '&id=' + id;
			setPopup(act + ' Reserve', url, 700, 550);
			return false;
		}
		
		function showPopup2(act, id, nm)
		{
			var url = base_marketing_operasional + 'persediaan_awal/reserve/spp_popup.php' + '?act=' + act + '&id=' + id + '&nm=' + nm;
			setPopup(act + ' SPP', url, 830, 550);	
			return false;
		}
		
		
		$(document).on('click', '#surat', function(e) {
			e.preventDefault();
				var cb_data = $('.cb_data').val();
				var act = $('#act').val();
				
			var checked = $(".cb_data:checked").length;
			if (checked < 1) {
				alert('Pilih data yang akan dicetak surat.');
				} else if (confirm('Apa anda yakin akan mencetak surat untuk data ini?')) {
				e.preventDefault();
				// location.href = base_marketing_operasional + 'persediaan_awal/reserve/surat_reserve_persediaan.php' + '?act=' + act + '&cb_data=' + cb_data;
				location.href = base_marketing_operasional + 'persediaan_awal/reserve/surat_reserve_persediaan.php?' + $('#form').serialize();
				//cetakSurat();
			}
			return false;
		});
		
		$(document).on('click', '#print', function(e) {
			e.preventDefault();
			location.href = base_marketing_operasional + 'persediaan_awal/reserve/print_reserve_persediaan.php?' + $('#form').serializeArray();
			return false;
		});
	});
</script>

<?php
	close($conn);
	exit;
?>
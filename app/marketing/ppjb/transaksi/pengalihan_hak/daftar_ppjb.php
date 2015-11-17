<?php
	require_once('../../../../../config/config.php');
	die_login();
	// die_app('P');
	die_mod('P09');
	$conn = conn($sess_db);
	ex_conn($conn);
	
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
	CS_PPJB z
	$query_search
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
					var kode_blok = $(this).data('kode_blok'),
					no_va = $(this).data('no_va'),
					nomor = $(this).data('nomor'),
					tanggal = $(this).data('tanggal'),
					nama = $(this).data('nama'),
					no_identitas = $(this).data('no_identitas'),
					alamat = $(this).data('alamat'),
					tlp_rmh = $(this).data('tlp_rmh'),
					tlp_lain = $(this).data('tlp_lain'),
					email = $(this).data('email'),
					suami_istri = $(this).data('suami_istri'),
					fax = $(this).data('fax'),
					harga = $(this).data('harga');
					
					parent.jQuery('#kode').val(kode_blok);
					parent.jQuery('#no_va').val(no_va);
					parent.jQuery('#no_ppjb_awal').val(nomor);
					parent.jQuery('#tanggal_awal').val(tanggal);
					parent.jQuery('#pihak_pertama').val(nama);
					parent.jQuery('#no_id').val(no_identitas);
					parent.jQuery('#alamat').val(alamat);
					parent.jQuery('#tlp1').val(tlp_rmh);
					parent.jQuery('#tlp3').val(tlp_lain);
					parent.jQuery('#email').val(email);
					parent.jQuery('#suami_istri').val(suami_istri);
					parent.jQuery('#no_fax').val(fax);
					parent.jQuery('#harga_awal').val(harga);
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
						<th class="w10">NO VA</th>
						<th class="w10">BLOK / NOMOR</th>
						<th class="w15">NO. PPJB</th>
						<th class="w20">NAMA PEMBELI</th>
					</tr>
					<?php
						if ($total_data > 0)
						{
							$query = "
							SELECT *, z.TANGGAL, a.NOMOR_CUSTOMER
							FROM
							CS_PPJB z
							JOIN SPP a ON z.KODE_BLOK = a.KODE_BLOK
							LEFT JOIN STOK b ON a.KODE_BLOK = b.KODE_BLOK
							LEFT JOIN TIPE c ON b.KODE_TIPE = c.KODE_TIPE
							LEFT JOIN HARGA_TANAH d ON b.KODE_SK_TANAH = d.KODE_SK
							LEFT JOIN HARGA_BANGUNAN e ON b.KODE_SK_BANGUNAN = e.KODE_SK
							LEFT JOIN FAKTOR f ON b.KODE_FAKTOR = f.KODE_FAKTOR
							$query_search
							order by z.kode_blok
							";
							$obj = $conn->selectlimit($query, $per_page, $page_start);
							
							while( ! $obj->EOF)
							{
								$harga 		= $obj->fields['HARGA_TOTAL'];
								$luas_bangunan 		= $obj->fields['LUAS_BANGUNAN'];
								
								$tanah 				= $luas_tanah * ($obj->fields['HARGA_TANAH']) ;
								$disc_tanah 		= round($tanah * ($obj->fields['DISC_TANAH'])/100,0) ;
								$nilai_tambah		= round(($tanah - $disc_tanah) * ($obj->fields['NILAI_TAMBAH'])/100,0) ;
								$nilai_kurang		= round(($tanah - $disc_tanah) * ($obj->fields['NILAI_KURANG'])/100,0) ;
								$faktor				= $nilai_tambah - $nilai_kurang;
								$total_tanah		= $tanah - $disc_tanah + $faktor;
								$ppn_tanah 			= round($total_tanah * ($obj->fields['PPN_TANAH'])/100,0) ;
								
								$bangunan 			= $luas_bangunan * ($obj->fields['HARGA_BANGUNAN']) ;
								$disc_bangunan 		= round($bangunan * ($obj->fields['DISC_BANGUNAN'])/100,0) ;
								$total_bangunan		= $bangunan - $disc_bangunan;
								$ppn_bangunan 		= round($total_bangunan * ($obj->fields['PPN_BANGUNAN'])/100,0) ;
								
							
							?>
							<tr class="onclick" 
							data-kode_blok="<?php echo $obj->fields['KODE_BLOK']; ?>"
							data-no_va="<?php echo $obj->fields['NOMOR_CUSTOMER']; ?>"
							data-nomor="<?php echo $obj->fields['NOMOR']; ?>"
							data-tanggal="<?php echo f_tgl($obj->fields['TANGGAL']); ?>"
							data-nama="<?php echo $obj->fields['NAMA_PEMBELI']; ?>"
							data-no_identitas="<?php echo $obj->fields['NO_IDENTITAS']; ?>"
							data-alamat="<?php echo $obj->fields['ALAMAT_RUMAH']; ?>"
							data-tlp_rmh="<?php echo $obj->fields['TELP_RUMAH']; ?>"
							data-tlp_lain="<?php echo $obj->fields['TELP_LAIN']; ?>"
							data-email="<?php echo $obj->fields['ALAMAT_EMAIL']; ?>"
							data-suami_istri="<?php echo $obj->fields['NAMA_SUAMI_ISTRI']; ?>"
							data-fax="<?php echo $obj->fields['NO_FAX']; ?>"
							data-harga="<?php echo to_money($harga); ?>">
								<td><?php echo $obj->fields['NOMOR_CUSTOMER']; ?></td>
								<td><?php echo $obj->fields['KODE_BLOK']; ?></td>
								<td><?php echo $obj->fields['NOMOR']; ?></td>
								<td><?php echo $obj->fields['NAMA_PEMBELI']; ?></td>
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
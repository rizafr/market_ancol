<?php
	require_once('../../../../../config/config.php');
	die_login();
	// die_app('C');
	die_mod('C38');
	$conn = conn($sess_db);
	die_conn($conn);
	
	$nama_bank = (isset($_REQUEST['nama_bank'])) ? clean($_REQUEST['nama_bank']) : '';
	$kode_bank = $nama_bank;
	$query = "SELECT NAMA_BANK FROM BANK WHERE KODE_BANK = '$nama_bank'";
	$nama_bank = $conn->Execute($query)->fields['NAMA_BANK'];
	switch ($nama_bank)
	{
		case 'BCA'			: $ext = 'txt'; $bayar_via = 'BC'; $user_bayar = '2'; $ket_bayar = 'TRF BCA'; break;
		// case 'BUKOPIN'		: $ext = 'xls'; $bayar_via = 'BK'; $user_bayar = '2'; $ket_bayar = 'TRF BUKOPIN'; break;
		#case 'BUKOPIN_AD'	: $ext = 'xls'; $bayar_via = 'XX'; $user_bayar = '3'; $ket_bayar = 'AD BUKOPIN'; break;
		// case 'BUMIPUTERA'	: $ext = 'xls'; $bayar_via = 'BB'; $user_bayar = '2'; $ket_bayar = 'TRF BUMIPUTERA'; break;
		case 'BANK MANDIRI'		: $ext = 'csv'; $bayar_via = 'BM'; $user_bayar = '2'; $ket_bayar = 'TRF MANDIRI'; break;
		// case 'NIAGA'		: $ext = 'txt'; $bayar_via = 'BN'; $user_bayar = '2'; $ket_bayar = 'TRF NIAGA'; break;
		// case 'NIAGA_AD'		: $ext = 'txt'; $bayar_via = 'BN'; $user_bayar = '3'; $ket_bayar = 'AD NIAGA'; break;
		// case 'PERMATA'		: $ext = 'txt'; $bayar_via = 'BP'; $user_bayar = '2'; $ket_bayar = 'TRF PERMATA'; break;
	}
	if ($nama_bank == '')
	{
		echo '<script type="text/javascript">alert("Proses import untuk bank yang anda pilih belum tersedia.");</script>';
	}
	else
	{
		$path = 'C:BANK/' . strtolower($nama_bank);
		
	?>
	<table class="t-control">
		<tr>
			<td>
				<input type="button" id="save" value=" Simpan Data Pembayaran ">
			</td>
		</tr>
	</table>
	
	<form name="form-data" id="form-data" method="post">
		<table class="t-data w50">
			<tr>
				<th class="w5">NO.</th>
				<th class="w5"><input type="checkbox" id="cb_all"></th>
				<th class="w20">VIRTUAL ACCOUNT</th>
				<th class="w10">TANGGAL TRANSAKSI</th>
				<th class="w20">NILAI</th>
				
			</tr>
			<?php
				
				$uploaded_file = $path . "upload.$ext";
				//echo $uploaded_file;
				if ( ! file_exists($uploaded_file))
				{
					echo '<tr><td colspan="14">Error. File import tidak ditemukan.</td></tr>';
					exit;
				}
				
				require_once('../../../../../config/excel_reader.php');
				require_once('data_'. strtolower($nama_bank) .'.php');
				
				// require_once('load_data.php');
			}
			
			close($conn);
		?>
		
		<input type="hidden" name="user_bayar" id="user_bayar" value="<?php echo $user_bayar; ?>">
		<input type="hidden" name="bayar_via" id="bayar_via" value="<?php echo $bayar_via; ?>">
		<input type="hidden" name="ket_bayar" id="ket_bayar" value="<?php echo $ket_bayar; ?>">
		<input type="hidden" name="nama_bank" id="nama_bank" value="<?php echo $nama_bank;?>">
		<input type="hidden" name="kode_bank" id="nama_bank" value="<?php echo $kode_bank;?>">
	</form>
	
	<script type="text/javascript">
		jQuery(function($) {
			t_strip('.t-data');
		});
	</script>
	
	
	<script type="text/javascript">
		jQuery(function($) {
			
			$('#save').on('click', function(e) {
				e.preventDefault();
				var checked = $(".cb_data:checked").size();
				if (checked < 1)
				{
					alert('Pilih data yang akan disimpan.');
					return false;
				}
				
				var url		= base_marketing + 'collection_tunai/lain/upload_penerimaan_va/proses_data.php',
				data	= $('#form-data').serializeArray();
				
				$.post(url, data, function(result) {
					
					if (result.error == false)
					{
						var list_id_sukses = result.list_id_sukses.join(', #');
						$('#' + list_id_sukses).remove();
					}
					
					alert(result.msg+" \n Data Sukses (" +result.jumlah_sukses+ "): "+result.list_id_sukses+ " \n Data Gagal (" +result.jumlah_eror+ "): "+result.list_id_error);
					parent.loadData();
					
				}, 'json');
				
				return false;
			});
		});
	</script>	
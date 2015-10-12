<?php
	
	$l = 0;
	$list = array();
	$file = fopen($uploaded_file, 'r');
	while ( ! feof($file))
	{
		$l++;
		
		$line = fgets($file);
		if (strlen($line) < 71 || $line == '')
		{
			echo '<tr><td colspan="14">Error. Format baris : '.$l.'</td></tr>';
			continue;
		}
		$list[$l] = array(
		'no_va' => (string) clean(substr($line, 8, 19)),
		'nilai' => to_decimal(substr($line, 40,13 )),
		'tanggal_transaksi' => clean(substr($line, 57, 8))
		);
	}
	fclose($file);
	
	$in_no_pelanggan = array();
	$data_imp = array();
	$i=1;
	$j=1;
	$k=1;
	$l=1;
	$m=1;
	foreach ($list as $x)
	{
		$no_va = (string) $x['no_va'];
		$nomor_va = substr($no_va, -7);
		$nilai = (int) $x['nilai'];
		$rupiah = number_format($nilai,2,',','.');
		$spl = explode('/', $x['tanggal_transaksi']);
		
		if ((count($spl) == 3))
		{
		?>
		<tr>
			<td><?php echo $i++ ?></td>
			<td><input type="checkbox" name="cb_data[<?php echo $j++; ?>]" value="<?php echo $nomor_va; ?>" class="cb_data"></td>
			<td><input type="hidden" name="nomor_va[<?php echo $k++; ?>]" value="<?php echo $nomor_va; ?>"><?php echo $nomor_va ?></td>
			<td><input type="hidden" name="tanggal_transaksi[<?php echo $l++; ?>]" value="<?php echo $x['tanggal_transaksi']; ?>"> <?php echo $x['tanggal_transaksi']  ;  ?> </td>			
			<td><input type="hidden" name="nilai[<?php echo $m++; ?>]" value="<?php echo $nilai; ?>"><?php echo $rupiah ?></td>
		</tr>
		<?php	continue;
			
		}
		
	}
	
?>
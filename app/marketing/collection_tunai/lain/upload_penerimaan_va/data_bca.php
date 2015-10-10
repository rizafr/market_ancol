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
		$num = intval(substr($line, 0,6));
		if ($num<1)
		{
			continue;
		}

		$nilai = substr($line, 49,22 );
		$tmp = str_replace(',', '#', $nilai);
		$tmp = str_replace('.', ',', $tmp);
		$tmp = str_replace('#', '.', $tmp);

		$list[$l] = array(
		'no_va' => (string)clean(substr($line, 7, 19)),
		'nilai' => (string)clean($tmp),
		'tanggal_transaksi' => clean(substr($line, 73, 8))
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
		$no_va = $x['no_va'];
		$nilai = $x['nilai'];
		$nilai_save	= str_replace('.', '', $nilai);
		$nilai_save	= str_replace(',', '.', $nilai_save);
		$spl = explode('/', $x['tanggal_transaksi']);

		if ((count($spl) == 3))
		{
		?>
		<tr>
			<td><?php echo $i++ ?></td>
			<td><input type="checkbox" name="cb_data[<?php echo $j++; ?>]" value="<?php echo $no_va; ?>" class="cb_data"></td>
			<td><input type="hidden" name="nomor_va[<?php echo $k++; ?>]" value="<?php echo $no_va; ?>"><?php echo $no_va ?></td>
			<td><input type="hidden" name="tanggal_transaksi[<?php echo $l++; ?>]" value="<?php echo $x['tanggal_transaksi']; ?>"> <?php echo $x['tanggal_transaksi']  ;  ?> </td>			
			<td><input type="hidden" name="nilai[<?php echo $m++; ?>]" value="<?php echo $nilai_save; ?>"><?php echo $nilai ?></td>
		</tr>
		<?php	continue;
			
		}
		
	}
	
?>
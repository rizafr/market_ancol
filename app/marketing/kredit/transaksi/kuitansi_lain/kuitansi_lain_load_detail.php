<?php
require_once('../../../../../config/config.php');
$conn = conn($sess_db);
die_conn($conn);

$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
?>

<table class="t-data w100">
<tr>
	<th class="w5"><input type="checkbox" id="cb_all"></th>
	<th class="w5">NO.</th>
	<th class="w10">NOMOR KWITANSI</th>
	<th class="w10">TANGGAL BAYAR</th>
	<th class="w10">JUMLAH (Rp)</th>
	<th class="w50">REDAKASI</th>
	<th class="w30">CATATAN COLL</th>
</tr>

<?php
	$query = "
	SELECT *
	FROM 
		KWITANSI_LAIN_LAIN
	WHERE KODE_BLOK = '$id' AND KODE_PEMBAYARAN != '29'
	ORDER BY TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_KWITANSI'];
		$status	= $obj->fields['STATUS_KWT'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>">
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td> 			
			<td class="text-center"><?php echo $i; ?></td>
			<?php 
			if($status == '0')
			{?>
				<td>-</td>
			<?php
			}
			else 
			{?>
				<td><?php echo $id; ?></td>
			<?php
			}
			?>
			<td class="text-center"><?php echo date("d M Y", strtotime($obj->fields['TANGGAL']));  ?></td>
			<td class="text-center"><?php echo to_money($obj->fields['NILAI']);  ?></td>
			<?php
			if($status == '0')
			{?>
				<td>-</td>
			<?php
			}
			else
			{?>
				<td><?php echo $obj->fields['KETERANGAN'];  ?></td>
			<?php
			}
			?>
			
			<td><?php echo $obj->fields['CATATAN'];  ?></td>
			
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
?>
</table>

<script type="text/javascript">
jQuery(function($) {
	t_strip('.t-data');
});
</script>

<?php
close($conn);
exit;
?>
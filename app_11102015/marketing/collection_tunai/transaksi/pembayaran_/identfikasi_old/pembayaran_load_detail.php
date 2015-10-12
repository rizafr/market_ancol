<?php
require_once('../../../../../config/config.php');
$conn = conn($sess_db);
die_conn($conn);

$id					= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
$status_otorisasi	= (isset($_REQUEST['status_otorisasi'])) ? clean($_REQUEST['status_otorisasi']) : '';
?>

<table class="t-data w100">
<tr>
	<th class="w5"><input type="checkbox" id="cb_all"></th>
	<th class="w5">NO.</th>
	<th class="w15">NOMOR KWITANSI</th>
	<th class="w10">TANGGAL BAYAR</th>
	<th class="w10">JUMLAH (Rp)</th>
	<th class="w70">CATATAN</th>
</tr>

<?php

if ($status_otorisasi== 1)
	{
		$query = "	SELECT * FROM KWITANSI WHERE KODE_BLOK = '$id' ORDER BY TANGGAL";
	}
else if ($status_otorisasi == 2)
	{
		$query = "	SELECT * FROM KWITANSI_LAIN_LAIN WHERE KODE_BLOK = '$id' ORDER BY TANGGAL";
	}
	
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		$id 	= $obj->fields['NOMOR_KWITANSI'];
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
			<td><?php echo date("d M Y", strtotime($obj->fields['TANGGAL']));  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
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
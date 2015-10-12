<?php
require_once('../../../../../config/config.php');
$conn = conn($sess_db);
die_conn($conn);

$id		= (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';
?>

<table class="t-data w100">
<tr>
	<th class="w5">NO.</th>
	<th class="w15">NOMOR KWITANSI</th>
	<th class="w10">TANGGAL BAYAR</th>
	<th class="w10">JUMLAH (Rp)</th>
	<th class="w50">REDAKSI</th>
	<th class="w30">CATATAN</th>
</tr>

<?php
	$query = "
	
	SELECT NOMOR_KWITANSI, STATUS_KWT, TANGGAL,NILAI, KETERANGAN, CATATAN, TYPE = 'KWITANSI'
	FROM 
		KWITANSI
	WHERE KODE_BLOK = '$id'
	ORDER BY TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		$id = $obj->fields['NOMOR_KWITANSI'];
		$status	= $obj->fields['STATUS_KWT'];
		$type	= $obj->fields['TYPE'];
		?>
		<tr class="onclick" id="<?php echo $id; ?>" tipe = "<?php echo $type;?>">			
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
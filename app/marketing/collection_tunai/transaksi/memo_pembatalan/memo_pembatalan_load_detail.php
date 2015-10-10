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
	<th class="w15">KODE BLOK</th>
	<th class="w30">NAMA PEMBELI</th>
	<th class="w20">NILAI TRANSAKSI</th>
	<th class="w20">TOTAL PEMBAYARAN</th>
</tr>

<?php
	$query = "
	SELECT a.KODE_BLOK, b.NAMA_PEMBELI, a.NILAI_TRANSAKSI, a.TOTAL_PEMBAYARAN FROM 
	CS_MEMO_PEMBATALAN a LEFT JOIN SPP b ON a.KODE_BLOK = b.KODE_BLOK
	WHERE NOMOR_MEMO = '$id'
	ORDER BY a.KODE_BLOK
	";
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr>
			<td width="30" class="notclick text-center"><input type="checkbox" name="cb_data[]" class="cb_data" value="<?php echo $id; ?>"></td> 			
			<td class="text-center"><?php echo $i;  ?></td>
			<td><?php echo $obj->fields['KODE_BLOK'];  ?></td>
			<td><?php echo $obj->fields['NAMA_PEMBELI'];  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI_TRANSAKSI']);  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['TOTAL_PEMBAYARAN']);  ?></td>
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
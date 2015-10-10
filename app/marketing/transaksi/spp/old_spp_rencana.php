<?php
require_once('rencana_proses.php');
require_once('../../../../config/config.php');
die_login();
//die_app('');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);
?>

<script type="text/javascript">
jQuery(function($) {
	t_strip('.t-data');
	
	$('.dd-mm-yyyy').Zebra_DatePicker({
		format: 'd-m-Y',
		readonly_element : false,
		inside: true
	});
	
	$(document).on('click', '#Hapus', function(e) {
		e.preventDefault();
		if (confirm('Apa data SPP ini akan dihapus?')) {
			hapusData("Hapus");
		}
		return false;
	});
	
});

function load()
{
	if (popup) { popup.close(); }
	parent.loadData3();
	return false;
}

function rencana_pembayaran(act) {
	var id = '<?php echo $id; ?>';
	var url = base_marketing_transaksi + 'spp/rencana_popup.php' + '?act=' + act + '&id=' + id;
	setPopup( 'RENCANA PEMBAYARAN', url, 700, 300);
	return false; 
}

function hapusData(act)
{	
	// var id = document.getElementById("kode_blok").value;
	alert(id);
	var url		= base_marketing_transaksi + 'spp/rencana_proses.php?act=Hapus',
	data	= jQuery('#form').serializeArray();
	return false;
}
</script>

<button onclick="return rencana_pembayaran('Ubah')"> Rencana </button>
<input type="button" id="Hapus" value=" Hapus ">
<div class="clear"><br></div>

<table class="t-data w100">
<tr>
	<th class="w5">NO.</th>
	<th class="w15">KODE BLOK</th>
	<th class="w15">TANGGAL</th>
	<th class="w15">JENIS PEMBAYARAN</th>
	<th class="w15">NILAI (RP)</th>
	<th class="">KETERANGAN</th>
</tr>

<?php
	$query = "
	SELECT *
	FROM 
		RENCANA a
	LEFT JOIN JENIS_PEMBAYARAN b ON a.KODE_BAYAR = b.KODE_BAYAR
	WHERE a.KODE_BLOK = '$id'
	ORDER BY a.TANGGAL
	";
	$obj = $conn->execute($query);
	$i = 1;

	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		?>
		<tr>
			<td class="text-center"><?php echo $i; ?></td>
			<td><?php echo $obj->fields['KODE_BLOK'];  ?></td>
			<td><?php echo tgltgl(f_tgl($obj->fields['TANGGAL'])); ?></td>
			<td><?php echo $obj->fields['JENIS_BAYAR'];  ?></td>
			<td class="text-right"><?php echo to_money($obj->fields['NILAI']);  ?></td>
			<td><?php echo $obj->fields['KETERANGAN'];  ?></td>
		</tr>
		<?php
		$i++;
		$obj->movenext();
	}
	
?>
<input type="hidden" name="kode_blok" id="kode_blok" value="<?php echo $id ?>">
</table>
<div id="t-detail"></div>
<?php
close($conn);
exit;
?>
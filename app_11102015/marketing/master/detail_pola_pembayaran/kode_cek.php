
<script type="text/javascript">
<?php
require_once('../../../../config/config.php');
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$msg = '';
$error = FALSE;

$conn = conn($sess_db);
ex_conn($conn);

try{
	$obj = $conn->Execute("	SELECT count(*) as TOTAL FROM STOK WHERE KODE_BLOK = '$id'");
	ex_not_found($obj->fields['TOTAL'], 'Kode Blok Tidak Terdaftar');
}
catch(Exception $e)
{
		$msg = $e->getmessage();
		$error = TRUE;
		if ($conn) { $conn->rollbacktrans(); } 
}


$query = "SELECT * FROM DETAIL_POLA_BAYAR WHERE KODE_BLOK = '$id'";
$harga = $conn->Execute($query);
?>

jQuery(function($) {
	<?php
	
	if($error){
		echo "alert('Data Kode Blok tidak tersedia');";
		echo "jQuery('.harga').val('');";
		echo "jQuery('#kode_blok').val('');";
	}
	while (!$harga->EOF) {
		$pola_bayar = $harga->fields['KODE_POLA_BAYAR'];

		$h_tanah = $harga->fields['HARGA_TANAH'];
		if(!isset($h_tanah)){
			$h_tanah = '0';
		}
		$h_bangunan = $harga->fields['HARGA_BANGUNAN'];
		if(!isset($h_tanah)){
			$h_bangunan = '0';
		}
		$hrg_tanah[$harga->fields['KODE_POLA_BAYAR']] = $h_tanah;
		$hrg_bangunan[$harga->fields['KODE_POLA_BAYAR']] = $h_bangunan;
		echo "jQuery('#harga_tanah_".$pola_bayar."').val(".$h_tanah.");";
		echo "jQuery('#harga_bangunan_".$pola_bayar."').val(".$h_bangunan.");";
		$harga->movenext();
	}
	?>
});
</script>
<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$kode_blok	= (isset($_REQUEST['kode_blok'])) ? clean($_REQUEST['kode_blok']) : '';
$tgl 		= (isset($_REQUEST['tgl'])) ? clean($_REQUEST['tgl']) : '';
try
{
	ex_login();
	ex_app('M');
	ex_mod('M02');
	$conn = conn($sess_db);
	ex_conn($conn);

	$conn->begintrans(); 
	$query = "DELETE FROM RENCANA WHERE KODE_BLOK = '$kode_blok' AND TANGGAL = CONVERT(DATETIME,'$tgl',105)";
	ex_false($conn->Execute($query), $query);
	
	$conn->committrans(); 
}
catch(Exception $e)
{
	$msg = $e->getmessage();
	$error = TRUE;
	if ($conn) { $conn->rollbacktrans(); } 
}

close($conn);


$conn = conn($sess_db);
die_conn($conn);
if(!$error){
?>

<script type="text/javascript">
	alert('Data Berhasil Dihapus');
</script>
<?php
}
?>
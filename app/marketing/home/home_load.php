<?php

require_once('../../../config/config.php');
require_once('../../../config/terbilang.php');
$terbilang = new Terbilang();
die_login();
$conn = conn($sess_db);
die_conn($conn);

//pengembalian stok yang lewat masa reserve
$tgl = f_tgl (date("Y-m-d"));
$query = "
	SELECT *
	FROM 
		RESERVE
	WHERE
	BERLAKU_SAMPAI < CONVERT(DATETIME,'$tgl',105)
	";
	$obj = $conn->execute($query);

	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		
		$query2 = "
		UPDATE STOK
		SET STATUS_STOK = 1, TERJUAL = 0
		WHERE
			KODE_BLOK = '$id'
		";
		$obj2 = $conn->execute($query2);
		
		$obj->movenext();
	}

//penghapusan reserve yang telah lewat masa pembuatan SPP
$conn->Execute("DELETE FROM RESERVE
	WHERE
	BERLAKU_SAMPAI < CONVERT(DATETIME,'$tgl',105)
");


//pengembalian stok yang spp lewat masa tenggang
$tgl = f_tgl (date("Y-m-d"));
$obj = $conn->Execute("SELECT BATAS_DISTRIBUSI FROM CS_PARAMETER_MARK");
$query_batas	= $obj->fields['BATAS_DISTRIBUSI'];
$obj = $conn->Execute("SELECT TENGGANG_DISTRIBUSI FROM CS_PARAMETER_MARK");
$query_tenggang	= $obj->fields['TENGGANG_DISTRIBUSI'];
$total_hari = $query_batas + $query_tenggang;

$query = "
	SELECT *
	FROM 
		SPP
	WHERE
	DATEADD(dd,$total_hari,TANGGAL_SPP) < CONVERT(DATETIME,'$tgl',105)
	AND STATUS_SPP = 2
	";
	$obj = $conn->execute($query);

	while( ! $obj->EOF)
	{
		$id = $obj->fields['KODE_BLOK'];
		
		$query2 = "
		UPDATE STOK
		SET STATUS_STOK = 1, TERJUAL = 0
		WHERE
			KODE_BLOK = '$id'
		";
		$obj2 = $conn->execute($query2);
		
		$obj->movenext();
	}

//penghapusan spp yang telah lewat tenggang
$conn->Execute("DELETE FROM SPP 
WHERE
	CONVERT(DATETIME,'$tgl',105) > DATEADD(dd,$total_hari,TANGGAL_SPP)
	AND STATUS_SPP = 2
");

//belum distribusi
$now = explode('-', $tgl);
$now[1] = intval($now[1]);
$query = "select COUNT(STATUS_SPP) AS TOTAL_BELUM_DISTRIBUSI FROM SPP WHERE STATUS_SPP IS NULL OR STATUS_SPP != 1";
$obj = $conn->execute($query);

$belum_distribusi		= $obj->fields['TOTAL_BELUM_DISTRIBUSI'];

//belum otorisasi
$query = "select COUNT(OTORISASI) AS TOTAL_BELUM_OTORISASI FROM SPP WHERE OTORISASI != 1";
$obj = $conn->execute($query);

$belum_otorisasi		= $obj->fields['TOTAL_BELUM_OTORISASI'];

//belum identifikasi
$query = "select COUNT(SISA) AS TOTAL_BELUM_IDENTIFIKASI FROM CS_VIRTUAL_ACCOUNT WHERE SISA != 0";
$obj = $conn->execute($query);

$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM STOK WHERE STATUS_STOK = '0' AND TERJUAL = '0' ";
$total_stok_awal = $conn->execute($query)->fields['TOTAL'];

$query = "SELECT COUNT(KODE_BLOK) AS TOTAL FROM STOK WHERE STATUS_STOK = '1' AND TERJUAL = '0' ";
$total_stok = $conn->execute($query)->fields['TOTAL'];

$query = "SELECT COUNT(s.KODE_BLOK) AS TOTAL FROM STOK s JOIN RESERVE r ON s.KODE_BLOK = r.KODE_BLOK  WHERE s.STATUS_STOK = '1' AND s.TERJUAL = '1' AND MONTH(r.TANGGAL_RESERVE) = '$now[1]' AND YEAR(r.TANGGAL_RESERVE) = '$now[2]' ";
$total_stok_reserve = $conn->execute($query)->fields['TOTAL'];

$query = "SELECT COUNT(s.KODE_BLOK) AS TOTAL FROM STOK s JOIN SPP p ON s.KODE_BLOK = p.KODE_BLOK WHERE s.STATUS_STOK = '1' AND s.TERJUAL = '2' AND MONTH(p.TANGGAL_SPP) = '$now[1]' AND YEAR(p.TANGGAL_SPP)= '$now[2]' ";
$total_stok_terjual = $conn->execute($query)->fields['TOTAL'];

$belum_identifikasi		= $obj->fields['TOTAL_BELUM_IDENTIFIKASI'];

//belum ppjb
$query = "SELECT count(*) as TOTAL_BELUM_PPJB
FROM SPP WHERE KODE_BLOK NOT IN 
(
SELECT DISTINCT a.KODE_BLOK FROM CS_PPJB a
JOIN SPP b on a.KODE_BLOK = b.KODE_BLOK
)";
$obj = $conn->execute($query);

$belum_ppjb		= $obj->fields['TOTAL_BELUM_PPJB'];
/* End Pagination */
?>
<script type="text/javascript">
jQuery(function($) {
	
});
</script>
<div class="w48 f-left">
	<table class="t-data">
	<tr align="center">
		<td colspan="2"> Data SPP Periode <?php echo $terbilang->nama_bln_thn($tgl);?></td>
	</tr>
	<tr align="center">
		<td width="250" align="left">Jumlah SPP belum Distribusi</td>
		<td align="right">
			<?php echo $belum_distribusi; ?>
		</td>
	</tr>
	<tr align="center">
		<td width="250" align="left">Jumlah SPP belum PPJB</td>
		<td align="right">
			<?php echo $belum_ppjb; ?>
		</td>
	</tr>
	<tr align="center">
		<td width="250" align="left">Jumlah SPP belum Otorisasi</td>
		<td align="right">
			<?php echo $belum_otorisasi; ?>
		</td>
	</tr>
	<tr align="center" id = 'detail_identifikasi'>
		<td width="250" align="left">Jumlah SPP belum Identifikasi</td>
		<td align="right">
			<?php echo $belum_identifikasi; ?>
		</td>
	</tr>

	</table>
</div>

<div class="w48 f-right">
	<table class="t-data">
	<tr>
		<td colspan="2" class="text-center">Data Stok Periode <?php echo $terbilang->nama_bln_thn($tgl);?></td>
	</tr>
	<tr align="center">
		<td align="left">Jumlah Stok Belum Otorisasi</td>
		<td align="right"><?php echo $total_stok_awal;?></td>
	</tr>
	<tr align="center">
		<td width="250" align="left">Jumlah Stok Siap Jual</td>
		<td align="right">
			<?php echo $total_stok; ?>
		</td>
	</tr>
	<tr align="center">
		<td width="250" align="left">Jumlah Stok Reserve</td>
		<td align="right">
			<?php echo $total_stok_reserve; ?>
		</td>
	</tr>
	<tr align="center">
		<td width="250" align="left">Jumlah Stok Terjual</td>
		<td align="right">
			<?php echo $total_stok_terjual; ?>
		</td>
	</tr>

	</table>
</div>



<?php
close($conn);
exit;
?>
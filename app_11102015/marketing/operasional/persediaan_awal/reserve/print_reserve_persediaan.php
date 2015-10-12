
<?php
	require_once('../../../../../config/config.php');
	die_login();
	die_app('A01');
	die_mod('PT02');
	$conn = conn($sess_db);
	die_conn($conn);
	
	$namafile = "Daftar Surat Reserve "."(".date('d F Y').").doc";
	header("Content-Disposition: attachment; filename=\"" . basename($namafile) . "\"");
	header("Content-type: application/octet-stream");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$s_opf1		= (isset($_REQUEST['s_opf1'])) ? clean($_REQUEST['s_opf1']) : '';
	$s_opv1		= (isset($_REQUEST['s_opv1'])) ? clean($_REQUEST['s_opv1']) : '';
	
	$query_search = '';
	if ($s_opv1 != '')
	{
		$query_search .= " WHERE $s_opf1 LIKE '%$s_opv1%' ";
	}
	
?>
<html>
	<body>	
		<style>
		body{
			font-family : arial;
		
		}
		 table{
			border: 1px solid #131304;  
			border:none;
			background-color: #fff ;
			font-size: 11px;
			
		}
		/* Zebra striping */

		tr:nth-of-type(odd) { 
			background: #fff; 
			
		}
		th { 
			background: #333; 
			color: white; 
			font-weight: bold; 
			text-align:center;
		}
		td, th { 
			padding: 5px; 
			text-align: left; 
			border: 1px solid #131304;  
		}
		h2{
			text-align:center;
			font-size: 20px;
			font-family : arial;
		}
		
		</style>
		
		<h2> RESERVE PERSEDIAAN </h2>
		<br />
		<table>
			<tr align ="center">
				<th>NO</th>
				<th>KODE BLOK</th>
				<th>NAMA CALON PEMBELI</th>
				<th>TANGGAL RESERVE</th>
				<th>BERLAKU SAMPAI</th>
				<th>ALAMAT</th>
				<th>TELEPON</th>
				<th>AGEN</th>
				<th>KOORDINATOR</th>
				
			</tr>
			
			<?php
				
				$query = "
				SELECT  
				KODE_BLOK,
				NAMA_CALON_PEMBELI,
				TANGGAL_RESERVE,
				BERLAKU_SAMPAI,
				ALAMAT,
				TELEPON,
				AGEN,
				KOORDINATOR
				FROM
				RESERVE 
				$query_search
				ORDER BY KODE_BLOK, TANGGAL_RESERVE
				";
				
				$obj = $conn->Execute($query);
				$i = 1;
				while( ! $obj->EOF)
				{
					$id = $obj->fields['KODE_BLOK'];
					$nama = $obj->fields['NAMA_CALON_PEMBELI'];
					$alamat = $obj->fields['ALAMAT'];
				?>
				<tr> 
					<td><?php echo $i++ ?></td>
					<td><?php echo $id; ?></td>
					<td><?php echo $obj->fields['NAMA_CALON_PEMBELI']; ?></td>
					<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['TANGGAL_RESERVE']))); ?></td>
					<td><?php echo tgltgl(date("d-m-Y", strtotime($obj->fields['BERLAKU_SAMPAI']))); ?></td>
					<td><?php echo $obj->fields['ALAMAT']; ?></td>
					<td><?php echo $obj->fields['TELEPON']; ?></td>
					<td><?php echo $obj->fields['AGEN']; ?></td>
					<td><?php echo $obj->fields['KOORDINATOR']; ?></td>
					
				</tr>
				<?php
					$obj->movenext();
				}
				
			?>
		</table>
		
		
	</body>
</html>

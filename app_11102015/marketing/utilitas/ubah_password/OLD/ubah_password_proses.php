<?php
require_once('../../../../config/config.php');

$msg = '';
$error = FALSE;

$act = (isset($_REQUEST['act'])) ? clean($_REQUEST['act']) : '';
$id = (isset($_REQUEST['id'])) ? clean($_REQUEST['id']) : '';

$user_id = (isset($_REQUEST['user_id'])) ? clean($_REQUEST['user_id']) : '';
$login_id = (isset($_REQUEST['login_id'])) ? clean($_REQUEST['login_id']) : '';
$passowrd_id = (isset($_REQUEST['passowrd_id'])) ? clean($_REQUEST['passowrd_id']) : '';
$full_name = (isset($_REQUEST['full_name'])) ? clean($_REQUEST['full_name']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	try
	{
		ex_login();
		ex_app('M');
		//ex_mod('');
		$conn = conn($sess_db);
		ex_conn($conn);

		$conn->begintrans(); 
			
		if ($act == 'Tambah') # Proses Tambah
		{
			//ex_ha('PU01', 'I');
			
			ex_empty($user_id, 'User id harus diisi.');
			ex_empty($login_id, 'Login id harus diisi.');
			ex_empty($passowrd_id, 'Password harus diisi.');
			ex_empty($full_name, 'Nama harus diisi.');
		
			$query = "SELECT COUNT(USER_ID) AS TOTAL FROM USER_APPLICATIONS WHERE USER_ID = '$user_id'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "User ID \"$user_id\" telah terdaftar.");
			
			$query = "SELECT COUNT(LOGIN_ID) AS TOTAL FROM USER_APPLICATIONS WHERE LOGIN_ID = '$login_id'";
			ex_found($conn->Execute($query)->fields['TOTAL'], "login id \"$login_id\" telah terdaftar.");
			
			$query = "INSERT INTO USER_APPLICATIONS (USER_ID, LOGIN_ID, PASSOWRD_ID, FULL_NAME)
			VALUES('$user_id', '$login_id', '$passowrd_id', '$full_name')";
			ex_false($conn->Execute($query), $query);
					
			$msg = "User \"$login_id\" berhasil ditambahkan.";
		}
		elseif ($act == 'Edit') # Proses Ubah
		{
			//ex_ha('PU01', 'U');
			
			ex_empty($user_id, 'User id harus diisi.');
			ex_empty($login_id, 'Login id harus diisi.');
			ex_empty($passowrd_id, 'Password harus diisi.');
			ex_empty($full_name, 'Nama harus diisi.');
			
			$ols_login_id = $conn->Execute("SELECT LOGIN_ID FROM USER_APPLICATIONS WHERE USER_ID = '$id'")->fields['LOGIN_ID'];
			
			if ($user_id != $id)
			{
				$query = "SELECT COUNT(USER_ID) AS TOTAL FROM USER_APPLICATIONS WHERE USER_ID = '$user_id'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "User ID \"$user_id\" telah terdaftar.");
			}
			
			if ($login_id != $ols_login_id)
			{
				$query = "SELECT COUNT(LOGIN_ID) AS TOTAL FROM USER_APPLICATIONS WHERE LOGIN_ID = '$login_id'";
				ex_found($conn->Execute($query)->fields['TOTAL'], "login id \"$login_id\" telah terdaftar.");
			}
					
			$query = "
			UPDATE USER_APPLICATIONS 
			SET USER_ID = '$user_id',
				LOGIN_ID = '$login_id',
				PASSOWRD_ID = '$passowrd_id',
				FULL_NAME = '$full_name'
			WHERE
				USER_ID = '$id'
			";
			ex_false($conn->Execute($query), $query);
			
			$msg = 'Data user berhasil diubah.';
		}
		elseif ($act == 'Hapus') # Proses Delete
		{
			//ex_ha('PU01', 'D');
			
			$act = array();
			$cb_data = $_REQUEST['cb_data'];
			ex_empty($cb_data, 'Pilih data yang akan dihapus.');
			
			foreach ($cb_data as $id_del)
			{
				if ($conn->Execute("DELETE FROM USER_APPLICATIONS WHERE USER_ID = '$id_del'")) {
					$act[] = $id_del;
				} else {
					$error = TRUE;
				}
			}
			
			$msg = ($error) ? 'Sebagian data gagal dihapus.' : 'Data user berhasil dihapus.';
		}
		
		$conn->committrans(); 
	}
	catch(Exception $e)
	{
		$msg = $e->getmessage();
		$error = TRUE;
		if ($conn) { $conn->rollbacktrans(); } 
	}

	close($conn);
	$json = array('act' => $act, 'error'=> $error, 'msg' => $msg);
	echo json_encode($json);
	exit;
}

die_login();
die_app('M');
//die_mod('');
$conn = conn($sess_db);
die_conn($conn);

if ($act == 'Edit')
{
	$obj = $conn->Execute("SELECT * FROM USER_APPLICATIONS WHERE USER_ID = '$id'");
	$user_id = $obj->fields['USER_ID'];
	$login_id = $obj->fields['LOGIN_ID'];
	$passowrd_id = $obj->fields['PASSOWRD_ID'];
	$full_name = $obj->fields['FULL_NAME'];
}
?>
<?php
	require_once('../config/config.php');
	$login_id=$_SESSION['LOGIN_ID']	;
	$query="SELECT 
		m.MENU_ID, 
		m.MENU_PATH,
		am.STATUS,
		a.APP_NAME,
		m.MENU_NAME,
		ua.FULL_NAME
	FROM 
		MENUS m
		LEFT JOIN APPLICATIONS a ON a.APP_ID = m.APP_ID
		LEFT JOIN APPLICATION_MENU am ON am.MENU_ID = m.MENU_ID
		LEFT JOIN USER_APPLICATIONS ua ON ua.USER_ID = am.USER_ID
	WHERE ua.LOGIN_ID='$login_id'
	ORDER BY m.MENU_ORDER ASC";
	
	$obj = $conn->Execute($query);
	
	while( ! $obj->EOF)
	{
	$menu =$obj->fields['MENU_PATH'];
	include ($menu) ;
	$obj->movenext();
	}
?>

<html>
<head>
<style type="text/css">
#myiframe {border: 0; position:fixed; top:0; left:0; right:0; bottom:0; width:100%; height:100%; z-index:999999999;} 
</style>
</head>
<body>
<div id="scroller">
<?php

$file = '../layout_sold.pdf';
//$file = '../files/upload.xlsx';
$filename = 'layout_sold.pdf'; /* Note: Always use .pdf at the end. */
if(file_exists($file)) {
	
// header('Content-type: application/pdf');
// header('Content-Disposition: inline; filename="' . $filename . '"');
// header('Content-Transfer-Encoding: binary');
// header('Content-Length: ' . filesize($file));
// header('Accept-Ranges: bytes');

// @readfile($file);

echo "<iframe name=\"myiframe\" id=\"myiframe\" src=\"$file\" width=\"100%\" style=\"height:100%\"></iframe>";


}else{
	echo "Tidak ada";
}

?>

</div>
</body>
</html>


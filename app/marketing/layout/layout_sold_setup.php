<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">    
<link rel="stylesheet" href="../config/css/antrian/bootstrap.css">
<link rel="stylesheet" href="../config/css/antrian/frontend.css">
<link rel="stylesheet" href="../config/css/antrian/font-awesome.css">
<link rel="stylesheet" href="../config/css/antrian/hover-min.css">

<style type="text/css">


    /* make keyframes that tell the start state and the end state of our object */
    @-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    @-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

    b{
        text-align:center;
        background-color : #006064;
        color: #FFFFF2;
    }

    td{
        padding-right: 2px!important; text-align: center !important;
        vertical-align: middle  !important;
        line-height: 10px  !important;  
        font-size: 14px;
        border: 1px solid !important;}

    </style>
</head> 

<?php
$data_sort = array();
for ($a = 0; $a <= 36; $a++) {
    $data_sort[$a] = array();
    for ($b = 0; $b <= 22; $b++) {
        $data_sort[$a][$b] = 'x';
    }
}

$limitPerLine = 22;
$currentData = 0;

$query = "
SELECT  
s.KODE_BLOK,
s.STATUS_STOK,
s.TERJUAL,
t.TIPE_BANGUNAN,
LOKASI
FROM 
STOK s
LEFT JOIN TIPE t ON s.KODE_TIPE = t.KODE_TIPE
LEFT JOIN HARGA_SK hs ON s.KODE_SK = hs.KODE_SK AND s.KODE_BLOK = hs.KODE_BLOK
LEFT JOIN LOKASI h ON s.KODE_LOKASI = h.KODE_LOKASI
ORDER BY s.NO_VA ASC
";

$obj        = $conn->execute($query);
while( ! $obj->EOF)
{       

 $kode_blok = $obj->fields['KODE_BLOK'];
 $blok = explode("-", $kode_blok);
 $no_unit = (int) $blok[1];
 $jml= strlen($blok[0]);
 if($jml>2){
    $tower = substr($blok[0], 0,1);
    $lantai = substr($blok[0], 1,2);
}else{
    $tower = substr($blok[0], 0,1);
    $lantai = substr($blok[0], 1,3);    
}

if ($obj->fields['STATUS_STOK'] == '0' AND $obj->fields['TERJUAL'] == '0'){
    $status = 'STOK BELUM SIAP JUAL';
    $data_sort[$lantai][$no_unit] = 'Avail';
}else
if ($obj->fields['STATUS_STOK'] == '1' AND $obj->fields['TERJUAL'] == '0'){
    $status = 'STOK SUDAH SIAP JUAL';
    $data_sort[$lantai][$no_unit] = 'Avail';
}else 
if ($obj->fields['STATUS_STOK'] == '1' AND $obj->fields['TERJUAL'] == '1'){
    $status = 'STOK SUDAH DI RESERVE';
    $data_sort[$lantai][$no_unit] = 'Sold';
}else 
if ($obj->fields['STATUS_STOK'] == '1' AND $obj->fields['TERJUAL'] == '2'){
    $status = 'STOK SUDAH TERJUAL';
    $data_sort[$lantai][$no_unit] = 'Sold';
}
$obj->movenext();

}

?>
<div class="row">
    <div class="col-md-6">
        <table class="table" >					
            <tbody >
                <?php
                echo "<tr><td class = 'active' colspan='10'>Ancol View</td>";
                echo "<tr><td class = 'active'>LT</td>";

                for ($b = 22; $b >= 1; $b-=2) {
                    if ($b == 13 || $b == 14 || $b == 4) {
                        continue;
                    }
                    echo "<td class='active'>$b</td>";
                }

                for ($a = 36; $a > 0; $a--) {
                    if ($a == 13 || $a == 4 || $a == 14 || $a == 24 || $a == 34) {
                        continue;
                    }
                    echo "<tr><td class='active'>$a</td>";
                    for ($b = 22; $b >= 1; $b-=2) {
                        if ($b == 13 || $b == 14 || $b == 4) {
                            continue;
                        }
                        if ($data_sort[$a][$b] == 'Sold') {
                            echo "<td class = 'danger'>A$a-$b</td>";
                        } else if ($data_sort[$a][$b] == 'Avail') {
                            echo "<td class = 'success'>A$a-$b</td>";
                        } else if ($data_sort[$a][$b] == 'Booked') {
                            echo "<td class = 'danger'>A$a-$b</td>";
                        } else {
                            if ($a >= 2) {
                                if ($data_sort[$a - 1][$b] == 'x') {
                                    echo "<td class = 'active'>Void</td>";
                                } else {
                                    echo "<td class = 'active'>Garden</td>";
                                }
                            } else {
                                echo "<td class = 'active'>Garden</td>";
                            }
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>	
    </div>

    <div class="col-md-6">
        <table class="table" >					
            <tbody >
                <?php
                echo "<tr><td class = 'active' colspan='11'>Sea View</td>";
                echo "<tr><td class = 'active'>LT</td>";

                for ($b = 21; $b >= 1; $b-=2) {
                    if ($b == 13) {
                        continue;
                    }
                    echo "<td class='active'>$b</td>";
                }

                for ($a = 36; $a > 0; $a--) {
                    if ($a == 13 || $a == 14 || $a == 4 || $a == 24 || $a == 34) {
                        continue;
                    }
                    echo "<tr>
                    <td class='active'>$a</td>";
                    for ($b = 21; $b >= 1; $b-=2) {
                        if ($b == 13 || $b == 14 || $b == 4) {
                            continue;
                        }
                        if ($data_sort[$a][$b] == 'Sold') {
                            echo "<td class = 'danger'>A$a-$b</td>";
                        } else if ($data_sort[$a][$b] == 'Avail') {
                            echo "<td class = 'success'>A$a-$b</td>";
                        } else if ($data_sort[$a][$b] == 'Booked') {
                            echo "<td class = 'danger'>A$a-$b</td>";
                        } else {
                            if ($a >= 2) {
                                if ($data_sort[$a - 1][$b] == 'x') {
                                    echo "<td class = 'active'>Void</td>";
                                } else {
                                    echo "<td class = 'active'>Garden</td>";
                                }
                            } else {
                                echo "<td class = 'active'>Garden</td>";
                            }
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        Ket : 
        <table class="table">
            <tr>
                <td class="success">
                    Marketable
                </td>
                <td class="danger">
                    SOLD
                </td>
                <td class="active">
                    SKY GARDEN
                </td>
            </tr>
        </table>

    </div>
</div>


<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
$jsessionid = $_GET["jsessionid"];
$idFile = $_GET["idFile"];
$size = $_GET["size"];
$pdf = '';
$array=explode("_",$idFile);
$doc = $array[0];
$accion = $array[1];
$cod_firmante = $array[2];
$num_firma = $array[3];

$prefijo = "C:/wamp/www/";
$pdf_path = '';

$pdf_path =  $prefijo.'framework/pdf/'.$doc.'.pdf';
if($accion=='VB') {
    if($num_firma==1) {
        $pdf_path =  $prefijo.'framework/pdf/'.$doc.'_temp.pdf';
    }
}else{
    if($num_firma==1) {
        $pdf_path =  $prefijo.'framework/pdf/'.$doc.'_temp.pdf';
    }
}

if ($size == '1') {
    $size = filesize($pdf_path);
    echo $size;
}else {
    $handle = fopen($pdf_path, "r");
    $pdf = fread($handle, filesize($pdf_path));
    fclose($handle);
    echo $pdf;
}
?>
<?php

$doc = $_POST["idFile"];
$array=explode("_",$doc);
$coDocumento = $array[0];
$accion = $array[1];
$cod_firmante = $array[2];
$num_firma = $array[3];
// para linux
$prefijo = "/var/www/intranet/";
$uploaddir = $prefijo.'sharepointdocsproyecto/';
$retorno = 'OK';

if (is_uploaded_file($_FILES['PDFFile']['tmp_name'])) {
    $filename = $_FILES["PDFFile"]["name"];
    $uploadfile = $uploaddir . basename($filename);
    // copia el archivo subido al directorio en el servidor
    move_uploaded_file($_FILES['PDFFile']['tmp_name'], $uploadfile);
    sleep(1);
    $finalfile = $uploaddir . $coDocumento.'_temp.pdf';
    rename($uploadfile , $finalfile);
    $retorno = 'OK';
}
echo $retorno;
?>

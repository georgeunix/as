<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
$coDocumento = $_GET["doc"];
$codFirmante = $_GET["cod_firmante"];
$num_firma = $_GET["num_firma"];
$cant_firmas = $_GET["cant_firmas"];
$accion = $_GET["accion"];

$update="";
if (isset($_GET["update"])) {
    $update = $_GET["update"];
} else {
    $update = "";
}

// para linux
$prefijo = "/var/www/intranet/";
$pathPDFFile = "";

try {
    if($update=='1') {
        $pathPDFFinal = $prefijo . 'sharepointdocsproyecto/' . $coDocumento . '_temp.pdf';
        header('Content-Disposition: inline; filename=certificado' . $coDocumento . '_temp.pdf');
        $pdf = readfile($pathPDFFinal);

    }else {
        $pathPDFFinal = $prefijo . 'sharepointdocsproyecto/' . $coDocumento . '.pdf';
        header('Content-Disposition: inline; filename=certificado' . $coDocumento . '.pdf');
        $pdf = readfile($pathPDFFinal);
    }
}catch (Exception $exc){
    $pdf = $exc->getTraceAsString();
}

echo $pdf;
?>

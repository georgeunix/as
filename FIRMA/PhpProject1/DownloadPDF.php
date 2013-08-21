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

$prefijo = "C:/wamp/www/";
$pathPDFFile = $prefijo . 'framework/pdf/' . $coDocumento . '.pdf';
//$pathPDFFinal = $prefijo . 'framework/pdf/' . $coDocumento . '.pdf';

$numberPDF = 'INFORME Nº 1231231-SGPIT/2012';
$pfxFileServer = 'C:/ZyLicClntLib/2.2/pfx/zytrust_er_2012.pfx';
$pfxFilePassword = '20512321357';
$emailServerSITRADOC = 'SITRADOC@produce.gob.pe';
$xpos = 88;
$ypos = 169;
$fileLog = $prefijo."framework/pdf/log.txt";
$fp = fopen($fileLog, "a");
try {
    if($update=='1') {
        $pathPDFFinal = $prefijo . 'framework/pdf/' . $coDocumento . '.pdf';
        header('Content-Disposition: inline; filename=certificado' . $coDocumento . '.pdf');
        $pdf = readfile($pathPDFFinal);

    }else {

        $client = new SoapClient("http://localhost:5010/ZySubmitService?wsdl");
        $certifica = false;

        if ($accion == 'VB') {
            if ($num_firma == 1) {
                $certifica = true;
            }
        } else {
            if ($accion == 'SIGN') {
                if ($num_firma == 1) {
                    $certifica = true;
                }
            }
        }

        fwrite($fp, "accion" . $accion);
        if ($certifica) {
            // Certificar PDF
            $pathPDFFinal = $prefijo . 'framework/pdf/' . $coDocumento . '_temp.pdf';
            $certifyPDFRequest = new stdClass();
            $certifyPDFRequest->cantFirmas = $cant_firmas;
            $certifyPDFRequest->pfxFile = $pfxFileServer;
            $certifyPDFRequest->pfxPassword = $pfxFilePassword;
            $certifyPDFRequest->tsaServer = '';
            $certifyPDFRequest->email = $emailServerSITRADOC;
            $certifyPDFRequest->pathFilePDF = $pathPDFFile;
            $certifyPDFRequest->numberDoc = $numberPDF;
            $certifyPDFRequest->xPos = $xpos;
            $certifyPDFRequest->yPos = $ypos;
            $certifyPDFRequest->pathFileCertified = $pathPDFFinal;

            fwrite($fp, "cant_firmas" . $cant_firmas);
            fwrite($fp, "pfxFile" . $pfxFileServer);
            fwrite($fp, "pfxPassword" . $pfxFilePassword);
            fwrite($fp, "email" . $emailServerSITRADOC);
            fwrite($fp, "pathPDFFile" . $pathPDFFile);
            fwrite($fp, "numberDoc" . $numberPDF);
            fwrite($fp, "pathFileCertified" . $pathPDFFinal);

            $certifyPDFResponse = $client->certificaPDF($certifyPDFRequest);
            $responseCertify = $certifyPDFResponse->resp;
            if ($responseCertify == 'OK') {
                //            $handle = fopen($pathPDFFinal, "r");
                //            $pdf = fread($handle, filesize($pathPDFFinal));
                //            fclose($handle);
                header('Content-Disposition: inline; filename=certificado' . $coDocumento . '.pdf');
                $pdf = readfile($pathPDFFinal);
            } else {
                $pdf = null;
            }
        } else {
            $pathPDFFinal = $prefijo . 'framework/pdf/' . $coDocumento . '.pdf';
            header('Content-Disposition: inline; filename=certificado' . $coDocumento . '.pdf');
            $pdf = readfile($pathPDFFinal);
        }
    }
} catch (Exception $exc) {
    $pdf = $exc->getTraceAsString();
    fwrite($fp, "Excepcion ".$exc->getTraceAsString() . PHP_EOL);
}
fclose($fp);
echo $pdf;
?>

<?php
session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

mt_srand(time());
$sessionID = mt_rand(1, 50000);
$sessionID = 'PHP' . $sessionID;
$email = 'jsevillano@zytrust.com';
$place = 'Lima/Peru';
$subject = 'He leido el documento';

// Parametros para la certificacion del PDF
$numberPDF = '';
$pfxFileServer = '/td/ZyLicClntLib/2.2/pfx/zytrust_er_2012.pfx';
$pfxFilePassword = '20512321357';
$emailServerSITRADOC = 'SITRADOC@produce.gob.pe';
$xpos = 88;
$ypos = 169;
$certifyPDFResponse = '';




$responseError = "";
$coDocumento = $_GET["doc"];
$codFirmante = $_GET["cod_firmante"];
$num_firma = $_GET["num_firma"];
$cant_firmas = $_GET["cant_firmas"];
$accion = $_GET["accion"];


// para linux
$prefijo = "/var/www/intranet/";
$fileLog = $prefijo . "framework/pdf/log.txt";
$fp = fopen($fileLog, "a");
$pathPDFFile = "";
$pathPDFFinal = "";
$responseCertify = false;
$allOperationOK = true;
$val = "?doc=" . $coDocumento . "&cod_firmante=" . $codFirmante . "&num_firma=" . $num_firma . "&cant_firmas=" . $cant_firmas . "&accion=" . $accion;
$pathApp = "http://172.16.247.20/";
//$pathApp = "http://localhost/";
$client = '';
$usuario = 'lsantos';

// Consulta Numero
$server = '172.16.247.22';
$usu = "web_tramite";
$cla = "deimos";
$bd = "DB_TRAMITE_DOCUMENTARIO";
$resEmp = '';
$queryConsultaNumeroDoc = "EXEC	[web_tramite].[sp_traer_contador_Doc_Gen_SYTRUS]
		@IDDOCUMENTO = $coDocumento,
		@USUARIO = 'lsantos',
		@ASUNTO =  '-',
		@OBSERVACIONES = N'-',
		@ID_TIPO_DOCUMENTO = 4,
		@CODDEP = 5";
$conexion = '';

// Sino se toma el documento PDF
$certifica = false;

if ($accion == 'VB') {
    if ($num_firma == 1) {
        //  Verificar si la accion es firma o vb , si es VB(VISADO) y es el primer visado
        //  entonces se certifica el PDF y se almacena  en una archivo temporal
        $certifica = true;
    }
} else {
    if ($accion == 'SIGN') {
        //  Si es SIGN(FIRMA PRINCIPAL) y hay un solo firmante se certifica el PDF
        //  y se almacena en un archivo temporal
        if ($num_firma == 1) {
            $certifica = true;
        }
    }
}

fwrite($fp, "INICIO ".$coDocumento); 

if ($certifica) {
    $client = new SoapClient("http://localhost:5010/ZySubmitService?wsdl");
    $pathPDFFile = $prefijo . 'sharepointdocsproyecto/' . $coDocumento . '.pdf';
    
       
    if (file_exists($pathPDFFile)) {
        $allOperationOK = true;
        try {
            // conecta bd para obtener numero
            $conexion = mssql_connect($server, $usu, $cla);
            mssql_select_db($bd, $conexion);
            
            $sql_proy_doc = "select ASUNTO,OBSERVACIONES,ID_TIPO_DOCUMENTO,CODDEP from DAT_DOCUMENTO_PROYECTO where ID_DOCUMENTO_PROY=$coDocumento";
            $responseQuery = mssql_query($sql_proy_doc, $conexion);
            $asunto = "";
            $observaciones = "";
            $id_tipo_documento = "";
            $coddep = "";

            while ($rs = mssql_fetch_assoc($responseQuery)) {
                $asunto = $rs["ASUNTO"];
                $observaciones = $rs["OBSERVACIONES"];
                $id_tipo_documento = $rs["ID_TIPO_DOCUMENTO"];
                $coddep = $rs["CODDEP"];
            }
            
            fwrite($fp, "ASUNTO: ");
            fwrite($fp, $asunto);
            fwrite($fp, "OBS: ");
            fwrite($fp, $observaciones);
            fwrite($fp, "ID_TIPO_DOCUMENTO ");
            fwrite($fp, $id_tipo_documento);
            fwrite($fp, "CODDEP ");
            fwrite($fp, $coddep);
            
//            $queryConsultaNumeroDoc = "exec [web_tramite].[sp_traer_contador_Doc_Gen_SYTRUS] 
//		@IDDOCUMENTO = " . $coDocumento . ",
//		@USUARIO = 'lsantos',
//		@ASUNTO =  " . $asunto . ",
//		@OBSERVACIONES = " . $observaciones . ",
//		@ID_TIPO_DOCUMENTO = " . $id_tipo_documento . ",
//		@CODDEP = " . $coddep;
            
                
            $queryConsultaNumeroDoc = "exec [web_tramite].[sp_traer_contador_Doc_Gen_SYTRUS] 
		 '" . $coDocumento . "',
		 '" . $usuario . "',
		 '" . $asunto . "',
		 '" . $observaciones . "',
		 '" . $id_tipo_documento . "',
		 '" . $coddep . "'";
            
            fwrite($fp, "queryConsultaNumeroDoc: ");
            fwrite($fp, $queryConsultaNumeroDoc);
            
//            $proc = mssql_init('web_tramite.sp_traer_contador_Doc_Gen_SYTRUS', $conexion);
//            mssql_bind($proc, '@IDDOCUMENTO', $coDocumento, SQLINT2);
//            mssql_bind($proc, '@USUARIO', $usuario, SQLVARCHAR);
//            mssql_bind($proc, '@ASUNTO', $asunto, SQLVARCHAR);
//            mssql_bind($proc, '@OBSERVACIONES', $observaciones, SQLVARCHAR);
//            mssql_bind($proc, '@ID_TIPO_DOCUMENTO', $id_tipo_documento, SQLINT2);
//            mssql_bind($proc, '@CODDEP', $coddep, SQLINT2);
//            $responseQuery2 = mssql_execute($proc);
//            $responseQuery2row = mssql_fetch_row($responseQuery2);
//            $numberPDF = trim($responseQuery2row['INDICATIVO']);
            // consulta numero
            $responseQuery2 = mssql_query($queryConsultaNumeroDoc, $conexion);
//
            while ($rs2 = mssql_fetch_assoc($responseQuery2)) {
                $numberPDF = trim($rs2['INDICATIVO']);
            }
            
            fwrite($fp, "NUMEROPDF: ");
            fwrite($fp, $numberPDF);
                        
            if ($numberPDF == null) {
                
                $allOperationOK = false;
                //$responseError = $queryConsultaNumeroDoc;
                $responseError = "Hubo un error , no se pudo obtener el numero de Documento ";
                
                fwrite($fp, "  ");
                fwrite($fp, $responseError);
                
            } else {
                // Certificar PDF
                $pathPDFFinal = $prefijo . 'sharepointdocsproyecto/' . $coDocumento . '_temp.pdf';
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

                $certifyPDFResponse = $client->certificaPDF($certifyPDFRequest);
                $responseCertify = $certifyPDFResponse->resp;
                if ($responseCertify == 'KO') {
                    $allOperationOK = false;
                    $responseError = "Hubo un error en la Proceso de Certificación del PDF";
                    
                    fwrite($fp, "  ");
                    fwrite($fp, $responseError);
                    
                }else{
                    $allOperationOK = true;
                    fwrite($fp, "PDF CERTIFICADO: ");
                }
            }
        } catch (Exception $exc) {
            $allOperationOK = false;
            $responseError = "Hubo un error en la Proceso de Certificación del PDF : " . $exc->getTraceAsString();
            fwrite($fp, "ERROR CERTIFICADO: ".$responseError);
        }
    } else {
        $allOperationOK = false;
        $responseError = "No existe en el servidor el archivo para firmar ";
        fwrite($fp, "ERROR firmadigital: ".$responseError);
    }
}



$pathContexto = 'framework/FIRMA/PhpProject1';
$coDocumento = $coDocumento . "_" . $accion . "_" . $codFirmante . "_" . $num_firma;

$urlPDF = $pathApp . 'framework/FIRMA/PhpProject1/DownloadPDF.php' . $val;
$urlEnviarPDF = $pathApp . 'framework/FIRMA/PhpProject1/EnviarPDF.php' . $val;

fwrite($fp, "urlPDF: ".$urlPDF);
fwrite($fp, "urlEnviarPDF: ".$urlEnviarPDF);
fwrite($fp, "FIN" . PHP_EOL);
fclose($fp);

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Firmar Documento</title>
        <script type="text/javascript">
            function cerrar() {
                window.close();
                return false;
            }

            function enviar() {
                document.getElementById("respEnvio").innerHTML = "";
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function()
                {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("respEnvio").innerHTML = xmlhttp.responseText;
                        if (xmlhttp.responseText == 'OK') {
                            alert("Documento fue Enviado Correctamente");
                        } else {
                            alert("Hubo un error al Firmar el Documento, por favor intente nuevamente");
                        }
                        window.close();
                    }
                }
                xmlhttp.open("GET", "<?php echo $urlEnviarPDF ?>&sesion=<?php echo $sessionID ?>", true);
                xmlhttp.send();
            }

            function verFirmado() {
                var ifpdf = document.getElementById("ifPDF");

                if (ifpdf.style.display == 'block') {
                    ifpdf.style.display = 'none';
                } else {
                    ifpdf.style.display = 'block';
                }
                ifpdf.src = '<?php echo $urlPDF ?>&update=1&sesion=<?php echo $sessionID ?>'
            }
        </script>
    </head>

    <body id="cuerpo">
        <?php if ($allOperationOK) { ?>
            <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                <tr>
                    <td class="tablatd" align="center" colspan="3">
                         FIRMA DIGITAL
                    </td>    
                <tr>
                    <td class="tablatd" align="center" colspan="3">
                <APPLET
                    ARCHIVE = "SignTrustApplet_V1.jar"
                    CODE = "com.zy.signtrust.agent.applet.gui.SignTrustApplet.class"
                    ALT = "No se pudo cargar el applet de firma digital.."
                    NAME = "SignTrustApplet"
                    WIDTH = "800"  HEIGHT = "240"
                    ALIGN = "CENTER"
                    VSPACE = "0"  HSPACE = "0">
                    <PARAM NAME="batch" VALUE = "0">
                    <PARAM NAME="number" VALUE = "3123123">
                    <PARAM NAME="jsessionid" VALUE = "<?php echo $sessionID ?>">
                    <PARAM NAME="action" VALUE = "<?php echo $accion ?>"> CERTIFY, CERTIFYSIGN, CERTIFYVB, SIGN, VB, ONLYSIGN
                    <PARAM NAME="email" VALUE = "<?php echo $email ?>">signer email for signing
                    <PARAM NAME="place" VALUE = "<?php echo $place ?>">place for signing
                    <PARAM NAME="subject" VALUE = "<?php echo $subject ?>">subject for signing

                    <PARAM NAME="files" VALUE = "<?php echo $coDocumento ?>"> file1; file2; file3; etc.
                    <PARAM NAME="filessign" VALUE = "<?php echo $coDocumento ?>">	file1_s; file2_s; file3_s; etc.
                    <PARAM NAME="downloadUrl" VALUE = "<?php echo $pathContexto ?>/PDFDownload.php">
                    <PARAM NAME="uploadUrl" VALUE = "<?php echo $pathContexto ?>/PDFUpload.php">
                    <PARAM NAME="tsa" VALUE ="">
                    <PARAM NAME="clocktype" VALUE ="PCCLOCK">
                    <PARAM NAME="heightsign" VALUE = "50">
                    <PARAM NAME="widthsign" VALUE = "200">
                    <PARAM NAME="signPosition" VALUE = "2">
                    <PARAM NAME="storetype" VALUE = "WIN">
                    <PARAM NAME="clocktype" VALUE = "TSA">
                    <PARAM NAME="visible" VALUE = "1">
                    <PARAM NAME="debug" VALUE = "1">
                    <PARAM NAME="id" VALUE = "123">
                    <PARAM NAME="path" VALUE = "C:\PRODUCE">
                    <PARAM NAME="xSign" VALUE = "">
                    <PARAM NAME="ySign" VALUE = "">
                    <PARAM NAME="cn" VALUE = "">
                    <PARAM NAME="fontsize" VALUE = "">
                    <PARAM NAME="nosignimage" VALUE = "">
                    <PARAM NAME="nosignfield" VALUE = "">
                    <PARAM NAME="pfxfile" VALUE = "">
                    <PARAM NAME="pfxpassword" VALUE = "">
                    <PARAM NAME="signimage" VALUE = "">
                    <PARAM NAME="vbnumber" VALUE = "">
                    <PARAM NAME="cclocktype" VALUE = "">
                    <PARAM NAME="ctsa" VALUE = "">
                    <PARAM NAME="cstoretype" VALUE = "">
                    <PARAM NAME="ccn" VALUE = "">
                    <PARAM NAME="cpfxfile" VALUE = "">
                    <PARAM NAME="cpfxpassword" VALUE = "">
                    <PARAM NAME="cemail" VALUE = "">
                    <PARAM NAME="cplace" VALUE = "">
                    <PARAM NAME="csubject" VALUE = "">
                    <PARAM NAME="nocrlgui" VALUE = "">
                    <PARAM NAME="signimageB64" VALUE = "">
                    Su explorador no soporta Java
                </APPLET>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td colspan="3" >
                <div id="respEnvio"><b></b></div>
            </td>
        </tr>
        <tr>
            <td>
                <input type="button" onclick="cerrar()" value="cerrar" id="cerrar" />
            </td>
            <td>
                <input type="button" onclick="verFirmado()" value="Ver Version Firmada" id="firmado" />
            </td>
            <td>
                <input type="button" onclick="enviar()" value="Enviar" id="enviar" />
            </td>
        </tr>
        <tr>
            <td class="tablatd" colspan="3" >
                <iframe id="ifPDF"  scrolling="auto" src="<?php echo $urlPDF ?>"
                        width="100%" height="500" align="center"></iframe>
            </td>
        </tr>
    </table>
<?php } else { ?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td>
                <input type="text" value="<?php echo $responseError ?>" />
            </td>
        </tr>
    </table>
<?php } ?>
</body>
</html>
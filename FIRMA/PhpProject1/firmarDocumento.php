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

$coDocumento = $_GET["doc"];
$codFirmante = $_GET["cod_firmante"];
$num_firma = $_GET["num_firma"];
$cant_firmas = $_GET["cant_firmas"];
$accion = $_GET["accion"];

$val = "?doc=" . $coDocumento . "&cod_firmante=" . $codFirmante . "&num_firma=" . $num_firma . "&cant_firmas=" . $cant_firmas . "&accion=" . $accion;
$prefijo = "http://localhost/";
$pathContexto = 'framework/FIRMA/PhpProject1';

$coDocumento = $coDocumento."_".$accion."_".$codFirmante."_".$num_firma;
$urlPDF = $prefijo . 'framework/FIRMA/PhpProject1/DownloadPDF.php' . $val;

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

            function verFirmado() {
                var ifpdf = document.getElementById("ifPDF");

                if(ifpdf.style.display == 'block'){
                    ifpdf.style.display = 'none';
                }else{
                    ifpdf.style.display = 'block';
                }
                ifpdf.src = '<?php echo $urlPDF ?>&update=1&sesion=<?php echo $sessionID ?>'
            }
        </script>
    </head>

    <body id="cuerpo">
        <table width="100%"  border="0" cellspacing="1" cellpadding="1">
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
                        <PARAM NAME="tsa" VALUE ="http://tsa.zytrust.com/tsa/">

                        <PARAM NAME="heightsign" VALUE = "50">
                        <PARAM NAME="widthsign" VALUE = "200">
                        <PARAM NAME="signPosition" VALUE = "2">
                        <PARAM NAME="storetype" VALUE = "WIN">
                        <PARAM NAME="clocktype" VALUE = "TSA">
                        <PARAM NAME="visible" VALUE = "1">
                        <PARAM NAME="debug" VALUE = "1">
                        <PARAM NAME="id" VALUE = "123">
                        <PARAM NAME="path" VALUE = "C:\SERVIR">
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
                <td>
                    <input type="button" onclick="cerrar()" value="cerrar" id="cerrar" />
                </td>
                <td>
                    <input type="button" onclick="verFirmado()" value="ver Version Firmada" id="firmado" />
                </td>
            </tr>
            <tr>
                <td class="tablatd" colspan="2" >
                    <iframe id="ifPDF"  scrolling="auto" src="<?php echo $urlPDF ?>"
                            width="100%" height="500" align="center"></iframe>
                </td>
            </tr>
        </table>
    </body>

</html>

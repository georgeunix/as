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

// Consulta Numero
$server = '172.16.247.22';
$usu = "web_tramite";
$cla = "deimos";
$bd = "DB_TRAMITE_DOCUMENTARIO";
$resEmp = '';
$usuario = 'lsantos';

$queryRegistraNumeroDoc = "EXEC	[web_tramite].[sp_newDoc_Gen_SYTRUS]
		@IDDOCUMENTO = $coDocumento,
		@USUARIO = 'lsantos',
		@ASUNTO =  '-',
		@OBSERVACIONES = N'-',
		@ID_TIPO_DOCUMENTO = 4,
		@CODDEP = 5";

$conexion = '';
$responseError = "OK"; //"El Documento fue Enviado Correctamente";
$numberPDF = "";

$prefijo = "/var/www/intranet/";
$uploaddir = $prefijo . 'sharepointdocsproyecto/';
$uploaddir2 = $prefijo . 'sharepointdocs/';
$origen = $uploaddir . $coDocumento . '_temp.pdf';

// borrar al desplegar
//$numberPDF = 'INFORME Nº 1231231-SGPIT/2012';
$numberPDF = $coDocumento; // Borrar y reemplazar por el valor final del sp_newDoc_Gen_SYTRUS

$destino2 = $uploaddir2 . $numberPDF . '.pdf';
$destino1 = $uploaddir . $coDocumento . '.pdf';

$fileLog = $prefijo . "framework/pdf/log.txt";
$fp = fopen($fileLog, "a");
// parametros de conexion a base de datos

$sql_update_firma = "update web_tramite.DAT_DETALLE_FIRMANTE set ID_ESTADOFIRMA=1 WHERE ID_DOCUMENTO_PROY='$coDocumento' AND codigo_trabajador='$codFirmante'";
$sql_update_documento = "update web_tramite.DAT_DOCUMENTO_PROYECTO set id_ciclofirma= 3 WHERE ID_DOCUMENTO_PROY='$coDocumento'";
$sql_proy_doc = "select ASUNTO,OBSERVACIONES,ID_TIPO_DOCUMENTO,CODDEP,USUARIO from DAT_DOCUMENTO_PROYECTO where ID_DOCUMENTO_PROY=$coDocumento";

fwrite($fp, "INICIO ");
try {
    // Registro de la numeracion definitiva
    $conexion = mssql_connect($server, $usu, $cla);
    mssql_select_db($bd, $conexion);

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
        $usuario = $rs["USUARIO"];
    }

    $queryRegistraNumeroDoc = "exec [web_tramite].[sp_newDoc_Gen_SYTRUS] 
		 '" . $coDocumento . "',
		 '" . $usuario . "',
		 '" . $asunto . "',
		 '" . $observaciones . "',
		 '" . $id_tipo_documento . "',
		 '" . $coddep . "'";

    $responseQuery2 = mssql_query($queryRegistraNumeroDoc, $conexion);

    while ($rs2 = mssql_fetch_assoc($responseQuery2)) {
        $numberPDF = trim($rs2['ID_INFORME_LEGAL']);        
    }
        
    /*
      // consulta numero
      $resEmp = mssql_query($queryRegistraNumeroDoc, $conexion);

      while ($rowEmp = mssql_fetch_assoc($resEmp)) {
      $numberPDF = trim($rowEmp['INDICATIVO']);
      }
     */

    rename($origen, $destino1);

    //if (!copy($origen, $destino1)) {
//        $responseError = "Error al copiar $origen...\n al destino:" + $destino1;
//        fwrite($fp, $responseError);
//        $responseError = "KO";        
//    } else {
    sleep(2);
    $destino2 = $uploaddir2 . $numberPDF . '.pdf';

    //  copy($destino1, $destino2);


    if (!copy($destino1, $destino2)) {
        $responseError = "Error al copiar $origen...\n al destino:" + $destino2;
        fwrite($fp, $responseError);
        $responseError = "KO";
    } else {
        // Actualizo el resgistro correspondiente a la firma
        if ($accion == 'VB') {
            mssql_query($sql_update_firma, $conexion);
            fwrite($fp, $coDocumento . "_VB" . $num_firma);
        } else {
            if ($accion == 'SIGN') {
                fwrite($fp, $coDocumento . "_SIGN" . $num_firma);
                mssql_query($sql_update_firma, $conexion);
                mssql_query($sql_update_documento, $conexion);
            }
        }
        $responseError = "OK";
    }
//    }
} catch (Exception $exc) {
    $responseError = "Hubo un error Durante del Envio del PDF : " . $exc->getTraceAsString();
    fwrite($fp, $responseError);
    $responseError = "KO";
}

fwrite($fp, "FIN" . PHP_EOL);
fclose($fp);
echo $responseError;
?>

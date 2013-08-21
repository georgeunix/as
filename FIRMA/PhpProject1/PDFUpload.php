<?php

$doc = $_POST["idFile"];
$array=explode("_",$doc);
$coDocumento = $array[0];
$accion = $array[1];
$cod_firmante = $array[2];
$num_firma = $array[3];
$prefijo = "C:/wamp/www/";
$uploaddir = $prefijo.'framework/pdf/';

// parametros de conexion a base de datos
$server = '172.16.247.22';
$usu = "web_tramite";
$cla = "deimos";
$bd = "DB_TRAMITE_DOCUMENTARIO";

$sql_update_firma = "update web_tramite.DAT_DETALLE_FIRMANTE set ID_ESTADOFIRMA=1 WHERE ID_DOCUMENTO_PROY='$doc' AND codigo_trabajador='$cod_firmante'";
$sql_update_documento = "update web_tramite.DAT_DOCUMENTO_PROYECTO set id_ciclofirma= 3 WHERE ID_DOCUMENTO_PROY='$doc'";
$retorno = 'OK';

if (is_uploaded_file($_FILES['PDFFile']['tmp_name'])) {
    $filename = $_FILES["PDFFile"]["name"];
    //$fileFinal = $coDocumento.'.pdf';
    //$uploadfile = $uploaddir . basename($filename);
    $uploadfile = $uploaddir . basename($filename);
    // copia el archivo subido al directorio en el servidor
    move_uploaded_file($_FILES['PDFFile']['tmp_name'], $uploadfile);
    sleep(1);
    $finalfile = $uploaddir . $coDocumento.'.pdf';
    rename($uploadfile , $finalfile);
    
    // Actualizo el resgistro correspondiente a la firma
    if ($accion == 'SIGN') {// firma
        $connectionInfo = array( "Database"=>$bd, "UID"=>$usu, "PWD"=>$cla);
        $conexion = sqlsrv_connect($server,$connectionInfo);
//        // para windows
        sqlsrv_query($conexion, $sql_update_firma);
        sqlsrv_query($conexion, $sql_update_documento);    
    }else {
        $connectionInfo = array( "Database"=>$bd, "UID"=>$usu, "PWD"=>$cla);
        $conexion = sqlsrv_connect($server,$connectionInfo);
        sqlsrv_query($conexion, $sql_update_firma);
    }
    $retorno = 'OK';
}
echo $retorno;
?>

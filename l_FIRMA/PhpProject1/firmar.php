<?php
session_start();

$_SESSION["doc"] ;
$_SESSION["cod_firmante"] ;
$_SESSION["num_firma"];      
        



$server = '172.16.247.22';
$usu = "web_tramite";
$cla = "deimos";
$bd = "DB_TRAMITE_DOCUMENTARIO";

$cod_firmante = $_GET["cod_firmante"];
$doc = $_GET["doc"];

//$link1 = mssql_pconnect($server, $usu, $cla);
//mssql_select_db($bd, $link1);
//$sql1 = "select f.id_codigofirma,f.id_estadofirma,(select COUNT(*) from web_tramite.DAT_DETALLE_FIRMANTE q wHERE q.ID_DOCUMENTO_PROY=f.ID_DOCUMENTO_PROY) as cantidad,,f.numero_firma as numero_firma 
//from web_tramite.DAT_DETALLE_FIRMANTE f WHERE f.ID_DOCUMENTO_PROY=$doc AND f.codigo_trabajador=$cod_firmante";


$sql1 = "select f.id_codigofirma,f.id_estadofirma,(select COUNT(*) from web_tramite.DAT_DETALLE_FIRMANTE q 
WHERE q.ID_DOCUMENTO_PROY=f.ID_DOCUMENTO_PROY) as cantidad,f.numero_firma as numero_firma ,
( SELECT count(*)  FROM  
                DAT_DETALLE_FIRMANTE q 
                WHERE q.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY AND q.CODIGO_TRABAJADOR  = f.CODIGO_TRABAJADOR and 
                q.NUMERO_FIRMA = (SELECT min(bx.NUMERO_FIRMA) FROM DAT_DETALLE_FIRMANTE bx 
                WHERE bx.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY AND bx.ID_ESTADOFIRMA = 2 ) ) as canbesign 
from web_tramite.DAT_DETALLE_FIRMANTE f 
WHERE f.ID_DOCUMENTO_PROY='$doc' AND f.codigo_trabajador='$cod_firmante' and f.id_estadofirma=2";

$sql_update_firma = "update web_tramite.DAT_DETALLE_FIRMANTE set ID_ESTADOFIRMA=1 WHERE ID_DOCUMENTO_PROY='$doc' AND codigo_trabajador='$cod_firmante'";
$sql_update_documento = "update web_tramite.DAT_DOCUMENTO_PROYECTO set id_ciclofirma= 3 WHERE ID_DOCUMENTO_PROY='$doc'";

$conexion = mssql_connect($server, $usu, $cla);
mssql_select_db($bd, $conexion);
$detalle_firmante = mssql_query($sql1, $conexion);

$id_codigofirma = "";
$id_estadofirma = "";
$cantidad = "";
$numero_firma = "";
$canbesign = "";

while ($rs = mssql_fetch_assoc($detalle_firmante)) {
    $id_codigofirma = $rs["id_codigofirma"];
    $id_estadofirma = $rs["id_estadofirma"];
    $cantidad = $rs["cantidad"];
    $numero_firma = $rs["numero_firma"];
    $canbesign = $rs["canbesign"];
};


if ($canbesign == '1') {
    if ($id_codigofirma == '1') {// firma 
        mssql_query($sql_update_firma, $conexion);
        mssql_query($sql_update_documento, $conexion);
    }else{
        //if(($id_codigofirma=='2') and ($numero_firma=='1')){
        // registrar en tabla final de documento el registro correspondiente al documento y su numero
        // update documento_detalle_firmante
        mssql_query($sql_update_firma, $conexion);
        //}
    }
}
echo"se firmo";
?>











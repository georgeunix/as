<?php
date_default_timezone_set('America/Lima');
setlocale(LC_ALL, "es_ES");

$server = '172.16.247.22';
$usu = "web_tramite";
$cla = "deimos";
$bd = "DB_TRAMITE_DOCUMENTARIO";

// en linux
$conexion = mssql_connect($server, $usu, $cla);
mssql_select_db($bd, $conexion);

// en windows
//$connectionInfo = array( "Database"=>$bd, "UID"=>$usu, "PWD"=>$cla);
//$conexion = sqlsrv_connect($server,$connectionInfo);

$queEmp = "select  (select t.APELLIDOS_TRABAJADOR + ' ' + t.NOMBRES_TRABAJADOR from db_general.jcardenas.h_trabajador t WHERE t.CODIGO_TRABAJADOR=f.CODIGO_TRABAJADOR) as nombre_firmante,
       (select  dep.DEPENDENCIA from db_general.jcardenas.h_trabajador t,db_general.jcardenas.H_DEPENDENCIA dep WHERE t.CODIGO_DEPENDENCIA=dep.CODIGO_DEPENDENCIA and t.CODIGO_TRABAJADOR=f.CODIGO_TRABAJADOR) as unidad_organica_firmante,

	   (select t.APELLIDOS_TRABAJADOR + ' ' + t.NOMBRES_TRABAJADOR from db_general.jcardenas.h_trabajador t WHERE t.CODIGO_TRABAJADOR=dt.CODIGO_TRABAJADOR_destino) as nombre_destino,
           (select DES_CARGO from TBL_CARGO_DEPENDENCIA tcar where tcar.CODDEP=dt.CODDEP_DESTINO)as cargo,
           (select  dep.DEPENDENCIA from db_general.jcardenas.h_trabajador t,db_general.jcardenas.H_DEPENDENCIA dep WHERE t.CODIGO_DEPENDENCIA=dep.CODIGO_DEPENDENCIA and t.CODIGO_TRABAJADOR=dt.CODIGO_TRABAJADOR_destino) unidad_organica_destino,
		      d.ASUNTO as asunto,'referencia' as referencia,
		      doc.descripcion as tipo_documento,
		      d.USUARIO
        from DAT_DOCUMENTO_PROYECTO d, DAT_DETALLE_FIRMANTE f , DAT_DETALLE_DESTINO dt,dbo.CLASE_DOCUMENTO_INTERNO doc
       where d.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY and d.ID_DOCUMENTO_PROY=dt.ID_DOCUMENTO_PROY and f.ID_CODIGOFIRMA=1 and doc.ID_CLASE_DOCUMENTO_INTERNO=d.ID_CLASE_DOCUMENTO_INTERNO and
        f.ID_DOCUMENTO_PROY=" . $_GET["id"];

// en linux
$resEmp = mssql_query($queEmp, $conexion);

//$resEmp = sqlsrv_query($conexion, $queEmp);
$xmlWordMege = '';
$tipo_doc = "";
$usuario = "";
$nom_destino = "";
$cargo = "";
$nom_firmante = "";
$u_organica_firmante = "";
$asunto = "";
$referencia = "";

// en linux
while ($rowEmp = mssql_fetch_assoc($resEmp)) {

    $tipo_doc = trim($rowEmp['tipo_documento']);
    $usuario = $rowEmp['USUARIO'];
    $nom_destino = $rowEmp['nombre_destino'];
    $cargo = $rowEmp['cargo'];
    $nom_firmante = $rowEmp['nombre_firmante'];
    $u_organica_firmante = $rowEmp['unidad_organica_firmante'];
    $asunto = $rowEmp['asunto'];
    $referencia = $rowEmp['referencia'];
}




$sql_receptores = "SELECT (t.APELLIDOS_TRABAJADOR + ' ' + t.NOMBRES_TRABAJADOR) as trabajador ,dep.DEPENDENCIA FROM DAT_DETALLE_FIRMANTE dat  
inner join db_general.jcardenas.h_trabajador t on dat.CODIGO_TRABAJADOR=t.CODIGO_TRABAJADOR
inner join db_general.jcardenas.H_DEPENDENCIA dep on t.CODIGO_DEPENDENCIA=dep.CODIGO_DEPENDENCIA
WHERE dat.ID_DOCUMENTO_PROY=" . $_GET["id"];

$array_receptores = mssql_query($sql_receptores, $conexion);

//primer receptor para las pruebas
$nombre_rec = "";
$u_organica_rec = "";
while ($rs = mssql_fetch_assoc($array_receptores)) {
    $nombre_rec = $rs["trabajador"];
    $u_organica_rec = $rs["DEPENDENCIA"];
}

$tipoTemplate = "";
$generate_doc = true;

$tipo_doc = trim($tipo_doc);

$fecha = strftime("%d de %B del %Y");
switch ($tipo_doc) {

    case "DICTAMEN":

        $tipoTemplate = 'DICTAMENLEGAL.docx';
        $xmlWordMege = '<DOCUMENTO>';
        $xmlWordMege .= '<NOM_ANIO>AÑO DEL BICENTENARIO</NOM_ANIO>';
        $xmlWordMege .= '<FECHA_DOC>' . $fecha . '</FECHA_DOC>';
        $xmlWordMege .= '<TIPO_DOC>' . $tipo_doc . '</TIPO_DOC>';
        $xmlWordMege .= '<DEPENDENCIA2></DEPENDENCIA2>';
        $xmlWordMege .= '<USUARIO>' . $usuario . '</USUARIO>';
        $xmlWordMege .= '<INICIALES_EMP></INICIALES_EMP>';
        $xmlWordMege .= '</DOCUMENTO>';
        break;


//    case "CIRCULAR":
//
//        $tipoTemplate = 'CIRCULAR.docx';
//        $xmlWordMege = '<DOCUMENTO>';
//        $xmlWordMege .= '<NOM_ANIO>AÑO DEL BICENTENARIO</NOM_ANIO>';
//        $xmlWordMege .= '<FECHA_DOC>'.$fecha.'</FECHA_DOC>';
//        $xmlWordMege .= '<COSTO_RECIBE>' . $cargo . '</COSTO_RECIBE>';
//        $xmlWordMege .= ' <ASUNTO>' . $asunto . '</ASUNTO>';
//        $xmlWordMege .= ' <REFERENCIA>' . $referencia . '</REFERENCIA>';
//        $xmlWordMege .= '<INICIALES_EMP></INICIALES_EMP>';
//        $xmlWordMege .= '</DOCUMENTO>';
//        break;


    case "INFORME":

        $tipoTemplate = 'INFORME.docx';
        $xmlWordMege = '<DOCUMENTO>';
        $xmlWordMege .= '<NOM_ANIO>AÑO DEL BICENTENARIO</NOM_ANIO>';
        $xmlWordMege .= '<FECHA_DOC>' . $fecha . '</FECHA_DOC>';
        $xmlWordMege .= ' <TIPO_DOC>' . $tipo_doc . '</TIPO_DOC>';
        $xmlWordMege .= '<USUARIO>' . $usuario . '</USUARIO>';
        $xmlWordMege .= ' <EMPLEADO_REBIBE>' . $nombre_rec . '</EMPLEADO_REBIBE>';
        $xmlWordMege .= ' <COSTO_RECIBE>' . $u_organica_rec . '</COSTO_RECIBE>';
        $xmlWordMege .= ' <EMPLEADO_EMITE>' . $nom_firmante . '</EMPLEADO_EMITE>';
        $xmlWordMege .= ' <COSTO_EMITE>' . $u_organica_firmante . '</COSTO_EMITE>';
        $xmlWordMege .= ' <ASUNTO>' . $asunto . '</ASUNTO>';
        $xmlWordMege .= ' <REFERENCIA>' . $referencia . '</REFERENCIA>';
        $xmlWordMege .= ' <INICIALES_EMP></INICIALES_EMP>';
        $xmlWordMege .= ' <COPIA></COPIA>';
        $xmlWordMege .= ' </DOCUMENTO>';

        break;

    case "MEMORANDO":

        $tipoTemplate = 'MEMORAN.docx';
        $xmlWordMege = '<DOCUMENTO>';
        $xmlWordMege .= '   <NOM_ANIO>AÑO DEL BICENTENARIO</NOM_ANIO>';
        $xmlWordMege .= '  <FECHA_DOC>' . $fecha . '</FECHA_DOC>';
        $xmlWordMege .= '  <TIPO_DOC>' . $tipo_doc . '</TIPO_DOC>';
        $xmlWordMege .= '  <USUARIO>' . $usuario . '</USUARIO>';
        $xmlWordMege .= '  <EMPLEADO_REBIBE>' . $nombre_rec . '</EMPLEADO_REBIBE>';
        $xmlWordMege .= '  <COSTO_RECIBE>' . $u_organica_rec . '</COSTO_RECIBE>';
        $xmlWordMege .= '  <EMPLEADO_EMITE>' . $nom_firmante . '</EMPLEADO_EMITE>';
        $xmlWordMege .= '  <COSTO_EMITE>' . $u_organica_firmante . '</COSTO_EMITE>';
        $xmlWordMege .= '   <ASUNTO>' . $asunto . '</ASUNTO>';
        $xmlWordMege .= '  <REFERENCIA>' . $referencia . '</REFERENCIA>';
        $xmlWordMege .= '  <INICIALES_EMP>' . $usuario . '</INICIALES_EMP>';
        $xmlWordMege .= '  </DOCUMENTO>';

        break;
    case "OFICIO":
        $tipoTemplate = 'OFICIO.docx';
        $xmlWordMege = '<DOCUMENTO>';
        $xmlWordMege .= '  <NOM_ANIO>AÑO DEL BICENTENARIO</NOM_ANIO>';
        $xmlWordMege .= '  <FECHA_DOC>' . $fecha . '</FECHA_DOC>';
        $xmlWordMege .= '  <TIPO_DOC>' . $tipo_doc . '</TIPO_DOC>';
        $xmlWordMege .= ' <DEPENDENCIA2></DEPENDENCIA2>';
        $xmlWordMege .= ' <USUARIO>' . $usuario . '</USUARIO>';
        $xmlWordMege .= ' <EMPLEADO_REBIBE>' . $nombre_rec . '</EMPLEADO_REBIBE>';
        $xmlWordMege .= ' <COSTO_RECIBE>' . $u_organica_rec . '</COSTO_RECIBE>';
        $xmlWordMege .= ' <ASUNTO>' . $asunto . '</ASUNTO>';
        $xmlWordMege .= '  <REFERENCIA>' . $referencia . '</REFERENCIA>';
        $xmlWordMege .= ' <INICIALES_EMP>' . $usuario . '</INICIALES_EMP>';
        $xmlWordMege .= '  <COPIA>CLR</COPIA>';
        $xmlWordMege .= '  </DOCUMENTO>';

        break;
    default:
        $generate_doc = false;
        $xmlWordMege = "";
        break;
}
if ($generate_doc == true) {
    if ($xmlWordMege != "")
        $xmlWordMege = base64_encode($xmlWordMege);

    mt_srand(time());
    $sessionID = mt_rand(1, 50000);
    $pathContexto = 'framework/FIRMA/PhpProject1';
    $pathDownloadFiles = 'c:/produce';
//echo $tipoTemplate;
    ?>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <script type="text/javascript">
                function convertirWord() {
                    //alert("convertirWord inicio");
                    var applet = document.getElementById('applet1');
                    if (applet == null) {
                        alert("El componente de conversion no ha cargado correctamente, por favor intente nuevamente");
                    }
                    //alert("convertirWord fin");
                    applet.convertir();
                }
            </script>
            <title>Conversion de Plantilla Word</title>
        </head>

        <body id="cuerpo" onload="convertirWord()">
            <!--form id="form1" action="" method="post" enctype="multipart/form-data"-->
            <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                <tr>
                    <td>
                        tipo_doc:<?php echo $tipo_doc ?>
                    </td>
                </tr>

                <tr>
                    <td class="tablatd" align="center" colspan="3">
                <APPLET id="applet1"
                        ARCHIVE = "ZyTrustConvertionApplet_V1.jar,dom4j-1.6.1.jar,poi-3.9-20121203.jar,poi-examples-3.9-20121203.jar,poi-excelant-3.9-20121203.jar,poi-ooxml-3.9-20121203.jar,poi-ooxml-schemas-3.9-20121203.jar,poi-scratchpad-3.9-20121203.jar,xmlbeans-2.3.0.jar"
                        CODE = "zytrust.convertion.applet.gui.ConvertionFormApplet.class"
                        ALT = "No se pudo cargar el applet de Conversion de Documentos.."
                        NAME = "ConvertionFormApplet"
                        WIDTH = "520"  HEIGHT = "250"
                        ALIGN = "CENTER"
                        VSPACE = "0"  HSPACE = "0">

                    <PARAM NAME="batch" VALUE = "0">
                    <PARAM NAME="number" VALUE = "<?php echo $_GET["id"]; ?>">
                    <PARAM NAME="jsessionid" VALUE = "<?php echo $sessionID ?>">
                    <PARAM NAME="tipoTemplate" VALUE = "<?php echo $tipoTemplate ?>">
                    <PARAM NAME="xmlWordMege" VALUE = "<?php echo $xmlWordMege ?>">
                    <PARAM NAME="pathDownloadFiles" VALUE = "<?php echo $pathDownloadFiles ?>">
                    <PARAM NAME="DownloadFile" VALUE = "<?php echo $pathContexto ?>/DownloadFile.php">

                    Su explorador no soporta Java
                </APPLET>
            </td>
        </tr>
        <tr class="tablatd" align="center" colspan="3"><input type="button" value="Generar Word"><input type="button" value="Cargar PDF"></tr>
    </table>

    <!--/form-->
    </body>

    </html>
    <?php
}else {
    echo "<script>alert('no se encontro la plantilla $tipo_doc.')</script>";
}
?>

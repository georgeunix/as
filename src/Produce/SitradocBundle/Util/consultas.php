<?php

namespace Produce\SitradocBundle\Util;

class consultas {

    public static function comboTrabajador($cn, $filtro) {

        $sql = "SELECT top 30 CODIGO_TRABAJADOR,(LTRIM(RTRIM(NOMBRES_TRABAJADOR))+' '+LTRIM(RTRIM(APELLIDOS_TRABAJADOR)))as TRABAJADOR, LTRIM(RTRIM(EMAIL))";
        $sql.=" FROM db_general.jcardenas.H_TRABAJADOR ";
        $sql.=" WHERE LTRIM(RTRIM(APELLIDOS_TRABAJADOR)) LIKE '$filtro%'";
        $sql.=" order by NOMBRES_TRABAJADOR ASC";

        $query = $cn->prepare($sql); //preparo consulta
        $query->execute(); //ejecuto la consulta
        $result = $query->fetchAll(); //guardo los resultados

        $select = "";
        foreach ($result as $cbo) {
            $select .="<option value='" . $cbo["CODIGO_TRABAJADOR"] . "'>" . $cbo["TRABAJADOR"] . "</option>";
        }
        return $select;
    }

    public static function comboDestinatarios($cn, $filtro) {
        $sql = "SELECT TOP 20 CODIGO_DEPENDENCIA,LTRIM(RTRIM(DEPENDENCIA))AS DEPENDENCIA FROM db_general.jcardenas.H_DEPENDENCIA where DEPENDENCIA LIKE '$filtro%'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();

        $select = "";
        foreach ($result as $cbo) {
            $select .="<option value='" . $cbo["CODIGO_DEPENDENCIA"] . "'>" . $cbo["DEPENDENCIA"] . "</option>";
        }
        return $select;
    }

    public static function comboTipoDocumento($cn) {
        $sql = "select ID_CLASE_DOCUMENTO_INTERNO as ID,DESCRIPCION from dbo.CLASE_DOCUMENTO_INTERNO ORDER BY (LTRIM(RTRIM(DESCRIPCION)))";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public static function listaCicloProyecto($cn, $usuario) {
        $sql = "select DAT.ID_DOCUMENTO_PROY AS CODIGO,CLA.DESCRIPCION AS TIPO_DE_DOCUMENTO,convert(char(10), DAT.AUDITMOD, 103) as FECHA_CREACION,";
        $sql.=" CIC.DESCRIPCION AS CICLO,DAT.INDICATIVO_OFICIO AS INDICATIVO_DEL_DOCUMENTO, DAT.ASUNTO AS ASUNTO, DAT.USUARIO AS USUARIO";
        $sql.=" from DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql.=" dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO=CLA.ID_CLASE_DOCUMENTO_INTERNO where CIC.ID_CICLOFIRMA=1";
        $sql.=" AND   USUARIO=  '$usuario' order by convert(DATETIME, DAT.AUDITMOD, 103) desc";

        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public static function listaParaFirmar($cn, $usuario) {

        $sql = " SELECT DAT.ID_DOCUMENTO_PROY AS CODIGO,CLA.DESCRIPCION AS TIPO_DE_DOCUMENTO,convert(char(10), DAT.AUDITMOD, 103) as FECHA_CREACION,";
        $sql .= " CIC.DESCRIPCION AS CICLO,DAT.INDICATIVO_OFICIO AS INDICATIVO_DEL_DOCUMENTO, DAT.ASUNTO AS ASUNTO, DAT.USUARIO AS USUARIO,";
        $sql .= " (SELECT count(*) FROM DAT_DETALLE_FIRMANTE q WHERE q.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY AND q.CODIGO_TRABAJADOR  = f.CODIGO_TRABAJADOR and ";
        $sql .= " q.NUMERO_FIRMA = (SELECT min(bx.NUMERO_FIRMA) FROM DAT_DETALLE_FIRMANTE bx WHERE bx.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY AND bx.ID_ESTADOFIRMA = 2 ) ) as canbesign,";
        $sql .= " f.codigo_trabajador as codigo_firmante, f.NUMERO_FIRMA AS NUM_FIRMA, (SELECT max(bx.NUMERO_FIRMA) FROM DAT_DETALLE_FIRMANTE bx WHERE bx.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY) as cant_firma  from DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql .= " dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO = CLA.ID_CLASE_DOCUMENTO_INTERNO";
        $sql .=" INNER JOIN DAT_DETALLE_FIRMANTE f";
        $sql .=" ON DAT.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY";
        $sql .=" INNER JOIN db_general.jcardenas.H_TRABAJADOR t";
        $sql .=" ON f.codigo_trabajador = t.codigo_trabajador";
        $sql .=" where CIC.ID_CICLOFIRMA = 2";
        $sql .=" AND t.EMAIL = '$usuario'";
        $sql .=" AND DAT.ID_CICLOFIRMA = 2";
        $sql .=" AND f.ID_CODIGOFIRMA = 1";
        $sql .=" AND f.ID_ESTADOFIRMA = 2 order by convert(DATETIME, DAT.AUDITMOD, 103) desc";

        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public static function listaParaVistoBueno($cn, $usuario) {

        $sql = " SELECT DAT.ID_DOCUMENTO_PROY AS CODIGO,CLA.DESCRIPCION AS TIPO_DE_DOCUMENTO,convert(char(10), DAT.AUDITMOD, 103) as FECHA_CREACION,";
        $sql .= " CIC.DESCRIPCION AS CICLO,DAT.INDICATIVO_OFICIO AS INDICATIVO_DEL_DOCUMENTO, DAT.ASUNTO AS ASUNTO, DAT.USUARIO AS USUARIO,";
        $sql .= " (SELECT count(*) FROM DAT_DETALLE_FIRMANTE q WHERE q.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY AND q.CODIGO_TRABAJADOR  = f.CODIGO_TRABAJADOR and ";
        $sql .= " q.NUMERO_FIRMA = (SELECT min(bx.NUMERO_FIRMA) FROM DAT_DETALLE_FIRMANTE bx WHERE bx.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY AND bx.ID_ESTADOFIRMA = 2 ) ) as canbesign,";
        $sql .= " f.codigo_trabajador as codigo_firmante, f.NUMERO_FIRMA AS NUM_FIRMA, (SELECT max(bx.NUMERO_FIRMA) FROM DAT_DETALLE_FIRMANTE bx WHERE bx.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY) as cant_firma from DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql .= " dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO = CLA.ID_CLASE_DOCUMENTO_INTERNO";
        $sql .=" INNER JOIN DAT_DETALLE_FIRMANTE f";
        $sql .=" ON DAT.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY";
        $sql .=" INNER JOIN db_general.jcardenas.H_TRABAJADOR t";
        $sql .=" ON f.codigo_trabajador = t.codigo_trabajador";
        $sql .=" where CIC.ID_CICLOFIRMA = 2";
        $sql .=" AND t.EMAIL = '$usuario'";
        $sql .=" AND DAT.ID_CICLOFIRMA = 2";
        $sql .=" AND f.ID_CODIGOFIRMA = 2";
        $sql .=" AND f.ID_ESTADOFIRMA = 2 order by convert(DATETIME, DAT.AUDITMOD, 103) desc";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public static function detalleProyecto($controller, $request) {
        $DB_TRAMITE_DOCUMENTARIO = $controller->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
        $codigo_proy = $request->request->get("CODIGO");


        $sql = " select (select t.APELLIDOS_TRABAJADOR + ' ' + t.NOMBRES_TRABAJADOR from db_general.jcardenas.h_trabajador t WHERE t.CODIGO_TRABAJADOR = f.CODIGO_TRABAJADOR) as nombre_firmante,";
        $sql .=" (select dep.DEPENDENCIA from db_general.jcardenas.h_trabajador t, db_general.jcardenas.H_DEPENDENCIA dep WHERE t.CODIGO_DEPENDENCIA = dep.CODIGO_DEPENDENCIA and t.CODIGO_TRABAJADOR = f.CODIGO_TRABAJADOR) as unidad_organica_firmante,";
        $sql .=" (select t.APELLIDOS_TRABAJADOR + ' ' + t.NOMBRES_TRABAJADOR, f.NUMERO_FIRMA AS NUM_FIRMA  from db_general.jcardenas.h_trabajador t WHERE t.CODIGO_TRABAJADOR = dt.CODIGO_TRABAJADOR_destino) as nombre_destino,";
        $sql .=" (select DES_CARGO from TBL_CARGO_DEPENDENCIA tcar where tcar.CODDEP = dt.CODDEP_DESTINO)as cargo,";
        $sql .=" (select dep.DEPENDENCIA from db_general.jcardenas.h_trabajador t, db_general.jcardenas.H_DEPENDENCIA dep WHERE t.CODIGO_DEPENDENCIA = dep.CODIGO_DEPENDENCIA and t.CODIGO_TRABAJADOR = dt.CODIGO_TRABAJADOR_destino) unidad_organica_destino,";
        $sql .=" d.ASUNTO as asunto, '' as referencia from DAT_DOCUMENTO_PROYECTO d, DAT_DETALLE_FIRMANTE f, DAT_DETALLE_DESTINO dt where d.ID_DOCUMENTO_PROY = f.ID_DOCUMENTO_PROY and d.ID_DOCUMENTO_PROY = dt.ID_DOCUMENTO_PROY and f.ID_CODIGOFIRMA = 1 and";
        $sql .=" f.ID_DOCUMENTO_PROY = $codigo_proy";

        $query = $DB_TRAMITE_DOCUMENTARIO->prepare($sql);
        $query->execute();
        $result_docu = $query->fetchAll();

        foreach ($result_docu as $rs) {
            $nombre_firmante = $rs["nombre_firmante"];
            $u_organica_firmantes = $rs["unidad_organica_firmante"];
            $nombre_destino = $rs["nombre_destino"];
            $cargo = $rs["cargo"];
            $unidad_organica_destino = $rs["unidad_organica_destino"];
            $asunto = $rs["asunto"];
            $referencia = $rs["referencia"];

            $string_datos.=$nombre_firmante . "|" . $u_organica_firmantes . "|" . $asunto . "|" . $nombre_destino . "|" . $cargo . "|" . $unidad_organica_destino . "|" . $referencia . "#";
        }
        $string_datos = substr($string_datos, 0, ( strlen($string_datos) - 1));

        return $string_datos;
    }

    public static function nuevoDocumentoProyecto($controller, $request, $user) {
        $DB_TRAMITE_DOCUMENTARIO = $controller->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");

        $sql_trabajador = "SELECT CODIGO_TRABAJADOR FROM db_general.jcardenas.H_TRABAJADOR WHERE EMAIL='$user'";
        $query1 = $DB_TRAMITE_DOCUMENTARIO->prepare($sql_trabajador);
        $query1->execute();
        $result_trabajador = $query1->fetchAll();


        $sql_oficina = "select coddep from db_general.jcardenas.h_trabajador where email='$user' AND ESTADO='ACTIVO'";
        $query2 = $DB_TRAMITE_DOCUMENTARIO->prepare($sql_oficina);
        $query2->execute();
        $result_oficina = $query2->fetchAll();
        $numresult = count($result_oficina);


        $codigo_trabajador = $result_trabajador[0]["CODIGO_TRABAJADOR"];
        $codigo_oficina = $result_oficina[0]["coddep"];
        $cbo_tipo_documento = $request->request->get("cbo_tipo_documento");
        $txt_asunto = $request->request->get("txt_asunto");
        $txt_observaciones = $request->request->get("txt_observaciones");


        $new_documento = "exec [web_tramite].[sp_new_proyecto_documento]";
        $new_documento.="@a1=1,";
        $new_documento.="@a2=4,";
        $new_documento.="@a3=1,";
        $new_documento.="@a4=$cbo_tipo_documento,"; /* id_clase_de_documento informe, oficio, memo */
        $new_documento.="@a5=$codigo_oficina,"; /* coddep codigo de oficina */
        $new_documento.="@a6='$txt_asunto',"; /* asunto */
        $new_documento.="@a7='$txt_observaciones',"; /* observaciones */
        $new_documento.="@a8='',"; /* fecha maxima de plazo */
        $new_documento.="@a9='$codigo_trabajador',"; /* codigo_trabajador */
        $new_documento.="@a10 ='$user'"; /* usuario */

        $query3 = $DB_TRAMITE_DOCUMENTARIO->prepare($new_documento);
        $query3->execute();

        $cbo_firmantes = $request->request->get("cbo_firmantes");
        $cbo_destinatarios = $request->request->get("cbo_destinatarios");

        $firmantes = explode(",", $cbo_firmantes);
        $destinatarios = explode(",", $cbo_destinatarios);



        $id_proy_query = "select TOP 1 ID_DOCUMENTO_PROY from web_tramite.DAT_DOCUMENTO_PROYECTO  order by ID_DOCUMENTO_PROY desc";
        $query4 = $DB_TRAMITE_DOCUMENTARIO->prepare($id_proy_query);
        $query4->execute();
        $result_id_proy = $query4->fetchAll();

        $id_proy = $result_id_proy[0]["ID_DOCUMENTO_PROY"];

        if ($firmantes[0] != "") {
            foreach ($firmantes as $cod_emp) {

                $sp_new_proyecto_destino_firma_query = "exec [web_tramite].[sp_new_proyecto_destino_firma]";
                $sp_new_proyecto_destino_firma_query.="@IDPROY='$id_proy',";
                $sp_new_proyecto_destino_firma_query.="@A1='" . $cod_emp . "',"; //--CODIGO TRABAJADOR
                $sp_new_proyecto_destino_firma_query.="@A2='$user',"; //--USUARIO 
                $sp_new_proyecto_destino_firma_query.="@A3=''"; //--CODDEP

                $query5 = $DB_TRAMITE_DOCUMENTARIO->prepare($sp_new_proyecto_destino_firma_query);
                $query5->execute();
            }
        }
        //firma principal

        $sp_new_proyecto_destino_firma_principal = "exec [web_tramite].[sp_new_proyecto_destino_firma_principal]";
        $sp_new_proyecto_destino_firma_principal.="@IDPROY='$id_proy',";
        $sp_new_proyecto_destino_firma_principal.="@A1='" . $codigo_trabajador . "',"; //--CODIGO TRABAJADOR
        $sp_new_proyecto_destino_firma_principal.="@A2='$user',"; //--USUARIO 
        $sp_new_proyecto_destino_firma_principal.="@A3=''"; //--CODDEP

        $query6 = $DB_TRAMITE_DOCUMENTARIO->prepare($sp_new_proyecto_destino_firma_principal);
        $query6->execute();


        foreach ($destinatarios as $dest) {
            $sp_new_proyecto_destino_oficina_firma = " sp_new_proyecto_destino_oficina_firma";
            $sp_new_proyecto_destino_oficina_firma .= " @IDPROY='$id_proy' ,";
            $sp_new_proyecto_destino_oficina_firma .= " @A1='$codigo_trabajador', "; //--CODIGO TRABAJADOR
            $sp_new_proyecto_destino_oficina_firma .= " @A2='$user',"; //--USUARIO
            $sp_new_proyecto_destino_oficina_firma .= " @A3='', "; //--CODDEP
            $sp_new_proyecto_destino_oficina_firma .= " @A4='" . $dest . "' "; //--CODDEP DESTINO

            $query7 = $DB_TRAMITE_DOCUMENTARIO->prepare($sp_new_proyecto_destino_oficina_firma);
            $query7->execute();
        }

        return "se inserto correctamente";
    }

    public function parafirma($controller, $id_docu) {
        $DB_TRAMITE_DOCUMENTARIO = $controller->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
        $id_documento_proy = $id_docu;
        $sql_trabajador = "update web_tramite.DAT_DOCUMENTO_PROYECTO set ID_CICLOFIRMA = '2' WHERE ID_DOCUMENTO_PROY = '$id_documento_proy'";
        $query1 = $DB_TRAMITE_DOCUMENTARIO->prepare($sql_trabajador);
        $query1->execute();

        return "Se envio para firma.";
    }

    public static function retornarestadoDocumento($controller, $request) {
        $DB_TRAMITE_DOCUMENTARIO = $controller->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
        $id_documento_proy = $request->request->get("id_docu");
        $sql_trabajador = "update web_tramite.DAT_DOCUMENTO_PROYECTO set ID_CICLOFIRMA = '1' WHERE ID_DOCUMENTO_PROY = '$id_documento_proy'";
        $query1 = $DB_TRAMITE_DOCUMENTARIO->prepare($sql_trabajador);
        $query1->execute();

        return "Se retorno a estado en proyecto.";
    }

    public static function vistoBuenoDocumento($controller, $request) {
        $DB_TRAMITE_DOCUMENTARIO = $controller->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
        $id_documento_proy = $request->request->get("id_docu");
        $sql_trabajador = "update web_tramite.DAT_DOCUMENTO_PROYECTO set ID_CICLOFIRMA = '7' WHERE ID_DOCUMENTO_PROY = '$id_documento_proy'";
        $query1 = $DB_TRAMITE_DOCUMENTARIO->prepare($sql_trabajador);
        $query1->execute();

        return "Se dio el visto bueno al Documento.";
    }

    public function muestraDocumento($controller, $request) {

        $cn = $controller->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
        $codigo_documento = $request->request->get("id_doc");
        $sql = "select ID_CLASE_DOCUMENTO_INTERNO,ID_DOCUMENTO_PROY,ASUNTO,OBSERVACIONES from DAT_DOCUMENTO_PROYECTO WHERE ID_DOCUMENTO_PROY = '$codigo_documento'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        $respuesta = "";

        $sql2 = " select tr.CODIGO_TRABAJADOR,(LTRIM(RTRIM(tr.NOMBRES_TRABAJADOR))+' '+LTRIM(RTRIM(tr.APELLIDOS_TRABAJADOR)))as TRABAJADOR";
        $sql2.=" from DAT_DETALLE_FIRMANTE dat inner join DB_GENERAL.jcardenas.H_TRABAJADOR tr on dat.CODIGO_TRABAJADOR=tr.CODIGO_TRABAJADOR";
        $sql2.=" where dat.ID_DOCUMENTO_PROY=$codigo_documento AND dat.ID_CODIGOFIRMA<>1";
        $query2 = $cn->prepare($sql2);
        $query2->execute();
        $result_query2 = $query2->fetchAll();

        $firmantes = "";
        if (count($result_query2) > 0) {

            foreach ($result_query2 as $row) {
                $firmantes.=$row["CODIGO_TRABAJADOR"] . "|";
                $firmantes.=$row["TRABAJADOR"] . "#";
            }
            $firmantes = substr($firmantes, 0, strlen($firmantes) - 1);
        }


        $sql3 = " select h_dep.CODIGO_DEPENDENCIA, LTRIM(RTRIM(h_dep.DEPENDENCIA))AS DEPENDENCIA from";
        $sql3 .=" web_tramite.DAT_DETALLE_DESTINO  det inner join db_general.jcardenas.H_DEPENDENCIA h_dep on det.CODDEP_DESTINO=h_dep.CODIGO_DEPENDENCIA";
        $sql3 .=" WHERE det.ID_DOCUMENTO_PROY=$codigo_documento";

        $query3 = $cn->prepare($sql3);
        $query3->execute();
        $result_query3 = $query3->fetchAll();

        $destino = "";
        if (count($result_query3) > 0) {

            foreach ($result_query3 as $row2) {
                $destino.=$row2["CODIGO_DEPENDENCIA"] . "|";
                $destino.=$row2["DEPENDENCIA"] . "#";
            }
            $destino = substr($destino, 0, strlen($destino) - 1);
        }

        $respuesta.=$result_query[0]["ID_CLASE_DOCUMENTO_INTERNO"] . "$$";
        $respuesta.=$result_query[0]["ASUNTO"] . "$$";
        $respuesta.=$result_query[0]["OBSERVACIONES"] . "$$";
        $respuesta.=$firmantes . "$$";
        $respuesta.=$destino;

        return $respuesta;
    }

    public function editardocumento($controller, $request, $user) {
        $cn = $controller->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");

        $cbo_tipo_documento = $request->request->get("cbo_tipo_documento");
        $txt_asunto = $request->request->get("txt_asunto");
        $txt_observaciones = $request->request->get("txt_observaciones");

        $cbo_firmantes = $request->request->get("cbo_firmantes");
        $cbo_destinatarios = $request->request->get("cbo_destinatarios");

        $id_doc = $request->request->get("num_doc");


        $sql = "UPDATE web_tramite.DAT_DOCUMENTO_PROYECTO set ASUNTO='$txt_asunto',";
        $sql.=" ID_CLASE_DOCUMENTO_INTERNO='$cbo_tipo_documento',OBSERVACIONES='$txt_observaciones'";
        $sql.=" where ID_DOCUMENTO_PROY=$id_doc";

        $query = $cn->prepare($sql);
        $query->execute();

        $firmantes = explode(",", $cbo_firmantes);
        $sp_new_proyecto_destino_firma_query = "";

        $sql2 = "DELETE DAT_DETALLE_FIRMANTE where ID_DOCUMENTO_PROY='$id_doc' AND ID_CODIGOFIRMA<>1";
        $query2 = $cn->prepare($sql2);
        $query2->execute();

        if (trim($firmantes[0]) != "") {

            foreach ($firmantes as $cod_emp) {

                $sp_new_proyecto_destino_firma_query = "exec [web_tramite].[sp_new_proyecto_destino_firma]";
                $sp_new_proyecto_destino_firma_query.="@IDPROY='$id_doc',";
                $sp_new_proyecto_destino_firma_query.="@A1='$cod_emp',"; //--CODIGO TRABAJADOR
                $sp_new_proyecto_destino_firma_query.="@A2='$user',"; //--USUARIO 
                $sp_new_proyecto_destino_firma_query.="@A3=''"; //--CODDEP

                $query3 = $cn->prepare($sp_new_proyecto_destino_firma_query);
                $query3->execute();
            }
        }
        $sql_actualiza_crea_proy = " UPDATE DAT_DETALLE_FIRMANTE ";
        $sql_actualiza_crea_proy .= " set NUMERO_FIRMA=(SELECT (COUNT(NUMERO_FIRMA)+1)FROM DAT_DETALLE_FIRMANTE WHERE ID_DOCUMENTO_PROY=$id_doc and ID_CODIGOFIRMA<>1)";
        $sql_actualiza_crea_proy .= " WHERE ID_DOCUMENTO_PROY=$id_doc and ID_CODIGOFIRMA=1";

        $query4 = $cn->prepare($sql_actualiza_crea_proy);
        $query4->execute();



        $destinos = explode(",", $cbo_destinatarios);
        $sp_new_proyecto_destino_oficina = "";

        $sql5 = "DELETE DAT_DETALLE_DESTINO where ID_DOCUMENTO_PROY='$id_doc'";
        $query5 = $cn->prepare($sql5);
        $query5->execute();

        if (trim($destinos[0]) != "") {

            $sql6 = "select CODIGO_TRABAJADOR,USUARIO from DAT_DOCUMENTO_PROYECTO WHERE ID_DOCUMENTO_PROY=$id_doc";
            $query6 = $cn->prepare($sql6);
            $query6->execute();
            $rs_proy = $query6->fetchAll();

            foreach ($destinos as $dest) {
                $sp_new_proyecto_destino_oficina = "exec [web_tramite].[sp_new_proyecto_destino_oficina_firma]";
                $sp_new_proyecto_destino_oficina .= "@IDPROY=$id_doc,";
                $sp_new_proyecto_destino_oficina .= "@A1=" . $rs_proy[0]["CODIGO_TRABAJADOR"] . ",";
                $sp_new_proyecto_destino_oficina .= "@A2=" . $rs_proy[0]["USUARIO"] . ",";
                $sp_new_proyecto_destino_oficina .= "@A3='',";
                $sp_new_proyecto_destino_oficina .= "@A4=$dest";

                $query7 = $cn->prepare($sp_new_proyecto_destino_oficina);
                $query7->execute();
            }
        }

        return "SE ACTUALIZO DOCUMENTO";
    }

    public function listar_jqgrid($controller, $sql_select, $sql_where, $sidx, $sord, $page, $limit) {

        $DB_TRAMITE_DOCUMENTARIO = $controller->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");

        if (!$sidx)
            $sidx = 1;

        $sql_select = trim($sql_select);
        $sql_where = trim($sql_where) == "" ? "" : "where $sql_where";
        $order_by = trim($sidx) == "" ? "" : "order by $sidx $sord";


        $sql_no_select = substr($sql_select, 6, strlen($sql_select));
        $sql_colums = "SELECT TOP $limit $sql_no_select";
        $sql_not_in = "";

//        CONSULTA CANTIDAD DE FILAS
        $sql_num = "WITH DATOS AS($sql_select $sql_where)SELECT COUNT(*) FROM DATOS";
        $count_query = $DB_TRAMITE_DOCUMENTARIO->prepare($sql_num);
        $count_query->execute();
        $num_rows = $count_query->fetchColumn();
//       END CONSULTA CANTIDAD DE FILAS
        //En base al numero de registros se obtiene el numero de paginas
        if ($num_rows > 0) {
            $total_pages = ceil($num_rows / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;

        //Almacena numero de registro donde se va a empezar a recuperar los registros para la pagina
        $start = $limit * $page - $limit;

        if ($sql_where != "") {

            $array_from_cad = explode("FROM", $sql_no_select);
            $from_cade = $array_from_cad[count($array_from_cad) - 1];

            $array_prim_dato = explode(",", $sql_no_select);
            $prim_dato = $array_prim_dato[0];


            $sql_not_in = "AND $prim_dato NOT IN (SELECT TOP $start $prim_dato FROM $from_cade $sql_where $order_by)";
        }

        $sql_result = "$sql_colums $sql_where $sql_not_in $order_by";


//        $result_query = $DB_TRAMITE_DOCUMENTARIO->prepare($sql_result);
//        $result_query->execute();
//
//        $resultado = (json_encode($result_query->fetchAll()));

        return $sql_result;
    }

}

?>

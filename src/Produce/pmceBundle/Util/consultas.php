<?php

namespace Produce\pmceBundle\Util;

class consultas {

    public static function InsertarSolicitud($cn, $request){
        
        $apor = $request->request->get("aportantes");
        $rece = $request->request->get("receptores");
        $distribucion = $request->request->get("radio-proporcion"); // distribuicion radio1            equitativo radio2      
        $magnitud = $request->request->get("radio-magnitud"); // %radio1                        m3 radio2  
        $item_tipo = $request->request->get("item");
        if ($item_tipo == 0)
            $value = "Asociación";
        if ($item_tipo == 1)
            $value = "Reducción";
        if ($item_tipo == 2)
            $value = "Sustitución";
        //$ultimo = $request->request->get("codem0");         
        $DB_CON=$cn->getDoctrine()->getConnection("PMCE");
        ////var comprobacion
        $IMPRMIW=$distribucion."-".$magnitud;
        $suma_PMCE=0;                         ////////////////Para la comprobacion
        $PMCE_APORTANTE_REPARTIR=0;
        $PMCE_DISTRIBUIDO=0;
        $CUOTA_TOTAL_ASIGNADA=810000;         ///////////////AQUI ASIGNO LA CUOTA TOTAL
        $LMCE_APORTANTE_REPARTIR=0;
        $LMCE_DISTRIBUIDO=0;
        $porcentaje_aportacion=0;
        $new_documento = "exec sp_solicitud_insert ";
        $new_documento.="@A0=0,";
        $new_documento.="@A1=" . $item_tipo . ",";
        $new_documento.="@A2='$value'";
        $query1 = $DB_CON->prepare($new_documento);
        $query1->execute();
        $total = $apor + $rece;
        $cont = 0;
        while ($total>0){
            if ($cont < $apor){
                $embarcacion = $request->request->get("codem" . $cont);
                $sql_aportantes = " insert into EMB_APORTANTES (id_embarcacion,id_solicitud) values('" . $embarcacion . "',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD)); ";
                $query2 = $DB_CON->prepare($sql_aportantes);
                $query2->execute();
                $sql = "select ID_EMB, PMCE,PMCE_SIN_BENEFICIO from DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL  WHERE ID_EMB='" . $embarcacion . "'";
                $query = $DB_CON->prepare($sql);
                $query->execute();
                $result = $query->fetchAll();
                foreach($result as $pmce_apor){
                    $PMCE_APORTANTE_REPARTIR = $pmce_apor["PMCE_SIN_BENEFICIO"];
                    $suma_PMCE+=$pmce_apor["PMCE"];
                    }
            }else{
                $embarcacion = $request->request->get("codem" . $cont);
                $sql_aportantes = " insert into EMB_RECEPTORAS (id_embarcacion,id_solicitud) values('" . $embarcacion . "',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD)); ";
                $query2 = $DB_CON->prepare($sql_aportantes);
                $query2->execute();
                $sql = " select ID_EMB, PMCE from DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL WHERE ID_EMB='" . $embarcacion . "'";
                $query = $DB_CON->prepare($sql);
                $query->execute();
                $result = $query->fetchAll();
                foreach ($result as $pmce_apor){
                    $suma_PMCE+=$pmce_apor["PMCE"];
                }
            }
            $total--;
            $cont++;
        }
        
        
        
        
        
        
        $LMCE_APORTANTE_REPARTIR = round($PMCE_APORTANTE_REPARTIR * $CUOTA_TOTAL_ASIGNADA);
        //porcentajes de aportacion
        if ($distribucion=="radio1"){//armador asigna y si la distribucion se ingresan por los campos de entrada
            $sumatoria_Datos="";
            for ($i=($apor);$i<$apor+$rece;$i++){
                $sumatoria_Datos+=round($request->request->get("distr" . ($i - $apor)), 8);
            }
            //            return $sumatoria_Datos."    -    ".($PMCE_APORTANTE_REPARTIR*100);
                    if (($sumatoria_Datos == (100) || $sumatoria_Datos == $PMCE_APORTANTE_REPARTIR * 100 ) && $magnitud == "radio1"){
                                        //Si Ingresan Porcentajes%
                                        if($sumatoria_Datos==(100)){
                                            for ($i = ($apor); $i < $apor + $rece; $i++){
                                                $PMCE_DISTRIBUIDO = round($PMCE_APORTANTE_REPARTIR*$request->request->get("distr".($i - $apor)),8);
                                                $LMCE_DISTRIBUIDO = round($LMCE_APORTANTE_REPARTIR * $request->request->get("distr".($i - $apor))/100,2);
                                                $sql_historial = "insert into HIST_PMCE_DET ([des_hist_ini],[id_solicitud],[fecha],[lmce],[pmce_calculado] , ";
                                                $sql_historial.= "[lmce_calculado] , [porcentaje_aportacion],[id_receptoras])VALUES ('distributiva',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD) , ";
                                                $sql_historial.= "GETDATE(),((SELECT pmce  FROM DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL where ID_EMB='" . $request->request->get("codem".$i)."')*'".$CUOTA_TOTAL_ASIGNADA."'), ";
                                                $sql_historial.= "'".$PMCE_DISTRIBUIDO."' , '".$LMCE_DISTRIBUIDO."' , '".$request->request->get("distr".($i-$apor))."' , '" . $request->request->get("codem".$i)."')";
                                                $query2 = $DB_CON->prepare($sql_historial);
                                                $query2->execute();
                                            }
                                            return ":::1:1:1:::si sumatoria de datos: es 100::"."LMCE_DISTRIBUIDO:" . $LMCE_DISTRIBUIDO . "  -  LMCE_APORTANTE_REPARTIR:" . $LMCE_APORTANTE_REPARTIR . "        -   PMCE_APORTANTE_REPARTIR:" . $PMCE_APORTANTE_REPARTIR . "  -   porcentaje_aportacion:" . $porcentaje_aportacion . "    ----   PMCE_DISTRIBUIDO:" . $PMCE_DISTRIBUIDO;
                                        }
                                         //  Si Ingresan PMCE  distribuido
                                        if($sumatoria_Datos == $PMCE_APORTANTE_REPARTIR*100){
                                            for ($i = ($apor); $i < $apor + $rece; $i++){
                                                $PMCE_DISTRIBUIDO = round($request->request->get("distr".($i - $apor)),6);
                                                $porcentaje_aportacion = round($PMCE_DISTRIBUIDO/$PMCE_APORTANTE_REPARTIR,2);                      // Parte entre el todo %
                                                $LMCE_DISTRIBUIDO = round($LMCE_APORTANTE_REPARTIR*$porcentaje_aportacion/100,2);
                                                $sql_historial = "insert into HIST_PMCE_DET ([des_hist_ini],[id_solicitud],[fecha],[lmce],[pmce_calculado] , [lmce_calculado] , [porcentaje_aportacion],[id_receptoras])VALUES "; 
                                                $sql_historial.= "('distributiva',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD) , GETDATE(),((SELECT pmce  FROM  DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL ";
                                                $sql_historial.= " where ID_EMB='" . $request->request->get("codem" . $i) . "')*'" . $CUOTA_TOTAL_ASIGNADA . "')  ,'" . $PMCE_DISTRIBUIDO . "' , '" . $LMCE_DISTRIBUIDO . "' , ";
                                                $sql_historial.= "'" . $porcentaje_aportacion . "' , '" . $request->request->get("codem" . $i) . "' )";
                                                $query2 = $DB_CON->prepare($sql_historial);
                                                $query2->execute();
                                            }
                                            return "::::2:2:2::::PRUEBAS DE ENTORNO FALLIDO::::::::::  "."LMCE_DISTRIBUIDO:" . $LMCE_DISTRIBUIDO . "  -  LMCE_APORTANTE_REPARTIR:" . $LMCE_APORTANTE_REPARTIR . "        -       PMCE_APORTANTE_REPARTIR:" . $PMCE_APORTANTE_REPARTIR . "            -          porcentaje_aportacion:" . $porcentaje_aportacion . "    ----   PMCE_DISTRIBUIDO:" . $PMCE_DISTRIBUIDO;
                                        }
                    }else if($magnitud=="radio2"){//porcentajes de aportacion
                        
                        
                        
                        $empresari = "";
                        for($i=($apor);$i<$apor+$rece;$i++){
                            $porcentaje_aportacion = round(($request->request->get("distr" . ($i - $apor)) - $request->request->get("capbode" . $i)) / $request->request->get("capbode0") * 100, 0);
                            $PMCE_DISTRIBUIDO = round($PMCE_APORTANTE_REPARTIR * $porcentaje_aportacion, 6);
                            $LMCE_DISTRIBUIDO = round($LMCE_APORTANTE_REPARTIR * $porcentaje_aportacion / 100, 2);
                            $empresari.="LMCE_DISTRIBUIDO:" . $LMCE_DISTRIBUIDO . " PMCE_DISTRIBUIDO:" . $PMCE_DISTRIBUIDO;
                            $sql_historial = "insert into HIST_PMCE_DET ([des_hist_ini],[id_solicitud],[fecha],[lmce],[pmce_calculado] , [lmce_calculado] , [porcentaje_aportacion],[id_receptoras],[cuota_asig])VALUES ";
                            $sql_historial.= " ('distributiva',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD) , GETDATE(),((SELECT pmce  FROM  DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL  where ID_EMB='" . $request->request->get("codem" . $i) . "')*'" . $CUOTA_TOTAL_ASIGNADA . "')  , ";
                            $sql_historial.= " '" . $PMCE_DISTRIBUIDO . "','" . $LMCE_DISTRIBUIDO."' , '".$porcentaje_aportacion."' , '".$request->request->get("codem".$i)."', '" . $CUOTA_TOTAL_ASIGNADA . "')";
                            $query2 = $DB_CON->prepare($sql_historial);
                            $query2->execute();
                        }
                        return ":::3:3:3:3:3:::".$empresari . " ";
                            
                    }else{
                        return "Verfique Entrada Datos  :" . ($PMCE_APORTANTE_REPARTIR * 100) . "==> Sumatoria Datos  : " . $sumatoria_Datos;
                    }
        }                       
        if($distribucion == "radio2"){
                        //siesequitativo
                        $porcentaje_aportacion = round(100 / $rece, 6);                    //   se divide entre el total de receptores
                        $PMCE_DISTRIBUIDO = round($PMCE_APORTANTE_REPARTIR * $porcentaje_aportacion, 8);
                        $LMCE_DISTRIBUIDO = round($LMCE_APORTANTE_REPARTIR * $porcentaje_aportacion / 100, 2);
                        if($rece * $PMCE_DISTRIBUIDO == ($PMCE_APORTANTE_REPARTIR * 100)) {//return"".$rece*$PMCE_DISTRIBUIDO."esequal".($PMCE_APORTANTE_REPARTIR*100)."-----".$PMCE_DISTRIBUIDO;
                            //$apor+$rece;
                            for ($i = ($apor); $i < $apor + $rece; $i++){
                                $sql_historial = " insert into HIST_PMCE_DET ([des_hist_ini],[id_solicitud],[fecha],[lmce],[pmce_calculado] , [lmce_calculado] , [porcentaje_aportacion],[id_receptoras])VALUES "; 
                                $sql_historial.= " ('equitaviva',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD),GETDATE(),((SELECT pmce  FROM  DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL  where ID_EMB='" . $request->request->get("codem" . $i) . "')*'" . $CUOTA_TOTAL_ASIGNADA . "')  ,'" . $PMCE_DISTRIBUIDO . "' , '" . $LMCE_DISTRIBUIDO . "' , '" . $porcentaje_aportacion . "' , '" . $request->request->get("codem" . $i) . "')";   
                                $query2 = $DB_CON->prepare($sql_historial);
                                $query2->execute();
                            }
                            // return "Fin del Calculo!"."";         
                            return ":4:4:4:4equitativo:4:4:::LMCE_DISTRIBUIDO:" . $LMCE_DISTRIBUIDO . "   --   -     LMCE_APORTANTE_REPARTIR::" . $LMCE_APORTANTE_REPARTIR . "  --------  PMCE_APORTANTE_REPARTIR:" . $PMCE_APORTANTE_REPARTIR . "     ----    porcentaje_aportacion:" . $porcentaje_aportacion . "    ----   PMCE_DISTRIBUIDO:" . $PMCE_DISTRIBUIDO;
                        }else{
                            return "equitativo aplicar ajuste" . "";
                        }
        }
        return "no salio pòrque " . $distribucion;
    }
    
    
    public static function listareporte($cn){
        $sql = "SELECT ma.EMBARCACION,ma.MATRICULA,ma.PMCE,ma.REGIMEN,ma.CAP_BOD_M3,ma.ESTADO_PERMISO,   convert(varchar,convert(decimal(8,6), ma.PMCE* 100.0)) + '%' as PMCE  , cast(round(hd.lmce, 2) as decimal(10,2)) as lmce ,hd.pmce_calculado, hd.lmce_calculado,hd.porcentaje_aportacion,hd.cuota_asig FROM ";
        $sql.= "HIST_PMCE_DET as hd inner join DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL ma on ma.ID_EMB=hd.id_receptoras where id_solicitud=(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD) order by hd.id_hist_pmce_det  ";
//        $sql .= " CIC.DESCRIPCION AS CICLO,DAT.INDICATIVO_OFICIO AS INDICATIVO_DEL_DOCUMENTO, DAT.ASUNTO AS ASUNTO, DAT.USUARIO AS USUARIO,";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }

    public static function listar_idembarcacion($cn){
        $sql1 = "SELECT ma.EMBARCACION,ma.MATRICULA,ma.PMCE,ma.REGIMEN,ma.CAP_BOD_M3,ma.ESTADO_PERMISO,convert(varchar,convert(decimal(8,6), ma.PMCE* 100.0)) + '%' as PMCE    FROM ";
        $sql1 .= "DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL  as ma ";
        $sql1 .= "inner join ";
        $sql1 .= "EMB_APORTANTES hd on ma.ID_EMB=hd.id_embarcacion ";
        $sql1 .= "where hd.id_solicitud=(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD)";
        $query = $cn->prepare($sql1);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }

    public static function Cuota_Limite_temporada() {
        $sql1 = " select ANO_EJE,ID_TEMPORADA,CUOTA,RESOLUCION,ZONA from desarrollo_prueba.LIMITE_TEMPORADA  WHERE ID=(SELECT MAX(ID) FROM desarrollo_prueba.LIMITE_TEMPORADA)";
        $query = $cn->prepare($sql1);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }

    public static function listaDSCTO_LMCE($cn, $ID) {
        $sql = " select ID_EMB,EMBARCACION,LMCE,DSCTO_LMCE,ZONA,USUARIO from DB_DNEPP.dbo.EMBARCACIONES_PMCE WHERE ID_EMB=" . $ID . "'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }
              

    public static function listaembarcacion($cn, $request){
  
        $sql = "SELECT top 1  convert(varchar,convert(decimal(8,6), ma.PMCE_SIN_BENEFICIO* 100.0)) as PMCE_SIN_BENEFICIO , EP.ID,EP.ID1,EP.ID_EMB,EP.EMBARCACION,EP.MATRICULA,EP.REGIMEN,EP.CAP_BOD_AUT,CAST(convert(decimal(8,6),EP.PMCE) AS varchar(9)) AS PMCE,CAST(convert(decimal(8,2),REDISTRIB_DSCTO) AS varchar(4)) AS REDISTRIB_DSCTO,CAST(EP.LMCE AS FLOAT) AS LMCE,";
        $sql .="CAST(EP.DSCTO_LMCE AS FLOAT) AS DSCTO_LMCE ,EP.ZONA,EP.USUARIO,MA.ARMADOR , MA.EMBARCACION , MA.TEMPORA_INGRESO, MA.RESOLUCION ,MA.BENEFICIO , MA.ESTADO_PERMISO ";
        $sql .="FROM ";
        $sql .="DB_DNEPP.dbo.EMBARCACIONES_PMCE AS EP ";
        $sql .="INNER JOIN ";
        $sql .="DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL AS MA ON ";
        $sql .="EP.ID_EMB=MA.ID_EMB ";
        $sql .="WHERE EP.ID_EMB=" . $request->request->get("id_embar") . " order by EP.ID1 desc ";

        $DB_CON = $cn->getDoctrine()->getConnection("PMCE");
        $query = $DB_CON->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }

    
      public static function lista_all_embarcacion($cn, $request){
  
        $sql="select ID_EMB,EMBARCACION,MATRICULA from DB_DNEPP.dbo.MATRIZ_PMCE_ACTUAL where EMBARCACION like '".$request->request->get("embar")."%%'   ";
       // $sql .="WHERE EP.ID_EMB=" . $request->request->get("id_embar") . " order by EP.ID1 desc ";

        $DB_CON = $cn->getDoctrine()->getConnection("PMCE");
        $query = $DB_CON->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }
    
     
    

}

?>

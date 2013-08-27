<?php

namespace Produce\pmceBundle\Util;


class consultas {
    
     
    public static function InsertarSolicitud($cn,$request){
        
        $apor = $request->request->get("aportantes");        
        $rece = $request->request->get("receptores");  
     
        $distribucion = $request->request->get("radio-proporcion");
        // distribuicion radio1            equitativo radio2
        $magnitud = $request->request->get("radio-magnitud"); 
        // %radio1                        m3 radio2
        $item_tipo = $request->request->get("item");        
        if($item_tipo==0) $value="Asociación";                  
        if($item_tipo==1) $value="Reducción";                   
        if($item_tipo==2) $value="Sustitución";             
        //$ultimo = $request->request->get("codem0");         
        $DB_CON= $cn->getDoctrine()->getConnection("PMCE");
        ////var comprobacion
        $IMPRMIW=$distribucion+"-"+$magnitud;
        $suma_PMCE=0;
        $PMCE_APORTANTE_REPARTIR=0;
        $porcentaje_aportacion=0;
        $PMCE_DISTRIBUIDO=0;
        $CUOTA_TOTAL_ASIGNADA=810000;                        /////////////////AQUI ASIGNO EL CUOTA TOTAL
        
        $new_documento =" exec sp_solicitud_insert ";
        $new_documento.="@A0=0,";
        $new_documento.="@A1=".$item_tipo.",";
        $new_documento.="@A2='$value'";
        $query1 = $DB_CON->prepare($new_documento);
        $query1->execute();
        $total=$apor+$rece;
        $cont=0;
        return "sigue";
            while($total>0){
                if($cont<$apor){
                    $embarcacion= $request->request->get("codem".$cont);
                    $sql_aportantes = " insert into EMB_APORTANTES (id_embarcacion,id_solicitud) values('".$embarcacion."',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD)); ";                    
                    $query2 = $DB_CON->prepare($sql_aportantes);
                    $query2->execute();
                            $sql ="select ID_EMB, PMCE from DAT_MATRIZ_PMCE_ACTUAL WHERE ID_EMB='".$embarcacion."'";
                            $query = $DB_CON->prepare($sql);
                            $query->execute();
                            $result = $query->fetchAll();
                            foreach ($result as $pmce_apor){
                            $PMCE_APORTANTE_REPARTIR=$pmce_apor["PMCE"];;   
                            $suma_PMCE+=$pmce_apor["PMCE"];
                            }
                }else
                {
                    $embarcacion= $request->request->get("codem".$cont);
                    $sql_aportantes = " insert into EMB_RECEPTORAS (id_embarcacion,id_solicitud) values('".$embarcacion."',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD)); ";
                    $query2 = $DB_CON->prepare($sql_aportantes);
                    $query2->execute();
                                $sql =" select ID_EMB, PMCE from DAT_MATRIZ_PMCE_ACTUAL WHERE ID_EMB='".$embarcacion."'";
                                $query = $DB_CON->prepare($sql);
                                $query->execute();
                                $result = $query->fetchAll();
                                foreach ($result as $pmce_apor){
                                $suma_PMCE+=$pmce_apor["PMCE"];
                                //$IMPRMIW=$pmce_apor["ID_EMB"]+"----"+$pmce_apor["PMCE"];
                                }
                }
            $total--;
            $cont++;
            }
            
          
            //porcentajes de aportacion                                                                                     
            if($distribucion=="radio1"){      //armador asigna y si la distribucion se ingresan por los campos de entrada
                
            }
            if($distribucion=="radio2"){//siesequitativo
                $porcentaje_aportacion=100/$rece;    //   se divide entre el total de receptores
                $PMCE_DISTRIBUIDO=round($PMCE_APORTANTE_REPARTIR*$porcentaje_aportacion,8);
                $LMCE_DISTRIBUIDO=round($PMCE_APORTANTE_REPARTIR*$porcentaje_aportacion,2);
                //$PMCE_APORTANTE_REPARTIR;
                //round($rece*$PMCE_DISTRIBUIDO,2);
                // $rece*$PMCE_DISTRIBUIDO
       
               // return "".$rece*$PMCE_DISTRIBUIDO." es equal ".($PMCE_APORTANTE_REPARTIR*100)."     -----     ".$PMCE_DISTRIBUIDO; 
                    if($rece*$PMCE_DISTRIBUIDO=($PMCE_APORTANTE_REPARTIR*100))
                    {   // return "".$rece*$PMCE_DISTRIBUIDO." es equal ".($PMCE_APORTANTE_REPARTIR*100)."     -----     ".$PMCE_DISTRIBUIDO;
                        
//                        for($i=0;$i<;$i++){
//                            
//                        }
                        $embarcacion= $request->request->get("codem".$cont);
                        
                        $sql_historial="insert into HIST_PMCE_DET ([des_hist_ini],[id_solicitud], [fecha] , [pmce_calculado] , [lmce_calculado] , [porcentaje_aportacion],[id_receptoras])VALUES
                        ('agregando',(SELECT MAX(id_solicitud) FROM DAT_SOLICITUD) , GETDATE(), '".$PMCE_DISTRIBUIDO."' , '".$PMCE_DISTRIBUIDO."' , '".$porcentaje_aportacion."' , '24' )";
                        $query2=$DB_CON->prepare($sql_historial);
                        $query2->execute();
                         
                        return "paso por el inserto"."";
                         
                    }
                    else 
                    {
                        return "salio de la condicion"."";  
                    }
            }
        
            
        
       return "hola "."no salio pòrque ".$distribucion;    
        
        //(SELECT MAX(id_solicitud) FROM SOLICITUD)
 
 
    }
    
   
    
    
    
     
    
    

}
?>

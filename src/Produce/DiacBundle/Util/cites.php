<?php

namespace Produce\DiacBundle\Util;

class cites{
    /***************************************************************************/
    /* Métodos para Acta de Inspección de Reproduccion                         */    
    /***************************************************************************/
    public function ActaRep_Listar($cn){        
        $sql = "SELECT 
                    A.ID_ACTA_INS_REPRO, A.ID_ACUICULTOR, (SELECT B.NOM_ACUICULTOR FROM ACUICULTOR AS B WHERE B.ID_ACUICULTOR = A.ID_ACUICULTOR) AS NOM_ACUICULTOR, A.NUM_ACTA, CONVERT(VARCHAR(10),A.FECHA,111) AS FECHA, CONVERT(VARCHAR(10),A.HORA,108) AS HORA, A.NUM_RESOLUCION, A.OBSERVACIONES 
                FROM 
                    acta_inspeccion_reproduccion as A";         
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;        
    }

    public function ActaRep_Buscar($cn,$nActaRep){        
        $sql = "SELECT   
                    A.ID_ACTA_INS_REPRO, A.ID_ACUICULTOR, (SELECT B.NOM_ACUICULTOR FROM ACUICULTOR AS B WHERE B.ID_ACUICULTOR = A.ID_ACUICULTOR) AS NOM_ACUICULTOR, A.NUM_ACTA, CONVERT(VARCHAR(10),A.FECHA,111) AS FECHA, CONVERT(VARCHAR(10),A.HORA,108) AS HORA, A.NUM_RESOLUCION, A.OBSERVACIONES 
                FROM 
                    acta_inspeccion_reproduccion as A
                WHERE 
                    A.ID_ACTA_INS_REPRO = '".$nActaRep."'";         
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;        
    }
    
    public function ActaRep_Guardar($cn,$nActaRep,$Acuicultor,$nResol,$nFecha,$nObs){                                    
        $sql = "exec p_ACTAREPRODUCCION_ADD '".$nActaRep."','".$Acuicultor."','".$nResol."','".$nFecha."','".$nObs."'";           
        $query = $cn->prepare($sql);            
        $query->execute();
        $result = $query->fetchColumn();
        return $result;
    }    
    
    public function ActaRep_Actualizar($cn,$nID,$nActaRep,$Acuicultor,$nResol,$nFecha,$nObs){                                    
        $sql = "exec p_ACTAREPRODUCCION_UPDATE '".$nID."','".$nActaRep."','".$Acuicultor."','".$nResol."','".$nFecha."','".$nObs."'";           
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchColumn();
        return $result;
    }    
    
    public function ActaRep_Eliminar($cn,$nID){                          
        $sql = "exec p_ACTAREPRODUCCION_DELETE '".$nID."'";        
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchColumn();
        return $result;        
    } 
    
    
    /***************************************************************************/
    /* Métodos para Acta de Inspección y Verificación                          */        
    /***************************************************************************/    
    public function ActaVerif_Listar($cn){        
        $sql = "SELECT 
                    ID_ACTA_INS_LEVANTE,
                    NUM_ACTA,
                    FECHA,
                    HORA,
                    NUM_ALEVINOS,
                    LONG_PROMEDIO,
                    OBSERVACIONES,
                    DESTINO
                FROM 
                    user_dna.ACTA_INSPECCION_VERIF_LEVANTE
                ORDER BY
                    ID_ACTA_INS_LEVANTE ASC";         
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;        
    }
    
    public function ActaVerif_Buscar($cn,$NumActaRep){        
        $sql = "SELECT *FROM ACTA_INSPECCION_VERIF_LEVANTE WHERE ID_ACTA_INS_LEVANTE = '".$NumActaRep."'";         
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;        
    }
    public function ActaVerif_Guardar($cn,$NumActaRep,$NumActa,$Fecha,$NumAlevinos,$LongAlevinos,$Observaciones){                                    
        $sql = "exec p_ActaVerificacion_Add '".$NumActaRep."','".$NumActa."','".$Fecha."','".$NumAlevinos."','".$LongAlevinos."','".$Observaciones."'";        
        $query = $cn->prepare($sql);            
        $query->execute();
        $result = $query->fetchColumn();
        return $result;
    }    
    
    public function ActaVerif_Actualizar($cn,$nID,$nActaRep,$Acuicultor,$nResol,$nFecha,$nObs){                                    
        $sql = "exec p_ACTAREPRODUCCION_UPDATE '".$nID."','".$nActaRep."','".$Acuicultor."','".$nResol."','".$nFecha."','".$nObs."'";           
        $query = $cn->prepare($sql);
        $query->execute();
    }    
    
    public function ActaVerif_Eliminar($cn,$nID){                          
        $sql = "exec p_ACTAREPRODUCCION_DELETE '".$nID."'";        
        $query = $cn->prepare($sql);
        $query->execute();
    } 
}
?>

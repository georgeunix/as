<?php

namespace Produce\DiacBundle\Util;

class Acuicultor{            
    
    public function Acuicultor_Listar($cn){
        $sql = "SELECT 
                    ID_ACUICULTOR, NOM_ACUICULTOR
                FROM 
                    ACUICULTOR";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result; 
    }   
    
    
    
    
}
?>

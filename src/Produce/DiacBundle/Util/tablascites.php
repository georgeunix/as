<?php

namespace Produce\DiacBundle\Util;

class tablascites{
    
    public function Acuario_Listar($cn){
        $sql = "SELECT ID_ACUARIO, NOM_ACUARIO FROM ACUARIO";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;     
    }
    
    public function Acuicultor_Listar($cn){
        $sql = "SELECT ID_ACUICULTOR, NOM_ACUICULTOR FROM ACUICULTOR";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;     
    }
    
    public function Comercializador_Listar($cn){
        $sql = "SELECT ID_COMERCIALIZADOR,NOMBRE_COMERCIALIZADOR FROM COMERCIALIZADOR";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;     
    }

    public function Fundo_Listar($cn){
        $sql = "SELECT ID_FUNDO, NOMBRE_FUNDO FROM FUNDO FROM FUNDO";
        $query = $cn->prepare($sql);
        $query -> execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function UnidadMedida_Listar($cn){
        $sql = "SELECT ID_UNI_MEDIDA,NOM_UNI_MEDIDA FROM UNIDAD_MEDIDA";
        $query = $cn->prepare($sql);
        $query -> execute();
        $result = $query->fetchAll();
        return $result;
    }
    
    
    
    
  





    
        
        
        
}
?>
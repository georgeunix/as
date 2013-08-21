<?php
namespace Produce\DiacBundle\Util;

class General {
    
    public function devolverDepartamentos($cn) {
        $sql = "SELECT * FROM DB_GENERAL.dbo.DEPARTAMENTO WHERE CODIGO_DEPARTAMENTO!='00'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }
    public function devolverProvincias($cn,$cod_dep) {
        $sql = "SELECT * FROM DB_GENERAL.dbo.PROVINCIA WHERE CODIGO_DEPARTAMENTO='$cod_dep' AND CODIGO_PROVINCIA!='00'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }
    public function devolverDistritos($cn,$cod_dep,$cod_prov) {
        $sql = "SELECT * FROM DB_GENERAL.dbo.DISTRITO WHERE CODIGO_DEPARTAMENTO='$cod_dep' AND CODIGO_PROVINCIA='$cod_prov' AND CODIGO_DISTRITO!='00'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
    }
    public function devolverTipoDerecho($cn){
        $sql = "SELECT CODIGO_TIP_DERECHO AS ID, DESCRIP_TIP_DERECHO AS DERECHO FROM TIPO_DERECHO";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
        
    }
    public function devolverTipoResolucion($cn){
        $sql = "SELECT CODIGO_TIP_RESOLUCION AS ID, DESCRIP_TIP_RESOLUCION AS RESOLUCION FROM TIPO_RESOLUCION";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
        
    }
    public function devolverTipoDesarrollo($cn){
        $sql = "SELECT CODIGO_TIPO_DESARROLLO AS ID, DESCRIPCION_TIPO_DESARROLO AS DESARROLLO FROM TIPO_DESARROLLO";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
        
    }
    public function devolverTipoEstado($cn){
        $sql = "SELECT CODIGO_ESTADO AS ID,DESCRIP_ESTADO AS ESTADO FROM ESTADO";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
        
    }
    public function devolverTipoRecurso($cn){
        $sql = "SELECT CODIGO_TIPO_RECURSO AS ID,DESCRIPCION_TIPO_RECURSO AS RECURSO FROM TIPO_RECURSO WHERE CODIGO_TIPO_RECURSO!=0";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
        
    }
   public function devolverRecursosxTipo($cn,$cod_tipo_recurso){
        $sql = "SELECT CODIGO_RECURSO AS ID, NOMBRE_RECURSO AS RECURSO FROM vw_listado_recurso_diac WHERE CODIGO_TIPO_RECURSO=$cod_tipo_recurso";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;
        
    }
    
    
}
?>

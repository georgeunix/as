<?php

namespace Produce\DiacBundle\Util;

class Mantenimiento {

    public function listFamilia($cn) {
        $sql = "select * from dna.dbo.familia";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
     public function listGrupoEspecie($cn) {
        $sql = "select COD_GRUPO_ESPECIE as ID, NOM_GRUPO_ESPECIE AS GRUPO from dbo.GRUPO_ESPECIE";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    public function listEspecies($cn) {
        $sql = "select * from vista_especies_familia";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
     public function returnEspecie($cn, $id) {
        $sql = "select * from vista_especies_familia where id=$id";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    public function devolverEspecies($cn, $id_familia){
        $sql = "select * from ESPECIE where IDFAMILIA=$id_familia";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public function guardarEspecie($cn, $nom, $cient, $fam, $user,$grupoespecie) {
        $sql = "exec sp_ingresar_especie $fam,'$nom','$cient','$user',$grupoespecie";
        $query = $cn->prepare($sql);
        if ($query->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function actualizarEspecie($cn, $id, $nom, $cient, $fam, $user,$grupoespecie) {
        $sql = "exec sp_actualizar_especie $fam,'$nom','$cient','$user',$id,$grupoespecie";
        $query = $cn->prepare($sql);
        if ($query->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function eliminarEspecie($cn, $id) {
        $sql = "exec sp_eliminar_especie $id";
        $query = $cn->prepare($sql);
        if ($query->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function devolverEmpresas($cn) {
        $sql = "SELECT top 20000 * FROM vw_listado_persona_diac";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    public function devolverRecursos($cn) {
        $sql = "SELECT * FROM vw_listado_recurso_diac";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    public function devolverTiposRecursos($cn) {
        $sql = "SELECT * FROM TIPO_RECURSO WHERE CODIGO_TIPO_RECURSO!=0";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
     public function returnRecurso($cn,$cod_recurso) {
        $sql = "SELECT * FROM vw_listado_recurso_diac where CODIGO_RECURSO=$cod_recurso";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    public function guardarRecurso($cn,$cod_recurso,$recurso,$tiporecurso,$dep,$prov,$dis,$zona,$espejo,$profundidad){
        
        if($cod_recurso==''){
            $sql = "exec sp_ingresar_recurso '$recurso',$tiporecurso,'$dep','$prov','$dis',$espejo,$profundidad,'$zona' ";
        }else{
             $sql = "exec sp_actualizar_recurso $cod_recurso,$tiporecurso,'$dep','$prov','$dis','$recurso',$espejo,$profundidad,'$zona' ";
        }
        $query = $cn->prepare($sql);
        
        if ($query->execute()) {
            return 1;
        } else {
            return 0;
        }        
    }
    public function eliminarRecurso($cn,$cod_recurso){
        $sql = "exec sp_eliminar_recurso $cod_recurso ";
        $query = $cn->prepare($sql);
        
        if ($query->execute()) {
            return 1;
        } else {
            return 0;
        }
        
    }
}

?>

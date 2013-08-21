<?php

namespace Produce\DiacBundle\Util;

class Autorizacion {

    public function devolverResoluciones($cn, $id_dep) {
        $sql = "select * from vw_listado_areas_habilitadas where codigo_departamento=$id_dep";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public function devolverMapasAcuicolas($cn, $id_dep) {
        $sql = "select * from vw_listado_mapa_acuicola where codigo_departamento=$id_dep";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    public function caducarDerechoVigente($cn, $cod_sucursal) {
        $sql = "update dbo.SUCURSAL set CODIGO_ESTADO=2 where CODIGO_SUCURSAL=$cod_sucursal";
        $query = $cn->prepare($sql);
        if ($query->execute()) {
            return 1;
        } else {
            return 0;
        }
    }
    public function buscarempresa($cn, $like) {
        $sql = "select top 10 * from vw_listado_persona_diac where persona like'%$like%'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
     public function listadoAnexoResolucion($cn,$resolucion ) {
        $sql = "select * from vw_listado_anexos_diac where RESOLUCION ='$resolucion'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        
        return $result_query;
    }
}

?>

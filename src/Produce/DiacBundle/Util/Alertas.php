<?php

namespace Produce\DiacBundle\Util;

/**
 * Description of Alertas
 *
 * @author Alex Santiago
 */
class Alertas {

    //put your code here


    public function alerta01($cn) {
        $sql = "select id,Case id_tipo_persona when 1 then nombres  + ' ' + apellidos 
                when 2 then razon_social end as persona,flag  From db_general.dbo.persona 
                where flag IN ('C')  and substring(rubro,1,1)='1' order by persona asc";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public function alerta02($cn) {
        $sql = "SELECT * FROM lista_consolidado_1  order by descrip_tip_derecho,descrip_estado";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public function alerta02D($cn, $id) {
        $sql = "select s.codigo_sucursal,numero_resolucion,fecha_emision,fecha_vigencia,codigo_empresa ,case p.id_tipo_persona
                when 1 then p.nombres + ' ' + p.apellidos when 2 then p.razon_social end as persona 
                from  sucursal s,db_general.dbo.persona p  Where s.codigo_tipo_derecho ='$id' And s.codigo_estado ='1'  And s.codigo_empresA = p.id ";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public function alerta03($cn) {
        $sql = "select * from lista_seg";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public function alerta03D($cn,$mayor,$menor) {
        $sql = "select DEP.DEPARTAMENTO, s.codigo_sucursal,th.descrip_tip_derecho,td.DESCRIPCION_TIPO_DESARROLO as desarrollo,
                       case p.id_tipo_persona 
			 when 1 then p.nombres+' ' + p.apellidos 
			 when 2 then p.razon_social end as persona,
			s.numero_resolucion,s.fecha_emision,s.fecha_vigencia,datediff(dd,getdate(),convert(datetime,fecha_vigencia,103)) as dias 
			
                 from dna.dbo.sucursal s,db_general.dbo.persona p,dna.dbo.tipo_derecho th,dbo.TIPO_DESARROLLO as td, DB_GENERAL.DBO.DEPARTAMENTO DEP
                Where S.CODIGO_DEPARTAMENTO=DEP.CODIGO_DEPARTAMENTO 
                  AND s.codigo_Estado = 1 
                  and  datediff(dd,getdate(),convert(datetime,s.fecha_vigencia,103))>=$menor 
                  and datediff(dd,getdate(),convert(datetime,s.fecha_vigencia,103))<=$mayor
                  and s.codigo_empresa=p.id and s.codigo_tipo_derecho=th.codigo_tip_derecho 
                  and s.CODIGO_DESARROLLO=td.CODIGO_TIPO_DESARROLLO
                  order by  convert(datetime,s.fecha_vigencia,103) 
                  asc";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

    public function alerta04($cn) {
        $sql = "SELECT * FROM lista_derecho_cad_activos ORDER BY fecha_vigencia DESC";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    public function alerta05($cn) {
        $sql = "select  * from lista_autorizaciones_concesiones_cad";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }

}

?>

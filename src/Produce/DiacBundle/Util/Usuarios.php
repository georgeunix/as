<?php

namespace Produce\DiacBundle\Util;

/**
 * Description of Usuarios
 *
 * @author Jesús Vásquez
 */
class Usuarios {
    //put your code here
      
    public function listaTrabajador($cn,$apellido,$nombre) {
        $sql = " select v.codigo_trabajador, v.tipo_trabajador,v.apellidos,v.nombres,v.dni,v.usuario_sistema 
                 From vw_trabajador_detalle v
                 where apellidos like '%".$apellido."%' or nombres like '%".$nombre."%'
                 order by codigo_trabajador asc";
            
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    
    public function listaPerfiles($cn,$codTrabajador) {
        $sql = "select a.id_grupo, a.ID_DEPENDENCIA, a.descripcion, isnull(b.codigo_trabajador,0) as codigo
                FROM DB_GENERAL.dbo.grupo a
                Left join grupo_trabajador b on b.id_grupo = a.id_grupo and b.codigo_trabajador = ".$codTrabajador." 
                where ID_DEPENDENCIA=22 and ID_APLICACION=1
                ORDER BY a.descripcion";
        
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;        
    }
    
    /*Eliminar esta funcion*/
    public function codTrabajador($cn,$email) {
        $sql = "select codigo_trabajador from db_general.jcardenas.h_trabajador where email='".$email."' AND ESTADO='ACTIVO'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;                
    }
    
    public function ValidaCodGrupo($cn,$codTrabajador){
        $sql = "select count(*) as hay from grupo_trabajador where codigo_trabajador=".$codTrabajador."";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();
        return $result_query;                                
    }

    public function GuardarPerfilUsuario($cn,$idgrupo,$codTrabajador,$accion) {
        if ($accion == "I"){
            $sql = "insert into grupo_trabajador (id_grupo,codigo_trabajador,estado_trabajador_grupo)
             values('".$idgrupo."',".$codTrabajador.",'1')";
        }elseif ($accion == "U"){
            $sql = "update grupo_trabajador set id_grupo='".$idgrupo."' where codigo_trabajador=".$codTrabajador."";
        } 
        $query = $cn->prepare($sql);
        $query->execute();
    }    
    
    public function EliminarPerfilUsuario($cn,$idgrupo,$codTrabajador) {
        $sql = "delete from grupo_trabajador where id_grupo='".$idgrupo."' and codigo_trabajador=".$codTrabajador."";
        $query = $cn->prepare($sql);
        $query->execute();
    }    
    
}

?>

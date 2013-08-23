<?php

namespace Produce\DiacBundle\Util;

class consultas {

   function CodGrupo($cn,$codUsuario) {
        $sql = "select tg.id_grupo from db_general.jcardenas.h_trabajador h
                inner join grupo_trabajador tg on tg.codigo_trabajador=h.CODIGO_TRABAJADOR
                where h.EMAIL='".$codUsuario."' ";
        $query = $cn->prepare($sql);//preparo consulta
        $query->execute();//ejecuto la consulta
        $result = $query->fetchAll();//guardo los resultados
        return $result;//retorno el array de resultado
    }

    public function MenuNivel_1($cn,$codGrupo) {
        $sql = "SELECT nm.id_main_menu,nm.descripcion,nm.estado_menu,nm.archivo,nm.orden,nm.parent,nm.nivel_menu
	FROM grupo_main_menu nm
	inner join grupo_grupo_main_menu gm on gm.id_main_menu = nm.id_main_menu 
	WHERE nm.parent=0 and nm.nivel_menu=1 and nm.estado_menu=1 
	and gm.id_grupo = '".$codGrupo."' ORDER BY nm.orden ASC";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    
    public function MenuNivel_2($cn,$codGrupo,$id_main_menu) {
        $sql = "SELECT nm.id_main_menu,nm.descripcion,nm.estado_menu,nm.archivo,nm.orden,nm.parent,nm.nivel_menu
          FROM grupo_main_menu nm
          INNER JOIN grupo_grupo_main_menu gm on gm.id_main_menu = nm.id_main_menu 
          WHERE nm.parent=".$id_main_menu." and nm.nivel_menu=2 and nm.estado_menu=1
		and gm.id_grupo = '".$codGrupo."' ORDER BY nm.orden ASC";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
    
    
    public function MenuNivel_3($cn,$codGrupo,$id_main_menu) {
        $sql = "SELECT nm.id_main_menu,nm.descripcion,nm.estado_menu,nm.archivo,nm.orden,nm.parent,nm.nivel_menu
                FROM grupo_main_menu nm
                INNER JOIN grupo_grupo_main_menu gm on gm.id_main_menu = nm.id_main_menu 
                WHERE nm.parent=".$id_main_menu." and nm.nivel_menu=3 and nm.estado_menu=1
                and gm.id_grupo = '".$codGrupo."' ORDER BY nm.orden ASC";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }     
    
    
    public function MenuTotal($cn,$uname) {
        
        $codGrupo = $this->CodGrupo($cn,$uname);
        //var_dump($uname);
        
        //Menu: Primer Nivel
        $menuNivel_1 = $this->MenuNivel_1($cn,$codGrupo[0]["id_grupo"]);

        $html_menu='';

        $html_menu.='<div id="smoothmenu1" class="ddsmoothmenu">';
        $html_menu.='<ul>';
        $i=0;
        foreach ($menuNivel_1 as $value) {
            $ruta="#";
            if ($value["archivo"]!=""){
                $ruta=$value["archivo"];
            }
            $html_menu.='<li><a href="'.$ruta.'">'.$value["descripcion"].'</a>';

            //Menu: Segundo Nivel
            $menuNivel_2 = $this->MenuNivel_2($cn,$codGrupo[0]["id_grupo"],$menuNivel_1[$i]["id_main_menu"]);
            if (count($menuNivel_2)>0){
                $html_menu.='<ul>';
                
                $j=0;
                foreach ($menuNivel_2 as $value2) {
                    if ($value2["archivo"]!=""){
                        $ruta=$value2["archivo"];
                    }    
                    $html_menu.='<li><a href="'.$ruta.'">'.$value2["descripcion"].'</a>';


                    //Menu: Tercer Nivel
                    $menuNivel_3 = $this->MenuNivel_3($cn,$codGrupo[0]["id_grupo"],$menuNivel_2[$j]["id_main_menu"]);
                    if (count($menuNivel_3)>0){
                        $html_menu.='<ul>';
                        foreach ($menuNivel_3 as $value3) {
                            if ($value3["archivo"]!=""){
                                $ruta=$value3["archivo"];
                            }
                            $html_menu.='<li><a href="'.$ruta.'">'.$value3["descripcion"].'</a></li>';
                        }
                        $html_menu.='</ul>';
                    }
                    $j++;
                    
                    $html_menu.='</li>';
                }
                $html_menu.='</ul>';
            }
            $html_menu.='</li>';

            $i++;
        }
        $html_menu.='</ul>';
        $html_menu.='</div>';

        Return $html_menu;
    }
    
    /*
    public static function listaCicloProyecto($cn,$usuario) {
        $sql = " select DAT.ID_DOCUMENTO_PROY AS CODIGO,CLA.DESCRIPCION AS TIPO_DE_DOCUMENTO,DAT.AUDITMOD as FECHA_CREACION,";
        $sql.=" CIC.DESCRIPCION AS CICLO,DAT.INDICATIVO_OFICIO AS INDICATIVO_DEL_DOCUMENTO, DAT.ASUNTO AS ASUNTO, DAT.USUARIO AS USUARIO";
        $sql.=" from DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql.=" dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO=CLA.ID_CLASE_DOCUMENTO_INTERNO where DAT.ESTADO=1";
        $sql.=" AND   USUARIO=  '$usuario'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    
    public static function listaParaFirmar($cn,$usuario) {
        $sql = " select DAT.ID_DOCUMENTO_PROY AS CODIGO,CLA.DESCRIPCION AS TIPO_DE_DOCUMENTO,DAT.AUDITMOD as FECHA_CREACION,";
        $sql.=" CIC.DESCRIPCION AS CICLO,DAT.INDICATIVO_OFICIO AS INDICATIVO_DEL_DOCUMENTO, DAT.ASUNTO AS ASUNTO, DAT.USUARIO AS USUARIO";
        $sql.=" from DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql.=" dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO=CLA.ID_CLASE_DOCUMENTO_INTERNO where DAT.ESTADO=2";
        $sql.=" AND   USUARIO=  '$usuario'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
    
    public static function listaParaVistoBueno($cn,$usuario) {
        $sql = " select DAT.ID_DOCUMENTO_PROY AS CODIGO,CLA.DESCRIPCION AS TIPO_DE_DOCUMENTO,DAT.AUDITMOD as FECHA_CREACION,";
        $sql.=" CIC.DESCRIPCION AS CICLO,DAT.INDICATIVO_OFICIO AS INDICATIVO_DEL_DOCUMENTO, DAT.ASUNTO AS ASUNTO, DAT.USUARIO AS USUARIO";
        $sql.=" from DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql.=" dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO=CLA.ID_CLASE_DOCUMENTO_INTERNO where DAT.ESTADO=7";
        $sql.=" AND   USUARIO=  '$usuario'";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;
    }
     * */

}
?>

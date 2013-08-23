<?php

namespace Produce\DiacBundle\Util;

/**
 * Description of Perfiles
 *
 * @author Jesús Vásquez
 */
class Perfiles {
    //put your code here
    
    public function listaPerfiles($cn) {
        $sql = "select g.ID_GRUPO,g.DESCRIPCION,g.ID_DEPENDENCIA,d.DEPENDENCIA
                from db_general.dbo.grupo g
                inner join DB_GENERAL.jcardenas.H_DEPENDENCIA d on d.CODIGO_DEPENDENCIA = g.ID_DEPENDENCIA
                where ID_DEPENDENCIA=22 and ID_APLICACION=1
                ORDER BY DESCRIPCION";
        $query = $cn->prepare($sql);
        $query->execute();
        $result_query = $query->fetchAll();

        return $result_query;        
    }
    
    
   public function GuardarPerfil($cn,$idgrupo,$descri,$accion) {
        try{
            if ($accion == "N"){
                $sql = "exec dbo.p_GRUPO_ADD '".$descri."'";
            }elseif ($accion == "U"){
                $sql = "exec dbo.p_GRUPO_UPDATE '".$idgrupo."','".$descri."'";
            } 
            $query = $cn->prepare($sql);
            $query->execute();
            return $idgrupo;
            
        }catch(Exception $e){
            return $e;
        }
   }
   
   public function EliminarOpciones($cn,$idgrupo) {
        try{
            $sql = "delete from grupo_grupo_main_menu where id_grupo = '".$idgrupo."'";
            $query = $cn->prepare($sql);
            $query->execute();
            return $idgrupo;
            
        }catch(Exception $e){
            return $e;
        }
   }
   
   public function GuardarOpciones($cn,$idgrupo,$idmenu) {
        try{
            $sql="INSERT INTO grupo_grupo_main_menu(id_main_menu,id_grupo,estado_grupo_main_menu) VALUES (".$idmenu.",'".$idgrupo."',1)";
            $query = $cn->prepare($sql);
            $query->execute();
            return $idgrupo;
            
        }catch(Exception $e){
            return $e;
        }
   }   
   
   
   public function OpcionesNivel_1($cn) {
        $sql = "SELECT * FROM vw_menu where nivel_menu=1 and estado_menu=1 order by orden";
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
    
    public function OpcionesNivel_2($cn,$padre) {
        $sql = "SELECT * FROM vw_menu where parent=".$padre." and nivel_menu=2 and estado_menu=1 order by orden asc";
        
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }    
            
    public function OpcionesNivel_3($cn,$padre) {
        $sql = "SELECT * FROM vw_menu where parent=".$padre." and nivel_menu=3 and estado_menu=1 order by orden asc";
        
        $query = $cn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }              
    
    public function TraeGrupo($cn,$codGrupo) {
        //Captura mombre del Grupo
        $sql= "select ID_GRUPO, ID_DEPENDENCIA, DESCRIPCION from db_general.dbo.GRUPO where ID_DEPENDENCIA=22 and ID_APLICACION=1 and ID_GRUPO='".$codGrupo."'";       
        $query = $cn->prepare($sql);
        $query->execute();
        $data_Grupo = $query->fetchAll();
        return $data_Grupo;
    } 
    
    public function listaOpciones($cn,$codGrupo) {
        $html_menu='';
                
        $Grupo = $this->TraeGrupo($cn,$codGrupo);
        foreach ($Grupo as $valueGrupo) {
            $html_menu.='<p class="CSS_title1"> PERFIL:  "'.$valueGrupo['DESCRIPCION'].'"</p>';
        }
        
        $html_menu.='<table id="CSS_dataPerfil">
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Descripcion</th>
                        </tr>';
                        
        //Menu: Primer Nivel
        $menuNivel_1 = $this->OpcionesNivel_1($cn);

        foreach ($menuNivel_1 as $value) {
                        
            $qry_grupo1= "select * from grupo_grupo_main_menu where id_grupo = '".$codGrupo."'";
            $query = $cn->prepare($qry_grupo1);
            $query->execute();
            $rsgrup1 = $query->fetchAll();
            
            $chk_N1="";
            foreach ($rsgrup1 as $row_chN1) {
                //$html_menu.=$value['id_main_menu'].'<br/>';
                if ($row_chN1['id_main_menu']==$value['id_main_menu']){
                    $chk_N1="checked=checked";
                    break;
                }
            }//end while grupo 1
            
            $html_menu.='<tr bgcolor="#E6CF98">';
            $html_menu.='    <td>';
            $html_menu.='        <input type="checkbox" name="id_main_menu[]" class="nivelP'.$value['id_main_menu'].'" id="id_main_menu" value="'.$value['id_main_menu'].'" onclick="checkNivel01(this,'.$value['id_main_menu'].',0,\'P\')" '.$chk_N1.' />';
            $html_menu.='    </td>';
            $html_menu.='    <td>'.$value['id_main_menu'].'</td>';
            $html_menu.='    <td>'.$value['descripcion'].'</td>';
            $html_menu.='</tr>';
            
            //Menu: Segundo Nivel
            $menuNivel_2 = $this->OpcionesNivel_2($cn,$value['id_main_menu']);
            if (count($menuNivel_2)>0){
                 
                foreach ($menuNivel_2 as $value_2) { 

                    $qry_grupo2= "select * from grupo_grupo_main_menu where id_grupo = '".$codGrupo."'";
                    $query = $cn->prepare($qry_grupo2);
                    $query->execute();
                    $rsgrup2 = $query->fetchAll();

                    $chk_N2="";
                    foreach ($rsgrup2 as $row_chN2) {
                        //$html_menu.=$value['id_main_menu'].'<br/>';
                        if ($row_chN2['id_main_menu']==$value_2['id_main_menu']){
                            $chk_N2="checked=checked";
                            break;
                        }
                    }//end while grupo 2
                    
                    $html_menu.='<tr bgcolor="#F2E6CA">';
                    $html_menu.='   <td>&nbsp;&nbsp;';
                    $html_menu.='     <input type="checkbox" name="id_main_menu[]" class="nivelH'.$value_2['parent'].' N2'.$value_2['id_main_menu'].'" id="id_main_menu" value="'.$value_2['id_main_menu'].'" onclick="checkNivel01(this,'.$value['id_main_menu'].','.$value_2['id_main_menu'].',\'H2\')" '.$chk_N2.'/>';
                    $html_menu.='   </td>';
                    $html_menu.='   <td>'.$value_2['id_main_menu'].'</td>';
                    $html_menu.='   <td>'.$value_2['descripcion'].'</td>';
                    $html_menu.='</tr>';
                    
                    
                    //Menu: Segundo Nivel
                    $menuNivel_3 = $this->OpcionesNivel_3($cn,$value_2['id_main_menu']);
                    if (count($menuNivel_3)>0){
                            
                        foreach ($menuNivel_3 as $value_3) { 
                            
                            $clase="";
                            if ($value_2["id_main_menu"]==63){
                                    $clase="NivelH363";
                            }elseif($value_2["id_main_menu"]==65){
                                    $clase="NivelH365";
                            }elseif($value_2["id_main_menu"]==67){
                                    $clase="NivelH367";
                            }
                        
                            $qry_grupo3= "select * from grupo_grupo_main_menu where id_grupo = '".$codGrupo."'";
                            $query = $cn->prepare($qry_grupo3);
                            $query->execute();
                            $rsgrup3 = $query->fetchAll();

                            $chk_N3="";
                            foreach ($rsgrup3 as $row_chN3) {
                                if ($row_chN3['id_main_menu']==$value_3['id_main_menu']){
                                    $chk_N3="checked=checked";
                                    break;
                                }
                            }//end while grupo 3                 
                            
                            $html_menu.='<tr bgcolor="#F6EEDC">';                           
                            $html_menu.='   <td>&nbsp;&nbsp;&nbsp;&nbsp;';
                            $html_menu.='     <input type="checkbox" name="id_main_menu[]" class="'.$clase.'" id="id_main_menu" value="'.$value_3['id_main_menu'].'" onclick="checkNivel01(this,'.$value['id_main_menu'].','.$value_2['id_main_menu'].','.$value_3['id_main_menu'].',\'H3\')" '.$chk_N3.'/>';
                            $html_menu.='   </td>';
                            $html_menu.='   <td>'.$value_3['id_main_menu'].'</td>';
                            $html_menu.='   <td>'.$value_3['descripcion'].'</td>';
                            $html_menu.='</tr>';                            
                            
                        }

                    }
                
                }      
            }
            
        }
        //$html_menu.='</ul>';
        //$html_menu.='</div>';
        $html_menu.='</table>';
        
        Return $html_menu;
    }  
    
}

?>

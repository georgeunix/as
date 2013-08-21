<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\DiacBundle\Util\Perfiles;
use Produce\DiacBundle\Util\consultas;

class DiacPerfilesController extends Controller {

    /**
     * @Route("/perfiles", name="perfiles")
     */
    public function PerfilesDiacAction() {
        $session=new SessionManager();
        
        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];


        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);

            $Perfiles = new Perfiles();
            $Listado = $Perfiles->listaPerfiles($DNA);

            $response = array(
                "menutotal" => $menutotal,
                "trabajadores" => $Listado
            );
            $respuesta = array_merge($obj_session, $response);

            return $this->render("DiacBundle:diac:perfiles.html.twig", $respuesta);
        } else {
            return $this->redirect($obj_session["redirect_page"]);
        }
    }

    /**
     * @Route("/opcperfil", name="opcperfil")
     */
    public function listaOpcionesDiacAction(Request $request){
        if ($request->isXmlHttpRequest()) {
            
            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");

            if ($response == true) {
                $codGrupo = $request->request->get("idgrupo");
                
                $Opciones = new Perfiles();
                $menuOpciones = $Opciones->listaOpciones($DNA,$codGrupo);  

                return new Response($menuOpciones);
            } else {
                return new Response("Error");
            }               
        }
    }
    
    
    /**
     * @Route("/guardaopciones", name="guardaopciones")
     */
    public function GuardarOpcionesDiacAction(Request $request){
        if ($request->isXmlHttpRequest()) {
            
            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");

            if ($response == true) {
                $Ex = explode(',',$request->request->get("ArrOpc"));
                $codgrupo = $request->request->get("codgrupo");
                
                $Opciones = new Perfiles();
                
                //Se elimina todas las opciones
                $res = $Opciones->EliminarOpciones($DNA,$codgrupo); 
                
		//Se inserta los nuevos valores
                for($i = 0; $i <= count($Ex) - 1; $i++){
                    $res = $Opciones->GuardarOpciones($DNA,$codgrupo,$Ex[$i]); 
		}
                return new Response($res);
                
            } else {
                return new Response("Error");
            }
        }
    }    
            

    
    /**
     * @Route("/guardaperfil", name="guardaperfil")
     */
    public function GuardarPerfilesDiacAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DB_GENERAL_2 = $this->getDoctrine()->getConnection("DB_GENERAL_2");
            
            if ($response == true) {
                $Perfiles = new Perfiles();

                $codAccion = $request->request->get("accion");
                $codGrupo = $request->request->get("idgrupo");
                $descrip = $request->request->get("descri");

                if ($codAccion == "N") {
                    $res = $Perfiles->GuardarPerfil($DB_GENERAL_2, $codGrupo, $descrip, "I");
                } else {
                    $res = $Perfiles->GuardarPerfil($DB_GENERAL_2, $codGrupo, $descrip, "U");
                }
                    
                return new Response($res);
                //new Response("OK");
            } else {
                return new Response("Error");
            }
        }
    }    
    

}

?>

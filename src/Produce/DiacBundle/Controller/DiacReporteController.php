<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\DiacBundle\Util\Perfiles;
use Produce\DiacBundle\Util\consultas;

class DiacReporteController extends Controller {

    /**
     * @Route("/directorio", name="directorio")
     */
    public function DirectorioDiacAction() {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];


        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
//            $respuesta = array_merge($obj_session, $response);
            return $this->render("DiacBundle:diac:directorio.html.twig", $obj_session);
        } else {
            
        }
    }
    
    
    }

    
    
    
    
    
    
    
    
    
    
    
//    class DiacPerfilesController extends Controller {
//
//        /**
//         * @Route("/perfiles", name="perfiles")
//         */
//        public function PerfilesDiacAction() {
//            $session = new SessionManager();
//
//            $obj_session = $session->valida_session($this);
//            $response = $obj_session["response"];
//
//
//            $DNA = $this->getDoctrine()->getConnection("DNA");
//
//            if ($response == true) {
//                $consultas = new consultas();
//                $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);
//
//                $Perfiles = new Perfiles();
//                $Listado = $Perfiles->listaPerfiles($DNA);
//
//                $response = array(
//                    "menutotal" => $menutotal,
//                    "trabajadores" => $Listado
//                );
//                $respuesta = array_merge($obj_session, $response);
//
//                return $this->render("DiacBundle:diac:perfiles.html.twig", $respuesta);
//            } else {
//                return $this->redirect($obj_session["redirect_page"]);
//            }
//        }
//
//        /**
//         * @Route("/guardaperfilusuario", name="guardaperfilusuario")
//         */
//        public function GuardarPerfilesDiacAction(Request $request) {
//            if ($request->isXmlHttpRequest()) {
//
//                $session = new SessionManager();
//
//                $obj_session = $session->valida_session($this);
//                $response = $obj_session["response"];
//
//                $DNA = $this->getDoctrine()->getConnection("DNA");
//                if ($response == true) {
//                    $Perfiles = new Perfiles();
//
//                    $codAccion = $request->request->get("accion");
//                    $codGrupo = $request->request->get("idgrupo");
//                    $descrip = $request->request->get("descri");
//
//                    //$DB_GENERAL = $this->getDoctrine()->getConnection("DB_GENERAL");
//                    //$codTraba = $Usuarios->codTrabajador($DB_GENERAL, $session["uname"]);
//                    //Validar Si existe el grupo - Inserta o Actualiza
//                    //$codGrupoHay = $Perfiles->ValidaCodGrupo($DNA, $codtrab);
//
//                    if ($codAccion == "I") {
//                        $Perfiles->GuardarPerfil($DNA, $codGrupo, $descrip, "I");
//                    } else {
//                        $Perfiles->GuardarPerfil($DNA, $codGrupo, $descrip, "U");
//                    }
//
//                    return new Response("OK");
//                } else {
//                    return new Response("");
//                }
//            }
//        }

    

?>

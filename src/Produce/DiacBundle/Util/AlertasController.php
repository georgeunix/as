<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\DiacBundle\Util\Perfiles;
use Produce\DiacBundle\Util\consultas;
use Produce\DiacBundle\Util\Alertas;

/**
 * Alert of DiacAlertasController
 *
 * @author Alex  Santiago
 */
class AlertasController extends Controller {

    /**
     * @Route("/alerta01", name="alerta01")
     */
    public function alerta01Action() {       
         $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);
            
            $alerta = new Alertas();
            $Listado = $alerta->alerta01($DNA);
            
            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal, "listado" => $Listado));
            
            return $this->render("DiacBundle:diac:alerta01.html.twig", $respuesta);
        } else {
            
        }
    }

    /**
     * @Route("/alerta02", name="alerta02")
     */
    public function alerta02Action() {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);
            
            $alerta = new Alertas();
            $Listado = $alerta->alerta02($DNA);
            
            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal, "listado" => $Listado));           
            
            return $this->render("DiacBundle:diac:alerta02.html.twig", $respuesta);
        } else {
            
        }
    }
    
    /**
     * @Route("/alerta02D/{id}", name="alerta02D")
     */
     public function alerta02DAction($id) {
         
         $DNA = $this->getDoctrine()->getConnection("DNA");
         $alerta = new Alertas();
         $Listado = $alerta->alerta02D($DNA,$id);
         
         $respuesta = array_merge( array("listado" => $Listado));     
         
         return $this->render("DiacBundle:diac:alerta02D.html.twig",$respuesta);
     }

    /**
     * @Route("/alerta03", name="alerta03")
     */
    public function alerta03Action() {
       $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);
            
            $alerta = new Alertas();
            $Listado = $alerta->alerta03($DNA);
            
            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal,"listado" =>$Listado));
            
            return $this->render("DiacBundle:Alertas:alerta03.html.twig", $respuesta);
        } else {
            
        }
    }
    
    /**
     * @Route("/alerta03D/{may}/{men}", name="alerta03D")
     */
     public function alerta03DAction($may,$men) {
         
         $DNA = $this->getDoctrine()->getConnection("DNA");
         $alerta = new Alertas();
        $Listado = $alerta->alerta03D($DNA,$may,$men);
         
        $respuesta = array_merge( array("listado" => $Listado));     
         
         return $this->render("DiacBundle:Alertas:alerta03D.html.twig",$respuesta);
     }

    /**
     * @Route("/alerta04", name="alerta04")
     */
    public function alerta04Action() {

        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {

            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);

            $alerta = new Alertas();
            $Listado = $alerta->alerta04($DNA);

            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal, "listado" => $Listado));

            return $this->render("DiacBundle:Alertas:alerta04.html.twig", $respuesta);
        } else {
            
        }
    }

    /**
     * @Route("/alerta05", name="alerta05")
     */
    public function alerta05Action() {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);
            
             $alerta = new Alertas();
            $Listado = $alerta->alerta05($DNA);
            
            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal, "listado" => $Listado));
            
            return $this->render("DiacBundle:diac:alerta05.html.twig", $respuesta);
        } else {
            
        }
    }

}

?>

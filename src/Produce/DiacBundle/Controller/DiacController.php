<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\DiacBundle\Util\consultas;

//use Symfony\Component\HttpFoundation\Response; -- Para Ajax

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DiacController
 *
 * @author Jesús Vásquez
 */
class DiacController extends Controller {
    //put your code here

    /**
     * @Route("/index", name="general")
     */
    public function IndexDiacAction() {
        $session=new SessionManager();
        
        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);

            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal));
            return $this->render("DiacBundle:diac:inicio.html.twig", $respuesta);
        } else {
            return $this->redirect($obj_session["redirect_page"]);
        }
    }

    /**
     * @Route("/prueba", name="_prueba")
     */
    public function pruebaAction() {
       return new \Symfony\Component\HttpFoundation\Response("prueba");
    }

}

?>

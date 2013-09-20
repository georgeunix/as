<?php

namespace Produce\ServiciosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;

class intranetController extends Controller {

    /**
     * @Route("/intranet", name="_intranet")
     */
    public function intranetAction(){
        $session = new SessionManager();
        $session_obj = $session->valida_session($this); //inicializo SessionManager
        $response = $session_obj["response"]; //seteo $respose puede tener 2 respuestas true o false
        if ($response == true) {
            //si es que existe la session redirijo a la plantilla
            return $this->render("ServiciosBundle:vistas:index_intranet.html.twig", $session_obj);
        } else {
            //sino lo redirijo fuera del portal
            return $this->redirect($session_obj["redirect_page"]);
        }
    }

    /**
     * @Route("/close_session", name="_close_session")
     */
    public function cerrar_sesion() {
        //cierro session y redirijo
        $session = new SessionManager();
        return $this->redirect($session->cerrar_session());
    }

}

?>

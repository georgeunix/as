<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\DiacBundle\Util\Perfiles;
use Produce\DiacBundle\Util\consultas;

use Produce\DiacBundle\Util\cites;
use Produce\DiacBundle\Util\tablascites;
use Produce\DiacBundle\Util\ServerSide;


class DiacFormulario2Controller extends Controller{                
        
    /**
    * @Route("/buscar2", name="buscar2")
    */
    public function BuscarAVAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");
            if ($response == true) {
                $cites = new cites();                
                
                $spk = $request->request->get("id2");                
                                                               
                $x = $cites->ActaVerif_Guardar($DNA,$spk);                                    
                
                //return new Response("Registro grabado correctamente"); 
                return new Response ($x);
                
            } else {
                return new Response("");
            }
        }
    }      
        
    /**
    * @Route("/guardar2", name="guardar2")
    */
    public function GuardarActaRepDiacAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");
            if ($response == true) {
                $cites = new cites();                
                
                $sNumActaRep = $request->request->get("id2");                
                $sNumActa = $request->request->get("num2");                
                $sNumAlevinos = $request->request->get("cant2");
                $sLongAlevinos = $request->request->get("long2");
                $sFecha = $request->request->get("fec2");
                $sObservaciones = $request->request->get("obs2");
                                                
                $x = $cites->ActaVerif_Guardar($DNA,$sNumActaRep,$sNumActa,$sFecha,$sNumAlevinos,$sLongAlevinos,$sObservaciones);                                    
                
                //return new Response("Registro grabado correctamente"); 
                return new Response ($x);
                
            } else {
                return new Response("");
            }
        }
    }    
    
    
    
    
    
    
    
}
?>
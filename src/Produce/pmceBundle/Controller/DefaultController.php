<?php

namespace Produce\pmceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
//use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\pmceBundle\Util\consultas;

class DefaultController extends Controller{
    
    /**
     * @Route("/pmce_asindef", name="_pmce_asindef")
     */
    public function asociacion_Action(){
        return $this->render('ProducepmceBundle:plantillas:interfaces_1.html.twig', array('uname'=>"prueba"));
    }
    /**                                                                         
     * @Route("/pmce_asinasoc" , name="_pmce_asocdef")                          
     */                                                                         
    public function sustitucion_Action(){
        return $this->render('ProducepmceBundle:plantillas:interfaces_3.html.twig',array('uname'=>"prueba"));
    }
                                                                                
    /**
     * @route("/pmce_inserta", name="_insert_wem")
     */
    public function inserta_Asociacion_Action(Request $request){
        if ($request->isXmlHttpRequest()){
            $respuesta = consultas::InsertarSolicitud($this,$request);
            return new Response($respuesta);
        }
    }
    
    /**
     * @route("/pmce_inserta3", name="_insert_3")
     */
    public function inserta_Sustitucion_Action(Request $request){
        if ($request->isXmlHttpRequest()){
            $respuesta = consultas::InsertarSolicitud($this,$request);
            return new Response($respuesta);
        }
    }

    /**
     * @route("/home", name="_home")
     */
    public function homeAction(){
        return $this->render('ProducepmceBundle:plantillas:index.html.twig', array('uname' => "prueba"));
    }

    /**
     * @Pdf()
     * @Route("/reporte_pmce",name="_reporte_pmce")
     */
    
    public function reportePDFAction(){
        $facade = $this->get('ps_pdf.facade');
        $response = new Response();
        $DB_PMCE = $this->getDoctrine()->getConnection("PMCE");
        $listaProyecto = consultas::listareporte($DB_PMCE);
        $listaaportante = consultas::listar_idembarcacion($DB_PMCE);
        $this->render('ProducepmceBundle:plantillas:Template_pmce.pdf.twig',  array("info" => $listaProyecto  , "hola"=>$listaaportante) , $response);
        $xml = $response->getContent();
        $content = $facade->render($xml);

        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }
    
    /**
     * @Pdf()
     * @Route("/reporte_pmce3",name="_reporte_pmce3")
     */
    public function reporte3PDFAction(){
        $facade = $this->get('ps_pdf.facade');
        $response = new Response();
        $DB_PMCE = $this->getDoctrine()->getConnection("PMCE");
        $listaProyecto = consultas::listareporte($DB_PMCE);
        $listaaportante = consultas::listar_idembarcacion($DB_PMCE);
        $this->render('ProducepmceBundle:plantillas:Template_pmce_3.pdf.twig',  array("info" => $listaProyecto  , "hola"=>$listaaportante) , $response);
        
        $xml = $response->getContent();
        $content = $facade->render($xml);
        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }
    

    /**
     * @Route("/buscando", name="_all_embarcaciones")
     */
    public function Embarcaciones_all_Action(Request $request){
        if ($request->isXmlHttpRequest()){
            //$session = new SessionManager();
            //$obj_session = $session->valida_session($this);
            //$response = $obj_session["response"];
            //if ($response == true) {
               $listaEmbarcacion = consultas::lista_all_embarcacion($this,$request);
                $rs = new Response();
                $rs->setContent(json_encode($listaEmbarcacion));
                return $rs;
        }
    }
    
     /**
     * @route("/probando", name="_probala")
     */
    public function pruebasAction(){
        return $this->render('ProducepmceBundle:plantillas:prueba.html.twig', array('uname' => "prueba"));
    }
     
  
    /**
     * @Route("/listaembarcaciones", name="_datos_embarcaciones")
     */
    public function listaEmbarcacionesAction(Request $request){
        if ($request->isXmlHttpRequest()){
            //$session = new SessionManager();
            //$obj_session = $session->valida_session($this);
            //$response = $obj_session["response"];
            //if ($response == true) {
               $listaEmbarcacion = consultas::listaembarcacion($this,$request);
                $rs = new Response();
                $rs->setContent(json_encode($listaEmbarcacion));
                return $rs;
        }
    }
    
}
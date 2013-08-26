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
     * @Route("/pmce", name="_index_pmce")
     */
    
     public function mapaAction(){
         
        $errors = array(
                  array("Title" =>"rose","Price" =>1.25,"Number"=>15),
                  array("Title" => "daisy","Price" => 0.75,"Number" => 25),
                  array("Title" => "orchid","Price" => 1.15,"Number" => 7)
                 );
        
      
            
        return $this->render('ProducepmceBundle:plantillas:interfaces_1.html.twig', array( "empresa_tripu" => $errors ,'uname'=>"ratin ") );
    }
    
    
     /**
     * @route("/pmce_inserta", name="_insert_wem")
     */
    
    public function llena_Solicitud_Action(Request $request){
          if ($request->isXmlHttpRequest()){
              $respuesta = consultas::InsertarSolicitud($this,$request);
              // return new Response($respuesta);

              return new Response($respuesta);
          }
    }
                                                                                
    /**
     * @Route("/pmce_insert", name="_insert_solicitud")
     */
    public function mapingAction(Request $request){
        $CONPMCE = $this->getDoctrine()->getConnection("PMCE");
        consultas::InsertarSolicitud($CONPMCE,$request);
        return $this->redirect('http://www.google.com');
        //return $this->redirect("http://localhost/framework/web/app.php/reporte_pmce");
    }
    
    
     /**
     * @Pdf()
     * @Route("/reporte_pmce",name="_reporte_pmce")
     */
    public function reportePDFAction(){
        
        $facade = $this->get('ps_pdf.facade');

        $response = new Response();
        
        $errors = array(
                  array("Title" =>"rose","Price" =>1.25,"Number"=>15),
                  array("Title" => "daisy","Price" => 0.75,"Number" => 25),
                  array("Title" => "orchid","Price" => 1.15,"Number" => 7)
                );
         
        $this->render('ProducepmceBundle:plantillas:Template_pmce.pdf.twig', $errors , $response);
        $xml = $response->getContent();
        $content = $facade->render($xml);

        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }

    
    

}









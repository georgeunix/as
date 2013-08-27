<?php

namespace Produce\pmceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;
//use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\pmceBundle\Util\consultas;

class DefaultController extends Controller {

    /**
     * @Route("/pmce_asindef", name="_pmce_asindef")
     */
    public function mapaAction(){
        $errors = array(
            array("Title" => "rose", "Price" => 1.25, "Number" => 15),
            array("Title" => "daisy", "Price" => 0.75, "Number" => 25),
            array("Title" => "orchid", "Price" => 1.15, "Number" => 7)
        );
        return $this->render('ProducepmceBundle:plantillas:interfaces_1.html.twig', array("empresa_tripu" => $errors, 'uname' => "ratin "));
    }

    /**
     * @route("/pmce_inserta", name="_insert_wem")
     */
    public function llena_Solicitud_Action(Request $request){
        if ($request->isXmlHttpRequest()) {
            $respuesta = consultas::InsertarSolicitud($this, $request);
            return new Response($respuesta);
        }
    }

    /**
     * @route("/home", name="_home")
     */
    public function homeAction() {
        return $this->render('ProducepmceBundle:plantillas:index.html.twig', array('uname' => "prueba"));
    }

    /**
     * @Route("/pmce", name="_index_pmce")
     */
    public function IndexAction(){
        return $this->render('ProducepmceBundle:plantillas:interfaces_1.html.twig', array('uname' => "ratin "));
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
        $botones = "";                                                                  
        $new_listaProyecto = array();                              
        foreach ($listaProyecto as $value){

//            $botones = "<span class='btn edit-doc' title='Editar Documento' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus cus-application-form-edit'></i></span>";
//            $botones .= "<span class='btn generar_word' title='Generar Word' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus cus-page-word'></i></span>";
//            $botones .= "<span class='btn subir_documento' title='Cargar documento' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus  cus-page-save'></i></span>";

            $row = array("EMBARCACION" => $value["EMBARCACION"],
                "MATRICULA" => $value["MATRICULA"],
                "REGIMEN" => $value["REGIMEN"],
                "CAP_BOD_M3" => $value["CAP_BOD_M3"],
                "INDICATIVO_DEL_DOCUMENTO" => $value["ESTADO_PERMISO"],
                "pmce_calculado" => $value["pmce_calculado"],
                "lmce_calculado" => $value["lmce_calculado"],
                "porcentaje_aportacion" => $value["porcentaje_aportacion"] );

            array_push($new_listaProyecto, $row);
        }
        $rs = new Response();
       // $rs->setContent(json_encode($new_listaProyecto));
       // return $rs;
          
        $errors = array(
            array("Title" => "rose", "Price" => 1.25, "Number" => 15),
            array("Title" => "daisy", "Price" => 0.75, "Number" => 25),
            array("Title" => "orchid", "Price" => 1.15, "Number" => 7)
        );
        $this->render('ProducepmceBundle:plantillas:Template_pmce.pdf.twig',  array("info" => $new_listaProyecto) , $response);
        $xml = $response->getContent();
        $content = $facade->render($xml);

        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }

}


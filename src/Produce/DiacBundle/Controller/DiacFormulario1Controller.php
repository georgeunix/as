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


class DiacFormulario1Controller extends Controller{        
    
    /**
    * @Route("/listaractarep", name="listaractarep")
    */
    public function ListarActaRepAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

                $DNA = $this->getDoctrine()->getConnection("DNA");
                $get = $request->query->all();
                               
                $SSS = new ServerSide();
                $SSS->setTable('vw_listado_actarep');
                $SSS->setIndexColumn('ID_ACTA_INS_REPRO');
                $SSS->setColumns(array('ID_ACTA_INS_REPRO','EDITAR','ELIMINAR','NUM_ACTA','NOM_ACUICULTOR','NUM_RESOLUCION','FECHA','HORA','OBSERVACIONES','CREAR'));                
                $SSS->setColumnsName(array('ID_ACTA_INS_REPRO','EDITAR','ELIMINAR','NUM_ACTA','NOM_ACUICULTOR','NUM_RESOLUCION','FECHA','HORA','OBSERVACIONES','CREAR'));                
                $SSS->setColumnsSearch(array('NOM_ACUICULTOR'));
                $data = $SSS->data($get, $DNA);                
                return new Response(json_encode($data));
        }
    }
    
    /**
    * @Route("/actarep", name="actarep")
    */
    public function ActaRepDiacAction() {
        $session=new SessionManager();
        
        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];


        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);

            $rsactarep = new cites();
            $xactarep = $rsactarep->ActaRep_Listar($DNA);
                      
            $rsacuicultor = new tablascites();
            $xacuicultor = $rsacuicultor->Acuicultor_Listar($DNA);
            
            $response = array(
                "menutotal" => $menutotal,
                "actarp" => $xactarep,
                "acuicultor" => $xacuicultor
            );
            $respuesta = array_merge($obj_session, $response);

            return $this->render("DiacBundle:diac:formulario1.html.twig", $respuesta);            
        } else {
            return $this->redirect($obj_session["redirect_page"]);
        }
    }
    
    /**
    * @Route("/actarep1", name="actarep1")
    */
    /*
    public function ActaRep1DiacAction() {
        $session=new SessionManager();
        
        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];


        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);

            $rsactarep = new cites();
            $xactarep = $rsactarep->ActaRep_Listar($DNA);
            
            $rsacuicultor = new tablascites();
            $xacuicultor = $rsacuicultor->Acuicultor_Listar($DNA);
            
            $response = array(
                "menutotal" => $menutotal,
                "actarp" => $xactarep,
                "acuicultor" => $xacuicultor
            );
            $respuesta = array_merge($obj_session, $response);
            
            return $this->render("DiacBundle:diac:detalleform1.html.twig", $respuesta);
        } else {
            return $this->redirect($obj_session["redirect_page"]);
        }
    }
    */
        
    /**
    * @Route("/guardar", name="guardar")
    */
    public function GuardarActaRepDiacAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");
            if ($response == true) {
                $cites = new cites();                
                
                $nActaRep = $request->request->get("num");
                $Acuicultor = $request->request->get("nom");
                $nResol = $request->request->get("resol");
                $nFecha = $request->request->get("fec");
                $nObs = $request->request->get("obs");
                                
                $x = $cites->ActaRep_Guardar($DNA,$nActaRep,$Acuicultor,$nResol,$nFecha,$nObs);                    
                //return new Response("Registro grabado correctamente"); 
                return new Response ($x);
                
            } else {
                return new Response("");
            }
        }
    }    
    
    /**
    * @Route("/actualizar", name="actualizar")
    */
    public function ActualizarActaRepDiacAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");
            if ($response == true) {
                $cites = new cites();                
                
                //$Accion = $request->request->get("accion");
                $nID = $request->request->get("id");
                $nActaRep = $request->request->get("num");
                $Acuicultor = $request->request->get("nom");
                $nResol = $request->request->get("resol");
                $nFecha = $request->request->get("fec");
                $nObs = $request->request->get("obs");
                
                $x = $cites->ActaRep_Actualizar($DNA,$nID,$nActaRep,$Acuicultor,$nResol,$nFecha,$nObs);
                return new Response($x);     
                //return new Response("Registro actualizado correctamente");     
                
            } else {
                return new Response("");
            }
        }
    }    
    
    /**
    * @Route("/eliminar", name="eliminar")
    */
    public function EliminarActaRepDiacAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");
            if ($response == true) {
                $cites = new cites();                
                                
                $nID = $request->request->get("id");
                
                $x = $cites->ActaRep_Eliminar($DNA,$nID);                    
                
                //return new Response($nID);
                return new Response($x);
                //return new Response("Registro eliminado correctamente");
                                
            } else {
                return new Response("");
            }
        }
    } 
    
    
    
    
    
}
?>
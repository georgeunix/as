<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\DiacBundle\Util\Perfiles;
use Produce\DiacBundle\Util\consultas;
use Produce\DiacBundle\Util\Autorizacion;
use Produce\DiacBundle\Util\Mantenimiento;
use Produce\DiacBundle\Util\General;
use Produce\DiacBundle\Util\ServerSide;

/**
 * Alert of DiacAutorizacionController
 *
 * @author Alex  Santiago
 */
class AutorizacionController extends Controller {

    /**
     * @Route("/autorizacion", name="autorizacion")
     */
    public function autorizacionAction() {

        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {

            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);

            /* Generando el Combo de Familia-Especie */
            $_especies = new Mantenimiento();
            $Familia = $_especies->listFamilia($DNA);

            $contenido = '<select name="cbofamilia" id="cbofamilia">';
            $contenido.= '<option value="0">--Seleccione--</option>';
            foreach ($Familia as $value) {
                $contenido.= '<option value="' . $value["ID"] . '">' . $value["DESCRIPCION"] . '</option>';
            }
            $contenido.= '</select>';
            /* End Generated */
            /* Generando el Combo de Departamento */
            $_departamentos = new General();
            $Departamentos = $_departamentos->devolverDepartamentos($DNA);

            $deps = '<select name="cboDepartamento" id="cboDepartamento">';
            $deps.= '<option value="00">--Seleccione--</option>';
            foreach ($Departamentos as $value) {
                $deps.= '<option value="' . $value["CODIGO_DEPARTAMENTO"] . '">' . $value["DEPARTAMENTO"] . '</option>';
            }
            $deps.= '</select>';
            /* End Generated */

            $respuesta = array_merge($obj_session, array("menutotal" => $menutotal, "familia" => $contenido, 'departamento' => $deps));

            return $this->render("DiacBundle:Autorizacion:autorizacion.html.twig", $respuesta);
        } else {
            
        }
    }

    /**
     * @Route("/dicapiDialog", name="dicapiDialog")
     */
    public function dicapiDialogAction() {
        return $this->render("DiacBundle:Autorizacion:dicapiDialog.html.twig");
    }

    /**
     * @Route("/empresaDialog", name="empresaDialog")
     */
    public function empresaDialogAction() {
        return $this->render("DiacBundle:Autorizacion:empresaDialog.html.twig");
    }

    /**
     * @Route("/anexoDialog", name="anexoDialog")
     */
    public function anexoDialogAction() {
        return $this->render("DiacBundle:Autorizacion:anexoDialog.html.twig");
    }

    /**
     * @Route("/resolucionDicapi", name="resolucionDicapi")
     */
    public function resolucionDicapiAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $dep = $request->request->get("cod_dep");
            /* Generando el Combo de Resolucion */
            $_resolucion = new Autorizacion();
            $resoluciones = $_resolucion->devolverResoluciones($DNA, $dep);

            $contenido = '';
            $contenido.= '<option value="00">--Seleccione--</option>';
            foreach ($resoluciones as $value) {
                $contenido.= '<option value="">' . $value["RESOLUCION"] . '</option>';
            }
            $contenido.= '';
            /* End Generated */
            return new Response($contenido);
        }
    }

    /**
     * @Route("/mapaAcuicolaDialog", name="mapaAcuicolaDialog")
     */
    public function mapaAcuicolaDialogAction() {
        return $this->render("DiacBundle:Autorizacion:mapaAcuicolaDialog.html.twig");
    }

    /**
     * @Route("/mapaAcuicola", name="mapaAcuicola")
     */
    public function mapaAcuicolaAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $dep = $request->request->get("cod_dep");
            /* Generando el Combo de Resolucion */
            $_resolucion = new Autorizacion();
            $resoluciones = $_resolucion->devolverMapasAcuicolas($DNA, $dep);

            $contenido = '';
            $contenido.= '<option value="00">--Seleccione--</option>';
            foreach ($resoluciones as $value) {
                $contenido.= '<option value="">' . $value["MAPA"] . '</option>';
            }
            $contenido.= '';
            /* End Generated */
            return new Response($contenido);
        }
    }

    /**
     * @Route("/caducarDerechoVigente", name="caducarDerechoVigente")
     */
    public function caducarDerechoVigenteAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $cod_sucursal = $request->request->get("cod_sucursal");

            $_derechoEstado = new Autorizacion();
            $result = $_derechoEstado->caducarDerechoVigente($DNA, $cod_sucursal);

            return new Response($result);
        }
    }

    /**
     * @Route("/buscarempresadiac", name="buscarempresadiac")
     */
    public function buscarempresadiacAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            if ($request->isXmlHttpRequest()) {
                $DNA = $this->getDoctrine()->getConnection("DNA");
                $get = $request->query->all();

                $SSS = new ServerSide();
                $SSS->setTable('vw_listado_persona_diac');
                $SSS->setIndexColumn('id');
                $SSS->setColumns(array('id', 'persona', "'<a href='+CHAR(34)+'javascript:;'+CHAR(34)+' onclick='+CHAR(34)+'agregar_empresa('+cast(id as varchar(50))+','+CHAR(39)+persona+CHAR(39)+')'+CHAR(34)+'><span class='+CHAR(34)+'btn'+CHAR(34)+'><i class='+CHAR(34)+'cus cus-add'+CHAR(34)+'></i></span></a>' as PA"));
                $SSS->setColumnsName(array('id', 'persona', 'PA'));
                $SSS->setColumnsSearch(array('persona'));

                $data = $SSS->data($get, $DNA);
                
                return new Response(json_encode($data));
            }
        }
    }
     /**
     * @Route("/listadoAnexoResolucion", name="listadoAnexoResolucion")
     */
    public function listadoAnexoResolucionAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

           $DNA = $this->getDoctrine()->getConnection("DNA");
                $get = $request->query->all();

                $SSS = new ServerSide();
                $SSS->setTable('vw_listado_anexos_diac');
                $SSS->setIndexColumn('ID');
                $SSS->setColumns(array('ID', 'NUMERO_RESOLUCION', 'FECHA'));
                $SSS->setColumnsName(array('ID', 'NUMERO_RESOLUCION', 'FECHA'));
                $SSS->setColumnsSearch(array('NUMERO_RESOLUCION'));

                $data = $SSS->data($get, $DNA);
                
                return new Response(json_encode($data));
        }
    }

}

?>

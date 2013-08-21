<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Produce\DiacBundle\Util\General;
use Produce\DiacBundle\Util\ServerSide;

class GeneralController extends Controller {

    /**
     * @Route("/devolverDepartamentos", name="devolverDepartamentos")
     */
    public function devolverDepartamentos(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");

            /* Generando el Combo de Departamento */
            $_provincias = new General();
            $Provincias = $_provincias->devolverDepartamentos($DNA);

            $contenido = '';
            $contenido.= '<option value="00">--Seleccione--</option>';
            foreach ($Provincias as $value) {
                $contenido.= '<option value="' . $value["CODIGO_DEPARTAMENTO"] . '">' . $value["DEPARTAMENTO"] . '</option>';
            }
            $contenido.= '';
            /* End Generated */
            return new Response($contenido);
        }
    }

    /**
     * @Route("/devolverProvincias", name="devolverProvincias")
     */
    public function devolverProvincias(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $dep = $request->request->get("cod_dep");
            $prov = $request->request->get("cod_prov");

            /* Generando el Combo de Departamento */
            $_provincias = new General();
            $Provincias = $_provincias->devolverProvincias($DNA, $dep);

            $contenido = '';
            $contenido.= '<option value="00">--Seleccione--</option>';
            foreach ($Provincias as $value) {
                $select = ($value["CODIGO_PROVINCIA"] == $prov) ? 'selected' : '';
                $contenido.= '<option value="' . $value["CODIGO_PROVINCIA"] . '" ' . $select . '>' . $value["PROVINCIA"] . '</option>';
            }
            $contenido.= '';
            /* End Generated */
            return new Response($contenido);
        }
    }

    /**
     * @Route("/devolverDistritos", name="devolverDistritos")
     */
    public function devolverDistritos(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");

            $dep = $request->request->get("cod_dep");
            $prov = $request->request->get("cod_prov");
            $dis = $request->request->get("cod_dis");

            /* Generando el Combo de Departamento */
            $_distritos = new General();
            $Distritos = $_distritos->devolverDistritos($DNA, $dep, $prov);

            $contenido = '';
            $contenido.= '<option value="00">--Seleccione--</option>';
            foreach ($Distritos as $value) {
                $select = ($value["CODIGO_DISTRITO"] == $dis) ? 'selected' : '';
                $contenido.= '<option value="' . $value["CODIGO_DISTRITO"] . '" ' . $select . '>' . $value["DISTRITO"] . '</option>';
            }
            $contenido.= '';
            /* End Generated */
            return new Response($contenido);
        }
    }

    /**
     * @Route("/devolverTipoDerecho", name="devolverTipoDerecho")
     */
    public function devolverTipoDerecho(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $_tipoderecho = new General();
            $tipoderecho = $_tipoderecho->devolverTipoDerecho($DNA);

            $combo = '';
            $combo.= '<option value="0">--Seleccione--</option>';
            foreach ($tipoderecho as $value) {
                $combo.= '<option value="' . $value["ID"] . '">' . $value["DERECHO"] . '</option>';
            }
            $combo.= '';

            return new Response($combo);
        }
    }

    /**
     * @Route("/devolverTipoResolucion", name="devolverTipoResolucion")
     */
    public function devolverTipoResolucion(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $_tiporesolucion = new General();
            $tiporesolucion = $_tiporesolucion->devolverTipoResolucion($DNA);

            $combo = '';
            $combo.= '<option value="0">--Seleccione--</option>';
            foreach ($tiporesolucion as $value) {
                $combo.= '<option value="' . $value["ID"] . '">' . $value["RESOLUCION"] . '</option>';
            }
            $combo.= '';

            return new Response($combo);
        }
    }

    /**
     * @Route("/devolverTipoDesarrollo", name="devolverTipoDesarrollo")
     */
    public function devolverTipoDesarrollo(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $_tipodesarrollo = new General();
            $tipodesarrollo = $_tipodesarrollo->devolverTipoDesarrollo($DNA);

            $combo = '';
            $combo.= '<option value="0">--Seleccione--</option>';
            foreach ($tipodesarrollo as $value) {
                $combo.= '<option value="' . $value["ID"] . '">' . $value["DESARROLLO"] . '</option>';
            }
            $combo.= '';

            return new Response($combo);
        }
    }

    /**
     * @Route("/devolverTipoEstado", name="devolverTipoEstado")
     */
    public function devolverTipoEstado(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $_tipoestado = new General();
            $tipoestado = $_tipoestado->devolverTipoEstado($DNA);

            $combo = '';
            $combo.= '<option value="0">--Seleccione--</option>';
            foreach ($tipoestado as $value) {
                $combo.= '<option value="' . $value["ID"] . '">' . $value["ESTADO"] . '</option>';
            }
            $combo.= '';

            return new Response($combo);
        }
    }

    /**
     * @Route("/devolverTipoRecurso", name="devolverTipoRecurso")
     */
    public function devolverTipoRecurso(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $_tiporecurso = new General();
            $tiporecurso = $_tiporecurso->devolverTipoRecurso($DNA);

            $combo = '';
            $combo.= '<option value="0">--Seleccione--</option>';
            foreach ($tiporecurso as $value) {
                $combo.= '<option value="' . $value["ID"] . '">' . $value["RECURSO"] . '</option>';
            }
            $combo.= '';

            return new Response($combo);
        }
    }

    /**
     * @Route("/devolverRecursosxTipo", name="devolverRecursosxTipo")
     */
    public function devolverRecursosxTipo(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $cod_tipo_recurso = $request->request->get("cod_tipo_recurso");

            $_recursos = new General();
            $recursos = $_recursos->devolverRecursosxTipo($DNA, $cod_tipo_recurso);
            $combo = '';
            $combo.= '<option value="0">--Seleccione--</option>';
            foreach ($recursos as $value) {
                $combo.= '<option value="' . $value["ID"] . '">' . $value["RECURSO"] . '</option>';
            }
            $combo.= '';

            return new Response($combo);
        }
    }

    /**
     * @Route("/devolverlistadotableprueba", name="devolverlistadotableprueba")
     */
    public function devolverlistadotableprueba(Request $request) {

        if ($request->isXmlHttpRequest()) {
            $DNA = $this->getDoctrine()->getConnection("DNA");
            $get = $request->query->all();
            
             $SSS = new ServerSide();
             $SSS->setTable('vw_listado_persona_diac');
             $SSS->setIndexColumn('id');
             $SSS->setColumns(array('id','persona'));
             $SSS->setColumnsName(array('id','persona'));
             $SSS->setColumnsSearch(array('persona'));
             
             $data=$SSS->data($get,$DNA);
             
             return new Response(json_encode($data));
            
        }
    }

}

?>

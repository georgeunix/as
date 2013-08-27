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

use Symfony\Component\HttpFoundation\StreamedResponse; //abrir pdf
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Alert of DiacAutorizacionController
 *
 * @author Alex  Santiago
 */
class AutorizacionController extends Controller {

     protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return '/uploads/documents/diac/modificatorias/';
    }

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
            $deps.= '<option value="0">--Seleccione--</option>';
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
     * @Route("/empresaDialog/", name="empresaDialog")
     */
    public function empresaDialogAction() {

        return $this->render("DiacBundle:Autorizacion:empresaDialog.html.twig");
    }

    /**
     * @Route("/anexoDialog/", name="anexoDialog")
     */
    public function anexoDialogAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $DNA = $this->getDoctrine()->getConnection("DNA");
            $codsucursal = $request->request->get("codsucursal");
            $_res_anexo = new Autorizacion();
            $result = $_res_anexo->devolverResolucionSucursal($DNA, $codsucursal);

            return $this->render("DiacBundle:Autorizacion:anexoDialog.html.twig", array("codsucursal" => $codsucursal, "resolucion" => $result));
        }
    }

    /**
     * @Route("/devolverAnexo", name="devolverAnexo")
     */
    public function devolverAnexoAction(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $DNA = $this->getDoctrine()->getConnection("DNA");
            $codigo_anexo = $request->request->get("codigo_anexo");

            $_anexo = new Autorizacion();

            $anexo = $_anexo->devolverAnexo($DNA, $codigo_anexo);

            return new Response(json_encode($anexo[0]));
        }
    }

    /**
     * @Route("/guardarAnexo", name="guardarAnexo")
     */
    public function guardarAnexoAction(Request $request) {

        if ($request->isXmlHttpRequest()) {
            
            $DNA = $this->getDoctrine()->getConnection("DNA");
            $post = $request->request->all();

            $ruta = $this->getUploadRootDir();
            $result='1';
            $nombre='';
            if(count($_FILES)){
                foreach ($_FILES as $key) {

                    $nombre = str_replace(" ","_",$request->request->get('res').'_'.$key['name']); 
                    $temporal = $key['tmp_name']; 
                    if (move_uploaded_file($temporal, $ruta . $nombre)) {
                        $result='1';
                    }else{
                        $result='0';
                    }
                }
            }
            if($result=='1'){
                $_anexo = new Autorizacion();
                $result = $_anexo->guardarAnexo($DNA, $post,$nombre);
            }
            return new Response($result);
        }
    }

    /**
     * @Route("/eliminarAnexo", name="eliminarAnexo")
     */
    public function eliminarAnexoAction(Request $request) {

        if ($request->isXmlHttpRequest()) {
            $DNA = $this->getDoctrine()->getConnection("DNA");
            $cod_anexo = $request->request->get('cod_anexo');

            $_anexo = new Autorizacion();
            $result = $_anexo->eliminarAnexo($DNA, $cod_anexo);

            return new Response($result);
        }
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
            
            $codigo_sucursal = $request->query->get('codigo_sucursal');
            if ($codigo_sucursal != '') {
                $sWhere = " CODIGO_SUCURSAL=$codigo_sucursal";
            }
            $SSS = new ServerSide();
            $SSS->setTable('vw_listado_anexos_diac');
            $SSS->setIndexColumn('ID');
            $SSS->setWhere($sWhere);
            $SSS->setColumns(array('ID', 'NUMERO_RESOLUCION', 'FECHA', "'<a href='+CHAR(34)+'javascript:;'+CHAR(34)+' onclick='+CHAR(34)+'editar_anexo('+CAST(ID AS VARCHAR(50))+')'+CHAR(34)+'><span class='+CHAR(34)+'btn'+CHAR(34)+'><i class='+CHAR(34)+'cus cus-page-white-edit'+CHAR(34)+'></i></span></a><a href='+CHAR(34)+'javascript:;'+CHAR(34)+' onclick='+CHAR(34)+'eliminar_anexo('+CAST(ID AS VARCHAR(50))+')'+CHAR(34)+'><span class='+CHAR(34)+'btn'+CHAR(34)+'><i class='+CHAR(34)+'cus cus-cancel'+CHAR(34)+'></i></span></a>' AS PA"));
            $SSS->setColumnsName(array('ID', 'NUMERO_RESOLUCION', 'FECHA', 'PA'));
            $SSS->setColumnsSearch(array('NUMERO_RESOLUCION'));

            $data = $SSS->data($get, $DNA);

            return new Response(json_encode($data));
        }
    }

    /**
     * @Route("/validarUploadFile", name="validarUploadFile")
     */
    public function validarUploadFileAction(Request $request) {

        if ($request->isXmlHttpRequest()) {
            
           // $nombre = $request->request->get("nombre");
            $msg = '';
            foreach ($_FILES as $key) {

                if ($key["type"] != 'application/pdf') {
                    $msg = '2'; //1:Formato No valido
                } else if (($key['size'] / 1000) > 2048) {
                    $msg = '3'; //3:Máximo 2 MB
                }else{
                    $msg=$key["name"];//$nombre;
                    //$msg=$nombre;
                }
                return new Response($msg);
            }
        }
    }
    /**
     * @Route("/downloadFile/{file}", name="downloadFile")
     */
    public function downloadFileAction($file)
    //public function downloadFileAction(Request $request)
    
    {
            $filename=$file;//$request->query->get("filename");
            $path=$this->getUploadRootDir();
            
            $content = file_get_contents($path.$filename);

            $response = new Response();

            //set headers
            $response->headers->set('Content-Type', 'mime/type');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

            $response->setContent($content);
            
            $response->send();
//        return new Response("rgfhgf");
            
            
    }

}

?>

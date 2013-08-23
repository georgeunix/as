<?php

namespace Produce\SitradocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\SitradocBundle\Util\consultas;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile; //upload de archivos pdf
use Symfony\Component\HttpFoundation\StreamedResponse; //abrir pdf
use Symfony\Component\HttpFoundation\ResponseHeaderBag; //abrir pdf

class DocumentosController extends Controller {

    /**
     * @Route("/documentos", name="_documentos_sitradoc")
     */
    public function documentosSitradocAction() {

        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        if ($response == true) {

            $DB_TRAMITE_DOCUMENTARIO = $this->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
            $comboTipoDocumento = consultas::comboTipoDocumento($DB_TRAMITE_DOCUMENTARIO);


            $data = array(
                "comboTipoDocumento" => $comboTipoDocumento
            );

            $results = array_merge($obj_session, $data);

            return $this->render("SitradocBundle:Interfaz_documentos:main_documentos_sitradoc.html.twig", $results);
        } else {
            return $this->redirect($obj_session["redirect_page"]);
        }
    }

    /**
     * @Route("/cboFirmantes", name="_cboFirmantes")
     */
    public function cboFirmantesAction(Request $request) {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        if ($response == true) {

            $filtro = $request->request->get("filtro");
            $DB_GENERAL = $this->getDoctrine()->getConnection("DB_GENERAL");
            $comboTrabajador = consultas::comboTrabajador($DB_GENERAL, $filtro);
            return new Response($comboTrabajador);
        } else {
            return new Response("vuelva a iniciar sesion.");
        }
    }

    /**
     * @Route("/mostrarDocumento", name="_mostrarDocumento")
     */
    public function mostrarDocumentoAction(Request $request) {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        if ($response == true) {
            $con = new consultas();
            $data = $con->muestraDocumento($this, $request);
            return new Response($data);
        } else {
            return new Response("vuelva a iniciar sesion.");
        }
    }

    /**
     * @Route("/editarDocumento", name="_editarDocumento")
     */
    public function editarDocumentoAction(Request $request) {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        if ($response == true) {
            $con = new consultas();
            $data = $con->editardocumento($this, $request, $obj_session["uname"]);
            return new Response($data);
        } else {
            return new Response("vuelva a iniciar sesion.");
        }
    }

    /**
     * @Route("/cboDestinatarios", name="_cboDestinatarios")
     */
    public function cboDestinatariosAction(Request $request) {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];

        if ($response == true) {
            $filtro = $request->request->get("filtro");
            $DB_GENERAL = $this->getDoctrine()->getConnection("DB_GENERAL");
            $comboDestinatarios = consultas::comboDestinatarios($DB_GENERAL, $filtro);
            return new Response($comboDestinatarios);
        } else {
            return new Response("vuelva a iniciar sesion.");
        }
    }

    /**
     * @Route("/newProyectoDocumento", name="_newProyectoDocumento")
     */
    public function newProyectoDocumentoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            if ($response == true) {
                $respuesta = consultas::nuevoDocumentoProyecto($this, $request, $obj_session["uname"]);
                return new Response($respuesta);
            } else {
                return new Response("vuelva a iniciar sesion.");
            }
        }
    }

    /**
     * @Route("/listacicloProyecto", name="_listacicloProyecto")
     */
    public function listacicloProyectoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $session = new SessionManager();
            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];
            if ($response == true) {

                $DB_TRAMITE_DOCUMENTARIO = $this->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
                $listaProyecto = consultas::listaCicloProyecto($DB_TRAMITE_DOCUMENTARIO, $obj_session["uname"]);
                $botones = "";
                $new_listaProyecto = array();
                foreach ($listaProyecto as $value) {

                    $botones = "<span class='btn edit-doc' title='Editar Documento' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus cus-application-form-edit'></i></span>";
                    $botones .= "<span class='btn generar_word' title='Generar Word' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus cus-page-word'></i></span>";
                    $botones .= "<span class='btn subir_documento' title='Cargar documento' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus  cus-page-save'></i></span>";


                    $row = array("CODIGO" => $value["CODIGO"],
                        "TIPO_DE_DOCUMENTO" => $value["TIPO_DE_DOCUMENTO"],
                        "FECHA_CREACION" => $value["FECHA_CREACION"],
                        "CICLO" => $value["CICLO"],
                        "INDICATIVO_DEL_DOCUMENTO" => $value["INDICATIVO_DEL_DOCUMENTO"],
                        "ASUNTO" => $value["ASUNTO"],
                        "USUARIO" => $value["USUARIO"],
                        "ACCION" => $botones);

                    array_push($new_listaProyecto, $row);
                }
                $rs = new Response();
                $rs->setContent(json_encode($new_listaProyecto));

                return $rs;
            } else {
                return new Response("vuelva a iniciar sesion.");
            }
        }
    }

    /**
     * @Route("/consulta_proyecto", name="_consulta_proyecto")
     */
    public function consulta_proyectoAction(Request $request) {

        if ($request->isXmlHttpRequest()) {
            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            if ($response == true) {
                $detalleproyecto = consultas::detalleProyecto($this, $request);

                return new Response($detalleproyecto);
            } else {
                return new Response("vuelva a iniciar sesion.");
            }
        }
    }

//    veProyecto

    /**
     * @Route("/listaParaFirmar", name="_listaParaFirmar")
     */
    public function listaParaFirmarAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $session = new SessionManager();
            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];
            if ($response == true) {

                $DB_TRAMITE_DOCUMENTARIO = $this->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
                $listaParaFirmar = consultas::listaParaFirmar($DB_TRAMITE_DOCUMENTARIO, $obj_session["uname"]);


                $new_listaParaFirmar = array();


                foreach ($listaParaFirmar as $value) {

                    $path_open_doc
                            = $this->get('router')->generate('_abrirArchivo', array("codigo" => $value["CODIGO"]));

                    $botones = "";
                    $botones .= "<span class='btn return-doc-proye' title='Retornar a estado en Proyecto' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus cus-arrow-rotate-clockwise'></i></span>";
                    $botones .= "<a target='_blank' href='" . $path_open_doc . "'><span class='btn mostrarPDF' title='ver documento' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus cus-page-white-acrobat'></i></span></a>";

                    if ($value["canbesign"] == '1') {
                        $botones .= "<span class='btn para-firma' title='para firmar' rel='" . $value["CODIGO"] . "-" . $value["codigo_firmante"] . "-" . $value["NUM_FIRMA"] . "-" . $value["cant_firma"] . "-" . $obj_session["uname"] . "-" . $value["ASUNTO"] . "' type='button' ><i class='cus cus-pencil'></i></span>";
                    }

                    $row = array("CODIGO" => $value["CODIGO"],
                        "TIPO_DE_DOCUMENTO" => $value["TIPO_DE_DOCUMENTO"],
                        "FECHA_CREACION" => $value["FECHA_CREACION"],
                        "CICLO" => $value["CICLO"],
                        "INDICATIVO_DEL_DOCUMENTO" => $value["INDICATIVO_DEL_DOCUMENTO"],
                        "ASUNTO" => $value["ASUNTO"],
                        "USUARIO" => $value["USUARIO"],
                        "ACCION" => $botones);

                    array_push($new_listaParaFirmar, $row);
                }


                $rs = new Response();
                $rs->setContent(json_encode($new_listaParaFirmar));

                return $rs;
            } else {
                return new Response("vuelva a iniciar sesion.");
            }
        }
    }

    /**
     * @Route("/listaParavistoBueno", name="_listaParavistoBueno")
     */
    public function listaParavistoBuenoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $session = new SessionManager();
            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];
            if ($response == true) {

                $DB_TRAMITE_DOCUMENTARIO = $this->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");
                $listaParaVistoBueno = consultas::listaParaVistoBueno($DB_TRAMITE_DOCUMENTARIO, $obj_session["uname"]);


                $new_listaParaVistoBueno = array();
                foreach ($listaParaVistoBueno as $value) {
                    $botones = "";
                    $botones .= "<span class='btn return-doc-proye' title='Retornar a estado en Proyecto' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus cus-arrow-rotate-clockwise'></i></span>";
                    $botones .= "<span class='btn' title='Ver PDF' rel='" . $value["CODIGO"] . "' type='button' ><i class='cus cus-page-white-acrobat'></i></span>";


                    if ($value["canbesign"] == '1') {

                        $botones .= "<span class='btn para-firma-vb' title='Para firma' rel='" . $value["CODIGO"] . "-" . $value["codigo_firmante"] . "-" . $value["NUM_FIRMA"] . "-" . $value["cant_firma"] . "-" . $obj_session["uname"] . "' type='button' ><i class='cus cus-pencil'></i></span>";
                    }

                    $row = array("CODIGO" => $value["CODIGO"],
                        "TIPO_DE_DOCUMENTO" => $value["TIPO_DE_DOCUMENTO"],
                        "FECHA_CREACION" => $value["FECHA_CREACION"],
                        "CICLO" => $value["CICLO"],
                        "INDICATIVO_DEL_DOCUMENTO" => $value["INDICATIVO_DEL_DOCUMENTO"],
                        "ASUNTO" => $value["ASUNTO"],
                        "USUARIO" => $value["USUARIO"],
                        "ACCION" => $botones);

                    array_push($new_listaParaVistoBueno, $row);
                }



                $rs = new Response();
                $rs->setContent(json_encode($new_listaParaVistoBueno));

                return $rs;
            } else {
                return new Response("vuelva a iniciar sesion.");
            }
        }
    }

    /**
     * @Route("/ajax_retornar_estado_Documento", name="_ajax_retornar_estado_Documento")
     */
    public function retornarestadoDocumentoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $session = new SessionManager();
            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];
            if ($response == true) {
                $respuesta = consultas::retornarestadoDocumento($this, $request);

                return new Response($respuesta);
            } else {
                return new Response("vuelva a iniciar sesion.");
            }
        }
    }

    /**
     * @Route("/ajax_visto_bueno_Documento", name="_ajax_visto_bueno_Documento")
     */
    public function vistobuenoDocumentoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $session = new SessionManager();
            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];
            if ($response == true) {
                $respuesta = consultas::vistoBuenoDocumento($this, $request);

                return new Response($respuesta);
            } else {
                return new Response("vuelva a iniciar sesion.");
            }
        }
    }

    /**
     * @Route("/upload", name="_upload")
     */
    public function uploadAction() {

        if ($this->getRequest()->isMethod('POST')) {

            $session = new SessionManager();
            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            if ($response == true) {

                $request = $this->getRequest();

                $uploadedFile = $request->files->get('archivo');
                $id_docu = $request->request->get("id_docu");

//                $destino = 'c:/prueba';
                $destino = "/var/www/intranet/sharepointdocsproyecto";

                $uploadedFile->move($destino, "$id_docu." . $uploadedFile->guessExtension());

                $consulta = new consultas();


                $consulta->parafirma($this, $id_docu);


                return $this->redirect($this->generateUrl("_documentos_sitradoc"));
            } else {

                return new Response("vuelva a iniciar sesion.");
            }
        }
    }

    /**
     * @Route("/abrirArchivo/{codigo}", name="_abrirArchivo")
     */
    public function abrirArchivoAction($codigo) {

        $session = new SessionManager();
        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];
        //  $destino = 'c:\prueba';
        $destino = "/var/www/intranet/sharepointdocsproyecto";

        if ($response == true) {
            return new Response(readfile($destino . '/' . $codigo . '.pdf'), 200, array('Content-Type' => 'application/pdf'));
        } else {

            return new Response("vuelva a iniciar sesion.");
        }
    }

    /**
     * @Route("/prueba_grilla", name="_prueba")
     */
    public function pruebagridAction(Request $request) {


        $page = $request->request->get("page");  // Almacena el numero de pagina actual
        $limit = $request->request->get("rows"); // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = $request->request->get("sidx");  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = $request->request->get("sord");  // Almacena el modo de ordenación


        if (!$sidx)
            $sidx = 1;



        $DB_TRAMITE_DOCUMENTARIO = $this->getDoctrine()->getConnection("DB_TRAMITE_DOCUMENTARIO");

        $sql_count = "select count(DAT.ID_DOCUMENTO_PROY) as cantidad";
        $sql_count .= " from DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql_count .=" dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO=CLA.ID_CLASE_DOCUMENTO_INTERNO where CIC.ID_CICLOFIRMA=1";
        $sql_count .=" AND   USUARIO=  'lsantos'";

        $qCount = $DB_TRAMITE_DOCUMENTARIO->prepare($sql_count);
        $qCount->execute();

        // Se obtiene el resultado de la consulta
        $count = trim($qCount->fetchColumn());

        //En base al numero de registros se obtiene el numero de paginas
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;

        //Almacena numero de registro donde se va a empezar a recuperar los registros para la pagina
        $start = $limit * $page - $limit;


        $sql = "DAT.ID_DOCUMENTO_PROY AS CODIGO,CLA.DESCRIPCION AS TIPO_DE_DOCUMENTO,convert(char(10), DAT.AUDITMOD, 103) as FECHA_CREACION,";
        $sql.=" CIC.DESCRIPCION AS CICLO,DAT.INDICATIVO_OFICIO AS INDICATIVO_DEL_DOCUMENTO, DAT.ASUNTO AS ASUNTO, DAT.USUARIO AS USUARIO";
        $sql.=" from DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql.=" dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO=CLA.ID_CLASE_DOCUMENTO_INTERNO where CIC.ID_CICLOFIRMA=1";
        $sql.=" AND   USUARIO=  'lsantos'";

        $order_by = " order by convert(DATETIME, DAT.AUDITMOD, 103) $sord";

        $sql_no_incluye = " SELECT TOP $start DAT.ID_DOCUMENTO_PROY ";
        $sql_no_incluye.=" FROM DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql_no_incluye.=" dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO=CLA.ID_CLASE_DOCUMENTO_INTERNO where CIC.ID_CICLOFIRMA=1";
        $sql_no_incluye.=" AND   USUARIO=  'lsantos' order by convert(DATETIME, DAT.AUDITMOD, 103) $sord";

        $new_sql = "SELECT TOP $limit $sql AND DAT.ID_DOCUMENTO_PROY NOT IN ($sql_no_incluye) $order_by";


        $query = $DB_TRAMITE_DOCUMENTARIO->prepare($new_sql);
        $query->execute();

//        Consulta que devuelve los registros de una sola pagina    
        $result = $query->fetchAll();


// Se agregan los datos de la respuesta del servidor
        $respuesta = (object) '';

        $respuesta->page = $page;
        $respuesta->total = $total_pages;
        $respuesta->records = $count;
        $i = 0;

        foreach ($result as $fila) {
            $respuesta->rows[$i]['id'] = $fila["CODIGO"];
            $celdas = array($fila["CODIGO"], $fila["TIPO_DE_DOCUMENTO"], $fila["FECHA_CREACION"], $fila["CICLO"], $fila["INDICATIVO_DEL_DOCUMENTO"], $fila["ASUNTO"], $fila["USUARIO"]);
            $respuesta->rows[$i]['cell'] = $celdas;
            $i++;
        }

        $rs = new Response();
        $rs->setContent(json_encode($respuesta));
//        $rs->setContent($new_sql);


        return $rs;
    }

    /**
     * @Route("/prob", name="_prob")
     */
    public function probAction() {
        return $this->render("SitradocBundle::prueba.html.twig");
    }

    /**
     * @Route("/me", name="_me")
     */
    public function meAction() {

        $num_rows = 30;
        $page = "";  // Almacena el numero de pagina actual
        $limit = 10; // Almacena el numero de filas que se van a mostrar por pagina
        $sidx = "convert(DATETIME, DAT.AUDITMOD, 103)";  // Almacena el indice por el cual se hará la ordenación de los datos
        $sord = "desc";  // Almacena el modo de ordenación





        $sql_select = "select DAT.ID_DOCUMENTO_PROY,CLA.DESCRIPCION,convert(char(10), DAT.AUDITMOD, 103),";
        $sql_select.=" CIC.DESCRIPCION,DAT.INDICATIVO_OFICIO, DAT.ASUNTO, DAT.USUARIO";
        $sql_select.=" FROM DAT_DOCUMENTO_PROYECTO DAT INNER JOIN TBL_CICLO_FIRMA CIC ON DAT.ID_CICLOFIRMA=CIC.ID_CICLOFIRMA INNER JOIN";
        $sql_select.=" dbo.CLASE_DOCUMENTO_INTERNO CLA ON DAT.ID_CLASE_DOCUMENTO_INTERNO=CLA.ID_CLASE_DOCUMENTO_INTERNO";

        $sql_where = "CIC.ID_CICLOFIRMA=1 AND   USUARIO=  'lsantos'";
//        $sql_where = "";

        $sql_where = trim($sql_where) == "" ? "" : "where $sql_where";

        $order_by = trim($sidx) == "" ? "" : "order by $sidx $sord";
//        $order_by = "";


        $sql_select = trim($sql_select);

        $sql_no_select = substr(trim($sql_select), 6, strlen($sql_select));

        $sql_colums = "SELECT TOP $limit $sql_no_select";

        $sql_not_in = "";

        if ($sql_where != "") {

            //ULTIMO "FROM" DE LA CONSULTA
            $array_from_cad = explode("FROM", $sql_no_select);
            $from_cade = $array_from_cad[count($array_from_cad) - 1];

            //1er DATO A MOSTRAR DE LA CONSULTA
            $array_prim_dato = explode(",", $sql_no_select);
            $prim_dato = $array_prim_dato[0];


            $sql_not_in = "AND $prim_dato NOT IN (SELECT TOP 0 $prim_dato FROM $from_cade $sql_where $order_by)";
        }

        $sql_result = "$sql_colums $sql_where $sql_not_in $order_by";


        return new Response($sql_result);
    }

}

//            ::::::::  DESCARGAR DE ARCHIVOS::::::::
//            $response = new StreamedResponse();
//            $response->setCallback(function () {
//                        $name = 'C:\prueba\95.pdf';
//                        $fp = fopen($name, 'rb');
//                        fpassthru($fp);
//                    });
//            $d = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, '95.pdf');
//            $response->headers->set('Content-Disposition', $d);
//            $response->send();
?>
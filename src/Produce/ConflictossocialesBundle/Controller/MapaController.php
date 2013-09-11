<?php

namespace Produce\ConflictossocialesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;

class MapaController extends Controller {

    /**
     * @Route("/mapa", name="_mapa")
     */
    public function mapaAction() {

        $anio = date("Y");
        $mes = date("m");

        $DB_CONFLICTOS_SOCIALES = $this->getDoctrine()->getConnection("DB_CONFLICTOS_SOCIALES");
        $sql = " select co.conflicto_id,re.region_nombre,re.region_variable ";
        $sql .=" from conflictos co inner join dbo.REGION re on co.region_id=re.region_id";
        $sql .=" where co.anio=$anio and mes=$mes";

        $query = $DB_CONFLICTOS_SOCIALES->prepare($sql); //preparo consulta
        $query->execute(); //ejecuto la consulta
        $result = $query->fetchAll();
        return $this->render("ConflictosSocialesBundle:mapa:mapa_conflictos_sociales.html.twig", array("punto_conflicto" => $result));
    }

    /**
     * @Route("/mapa_vista", name="_mapa_vista")
     */
    public function mapa_vistaAction() {

        $anio = date("Y");
        $mes = date("m");

        $DB_CONFLICTOS_SOCIALES = $this->getDoctrine()->getConnection("DB_CONFLICTOS_SOCIALES");
        $sql = " select co.conflicto_id,re.region_nombre,re.region_variable ";
        $sql .=" from conflictos co inner join dbo.REGION re on co.region_id=re.region_id";
        $sql .=" where co.anio=$anio and mes=$mes";

        $query = $DB_CONFLICTOS_SOCIALES->prepare($sql); //preparo consulta
        $query->execute(); //ejecuto la consulta
        $result = $query->fetchAll();
        return $this->render("ConflictosSocialesBundle:mapa:mapa_vista_conflictos_sociales.html.twig", array("punto_conflicto" => $result));
    }

    /**
     * @Route("/buscarMapa", name="_buscarMapa")
     */
    public function buscarMapaAction(Request $request) {
        if ($request->isXmlHttpRequest()){

            $cbo_mes = $request->request->get("cbo_mes");
            $cbo_anio = $request->request->get("cbo_anio");


            $DB_CONFLICTOS_SOCIALES = $this->getDoctrine()->getConnection("DB_CONFLICTOS_SOCIALES");
            $sql = "select co.conflicto_id, re.region_nombre, re.region_variable from conflictos co inner join REGION re on co.region_id = re.region_id";
            $sql .= " where co.anio = $cbo_anio and co.mes = $cbo_mes";
            $query = $DB_CONFLICTOS_SOCIALES->prepare($sql); //preparo consulta
            $query->execute(); //ejecuto la consulta
            $result = $query->fetchAll();
            $contenido = '';
            foreach ($result as $value){
                $contenido.= '<div class="' . $value["region_variable"] . '-area" >';
                $contenido.= '<div class="div-tooltip ' . $value["region_variable"] . '-alert alert-icon" rel="' . $value["conflicto_id"] . '" title="' . $value["region_nombre"] . '">';
                $contenido.= '<div class="flecha_nube"></div>';
                $contenido.= '<div class="Info-Section"></div>';
                $contenido.= '</div>';
                $contenido.= '</div>';
            }
            return new Response($contenido);
        }
    }

    /**
     * @Route("/newConflicto", name="_newconflicto")
     */
    public function insertarConflictoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $anio = date("Y");
            $mes = date("m");
            $detalles = $request->request->get("detalles");
            $data_detalles = split("\\|", $detalles);



            $region = $request->request->get("cbo_region");

            $DB_CONFLICTOS_SOCIALES = $this->getDoctrine()->getConnection("DB_CONFLICTOS_SOCIALES");
            $sql = "INSERT INTO CONFLICTOS VALUES($anio, $mes, $region)";
            $query = $DB_CONFLICTOS_SOCIALES->prepare($sql);
            $query->execute();

            for ($i = 0; $i < count($data_detalles); $i++) {
                $sql2 = "insert into DETALLE_CONFLICTO values('" . $data_detalles[$i] . "', ";
                $sql2 .= "(select co.conflicto_id from CONFLICTOS co where co.anio = $anio and co.mes = $mes and region_id = $region))";
                $query2 = $DB_CONFLICTOS_SOCIALES->prepare($sql2);
                $query2->execute();
            }
            return new Response($data_detalles[0]);
        }
    }

    /**
     * @Route("/departamentosValidos", name="_departamentosValidos")
     */
    public function departamentosValidosAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $anio = date("Y");
            $mes = date("m");

            $DB_CONFLICTOS_SOCIALES = $this->getDoctrine()->getConnection("DB_CONFLICTOS_SOCIALES");
            $sql = "select region_id from conflictos where anio = $anio and mes = $mes";
            $query = $DB_CONFLICTOS_SOCIALES->prepare($sql);
            $query->execute();
            $conflictos = $query->fetchAll();

            $sql2 = "select region_id, region_nombre, region_variable from region";
            $query2 = $DB_CONFLICTOS_SOCIALES->prepare($sql2);
            $query2->execute();
            $regiones = $query2->fetchAll();

            foreach ($conflictos as $con) {
                $posicion = $con["region_id"];
                unset($regiones[($posicion - 1)]);
            }
            $regiones = array_filter($regiones);

            $response = "";
            foreach ($regiones as $reg) {
                $response.="<option value = '" . $reg["region_id"] . "' > " . $reg["region_nombre"] . "</option>";
            }



            return new Response($response);
        }
    }

    /**
     * @Route("/listaObservaciones", name="_listaObservaciones")
     */
    public function listaObservacionesAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $conflicto_id = $request->request->get("conflicto_id");
            $DB_CONFLICTOS_SOCIALES = $this->getDoctrine()->getConnection("DB_CONFLICTOS_SOCIALES");
            $sql = "select descripcion from DETALLE_CONFLICTO where conflicto_id = '$conflicto_id'";
            $query = $DB_CONFLICTOS_SOCIALES->prepare($sql);
            $query->execute();
            $observaciones = $query->fetchAll();
            $response = "<ul>";

            foreach ($observaciones as $row) {
                $response .="<li>" . $row["descripcion"] . "<br></li>";
            }
            $response.="</ul>";
            return new Response($response);
        }
    }

    /**
     * @Pdf()
     * @Route("/pdf", name="_pdf")
     */
    public function pdfAction() {
        /*
         * https://github.com/psliwa/PHPPdf#intro
         */
        $facade = $this->get('ps_pdf.facade');
        $response = new Response();

        $DB_CONFLICTOS_SOCIALES = $this->getDoctrine()->getConnection("DB_CONFLICTOS_SOCIALES");
        $sql2 = "select region_id, region_nombre, region_variable from region";
        $query2 = $DB_CONFLICTOS_SOCIALES->prepare($sql2);
        $query2->execute();
        $regiones = $query2->fetchAll();



        $this->render('ConflictosSocialesBundle:mapa:pruebapdf.pdf.twig', array("regiones" => $regiones), $response);
        $xml = $response->getContent();
        $content = $facade->render($xml);

        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }

}

?>

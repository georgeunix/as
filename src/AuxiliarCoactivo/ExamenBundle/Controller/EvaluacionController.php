<?php

namespace AuxiliarCoactivo\ExamenBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ps\PdfBundle\Annotation\Pdf;

class EvaluacionController extends Controller {

    /**
     * @Route("/lista_postulantes",name="_lista_postulantes")
     */
    public function lista_postulantesAction() {
        $cn = $this->getDoctrine()->getConnection("EXAMEN");
        $sql = "SELECT EVALUACION_ID,NOMBRES_APELLIDOS,FECHA FROM EVALUACIONES";
        $query = $cn->prepare($sql); //preparo consulta
        $query->execute(); //ejecuto la consulta
        $lista = $query->fetchAll();

        $tabla = "";

        foreach ($lista as $row) {
            $tabla.="<tr>";
            $tabla.="<td>" . $row["EVALUACION_ID"] . "</td>";
            $tabla.="<td>" . $row["NOMBRES_APELLIDOS"] . "</td>";
            $tabla.="<td>" . $row["FECHA"] . "</td>";

            $sql2 = " select pre_eval.OPCION_MARCADA,p.respuesta";
            $sql2 .= " from EVALUACIONES AS eval ";
            $sql2 .= " inner join PREGUNTAS_EVALUADAS AS pre_eval on eval.EVALUACION_ID=pre_eval.EVALUACION_ID";
            $sql2 .= " inner join PREGUNTAS AS p ON  pre_eval.PREGUNTA_ID= p.PREGUNTA_ID where eval.evaluacion_id='" . $row["EVALUACION_ID"] . "'";

            $query2 = $cn->prepare($sql2);
            $query2->execute();
            $notas = $query2->fetchAll();
            $puntaje = 0;
            foreach ($notas as $n) {
                if ($n["OPCION_MARCADA"] == $n["respuesta"]) {
                    $puntaje++;
                }
            }
            $tabla.="<td>" . ($puntaje / 2) . "</td>";
            $tabla.="<td><a href='reporte_examen/" . $row["EVALUACION_ID"] . "' target='_blank'>ver</a></td>";

            $tabla.="</tr>";
        }



        return $this->render("ExamenBundle:examen:panel_control.html.twig", array("postulantes" => $tabla));
    }

    /**
     * @Pdf()
     * @Route("/reporte_examen/{ID}",name="_reporte_examen")
     */
    public function reportePDFAction($ID) {
        $facade = $this->get('ps_pdf.facade');
        $letras = array("(*", "a", "b", "c", "d", "e");
        $response = new Response();

        $cn = $this->getDoctrine()->getConnection("EXAMEN");
        $sql = " select PR_EVAL.EVALUACION_ID,PR.PREGUNTA_ID,PR.DESCRIPCION,PR.respuesta,PR_EVAL.OPCION_MARCADA from PREGUNTAS_EVALUADAS PR_EVAL ";
        $sql .=" INNER JOIN PREGUNTAS PR ON PR_EVAL.PREGUNTA_ID=PR.PREGUNTA_ID WHERE PR_EVAL.EVALUACION_ID='$ID'";
        $sql .=" ORDER BY PR_EVAL.EVALUACION_ID ";
        $query = $cn->prepare($sql);
        $query->execute();
        $examen = $query->fetchAll();

        $datos_examen = array();
        foreach ($examen as $row) {
            $punto = "incorrecta";
            if ($letras[$row["OPCION_MARCADA"]] == $letras[$row["respuesta"]]) {
                $punto = "correcta";
            }

            $row = array(
                "EVALUACION_ID" => $row["EVALUACION_ID"],
                "PREGUNTA_ID" => $row["PREGUNTA_ID"],
                "DESCRIPCION" => $row["DESCRIPCION"],
                "OPCION_MARCADA" => $letras[$row["OPCION_MARCADA"]],
                "respuesta" => $letras[$row["respuesta"]],
                "puntos" => $punto
            );
            array_push($datos_examen, $row);
        }

       
        $sql2 = "select NOMBRES_APELLIDOS from EVALUACIONES WHERE EVALUACION_ID='$ID' ";
        $query2 = $cn->prepare($sql2);
        $query2->execute();
        $evaluacion = $query2->fetchAll();



        $this->render('ExamenBundle:examen:reporte_examen.pdf.twig', array("examen" => $datos_examen,"persona"=>$evaluacion[0]["NOMBRES_APELLIDOS"]), $response);
        $xml = $response->getContent();
        $content = $facade->render($xml);

        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }

    /**
     * @Route("/evaluacion",name="_evaluacion")
     */
    public function listaPreguntasAction() {
        $cn = $this->getDoctrine()->getConnection("EXAMEN");
        $sql = "SELECT PREGUNTA_ID,DESCRIPCION FROM PREGUNTAS";

        $query = $cn->prepare($sql); //preparo consulta
        $query->execute(); //ejecuto la consulta
        $preguntas = $query->fetchAll();
        $letras = array("a", "b", "c", "d", "e", "f");

        $cuestionario = "";
        foreach ($preguntas as $quest) {
            $cuestionario.="<div class='pregunta'><label>" . $quest["PREGUNTA_ID"] . ".-</label>&nbsp;&nbsp;" . $quest["DESCRIPCION"] . ":</div>";
            $sql2 = "SELECT OPCION_ID, DESCRIPCION FROM OPCION_PREGUNTA where PREGUNTA_ID='" . $quest["PREGUNTA_ID"] . "'";
            $qr = $cn->prepare($sql2);
            $qr->execute();
            $opciones = $qr->fetchAll();
            $alternativas = "<div class='opciones'>";
            $cont = 0;
            foreach ($opciones as $op) {

                $alternativas.="<label><input type='radio'name='quest_" . $quest["PREGUNTA_ID"] . "' value='" . ($cont + 1) . "'/>";
                $alternativas.="<b>" . $letras[$cont] . ")</b>&nbsp;" . $op["DESCRIPCION"] . ".</label><br><br>";
                $cont++;
            }
            $alternativas.="</div>";
            $cuestionario.=$alternativas;
        }

        return $this->render("ExamenBundle:examen:evaluacion.html.twig", array("cuestionario" => $cuestionario));
    }

    /**
     * @Route("/termina_examen",name="_termina_examen")
     */
    public function terminaExamenAction(Request $request) {

        if ($request->isXmlHttpRequest()) {
            $nombres_apellidos = $request->request->get("NOMBRES_APELLIDOS");
            $fecha = date("d/m/Y H:i");
            $cn = $this->getDoctrine()->getConnection("EXAMEN");
            $insert_evaluaciones = "insert into EVALUACIONES values('$nombres_apellidos','$fecha')";
            $query = $cn->prepare($insert_evaluaciones);
            $query->execute(); //ejecuto la consulta

            $consulta_id_evaluaciones = "SELECT TOP 1 EVALUACION_ID FROM EVALUACIONES ORDER BY FECHA DESC";
            $query_2 = $cn->prepare($consulta_id_evaluaciones);
            $query_2->execute(); //ejecuto la consulta
            $consulta_eval = $query_2->fetchAll();

            $codigo_eval = $consulta_eval[0]["EVALUACION_ID"];


            $consulta_preguntas = "SELECT PREGUNTA_ID FROM PREGUNTAS";
            $query_3 = $cn->prepare($consulta_preguntas);
            $query_3->execute();
            $consulta_preguntas_id = $query_3->fetchAll();

            foreach ($consulta_preguntas_id as $row) {

                $opcion_marcada = $request->request->get("quest_" . $row["PREGUNTA_ID"]);
                $inserta_evaluadas = "INSERT INTO PREGUNTAS_EVALUADAS VALUES('" . $row["PREGUNTA_ID"] . "','$opcion_marcada','$codigo_eval')";
                $query_4 = $cn->prepare($inserta_evaluadas);
                $query_4->execute();
            }
            return new Response("Se agrego con exito");
        }
    }

    /**
     * @Route("/muestra_nota",name="_muestra_nota")
     */
    public function muestra_nota(Request $request) {
        if ($request->isXmlHttpRequest()) {
            
        }
    }

}
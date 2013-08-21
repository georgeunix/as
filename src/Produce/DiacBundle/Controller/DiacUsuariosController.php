<?php

namespace Produce\DiacBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce\SessionManager;
use Produce\DiacBundle\Util\Usuarios;
use Produce\DiacBundle\Util\consultas;

class DiacUsuariosController extends Controller {

    /**
     * @Route("/usuario", name="usuario")
     */
    public function TrabajadorDiacAction() {
        $session = new SessionManager();

        $obj_session = $session->valida_session($this);
        $response = $obj_session["response"];


        $DNA = $this->getDoctrine()->getConnection("DNA");

        if ($response == true) {
            $consultas = new consultas();
            $menutotal = $consultas->MenuTotal($DNA, $obj_session["uname"]);

            $Usuarios = new Usuarios();
            $trabajadores = $Usuarios->listaTrabajador($DNA, "", "");

            $array_trabajadores = array();
            foreach ($trabajadores as $value) {
                $seleccion = "";

                $Perfiles = $Usuarios->listaPerfiles($DNA, $value["codigo_trabajador"]);
                $contenido = '<select name="cboPerfil' . $value["codigo_trabajador"] . '" id="cboPerfil' . $value["codigo_trabajador"] . '">';
                $contenido.= '<option value="">--Seleccione--</option>';
                foreach ($Perfiles as $row_perfil) {
                    if (trim($value["codigo_trabajador"]) == trim($row_perfil["codigo"])) {
                        $seleccion = "selected=selected";
                    }
                    $contenido.= '<option value="' . $row_perfil["id_grupo"] . '" ' . $seleccion . '>' . $row_perfil["descripcion"] . '</option>';
                    $seleccion = "";
                }
                $contenido.= '</select>';

                $row = array(
                    "codigo_trabajador" => $value["codigo_trabajador"],
                    "tipo_trabajador" => $value["tipo_trabajador"],
                    "apellidos" => $value["apellidos"],
                    "nombres" => $value["nombres"],
                    "dni" => $value["dni"],
                    "usuario_sistema" => $value["usuario_sistema"],
                    "combo_perfil" => $contenido);
                array_push($array_trabajadores, $row);
                asort($array_trabajadores);
            }

            $response = array(
                "menutotal" => $menutotal,
                "trabajadores" => $array_trabajadores
            );
            $respuesta = array_merge($obj_session, $response);


            return $this->render("DiacBundle:diac:usuarios.html.twig", $respuesta);
        } else {
            return $this->redirect($obj_session["redirect_page"]);
        }
    }

    /**
     * @Route("/guardaperfilusuario", name="guardaperfilusuario")
     */
    public function GuardarPerfilUsuarioDiacAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");
            if ($response == true) {
                $Usuarios = new Usuarios();

                $codGrupo = $request->request->get("idgrupo");
                $codtrab = $request->request->get("codtrab");

                //$DB_GENERAL = $this->getDoctrine()->getConnection("DB_GENERAL");
                //$codTraba = $Usuarios->codTrabajador($DB_GENERAL, $session["uname"]);
                //Validar Si existe el grupo - Inserta o Actualiza
                $codGrupoHay = $Usuarios->ValidaCodGrupo($DNA, $codtrab);

                if ($codGrupoHay[0]["hay"] == 0) {
                    $Usuarios->GuardarPerfilUsuario($DNA, $codGrupo, $codtrab, "I");
                } else {
                    $Usuarios->GuardarPerfilUsuario($DNA, $codGrupo, $codtrab, "U");
                }

                return new Response($codGrupo);
            } else {
                return new Response("");
            }
        }
    }

    /**
     * @Route("/deleteperfilusuario", name="deleteperfilusuario")
     */
    public function EliminarPerfilUsuarioDiacAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = new SessionManager();

            $obj_session = $session->valida_session($this);
            $response = $obj_session["response"];

            $DNA = $this->getDoctrine()->getConnection("DNA");
            if ($response == true) {
                $Usuarios = new Usuarios();
                $codGrupo = $request->request->get("idgrupo");
                $codtrab = $request->request->get("codtrab");
                $Usuarios->EliminarPerfilUsuario($DNA, $codGrupo, $codtrab);

                return new Response($codGrupo);
            } else {
                return new Response("");
            }
        }
    }

}

?>
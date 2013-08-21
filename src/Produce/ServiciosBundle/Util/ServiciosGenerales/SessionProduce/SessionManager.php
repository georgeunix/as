<?php

namespace Produce\ServiciosBundle\Util\ServiciosGenerales\SessionProduce;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class SessionManager {

    private $redirect_page = "https://172.16.247.20/";

    public function valida_session($controller) {
        $global = Request::createFromGlobals();
        $session = new Session();

        if ($global->request->has("cod_usuario")) {

            $cod_usuario = $global->request->get("cod_usuario");
            $email = $this->decode_email($cod_usuario);
            $EMAILS = $this->buscaEmail($controller, $email);
            if (count($EMAILS) == 1) {
                $session->start();
                $session->set("uname", $email);
                $usuario = array("response" => true, "uname" => $email);
                return $usuario;
            } else {
                return array("response" => false, "redirect_page" => $this->redirect_page);
            }
        } else {
            if ($session->has("uname")) {//pregunta si la session ya se inicio
                $email = $session->get("uname"); //obtengo la variable de session ya inicializada
                $usuario = array("response" => true, "uname" => $email); //almaceno dentro de un array la peticion

                return $usuario; //retorna la respuesta de usuario
            } else {
                return array("response" => false, "redirect_page" => $this->redirect_page);
            }
        }
    }

    public function cerrar_session() {
        $session = new Session();
        if ($session->has("uname")) {//pregunta si existe la session uname
            $session->remove("uname"); //cierra la session
        }
        $session_close=($this->redirect_page)."exit.php";
        return $session_close; //cierra la session del portal de produce.
    }

    private function buscaEmail($controller, $email) {
        $DB_GENERAL = $controller->getDoctrine()->getConnection("DB_GENERAL");
        $sql = "SELECT EMAIL FROM db_general.jcardenas.H_TRABAJADOR WHERE EMAIL='$email'";
        $query = $DB_GENERAL->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    private function decode_email($encriptado) {

        $desencriptado = trim($encriptado);
        $i = 1;
        while ($i < 6) {
            $desencriptado = base64_decode($desencriptado);
            $i++;
        }
        return $desencriptado;
    }

}

?>

<?php

namespace Produce\pmceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller {
    /**
     * @Route("/pmce", name="_index_pmce")
     */
    public function mapaAction() {

$errors = array(array("Title" =>"rose","Price" =>1.25,"Number"=>15),    array("Title" => "daisy","Price" => 0.75,"Number" => 25, ),
    array("Title" => "orchid",
        "Price" => 1.15,
        "Number" => 7
    )
            );
        return $this->render('ProducepmceBundle:plantillas:interfaces_1.html.twig', array('errors' => $errors,'uname'=>"user default "));
    }

}

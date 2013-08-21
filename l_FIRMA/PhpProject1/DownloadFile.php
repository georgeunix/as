<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
$prefijo = "/var/www/intranet/";
$tipoTemplate = $_GET["tipoTemplate"]; 

$wordTemplate = file_get_contents($prefijo.'/framework/plantillas/'.$tipoTemplate, true);
// descomentar para la version en linux
echo $wordTemplate;

?>

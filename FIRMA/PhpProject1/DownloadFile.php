<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

//$jsessionid = $_GET["jsessionid"];
$tipoTemplate = $_GET["tipoTemplate"]; 
//$wordTemplate = file_get_contents('http://172.16.247.20/framework/FIRMA/PhpProject1/plantillas/'.$tipoTemplate, true);

$prefijo_win = "C:/wamp/www/";
$prefijo_linux = "/var/www/intranet/";

$wordTemplate = file_get_contents($prefijo_win.'/framework/plantillas/'.$tipoTemplate, true);
// descomentar para la version en linux
//$wordTemplate = file_get_contents($prefijo_linux.'/framework/plantillas/'.$tipoTemplate, true);
echo $wordTemplate;

?>

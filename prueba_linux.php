<!DOCTYPE html>


<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div>Ingrese Usuario de Prueba </div>
        <form action="http://172.16.210.200/framework/web/app_dev.php/sitradoc/documentos" method="POST">
            <input name="cod_usuario" type="text" value="VjFkMGIxUXlSa2hUYmxaV1lteHdjVnBJYjNkUFVUMDk=">
            <input type="submit">
        </form>
    </body>
</html>


<?php
//jbutron
//saspillaga
//casmad
//aalanoca
//aaguilar;
$encriptado = "jvasquezu";
echo $encriptado . "<br>";
$i = 1;
while ($i < 6) {

    $encriptado = base64_encode($encriptado);
    echo $encriptado . "<br>";
    $i++;
}

echo "<br>Desencriptado:" . base64_decode(base64_decode(base64_decode(base64_decode(base64_decode($encriptado)))));

//A implementar
//        function encodeUser($encriptado) {
//            $i = 1;
//            while ($i < 6) {
//
//                $encriptado = base64_encode($encriptado);
//                $i++;
//            }
//            return $encriptado;
//           //return base64_decode(base64_decode(base64_decode(base64_decode(base64_decode($encriptado)))));
//        }
//if (md5($str) === '1f3870be274f6c49b3e31a0c6728957f') {
//    echo "Would you like a green or red apple?";
//}
//// Server in the this format: <computer>\<instance name> or 
//// <server>,<port> when using a non default port number
//$server = '172.16.247.22';
//
//// Connect to MSSQL
//$link = mssql_connect($server, 'web_tramite', 'deimos');
//
//if (!$link) {
//    die('Something went wrong while connecting to MSSQL');
//}else{
//    echo "se conecto";
//}
?>



<script>
    function isBigEnough(element, index, array) {

        var firstChar = element.substr(0, 1);
        if (firstChar.toLowerCase() === "d")
            return element;
        else
            return false;

    }
    var filtered = ["css-dato", "css-dato2", "goofy", "nada", "ddd"].filter(isBigEnough);
    //alert(filtered[0]);
</script>        




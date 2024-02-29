<?php 

include 'formato521.php';

$matricula = trim($_POST['matricula']);

$provincia = "35";
$municipio= "012";
$iddocumento= trim($_POST['expediente']);
$tributo= "092";//Ejemplo a consultar con Alexis
$ejercicio= date("Y");
$fechalimite= Date('y-m-d', strtotime("+20 days"));
$importe=  str_replace(".", "", $_POST['importe']);

$formato521 = new formato521($provincia, $municipio, $iddocumento, $tributo, $ejercicio, $fechalimite, $importe);

$identificacion = $formato521->generaIdentificacion();
$referencia = $formato521->generaReferencia();


$respuesta = array ('identificacion' => $identificacion, 'referencia' => $referencia, 'importe' => $_POST['importe']);

echo json_encode($respuesta);
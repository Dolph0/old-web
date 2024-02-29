<?php
// El script conversion.php que convierte los datos del formulario HTML 
// en los símbolos substitutivos en la plantilla RTF

//calculando valores de algunos cuadros
//del formulario en base de datos que proceden
//de otros cuadros de este formulario;
//usando la matriz superglobal llamada $_POST,
//recogiendo datos del formulario enviados por
//el método POST
function calculate()
{
    $_POST["sum_m"] = $_POST["lim"] * $_POST["rat"];
    $_POST["day"] = $_POST["res"] + $_POST["sic"] +     $_POST["bus"];
    $_POST["sum_l"] = round($_POST["sum_m"]/22, 2);
    $_POST["sum_d"] = round($_POST["day"] * $_POST["sum_l"], 2);
    $_POST["pay"] = round($_POST["sum_m"] - $_POST["sum_d"], 2);
}

//convirtiendo atributos de la plantilla en valores de los cuadros del formulario
function convert($content)
{
    foreach($_POST as $symbol=>$value)
    $content = str_replace("[<".strtoupper($symbol).">]",$value,$content);
    return $content;
}

//generando la cabecera para facilitar la navegadora
//en seleccionar la aplicación correcta

//tomando el nombre de la plantilla
$file = $_GET["doc"].".doc";

header("Content-Type: application/msword");
header("Content-Disposition: inline;");

calculate();

//abriendo plantilla y tomando su contenido
$fp = fopen($file,"r");
$content = fread($fp,filesize($file));
$content = convert($content);

//mostrando documento
print $content;
?>

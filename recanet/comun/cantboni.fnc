<?

// Devuelve una cantidad "bonita", poniendole decimales si tiene, y
// añadiendo algo de cosmética (como puntos en los miles)
function cantboni ($cant) {
   // El parametro de entrada es un entero que representa una cantidad con dos decimales, 
   // sin coma decimal, y devuelve dicha cantidad representada de forma correcta para su
   // visualizacion, con la coma decimal. 
   # Si vale 0, devolvemos "0"
   if (!$cant) return "0";

   # Separación de céntimos
   preg_match ('/.?.$/', $cant, $deci);
   $deci = $deci[0];
   if ($cant < 10)
     $deci = "0$deci";
   $cant = preg_replace ('/.?.$/', '', $cant);
   $cant = ($cant == '')?0:$cant;

   # Cálculo de comas en los miles
   while ($cant != '') {
      preg_match ('/.{0,3}$/', $cant, $foo);
      $resultado = $foo[0] . (($resultado != '')?'.':'') . $resultado;
      $cant = preg_replace ('/.{0,3}$/', '', $cant);
   }
   if ($resultado == '') $resultado = 0;
   
   if ($deci=='0') return "$resultado";
   else return "$resultado,$deci";
}
?>

<?

// Comprueba si el código cuenta cliente (CCC) es correcto,
// es decir, que se cumple la autenticidad dada por el 
// dígito de control.
// La función es lógica, devolviendo verdadero si el número
// es correcto y falso en caso contrario

// Para el cálculo de cada uno de los dos dígitos de control
// se emplea el módulo 11. Por consiguiente, la suma de los 
// productos obtenidos de multiplicar las cifras de las 
// informaciones a verificar por sus correspondientes pesos
// se divide entre 11. La diferencia del resto obtenido de la
// división y 11 será el dígito de control correspondiente. 
// Dado que este control está computado por una sola cifra, si
// el dígito de control resultante fuera 10, se aplicará en 
// su lugar el dígito 1, y si fuera 11 el 0.
// Los pesos utilizados son los mismos para el cálculo de los
// dos dígitos y son comenzando por las unidades 6,3,7,9,10,5,
// 8,4,2 y 1) si bien los 3 últimos no tienen utilidad para 
// calcular el dígito entidad oficina.
function compdico ($cccx) {
  
  if (strlen ($cccx) != 20) return FALSE;

  $pesos = array (6,3,7,9,10,5,8,4,2,1);

  // Entidad - oficina
  $entiofic = substr ($cccx,0,8);
  // Caracteres de control
  $caracont = substr ($cccx,8,2);
  // Cuenta
  $cuen = substr ($cccx,10);

  $car1 = 0;
  $car2 = 0;  // Caracteres de ctrol a calcular

  $tam = strlen($entiofic)-1;
  for ($i=$tam; $i>=0; $i--) {
    $car1 += ($entiofic[$i] * $pesos[$tam-$i]);
  }

  $tam = strlen ($cuen) - 1;
  for ($i=$tam; $i>=0; $i--) {
    $car2 += ($cuen[$i] * $pesos[$tam-$i]);
  }
  
  $car1 = 11 - ($car1 % 11);
  $car2 = 11 - ($car2 % 11);

  if ($car1 == 11) { $car1 = 0; }
  if ($car1 == 10) { $car1 = 1; }
 
  if ($car2 == 11) { $car2 = 0; }
  if ($car2 == 10) { $car2 = 1; }

  if ("$car1$car2" != $caracont) { return FALSE; }
  else return TRUE; 
}
?>

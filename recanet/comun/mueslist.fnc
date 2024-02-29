<?
function mueslist( $nomb, $query, $valor, $onchange="", $gui=0 , $on = 1) {
  // muestra una lista de selecci�n con un solo dato a partir de varios querys
  // $nomb es el nombre del campo
  // $query es una ristra con varios querys enlazados por la palabra union
  //   cuyos resultados unidos ser�n los valores de la lista
  //   (posiciones 0 y 1) para parejas c�digo-valor
  //   se reducir�a a un s�lo query si Mysql admitiera la clausula SQL union
  // $valor es el valor por omisi�n de la lista
  // onchange es la cadena que queremos que se ejecute cuando se cambia el
  // select. En caso de ser "" (valor por defecto) no se a�adir� el onChange.
  // $gui activa funciones de interfaz de usuaio
  // $on nos permite desactivar la modificaci�pn del combo
  $print='';
  if ($onchange != "") {
    $cade = " onChange=\"".$onchange."\"";
  }
  
  $onoff = '';
  if ($on == 0) $onoff = 'disabled';

  $print.= "<select $onoff class='inputform' name='$nomb' size='1' $cade>
    <option value=''></option>\n";
  // descompone los querys de entrada
  $listquery = explode( " union ", $query );
  $i = 0;
  while ( $listquery[ $i ] ) {
    $resp = sql( $listquery[ $i ] );
    $i++;
    if (!is_array ($resp)) continue;
    while ( $regi = each( $resp ) ) { 
      $camp = $regi[ 1 ];
      //mens("<br> depurando camp0=$camp[0] camp1=$camp[1]<br>");
      $print.= "    <option value = '$camp[0]' ";
      if ( $camp[0] == $valor ) { $print.=( " selected" ); }
      $print.= ">$camp[1]</option>\n";
    }
  }
  $print.="</select>\n";

  # Esto s�lo funciona si se llama antes a opci. Activa las opciones de
  # componente de interfaz de usuario (por ahora, s�lo a�ade a la variable JS
  # limpieza una l�nea que pone al valor inicial la lista
  if ($gui) {
    $print.= "<script>limpieza += ' form.$nomb.value = \"\"; ';</script>";
  }
       echo $print;
}

?>

<?
function cheqsesi() {
 
  // chequea sesi�n y recupera el siguiente vector asociativo
  //   $sesi[sesicodizona] => codigo de la zona del usuario de la sesion actual
  //   $sesi[sesicodiusua] => c�digo del usuario de la sesion actual
  //   Los c�digos de usuario y de zona se recoger�n de una petici�n SQL.
  global $sesicodiusua;

  if ( !session_is_registered( "sesicodiusua" ) ) {
    // Se comprueba si el usuario se ha autentificado correctamente,
    // lo que implicara que la variable codiusua
    // estara registrada en su sesion. En caso contrario, 
    // se le deniega el acceso, mediante la funcion Segu
   //segu("Intento de acceso con usuario no registrado");

   //Llamo a Mens en lugar de a segu porque la llamada a segu provocaria bucle infinito 
   Mens("Intento de acceso con usuario no registrado");
   //No hay que hacer session_destroy porque no se habia registrado el usuario
   print("<script language='JavaScript'>window.top.navigate(\"" . cheqroot("index.php") . "\")</script>");   
   exit; //No le dejamos entrar al portal
  }
  else {
    // Asignamos los campos de la sesi�n
    $sesi[sesicodiusua] = $sesicodiusua;

    // A�adimos los campos de zona y de grupo
    $peti = sql ("SELECT codiambi,desa FROM usua WHERE codiusua='$sesicodiusua'"); 
    $peti = $peti [0];
    
    $sesi[sesicodizona] = $peti[0];

     
    // Comprobamos si la cuenta ha sido desactivada por otro 
    // usuario con privilegios 
    if ($peti[1]) {
       Mens ("Su cuenta ha sido desactivada");
       print("<script language='JavaScript'>window.top.navigate(\"" . cheqroot("index.php") . "\")</script>");
       exit; // Lo sacamos del portal
    }
  } 
  return($sesi);
}

?>

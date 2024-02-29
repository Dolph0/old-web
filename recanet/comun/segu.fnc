<?

function segu( $mens ) {
  // guarda un registro de los intentos de acceso indebidos
  // y fuerza la desconexión del usuario
  // $mens indica el motivo
  $estaurl = estaurlx(); 
  $sesi = cheqsesi(); 
  $query = sprintf( "insert into segu (codiusua, mome, mens, url, ip) 
    VALUES ('$sesi[sesicodiusua]', '%s', '$mens', '$estaurl', '%s')",
    date( "Y-m-d, H:i:s", time() ), getenv("REMOTE_ADDR") );
  sql($query);
  // desactiva la cuenta de usuario
  $desactivar=0; // Esta variable dice si hay que mostrar el mensaje de cuenta desactivada
  if ($sesi[sesicodiusua]!="") {
     // depurando mientras desarrollamos lo desactivamos
     // $query = "update usua set desa = 1 where codiusua = '$sesi[sesicodiusua]'";
     // sql($query);
    //Mens( $mens );
    $desactivar=1; // Hay que desactivar esta cuenta por portarse mal
  } 
  Mens( $mens ); // Mensaje que se le pasa a segu
  if($desactivar) 
    Mens( "Cuenta bloqueada" );
  session_destroy(); // cierra la sesión y presenta una página en blanco
  //Debe saltar a index para comenzar una nueva sesion
  print("<script language='JavaScript'>window.top.navigate(\"" . cheqroot("index.php") . "\")</script>");
  //Este exit evita retornar a funciones como usua.php cuando se intenta guardar
  //un registro sin permiso. Si no pusiera exit, guardaria el registro.
  exit;
}
?>

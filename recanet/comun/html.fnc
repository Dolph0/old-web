<?

# Funciones que facilitan producir HTML com�n (mensajes, JavaScript, etc.)

function mens( $mens ) {
  // muestra un mensaje en una ventana emergente
  #cabecera("");
  # Por si a alguien se le ocurre escribir comillas simples
  $mens = preg_replace ("/'/", "\\'", $mens);
  print("<script language = 'JavaScript'> alert('$mens')</script>\n");
}


# Imprime un programa
function script ($prog, $leng = '') {
  print "<script" . ($leng?"language='$leng'":"") . ">$prog</script>";
}


// Inserta un salto de p�gina en una p�gina php
function salto () {
  echo "<h1 class=SaltoDePagina>&nbsp;</h1>\n";
}
?>

<?
// Crea un enlace mediante el cual se lanza una
// ventana por medio de Javascript

function muesvent ($urlx,$text,$anch,$alto,$posi)
{
  // Se codifca como dirección de internet
  $urlx = urlencode ($urlx);
  // Se restauran los signos que deben quedar como antes
  $urlx = ereg_replace ("%3F","?",$urlx);
  $urlx = ereg_replace ("%3D","=",$urlx);

  echo ("<a href='' onClick=\"window.open ('$urlx','hija','location=no,menubars=no,toolbars=no,resizable=no,scrollbars=yes,top=$posi,left=$posi,width=$anch,height=$alto'); return false;\">$text</a>");
}

?>

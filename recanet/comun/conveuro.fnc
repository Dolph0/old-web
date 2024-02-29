<?
function conveuro ($pesetas) {
  $valor = (float) $pesetas / 166.386;
  return number_format ($valor,2,".","");
}
?>

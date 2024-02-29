<?
function combometodo ($valor) {
  $retornar="
             <select class='inputform' name='_metovalo' size='1'  onChange=\"metodo()\">
                <option value = 'SI' ";if ($valor=='SI')$retornar.=" selected";$retornar.=">PADRON</option>
                <option value = 'NO' ";if ($valor=='NO')$retornar.=" selected";$retornar.=">PONENCIA DE VALORES</option>
             </select>";
  return $retornar;
  
}

function calculocuota ($estado='M') {
 global

 //Cálculo con Ponencia de Valores
 $_metovalo,
 $_supesola,
 $_valocatam2,
 $_cuotpart;

 if ($estado=='M') {
 ?>
  <table cellpadding=2 cellspacing=1 border=0 width=100%>
    <tr>
      <td colspan=2  class=izqform>Valor catastral del suelo </td>
    </tr>
    <tr>
      <td class=izqform width=20%>Método</td>
      <td class=derform>
       <table cellpadding=2 cellspacing=1 border=0>
        <tr><td>
         <? 
          echo combometodo ($_metovalo);
          if ($_metovalo == 'NO') $display="display:block";
          else $display="display:none";
         ?>
         </td></tr>
       </table>
     <div name="valores" id="valores" style="<?echo $display?>"> 
        <table cellpadding=4 cellspacing=1 border=0 width=100%>
            <tr>
              <td><b>Superficie del solar</b></td>
              <td><b>Valor catastral del suelo / m<sup>2</sup></b></td>
              <td colspan=2><b>Cuota participación de<br> elementos comunes</b></td>
            </tr>
            <tr>
              <td><? cajatexto ('_supesola', $_supesola, 'deci') ?> m<sup>2</sup></td>
              <td><? cajatexto ('_valocatam2', $_valocatam2, 'euro') ?> €</td>
              <td colspan=2><? cajatexto ('_cuotpart', $_cuotpart, 'deci') ?></td>
            </tr>
         </table>
       </div>
     </td>
    </tr>

    <tr><td>
      &nbsp;
    </td></tr>
  </table>
  <?
  }
}

//-----------------------------------------------------------------
// Devuelve el porcentaje del periodo segun los años transcurridos
//
// Entradas:
//   $codiayun => Ayuntamiento  
//   $fechdeve => Fecha del devengo (AAAA-MM-DD)
//   $aniotran => Años transcurridos
// 
// Salidas:
//   El porcentaje de aplicacion o -1 si hubo algun error. 
//-----------------------------------------------------------------
function porcperiplus ($codiayun, $fechdeve, $aniotran) {
  // Inicializo
  $porcperi = -1;
  
  if ($aniotran <= 0) {
    $porcperi = 0;
  } else {
    $ejerperi = substr ($fechdeve, 0, 4);

    $sql_porcperi = "SELECT porcperi 
                     FROM oiplporcincr 
                     WHERE codiayun = $codiayun
                       AND ejer = '$ejerperi'
                       AND inicperi <= '$fechdeve'
                       AND finaperi >= '$fechdeve'
                       AND limiinfe <= $aniotran 
                       AND limisupe >= $aniotran";
    //print "DEPURANDO: ".$sql_porcperi;
                       
    $porcperi = sql ($sql_porcperi);
   
    if (is_array ($porcperi)) $porcperi = -1;
  }
  
  return $porcperi;
}

?>


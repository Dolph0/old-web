<?

$titulo ="INFORMACIÓN ";
switch ($tipoobje)
 {
  case 'volu':$titulo.="VOLUNTARIA";break;
  case 'prov':$titulo.="PROVIDENCIA APREMIO";break;
 }

   $tipoobje = sql ("SELECT estanoti$tipoobje as estado,
                          fechnoti$tipoobje as fechanoti,
                          dato$tipoobje     as datos,
                          fechinic$tipoobje as fechainicio,
                          fechfina$tipoobje as fechafin,
                          agennoti$tipoobje as agente
                     FROM carg
                   WHERE codiayun = '$codiayun' AND
                         codiconc = '$codiconc' AND
                         ejer     = '$ejer'     AND
                         numedocu = '$numedocu'");


if (is_array ($tipoobje)) $tipoobje = each ($tipoobje);
 $tipoobje = $tipoobje [value];
$tipoobje[agente]=sql ("SELECT nomb FROM agennoti WHERE codiagen='$tipoobje[agente]'");
#if (!$tipoobje[agente]) $tipoobje[agente]="Sin agente asociado";
$tipoobje[estado]=sql ("SELECT nomb FROM estanoti WHERE codiestanoti='$tipoobje[estado]'");
$tipoobje[fechanoti]=mostrarfecha($tipoobje[fechanoti]);
$tipoobje[fechainicio]=mostrarfecha($tipoobje[fechainicio]);
$tipoobje[fechafin]=mostrarfecha($tipoobje[fechafin]);

?>
   <tr>
     <td colspan=2 class=izqform><? echo $titulo?></td>
   </tr>
   <tr>
     <td class=izqform>Estado</td>
     <td class=derform><? print "$tipoobje[estado]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Fecha Notificacion</td>
     <td class=derform><? print "$tipoobje[fechanoti]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Fecha Inicio</td>
     <td class=derform><? print "$tipoobje[fechainicio]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Fecha Final</td>
     <td class=derform><? print "$tipoobje[fechafin]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Agente</td>
     <td class=derform><? print "$tipoobje[agente]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Observaciones</td>
     <td class=derform><? print "$tipoobje[datos]"; ?></td>
   </tr>

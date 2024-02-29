<?

$titulo ="INFORMACIÓN DEL CARGO";

   $tipoobje = sql ("SELECT fechcarg,horacarg,usuacarg,numecarg
                     FROM carg
                   WHERE codiayun = '$codiayun' AND
                         codiconc = '$codiconc' AND
                         ejer     = '$ejer'     AND
                         numedocu = '$numedocu'");
if (is_array ($tipoobje)) $tipoobje = each ($tipoobje);
$tipoobje = $tipoobje[value];

$tipoobje[fechcarg]=mostrarfecha($tipoobje[fechcarg]);

$tipoobje[usuacarg]=sql ("SELECT nombcomp FROM usua WHERE codiusua='$tipoobje[usuacarg]'");



?>
   <tr>
     <td colspan=2 class=izqform><? echo $titulo?></td>
   </tr>
   <tr>
     <td class=izqform>Número del Cargo</td>
     <td class=derform><? print "$tipoobje[estasusp]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Fecha</td>
     <td class=derform><? print "$tipoobje[fechcarg]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Hora</td>
     <td class=derform><? print "$tipoobje[horacarg]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Usuario</td>
     <td class=derform><? print "$tipoobje[usuacarg]"; ?></td>
   </tr>

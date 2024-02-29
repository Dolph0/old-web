<?

$titulo ="INFORMACIÓN SUSPENSIÓN";

   $tipoobje = sql ("SELECT estasusp,perisusp,fechestasusp,caussusp,datosusp
                     FROM carg
                   WHERE codiayun = '$codiayun' AND
                         codiconc = '$codiconc' AND
                         ejer     = '$ejer'     AND
                         numedocu = '$numedocu'");
if (is_array ($tipoobje)) $tipoobje = each ($tipoobje);
$tipoobje = $tipoobje[value];

$tipoobje[estasusp]=sql ("SELECT nomb FROM estasusp WHERE codiestasusp='$tipoobje[estasusp]'");

if ($tipoobje[perisusp]=='V') $tipoobje[perisusp]='Voluntaria';
if ($tipoobje[perisusp]=='E') $tipoobje[perisusp]='Ejecutiva';

$tipoobje[fechestasusp]=mostrarfecha($tipoobje[fechestasusp]);

$tipoobje[caussusp]=sql ("SELECT nomb FROM caususp WHERE codicaus='$tipoobje[caussusp]'");


?>
   <tr>
     <td colspan=2 class=izqform><? echo $titulo?></td>
   </tr>
   <tr>
     <td class=izqform>Estado</td>
     <td class=derform><? print "$tipoobje[estasusp]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Periodo</td>
     <td class=derform><? print "$tipoobje[perisusp]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Fecha</td>
     <td class=derform><? print "$tipoobje[fechestasusp]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Causa</td>
     <td class=derform><? print "$tipoobje[caussusp]"; ?></td>
   </tr>
   <tr>
     <td class=izqform>Observaciones</td>
     <td class=derform><? print "$tipoobje[datosusp]"; ?></td>
   </tr>

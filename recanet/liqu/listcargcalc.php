<?
include "comun/func.inc";
include "comun/fecha.fnc";
include "comun/dire.fnc";

$sesi = cheqsesi();

cabecera("Unidades que formarán parte del padrón");
print "<hr>\n";
echo "<body  onload=\"window.focus();\">";

// Obtengo el historial del tercero segun los parámetros pasados

if ($numeregi == ''){
   mens("Debe especificar un padrón.");
   print "<script>self.close();</script>";
   exit();
}

$query = "SELECT conctrib.nomb as nomb, liqupadr.anio as anio, liqupadr.peri as peri FROM tipoobje INNER JOIN (conctrib INNER JOIN liqupadr ON conctrib.codiconc = liqupadr.codiconc) ON tipoobje.tipoobje = conctrib.tipoobje WHERE liqupadr.numeregi = $numeregi";

$resp = sql($query);
if ($resp){
	$resp = each($resp);
	$concnomb = $resp[value][nomb];
	$anio = $resp[value][anio];
	$peri = $resp[value][peri];	
}else{
   mens("No se encuentra el concepto para este padrón.");
   print "<script>self.close();</script>";
   exit();	
}

$query = "SELECT liqupadrobje.codisequ,
                 liqupadrobje.codipadr, 
                 liqupadrobje.coditrib, 
                 liqupadrobje.codiobje, 
                 liqupadrobje.deud, 
                 liqupadrobje.cuot, 
                 tercdato.nifx as nifx, 
                 tercdato.nomb as nomb 
          FROM (liqupadrobje INNER JOIN
	       (liqupadr INNER JOIN conctrib
		       ON liqupadr.codiconc = conctrib.codiconc) 
		       ON liqupadrobje.codipadr = liqupadr.numeregi) INNER JOIN
		       (tercobje INNER JOIN tercdato ON tercobje.coditerc = tercdato.coditerc)
              ON liqupadrobje.codiobje = tercobje.codiobje AND 
                 tercobje.tipoobje = conctrib.tipoobje 
          WHERE liqupadrobje.codipadr = $numeregi
		 		 ORDER BY liqupadrobje.coditrib, tercdato.nomb";

$resp = sql($query);

if ( is_array( $resp) ) {

      set_time_limit(0);
      $contador=-1;
      $deudatotal = 0;
      while ( $regi = each( $resp) ) {
        $contador++;
        $camp = $regi[value];
        if ($contador == 0){
           print "<center><table cellpadding=4 cellspacing=1 border=0 width=100%>\n";
           print "<tr class=cabeform><td>Concepto</td><td>Ejercicio</td><td>Periodo</td></tr>";
           print "<tr class=cabeform><td>$concnomb</td><td>$anio</td><td>$peri</td>";
           print "</tr>";
           print "</table></center>";
           print "<center><table cellpadding=4 cellspacing=1 border=0 width=100%>\n";
           print "<tr class=tituform><td>Código</td><td>Sujeto pasivo</td><td>Cuota</td><td>Deuda</td></tr>";
			
        }
        print "<tr class=derform>";
        print "<td>";
        print $camp[coditrib];
        print "</td>";
        print "<td>";
        print $camp[nifx] . " " . $camp[nomb];
        print "</td>";
        print "<td align='right'>";
        print impoboni($camp[cuot]);
        print "</td>";
        print "<td align='right'>";
        print impoboni($camp[deud]);
        $deudatotal += $camp[deud];
        print "</td>";
        print "</tr>";
      }
      if ($contador > 0){
      	print "<tr class='izqform'><td>Deuda total</td><td colspan=2>&nbsp;</td><td>". impoboni($deudatotal) . "</td><tr>";
      }
      print "</table>\n</center>";

} else {
    $mens = "No existen unidades para este padrón\n";
  	print $mens;
}

include "comun/pie.inc";
?>

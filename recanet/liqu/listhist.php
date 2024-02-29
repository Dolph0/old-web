<?
//--------------------------------------------
//   Informacion del padron liquidado y de la
// salida de adeudos si la hubiera.
//
// Entrada:
//   $numeregi     => Para estado de padrones
//   $codifichno19 => Salida de adeudos
//--------------------------------------------

include "comun/func.inc";
include "comun/fecha.fnc";
include "comun/dire.fnc";

$sesi = cheqsesi();

cabecera("Detalle del padrón ");
print "<hr>\n";
//echo "<body  onload=\"window.focus();\" onBlur=\"window.focus();\">";
echo "<body  onload=\"window.focus();\">";

// Obtengo el historial del tercero segun los parámetros pasados
if ($codifichno19 == '' && $numeregi == ''){
   mens("Debe especificar un padrón.");
   print "<script>self.close();</script>";
   exit();
}

if ($numeregi > 0) {
  $sufijo = '001';
} else {
  $sufijo = sql ("SELECT fichno19.tipofichno19 FROM fichno19 WHERE fichno19.codifichno19 = $codifichno19");

  if ($sufijo == '001') {
     $numeregi = sql ("SELECT liqufichno19.numeregi FROM liqufichno19 WHERE liqufichno19.codifichno19 = $codifichno19");
  }
}

switch ($sufijo) {
  case '001': // Liquidaciones
    $query = "SELECT liqupadr.codiayun,
                     liqupadr.codiconc,
                     liqupadr.numeregi, 
                     liqupadr.anio, liqupadr.peri, 
                     liqupadr.fechiniccarg, liqupadr.horainiccarg, 
                     usua.nomb as usuainiccarg,
                     liqupadr.numecalc, liqupadr.numeexcl, 
                     liqupadr.numeexen, liqupadr.numeproc,
                     liqupadr.numecarg, liqupadr.numeboni, 
                     liqupadr.numedomi, liqupadr.domineto, 
                     liqupadr.deudneto, liqupadr.cuotneto,
                     liqupadr.fechfinacarg, liqupadr.horafinacarg,
                     liqupadr.liqudivi,
                     fichno19.codifichno19,
                     fichno19.descr,
                     fichno19.fechinicno19, fichno19.horainicno19, 
                     fichno19.usuainicno19, fichno19.numeno19,
                     fichno19.totaimpono19, fichno19.fechentrno19,
                     fichno19.fechfinano19, fichno19.horafinano19, 
                     fichno19.sucureceno19, fichno19.fechcargno19,
                     conctrib.liqudivi,
                     conctrib.tipo as conctribtipo
    					FROM ((liqupadr LEFT JOIN usua ON liqupadr.usuainiccarg = usua.codiusua) 
    					      LEFT JOIN liqufichno19 ON liqupadr.numeregi = liqufichno19.numeregi)
    					     LEFT JOIN fichno19 ON fichno19.codifichno19 = liqufichno19.codifichno19,
    					     conctrib
    					WHERE liqupadr.numeregi = $numeregi 
    					  AND conctrib.codiconc = liqupadr.codiconc ";
    $resu = sql($query);            				
    break;
    
  case '002': // Aplaz/fracc
    $query = "SELECT fichno19.codiayun,
                     fichno19.codifichno19,
                     fichno19.descr,
                     fichno19.fechinicno19, fichno19.horainicno19, 
                     fichno19.usuainicno19, fichno19.numeno19,
                     fichno19.totaimpono19, fichno19.fechentrno19,
                     fichno19.fechfinano19, fichno19.horafinano19, 
                     fichno19.sucureceno19, fichno19.fechcargno19				
    					FROM fichno19
    					WHERE fichno19.codifichno19 = $codifichno19 ";
$resu = sql($query);            					
    break;
    
  default:
    $resu = false;
}

if ($resu){
	$regino19 = each($resu);
	$datono19 = $regino19[value];

  list ($conc, $anio, $peri) = explode ('|', $datono19[descr]);
	
	$salida .= "<table width=100% cellpadding=4 cellspacing=1 border=0>";
	switch ($sufijo) {
	  case '001': // LIQUIDACIONES
	$salida .= "<tr><td class='cabeform'>Concepto</td><td class='cabeform'>Año</td><td class='cabeform'>Periodo</td></tr>";
    	$salida .= "<tr><td class='derform'>$conc</td><td class='derform'>$anio</td><td class='derform'>$peri</td></tr>";
	$salida .= "<tr><td class='cabeform'>Fecha de inicio</td><td class='cabeform'>Hora de inicio</td><td class='cabeform'>Usuario</td></tr>";
    	$salida .= "<tr><td class='derform'>" . mostrarFecha($datono19[fechiniccarg]) . "</td><td class='derform'>" . $datono19[horainiccarg] . "</td><td class='derform'>" . $datono19[usuainiccarg] . "</td></tr>";
	$salida .= "<tr><td class='cabeform'>Fecha finalización</td><td class='cabeform'>Hora finalización</td><td class='cabeform'>Usuario</td></tr>";
    	$salida .= "<tr><td class='derform'>" . mostrarFecha($datono19[fechfinacarg]) . "</td><td class='derform'>" . $datono19[horafinacarg] . "</td><td class='derform'>" . $datono19[usuainiccarg] . "</td></tr>";
	$salida .= "<tr><td class='cabeform'>Objetos procesados</td><td class='cabeform'>Excluidos</td><td class='cabeform'>Exentos</td></tr>";
    	$salida .= "<tr><td class='derform'>" . $datono19[numeproc] . "</td><td class='derform'>" . $datono19[numeexcl] . "</td><td class='derform'>" . $datono19[numeexen] . "</td></tr>";
    	$salida .= "<tr><td class='cabeform'>Recibos</td><td class='cabeform'>Domiciliaciones (nº / importe)</td><td class='cabeform'>Dividir cargo</td></tr>";
    	$salida .= "<tr><td class='derform'>" . $datono19[numecarg] . "</td><td class='derform'>" . $datono19[numedomi] ." / ". impoboni($datono19[domineto]) ."</td><td class='derform'>" . ($datono19[liqudivi] == 1?'SI':'NO') . "</td></tr>";
    	$salida .= "<tr><td class='cabeform'>Bonificaciones</td><td class='cabeform'>Cuota total/integra</td><td class='cabeform'>Deuda total/liquida</td></tr>";
    	$salida .= "<tr><td class='derform'>" . $datono19[numeboni] . "</td><td class='derform'>" . impoboni($datono19[cuotneto]) . "</td><td class='derform'>" . impoboni($datono19[deudneto]) . "</td></tr>";
    	$salida .= "<tr><td class='cabeform'>Primer deudor</td><td class='cabeform'>Ultimo deudor</td><td class='cabeform'></td></tr>";
    	
    	if ($datono19[liqudivi] == 1) $periliqu_padr = "'P1', 'P2', 'PA'";
    	else $periliqu_padr = "'$datono19[peri]'";
    	
    	$sql_limite_deudores = "SELECT min(tercdato.nomb) as primer, max(tercdato.nomb) as ultimo
                              FROM carg, tercdato
                              WHERE carg.codiayun = $datono19[codiayun]
                                AND carg.codiconc = $datono19[codiconc]
                                AND carg.anio = '$datono19[anio]'
                                AND carg.periliqu IN  ($periliqu_padr )
                                AND carg.modoliqu = 'PER'
                                AND tercdato.nifx = carg.nifx";
      $resu_limite_deudores = sql ($sql_limite_deudores);
      
      if (is_array ($resu_limite_deudores)) {
        $regi_limite_deudores = each ($resu_limite_deudores);
        $dato_limite_deudores = $regi_limite_deudores[value];
        
        $primero = $dato_limite_deudores[primer];
        $ultimo = $dato_limite_deudores[ultimo];
      } else {
        $primero = 'NO DEFINIDO';
        $ultimo = 'NO DEFINIDO';
      }
      
    	$salida .= "<tr><td class='derform'> $primero </td><td class='derform'> $ultimo </td><td class='derform'> </td></tr>";

      // Datos especiales para CATASTRO (URBANA/RUSTICA)
      if ($datono19[conctribtipo] == 'IBIU' || $datono19[conctribtipo] == 'IBIR') {
        switch ($datono19[conctribtipo]) {
          case 'IBIU': 
            $baseimpo = sql ("select sum(base) as base, sum(valocata) as valocata FROM carg, cargoibiurba WHERE carg.codiayun = $datono19[codiayun] AND carg.ejer = '$datono19[anio]' AND carg.codiconc = $datono19[codiconc] AND carg.periliqu in ('P1', 'PA') AND carg.modoliqu='PER' AND carg.codiayun=cargoibiurba.codiayun AND carg.codiconc=cargoibiurba.codiconc AND carg.ejer=cargoibiurba.ejer AND carg.numedocu=cargoibiurba.numedocu ");
            break;
          case 'IBIR': 
            $baseimpo = sql ("SELECT sum(base) as base, sum(valocata) as valocata FROM carg, oibirust WHERE carg.codiayun = $datono19[codiayun] AND carg.ejer = '$datono19[anio]' AND carg.codiconc = $datono19[codiconc] and carg.periliqu in ('P1', 'PA') and carg.modoliqu = 'PER' and oibirust.numeorde = carg.codiobje; ");
            break;        
        }
        $baseimpo = each($baseimpo);

      	$salida .= "<tr><td class='cabeform'>Base imponible</td><td class='cabeform'>Valor catastral</td><td class='cabeform'></td></tr>";
      	$salida .= "<tr><td class='derform'>".impoboni($baseimpo[value][0])."</td><td class='derform'>".impoboni($baseimpo[value][1])."</td><td class='derform'> </td></tr>";
      }    	     
	    break;
	  
	  case '002': // APLAZ.FRACC
	    break;
	}
	
	reset ($resu);
  while ($regino19_aux = each ($resu)) {
    $datono19_aux = $regino19_aux[value];

  	if ($datono19_aux[codifichno19] > 0){
		$salida .= "<tr><td colspan=3>&nbsp;</td></tr>";
		$salida .= "<tr><td class='tituform' colspan=3>Salida de adeudos</td></tr>";
		$salida .= "<tr><td class='cabeform'>Fecha de inicio</td><td class='cabeform'>Hora de inicio</td><td class='cabeform'>Usuario</td></tr>";
     	$salida .= "<tr><td class='derform'>" . mostrarFecha($datono19_aux[fechinicno19]) . "</td><td class='derform'>" . $datono19_aux[horainicno19] . "</td><td class='derform'>" . sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = $datono19_aux[usuainicno19]") . "</td></tr>";
		$salida .= "<tr><td class='cabeform'>Fecha de finalización</td><td class='cabeform'>Hora de finalización</td><td class='cabeform'>Usuario</td></tr>";
     	$salida .= "<tr><td class='derform'>" . mostrarFecha($datono19_aux[fechfinano19]) . "</td><td class='derform'>" . $datono19_aux[horafinano19] . "</td><td class='derform'>" . sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = $datono19_aux[usuainicno19]") . "</td></tr>";
		$salida .= "<tr><td class='cabeform'>Objetos procesados</td><td class='cabeform'>Sucursal receptora</td><td class='cabeform'>Fecha de cargo en cuenta</td></tr>";
  		$salida .= "<tr><td class='derform'>" . $datono19_aux[numeno19] . "</td><td class='derform'>" . sql("SELECT banc.nomb||' - '||sucubanc.nomb FROM sucubanc INNER JOIN banc ON sucubanc.codibanc = banc.codibanc WHERE sucubanc.codisucu = $datono19_aux[sucureceno19]") . "</td><td class='derform'>" . mostrarFecha($datono19_aux[fechcargno19]) . "</td></tr>";
  		$salida .= "<tr><td class='cabeform'>Fecha de entrega</td><td class='cabeform'>Importe domiciliado</td><td class='cabeform'></td></tr>";
  		$salida .= "<tr><td class='derform'>" . mostrarfecha($datono19_aux[fechentrno19]) . "</td><td class='derform'>" . impoboni($datono19_aux[totaimpono19]) . "</td><td class='derform'> </td></tr>";
  	}
	}
	
	$salida .= "</table><br>";
	
	print $salida;	 
}

print "<center><h5>Histórico</h5></center>";

switch ($sufijo) {
  case '001': // Liquidaciones
    $query = "SELECT liqupadrhist.codisequ, liqupadrhist.codiayun, liqupadrhist.codiconc, liqupadrhist.anio, liqupadrhist.peri, 
                     liqupadrhist.fechiniccalc, liqupadrhist.horainiccalc, liqupadrhist.usuainiccalc, liqupadrhist.fechfinacalc,
                     liqupadrhist.fechintecalc, liqupadrhist.horaintecalc, liqupadrhist.usuaintecalc, liqupadrhist.causintecalc, 
                     liqupadrhist.fechintecarg, liqupadrhist.horaintecarg, liqupadrhist.causintecarg, 
                     liqupadrhist.fechinicno19, liqupadrhist.horainicno19, liqupadrhist.usuainicno19, 
                     liqupadrhist.fechinteno19, liqupadrhist.horainteno19, liqupadrhist.usuainteno19, liqupadrhist.causinteno19, 
                     liqupadrhist.fechfinano19, liqupadrhist.horafinano19, 
                     liqupadrhist.fechcargno19, 
                     liqupadrhist.numeno19, 
                     liqupadrhist.sucureceno19 
              FROM liqupadrhist	INNER JOIN liqupadr 
                   ON liqupadrhist.codiayun = liqupadr.codiayun AND 
                      liqupadrhist.codiconc = liqupadr.codiconc AND 
                      liqupadrhist.peri = liqupadr.peri
    		 		  WHERE liqupadr.numeregi = $datono19[numeregi]";

$resp = sql($query);
    break;
  
  case '002': // Aplaz/fracc
    $resp = false; // No existe historico de adeudos para aplaz/fracc
    break;
    
  default:
    $resp = false;
}

if ( is_array( $resp) ) {
      set_time_limit(0);
      $contador=-1;
      $deudatotal = 0;
      while ( $regi = each( $resp) ) {
        $contador++;
        $regi = $regi[value];
        if ($contador == 0){
           print "<center><table cellpadding=4 cellspacing=1 border=0 width=100%>\n";
           print "<tr class=cabeform><td>Cálculo deuda</td><td colspan=2>Usuario</td></tr>";
           print "<tr class=cabeform><td>Interrupción cálculo</td><td>Usuario interrupción</td><td>Causa</td></tr>";
           print "<tr class=cabeform><td>Interrupción liquidación</td><td>Usuario interrupción</td><td>Causa</td></tr>";
           print "<tr class=cabeform><td>Fecha salida adeudo</td><td>Hora salida adeudo</td><td>Usuario</td></tr>";
			
        }
        print "<tr class=derform>";
        print "<td>";
        if ($regi[fechiniccalc] != '0001-01-01')
	        print mostrarFecha($regi[fechiniccalc]) . " " . $regi[horainiccalc];
        else
        	print "&nbsp";
        print "</td>";
        print "<td colspan=2>";
        if ($regi[usuainiccalc] > 0){
        	print sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = $regi[usuainiccalc]");
        }
        print "</td>";
        print "</tr>";
        print "<tr class=derform>";
        print "<td>";
        if ($regi[fechintecalc] != '0001-01-01')
        	print mostrarFecha($regi[fechintecalc]) . " " . $regi[horaintecalc];
        else
        	print "&nbsp";
        print "</td>";
        print "<td>";
        if ($regi[usuaintecalc] > 0){
        	print sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = $regi[usuaintecalc]");
        }
        print "</td>";
        print "<td>";
        print "$regi[causintecalc]";
        print "</td>";
        print "</tr>";
        
        print "<tr class=derform>";
        print "<td>";
        if ($regi[fechintecarg] != '0001-01-01')
        	print mostrarFecha($regi[fechintecarg]) . " " . $regi[horaintecarg];
        else
        	print "&nbsp";
        print "</td>";
        print "<td>";
        if ($regi[usuaintecarg] > 0){
        	print sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = $regi[usuaintecarg]");
        }
        print "</td>";
        print "<td>";
        print "$regi[causintecarg]";
        print "</td>";
        print "</tr>";

        print "<tr class=derform>";
        print "<td>";
        if ($regi[fechinicno19] != '0001-01-01')
        	print mostrarFecha($regi[fechinicno19]);
        else
        	print "&nbsp";
        print "</td>";
        print "<td>";
        if ($regi[fechinicno19] != '0001-01-01')
        	print $regi[horainicno19];
        else
        	print "&nbsp";
        print "</td>";
        print "<td>";
        if ($regi[usuainicno19] > 0){
        	print sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = $regi[usuainicno19]");
        }
        print "</td>";
        print "</tr>";
        
        print "<tr></tr>";
      }
      print "</table>\n</center>";

} else {
  switch ($sufijo) {
    case '001': // LIQUIDACIONES
      $mens = "<center><table cellpadding=4 cellspacing=1 border=0 width=100%>\n";
      $mens .= "<tr><td class=tituform colspan=3>No existe histórico para este padrón</td></tr>";
      $mens .= "</table>\n</center>";
      break;
      
    case '002': // APLAZ.FRACC  
      $mens = "<center><table cellpadding=4 cellspacing=1 border=0 width=100%>\n";
      $mens .= "<tr><td class=tituform colspan=3>No existe histórico para esta salida de adeudos</td></tr>";
      $mens .= "</table>\n</center>";
      break;
  }

  	print $mens;
}

include "comun/pie.inc";
?>
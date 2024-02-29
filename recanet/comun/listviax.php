<?
include "comun/func.inc";

$sesi = cheqsesi();

cabecera("Elige un inmueble");
print "<hr>\n";
// Este script recibe la sigla de la via como parametro concatenado con el nombre de la via en la
// variable nombvias, por lo que no hay que acceder a la base de datos para obtener la sigl
// Obtengo el listado de inmuebles de esa via

$query= "SELECT nomb, codiinmu, codiviax, nume, letr, esca, plan, puer, refecata, 
         numecarg, caracont, nfij, idenloca FROM inmu WHERE codiayun = '$ayun' ";
if ( $codiviax != "" ) {
  $where .= " AND inmu.codiviax = '$codiviax' ";
  if ( $nume != "" ) { $where .= " AND inmu.nume ~ '^0*$nume$' ";}
  if ( $letr != "" ) { $where .= " AND inmu.letr = '$letr' "; }
  if ( $esca != "" ) { $where .= " AND inmu.esca = '$esca' "; }
  if ( $puer != "" ) { $where .= " AND inmu.puer = '$puer' "; }
  if ( $plan != "" ) { $where .= " AND inmu.plan = '$plan' "; }
}
if ( $refecata && !$codiviax){
    if ( $esca != "" ) { $where .= " AND inmu.esca = '$esca' "; }
    if ( $puer != "" ) { $where .= " AND inmu.puer = '$puer' "; }
    if ( $plan != "" ) { $where .= " AND inmu.plan = '$plan' "; }
}

if ( $refecata != "" ) {    $where .= " AND inmu.refecata = '$refecata' ";
  if ( $numecarg != "" )    $where .= " AND inmu.numecarg = '$numecarg' ";
  if ( $caracont != "" )    $where .= " AND inmu.caracont = '$caracont' ";
  if ( $nfij != "" )        $where .= " AND inmu.nfij = '$nfij' ";
}

if ( $nombinmu != "" ) {  $where .= " AND inmu.nomb = '$nombinmu' ";
  if ( $idenloca != "" )  $where .= " AND inmu.idenloca LIKE '%$idenloca%' ";
}

if ( $codiviax == "" && $refecata == "" && $nombinmu == "" ) {
  $contvias = 0;
  // De esta forma evito obtener un listado de todos los inmuebles de un mismo ayuntamiento
  // cuando no se ha puesto alguna condicion a la via, ref. catastral o nombre del inmueble
} else {
  if ($query) $query.= $where; // Agregamos a la consulta la condicion.. 
  if ($tipoobje=='oibiurba' || $tipoobje=='busq')   $query.=" ORDER BY refecata,numecarg, caracont";
  else   $query.=" ORDER BY codiviax, nomb, nume, letr, esca, plan, puer";
   #  print $query."<br>";
  $resp = sql( $query );
  if ( is_array( $resp ) ) {
    $contvias = count($resp);
  } else {
    $contvias = 0;
  }
}


// Probando...
if ( $tipoobje == 'busq' ) {
  $query2 = "SELECT distinct refecata FROM inmu WHERE codiayun = '$ayun' ".$where;
  $resp2 = sql( $query2 );
  if ( $resp2 ) {
    $contrefe = count( $resp2 );
    if ( $contrefe == 1 ) {
      echo "
        <script>
        if ( opener.document.form.busca.value=='refe' ) {
          opener.document.form.refecata$sufi.value='".$resp2."';
          try { opener.despelecviax () } catch (e) { }
          self.close();
        }
        </script>
      \n";
    }
  }
}


 if (!$nombviax) {
   $nombviax=sql ("SELECT siglvias.nomb||' '||vias.nomb
                     FROM (vias INNER JOIN inmu ON inmu.codiviax=vias.codiviax)
               INNER JOIN siglvias ON vias.codisigl=siglvias.codisigl
                    WHERE inmu.codiayun='$ayun' $where
                 GROUP BY vias.nomb, siglvias.nomb");
   // Si existe mas de uno los separa con comas (no se deberia dar el caso PERO NUNCA SE SABE
   if (is_array ($nombviax)) 
   {
     $temp ='';
     foreach ($nombviax as $v)
     {
      if ($temp) $temp.=', ';
      $temp.=$v;
     }
     $nombviax = $temp;
   }
 } 

if ($tipoobje=='busq'){
      if ($codiviax == "" && $nombinmu == "" && $refecata == ""){ 
         echo "<script>alert('Debe seleccionar una vía, una referencia catastral o un nombre de inmueble');try { opener.despelecviax () } catch (e) { }self.close();window.close();</script>";
      }else{
         echo "\n<body onload='comprefe ($contador)'>\n";
      }
}

if ( is_array( $resp) ) {
  if ( $contvias > 1 ) {
  
      print "<center><table cellpadding=4 cellspacing=1 border=0 width=100%>\n";
      print "<tr>\n<td></td>\n";
      print "<td colspan=11 class='izqform'>".count($resp)."  Inmuebles encontrados en $nombviax</td>\n";
      print "<tr>\n<td></td>\n";
      print "<td colspan=5 class='izqform'>Domicilio Tributario</td>\n";
      print "<td colspan=4 class='izqform'>Referencia Catastral</td>\n";
      print "<td colspan=2 class='izqform'>Inmueble</td>\n";
      print "<tr>\n<td></td>\n";
      print "<td class='izqform' width=5%>Nº</td>\n";
      print "<td class='izqform' width=5%>Letr</td>\n";
      print "<td class='izqform' width=5%>Esca</td>\n";
      print "<td class='izqform' width=5%>Plan</td>\n";
      print "<td class='izqform' width=5%>Puer</td>\n";
      print "<td class='izqform' width=20%>Ref. Catastral</td>\n";
      print "<td class='izqform' width=5%>Nº Cargo</td>\n";
      print "<td class='izqform' width=5%>C.C.</td>\n";
      print "<td class='izqform' width=10%>Nº Fijo</td>\n";
      print "<td class='izqform' width=20%>Nombre</td>\n";
      print "<td class='izqform' width=10%>Ident. local</td></tr>\n";
      

      set_time_limit(0);
    $contador=-1;
      while ( $regi = each( $resp) ) {
        $contador++;
        $camp = $regi[value];
        print "<tr>\n";
        print "<td>
               <input type='button' value='Elegir' onClick='";
  // Si el listado es de OIBIURBA, con el codigo del inmueble nos es suficiente. 
  // el resto de información no nos interesa. 
       if ($tipoobje=='oibiurba' && $camp[codiinmu])
           echo "opener.location.href=\"" . cheqroot("oibi/oibi.php?opci=Mirar&codiinmu=" . $camp[codiinmu]) . "\";";
  // En otro caso, tenemos que poner los valores en las cajas. 
       else 
           echo "long = opener.document.form.codiviax$sufi.length;                                                    for (i=1; i<long; i++) {
                                      if (opener.document.form.codiviax$sufi.options[i].value==\"$camp[codiviax]\"){
                                        opener.document.form.codiviax$sufi.options[i].selected = true;
                                        break;
                                      }
                                    } 
                                    opener.document.form.esca$sufi.value=\"$camp[esca]\";
                                    opener.document.form.codiinmu$sufi.value=\"$camp[codiinmu]\";
                                    opener.document.form.nume$sufi.value=\"$camp[nume]\";
                                    opener.document.form.letr$sufi.value=\"$camp[letr]\";
                                    opener.document.form.plan$sufi.value=\"$camp[plan]\";
                                    opener.document.form.puer$sufi.value=\"$camp[puer]\";
                                    opener.document.form.nombinmu$sufi.value=\"" . preg_replace ('/"/', '\"', $camp[nomb]) . "\";
                                    opener.document.form.idenloca$sufi.value=\"$camp[idenloca]\";
                                    opener.document.form.refecata$sufi.value=\"$camp[refecata]\";
                                    opener.document.form.numecarg$sufi.value=\"$camp[numecarg]\";
                                    opener.document.form.caracont$sufi.value=\"$camp[caracont]\";
                                    opener.document.form.nfij$sufi.value=\"$camp[nfij]\";";

                              echo "try { opener.despelecviax () } catch (e) { }"; 
        print"                      self.close();'>
               </td>\n";
        print "<td class='derform'>$camp[nume]</td>\n";
        print "<td class='derform'>$camp[letr]</td>\n";
        print "<td class='derform'>$camp[esca]</td>\n";
        print "<td class='derform'>$camp[plan]</td>\n";
        print "<td class='derform'>$camp[puer]</td>\n";
        print "<td class='derform'>$camp[refecata]";
        
        if ($tipoobje=='busq') 
          echo "<input type='hidden' name='refecata$contador' value='$camp[refecata]'>";
        
        
        print "</td>\n";
        print "<td class='derform'>$camp[numecarg]</td>\n";
        print "<td class='derform'>$camp[caracont]</td>\n";
        print "<td class='derform'>$camp[nfij]</td>\n";
        print "<td class='derform'>$camp[nomb]</td>\n";
        print "<td class='derform'>$camp[idenloca]</td>\n";
        print "</tr>\n";
      }
      print "</table>\n</center>";


if ($tipoobje=='busq')

echo "
<script>
function comprefe ()
{
  if (opener.document.form.busca.value=='refe')
  {
  var regi;
  regi = $contador;
  // Si el numero es uno o inferior eso es imposible que de error
  // en caso contrario.
  if (regi > 1)
  {
    var i = 0;
    var errorefe ='Se han encontrado referencias distintas en la busqueda\\n';
    var comp ='';      //referencia que se utilizara como patron.
    var erro = false;  //nos indicara si se ha producido un error

    while (i < regi)
    {
      if (eval ('refecata'+i+'.value'))
      {
        //Si no existe nombre a comprobar asignamos el primero que nos encontramos.
        if (!comp){
           comp = eval ('refecata'+i+'.value');
        }else{
          //realizada la asignacion es momento para compararlos y asegurarnos

          if (comp != eval ('refecata'+i+'.value'))
          {
            comp = eval ('refecata'+i+'.value');
            erro = true;
          }
        }
      }
      i++;
    }
    if (erro) { alert (errorefe); return true;}
  }
  // Si no hay errores se retorna falso.
  opener.document.form.refecata$sufi.value=refecata0.value;
  try { opener.despelecviax () } catch (e) { }
  self.close();
  return false;
  }
}
</script>";


// En el caso que exista un unico registro....
  } else {
     if ( $contvias == 1 ) {
       print $resp[0][0];
       print "<form name='form' action='listvias.php'>";
       print "<input type='hidden' value='".$resp[0][0]."' name='nombinmu'>\n";
       print "<input type='hidden' value='".$resp[0][1]."' name='codiinmu'>\n";
       print "<input type='hidden' value='".$resp[0][2]."' name='codiviax'>\n";
       print "<input type='hidden' value='".$resp[0][3]."' name='nume'>\n";
       print "<input type='hidden' value='".$resp[0][4]."' name='letr'>\n";
       print "<input type='hidden' value='".$resp[0][5]."' name='esca'>\n";
       print "<input type='hidden' value='".$resp[0][6]."' name='plan'>\n";
       print "<input type='hidden' value='".$resp[0][7]."' name='puer'>\n";
       print "<input type='hidden' value='".$resp[0][8]."' name='refecata'>\n";
       print "<input type='hidden' value='".$resp[0][9]."' name='numecarg'>\n";
       print "<input type='hidden' value='".$resp[0][10]."' name='caracont'>\n";
       print "<input type='hidden' value='".$resp[0][11]."' name='nfij'>\n";
       print "<input type='hidden' value='".$resp[0][12]."' name='idenloca'>\n";
       print "</form>\n";
       echo "<script language='JavaScript'>
       //opener.document.form.codiinmu$sufi.value = form.codiinmu.value;
       var long = opener.document.form.codiviax$sufi.length;
       for (i=1; i<long; i++) {
         if ( opener.document.form.codiviax$sufi.options[i].value == form.codiviax.value ) {
           opener.document.form.codiviax$sufi.options[i].selected = true;
           //opener.document.form.codiviax$sufi.selectedIndex = i;
           break;
         }
       }";
  // Si el listado es de OIBIURBA, con el codigo del inmueble nos es suficiente. 
  // el resto de información no nos interesa. 
 if ($tipoobje=='oibiurba' && $resp[0][1]) echo "opener.location.href=\"" . cheqroot("oibi/oibi.php?opci=Mirar&codiinmu=".$resp[0][1]) ."\";";
 else 
  echo" 
       opener.document.form.nume$sufi.value = form.nume.value;
       opener.document.form.esca$sufi.value = form.esca.value;
       opener.document.form.letr$sufi.value = form.letr.value;
       opener.document.form.plan$sufi.value = form.plan.value;
       opener.document.form.puer$sufi.value = form.puer.value;
       opener.document.form.nombinmu$sufi.value = form.nombinmu.value;
       opener.document.form.idenloca$sufi.value = form.idenloca.value;
       opener.document.form.refecata$sufi.value = form.refecata.value;
       opener.document.form.numecarg$sufi.value = form.numecarg.value;
       opener.document.form.caracont$sufi.value = form.caracont.value;
       opener.document.form.nfij$sufi.value = form.nfij.value;";
       
 echo "try { opener.despelecviax () } catch (e) { }"; 
echo"      self.close();
       </script>";
     }
  }

} else {
  if ( $codiviax == "" && $nombinmu == "" && $refecata == "" ) {
    $mens = "Debe seleccionar una vía, una referencia catastral o un nombre de inmueble\n";
  } else {
    $mens = "No hay inmuebles a mostrar\n";
  }
  if ( $codiviax != "" ) {
    $mens .= " en $nombviax\n";
  }
  print $mens;
}

include "comun/pie.inc";
#include "comun/debug.fnc";
#infovari();
?>

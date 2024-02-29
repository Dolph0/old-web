<?
include "comun/func.inc";
include_once "comun/fecha.fnc";

$sesi = cheqsesi();

cabeceraAviso("Se han detectado los siguientes<br>documentos en estado de suspensión");

  $query = stripslashes($query);

  if ($query){
       $orderbypos = strpos($query, "ORDER BY ");
       $groupbypos = strpos($query, "GROUP BY ");
       if ($groupbypos){
         if ($orderbypos > $groupbypos){
           $query = substr($query, 0, $groupbypos) . substr($query, $orderbypos);
         }
         else{
           $query = substr($query, 0, $groupbypos);
         }
       }
       $query = "SELECT conctrib.nomb as conctribnomb,
                         carg.ejer as cargejer,
                         carg.numedocu as cargnumedocu,
                         carg.usuasusp as cargusuasusp,
                         carg.fechsusp as cargfechsusp,
                         carg.horasusp as carghorasusp,
                         estasusp.nomb as estasuspnomb,
                         caussusp.nomb as caussuspnomb " . $query;
       if (strpos($query, " WHERE "))
          $condicion = " AND ";
       else
           $condicion = " WHERE ";
       $condicion .= " estasusp.codiestasusp != 'STR'";

       $orderbypos = strpos($query, "ORDER BY ");
       $groupbypos = strpos($query, "GROUP BY ");
       $insertpos = 0;
       if ($orderbypos){
          $insertpos = $orderbypos;
       }
       if ($groupbypos && ($groupbypos < $orderbypos)){
          $insertpos = $groupbypos;
       }
       if ($insertpos){
          $query = substr($query, 0, $insertpos) . $condicion . " " . substr($query, $insertpos);
       }
       else{
            $query .= $condicion;
       }
     #print $query."<br>";
      set_time_limit(0);
     $resp = sql( $query );
     if ( is_array( $resp ) ) {
        $contsusp = count($resp);
     } else {
        $contsusp = 0;
     }
  }

  print "<body>\n";

if ( is_array( $resp) ) {
  if ( $contsusp > 0 ) {
      opci("Imprimir");
      print "<center><table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";
//      print "<tr>\n<td></td>\n";
      if ($contsusp == 1)
         print "<tr>\n<td colspan='7' class='izqform'>".count($resp)."  documento en suspensión</td>\n";
      else
         print "<tr>\n<td colspan='7' class='izqform'>".count($resp)."  documentos en suspensión</td>\n";

      print "</tr><tr>\n";
      print "<td class='izqform'>Concepto</td>\n";
      print "<td class='izqform'>Ejercicio</td>\n";
      print "<td class='izqform'>Nº documento</td>\n";
      print "<td class='izqform'>Estado</td>\n";
      print "<td class='izqform'>Causa</td>\n";
      print "<td class='izqform'>Usuario</td>\n";
      print "<td class='izqform'>Fecha</td></tr>\n";
      print "<tr>\n<td></td>\n</tr>";

      $usuasuspante = 0;
      while ( $regi = each( $resp) ) {
        $camp = $regi[value];

        if ($camp[cargusuasusp] != null && $usuasuspante != $camp[cargusuasusp]) {
          $nombusuasusp = sql ("SELECT nomb FROM usua WHERE codiusua = $camp[cargusuasusp]");
          $usuasuspante = $camp[cargusuasusp];
        }

        print "<tr>\n";
        print "<td class='derform'>$camp[conctribnomb]</td>\n";
        print "<td class='derform'>$camp[cargejer]</td>\n";
        print "<td class='derform'>$camp[cargnumedocu]</td>\n";
        print "<td class='derform'>$camp[estasuspnomb]</td>\n";
        print "<td class='derform'>$camp[caussuspnomb]</td>\n";
        print "<td class='derform'>$nombusuasusp</td>\n";
        print "<td class='derform'>".mostrarfecha($camp[cargfechsusp])." / ".$camp[carghorasusp]."</td>\n";
        print "</tr>\n";
      }
      print "</table>\n</center>\n";
      print "  <table width='100%'>\n";
      print "    <tr><td align=center>\n";
      print "        <br>\n";
      print "    </td></tr>\n";
      print "    <tr><td align=center>\n";
      print "        <br>\n";
      print "    </td></tr>\n";
      print "    <tr><td align=center>\n";
      print "      <input type='button' onClick='window.close()' value='Aceptar'>\n";
      print "    </td>\n";
      print "  </tr></table>\n";
      }
}
include "comun/pie.inc";
#include "comun/debug.fnc";
#infovari();
?>


<?
include "comun/func.inc";
include "comun/fecha.fnc";

$sesi = cheqsesi();


# Se mira que se ha puesto algun nif o parte de un nombre
# en caso de llegar vacio indicamos al usuario que introduzca
# alguno de los dos. Cerramos la ventana.
if (!$nifx && !$nombsuje)
{
  echo "
  <script>
   alert (\"Por favor introduzca:\\n · NIF \\n o\\n · parte del Nombre\\n\");
   window.close ();
  </script>
  ";
  include "comun/pie.inc";
  exit;
}

cabecera("Elige un sujeto");
print "<hr>\n";
?>

<?
# Este script recibe como minimo 3 parametros a traves del metodo GET:
#  ·Código del ayuntamiento,
#  ·NIF del sujeto,
#  ·Nombre del sujeto.
# Devuelve el Nombre Nif y el codigo del sujeto.

# Adicionalmente puede recibir los nombres de los campos donde se mostrara la
# informacion:
#  ·nombcampcodi -> coditerc
#  ·nombcampnifx -> nifx
#  ·nombcampnomb -> nombre
#  ·nombcampsigl -> sigla de la via
#  ·nombcampdire -> direccion
#  ·nombcampnume -> numero
#  ·nombcampletr -> letra
#  ·nombcampesca -> escalera
#  ·nombcampplan -> planta
#  ·nombcamppuer -> puerta
#  .nombcampbloq -> bloque
#  .nombcampport -> portal
#  .nombcampkilo -> kilometro
#  ·nombcamppers -> personalidad
#  ·nombcamppost -> codigo postal
#  ·nombcamploca -> localidad
#  ·nombcampprov -> provincia (esta en beta)
#  ·nombcampmuni -> municipio (esta en beta)
#  ·nombcamptel1 -> telefono 1
#  ·nombcamptel2 -> telefono 2
#  ·nombcamppais -> pais
#  ·nombcampcodipais -> codipais
#  ·nombcampcorr -> E-mail
#  ·nombcampusuamodi -> Usuario de última modificación
#  ·nombcampfechmodi -> Fecha de última modificación
#  ·nombcamppriomodi -> Prioridad de la última modificación
#  .tiposuje -> Tipo de sujeto pasivo tran(transmitente), adqu(adquirente), repr(declarante)

// Si no se indica ningun nombre para esos campos, por omision tomamos los siguientes
if ( $nombcampnifx == "" ) { $nombcampnifx = "nifx";}
if ( $nombcampnomb == "" ) { $nombcampnomb = "nombsuje";}
if ( $nombcampcodi == "" ) { $nombcampcodi = "coditerc";}


// Se listan todos los sujetos y no los de un solo ayuntamiento
$query = "SELECT nifx, T.nomb, dire, DN.nomb as nombnoes, DE.nomb as nombestr,nume,letr,esca,
          plan,puer,bloq,port,kilo, T.coditerc,siglviax,pers,codipost,loca,codimuni,T.tel1,
          T.tel2,T.direcorr,pais,codipais,aparcorr,
          usua.nomb AS usuamodi, tercprio.descruti AS priomodi, fechmodi
          FROM (((tercdato T LEFT JOIN direnoes DN ON T.coditerc = DN.coditerc)
                 LEFT JOIN direestr DE ON T.coditerc = DE.coditerc)
                LEFT JOIN usua ON T.usuamodi = usua.codiusua)
               LEFT JOIN tercprio ON T.priomodi = tercprio.codiruti";

if ( ( $nifx != "" ) || ( $nombsuje != "") ) { $query .= " WHERE "; }
if ( $nifx != "" ) {
  $nifx = strtoupper( $nifx );
  $query .= " nifx = '$nifx'";
}
if ( $nombsuje != "" ) {
  $nombsuje = strtoupper( $nombsuje );
  if ( $nifx != "" ) { $query .= " AND "; }
  $query .= " T.nomb like '%$nombsuje%'";
}
$query .= " ORDER BY T.nomb ";

$resp = sql( $query );
if ( is_array( $resp ) ) {
  $contsuje = count($resp);
} else {
  $contsuje = 0;
}


if ( $contsuje > 1 ) {
  print "<table cellpadding=4 cellspacing=1 border=0>\n";
  echo  "<tr><td colspan=3 class='izqform'> Se han encontrado ".count($resp)." sujetos</td></tr>\n";
  echo  "<tr><td colspan=4>&nbsp</td></tr>\n";
  print "<tr>\n";
  print "<td class='izqform'>NIF</td>\n";
  print "<td class='izqform'>Nombre</td>\n";
  print "<td class='izqform'>Dirección fiscal</td>\n</tr>\n";

  set_time_limit(0);

  while ( $regi = each( $resp) )
  {
    $camp = $regi[value];

    #Formamos la direccion:
    # - Si es estructuradada se pondran todas las variables menos [nombnoes].
    # - Si es no estructurada simplemente la primera variable tendra valor y el resto se quedaran en blanco.
    # Obteniendo la dirección que queremos mostrar
    $direreto = "$camp[nombnoes] $camp[siglviax] $camp[nombestr] $camp[nume] $camp[letr] $camp[esca] $camp[plan] $camp[puer]";

    # Obtenemos el codigo de la provincia asociado al municipio.
    if ($camp[codimuni])
     $codiprov = sql ("SELECT codiprov FROM muni WHERE codimuni='$camp[codimuni]'");


    # Si existe algun campo de dirección estruturada tendremos que dividir la informacion.
    if ($nombcampsigl || $nombcampnume || $nombcampletr || $nombcampesca || $nombcampplan || 
        $nombcamppuer || $nombcampbloq || $nombcampport || $nombcampkilo || 
        $nombcamppers || $nombcamppost || $nombcamploca || $nombcampprov || 
        $nombcampmuni || $nombcamptel1 || $nombcamptel2 || $nombcamppais || $nombcampcodipais || $nombcampcorr)
      $direccion="$camp[nombnoes]$camp[nombestr]";
    else
      $direccion="$direreto";

    print "<tr>
          <td class='derform'>
          <a href = '' onClick = 'opener.document.form.$nombcampcodi.value = \"$camp[coditerc]\";
                                  opener.document.form.$nombcampnifx.value = \"$camp[nifx]\";
                                  opener.document.form.$nombcampnomb.value = \"$camp[nomb]\";\n";

               if ( $nombcampsigl != "" ) { 
                 echo "           opener.document.form.$nombcampsigl.value = \"$camp[siglviax]\";\n"; }
               if ( $nombcampdire != "" ) { 
                 echo "           opener.document.form.$nombcampdire.value = \"$direccion\";\n"; }
               if ( $nombcampnume != "" ) { 
                 echo "           opener.document.form.$nombcampnume.value = \"$camp[nume]\";\n"; }
               if ( $nombcampletr != "" ) { 
                 echo "           opener.document.form.$nombcampletr.value = \"$camp[letr]\";\n"; }
               if ( $nombcampesca != "" ) { 
                 echo "           opener.document.form.$nombcampesca.value = \"$camp[esca]\";\n"; }
               if ( $nombcampplan != "" ) { 
                 echo "           opener.document.form.$nombcampplan.value = \"$camp[plan]\";\n"; }
               if ( $nombcamppuer != "" ) { 
                 echo "           opener.document.form.$nombcamppuer.value = \"$camp[puer]\";\n"; }
               if ( $nombcampbloq != "" ) { 
                 echo "           opener.document.form.$nombcampbloq.value = \"$camp[bloq]\";\n"; }
               if ( $nombcampport != "" ) { 
                 echo "           opener.document.form.$nombcampport.value = \"$camp[port]\";\n"; }
               if ( $nombcampkilo != "" ) { 
                 echo "           opener.document.form.$nombcampkilo.value = \"$camp[kilo]\";\n"; }
               if ( $nombcamppers != "" ) { 
                 echo "           opener.document.form.$nombcamppers.value = \"$camp[pers]\";\n"; }
               if ( $nombcamppost != "" ) { 
                 echo "           opener.document.form.$nombcamppost.value = \"$camp[codipost]\";\n"; }
               if ( $nombcamploca != "" ) { 
                 echo "           opener.document.form.$nombcamploca.value = \"$camp[loca]\";\n"; }
               if ( $nombcampprov != "" ) { 
                 echo "           opener.document.form.$nombcampprov.value = \"$codiprov\";\n"; }
               if ( $nombcampmuni != "" ) { 
                 echo "           opener.document.form.$nombcampmuni.value = \"$camp[codimuni]\";\n"; }
               if ( $nombcamptel1 != "" ) { 
                 echo "           opener.document.form.$nombcamptel1.value = \"$camp[tel1]\";\n"; }
               if ( $nombcamptel2 != "" ) { 
                 echo "           opener.document.form.$nombcamptel2.value = \"$camp[tel2]\";\n"; }
               if ( $nombcamppais != "" ) {
                 echo "           opener.document.form.$nombcamppais.value = \"$camp[pais]\";\n"; }
               if ( $nombcampcodipais != "" ) {
                 echo "           opener.document.form.$nombcampcodipais.value = \"$camp[codipais]\";\n"; }
               if ( $nombcampcorr != "" ) {
                 echo "           opener.document.form.$nombcampcorr.value = \"$camp[direcorr]\";\n"; }
               if ( $nombcampapar != "" ) { 
                 echo "           opener.document.form.$nombcampapar.value = \"$camp[aparcorr]\";\n"; }
               if ( $nombcampusuamodi != "" ) { 
                 echo "           opener.document.form.$nombcampusuamodi.value = \"$camp[usuamodi]\";\n"; }
               if ( $nombcampfechmodi != "" ) { 
                 echo "           opener.document.form.$nombcampfechmodi.value = \"" . mostrarFecha ($camp[fechmodi]) . "\";\n"; }
               if ( $nombcamppriomodi != "" ) { 
                 echo "           opener.document.form.$nombcamppriomodi.value = \"$camp[priomodi]\";\n"; }
               echo "           try { opener.despelecsuje () } catch (e) { }
               self.close();'>
          $camp[nifx]
          </a>
          </td>
          <td class='derform'>$camp[nomb]</td>
          <td class='derform'>$direreto</td>
         </tr>\n";
  }
  print "</table>\n";
} else {
   if ( $contsuje == 1 ) {
     if (is_array ($resp)) $camp = each ($resp);
     $camp = $camp[value];

     #Formamos la direccion:
     # - Si es estructuradada se pondran todas las variables menos [nombnoes].
     # - Si es no estructurada simplemente la primera variable tendra valor y el resto se quedaran en blanco.
     # Obteniendo la dirección que queremos mostrar
     $direreto = "$camp[nombnoes] $camp[siglviax] $camp[nombestr] $camp[nume] $camp[letr] $camp[esca] $camp[plan] $camp[puer] $camp[bloq] $camp[port] $camp[kilo]";

     # Obtenemos el codigo de la provincia asociado al municipio.
     if ($camp[codimuni])
       $codiprov = sql ("SELECT codiprov FROM muni WHERE codimuni='$camp[codimuni]'");

     if ($nombcampsigl || $nombcampnume || $nombcampletr || $nombcampesca || $nombcampplan || 
         $nombcamppuer || $nombcampbloq || $nombcampport || $nombcampkilo || 
         $nombcamppers || $nombcamppost || $nombcamploca || $nombcampprov || 
         $nombcampmuni || $nombcamptel1 || $nombcamptel2 || $nombcamppais || $nombcampcodipais || $nombcampcorr)
       $direccion="$camp[nombnoes]$camp[nombestr]";
     else
       $direccion="$direreto";

     echo"<form name='form' action='listsuje.php'>\n
           <input type='hidden' value='".$camp[coditerc]."' name='coditerc'>\n
           <input type='hidden' value='".$camp[nifx]."' name='nifx'>\n
           <input type='hidden' value='".$camp[nomb]."' name='nomb'>\n
           <input type='hidden' value='".$direccion."' name='dire'>\n
           <input type='hidden' value='".$camp[nume]."' name='nume'>\n
           <input type='hidden' value='".$camp[letr]."' name='letr'>\n
           <input type='hidden' value='".$camp[esca]."' name='esca'>\n
           <input type='hidden' value='".$camp[plan]."' name='plan'>\n
           <input type='hidden' value='".$camp[puer]."' name='puer'>\n
           <input type='hidden' value='".$camp[bloq]."' name='bloq'>\n
           <input type='hidden' value='".$camp[port]."' name='port'>\n
           <input type='hidden' value='".$camp[kilo]."' name='kilo'>\n
           <input type='hidden' value='".$camp[siglviax]."' name='sigl'>\n
           <input type='hidden' value='".$camp[pers]."' name='pers'>\n
           <input type='hidden' value='".$camp[codipost]."' name='post'>\n
           <input type='hidden' value='".$camp[loca]."' name='loca'>\n
           <input type='hidden' value='".$camp[codimuni]."' name='muni'>\n
           <input type='hidden' value='".$codiprov."' name='prov' >\n
           <input type='hidden' value='".$camp[tel1]."' name='tel1'>\n
           <input type='hidden' value='".$camp[tel2]."' name='tel2'>\n
           <input type='hidden' value='".$camp[direcorr]."' name='corr'>\n
           <input type='hidden' value='".$camp[aparcorr]."' name='apar'>\n
           <input type='hidden' value='".$camp[pais]."' name='pais'>\n
           <input type='hidden' value='".$camp[codipais]."' name='codipais'>\n
           <input type='hidden' value='".$camp[usuamodi]."' name='usuamodi'>\n
           <input type='hidden' value='".mostrarFecha($camp[fechmodi])."' name='fechmodi'>\n
           <input type='hidden' value='".$camp[priomodi]."' name='priomodi'>\n
          </form>
     <script language='JavaScript'>
     opener.document.form.$nombcampnifx.value = form.nifx.value;
     opener.document.form.$nombcampnomb.value = form.nomb.value;
     opener.document.form.$nombcampcodi.value = form.coditerc.value;\n";

       // Si no se especificara el nombre del campo es porque no se muestra dicho campo en el formulario
       // Nos aseguramos que la direccion es "e" (estructurada) para que no de errores de javascript.
       // Ademas de aseguraros que se nos han pedido los campos.
  if ($camp[dire]=="e")
  {
     if ( $nombcampsigl != '' ) { echo "     opener.document.form.$nombcampsigl.value = form.sigl.value;\n";}
     if ( $nombcampnume != "" ) { echo "     opener.document.form.$nombcampnume.value = form.nume.value;\n";}
     if ( $nombcampletr != "" ) { echo "     opener.document.form.$nombcampletr.value = form.letr.value;\n";}
     if ( $nombcampesca != "" ) { echo "     opener.document.form.$nombcampesca.value = form.esca.value;\n";}
     if ( $nombcampplan != "" ) { echo "     opener.document.form.$nombcampplan.value = form.plan.value;\n";}
     if ( $nombcamppuer != "" ) { echo "     opener.document.form.$nombcamppuer.value = form.puer.value;\n";}
     if ( $nombcampbloq != "" ) { echo "     opener.document.form.$nombcampbloq.value = form.bloq.value;\n";}
     if ( $nombcampport != "" ) { echo "     opener.document.form.$nombcampport.value = form.port.value;\n";}
     if ( $nombcampkilo != "" ) { echo "     opener.document.form.$nombcampkilo.value = form.kilo.value;\n";}
  }

  if ( $nombcampdire != '' ) { echo "     opener.document.form.$nombcampdire.value = form.dire.value;\n";}
  if ( $nombcamppers != "" ) { echo "     opener.document.form.$nombcamppers.value = form.pers.value;\n";}
  if ( $nombcamppost != "" ) { echo "     opener.document.form.$nombcamppost.value = form.post.value;\n";}
  if ( $nombcamploca != "" ) { echo "     opener.document.form.$nombcamploca.value = form.loca.value;\n";}
  if ( $nombcampmuni != "" ) { echo "     opener.document.form.$nombcampmuni.value = form.muni.value;\n";}
  if ( $nombcampprov != "" ) { echo "     opener.document.form.$nombcampprov.value = form.prov.value;\n";}
  if ( $nombcamptel1 != "" ) { echo "     opener.document.form.$nombcamptel1.value = form.tel1.value;\n";}
  if ( $nombcamptel2 != "" ) { echo "     opener.document.form.$nombcamptel2.value = form.tel2.value;\n";}
  if ( $nombcampcorr != "" ) { echo "     opener.document.form.$nombcampcorr.value = form.corr.value;\n";}
  if ( $nombcampapar != "" ) { echo "     opener.document.form.$nombcampapar.value = form.apar.value;\n";}
  if ( $nombcamppais != "" ) { echo "     opener.document.form.$nombcamppais.value = form.pais.value;\n";}
  if ( $nombcampcodipais != "" ) { echo "     opener.document.form.$nombcampcodipais.value = form.codipais.value;\n";}
  if ( $nombcampcorr != "" ) { echo "     opener.document.form.$nombcampcorr.value = form.corr.value;\n";}
  if ( $nombcampusuamodi != "" ) { echo "     opener.document.form.$nombcampusuamodi.value = form.usuamodi.value;\n";}
  if ( $nombcampfechmodi != "" ) { echo "     opener.document.form.$nombcampfechmodi.value = form.fechmodi.value;\n";}
  if ( $nombcamppriomodi != "" ) { echo "     opener.document.form.$nombcamppriomodi.value = form.priomodi.value;\n";}
  echo "try { opener.despelecsuje$tiposuje () } catch (e) { } self.close();</script>";
   } else {

     # Significa que no se ha encontrado nada mostramos por pantalla el mensaje y cerramos ventana.
     $mens = "No hay sujetos a mostrar con\\n\\n";
     if ( $nifx != "" ) {$mens .= " ·NIF: $nifx\\n\\n";}
     if ( $nombsuje != "" ) {$mens .= " ·Nombre: $nombsuje\\n";}
     echo " <script>  alert (\"$mens \\n\");  window.close (); </script>  ";
   }
}


include "comun/pie.inc";
?>

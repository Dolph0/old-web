<?
 # Inicializacion
 $codiespa = sql ("SELECT codipais FROM pais where nomb='ESPAÑA'");
 print "<input type='hidden' name='codiespa' value='$codiespa'> \n";
?>

<script>
 // Cambio de pais
 function cambpais (codipais, codiespa, de) { 
   if (codipais == codiespa || codipais == 0) {
     if (de == adqu){
        adquceldsiglviax.style.display = 'block';
        adquceldsiglviaxdato.style.display = 'block';
        adquceldcodipost.style.display = 'block';
        adquceldcodipostdato.style.display = 'block';
        adqufilaprovmuni.style.display = 'block';
        adqufilaprovmunidato.style.display = 'block';
     }else{
        tranceldsiglviax.style.display = 'block';
        tranceldsiglviaxdato.style.display = 'block';
        tranceldcodipost.style.display = 'block';
        tranceldcodipostdato.style.display = 'block';
        tranfilaprovmuni.style.display = 'block';
        tranfilaprovmunidato.style.display = 'block';
     }
   } else {
     if (de == adqu){
        adquceldsiglviax.style.display = 'none';
        adquceldsiglviaxdato.style.display = 'none';
        adquceldcodipost.style.display = 'none';
        adquceldcodipostdato.style.display = 'none';
        adqufilaprovmuni.style.display = 'none';
        adqufilaprovmunidato.style.display = 'none';
     }else{
        tranceldsiglviax.style.display = 'none';
        tranceldsiglviaxdato.style.display = 'none';
        tranceldcodipost.style.display = 'none';
        tranceldcodipostdato.style.display = 'none';
        tranfilaprovmuni.style.display = 'none';
        tranfilaprovmunidato.style.display = 'none';
     }
   }
 }
 
 function despelecsuje() {
       despelecsujetran();
       despelecsujeadqu();
       despelecsujerepr();
 }
</script>

<?

#
#Funcion encargada de gestionar el domicilio.
#   Se puede llamar directamente la funcion sin pasar ningun parametro en caso de pasar datos serian:
#   $DE: destancan dos:
#    'adqu' Datos del adquirente.
#    'repr' Datos del representante.

function combosuje ($valor)
{
  $retornar="
             <select class='inputform' name='_idxxdecl' size='1'  onChange=\"datodecl()\">
                <option value = 'TR' ";if ($valor=='TR')$retornar.=" selected";$retornar.=">TRANSMITENTE</option>
                <option value = 'AD' ";if ($valor=='AD')$retornar.=" selected";$retornar.=">ADQUIRENTE</option>
                <option value = 'RA' ";if ($valor=='RA')$retornar.=" selected";$retornar.=">REPRESENTANTE DEL ADQUIRENTE</option>
                <option value = 'RT' ";if ($valor=='RT')$retornar.=" selected";$retornar.=">REPRESENTANTE DEL TRANSMITENTE</option>
             </select>";
  return $retornar;
  
}

function domicilio ($de='terc',$estado='M',$codiobje='')
{
  global
    $ERRORSQL,
    $opci,
    $mensaje, 
    $sesi,
    $_domihabi,
    $_idxxdecl,
    $_clastran,
    $codiespa,
    ${$de."coditerc"}, 
    ${$de."nomb"},
    ${$de."nifx"},
    ${$de."sigl"},
    ${$de."dire"},
    ${$de."nume"},
    ${$de."letr"},
    ${$de."esca"},
    ${$de."plan"},
    ${$de."puer"},
    ${$de."tel1"},
    ${$de."tel2"},
    ${$de."correlec"}, 
    ${$de."muni"},
    ${$de."prov"},
    ${$de."info"},
    ${$de."pers"},
    ${$de."codipais"},
    ${$de."aparcorr"},
    ${$de."codipost"},
    ${$de."loca"},
    
// EN CASO QUE EL ADQUIRENTE DECIDA QUEDARSE CON LA CASA
//REFERENCIA TERRITORIO
   $codiambi,
   $codimuni,
   $codiprov,
   $codiviax,
   $nume,
   $letr,
   $esca,
   $plan,
   $puer,
   $nombinmu,
   $idenloca;

   if ($_domihabi && $de=='adqu')
   {
     
    $vias = sql("SELECT vias.codipost as codipost, vias.nomb as nombvias,siglvias.siglviax as nombsigl
                   FROM vias,siglvias
                  WHERE codiayun='$codiambi' AND vias.codisigl=siglvias.codisigl AND codiviax='$codiviax' 
               ORDER BY nombvias");
    
    if (is_array ($vias)) {$vias = each ($vias);$vias = $vias [value];}
    
    $adqusigl     = $vias [nombsigl];
    $adqudire     = $vias [nombvias];
    $adqunume     = $nume;
    $adquletr     = $letr;
    $adquesca     = $esca;
    $adquplan     = $plan;
    $adqupuer     = $puer;
    $adqucodipost = $vias [codipost];
    $adquloca     = '';
    $adqumuni     = $codimuni;
    $adquprov     = $codiprov;

    // Si vamos a grabar y la casa sera el domicilio habitual
    // el pais automaticamente del adquierente es ESPAÑA
    if ($_domihabi == 1 && $estado == 'C') $adqucodipais = $codiespa;

   }// fin de recogida de datos para el adquirente     
  

// Creacion de un array con todos sus datos para utilizar en el inseterc. 
   ${$de."info"}=array ( "coditerc" => ${$de."coditerc"},   
                         "nomb"     => ${$de."nomb"},
                         "nifx"     => ${$de."nifx"},
                         "sigl"     => ${$de."sigl"},
                         "dire"     => ${$de."dire"}, 
                         "nume"     => ${$de."nume"},
                         "letr"     => ${$de."letr"},
                         "esca"     => ${$de."esca"},
                         "plan"     => ${$de."plan"},
                         "puer"     => ${$de."puer"},
                         "codipost" => ${$de."codipost"},    
                         "loca"     => ${$de."loca"},
                         "tel1"     => ${$de."tel1"},
                         "tel2"     => ${$de."tel2"},
                         "correlec" => ${$de."correlec"}, 
                         "muni"     => ${$de."muni"},
                         "prov"     => ${$de."prov"},   
                         "pers"     => ${$de."pers"},   
                         "codipais" => ${$de."codipais"},   
                         "aparcorr" => ${$de."aparcorr"});   

  
    if ( !$opci ) {
      // Valores iniciales al entrar en la pagina o al volver del listado
      // sin seleccionar ningun registro
    if ($de=='adqu')$_domihabi='';
    if ($de=='repr')$_idxxdecl='AD';
    ${$de."coditerc"}=''; 
    ${$de."nomb"}='';
    ${$de."nifx"}='';
    ${$de."sigl"}='';
    ${$de."dire"}='';
    ${$de."nume"}='';
    ${$de."letr"}='';
    ${$de."esca"}='';
    ${$de."plan"}='';
    ${$de."puer"}='';
    ${$de."codipost"}='';
    ${$de."loca"}='';
    ${$de."tel1"}='';
    ${$de."tel2"}='';
    ${$de."correlec"}=''; 
    ${$de."muni"}='';
    ${$de."prov"}='';
    ${$de."codipais"}='';
    ${$de."pers"}='';
    ${$de."aparcorr"}='';
    } // opci = ModificarERROR deja los campos igual 
 


 //MOSTRAR DATOS A RELLENAR.
 if ($estado == 'M') { 
 switch ($de)
 {
  case 'tran':$titulo.=" Transmitente";break;
  case 'adqu':$titulo.=" Adquirente";break;
  case 'repr':$titulo.=" Declarante";break;
  default: $titulo.=" Sujeto";break;
 }
 
 echo "
<table cellpadding=2 cellspacing=1 border=0 width=100%>
 <tr>
  <td colspan=2 class=izqform width=20%> $titulo</td></tr>\n";
if ($_idxxdecl=='RA' || $_idxxdecl=='RT') $displayrepr="style=\"display:inline\"";
else $displayrepr="style=\"display:none\"";
 
 if ($de=='repr') echo "
</table>
<table cellpadding=2 cellspacing=1 border=0 width=100%>
  <tr>
    <td class=izqform width=20%>Relación</td>
    <td class=derform>
      <table cellpadding=4 cellspacing=1 border=0><tr><td>
        <table>
          <tr>
           <td>
".combosuje ($_idxxdecl)."
                 
            </td>
          </tr>
        </table>
      </td></tr></table>
    </td>
  </tr>
</table>
<table width=100% cellpadding=2 cellspacing=1 border=0 id=\"representante\" $displayrepr>";

?>

  <tr>
     <td width=20% class='izqform'>Identificación</td>
     <td class='derform'>

<script>
<!--
    function despelecsuje<? echo $de; ?> () {
       // Cambio del pais
       cambpais (form.<? echo $de; ?>codipais.value, <? print $codiespa; ?>, <? print $de; ?>);

       // Actualizamos muni con los municipios pertenecientes a la provincia
       // elegida
       cambiamuni (form.<? echo $de; ?>prov, form.<? echo $de; ?>muni);

       // Ponemos el valor que escondimos en munihidden en muni, ahora que tiene
       // la lista de valores posibles actualizada
       form.<? echo $de; ?>muni.value = form.<? echo $de; ?>munihidden.value;

       // Mostramos la caja con los datos de última actualización
       cajamodi.style.display = 'block';
    }

function vent<?echo $de?>()
{
       abrevent("<? cheqroot("comun/listsuje.php", TRUE) ?>","ayun=<?print $codiambi?>&nifx="+form.<?echo $de?>nifx.value+"&nombsuje="+form.<?echo $de?>nomb.value+"&nombcampcodi=<?echo$de?>coditerc&nombcampnomb=<?echo $de?>nomb&nombcampnifx=<?echo $de?>nifx&nombcampsigl=<?echo $de?>sigl&nombcampdire=<?echo $de?>dire&nombcampnume=<?echo $de?>nume&nombcampletr=<?echo $de?>letr&nombcampesca=<?echo $de?>esca&nombcampplan=<?echo $de?>plan&nombcamppuer=<?echo $de?>puer&nombcamppost=<?echo $de?>codipost&nombcamploca=<?echo $de?>loca&nombcamptel1=<?echo $de?>tel1&nombcamptel2=<?echo $de?>tel2&nombcampmuni=<?echo $de?>munihidden&nombcampprov=<?echo $de?>prov&nombcampcorr=<?echo $de?>correlec&nombcampcodipais=<?echo $de?>codipais&nombcampapar=<?echo $de?>aparcorr&nombcamppers=<?echo $de?>pers&tiposuje=<?echo $de?>",600,500);       
    }

function <?echo $de?>()
{
   vent<?echo $de?>();
 }
//-->
</script>

       <table cellpadding=4 cellspacing=1 border=0>
          <tr>
           <td colspan=3><b>Nombre</b></td>
          </tr>
          <tr>
           <td colspan=3>
            <? cajatexto ($de."nomb", ${$de."nomb"}, "nomb")?>
            <input name="<?echo $de?>coditerc" type="hidden" value="<? print ${$de."coditerc"} ?>">
           </td>
         </tr>
         <tr>
           <td><b>NIF/NIE</b>
           </td>            
           <td><b>Personalidad</b></td>
           <td rowspan="2">
            <input type='button' name='buscsuje' value='Buscar' onClick='<?echo $de?>();'></td>
           </td>
         </tr>
         <tr>
         <td><? cajatexto ($de."nifx", ${$de."nifx"}, "nifx") ?></td>
         <td> <? mueslist ($de."pers", "SELECT codipers, descpers FROM tercpers", ${$de."pers"}, "", 1) ?>
         </td>
         </tr>
         <tr>
          </tr>
        </table>
    </td>
  </tr>


  <tr>
     <td class='izqform'>Domicilio fiscal</td>
     <td class='derform'>
<? if ($de=='adqu')
             {echo" 
              El objeto de la transmisión ¿será el domicilio habitual del adquirente?
              <select class='inputform' name='_domihabi' size='1' value='".$_domihabi."' onChange=\"domifisc()\">";
                 if ($_domihabi)
                 {
                   echo" <option value = '1' selected>SÍ</option>\n<option value = '0'>NO</option>\n";
                   $display="style='display:none'";
                 } else  {
                   echo" <option value = '1'>SÍ</option>\n<option value = '0' selected>NO</option>\n";
                   $display="style='display:block'";
                 }
              echo"</select><div id=\"domiciliofiscal\" $display>";
             }
?>
       <table cellpadding=4 cellspacing=1 border=0>
        <tr>
          <td id="celdpais"><b>País</b></td>
        </tr>
        <tr>
          <td id="celdpaisdato">
            <? if (!${$de."codipais"}) {
                 ${$de."codipais"} = $codiespa;
               }
               if($de=="repr") 
                   mueslist($de."codipais", 
                        "SELECT codipais, nomb as paisnomb FROM pais WHERE codipais=$codiespa",$codiespa,'', 1);               
               else
                   mueslist($de."codipais", 
                        "SELECT codipais, nomb as paisnomb FROM pais ORDER BY nomb", 
                        ${$de."codipais"}, 
                        "cambpais (form.".$de."codipais.value,".$codiespa.",".$de.")",
                        1);               
            ?>
          </td>
        </tr>
          <tr>
          <td id="<?echo $de."celdsiglviax"?>" <? print (${$de."codipais"}== $codiespa)?'':" style='display:none'"; ?> >
            <b>Sigla vía</b>
          </td>
          <td colspan=2><b>Nombre de la vía</b></td>
          </tr>
          <tr>
          <td id="<?echo $de."celdsiglviaxdato"?>" <? print (${$de."codipais"} == $codiespa)?'':" style='display:none'"; ?> >
            <? mueslist ($de."sigl", "SELECT siglviax, nomb FROM siglvias", ${$de."sigl"}, "", 1) ?></td>
          <td colspan=2><? cajatexto ($de."dire", ${$de."dire"}, "viax") ?></td>
          </tr>
          <tr>
          <td>
              <table>
                <tr>
                <td><b>Número</b></td>
                  <td>Letra</td>
                  <td>Escalera</td>
                  <td>Planta</td>
                  <td>Puerta</td>
                </tr>
                <tr>
                  <td><? cajatexto ($de."nume", ${$de."nume"}, "direnume") ?></td>
                  <td><? cajatexto ($de."letr", ${$de."letr"}, "direletr") ?></td>
                  <td><? cajatexto ($de."esca", ${$de."esca"}, "direesca") ?></td>
                  <td><? cajatexto ($de."plan", ${$de."plan"}, "direplan") ?></td>
                  <td><? cajatexto ($de."puer", ${$de."puer"}, "direpuer") ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
          <td id="<?echo $de."celdcodipost"?>" <? print (${$de."codipais"} == $codiespa)?'':" style='display:none'"; ?> >
            <b>Código postal</b>
          </td>
          <td colspan=2>Localidad</td>
          </tr>
          <tr>
          <td id="<?echo $de."celdcodipostdato"?>" <? print (${$de."codipais"} == $codiespa)?'':" style='display:none'"; ?> >
            <? cajatexto ($de."codipost", ${$de."codipost"}, "nume") ?>
          </td>
          <td colspan=2><? cajatexto ($de."loca",     ${$de."loca"},     "nomb") ?></td>
          </tr>
        <tr id="<?echo $de."filaprovmuni"?>" <? print (${$de."codipais"} == $codiespa)?'':" style='display:none'"; ?> >
          <td colspan=2><b>Provincia</b></td>
          <td><b>Municipio</b></td>
          </tr>
        <tr id="<?echo $de."filaprovmunidato"?>" <? print (${$de."codipais"} == $codiespa)?'':" style='display:none'"; ?> >
          <td colspan=2><? mueslist ($de."prov", "SELECT codiprov, nomb FROM prov ORDER BY nomb", ${$de."prov"}, "cambiamuni(form.".$de."prov, form.".$de."muni)", 1) ?></td>
            <td>
            <? 
            if (!${$de."prov"}) {
              $querymunicipio = '';
            } else {
              $querymunicipio = "SELECT codimuni, nomb FROM muni WHERE codiprov = '${$de.'prov'}' ORDER BY nomb";
            } 
            mueslist ($de."muni", $querymunicipio, ${$de."muni"}, "", 1) ?>
            <input type=hidden name="<?echo $de."munihidden"?>">
          </td>
        </tr>
        <tr>
            
          <td colspan=2>Apartado de correos</td>
        </tr>
        <tr>
          <td colspan=2><? cajatexto ($de."aparcorr",${$de."aparcorr"}, 'aparcorr') ?></td>
        </tr>
       </table>
            </td>
          </tr>
      
<?if ($de=='adqu') echo "</div>";?>

   <tr>
    <td class=izqform width=20%>Contacto</td>
    <td class=derform>
      <table cellpadding=4 cellspacing=1 border=0><tr><td>
        <table>
          <tr>
            <td>Teléfono</td>
            <td>Teléfono </td>
            <td>Email</td>
          </tr>
          <tr>
            <td><? cajatexto ($de."tel1",${$de."tel1"}, "tele") ?></td>
            <td><? cajatexto ($de."tel2",${$de."tel2"}, "tele") ?></td>
            <td><? cajatexto ($de."correlec",${$de."correlec"}, "mail") ?></td>
          </tr>
        </table>
      </td></tr></table>
    </td>
  </tr>
<?
 if ($de=='repr') echo "</table></tr></td>";
 
 echo "<tr><td>&nbsp;</td></tr></table>";
} // FIN DE MOSTRAR

//CREAR SUJETO...

if ($estado =='C')
{

if ($_clastran=='CVT' || $_clastran=='PER')
 {
  if ($de=="tran") $codifigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'CNP'");
  if ($de=="adqu") $codifigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'ADQ'");
 } else {  
  if ($de=="tran") $codifigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'ADQ'");
  if ($de=="adqu") $codifigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'CNP'");
 }

if ($de=="repr")  $codifigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'DCL'");

if ($de=='tran')$desujeto ="del transmitente";
if ($de=='adqu')$desujeto ="del adquirente";
if ($de=='repr')$desujeto ="del representante";

$ERRORSQL='';

// Si el pais es ESPAÑA, el codigo postal es obligatorio
$direcodipost = 0; // Ok
if (${$de."codipais"} == $codiespa && !${$de."codipost"}) {
  $direcodipost = 1; // NOk
}
 
if (${$de."nomb"} && ${$de."nifx"} && $sesi[sesicodiusua] && $direcodipost == 0)
 $t=array(nomb     => "${$de."nomb"}",    
          nifx     => "${$de."nifx"}",
          pers     => "${$de."pers"}",
          codipost => "${$de."codipost"}",
          loca     => "${$de."loca"}",
          codipais => "${$de."codipais"}",
          codimuni => "${$de."muni"}",
          tel1     => "${$de."tel1"}",
          tel2     => "${$de."tel2"}",
          direcorr => "${$de."correlec"}",
          aparcorr => "${$de."aparcorr"}",
          dire     => 'e',
          usuamodi => "$sesi[sesicodiusua]",
          priomodi => 'GESTOIPL');
 else 
 {
   if (!${$de."nomb"})       $ERRORSQL.="Falta el nombre $desujeto<br>";
   if (!${$de."nifx"})       $ERRORSQL.="Falta el nif $desujeto<br>";
   if (!${$de."codipost"} && ${$de."codipais"} == $codiespa) { 
     $ERRORSQL.="Falta el codigo postal $desujeto<br>"; 
     $_domihabi = 0; // Pa'que pueda poner el codigo postal
   }
   if (!$sesi[sesicodiusua]) $ERRORSQL.="Se desconoce el usuario que grabara los datos<br>";
 }
 
 if (${$de."dire"}||${$de."nume"}||${$de."letr"}||${$de."esca"}||${$de."plan"}||${$de."puer"}||${$de."sigl"})
 $d=array(nomb     => "${$de."dire"}",
          nume     => "${$de."nume"}",             
          letr     => "${$de."letr"}",             
          esca     => "${$de."esca"}",             
          plan     => "${$de."plan"}",             
          puer     => "${$de."puer"}",             
          siglviax => "${$de."sigl"}");
 else
   $ERRORSQL.="Se desconoce su direccion $desujeto<br>";

 if ($codiobje && $codifigu)
 $o=array(tipoobje => 'OIPL',
          codiobje => $codiobje,
          codifigu => $codifigu);
 else 
   $ERRORSQL.="No se puede establecer la relacion entre SUJETO - OBJETO<br>";
  
 if ($ERROR || $ERRORSQL) return false ;
 else return insetercnew ($t,$d,$o);
 }
}
?>
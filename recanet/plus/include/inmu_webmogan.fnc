<?php
function inmueble ($estado,$cuota_plusvalia) {

  global
   $_POST,
   $codiambi,

//OCULTOS.
   $tipovia,
   $codiinmu,
   $codimuni,
   $codiprov,
   $estanota,

//REFERENCIA TERRITORIO
   $codiviax,
   $viax,
   $codisigl,
   $nume,
   $letr,
   $esca,
   $plan,
   $puer,
   $refecata,
   $numecarg,
   $caracont,
   $nfij,    
   $nombinmu,
   $idenloca,

//ESCRITURA
   $_nombnota,
   $_nombnota2,
   $_prot,   
   $_vari,    
   $_fechescr,
   $_fechdeve,
   $_fechpres,
   $_cuotadqu, 
   $_fechtran, 
   $_aniotran, 
   $_clastran,
   $_clasdere,

//REGISTRO 
   $_regi,
   $_tomo,
   $_libr,
   $_secc,
   $_foli,
   $_finc,
   $_insc;

  if (!$estanota) $estanota='REGIS';
  if (!$tipovia) $tipovia='CALLEJERO';
  if (!$_fechpres) $_fechpres=hoy;


 echo "<input type='hidden' id='calcular_plusvalia' name='calcular_plusvalia' value='no'>";
 
 if ($estado =='M')
 {
   $codimuni = sql ("SELECT codimuni FROM ayun WHERE codiayun='$codiambi'");
   $codiprov = sql ("SELECT codiprov FROM muni WHERE codimuni='$codimuni'");
   echo "<input type='hidden' name='codimuni' value='$codimuni'>
         <input type='hidden' name='codiprov' value='$codiprov'>";
?>
<div style="font-size: 12px; color: #FF6600; font-weight: bold;" align="left">
<span style="font-weight: bold; font-size: 14px;">1º)</span> Identifique el objeto de la transmisión.
Para hacerlo, puede utilizar la dirección, la referencia catastral, o el nombre del inmueble. Utilice el botón <b>"Continuar"</b>
para completar la información del inmueble.
<br>
</div>
  <table cellpadding=2 cellspacing=1 border=0 width=100%>
  <tr>
    <td class='derform'>
      <input type=hidden name='codiinmu' value="<?php print $codiinmu?>">
      <table cellpadding=2 cellspacing=1 border=0 width="100%">
       <tr>
        <td><b>Vía pública</b><input type='hidden' name='tipovia' value='<?php echo $tipovia?>'></td>
        <td><b>Número</b></td>
        <td>Letra</td>
        <td>Esc</td>
        <td>Planta</td>
        <td>Puerta</td>
       </tr>
       <tr>
        <td>
<?php

if ($tipovia=='NUEVA') {$list='none';$box='';}
else {$list='';$box='none';}

         echo"&nbsp;";
         $vias = sql("SELECT codiviax,webmogan_vias.nomb as nombvias,siglvias.nomb as nombsigl FROM webmogan_vias,siglvias WHERE codiayun='$codiambi' AND webmogan_vias.codisigl=siglvias.codisigl ORDER BY nombvias");        
         print "<select  class='inputform' name='codiviax' size='1'> \n";
         print "<option></option>\n";
         while ( $regi = each($vias) ) {
           $camp = $regi['value'];
           print "<option value='{$camp['codiviax']}' ";
           if ( $camp['codiviax'] == $codiviax ) { print "selected"; }
           print "> ".$camp['nombvias']." (".$camp['nombsigl'].")</option>\n";
         }
         print "</select>";
?>
        </td>
        <td><?php cajatexto ('nume', $nume, 'direnume') ?></td>
        <td><?php cajatexto ('letr', $letr, 'direletr') ?></td>
        <td><?php cajatexto ('esca', $esca, 'direesca') ?></td>
        <td><?php cajatexto ('plan', $plan, 'direplan') ?></td>
        <td><?php cajatexto ('puer', $puer, 'direpuer') ?></td>
       </tr>
       <tr>
        <td>Referencia catastral</td>
        <td>Cargo</td>
        <td>CC</td>
        <td colspan=3>Número fijo</td>
       </tr>
       <tr>
        <td>
         <?php cajatexto ('refecata', $refecata, 'cata'); ?>
		&nbsp;<input style='visibility: hidden;' type='button' name='BotonMapa' value='Ver en el mapa' onclick='VerMapa();'>
	</td>
        <td><?php cajatexto ('numecarg', $numecarg, 'carg') ?></td>
        <td><?php cajatexto ('caracont', $caracont, 'cc') ?></td>
        <td colspan='3'><?php cajatexto ('nfij', $nfij, 'nfij') ?></td>
       </tr>
       <tr>
        <td>Nombre inmueble</td>
        <td colspan=5>Identificador local</td>
       </tr>
       <tr>
        <td><?php 
               mueslist ('nombinmu', "SELECT DISTINCT (nomb), nomb FROM webmogan_inmu WHERE codiayun='$codiambi' ORDER BY nomb", $nombinmu); ?></td>
        <td ><?php cajatexto ('idenloca', $idenloca, 'loca') ?></td>
        <td colspan='4' align="right" >
        <?php 
		if ($_POST['calcular_plusvalia']!="si") {
			print "<input type='button' name='buscviax' value='Continuar' onClick='buscaviax($codiambi)'>\n";
			print "<input type='button' name='buscviax' value='Borrar' onClick='LimpiarControles();'>\n"; 
		} else
			if ($cuota_plusvalia!="INTRODUCIR")
				print "<input type='button' value='Calcular otra plusvalía' onClick='document.location.href=\"oipl_webmogan.php\"'>\n";
	?>
        </td>
       </tr>
      </table>
    </td>
  </tr>
</table>

<div style="font-size: 12px; color: #FF6600; font-weight: bold;" align="left">
<span style="font-weight: bold; font-size: 14px;"><br>2º)</span> Introduzca los datos para el
cálculo del valor de la plusvalía.  Una vez haya completado la información, pulse el botón <b>"Calcular"</b>.
<br>
</div>

<table cellpadding=2 cellspacing=1 border=0 width=100%>
  <tr>
    <td class=derform>
        <table cellpadding=1 cellspacing=1 border=0 width=100%>
        <tr>
          <td><b>Fecha transmisión prevista</b></td>
          <td><b>Fecha transmisión anterior</b></td>
          <td><b>Cuota de adquisición</b></td>
		  <td align="right">
		  <?php
		  if ($_POST["calcular_plusvalia"]=="si") 
		  	if ($cuota_plusvalia=="INTRODUCIR") echo "<b>Introduzca el valor catastral</b>";
			else echo "<font style=\"font-size: 12px; font-weight: bold;\">Cuota a Ingresar</font>";
		  ?>
		  </td>
        </tr>
        <tr>
          <td><?php cajatexto ('_fechdeve', $_fechdeve, 'fech') ?></td>
          <td><?php cajatexto ('_fechtran', $_fechtran, 'fech') ?></td>
          <td><?php cajatexto ('_cuotadqu', $_cuotadqu, 'porc') ?></td>
		  <?php
if (($_POST["calcular_plusvalia"]=="si")&&($cuota_plusvalia!="INTRODUCIR"))
	echo "
	<td align=\"right\" style=\"border: 1px solid black; background: white; height: 20px;\">
	<font style=\"font-weight: bold; font-size: 12px; color: red;\">&nbsp;&nbsp;".impoboni($cuota_plusvalia)." &nbsp;&nbsp;</font>
	</span>
	</td>
	"; 
else {
    echo "<td align=\"right\">";
	if ($cuota_plusvalia=="INTRODUCIR")	echo "<input type=\"text\" name=\"valocatausua\" value=\"$valocatausua\"> &euro;&nbsp;";
	echo "<input style=\"font-weight: bold;\" type='button' value='Calcular' onClick=\"if (cheq()) { document.getElementById('calcular_plusvalia').value='si'; form.submit(); }\">";
	echo "</td>";
}
		  ?>
          <input type='hidden' name='_aniotran' value='<?php echo $_aniotran?>'>
          <input type='hidden' name='_hoy'value='<?php echo hoy?>'>
        </tr>
<tr>
  	<td><b>&nbsp;&nbsp;&nbsp;dd-mm-aaaa</b></td>
  	<td><b>&nbsp;&nbsp;&nbsp;dd-mm-aaaa</b></td>
  	<td><b>en porcentaje</b></td>
	<td></td>
</tr>
      </table>
    </td>
  </tr>
  </table>
<?php 
 }

}
?>
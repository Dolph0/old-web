<?
function inmueble ($estado='M')
{
  global
   $codiambi,
   $codioipl,

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
  if (!$_fechpres && !$codioipl) $_fechpres=hoy;
  
 if ($estado =='M')
 {
   $codimuni = sql ("SELECT codimuni FROM ayun WHERE codiayun='$codiambi'");
   $codiprov = sql ("SELECT codiprov FROM muni WHERE codimuni='$codimuni'");
   echo "<input type='hidden' name='codimuni' value='$codimuni'>
         <input type='hidden' name='codiprov' value='$codiprov'>";
?>
  <table cellpadding=2 cellspacing=1 border=0 width=100%>
  <tr>
    <td colspan=2 class=izqform>Identificación del inmueble</td>
  </tr>
  <tr>
    <td class='izqform' width=20%>Referencia territorio</td>
    <td class='derform'>
      <input type=hidden name='codiinmu' value="<?print $codiinmu?>">
      <table cellpadding=2 cellspacing=1 border=0 width="100%">
       <tr>
        <td><b>Vía pública</b><input type='hidden' name='tipovia' value='<?echo $tipovia?>'></td>
        <td><b>Número</b></td>
        <td>Letra</td>
        <td>Esc</td>
        <td>Planta</td>
        <td>Puerta</td>
       </tr>
       <tr>
        <td>
<?
if ($tipovia=='NUEVA') {$list='none';$box='block';}
else {$list='block';$box='none';}

echo "
      <div id='vialist' style=\"display:'$list'\"> 
       <a href=\"#\" onclick=\"alternar(vialist,viabox);form.tipovia.value='NUEVA';return false\">CALLEJERO</a>";
         echo"&nbsp;";
         $vias = sql("SELECT codiviax,vias.nomb as nombvias,siglvias.nomb as nombsigl FROM vias,siglvias WHERE codiayun='$codiambi' AND vias.codisigl=siglvias.codisigl ORDER BY nombvias");        
         print "<select class='inputform' name='codiviax' size='1'>\n";
         print "<option></option>\n";
         while ( $regi = each($vias) ) {
           $camp = $regi[value];
           print "<option value='$camp[codiviax]' ";
           if ( $camp[codiviax] == $codiviax ) { print "selected"; }
           print "> ".$camp[nombvias]." (".$camp[nombsigl].")</option>\n";
         }
         print "</select>
         </div>
         <div id='viabox' style=\"display:'$box'\">
         <a href=\"#\" onclick=\"alternar(vialist,viabox);form.tipovia.value='CALLEJERO';return false\">NUEVA</a>";
           echo"&nbsp;";
           mueslist ('codisigl', "SELECT codisigl, nomb FROM siglvias", $codisigl);
           echo"&nbsp;&nbsp;&nbsp;";
           cajatexto ('viax',$viax,'viax');
?>
         </div>
        </td>
        <td><? cajatexto ('nume', $nume, 'direnume') ?></td>
        <td><? cajatexto ('letr', $letr, 'direletr') ?></td>
        <td><? cajatexto ('esca', $esca, 'direesca') ?></td>
        <td><? cajatexto ('plan', $plan, 'direplan') ?></td>
        <td><? cajatexto ('puer', $puer, 'direpuer') ?></td>
       </tr>
       <tr>
        <td>Referencia catastral</td>
        <td>Cargo</td>
        <td>CC</td>
        <td colspan=3>Número fijo</td>
       </tr>
       <tr>
        <td><? cajatexto ('refecata', $refecata, 'cata') ?></td>
        <td><? cajatexto ('numecarg', $numecarg, 'carg') ?></td>
        <td><? cajatexto ('caracont', $caracont, 'cc') ?></td>
        <td colspan='3'><? cajatexto ('nfij', $nfij, 'nfij') ?></td>
       </tr>
       <tr>
        <td>Nombre inmueble</td>
        <td colspan=5>Identificador local</td>
       </tr>
       <tr>
        <td><? /*^cajatexto ('nombinmu', $nombinmu, 'inmu') */
               mueslist ('nombinmu', "SELECT DISTINCT nomb, nomb as nombre FROM inmu WHERE codiayun='$codiambi' ORDER BY nomb", $nombinmu) ?></td>
        <td colspan='4'><? cajatexto ('idenloca', $idenloca, 'loca') ?></td>
        <td>
        <? print "<input type='button' name='buscviax' value='Buscar' onClick='buscaviax($codiambi)'>\n"; ?>
        </td>
       </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td class=izqform width=20%>Escritura</td>
    <td class=derform>
      <table cellpadding=4 cellspacing=1 border=0 width=100%>
        <tr>
          <td><b>Nombre notario</b><input type='hidden' name='estanota' value='<?echo $estanota?>'></td>
        </tr>
        <tr>
          <td>
<?
if ($estanota=='NOREG') {$boxnota='block';$listnota='none';}
else {$boxnota='none';$listnota='block';}         
 
echo" <div id='notabox' style=\"display:'$boxnota'\">
       <a href=\"#\" onclick=\"alternar(notalist,notabox);form.estanota.value='REGIS'; return false\">NUEVO </a>";     
          cajatexto ('_nombnota', $_nombnota, 'nomb');

if (!$_nombnota2) $_nombnota2=$_nombnota;

echo"  </div><div id='notalist' style=\"display:'$listnota'\">
        <a href=\"#\" onclick=\"alternar(notalist,notabox);form.estanota.value='NOREG';return false\">REGISTRADO </a>";

        mueslist ('_nombnota2', "SELECT DISTINCT(nombnota),nombnota as nombnotaaux FROM oipl", "$_nombnota2");
?>
       </div>
       </td>
        </tr>
        </table>
        <table cellpadding=4 cellspacing=1 border=0 width=80%>
        <tr>
          <td><b>Protocolo</b></td>
          <td><b>Fecha escritura</b></td>
          <td><?if ($_vari) { echo "<b>Variación<b>";}?></td>
        </tr>
        <tr>
          <td><? cajatexto ('_prot', $_prot, 'prot') ?></td>
          <td><? cajatexto ('_fechescr', $_fechescr, 'fech') ?></td>
          <td><? echo  $_vari ?><input type='hidden' name='_vari' value='<? echo $_vari?>'></td>
        </tr>
        <tr>
          <td><b>Clase de transmisión</b></td>
          <td><b>Fecha devengo</b></td>
          <td><b>Fecha transmisión anterior</b></td>
        </tr>
        <tr>
          <td><? mueslist ('_clastran', "SELECT codiclastran, nomb FROM clastran", $_clastran) ?></td>
          <td><? cajatexto ('_fechdeve', $_fechdeve, 'fech') ?></td>
          <td><? cajatexto ('_fechtran', $_fechtran, 'fech') ?></td>
        </tr>
        <tr>
          <td>Clase de derecho</td>
          <td><b>Fecha presentación</b></td>
          <td><b>Cuota adquisición</b></td>
        </tr>
        <tr>
          <td><? mueslist ('_clasdere', "SELECT codiclasdere, nomb FROM clasdere", $_clasdere) ?>
          <td><? cajatexto ('_fechpres', $_fechpres, 'fech') ?></td>
          <td><? cajatexto ('_cuotadqu', $_cuotadqu, 'porc') ?></td>          
          <input type='hidden' name='_aniotran' value='<? echo $_aniotran?>'>
          <input type='hidden' name='_hoy'value='<?echo hoy?>'>
        </tr>
      </table>
        <table>
          <tr>
          </tr>
          <tr>
          </tr>
        </table>
    </td>
  </tr>

  <tr>
    <td class=izqform width=20%>Registro</td>
    <td class=derform>
      <table cellpadding=4 cellspacing=1 border=0>
        <tr>
          <td>Registro</td>
          <td>Tomo</td>
          <td>Libro</td>
          <td>Sección</td>
        </tr>
        <tr>
          <td><? cajatexto ('_regi', $_regi, 'regi') ?></td>
          <td><? cajatexto ('_tomo', $_tomo, 'nume') ?></td>
          <td><? cajatexto ('_libr', $_libr, 'nume') ?></td>
          <td><? cajatexto ('_secc', $_secc, 'nume') ?></td>
        </tr>
        <tr>
          <td>Folio</td>
          <td colspan=2>Finca</td>
          <td>Inscripción</td>
        </tr>
        <tr>
          <td><? cajatexto ('_foli', $_foli, 'foli') ?></td>
          <td colspan=2><? cajatexto ('_finc', $_finc, 'nume') ?></td>
          <td><? cajatexto ('_insc', $_insc, 'nume') ?></td>
        </tr>
      </table>
    </td>
  </tr>

  <tr><td>
    &nbsp;
  </td></tr>
  </table>
<? 
 }

}
?>

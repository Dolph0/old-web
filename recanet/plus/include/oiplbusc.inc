<?
// Si no existe el codioipl, lo enviamos a la pantalla de busqueda. 
 if (!$codioipl)  {echo"<meta http-equiv=\"REFRESH\" content=\"0; URL=" . cheqroot("listados/plus.php") . "\">";exit;}
 else  $oipl = sql ("SELECT * FROM oipl WHERE codioipl='$codioipl'");
 
 if (!is_array ($oipl)) {echo"<meta http-equiv=\"REFRESH\" content=\"0; URL=" . cheqroot("listados/plus.php") . "\">";exit;}
// si se ha encontrado registro empesamos asignar
  $oipl=each ($oipl);
  $oipl =$oipl[value];

  if (!isset ($codiayun)) $codiayun = $codiambi;

  $codioipl  = $oipl[codioipl];
  $nombsigl   = $oipl[sigl];
  $nombvias   = $oipl[nombviax];
  $codisigl = sql ("SELECT codisigl FROM siglvias WHERE siglviax='$oipl[sigl]'"); 

  if ($codisigl != null & $codisigl > 0) {
    $vias = sql("SELECT codiviax FROM vias WHERE nomb='$oipl[nombviax]' and codisigl=$codisigl and codiayun=$codiayun");
  } else {
    $vias = '';
  }
  if ($vias)  $codiviax= $vias;
  else $tipovia='NUEVA';

  $viax = "$oipl[nombviax]";
  $nume       = $oipl[nume];
  $letr       = $oipl[letr];
  $esca       = $oipl[esca];
  $plan       = $oipl[plan];
  $puer       = $oipl[puer];
  $refecata   = $oipl[refecata];
  $numecarg   = $oipl[numecarg];
  $caracont   = $oipl[caracont];
  $nfij       = $oipl[nfij];
  $nombinmu   = $oipl[nombinmu];
  $idenloca   = $oipl[idenloca];
  $_idxxdecl  = $oipl[decl];
  $_nombnota  = $oipl[nombnota];
  $_nombnota2 = $oipl[nombnota];
  $_prot      = $oipl[prot];
  $_vari      = $oipl[vari];
  $_regi      = $oipl[regi];
  $_tomo      = $oipl[tomo];
  $_libr      = $oipl[libr];
  $_secc      = $oipl[secc];
  $_foli      = $oipl[foli];
  $_finc      = $oipl[finc];
  $_insc      = $oipl[insc];
  $_supesola  = $oipl[supesola];
  $_cuotpart  = $oipl[cuotpart];
  $_porcredu  = $oipl[tiporedu];
  $_aniotran  = $oipl[aniotran];     
  $_clastran  = $oipl[clastran];
  $_clasdere  = $oipl[clasdere];
  $codiambi   = $oipl[codiayun]; 
  $_cuotadqu  = $oipl[cuotadqu];
  $_obse      = $oipl[obse]; 
  $_domihabi  = $oipl[domihabi]; 

  // Son fechas 
  $_fechescr  = mostrarfecha($oipl[fechescr]);
  $_fechdeve  = mostrarfecha($oipl[fechdeve]);
  $_fechpres  = mostrarfecha($oipl[fechpres]);
  $_fechtran  = mostrarfecha($oipl[fechante]); 
  
  // Son euros ... 
  $_valocatam2= $oipl[valopone]/100; 
  $_valocata  = $oipl[valocata]/100;
  $_valoapli  = $oipl[valoapli]/100;
  $_precescr  = $oipl[precdecl]/100; 

if ($_valocatam2) $_metovalo='NO';
 //  INFORMACION DE LOS SUJETOS 
 // Primero tendremos que buscarlos para despues obtener su informacion
 // Averiguamos si es una compraventa o si es donacion/herencia para mostrar los datos.

 if ($_clastran=='CVT' || $_clastran=='PER')
 {    
     $tranfigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'CNP'");
     $adqufigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'ADQ'");
 } else {  
     $tranfigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'ADQ'");
     $adqufigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'CNP'");
 }

 $reprfigu = sql("SELECT codifigu FROM tercfigu WHERE abrefigu = 'DCL'");
 
 $trancoditerc= sql ("SELECT coditerc FROM tercobje WHERE  tipoobje = 'OIPL'          AND
                                                 codiobje = '$codioipl'     AND
                                                 codifigu = '$tranfigu'");
 
 $adqucoditerc= sql ("SELECT coditerc FROM tercobje WHERE  tipoobje = 'OIPL'          AND
                                                 codiobje = '$codioipl'     AND
                                                 codifigu = '$adqufigu'");
 
 $reprcoditerc= sql ("SELECT coditerc FROM tercobje WHERE  tipoobje = 'OIPL'          AND
                                                 codiobje = '$codioipl'     AND
                                                 codifigu = '$reprfigu'");

 // Si se ha encontrado el transmitente se le asigna los valores.
if ($trancoditerc)
{
 $tran = sql ("SELECT * FROM tercdato WHERE coditerc='$trancoditerc'");
 if (is_array ($tran)) $tran = each ($tran);
  $tran=$tran[value];
 if ($tran[dire]=='e')
  $trand= sql ("SELECT * FROM direestr WHERE coditerc='$trancoditerc'");
 if ($tran[dire]=='n')
  $trand= sql ("SELECT * FROM direnoes WHERE coditerc='$trancoditerc'");

 if (is_array ($trand))
  {
    $trand=each($trand);
    $trand=$trand[value];
  }
    
    $trancoditerc= $tran[coditerc];
    $trannomb    = $tran[nomb];
    $trannifx    = $tran[nifx];
    $trancodipais= $tran[codipais];
    $tranpers    = $tran[pers];
    $trancodipost= $tran[codipost];
    $tranloca    = $tran[loca];
    $trantel1    = $tran[tel1];
    $trantel2    = $tran[tel2];
    $trancorrelec= $tran[direcorr];
    $tranaparcorr= $tran[aparcorr];
    $tranprov = sql ("SELECT codiprov FROM muni WHERE codimuni='$tran[codimuni]'");
    $tranmuni    = $tran[codimuni];
    $traninfo    = $tran[info];
    $transigl    = $trand[siglviax];
    $trandire    = $trand[nomb];
    $trannume    = $trand[nume];
    $tranletr    = $trand[letr];
    $tranesca    = $trand[esca];
    $tranplan    = $trand[plan];
    $tranpuer    = $trand[puer];
}


 // Si se ha encontrado el adquirente se le asigna los valores.
if ($adqucoditerc)
{
 $adqu = sql ("SELECT * FROM tercdato WHERE coditerc='$adqucoditerc'");
 if (is_array ($adqu)) $adqu = each ($adqu);
  $adqu=$adqu[value];
 if ($adqu[dire]=='e')
  $adqud= sql ("SELECT * FROM direestr WHERE coditerc='$adqucoditerc'");
 if ($adqu[dire]=='n')
  $adqud= sql ("SELECT * FROM direnoes WHERE coditerc='$adqucoditerc'");

 if (is_array ($adqud))
  {
    $adqud=each($adqud);
    $adqud=$adqud[value];
  }

    $adqucoditerc= $adqu[coditerc];
    $adqunomb    = $adqu[nomb];
    $adqunifx    = $adqu[nifx];
    $adqucodipais= $adqu[codipais];
    $adqupers    = $adqu[pers];
    $adqucodipost= $adqu[codipost];
    $adquloca    = $adqu[loca];
    $adqutel1    = $adqu[tel1];
    $adqutel2    = $adqu[tel2];
    $adqucorrelec= $adqu[direcorr];
    $adquaparcorr= $adqu[aparcorr];
    $adquprov = sql ("SELECT codiprov FROM muni WHERE codimuni='$adqu[codimuni]'");
    $adqumuni    = $adqu[codimuni];
    $adquinfo    = $adqu[info];
    $adqusigl    = $adqud[siglviax];
    $adqudire    = $adqud[nomb];
    $adqunume    = $adqud[nume];
    $adquletr    = $adqud[letr];
    $adquesca    = $adqud[esca];
    $adquplan    = $adqud[plan];
    $adqupuer    = $adqud[puer];
}


  // Si se ha encontrado al representante o al declarante se le asigna los valores.
if ($reprcoditerc)
{
 $repr = sql ("SELECT * FROM tercdato WHERE coditerc='$reprcoditerc'");
 if (is_array ($repr)) $repr = each ($repr);
  $repr=$repr[value];
 if ($repr[dire]=='e')
  $reprd= sql ("SELECT * FROM direestr WHERE coditerc='$reprcoditerc'");
 if ($repr[dire]=='n')
  $reprd= sql ("SELECT * FROM direnoes WHERE coditerc='$reprcoditerc'");

 if (is_array ($reprd))
  {
    $reprd=each($reprd);
    $reprd=$reprd[value];
  }

    $reprcoditerc= $repr[coditerc];
    $reprnomb    = $repr[nomb];
    $reprnifx    = $repr[nifx];
    $reprcodipais= $repr[codipais];
    $reprpers    = $repr[pers];
    $reprcodipost= $repr[codipost];
    $reprloca    = $repr[loca];
    $reprtel1    = $repr[tel1];
    $reprtel2    = $repr[tel2];
    $reprcorrelec= $adqu[correlec];
    $repraparcorr= $adqu[aparcorr];
    $reprprov = sql ("SELECT codiprov FROM muni WHERE codimuni='$repr[codimuni]'");
    $reprmuni    = $repr[codimuni];
    $reprinfo    = $repr[info];
    $reprsigl    = $reprd[siglviax];
    $reprdire    = $reprd[nomb];
    $reprnume    = $reprd[nume];
    $reprletr    = $reprd[letr];
    $represca    = $reprd[esca];
    $reprplan    = $reprd[plan];
    $reprpuer    = $reprd[puer];
}

  // para terminar mostramos las nuevas opciones en el menu. 
$tipoobje='OIPL';
$conc='C'. sql ("SELECT codiconc FROM conctrib WHERE codiayun='$codiambi' AND abre='IPLUSVA'");
$codiobje=$codioipl;
opci ("Eliminar:Liquidar:Crear:Modificar:Limpiar");
   echo"
<center>
";
mostrartodo ();
?>

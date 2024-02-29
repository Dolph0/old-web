<?
 function errores ($MENSAJE, $sql='') {
   if ($MENSAJE) {
     opci ("Crear:Limpiar");

     echo "
       <table width=100%>
         <tr><td class=tituform>** ERROR **</td></tr>
         <tr><td class=derform>$MENSAJE</td></tr>
         <tr><td class=cabeform>Los datos no se han guardado</td></tr>
         <tr><td>&nbsp</td></tr>
       </table>";

      mostrartodo();

      if ($sql) sql ($sql);

      fin();
   } else {
     return 0;
   }
 }

 function tipoerror ($mensaje, $id, $consulta='', $info='') {
   if (!$consulta && !$info) {
     return $mensaje;
   } else {
     return "<a href=\"javascript:alternar(".$id."_sql,".$id."_txt)\">$mensaje</a>
                <div id=".$id."_sql style=display:none>$consulta</div>
                <div id=".$id."_txt style=display:block>$info</div>";
   }
 }

 //COMPROBACION QUE SE PUEDA GRABAR...
 //ESTA FRAGMENTO TENDRIA QUE SOBRAR PQ SE CHEQUEA EN JAVASCRIPT ... PERO NUNCA SE SABE...
 //-->
 $MENSAJE ="";

 //Comprobacion del inmueble:
 if ($tipovia =="NUEVA") {
      if ($viax == "") $MENSAJE .= " ·Vía pública del inmueble<br>\n";
      if ($codisigl == "") $MENSAJE .= " ·Vía pública del inmueble<br>\n";
 }

 if ($tipovia =="CALLEJERO") if ($codiviax =="")   $MENSAJE .= " .Vía pública del inmueble<br>\n";
 if ($tipovia !="NUEVA" && $tipovia !="CALLEJERO") $MENSAJE .= " ·ERROR EN LA VIA<br>\n";
 if ($nume =="")                                   $MENSAJE .= " ·Número del inmueble.<br>\n";
 if ($estanota =="REGIS")if ($_nombnota2 =="")     $MENSAJE .= " ·Notario<br>\n";
 if ($estanota =="NOREG")if ($_nombnota =="")      $MENSAJE .= " ·Notario<br>\n";
 if ($estanota !="REGIS" && $estanota !="NOREG")   $MENSAJE .= " ·ERROR EN EL NOTARIO<br>\n";
 if ($_prot =="")                                  $MENSAJE .= " ·Protocolo <br>\n";
 // if ($_vari =="")                                  $MENSAJE .= " ·Variación <br>\n";
 if ($_fechescr =="")                              $MENSAJE .= " ·fecha transmisión <br>\n";

 //Comprobacion del tipo de transmision
 if ($_clastran =="") $MENSAJE .= " ·Clase Transmision <br>\n";

 //Segun el tipo transmision chequeo del sujeto.
 if ($_clastran =="CVT" || $_clastran =="PER") {
   if ($trannomb =="") $MENSAJE .= " ·Nombre del Transmitente<br>\n";
   if ($trannifx =="") $MENSAJE .= " ·NIF del Transmitente<br>\n";
   if ($transigl =="") $MENSAJE .= " ·Sigla del Transmitente<br>\n";
   if ($trandire =="") $MENSAJE .= " ·Nombre de la vía del Transmitente<br>\n";
 } else {
   if ($adqunomb =="") $MENSAJE .= " ·Nombre del Adquirente<br>\n";
   if ($adqunifx =="") $MENSAJE .= " ·NIF del Adquirente<br>\n";
   if ($_domihabi =="0") {
     if ($adqusigl =="") $MENSAJE .= " ·Sigla del Adquirente<br>\n";
     if ($adqudire =="") $MENSAJE .= " ·Nombre de la vía del Adquirente<br>\n";
   }
 }

 //chequeo de la direccion del adquirente en caso de no vivira en el piso que compra.
 if ($_domihabi =="0") {
   if ($adqusigl =="") $MENSAJE .= " ·Sigla del Adquirente<br>\n";
   if ($adqudire =="") $MENSAJE .= " ·Nombre de la vía del Adquirente<br>\n";
 }

 //chequeo del representante en caso de existir.
 if ($_idxxdecl =="RT" || $_idxxdecl=="RA") {
   if ($reprnomb =="") $MENSAJE .= " ·Nombre del Declarante<br>\n";
   if ($reprnifx =="") $MENSAJE .= " ·NIF del Declarante<br>\n";
   if ($reprsigl =="") $MENSAJE .= " ·Sigla del Declarante<br>\n";
   if ($reprdire =="") $MENSAJE .= " ·Nombre de la vía del Declarante<br>\n";
 }

 // chequeo de las fechas
 if (compfech($_fechescr,$_fechtran)==2) $MENSAJE.= "<b>La Fecha de transmisión Actual no puede ser menor que la Anterior<b><br>";

 // chequeo en caso de que se ponga en Valor catastral del suelo "Ponencia de valores";
 if ($_metovalo == 'NO') {
   if ($_supesola   == "") $MENSAJE .= " ·Superficie del solar\n";
   if ($_valocatam2 == "") $MENSAJE .= " ·Valor catastral del suelo / m²\n";
   if ($_cuotpart   == "") $MENSAJE .= " ·Cuota participación de elementos comunes\n";
 }

 //<---
 $vias = sql("SELECT vias.nomb as nombvias,siglvias.siglviax as nombsigl
              FROM vias,siglvias
              WHERE codiayun='$codiambi'
                AND vias.codisigl=siglvias.codisigl
                AND codiviax='$codiviax'
              ORDER BY nombvias");

 if (is_array ($vias)) {
   $vias = each ($vias);
   $vias = $vias [value];
 }

 if ($tipovia=='NUEVA') {
   if ($codisigl!='') {
     $nombsigl=sql("SELECT siglviax FROM siglvias WHERE codisigl=$codisigl");
     $nombvias=$viax;
   }
 }

 if ($tipovia=='CALLEJERO') {
   $nombsigl=$vias[nombsigl];
   $nombvias=$vias[nombvias];
 }

 if ($estanota=='NOREG') $notario=$_nombnota;
 if ($estanota=='REGIS') $notario=$_nombnota2;

 if (empty($_vari)) $_vari=1;

  $G_fechescr=guardarfecha($_fechescr);
  $codioipl = sql ("SELECT codioipl
                   FROM oipl
                   WHERE nombnota = '$notario'
                   AND prot       = '$_prot'
                   AND vari       = $_vari
                   AND fechescr   = '$G_fechescr'");


  if (!$codioipl && $opci=="Modificar"){
    $MENSAJE .= " ·No existe la plusvalía que desea actualizar\n";
  }

 //GENERAMOS UNA CONSULTA DE ACTUALIZACION PORQUE EL CODIGO OIPL SE INSERTA MANUALMENTE.
 $query = "LOCK TABLE oipl IN EXCLUSIVE MODE; UPDATE oipl SET ";

 if ($nombsigl) $query.=" sigl     = '$nombsigl',";
 if ($nombvias) $query.=" nombviax = '$nombvias',";
 if ($nume)     $query.=" nume     = '$nume',";

 $query.=" letr     = '$letr',";
 $query.=" esca     = '$esca',";
 $query.=" plan     = '$plan',";
 $query.=" puer     = '$puer',";
 $query.=" refecata = '$refecata',";
 $query.=" numecarg = '$numecarg',";
 $query.=" caracont = '$caracont',";
 $query.=" nfij     = '$nfij',"    ;
 $query.=" nombinmu = '$nombinmu',";
 $query.=" idenloca = '$idenloca',";
 $query.=" decl     = '$_idxxdecl',";

 if ($notario) $query.=" nombnota = '$notario',";
 if ($_prot)   $query.=" prot     = '$_prot',";

 if (empty($_vari)) $_vari=1;
 else{
   if ($opci=='Crear'){
     //Obtenermos el siguiente valor de vari;
      $_vari=sql("LOCK TABLE oipl IN EXCLUSIVE MODE; SELECT max(vari) FROM oipl WHERE nombnota = '$_nombnota' AND prot = '$_prot' AND fechescr = '$G_fechescr'");
      $_vari++;
   }
 }

 if ($_vari)   $query.=" vari = $_vari,";
 if ($_regi)   $query.=" regi = '$_regi',";

 $query.=" tomo = '$_tomo',";
 $query.=" libr = '$_libr',";
 $query.=" secc = '$_secc',";
 $query.=" foli = '$_foli',";
 $query.=" finc = '$_finc',";
 $query.=" insc = '$_insc',";

  if ($_cuotpart) $query.=" cuotpart = $_cuotpart,";

 $query.=" clastran = '$_clastran',";
 $query.=" clasdere = '$_clasdere',";
 $query.=" codiayun = $codiambi,";
 if ($_cuotadqu) $query.=" cuotadqu = $_cuotadqu,";
 if ($_domihabi) $query.=" domihabi = $_domihabi,";
 $query.=" obse     = '$_obse',";

 if ($sesi[sesicodiusua]) $query.=" usuamodi = $sesi[sesicodiusua],";
 if ($_supesola)      $query.=" supesola = $_supesola,";

 // NECESITAN SER CAMBIADOS PARA QUE NO DEN PROBLEMAS EN LA BASE DE DATOs.
 if ($_fechescr)   {$fech1=guardarfecha ($_fechescr);  $query.=" fechescr = '$fech1',";}
 if ($_fechpres)   {$fech2=guardarfecha ($_fechpres);  $query.=" fechpres = '$fech2',";}
 if ($_fechtran)   {$fech3=guardarfecha ($_fechtran);  $query.=" fechante = '$fech3',";}
 if ($_fechdeve)   {$fech4=guardarfecha ($_fechdeve);  $query.=" fechdeve = '$fech4',";}
 if ($_valocatam2) {$valo1=euro2cent    ($_valocatam2);$query.=" valopone = $valo1,";}
 if ($_precescr)   {$valo5=euro2cent    ($_precescr);  $query.=" precdecl = $valo5,";}

 // OTROS CALCULOS..
 //CALCULO DE LOS AÑOS TRANSCURRIDOS.
 // El abs() para numeMeses() es por si obtenemos un valor negativo, que se da
 $_aniotran= floor (abs (numeMeses ($fech3,$fech4)) / 12);

 if ($_aniotran) $query.=" aniotran = $_aniotran,";

 //CALCULO DEL PORCENTAJE.
 //Segun los años transcurridos calculamos el porcentaje del periodo,
 $porcperi = porcperiplus ($codiambi, $fech4, $_aniotran);

 // La funcion devuelve -1 si hubo algun error
 if ($porcperi == -1) {
   $E_PORCPERI="<p>CALCULANDO EL PORCENTAJE DEL PERIODO";
 }

 if ($E_PORCPERI) $MENSAJE.= tipoerror ($E_PORCPERI, 'e_porcperi', 'porcperiplus ('.$codiambi.', '.$fech1.', '.$_aniotran.')');

 //CALCULO DEL VALOR CATASTRAL DEL SUELO. SEGUN METODO.
 // SI = PADRON
 // GENERAMOS EL CODIGO OBJETO QUE ES:
 // Referencia catastral
 // 1 caracter de la escalera
 // 2 caracteres de la planta
 // 2 caracteres de la puerta
 // teneiendo en cuenta dos casos muy particulares
 // que el valor obtenido de la planta sea = B0 que lo igualamos a 00
 // que la suma de esca+plant+puert es = SUELO lo cambiamos por SOLAR
 // en caso de que alguna de ellas esten vacias se ponen los espacios en blancos.
 // Obtenemos el año
 // En caso de no encontrar nada en la base de datos, quitamos un año
 // Y volvemos a ralizar la busqueda.
 // En caso de no volver a encontrar nada. Salimos de la ejecución, no se encuentra el valor del suelo.
 // consultamos el valor del suelo para los resultados obtenidos.
 // en caso de que se cree la variable aniomenos, tenemos que adaptar el valor del suelo
 // al año presente.

 if (!$valocatausua) {
   if ($_metovalo =='SI') {
     $plantita = substr($plan,0,2);

     if ($plantita =='B0') $plantita ='00';
     if (!$plantita) $plantita='  ';

     $escalerita = substr($esca,0,1);
     if (!$escalerita) $escalerita=' ';

     $puertita = substr($puer,0,2);
     if (!$puertita) $puertita='  ';

     $comprobar= $escalerita.$plantita.$puertita;

     if ($comprobar == 'SUELO') $comprobar = 'SOLAR';

     $codiobje=$refecata.$comprobar;

     // AQUI HAY UN PARCHE: el valor catastral del suelo se determina a partir del cargo del inmueble
     // en cargoibiurba para el mismo año que el de la fecha de devengo o el año anterior.
     // Pero hay problemas con la busqueda de dicho cargo de IBIurbana. A partir del 2002, el codigo
     // tributario de un objeto de urbana (carg.codiobje) se representaba como la concatenacion de
     // refecata.numecarg.caracont del inmueble.
     // Pero antes del 2002 se guardaba dicho codiobje como refecata.esca.plan.puer
     // Por tanto, el criterio de busqueda del cargo depende del año contraido, para saber que
     // criterio emplear en el codigo tributario del objeto.

     // Entonces, se busca en carg para carg.codiobje='$codiobje' cuando el año sea igual a 2001
     // o anterior; y se busca en carg por carg.codiobje='$codiobje2' cuando el año sea igual a 2002
     // o posterior.
     $codiobje2 = $refecata.$numecarg.$caracont;

     $aniomeno = '';
     $anio = substr ($fech4,0,4);

     $sql_valocata = "SELECT codiayun, codiconc, ejer, numedocu
                      FROM carg
                      WHERE estacont != 'ANU'
                        AND periliqu = 'PA'
                        AND tipoobje = 'OIBIURBA'
                        AND anio = '$anio' ";

     // A continuacion, el parche. El parche se repite unas lineas mas abajo,
     // cuando se busca en carg por el año anterior.
     if ($anio <= 2001) {
       $sql_valocata .= " AND codiobje = '$codiobje' 
                          AND fechcarg = (SELECT MAX (fechcarg)
                                          FROM carg
                                          WHERE estacont != 'ANU'
                                            AND periliqu = 'PA'
                                            AND tipoobje = 'OIBIURBA'
                                            AND anio = '$anio' 
                                            AND codiobje = '$codiobje')";
     } else {
       $sql_valocata .= " AND codiobje = '$codiobje2' 
                          AND fechcarg = (SELECT MAX (fechcarg)
                                          FROM carg
                                          WHERE estacont != 'ANU'
                                            AND periliqu = 'PA'
                                            AND tipoobje = 'OIBIURBA'
                                            AND anio = '$anio' 
                                            AND codiobje = '$codiobje2')";
     }

     $regi = sql ($sql_valocata);

     if (!$regi) {
       $aniomeno = $anio-1;
       $sql_valocata = "SELECT codiayun, codiconc, ejer, numedocu
                        FROM carg
                        WHERE estacont != 'ANU'
                          AND periliqu = 'PA'
                          AND tipoobje = 'OIBIURBA'
                          AND anio = '$aniomeno'";

       // Aqui tenemos el parche por segunda y ultima vez, ya que la consulta se repite para el año anterior.
       if ($aniomeno <= 2001) {
         $sql_valocata .= " AND codiobje = '$codiobje' 
                            AND fechcarg = (SELECT MAX (fechcarg)
                                            FROM carg
                                            WHERE estacont != 'ANU'
                                              AND periliqu = 'PA'
                                              AND tipoobje = 'OIBIURBA'
                                              AND anio = '$aniomeno' 
                                              AND codiobje = '$codiobje')";
       } else {
         $sql_valocata .= " AND codiobje = '$codiobje2' 
                            AND fechcarg = (SELECT MAX (fechcarg)
                                            FROM carg
                                            WHERE estacont != 'ANU'
                                              AND periliqu = 'PA'
                                              AND tipoobje = 'OIBIURBA'
                                              AND anio = '$aniomeno' 
                                              AND codiobje = '$codiobje2')";
       }

       $regi = sql ($sql_valocata);
     }

     if (is_array ($regi)) {
       $regi=each ($regi);
       $valor=$regi[value];
     }

     // ERROR
     if (!$regi) $E_VALOCATA.="NO SE HA PODIDO OBTENER EL VALOR CATASTRAL ";
     if (!$regi) $valor_E.="Cual es el valor catastral de $codiobje <br>
                            <input type=\"text\" name=\"valocatausua\" value=\"$valocatausua\"> &euro;";
     if ($E_VALOCATA) $MENSAJE.= tipoerror ($E_VALOCATA, 'e_valocata',$valor_E,'');

     if (is_array ($regi)) {
       $_valocata = sql ("SELECT cargoibiurba.valosuel
                          FROM cargoibiurba WHERE codiayun = '$valor[codiayun]'
                           AND codiconc = '$valor[codiconc]'
                           AND ejer = '$valor[ejer]'
                           AND numedocu = '$valor[numedocu]'");
     }

     if ($aniomeno) {
       $reviurba = sql ("select reviurba from ayun where codiayun=$codiambi");
       if (!($reviurba!="" && $reviurba>1997 && $reviurba<=$anio && $anio<=$reviurba+9))
       {
          $incremento = sql ("select incrurba from valoanua where anio=$anio order by anio");
      $_valocata =  euro2cent ((($_valocata*($incremento/100))+$_valocata)/100);
       }
     }
   }
 }

 // Si fue el usuario el que puso el valo catastral tenemos que pasarlo
 // a centimos de euros..
 if ($valocatausua) $_valocata= euro2cent ($valocatausua);

 // CALCULO DEL VALOR CATASTRAL DEL SUELO. SEGUN METODO.
 // NO = PONENCIA DE VALORES
 // En este caso no tiene mas ciencia que aplicar la siguiente formula.
 if ($_metovalo =='NO') $_valocata = round ($_supesola*$_valocatam2*$_cuotpart);

 # if ($_valocata) echo "El VALOR CATASTRAL DEL SUELO ES DE: $_valocata <br>";

 // Antes de insertar el valor catastral tenemos que pasarlo a centimos de euros.
 if ($_valocata) {
   $query.=" valocata = $_valocata,";
 }

 $anio = substr($_fechdeve,-4);
 $dedu = sql ("SELECT deduvalosuel
               FROM valoanua
               WHERE anio=$anio");

 // Si existe deduvalosuel lo guardamos en  la tabla OIPL.
 if ($dedu) $query.=" tiporedu = $dedu,";

 $dedu = $dedu /100;

 // pasamos el valor catastras a euros, para poder despues redondear con
 // la funcion euro2cent el valor que nos va a mostrar.
 $_valoapli = euro2cent (($_valocata/100)*(1-$dedu));

 if ($_valoapli)$query.=" valoapli = $_valoapli,";

 if ($porcperi) $query.=" porcperi = $porcperi,";
 if ($query)    $query.=" rutimodi = 'GESTOIPL'";



 // ANTES DE CONTINUAR COMPROBAMOS QUE NO SE HA PRODUCIDO NINGUN ERROR EN TODOOO EL PROCESO.
 errores ($MENSAJE);
 $MENSAJE='';

 // A esta altura se ha generado por completo la consulta que queremos introducir.
 // Lo primero antes de continuar es comprobar que no existe otro igual, ya que contamos
 // con cuatro campos identificativos para cada registro:
 // - Nombre notario oipl.nombnota
 // - Protocolo oipl.prot
 // - Variacicion oipl.vari
 // - Fecha de transmision

 $G_fechescr = guardarfecha($_fechescr);

 $codioipl = sql ("SELECT codioipl
                   FROM oipl
                   WHERE nombnota = '$notario'
                     AND prot     = '$_prot'
                     AND vari     = $_vari
                     AND fechescr = '$G_fechescr'");


 if (!$codioipl || is_array ($codioipl)) {
   if (!$codioipl = sql ("SELECT codioipl FROM oipl WHERE nombnota=''")) {
     $codioipl = sql ("SELECT max (oipl.codioipl)")+1;

     sql ("INSERT INTO oipl (codiayun, codioipl) VALUES ($codiambi, $codioipl)");
   }
 }


 // Con el codigo generamos la ultima parte de la consulta de actualizacion.
 if ($codioipl) {
   $query.=" WHERE codioipl=$codioipl";
 } else {
   errores ("FALLO NO SE HA GENERADO EL CODIGO CORRECTAMENTE");
 }

 // Y aunque sea un update introducimos todos los datos.
 sql ($query);
 $query = '';

 // Tratamos los sujetos.
 global $ERRORSQL;

 $trancoditerc = domicilio ("tran","C",$codioipl);
 $E_suje.=$ERRORSQL."<br>";
 $adqucoditerc = domicilio ("adqu","C",$codioipl);
 $E_suje.=$ERRORSQL."<br>";

 if ($_idxxdecl =="RT" || $_idxxdecl=="RA") {
   $reprcoditerc = domicilio ("repr","C",$codioipl);
 } else {
   $reprcoditerc=1;
 }

 if ($ERRORSQL) {
   if ($codioipl) {
     sql ("DELETE FROM oipl     WHERE codioipl='$codioipl';
           DELETE FROM tercobje WHERE codiobje='$codioipl' AND tipoobje='OIPL';");
   }

   if ($trancoditerc == FALSE) $MENSAJE.=' el Transmitente<br>';
   if ($adqucoditerc == FALSE) $MENSAJE.=' el Adquirente <br>';
   if ($reprcoditerc == FALSE) $MENSAJE.=' el Representante<br>';

   $MENSAJE="FALLO AL INTENTAR CREAR LA RELACION:<br> Plusvalía con<br> $MENSAJE $E_suje";
 }

 // Si se ha producido un error detenemos la ejecucion y borramos
 // Lo que se ha introducido hasta el momento.
 $opci = "GuardarOK";

 if ($MENSAJE) {
   errores ($MENSAJE,$borrar);
 } else {
   $mensaje = "Registro guardado";
 }

 //PARA LA LIQUIDACION ASIGNAMOS A CODIOBJE EL VALOR DE CODIOIPL
 $codiobje = $codiopl;

 $tipoobje='OIPL';
 $conc = 'C'.sql ("SELECT codiconc FROM conctrib WHERE codiayun='$codiambi' AND abre='IPLUSVA'");
 $codiobje = $codioipl;

 opci ("Liquidar:Modificar:Crear:Eliminar:Limpiar");

 mens ("Registro guardado");

 echo " <center> ";

 mostrartodo();
?>
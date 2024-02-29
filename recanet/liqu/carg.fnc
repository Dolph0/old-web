<?
include_once "comun/fecha.fnc";

//--------------------------------------------------------------
// *** IMPORTANTE: ***
//    A partir del 1-MARZO-2007, los documentos solo se pueden 
// anular explicitamente (Otras bajas contables). Desde esta 
// fecha los documentos pasan a: 
//         SUSP. PROVISIONAL - PENDIENTE DE ANULAR (SPR - PAN)
//--------------------------------------------------------------
function anulexis( $codiayun, $anio, $codiconc, $periliqu, $abreobje, $usuacarg ) {
  // Anular los cargos ya existentes del objeto, que esten Pendientes de
  // Cobro, en el mismo año contraido y periodo de liquidacion
  $hora = date( "H:i:s", time() );
  $fech = date( "Y-m-d",time());
  // Busco los cargos a anular para determinar el periodo contable (pericont), a partir de 
  // su fecha final de voluntaria. Si no tiene fecha final, o es posterior o igual 
  // al dia de hoy, pericont es 'V' de voluntaria. Si no, es 'E' de ejecutiva
  // Además, busco el campo estasusp, porque si está en suspensión, al anular el cargo
  // le quitamos la suspensión.
  $query = "SELECT carg.numedocu, carg.ejer, carg.fechfinavolu, carg.estasusp 
            FROM carg 
            WHERE carg.codiayun = $codiayun 
              AND carg.anio = '$anio' 
              AND carg.codiconc = $codiconc 
              AND carg.codiobje = '$abreobje' 
              AND carg.estacont = 'PEN' 
              AND carg.estasusp = 'STR' ";
              
  if ($periliqu == ''){
    $query .= " AND (carg.periliqu = '' OR carg.periliqu IS NULL);";
  }else{
    $txt_aux = '';
    if ($periliqu == 'PA') $txt_aux = ",'P1','P2'";
    $query .= " AND carg.periliqu IN ('$periliqu'$txt_aux);";
  }
  
  $resu = sql( $query );
  if ( is_array( $resu ) ) {
    // Concateno en una sola ristra los querys de cada cargo a anular
    $query = "";
    while( $regi = each($resu) ) {
      $regi = $regi[value];
  
      // Compruebo si está en suspensión, para ponerlos sin tramitar al anular el cargo.
      $query .= "UPDATE carg SET estasusp = 'SPR', caussusp = 'PAN', datosusp = 'POR LIQUIDACION', fechestasusp = '$fech', fechsusp = '$fech', horasusp = '$hora', usuasusp = '$usuacarg', procsusp = 'LIQU' ";
      $query .= "WHERE carg.codiayun = $codiayun AND carg.ejer = '$regi[ejer]' AND carg.numedocu = '$regi[numedocu]' AND carg.codiconc = $codiconc;";
    }
    
    if ( $query != "" ) { sql( $query ); }
  }
}

function insecarg( $vect, $cheq, $tipo, $mostrar = 1, $fecha_del_cargo = '0001-01-01') {

// Consulta sql para insertar el cargo de la deuda obtenida para un objeto concreto.
// Se inserta un cargo, si no existe ya, o bien, si existe y se desea anular el existente.

// $vect contiene los campos del cargo actual a insertar.
// $cheq indica si se anula el cargo ya existente, para insertar uno nuevo
// $tipo es el tipo del estado contable del cargo existente, por ejemplo, "I" es un ingreso
// $view nos indica si se debe mostrar resultados de salida

  // Usuario que genera el cargo
  global $codiusua;
  // Las siguientes variables globales son recibidas en carg.php por formulario
  global $codiayun, $modoliqu, $periliqu, $tipoobje, $fechinicvolu, $fechfinavolu;
  global $impoingr, $refeingr, $fechingr;
  
  // El redondeo de la deuda y la cuota se realiza en la funcion calcdeud (en deuda.php)
  $deud = $vect[deud] * 1; // Multiplicamos por 1 para evitar casos como 1.2E+6

  // Campos comunes a todos los objetos
  $vect[usuacarg] = $codiusua;

  $vect[ejer] = date('Y', time());  // Año actual
  $vect[estacont] = "PEN";          // Pendiente de cobro
  $vect[estanotiprov] = "STR";      // Sin tramitar
  $proccarg = "LIQU";

  // Campos dependientes de la modalidad
  $fechhoy = date( "Y-m-d",time());
  if ( ( $modoliqu == "PER" ) || ( $modoliqu == "AUT" ) ) {
    $vect[fechnotivolu] = $fechhoy;
    $vect[fechvolu] = $fechhoy;
    $vect[horavolu] = date( "H:i:s", time() );
    $vect[usuavolu] = $vect[usuacarg];
    $vect[procvolu] = "LIQU";
    if ( $modoliqu == "PER" ) {
      // Liquidacion periodica
      $vect[estanotivolu] = "NCL";
      $vect[fechinicvolu] = Guardarfecha( $fechinicvolu );
      $vect[fechfinavolu] = Guardarfecha( $fechfinavolu );
    }
    if ( $modoliqu == "AUT" ) {
      // Autoliquidacion
      $vect[estanotivolu] = "NPE";
      $vect[fechinicvolu] = "0001-01-01";
      $vect[fechfinavolu] = "0001-01-01";
    }
  } else {
    $vect[fechnotivolu] = "0001-01-01";
    $vect[fechvolu] = "0001-01-01";
    $vect[horavolu] = "00:00:00";
    $vect[usuavolu] = 0;
    $vect[procvolu] = "";
    $vect[estanotivolu] = "STR";
    $vect[fechinicvolu] = "0001-01-01";
    $vect[fechfinavolu] = "0001-01-01";
  }


  // Anular los cargos pendientes de cobro, cuyo checkbox fue marcado
  if ( $cheq && ( $tipo == "P" || substr($modoliqu,0,1) == "D" ) ) {
    // Anular los cargos ya existentes del objeto, que esten Pendientes de
    // Cobro, en el mismo año contraido y periodo de liquidacion
    anulexis( $codiayun, $vect[anio], $vect[codiconc], $periliqu, $vect[abreobje], $codiusua );
  }

  $codifigu = sql( "SELECT tercfigu.codifigu FROM tercfigu WHERE tercfigu.abrefigu = 'CNP'" );

  // Solo para plusvalía, comprobamos si el Contribuyente Principal es estranjero, 
  // en ese caso el Contribuyente Principal sería el Adquirente, pero solo para las
  // CVT, EXT, DAC, CAP, SUB, LIS, SEN, ABS, DIS, ADJ y FUS.
  if ( $tipoobje == "OIPL" ) {
      $clastran= sql ("SELECT oipl.clastran FROM oipl WHERE oipl.codioipl = $vect[codiobje]"); 
      if ($clastran=="CVT" || $clastran=="EXT" || $clastran=="DAC" || $clastran=="CAP" ||
          $clastran=="SUB" || $clastran=="LIS" || $clastran=="SEN" || $clastran=="ABS" ||
          $clastran=="DIS" || $clastran=="ADJ" || $clastran=="FUS"){
         $coditerc= sql ("SELECT tercobje.coditerc FROM tercobje WHERE tercobje.tipoobje = 'OIPL' AND tercobje.codiobje = $vect[codiobje] AND tercobje.codifigu = $codifigu");
         $codipais = sql ("SELECT tercdato.codipais FROM tercdato WHERE tercdato.coditerc = $coditerc");
         $codiespa = sql ("SELECT pais.codipais FROM pais where pais.nomb='ESPAÑA'");
         if ($codipais!=$codiespa)   
            $codifigu = sql("SELECT tercfigu.codifigu FROM tercfigu WHERE tercfigu.abrefigu = 'ADQ'");
      }
  }
  $tipoobjeaux = $tipoobje;
  
  //Bloqueamos tercdato
  $selectnifx  = "SELECT tercdato.nifx FROM tercdato, tercobje WHERE tercobje.coditerc = tercdato.coditerc and tercobje.tipoobje = '$tipoobjeaux' and tercobje.codiobje = $vect[codiobje] AND tercobje.codifigu = $codifigu";
  $query = $selectnifx . " FOR UPDATE;";
  
  if ( $tipoobje == 'OIBIURBAHIST'){
  	$tipoobjeaux = 'OIBIURBA';
  	$selectnifx = "'" . $vect[nifx] . "'";
  	$query = '';
  }
  if ( $tipoobje == 'OIBIRUSTHIST'){
  	$tipoobjeaux = 'OIBIRUST';
  	$selectnifx = "'" . $vect[nifx] . "'";
  	$query = '';
  }
  	
  // Solucionamos los casos como 1.2E+6
  $pos = strpos($deud, 'E+');
  if ($pos){
    $parte1 = substr($deud, 0, $pos);
    $parte2 = '1'.substr($deud, $pos);
    $deud = intval($parte1 * $parte2);
  }

  // Insertar el cargo en la tabla carg
  // Añado la fecha del cargo si se pasa por parametro, en otro caso
  // ya se toma la fecha actual desde BBDD
  $query .= "INSERT INTO carg (codiayun, modoliqu, periliqu, ejer, codiconc, anio, numedocu,
            nifx, codiobje, tipoobje, usuacarg, numecarg, deud, domibanc, estanotivolu, fechvolu, 
            horavolu, usuavolu, procvolu, fechinicvolu, fechfinavolu, estacont, refeno60,  
            estanotiprov,fechnotivolu,proccarg ";
  
  if ($fecha_del_cargo != '0001-01-01') $query .= ", fechcarg";
  
  $query .= " ) VALUES ('$codiayun', '$modoliqu', '$periliqu', 
            '$vect[ejer]', '$vect[codiconc]', '$vect[anio]', '', ($selectnifx), '$vect[abreobje]',
            '$tipoobjeaux', '$vect[usuacarg]', '0', '".number_format ($deud, 0, '', '')."', '$vect[domibanc]', 
            '$vect[estanotivolu]', '$vect[fechvolu]', '$vect[horavolu]', '$vect[usuavolu]', 
            '$vect[procvolu]', '$vect[fechinicvolu]', '$vect[fechfinavolu]', '$vect[estacont]', 
            '$vect[refeno60]',  
            '$vect[estanotiprov]', '$vect[fechnotivolu]','$proccarg'";
  
  if ($fecha_del_cargo != '0001-01-01') $query .= ", '$fecha_del_cargo'";
  
  $query .= ");";
  sql( $query);

  // Obtengo el campo numedocu del registro insertado, el cual se lo asigna un
  // procedimiento almacenado
  $query = "SELECT carg.numedocu FROM carg WHERE carg.codiayun = $codiayun AND carg.ejer = '$vect[ejer]' AND carg.codiconc = $vect[codiconc] AND carg.codiobje = '$vect[abreobje]' AND carg.tipoobje = '$tipoobjeaux' AND carg.estacont = '$vect[estacont]'";
  if ($periliqu == ''){
     $query .= " AND (carg.periliqu = '' OR carg.periliqu IS NULL)";
  }else{
      $query .= " AND carg.periliqu = '$periliqu'";
  }
  $numedocu = sql($query);

  // Puede que hayan mas de un cargo igual, pero con distinto numedocu. Entonces la consulta anterior 
  // devuelve un array de numedocus, de los que solo hay que coger el mayor.
  // Si no hay varios cargos, la consulta anterior devuelve el numedocu esperado.
  if ( is_array( $numedocu ) ) {
    $nume = 0;
    while ( $numeactu = each( $numedocu ) ) {
      $numeactu = $numeactu[value][0];
      if ( (int)$nume < (int)$numeactu ) {
        $nume = $numeactu;
      }
    }
    $numedocu = $nume;
  }

  // Si existe domiciliacion la añado
  if (trim ($vect[iban]) != null) {
    $codicarg = sql ("SELECT carg.codicarg FROM carg WHERE carg.codiayun = $codiayun AND carg.ejer = '$vect[ejer]' AND carg.codiconc = $vect[codiconc] AND carg.numedocu = '$numedocu'");
    $query_domi = "INSERT INTO cargdomibanc (codicarg, bic, iban, fechdomi) 
                   VALUES ('$codicarg', '$vect[bic]', '$vect[iban]', '$vect[fechdomi]');";
    sql ($query_domi);        
  }
  
  // Insertar los datos descriptivos del objeto en la tabla correspondiente
  // segun el tipo de objeto. Las tablas son cargoicv, cargotpp, ...
  // Primero determino el porcentaje y la causa de una posible bonificacion.
  $bene = sql( "SELECT B.nomb, BO.porcboni, BO.fechinibene, BO.fechfinbene FROM benetrib B LEFT JOIN benetribobje BO ON BO.codibene = B.codibene WHERE BO.tipoobje = '$tipoobje' AND BO.codiobje = $vect[codiobje]" );
  $porcboni = 0;
  $causboni = '';
  if ( is_array( $bene ) ) {
    $bene = each( $bene );

    $fechhoy_anio = date("Y-m-d",time());

    if (trim ($periliqu) != null) {
      $mes_actu = sql ("SELECT liquperi.inicperi FROM liquperi WHERE liquperi.abre = '$periliqu'");
    
      if (trim($mes_actu) != null) $fechhoy_anio = $vect[anio] . "-$mes_actu-01";
    }
    
    $fechinic = mostrarfecha ($bene[value][fechinibene]);
    $fechfina = mostrarfecha ($bene[value][fechfinbene]);
    if (( $fechinic != "" && $fechinic != '01-01-0001' && compfech( $fechinic, mostrarfecha($fechhoy_anio) ) == 1 ) || 
        ( $fechfina != "" && $fechfina != '01-01-0001' && compfech( $fechfina, mostrarfecha($fechhoy_anio) ) == 2 ) 
        ){
      $porcboni = 0;
      $causboni = '';
    } else {
      $porcboni = $bene[value][porcboni];
      $causboni = $bene[value][nomb];
    }
  }

  // Antes de insertar los datos en las tablas cargXXXX, se insertan los subimportes de la deuda en 
  // la tabla cargdeud, pero sólo en el caso de que el concepto tributario que se está liquidando 
  // tenga más de un subconcepto.
  $contsubc = sql( "SELECT subxconc.codisubc, subxconc.abre FROM subxconc WHERE subxconc.codiconc = $vect[codiconc]" );
  if ( count( $contsubc ) > 1 ) {
    // Si el concepto tiene más de 1 subconcepto, se insertan los subimportes en cargdeud.
    $query = "";
    while( $regisubc = each( $contsubc ) ) {
      $regi = $regisubc[value];
      $abresubc = $regi[abre];
      if ( isset( $vect[$abresubc] ) ) {
        // Sólo se insertan en la tabla cargdeud, los importes de los subconceptos que no estén
        // excluidos de la liquidación. Si no estuviera excluido, se recibe su importe en el vecto $vect.
        $query .= "INSERT INTO cargdeud (codiayun, ejer, codiconc, numedocu, codisubc, subxdeud) 
                   VALUES ('$codiayun', '$vect[ejer]', '$vect[codiconc]', '$numedocu', 
                   '$regi[codisubc]', '".number_format ($vect[$abresubc], 0, '', '')."');";
      }
    }
    sql( $query );
  }

  
  if ( $tipoobje == "OICV" ) {
    $query = "INSERT INTO cargoicv (codiayun, codiconc, ejer, numedocu, bast, marcmode, coditipo, 
              codiserv, plaz, capa, cili, pote, calc, porcboni, impoingr, fechingr, refeingr, causboni) 
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, 
              '$vect[ejer]' as ejer, '$numedocu' as numedocu, bast, marcmode, coditipo, codiserv, 
              plaz, capa, cili, pote, calc, '$porcboni' as porcboni, '$impoingr' as impoingr, 
              '$fechingr' as fechingr, '$refeingr' as refeingr, '$causboni' as causboni 
              FROM oicv WHERE oicv.codioicv = $vect[codiobje]";
    sql( $query );
  }
  
  if ( $tipoobje == "OTPP" ) {
    $query_refe = "SELECT inmu.refecata, inmu.numecarg, 
                          inmu.caracont, 
                          inmu.nomb as nombinmu, 
                          inmu.idenloca 
                   FROM inmu, refeterrobje
                   WHERE refeterrobje.tipoobje = 'OTPP'
                     AND refeterrobje.codiobje = $vect[codiobje]
                     AND inmu.codiinmu = refeterrobje.codiinmu ";
    $resu_refe = sql ($query_refe);
    if (is_array ($resu_refe)) {
      $regi_refe = each ($resu_refe);
      $dato_refe = $regi_refe[value];
      
      $refecata_otpp = $dato_refe[refecata];
      $numecarg_otpp = $dato_refe[numecarg];
      $caracont_otpp = $dato_refe[caracont];
      $nombinmu_otpp = $dato_refe[nombinmu];
      $idenloca_otpp = $dato_refe[idenloca];
    } else {
      $refecata_otpp = '';
      $numecarg_otpp = '';
      $caracont_otpp = '';
      $nombinmu_otpp = '';
      $idenloca_otpp = '';
    }

    $query = "INSERT INTO cargotpp (codiayun, codiconc, ejer, numedocu, refetasa, codiviax, 
              nume, letr, esca, plan, puer, datoactu, datoante, impoingr, fechingr, refeingr, 
              porcboni, causboni, refesect, refecata, numecarg, caracont, nombinmu, idenloca) 
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, '$vect[ejer]' as ejer, 
              '$numedocu' as numedocu, refetasa, codiviax, nume, letr, esca, plan, puer,
              dat1 as datoactu, dat2 as datoante, '".number_format ($impoingr, 0, '', '')."' as impoingr, '$fechingr' as fechingr, 
              '$refeingr' as refeingr, '$porcboni' as porcboni, '$causboni' as causboni, refesect,
              '$refecata_otpp', '$numecarg_otpp', '$caracont_otpp', '$nombinmu_otpp', '$idenloca_otpp' 
              FROM (otpp LEFT JOIN otpphist ON otpp.codiotpp = otpphist.codiotpp)
              WHERE otpp.codiotpp = $vect[codiobje];";
    $query .= "INSERT INTO cargotppcalc (codiayun, codiconc, ejer, numedocu, abresubc, busc, oper, oper2, impuesto)
               SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, 
               '$vect[ejer]' as ejer, '$numedocu' as numedocu, subxconc.abre, otppcalc.busc, otppcalc.oper, otppcalc.oper2,
               case when subxconc.impuesto > 0 then 
                 subxconc.impuestonomb || ' ' || subxconc.impuesto 
               else
                 ''
               end as impunombvalor
               FROM otppcalc LEFT JOIN subxconc ON otppcalc.codisubc = subxconc.codisubc 
               WHERE otppcalc.codiotpp = $vect[codiobje] AND subxconc.codiconc = $vect[codiconc]
               AND otppcalc.exclsubc = '0';";

    sql( $query );
  }
  if ( $tipoobje == "OIPL" ) {
    // Busco el valor de los años trasncurridos, para poder buscar el tipo de gravamen
    $valobusc = sql( "SELECT oipl.aniotran FROM oipl WHERE oipl.codioipl = $vect[codiobje]" );
    if ( $valobusc == "" ) $valobusc = 0;
    // Obtengo el tipo de gravamen a partir de los años transcurridos
    $tipograv = sql( "SELECT porc FROM tari T, subxconc S WHERE T.ejer='$vect[ejer]' AND T.codisubc=S.codisubc AND S.codiconc = $vect[codiconc] AND '$valobusc' BETWEEN limiinfe AND limisupe" );
    // Si no ha transcurrido aun un año (aniotran=0), entonces la variable $tipograv tiene el valor ""
    // que no es un valor numerico, asi que le asigno el valor 0
    if ( $tipograv == "" ) $tipograv = 0;
    $query = "INSERT INTO cargoipl (codiayun, codiconc, ejer, numedocu, nombnota, fechescr, prot,
              vari, fechpres, fechdeve, sigl, nombviax, nume, letr, esca, plan, puer, refecata, numecarg, 
              caracont, nombinmu, idenloca, valocata, tiporedu, valoapli, cuotadqu, aniotran, 
              porcperi, tipograv, impoingr, fechingr, refeingr, imporeca, impointe, porcboni, causboni) 
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, 
              '$vect[ejer]' as ejer, '$numedocu' as numedocu, nota.nomb as nombnota, fechescr, prot,
              vari, fechpres, fechdeve, sigl, nombviax, nume, letr, esca, plan, puer, refecata, numecarg, 
              caracont, nombinmu, idenloca, valocata, tiporedu, valoapli, cuotadqu, aniotran, 
              porcperi, '$tipograv' as tipograv, '$impoingr' as impoingr, '$fechingr' as fechingr, 
              '$refeingr' as refeingr, imporeca, '$vect[campespe]' as impointe,
              '$porcboni' as porcboni, '$causboni' as causboni
              FROM oipl, nota WHERE oipl.codioipl = $vect[codiobje] and nota.codinota=oipl.codinota";
    sql( $query );
  }
  if ( $tipoobje == "OIBIURBA" ) {
    $query = "INSERT INTO cargoibiurba ( codiayun, codiconc, ejer, numedocu, codiviax, nume, letr,
              esca, plan, puer, valosuel, valocons, valocata, base, nomb, idenloca, 
              numecarg, caracont, porcboni, impoingr, fechingr, refeingr, causboni, clavusox ) 
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, '$vect[ejer]' as ejer, 
              '$numedocu' as numedocu, codiviax, nume, letr, esca, plan, puer, valosuel, valocons , 
              valocata, base, nomb, idenloca, numecarg, caracont, '$porcboni' as porcboni, '$impoingr' 
              as impoingr, '$fechingr' as fechingr, '$refeingr' as refeingr, '$causboni' as causboni,
              oibiurba.clavusox
              FROM ( oibiurba INNER JOIN inmu ON inmu.codiinmu = oibiurba.codiinmu )
              WHERE oibiurba.codiinmu = $vect[codiobje];";
    sql( $query );
  }
  if ( $tipoobje == "OIBIURBAHIST" ) {
    $query = "INSERT INTO cargoibiurba ( codiayun, codiconc, ejer, numedocu, codiviax, nume, letr,
              esca, plan, puer, valosuel, valocons, valocata, base, nomb, idenloca, 
              numecarg, caracont, porcboni, impoingr, fechingr, refeingr, causboni, clavusox ) 
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, '$vect[ejer]' as ejer, 
              '$numedocu' as numedocu, codiviax, numebien, letrbien, escabien, planbien, puerbien, valosuel, valocons , 
              valocata, base, nombbien, idenloca, numecarg, caracont, porcboni, '$impoingr' 
              as impoingr, '$fechingr' as fechingr, '$refeingr' as refeingr, nombbene,
              oibiurbahist.clavusox
              FROM oibiurbahist 
              WHERE oibiurbahist.codisequ = $vect[codiobje];";
    sql( $query );
  }
  if ( $tipoobje == "OIAE" ) {
    if ($modoliqu == 'PER'){
	                $query = "SELECT cuottari, cuotmaqu, supe, nombviax, siglviax, tipooper, catepond
	                        FROM oiae LEFT JOIN oiaeepig ON secc = tari AND grup = codigrup 
	                        WHERE oiae.codioiae = $vect[codiobje]";
	                $datooiae = sql( $query );
	                if (is_array($datooiae)){
                        $datooiae = each($datooiae);
                        $datooiae = $datooiae[value];
		                if ( !$datooiae[supe] || ( $datooiae[nombviax] == "" ) ) {
		                  $indisitu = 1;
	 	                } else {
		                  if ( $codiviax = sql( "SELECT viasiaex.codiviax FROM viasiaex WHERE viasiaex.siglviax = '$datooiae[siglviax]' AND viasiaex.nombviax = '$datooiae[nombviax]' AND viasiaex.codiayun = $codiayun" ) ) {
		                    $indisitu = sql( "SELECT oiaecate.indisitu FROM oiaecate INNER JOIN vias ON ciae = codicate AND oiaecate.codiayun = vias.codiayun WHERE vias.codiviax = $codiviax AND oiaecate.anio = '$vect[anio]' AND oiaecate.codiayun = $codiayun" );
		                    if ( $indisitu == '' ) {
		                       $indisitu = 0;
		                    }
		                  } else {
		                    $indisitu = 0;
		                  }
		                }    	
		            }else{
		            	$indisitu = 0;
		            }
	                $vect[campespe] = $indisitu;
    }
    $query = "INSERT INTO cargoiae (codiayun, codiconc, ejer, numedocu, 
              sigl, nomb, nume, letr, esca, plan, puer, tipoacti, tari, codigrup, fechinic,
              supetota, superect, supecomp, cuotmaqu, cuottari, indisitu, 
              porcboni, causboni, fechcese, periextr, impoingr, fechingr, refeingr, tipooper,
              impoincn, catepond, nombcome) 
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, 
              '$vect[ejer]' as ejer, '$numedocu' as numedocu, siglviax, nombviax, numeviax, 
              letr, esca, plan, puer, tipoacti, tari, codigrup, fechinic, supetota, superect, 
              supecomp, cuotmaqu, cuottari, '$vect[campespe]' as indisitu, 
              '$porcboni' as porcboni, '$causboni' as causboni, 
              fechbaja, periextr, '$impoingr' as impoingr, '$fechingr' as fechingr, 
              '$refeingr' as refeingr, oiae.tipooper, oiae.impoincn, oiae.catepond, oiae.nomb
              FROM oiae WHERE oiae.codioiae = $vect[codiobje];";
    $query .= "INSERT INTO cargoiaeelem ( codiayun, codiconc, ejer, numedocu, nume, codielem, cant)
               SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, '$vect[ejer]' as ejer,
               '$numedocu' as numedocu, oiaeobjeelem.nume, oiaeobjeelem.codielem, oiaeobjeelem.cant 
               FROM oiaeobjeelem WHERE oiaeobjeelem.codioiae = $vect[codiobje];";
    sql( $query );
  }
  
  if ($tipoobje == "OIBIRUST") {
    $query = "INSERT INTO cargoibirust (codiayun, codiconc, ejer, numedocu, 
                                        impoingr, fechingr, refeingr)
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, '$vect[ejer]' as ejer, 
              '$numedocu' as numedocu, '$impoingr' as impoingr, '$fechingr' as fechingr,
              '$refeingr' as refeingr
              FROM dual;";
    $query .= "INSERT INTO cargoibirustparc (codiayun, codiconc, ejer, numedocu, 
                                             poli, parc, para, supefinc, supesola, valocata,
                                             valocons, valosuel, base,
                                             codidele, codimuni, sect,
                                             idencons, caracont, coefprop, porcboni, causboni) 
               SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, 
               '$vect[ejer]' as ejer, '$numedocu' as numedocu, 
               IR.poli, IR.parc, IR.para, IR.supefinc, IR.supesola, IR.valocata,
               IR.valocons, IR.valosuel, IR.base,
               IR.codidele, IR.codimuni, IR.sect, IR.idencons, IR.caracont,
               IR.coefprop, BO.porcboni, B.nomb 
               FROM  oibirust IR LEFT JOIN (benetribobje BO INNER JOIN benetrib B ON BO.codibene = B.codibene AND tipoobje = '$tipoobje') ON IR.codioibirust = BO.codiobje WHERE IR.numeorde = '$vect[abreobje]';";
    sql ($query);
  }
  if ( $tipoobje == "OIBIRUSTHIST" ) {
    $query = "INSERT INTO cargoibirust (codiayun, codiconc, ejer, numedocu, 
                                        impoingr, fechingr, refeingr)
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, '$vect[ejer]' as ejer, 
              '$numedocu' as numedocu, '$impoingr' as impoingr, '$fechingr' as fechingr,
              '$refeingr' as refeingr
              FROM dual;";
    $query .= "INSERT INTO cargoibirustparc (codiayun, codiconc, ejer, numedocu, 
                                             poli, parc, para, supefinc, supesola, valocata,
                                             valocons, valosuel, base,
                                             codidele, codimuni, sect,
                                             idencons, caracont, coefprop, porcboni, causboni) 
               SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, 
               '$vect[ejer]' as ejer, '$numedocu' as numedocu, 
               IR.poli, IR.parc, IR.para, IR.supefinc, IR.supesola, IR.valocata,
               IR.valocons, IR.valosuel, IR.base,
               IR.codidele, IR.codimuni, IR.sect, IR.idencons, IR.caracont,
               IR.coefprop, IR.porcboni, IR.nombbene 
               FROM  oibirusthist IR WHERE IR.numeorde = '$vect[abreobje]';";
    sql ($query);
  }
  
  if ( $tipoobje == "OICI" ) {
    // Obtengo la direccion de la obra, segun el campo oici.refeterr
    $refeterr = sql( "SELECT oici.refeterr FROM oici WHERE oici.codiayun = $codiayun AND oici.codioici = $vect[codiobje]" );
    // Inicializo valores de la direccion
    $codisigl = "0";
    $nombviax = "";
    $nume = "";
    $letr = "";
    $esca = "";
    $plan = "";
    $puer = "";
    $refecata = "";
    $numecarg = "";
    $caracont = "";
    $nombinmu = "";
    $idenloca = "";
    if ( $refeterr == 0 ) {
      $query = "SELECT oicidire.codisigl, oicidire.nombviax, oicidire.nume, oicidire.letr, oicidire.esca, oicidire.plan, oicidire.puer 
                FROM oicidire
                WHERE oicidire.codioici = $vect[codiobje]";
      $resu = sql( $query );
      if ( is_array( $resu ) ) {
        $regi = each( $resu );
        $regi = $regi[value];
        $codisigl = $regi[codisigl];
        $nombviax = $regi[nombviax];
        $nume = $regi[nume];
        $letr = $regi[letr];
        $esca = $regi[esca];
        $plan = $regi[plan];
        $puer = $regi[puer];
      }
    } else {
      $query = "SELECT codisigl, vias.nomb as nombviax, nume, letr, esca, plan, puer, refecata,
                numecarg, caracont, inmu.nomb as nombinmu, idenloca FROM inmu, vias, refeterrobje
                WHERE inmu.codiviax = vias.codiviax AND refeterrobje.tipoobje = 'OICI'
                AND refeterrobje.codiinmu = inmu.codiinmu AND refeterrobje.codiobje = '$vect[codiobje]'";
      $resu = sql( $query );
      if ( is_array( $resu ) ) {
        $regi = each( $resu );
        $regi = $regi[value];
        $codisigl = $regi[codisigl];
        $nombviax = $regi[nombviax];
        $nume = $regi[nume];
        $letr = $regi[letr];
        $esca = $regi[esca];
        $plan = $regi[plan];
        $puer = $regi[puer];
        $refecata = $regi[refecata];
        $numecarg = $regi[numecarg];
        $caracont = $regi[caracont];
        $nombinmu = $regi[nombinmu];
        $idenloca = $regi[idenloca];
      }
    }
    $query = "INSERT INTO cargoici ( codiayun, codiconc, ejer, numedocu, codisubxobra, fechinic,
              fechbaja, base, origbase, fechbase, codisigl, nombviax, nume, letr, esca, plan, puer,
              refecata, numecarg, caracont, nombinmu, idenloca, impoingr, fechingr, refeingr, 
              porcboni, causboni ) 
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, '$vect[ejer]' as ejer,
              '$numedocu' as numedocu, oici.codisubxobra, oici.fechinic, oici.fechbaja, oici.base, oici.origbase, oici.fechbase, 
              '$codisigl' as codisigl, '$nombviax' as nombviax, '$nume' as nume, '$letr' as letr, 
              '$esca' as esca, '$plan' as plan, '$puer' as puer, '$refecata' as refecata, 
              '$numecarg' as numecarg, '$caracont' as caracont, '$nombinmu' as nombinmu, 
              '$idenloca' as idenloca, '$impoingr' as impoingr, '$fechingr' as fechingr, 
              '$refeingr' as refeingr, '$porcboni' as porcboni, '$causboni' as causboni 
              FROM oici WHERE oici.codioici = $vect[codiobje];";
    sql( $query );
  }
  if ($tipoobje == 'SANCTRAF'){
    // Obtengo la direccion de la denuncia, segun el campo osantraf.refeterr
    // Inicializo valores de la direccion
    $codiviax = "";
    $nume = "";
    $letr = "";
    $esca = "";
    $plan = "";
    $puer = "";
    $kilo = 0;
    $domiampl = "";
    $query = "SELECT osantrafdire.codiviax, osantrafdire.nume, osantrafdire.letr, osantrafdire.esca, osantrafdire.plan, osantrafdire.puer, osantrafdire.kilo, osantrafdire.domiampl 
              FROM osantrafdire
              WHERE osantrafdire.codiosan = $vect[codiobje]";
    $resu = sql( $query );
    if ( is_array( $resu ) ) {
      $regi = each( $resu );
      $regi = $regi[value];
      
      $codiviax = $regi[codiviax];
      $nume = $regi[nume];
      $letr = $regi[letr];
      $esca = $regi[esca];
      $plan = $regi[plan];
      $puer = $regi[puer];
      $kilo = $regi[kilo];
      $domiampl = $regi[domiampl];
    }
    $query = "INSERT INTO cargosantraf ( codiayun, codiconc, ejer, numedocu, numedenu, impodenu, anioexpe, numeexpe,
              codiinfr, codivehi, fechdenu, horadenu, codiviax, nume, letr, esca, plan, puer, 
              porcboni, causboni, kilo, domiampl )
              SELECT '$codiayun' as codiayun, '$vect[codiconc]' as codiconc, '$vect[ejer]' as ejer,
              '$numedocu' as numedocu, osantraf.numedenu, osantraf.impodenu, osantraf.anioexpe, osantraf.numeexpe, osantraf.codiinfr, osantraf.codivehi, osantraf.fechdenu, osantraf.horadenu,
              '$codiviax' as codiviax, '$nume' as nume, '$letr' as letr,
              '$esca' as esca, '$plan' as plan, '$puer' as puer, '$porcboni' as porcboni, 
              '$causboni' as causboni,
              $kilo as kilo,
              '$domiampl' as domiampl
              FROM osantraf WHERE osantraf.codiosan = $vect[codiobje];";
    sql( $query );
     
  }
  
  if ($mostrar){
	  // Muestro una linea en la tabla con los datos del cargo generado
	  datocarg( $vect, $numedocu );
  }
  // Devuelve numedocu porque es un valor necesario para el script rcoi/baja.php para
  // cobrar el importe de la liquidacion
  return $numedocu;
}
?>

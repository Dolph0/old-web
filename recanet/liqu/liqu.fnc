<?
require_once "comun/sql.fnc";
require_once "comun/fecha.fnc";
require_once "liqu/cuot.php";
require_once "comun/euro.fnc";
require_once "liqu/redocuot.fnc";
require_once "comun/compdico.fnc";
require_once "comun/html.fnc";
require_once "comun/error.fnc";
require_once "liqu/pror.fnc";
require_once "clas/config.inc";

function calcdeud( $conc, $ejer, $ayun, $busc, $oper, $tipobene, $porcboni, $pror, $fechalta, $fechbaja, $tipofech, $modoliqu, $impoingr, $exclsubc, $oper2 = '') {  
// Calcula la deuda de un solo objeto
// Aplica la formula Deuda = Cuota [- % benetrib de la cuota][* Aplicacion del prorrateo]
  // El parametro $ejer engaña un poco. No es el ejercicio, sino el año contraido de la deuda.

  // Vector de 2 elementos a retornar, donde el primer elemento es el valor de la cuota, 
  // y el segundo es el valor de la deuda
  $reto[cuot] = 0;
  $reto[deud] = 0;

  // Calculo de la cuota de este objeto
  $reto = calccuot( $conc, $ejer, $ayun, $busc, $oper, $exclsubc, $oper2 );
  if ( $reto[cuot] == -1 ) {
    return $reto;
  }

  // Comprobar si esta exento de pago (exencion)
  if ( $tipobene == "e" ) {
    // Esta exento de pago; salta a la siguiente iteracion (siguiente objeto)
      return $reto;
  }

  // Redondeo el resultado del calculo de la cuota
  // La funcion euro2cent recibe euros y devuelve centimos, por eso multiplico por 0.01
  foreach( $reto as $clav => $valo ) {
    // Redondeo la cuota total y las subcuotas, solo si el valor es numerico
    if (!is_numeric ($reto[$clav])) continue;
    
    $reto[$clav] = euro2cent( $valo*0.01 );
  }
  //$cuot = euro2cent( $cuot*0.01 );
  // Inicializo la deuda
  $reto[deud] = $reto[cuot];

  // Aplicar una posible bonificacion
  if ( $tipobene == "b" ) {
    // Se le resta el pocentaje de bonificacion
    $reto[deud] = $reto[cuot] - ( ( $reto[cuot] * $porcboni ) / 100 );
  }

  // Aplicar el prorrateo
  if ( $tipofech != "" ) {
    // Antes, la fecha de prorrateo es la fecha actual
    // Ahora, se toma de la fecha de alta y/o de baja del objeto
    //$fech = date("d-m-Y");

    $valopror = prorrateo( $pror, $fechalta, $fechbaja, $tipofech, $ejer );
    if ( $valopror == -1 ) {
      $reto[deud] = -1;
      return $reto;
    }
    $reto[deud] = $reto[deud] * $valopror;

    // Si el prorrateo devuelve 0, es porque no debe liquidarse, ya que la fecha de inicio
    // del objeto es posterior al año contraido, con lo que aun no se ha dado de alta, o bien
    // que la fecha de baja sea anterior al contraido, con lo que el objeto fue dado de baja
    // en años anteriores y no debe liquidarse.
    // Es por eso, que en este punto retorno 0, cuando se da ese caso, para no restar mas adelante,
    // posibles valores de $impoingr.
    if ( $valopror == 0 ) return $reto;    
  }
  
//
//  // Devuelvo en centimos de euro
//  $reto[cuot] = $cuot;
//  $reto[deud] = $deud;

  // Redondeo el resultado del calculo de la deuda
  // La funcion euro2cent recibe euros y devuelve centimos, por eso multiplico por 0.01
  $reto[deud] = euro2cent( $reto[deud]*0.01 );

  if ( substr( $modoliqu, 0, 1 ) == "D" ) {
    // Solo en liquidaciones directas
    // El importe ingresado a cuenta se resta a la deuda
    if ( $impoingr > 0 ) $reto[deud] -= $impoingr;
  }

  // Compruebo si hay diferencia entre la deuda y la suma de las subcuotas. Si es asi, se redondean
  // las cuotas, o se les reduce un porcentaje.
  $reto = redocuot( $reto );

  // Devuelve la cuota y la deuda en centimos de euro
  return $reto;
}


function recaplus( $codioipl, $deud ) {
// Funcion que aplica el recargo de autoliquidacion en plusvalia
// Obtiene un porcentaje a partir de la fecha de devengo y la fecha de presentacion
// del objeto OIPL, y se lo aplica a la deuda, devolviendo el resultado
// Dicho porcentaje se obtiene de la tabla oiplporcreca segun los meses transcurridos
// desde la fecha de finalizacion del plazo para presentar la autoliquidacion, que son 30 
// dias naturales despues de la fecha de devengo (oipl.fechdeve), en el caso de las Herencia
// que son 6 meses naturales, hasta la fecha de presentacion (oipl.fechpres)
// Para calcular el numero de meses transcurridos de una fecha a la otra, uso
// la función numeMeses que se encuentra en el fichero comun/fecha.fnc
// Las fechas deben estar en formato aaaa-mm-dd, como en la base de datos

  $obje = sql("SELECT oipl.codiayun, oipl.fechpres, oipl.fechdeve, oipl.clastran FROM oipl WHERE oipl.codioipl = $codioipl");
  // Leo los campos necesarios del objeto de plusvalia
  if ( is_array( $obje ) ) {
    $obje = each( $obje );
    $obje = $obje[value];

    // Calculamos la fecha final del plazo de presentacion de la autoliquidacion.
    // Solo las HERENCIAS por el FALLECIMIENTO tienen un plazo diferente
    $clastran = $obje[clastran];
    if ($clastran=='HER'){ 
      $campfech = split( '[./-]', $obje[fechdeve]);
      $fechfina = date ('Y-m-d', mktime (0, 0, 0, $campfech[1] + 6, $campfech[2],  $campfech[0])); 
    }else $fechfina = SumaDiasHabi( $obje[fechdeve], 30, $obje[codiayun] );

    // Inicializo el importe de recargo
    $imporeca = 0;

    // Se comprueba el recargo si la fecha de hoy es posterior a la fecha final
    if ( compfech( Mostrarfecha($fechfina), Mostrarfecha($obje[fechpres]) ) == 2 ) {
      // Obtengo la diferencia de meses de las dos fechas
      $dife = numeMeses( $fechfina, $obje[fechpres] );
  
      if ( $dife >= 0 ) {
        // Con esa diferencia de meses, obtengo de la tabla oiplporcreca, el porcentaje a aplicar
        // ATENCION: he buscado en oiplporcreca segun el campo ejer sea el ejercicio actual. 
        $ejeractu = date( 'Y', time() );
        $oiplporcreca = sql( "SELECT oiplporcreca.porcperi FROM oiplporcreca WHERE oiplporcreca.ejer = '$ejeractu' AND '$dife' BETWEEN oiplporcreca.limiinfe AND oiplporcreca.limisupe AND oiplporcreca.codiayun = $obje[codiayun]" );

        if ( $oiplporcreca != "" ) {
          // Obtengo el porcentaje, y se lo añado a la deuda
          $imporeca = $deud*$oiplporcreca*0.01;
          // Lo redondeo. A la funcion euro2cent se le pasan euros, lo redondea y devuelve centimos
          $imporeca = euro2cent($imporeca*0.01);
          $deud += $imporeca;
        }
      }
    }
    // Guardo el recargo en la tabla oipl, para posteriormente recuperar ese valor
    // y almacenarlo en cargoipl
    sql( "UPDATE oipl SET imporeca = $imporeca WHERE oipl.codioipl = $codioipl" );

    // Devuelvo el total de la deuda, se le haya añadido el importe de recargo o no
    return ( $deud );
  } else {
    // Error, no encontro el objeto oipl en la base de datos
    return -1;
  }
}


function creavect ( $conc, $abreconc, $grup, $abregrup, $nifx, $nombsuje, $obje, $abreobje, $vectliqu, $iban, $refeno60, $anio, $campespe ) {
// Crea un vector con los campos a insertar en la tabla de cargos en la liquidación

// Los campos numedocu, fechcarg, horacarg, usuacarg se obtienen en el momento de la 
// insercion, a los que hay que añadir fechvolu, horavolu, usuavolu cuando la liquidación es periódica.
// Los campos codiayun, periliqu, modoliqu, ejer, tipoobje, fechinicvolu, fechfinavolu, usuacarg,
// estacont, estanotiprov son comunes a todos los objetos
// Antes, el campo anio era comun a todos los objetos. Pero ahora, con la liquidacion de OIBIURBA
// la cosa ha cambiado: cuando se liquidan varios inmuebles, el año contraido para cada uno
// de ellos, es el año del valor catastral de cada objeto.

// La tabla carg tiene mas campos, pero se dejan en blanco en el momento de la liquidación

  $vect[codiconc] = $conc;
  $vect[nifx] = $nifx;
  $vect[nombsuje] = $nombsuje;
  $vect[codiobje] = $obje;
  $vect[anio] = $anio;

  // Almaceno los resultados del calculo de la deuda, es decir, las subcuotas de cada subconcepto,
  // la cuota total o suma de aquellas subcuotas, y la deuda final.
  // La cuota total no es necesaria para generar el cargo, pero se muestra en el resumen.
  // Las subcuotas se utilizan para guardarlas, en algunos casos, en la tabla cargXXXX,
  // por ejemplo, en cargoiae.
  foreach( $vectliqu as $clav => $valo ) {
    $vect[$clav] = $valo;
  }

  // Si el concepto que se liquidó, pertenecía a un grupo, en el campo grup indico el código del grupo
  $vect[codigrup] = $grup;

  // Campos necesarios para mostrar el resumen, antes de generar el cargo
  $vect[abreconc] = $abreconc;
  $vect[abregrup] = $abregrup;
  $vect[abreobje] = $abreobje;
  
  // Domiciliacion bancaria
  if ( ( $iban != "" ) ) {
    $vect[domibanc] = 1;
    $vect[iban] = $iban;
    $vect[refeno60] = $refeno60;
  } else {
    $vect[domibanc] = 0;
    $vect[iban] = "";
    $vect[refeno60] = "";
  }

  // Campo adicional, especifico de cada tipo de objeto.
  // Por ejemplo, en IAE es el indice de situacion
  $vect[campespe] = $campespe;

  return $vect;
}

function compdomi( $iban ) {
// Esta funcion comprueba si la domiciliacion bancaria que pudiera tener un objeto, es valida.
// Si no, es un error en el calculo de la deuda de este objeto, y no se puede generar el cargo.

  if ( ( $iban != "" ) ) {
    if ( !compiban( $iban ) ) {
      return 0;
    }
  }
  return 1;
}

function liquida($query, $anio, $tipoobje, $codiobje, $concactu, $modoliqu, $periliqu, $ayun, $usua, $vectconc, $i, &$vect, $fechinicvolu = '0001-01-01', $fechfinavolu = '0001-01-01', $fechaplibene = '', $impoingr = 0, $otroindice = -1, $fechinicvolu_2 = '0001-01-01', $fechfinavolu_2 = '0001-01-01', $liqudivi = 0) {
        $resp = sql($query);
        if (!is_array($resp)){
            return false;
        }

        // Recorremos cada objeto, para calcular su deuda
        $hoy = date('Y-m-d', time());
        $hora = date('H:i:s', time());
        $rutiname = 'CALCDEUD';
        $inciarray = array();
        $inciarray[codiayun] = $ayun;
        $inciarray[codiconc] = $concactu;
        $inciarray[peri] = $periliqu;
        $inciarray[modoliqu] = $modoliqu;        
        $inciarray[anio] = $anio;        
        
  $ayunnifx = sql("SELECT ayun.nifx FROM ayun WHERE ayun.codiayun = $ayun");
                
        if ($modoliqu == 'PER'){
            // Cuantos excluidos
            $tipofech = ''; // Sin prorrateo
            $exclliqu = 0;
            if (strpos($query, 'exclliqu') > 0){
            	$queryaux = ereg_replace( "exclliqu = '0'", "exclliqu = '1'", $query );
            	$pos = strpos($queryaux, 'FROM');
            	$exclliqu = sql("SELECT count(*) " . substr($queryaux, $pos));
            } elseif ($tipoobje == 'OTPP') {
              $queryaux = $query . " AND not exists (select otppcalc.codiotpp from otppcalc where otppcalc.codiotpp = otpp.codiotpp and otppcalc.exclsubc = '0') ";
              $pos = strpos($queryaux, 'FROM');
              $exclliqu = sql("SELECT count(*) " . substr($queryaux, $pos));
            }

			$numeregi = sql("INSERT INTO liqupadr (codiayun, codiconc, anio, peri, fechiniccalc, horainiccalc, usuainiccalc, numecalc, numeexcl, fechinicvolu, fechfinavolu, fechinicvolu_2, fechfinavolu_2, liqudivi)
					 VALUES ('$ayun', '$concactu', '$anio', '$periliqu', '$hoy', '$hora', '$usua', '" . count($resp) . "', '$exclliqu', '$fechinicvolu', '$fechfinavolu', '$fechinicvolu_2', '$fechfinavolu_2', '$liqudivi'); 
                         SELECT max(numeregi) FROM liqupadr;");
    }else{
		  // Determinar si el prorrateo ($tipofech) es por fecha de alta (A) o por fecha de baja (B), 
		  // si la modalidad de liquidacion es directa por alta (DAT) o por baja (DBJ). 
		  // Se aplica prorrateo por fecha de alta y de baja (ambos) si la modalidad es DAL o DPV.
		  // En el resto de tipos de liquidacion no se aplica prorrateo.
		  // Ademas, depende de si el concepto admite prorrateo y del tipo de este, pero eso se
		  // tiene en cuenta en la funcion prorrateo() de pror.fnc, llamada desde la funcion calcdeud()
		  if ( $modoliqu == "DAT" ) {
		    // Prorrateo por fecha de alta
		    $tipofech = "A";
		  } elseif ( $modoliqu == "DBJ" ) {
		    // Prorrateo por fecha de baja
		    $tipofech = "B";
		  } elseif ( ( $modoliqu == "DAL" ) || ( $modoliqu == "DPV" ) ) {
		    // Prorrateo por fecha de alta y de baja (ambos).
		    // Si se trata del IAE, y el tipo de operacion sea "B" (baja), pero habrá que comprobarlo
		    // objeto a objeto, con lo que se comprueba mas adelante, tras tomar los datos de cada objeto.
		    // Por ahora le asigno liquidación por alta y por baja, y si fuera necesario, se cambia mas
		    // adelante en el codigo, cuando se comprueba si el tipo de operacion del objeto es una baja.
		    $tipofech = "D";
		  } else {
		    // No se aplica prorrateo
		    $tipofech = "";
		  }
	    }

        $reiniciar = true;
		while ($reiniciar){
	        $contobje = 0;
	        $contdomi = 0;
	        $cancelado = false;
	        $reiniciar = false;

    $config = &config::getInstance();
    $nifxayun = $config->getValue('liquidación.propioayuntamiento', $ayun);
            
	        reset($resp);
	        while ( $regi = each( $resp ) ) {
	            // Si es liquidación periodica comprobamos que no se haya
	            // cancelado el proceso para poder continuar	
	            if ($modoliqu == 'PER'){
        if (sql("SELECT count(*) FROM liqupadr WHERE liqupadr.numeregi = $numeregi") > 0){
          $res = sql("SELECT liqupadr.numeproc FROM liqupadr WHERE liqupadr.numeregi = $numeregi AND liqupadr.fechintecalc <> '0001-01-01'");
						if ($res){
						    if ($res == -1){
    			    sql("UPDATE liqupadr SET fechintecalc = '0001-01-01', horaintecalc = '00:00:00', usuaintecalc = NULL, causintecalc = '', numeproc = 0, numeexen = 0, numeexcl = 0 WHERE liqupadr.numeregi = $numeregi");
							    $reiniciar = true;
								break;
							}
						    $cancelado = true;
							break;
						}
					}else{
					    $cancelado = true;
						break;
					}
		    	}
	        
		        $regi = $regi[value];
            $inciarray[codiobje] = $regi[abreobje];  			
		        $regi[fechinibene] = Mostrarfecha($regi[fechinibene]);
		        $regi[fechfinbene] = Mostrarfecha($regi[fechfinbene]);
		        $regi[fechalta] = Mostrarfecha($regi[fechalta]);
		        $regi[fechbaja] = Mostrarfecha($regi[fechbaja]);
			
		        // Comprobar que la fecha actual se encuentra entre la fecha inicial y final del posible
		        // beneficio tributario a aplicar. 
				if ($tipoobje == 'SANCTRAF' && $codiobje != '' && $fechaplibene != ''){
         	$fechactu = $fechaplibene;
       	}else{
        $fechactu = date("d-m-Y");

     	  if (trim($periliqu) != null) {
     	    $mes_actu = sql ("SELECT liquperi.inicperi FROM liquperi WHERE liquperi.abre = '$periliqu'");

          if (trim($mes_actu) != null) {     	    
     	      $fechactu = "01-$mes_actu-$anio";
     	    }
       	}
       	}
       	
       	$fechinic = $regi[fechinibene];
	      $fechfina = $regi[fechfinbene];
      if ( ( $fechinic != "" && $fechinic != '01-01-0001' && compfech( $fechinic, $fechactu ) == 1 ) || 
           ( $fechfina != "" && $fechfina != '01-01-0001' && compfech( $fechfina, $fechactu ) == 2 ) 
                    ) {
	          // No se aplica el beneficio porque la fecha actual en la que se aplica la liquidacion
	          // no se encuentra dentro del intervalo fechinibene-fechfinbene
	          // Anulo el beneficio
	          $regi[tipobene] = "";
        $regi[porcboni] = 0; // Para que no incremente el contador de bonificados.

	        }
	
      // Ahora compruebo que el periodo liquidado sea anterior a la fecha de baja
      // Y solo para las tasas OTPP
      if ($tipoobje == 'OTPP') {
        if ($regi[fechbaja] != "" && compfech($regi[fechbaja], $fechactu) == 2 ) {
          // Si el periodo liquidado es superior al de la fecha
          // de baja. Lo señalo como exento para que no calcule
          // su deuda.
          $regi[tipobene] = "e";
        }
      }
      
	            // Se quieren liquidar objetos asociados a un concepto
	
	            // Elementos para el calculo de la cuota segun el tipo de calculo
	            $busc = "";
	            $oper = "";
	            $oper2 = "";
	            // Los elementos para el calculo son numeros o categorias. Se los paso a la funcion
	            // en forma de vector, donde los indices de ambos vectores $busc y $oper son los códigos
	            // de los subconceptos
	
	            // Al crear el vector con los datos de la liquidacion de cada objeto (al llamar a la
	            // funcion creavect), empleo una variable de apoyo, en la que se almacena algun
	            // campo especifico segun el tipo de objeto.
	            // Por ejemplo, el indice de situacion en IAE
	            $campextr = "";  // La inicializo
	
	            // Novedad 4-6-2003: para excluir determinados subconceptos del calculo de la deuda,
	            // se le pasa a cuot.php un vector con dichos subconceptos exlcuidos.
	            // Ahora mismo, solo se usa en OTPP, pero lo pongo de forma general por si se
	            // acaba usando en algun otro concepto.
	            $exclsubc = array();  // Lo inicializo a vacio. Es un array de subconceptos excluidos.
				//Comprobamos que el tercero existe
				$tipoaux = $tipoobje;
				if ($tipoobje == 'OIBIURBAHIST'){
					$tipoaux = 'OIBIURBA';
    			$sql_tercdato = "SELECT count(*) FROM oibiurbahist WHERE oibiurbahist.codisequ = $regi[codiobje]";
				} elseif ($tipoobje == 'OIBIRUSTHIST'){
					$tipoaux = 'OIBIRUST';
		$sql_tercdato = "SELECT count(*) FROM oibirusthist WHERE oibirusthist.codisequ = $regi[codiobje]";					
				} else {
		$sql_tercdato = "SELECT count(*) FROM tercdato INNER JOIN (tercobje INNER JOIN tercfigu ON tercobje.codifigu = tercfigu.codifigu) ON tercdato.coditerc = tercobje.coditerc WHERE tercobje.tipoobje = '$tipoaux' and tercobje.codiobje = $regi[codiobje] and tercfigu.abrefigu = 'CNP'";
				}

				if (sql ($sql_tercdato) == 0){			    
					inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
					continue;
				}
	            if ( $tipoobje == "OICV" ) {
	              // El elemento para el calculo de un objeto OICV es el campo calc de la tabla oicv
          $subc = sql("SELECT subxconc.codisubc FROM subxconc WHERE subxconc.codiconc = $concactu");
          $busc[$subc] = sql("SELECT oicv.calc FROM oicv WHERE oicv.codioicv = $regi[codiobje]");
	              if ($busc[$subc] == ''){
	                if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{
	              		inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	              	}
	              	continue;
	              }
	            }
	            if ( $tipoobje == "OTPP" ) {
	              // La fecha de alta del prorrateo es el campo otpp.fechsoli, cuando el devengo
	              // del concepto sea "Al presentar la solicitud"
	              if ( $vectconc[$i][deve] == "S" ) {
	                $regi[fechalta] = Mostrarfecha($regi[fechsoli]);
	              }
	
	              // Los elementos para el calculo de los objetos OTPP se encuentran en la tabla otppcalc
	              $query = sql("SELECT otppcalc.busc, otppcalc.oper, otppcalc.oper2, otppcalc.codisubc, otppcalc.exclsubc FROM otppcalc WHERE otppcalc.codiotpp = $regi[codiobje]");
	              if ( is_array( $query ) ) {
	                while ( $otppcalc = each( $query ) ) {
	                  $otppcalc = $otppcalc[value];
	                  if ( $otppcalc[exclsubc] == 0 ) {
	                    // Subconceptos no excluidos de la liquidacion
	                    $busc[$otppcalc[codisubc]] = $otppcalc[busc];
	                    $oper[$otppcalc[codisubc]] = $otppcalc[oper];
	                    $oper2[$otppcalc[codisubc]] = $otppcalc[oper2];
	                  } else {
	                    // Subconceptos excluidos de la liquidacion.
	                    // Lo añado al vector de subconceptos excluidos.
	                    array_push ($exclsubc, $otppcalc[codisubc]);
	                  }
	                }
	                
	                //-----------------------------------------------------
	                // Añado al vector de subconceptos excluidos el de las
                        // tarifas con base = 0 o porc = 0
                        // o subconceptos nuevos que no hayan sido INSERTADOS todavia
                        // en otppcalc porque son nuevos.
	                //-----------------------------------------------------
	                $sql_subxcero = sql ("SELECT subxconc.codisubc, subxconc.abre 
                                              FROM otpp, subxconc, tari 
                                              WHERE otpp.codiotpp = $regi[codiobje] 
                                              AND subxconc.codiconc = otpp.codiconc 
                                              AND tari.codisubc = subxconc.codisubc 
                                              AND tari.ejer = to_char(current_date, 'yyyy') 
                                              AND ((tari.base = 0 AND tari.porc = 0) OR
                                                   (NOT EXISTS (SELECT otppcalc.codisubc
                                                                FROM otppcalc
                                                                WHERE otppcalc.codiotpp = otpp.codiotpp
                                                                  AND otppcalc.codisubc = subxconc.codisubc)))");
	                if (is_array ($sql_subxcero)) {
	                  while ($regi_cero = each ($sql_subxcero)) {
	                    $dato_cero = $regi_cero[value];
	                    
	                    array_push ($exclsubc, $dato_cero[codisubc]);
	                  }
	                }
	              } else {
	              	if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe.");
	                }else{	              	
	              		inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	              	}
	              	continue;
	              }
	            }
	            if ( $tipoobje == "OIPL" ) {
		          // Obtengo el subconcepto de la plusvalia
        $subc = sql("SELECT subxconc.codisubc FROM subxconc WHERE subxconc.codiconc = $concactu");
		          // Obtengo los datos de la tabla oipl necesarios para los elementos para el calculo
        $query = "SELECT oipl.aniotran,oipl.valoapli,oipl.porcperi,oipl.cuotadqu 
                  FROM oipl WHERE oipl.codioipl = $codiobje";
		          $datooipl = sql( $query );
		          if ( is_array( $datooipl ) ) {
		            $datooipl = each( $datooipl );
		            $datooipl = $datooipl[value];
		            // Obtengo los elementos para el calculo
		            // Si los años transcurridos pasan de 20, entonces se pone que son 20, es decir,
		            // el maximo de años transcurridos sera siempre 20
		            if ( $datooipl[aniotran] > 20 ) { $datooipl[aniotran] = 20; }
		            $busc[$subc] = $datooipl[aniotran];
		            $oper[$subc] =  ($datooipl[valoapli]*0.01)*$datooipl[aniotran];
		            $oper[$subc] *= $datooipl[porcperi]*$datooipl[cuotadqu]*0.01;
		          }else{
		            if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{		            
	              		inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	              	}
	              	continue;
		          }
		        }
	            if ( $tipoobje == "OIAE" ) {
	              // Cuando se liquida de forma directa mas de un objeto, el año contraido para 
	              // cada objeto es el ejercicio de efectividad de cada objeto
	              if ($codiobje == '' && substr( $modoliqu, 0, 1 ) == "D" ){
            $anio = sql( "SELECT oiae.ejerefec FROM oiae WHERE oiae.codioiae = $regi[codiobje]" );
	              }
	
	              // La deuda del IAE se compone de la suma de dos cuotas: la cuota municipal
	              // y el recargo provincial
	              // Obtengo los datos de la tabla oiae necesarios para los elementos para el calculo
	              $query = "SELECT cuottari, cuotmaqu, supe, nombviax, siglviax, tipooper, catepond
	                        FROM oiae LEFT JOIN oiaeepig ON secc = tari AND grup = codigrup 
                    WHERE oiae.codioiae = $regi[codiobje]";
	              $datooiae = sql( $query );
	              if ( is_array( $datooiae ) ) {
	                $datooiae = each( $datooiae );
	                $datooiae = $datooiae[value];
	  
	                // Lo primero: determino, en caso de que sea una liquidación directa por
	                // alta, baja, variación (DAL) o solo variacion (DPV), si el tipo de operación 
	                // es baja porque solo entonces se aplica prorrateo por fecha de alta y de baja. 
	                // Si no, solo prorrateo por fecha de alta.                
	                if ( ( $modoliqu == "DAL" ) || ( $modoliqu == "DPV" ) ) {
	                  if ( $datooiae[tipooper] == "B" ) {
	                    $tipofech = "D";
	                  } else {
	                    $tipofech = "A";
	                  }
	                  // Si es una baja, el tipo de fecha "D" (por alta y por baja) ya
	                  // fue asignado anteriormente.
          } else {
                    # Si se está haciendo el padrón de OIAE tambien se debe tener en cuenta el prorateo                    
                    # Le pasamos el tipo de fecha D y dentro de la funcion prorateo se controla si se aplica
                    #  el de alta, baja o ambos.
          if ($modoliqu == "PER"){
                      $tipofech = "D";
                    }
                  }
	
	                // Variable que nos indicara si se produjo error de correspondecia
	                // de vias IAE-ayuntamiento en el objeto
	                $erroviax = 0;
	  
	                // Obtengo el indice de situacion de la tabla oiaeepig, segun tari y codigrup
	                if ( !$datooiae[supe] || ( $datooiae[nombviax] == "" ) ) {
	                  $indisitu = 1;
 	                } else {
              if ( $codiviax = sql( "SELECT viasiaex.codiviax FROM viasiaex WHERE viasiaex.siglviax = '$datooiae[siglviax]' AND viasiaex.nombviax = '$datooiae[nombviax]' AND viasiaex.codiayun = $ayun" ) ) {
                $indisitu = sql( "SELECT oiaecate.indisitu FROM oiaecate INNER JOIN vias ON ciae = codicate AND oiaecate.codiayun = vias.codiayun WHERE vias.codiviax = $codiviax AND oiaecate.anio = '$anio' AND oiaecate.codiayun = $ayun" );
	                    if ( $indisitu == '' ) {
	                      // Error: la via no tiene asiganada una categoria
	                      $erroviax = 1;
  		                  if ($codiobje != ''){
			              	mens("Error: la vía no tiene categoría asignada");
			              }else{
	                      	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	                      }
	              	      continue;	                      
	                    }
	                  } else {
	                    // Error: esa via del IAE no esta asociada con alguna via del ayuntamiento
	                    $erroviax = 1;
	                    if ($codiobje != ''){
			            	mens("Error: el objeto no tiene correspondencia entre las vias del IAE y del ayuntamiento");
			            }else{
			            	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
			            }
	              	    continue;	                      
	                  }
	                }
	
	                // Asigno el campo especifico del IAE, para pasarselo a la funcion creavect
	                $campextr = $indisitu;
	  
	                // Busco los subconceptos del IAE
            $codisub1 = sql("SELECT subxconc.codisubc FROM subxconc WHERE subxconc.abre ='CMUN' AND subxconc.codiconc = $concactu");
            $codisub2 = sql("SELECT subxconc.codisubc FROM subxconc WHERE subxconc.abre ='CPRO' AND subxconc.codiconc = $concactu");
	                if ( $erroviax == 0 ) {
	                  // Necesito el coeficiente de ponderación, que está en las tarifas del otro
	                  // subconcepto, la cuota municipal.
              $coefpond = sql( "SELECT tari.porc FROM tari WHERE tari.codisubc= $codisub1 AND tari.cate = '$datooiae[catepond]' AND tari.ejer = '$anio'" );
	
	                  // Primero obtengo los parametros de la cuota municipal
	                  $oper[$codisub1] = $datooiae[cuottari] * $indisitu;
	                  $oper[$codisub1] += $datooiae[cuotmaqu] * ( 1 - $indisitu ) * ( 1 / ($coefpond*0.01) );
	                  $busc[$codisub1] = $datooiae[catepond];
	    
	                  // Ahora los parametros del recargo provincial
	                  // La formula del recargo provincial cambia a partir del
		              // 1 de enero de 2003
		              if ($anio >= 2003) {
		                $oper[$codisub2] = $datooiae[cuottari] * $coefpond * 0.01;
		              } else {
		                $oper[$codisub2] = $datooiae[cuottari];
		              }
	                } else {
	                  // Se produjo error, porque la via no tiene su correspondiente en las vias
	                  // del ayuntamiento. 
	                  // Asigno -1 en el operador, porque asi da error el calculo de 
	                  // la cuota. Si no lo hiciera asi, daria deuda 0
	                  $oper[$codisub1] = -1;
	                  $oper[$codisub2] = -1;
	                }
	              }else{
	              	if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{
	                	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	                }
	              	continue;
	              }
	            }
	            if ( $tipoobje == "OIBIURBA" ) {
	              // El concepto de inmueble urbana solo tiene un subconcepto
          $subc = sql( "SELECT subxconc.codisubc FROM subxconc WHERE subxconc.codiconc = $concactu" );
	              // El elemento para el calculo de OIBIURBA esta en la tabla oibiurba,
	              // y se trata de una valor base al que se le calcula un porcentaje
          $oper[$subc] = sql( "SELECT oibiurba.base FROM oibiurba WHERE oibiurba.codiinmu = $regi[codiobje]" );
        $busc[$subc] = sql( "SELECT oibiurba.clavusox FROM oibiurba WHERE oibiurba.codiinmu = $regi[codiobje]" );
	              if ($oper[$subc] == 0){
	              	if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{
	                	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	                }
	              	continue;
                  }	
	              // Cuando se liquida mas de un objeto de forma directa, el año contraido para cada objeto
	              // es el año del valor catastral de cada objeto
	              if ($codiobje == '' && substr( $modoliqu, 0, 1 ) == "D" ){
            $anio = sql( "SELECT oibiurba.aniovalo FROM oibiurba WHERE oibiurba.codiinmu = $regi[codiobje]" );
	              }
	            }  
	            if ( $tipoobje == "OIBIURBAHIST" ) {
	              // El concepto de inmueble urbana solo tiene un subconcepto
          $subc = sql( "SELECT subxconc.codisubc FROM subxconc WHERE subxconc.codiconc = $concactu" );
	              // El elemento para el calculo de OIBIURBA esta en la tabla oibiurba,
	              // y se trata de una valor base al que se le calcula un porcentaje
          $oper[$subc] = sql( "SELECT oibiurbahist.base FROM oibiurbahist WHERE oibiurbahist.codisequ = $regi[codiobje]" );
        $busc[$subc] = sql( "SELECT oibiurbahist.clavusox FROM oibiurbahist WHERE oibiurbahist.codiinmu = $regi[codiobje]" );
	              if ($oper[$subc] == 0){
	              	if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{
	                	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	                }
	              	continue;
                  }	
	              // Cuando se liquida mas de un objeto de forma directa, el año contraido para cada objeto
	              // es el año del valor catastral de cada objeto
	              if ($codiobje == '' && substr( $modoliqu, 0, 1 ) == "D" ){
            $anio = sql( "SELECT oibiurbahist.aniovalo FROM oibiurbahist WHERE oibiurbahist.codisequ = $regi[codiobje]" );
	              }
	            }  
	            if ( $tipoobje == "OIBIRUST" ) {
	              // El concepto de inmueble rústica solo tiene un subconcepto
          $subc = sql( "SELECT subxconc.codisubc FROM subxconc WHERE subxconc.codiconc = $concactu" );
	              // El elemento para el calculo de OIBIRUST esta en la tabla oibirust,
	              // y se trata de una valor base al que se le calcula un porcentaje
        // DESACTIVO ESTAS DOS LINEAS: Ahora se busca cada finca independientemente.
        // $numeorde = sql ("SELECT oibirust.numeorde FROM oibirust WHERE oibirust.codioibirust = $regi[codiobje]" );
        // $oper[$subc] = sql( "SELECT oibirust.base FROM oibirust WHERE oibirust.numeorde = '$numeorde'" );
        $oper[$subc] = sql( "SELECT oibirust.base FROM oibirust WHERE oibirust.codioibirust = $regi[codiobje]" ); 
	              if ($oper[$subc] == 0){
	              	if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{
	                	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	                }
	              	continue;
                  }		
	              // Cuando se liquida mas de un objeto de forma directa, el año contraido para cada objeto
	              // es el año del valor catastral de cada objeto
	              if ($codiobje == '' && substr( $modoliqu, 0, 1 ) == "D" ){
            $anio = sql( "SELECT oibirust.aniovalo FROM oibirust WHERE oibirust.codioibirust = $regi[codiobje]" );
	              }
	            }
	            if ( $tipoobje == "OIBIRUSTHIST" ) {
	              // El concepto de inmueble rústica solo tiene un subconcepto
          $subc = sql( "SELECT subxconc.codisubc FROM subxconc WHERE subxconc.codiconc = $concactu" );
	              // El elemento para el calculo de OIBIRUST esta en la tabla oibirust,
	              // y se trata de una valor base al que se le calcula un porcentaje
          $oper[$subc] = sql( "SELECT oibirusthist.base FROM oibirusthist WHERE oibirusthist.codisequ = $regi[codiobje]" );
	              if ($oper[$subc] == 0){
	              	if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{
	                	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	                }
	              	continue;
                  }		
	              // Cuando se liquida mas de un objeto de forma directa, el año contraido para cada objeto
	              // es el año del valor catastral de cada objeto
	              if ($codiobje == '' && substr( $modoliqu, 0, 1 ) == "D" ){
            $anio = sql( "SELECT oibirusthist.aniovalo FROM oibirusthist WHERE oibirusthist.codisequ = $regi[codiobje]" );
	              }
	            }
	            if ( $tipoobje == "OICI" ) {
	              // OICI no tiene liquidaciones periodicas. Si pasa por aqui es para realizar
	              // liquidaciones directas, con rangos de fechas (o sin rango de fechas).
	              // El concepto de construcciones solo tiene un subconcepto
          $subc = sql( "SELECT subxconc.codisubc FROM subxconc WHERE subxconc.codiconc = $concactu" );
	              // El elemento para el calculo de OICI esta en la tabla oici,
	              // y se trata de una valor base al que se le calcula un porcentaje
          $oper[$subc] = sql( "SELECT oici.base FROM oici WHERE oici.codioici = $regi[codiobje]" );
	              if ($oper[$subc] == 0){
	              	if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{
	                	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	                }
	              	continue;
                  }		
	            }
	            if ( $tipoobje == "SANCTRAF" ) {
	              // OSANTRAF no tiene liquidaciones periodicas. Si pasa por aqui es para realizar
	              // liquidaciones directas, con rangos de fechas (o sin rango de fechas).
	              // El concepto de construcciones solo tiene un subconcepto
          $subc = sql( "SELECT subxconc.codisubc FROM subxconc WHERE subxconc.codiconc = $concactu" );
	              // El elemento para el calculo de OSANTRAF esta en la tabla osantraf,
	              // y se trata de una valor base al que se le calcula un porcentaje
          $oper[$subc] = sql( "SELECT osantraf.impodenu FROM osantraf WHERE osantraf.codiosan = $regi[codiobje]" );
	              if ($oper[$subc] == 0){
	              	if ($codiobje != ''){
	                	mens("Error: Elemento de calculo no existe");
	                }else{
	                	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	                }
	              	continue;
                  }		
	            }

	            // Como no hay un objeto concreto, no pasara por aqui cuando tipoobje es OIPL
	            // porque las liquidaciones de plusvalia son sobre objetos concretos.
	  
	            // Calcular la deuda de cada objeto 
	            // Primero, si se trata de liquidaciones directas (no es un padrón) no tienen
	            // cabida las domiciliaciones bancarias
	            if ( $modoliqu != 'PER' ) {
                      $regi[iban] = '';
	            }
	            // Ahora comprobamos que si tiene domiciliacion, que sea correcta
                    if ( compdomi( $regi[iban] ) ) {
	              $contdomi++;

	              $resuliqu = calcdeud( $concactu, $anio, $ayun, $busc, $oper, $regi[tipobene], $regi[porcboni], $vectconc[$i][pror], $regi[fechalta], $regi[fechbaja], $tipofech, $modoliqu, $impoingr, $exclsubc, $oper2 );
	            } else {
	              $resuliqu[cuot] = -1;
	              $resuliqu[deud] = -1;
	              if ($codiobje != ''){
	                mens("Domiciliación bancaria errónea en el objeto ".$regi[abreobje]);
	              }else{
	              	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
	              	continue;
	              }
	            }
	
				if ($codiobje != ''){
			        // Si es OIPL y es autoliquidacion, le aplico el porcentaje de recargo,
			        // si no hubo error en el calculo de la deuda.
			        // Si es OIPL, inicializo el importe de demora para posteriormente almacenarlo en cargoipl.
			        if ( $tipoobje == 'OIPL' ) { $impodemo = 0; }
			        if ( ( $resuliqu[deud] > 0 ) && ( $tipoobje == "OIPL" ) && ( $modoliqu == "AUT" ) ) {
			          $resuliqu[deud] = recaplus( $codiobje, $resuliqu[deud] );
			          if ($resuliqu[deud] == -1){
			          	if ($codiobje != ''){
			          		mens( "Error: se ha eliminado el objeto de plusvalía");
			          	}else{
			              	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );
			              	continue;			          		
			          	}
			          }
			        } else {
			          // Si se trata de plusvalia, pero no es autoliquidacion, se pone el recargo
			          // de autoliquidacion a 0 en la tabla oipl, pero calculamos el interes de 
			          // demora
			          if ( $tipoobje == "OIPL" ) {
            sql( "UPDATE oipl SET imporeca = 0 WHERE oipl.codioipl = $codiobje" );
			            // Interes de demora AAAAA
			            // Cálculo del interés de demora, si hay deuda
			            if (($resuliqu[deud] > 0) && ($tipoobje == 'OIPL')) {
              $fechoipl = sql ("SELECT oipl.fechescr, oipl.fechpres, oipl.fechdeve, oipl.clastran, oipl.regipror
			                                FROM oipl
                                WHERE oipl.codioipl = $codiobje");
			                      
			              if (is_array($fechoipl)) {
			                $fechoipl = each($fechoipl);
			                $fechoipl = $fechoipl[value];
			                       
			                // Solo en las HERENCIAS por ser causa el FALLECIMIENTO van
			                // con un plazo diferente 
			                $clastran = $fechoipl[clastran];
			                if ($clastran=='HER'){ 
                  if (trim($fechoipl[regipror]) != null) $meses_herencia = $config->getValue('oipl.herencia.meses presentacion sin intereses con prorroga', $ayun);
                  else $meses_herencia = $config->getValue('oipl.herencia.meses presentacion sin intereses', $ayun);
			                   $campfech = split( '[./-]', $fechoipl[fechdeve]);
                  $fechinidemora = date ('Y-m-d', mktime (0, 0, 0, $campfech[1] + $meses_herencia, $campfech[2],  $campfech[0])); 
			                } else  $fechinidemora = sumaDiasHabi ($fechoipl[fechdeve], 30, $ayun);
			                $fechfindemora = $fechoipl[fechpres];        
			                if ($fechoipl[fechpres] > $fechinidemora) {                    
			                  $impodemocalc = impodemo ('PEN', '', 
			                                            $fechinidemora, 
			                                            sumaDias(ereg_replace('-', '', $fechfindemora), -1), 
			                                            $resuliqu[cuot], 
			                                            $fechfindemora);
			                } else {
			                  $impodemocalc = 0;
			                }  
			                $resuliqu[deud] += $impodemocalc;
			                
			                $impodemo = $impodemocalc;
			              }
			            }
			          }
			        }
        
			        if ( $tipoobje == 'OIPL' ) {
			          $campextr = $impodemo;
		            }
				}
	
      $vect[$contobje] = creavect ( $concactu, $vectconc[$i][abreconc], $grupactu, $vectconc[$i][abregrup], $regi[nifx], $regi[nombsuje], $regi[codiobje], $regi[abreobje], $resuliqu, $regi[iban], $regi[numerefe], $anio, $campextr );

	            if ($modoliqu != 'PER'){
		            // Guardo el resultado de la liquidacion del objeto en un vector, junto con toda
		            // la informacion relacionada con dicha liquidacion
		            // Muestro el resumen del objeto que se desea liquidar en esta iteracion del bucle for
		            if ( ( $contobje % 50 == 0 ) && ( $contobje > 0 ) ) {
		              print "</table>\n";
		              salto();
		              caberesu();
		            }
		            // Muestro el resumen del objeto que se desea liquidar en esta iteracion del bucle for
		            resucarg( $vect[$contobje], ($otroindice < 0?$contobje:$otroindice), $ayun, $anio, $periliqu, $modoliqu);
		        }else{		            
        // La cuota se suma SIEMPRE (Si es positiva)
        if ($vect[$contobje][cuot] > 0) {
          $query  = "UPDATE liqupadr SET cuotneto = cuotneto + " . $vect[$contobje][cuot];
          $query .= " WHERE liqupadr.numeregi = $numeregi AND liqupadr.numeproc > -1;"; 
          sql ($query);
        }
        
		            if ($vect[$contobje][deud] > 0){
		                $nuevo = false;
          if ($codipadrobje = sql("SELECT liqupadrobje.codisequ FROM liqupadrobje WHERE liqupadrobje.codipadr = $numeregi AND liqupadrobje.coditrib = '" . $regi[abreobje] . "'")) {
            $query = "UPDATE liqupadrobje 
                      SET deud = deud + " . $vect[$contobje][deud] . " ,
                          cuot = cuot + " . $vect[$contobje][cuot] . " 
                      WHERE liqupadrobje.codipadr = $numeregi 
                        AND liqupadrobje.coditrib = '" . $regi[abreobje] . "'";
            $query .= "; UPDATE liqupadr SET deudneto = deudneto + " . $vect[$contobje][deud]; 
            $query .= " WHERE liqupadr.numeregi = $numeregi AND liqupadr.numeproc > -1;"; 

			            	sql($query);
			            }else{
			                //Comprobamos si es un objeto del propio ayuntamiento y si se han de liquidar
			                if (!$nifxayun || $ayunnifx != $regi[nifx]){
				                //Comprobamos que no exista ya un cargo para este objeto para el mismo año contraido.
	                if (sql("SELECT count(*) FROM carg WHERE carg.codiayun = $ayun and carg.codiobje = '" . $regi[codiobje] . "' and carg.codiconc = " . $concactu . " and carg.anio = '$anio' and carg.periliqu = '$periliqu'") == 0){
                $query = "INSERT INTO liqupadrobje (codipadr, coditrib, codiobje, cuot, deud) VALUES ('$numeregi', '" . $regi[abreobje] . "', '" . $regi[codiobje] . "', '" . number_format ($vect[$contobje][cuot], 0, '', '') . "', '" .  number_format ($vect[$contobje][deud], 0, '', '') . "');"; 
                $query .= "UPDATE liqupadr SET deudneto = deudneto + " . $vect[$contobje][deud]; 
                $query .= " WHERE liqupadr.numeregi = $numeregi AND liqupadr.numeproc > -1; "; 
                $query .= "SELECT max(liqupadrobje.codisequ) FROM liqupadrobje WHERE liqupadrobje.codipadr = $numeregi";
					            	$codipadrobje = sql($query);
					            	$nuevo = true;
				            	}
			            	}else{
              sql("UPDATE liqupadr SET numeexen = numeexen + 1 WHERE liqupadr.numeregi = $numeregi");
			            	}
			            }
          
		            	$query = "";
		            	if ($codipadrobje && $nuevo){
            	$contsubc = sql( "SELECT subxconc.codisubc, subxconc.abre FROM subxconc WHERE subxconc.codiconc = " . $vect[$contobje][codiconc] );
							if ( count( $contsubc ) > 1 ) {
							    // Si el concepto tiene más de 1 subconcepto, se insertan los subimportes en liqupadrsubc.
							    while( $regisubc = each( $contsubc ) ) {
							      $regi = $regisubc[value];
							      $abresubc = $regi[abre];
							      if ( isset( $vect[$contobje][$abresubc] ) && $vect[$contobje][$abresubc] > 0) {
							        $query .= "INSERT INTO liqupadrsubc (codipadrobje, codisubc, subxdeud) 
							                   VALUES ('$codipadrobje', '$regi[codisubc]', '" . $vect[$contobje][$abresubc] . "');";
							      }
							    }
							}		         
						}
          
          $query .= "UPDATE liqupadr SET numeproc = numeproc + 1 ";
						if ($vect[$contobje][domibanc] == 1){
            $query .= ", numedomi = numedomi + 1 ";
            $query .= ", domineto = domineto + " . $vect[$contobje][deud];
						}
						if ($regi[porcboni] > 0){
            $query .= ", numeboni = numeboni + 1 ";							
						}
        	$query .= " WHERE liqupadr.numeregi = $numeregi AND liqupadr.numeproc > -1;"; 
          
			        	sql($query);
          
		            }else{
		                if ($regi[tipobene] == 'e'){
            sql("UPDATE liqupadr set numeexen = numeexen + 1 WHERE liqupadr.numeregi = $numeregi");
		                }else{
		                	inseinci( 'LIQUPADRINCI', $inciarray, 'LIQUERRO', $usua, $rutiname );		                	
		                }
		                $query = '';
		            }
		        }
	            $contobje++;
	        }
        } 

        if ($modoliqu == 'PER' and !$cancelado){
    sql("UPDATE liqupadr set fechfinacalc = '" . date('Y-m-d', time()) . "', horafinacalc = '" . date('H:i:s', time()) . "' WHERE liqupadr.numeregi = $numeregi");         	
        }         
        return $contobje;
}

function creapadrquery($codiobje, $ayun, $conc, $concgrup, $codigrup, $periliqu, $anio, $modoliqu, $codifigu = -1){
  if ($concgrup != 'H'){
	  $resu = sql("SELECT tipoobje.nombtabl, tipoobje.campabre, conctrib.tipoobje as tipoobje FROM conctrib INNER JOIN tipoobje ON conctrib.tipoobje = tipoobje.tipoobje WHERE conctrib.codiconc = $conc");
  }else{
  	$concaux = sql("SELECT conctrib.abre FROM conctrib WHERE conctrib.codiconc = $conc");
  	  switch ($concaux){ 
  	  	case 'IBIURB':
		  $resu = sql("SELECT tipoobje.nombtabl, tipoobje.campabre, tipoobje as tipoobje FROM tipoobje WHERE tipoobje.tipoobje = 'OIBIURBAHIST'");  	
		break;
		case 'IBIRUS':
		  $resu = sql("SELECT tipoobje.nombtabl, tipoobje.campabre, tipoobje as tipoobje FROM tipoobje WHERE tipoobje.tipoobje = 'OIBIRUSTHIST'");  	
		break;
	  }
  }	  
  if (!$resu)
  	return '';

  // Obtener el codigo de la figura del CONTRIBUYENTE PRINCIPAL, que es a quien
  // se le asigna la deuda
  if ($codifigu <= 0) $codifigu = sql( "SELECT tercfigu.codifigu FROM tercfigu WHERE tercfigu.abrefigu = 'CNP'" );
  
  // Determino el campo que es clave en la tabla de objetos, y el campo abreviatura que describe 
  // al objeto
  $resu = $resu[0];
  $tabl = $resu[nombtabl];
  $campabre = $resu[campabre];
  $tipoobje = $resu[tipoobje];
  $clav = pg_primaryKey($tabl);
  if ($concgrup == 'H'){
  	$clav = $tabl.'.codisequ';
  }
  if ($tabl == 'inmu'){
     $tabl = 'oibiurba';
     
     // El campo clave ahora trae incorporado el nombre de la tabla pero
     // para 'inmu' cambia un poco
     $clav = ereg_replace ('inmu.', 'oibiurba.', $clav);
  }
  $count = sql("SELECT count(*) FROM subxconc, tipocalc
                WHERE subxconc.codiconc = $conc AND tipocalc.coditipo = subxconc.coditipo 
                AND EXISTS (SELECT tari.abre, tari.nomb FROM tari WHERE tari.codisubc = subxconc.codisubc AND tari.ejer = '$anio')");
                
  if ($count <= 0){
	//print "ERROR: No existen subconceptos asociados a ese concepto tributario (" . $vectconc[$i][abreconc] . ") en ese ejercicio ($anio)<br>";
	return '';
  }
	  	
  // Leer todos los objetos de ese concepto desde la base de datos
  // a no ser que se trate de un objeto concreto.
  if ($concgrup == 'H'){
	  $query = "SELECT $clav as codiobje, 
	                   ".$campabre." as abreobje, 
	                   ".$tabl.".tipobene, 
	                   ".$tabl.".porcboni, 
	                   ".$tabl.".fechinicbene as fechinibene, 
	                   ".$tabl.".fechfinabene as fechfinbene, 
	                   ".$tabl.".nifx, 
	                   ".$tabl.".nomb as nombsuje, 
	                   ".$tabl.".iban, 
	                ".$tabl.".numerefe ";  	
  }else{
	  $query = "SELECT $clav as codiobje, 
	                   ".$campabre." as abreobje, 
	                   B.tipo as tipobene, 
	                   BO.porcboni, 
	                   BO.fechinibene, 
	                   BO.fechfinbene, 
	                   TD.nifx, 
	                   TD.nomb as nombsuje, 
	                   D.iban, 
	                D.numerefe ";
  }
  
  if ( $tipoobje != "OIPL" && $tipoobje != "OIBIURBA" && $tipoobje != "OIBIRUST" && $tipoobje != "SANCTRAF" && $tipoobje != "OIBIURBAHIST" && $tipoobje != "OIBIRUSTHIST") {
  	// Plusvalia y urbana no tienen fecha de alta ni de baja
    $query .= ", ".$tabl.".fechinic as fechalta, ".$tabl.".fechbaja ";
  }
  if ( $tipoobje == "OTPP" ) {
    // Para el prorrateo, si el devengo es "Al presentar la solicitud", la fecha
    // de alta es el campo otpp.fechsoli, y no otpp.fechinic
    $query .= ", ".$tabl.".fechsoli ";
  }
  if ($concgrup == 'H'){
      $query .= "FROM $tabl";
  }else{
      $query .= "FROM ( ( ( ".$tabl." LEFT JOIN (benetrib B INNER JOIN benetribobje BO 
                 ON B.codibene = BO.codibene AND BO.tipoobje = '$tipoobje' ) 
                 ON $clav = BO.codiobje AND BO.tipoobje = '$tipoobje') 
                 LEFT JOIN domibancobje D ON $clav = D.codiobje AND D.tipoobje = '$tipoobje' ) 
                 LEFT JOIN (tercobje T INNER JOIN tercdato TD ON T.coditerc = TD.coditerc 
                 AND T.tipoobje = '$tipoobje' AND codifigu = '$codifigu' ) 
                 ON T.codiobje = $clav ) ";
  }
  if ( $tipoobje == 'OICI' ) {
    $query .= ", subxobra, tipoobra ";
  }
  
  if ( $tipoobje == 'OIPL' ) {
    $query .= ", nota ";
  }

  if ( $tipoobje == 'OIBIURBA' ) {
    $query .= " INNER JOIN inmu ON $tabl.codiinmu = inmu.codiinmu ";
    $query .= " WHERE inmu.codiayun = $ayun";
        
    // Las bajas de OIBIURBA no deben entrar en el padrón.
        if ($modoliqu == 'PER' and $periliqu=='PA') {
          $query .= " AND inmu.baja != 1 ";
    }
        
  } else {
    $query .= " WHERE ".$tabl.".codiayun = $ayun";
  }
  
      if ($codiobje != '')
    $query .= "AND $clav = '$codiobje'";
    
  if ( $tipoobje == 'OTPP' ) {
    // Solo la tabla OTPP de entre todas las de objetos (oicv, oibi, ...) tiene el campo codiconc
    // porque en las demas esta implicito en el impuesto
        if ( $concgrup == "C" ) {
          $query .= " AND ".$tabl.".codiconc = $conc";
        }
        if ( $concgrup == "G" ) {
          $query .= " AND ".$tabl.".codiconc = $grupactu";
        }
  }
  if ( ( $tipoobje != 'OTPP' ) && ( $tipoobje != 'OIPL' ) && ( $tipoobje != 'OICI' ) && ( $tipoobje != 'SANCTRAF') ) {
    // Las tablas otpp, oipl, oici y osantraf aun no tienen el campo exclliqu.
    // Cuando se les ponga, quitar este IF, y añadir siempre la condicion
    // al query de que no estén excluidos de la liquidacion.
    $query .= " AND ".$tabl.".exclliqu = '0'";
  }
  if ( $tipoobje == 'OICI' ) {
    $query .= " AND ".$tabl.".codisubxobra = subxobra.codisubxobra
                AND subxobra.codiobra = tipoobra.codiobra ";
  }
  if ( $tipoobje == 'OIPL' ) {
    $query .= " AND nota.codinota = ".$tabl.".codinota ";
  }
  if ( $tipoobje == 'OIAE') {
    // Solo entran en el padron los registros que venian del fichero con ejercicio de extraccion
    // y ejercicio de efectividad igual al año contraido de la deuda del padron, que el tipo de
    // informacion sea "Matricula para Liquidacion" y que el periodo de extraccion sea "Anual".
    $query .= " AND ".$tabl.".ejerefec = '$anio'";

        if ($modoliqu == 'PER') {
    $query .= " AND ".$tabl.".ejerextr = '$anio'
                     AND ".$tabl.".tipoinfo = 'M'
                AND ".$tabl.".periextr = '0A'";
  }
      }
  if ( $tipoobje == 'OIBIURBA' || $tipoobje == 'OIBIRUST' ) {
    if ( $docdgc ) {
      $query .= " AND ".$tabl.".rutimodi = 'APDOCDGC'";
    }
  }
  
  // Ordenar los objetos alfabeticamente
//  $query .= " ORDER BY abreobje";
  return $query;
}
?>      

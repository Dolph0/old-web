<?
include "liqu/redocuot.fnc";
include "liqu/liqu.fnc";

function deuda( $ayun, $conc, $codiobje, $codiconc, $tipoobje, $modoliqu, $periliqu, $anio, $soliquery, $altaquery, $modiquery, $impoingr, $docdgc, $fechaplibene, $usua = 0, $fechinicvolu = '0001-01-01', $fechfinavolu = '0001-01-01', $echo = true, $otroindice = -1, $fechinicvolu_2 = '0001-01-01', $fechfinavolu_2 = '0001-01-01', $liqudivi = 0, $query_extra = '') {
  // Parametros:
  // ayun es el codigo de ayuntamiento del usuario conectado
  // conc es el codido del concepto o codigo del grupo de conceptos que se liquidan
  // codiobje esta en blanco o tiene un valor si la liquidacion es invocada 
  //   desde la pagina de un objeto concreto
  // codiconc es el concepto o grupo de conceptos inicialmente elegido a liquidar. 
  //   Si codiobje es distinto de blanco, se liquida un objeto concreto asociado a un concepto o grupo.
  //   Si codiobje esta en blanco hay 3 casos posibles:
  //    - Si codiconc es un grupo, se liquidan los objetos de ese grupo, asi como los objetos asociados
  //     a conceptos que pertenecen a ese grupo
  //    - Si codiconc es un concepto, solo se liquidan los objetos asociados a ese concepto concreto 
  // tipoobje nos indica de que tipo de objeto estamos hablando: OTPP, OICV, ... y asi, sabemos en 
  //   que tabla buscar los objetoS
  // modoliqu es la modalidad de liquidacion. Hace falta para saber si la liquidacion no es periodica
  // cuando se buscan objetos de determinadas fechas
  // anio es el año contraído
  // soliquery, altaquery, modiquery indican la condicion que se añade al query, referente a los 
  // intervalos de fechas en los que se encuentran los objetos que se van a liquidar.
  // impoingr es un importe que se le resta a la deuda de los objetos, y solo
  // tiene valor si la liquidacion es directa. Vienen en centimos de euro
  // El parametro docdgc especifica cuando se liquidan sólo los objeto cargados del DOC-DGC (solo en
  // urbana y rústica).
  // $fechinicvolu_2, $fechfinavolu_2: Segundo plazo de ingreso
  // $liqudivi: Divide el cargo en dos.
  //
  // Primero compruebo si se calcula la deuda de un concepto, 
  // o de los conceptos que pertenecen a un grupo
  // Los conceptos o el concepto que hay que liquidar se guardan en el vector $vectconc
  // El contador $cont indica el numero de conceptos a liquidar
  
  $cont = 0;  // Inicializacion del contador

  // Codigo del concepto o del grupo que se solicita liquidar
  $codi = substr($conc,1);
  // tipo tiene el valor C o G segun lo que se solicita liquidar, un concepto o un grupo
  $tipo = substr($conc, 0, 1);

  // Construyo el vector de conceptos segun nos pasa por parametros un concepto o un grupo
  if ( $tipo == "C" || $tipo == "H") {
    // Se liquida un solo concepto cuando la liquidacion es invocada desde la pagina de un 
    // objeto concreto asociado a un concepto (aunque el concepto pueda pertenecer a un grupo), 
    // o bien, cuando liquidamos los objetos de un concepto que no pertenece a un grupo, por ejemplo
    // cuando es invocado desde el menu de un impuesto concreto, o cuando es invocado desde el menu
    // otpp y se elige un concepto 
    // Solo se liquida un concepto
    $query = "SELECT conctrib.codiconc, conctrib.abre, conctrib.pror, conctrib.deve 
              FROM conctrib 
              WHERE conctrib.codiayun = $ayun AND conctrib.codiconc = $codi";
    $resp = sql($query);
    if ( is_array( $resp ) ) {
      $regi = each( $resp );
      $regi = $regi[value];
      $vectconc[$cont][codiconc] = $regi[codiconc];
      $vectconc[$cont][abreconc] = $regi[abre];
      // Indicamos que en esta posicion del vector hay un concepto y no un grupo
      $vectconc[$cont][concgrup] = $tipo;
      $vectconc[$cont][pror] = $regi[pror];
      $vectconc[$cont][deve] = $regi[deve];
      $cont++;
    }
  } elseif ( $tipo == "G" ) {

      // Si se selecciono un grupo, al menos se liquidan los objetos asociados a ese grupo.
      $vectconc[$cont][codigrup] = $codi;
      $vectconc[$cont][abregrup] = sql("SELECT grupconc.abre FROM grupconc WHERE grupconc.codigrup = $codi");
      // Indicamos que en esta posicion del vector hay un grupo y no un concepto
      $vectconc[$cont][concgrup] = "G";
      // Los valores de prorrateo y devengo se toman cuando se liquidan los objetos 
      // asociados al grupo, y leemos uno a uno los conceptos que forman el grupo. Pero como digo,
      // esto es antes de calcular la deuda y no aqui
      //$vectconc[$cont][pror] = $regi[pror];
      //$vectconc[$cont][deve] = $regi[deve];
      $cont++;

      // Si la liquidacion es invocada desde la pagina de un objeto otpp concreto asociado a un grupo,
      // y no a un concepto, entonces solo se liquidarian los objetos asociados a ese grupo.

      // Pero si es invocada desde el menu otpp, y se selecciona un grupo de conceptos, entoces
      // se liquidan los objetos asociados a ese grupo, asi como los objetos asociados a los conceptos
      // que pertenecen a ese grupo
      if ( $codiobje == "" ) {
        // Se liquidan los objetos pertenecientes a un grupo, asi como los objetos asociados
        // a conceptos que pertencen a ese grupo
        $query = "SELECT conctrib.codiconc, conctrib.abre, conctrib.pror, conctrib.deve 
                  FROM conctrib 
                  WHERE conctrib.codiayun = $ayun AND conctrib.codigrup = $codi";
        $resp = sql($query);
        if ( is_array( $resp ) ) {
          while ( $regi = each( $resp ) ) {
            $regi = $regi[value];
            $vectconc[$cont][codiconc] = $regi[codiconc];
            $vectconc[$cont][abreconc] = $regi[abre];
            // Indicamos que en esta posicion del vector hay un concepto y no un grupo
            $vectconc[$cont][concgrup] = "C";
            $vectconc[$cont][pror] = $regi[pror];
            $vectconc[$cont][deve] = $regi[deve];
            $cont++;
          }
        }
      }
  } else {  // Ha habido un error; el primer caracter del parametro $conc debe ser "C","G" o "H"
	  return -1;
  }


  // Determinar de que tabla leemos los objetos segun el parametro $tipoobje
  // ya que los objetos pueden estar en diferentes tablas: oicv, otpp, oiae,...
  $resu = sql("SELECT tipoobje.nombtabl, tipoobje.campabre FROM tipoobje WHERE tipoobje.tipoobje = '$tipoobje'");
  if ( !$resu ){
  	 return -1;  // Error en el tipo de objeto
  }
  // Determino el campo que es clave en la tabla de objetos, y el campo abreviatura que describe 
  // al objeto
  $resu = $resu[0];
  $tabl = $resu[nombtabl];
  $campabre = $resu[campabre];
  $clav = pg_primaryKey($tabl);
  if ($tabl == 'inmu'){
     $tabl = 'oibiurba';
  }

  // Obtener el codigo de la figura del CONTRIBUYENTE PRINCIPAL, que es a quien
  // se le asigna la deuda
  $codifigu = sql( "SELECT tercfigu.codifigu FROM tercfigu WHERE tercfigu.abrefigu = 'CNP'" );

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

  // Me aseguro de que si no se trata de una liquidacion directa, el importe ingresado sea 0
  if ( substr( $modoliqu, 0, 1 ) != "D" ){
    $impoingr = 0;
  }
  set_time_limit(0);
  
  if ($modoliqu != "PER" && $echo){
	  print "<hr>\n<br><br><br>\n";
	  // Mostrar una pequeña ayuda que permita al usuario saber que hacer en cada
	  // caso de la liquidación, cuando un cargo ya fue generado anteriormente
	  echo "Cuando un objeto ya tiene cargos generados anteriormente para el Año Contraído y Período seleccionados:
	    <UL>
	    <LI>Si se trata de una <b>liquidación directa</b>:
	        <UL>
	        <LI>Siempre se genera el nuevo cargo deseado.
	        <LI>Si se marca la casilla \"Pasar a PENDIENTE DE ANULAR\", al generar el nuevo cargo
	            se SUSPENDERÁN todos los cargos anteriores que estén pendientes de cobro.
	        </UL>
	    <LI>Si se trata de una <b>liquidación periódica</b>:
	        <UL>
	        <LI>Solo se genera el nuevo cargo si ninguno de los existentes está ingresado.
	        <LI>Se deben \"Pasar a PENDIENTE DE ANULAR\" si existen cargos PENDIENTES DE COBRO.
	        </UL>
	    </UL>\n";
	  
	  // Mostrar la cabecera de la tabla resumen de lo que se pretende liquidar
	  // Podría darse el caso de que el concepto no tuviera objetos, con lo que la tabla estará vacia
	  print "<br>\n<br>\n";
	  print "<center>\n";
	  print "<b>Resumen del cargo a generar</b><br>\n";
	  print "(Espere hasta que aparezca el botón 'Confirmar Cargo')\n";
	  print "<br><br>\n";
	  salto();
	  caberesu();
  }
  // Contador de objetos a liquidar. Pero si se liquida un objeto para un grupo de dos conceptos, 
  // el contador considera dos objetos y no solo uno porque esta liquidando el objeto tantas veces
  // como conceptos tenga el grupo
  $contobje = 0; 

  // Recorrer el vector de conceptos y calcular la deuda de cada uno
  for ($i=0; $i<$cont; $i++) {
  	  // Comprobamos que exista al menos un subconcepto asociado al concepto tributario
  	  $count = sql("SELECT count(*) FROM subxconc, tipocalc
        WHERE subxconc.codiconc = " . $vectconc[$i][codiconc] . " 
         AND tipocalc.coditipo = subxconc.coditipo 
        AND EXISTS (SELECT tari.coditari FROM tari WHERE tari.codisubc = subxconc.codisubc AND tari.ejer = '$anio')");
	  if ($count <= 0){
	  		print "ERROR: No existen subconceptos asociados a ese concepto tributario (" . $vectconc[$i][abreconc] . ") en ese ejercicio ($anio)<br>";
	  		continue;
	  }
      // En cada iteracion del bucle for, se trata un concepto o un grupo
      // Variable que nos indica si tratamos un grupo o un concepto en la iteracion actual
      $concgrup = $vectconc[$i][concgrup];
      if ( $concgrup == "C" || $concgrup == "H") {
        // Si en la iteracion actual del bucle for tratamos un concepto
        $concactu = $vectconc[$i][codiconc];
        $grupactu = "";
      }
      if ( $concgrup == "G" ) {
        // Si lo que tratamos en esta iteracion es un grupo
        $grupactu = $vectconc[$i][codigrup];
        $concactu = "";
      }


      // Se liquidan todos los objetos del concepto/conceptos/grupo
      // a no ser que se trate de un solo objeto 
      if ($codiobje != ''){
	      // Comprobamos si el Contribuyente Principal es extranjero, en ese caso el Contribuyente 
	      // Principal sería el Adquirente si es español, sino sería el Representante
	      if ( $tipoobje == "OIPL" ) {
	        $clastran= sql ("SELECT oipl.clastran FROM oipl WHERE oipl.codioipl = $codiobje"); 
	        if ($clastran=="CVT" || $clastran=="EXP" || $clastran=="DAC" || $clastran=="CAP" ||
	            $clastran=="SUB" || $clastran=="LIS" || $clastran=="SEN" || $clastran=="ABS" ||
	            $clastran=="DIS" || $clastran=="ADJ" || $clastran=="FUS"){
	          $coditerc= sql ("SELECT tercobje.coditerc FROM tercobje WHERE tercobje.tipoobje = 'OIPL' AND tercobje.codiobje = $codiobje AND tercobje.codifigu = $codifigu");
	          $codipais = sql ("SELECT tercdato.codipais FROM tercdato WHERE tercdato.coditerc = $coditerc");
	          $codiespa = sql ("SELECT pais.codipais FROM pais where pais.nomb='ESPAÑA'");
	          if ($codipais!=$codiespa)   
	             $codifigu = sql("SELECT tercfigu.codifigu FROM tercfigu WHERE tercfigu.abrefigu = 'ADQ'");
	        }
	      }
	
	      // No puede ocurrir que el objeto que liquidamos no sea del concepto/grupo seleccionado
	      // pero por si acaso, lo compruebo
	      // Si el objeto no pertenece a ese concepto no se tiene en cuenta a la hora de calcular 
	      // la deuda. Esto solo puede ocurrir con OTPP
	      if ( $tipoobje == "OTPP" ) {
	        $concotpp = sql("SELECT otpp.codiconc FROM otpp WHERE otpp.codiotpp = $codiobje AND otpp.codiayun = $ayun");
	        if ( $concgrup == "C" ) {
	          if ( $concactu != $concotpp ) {
	            // El concepto/grupo al que pertenece el objeto es distinto del concepto que estamos tratando
	            $mens = "Ese objeto no está asociado al concepto ".sql("SELECT conctrib.nomb FROM conctrib WHERE conctrib.codiconc = $concactu");
	            mens($mens);
	            continue;
	          }
	        }
	        if ( $concgrup == "G" ) {
	          if ( $grupactu != $concotpp ) {
	            // El concepto/grupo al que pertenece el objeto es distinto del concepto que estamos tratando
	            $mens = "Ese objeto no está asociado al grupo ".sql("SELECT grupconc.nomb FROM grupconc WHERE grupconc.codigrup = $grupactu");
	            mens($mens);
	            continue;
	          }
	        }
	      }
      }

	  $query = creapadrquery($codiobje, $ayun, $codi, $concgrup, $grupactu, $peri, $anio, $modoliqu, $codifigu);

//      if ($codiobje != ''){
	      // Aqui es donde se establece el conjunto de objetos que se liquidan segun el rango de las 
	      // fechas de solicitud, de alta y de ultimo tramite
	      // La tabla otpp tiene las fechas de solicitud, alta y ultimo tramite
	      // La tabla oicv solo tiene el campo de ultimo tramite
	      if ( $modoliqu != "PER" ) {
	        // Si la liquidacion es periodica, no se seleccionan objetos segun las fechas, 
	        // sino todos los objetos
	        // Revisar esta parte, porque se han actualizado varias tablas y se le han
	        // añadido los campos de fechas.
	        if ( ( $tipoobje == 'OTPP' ) || ( $tipoobje == 'OICI' ) ) {
	          if ( $soliquery ){
	             $soliquery = str_replace('camprempl', 'fechsoli', $soliquery);
	             $query .= $soliquery;
	          }
	          if ( $altaquery ){
	             $altaquery = str_replace('camprempl', 'fechinic', $altaquery);
	             $query .= $altaquery;
	          }
	        }
	       /* if ( $tipoobje == 'OICV' ) {
	          if ( $modiquery ){
	             $modiquery = str_replace('camprempl', 'fechtram', $modiquery);
	             $query .= $modiquery;
	          }
	        } */
	        if ( ( $tipoobje == 'OIAE' ) || ( $tipoobje == 'OIPL' ) || ( $tipoobje == 'OICI' ) || 
	             ( $tipoobje == 'OIBIURBA' ) || ( $tipoobje == 'OIBIRUST' ) || ( $tipoobje == 'OTPP' ) || ($tipoobje == 'OICV') ) {
	          if ( $modiquery ){
	             $modiquery = str_replace('camprempl', $tabl.".fechmodi", $modiquery);
	             $query .= $modiquery;
	          }
	        }
          
                // Este nuevo parametro, añade mas condiciones de seleccion al query de liquidacion.
                // Su uso se realiza con las consultas obtenidas de cada objeto tributario.
                // Puesto que el query hace referencia unicamente a la tabla que se liquida, la formula
                // del query que se añade deberia ser.
                // AND <tabla>.codigo in (<lista de codigos>)
                // Ej. para OICV, ' AND oicv.codioicv IN (343,455,66787,3343, y todos los que sean) '
                if ($query_extra != '') {
                  $query .= $query_extra;
                }
	      } else {
	        // PERIODICA
	        // ---- En los padrones de VEHICULOS no se tienen en cuenta las altas nuevas
	        if ( $tipoobje == 'OICV' ) {
	          if ( $altaquery ){
                    $altaquery = str_replace('camprempl', 'fechinic', $altaquery);
                    $query .= $altaquery;
	          }
	        }
	        
	        if ( ( $tipoobje == 'OTPP' ) ) {
	          if ( $soliquery ){
	             $soliquery = str_replace('camprempl', 'otpp.fechsoli', $soliquery);
	             $query .= $soliquery;
	          }
	          if ( $altaquery ){
	             $altaquery = str_replace('camprempl', 'otpp.fechinic', $altaquery);
	             $query .= $altaquery;
	          }
	          if ( $modiquery ){
	             $modiquery = str_replace('camprempl', 'otpp.fechmodi', $modiquery);
	             $query .= $modiquery;
	         }
	       } 
	        
              }
	      
	      
//      }
      // Ordenar los objetos alfabeticamente
      if ($modoliqu != 'PER')
      	$query .= " ORDER BY abreobje";

      if ($modoliqu == 'PER'){
      	$objetos = sql(ereg_replace("SELECT (.*) FROM", "SELECT COUNT(*) FROM", $query));
      	if ($objetos == 0){
	        // Ese concepto no tiene objetos. Hay que notificarselo al usuario
	        if ( $concgrup == "C" ) {
	          $mens = "El concepto ".sql("SELECT conctrib.nomb FROM conctrib WHERE conctrib.codiconc = $concactu")." no tiene objetos para liquidar";
	        } else {
	          $mens = "El grupo ".sql("SELECT grupconc.nomb FROM grupconc WHERE grupconc.codigrup = $grupactu")." no tiene objetos para liquidar";
	        }
	        if ( $soliquery || $altaquery || $modiquery ) {
	          $mens .= " en esas fechas";
	        }
	        mens($mens);
      		continue;
      	}
      	// Si el modo de liquidación es periódica
      	// lanzamos un proceso en background
      	// y dibujamos un proceso ajax que nos vaya 
      	// informando del estado del proceso
      	$resu = sql("SELECT count(*) 
                     FROM liqupadr 
                     WHERE liqupadr.codiconc = $concactu 
                       AND liqupadr.anio = '$anio' 
                       AND liqupadr.peri = '$periliqu'
      									AND liqupadr.codiayun = $ayun");

        if ($resu == 0){
	      	 // Semaforo para otros procesos que quieran
	      	 // calcular la deuda de un padrón     		
    		 $SHM_KEY = ftok("/var/log/error20.log", chr( 2 ) );
    		 $shmid = sem_get($SHM_KEY, 1, 0666 | IPC_CREAT);
    		 sem_acquire($shmid);

			 // Semaforo para el proceso hijo
    		 $SHM_KEY = ftok("/var/log/error20.log", chr( 3 ) );
    		 $shmid2 = sem_get($SHM_KEY, 1, 0666 | IPC_CREAT);
    		 sem_acquire($shmid2);
    		 
    		 $SHM_KEY = ftok("/var/log/error20.log", chr( 4 ) );
			 
  			 $data =  shm_attach($SHM_KEY, 3072, 0666);
						 
			 shm_put_var($data, 1, $query);
			 shm_put_var($data, 2, $anio);
			 shm_put_var($data, 3, $tipoobje);
			 shm_put_var($data, 4, $codiobje);
			 shm_put_var($data, 5, $concactu);
			 shm_put_var($data, 6, $modoliqu);
			 shm_put_var($data, 7, $periliqu);
			 shm_put_var($data, 8, $ayun);
			 shm_put_var($data, 9, $vectconc);
			 shm_put_var($data, 10, $cont);
			 shm_put_var($data, 11, $vect);
			 shm_put_var($data, 12, $fechaplibene);
			 shm_put_var($data, 13, $usua);
			 shm_put_var($data, 14, $fechinicvolu);
			 shm_put_var($data, 15, $fechfinavolu);
  			 shm_put_var($data, 16, $fechinicvolu_2);
  			 shm_put_var($data, 17, $fechfinavolu_2);
  			 shm_put_var($data, 18, $liqudivi);
			 		 
			 shm_detach($data);
	      	
	      	 $command = 'php ' . getFullPath("liqu/deudabackground.php") . ' > /var/log/error2.log &';
  	      	 system($command);

	      	 sem_release($shmid2);
	      	 sleep(1);
	      	 // Esperamos por que el proceso hijo lea la memoria compartida
	      	 sem_acquire($shmid2);
	      	 sem_release($shmid2);
	      	 // Permitimos que otros procesos se ejecuten
	      	 sem_release($shmid);
	      	 
        }else{
        	mens('Ya existe un padrón en ejecución para este concepto, ejercicio y periodo');
        }	
        
      	//Mostramos la barra de progreso del proceso
      	?>
      	<center>
      	<div id='padrcalcinic'>
      	</div>
      	</center>
			<script>
			      	refrcalcinic(<? print $ayun ?>, <? print $usua ?>);
			</script>
		<?
      	
    	
      	continue;
      }
      $contliqu = 0;

      if ( !($contliqu = liquida($query, $anio, $tipoobje, $codiobje, $concactu, $modoliqu, $periliqu, $ayun, $usua, $vectconc, $i, $vect, '0001-01-01', '0001-01-01', $fechaplibene, $impoingr, $otroindice)) ) {
        if ($codiobje == ''){
	        // Ese concepto no tiene objetos. Hay que notificarselo al usuario
	        if ( $concgrup == "C" ) {
	          $mens = "El concepto ".sql("SELECT conctrib.nomb FROM conctrib WHERE conctrib.codiconc = $concactu")." no tiene objetos para liquidar";
	        } else {
	          $mens = "El grupo ".sql("SELECT grupconc.nomb FROM grupconc WHERE grupconc.codigrup = $grupactu")." no tiene objetos para liquidar";
	        }
	        if ( $soliquery || $altaquery || $modiquery ) {
	          $mens .= " en esas fechas";
	        }
	        mens($mens);
        }
      }else{
      	$contobje += $contliqu;
      }


  } // Fin for que recorre el vector de conceptos
  
  if ($modoliqu != 'PER' && $echo){
	  if ( $contobje == 0) {
	    print "<td class=\"derform\" colspan=7><center>No hay objetos a liquidar</center></td>\n";
	    print "<td class=\"derform\" colspan=6></td>\n";
	    print "</table>\n";
	  } else {
	    // Mostrar el total de la cuota y la deuda a liquidar
	    print "<tr>\n<td class=\"derform\" colspan=13></td>\n</tr>\n";
	    print "<tr>\n";
	    print "<td class=\"derform\" colspan=5><b>TOTAL</b></td>\n";
	
	    // Calculo el total de las cuotas y de las deudas
	    $totacuot = $totadeud = 0;  // Inicializacion
	    for ( $j = 0; $j < $contobje; $j++ ) {
	      if ( $vect[$j][cuot] > 0 ){
	        $totacuot += $vect[$j][cuot];
	      }
	      if ( $vect[$j][deud] > 0 ){
	        $totadeud += $vect[$j][deud];
	      }
	    }
	
	    print "<td class=\"derform\">".impoboni($totacuot)."</td>\n";
	    print "<td class=\"derform\">".impoboni($totadeud)."</td>\n";
	    print "<td class=\"derform\" colspan = 6></td>\n";
	    print "</tr>\n";
	
	    // Cierro la tabla resumen del cargo que se desea generar
	    print "</table>\n</center>\n";
	  }
  }
  // Ejecucion sin errores
  return $vect;
}

?>

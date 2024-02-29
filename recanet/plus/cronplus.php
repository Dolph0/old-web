<?
  #---------------------------------------------------------------
  # Este proceso actualiza la titularidad y algunos datos del 
  # VARPAD en OIBIURBA derivados de las plusvalias.
  #---------------------------------------------------------------
  # Inicializacion
  $hoy = date('Y-m-d',time()); 

  # Marca la plusvalia a tratar
  $codioipl = ''; 

  # Guarda los personajillos implicados en la plusvalia
  $fulanito = array (1 => '',
                     10=> '');
 
  #------------------------------------------------------
  # Devuelve la letra correspondiente al DNI introducido
  #
  # Entradas:
  #   $numero => Los 8 números del DNI.
  #------------------------------------------------------
  function letradni ($numero) {
    $tabla = "TRWAGMYFPDXBNJZSQVHLCKET";
    $cantidad = (int)$numero / 23;
    $cantidad = (int)$cantidad * 23;
    $cantidad = $numero - (int)$cantidad;
    if ($cantidad < 0) $cantidad = -$cantidad;

    return $tabla[$cantidad];
  }
 
  #------------------------------------------------------
  # Devuelve la personalidad de un sujeto a partir del
  # NIF, es decir, F, J ó E.
  #------------------------------------------------------
  function devupers ($nifx) {
    if (ereg("^[0-9]{8}[A-Z]$",$nifx)) {
      # Comprobamos si la letra es correcta
      if (substr ($nifx, 8, 1) == letradni (substr ($nifx, 0, 8))) return 'F';
    } else {
      if (ereg ("^[A-H][0-9]{2}[0-9]{5}[A-Z0-9]$", $nifx)) return 'J';
      if (ereg ("^[PQS][0-9]{2}[0-9]{5}[A-Z0-9]$", $nifx)) return 'E';

      if (ereg ("^X[0-9]{7}[A-Z]$",$nifx)) {
        # Comprobamos si la letra es correcta
        if (substr ($nifx, 8, 1) == letradni (substr ($nifx, 1, 7))) return 'F'; // Extranjero
      }
       
      if (ereg ("^N[0-9]{3}[0-9]{4}[A-Z0-9]$", $nifx)) return 'J';
      # aunque esta última puede ser también Entidad
    } 
    
    # Si no pasó por ninguna -> error
    return '';
  }
 
  # Incluimos ficheros necesarios (SQL)
  # Ultimo cambio : 6-09-2001. Interfaz sql para Postgresql
  function sql ($peticion) {
    # Variable donde se devuelve el mensaje de error
    global $ERRORSQL;  

    # Datos de la conexión
    $base= "recanet";
    $usua= "web";

    # Se inicia ERRORSQL
    $ERRORSQL = "";

    if (!($idencone = pg_connect ("dbname=$base user=$usua")))
      return FALSE;
  
    $peticion = ltrim ($peticion);
    $peticion = rtrim ($peticion);

    # Se elimina el ultimo punto y coma si lo tuviera
    $peticion = ereg_replace (";$","",$peticion);

    # Si se trata de una única petición
    if (!strstr ($peticion,";")) {
      $sentencias[0] = $peticion;
    } else {
      $peticion = ereg_replace ("; *[\n]$","",$peticion); 

      # Si no se trata de una única petición, se divide en sentencias SQL
      $sentencias = explode (";",$peticion);
      if (!$sentencias[count($sentencias)-1]) {
        array_pop ($sentencias);
      }
    }

    # TRANSACCIÓN 
    @pg_exec ($idencone,"BEGIN;");

    $devolver = TRUE; 
 
    # Mientras haya sentencias que ejecutar...
    while ($query = each ($sentencias)) { 
      # Se le quita los espacios y saltos de linea al principio y al final
      $query[value] = trim ($query[value]);

      # Si se trata de un SELECT entonces a ver si detalla el erro
      $resu = @pg_exec ($idencone,"$query[value];") or pg_die(pg_errormessage(),$peticion,__FILE__,__LINE__); 

      if ($ERRORSQL = pg_ErrorMessage ()) { 
        # Ocurrio algo grave en el QUERY actual
        @pg_exec ($idencone, "ROLLBACK;"); # Fallo --> Rollback  
        pg_close ($idencone);              # Se termina la conexion

        #---- Tratamos los posibles errores de ERRORSQL

        # Fallo en una restriccion de campo
        if (ereg ("CHECK constraint", $ERRORSQL)) {
          $ERRORSQL= substr ($ERRORSQL,52);
          $ERRORSQL= "Formato incorrecto: ". $ERRORSQL;
        }
      
        # Fallo por una clave duplicada
        if (ereg ("Cannot insert a duplicate key", $ERRORSQL)) {
          $ERRORSQL = "Clave duplicada: ".substr($ERRORSQL,(strrpos($ERRORSQL," ") + 1));
        }

        # Fallo en la integridad referencial entre tablas
        if (ereg ("referential integrity violation", $ERRORSQL)) {
          $ERRORSQL = substr($ERRORSQL,8);
          $ERRORSQL = "Violación de la integridad referencial: ".substr($ERRORSQL,0,strpos($ERRORSQL," "));
        }
      
        return FALSE; 
      }

      if (ereg ("^[sS][eE][lL][eE][cC][tT]",strtoupper($query[value]))) {
        # Si la sentencia comienza por SELECT
        if (gettype($resu) == "boolean") { 
          $devolver = $resu;
        } else {
          $numeregi = pg_numrows ($resu);   # Registros encontrados
          $numecamp = pg_numfields ($resu); # Campos solicitados
          $devolver = "";
          if (($numecamp == 1) and ($numeregi == 1)) {
            $campo = pg_fetch_row($resu,0);
            $devolver = $campo[0];
          } else { 
            $i=0;
            while ($i < $numeregi) {
              $devolver[$i] = pg_fetch_array($resu,$i);
              $i++;
            }   
          }
        }   
      }
    }

    # Si se llego a este punto es que todo esta ok
    @pg_exec ($idencone,"COMMIT;"); # Ok -> Commit
    pg_FreeResult( $resu );
    pg_close ($idencone);

    return $devolver;
  }

  # -----------------------------------------------------------------
  # Function: pg_die($error, $query)
  # Params  : $error -- The displayable error text.  
  #                     Usually passed as pg_errormessage()
  #           $query -- The query which was attempted
  # -----------------------------------------------------------------
  function pg_die($error = "", $query = "", $err_file = __FILE__, $err_line = __LINE__) {

    global $strError,$strMySQLSaid, $strBack, $sql_query, $HTTP_REFERER, $SCRIPT_FILENAME, $link, $db, $table, $cfgDebug, $server;

    echo "<div class=solopantalla>$strError - $err_file -- Linea: $err_line<p>";

    if (empty($error)) {$error = @pg_errormessage();}

    echo , $strMySQLSaid . $error;

    if (empty($query)) {$query = $sql_query;}

    if (!empty($query)) {echo "<br>CONSULTA: <br>",  nl2br(htmlentities($query));}

    echo "</div>";
  }

  # Incluimos ficheros necesarios (INCIDENCIAS)
  #---------------------------------------------------------------------
  # Esta función inserta una incidencia en la tabla incihist
  # 
  # Entradas:
  #   $codiayun   -> Ayuntamiento 
  #   $codiusua   -> Usuario implicado en la incidencia 
  #   $codierro   -> Tipo de error
  #   $resu       -> Campos relacionados con la incidencia
  #
  # Salidas:
  #   True / false, si se ejecuto la rutina correctamente
  #---------------------------------------------------------------------
  function inseinci ($codiayun, $codiusua, $codierro, $resu) {
    # Inicializaciones
    $ejecuto = false;

    # Fecha  
    $fechinci = date("Y-m-d", time());

    # Buscamos la incidencia
    $codiinci = sql ("SELECT codiinci
                      FROM inci
                      WHERE codierro = '$codierro'");

    if ((isset($codiinci)) && ($codiinci > 0)) {
      # Valores del registro 
      $campos = "fechinci, codiinci, desa";
      $valo  = "'$fechinci', '$codiinci', '0'";
  
      if ($codiayun != '') {
        $campos .= ",codiayun";
        $valo .= ",'$codiayun'";
      }

      if ($codiusua != '') {
        $campos .= ",codiusua";
        $valo .= ",'$codiusua'";
      }
  
      if (is_array ($resu)) {
        foreach ($resu as $campo => $inse) {
          $campos.= ", $campo";
          $valo.= ", '$inse'";
        }
      }

      # Insertamos el registro 
      sql ("INSERT INTO incihist ($campos) VALUES ($valo)");
 
      # Resultado
      $ejecuto = true;
    }

    # Resultado
    return $ejecuto;
  }

  #------------------- PROCESO 
  
  # Query principal 
  $querycarg = "SELECT carg.codiayun as cargcodiayun,
                       carg.fechcarg as cargfechcarg,
                       carg.horacarg as carghoracarg,
                       carg.usuacarg as cargusuacarg,
                       carg.ejer     as cargejer,
                       carg.numedocu as cargnumedocu,
                       cargoipl.refecata as cargoiplrefecata,
                       cargoipl.numecarg as cargoiplnumecarg,
                       cargoipl.caracont as cargoiplcaracont,
                       cargoipl.nombnota as cargoiplnombnota, 
                       cargoipl.fechescr as cargoiplfechescr, 
                       cargoipl.prot as cargoiplprot, 
                       cargoipl.vari as cargoiplvari,
                       cargoipl.cuotadqu as cargoiplcuotadqu
                FROM carg, cargoipl
                WHERE (carg.tipoobje = 'OIPL') 
                  AND (carg.estacont <> 'ANU')
                  AND (carg.fechcarg = '$hoy')
                  AND (cargoipl.refecata <> '')
                  AND (cargoipl.numecarg <> '')
                  AND (cargoipl.caracont <> '')
                  AND (cargoipl.codiayun = carg.codiayun) 
                  AND (cargoipl.codiconc = carg.codiconc) 
                  AND (cargoipl.ejer     = carg.ejer) 
                  AND (cargoipl.numedocu = carg.numedocu)
                ORDER BY carg.fechcarg, carg.horacarg";
  // print 'DEPURANDO: '.$querycarg; 
  $resuquerycarg = sql ($querycarg); 

  # Proceso 
  if (is_array ($resuquerycarg)) { 
    while ($regicarg = each ($resuquerycarg)) { 
      $datocarg = $regicarg [value];

      # Si la cuota de adquisicion no es del 100% no se tiene en cuenta
      if ($datocarg[cargoiplcuotadqu] < 100) {
        continue;
      }

      # Datos para posible incidencia
      $incidencia = 'Numero de documento = '.$datocarg[cargnumedocu].
                    ', Ejercicio = '.$datocarg[cargejer].
                    ', Referencia = '.$datocarg[cargoiplrefecata].
                    ', Cargo = '.$datocarg[cargoiplnumecarg].
                    ', CC = '.$datocarg[cargoiplcaracont].
                    ', Notario = '.$datocarg[cargoiplnombnota].
                    ', Fecha escritura = '.$datocarg[cargoiplfechescr].
                    ', Protocolo = '.$datocarg[cargoiplprot].
                    ', Variacion = '.$datocarg[cargoiplvari];

      # Buscamos el inmueble
      $queryinmu = "SELECT inmu.codiinmu
                    FROM inmu
                    WHERE (inmu.codiayun = '$datocarg[cargcodiayun]')
                      AND (inmu.refecata = '$datocarg[cargoiplrefecata]')
                      AND (inmu.numecarg = '$datocarg[cargoiplnumecarg]')
                      AND (inmu.caracont = '$datocarg[cargoiplcaracont]')";
      $codiinmu = sql($queryinmu);

      if ($codiinmu > 0) {
        # Buscamos oipl
        $queryoipl = "SELECT oipl.codioipl
                      FROM oipl
                      WHERE (oipl.nombnota = '$datocarg[cargoiplnombnota]')
                        AND (oipl.fechescr = '$datocarg[cargoiplfechescr]')
                        AND (oipl.prot = '$datocarg[cargoiplprot]')
                        AND (oipl.vari = '$datocarg[cargoiplvari]')";
        $codioipl = sql($queryoipl);

        if ($codioipl > 0) {
          # Buscamos el Transmitente y Adquirente
          $query = "SELECT tercobje.coditerc as tercobjecoditerc,
                           tercobje.codifigu as tercobjecodifigu
                    FROM tercobje
                    WHERE (tercobje.tipoobje = 'OIPL')
                      AND (tercobje.codiobje = '$codioipl')
                      AND (tercobje.codifigu IN (1, 10))";
          $query = sql ($query);

          if (is_array($query)) {
            # Inicializacion
            $fulanito [1]  = '';
            $fulanito [10] = '';
 
            while ($regi = each ($query)) {
              $dato = $regi [value];

              # Cargo los dos fulanos (1:Transmitente, 10:Adquirente)
              $fulanito [$dato[tercobjecodifigu]] = $dato[tercobjecoditerc];
            }

            # Filtro (Si existen los dos individuos, procesamos)
            if ($fulanito[1] && $fulanito[10]) {
              # Buscamos titular del inmueble
              $titular = "SELECT tercobje.coditerc
                          FROM tercobje
                          WHERE tercobje.tipoobje = 'OIBIURBA'
                            AND tercobje.codifigu = 1
                            AND tercobje.codiobje = '$codiinmu'";
              $titular = sql ($titular);

              if ($titular == $fulanito[1]) {
                # Inicializacion
                $hora = date ('H:m:s', time()); 

                # Año de entrada en padron
                $fechpres = sql("SELECT fechpres FROM oipl WHERE codioipl='$codioipl'");
                $anioentrpadr = substr($fechpres, 0, 4);
                $anioentrpadr = $anioentrpadr + 1;

                # Recogemos las cuentas y beneficios
                $bene = sql ("SELECT benetrib.nomb || ' ('  || 
                                     benetribobje.fechinibene || ')' as beneficio
                              FROM benetribobje, benetrib
                              WHERE benetribobje.tipoobje = 'OIBIURBA'
                                AND benetribobje.codiobje = '$codiinmu'
                                AND benetrib.codibene = benetribobje.codibene"); 
            
                $cuenta = sql ("SELECT enti || '-' || sucu || '-' || 
                                       ccon || '-' || cuen || ' (' || 
                                       fechdomi || ')' as cuenta
                                FROM domibancobje
                                WHERE domibancobje.tipoobje = 'OIBIURBA'
                                  AND domibancobje.codiobje = '$codiinmu'"); 
                
                $usuanif = sql ("SELECT nifx
                                 FROM tercdato
                                 WHERE coditerc = '$fulanito[1]'");

                $salida = '';

                if ($bene || $cuenta) {
                  $salida = " Aplicacion PLUSVALIA, para el nif:".$usuanif;
                  
                  if ($bene) $salida .= " elimina Beneficio: ".$bene;
                  if ($bene && $cuenta) {
                    $salida .= " y ";
                  } else {
                    if ($cuenta) $salida .= " elimina ";
                  }
                  if ($cuenta) $salida .= " Cuenta: ".$cuenta;
                }

                # Observaciones que habian
                $borrado = sql ("SELECT obse
                                 FROM oibiurba
                                 WHERE codiinmu = '$codiinmu'");
                
                $borrado .= strtoupper($salida);

                # Comprobamos que el nif del Adquirente tenga un  
                # formato válido 
                $nifxadqui = sql ("SELECT nifx FROM tercdato WHERE coditerc = $fulanito[10]"); 
                if (devupers ($nifxadqui) == '') { 
                  $incidencia = 'ERROR EN EL NUEVO TITULAR DEL INMUEBLE: '.$incidencia; 
                  $inciarray = array(); 
                  $inciarray[refeinci] = $incidencia; 
                  inseinci ($datocarg[cargcodiayun], $datocarg[cargusuacarg], 
                            'NIFXERRO', $inciarray); 
                } 
 
                #-------------------------------- INICIO TRANSACCIÓN
                # Query que contendra los cambios a realizar                
                $querycambtitu = '';

                # Cambio titular en OIBIURBA
                $querycambtitu .= "UPDATE tercobje 
                                   SET coditerc = $fulanito[10] 
                                   WHERE tipoobje = 'OIBIURBA'
                                     AND codifigu = 1 
                                     AND codiobje = '$codiinmu'
                                     AND coditerc = '$fulanito[1]';";

                # Actualizo datos VARPAD
                $querycambtitu .= "UPDATE oibiurba 
                                   SET tipomovi = 'M',
                                       anioexpe = '$datocarg[cargejer]',
                                       refeexpe = 'PLV $datocarg[cargnumedocu]',
                                       origexpe = 'E',
                                       tipoexpe = 'CV01',
                                       m901 = '0',
                                       fechalte = '$datocarg[cargoiplfechescr]',
                                       anioentrpadr = '$anioentrpadr',
                                       refecataante = '',
                                       nifxante = '$usuanif',
                                       fechmodi = '$hoy',
                                       horamodi = '$hora',
                                       usuamodi = '$datocarg[cargusuacarg]',
                                       rutimodi = 'CRON',
                                       fechvari = '0001-01-01',
                                       fechcarg = '0001-01-01',
                                       obse = '$borrado'
                                   WHERE codiinmu = '$codiinmu';";

                # Elimino la domiciliacion y beneficio que tenia
                $querycambtitu .= "DELETE FROM domibancobje 
                                   WHERE tipoobje = 'OIBIURBA' 
                                     AND codiobje = '$codiinmu'; 
                                   DELETE FROM benetribobje 
                                   WHERE tipoobje = 'OIBIURBA' 
                                     AND codiobje = '$codiinmu'; ";
                                   
                sql ($querycambtitu);

                #-------------------------------- FIN DE LA TRANSACCIÓN
              } else {
                $incidencia = 'ERROR EN EL TITULAR DEL INMUEBLE: '.$incidencia;
                $inciarray = array();
                $inciarray[refeinci] = $incidencia;
                inseinci ($datocarg[cargcodiayun], $datocarg[cargusuacarg], 
                          'VARPLUTI', $inciarray);
              }
            } else {
              $incidencia = 'NO EXISTE FIGURA (Transmitente o adquirente): '.$incidencia;
              $inciarray = array();
              $inciarray[refeinci] = $incidencia;
              inseinci ($datocarg[cargcodiayun], $datocarg[cargusuacarg], 
                        'VARPLUTI', $inciarray);
            }
          }
        } else {
          $incidencia = 'NO EXISTE LA PLUSVALIA: '.$incidencia;
          $inciarray = array();
          $inciarray[refeinci] = $incidencia;
          inseinci ($datocarg[cargcodiayun], $datocarg[cargusuacarg], 
                    'VARPLURP', $inciarray);
        }
      } else {
        $incidencia = 'NO EXISTE EL INMUEBLE: '.$incidencia;
        $inciarray = array();
        $inciarray[refeinci] = $incidencia;
        inseinci ($datocarg[cargcodiayun], $datocarg[cargusuacarg], 
                  'VARPLURC', $inciarray);
      }
    }
  }            
?>

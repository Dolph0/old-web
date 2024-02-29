<?
// El año debe tener obligatoriamente 4 digitos 
// El dia y el mes pueden ser de 1 o 2 digitos 
// Los separadores admitidos son - / . 
 
/* Convierte la fecha del formato 'dd/mm/aaaa' al formato 'aaaammdd' */ 
function Guardarfecha ($fech) { 
   if ( strlen($fech) == 0 ) { 
      $aux = "0001-01-01"; 
      return ($aux); 
   } else { 
      // El separador lo tomamos del 5º caracter empezando por el final 
      // ya que las dimensiones del dia y mes son variables 
      $separa = substr($fech,-5,1); 
      $dia = substr($fech,0,strpos($fech,$separa)); 
      $mes = substr($fech,strpos($fech,$separa)+1,-5); 
      $anio = substr($fech,-4); // Los 4 ultimos caracteres 
      if (strlen($mes) == 1) $mes = "0$mes"; 
      if (strlen($dia) == 1) $dia = "0$dia"; 
      $aux = $anio."-".$mes."-".$dia; 
   } 
   return ($aux); 
} 
 
 
/* Convierte la fecha del formato 'aaaammdd' al formato 'dd/mm/aaaa' */ 
function Mostrarfecha ($fech) { 
   if ( ( strlen($fech) == 0 ) || ( $fech == "0001-01-01" ) ) { 
      $aux = ""; 
      return ($aux); 
   } else { 
      // El separador lo tomamos del 5º caracter empezando por el principio 
      $separa = substr($fech,4,1); 
      if ($separa != '.' and $separa != '/' and $separa != '-') 
        return "<fecha no válida>"; 
      $anio = substr($fech,0,4); // Los 4 primeros caracteres 
      $mes = substr($fech,5,(strrpos($fech,$separa) - (strpos($fech,$separa)+1))); 
      $dia = substr($fech,strrpos($fech,$separa)+1); 
      $aux = $dia."-".$mes."-".$anio; 
   } 
   return ($aux); 
} 
 
 
function cheqfech($fech){ 
// Comprueba si la fecha es correcta. La fecha viene en el formato dd-mm-aaaa 
// Recibe de entrada una fecha y devuelve true si no hay error, o false 
 
  // Comprobar la expresion regular 
  if ( !ereg("^([0-9]{1,2}(-|/|\.)){2}[0-9]{4}$",$fech) ) 
    return false; 
 
  // Los separadores entre el dia y mes, y entre el mes y el año 
  // deben ser iguales. Es decir, el separador aparece 2 veces 
  if ( substr_count($fech,substr($fech,-5,1)) != 2 )   
    return false; 
 
  $sepa = substr($fech,-5,1); 
  if ( $sepa == "." ) $sepa = "\."; 
  list($dia, $mes, $anio) = split($sepa,$fech); 
  $dia = (int)$dia; 
  $mes = (int)$mes; 
  $anio = (int)$anio; 
 
  // El dia debe ser un numero entre 1 y 31, y el mes entre 1 y 12 
  if ( ($dia < 1) || ($dia > 31) ) { 
    //return "No es un dia valido\n"; 
    return false; 
  } 
  if ( ($mes < 1) || ($mes > 12) ) { 
    //return "No es un mes valido\n"; 
    return false; 
  } 
 
  // Comprobar el dia del mes, por ejemplo, febrero no tiene 31 
   $diasmes = array("1" => 31, 
                    "2" => 29, 
                    "3" => 31, 
                    "4" => 30, 
                    "5" => 31, 
                    "6" => 30, 
                    "7" => 31, 
                    "8" => 31, 
                    "9" => 30, 
                    "10" => 31, 
                    "11" => 30, 
                    "12" => 31); 
 
   if ( $dia > $diasmes[$mes] ) { 
     //return "El mes no tiene tantos días\n"; 
     return false; 
   } 
   // Ademas, febrero solo tiene 29 dias, si el año es multiplo de 4 
   if ( ( $mes == 2 ) && ( $dia == 29 ) && 
        ( ($anio % 4) != 0) ) { 
     //return "Ese año, febrero no tiene 29 dias\n"; 
     return false; 
   } 
 
  // Si llega hasta aqui, no hay errores 
  return true; 
 
} 
 
 
 
function compfech( $fech1, $fech2 ) { 
  // Esta funcion compara dos fechas en formato dd-mm-aaaa 
  // Devuelve 0 si son iguales, 1 si $fech1 > $fech2, y 2 si $fech2 > $fech1 
  // Si las fechas no son correctas, es un error y devuelve -1 
  // $camp1 son los campos de $fech1 que estamos comparando 
  // $camp2 son los campos de $fech2 que estamos comparando 
 
  // Compruebo si las fechas son correctas 
  if ( ( !cheqfech( $fech1 ) ) || ( !cheqfech( $fech2 ) ) ) { return -1; } 
 
  // Obtengo cada campo (dia, mes y año) de ambas fechas 
  $camp1 = split( '[./-]', $fech1 ); 
  $camp2 = split( '[./-]', $fech2 ); 
 
  // Comienzo la comparacion por el año, luego el mes, y por ultimo el dia 
  // Comparo los años 
  if ( (int)$camp1[2] > (int)$camp2[2] ) { return 1; } 
  if ( (int)$camp1[2] < (int)$camp2[2] ) { return 2; } 
 
  // Los años son iguales, comparo el mes 
  if ( (int)$camp1[1] > (int)$camp2[1] ) { return 1; } 
  if ( (int)$camp1[1] < (int)$camp2[1] ) { return 2; } 
 
  // Por ultimo comparo los dias, ya que meses y años son iguales 
  if ( (int)$camp1[0] > (int)$camp2[0] ) { return 1; } 
  if ( (int)$camp1[0] < (int)$camp2[0] ) { return 2; } 
 
  // Si llega aqui, las fechas son iguales 
  return 0; 
} 
 
 
 
function nombmes ($numemes) { 
  switch ($numemes) { 
    case  1: return 'enero'; break; 
    case  2: return 'febrero'; break; 
    case  3: return 'marzo'; break; 
    case  4: return 'abril'; break; 
    case  5: return 'mayo'; break; 
    case  6: return 'junio'; break; 
    case  7: return 'julio'; break; 
    case  8: return 'agosto'; break; 
    case  9: return 'septiembre'; break; 
    case 10: return 'octubre'; break; 
    case 11: return 'noviembre'; break; 
    case 12: return 'diciembre'; break; 
    default: return '[mes desconocido]'; 
  } 
} 
 
 
function NumeDias( $fechinic, $fechfina ) { 
  # Pequeña conversión para entender ambos formatos de fechas 
  $fechinic = preg_replace ("/-/", "", $fechinic); 
  $fechfina = preg_replace ("/-/", "", $fechfina); 
 
  // print "Recibo como parámetro $fechinic y $fechfina<br>\n"; 
 
  # 
  # devuelve como (int) el número de días entre dos fechas 
  #   e.g.  19680301  19680313 -> 12 
  # 
  # Parámetros de entrada 
  # 
  #   fechinic fecha del día inicial del periodo 
  #            formato aaammdd 
  #   
  #   fechfina fecha del día final del periodo 
  #            formato aaammdd 
  # 
  # descompone las fechas de inicio y final 
  $anioinic = (int)substr($fechinic,0,4); 
  $mesinic  = (int)substr($fechinic,4,2); 
  $diainic  = (int)substr($fechinic,6,2); 
  # 
  $aniofina = (int)substr($fechfina,0,4); 
  $mesfina  = (int)substr($fechfina,4,2); 
  $diafina  = (int)substr($fechfina,6,2); 
  # 
  # comprueba si la fechas tienen el formato correcto 
  # incluidos bisiestos 
  if ( !checkdate( $mesinic, $diainic, $anioinic )  
    || !checkdate( $mesfina, $diafina, $aniofina )  
    || $fechinic > $fechfina ) $numedias = -1; 
  else { 
 
    $dife = mktime (0, 0, 0, $mesfina, $diafina, $aniofina) - 
            mktime (0, 0, 0, $mesinic, $diainic, $anioinic); 
    return $dife / 60 / 60 / 24; 
 
    # contador de días 
    /* $numedias = 0; 
     
    $fech = $fechinic;  
    while ( $fech < $fechfina ) { 
      $numedias++; 
      $anio = (int)substr($fech,0,4); 
      $mes  = (int)substr($fech,4,2); 
      $dia  = (int)substr($fech,6,2); 
      $timestam = mktime(0,0,0, $mes, $dia + 1, $anio); 
      # si se sale del rango: 19011200 < fech < 20371231 
      if ( $timestam == -1 ) $fech = "-1"; 
      else $fech  = date( "Ymd", $timestam ); 
    } */ 
  } 
  return ($numedias); 
} 
 
 
function SumaDias( $fech, $numedias, $format = 'Ymd' ) { 
  # 
  # Dada una fecha y un número de días hacia delante o hacia atrás, 
  # calcula la fecha resultante 
  # Si la fecha de partida es errónea  
  # o la de salida sale del rango, devuelve -1 
  #   formato aaaammdd 
  # 
  # Parámetros de entrada 
  # 
  #   fech fecha sobre la que hacer la suma 
  #        formato aaammdd 
  #    
  #   numedias número de días a sumar 
  #        formato entero positivo o negativo 
 
  # Conversión para entender ambos formatos de fechas 
  $fech = preg_replace ("/-/", "", $fech); 
 
  # descompone la fecha 
  $anio = (int)substr($fech,0,4); 
  $mes  = (int)substr($fech,4,2); 
  $dia  = (int)substr($fech,6,2); 
  
  # comprueba si la fecha de entrada tiene el formato correcto 
  # incluidos bisiestos 
  if ( !checkdate( $mes, $dia, $anio ) ) $fechfina = -1; 
  else { 
    $timestamp = mktime(0,0,0, $mes, $dia+$numedias, $anio); 
  
    # si se sale del rango:  19011200 < fechfina < 20371231 
    if ( $timestamp == -1 ) $fechfina=""; 
    else $fechfina  = date( $format, $timestamp ); 
  } 
  return ($fechfina); 
} 
 
function SumaDiasHabi( $fech, $numedias, $codiayun ) {  
  # 
  # Dada una fecha y un número de días hábiles hacia delante o hacia atrás, 
  # calcula la fecha resultante. 
  # Si la fecha de partida es errónea  
  # o la de salida sale del rango, devuelve -1 
  #   formato aaaammdd 
  # 
  # Parámetros de entrada 
  # 
  #   fech fecha sobre la que hacer la suma 
  #        formato aaaa-mm-dd   aaaa/mm/dd   aaaa.mm.dd 
  #    
  #   numedias número de días a sumar 
  #        formato entero positivo o negativo 
  
  // Compruebo que el ayuntamiento dado, esté en la tabla de ayuntamientos 
  // y en la tabla de dias festivos 
  if ( !sql( "SELECT codiayun FROM ayun WHERE codiayun = '$codiayun'" ) ||  
       !sql( "SELECT codiayun FROM diasfest WHERE codiayun = '$codiayun'" ) ) { 
    return -1; 
  } 
 
  # comprueba si la fecha de entrada tiene el formato correcto 
  # incluidos bisiestos 
  if ( !cheqfech( Mostrarfecha( $fech ) ) ) { 
    $fechfina = -1;  
  } else { 
    // Inicializo la fecha final que devuelve 
    $fechfina = $fech; 
 
    // Determino el incremento de dias, segun se sumen o resten dias.  
    if ( $numedias < 0 ) { 
      $incr = -1; 
    } else { 
      $incr = 1; 
    } 
 
    // Contador de dias habiles en que incrementamos/decrementamos la fecha 
    $conthabi = 0; 
 
    while ( $conthabi < abs( $numedias ) ) { 
      # descompone la fecha 
      list( $anio, $mes, $dia )= split( '[./-]', $fechfina ); 
  
      $timestamp = mktime(0,0,0, $mes, $dia+$incr, $anio); 
    
      # si se sale del rango:  19011200 < fechfina < 20371231 
      if ( $timestamp == -1 ) { 
        $fechfina = -1; 
        break; 
      } else { 
        // Obtengo la nueva fecha, en el formato aaaa-mm-dd 
        $fechfina  = date( "Y-m-d", $timestamp ); 
        if ( esHabil( $fechfina, $codiayun ) ) { 
          // Solo si es un dia habil, se incrementa el contador de dias 
          $conthabi ++; 
        } 
      } 
    } 
  } 
  return ($fechfina); 
} 
 
 
// Comprueba si una fecha determinada es un día hábil o no, comparando en la 
// tabla de días festivos y mirando si es sábado o domingo. 
// La fecha tiene que pasarse en formato aaaa-mm-dd. 
// El tipo indica el tipo de día hábil: si es 'inicio', los sábados se 
// consideran como hábiles 
function esHabil ($fecha, $ayun, $tipo = 'fin') { 
  $presente = sql ("SELECT codiayun FROM diasfest 
                    WHERE  codiayun = '$ayun' AND fech = '$fecha'"); 
  if ($presente) 
    return false; 
  $dia_semana = date ("D", mktime (0, 0, 0, substr ($fecha, 5, 2), 
                                            substr ($fecha, 8, 2), 
                                            substr ($fecha, 0, 4))); 
  if ($dia_semana == 'Sun' or 
      ($dia_semana == 'Sat' and $tipo == 'fin')) 
    return false; 
 
  return true; 
} 
 
 
 
// Devuelve la fecha si es hábil, o la siguiente hábil si no lo es 
// La fecha va en formato aaaa-mm-dd 
// Si el tipo de día hábil vale 'inicio', los sábados se consideran hábiles. 
function diaHabil ($fecha, $ayun, $tipo = 'fin') { 
  $aux = $fecha; 
  while (! esHabil ($aux, $ayun, $tipo)) { 
    $aux = preg_replace ("/-/", "", $aux); 
    $aux = SumaDias ($aux, 1); 
    $aux = preg_replace ("/(....)(..)(..)/", '$1-$2-$3', $aux); 
  } 
  return $aux; 
} 
 
 
 
// Devuelve el número de meses de diferencia entre dos fechas. Las fechas deben 
// estar en el formato PostgreSQL, es decir, del tipo date ("Y-m-d", time()) 
function numeMeses ($fechinic, $fechfina = ""){ 
 
// Si no se especifica la fecha final, se entiende que es a dia actual (modificado por gus) 
  if ($fechfina==''){ $fechfina = date ("Y-m-d", time ());} 
   
  $anioinic = preg_replace ("/-.*/", "", $fechinic); 
  $aniofina = preg_replace ("/-.*/", "", $fechfina); 
 
  $mesinic  = $anioinic * 12 + preg_replace ("/.*-(.*)-.*/", '$1', $fechinic); 
  $mesfina  = $aniofina * 12 + preg_replace ("/.*-(.*)-.*/", '$1', $fechfina); 
 
  $diainic  = preg_replace ("/.*-/", "", $fechinic); 
  $diafina  = preg_replace ("/.*-/", "", $fechfina); 
 
  return $mesfina - $mesinic - ($diafina < $diainic); 
} 
 
//-------------------------------------------------------- 
// Devuelve el dia de la semana de una fecha determinada. 
// 
// Entradas: 
//   Creo que sobran las explicaciones. 
// 
// Salida: [0-lunes; 1-martes; 2-miércoles; 3-jueves; 
//          4-viernes; 5-sábado; 6-domingo] 
//-------------------------------------------------------- 
function dia_semana ($dia, $mes, $anio) {  
  $numerodiasemana = date('w', mktime(0, 0, 0, $mes, $dia, $anio));  
  if ($numerodiasemana == 0)  
    $numerodiasemana = 6;  
  else  
    $numerodiasemana--;  
 
  return $numerodiasemana;  
}  
 
//-------------------------------------------------------- 
// Devuelve el número de dias de un mes y año determinado. 
// 
// Entradas: 
//   No commnet. 
// 
// Salidas: 
//   Número de dias del mes. 
//-------------------------------------------------------- 
function ultimo_dia ($mes, $anio) {  
  $ultimo_dia = 28;  
  while (checkdate ($mes, $ultimo_dia + 1, $anio)) {  
    $ultimo_dia++;  
  }  
  
  return $ultimo_dia;  
}  
 
//----------------------------------------------------------- 
// Esta función calcula el numero de veces que aparece un 
// dia de la semana (lunes, martes,...,domingo) en el mes 
// indicado. 
// 
// Entradas: 
//   $diasemana => Dia de la semana, valores [L,M,X,J,V,S,D] 
//   $mes       => Mes. 
//   $anio      => Año. 
// 
// Salida: 
//   Número de veces que aparece el dia de la semana en el  
// mes indicado. 
//----------------------------------------------------------- 
function dia_semana_mes ($diasemana, $mes, $anio) { 
  // Inicializaciones 
  $veces = 0; 
   
  // Verificaciones 
  if (!cheqfech ('1-'.$mes.'-'.$anio) || 
      !in_array ($diasemana, array ('L','M','X','J','V','S','D'))) { 
    mens ('El dia de la semana es incorrecto'); 
    $veces = -1; 
  } 
 
  // Proceso 
  if ($veces == 0) { 
    $diasmes = ultimo_dia ($mes, $anio); 
    $diasemanaint = strpos ('LMXJVSD', $diasemana); 
    for ($dia = 1; $dia <= $diasmes; $dia++){
      if (dia_semana($dia, $mes, $anio) == $diasemanaint) $veces++;
    }
  } 
   
  return $veces; 
} 
 
//-----------------------------------------------------------------
// Dada una fecha y un número de meses hacia delante o hacia atrás, 
// calcula la fecha resultante. 
// 
// Entrada:
//   fech      => fecha sobre la que hay que operar (aaaa-mm-dd)
//   numemeses => número de meses a sumar (entero positivo o negativo)
//
// Salidas:
//   Fecha resultante en formato (aaaammdd) ó -1 (error).
//-----------------------------------------------------------------
function SumaMes ($fech, $numemeses, $format = 'Ymd' ) { 
  # Conversión para entender ambos formatos de fechas 
  $fech = preg_replace ("/-/", "", $fech); 
 
  # descompone la fecha 
  $anio = (int)substr($fech,0,4); 
  $mes  = (int)substr($fech,4,2); 
  $dia  = (int)substr($fech,6,2); 
  
  # comprueba si la fecha de entrada tiene el formato correcto 
  # incluidos bisiestos 
  if (!checkdate ($mes, $dia, $anio)) $fechfina = -1; 
  else { 
    $timestamp = mktime(0,0,0, $mes+$numemeses, $dia, $anio); 
  
    # si se sale del rango:  19011200 < fechfina < 20371231 
    if ($timestamp == -1) $fechfina=""; 
    else $fechfina  = date ($format, $timestamp); 
  } 
  
  return ($fechfina); 
} 


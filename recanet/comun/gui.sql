<?
// Este fichero incluye las funciones que construyen el query a partir de unos 
// criterios de selección (seleccionados con las funciones existentes en gui.fnc),
// así como las funciones que muestran por pantalla los criterios de selección.
// FUNCIONES EN ESTE FICHERO:
// +---------------+-------+-------+--------------------
// |  FUNCIONES    | QUERY | INFO  | FUNCIONES EXTERNAS 
// +---------------+-------+-------+--------------------
// |· dispelegquery|   X   |   ·   |                    
// |· dispeleginfo |   ·   |   X   |                    
// |· radioinfo    |   ·   |   X   |                    
// |· busctextinfo |   ·   |   X   |                    
// |· busctextquery|   X   |   ·   |                    
// |· muesranginfo |   ·   |   X   | impoboni (euro2cent())        
// |· muesrangquery|   X   |   ·   | guardarfecha () euro2cent ()
// |· mesquery     |   X   |   ·   | 
// |· sqlinfo      |   ·   |   X   | sql ()             
// +---------------+-------+-------+--------------------



//-------------------------------------------------------------------------
// FUNCION: dispelegquery ($pref, $campos, $array=0)
//  Para conocer los campos por los que tenemos que agrupar u ordenar
// que es normalmente para lo que se emplea la funcion dispeleg ()
// $pref = prefijo que se puso en dispeleg ()
// $camp = un array al estilo $lista, pero esta vez contiene los campos 
//         que vamos a consultar
// $array = indica si lo que hay que retornar es un array en tal caso su
//          su estructura seria: 
//   ·camp1 = primer campo seleccionado
//   ·camp2 = segundo campo seleccionado
//   ·campn = n campo
//   ·query = los dos juntos con la coma 
// Nos devolvera separado por comas los campos que se han pedido que ordene
// o agrupe. 
//-------------------------------------------------------------------------

function dispelegquery ($pref, $campos, $array=0) {
    global ${$pref."sele"};
    $sele   = ${$pref."sele"};
    $retornar= array ();
    $contador =0;
    
    // Obtenemos los campos por los que se agrupa. 
    foreach ($sele as $v) {
     $retornar['camp'.$contador]=$campos[$v];
     $contador ++;
    }   
     
    // generamos la consulta con la cantidad de veces 
    // que se realizo el anterior ciclo
    for ($x=0;$x<$contador;$x++) { 
      if ($retornar[query]) $retornar[query].=',';
      $retornar[query].= $retornar['camp'.$x];
    }

  if ($array)  return $retornar; 
  else return $retornar[query];
}

//-------------------------------------------------------------------------
// FUNCION: dispeleginfo ($pref, $lista, $texto='')
// $pref = prefijo que se puso en dispeleg ()
// $lista = el vector que se paso a la funcion dispeleg () 
// Nos devolvera separado por comas los titulos que se han pedido que ordene
// o agrupe. 
//-------------------------------------------------------------------------
function dispeleginfo ($pref, $lista, $texto='') {
  global ${$pref."sele"};
  $sele   = ${$pref."sele"};
  $retornar="";
  
  foreach ($sele as $v) {
    if ($retornar) $retornar.=', ';
    $retornar.=$lista[$v];
  }
 
  return "<div> $texto".$retornar."</div>"; 
}


//-------------------------------------------------------------------------
// FUNCION:radioinfo ($nombre,$opciones) 
//
// Muestra que opcion se tuvo encuenta en el radio. 
// $nombre   = es el nombre que se le asigna en el formulario.
// $opciones = es un array, contiene VALOR => TEXTO 
//  · $valor = es el valor que optiene cuando se marca la opcion. 
//  · $texto = se muestra para indicar que es cada radio.
//-------------------------------------------------------------------------
function radioinfo ($nombre,$opciones) {
 global ${$nombre};
  return $opciones[${$nombre}];
}



//------------------------------------------------------
// FUNCION busctextinfo ($pref)
// Despues de hacer una busqueda normalmente se tiene que 
// mostrar al usuario como realizo la busqueda, para 
// esto se ha creado una pagina que da esta información 
// Si es una cajatexto, mostrar la información es simple 
// Pero un busctext tiene varias opciones. 
// Esta funcion se encarga de devolver con que criterio
// se realizo la busqueda en el busctext correspondiente. 
//------------------------------------------------------
function busctextinfo ($pref,$tipo='') {
  global 
    ${$pref.'select'},
    ${$pref.'textbox'};
  $caso   = ${$pref.'select'};
  $valor1 = ${$pref.'textbox'};

  switch ($caso) {
    case 1: $retornar.= "Igual a         $valor1"; break;
    case 2: $retornar.= "Empieza por     $valor1"; break;
    case 3: $retornar.= "Contiene        $valor1"; break;
  }

  return $retornar;
}



//------------------------------------------------------
// FUNCION busctextquery ($pref,$camp)
// Mismo motivo que busctextinfo, realizar un query de 
// una cajatexto es simple pero aqui se juegan varias 
// posibilidades, esta funcion de encarga de decidir 
// como generar la consulta para formar la condición 
// segun lo que el usuario pidio en el busctext 
// correspondiente.
//------------------------------------------------------
#genera la sentencia sql correspondiente.
function busctextquery ($pref,$camp) {
  global
    ${$pref.'select'},
    ${$pref.'textbox'};
    
  $caso   = ${$pref.'select'};
  $valor1 = ${$pref.'textbox'};

  if ($valor1 !='') { 
    $query = "(";
    switch ($caso) {
      case 1: $query .= "$camp  = '$valor1'"; break;
      case 2: $query .= "$camp like '$valor1%'"; break;
      case 3: $query .= "$camp like '%$valor1%'"; break;
    }
    $query .= ")";
  }
  return $query;
}

 

//------------------------------------------------------
// FUNCION sqlinfo ($consulta)
// Funcion simplemente lanza una consulta y retorna 
// el resultado (que solo puede ser de un unico resultado) 
// todo en minuscula menos el primer caracter 
// especialmente creado para la pantalla de informacion
// de los criterios de busqueda utilizados. 
//------------------------------------------------------
function sqlinfo ($consulta,$todos=0) {
  if ($todos) return ucwords (strtolower(sql ($consulta)));
  else return ucfirst(strtolower(sql ($consulta)));
}



//------------------------------------------------------
// FUNCION muesrangquery ($pref,$camp,$tipo='')
// Se encarga de construir las condiciones del query, 
// correspondientes a lo seleccionado en el muesrang,
// una vez recibido el formulario.
// pref es el prefijo utilizado para crear el muesrang
// camp es el campo en la base de datos al que se le aplican las condiciones del muesrang
// tipo es la clase de campo con que tratamos
//------------------------------------------------------
function muesrangquery ($pref,$camp,$tipo='') {
  global
    ${$pref.'select'},
    ${$pref.'textbox1'},
    ${$pref.'textbox2'};
    
    
  $caso   = ${$pref.'select'};
  $valor1 = ${$pref.'textbox1'};
  $valor2 = ${$pref.'textbox2'};

// Traza para conocer los valores 
//echo "$pref / $camp / $tipo / $caso / $valor1 / $valor2";
  //Si se trata de fechas, se convierte al formato de la base de datos.
  if ($tipo=='fech') {
    $valor1 = guardarfecha ($valor1);
    $valor2 = guardarfecha ($valor2);
  }
  
  // Si son euros, se redondean y se pasan a centimos.
  if ($tipo=='euro') {
    $valor1 = euro2cent ($valor1);
    $valor2 = euro2cent ($valor2);
  }
  
  // Si se trata de una cantidad de elementos, por ejemplo, los elementos tributarios del IAE,
  // en la base de datos se guardan con dos decimales, de manera similar a los euros.
  if ( $tipo == 'elemcant' ) {
    $valor1 = $valor1*100;
    $valor2 = $valor2*100;
  }
  
 // Inicializacion del trozo de query a devolver.
 $query = "";
 if ( $valor1 != '' ) { 
   switch ( $caso ) {
     case 1: 
     case 9: $query .= "$camp  = '$valor1'"; break;
     case 2: $query .= "$camp <  '$valor1'"; break;
     case 3: $query .= "$camp <= '$valor1'"; break;
     case 4: $query .= "$camp >  '$valor1'"; break;
     case 5: $query .= "$camp >= '$valor1'"; break;
     case 6: $query .= "$camp >= '$valor1'   AND
                        $camp <= '$valor2'"; break;
     case 7: case 8: $query .= mesquery ($pref.'listmes1',$pref.'listmes2',$caso,$camp);
   }
 }
 if ( $query )  return "($query)";
}


//------------------------------------------------------
// FUNCION mesquery ($name)
// Por la idea de que el usuario decida poner la fecha 
// por meses ... esta funcion se encarga de mostrar
// El mes seleccionado 
// Aunque se puede utilizar por separado, acompaña a 
// a la funcion "muesrangquery"
//------------------------------------------------------
function mesquery ($name1,$name2,$estado,$campo) {
  global $$name1;
  global $$name2;
  global ${$name1.'anio'};
  global ${$name2.'anio'};
  
  // El segundo mes y año depende de la eleccion del usuario 
  if ( $estado == 7 ) {$mes = $$name1; $anio = ${$name1.'anio'};}
  if ( $estado == 8 ) {$mes = $$name2; $anio = ${$name2.'anio'};}
  
  //Se averigua si el año es bisiesto
  if ( $anio%4 ) $feb = 28;
  else $feb =29;

  // Sabemos que siempre empezara a contar desde el dia uno 
  // y el ultimo dia del mes ya hemos mirando antes si pertenece 
  // al mes marcado en la primera caja o es el mes marcado en la segunda 
  $query = "$campo >= '".${$name1.'anio'}."-".$$name1."-01' AND $campo <= '$anio-$mes-";

  // Calculamos cual es el ultimo dia del mes. 
  switch ($mes) {
   // febrero que es "especial"
   case  '02':  $query.= $feb; break; //Febrero
   // meses con 31 dias 
   case  '01':                        //Enero
   case  '03':                        //Marzo
   case  '05':                        //Mayo
   case  '07':                        //Julio
   case  '08':                        //Agosto
   case  '10':                        //Octubre
   case  '12':  $query.= "31"; break; //Diciembre
   // meses con 30 dias
   case  '04':                        //Abril
   case  '06':                        //Junio
   case  '09':                        //Septiembre
   case  '11':  $query.= "30"; break; //Noviembre
  }

 $query .= "'";
 return $query; 
}


//-------------------------------------------------------------------------
// Funcion encargada de mostrar los criterios con que se realizo la busqueda,
// a partir de un muesrang.
// Se le pasa por parametros el prefijo con que se construyó el muesrang, 
// y el tipo de datos que se selecciona en el muesrang (euro, fech, nume, ...)
//-------------------------------------------------------------------------
function muesranginfo ($pref,$tipo='') {
  global 
    ${$pref.'select'},
    ${$pref.'textbox1'},
    ${$pref.'textbox2'};
  $caso   = ${$pref.'select'};
  $valor1 = ${$pref.'textbox1'};
  $valor2 = ${$pref.'textbox2'};
  
  if ($tipo=='euro') {
    $valor1 = impoboni (euro2cent($valor1));
    $valor2 = impoboni (euro2cent($valor2));
  }
  
  if ($tipo=='supe') {
    $valor1 = $valor1 . " m&sup2;";
    $valor2 = $valor2 . " m&sup2;";
  }
  
  switch ($caso) {
    case 1:
      $retornar.= "Igual a $valor1";
       break;
    case 2:
      if ($tipo=='euro' || $tipo=='nume' || $tipo=='supe' || $tipo=='elemcant' || $tipo=='deci' ) 
        $retornar.= "Menor que $valor1"; 
      else $retornar.= "Anterior a  $valor1";
      break;
    case 3:
      if ($tipo=='euro' || $tipo=='nume' || $tipo=='supe' || $tipo=='elemcant' || $tipo=='deci' ) 
        $retornar.= "Menor o igual a $valor1";
      else $retornar.= "Anterior o igual a  $valor1";
      break;
    case 4:
      if ($tipo=='euro' || $tipo=='nume' || $tipo=='supe' || $tipo=='elemcant' || $tipo=='deci' ) 
        $retornar.= "Mayor que $valor1";
      else $retornar.= "Posterior a $valor1";
      break;
    case 5:
      if ($tipo=='euro' || $tipo=='nume' || $tipo=='supe' || $tipo=='elemcant' || $tipo=='deci' ) 
        $retornar.= "Mayor o igual a $valor1";
      else $retornar.= "Posterior o igual a $valor1";
      break;
    case 6:
      $retornar.= "Entre $valor1 y $valor2"; break;
    case 7: 
      $retornar.= "En ".mesinfo($pref."listmes1"); break;
    case 8:
      $retornar.= "Entre ".mesinfo($pref."listmes1")." y ".mesinfo($pref."listmes2"); break;
    case 9: 
      $retornar.= "No registrada          "; break;
  }
  return $retornar;
}



//-------------------------------------------------------------------------
// FUNCION mesinfo ($name)
// Cuando el usuario selecciona un rango de fechas especificando 
// meses, esta funcion se encarga de mostrar el mes seleccionado.
// Aunque se puede utilizar por separado, acompaña a 
// a la funcion "muesranginfo"
//-------------------------------------------------------------------------
function mesinfo ($name) {
  global $$name, ${$name.'anio'};
  return mesnomb ($$name)." del ".${$name.'anio'};
}
?>

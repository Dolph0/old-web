<?php
//------------------------------------------------------------------------
// INFORMACION ADICIONAL:
// ULTIMA MODIFICACION: 16-04-2003: Las funciones que no realicen funciones
// propias de GUI, sino que tratan con querys, por ejemplo, muesranquery o
// muesranginfo, están en otro fichero gui.sql.
// Este fichero gui.fnc incluye al fichero gui.sql
//
// - Se retocan las funciones: dispeleg,dispeleginfo
//   · dispeleg: Se le pone nombre e identidad a la tabla para poder mostrar
//               u ocultarla segun necesidad. 
//               Además con esta posibilidad se ve la necesidad de agregar 
//               un nuevo parametro de texto que imprime antes de mostrar 
//               las opciones. 
//               Tambien se asocia  imagenes a los botones que tenia. 
//   · dispeleginfo: Nevo parametro de texto que imprime antes de mostrar
//                   los criterios que se solicitaron.
//
// Elementos frecuentemente utilizados para las pantallas de busqueda. 
// pero que tambien nos puede servir en otros momentos. 
//
// Algunas funciones declaran globales para recoger el contenido de
// las variables que generan de esta manera nos despreocupamos que valores
// tengo o no tengo que pasar a una funcion. 
//
// LISTADO DE FUNCIONES: 
// Se clasifican escencialmente en 3 tipos:
// - HTML: retornan simplemente el codigo HTML para mostrar por pantalla
// - QUERY: retornar la condicion correspondiente para realizar la consulta
// - INFO: de utilidad para los listados y mostrar al usuario que criterios 
//         utilizo cuando realizo la busqueda. 
// - FUNCIONES EXTERNAS: necesitan de una funcion que no se encuentran en 
//                       este fichero. Cuidado que si no se tiene encuenta 
//                       puede producir errores. Pero se ha intentado que
//                       las funciones externas sean de las mas utilizadas 
//
// +---------------+------+-------+-------+------------------------------+-------------
// |  FUNCIONES    | HTML | QUERY | INFO  | FUNCIONES EXTERNAS           | FICHERO 
// +---------------+------+-------+-------+------------------------------+-------------
// |· iniccaja     |  X   |   ·   |   ·   |                              |  gui.fnc
// |· finacaja     |  X   |   ·   |   ·   |                              |  gui.fnc
// |· iniccaja2    |  X   |   ·   |   ·   |                              |  gui.fnc
// |· finacaja2    |  X   |   ·   |   ·   |                              |  gui.fnc
// |· dispeleg     |  X   |   ·   |   ·   |                              |  gui.fnc
// |· dispelegquery|  ·   |   X   |   ·   |                              |  gui.sql
// |· dispeleginfo |  ·   |   ·   |   X   |                              |  gui.sql
// |· checkbox     |  X   |   ·   |   ·   |                              |  gui.fnc
// |· radio        |  X   |   ·   |   ·   |                              |  gui.fnc
// |· radioinfo    |  ·   |   ·   |   X   |                              |  gui.sql
// |· cajatexto    |  X   |   ·   |   ·   |                              |  gui.fnc
// |· busctext     |  X   |   ·   |   ·   |                              |  gui.fnc
// |· busctextinfo |  ·   |   ·   |   X   |                              |  gui.sql
// |· busctextquery|  ·   |   X   |   ·   |                              |  gui.sql
// |· muesrang     |  X   |   ·   |   ·   |                              |  gui.fnc
// |· muesrangquery|  ·   |   X   |   ·   | guardarfecha () euro2cent () |  gui.sql
// |· muesranginfo |  ·   |   ·   |   X   | impoboni (euro2cent())       |  gui.sql
// |· mesnomb      |  ·   |   X   |   ·   |                              |  gui.gui
// |· mesquery     |  ·   |   X   |   ·   |                              |  gui.sql
// |· sqlinfo      |  ·   |   ·   |   X   | sql ()                       |  gui.fnc
// |· oculdato     |  X   |   ·   |   ·   |                              |  gui.fnc
// |· limpdato     |  X   |   ·   |   ·   |                              |  gui.fnc
// +---------------+------+-------+-------+------------------------------+-------------
// |· estacaja     |  ·   |   ·   |   ·   |
// +---------------+------+-------+-------+--------------------
//
//------------------------------------------------------------------------
// OBLIGATORIO
// Funciones en JavaScript necesarias para que funcionene correctamente.
//------------------------------------------------------------------------
echo" 
<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/gui.js") . "'></SCRIPT>
<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/tipos.js") . "'></SCRIPT>
<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/fecha.js") . "'></SCRIPT>
<SCRIPT language='JavaScript' SRC='" . cheqroot("comun/calendar.js") . "'></SCRIPT>
";


// Incluyo el fichero gui.sql, en el que se encuentran las funciones para construir el
// query a partir de los criterios seleccionados, así como las que muestran por pantalla
// los criterios de selección.
include "gui.sql";

//-------------------------------------------------------------------------
// FUNCION: iniccaja ($id,$titu,$display,$oculta='block')
//          finacaja()
//
// Estas dos funciones tiene que ir juntas, lo que hacen es crear una tabla 
// con dos celdas en la celda de la izquierda aparecera un titulo del contenido 
// de la derecha, donde pondremos todo el contenido necesario.
// $id = Tiene que ser un identificador unico, para que se pueda mostrar y ocultar 
//       el contenido de la caja. 
// $titu= Titulo que se mostrara 
// $display= Si el contenido se muestra o se oculta inicialmente por omision se muestra
// $oculta = Si la caja al completo se muestra o permanece oculta (esto por si hay alguna 
//           funcion javascript que hace que aparezca por seleccionar algo) 
// Tanto para $display como $oculta sus valores son 'block' o 'none'
// EJEMPLO: 
//                  +-----------+-------------------------------+
//                  |           |                               |
//                  |   TITULO* |  lo que se quiera poner.      |
//                  |           |                               |
//                  +-----------+-------------------------------+
// * Al pinchar en el titulo el contenido de su compañera desaparece
//-------------------------------------------------------------------------
function iniccaja ($id,$titu,$display='block',$oculta='block',$extrahtml='')
{
  $td=$id.'td';
  // Si se ve la caja inicial su color es #DDDDDD 
  if ($display!='none') $class="style = 'background-color: #DDDDDD'";
  // Si no se ve se queda el color blanco de fondo. 
  else $class="style = 'background-color:'"; 
echo "\n\n
<!--- INICIO DE: $titu --->
<table width=100% align=center cellpadding=2 cellspacing=1 border=0 style='display:$oculta'>
 <tr style='display:$oculta'> 
  <td width='20%' class='izqform' $extrahtml><a class=\"izqform\"  href=\"javascript:muestrasconde($id)\" onclick=\"color($id,$td)\">$titu</a></td>
  <td width='80%' style= valign='top' id='$td' $class><div id='$id' class='derform' style='display:$display'> \n\n\n\n";
}

function finacaja()
{
echo"\n\n
   </div>
  </td>
 </tr>
</table>
<!--- FIN DE LA CAJA --->\n\n\n";
} 


//-------------------------------------------------------------------------
// FUNCION: iniccaja2 ($id,$titu,$display,$oculta='block')
//          finacaja2 ()
//
// Son identicas que las dos anteriores pero el diseño de la caja que forma 
// es distinto.
// EJEMPLO: 
//                  +------------------------------------------+
//                  |               TITULO*                    |
//                  +------------------------------------------+
//                  |                                          |
//                  |           lo que se quiera poner         |
//                  |                                          | 
//                  +------------------------------------------+
// * Al pinchar en el titulo el contenido de su compañera desaparece
//-------------------------------------------------------------------------
function iniccaja2 ($id,$titu,$display='block',$oculta='block')
{
echo "\n\n
<!--- INICIO DE: $titu --->
<table width=100% align=center cellpadding=2 cellspacing=1 border=0 style='display:$oculta'>
 <tr style='display:$oculta'> 
  <td class='izqform'><a class=\"izqform\"  href=\"javascript:muestrasconde($id)\">$titu</a></td></tr>
</table> 
<table width=100% cellpadding=0 cellspacing=0 border=0 style='display:$display' id='$id'>
 <tr><td style= valign='top'>\n";
}

function finacaja2 ()
{
echo"\n\n
  </td>
 </tr>
</table>
<!--- FIN DE LA CAJA --->\n\n\n";
} 
//-------------------------------------------------------------------------
// FUNCION: dispeleg ($pref, $listado, $maxsele=2,$texto='')
//
// Esta funcion genera dos listados con elementos Disponibles y  Elementos elegidos. 
//
// $pref = es el prefijo que se le pondra a los elementos 
//
// $listado = Array con los elementos a mostrar. Tiene truco el funcionamiento....
//            es un vector asociativo en el que se ve mejor con un ejemplo como funciona
//            Queremos declarar un listado de 3 elementos. 2 Disponible y 1 Elegidoa. 
//
// $maxsele = 2 es el numero maximo de selecciones que se permite. 
//
// $texto   = '' Un texto adicional que se puede poner al inicio (Agrupación / Ordenación)
//
// EJEMPLO:
//      $listado=array('1'=>'Referencia Catastral',
//                     '2'=>'NIF Titular',
//                     '3'=>'Nombre Titular');
//
//      if (!isset ($ordesele) && !isset ($ordenosele)) 
//      {
//        $ordesele = array (3);
//        $ordenosele=array (1,2);
//      }
//
//      dispeleg ('orde',$listado,2,'texto');
//
//
// De esta manera estamos indicando lo siguiente, en caso de que no existan en el listado 
// Elegidos mostrara "Nombre Titular" y en Disponible los otros dos. Seria algo asi: 
//
//      texto
//
//      Disponible                      Elegidos          
//     +---------------------+    +------------------+
//     | Referencia Catastral| >> | Nombre Titular   | 
//     | NIF titular         | << |                  |
//     |                     |    +------------------+
//     +---------------------+            ^ v
// 
// En el fichero gui.js:
// Esta funcion para su correcto funcionamiento necesita de las siguientes funciones en Javascript.
// subeSeleccion (lista) 
// bajaSeleccion (lista)
// aniaorde(destList, srcList, tamamaxi)
// borrorde(destList, srcList)
// seleTodo (lista)
// 
// IMPORTANTE PARA QUE FUNCIONE: 
// "Desventaja" para poder recoger los valores Disponibles y Elegidos necesitamos la funcion
//  antesEnvio ()  y dentro de ella 
//  seleTodo(document.getElementById('<$pref>sele'));
//  seleTodo(document.getElementById('<$pref>nosele')); 
// Esta funcion se llama desde el boton de "Listar", y lo que hace es recoger los valores que 
// contienen estas cajas para pasarlas por POST. 
//----------------------------------------------------------------------------------------
    
function dispeleg ($pref, $listado, $maxsele=2, $texto='') {
  global ${$pref."sele"}, ${$pref."nosele"};
  $sele   = ${$pref."sele"};
  $nosele = ${$pref."nosele"};
  $max = count ($listado);

 $extratabla= " id=$pref name=$pref style='display:block'";
?>



<!-- DISPONIBLES y ELEGIDOS -->
    <table <?echo $extratabla?>>
     <tr><td><?echo $texto?> </td></tr>
     <tr><td>Disponibles</td><td>&nbsp;</td><td><b>Elegidos</b></td></tr>
     <tr><td valign='top'>
        <select multiple size=<?echo $max?> length=20 id='<?echo $pref?>nosele' name='<?echo $pref?>nosele[]'>
<?
        if (is_array ($nosele)) 
          foreach ($nosele as $c)
           if ($listado[$c])
            {echo "\t<option value='$c'>".$listado[$c]."</option>\n";}
?>
        </select>
      </td>
      <td valign='top'>
       <img width=16 height=16 src='<? cheqroot("imag/derecha.png", TRUE) ?>' alt='Pasar a elegidos' onClick='aniaorde (form.<?echo $pref?>sele, form.<?echo $pref?>nosele,<?echo $maxsele?>)'><br>
       <img width=16 height=16 src='<? cheqroot("imag/izquierda.png", TRUE) ?>' alt='Pasar a disponibles' onClick='borrorde (form.<?echo $pref?>sele, form.<?echo $pref?>nosele)'>
      </td>
      <td valign='top'>
       <select multiple size=<?echo $maxsele?> length=20 id='<?echo $pref?>sele' name='<?echo $pref?>sele[]'>
<?
       if (is_array ($sele))
         foreach ($sele as $c)
          if ($listado[$c])
           {echo "\t<option value='$c'>".$listado[$c]."</option>\n";}
        if ($maxsele==1) $style = "style='display:none'";
?>
       </select>
         <div align='center' <?echo $style;?>>
          <img width=16 height=16 src='<? cheqroot("imag/subir.png", TRUE) ?>' alt='Subir posición' onClick='subeSeleccion(form.<?echo $pref?>sele)'>
          <img width=16 height=16 src='<? cheqroot("imag/bajar.png", TRUE) ?>' alt='Bajar posición' onClick='bajaSeleccion(form.<?echo $pref?>sele)'>
         </div>
      </td>
     </tr>
    </table>
<!-- FIN DISPONIBLES y ELEGIDOS -->



<?  
}



//------------------------------------------------------
// FUNCIONES busctext ($pref,$tipo='')
// Funcion evolucionada de muesrang. Más simple y para 
// buscar algun texto en la base de datos. 
//------------------------------------------------------


function busctext ($pref,$tipo='') {
 // Muestra los componentes necesarios para elegir entre rangos. Por parámetro
 // recibe el prefijo (único por cada página) que debe ponerle al nombre de
 // todos los componentes que cree.

 //MODIFICACION. Se retira los parametros $valorselect='0',$valortb1='',$valortb2=''
 //              que optiene automaticamente declarandolos como globales. 
  global 
    ${$pref.'select'},
    ${$pref.'textbox'};
   $valorselect= ${$pref.'select'};
   $valortb    = ${$pref.'textbox'};
?>
 <select class = "inputform" name="<?print $pref?>select" >
   <option <?if ($valorselect==1) print "selected"?> value="1">Igual a
   <option <?if ($valorselect==2) print "selected"?> value="2">Empieza por 
   <option <?if ($valorselect==3) print "selected"?> value="3">Contiene
 </select>
 
   <? cajatexto ($pref . "textbox", $valortb, $tipo);
}

 


//------------------------------------------------------
// FUNCION oculdato ()
// Crea una serie de campos ocultos para cuando se presione 
// en el boton VOLVER se mantengan todos los valores 
// introducidos por el usuario en la pantalla anterior. 
// Los datos enviado por el metodo GET y POST.
//------------------------------------------------------
function oculdato ()
{
  global $_POST,$_GET;
  $variables="\n\n\n <!----- GUARDANDO VARIABLES SI SE PIDE VOLVER --->\n";
 if ($_POST)
  foreach ($_POST as $titulo => $valor)
  {
  if ($titulo != 'opci')  
   if (is_array ($valor)) foreach ($valor as $t => $v) $variables.= "   <input type='hidden' name='".$titulo."[".$t."]' value='$v'>\n";
   else   if ($valor) $variables.="<input type='hidden' name='$titulo' value='$valor'>\n";
  }
 if ($_GET)
  foreach ($_GET as $titulo => $valor)
  {
   if (is_array ($valor)) foreach ($valor as $t => $v) $variables.="   <input type='hidden' name='".$titulo."[".$t."]' value='$v'>\n";
   else if ($valor) $variables.="<input type='hidden' name='$titulo' value='$valor'>\n";
  }
  return $variables."<!----- FIN DE VARIABLES -----> \n\n\n\n";

}

//------------------------------------------------------
// FUNCION limpdato ()
// Encargada de limpar todos los datos de un formulario
// si no se puede hacer mediante la funcion javascript 
// reset del formulario.
// NOTA: Limpa las variables tantos pasadas por GET como 
// POST 
//------------------------------------------------------
function limpdato () {
 global $_POST,$_GET;
 if ($_POST)
   foreach ($_POST as $post => $v) {
     global $$post;
     $$post='';
   }
 if ($_GET)
   foreach ($_GET  as $get => $v) {
     global $$get;
     $$get='';
   }
}

//------------------------------------------------------
// FUNCION estacaja ($mirar)
// Esta función simplemente retornara: 
// block / none 
// Se ha creado especialmente para los listados
// comprueba en el array que se le pasa si alguno 
// de sus componente contiene valor. 
//------------------------------------------------------
function estacaja ($mirar) {
  // En caso de que solo sea uno, lo transformamos en array. 
  if (!is_array ($mirar)) $mirar = array ($mirar);

  // Miramos cada uno de ellos 
  foreach ($mirar as $variable) {
     global $$variable;
     // Si contiene valor ni seguimos ejecutamos,
     // retornamos diretamente 'block'
     if ($$variable) {return 'block';}
   }

   // Si se ha llegdo a este punto es que el los componentes 
   // no contienen valor alguno, retornamos 'none'
   return 'none';
}





// A PARTIR DE ESTA FUNCION, SE MUESTRAN LAS QUE SE CONTENIAN gui.fnc Y
// elemhtml.fnc, Y QUE HAN SIDO UNIFICADAS/FUSIONADAS





// -----------------------------------------------------------------------
// Muestra los componentes necesarios para elegir entre rangos o bien una 
// fecha o mes concreto. Por parámetro recibe el prefijo (único por cada página) 
// que debe ponerle al nombre de todos los componentes que cree.
// El tipo del campo debe estar definido en comun/tipos.js
// Para ejecutar muesrang, es obligatorio que previamente se haya establecido
// la directiva <FORM> de HTML, y que se llame a la funcion opci.
// -----------------------------------------------------------------------
function muesrang( $pref, $tipo='', $valorselect='0', $valortb1='', $valortb2='' ) {

 // En el onChange del SELECT, se comprueba si se cambia a otra opcion que no sea 
 // FECHA REGISTRADA (opcion 9), que entonces tenía la fecha 01/01/0001,
 // se pone ese campo en blanco.
 // Ademas, se llama a la funcion arreglaCajas que decide que campos mostrar/ocultar
 // segun la opcion escogida.
?>
 <select class = "inputform" name="<?print $pref?>select"
         onChange="if (form.<? print $pref?>textbox1.value == '01/01/0001') { 
                     form.<? print $pref?>textbox1.value = ''; 
                   }
                   arreglaCajas( form.<?print $pref?>select, '<? print $pref ?>' );">
   <option <?if ($valorselect==1) print "selected"?> value="1">Igual a
   </option>

   <option <?if ($valorselect==2) print "selected"?> value="2">
    <? if ($tipo=='euro' || $tipo=='nume' || $tipo=='supe' || $tipo=='elemcant' || $tipo=='deci' ) 
         echo " Menor que"; else echo " Anterior a";?>
   </option>
    
   <option <?if ($valorselect==3) print "selected"?> value="3">
    <? if ($tipo=='euro' || $tipo=='nume' || $tipo=='supe' || $tipo=='elemcant' || $tipo=='deci' )
         echo " Menor que o igual a"; else  echo " Anterior o igual a";?>
   </option>
    
   <option <?if ($valorselect==4) print "selected"?> value="4">
    <? if ($tipo=='euro' || $tipo=='nume' || $tipo=='supe' || $tipo=='elemcant' || $tipo=='deci' )
         echo " Mayor que"; else echo " Posterior a";?> 
   </option>
    
   <option <?if ($valorselect==5) print "selected"?> value="5">
    <? if ($tipo=='euro' || $tipo=='nume' || $tipo=='supe' || $tipo=='elemcant' || $tipo=='deci' )
         echo " Mayor que o igual a"; else echo " Posterior o igual a";?>
   </option>

   <option <?if ($valorselect==6) print "selected"?> value="6">Entre
   </option>

   <? if ($tipo == 'fech') { ?>
     <option <?if ($valorselect==7) print "selected"; ?> value="7">Mes
     </option>
     <option <?if ($valorselect==8) print "selected"; ?> value="8">Entre meses
     </option>
     <option <?if ($valorselect==9) print "selected"; ?> value="9">No registrada
     </option>
   <? } ?>
 
 </select>
 <span <? if ( $valorselect == 7 || $valorselect == 8 || $valorselect == 9 )
           print "style=\"display: none\""?> id="<?print $pref?>div1">
   <? cajatexto ($pref . "textbox1", $valortb1, $tipo) ?>
 </span>

  <span <? if ( $valorselect != 6 ) print "style=\"display: none\""?> id="<?print $pref?>div2">y
    <? cajatexto ($pref . "textbox2", $valortb2, $tipo) ?>
  </span>

  <span <? if ($valorselect!=7 && $valorselect!=8) print "style=\"display: none\""?> id="<?print $pref?>mes1">
    <? echo mes ($pref."listmes1",$pref); ?>
    <script> limpieza += ' form.<? print $pref ?>listmes1.value = \"\";'; </script>    
    <script> limpieza += ' form.<? print $pref ?>listmes1anio.value = \"\";'; </script>        
  </span>

  <span <? if ( $valorselect != 8 ) print "style=\"display: none\""?> id="<?print $pref?>mes2">
    <? echo "y".mes( $pref."listmes2", $pref );?>
    <script> limpieza += ' form.<? print $pref ?>listmes2.value = \"\";'; </script>
    <script> limpieza += ' form.<? print $pref ?>listmes2anio.value = \"\";'; </script>    
  </span>

  <script>
   function <?echo $pref?>mesok() {
     if ( form.<?print $pref?>listmes1.value != '' ) {
     form.<?print $pref?>textbox1.value= '01-'+form.<?print $pref?>listmes1.value+'-'+<?print date (Y)?>;
     } else {
       form.<?print $pref?>textbox1.value = '';
     }
   }
  </script> 
<?
  // if ($tipo=='euro') echo "&euro;";
  //if ($tipo=='supe') echo "m<sup>2</sup>";
}


//------------------------------------------------------
// FUNCION mes ($name,$pref)
// Cuando el usuario decida poner la fecha por un rango de meses 
// esta funcion se encarga de generar un combo con los meses del año 
// Aunque se puede utilizar por separado, acompaña a la funcion "muesrang"
//------------------------------------------------------
function mes ($name,$pref) {
  global $$name,${$name.'anio'};
  $mes="<select name=\"$name\" onchange=\"".$pref."mesok()\">
    <option>
    <option"; if ($$name == '01')  $mes.= " selected"; $mes.= " value='01'>".mesnomb('01')."
    <option"; if ($$name == '02')  $mes.= " selected"; $mes.= " value='02'>".mesnomb('02')."
    <option"; if ($$name == '03')  $mes.= " selected"; $mes.= " value='03'>".mesnomb('03')."
    <option"; if ($$name == '04')  $mes.= " selected"; $mes.= " value='04'>".mesnomb('04')."
    <option"; if ($$name == '05')  $mes.= " selected"; $mes.= " value='05'>".mesnomb('05')."
    <option"; if ($$name == '06')  $mes.= " selected"; $mes.= " value='06'>".mesnomb('06')."
    <option"; if ($$name == '07')  $mes.= " selected"; $mes.= " value='07'>".mesnomb('07')."
    <option"; if ($$name == '08')  $mes.= " selected"; $mes.= " value='08'>".mesnomb('08')."
    <option"; if ($$name == '09')  $mes.= " selected"; $mes.= " value='09'>".mesnomb('09')."
    <option"; if ($$name == '10')  $mes.= " selected"; $mes.= " value='10'>".mesnomb('10')."
    <option"; if ($$name == '11')  $mes.= " selected"; $mes.= " value='11'>".mesnomb('11')."
    <option"; if ($$name == '12')  $mes.= " selected"; $mes.= " value='12'>".mesnomb('12')."
   </select>\n";
   
   $anioactual = date(Y);
   $anio_1 = date(Y)-1;
   $anio_2 = date(Y)-2;
   
   $mes.="   <select name=\"".$name."anio\"> 
      <option"; if (${$name.'anio'} == $anioactual)    
                  $mes.= " selected"; $mes.= " value='$anioactual'>$anioactual
      <option"; if (${$name.'anio'} == $anio_1)  
                  $mes.= " selected"; $mes.= " value='$anio_1'>$anio_1
      <option"; if (${$name.'anio'} == $anio_2)  
                  $mes.= " selected"; $mes.= " value='$anio_2'>$anio_2
     </select>\n";
   return $mes ;
}


//------------------------------------------------------
// Funcion que devuelve el nombre del mes según su numero del 1 al 12
// Es llamada desde la función mes() y tambien desde mesinfo().
//------------------------------------------------------
function mesnomb ($mes) {
  switch ($mes) {
     case  '01':  return"Enero";
     case  '02':  return"Febrero";
     case  '03':  return"Marzo";
     case  '04':  return"Abril";
     case  '05':  return"Mayo";
     case  '06':  return"Junio";
     case  '07':  return"Julio";
     case  '08':  return"Agosto";
     case  '09':  return"Septiembre";
     case  '10':  return"Octubre";
     case  '11':  return"Noviembre";
     case  '12':  return"Diciembre";
  }
}


// -----------------------------------------------------------------------
# Función para dibujar una caja de texto. Se especifica el nombre, el valor
# por omisión, el tipo de dato que va a contener y, optativamente, atributos
# extra para la etiqueta <input>. Queda reservada la gestión del suceso
# onKeyPress para uso interno de la función
// Para ejecutar cajatexto es obligatorio que previamente se haya establecido
// la directiva <FORM> de HTML, y que se llame a la funcion opci.
// -----------------------------------------------------------------------
function cajatexto( $nombre, $valor, $tipo = "libre", $extrahtml = "" ) {

  if ($tipo == 'fech') {
    // Si se pulsa dos veces sobre un campo fecha, muestra un calendario
    // en el que seleccionar la fecha.
    $extrahtml .= " onDblClick='if(!form.$nombre.disabled && !form.$nombre.readOnly) show_calendar(\"form.$nombre\",\"".(date('m')-1)."\",\"".date('Y')."\",\"DD-MM-YYYY,DD.MM.YYYY,DD/MM/YYYY\", \"POPUP\", \"Title=Calendario;CurrentDate=Today;WeekStart=1;Weekends=06;AllowWeekends=Yes;CloseOnSelect=Yes;PopupX=\" + (window.event.x + 100) + \";PopupY=\" + (window.event.y - 100) + \";Fix=No;Css=" . cheqroot("comun/estilo.css") . "\");'";
  }

  if ($tipo) {
    $extrahtml = preg_replace ("/'/", "\\'", $extrahtml);
    
    $extrahtml .= " onKeyup=\"tecla=this.value; if (this.value.match(in_regex_$tipo)) this.value = this.value.toUpperCase();\"";
    
    print "<script>document.write ('<input type=\"text\" name=\"$nombre\" value=\"$valor\" size=\"' + size_$tipo + '\" maxlength=\"' + maxlength_$tipo + '\"');\n";
    print "document.write (' onKeyPress=\"tecla = String.fromCharCode(window.event.keyCode);');";

    # Pasamos siempre a mayúsculas
    $extra = " window.event.keyCode = tecla.toUpperCase().charCodeAt(0); return true;";

    # Aquí *podríamos* poner comprobaciones según el tipo

    # Comprobaciones comunes de entrada
    print "document.write (' if (! tecla.match(in_regex_$tipo)) return false; $extra\" $extrahtml>');\n";

    # Añadimos a las comprobaciones de envío las del campo actual
    print "comprobaciones += 'if (! form.$nombre.value.match (regex_$tipo)) { form.$nombre.focus(); alert (\'El valor \"\' + form.$nombre.value + \'\" (\' + literal_$tipo + \') es incorrecto\'); return false; }';";

    # Caso especial para las fechas
    if ($tipo == 'fech')
      print "comprobaciones += 'if (form.$nombre.value != \'\' && cheqfech (form.$nombre.value) != \'\') { form.$nombre.focus(); alert (\'La fecha \' + form.$nombre.value + \' no es válida: \' + cheqfech (form.$nombre.value)); return false; }';";

    # Añadimos a la rutina de limpieza el campo actual
    print " limpieza += ' form.$nombre.value = \"\";';";

    # Fin de JS
    print "</script>\n";
    if ($tipo == 'fech'){
         print "<input type='image' src='" . cheqroot("imag/cal.gif") . "' border='0' width='24' height='24' align='absmiddle' alt='Calendario' onclick='if(!form.$nombre.disabled && !form.$nombre.readOnly) show_calendar(\"form.$nombre\",\"".(date('m')-1)."\",\"".date('Y')."\",\"DD-MM-YYYY,DD.MM.YYYY,DD/MM/YYYY\", \"POPUP\", \"Title=Calendario;CurrentDate=Today;WeekStart=1;Weekends=06;AllowWeekends=Yes;CloseOnSelect=Yes;PopupX=\" + (window.event.x + 100) + \";PopupY=\" + (window.event.y - 100) + \";Fix=No;Css=" . cheqroot("comun/estilo.css") . "\"); return false' >";
//INLINE         print "<input type='image' src='" . cheqroot("imag/cal.gif") . "' border='0' width='24' height='24' align='absmiddle' alt='Calendario' onclick='if(!form.$nombre.disabled && !form.$nombre.readOnly) show_calendar(\"form.$nombre\",\"".(date(m)-1)."\",\"".date(Y)."\",\"DD-MM-YYYY\", \"INLINE\", \"Title=Calendario;CurrentDate=Today;WeekStart=1;Weekends=06;CloseOnSelect=Yes;InlineX=\" + (window.event.x + 10) + \";InlineY=\" + (window.event.y + document.body.scrollTop - 100) + \";Fix=No;Css=" . cheqroot("comun/estilo.css") . "\"); return false' >";
    }
  } else {
    // Si se el tipo=="", entonces se considera que es el tipo texto
    echo "<input name='$nombre' type='text' value='$valor' $extrahtml>";
  }
  if ($tipo=='euro') echo "&euro;";
  if ($tipo=='supe') echo "m<sup>2</sup>";
  if ($tipo=='hecta') echo "Ha";  
  
}

// -----------------------------------------------------------------------
// Función para dibujar un area de texto. Se especifica el nombre, el valor
// por omisión, y el tamaños de filas y columnas.
// Para ejecutar cajatexto es obligatorio que previamente se haya establecido
// la directiva <FORM> de HTML, y que se llame a la funcion opci.
// -----------------------------------------------------------------------
function areatexto( $nombre, $valor, $cols='60', $rows='4', $tipo='area' ) {

 print "<script>document.write ('<textarea name=\"$nombre\" class=\"inputform\" cols=\"' + $cols + '\" rows=\"' + $rows + '\"');\n";
 print "document.write (' onKeyPress=\"tecla = String.fromCharCode(window.event.keyCode);');";

 # Pasamos siempre a mayúsculas si no es tipo areamin
 if ($tipo != 'areamin')
    $extra = " window.event.keyCode = tecla.toUpperCase().charCodeAt(0); return true;";

 # Eliminamos los blancos del principio y del final
 $valor=trim($valor);
 
 # Comprobaciones comunes de entrada
 print "document.write (' if (! tecla.match(in_regex_$tipo)) return false; $extra\">$valor</textarea>');\n";

 # Añadimos a las comprobaciones de envío las del campo actual
 print "comprobaciones += 'if (! form.$nombre.value.match (regex_$tipo)) { form.$nombre.focus(); alert (\'El valor \"\' + form.$nombre.value + \'\" (\' + literal_$tipo + \') es incorrecto\'); return false; }';";

 # Añadimos a la rutina de limpieza el campo actual
 print " limpieza += ' form.$nombre.value = \"\";';";

 print "</script>\n";
 
 }

//-------------------------------------------------------------------------
// FUNCION:radio ($nombre,$opciones,$check,$text='', $extrahtml='') 
// Genera un radio 
// $nombre   = es el nombre que se le asigna al campo en el formulario.
// $opciones = es un array, contiene VALOR => TEXTO 
//  · $valor = es el valor que optiene cuando se marca la opcion. 
//  · $texto = se muestra para indicar que es cada radio.
// $check    = opcion marcada 
// $text     = texto inicial que se muestra antes de poner los radios. 
// $extrahtml= Si se necesita poner alguna funcion en javascript.
//-------------------------------------------------------------------------
function radio ( $nombre, $opciones, $check='', $text='', $extrahtml='' ) {
  if ( is_array ( $opciones ) ) { 
    echo $text;
    foreach ( $opciones as $valor => $texto ) {
     echo "<input name='$nombre' type='radio' value='$valor'";
     if ( $valor == $check ) echo " checked";
     if ( $extrahtml ) echo $extrahtml;
     echo "> $texto\n";
    }
  } else {
    echo "<b>ERROR: $nombre </b> Se tienen que indicar las opciones para el radio button<br>\n";
  }
}

//-------------------------------------------------------------------------
// Genera un checkbox 
// $nombre  = es el nombre que se le asigna en el formulario.
// $marcado = indica si el check esta marcado o no.
// $texto   = despues del Checkbox muestra información si se desea. 
// $extrahtml= si se necesita poner alguna funcion en javascript. 
// Puede agregar un texto detras de la caja. 
//-------------------------------------------------------------------------
function checkbox ($nombre, $marcado, $texto='', $extrahtml='') {
  echo "<INPUT TYPE='checkbox' NAME='$nombre'";
  if ($marcado) echo " CHECKED";
  if ($extrahtml) echo $extrahtml;
  echo "> $texto\n";
}
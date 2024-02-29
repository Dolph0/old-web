<?
include "comun/func.inc";
include "comun/fecha.fnc";
include "liqu/muescarg.fnc";
include_once "clas/config.inc";
include_once "liqu/carg.fnc";

  // recupera el nombre de página
  $estaurlx = estaurlx();
  echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("liqu/" . $estaurlx . ".js") . "'></SCRIPT>";

  // Recibe los datos del 2º formulario del script liqu.php e inserta los registros en 
  // la tabla carg tras mostrar el resumen, y el usuario de su conformidad.
  # Comprobar que usuario abre la pagina
  $sesi = cheqsesi();
  $codiusua = $sesi[sesicodiusua];

  # Comprueba si el usuario tiene permiso para entrar en la pagina
  if ( !cheqperm( "gestliqu", $sesi[sesicodiusua] )  )
    segu( "Intenta entrar a liquidación de deudas sin permiso" );

  print "<div class='solopantalla'>\n";
  cabecera("Cargo Generado");

  // Esto es lo que le paso al script de Esteban, ../rcoi/baja.php, al que envio
  // los datos de los objetos liquidados. $j se cuenta a partir de 1
  if ( $cobr == 1 ) {
      print "<form name='form' method='post' action='" . cheqroot("rcoi/baja.php") . "' target='_blank'>\n";
  }else{
      print "<form name='form' method='post' action='" . cheqroot("rnot/notiindivolu.php") . "' target='_blank'>\n";
  }
  
  $listopci = "Volverte:Imprimir:ImprimeNotiIndiPers";
  if (cheqperm("notiliqu", $sesi[sesicodiusua])) $listopci .= ":ImprimeNotiIndi";
  if (cheqperm("notisice", $sesi[sesicodiusua])) $listopci .= ":SicerImprimeNotiIndi";
  opci($listopci); 
  print "</div>\n";

  print "<input type='hidden' name='volvurlx' value='$volvurlx'>";

  print "<input type = 'hidden' name = 'aux_queryextra' value='$aux_queryextra'>";

  // Se necesita una caja con SI/NO/CANCELAR
  print "
  <SCRIPT language='JavaScript'>
    function volverte() {
      if (form.aux_queryextra.value != '') {
        if (opener) window.close();
      } else {
        if (top.area) top.area.location='$volvurlx'; 
        else if (top) top.location = '$volvurlx';
      }
      return false;
    }
  </SCRIPT>";
  
  print "
  <SCRIPT LANGUAGE='VBScript'>
    function MensajeSNC (txt)
    Res = msgbox(txt, 65536 + 32 + 3 + 512)
    'Res = 6 => SI
    'Res = 7 => NO
    'Res = 2 => Cancelar
    MensajeSNC = Res
    end function
  </SCRIPT>";

  $query = "SELECT liquvect.serivect FROM liquvect WHERE liquvect.codisequ = $codisequ";
  $vect = sql( $query );
  $vect = urldecode($vect);
  sql( "DELETE FROM liquvect WHERE liquvect.codisequ = $codisequ" );
  // El vector viene de forma compacta tras aplicarle la funcion serialize, asi que hay que aplicarle
  // la funcion unserialize. Ademas, como se envio por web a un formulario, las " se han transformado
  // en \" por lo que hay que quitar las barras \ con la funcion stripslashes
  $vect =  unserialize(stripslashes($vect)) ;

  if ( $contobje > 0 ) {

    set_time_limit(0);

    // Obtengo los indices del vector de objetos, ordenados por el nombre del sujeto titular,
    // ya que el vector viene ordenado por codigo tributario
    // Nos sirve para recorrer el vector de objetos en un bucle FOR posterior, 
    // ordenando los objetos por nombre del titular
    for( $i = 0; $i < $contobje; $i++ ) {
      $orde[$i] = $vect[$i][nombsuje];
    }
    asort( $orde );

    // NIF del propio ayuntamiento. Se usa para determinar si se generan cargos con NIF 
    // del propio ayuntamiento
    $nifx = sql("SELECT ayun.nifx FROM ayun WHERE ayun.codiayun = (SELECT usua.codiambi FROM usua WHERE usua.codiusua = $codiusua)");
	// Si no se ha definido la variable nifxayun obtenemos el valor de la tabla config
	if (!isset($nifxayun)){
		$config = &config::getInstance();
		$nifxayun = $config->getValue('liquidación.propioayuntamiento', $codiayun);
	}
    // Datos para la cabecera de cada pagina
    $ejer = date('Y', time());  // Año actual
    // Nombre completo del modo y periodo de liquidacion. 
    // Se usa para mostrarlo al comienzo de cada pagina
    $query = "SELECT liqutipo.nomb FROM liqutipo WHERE liqutipo.abre = '".$modoliqu."'";
    $modo = sql ( $query );
    $query = "SELECT liquperi.nomb FROM liquperi WHERE liquperi.abre = '".$periliqu."'";
    $peri = sql ( $query );

    // Contador de cargos generados
    $contcarg = 0;

    print "<center>\n";
//    // Mostrar la cabecera de la tabla por primera vez
//    cabecarg($ejer,$anio,$modo,$peri);

    // Inicializacion de los totales de los importes de las cuotas y deudas para mostrarlos
    // despues de generar el cargo
    $totacuot = 0;
    $totadeud = 0;

    // Determinar si es una liquidacion directa. Se necesita porque si es directa, 
    // el cargo nuevo se generara sin importar si ya existe un cargo igual anterior.
    // Pero si no es directa, y existe un cargo anterior igual, entonces solo se genera si el
    // anterior esta Pediente de Cobro, y se anula, usando el checkbox
    if ( substr( $modoliqu, 0, 1 ) == "D" ) {
      $liqudire = 1;
    } else {
      $liqudire = 0;
      // Me aseguro de que si no es una liquidacion directa, no tenga valor el importe ingresado
      $impoingr = 0;
      $refeingr = "";
      $fechingr = "";
    }
    // Obtengo el formato correcto de la fecha para insertarlo en la base de datos
    $fechingr = Guardarfecha( $fechingr );
    
    // Inicializo un contador de lineas para saber cuando hay un salto de pagina
    // El indice $j va desde 1 hasta $contobje, y lo uso por dos motivos:
    // Uno, para determinar cuando se produce un salto de pagina, 
    // y dos, porque Esteban, en su script ../rcoi/baja.php al que envio
    // los datos de los objetos liquidados, cuenta a partir de 1, y yo cuento 
    // a partir de 0 (en el indice $i)
    $j = 1;
    for( reset( $orde ); is_integer( $i = key( $orde ) ); next( $orde ) ) {
      // Nombre del checkbox para insertar el nuevo cargo
      $nombcheqcargo = "cargo".$i;
        
      // Filtro de los que no quiero generar  
      if (!$$nombcheqcargo) continue;

      if ( $vect[$i][deud] <= 0 ) {
        // No se generan cargos con deuda 0, por ejemplo, las exenciones
        // Ni con deuda negativa, por ejemplo, debido a la resta en la deuda del $impoingr
        continue;
      } else {
        // Determino los saltos de pagina
        // Solo muestro 59 lineas por pagina. Antes mostraba 60 por pagina, pero ahora se ha añadido
        // en la cabecera de cada pagina, otra linea que indica la fecha del cargo.
        if ( $j % 59 == 1 ) {
          // Busco el modulo 1, porque el indice $j comienza en 1, y asi se muestra la
          // cabecera, cuando se muestra el primer cargo.
          if ( $j != 1 ) {
            print "</table>\n";
            print "<hr>\n";
            salto();
          }
          cabecarg($ejer,$vect[$i][anio],$modo,$peri);
        }
        
        // Nombre del checkbox para anular un cargo existente
        $nombcheq = "anul".$i;
        
        // Nombre del campo oculto que indica el tipo del cargo ya existente (o blanco si no existe)
        $tipo = "tipo".$i;
        
        // Variable que vale 1 o 0 segun se inserte o no el nuevo cargo
        $nuevcarg = 0;
        
        if ( ( $vect[$i][cuot] != -1 ) && ( $vect[$i][deud] != -1 ) ) {
          // No se genera el cargo de objetos que producen error en la liquidacion

          if ( ( $vect[$i][nifx] == $nifx ) && ( !$nifxayun ) ) {
            // No se generan cargos con NIF del ayuntamiento cuando no se marcó el checkbox nifxayun
            continue;
          } else {
            if ( $liqudire == 1 ) {
              // Es una liquidacion directa.
              // En este caso, siempre se inserta el cargo deseado, aunque ya exista algun cargo
              // de ese objeto en ese año y periodo de liquidacion.
              // Si el checkbox estubiera marcado, pues se anula el existente

              // La variable $nuevcarg indica si se inserta el cargo o no
              $nuevcarg = 1;
            } else {
              // No es una liquidacion directa. Es periodica o autoliquidacion.
              // En este caso, si ya existe un cargo antes, solo se inserta el deseado, cuando el
              // existente esta Pendiente de Cobro, y ademas se marco el checkbox para anular 
              // el existente.

              if ( $$tipo != "I" ) {
                // Solo se insertan si ya existe un cargo pendiente de cobro y se desea
                // anular, o bien, existe pero esta anulado o dado de baja, 
                // o bien, no existe el cargo a generar.
                // No se inserta si el cargo existente es un ingreso
                if ( $$tipo != "P" ) {
                  // Anulados o dados de baja
                  $nuevcarg = 1;
                } else {
                  // Los pendientes de cobro se anulan si esta marcado el checkbox
                  if ( $$nombcheq ) {
                    $nuevcarg = 1;
                  }
                }
              }
            }

            // Insertar el cargo, si cumple los requisitos
            if ( $nuevcarg == 1 ) {
              // Datos de la domiciliacion bancaria
              $tipoobje_liqu = sql ("SELECT conctrib.tipoobje FROM conctrib WHERE conctrib.codiconc = ".$vect[$i][codiconc]);
              $resu2 = sql("SELECT domibancobje.bic,domibancobje.iban,domibancobje.numerefe,domibancobje.fechdomi FROM domibancobje WHERE domibancobje.codiobje = ".$vect[$i][codiobje]." AND domibancobje.tipoobje = '$tipoobje_liqu'");
              if ($resu2){
                $resu2 = each($resu2);
                $resu2 = $resu2[value];
                $vect[$i][domibanc] = 1;
                $vect[$i][bic]  = $resu2[bic];
                $vect[$i][iban] = $resu2[iban];
                $vect[$i][fechdomi] = $resu2[fechdomi];
                $vect[$i][refeno60] = $resu2[numerefe];				
              } else {
                $vect[$i][domibanc] = 0;
                $vect[$i][bic] = '';
                $vect[$i][iban] = '';
                $vect[$i][fechdomi] = '0001-01-01';
                $vect[$i][refeno60] = '';				
              }
              
              // Inserto el cargo
              $docu = insecarg( $vect[$i], $$nombcheq, $$tipo );
              $contcarg++;
              $totacuot += $vect[$i][cuot];
              $totadeud += $vect[$i][deud];

              // Una vez generados los cargos, se redirige la pagina a rcoi/baja.php para cobrar e 
              // imprimir los recibos. A continuacion determino los valores de las variables necesarias
              // en el script baja.php que se envian por formulario
      
              $susp=sql("SELECT carg.estanotivolu FROM carg WHERE carg.codiconc=".$vect[$i][codiconc]." AND carg.ejer='".$ejer."' AND carg.numedocu='".$docu."' AND carg.codiayun=".$codiayun);
                  print "<input type='hidden' name='susp$j' value='".$susp."'>\n";
                  print "<input type='hidden' name='codiayun$j' value='".$codiayun."'>\n";
                  print "<input type='hidden' name='codiconc$j' value='".$vect[$i][codiconc]."'>\n";
                  print "<input type='hidden' name='ejer$j' value='".$ejer."'>\n";
                  // insecarg devuelve el valor del numedocu que le asigna el procedimiento almacenado a
                  // cada cargo concreto, en la variable $docu

                  print "<input type='hidden' name='numedocu$j' value='".$docu."'>\n";
              print "<input type='hidden' name='regi$j' value='on'>\n";
              print "<input type='hidden' name='ayun$j' value='".$codiayun."'>\n";
              print "<input type='hidden' name='conc$j' value='".$vect[$i][codiconc]."'>\n";
              // insecarg devuelve el valor del numedocu que le asigna el procedimiento almacenado a 
              // cada cargo concreto, en la variable $docu
              print "<input type='hidden' name='docu$j' value='".$docu."'>\n";
              print "<input type='hidden' name='via$j' value='V'>\n";
              $j++;
            }
          }
        }
      }
    }  // Fin del for que recorre la lista de cargos que se generan

    if ( $contcarg == 0 ) { 
      print "</table>\n";
      print "No ha generado el cargo de ningún objeto.\n"; 
    } else {
      print "<tr>\n";
      print "<td class = 'derform' colspan=5><b>TOTAL</b></td>\n";
      print "<td class = 'derform'>".impoboni($totacuot)."</td>\n";
      print "<td class = 'derform'>".impoboni($totadeud)."</td>\n";
      print "</tr>\n";
      print "</table>\n";
      print "<hr>\n";

      print $contcarg;
      if ( $contcarg > 1 ) { print " cargos generados.\n"; }
      else { print " cargo generado.\n"; }

      print "<input type='hidden' name='numecbox' value='$contcarg'>\n";
      print "<input type='hidden' name='usua' value='$codiusua'>\n";
      print "<input type='hidden' name='ayun' value='$codiayun'>\n";

      if ( $cobr == 1 ) {
        // Pulso el boton de envio del formulario, y desea cobrar el cargo generado
        print "<script language='JavaScript'>form.submit();</script>\n";
      }else{
        $config = &config::getInstance();
        if ($config->getValue('correos.sicer.activado', $codiayun) == 1){
           $siceractivado = 'true';
        }else{
           $siceractivado = 'false';
        }
        // Opción de imprimir las notificaciones de estas liquidaciones en voluntaria.
        /*echo "<SCRIPT LANGUAGE='JavaScript'>
                if (confirm('¿Desea imprimir las notificaciones de estas liquidaciones en voluntaria?')){
                   if (" . $siceractivado . "){
                      if (confirm('¿Desea imprimir las notificaciones por el método SICER?')){
                         var targetantiguo = document.forms[0].target;
                         var actionantiguo = document.forms[0].action;
                         document.forms[0].target = '" . cheqroot("rnot/notiindivolusicer.php") . "';
                         document.forms[0].action = '" . cheqroot("rnot/notiindivolusicer.php") . "';
                         document.forms[0].submit ();
                         document.forms[0].target = targetantiguo;
                         document.forms[0].action = actionantiguo;
                      }else{
                          form.submit();
                      }
                   }else{
                    form.submit();
                   }
                }
              </SCRIPT>";*/
      }
      print "</form>\n";
    }
    print "</center>\n";
  } else {
    print "No se genera el cargo de ningún objeto<br>\n"; 
  }

  include "comun/pie.inc";
?>

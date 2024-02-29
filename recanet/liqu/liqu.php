<?
include "comun/func.inc";
include "comun/gui.fnc";
include "comun/fecha.fnc";
include "comun/compdico.fnc"; // Función que comprueba el dígito de control de las CCC
include_once "liqu/cuot.php";
include "liqu/muescarg.fnc";
include "liqu/deuda.php";
include_once "comun/carg.fnc";
include "comun/ajax.js.php";

print "<script> var limpieza = ''; </script>";
print "<script> var comprobaciones = ''; </script>";

// Este script se encarga de hacer la liquidacion, 
// que puede ser de tres tipos: directa, periodica y autoliquidacion. 
// El script puede ser llamado desde 3 enlaces:
//  - Desde una pagina de un objeto concreto
//  - Desde el menu de algun impuesto (OICV, OIBIURBA,...)
//  - Desde el menu de las tasas y precios publicos (OTPP)
// Los parametros necesarios son codiconc (codigo del concepto), 
// codiobje (codigo de un objeto concreto) y tipoobje (tipo de objeto).

# Comprobar que usuario abre la pagina
$sesi = cheqsesi(); // chequea la sesión
$codiusua = $sesi[sesicodiusua];

# Comprueba si el usuario tiene permiso para entrar en la pagina
if ( !cheqperm( "gestliqu", $sesi[sesicodiusua] )  )
  segu( "Intenta entrar a liquidación de deudas sin permiso" );

// recupera el nombre de página
$estaurlx = estaurlx();

print "<SCRIPT language='JavaScript' src='" . cheqroot("comun/fecha.js") . "'></SCRIPT>\n";
print "<SCRIPT language='JavaScript' src='" . cheqroot("liqu/" . $estaurlx . ".js") . "'></SCRIPT>\n";
print "<SCRIPT language='JavaScript' src='" . cheqroot("comun/vent.js") . "'></SCRIPT\n>";
print "<SCRIPT language='JavaScript' src='" . cheqroot("comun/misc.js") . "'></SCRIPT>\n";
// Se recupera el ámbito (ayuntamiento) del usuario
if (!isset($codiayun)){
   $codiambi = sql ("SELECT usua.codiambi FROM usua where usua.codiusua = $sesi[sesicodiusua]");
}else{
   $codiambi = $codiayun;
}

if ( $codiambi == _GLOBAL_ )
  segu( "No puede realizar la liquidación como usuario global" );


    if ( $codiobje == "" ) {

  //Se sustituyen las / por - en las fechas para que en javascript en el evento onclick del final de liqu.php no tome las / como parte de una dirección url.
  
   $solitextbox1 = preg_replace ("/\//", "-", $solitextbox1);
   $solitextbox2 = preg_replace ("/\//", "-", $solitextbox2);
   $altatextbox1 = preg_replace ("/\//", "-", $altatextbox1);
   $altatextbox2 = preg_replace ("/\//", "-", $altatextbox2);
   $moditextbox1 = preg_replace ("/\//", "-", $moditextbox1);
   $moditextbox2 = preg_replace ("/\//", "-", $moditextbox2);
   $fechinicvolu = preg_replace ("/\//", "-", $fechinicvolu);
   $fechfinavolu = preg_replace ("/\//", "-", $fechfinavolu);
   
      $volvurlx = cheqroot("liqu/liqu.php");
      $volvurlx .= "?codiconc=$codiconc&codiobje=&tipoobje=$tipoobje&modoliqu=$modoliqu&periliqu=$periliqu&fechinicvolu=$fechinicvolu&fechfinavolu=$fechfinavolu&soliselect=$soliselect&solitextbox1=$solitextbox1&solitextbox2=$solitextbox2&solilistmes1=$solilistmes1&solilistmes2=$solilistmes2&solilistmes1anio=$solilistmes1anio&solilistmes2anio=$solilistmes2anio&altaselect=$altaselect&altatextbox1=$altatextbox1&altatextbox2=$altatextbox2&altalistmes1=$altalistmes1&altalistmes2=$altalistmes2&altalistmes1anio=$altalistmes1anio&altalistmes2anio=$altalistmes2anio&modiselect=$modiselect&moditextbox1=$moditextbox1&moditextbox2=$moditextbox2&modilistmes1=$modilistmes1&modilistmes2=$modilistmes2&modilistmes1anio=$modilistmes1anio&modilistmes2anio=$modilistmes2anio&impoingr=$impoingr&refeingr=$refeingr&fechingr=$fechingr&oibidocdgc=$oibidocdgc";
      if ( $tipoobje == "OTPP" ) {
        // Si se liquida un concepto del tipo OTPP, el mueslist de nombre conc
        // indica de que concepto se trata
        $volvurlx .= "&conc=$conc";
      }
    } else {
      if ( $tipoobje == "OTPP" ) {
        $volvurlx = cheqroot("otpp/gestotpp.php");
        $volvurlx .= "?opci=delistado&codilist=$codiobje";
      }
      if ( $tipoobje == "OICV" ) {
        $volvurlx = cheqroot("oicv/gestoicv.php");
        $volvurlx .= "?opci=delistado&codilist=$codiobje";
      }
      if ( $tipoobje == "OIPL" ) {
        $volvurlx = cheqroot("plus/oipl.php");
        $volvurlx .= "?opci=Buscar&codioipl=$codiobje";
      }
      if ( $tipoobje == "OIBIURBA" ) {
        $volvurlx = cheqroot("oibi/oibi.php");
        $volvurlx .= "?opci=Buscar&codiinmu=$codiobje";
      }
      if ( $tipoobje == "OIBIRUST" ) {
        $volvurlx = cheqroot("oibirust/oibirust.php");
        $volvurlx .= "?opci=Buscar&codioibirust=$codiobje";
      }
      if ( $tipoobje == "OIAE" ) {
        $volvurlx = cheqroot("oiae/gestoiae.php");
        $volvurlx .= "?opci=delistado&codilist=$codiobje";
      }
      if ( $tipoobje == "OICI" ) {
        $volvurlx = cheqroot("icio/icio.php");
        $volvurlx .= "?opci=delistado&codilist=$codiobje";
      }
      if ( $tipoobje == "SANCTRAF" ) {
        $volvurlx = cheqroot("osantraf/gestosantraf.php");
        $volvurlx .= "?opci=delistado&codilist=$codiobje";
      }
    }

 // lanza la cabecera html
if ($opci == 'Volver'){
    print "<script> top.area.location = '" . $volvurlx . "';</script>";   
    exit();
}

cabecera("Liquidación");

print( "<BODY>\n" );

// página recursiva
print("<form name = 'form' method = 'post' action = '$estaurlx.php'>\n");
echo "<input type='hidden' name='consinte' value='$consinte'>";
echo "<input type='hidden' name='tipocons' value='$tipocons'>";

// Este nuevo parametro, añade mas condiciones de seleccion al query de liquidacion.
// Nos permite liquidar el resulta de las consultas obtenidas en los objetos tributarios.
// Puesto que el query de liquidacion hace referencia unicamente a la tabla que se 
// liquida, la formula del query que se añade deberia ser.
// AND <tabla>.codigo in (<lista de codigos>)
// Ej. para OICV, ' AND oicv.codioicv IN (343,455,66787,3343, y todos los que sean) '
print "<input type='hidden' name='aux_queryextra' value='$aux_queryextra'>";

// Una vez calculada la deuda, no se permite que se vuelva a calcular de nuevo lo mismo
// por lo que escondemos el menu de iconos
if ( $opci != "Deuda" ) {
   if ($codiobje != ""){
      opci("Volver:Deuda:Limpiar".($consinte?":ConsInte":""));
   }else{
         opci("Deuda:Limpiar".($consinte?":ConsInte":""));
   }
}else {
    // Compruebo que el año contraido no sea mayor que el actual.
    // Ademas, el año contraido no puede ser dos veces menor que el actual, si se trata de una
    // liquidacion periodica, ni 10 veces menor, si no es periodica (directa o autoliquidacion)
    $anioactu = date('Y', time()); 
    if ( $anio > $anioactu ) {
      segu("El año contraído no puede ser mayor que $anioactu");
    }
    if ( $modoliqu == "PER" ) {
      if ( $anio < ($anioactu-1) ) {
        segu("El año contraído no puede ser menor que ".($anioactu-1));
      }
    } else {
      if ( $anio < ($anioactu-10) ) {
        segu("El año contraído no puede ser menor que ".($anioactu-10));
      }
    }

    // Compruebo los rangos de fechas cuando no se liquida un objeto concreto
    if ( $codiobje != "" ) {
      // Me aseguro de que no se elige un rango de fechas cuando se liquida un objeto concreto
      $solitextbox1 = "";
      $solitextbox2 = "";
      $altatextbox1 = "";
      $altatextbox2 = "";
      $moditextbox1 = "";
      $moditextbox2 = "";
    } else {
      // Compruebo los rangos de fechas
      if ($solitextbox1 != ""){
        if ( !cheqfech($solitextbox1) )
          segu("Fecha inicial de solicitud incorrecta");
      }
      if( $altatextbox1 != "" ) {
        if ( !cheqfech($altatextbox1) )
          segu("Fecha inicial de alta incorrecta");
      }
      if( $moditextbox1 != "" ) {
        if ( !cheqfech($moditextbox1) )
          segu("Fecha inicial de último trámite incorrecta");
      }
      if( $solitextbox2 != "" ) {
        if ( !cheqfech($solitextbox2) )
          segu("Fecha final de solicitud incorrecta");
      }
      if( $altatextbox2 != "" ) {
        if ( !cheqfech($altatextbox2) )
          segu("Fecha final de alta incorrecta");
      }
      if( $moditextbox2 != "" ) {
        if ( !cheqfech($moditextbox2) )
          segu("Fecha final de último trámite incorrecta");
      }
    }

    // Compruebo las fechas de inicio/fin del plazo de voluntaria en las liquidaciones periodicas
    if ( $modoliqu != "PER" ) {
      // Me aseguro de que si la liquidacion no es periodica, que las fechas
      // del periodo de voluntaria estan en blanco
      $fechinicvolu = "";
      $fechfinavolu = "";
    } else {
      // Primero compruebo que se han proporcionado las fechas de Voluntaria
      if ( !cheqfech( $fechinicvolu ) ||  !cheqfech( $fechfinavolu ) ) {
        segu("No ha proporcionado fechas válidas para los plazos inicial/final de ingreso en Voluntaria");
      }

      // Ahora compruebo que la inicial sea menor que la final
      if ( compfech( $fechinicvolu, $fechfinavolu ) == 1 ) {
        segu("La fecha final del plazo de ingreso en Voluntaria debe ser mayor que la inicial");
      }

      // Ahora hay que comprobar que sean dias lectivos (no sabados ni domingos)
      $habiinic = esHabil( Guardarfecha($fechinicvolu), $codiambi, 'inicio' );
      $habifina = esHabil( Guardarfecha($fechfinavolu), $codiambi );
      if ( !$habiinic || !$habifina ) {
         if ($codiobje != ""){
            opci("Volver:Deuda:Limpiar".($consinte?":ConsInte":""));
            $opci = "Volver:Deuda:Limpiar";
         }
         else{
            opci("Deuda:Limpiar".($consinte?":ConsInte":""));
            $opci = "Deuda:Limpiar";
         }
         if ( !$habiinic ) { mens("La fecha inicial de voluntaria no es un día hábil"); }
         if ( !$habifina ) { mens("La fecha final de voluntaria no es un día hábil"); }
      }
      
      // Segundo plazo de ingreso
      if ($liqudivi == 1) {
        if ( !cheqfech( $fechinicvolu_2 ) || !cheqfech( $fechfinavolu_2 ) ) {
          segu("No ha proporcionado fechas válidas para los plazos inicial/final de ingreso en Voluntaria en el segundo plazo de ingreso");
        }
        if ( compfech( $fechinicvolu_2, $fechfinavolu_2 ) == 1 ) {
          segu("La fecha final del segundo plazo del plazo de ingreso en Voluntaria debe ser mayor que la inicial");
        }
        $habiinic_2 = esHabil( Guardarfecha($fechinicvolu_2), $codiambi, 'inicio' );
        $habifina_2 = esHabil( Guardarfecha($fechfinavolu_2), $codiambi );
        if ( !$habiinic_2 || !$habifina_2 ) {
           if ($codiobje != ""){
              opci("Volver:Deuda:Limpiar".($consinte?":ConsInte":""));
              $opci = "Volver:Deuda:Limpiar";
           }
           else{
              opci("Deuda:Limpiar".($consinte?":ConsInte":""));
              $opci = "Deuda:Limpiar";
           }
           if ( !$habiinic_2 ) { mens("La fecha inicial del segundo plazo de ingreso de voluntaria no es un día hábil"); }
           if ( !$habifina_2 ) { mens("La fecha final del segundo plazo de ingreso de voluntaria no es un día hábil"); }
        }
      }
      
      // Que no se solapen los dos plazos de ingreso
      if ($liqudivi == 1) {
        if (cheqfech ($fechfinavolu) && cheqfech ($fechinicvolu_2)) {
          if (compfech ($fechfinavolu, $fechinicvolu_2 ) == 1 ) {
            segu("La fecha final del primer plazo de ingreso en Voluntaria debe ser menor que la fecha inicial del segundo plazo");
          }
        }
      }
    }

    // Compruebo el importe de ingreso y la fecha y referencia del ingreso,
    // cuando es liquidacion directa
    if ( substr( $modoliqu, 0, 1 ) == "D" ) {
      if ( !ereg( "^([0-9]+\.?[0-9]{0,2})?$", $impoingr ) ) {
        segu( "Importe de ingreso incorrecto: Intento de entrar desactivando JavaScript" );
      }
      if ( $impoingr != "" ) {
        // Si se especifico un importe de ingreso, la referencia y la fecha no pueden estar en blanco
        if ( !cheqfech( $fechingr ) ) { 
          segu( "Fecha de ingreso incorrecta: Intento de entrar desactivando JavaScript" ); 
        }
        if ( !ereg("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿\?\.\+\*\|\(\)\$\[\{\}\^~/ªº@#·&=%`´¨>\-]+$", $refeingr ) ) {
          segu( "Referencia de ingreso incorrecta: Intento de entrar desactivando JavaScript" );
        }
      } else {
        // Tambien es un error, especificar la fecha o la referencia, y no el importe del ingreso
        if ( $fechingr != "" || $refeingr != "" ) {
          segu( "No ha especificado el importe del ingreso, y sí la fecha/referencia del ingreso" );
        }
      }
    }
   
    if ( ( $modoliqu != "PER" ) || ( $modoliqu == "PER" && $habiinic && $habifina ) ) {
      // No entra en este IF si es liquidacion periodica y la fecha inicial o final de
      // voluntaria, no es un dia hábil
      // No se muestran el menu de botones
      print "<hr><br>\n";
    }
    // Ahora se muestra el formulario de liquidacion, y despues se llama a la funcion deuda, 
    // para generar el cargo
}

// Esta linea es util cuando es invocado desde la pagina de un objeto OTPP concreto
if ( empty( $conc ) ) { $conc = $codiconc; }

if ( $conc ) {
// El codigo del concepto se le ha pasado por parametros al script (metodo GET)
// $codiconc estara en blanco solo cuando fue llamado desde el menu de OTPP.
// Entra en el if si viene desde el menu de un impuesto y desde la pagina de un objeto concreto

  // Hay que calcular si el codigo recibido en $conc se trata de un concepto o de un grupo
  // Se determina a partir del primer caracter: C -> concepto  G -> Grupo
  $tipoconc = substr($conc,0,1);

  $concauxi = substr($conc,1); // Le quito la 1ª letra que dice si es un concepto o un grupo 

  if ( $tipoconc == "C" || $tipoconc == "H" ) {  // Se trata de un concepto
    $query = "SELECT C.nomb, C.liqu, C.auto, C.codigrup, C.liqudivi, C.plazingrvolu,
                     G.nomb as nombgrup 
              FROM conctrib C LEFT JOIN grupconc G ON C.codigrup = G.codigrup 
              WHERE C.codiconc = $concauxi";
    $resp = sql($query);
    // El campo codigrup se usa si es invocado desde la pagina de un objeto OTPP
    if ( is_array( $resp) ) {
      $concdato = each( $resp );
      $concdato = $concdato[value];
    }
  } else {
    if ( $tipoconc == "G" ) {  // Se trata de un grupo de conceptos
      $concdato[codigrup] = $concauxi;
      $concdato[nombgrup] = sql("SELECT grupconc.nomb FROM grupconc WHERE grupconc.codigrup = $concauxi");
      $concdato[nomb] = $concdato[nombgrup];
      // Supongo que todos los conceptos de un mismo grupo tienen el mismo periodo de liquidacion
      $concdato[liqu] = sql ("SELECT DISTINCT conctrib.liqu FROM conctrib WHERE conctrib.codigrup = $concauxi");
    } else {
      segu("El código del concepto/grupo ha sido alterado");
    }
  }
}
//print "obje=$codiobje <br>tipoobje = $tipoobje <br> conc = $codiconc<br>";
//print "liqu = $concdato[liqu]  auto = $concdato[auto]<br>";
?>

<input type='hidden' name='codiconc' value='<? print $codiconc; ?>'>
<input type='hidden' name='codiobje' value='<? print $codiobje; ?>'>
<input type='hidden' name='tipoobje' value='<? print $tipoobje; ?>'>
<input type='hidden' name='liqudivi' value='<? print $concdato[liqudivi]; ?>'>

<center>
<table width = "100%" border = "0" cellspacing = "1" cellpadding = "2">
  <tr>
   <td class = "tituform" colspan = "2">Formulario de Liquidación</td>
  </tr>

  <tr>
    <td class="izqform">Ámbito</td>
    <td class="derform">
      <table width="100%" cellspacing=1 cellpadding=2 border=0>
      <tr>
        <td>Ayuntamiento</td>
        <td>Concepto/Grupo de Conceptos</td>
      </tr>
      <tr>
        <td><b>&nbsp;&nbsp;
          <? print sql("SELECT ayun.nomb FROM ayun WHERE ayun.codiayun=$codiambi");
             $codiayun = $codiambi;
             print "<input type='hidden' name='codiayun' value='$codiambi'>\n"; ?>
          </b>
        </td>
        <td>
        <? 
           if ( !$codiobje && $tipoobje != "OTPP" ) {
             // Es invocado desde el menu de un impuesto concreto
             print "<b>$concdato[nomb]</b>";
             print "<input type='hidden' name='conc' value='$codiconc'>\n";
           }


           if ( $codiobje == "" && $tipoobje == "OTPP" ) {
             // Es invocado desde el menu OTPP

             // El combo debe incluir conceptos del tipo OTPP y grupo de conceptos
             // Cada codigo en el combo, va precedido de una C si se trata de un concepto, 
             // o de una G si se trata de un grupo de conceptos
             $sqlx = "";  // Contendrá la consulta que obtiene grupos y conceptos
             // Primero obtengo los grupos de conceptos
             $resp = sql ("SELECT grupconc.codigrup, grupconc.nomb FROM grupconc WHERE grupconc.codiayun = $codiayun" );
             if ( is_array( $resp ) ) {
               while ( $respdato = each( $resp ) ) {
                 $respdato = $respdato[value];
                 if ( $sqlx != "" ) { $sqlx .= " UNION ";}
                 $sqlx .= " SELECT 'G$respdato[codigrup]', '$respdato[nomb]' FROM dual ";
               }
             }
             // Y ahora obtengo la lista de conceptos del tipo OTPP
             // No se muestran aquellos cuya fecha final haya expirado
             // Obtengo la fecha actual para comprobar cuales han expirado. Los que
             // tienen la fecha final en blanco, no tienen fecha de expiracion
             $fechactu = date( "Y-m-d", time() );
             $query = "SELECT conctrib.codiconc, conctrib.nomb FROM conctrib WHERE conctrib.codiayun = $codiayun 
                       AND (conctrib.tipo = 'T' OR conctrib.tipo = 'PP') AND 
                       (conctrib.fechfina = '".Guardarfecha("")."' OR conctrib.fechfina > '$fechactu')";
             $resp = sql( $query );
             if ( is_array( $resp ) ) {
               while ( $respdato = each( $resp ) ) {
                 $respdato = $respdato[value];
                 if ( $sqlx != "" ) { $sqlx .= " UNION ";}
                 $sqlx .= " SELECT 'C$respdato[codiconc]', '$respdato[nomb]' FROM dual ";
               }
             }

             mueslist("conc",$sqlx,"$conc","form.submit(); return false;");
           }

           if ( $codiobje != "" ) {
             // Es invocado desde la pagina de un objeto concreto
             // Se muestra el impuesto al que pertenece, o si es el tipo OTPP,
             // entonces es uno de estos dos casos:
             // 1) Es un concepto que pertenece a un grupo o no
             // 2) Es un grupo de conceptos 
             print "<b>$concdato[nomb]</b>";
             print "<input type='hidden' name='conc' value='$codiconc'>\n";
           }
        ?>
        </td>
      </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td class="izqform">Propiedades</td>
    <td class="derform">
      <table width="100%" cellspacing=1 cellpadding=2 border=0>
      <tr>
        <td>Modalidad</td>
      </tr>
      <tr>
        <td>
        <?  if ( $tipoconc == "G" ) {
              // Si es un grupo de conceptos, solo se aplica liquidacion periodica
              // Pero si la liquidación fue invocada desde la pagina de un objeto concreto, 
              // entonces la liquidacion es directa, aunque sea un grupo.
              if ( $codiobje != "" ) {
                // La liquidación es directa porque se invoco desde la pagina de un objeto
                mueslist("modoliqu","SELECT liqutipo.abre, liqutipo.nomb FROM liqutipo WHERE liqutipo.abre != 'AUT' AND liqutipo.abre !='PER';","$modoliqu");
              } else {
                // La liquidacion es periodica
                $modoliqu = "PER";
                mueslist("modoliqu","SELECT 'PER','PERIÓDICA'","$modoliqu","form.submit(); return false;");
              }
            } else {
              // Se liquida un concepto
              if ( $codiobje != "" ) {  // Proviene de la pagina de un objeto concreto
                 if ($tipoobje == "SANCTRAF"){
                    $sqlx = "SELECT liqutipo.abre, liqutipo.nomb FROM liqutipo WHERE liqutipo.abre = 'DDE'";
                 }else{
                    $sqlx = "SELECT liqutipo.abre, liqutipo.nomb FROM liqutipo WHERE liqutipo.abre != 'AUT' AND liqutipo.abre !='PER' ";
                 }
                // Hay que comprobar si el concepto permite autoliquidacion
                if ( $conc ) {
                  if ( $concdato[auto] == '1' )
                    $sqlx .= " UNION SELECT liqutipo.abre, liqutipo.nomb FROM liqutipo WHERE liqutipo.abre = 'AUT' ";
                }
                mueslist( "modoliqu", $sqlx, "$modoliqu", "form.submit(); return false;");
              } else {  // Proviene del menu de un impuesto o del menu de otpp
                 if ($tipoobje == "SANCTRAF"){
                    $sqlx = "SELECT liqutipo.abre, liqutipo.nomb FROM liqutipo WHERE liqutipo.abre = 'DDE'";
                 }else{
                    $sqlx = "SELECT liqutipo.abre, liqutipo.nomb FROM liqutipo WHERE liqutipo.abre != 'AUT' AND liqutipo.abre !='PER' ";
                 }
                // Hay que comprobar si el concepto permite autoliquidacion o liquidacion periodica
                if ( $conc ) {
                  if ( $concdato[auto] == '1' )
                    $sqlx .= " UNION SELECT liqutipo.abre, liqutipo.nomb FROM liqutipo WHERE liqutipo.abre = 'AUT' ";
                  if ( $concdato[liqu] != 'N' )
                    $sqlx .= " UNION SELECT liqutipo.abre, liqutipo.nomb FROM liqutipo WHERE liqutipo.abre = 'PER' ";
                } else {
                  // Si no hay un concepto/grupo seleccionado, no se permite seleccionar 
                  // liquidación periódica ni autoliquidación
                  if ( ( $modoliqu == "AUT" ) || ( $modoliqu == "PER" ) ) {
                    $modoliqu = "";
                  }
                }
                mueslist("modoliqu","$sqlx","$modoliqu","form.submit(); return false;");
              }
            }

            if ( $modoliqu == "PER" ) {
              // En la liquidación periódica, los campos de intervalos de fechas se anulan, porque
              // no se rellenan en ese tipo de liquidación
              //$solitextbox1 = "";
              //$solitextbox2 = "";
              //El rango de ALTA se usa en OICV
              //$altatextbox1 = "";
              //$altatextbox2 = "";
              //$moditextbox1 = "";
              //$moditextbox2 = "";
            } else {
              // Si no es periodica, no se pide fechas del plazo de ingreso en voluntaria
              $fechinicvolu = "";
              $fechfinavolu = "";
            }
        ?>
        </td>
      </tr>
      <tr>
<?
      if (  $tipoobje == "OIPL" || $tipoobje == "OICI" || $tipoobje == "SANCTRAF") {
?>
        <td>&nbsp;</td>
<?
      }else{
?>
        <td>Período</td>
<?
      }
?>
        <td>Año Contraído</td>
      </tr>
      <tr>
        <td>
        <?
           // En los casos de plusvalia OIPL y construcciones OICI, no tiene sentido el 
           // concepto "periodo de liquidacion" es decir, no tiene periodo, solo se hace una vez.
           // Esto viene dado en el campo conctrib.liqu
           if ( $concdato[liqu] == 'N' ) {
             $periliqu = "00";
             print "<input type = 'hidden' name = 'periliqu' value = '$periliqu'>\n";
           } else {
             // No se trata de OIPL ni de OICI: se muestra el periodo de liquidacion.
             if ( $conc && ( $modoliqu != "AUT" ) ) {
               if ( substr( $modoliqu, 0, 1 ) == "D" ) {
                 // Liquidacion directa
                 mueslist("periliqu","SELECT liquperi.abre, liquperi.nomb FROM liquperi WHERE liquperi.grup IN ('N','$concdato[liqu]') AND liquperi.abre NOT IN ('P1','P2') ORDER BY liquperi.inicperi","$periliqu");
               } else {
                 // Liquidacion periodica o cuando no se ha seleccionado aun ninguna modalidad de liquidacion
               mueslist("periliqu","SELECT liquperi.abre, liquperi.nomb FROM liquperi WHERE liquperi.grup = '$concdato[liqu]' AND liquperi.abre NOT IN ('P1','P2') ORDER BY liquperi.inicperi","$periliqu");
               }
             } else {
               // Autoliquidacion
               mueslist("periliqu","SELECT liquperi.abre, liquperi.nomb FROM liquperi WHERE liquperi.abre NOT IN ('P1','P2') ORDER BY liquperi.grup, liquperi.inicperi","$periliqu");
             }
           }
           // Tipo de liquidacion periodica, para comprobar en javascript los casos en que
           // no tiene sentido el concepto "periodo de liquidacion" porque tipoliquperi == 'N'
           print "<input type = 'hidden' name = 'tipoliquperi' value = '".$concdato[liqu]."'>\n";
        ?>
        </td>
        <? 
           if ( !isset( $anio ) ) {
             // Al entrar por primera vez en la pagina de liquidacion, se asigna el valor del
             // año contraido, y ya despues, si el usuario quiere, puede cambiarlo.
             // Si se recarga la pagina, toma el valor que tenia ya asignado, y no vuelve
             // a calcularlo, como hacía antes.
             if ( $tipoobje == "OIPL" ) {
               // El año contraido es el año de la fecha de escritura
               $fechdeve = sql("SELECT oipl.fechdeve FROM oipl WHERE oipl.codioipl = $codiobje");
               if ( $fechdeve ) { $anio = substr( $fechdeve, 0, 4 ); }
             } elseif ( $tipoobje == "OIBIURBA" ) {
               if ( $codiobje != "" ) {
                 // El año contraido es el año del valor catastral
                 $aniovalo = sql( "SELECT oibiurba.aniovalo FROM oibiurba WHERE oibiurba.codiinmu = $codiobje" );
                 $anio = $aniovalo;
               } else {
                 // En liquidaciones periodicas, muestro como contraido, el año actual,
                 // pero a la hora de calcular la cuota, el año contraido vendra dado
                 // por el campo aniovalo de cada objeto.
                 $anio = date('Y', time()); 
               }
             } elseif ( $tipoobje == "OIBIRUST" ) {
               if ( $codiobje != "" ) {
                 // El año contraido es el año del valor catastral
                 $aniovalo = sql( "SELECT oibirust.aniovalo FROM oibirust WHERE oibirust.codioibirust = $codiobje" );
                 $anio = $aniovalo;
               } else {
                 // En liquidaciones periodicas, muestro como contraido, el año actual,
                 // pero a la hora de calcular la cuota, el año contraido vendra dado
                 // por el campo aniovalo de cada objeto.
                 $anio = date('Y', time()); 
               }
             } elseif ( $tipoobje == "OIAE" ) {
               if ( $codiobje != "" ) {
                 // El año contraido es el ejercicio de efectividad
                 $ejerefec = sql( "SELECT oiae.ejerefec FROM oiae WHERE oiae.codioiae = $codiobje" );
                 $anio = $ejerefec;
               } else {
                 // En liquidaciones periodicas, muestro como contraido, el año actual,
                 // pero a la hora de calcular la cuota, el año contraido vendra dado
                 // por el campo ejerefec de cada objeto.
                 $anio = date('Y', time()); 
               }
             } elseif ( $tipoobje == "OICI" ) {
               // El año contraido es el año en el que empieza la obra
               if ( $codiobje != "" ) {
                 // Para un objeto concreto, el año contraido es el año en el que empieza la obra
                 $fechinic = sql( "SELECT oici.fechinic FROM oici WHERE oici.codioici = $codiobje" );
                 $anio = substr( $fechinic, 0, 4 );
               } else {
                 // En liquidaciones periodicas, muestro como contraido, el año actual,
                 // pero a la hora de calcular la cuota, el año contraido vendra dado
                 // por el campo ejerefec de cada objeto.
                 $anio = date('Y', time()); 
               }
             } elseif ( $tipoobje == "SANCTRAF"){
               if ( $codiobje != ""){
                 $anio = sql( "SELECT osantraf.anioexpe FROM osantraf WHERE osantraf.codiosan = $codiobje" );
               } else {
                 $anio = date('Y', time()); 
               }
             } elseif ( $tipoobje == "OIBIURBAHIST" ) {
               if ( $codiobje != "" ) {
                 // El año contraido es el año del valor catastral
                 $aniovalo = sql( "SELECT oibiurbahist.aniovalo FROM oibiurbahist WHERE oibiurbahist.codisequ = $codiobje" );
                 $anio = $aniovalo;
               } else {
                 // En liquidaciones periodicas, muestro como contraido, el año actual,
                 // pero a la hora de calcular la cuota, el año contraido vendra dado
                 // por el campo aniovalo de cada objeto.
                 $anio = date('Y', time()); 
               }
             } else {
                 if ( empty($anio) ) $anio = date('Y', time()); 
             }
           }
        ?>
        <td><input type='text' name='anio' value='<? print $anio; ?>' size='4' maxlength='4' 
        <? if ( $tipoobje == "OIPL" || $tipoobje == "OICI" || 
                $tipoobje == "OIBIURBA" || $tipoobje == "OIBIRUST" ) {
             print "readonly"; 
           } 
        ?> ></td>
      </tr>
      </table>
    </td>
  </tr>
  
  <!-- Fechas inicial y final de ingreso en Voluntaria. Se preguntan cuando la liquidacion es periodica-->
<?
  if ( $modoliqu == "PER"){
    if ($concdato[liqudivi] == 1)
    $txt_plazingr = 'Plazo de ingreso para las liquidaciones que no se dividen';
?>
  <tr>
    <td class="izqform">Fechas de plazo<br>de ingreso<br>en Voluntaria</td>
    <td class="derform">
      <table cellspacing=1 cellpadding=2 border=0>
      <tr>
        <td></td>
        <td>Fecha inicial</td>
        <td>Fecha final</td>
        <td></td>
      </tr>
      <tr>
    <? if ($concdato[liqudivi] == 1) { 
         print "<td> 1er. plazo </td>";
       } else {
         print "<td> </td>";
       }
    ?>
        <td>
          <? cajatexto ('fechinicvolu', $fechinicvolu, 'fech', ($modoliqu != "PER"?'disabled':'')); ?>
        </td>
        <td>
          <? cajatexto ('fechfinavolu', $fechfinavolu, 'fech', ($modoliqu != "PER"?'disabled':'')); ?>
        </td>
        <td> <? print ($concdato[plazingrvolu] == 1?$txt_plazingr:''); ?> </td>      
      </tr>
    <?
      if ($concdato[liqudivi] == 1) {
        print "<td> 2do. plazo </td>";
      ?>
        <td>
          <? cajatexto ('fechinicvolu_2', $fechinicvolu_2, 'fech', ($modoliqu != "PER"?'disabled':'')); ?>
        </td>
        <td>
          <? cajatexto ('fechfinavolu_2', $fechfinavolu_2, 'fech', ($modoliqu != "PER"?'disabled':'')); ?>
        </td>
        <td> <? print ($concdato[plazingrvolu] == 2?$txt_plazingr:''); ?> </td>
      </tr>
    <?
      }
    ?>
      </table>
    </td>
  </tr>
<?
  }
  if ($codiobje != ""){
?>
  <tr>
    <td class="izqform">Objeto Tributario</td>
    <td class="derform">
      <table width="100%" cellspacing=1 cellpadding=2 border=0>
      <tr>
        <td>Código</td>
        <td>Referencia</td>
      </tr>
      <tr>
        <? if ( $codiobje != "" ) : 
             // Cada concepto identifica a sus objetos con diferentes campos
             if ( $tipoobje == "OICV" ) { 
               $camp1 = "matr";  $camp2 = "bast";     $tabl = "oicv"; $camp3 = "codioicv"; 
             }
             if ( $tipoobje == "OTPP" ) { 
               $camp1 = "abre";  $camp2 = "refetasa"; $tabl = "otpp"; $camp3 = "codiotpp"; 
             }
             if ( $tipoobje == "OIPL" ) { 
               $camp1 = "trim(both from substr(nota.nomb,1,3))";  
               $camp1 .= "||trim(both from substr(cast(fechdeve as varchar),1,4))";  
               $camp1 .= "||trim(both from prot)";  
               $camp1 .= "||vari";  
               $camp2 = "''"; $tabl = "oipl, nota"; $camp3 = "codioipl"; 
             }
             if ( $tipoobje == "OIBIURBA") { 
               $camp1 = "refecata||numecarg||caracont";  $camp2 = "''";  $camp3 = "oibiurba.codiinmu";
               $tabl = "oibiurba inner join inmu on inmu.codiinmu = oibiurba.codiinmu";
             }
             if ( $tipoobje == "OIBIURBAHIST") { 
               $camp1 = "refecata||numecarg||caracont";  $camp2 = "''";  $camp3 = "oibiurbahist.codisequ";
               $tabl = "oibiurbahist";
             }
             if ( $tipoobje == "OIBIRUST" ) { 
               $camp1 = "numeorde";  $camp2 = "''";  $camp3 = "codioibirust";
               $tabl = "oibirust";
             }
             if ( $tipoobje == "OIAE" ) { 
               $camp1 = "numerefe";  $camp2 = "''";     $tabl = "oiae"; $camp3 = "codioiae"; 
             }
             if ( $tipoobje == "OICI" ) { 
               $camp1 = "anioexpe||'-'||lpad(cast(numeexpe as varchar),6,'0')||'-'||tipoobra.abre";  $camp2 = "''";
               $tabl = "oici, subxobra, tipoobra"; $camp3 = "codioici"; 
             }
             if ( $tipoobje == "SANCTRAF" ) {
               $query = "SELECT tipoobje.campabre FROM tipoobje WHERE tipoobje.tipoobje = '$tipoobje'";
               $resp = sql($query);
               if ($resp){
                  $camp1 = $resp;
                  $camp2 = "''";
                  $camp3 = "codiosan";
                  $tabl = "osantraf";
               }
             }
             $query = "SELECT ".$camp1." as obje, ".$camp2." as refe FROM ";
             $query .= $tabl." WHERE ".$camp3." = '$codiobje'";
             if ( $tipoobje == "OIPL" ) { 
               $query .= " AND nota.codinota = oipl.codinota ";
             }
             if ( $tipoobje == "OICI" ) { 
               // Concateno en el query, las relaciones de la tabla oici con subxobra y tipoobra
               $query .= " AND oici.codisubxobra = subxobra.codisubxobra
                           AND subxobra.codiobra = tipoobra.codiobra
                           AND tipoobra.codiayun = $codiayun";
             }
             $resp  = sql( $query );
             if ( is_array( $resp) ) {
               $obje = each( $resp );
               $obje = $obje[value];
             }
        ?>
        <td><input type='text' name='obje' value='<? print $obje[obje]; ?>' size='25' disabled></td>
        <td><input type='text' name='refe' value='<? print $obje[refe]; ?>' size='25' disabled></td>

        <? else : ?>

        <td><input type='text' name='obje' value='' disabled></td>
        <td><input type='text' name='refe' value='' disabled></td>

        <? endif; ?>
      </tr>
      </table>
    </td>
  </tr>
<?
     if ($tipoobje == 'SANCTRAF'){    
?>
  <tr>
    <td class="izqform">Beneficio tributario</td>
    <td class="derform">
      <table width="100%" cellspacing=1 cellpadding=2 border=0>
      <tr>
        <td>
        Aplicar el importe reducido por pronto pago a fecha:
        </td>
        </tr>
        <tr>
        <td>
<?
         if ($fechaplibene == '')
         	$fechaplibene = date('d-m-Y',time());
     	 	cajatexto('fechaplibene', $fechaplibene, 'fech');
?>        
        </td>
      </tr>
      </table>
    </td>
  </tr>
<?     	
     }
     
  }else{
?>
    <? // Alteraciones catastrales según la carga del DOC-DGC. (Solo en Urbana y Rústica). ?>
    <? if ( $tipoobje == 'OIBIURBA' || $tipoobje == 'OIBIRUST' ) { ?>
      <tr>
        <td class="izqform">Fechas</td>
        <td class="derform">
          <table width="100%" cellspacing=1 cellpadding=2 border=0>
            <tr>
              <td>
                <input type='checkbox' name='oibidocdgc' <? if ($oibidocdgc) print "CHECKED"; ?>>
                Sólo alteraciones catastrales (DOC-DGC)
              </td>
            </tr>
          </table>
        </td>
      </tr>
    <? } else { ?>
      <input type='hidden' name='oibidocdgc' value = ''>
    <? } ?>


  <tr>
    <td class="izqform">Fechas</td>
    <td class="derform">
      <table width="100%" cellspacing=1 cellpadding=2 border=0>
      <tr>
        <td>Solicitud</td><td> <? muesrang ('soli', 'fech', $soliselect,
                                                         $solitextbox1,
                                                         $solitextbox2) ?>
        </td>
      </tr>
      <tr>
        <td>Alta</td><td> <? muesrang ('alta', 'fech', $altaselect,
                                                         $altatextbox1,
                                                         $altatextbox2) ?>
        </td>
      </tr>
      <tr>
        <td>Último Trámite</td><td> <? muesrang ('modi', 'fech', $modiselect,
                                                         $moditextbox1,
                                                         $moditextbox2) ?>
        </td>
      </tr>
      </table>
    </td>
  </tr>
<?
  }
  
  if ($aux_queryextra != '') {
    // Para saber el numero de registros cuanto el numero de elementos que existen
    // dentro del query, es decir, el numero de comas ','
    $nume_regis = substr_count($aux_queryextra, ',') + 1;
    $b_plural_s = (($nume_regis > 1)?'s':'');
    $b_plural_n = (($nume_regis > 1)?'n':'');
    
    print " <tr>
              <td class='izqform'>Información</td>
              <td class='derform'>
                <table width'100%' cellspacing=1 cellpadding=2 border=0>
                <tr>
                  <td style='font-size: 10pt;'> Se procederá a la liquidación de $nume_regis registro$b_plural_s preseleccionado$b_plural_s. </td>
                </tr>
                </table>
              </td>
            </tr>";
    
  } 
?>

  <!-- Importes que se añaden o descuentan del total de la deuda resultante -->
  <?
    if ( substr( $modoliqu, 0, 1 ) != "D" ) {
      // Estos valores solo se permiten si la liquidacion es directa
      $impoingr = "";
      $refeingr = "";
      $fechingr = "";
    }
  ?>

<?
  if ($codiobje != ""){
?>
  <tr id='ingrcuen'>
    <td class="izqform">Ingreso a cuenta</td>
    <td class="derform">
      <table width="100%" cellspacing=1 cellpadding=2 border=0>
      <tr>
        <td>Importe</td>
        <td>Fecha</td>
      </tr>
      <tr>
        <td>
        <input type="text" size="20" maxlength="20" name="impoingr" value="<? print $impoingr; ?>"
        <? if ( $modoliqu == "PER" || $modoliqu == "AUT" ) {
             print " disabled ";
           }
        ?>
        >&euro;
        </td>
        <td>
        <input type="text" size="10" maxlength="10" name="fechingr" value="<? print $fechingr; ?>"
        <? if ( $modoliqu == "PER" || $modoliqu == "AUT" ) {
             print " disabled ";
           }
        ?>
        >
        </td>
      </tr>
      <tr>
        <td>Referencia</td>
      </tr>
      <tr>
        <td>
        <input type="text" size="9" maxlength="9" name="refeingr" value="<? print $refeingr; ?>"
        <? if ( $modoliqu == "PER" || $modoliqu == "AUT" ) {
             print " disabled ";
           }
        ?>
        >
        </td>
      </tr>
      </table>
  </tr>
<?
  }
if ($tipoobje == 'SANCTRAF'){
   echo "<script>";
   echo "document.getElementById('ingrcuen').style.display = 'none';";
   echo "</script>";
}
?>
</table>
</center>

</form>

<?

  if ( $opci == "Deuda" ) {
    // Una vez calculada la deuda, no se permite calcular de nuevo lo mismo.
    // Para ello, desactivo todos los campos del formulario, con lo que se muestra lo que se ha liquidado
    print "<SCRIPT>desatodo();</SCRIPT>\n";

    // Nuevo formulario
    print "<form name='carg' method='post' action='carg.php'>\n";

    $soliquery = "";
    $altaquery = "";
    $modiquery = "";
    if ( $solitextbox1 != "" ) {
      $soliquery = " AND " . muesrangquery( 'soli', 'camprempl', 'fech' );
    }
    if ( $altatextbox1 != "" ) {
      $altaquery = " AND " . muesrangquery( 'alta', 'camprempl', 'fech' );
    }
    if ( $moditextbox1 != "" ) {
      $modiquery = " AND " . muesrangquery( 'modi', 'camprempl', 'fech' );
    }

    // Cálculo de la deuda
    $vect = deuda ($codiayun, $conc, $codiobje, $codiconc, $tipoobje, $modoliqu, $periliqu, $anio, $soliquery, $altaquery, $modiquery, euro2cent($impoingr), $oibidocdgc, $fechaplibene, $codiusua, guardarFecha($fechinicvolu), guardarFecha($fechfinavolu), true, -1, guardarFecha($fechinicvolu_2), guardarFecha($fechfinavolu_2), $liqudivi, $aux_queryextra);

    if ($modoliqu != 'PER'){
	    // Contador del total de entradas en el vector y de entradas con error en la lquidacion
	    // Se sabe si hubo error en la liquidacion porque el campo cuot o el deud del vector es -1
	    $contvect = $conterro = 0;
	    // Estos contadores se usan para no mostrar el boton de Confirmar Cargo cuando todos los 
	    // objetos han dado error en la liquidación o cuando no hay objetos a liquidar
	    if ( is_array( $vect ) ) {
	      foreach( $vect as $v) {
	        $contvect++;
	        if ( ( $v[cuot] == -1 ) || ( $v[deud] == -1 ) ) {
	          $conterro++;
	        }
	      }
	    }
	
	    // Mostrar el número de objetos a liquidar y los errores producidos
	    print "Se calculó la deuda de $contvect";
	    if ( $contvect == 1 )  { print " objeto,"; }
	    else { print " objetos,"; }
	    if ( $contvect > 0 ) { 
	      if ( $conterro == 0 ) { print " ningún error."; }
	      else { 
	        print " ".$conterro; 
	        if ( $conterro == 1 )  { print " error."; }
	        else { print " errores."; }
	      }
	    }
	
	    // Permito que siga con la liquidación , solo si no se produjeron errores
	    // en ningun objeto
	    if ( ( $conterro == 0 ) && ( $contvect > 0 ) ) {
	      // Ningún objeto en $vect dio error en la liquidación
	
	      print "<center>\n<br>\n";
	      // Checkbox que indica si se generan o no cargos con nif del propio ayuntamiento
	      print "<input type='checkbox' name='nifxayun'>\n";
	      print "Generar cargos con NIF del propio ayuntamiento<br>\n";
	  
	      // Numero de registros a insertar en la tabla carg
	      print "<input type='hidden' name='contobje' value='".count($vect)."'>\n";
	  
	      // Campos comunes a todos los objetos a liquidar
	      print "<input type = 'hidden' name = 'codiayun' value = '$codiayun'>\n";
	      print "<input type = 'hidden' name = 'modoliqu' value = '$modoliqu'>\n";
	      print "<input type = 'hidden' name = 'periliqu' value = '$periliqu'>\n";
	      print "<input type = 'hidden' name = 'anio' value = '$anio'>\n";
	      print "<input type = 'hidden' name = 'tipoobje' value = '$tipoobje'>\n";
	      print "<input type = 'hidden' name = 'fechinicvolu' value = '$fechinicvolu'>\n";
	      print "<input type = 'hidden' name = 'fechfinavolu' value = '$fechfinavolu'>\n";
	      print "<input type = 'hidden' name = 'impoingr' value = '".euro2cent($impoingr)."'>\n";
	      print "<input type = 'hidden' name = 'refeingr' value = '$refeingr'>\n";
	      print "<input type = 'hidden' name = 'fechingr' value = '$fechingr'>\n";
	      print "<input type = 'hidden' name = 'oibidocdgc' value = '$oibidocdgc'>\n";
              print "<input type = 'hidden' name = 'aux_queryextra' value='$aux_queryextra'>";
	
	      print "<br>\n";
	      // Vector en el que se envia el resumen de los cargos a generar
	      $serivect =  serialize($vect) ;
	
	      // Las siguientes lineas se emplean para pasar el vector de objetos, no por el formulario
	      // como se hacia antes, sino a traves de la base de datos. 
	      // Lo tuve que hacer asi porque antes con 3600 objetos de basura funcionaba, 
	      // pero con 12mil coches no.
	      // El vector se guarda en una tabla liquvect con dos campos: un identificador unico, y un campo 
	      // TEXT donde se guarda el vector tras convertirlo en una ristra (serialize), y haberla
	      // codificado (urlencode). La tabla tambien tiene un campo fecha actual.
	      // Lo que se pasa por formulario es el identificador unico
	

	      $serivect = urlencode($serivect);
	      $query = "LOCK TABLE liquvect IN ACCESS EXCLUSIVE MODE;
	      		 	INSERT INTO liquvect (serivect) VALUES ('$serivect');
	      		 	SELECT max(codisequ) FROM liquvect";
	      $codisequ = sql( $query );
	      print "<input type = 'hidden' value = '$codisequ' name = 'codisequ'>\n";
	      //print "<input type = 'hidden' value = '$serivect' name = 'vect'>\n";
	  
	      // Boton para confirmar el cargo a generar que se muestra en el resumen:
	      // Hay dos botones de envio del formulario. En ambos se genera el cargo y 
	      // se inserta en la tabla carg, pero solo en un se reenvia a la pagina del
	      // cobro del cargo recien generado. Para saber que boton se ha pulsado
	      // tengo el campo oculto 'cobr' que se modifica con un onClick segun el boton pulsado
	      print "<input type='hidden' name='cobr' value='0'>\n";
	      if ( $modoliqu != "PER" ) {
	        print "<input type='submit' value='Confirmar y\ncobrar el cargo' onclick='form.cobr.value=1'>\n";
	      }
	      if ( $modoliqu != "AUT" && $tipoobje != 'SANCTRAF') {
	        print "<input type='submit' value='Confirmar y NO\ncobrar el cargo' onclick='form.cobr.value=2'>\n";
	      }
	  
	      print "<br><br>Las liquidaciones erróneas no se consideran en el total de la cuota 
	             y de la deuda.\n<br>";
	  
	    } else {
	      print "<br><br>No se puede continuar con la generación del cargo.<br>\n";
	    }
	
	    // Boton para cancelar la liquidacion y volver a la pagina del objeto, si
	    // la liquidacion fue invocada desde la pagina de un objeto concreto, o a
	    // la pagina inicial de la liquidacion, si se invoco desde el menu
	    print "<br>\n";
	    print "<input type='button' name='volv' value='Cancelar Cargo'";
	    if ($volvurlx != '' && $aux_queryextra == ''){
	          print " onClick='if (top.area) top.area.location=\"".$volvurlx."\"; else if (top) top.location = \"".$volvurlx."\";'>\n";
	    }else{
	          print " onClick='if (opener) window.close();'>\n";
	    }
	    
	    print "<input type='hidden' name='volvurlx' value='$volvurlx'>";
    }    
    print "</form>\n";
  }
?>

</center>

<?

 include "comun/pie.inc"; ?>

</body>
</html>
                                                                                         

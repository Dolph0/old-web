<?

function moditerc( $coditerc, $nomb1, $nomb2, $dire, $dire1, $dire2, $fech, $usuamodi, $priomodi) {
  // Esta funcion modifica los campos nombre y direccion de un tercero

  // nomb1 es el nombre que tiene el sujeto en el formulario
  // nomb2 es el nombre que tiene el sujeto en la base de datos
  // $dire es un caracter que indica si el sujeto tenia una direccion estructurada o no
  // dire1 es la direccion fiscal del sujeto en el formulario
  // dire2 es la direccion fiscal del sujeto en la base de datos
  $query = "";
  if ( $nomb1 != $nomb2 ) { 
    // Se modifica el nombre del tercero

    $query .= "UPDATE tercdato SET nomb='$nomb1', fechmodi='$fech', 
               usuamodi='$usuamodi',priomodi='$priomodi'
               WHERE coditerc ='$coditerc';";
  }
  if ( $dire1 != $dire2 ) { 
    // Se modifica la direccion

    $query .= "UPDATE tercdato SET dire='n', fechmodi='$fech', 
               usuamodi='$usuamodi',priomodi='$priomodi'
               WHERE coditerc ='$coditerc';";
    if ( $dire == "n" ) {
      // El tercero tenia una direccion no estrucutrada
      $query .= "UPDATE direnoes SET nomb = '$dire1' WHERE coditerc = '$coditerc';";
    } else {
      // El tercero tenia una direccion estructurada
      $query .= "DELETE FROM direestr WHERE coditerc = '$coditerc';
                 INSERT INTO direnoes VALUES ('$coditerc', '$dire1');";
    }
  }
  if ( $query != "" )  sql( $query );
}


// Devuelve la personalidad de un sujeto a partir del NIF, es
// decir, F, J ó E.
function devupers ($nifx)
{
    if (ereg("^[0-9]{8}[A-Z]$",$nifx)) return 'F';
    if (ereg("^[A-H][0-9]{2}[0-9]{6}$",$nifx)) return 'J';
    if (ereg("^[P-S][0-9]{2}[0-9]{5}[A-Z]$",$nifx)) return 'E';
    if (ereg("^X[0-9]{7}[A-Z]$",$nifx)) return 'F'; // Extranjero
    if (ereg("^N[0-9]{3}[0-9]{4}([A-Z]|[0-9])$",$nifx)) return 'J';
    // aunque esta última puede ser también Entidad
    // Si no pasó por ninguna -> error
    return '';
}


function tituobje( $coditerc, $nifx, $nomb, $dire, $priomodi, $usuamodi ) {
  // Esta funcion devuelve el coditerc del tercero que se establece como
  // titular de un objeto concreto, o -1 si hubo error

  if ( $nifx == "" || $nomb == "" || $dire == "" || $priomodi == "" || $usuamodi == "" ) {
    // Estos campos no pueden estar en blanco
    return -1;
  }

  // Determinar la personalidad (Entidad, Fisica o Juridica) del tercero
  $pers = devupers( $nifx );
  $vect = array("nifx" => $nifx, "nomb" => $nomb, "dire" => $dire, "pers" => $pers,
                "codipost" => "", "loca" => "", "codimuni" => "", "tel1" => "",
                "tel2" => "", "direcorr" => "", "aparcorr" => "", "pais" => "" );

  // Fecha de última modificación
  $fech = date ("Y-m-d",time());

  
  if ( $coditerc != "" ) {
    // El tercero que se establece como titular, se buscó en la base
    // de datos, utilizando la funcion listsuje

    // Buscar los datos del tercero para comprobar si hay diferencias entre
    // los datos que se muestran por pantalla y los del sujeto en la base de datos
    $query = "SELECT nifx, T.nomb as nombsuje, dire, DN.nomb as nombnoes, 
              DE.nomb as nombestr, nume, letr, esca, plan, puer 
              FROM ( ( tercdato T LEFT JOIN direnoes DN ON T.coditerc = DN.coditerc ) 
              LEFT JOIN direestr DE ON T.coditerc = DE.coditerc ) WHERE T.coditerc = '$coditerc'";
    $resu = sql( $query );

    if ( is_array( $resu ) ) {
      $dato = each( $resu );
      $dato = $dato[value];
      // Determino la direccion segun sea estructurada o no
      if ( $dato[dire] == "e" ) {
        $direterc = $dato[nombnoes].$dato[nume].$dato[letr].$dato[esca].$dato[plan].$dato[puer];
      } else {
        $direterc = $dato[nombnoes];
      }

      if ( $dato[nifx] != $nifx ) {
        // El NIF que tiene ese sujeto en el formulario es diferente del que
        // tiene ese sujeto en la base de datos. Lo que ha ocurrido es que se
        // buscó el tercero en la base de datos usando la funcion listsuje,
        // pero despues se modifico el campo NIF del formulario

        // Buscar el NIF en la base de datos
        $query = "SELECT T.coditerc, nifx, T.nomb as nombsuje, dire, DN.nomb as nombnoes, 
                  DE.nomb as nombestr, nume, letr, esca, plan, puer 
                  FROM ( ( tercdato T LEFT JOIN direnoes DN ON T.coditerc = DN.coditerc ) 
                  LEFT JOIN direestr DE ON T.coditerc = DE.coditerc ) WHERE nifx = '$nifx'";
        // Si ese NIF no existe en la base de datos, se considera que inserta
        // un nuevo tercero. Si ya existe, se considera que esta tratando el
        // tercero dado por el NIF y no por el campo coditerc

        $resu2 = sql( $query );
        if ( is_array( $resu2 ) ) {
          // El NIF ya existe en la base de datos. Se considera que tratamos
          // el tercero que viene dado por el NIF y no por el coditerc
          $dato2 = each( $resu2 );
          $dato2 = $dato2[value];
          // Determino la direccion segun sea estrucuturada o no
          if ( $dato2[dire] == "e" ) {
            $direterc = $dato2[nombnoes].$dato2[nume].$dato2[letr].$dato2[esca].$dato2[plan].$dato2[puer];
          } else {
            $direterc = $dato2[nombnoes];
          }
          // Si el nombre o direccion de ese tercero ha cambiado, entonces se
          // actualizan esos datos segun la prioridad de modificacion de datos de
          // sujetos de este script
          if ( $nomb != $dato2[nombsuje] || $dire != $direterc ) {
            // Peso de la prioridad que tiene el tercero insertado
            $pri1 = sql ("SELECT peso FROM tercprio WHERE codiruti='$dato2[priomodi]'");
            // Peso de la prioridad que tiene el tercero a insertar
            $pri2 = sql ("SELECT peso FROM tercprio WHERE codiruti='$priomodi'");
            // La última rutina que modificó tiene más prioridad que la nueva
            if ($pri1<$pri2) {
              // La prioridad de la última es menor o igual que la nueva. Actualizar
              moditerc( $dato2[coditerc], $nomb, $dato2[nombsuje], $dato2[dire], $dire, $direterc, $fech, $usuamodi, $priomodi);
            }
                    
          }
          return $dato2[coditerc];

        } else {
          // EL NIF no existe en la base de datos. Se considera que estamos
          // insertando un nuevo tercero
          // Lo que se hace es crear un nuevo usuario con esos datos, y se
          // devuelve el nuevo coditerc.
          $reto = inseterc( $vect, $usuamodi, $priomodi, "", "" );
    
          if ( $reto ) {
            // Devuelvo el coditerc del nuevo tercero insertado en la base de datos
            return sql( "SELECT coditerc FROM tercdato WHERE nifx = '$nifx'" );
          } else {
            return -1;
          }

        }
      } else {

        // El NIF del tercero dado por coditerc, coincide con el NIF que tiene
        // asignado ese tercero en la base de datos
        // Si el nombre o direccion de ese tercero ha cambiado, entonces se
        // actualizan esos datos segun la prioridad de modificacion de datos de
        // sujetos de este script
        if ( $nomb != $dato[nombsuje] || $dire != $direterc ) {
          // Peso de la prioridad que tiene el tercero insertado
          $pri1 = sql ("SELECT peso FROM tercprio WHERE codiruti='$dato[priomodi]'");
          // Peso de la prioridad que tiene el tercero a insertar
          $pri2 = sql ("SELECT peso FROM tercprio WHERE codiruti='$priomodi'");
          // La última rutina que modificó tiene más prioridad que la nueva
          if ($pri1<$pri2) {
            // La prioridad de la última es menor o igual que la nueva. Actualizar
            moditerc( $coditerc, $nomb, $dato[nombsuje], $dato[dire], $dire, $direterc, $fech, $usuamodi, $priomodi);
          }
                  
        }
        return $coditerc;
      }

    } else {
      // Si llega hasta aqui es porque el sujeto se busco en la base de datos con el boton Buscar
      // del formulario, pero alguien lo ha borrado y no se encontro cuando se busco en este script

      // Hay que buscarlo por NIF y comprobar que es el mismo. Si no es el mismo, se insera 
      // el sujeto en la base de datos
    }

  } else {
    // El tercero que se establece como titular, no ha sido buscado en la base
    // de datos, utilizando la funcion listsuje, sino que directamente se
    // rellenaron los campos del titular

    // Buscar el NIF en la base de datos
    $query = "SELECT T.coditerc, nifx, T.nomb as nombsuje, dire, DN.nomb as nombnoes, 
              DE.nomb as nombestr, nume, letr, esca, plan, puer, priomodi
              FROM ( ( tercdato T LEFT JOIN direnoes DN ON T.coditerc = DN.coditerc ) 
              LEFT JOIN direestr DE ON T.coditerc = DE.coditerc ) WHERE nifx = '$nifx'";
    $resu = sql( $query );

    if ( !is_array( $resu ) ) {
      // Ese NIF no existe en la base de datos.
      // Lo que se hace es crear un nuevo usuario con esos datos, y se
      // devuelve el nuevo coditerc.
      $reto = inseterc( $vect, $usuamodi, $priomodi, "", "" );

      if ( $reto ) {
        // Devuelvo el coditerc del nuevo tercero insertado en la base de datos
        return sql( "SELECT coditerc FROM tercdato WHERE nifx = '$nifx'" );
      } else {
        return -1;
      }

    } else {
      // Ese NIF existe en la base de datos. 
      $dato = each( $resu );
      $dato = $dato[value];
      // Determino la direccion segun sea estrucuturada o no
      if ( $dato[dire] == "e" ) {
        $direterc = $dato[nombestr].$dato[nume].$dato[letr].$dato[esca].$dato[plan].$dato[puer];
      } else {
        $direterc = $dato[nombnoes];
      }

      // Si el nombre o direccion de ese tercero ha cambiado, entonces se
      // actualizan esos datos segun la prioridad de modificacion de datos de
      // sujetos de este script
      if ( $nomb != $dato[nombsuje] || $dire != $direterc ) {
        // Peso de la prioridad que tiene el tercero insertado
        $pri1 = sql ("SELECT peso FROM tercprio WHERE codiruti='$dato[priomodi]'");
        // Peso de la prioridad que tiene el tercero a insertar
        $pri2 = sql ("SELECT peso FROM tercprio WHERE codiruti='$priomodi'");
        // La última rutina que modificó tiene más prioridad que la nueva
        if ($pri1<$pri2) {
          // La prioridad de la última es menor o igual que la nueva. Actualizar
          moditerc( $dato[coditerc], $nomb, $dato[nombsuje], $dato[dire], $dire, $direterc, $fech, $usuamodi, $priomodi);
        }
                
      }
      return $dato[coditerc];
    }
  }

}
?>

<?

// Sustituye a la funcion insesuje. 

// Función que se encarga de insertar o actualizar en la tabla de 
// terceros el vector asociativo t. La variable priomodi indica 
// cúal es la prioridad de inserción que se va a emplear.
// $t, como ya se dijo antes, es un vector asociativo que debe 
// contener, si no todos los atributos de un sujeto, al menos el nif
// (nifx).
// usuamodi es el usuario que va a modificar el sujeto pasivo.
// Además, creará, si no existe, la relación entre el sujeto y el 
// objeto determinado en las variables tipoobje y codiobje, si el 
// valor del parametro tipoobje es distinto de blanco.

// En cuanto a los valores que devuelve, se trata de una función 
// lógica que retornará verdadero si logró insertar el objeto y falso 
// en caso contrario. Los posibles casos negativos son:

// - No está definido el campo nifx ($t[nifx])
// - No está definido el campo priomodi
// - No esta definido o esta a cero usuamodi 
// - La última vez que se tocó el registro le fue dado una mayor prio-
//   ridad que con la que ahora se quiere insertar.

// - Agrego $ERRORSQL para conocer el fallo que se ha producido. 
function inseterc (&$t,$usuamodi,$priomodi,$tipoobje,$codiobje)
{
 global $ERRORSQL;
  // No está definido el NIF del sujeto
  if (!isset ($t[nifx])) 
    {
     $ERRORSQL="No tiene NIF";
     return FALSE;
    }
  // No está definido el usuario que graba el registro
  if (!isset ($usuamodi) || $usuamodi==0) 
    {
    $ERRORSQL="No se conoce el usuario para guardar el registro";
    return FALSE;
    }
  // No está definida la rutina que toca el registro
  if (!isset ($priomodi)) return FALSE;
     
  // Fecha de última modificación
  $fech = date ("Y-m-d",time());
  // Se comprueba la prioridad anterior que tiene el sujeto en disco...
  if (!($prioactu=sql("SELECT priomodi FROM tercdato WHERE nifx='$t[nifx]'"))) {
    // No hay ningún registro insertado. via libre...
    // Se procede con la inserción
 
    sql ("INSERT INTO tercdato (nifx, nomb, pers, dire, codipost, loca, codimuni, tel1,
          tel2, direcorr, aparcorr, pais, fechmodi, usuamodi, priomodi) VALUES 
          ('$t[nifx]','$t[nomb]','$t[pers]','n','$t[codipost]','$t[loca]',
           '$t[codimuni]','$t[tel1]','$t[tel2]','$t[direcorr]','$t[aparcorr]',
           '$t[pais]','$fech','$usuamodi','$priomodi')"); 
    $reto = TRUE; 
  }
  else {  // Hay que comprobar las prioridades

    $pri1 = sql ("SELECT peso FROM tercprio WHERE codiruti='$prioactu'"); 
    $pri2 = sql ("SELECT peso FROM tercprio WHERE codiruti='$priomodi'");

    // La última rutina que modificó tiene más prioridad que la nueva
    if ($pri1>$pri2)
       $reto = FALSE;
    else { // La prioridad de la última es menor o igual que la nueva
      // Actualizar
      sql ("UPDATE tercdato SET nomb='$t[nomb]',pers='$t[pers]',dire='n',
            codipost='$t[codipost]',loca='$t[loca]',codimuni='$t[codimuni]',
            tel1='$t[tel1]',tel2='$t[tel2]',direcorr='$t[direcorr]',
            aparcorr='$t[aparcorr]',pais='$t[pais]',fechmodi='$fech', 
            usuamodi='$usuamodi',priomodi='$priomodi' WHERE nifx='$t[nifx]'");
      $reto = TRUE; 
    }
  }

  // Se coge el código del tercero para insertar la relación con el objeto,
  // y para insertar su direccion no estructurada en la tabla direnoes
  $coditerc = sql ("SELECT coditerc FROM tercdato WHERE nifx='$t[nifx]'");

  // Inserto la direccion si se ha hecho la insercion o actualizacion del sujeto
  if ( $reto == TRUE ) {
    // Antes de insertar si direccion, borro la direccion que tenia antes
    // Borro tanto la posible direccion no estructura como la estructurada, que podia tener antes
    sql("DELETE FROM direnoes WHERE coditerc='$coditerc'; DELETE FROM direestr WHERE coditerc='$coditerc'");
    sql("INSERT INTO direnoes VALUES ('$coditerc','$t[dire]')");
  }

  // La relacion con algun objeto se crea si el parametro tipoobje no está en blanco
  // Si estubiera en blanco, se trata de una insercion de un sujeto, 
  // sin necesidad de crear una relacion con algun objeto
  if ( $tipoobje != "" ) {
    // Se toma la figura del contribuyente principal
    $codifigu = sql ("SELECT codifigu FROM tercfigu WHERE descfigu='CONTRIBUYENTE PRINCIPAL'");
    // Si no existe la relación se crea
    if (!sql ("SELECT * FROM tercobje WHERE coditerc='$coditerc' AND 
               codifigu='$codifigu' AND tipoobje='$tipoobje' AND codiobje='$codiobje'")) {
       sql ("INSERT INTO tercobje (coditerc,codifigu,tipoobje,codiobje) VALUES 
            ('$coditerc','$codifigu','$tipoobje','$codiobje')");
    }
  }
  
  return $reto;
}
?>

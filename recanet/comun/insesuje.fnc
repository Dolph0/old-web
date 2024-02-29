<?
// Función que se encarga de insertar o actualizar en la tabla de 
// sujetos pasivos  el vector asociativo dato. La variable tipoprio 
// indica cúal es la prioridad de inserción que se va a emplear.
// dato, como ya se dijo antes, es un vector asociativo que debe 
// contener, si no todos los atributos de un sujeto, al menos el nif
// (nifx) y el usuario que modifica el sujeto (usuamodi).
// Si estas concidiones no se cumplen no se insertará nada. 
// En cuanto a los campos codisuje y fechmodi en caso de existir,
// serán ignorados ya para primero se tomará el autoincremento y 
// para el segundo la fecha actual que se calculará dentro de la 
// función.

// En cuanto a los valores que devuelve, se trata de una función 
// lógica que retornará verdadero si logró insertar el objeto y falso 
// en caso contrario. Los posibles casos negativos son:

// - No está definido el campo nifx ($dato[nifx])
// - No está definido el campo tipoprio 
// - La última vez que se tocó el registro le fue dado una mayor prio-
//   ridad que con la que ahora se quiere insertar.

function insesuje ($dato,$tipoprio)
{
  // No está definido el NIF del sujeto
  if (!isset ($dato[nifx])) return FALSE;

  // No está definido el usuario que graba el registro
  if (!isset ($dato[usuamodi])) return FALSE;

  // No está definida la rutina que toca el registro
  if (!isset ($tipoprio)) return FALSE;

  // Fecha de última modificación
  $fech = date ("Y-m-d",time());

  // Se comprueba la prioridad anterior que tiene el sujeto en disco...
  if (!($prioactu=sql("SELECT priomodi FROM suje WHERE nifx='$dato[nifx]' AND codiayun='$dato[codiayun]'"))) {
    // No hay ningún registro insertado. via libre...
    // Se procede con la inserción
 
    sql ("INSERT INTO suje (codiayun, nifx, nomb, pers, dire, codipost, loca, codimuni, usuamodi, priomodi, fechmodi) VALUES ('$dato[codiayun]','$dato[nifx]','$dato[nomb]','$dato[pers]','$dato[dire]','$dato[codipost]','$dato[loca]','$dato[codimuni]','$dato[usuamodi]','$tipoprio','$fech')");
  }
  else {  // Hay que comprobar las prioridades

    $pri1 = sql ("SELECT prio FROM priosuje WHERE tipoprio='$abreanti'"); 
    $pri2 = sql ("SELECT prio FROM priosuje WHERE tipoprio='$abreprio'");

    // La última rutina que modificó tiene más prioridad que la nueva
    if ($pri1<$pri2)
       return FALSE;
    else { // La prioridad de la última es menor o igual que la nueva
      // Actualizar
      sql ("UPDATE suje SET nomb='$dato[nomb]',
            pers='$dato[pers]',dire='$dato[dire]',codipost='$dato[codipost]',
            loca='$dato[loca]',codimuni='$dato[codimuni]',
            usuamodi='$dato[usuamodi]',
            priomodi='$tipoprio',fechmodi='$fech' WHERE nifx='$dato[nifx]' AND codiayun='$dato[codiayun]'");

    }
  }
  return TRUE;
}
?>

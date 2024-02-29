<?
// Funci�n que se encarga de insertar o actualizar en la tabla de 
// sujetos pasivos  el vector asociativo dato. La variable tipoprio 
// indica c�al es la prioridad de inserci�n que se va a emplear.
// dato, como ya se dijo antes, es un vector asociativo que debe 
// contener, si no todos los atributos de un sujeto, al menos el nif
// (nifx) y el usuario que modifica el sujeto (usuamodi).
// Si estas concidiones no se cumplen no se insertar� nada. 
// En cuanto a los campos codisuje y fechmodi en caso de existir,
// ser�n ignorados ya para primero se tomar� el autoincremento y 
// para el segundo la fecha actual que se calcular� dentro de la 
// funci�n.

// En cuanto a los valores que devuelve, se trata de una funci�n 
// l�gica que retornar� verdadero si logr� insertar el objeto y falso 
// en caso contrario. Los posibles casos negativos son:

// - No est� definido el campo nifx ($dato[nifx])
// - No est� definido el campo tipoprio 
// - La �ltima vez que se toc� el registro le fue dado una mayor prio-
//   ridad que con la que ahora se quiere insertar.

function insesuje ($dato,$tipoprio)
{
  // No est� definido el NIF del sujeto
  if (!isset ($dato[nifx])) return FALSE;

  // No est� definido el usuario que graba el registro
  if (!isset ($dato[usuamodi])) return FALSE;

  // No est� definida la rutina que toca el registro
  if (!isset ($tipoprio)) return FALSE;

  // Fecha de �ltima modificaci�n
  $fech = date ("Y-m-d",time());

  // Se comprueba la prioridad anterior que tiene el sujeto en disco...
  if (!($prioactu=sql("SELECT priomodi FROM suje WHERE nifx='$dato[nifx]' AND codiayun='$dato[codiayun]'"))) {
    // No hay ning�n registro insertado. via libre...
    // Se procede con la inserci�n
 
    sql ("INSERT INTO suje (codiayun, nifx, nomb, pers, dire, codipost, loca, codimuni, usuamodi, priomodi, fechmodi) VALUES ('$dato[codiayun]','$dato[nifx]','$dato[nomb]','$dato[pers]','$dato[dire]','$dato[codipost]','$dato[loca]','$dato[codimuni]','$dato[usuamodi]','$tipoprio','$fech')");
  }
  else {  // Hay que comprobar las prioridades

    $pri1 = sql ("SELECT prio FROM priosuje WHERE tipoprio='$abreanti'"); 
    $pri2 = sql ("SELECT prio FROM priosuje WHERE tipoprio='$abreprio'");

    // La �ltima rutina que modific� tiene m�s prioridad que la nueva
    if ($pri1<$pri2)
       return FALSE;
    else { // La prioridad de la �ltima es menor o igual que la nueva
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

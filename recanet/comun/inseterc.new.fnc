<?
// Función que se encarga de:
// · Insertar o actualizar tanto TERCDATO como DIREESTR ó DIRENOES.
// · Creará, si no existe, la relación entre el sujeto y el objeto 
//   si el valor de $o[tipoobje] es distinto de blanco.

// $t un vector asociativo que contiene información para TERCDATO:
// $t=array(nomb     => '$nomb',   
//          nifx     => '$nifx', 
//          pers     => '$pers',
//          codipost => '$codipost',
//          loca     => '$loca',
//          codimuni => '$muni',
//          tel1     => '$tele',
//          tel2     => '$faxx',
//          direcorr => '$mail',
//          usuamodi => '$usuamodi',
//          priomodi => '$proimodi');
//  Son obligatorios nomb, nifx, usuamodi y priomodi.
 
// $d es la direccion o un vector asociativo para DIREESTR: 
// $d=array(nomb     => '$dire',
//          nume     => '$nume',             
//          letr     => '$letr',             
//          esca     => '$esca',             
//          plan     => '$plan',             
//          puer     => '$puer',             
//          siglviax => '$sigl')             

// $o un vector asociativo que contiene información para TERCOBJE: 
// $o=array(tipoobje => $tipoobje,
//          codiobje => $codiobje,
//          codifigu => $codifigu);

// En cuanto a los valores que devuelve coditerc si se inserto y falso 
// en caso contrario. 

//Los posibles casos negativos son:

// - No está definido el campo nomb 
// - No está definido el campo nifx 
// - No esta definida la dirección
// - No está definido el campo priomodi 
// - No esta definido o esta a cero usuamodi 
// - La última vez que se tocó el registro le fue dado una mayor prio-
//   ridad que con la que ahora se quiere insertar.


// - Agrego $ERRORSQL para conocer el fallo que se ha producido. 

function insetercnew ($t,$d,$o)
{
 global $ERRORSQL;

  $modificar=1;// variable para saber si se modifica la direccion o no 
 
  // No esta definido el NOMBRE del sujeto
  if (!isset ($t[nomb])) {
    $ERRORSQL="No tiene nombre";
    return FALSE;
  }
 
  // No está definido el NIF del sujeto
  if (!isset ($t[nifx])) {
     $ERRORSQL="No tiene NIF";
     return FALSE;
  }
    
  // No esta definida la direccion del sujeto
  if (!isset ($d)) {
     $ERRORSQL="No tiene direccion";
     return FALSE;
  }
   
  // No está definido el usuario que graba el registro
  if (!isset ($t[usuamodi]) || $t[usuamodi]==0) {
    $ERRORSQL="No se conoce el usuario para guardar el registro";
    return FALSE;
  }
    
  // No está definida la rutina que toca el registro
  if (!isset ($t[priomodi])) {
     $ERRORSQL="La última vez que se tocó el registro le fue dado una mayor prioridad que con la que ahora se quiere insertar";
     return FALSE;
  }
     
  // Fecha de última modificación
  $t[fechmodi] = date ("Y-m-d",time());
  // Hora de última modificación
  $t[horamodi] = date ("H:i:s",time());

  // Tipo de via si es un array la direcion es estructurada 
  // en el caso contrario queda logico que es no estructurada.
  
  if (is_array ($d)) $dire='e';
  else $dire='n';
  
  $reto='';
  // Se comprueba la prioridad anterior que tiene el sujeto en disco...
  if (!($prioactu=sql("SELECT priomodi FROM tercdato WHERE nifx='$t[nifx]'"))) {
    // No hay ningún registro insertado. via libre...
    // Se procede con la inserción
    
    // Tengo que concatenar la horamodi sino da error, nolontiendo.
    $queryy1 = "INSERT INTO tercdato (nifx, nomb, pers, dire, codipost, loca, codimuni, tel1,
               tel2, direcorr, aparcorr, pais, codipais, fechmodi, horamodi, usuamodi, priomodi) 
               VALUES ('$t[nifx]','$t[nomb]','$t[pers]','$dire','$t[codipost]','$t[loca]',
           '$t[codimuni]','$t[tel1]','$t[tel2]','$t[direcorr]','$t[aparcorr]',
               '$t[pais]','$t[codipais]','$t[fechmodi]', '".$t[horamodi]."','$t[usuamodi]','$t[priomodi]')";
    sql ($queryy1); 
    
    //Guardamos el coditerc generado.
    $reto = sujetocreado ($t[nifx]); 
    
  } else {  
    
  // Hay que comprobar las prioridades

    $pri1 = sql ("SELECT peso FROM tercprio WHERE codiruti='$prioactu'"); 
    $pri2 = sql ("SELECT peso FROM tercprio WHERE codiruti='$t[priomodi]'");
    if (!$pri2){
       $ERRORSQL="No se ha definido el peso para " . $t[priomodi];
       return FALSE;
    }else{

      // La última rutina que modificó tiene más prioridad que la nueva
      if ($pri1>$pri2) {
        $reto= sujetocreado ($t[nifx]);
        $modificar=0; // indicamos que no modifique la direccion del sujeto.
      } else { // La prioridad de la última es menor o igual que la nueva
        // Actualizar
        
        // Tengo que concatenar la horamodi sino da error, nolontiendo.        
        $queryy2 = "UPDATE tercdato SET nomb='$t[nomb]',pers='$t[pers]',dire='$dire',
              codipost='$t[codipost]',loca='$t[loca]',codimuni='$t[codimuni]',
              tel1='$t[tel1]',tel2='$t[tel2]',direcorr='$t[direcorr]',
                    aparcorr='$t[aparcorr]',pais='$t[pais]',codipais='$t[codipais]',fechmodi='$t[fechmodi]', 
                    horamodi='".$t[horamodi]."',usuamodi='$t[usuamodi]',priomodi='$t[priomodi]' 
                    WHERE nifx='$t[nifx]'";

        sql ($queryy2);
        //Guardamos el coditerc generado.
        $reto = sujetocreado ($t[nifx]); 
      }
    }
  }

  // Se coge el código del tercero para insertar la relación con el objeto,
  // y para insertar su direccion 
  if (!$reto) {
     $ERRORSQL="No se ha creado el tercero correctamente";
     return FALSE;
  } else {    
    $coditerc = $reto;
  }

  // Inserto la direccion si se ha hecho la insercion o actualizacion del sujeto
  if ( $reto!='FALSE' && $modificar) {
    // Antes de insertar la direccion, borro la que tenia antes
    // Borro tanto la posible direccion no estructura como la estructurada, que podia tener
    sql("DELETE FROM direnoes WHERE coditerc='$coditerc'; 
         DELETE FROM direestr WHERE coditerc='$coditerc'");
    
    // Comprobamos si se tiene que introducir en DIREESTR o DIRENOES.
    if ( !is_array ($d) ) {
      sql("INSERT INTO direnoes VALUES ('$coditerc','$d')");
    } else {
      // Generamos la consulta insert para direccion estructurada
      foreach ($d as $campo => $valor) {
        if ($campos) $campos.=',';
        $campos.= $campo;
        if ($valores) $valores.=',';
        $valores.= "'$valor'";
      }
       sql ("INSERT INTO direestr (coditerc,$campos) VALUES ('$coditerc',$valores)");
    }

  }

  // La relacion con algun objeto se crea si el parametro tipoobje no está en blanco
  // Si estubiera en blanco, se trata de una insercion de un sujeto, 
  // sin necesidad de crear una relacion con algun objeto
  if ( $o[tipoobje] != "" && $reto!='FALSE') {
    // Se toma la figura del contribuyente principal si no existe $o[codifigu]
    if (!isset ($o[codifigu])) {
      $o[codifigu] = sql ("SELECT codifigu FROM tercfigu WHERE abrefigu='CNP'");
    }
    
    // Borramos directamente si existe algun registro anterior que cumpla : 
    sql ("DELETE FROM tercobje WHERE codifigu='$o[codifigu]' AND tipoobje='$o[tipoobje]' AND codiobje='$o[codiobje]'");
    sql ("INSERT INTO tercobje (coditerc,codifigu,tipoobje,codiobje) VALUES 
            ('$coditerc','$o[codifigu]','$o[tipoobje]','$o[codiobje]')");
  }
  
  return $reto;
}


//consulta para conocer el coditerc generado.
function sujetocreado ($nifx)
{
  return sql ("SELECT coditerc FROM tercdato WHERE nifx='$nifx'");
}

//consulta para conocer si existe un usuario con el mismo nif.

function compsuje ($nifx,$nombsuje)
{
 
 // Preguntamos si desea modificar los datos, en el caso de que el nombre que queremos insertar sea distinto
 // al que existe, para un mismo nif.
  
  $nomb = sql ("SELECT nomb FROM tercdato WHERE nifx='$nifx'"); 

  if ($nomb!=$nombsuje && $nomb!=''){ 
       ?><script> if (confirm("El sujeto <?echo $nomb;?>  tiene el mismo nif, ¿desea modificarlo?")) {                  
                    window.open("<?print cheqroot('comun/terc.php').'?nombsuje='.$nombsuje.'&nomb='.$nomb.'&nif='.$nifx;?>","","location=no,scrollbars=no,menubars=no,toolbars=no,resizable=no,width=500,height=200");                   
                  }
       </script><?   
       return true;
  }
  return false;
}
?>

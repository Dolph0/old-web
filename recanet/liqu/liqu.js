
function limp() {
  if (form.conc)
     form.conc.value = "";
  if (form.modoliqu)
     form.modoliqu.value = "";
  if (form.periliqu)
     form.periliqu.value = "";
//  if (form.codiobje)
//     form.codiobje.value = "";
  // No borro los campos que identifican el objeto, para que si proviene de la pagina de un objeto
  // concreto, pues mantenga el codigo de ese objeto y no lo borre, a no ser que invoque de nuevo
  // la liquidacion 
  //form.obje.value = "";
  //form.refe.value = "";
  if (form.impoingr)
     form.impoingr.value = "";
  if (form.refeingr)
     form.refeingr.value = "";
  if (form.fechingr)
     form.fechingr.value = "";
  eval(limpieza);
}


function desatodo() {
  // Esta funcion desactiva todos los campos del formulario
  // Se usa tras liquidar, para no volver a aplicar la misma liquidacion dos veces
  var i;

  for ( i=0; i < form.length; i++)
    form.elements[i].disabled =true;
}

function cheq() {
  // Chequeo de valores de los campos antes de liquidar la deuda
  var mensaje = "";
  var mensaje2 = "";
  var foco = "";
  var mens = "";

  // Variables para la comprobacion del año
  var fechactu = new Date();
  var anioactu = fechactu.getFullYear();
  var aniodife;  // Año contraido minimo permitido
  if ( form.modoliqu ){
    if ( form.modoliqu.value == "PER" ) {
      // Solo permitimos liquidaciones periodicas de, como maximo, el año anterior al actual
      aniodife = anioactu -1;
    } else {
      // En las liquidaciones directas y autoliquidaciomes, 
      // permitimos liquidaciones desde 10 años en adelante
      aniodife = anioactu -10;
    }
  }
  if ( form.conc ){
    if ( form.conc.value == "" ) {
      mensaje += "* concepto/grupo\n";
      if (foco == "") {foco = "conc";}
    }
  }
  if ( form.modoliqu ){
    if ( form.modoliqu && form.modoliqu.value == "" ) {
      mensaje += "* modalidad\n";
      if (foco == "") {foco = "modoliqu";}
    }
  }
  if ( form.periliqu ){
    if ( ( form.periliqu.value == "" ) &&
         ( form.tipoliquperi && form.tipoliquperi.value != "N" ) ) {
      // OIPL y OICI no tienen periodo de liquidacion asi que se permite dejarlo en blanco
      // Esto viene dado por el campo tipoliquperi.
      mensaje += "* período\n";
      if (foco == "") {foco = "periliqu";}
    }
  }
  if ( form.anio ) {
    if ( form.anio.value == "" ) {
      mensaje += "* año contraído\n";
      if (foco == "") {foco = "anio";}
    }
    if ( ( form.anio.value != "" ) && ( form.anio.value < aniodife ) ) {
      mensaje2 += "- El año contraído no puede ser menor que "+aniodife+" \n";
      if (foco == "") {foco = "anio";}
    }
    if ( ( form.anio.value != "" ) && ( form.anio.value > anioactu ) ) {
      mensaje2 += "- El año contraído no puede ser mayor que "+anioactu+" \n";
      if (foco == "") {foco = "anio";}
    }
  }
  if ( form.modoliqu && form.fechinicvolu && form.fechfinavolu){
    if ( form.modoliqu.value == "PER" ) {
      // Si la liquidación es periódica, el usuario debe indicar 
      // las fechas del plazo de ingreso en Voluntaria
      if ( form.fechinicvolu.value == "" )  {
        mensaje += "* fecha inicial del plazo de ingreso\n";
        if (foco == "") {foco = "fechinicvolu";}
      } else {
        if ( cheqfech( form.fechinicvolu.value ) != "" ) {
          mensaje2 += "- Fecha inicial del plazo de ingreso en Voluntaria incorrecta\n";
          if (foco == "") {foco = "fechinicvolu";}
        }
      }
      if ( form.fechfinavolu.value == "" )  {
        mensaje += "* fecha final del plazo de ingreso\n";
        if (foco == "") {foco = "fechfinavolu";}
      } else {
        if ( cheqfech( form.fechfinavolu.value ) != "" ) {
          mensaje2 += "- Fecha final del plazo de ingreso en Voluntaria incorrecta\n";
          if (foco == "") {foco = "fechfinavolu";}
        }
      }
      if ( form.fechinicvolu.value != "" && form.fechfinavolu.value != "" )  {
        // Comparar las dos fechas y que la inicial sea menor que la final
        if ( compfech( form.fechinicvolu.value, form.fechfinavolu.value ) == 1 ) {
          mensaje2 += "- La fecha inicial del plazo de ingreso en Voluntaria debe ser menor que la final\n";
          if (foco == "") {foco = "fechfinavolu";}
        }
      }
    } else {
      form.fechinicvolu.value = "";
      form.fechfinavolu.value = "";
    }
  }

  if (form.liqudivi.value == 1 && form.modoliqu.value == "PER" && form.periliqu.value == 'PA') {
    // Si la liquidación es periódica, el usuario debe indicar 
    // las fechas del plazo de ingreso en Voluntaria
    if ( form.fechinicvolu_2.value == "" )  {
      mensaje += "* fecha inicial del plazo de ingreso del segundo plazo\n";
      if (foco == "") {foco = "fechinicvolu_2";}
    } else {
      if ( cheqfech( form.fechinicvolu_2.value ) != "" ) {
        mensaje2 += "- Fecha inicial del plazo de ingreso en Voluntaria incorrecta del segundo plazo\n";
        if (foco == "") {foco = "fechinicvolu_2";}
      }
    }
    if ( form.fechfinavolu_2.value == "" )  {
      mensaje += "* fecha final del plazo de ingreso del segundo plazo\n";
      if (foco == "") {foco = "fechfinavolu_2";}
    } else {
      if ( cheqfech( form.fechfinavolu_2.value ) != "" ) {
        mensaje2 += "- Fecha final del plazo de ingreso en Voluntaria incorrecta del segundo plazo\n";
        if (foco == "") {foco = "fechfinavolu_2";}
      }
    }
    if ( form.fechinicvolu_2.value != "" && form.fechfinavolu_2.value != "" )  {
      // Comparar las dos fechas y que la inicial sea menor que la final
      if ( compfech( form.fechinicvolu_2.value, form.fechfinavolu_2.value ) == 1 ) {
        mensaje2 += "- La fecha inicial del plazo de ingreso en Voluntaria debe ser menor que la final en el segundo plazo\n";
        if (foco == "") {foco = "fechfinavolu_2";}
      }
    }
  }

  // Para que no se solapen los dos plazos de ingreso
  if (form.liqudivi.value == 1 && form.modoliqu.value == "PER" && form.periliqu.value == 'PA') {
    if ( form.fechfinavolu.value != "" && form.fechinicvolu_2.value != "" && cheqfech(form.fechfinavolu.value) && cheqfech(form.fechinicvolu_2.value))  {
      // Comparar las dos fechas y que la inicial sea menor que la final
      if ( compfech( form.fechfinavolu.value, form.fechinicvolu_2.value ) == 1 ) {
        mensaje2 += "- La fecha final del primer plazo de ingreso en Voluntaria debe ser menor que la inicial en el segundo plazo\n";
        if (foco == "") {foco = "fechfinavolu_2";}
      }
    }
  }
  
  // Solo en liquidaciones directas se permiten valores en el importes de ingreso
  if ( form.modoliqu ){
    if ( form.modoliqu.value.substring(0,1) == "D" ) {
      // Es una liquidacion directa. Hay que comprobar el importe de 
      // ingreso, asi como la fecha de ingreso y la referencia
      // Ahora compruebo el formato del importe de ingreso
      if ( form.impoingr ){
      if ( !form.impoingr.value.match( "^([0-9]+\.?[0-9]{0,2})?$" ) ) {
          mensaje2 += "- Importe de ingreso\n";
          if (foco == "") {foco = "impoingr";}
      }
      }
      // Compruebo que sea una fecha valida
      if ( form.fechingr ){
      if ( form.fechingr.value != "" ) {
        mens += cheqfech(form.fechingr.value);
        if ( mens != "" ) {
          mensaje2 += "- "+mens;
          if (foco=="") { foco = "fechingr"; }
          mens = "";
        }
      }
      }
      // Compruebo el formato de la referencia del ingreso
      if ( form.refeingr ){
      if ( !form.refeingr.value.match("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿\?\.\+\*\|\(\)\$\[\{\}\^~/ªº@#·&=%`´¨>\-]*$") ) {
        mensaje2 += "- La referencia del ingreso no admite algún caracter introducido\n";
        if ( foco == "") { foco = "refeingr"; }
      }
      }
      // Compruebo que si rellena el importe del ingreso, no haya dejado en blanco 
      // la fecha y la referencia del ingreso
      if ( form.impoingr ){
        if ( form.impoingr.value != "" ) {
          if ( form.fechingr ){
          if ( form.fechingr.value == "" ) {
            mensaje += "* fecha del ingreso\n";
            if (foco == "") {foco = "fechingr";}
          }
          }
          if ( form.refeingr ){
          if ( form.refeingr.value == "" ) {
            mensaje += "* referencia del ingreso\n";
            if (foco == "") {foco = "refeingr";}
          }
          }
        }
        // Compruebo que no se quedó en blanco el importe del ingreso 
        // cuando rellenó la fecha o la referencia
        if ( form.impoingr.value == "" ) {
          if ( form.refeingr ){
          if ( form.refeingr.value != "" ) {
            mensaje += "Ha rellenado la referencia del ingreso, sin indicar el importe\n";
            if (foco == "") {foco = "impoingr";}
          }
          }
          if ( form.fechingr ){
          if ( form.fechingr.value != "" ) {
            mensaje += "Ha rellenado la fecha del ingreso, sin indicar el importe\n";
            if (foco == "") {foco = "impoingr";}
          }
          }
        }
      }
    } else {
      // No es una liquidacion directa, asi que no se permiten valores en el importe de ingreso
      if ( form.impoingr )
            form.impoingr.value = "" ;
      if ( form.refeingr )
            form.refeingr.value = "" ;
      if ( form.fechingr )
            form.fechingr.value = "" ;
    }
  }
  if ( mensaje.length > 0 || mensaje2.length >0 ) {
    if (mensaje.length > 0) {
       mensaje = "Es obligatorio rellenar los\nsiguientes campos: \n\n" + mensaje;
    }
    if (mensaje2.length > 0) {
       mensaje2 = "\nEs incorrecto el formato de\nlos siguientes campos: \n\n" + mensaje2;
    }
    alert(mensaje+mensaje2);
    form.elements[foco].focus();
    return false;
  } else return true;

}

function refrcalcinic(ayun, usua){ 
    return new AJAXRequest("POST", BASE_URL + "liqu/procestaajax.php", "dataset=calcinic&ayun=" + ayun + "&usua=" + usua + "&target=padrcalcinic");	
}

function iniciaproc(numeregi, usua){
    return new AJAXRequest("POST", BASE_URL + "liqu/procestaajax.php", "dataset=iniccalc&numeregi=" + numeregi + "&usua=" + usua + "&target=padrcalcinic");	
}

function cancelaproc(numeregi, usua){
    return new AJAXRequest("POST", BASE_URL + "liqu/procestaajax.php", "dataset=canccalc&numeregi=" + numeregi + "&usua=" + usua + "&target=padrcalcinic");	
}

function eliminaproc(numeregi, usua){
    return new AJAXRequest("POST", BASE_URL + "liqu/procestaajax.php", "dataset=elimcalc&numeregi=" + numeregi + "&usua=" + usua + "&target=padrcalcinic");	
}

function listcarg(numeregi, usua){
	abrevent(BASE_URL + "liqu/listcargcalc.php","numeregi=" + numeregi);	
}

function iniccarg(numeregi, usua){
    return new AJAXRequest("POST", BASE_URL + "liqu/procestaajax.php", "dataset=iniccarg&numeregi=" + numeregi + "&usua=" + usua + "&target=padrcalcinic");	
}

function canccarg(numeregi, usua){
    if (confirm("Se procederá a eliminar los cargos generados por este proceso. ¿Deseea continuar?")){
	    return new AJAXRequest("POST", BASE_URL + "liqu/procestaajax.php", "dataset=canccarg&numeregi=" + numeregi + "&usua=" + usua + "&target=padrcalcinic");	
	}else{
		return false;
	}
}

function elimcarg(numeregi, usua){
    if (confirm("Se procederá a eliminar el proceso de liquidación del padrón. ¿Deseea continuar?")){
	    return new AJAXRequest("POST", BASE_URL + "liqu/procestaajax.php", "dataset=elimcarg&numeregi=" + numeregi + "&usua=" + usua + "&target=padrcalcinic");	
	}else{
		return false;
	}
}

function listhist(numeregi, usua){
	abrevent(BASE_URL + "liqu/listhist.php","numeregi=" + numeregi);	
}

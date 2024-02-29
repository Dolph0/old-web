// ------------------------------------------------------------
// Esta función muestra u oculta las cajas adecuadas, según la
// selección actual del combo que le pasen. Es llamada en la función
// muesrang en PHP.
// Ademas, asigna el valor blanco a los campos que estén ocultos.
// ------------------------------------------------------------
function arreglaCajas( combo, pref ) {

  // Primero se decide cuando ocultar el primer campo de texto.
  // Ademas, si se elige FECHA NO REGISTRADA, se oculta y se pone la fecha 01/01/0001
  if (combo.options[combo.selectedIndex].text == "No registrada") {
    eval( pref+"div1.style.display = \"none\";" );
    eval( "form."+pref+"textbox1.value =\"01/01/0001\"; ");
  } else {
    if ( combo.options[combo.selectedIndex].text == "Entre meses" ||
         combo.options[combo.selectedIndex].text == "Mes" ){
      eval( pref+"div1.style.display = \"none\";" );
      eval( "form."+pref+"textbox1.value = \"\";");
    } else {
      eval( pref+"div1.style.display = \"inline\";" );
    }
  }

  // Ahora se comprueba cuando ocultar el segundo campo de texto
  if ( combo.options[combo.selectedIndex].text == "Entre" ) {
    eval( pref+"div2.style.display = \"inline\";" );
  } else {
    eval( pref+"div2.style.display = \"none\";" );
    eval( "form."+pref+"textbox2.value = \"\";");
  }

  // Aqui compruebo cuando se ocultan los dos campos cuando los tipos sean meses
  if ( combo.options[combo.selectedIndex].text != "Entre meses" &&
       combo.options[combo.selectedIndex].text != "Mes" ){
    eval (pref+"mes1.style.display = \"none\";"+
          pref+"mes2.style.display = \"none\";");
    eval( "form."+pref+"listmes1.value = \"\";"+
          "form."+pref+"listmes1anio.value = \"\";"+
          "form."+pref+"listmes2.value = \"\";"+
          "form."+pref+"listmes2anio.value = \"\";" );
  } else {
    eval (pref+"mes1.style.display = \"inline\";");
    if ( combo.options[combo.selectedIndex].text == "Entre meses" ) {
      eval (pref+"mes2.style.display = \"inline\";");
    } else {
      eval (pref+"mes2.style.display = \"none\";");
      eval( "form."+pref+"listmes2.value = \"\";"+
            "form."+pref+"listmes2anio.value = \"\";" );
    }
  }
}


// ------------------------------------------------------------
// Para que se vea correctamente la caja:
// cuando muestra contenido de color gris
// cuando no muestra nada de color blanco
// ------------------------------------------------------------
function color (id,td)
{
  if (!td.style.backgroundColor)
  td.style.backgroundColor  = '#DDDDDD';
  else 
  td.style.backgroundColor = '';
}

// Muestra/esconde el elemento pasado como parámetro
// Lo utiliza la funciones para generar la caja
function muestrasconde(elto) {
   if (elto.style.display == "none")
      elto.style.display = 'block';
   else
      elto.style.display = 'none';
}



// Sube  una posición los elementos seleccionados de la lista
function subeSeleccion (lista) {
  for(var i = 1; i < lista.length; i++) {
    if ((lista.options[i] != null) && (lista.options[i].selected)) {
      foo = new Option (lista.options[i-1].text, lista.options[i-1].value);
      lista.options[i-1] = new Option (lista.options[i].text, lista.options[i].value);
      lista.options[i] = new Option (foo.text, foo.value);
      lista.options[i-1].selected = true;
    }
  }
}


// Baja una posición los elementos seleccionados de la lista
function bajaSeleccion (lista) {
  for(var i = lista.length-2; i >= 0; i--) {
    if ((lista.options[i] != null) && (lista.options[i].selected)) {
      foo = new Option (lista.options[i+1].text, lista.options[i+1].value);
      lista.options[i+1] = new Option (lista.options[i].text, lista.options[i].value);
      lista.options[i] = new Option (foo.text, foo.value);
      lista.options[i+1].selected = true;
    }
  }
}

// Pasa un elemento de la lista fuente a la destino
// Además, actualiza la variable oculta que devuelve los campos seleccionados
function aniaorde(destList, srcList, tamamaxi) {
  var len = destList.length;
  for(var i = srcList.length -1; i >= 0 && len < tamamaxi; i--) {
    if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
      destList.options[len] = new Option(srcList.options[i].text,
                                         srcList.options[i].value); 
      len++;
      srcList.options[i] = null;
    }
  }
}


// Realiza la tarea contrario a la función anterior
function borrorde(destList, srcList) {
  var len = destList.options.length;
  for(var i = (len-1); i >= 0; i--) {
    if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
      srcList.options[srcList.options.length]= new Option(destList.options[i].text,
                                                          destList.options[i].value);
      destList.options[i] = null;
    }
  }
}

// Selecciona todas las opciones existentes en un select determinado
function seleTodo (lista) {
  for (var i = 0; i < lista.length; i++) {
    lista.options[i].selected = true;
  }
}

function setSelected(destList, value){
  var optionCounter;
  for (optionCounter = 0; optionCounter < destList.length; optionCounter++)
  {
     if (destList.options[optionCounter].value == value){
         destList.options[optionCounter].selected = true;
         break;
     }
  }
}

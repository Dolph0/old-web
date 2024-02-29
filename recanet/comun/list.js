// Funciones para el manejo de listas

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



// Sube una posición los elementos seleccionados de la lista
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



// Selecciona todas las opciones existentes en un select determinado
function seleTodo (lista) {
  for (var i = 0; i < lista.length; i++) {
    lista.options[i].selected = true;
  }
}

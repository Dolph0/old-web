// Fichero de funciones para la selecci�n de cargos

// Esta funci�n limpia el formulario
function limp() {
  eval (limpieza);
  form.ayun.value = '';
}


function antesEnvio () {
  var mensaje = "";
  var foco = "";

  // Es obligatorio seleccionar todos los campos del formulario
  if (form.conctrib.value == '') {
    mensaje += "* Concepto\n";
    if (foco == "") {foco = "conctrib";}
  }
  if (form.liquperi.value == '') {
    mensaje += "* Periodo de liquidaci�n\n";
    if (foco == "") {foco = "liquperi";}
  }
  if (form.anio.value == '') {
    mensaje += "* A�o contra�do\n";
    if (foco == "") {foco = "anio";}
  }

  if ( mensaje.length > 0 ) {
     mensaje = "Es obligatorio rellenar los\nsiguientes campos: \n\n" + mensaje;
     alert( mensaje );
     form.elements[foco].focus();
     return false;
  } else {
     return true;
  }
}

// Imprime un listado
function imprlist () {
  window.print ();
}

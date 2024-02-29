// Muestra/esconde el elemento pasado como parámetro
// El primer parámetro es el elemento a cambiar, y el segundo una variable
// escondida que sirve para marcar el estado del elemento
/* function muestrasconde(elto, hid) {
   if (elto.style.display == "none") {
      elto.style.display = 'block';
      hid.value = 1;
   } else {
      elto.style.display = 'none';
      hid.value = 0;
   }
} */

// Experimento, a ver si no da problemas comprobar si el segundo es undefined
function muestrasconde(elto, hid) {
   if (elto.style.display == "none") {
      elto.style.display = 'block';
      if (hid != undefined)
        hid.value = 1;
   } else {
      elto.style.display = 'none';
      if (hid != undefined)
        hid.value = 0;
   }
}

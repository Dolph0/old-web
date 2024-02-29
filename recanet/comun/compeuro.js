
function compeuro ( dato ) {
  // Esta funcion recibe por parametros un numero decimal, y comprueba que sea correcto.
  // Hay 2 formatos validos para los numeros decimales: 456,89 y 12.33 por ejemplo.
  // Es decir, la coma decimal puede ser , o .
  // SOLO PERMITIMOS DOS DECIMALES
  // Si la coma decimal es , hay que cambiarla por un . porque es la que el
  // PHP considera como coma decimal. Si no, da errores al operar.

  // Lo primero que hago es aplicar la expresion regular, y comprobar su formato.
  if ( !dato.match("^[0-9]+[,.]?[0-9]{0,2}$") ) {
    return -1;
  }

  // A continuacion, sustituyo la posible ',' por un '.'
  // Esto es necesario porque el PHP considera la coma decimal como '.' 
  // Asi permitimos al usuario que la escriba de ambas formas
  dato = dato.replace( ",", ".");

  return dato;
}


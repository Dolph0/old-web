// Funciones para el manejo de la moneda

//-------------------------------------------------------------------- 
// Funcion para convertir de euros a c�ntimos, *con redondeo inclu�do*
//
// Entradas:
//   value => Cantidad en euros con cualquier numero de decimales.
//
// Salidas:
//   Devuelve la cantidad en centimos tras el redondeo.
//-------------------------------------------------------------------- 
  function euro2cent(value) {
    return (Math.round (value * 100));
  }

  function convEuro (cantidad) {
    cantidad = "" + cantidad;
    if (cantidad == '') cantidad = '0';
    var foo = cantidad.replace (",", ".");
    return parseInt (Math.round(parseFloat (foo)*100));
  }

  // Implementaci�n de impoboni en JS, para el cliente
  function impobonijs (cantidad) {
    cantidad = "" + cantidad;
    if (cantidad == "" || cantidad == "NaN") return "0";

    // Signo
    var signo = cantidad.replace (/^(-).*$/, "$1");
    // Quitamos decimales
    if (signo != "") {
      cantidad = cantidad.replace (/^-(.*)/, "$1");
    }
    if (signo == cantidad) {
      signo = "";
    }
    // Separaci�n de c�ntimos
    var centis = cantidad.replace (/(.?.)$/, "quitame$1");
    centis = centis.replace (/.*quitame/, "");
    if (parseInt (cantidad) < 10) {
      centis = "0" + centis;
    }
    cantidad = cantidad.replace (/(.?.)$/, "");
    if (cantidad == "") {
      cantidad = "0";
    }

    // C�lculo de comas en los miles
    var resultado = "";
    while (cantidad != "") {
      cantidad = cantidad.replace (/(.?.?.)$/, "quitame$1");
      if (resultado != "") {
        resultado = "." + resultado;
      }
      resultado = cantidad.replace (/.*quitame/, "") + resultado;
      cantidad = cantidad.replace (/quitame.*/, "");
    }
    if (resultado == "") {
      resultado = "0";
    }
    return signo + resultado + "," + centis;
  }


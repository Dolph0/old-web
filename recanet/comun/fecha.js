function cheqfech(fech){
// Comprueba si la fecha es correcta. Recibe de entrada una fecha
// y devuelve la cadena en blanco si no hay error, o una ristra que
// explica el error
var dia;
var mes;
var anio;

  // Comprobar la expresion regular
  if (!fech.match ("^([0-9]{1,2}(-|/|\\.)){2}[0-9]{4}$"))
     return "fecha: dd-mm-aaaa\n";

  // Los separadores entre el dia y mes, y entre el mes y el año
  // deben ser iguales. Es decir, el separador aparece 2 veces
  // Lo que hago es comprobar si aparece solo una vez
  if ( fech.indexOf( fech.charAt( fech.length-5 ) ) == fech.length - 5 )
    return "Separadores en la fecha diferentes\n";

  vectfech = fech.split(fech.charAt(fech.length-5));
  dia = vectfech[0];
  mes = vectfech[1]
  anio = vectfech[2]

  // El dia debe ser un numero entre 1 y 31, y el mes entre 1 y 12
  if ( (dia < 1) || (dia > 31) ) {
    return "fecha: no es un dia válido\n";
  }
  if ( (mes < 1) || (mes > 12) ) {
    return "fecha: no es un mes válido\n";
  }

  // Comprobar el dia del mes, por ejemplo, febrero no tiene 31
   var diasmes = new Array();
   diasmes[1] = 31;
   diasmes[2] = 29;   // 28 ó 29: function diasfebrero(ano)
   diasmes[3] = 31;
   diasmes[4] = 30;
   diasmes[5] = 31;
   diasmes[6] = 30;
   diasmes[7] = 31;
   diasmes[8] = 31;
   diasmes[9] = 30;
   diasmes[10] = 31;
   diasmes[11] = 30;
   diasmes[12] = 31;

   if ( dia > diasmes[mes] ) {
      return "fecha: el mes no tiene tantos días\n";
   }
   // Ademas, febrero solo tiene 29 dias, si el año es multiplo de 4
   if ( ( mes == 2 ) && ( dia == 29 ) &&
        ( (anio % 4) != 0) ) {
     return "fecha: ese año, febrero no tiene 29 dias\n";
   }

  // Si llega hasta aqui, no hay errores
  return "";
}

function compfech( fech1, fech2 ) {
  // Esta funcion compara dos fechas en formato dd-mm-aaaa
  // Devuelve 0 si son iguales, 1 si fech1 > fech2, y 2 si fech2 > fech1
  // Si las fechas no son correctas, es un error y devuelve -1
  var camp1;       // Campos de fech1 que estamos comparando
  var camp2;       // Campos de fech2 que estamos comparando

  // Compruebo si las fechas son correctas
  if ( ( cheqfech( fech1 ) != "" ) || ( cheqfech( fech2 ) != "" ) ) { return -1; }

  // Obtengo cada campo (dia, mes y año) de ambas fechas
  camp1 = fech1.split(/[.\/-]/);
  camp2 = fech2.split(/[.\/-]/);

  // Comienzo la comparacion por el año, luego el mes, y por ultimo el dia
  // Comparo los años
  if ( parseInt( camp1[2], 10 ) > parseInt( camp2[2], 10 ) ) { return 1; }
  if ( parseInt( camp1[2], 10 ) < parseInt( camp2[2], 10 ) ) { return 2; }

  // Los años son iguales, comparo el mes
  if ( parseInt( camp1[1], 10 ) > parseInt( camp2[1], 10 ) ) { return 1; }
  if ( parseInt( camp1[1], 10 ) < parseInt( camp2[1], 10 ) ) { return 2; }

  // Por ultimo comparo los dias, ya que meses y años son iguales
  if ( parseInt( camp1[0], 10 ) > parseInt( camp2[0], 10 ) ) { return 1; }
  if ( parseInt( camp1[0], 10 ) < parseInt( camp2[0], 10 ) ) { return 2; }

  // Si llega aqui, las fechas son iguales
  return 0;
}



// Convierte la fecha del formato 'ddmmaaaa' al formato 'aaaa-mm-dd'
function Guardarfecha (fech) {
  var aux;  // Valor de la fecha a retornar con formato aaaa/mm/dd
  var camp;  // Array con los campos de la fecha (dia, mes y año)
   if ( fech.length == 0 ) {
      aux = "0001-01-01";
      return (aux);
   } else {
      // El separador lo tomamos del 5º caracter empezando por el principio
      camp = fech.split(/[.\/-]/);
      aux = camp[2] + "-" + camp[1] + "-" + camp[0];
   }
   return (aux);
}


//Sumar meses a una fecha.

function cerosIzq(sVal, nPos){
  var sRes = sVal;
  for (var i = sVal.length; i < nPos; i++)
     sRes = "0" + sRes;
  return sRes;
}

function armaFecha(nDia, nMes, nAno){
  var sRes = cerosIzq(String(nDia), 2);
  sRes = sRes + "-" + cerosIzq(String(nMes), 2);
  sRes = sRes + "-" + cerosIzq(String(nAno), 4);
  return sRes;
}

function sumaMes(nDia, nMes, nAno, nSum){
    if (nSum >= 0){
     for (var i = 0; i < Math.abs(nSum); i++){
      if (nMes == 12){
       nMes = 1;
       nAno += 1;
      } else nMes += 1;
     }
    } else {
     for (var i = 0; i < Math.abs(nSum); i++){
      if (nMes == 1){
       nMes = 12;
       nAno -= 1;
      } else nMes -= 1;
     }
    }
    return armaFecha(nDia, nMes, nAno);
   }

function sumfech(fecha,meses){
  var nSum = parseInt(meses);
  if (!isNaN(nSum)){
     var nDia = parseInt(fecha.substr(0, 2));
     var nMes = parseInt(fecha.substr(3, 2));
     var nAno = parseInt(fecha.substr(6, 4));
     fechfin = sumaMes(nDia, nMes, nAno, nSum);
  }
  return fechfin;
}

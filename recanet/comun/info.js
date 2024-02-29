// Función para mostrar información adicional sobre varios objetos

//-------------------------------------------------------------
// Si se le pasa 'estacont' como tipo de objeto, se muestra 
// información sobre el estado contable
// Entrada:
//   tipoobje => Tipo de objeto 
//   conc     => Concepto
//   ayun     => Ayuntamiento
//   ejer     => Ejercicio
//   docu     => Nº de documento
//   link     => Nº del ingreso a cuenta (Solo para los 
//               ingresos a cuenta), en las otras llamdas
//               no existe
// 
// NOTA: link nos permite identificar un parametro por omisión
//--------------------------------------------------------------
function infoobje (tipoobje, conc, ayun, ejer, docu, link) {
  window.open ('../comun/ventinfo.php?tipoobje=' + tipoobje + '&codiconc=' + conc + 
               '&codiayun=' + ayun + '&ejer=' + ejer + '&numedocu=' + docu +
               '&numeingr=' + link, 
               'infoobjeto', 'toolbar=no');
}


function infosuje (nifx) {
  window.open ('../comun/ventinfo.php?tipoobje=nifx&nifx=' + URLEncode(nifx), 
               'infoobjeto', 'toolbar=no');
}

function infoestanoti (tipoobje,conc,ayun, ejer,docu) {
  window.open ('../comun/ventinfo.php?tipoobje=' + tipoobje + '&codiconc=' + conc + 
               '&codiayun=' + ayun + '&ejer=' + ejer + '&numedocu=' + docu, 
               'infoobjeto', 'toolbar=no');
}

function infosusp (tipoobje,conc,ayun, ejer,docu) {
  window.open ('../comun/ventinfo.php?tipoobje=' + tipoobje + '&codiconc=' + conc + 
               '&codiayun=' + ayun + '&ejer=' + ejer + '&numedocu=' + docu, 
               'infoobjeto', 'toolbar=no');
}

function infocarg (conc,ayun,ejer,doc) {
  window.open ('../comun/ventinfo.php?tipoobje=carg&codiconc=' + conc + 
               '&codiayun=' + ayun + '&ejer=' + ejer + '&numedocu=' + doc,
               'infoobjeto', 'toolbar=no');
  
}

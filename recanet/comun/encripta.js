function encripta() {
   document.login.respuesta.value  = document.login.nomb.value + ":" +MD5( MD5(document.login.cont.value)+document.login.idensesi.value);

  // Se limpian el resto de cambos del formulario
  // no queremos que se pasen
  document.login.nomb.value = "";
  document.login.cont.value = "";
  document.login.idensesi.value = "";

  document.login.submit();

  return false;
}

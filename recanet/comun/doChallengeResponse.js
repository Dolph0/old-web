function encripta() {
   document.login.respuesta.value  = document.login.codiusua.value + ":"  + 
     MD5( MD5(document.login.cont.value) + document.login2.idensesi.value );

  // Se limpian el resto de cambos del formulario
  // no queremos que se pasen
  document.login.codiusua.value = "";
  document.login.cont.value = "";
  document.login.idensesi.value = "";

  document.login2.submit();

  return false;
}

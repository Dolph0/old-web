function focus() {

  // Activa el campo del formulario adecuado
  if (document.login.username.value == '') {
    document.login.codiusua.focus();
  } else {
    document.login.cont.focus();
  }
}

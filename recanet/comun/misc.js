// Asigna el valor val a una serie de componentes, que tienen un prefijo y
// sufijo comunes (pref y sufi) y que van desde ini hasta fin
function asig (pref, sufi, ini, fin, val) {
  var i;
  for (i = ini; i <= fin; ++i) {
    eval (pref + i + sufi + " = '" + val + "'");
  }
}


// Manda el formulario a la página que se diga, abriendo otra ventana
function enviform (f, url) {
  targetanterior = f.target;
  actionanterior = f.action;
  f.target = url;
  f.action = url;
  f.submit ();
  f.target = targetanterior;
  f.action = actionanterior;
}


function radioelegido (r) {
  var i;
  for (i = 0; i < r.length; ++i) {
    if (r[i].checked)
      return r[i].value;
  }
  // ???
  return false;
}

function radioindiceelegido (r) {
  var i;
  for (i = 0; i < r.length; ++i) {
    if (r[i].checked)
      return i;
  }
  // ???
  return false;
}


// Imprime las notificaciones personales 
function imprnotiindipers () {
  var targetantiguo = document.forms[0].target;  
  var actionantiguo = document.forms[0].action;

  document.forms[0].target = "../rnot/notiindivolu.php?notipers=PERS&impr=indi";
  document.forms[0].action = "../rnot/notiindivolu.php?notipers=PERS&impr=indi";
  document.forms[0].submit ();
  document.forms[0].target = targetantiguo;
  document.forms[0].action = actionantiguo;
}

// Imprime las notificaciones individuales
function imprnotiindi () {
  var b_acuserecibo;
  var targetantiguo = document.forms[0].target;
  var actionantiguo = document.forms[0].action;
  
  resp = MensajeSNC("¿Emitir la notificación con acuse de recibo?");
  switch (resp) {
    case 6: // SI
    b_acuserecibo = 'true';
      break;
    case 7: // NO
    b_acuserecibo = 'false';
      break;
    case 2: // CANCELAR
    default:
     return false;
  }
  
  document.forms[0].target = '../rnot/notiindivolu.php?b_acuserecibo=' + b_acuserecibo;
  document.forms[0].action = '../rnot/notiindivolu.php?b_acuserecibo=' + b_acuserecibo;
  document.forms[0].submit ();
  document.forms[0].target = targetantiguo;
  document.forms[0].action = actionantiguo;
}

// Imprime las notificaciones sicer
function imprnotiindisicer () {
  var targetantiguo = document.forms[0].target;
  var actionantiguo = document.forms[0].action;
  
  document.forms[0].target = '../rnot/notiindivolusicer.php';
  document.forms[0].action = '../rnot/notiindivolusicer.php';
  document.forms[0].submit ();
  document.forms[0].target = targetantiguo;
  document.forms[0].action = actionantiguo;
}

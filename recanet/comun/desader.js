// Función en JavaScript que se encarga de desactivar el botón derecho del navegador y la tecla
// de backspace.
// Así, cuando se pulse aparecerá un mensaje de botón derecho desactivado.

// Función que controla accion de pulsar los botones del ratón
function derecho(e)
{
    if (navigator.appName == 'Netscape' && (e.which == 3 || e.which == 2))return false;
    else {
      if (navigator.appName == 'Microsoft Internet Explorer' && (event.button == 2 || event.button == 3)) {
        alert("Utilice la barra de navegación de la aplicación");
        return false;
      }
    }
    return true;
}

// Función que controla la acción de pulsar las teclas
function dkeydown(e) 
{
  if(document.layers) { keycode = e.which;}
  if (document.all) { keycode = window.event.keyCode; }
  
  if(keycode==8) {
     alert("Utilice la barra de navegación de la aplicación");
     return false;
  }
  
  return true;
}


// Eventos para el boton derecho
document.onmousedown=derecho;
document.onmouseup=derecho;
if (document.layers) window.captureEvents(Event.MOUSEDOWN);
if (document.layers) window.captureEvents(Event.MOUSEUP);
window.onmousedown=derecho;
window.onmouseup=derecho;
 
// Eventos para la tecla de backspace
if(document.layers)document.captureEvents(Event.KEYDOWN);
document.onkeydown=dkeydown;


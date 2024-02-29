// Funciones JS de gestión de ventanas

// Abre la ventana del buscador
function abrevent(urlx,vget){
var windowprops = "location=no,scrollbars=yes,menubars=no,toolbars=no," +
                  "resizable=yes" + ",left=" + "20" + ",top=" + "20" +
                  ",width=" + "500" + ",height=" + "500";

  popup = window.open(urlx+"?"+vget,"hija",windowprops);
  if ( !popup.opener ) popup.opener = self;
}

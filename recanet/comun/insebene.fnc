<?

# Inserta una relacion de beneficio tributario - objeto
# Variables de entrada: 
#  codibene : codigo del beneficio tributario
#  tipoobje : tipo de objeto tributario (tabla tipoobje)
#  codiobje : codigo interno que tendra el objeto en 
#             cuestion, independientemente de la tabla en
#             la que se encuentre
#  inic :     fecha a partir de la cual entrara en vigor el 
#             beneficio tributario.
#  finx :     fecha en que expirara el beneficio tributario
#             asociado al objeto

function insebene ($codibene,$inic,$finx,$tipoobje,$codiobje) {

  // Si no existe ...
  if (!sql ("SELECT * FROM benetribobje WHERE codibene='$codibene' AND
             tipoobje='$tipoobje' AND codiobje='$codiobje' ") ) {
    // ... se inserta
    sql ("INSERT INTO benetribobje (codibene, tipoobje, codiobje,
          fechinibene, fechfinbene) VALUES ('$codibene','$tipoobje',
          '$codiobje','$inic','$finx')");
  }
 
}


?>
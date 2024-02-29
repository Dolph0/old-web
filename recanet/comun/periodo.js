// Funcion periodo encargada de ajustar el periodo segun el concepto. 
// Es necesario pasar el campo del formulario donde va a escribir los 
// resultados.
// Y el tipo de periodo a mostrar:
// A -> Anual
// S -> Semestral
// C -> Cuatrimestral
// T -> Trimestral 
// B -> Bimestral 
// M -> Mensaul
// POR OMISION -> TODO LO ANTERIOR.

 function periodo (liquperi,peri) 
 {
// definimos el tamaño del nuevo listado.
 var tamano;
// obtenemos el valor del periodo.
 var valor=peri;

// inicializamos el combo. 
  document.form.liquperi.value='';
  document.form.liquperi.length=0;
 
 switch (valor) {
 case "A":    
    document.form.liquperi.options[1] = new Option ("ANUAL","PA");
    tamano = 1;
    break;
 case "S":
    document.form.liquperi.options[1] = new Option ("PRIMER SEMESTRE","1S");
    document.form.liquperi.options[2] = new Option ("SEGUNDO SEMESTRE","2S");
    tamano = 2;
    break;
 case "C":
    document.form.liquperi.options[1] = new Option ("PRIMER CUATRIMESTRE","1C");
    document.form.liquperi.options[2] = new Option ("SEGUNDO CUATRIMESTRE","2C");
    document.form.liquperi.options[3] = new Option ("TERCER CUATRIMESTRE","3C");
    tamano = 3;
    break;
 case "T":
    document.form.liquperi.options[1] = new Option ("PRIMER TRIMESTRE","1T");
    document.form.liquperi.options[2] = new Option ("SEGUNDO TRIMESTRE","2T");
    document.form.liquperi.options[3] = new Option ("TERCER TRIMESTRE","3T");
    document.form.liquperi.options[4] = new Option ("CUARTO TRIMESTRE","4T");
    tamano = 4;
    break
 case "B":
    document.form.liquperi.options[1] = new Option ("ENERO-FEBRERO","EF");
    document.form.liquperi.options[2] = new Option ("MARZO-ABRIL","MA");
    document.form.liquperi.options[3] = new Option ("MAYO-JUNIO","MJ");
    document.form.liquperi.options[4] = new Option ("JULIO-AGOSTO","JA");
    document.form.liquperi.options[5] = new Option ("SEPTIEMBRE-OCTUBRE","SO");
    document.form.liquperi.options[6] = new Option ("NOVIEMBRE-DICIEMBRE","ND");
    tamano = 6;
    break;
 case "M":
    document.form.liquperi.options[1] = new Option ("ENERO","EN");
    document.form.liquperi.options[2] = new Option ("FEBRERO","FE");
    document.form.liquperi.options[3] = new Option ("MARZO","MZ");
    document.form.liquperi.options[4] = new Option ("ABRIL","AB");
    document.form.liquperi.options[5] = new Option ("MAYO","MY");
    document.form.liquperi.options[6] = new Option ("JUNIO","JN");
    document.form.liquperi.options[7] = new Option ("JULIO","JL");
    document.form.liquperi.options[8] = new Option ("AGOSTO","AG");
    document.form.liquperi.options[9] = new Option ("SEPTIEMBRE","SE");
    document.form.liquperi.options[10] = new Option ("OCTUBRE","OC");
    document.form.liquperi.options[11] = new Option ("NOVIEMBRE","NO");
    document.form.liquperi.options[12] = new Option ("DICIEMBRE","DI");
    tamano = 12;
    break;
 default:
    document.form.liquperi.options[1] = new Option ("ENERO","EN");
    document.form.liquperi.options[2] = new Option ("FEBRERO","FE");
    document.form.liquperi.options[3] = new Option ("MARZO","MZ");
    document.form.liquperi.options[4] = new Option ("ABRIL","AB");
    document.form.liquperi.options[5] = new Option ("MAYO","MY");
    document.form.liquperi.options[6] = new Option ("JUNIO","JN");
    document.form.liquperi.options[7] = new Option ("JULIO","JL");
    document.form.liquperi.options[8] = new Option ("AGOSTO","AG");
    document.form.liquperi.options[9] = new Option ("SEPTIEMBRE","SE");
    document.form.liquperi.options[10] = new Option ("OCTUBRE","OC");
    document.form.liquperi.options[11] = new Option ("NOVIEMBRE","NO");
    document.form.liquperi.options[12] = new Option ("DICIEMBRE","DI");
    document.form.liquperi.options[13] = new Option ("ENERO-FEBRERO","EF");
    document.form.liquperi.options[14] = new Option ("MARZO-ABRIL","MA");
    document.form.liquperi.options[15] = new Option ("MAYO-JUNIO","MJ");
    document.form.liquperi.options[16] = new Option ("JULIO-AGOSTO","JA");
    document.form.liquperi.options[17] = new Option ("SEPTIEMBRE-OCTUBRE","SO");
    document.form.liquperi.options[18] = new Option ("NOVIEMBRE-DICIEMBRE","ND");
    document.form.liquperi.options[19] = new Option ("PRIMER TRIMESTRE","1T");
    document.form.liquperi.options[20] = new Option ("SEGUNDO TRIMESTRE","2T");
    document.form.liquperi.options[21] = new Option ("TERCER TRIMESTRE","3T");
    document.form.liquperi.options[22] = new Option ("CUARTO TRIMESTRE","4T");
    document.form.liquperi.options[23] = new Option ("PRIMER CUATRIMESTRE","1C");
    document.form.liquperi.options[24] = new Option ("SEGUNDO CUATRIMESTRE","2C");
    document.form.liquperi.options[25] = new Option ("TERCER CUATRIMESTRE","3C");
    document.form.liquperi.options[26] = new Option ("PRIMER SEMESTRE","1S");
    document.form.liquperi.options[27] = new Option ("SEGUNDO SEMESTRE","2S");
    document.form.liquperi.options[28] = new Option ("ANUAL","PA");
    tamano = 27;
    break;
 }
 
 var tamactual = 0;
 

 if (tamactual > tamano) {
   var i=0;

   i = tamano;
   
   while (i<tamactual) {
     document.form.liquperi.options[tamano+1] = null;
     i++;
   }
 }
 return false;
} // Fin de la función 

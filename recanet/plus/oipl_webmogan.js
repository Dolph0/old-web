
function limp (){
  document.form.opci.value='Limpiar';
  document.form.submit();
}

function buscnota(){
 var mensaje;
 mensaje='';
 if (document.form.estanota.value=='REGIS')if (document.form._nombnota2.value=='') mensaje+=' ·Notario\n';
 if (document.form.estanota.value=='NOREG')if (document.form._nombnota.value=='') mensaje+=' ·Notario\n';
 if (document.form.estanota.value!='REGIS' && document.form.estanota.value!='NOREG') mensaje+=' ·ERROR EN EL NOTARIO\n';
 if (document.form._fechescr.value=='') mensaje+= ' ·fecha transmisión \n';
 if (mensaje)
 {
  alert ("Faltan datos en campo/s obligatorio/s para realizar la busqueda:\n"+mensaje);
  return false;
 }
 else 
 {
   document.form.opci.value='Buscar';
   document.form.submit();
   return true;
 }
}
 

function regisele(){
  
  if (document.form.codioipl.value)
  return true;
  else 
  return false;
}


function cheq () {

 var mensaje;
 var fecha;
 mensaje='';

 var regex_compnifx= new RegExp("^([0-9]{8}[A-Z]|[A-HNPQS][0-9]{7}[A-Z0-9]|X[0-9]{7}[A-Z])?$", "");

 if (compfech(document.form._fechdeve.value,document.form._fechtran.value)==2) 
 {
   fecha= '   FECHA DE TRANSMISION:\n';
   fecha+='\n ·ACTUAL:'+document.form._fechdeve.value+'\n';
   fecha+='\n ES MENOR QUE\n';
   fecha+='\n ·ANTERIOR:'+document.form._fechtran.value;
 }
//Comprobacion del inmueble: 
 if (document.form.tipovia.value=='NUEVA')if (document.form.viax.value=='')            mensaje+=' ·Vía pública del inmueble\n';
 if (document.form.tipovia.value=='CALLEJERO') if (document.form.codiviax.value=='')   mensaje+=' .Vía pública del inmueble\n';
 if (document.form.tipovia.value!='NUEVA' && document.form.tipovia.value!='CALLEJERO') mensaje+=' ·ERROR EN LA VIA\n'; 
 if (document.form.nume.value=='')                                                     mensaje+=' ·Número del inmueble.\n';
 if (document.form._fechdeve.value=='') mensaje+= ' ·Fecha prevista\n';
 if (document.form._fechtran.value=='') mensaje+= ' ·Fecha transmisión anterior\n';
 if (document.form._cuotadqu.value=='') mensaje+= ' ·Cuota adquisición\n';
 else{
    if (document.form._cuotadqu.value>100) mensaje+= ' ·Cuota adquisición no puede ser mayor que 100\n';
 }

 comphoy = document.form._hoy.value.split(/[.\/-]/);
 comphoy[2] = comphoy[2]-10;
 nuevfech = comphoy[0]+'.'+comphoy[1]+'.'+comphoy[2];
 if (compfech (document.form._fechdeve.value, nuevfech)==2) mensaje+=' · fecha transmision actual mayor a 10 años ('+document.form._fechdeve.value+')\n';

 if ((document.getElementById('valocatausua'))&&(form.valocatausua.value=='')) mensaje+= 'Valor catastral\n';

 /*
//if (compfech (document.form._fechdeve.value, document.form._fechescr.value)==1) mensaje+=' · la fecha de devengo no puede ser mayor a la fecha de escritura\n';
// chequeo del Valor catastral del suelo en caso de que se pida. Ponencia de valores.
 if (document.form._metovalo.value=='NO') {
   if (document.form._supesola.value=='')   mensaje+= ' ·Superficie del solar\n';
   if (document.form._valocatam2.value=='') mensaje+= ' ·Valor catastral del suelo / m²\n';
   if (document.form._cuotpart.value=='')   mensaje+= ' ·Cuota participación de elementos comunes\n';
 }
*/

  //pasando a mayusculas por si no lo han puesto en minusculas... 
//  document.form._obse.value = document.form._obse.value.toUpperCase();
   
if (mensaje || fecha)
{
 if (mensaje) alert ("Faltan datos en campo/s obligatorio/s:\n"+mensaje);
 if (fecha) alert (fecha);
 return false;
}
else return true;
}

//APERTURA DE VENTANAS.
//PARA EL INMUEBLE.
function abrevent(urlx,vget) {
  var windowprops = "location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=yes" + ",left=" + "20" + ",top=" + "20" + ",width=" + "800" + ",height=" + "600";

  popup = window.open(urlx+"?"+vget,"hija",windowprops);
  if ( !popup.opener ) popup.opener = self;
}

//PARA EL SUJETO 
function ventsuje(urlx,vget) {
  var windowprops = "location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=yes" + ",left=" + "20" + ",top=" + "20" + ",width=" + "400" + ",height=" + "500";

  popup = window.open(urlx+"?"+vget,"hija",windowprops);
  if ( !popup.opener ) popup.opener = self;
}

//PARA LA LIQUIDACION
function ventliqu(urlx,vget) {
  var windowprops = "location=no,scrollbars=yes,menubars=no,toolbars=no,resizable=yes" + ",left=" + "20" + ",top=" + "20" + ",width=" + "600" + ",height=" + "530";

  popup = window.open(urlx+"?"+vget,"hija",windowprops);
  if ( !popup.opener ) popup.opener = self;
}

//Funcion para mostrar y esconder elementos.
function muestrasconde(elto) {
   if (elto.style.display == "none")
     elto.style.display = 'block';     
   else
      elto.style.display = 'none';
}

function alternar(elto,segu) {
   if (elto.style.display == "none")
     {
      elto.style.display = 'block';
      segu.style.display = 'none';
     }
   else
    {
      elto.style.display = 'none';
      segu.style.display = 'block';
    }
}
//funcion para mostrar elemento oculto.
function muestra (elto){elto.style.display = 'block';}
//funcion para ocultar elemento.
function esconde (elto){ elto.style.display = 'none';}

function domifisc(){
 if (form._domihabi.value==1) esconde (domiciliofiscal);
 else muestra (domiciliofiscal);
}

function datodecl(){
 if (form._idxxdecl.value=='RT' || form._idxxdecl.value=='RA') muestra (representante);
 else esconde (representante);
}

function metodo(){
 if (form._metovalo.value=='NO') muestra (valores);
 else esconde (valores);
}

function buscaviax (codiambi)
{
  abrevent("../comun/listviax_webmogan.php", "ayun="+codiambi+"&codiviax="+form.codiviax.value+"&nombviax="+form.codiviax.options[form.codiviax.selectedIndex].text+"&nume="+form.nume.value+"&letr="+form.letr.value+"&esca="+form.esca.value+"&plan="+form.plan.value+"&puer="+form.puer.value+"&refecata="+form.refecata.value+"&numecarg="+form.numecarg.value+"&caracont="+form.caracont.value+"&nfij="+form.nfij.value+"&nombinmu="+form.nombinmu.value+"&idenloca="+form.idenloca.value+"&sufi=")
}


function liquoipl (){ ventliqu("../plus/liquoipl.php", "codioipl="+form.codioipl.value)}


function buscinmu (sufi) {}
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


function cheq (){
 var mensaje;
 var fecha;
 mensaje='';


 //Control del nif al insertar datos nuevos

 var regex_compnifx= new RegExp("^([0-9]{8}[A-Z]|[A-HNPQS][0-9]{7}[A-Z0-9]|X[0-9]{7}[A-Z])?$", "");

 if (document.form.trancodipais.value=='66' && document.form.trancoditerc.value==''){
    if (!form.trannifx.value.match (regex_compnifx)) 
    { 
       form.trannifx.focus(); 
       alert ('El valor ' + form.trannifx.value + ' (NIF/NIE) es incorrecto'); 
       return false; 
    }
 }

 if (document.form.adqucodipais.value=='66' && document.form.adqucoditerc.value==''){
    if (!form.adqunifx.value.match (regex_compnifx)) 
    { 
       form.adqunifx.focus(); 
       alert ('El valor ' + form.adqunifx.value + ' (NIF/NIE) es incorrecto'); 
       return false; 
    }
 }

 if (document.form.reprcodipais.value=='66' && document.form.reprcoditerc.value==''){
    if (!form.reprnifx.value.match (regex_compnifx)) 
    { 
       form.reprnifx.focus(); 
       alert ('El valor ' + form.reprnifx.value + ' (NIF/NIE) es incorrecto'); 
       return false; 
    }
 }

 //Control de la personalidad según el campo nifx insertado

 var regex_nif= new RegExp("^([0-9]{8}[A-Z])?$", "");
 var regex_nie= new RegExp("^(X[0-9]{7}[A-Z])?$", ""); 
 var regex_soc= new RegExp("^([A-H][0-9]{7}[A-Z0-9])?$", "");
 var regex_ent= new RegExp("^([PQS][0-9]{7}[A-Z0-9])?$", "");

 if (form.trannifx.value!='')
 {
    if ((form.trannifx.value.match (regex_nif) || form.trannifx.value.match (regex_nie)) && form.tranpers.value!='F')
    {
       form.tranpers.focus(); 
       alert ('Para el valor ' + form.trannifx.value + ' (NIF/NIE) la Personalidad debe ser FISICA'); 
       return false; 
    }
    if (form.trannifx.value.match (regex_soc) && form.tranpers.value!='J') 
    {
       form.tranpers.focus(); 
       alert ('Para el valor ' + form.trannifx.value + ' (NIF/NIE) la Personalidad debe ser JURIDICA'); 
       return false; 
    }
    if (form.trannifx.value.match (regex_ent) && form.tranpers.value!='E')
    {
       form.tranpers.focus(); 
       alert ('Para el valor ' + form.trannifx.value + ' (NIF/NIE) la Personalidad debe ser ENTIDAD'); 
       return false; 
    }
 }

 if (form.adqunifx.value!='')
 {
    if ((form.adqunifx.value.match (regex_nif) || form.adqunifx.value.match (regex_nie)) && form.adqupers.value!='F')
    {
       form.adqupers.focus(); 
       alert ('Para el valor ' + form.adqunifx.value + ' (NIF/NIE) la Personalidad debe ser FISICA'); 
       return false; 
    }
    if (form.adqunifx.value.match (regex_soc) && form.adqupers.value!='J')      
    {
       form.adqupers.focus(); 
       alert ('Para el valor ' + form.adqunifx.value + ' (NIF/NIE) la Personalidad debe ser JURIDICA'); 
       return false; 
    }
    if (form.adqunifx.value.match (regex_ent) && form.adqupers.value!='E')
    {
       form.adqupers.focus(); 
       alert ('Para el valor ' + form.adqunifx.value + ' (NIF/NIE) la Personalidad debe ser ENTIDAD'); 
       return false; 
    }
 } 

 if (form.reprnifx.value!='')
 {
    if ((form.reprnifx.value.match (regex_nif) || form.reprnifx.value.match (regex_nie)) && form.reprpers.value!='F')      
    {
       form.reprpers.focus(); 
       alert ('Para el valor ' + form.reprnifx.value + ' (NIF/NIE) la Personalidad debe ser FISICA'); 
       return false; 
    }
    if (form.reprnifx.value.match (regex_soc) && form.reprpers.value!='J')       
    {
       form.reprpers.focus(); 
       alert ('Para el valor ' + form.reprnifx.value + ' (NIF/NIE) la Personalidad debe ser JURIDICA'); 
       return false; 
    }  
    if (form.reprnifx.value.match (regex_ent) && form.reprpers.value!='E')
    {
       form.reprpers.focus(); 
       alert ('Para el valor ' + form.reprnifx.value + ' (NIF/NIE) la Personalidad debe ser ENTIDAD'); 
       return false; 
    }  
 }


 if (compfech(document.form._fechescr.value,document.form._fechtran.value)==2) 
 {
   fecha= '   FECHA DE TRANSMISION:\n';
   fecha+='\n ·ACTUAL:'+document.form._fechescr.value+'\n';
   fecha+='\n ES MENOR QUE\n';
   fecha+='\n ·ANTERIOR:'+document.form._fechtran.value;
 }
//Comprobacion del inmueble: 
 if (document.form.tipovia.value=='NUEVA')if (document.form.viax.value=='')            mensaje+=' ·Vía pública del inmueble\n';
 if (document.form.tipovia.value=='CALLEJERO') if (document.form.codiviax.value=='')   mensaje+=' .Vía pública del inmueble\n';
 if (document.form.tipovia.value!='NUEVA' && document.form.tipovia.value!='CALLEJERO') mensaje+=' ·ERROR EN LA VIA\n'; 
 if (document.form.nume.value=='')                                                     mensaje+=' ·Número del inmueble.\n';
 if (document.form.estanota.value=='REGIS')if (document.form._nombnota2.value=='')     mensaje+=' ·Notario\n';
 if (document.form.estanota.value=='NOREG')if (document.form._nombnota.value=='')      mensaje+=' ·Notario\n';
 if (document.form.estanota.value!='REGIS' && document.form.estanota.value!='NOREG')   mensaje+=' ·ERROR EN EL NOTARIO\n';
 if (document.form._prot.value=='')     mensaje+= ' ·Protocolo \n';
 if (document.form._fechescr.value=='') mensaje+= ' ·Fecha escritura\n';
 if (document.form._clastran.value=='') mensaje+= ' ·Clase Transmision \n';
 if (document.form._fechdeve.value=='') mensaje+= ' ·Fecha devengo\n';
 if (document.form._fechtran.value=='') mensaje+= ' ·Fecha transmisión anterior\n';
 if (document.form._fechpres.value=='') mensaje+= ' ·Fecha presentacion\n';
 if (document.form._cuotadqu.value=='') mensaje+= ' ·Cuota adquisición\n';
 else{
    if (document.form._cuotadqu.value>100) mensaje+= ' ·Cuota adquisición no puede ser mayor que 100\n';
 }

 comphoy = document.form._hoy.value.split(/[.\/-]/);
 comphoy[2] = comphoy[2]-15;
 nuevfech = comphoy[0]+'.'+comphoy[1]+'.'+comphoy[2];
 if (compfech (document.form._fechescr.value, nuevfech)==2) mensaje+=' · fecha transmision actual mayor a 15 años ('+document.form._fechescr.value+')\n';

 if (compfech (document.form._fechdeve.value, document.form._fechescr.value)==1) mensaje+=' · la fecha de devengo no puede ser mayor a la fecha de escritura\n';

 if (compfech (document.form._fechpres.value, document.form._fechescr.value)==2) mensaje+=' · la fecha de presentación no puede ser anterior a la fecha de escritura\n';

//Comprobar los campos obligatorios de los domicilios fiscales.
 if (document.form.trannomb.value=='') mensaje+= ' ·Nombre del Transmitente\n';
 if (document.form.trannifx.value=='') mensaje+= ' ·NIF del Transmitente\n';
 if (document.form.tranpers.value=='') mensaje+= ' ·Personalidad del Transmitente\n';
 if (document.form.trancodipais.value=='') mensaje+= ' ·País del Transmitente\n';
 if (document.form.trandire.value=='') mensaje+= ' ·Nombre de la vía del Transmitente\n';
 if (document.form.trancodipais.value=='66'){
    if (document.form.transigl.value=='') mensaje+= ' ·Sigla de la vía del Transmitente\n';
    if (document.form.trannume.value=='') mensaje+= ' ·Número de la vía del Transmitente\n';
    if (document.form.trancodipost.value=='') mensaje+= ' ·Código postal del Transmitente\n';
    if (document.form.tranprov.value=='') mensaje+= ' ·Provincia del Transmitente\n';
    if (document.form.tranmuni.value=='') mensaje+= ' ·Municipio del Transmitente\n';
 }

 if (document.form.adqunomb.value=='') mensaje+= ' ·Nombre del Adquirente\n';
 if (document.form.adqunifx.value=='') mensaje+= ' ·NIF del Adquirente\n';
 if (document.form.adqupers.value=='') mensaje+= ' ·Personalidad del Adquirente\n';

 // Los datos del domicilio serán los del objeto tributario
 if (form._domihabi.value == 0) {
   if (document.form.adqucodipais.value=='') mensaje+= ' ·País del Adquirente\n';
   if (document.form.adqudire.value=='') mensaje+= ' ·Nombre de la vía del Adquirente\n';
   if (document.form.adqucodipais.value=='66'){
      if (document.form.adqusigl.value=='') mensaje+= ' ·Sigla de la vía del Adquirente\n';
      if (document.form.adqunume.value=='') mensaje+= ' ·Número de la vía del Adquirente\n';
      if (document.form.adqucodipost.value=='') mensaje+= ' ·Código postal del Adquirente\n';
      if (document.form.adquprov.value=='') mensaje+= ' ·Provincia del Adquirente\n';
      if (document.form.adqumuni.value=='') mensaje+= ' ·Municipio del Adquirente\n';
   }
 }



//chequeo del representante en caso de existir. 
 if (document.form._idxxdecl.value=='RT' || document.form._idxxdecl.value=='RA')
 {
   if (document.form.reprnomb.value=='') mensaje+= ' ·Nombre del Declarante\n';
   if (document.form.reprnifx.value=='') mensaje+= ' ·NIF del Declarante\n';
   if (document.form.reprpers.value=='') mensaje+= ' ·Personalidad del Declarante\n';
   if (document.form.reprcodipais.value=='') mensaje+= ' ·País del Declarante\n';
   if (document.form.reprdire.value=='') mensaje+= ' ·Nombre de la vía del Declarante\n';
   if (document.form.reprcodipais.value=='66'){
      if (document.form.reprsigl.value=='') mensaje+= ' ·Sigla de la vía del Declarante\n';      
      if (document.form.reprnume.value=='') mensaje+= ' ·Número de la vía del Declarante\n';
      if (document.form.reprcodipost.value=='') mensaje+= ' ·Código postal del Declarante\n';
      if (document.form.reprprov.value=='') mensaje+= ' ·Provincia del Declarante\n';
      if (document.form.reprmuni.value=='') mensaje+= ' ·Municipio del Declarante\n';
   }
 }
// chequeo del Valor catastral del suelo en caso de que se pida   Ponencia de valores
 if (document.form._metovalo.value=='NO')
 {
   if (document.form._supesola.value=='')   mensaje+= ' ·Superficie del solar\n';
   if (document.form._valocatam2.value=='') mensaje+= ' ·Valor catastral del suelo / m²\n';
   if (document.form._cuotpart.value=='')   mensaje+= ' ·Cuota participación de elementos comunes\n';
 }

 // chequeo del Valor catastral del suelo en caso de que se pida   Ponencia de valores
 if (document.form.adqucodipais.value!='66' && document.form._domihabi.value==0 && document.form._idxxdecl.value!='RT' && document.form._idxxdecl.value!='RA')
 {
   if (document.form.reprnomb.value=='') mensaje+= ' ·Nombre del Declarante\n';
   if (document.form.reprnifx.value=='') mensaje+= ' ·NIF del Declarante\n';
   if (document.form.reprpers.value=='') mensaje+= ' ·Personalidad del Declarante\n';  
   if (document.form.reprcodipais.value=='') mensaje+= ' ·Pais del Declarante\n';
   if (document.form.reprdire.value=='') mensaje+= ' ·Nombre de la vía del Declarante\n';
   if (document.form.reprcodipais.value=='66'){
      if (document.form.reprsigl.value=='') mensaje+= ' ·Sigla de la vía del Declarante\n';      
      if (document.form.reprnume.value=='') mensaje+= ' ·Número de la vía del Declarante\n';  
      if (document.form.reprcodipost.value=='') mensaje+= ' ·Código postal del Declarante\n';  
      if (document.form.reprprov.value=='') mensaje+= ' ·Provincia del Declarante\n';  
      if (document.form.reprmuni.value=='') mensaje+= ' ·Municipio del Declarante\n';  
   }
 }

  //pasando a mayusculas por si no lo han puesto en minusculas... 
  document.form._obse.value = document.form._obse.value.toUpperCase();
   
// en caso de error "compilacion" del mensaje y mostrarlo por pantalla sin permitir insertar,

// Por culpa de ... ejem en fin por si estos campso estan nulos como tienen que entrarr en la base de datos 
// con valores suponemos que si se han quedado en blanco y solo son ellos pues los igualamos a LG = LUGAR
// de todas formas se lo seuimos dejando en negrita el campo para que "Crean" que es obligatorio ... 
   if (document.form.transigl.value=='') document.form.transigl.value='LG';
   if (document.form.adqusigl.value=='') document.form.adqusigl.value='LG';
   if (document.form.reprsigl.value=='') document.form.reprsigl.value='LG';
   
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
  abrevent("../comun/listviax.php", "ayun="+codiambi+"&codiviax="+form.codiviax.value+"&nombviax="+form.codiviax.options[form.codiviax.selectedIndex].text+"&nume="+form.nume.value+"&letr="+form.letr.value+"&esca="+form.esca.value+"&plan="+form.plan.value+"&puer="+form.puer.value+"&refecata="+form.refecata.value+"&numecarg="+form.numecarg.value+"&caracont="+form.caracont.value+"&nfij="+form.nfij.value+"&nombinmu="+form.nombinmu.value+"&idenloca="+form.idenloca.value+"&sufi=")
}


function liquoipl (){ ventliqu("../plus/liquoipl.php", "codioipl="+form.codioipl.value)}


function buscinmu (sufi) {}

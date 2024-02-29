/**************************************************************
infocata: 	Abre en una ventana nueva el informe de cargo o de
			finca de CatastroWeb en función de los parámetros que
			se le pasen.
			Si se le pasa un sólo parámetro lo toma como referencia
			catastral y mostrará la ventana de 'informefinca'
			Si se le pasan dos parámetros el primero lo toma
			como referencia catastral y el segundo como numero de cargo
			y mostrará la ventana de 'informecargo'
			
			Se usará esta función dentro del evento onclick del
			botón para tal efecto.
			
			PARÁMETROS (en este orden):
			refecata: Referencia catastral de 14 dígitos
			numecata: número del cargo. 4 dígitos (puede ser vacio)
			
***************************************************************/
function infocata(){

	var refecata=(arguments.length>0)? arguments[0] : "";
	var numecarg=(arguments.length==2)? arguments[1] : "";
	var urlinforme="";
	
	if(refecata!="" && numecarg!="") urlinforme="/catastrowebmogan/informes/informecargo.php4?refe="+refecata+"&cargo="+numecarg;
	else
		if(refecata!="") urlinforme="/catastrowebmogan/informes/informefinca.php4?refe="+refecata;
	
	if(urlinforme!=""){
		popup = window.open(urlinforme,"catastroweb","innerheight=550,innerwidth=790,width=790,height=550,status=yes,toolbar=no,menubar=no,resizable=no,scrollbars=yes");
		popup.focus;
		if (!popup.opener) {
			popup.opener = self;
		}
	}else
		alert("ERROR: no se ha indicado referencia catastral para mostrar el informe.");
}

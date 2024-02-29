/**************************************************************
infocata: 	Abre en una ventana nueva el informe de cargo o de
			finca de CatastroWeb en funci�n de los par�metros que
			se le pasen.
			Si se le pasa un s�lo par�metro lo toma como referencia
			catastral y mostrar� la ventana de 'informefinca'
			Si se le pasan dos par�metros el primero lo toma
			como referencia catastral y el segundo como numero de cargo
			y mostrar� la ventana de 'informecargo'
			
			Se usar� esta funci�n dentro del evento onclick del
			bot�n para tal efecto.
			
			PAR�METROS (en este orden):
			refecata: Referencia catastral de 14 d�gitos
			numecata: n�mero del cargo. 4 d�gitos (puede ser vacio)
			
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

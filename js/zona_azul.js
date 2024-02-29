function estadoBotonEnviar(){
	if(form.expediente.value.length > 0 && form.matricula.value.length > 0) { 
	    document.getElementById('boton_enviar_formulario').disabled = false; 
	} else { 
	    document.getElementById('boton_enviar_formulario').disabled = true;
	}
}

function enviarDatosBoletin(){
	
	const data = new FormData(form);
	data.append('importe', '4.45');//El importe es único
	const errorAjax = "No se ha podido procesar su solicitud";
	var botonEnviar = document.getElementById('div_boton_enviar_formulario');
	var botonEnviarOriginar = botonEnviar.innerHTML.substring(botonEnviar.innerHTML.indexOf("<table "));
	botonEnviar.innerHTML = "<span><img src='../../imagenes/loading.gif' alt='Smiley face' height='42' width='42'/><br>Conectando con la pasarela de pago...<br><br></span>"
	fetch('./no60/calculo_norma_60.php', {
		   method: 'POST',
		   body: data
		}).then(function(response) {
		   if(response.ok) {
		       return response.text()
		   } else {
		       throw errorAjax;
		   }
		}).then(function(respuesta) {
			var datosNorma60 = JSON.parse(respuesta);
			var matricula = data.get('matricula');
			var expediente = data.get("expediente");
			var ventanaEmergente =window.open('./pago_tarjeta_TPVBBVA.php?importe='+datosNorma60.importe+'&identificacion='+datosNorma60.identificacion+'&referencia='+datosNorma60.referencia+'&expediente='+expediente+'&matricula='+matricula);
			
			try {
				ventanaEmergente.focus();  
				botonEnviar.innerHTML = 'La pasarela se ha cargado correctamente para el expediente  '+expediente+'<br>'+botonEnviarOriginar;
				form.expediente.value='';
				form.matricula.value='';
				document.getElementById('boton_enviar_formulario').disabled = true;
		    } catch (e) {
		    	botonEnviar.innerHTML = 'No se ha podido conectar a la pasarela de pago debido a que las ventanas emergentes est&#225;n bloqueadas<br>'+botonEnviarOriginar;
		        alert("Por favor deshabilite el bloqueador de ventanas emergentes para www.gestmogan.com");
		    }
		}).catch(function(err) {
			console.log(err);
			alert(errorAjax);
			botonEnviar.innerHTML = botonEnviarOriginar;
		});
	
}
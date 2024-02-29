usuarioConsultas = "gestionmunicipal"
dominio = "gestmogan.com"
arroba = "@"
	
	
function union(usuario) {
	return usuario + arroba + dominio;
}

document.addEventListener("DOMContentLoaded", function(event) { 
	document.getElementById("correo").innerHTML = "<a href='mailto:" + union(usuarioConsultas)+ "' target='_blank'>"+ union(usuarioConsultas) +"</a>";
});

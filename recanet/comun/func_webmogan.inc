<?php
// Definicion de constantes de la aplicacion

// El tercer paremetro a 1 indica que la constante puede ser referenciada
// tanto en mayusculas como en minusculas
define("_GLOBAL_","-1",1);
define("RUTA","/var/www/vhosts/{$_SERVER['SERVER_NAME']}/httpdocs/recanet/",1);
define("RUTADATO","/var/datos/",1);

// relación de funciones genéricas a incluir en cada página
      
// para modularizar cada función en un archivo 
include "../comun/html.fnc";
include "../comun/cheqperm.fnc";
include "../comun/cheqsesi.fnc";
include "../comun/segu.fnc";
include "../comun/opci.fnc";
include "../comun/mueslist.fnc";
include "../comun/estaurlx.fnc";
include "../comun/sql_webmogan.fnc";
include "../comun/sqlxresu.fnc";
include "../comun/inicsesi.inc";
include "../comun/cheqroot.fnc";
include "../comun/cabecera_webmogan.fnc";
include "../comun/quitaespa.fnc";
include "../comun/convlist.fnc";
include "../comun/muesvent.fnc";
include "../comun/euro.fnc";
include "../comun/supe.fnc";
//include RUTA."comun/fecha.fnc";
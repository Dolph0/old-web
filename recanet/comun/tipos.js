// Fichero con definiciones sobre los tipos de datos de la interfaz de la
// aplicación (para generar automáticamente las cajas con cajatexto() desde
// PHP, entre otras cosas)

// Formato libre
var size_libre      = 20;
var maxlength_libre = 30;
var in_regex_libre  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_libre     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_libre   = "formato libre";

// Nombres (completos) de personas
var size_nomb      = 45;
var maxlength_nomb = 45;
var in_regex_nomb  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_nomb     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_nomb   = "nombre de sujeto";

// Apellidos de personas (o sólo los nombres)
var size_apel      = 20;
var maxlength_apel = 20;
var in_regex_apel  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_apel     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_apel   = "nombre/apellidos";

// Números
var size_nume      = 10;
var maxlength_nume = 10;
var in_regex_nume  = new RegExp("[0-9]", "");
var regex_nume     = new RegExp("^[0-9]*$", "");
var literal_nume   = "número";

// Números
var size_page      = 2;
var maxlength_page = 2;
var in_regex_page  = new RegExp("[0-9]", "");
var regex_page     = new RegExp("^[0-9]*$", "");
var literal_page   = "páginas";

// Números decimales
var size_deci      = 15;
var maxlength_deci = 15;
var in_regex_deci  = new RegExp("[0-9.,]", "");
var regex_deci     = new RegExp("^([0-9]+([,.][0-9]+)?)?$", "");
var literal_deci   = "número";

// Porcentajes
var size_porc      = 7;
var maxlength_porc = 7;
var in_regex_porc  = new RegExp("[^0-9.,]", "");
var regex_porc     = new RegExp("^([0-9]{1,3}([,.][0-9]+)?)?$", "");
var literal_porc   = "porcentaje";

// Cantidades de euros
var size_euro      = 20;
var maxlength_euro = 20;
var in_regex_euro  = new RegExp("[\-0-9,.]", "");
var regex_euro     = new RegExp("^-?[0-9]*([,.][0-9]{1,2})?$", "");
var literal_euro   = "euros";

// Superficies (metros cuadrados)
var size_supe      = 10;
var maxlength_supe = 10;
var in_regex_supe  = new RegExp("[0-9]", "");
var regex_supe     = new RegExp("^[0-9]*$", "");
var literal_supe   = "superficie";

// Superficies (hectáreas)
var size_hecta      = 15;
var maxlength_hecta = 15;
var in_regex_hecta  = new RegExp("[0-9.,]", "");
var regex_hecta     = new RegExp("^([0-9]+([,.][0-9]+)?)?$", "");
var literal_hecta   = "superficie";

// NIF
var size_nifx      = 10;
var maxlength_nifx = 9;
var in_regex_nifx  = new RegExp("[^;'\"`\\<\\]]", "i");
// Muchos NIF están mal
var regex_nifx     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_nifx   = "NIF/CIF";
// Formato real
//var regex_nifx     = new RegExp("^([0-9]{8}[A-Z]|[A-HNPQS][0-9]{7}[A-Z0-9]|X[0-9]{7}[A-Z]|[A-Z0-9]{9})?$", "");

// Horas
var size_hora      = 5;
var maxlength_hora = 5;
var in_regex_hora  = new RegExp("[0-9:]", "");
var regex_hora     = new RegExp("^(([01]?[0-9]|[2][0-3])[:]([0-5][0-9]))?$", "");
var literal_hora   = "hora";

// Fechas
var size_fech      = 10;
var maxlength_fech = 10;
var in_regex_fech  = new RegExp("[^0-9/.\-]", "");
var regex_fech     = new RegExp("^([0-9]{1,2}[/.-][0-9]{1,2}[/.-][0-9]{4})?$", "");
var literal_fech   = "fecha";

// Años
var size_anio      = 4;
var maxlength_anio = 4;
var in_regex_anio  = new RegExp("[0-9]", "");
var regex_anio     = new RegExp("^([0-9]{4})?$", "");
var literal_anio   = "año";

// Números de documento
var size_docu      = 9;
var maxlength_docu = 9;
var in_regex_docu  = new RegExp("[0-9a-z/\-]", "i");
var regex_docu     = new RegExp("^[0-9a-z/\-]*$", "i");
var literal_docu   = "número de documento";

// Numero de expediente de apremio
var size_expe       = 10;
var maxlength_expe  = 10;
var in_regex_expe  = new RegExp("[0-9]", "");
var regex_expe     = new RegExp("^[0-9]*$", "");
var literal_expe   = "número expediente de apremio";

// Números de folio
var size_foli      = 10;
var maxlength_foli = 10;
var in_regex_foli  = new RegExp("[0-9a-z/\-]", "i");
var regex_foli     = new RegExp("^[0-9a-z/\-]*$", "i");
var literal_foli   = "número de folio";

// Números de registro
var size_regi      = 10;
var maxlength_regi = 10;
var in_regex_regi  = new RegExp("[0-9a-z/\-]", "i");
var regex_regi     = new RegExp("^[0-9a-z/\-]*$", "i");
var literal_regi   = "número de registro";

// Nombres de vías
var size_viax      = 30;
var maxlength_viax = 35;
var in_regex_viax  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_viax     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_viax   = "vía";

// Correo electrónico
var size_mail      = 30;
var maxlength_mail = 45;
var in_regex_mail  = new RegExp("[0-9a-z@._-]", "i");
var regex_mail     = new RegExp("^([0-9A-Z-_.]+@[0-9A-Z_-]+(\\.[0-9A-Z_-]+)*\\.[A-Z]{2,4})?$", "");
var literal_mail   = "correo electrónico";

// Entidad bancaria (cuatro dígitos)
var size_enti      = 4;
var maxlength_enti = 4;
var in_regex_enti  = new RegExp("[0-9]", "");
var regex_enti     = new RegExp("^([0-9]{4})?$", "");
var literal_enti   = "entidad bancaria";

// Oficina (cuatro dígitos)
var size_ofic      = 4;
var maxlength_ofic = 4;
var in_regex_ofic  = new RegExp("[0-9]", "");
var regex_ofic     = new RegExp("^([0-9]{4})?$", "");
var literal_ofic   = "oficina bancaria";

// Caracter de control (dos dígitos)
var size_ccon      = 2;
var maxlength_ccon = 2;
var in_regex_ccon  = new RegExp("[0-9]", "");
var regex_ccon     = new RegExp("^([0-9]{2})?$", "");
var literal_ccon   = "caracter de control";

// Cuenta bancaria (diez dígitos)
var size_cuen      = 11;
var maxlength_cuen = 10;
var in_regex_cuen  = new RegExp("[0-9]", "");
var regex_cuen     = new RegExp("^([0-9]{10})?$", "");
var literal_cuen   = "cuenta bancaria";

// Código tributario
var size_trib      = 23;
var maxlength_trib = 20;
var in_regex_trib  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_trib     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_trib   = "código tributario";

// Caracter de control del inmueble (dos dígitos)
var size_cc      = 2;
var maxlength_cc = 2;
var in_regex_cc  = new RegExp("[^0-9A-Z]", "");
var regex_cc     = new RegExp("^([0-9A-Z]{2})?$", "");
var literal_cc   = "caracter de control";

// TIPOS PARA LAS DIRECCIONES POSTALES //////////////////////////////////

// Direccion no estructurada
var size_direnoes      = 30;
var maxlength_direnoes = 70;
var in_regex_direnoes  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_direnoes     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_direnoes   = "dirección";

// Sigla de la vía
var size_sigl      = 2;
var maxlength_sigl = 2;
var in_regex_sigl  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_sigl     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_sigl   = "sigla de vía";

// Nombre de la vía
var size_direnomb      = 26;
var maxlength_direnomb = 25;
var in_regex_direnomb  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_direnomb     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_direnomb   = "vía";

// Número del portal
var size_direnume      = 4;
var maxlength_direnume = 4;
var in_regex_direnume  = new RegExp("[^0-9]", "");
var regex_direnume     = new RegExp("^[0-9]*$", "");
var literal_direnume   = "portal";

// Letra
var size_direletr      = 1;
var maxlength_direletr = 1;
var in_regex_direletr  = new RegExp("[^A-Z]", "i");
var regex_direletr     = new RegExp("^[A-Z]*$", "i");
var literal_direletr   = "letra de portal";

// Escalera
var size_direesca      = 2;
var maxlength_direesca = 2;
var in_regex_direesca  = new RegExp("[^A-Z0-9-]", "i");
var regex_direesca     = new RegExp("^[A-Z0-9-]*$", "");
var literal_direesca   = "escalera";

// Planta
var size_direplan      = 3;
var maxlength_direplan = 3;
var in_regex_direplan  = new RegExp("[^A-Z0-9-+]", "i");
var regex_direplan     = new RegExp("^[A-Z0-9-+]*$", "");
var literal_direplan   = "planta";

// Puerta
var size_direpuer      = 3;
var maxlength_direpuer = 3;
var in_regex_direpuer  = new RegExp("[^A-Z0-9-]", "i");
var regex_direpuer     = new RegExp("^[A-Z0-9-]*$", "");
var literal_direpuer   = "puerta";

// Bloque
var size_direbloq      = 4;
var maxlength_direbloq = 4;
var in_regex_direbloq  = new RegExp("[a-z0-9]", "i");
var regex_direbloq     = new RegExp("^[A-Z0-9]*$", "");
var literal_direbloq   = "bloque";

// Portal
var size_direport      = 4;
var maxlength_direport = 4;
var in_regex_direport  = new RegExp("[a-z0-9]", "i");
var regex_direport     = new RegExp("^[A-Z0-9]*$", "");
var literal_direport   = "portal";

// Kilometro
var size_direkilo      = 5;
var maxlength_direkilo = 5;
var in_regex_direkilo  = new RegExp("[0-9.]", "");
var regex_direkilo     = new RegExp("^([0-9]{1,3}([.][0-9]{1,2})?)?$", "");
var literal_direkilo   = "kilómetro";

//codiviaxsuje
var size_codiviaxsuje      = 5;
var maxlength_codiviaxsuje = 5;
var in_regex_codiviaxsuje  = new RegExp("[0-9]", "");
var regex_codiviaxsuje     = new RegExp("^[0-9]*$", "");
var literal_codiviaxsuje   = "portal";


// Código postal
var size_direcodipost      = 5;
var maxlength_direcodipost = 5;
var in_regex_direcodipost  = new RegExp("[0-9]", "");
var regex_direcodipost     = new RegExp("^[0-9]*$", "");
var literal_direcodipost   = "código postal";

// Localidad
var size_direloca      = 25;
var maxlength_direloca = 25;
var in_regex_direloca  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_direloca     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_direloca   = "localidad";
// FIN DE LOS TIPOS PARA LAS DIRECCIONES POSTALES ///////////////////////

// Apartado de correos
var size_aparcorr      = 15;
var maxlength_aparcorr = 15;
var in_regex_aparcorr  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_aparcorr     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_aparcorr   = "apartado de correos";

// Referencia catastral
var size_cata      = 16;
var maxlength_cata = 14;
var in_regex_cata  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_cata     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_cata   = "referencia catastral";

// Número de cargo de la referencia catastral
var size_carg      = 4;
var maxlength_carg = 4;
var in_regex_carg  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_carg     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_carg   = "número de cargo";

// Número fijo
var size_nfij      = 9;
var maxlength_nfij = 9;
var in_regex_nfij  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_nfij     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_nfij   = "número fijo";
//var in_regex_nfij  = new RegExp("[0-9a-z]", "i");
//var regex_nfij     = new RegExp("^[0-9]{8}[a-z]$", "i");

// Nombre del inmueble
var size_inmu      = 35;
var maxlength_inmu = 35;
var in_regex_inmu  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_inmu     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_inmu   = "nombre de inmueble";

// Identificador del local
var size_loca      = 6;
var maxlength_loca = 6;
var in_regex_loca  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_loca     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_loca   = "identificador de local";


// Teléfono
var size_tele      = 10;
var maxlength_tele = 9;
var in_regex_tele  = new RegExp("[0-9]", "");
var regex_tele     = new RegExp("^([0-9]{9})?$", "");
var literal_tele   = "teléfono";

// Usuarios
var size_usua      = 20;
var maxlength_usua = 20;
var in_regex_usua  = new RegExp("[0-9A-ZÑÇ]", "i");
var regex_usua     = new RegExp("^[0-9A-ZÑÇ]*$", "");
var literal_usua   = "usuarios";

// Países
var size_pais      = 30;
var maxlength_pais = 30;
var in_regex_pais  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_pais     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_pais   = "país";

// Protocolo de plusvalía
var size_prot      = 10;
var maxlength_prot = 10;
var in_regex_prot  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_prot     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_prot   = "protocolo de plusvalía";

// Zona (vías)
var size_zona      = 25;
var maxlength_zona = 25;
var in_regex_zona  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_zona     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_zona   = "zona";

// Número de Gobierno (par)
var size_gobiparx      = 4;
var maxlength_gobiparx = 4;
var in_regex_gobiparx  = new RegExp("[0-9]", "i");
var regex_gobiparx     = new RegExp("^([0-9]*[02468])?$", "");
var literal_gobiparx   = "nº par";

// Número de Gobierno (impar)
var size_gobiimpa      = 4;
var maxlength_gobiimpa = 4;
var in_regex_gobiimpa  = new RegExp("[0-9]", "i");
var regex_gobiimpa     = new RegExp("^([0-9]*[13579])?$", "");
var literal_gobiimpa   = "nº impar";

// Campos de la gestion del IAE
var size_numerefe      = 16;
var maxlength_numerefe = 13;
var in_regex_numerefe  = new RegExp("[0-9]", "");
var regex_numerefe     = new RegExp("^[0-9]{13}$", "");
var literal_numerefe   = "Referencia";

// Causa de variacion (IAE)
var size_caus      = 19;
var maxlength_caus = 15;
var in_regex_caus  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_caus     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_caus   = "causa";

// Informacion adicional (IAE)
var size_infoadic      = 12;
var maxlength_infoadic = 9;
var in_regex_infoadic  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_infoadic     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_infoadic   = "informacion";

// 2 Números
var size_2N      = 2;
var maxlength_2N = 2;
var in_regex_2N  = new RegExp("[0-9]", "");
var regex_2N     = new RegExp("^([0-9]{0,2})$", "");
var literal_2N   = "2 números";

// 3 Números
var size_3N      = 3;
var maxlength_3N = 3;
var in_regex_3N  = new RegExp("[0-9]", "");
var regex_3N     = new RegExp("^([0-9]{0,3})$", "");
var literal_3N   = "3 números";

// 4 Números
var size_4N      = 4;
var maxlength_4N = 4;
var in_regex_4N  = new RegExp("[0-9]", "");
var regex_4N     = new RegExp("^([0-9]{0,4})$", "");
var literal_4N   = "4 números";

// 5 Números
var size_5N      = 5;
var maxlength_5N = 5;
var in_regex_5N  = new RegExp("[0-9]", "");
var regex_5N     = new RegExp("^([0-9]{0,5})$", "");
var literal_5N   = "5 números";

// 7 Números
var size_7N      = 7;
var maxlength_7N = 7;
var in_regex_7N  = new RegExp("[0-9]", "");
var regex_7N     = new RegExp("^([0-9]{0,7})$", "");
var literal_7N   = "7 números";

// 14 Números
var size_14N      = 14;
var maxlength_14N = 13;
var in_regex_14N  = new RegExp("[0-9]", "");
var regex_14N     = new RegExp("^([0-9]{0,13})$", "");
var literal_14N   = "14 números";

// 1 letra
var size_1X      = 1;
var maxlength_1X = 1;
var in_regex_1X  = new RegExp("[A-Z]", "");
var regex_1X     = new RegExp("^([A-Z]{1})?$", "");
var literal_1X   = "1 letra";

// 2 letra
var size_2X      = 2;
var maxlength_2X = 2;
var in_regex_2X  = new RegExp("[A-Z]", "");
var regex_2X     = new RegExp("^([A-Z]{0,2})$", "");
var literal_2X   = "2 letras";

// 70 letras
var size_70XN      = 70;
var maxlength_70XN = 70;
var in_regex_70XN  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "");
var regex_70XN     = new RegExp("^([A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]{0,70})$", "");
var literal_70XN   = "70 letras/numeros";

// Cantidades de elementos (se guardan en la base de datos con dos decimales, por ejemplo, oiaeobjeelem.cant)
var size_elemcant      = 20;
var maxlength_elemcant = 20;
var in_regex_elemcant  = new RegExp("[0-9,.]", "");
var regex_elemcant     = new RegExp("^[0-9]*([,.][0-9]{1,2})?$", "");
var literal_elemcant   = "cantidad";

// Matrículas de vehículos
var size_matr      = 11;
var maxlength_matr = 9;
var in_regex_matr  = new RegExp("[A-Z0-9ÑÇ ,:_.+*~/·&=%-]", "i");
var regex_matr     = new RegExp("^[A-Z0-9ÑÇ ,:_.+*~/·&=%-]*$", "");
var literal_matr   = "matrícula";

// Bastidores de vehículos
var size_bast      = 28;
var maxlength_bast = 21;
var in_regex_bast  = new RegExp("[A-Z0-9ÑÇ ,:_.+*~/·&=%-]", "i");
var regex_bast     = new RegExp("^[A-Z0-9ÑÇ ,:_.+*~/·&=%-]*$", "");
var literal_bast   = "bastidor";

// Areas de texto
var in_regex_area  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_area     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_area   = "area";

// Areas de texto con minúsculas
var in_regex_areamin  = new RegExp("[A-Za-z0-9ñÑÇáéíóúÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_areamin     = new RegExp("^[A-Za-z0-9ñÑÇáéíóúÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_areamin   = "areamin";

// Nombres de procesos
var size_procnomb      = 65;
var maxlength_procnomb = 60;
var in_regex_procnomb  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_procnomb     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_procnomb   = "nombre de proceso";

// Nombres de categorías de procesos
var size_catenomb      = 30;
var maxlength_catenomb = 25;
var in_regex_catenomb  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_catenomb     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_catenomb   = "categoría de proceso";

// Nombres de grupos de procesos
var size_grupnomb      = 30;
var maxlength_grupnomb = 25;
var in_regex_grupnomb  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_grupnomb     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_grupnomb   = "grupo de proceso";

// Nombres de fases (elementos) de los procesos
var size_elemnomb      = 45;
var maxlength_elemnomb = 40;
var in_regex_elemnomb  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_elemnomb     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_elemnomb   = "elementos de un proceso";

// Nombres de acronimos
var size_acronomb      = 8;
var maxlength_acronomb = 8;
var in_regex_acronomb  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_acronomb     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_acronomb   = "acronimo de proceso";

// Campo abreviatura de tamaño 8
var size_abre        = 11;
var maxlength_abre   = 8;
var in_regex_abre    = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_abre       = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_abre     = "abreviatura";

// Campo abreviatura de tamaño 12
var size_abre12      = 15;
var maxlength_abre12 = 12;
var in_regex_abre12  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_abre12     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_abre12   = "abreviatura";

// Campo Rerencia de la Tasa
var size_refetasa      = 25;
var maxlength_refetasa = 20;
var in_regex_refetasa  = new RegExp("[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]", "i");
var regex_refetasa     = new RegExp("^[A-Z0-9ÑÇÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛ ,:_¡!¿?.+*|()$[{}^~/ªº@#·&=%`´¨>-]*$", "");
var literal_refetasa   = "referencia de la tasa";

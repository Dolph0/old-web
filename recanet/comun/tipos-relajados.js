// Fichero con definiciones sobre los tipos de datos de la interfaz de la
// aplicación (para generar automáticamente las cajas con cajatexto() desde
// PHP, entre otras cosas)

// Formato libre
var size_libre      = 20;
var maxlength_libre = 30;
var in_regex_libre  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_libre     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_libre   = "";

// Nombres (completos) de personas
var size_nomb      = 45;
var maxlength_nomb = 45;
var in_regex_nomb  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_nomb     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_nomb   = "";

// Apellidos de personas (o sólo los nombres)
var size_apel      = 20;
var maxlength_apel = 20;
var in_regex_apel  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_apel     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_apel   = "";

// Números
var size_nume      = 10;
var maxlength_nume = 10;
var in_regex_nume  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_nume     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_nume   = "";

// Números decimales
var size_deci      = 15;
var maxlength_deci = 15;
var in_regex_deci  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_deci     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_deci   = "";

// Porcentajes
var size_porc      = 7;
var maxlength_porc = 7;
var in_regex_porc  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_porc     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_porc   = "";

// Cantidades de euros
var size_euro      = 20;
var maxlength_euro = 20;
var in_regex_euro  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_euro     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_euro   = "";

// NIF
var size_nifx      = 10;
var maxlength_nifx = 9;
var in_regex_nifx  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_nifx     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_nifx   = "";

// Fechas
var size_fech      = 10;
var maxlength_fech = 10;
var in_regex_fech  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_fech     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_fech   = "";

// Años
var size_anio      = 4;
var maxlength_anio = 4;
var in_regex_anio  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_anio     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_anio   = "";

// Números de documento
var size_docu      = 9;
var maxlength_docu = 9;
var in_regex_docu  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_docu     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_docu   = "";

// Numero de expediente de apremio
var size_expe       = 10;
var maxlength_expe  = 10;
var in_regex_expe  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_expe     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_expe   = "";

// Números de folio
var size_foli      = 10;
var maxlength_foli = 10;
var in_regex_foli  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_foli     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_foli   = "";

// Números de registro
var size_regi      = 10;
var maxlength_regi = 10;
var in_regex_regi  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_regi     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_regi   = "";

// Nombres de vías
var size_viax      = 30;
var maxlength_viax = 45;
var in_regex_viax  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_viax     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_viax   = "";

// Correo electrónico
var size_mail      = 30;
var maxlength_mail = 45;
var in_regex_mail  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_mail     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_mail   = "";

// Entidad bancaria (cuatro dígitos)
var size_enti      = 4;
var maxlength_enti = 4;
var in_regex_enti  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_enti     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_enti   = "";

// Oficina (cuatro dígitos)
var size_ofic      = 4;
var maxlength_ofic = 4;
var in_regex_ofic  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_ofic     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_ofic   = "";

// Caracter de control (dos dígitos)
var size_ccon      = 2;
var maxlength_ccon = 2;
var in_regex_ccon  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_ccon     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_ccon   = "";

// Cuenta bancaria (diez dígitos)
var size_cuen      = 10;
var maxlength_cuen = 10;
var in_regex_cuen  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_cuen     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_cuen   = "";

// Código tributario
var size_trib      = 23;
var maxlength_trib = 20;
var in_regex_trib  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_trib     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_trib   = "";

// Caracter de control del inmueble(dos dígitos)
var size_cc      = 2;
var maxlength_cc = 2;
var in_regex_cc  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_cc     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_cc   = "";

// TIPOS PARA LAS DIRECCIONES POSTALES //////////////////////////////////

// Direccion no estructurada
var size_direnoes      = 50;
var maxlength_direnoes = 70;
var in_regex_direnoes  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_direnoes     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_direnoes   = "";

// Sigla de la vía
var size_sigl      = 2;
var maxlength_sigl = 2;
var in_regex_sigl  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_sigl     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_sigl   = "";

// Nombre de la vía
var size_direnomb      = 25;
var maxlength_direnomb = 25;
var in_regex_direnomb  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_direnomb     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_direnomb   = "";

// Número del portal
var size_direnume      = 4;
var maxlength_direnume = 4;
var in_regex_nume  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_nume     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_nume   = "";

// Letra
var size_direletr      = 1;
var maxlength_direletr = 1;
var in_regex_direletr  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_direletr     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_direletr   = "";

// Escalera
var size_direesca      = 2;
var maxlength_direesca = 2;
var in_regex_direesca  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_direesca     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_direesca   = "";

// Planta
var size_direplan      = 3;
var maxlength_direplan = 3;
var in_regex_direplan  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_direplan     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_direplan   = "";

// Puerta
var size_direpuer      = 3;
var maxlength_direpuer = 3;
var in_regex_direpuer  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_direpuer     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_direpuer   = "";

//codiviaxsuje
var size_codiviaxsuje      = 5;
var maxlength_codiviaxsuje = 5;
var in_regex_codiviaxsuje  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_codiviaxsuje     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_codiviaxsuje   = "portal";



// Código postal
var size_direcodipost      = 5;
var maxlength_direcodipost = 5;
var in_regex_direcodipost  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_direcodipost     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_direcodipost   = "";

// Localidad
var size_direloca      = 25;
var maxlength_direloca = 25;
var in_regex_direloca  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_direloca     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_direloca   = "";
// FIN DE LOS TIPOS PARA LAS DIRECCIONES POSTALES ///////////////////////

// Apartado de correos
var size_aparcorr      = 15;
var maxlength_aparcorr = 15;
var in_regex_aparcorr  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_aparcorr     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_aparcorr   = "";

// Referencia catastral
var size_cata      = 16;
var maxlength_cata = 16;
var in_regex_cata  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_cata     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_cata   = "";

// Número de cargo de la referencia catastral
var size_carg      = 4;
var maxlength_carg = 4;
var in_regex_carg  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_carg     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_carg   = "";

// Número fijo
var size_nfij      = 9;
var maxlength_nfij = 9;
var in_regex_nfij  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_nfij     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_nfij   = "";

// Nombre del inmueble
var size_inmu      = 35;
var maxlength_inmu = 35;
var in_regex_inmu  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_inmu     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_inmu   = "";

// Identificador del local
var size_loca      = 6;
var maxlength_loca = 6;
var in_regex_loca  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_loca     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_loca   = "";

// Teléfono
var size_tele      = 9;
var maxlength_tele = 9;
var in_regex_tele  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_tele     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_tele   = "";

// Usuarios
var size_usua      = 20;
var maxlength_usua = 20;
var in_regex_usua  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_usua     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_usua   = "";

// Países
var size_pais      = 30;
var maxlength_pais = 30;
var in_regex_pais  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_pais     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_pais   = "";

// Protocolo de plusvalía
var size_prot      = 10;
var maxlength_prot = 10;
var in_regex_prot  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_prot     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_prot   = "";

// Zona (vías)
var size_zona      = 25;
var maxlength_zona = 25;
var in_regex_zona  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_zona     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_zona   = "";

// Número de Gobierno (par)
var size_gobiparx      = 4;
var maxlength_gobiparx = 4;
var in_regex_gobiparx  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_gobiparx     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_gobiparx   = "";

// Número de Gobierno (impar)
var size_gobiimpa      = 4;
var maxlength_gobiimpa = 4;
var in_regex_gobiimpa  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_gobiimpa     = new RegExp("^[^;'\"`\\<\\]]*$", "");
var literal_gobiimpa   = "";

// Cantidades de elementos (se guardan en la base de datos con dos decimales, por ejemplo, oiaeobjeelem.cant)
var size_elemcant      = 20;
var maxlength_elemcant = 20;
var in_regex_elemcant  = new RegExp("[^;'\"`\\<\\]]", "");
var regex_elemcant     = new RegExp("[^;'\"`\\<\\]]", "");
var literal_elemcant   = "cantidad";

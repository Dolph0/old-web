<?php
chdir ("/var/wwws/html/recanet");

include_once "liqu/carg.fnc";
include "comun/sql.fnc";
include "clas/config.inc";

$pid = pcntl_fork();
if ($pid == -1) {
  die("no se puede hacer fork"); 
} else if ($pid) {
  exit(); // somos el proceso padre
} else {
  // somos el proceso hijo
}

// detatch desde la terminal
if (!posix_setsid()) {
  die("no se puede hacer un detach desde la terminal");
}

// bucle infinito realizando tareas
while (1) {
  $SHM_KEY = ftok("/var/log/error20.log", chr( 7 ) );
  $shmid = sem_get($SHM_KEY, 1, 0644 | IPC_CREAT);
  sem_acquire($shmid);
  
  $SHM_KEY = ftok("/var/log/error20.log", chr( 5 ) ); 
  
  $data =  shm_attach($SHM_KEY, 2048, 0666);
  
  $numeregi = shm_get_var($data, 1);
  $usua = shm_get_var($data, 2);
  
  shm_remove($data);
  shm_detach($data);
  
  sem_release($shmid);
  
  // Usuario que genera el cargo
  global $codiusua;
  $codiusua = $usua;
  
  global $codiayun, $modoliqu, $periliqu, $tipoobje, $fechinicvolu, $fechfinavolu;
  global $fechinicvolu_2, $fechfinavolu_2, $liqudivi;
  //global $impoingr, $refeingr, $fechingr;
  
  if ($resp = sql("SELECT * FROM liqupadr WHERE liqupadr.numeregi = " . $numeregi)){
    $resp = each($resp);
    $resp = $resp[value];
    $codiayun = $resp[codiayun];	
    $modoliqu = 'PER';
    $periliqu = $resp[peri];
    $fechinicvolu = mostrarFecha($resp[fechinicvolu]);	
    $fechfinavolu = mostrarFecha($resp[fechfinavolu]);
    $impoingr = 0;
    $fechingr = '0001-01-01';
    $refeingr = '';
    $fechinicvolu_2 = mostrarFecha($resp[fechinicvolu_2]);	
    $fechfinavolu_2 = mostrarFecha($resp[fechfinavolu_2]);
    $liqudivi = $resp[liqudivi];	
  
    // Importe minimo si se divide la deuda
    if ($liqudivi == 1) {
      $config = &config::getInstance();
      $impomini_divi = $config->getValue('liquidación.dividir deuda.importe mínimo', $codiayun);
      
      if (!is_numeric ($impomini_divi)) $impomini_divi = 2147483647; // Max. valor entero
    } else {
      $impomini_divi = 2147483647; // Max. valor entero 
    }
  
    $resu_conctrib = sql("SELECT conctrib.tipoobje, conctrib.plazingrvolu FROM conctrib WHERE conctrib.codiconc = " . $resp[codiconc]);
    if (is_array ($resu_conctrib)) {
      $regi_conctrib = each($resu_conctrib);
      $tipoobje = $regi_conctrib[value][tipoobje];
      $plazingrvolu = $regi_conctrib[value][plazingrvolu];
    } else {
      $tipoobje = 'NO EXISTE CONCEPTO';
      $plazingrvolu = 0;
    }
  
    $query = "SELECT tipoobje.nombtabl, tipoobje.campsequ, tipoobje.campabre, tipoobje.tipoobje as tipoobje FROM tipoobje INNER JOIN conctrib ON tipoobje.tipoobje = conctrib.tipoobje WHERE conctrib.codiconc = $resp[codiconc]";
  
    $resp2 = sql($query);
    if ($resp2){
      $resp2 = each($resp2);
      $nombtabl = $resp2[value][nombtabl];
      $campsequ = $resp2[value][campsequ];
      $campabre = $resp2[value][campabre];
      $tipoobje = $resp2[value][tipoobje];
      $clav = pg_primaryKey($nombtabl);
    }
  
    $resu = sql("SELECT * FROM liqupadrobje WHERE liqupadrobje.codipadr = $numeregi");
    $fecha_del_cargo = date('Y-m-d', time());
    if ($resu){
      $cancelado = false;
      foreach ($resu as $dato => $value){
        $vect_subc = null; // Array aux. para dividir valor subxconceptos 
        $vect = array();
        $vect[deud] = $value[deud];
        $vect[anio]	= $resp[anio];
        $vect[codiconc] = $resp[codiconc];
        $vect[codiobje] = $value[codiobje];
        $vect[abreobje] = $value[coditrib];

        $resu_subx = sql("SELECT * FROM liqupadrsubc WHERE liqupadrsubc.codipadrobje = " . $value[codisequ]);
        if ($resu_subx){
          foreach($resu_subx as $dato2 => $value2) {
            $abre = sql("SELECT subxconc.abre FROM subxconc WHERE subxconc.codisubc = " . $value2[codisubc] );
            $vect[$abre] = $value2[subxdeud];
          
            // Tambien dividimos los subconceptos si hubiese
            if ($liqudivi == 1) {
              if ($vect[$abre] > 0) {
                // Paso 0: Divido la deuda
                $deudsubc_divi = $vect[$abre] % 2;
                
                if ($deudsubc_divi > 0) {
                  // Division con decimales
                  $deudsubc_divi_1 = floor($vect[$abre] / 2);
                  $deudsubc_divi_2 = floor($vect[$abre] / 2) + 1;
                } else {
                  // Division entera
                  $deudsubc_divi_1 = ($vect[$abre] / 2);
                  $deudsubc_divi_2 = $deudsubc_divi_1;
                }
              } else {
                $deudsubc_divi_1 = 0;
                $deudsubc_divi_2 = 0;
              }
              
              // Asigno valores subconcepto dividido
              $vect_subc[$abre][1] = $deudsubc_divi_1;
              $vect_subc[$abre][2] = $deudsubc_divi_2;
            }
          }
        }

        $resu2 = sql("SELECT domibancobje.bic,domibancobje.iban,domibancobje.numerefe,domibancobje.fechdomi FROM domibancobje WHERE domibancobje.codiobje = $value[codiobje] AND domibancobje.tipoobje = '$tipoobje'");
        if ($resu2){
          $resu2 = each($resu2);
          $resu2 = $resu2[value];
          $vect[domibanc] = 1;
          $vect[bic]  = $resu2[bic];
          $vect[iban] = $resu2[iban];
          $vect[fechdomi] = $resu2[fechdomi];
          $vect[refeno60] = $resu2[numerefe];				
        }else{
          $vect[domibanc] = 0;
          $vect[bic]  = '';
          $vect[iban] = '';
          $vect[fechdomi] = '0001-01-01';
          $vect[refeno60] = '';				
        }
      
        if (sql("SELECT count(*) FROM liqupadr WHERE liqupadr.fechintecarg <> '0001-01-01' AND liqupadr.numeregi = $numeregi") == 0){
          /// Se divide el cargo
          if ($liqudivi == 1) {
            // Se divide la deuda y los subconceptos si hubiese
            // Existe un importe minimo exigido para dividir la deuda
            if ($vect[deud] > $impomini_divi) {
              // Paso 0: Divido la deuda
              $deud_divi = $vect[deud] % 2;
              
              if ($deud_divi > 0) {
                // Division con decimales
                $deud_divi_1 = floor($vect[deud] / 2);
                $deud_divi_2 = floor($vect[deud] / 2) + 1;
              } else {
                // Division entera
                $deud_divi_1 = ($vect[deud] / 2);
                $deud_divi_2 = $deud_divi_1;
              }
            
              // Paso 1: Primer plazo de pago
              if ($vect_subc != null) {  
                // Subcuotas (1er. plazo)
                if ($resu_subx){
                  reset ($resu_subx);
                  $deud_divi_1 = 0;
                  foreach($resu_subx as $dato2 => $value2) {
                    $abre = sql("SELECT subxconc.abre FROM subxconc WHERE subxconc.codisubc = " . $value2[codisubc] );
                    
                    $vect[$abre] = $vect_subc[$abre][1];
                    $deud_divi_1 += $vect_subc[$abre][1];
                  }
                }
              }
              $vect[deud] = $deud_divi_1;
              $fechinicvolu = mostrarFecha($resp[fechinicvolu]); // Global en insecarg() 
              $fechfinavolu = mostrarFecha($resp[fechfinavolu]); // Global en insecarg()
              $periliqu = 'P1';	        
              insecarg ($vect, 0, '', 0, $fecha_del_cargo);
            
              // Paso 2: Segundo plazo de pago
              if ($vect_subc != null) {  
                // Subcuotas (2do. plazo)
                if ($resu_subx){
                  reset ($resu_subx);
                  $deud_divi_2 = 0;
                  foreach($resu_subx as $dato2 => $value2) {
                    $abre = sql("SELECT subxconc.abre FROM subxconc WHERE subxconc.codisubc = " . $value2[codisubc] );
                    
                    $vect[$abre] = $vect_subc[$abre][2];
                    $deud_divi_2 += $vect_subc[$abre][2];
                  }
                }
              }
              $vect[deud] = $deud_divi_2;
              $fechinicvolu = mostrarFecha($resp[fechinicvolu_2]); // Global en insecarg() 
              $fechfinavolu = mostrarFecha($resp[fechfinavolu_2]); // Global en insecarg()
              $periliqu = 'P2';
              insecarg ($vect, 0, '', 0, $fecha_del_cargo);
            
              $cont_cargos = 2;
            } else {
              switch ($plazingrvolu) {
                case 2:
                  $fechinicvolu = mostrarFecha($resp[fechinicvolu_2]); // Global en insecarg() 
                  $fechfinavolu = mostrarFecha($resp[fechfinavolu_2]); // Global en insecarg()
                  break;
                
                default:
                  $fechinicvolu = mostrarFecha($resp[fechinicvolu]); // Global en insecarg() 
                  $fechfinavolu = mostrarFecha($resp[fechfinavolu]); // Global en insecarg()
              }
              $periliqu = 'PA'; // Los cargos que no se dividen salen como ANUAL
              insecarg ($vect, 0, '', 0, $fecha_del_cargo);
              
              $cont_cargos = 1;      			
            }
          } else {
            // Un solo plazo de pago
            insecarg ($vect, 0, '', 0, $fecha_del_cargo);
            
            $cont_cargos = 1;
          }
        
          sql("UPDATE liqupadr SET numecarg = numecarg + $cont_cargos WHERE liqupadr.numeregi = $numeregi");
          
          if ($vect[domibanc] == 1){
            sql("UPDATE liqupadr SET numedomi = numedomi + $cont_cargos WHERE liqupadr.numeregi = $numeregi");
          }
        } else {
          $cancelado = true;
        }
      }
      
      if (!$cancelado){
        sql("UPDATE liqupadr SET fechfinacarg = '" . date('Y-m-d', time()) . "', horafinacarg = '" . date("H:i:s", time()) . "' WHERE liqupadr.numeregi = $numeregi");
        sql("DELETE FROM liqupadrobje WHERE liqupadrobje.codipadr = $numeregi");
      }
    }
  }
  
  exit();
}

function sig_handler($signo) {
  switch ($signo) {
    case SIGTERM:
      // tareas de finalización
      error_log("SIGTERM\n", 3, "/var/log/error.log");
      exit;
      break;
    case SIGHUP:
      error_log("SIGHUP\n", 3, "/var/log/error.log");
      // tareas de reinicio
      break;
    default:
      // tareas para las demás señales
  }
} // EndFunction sig_handler()

// congiguración de las señales
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");

?> 

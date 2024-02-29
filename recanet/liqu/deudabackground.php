<?php
chdir ("/var/wwws/html/recanet");

include "liqu/liqu.fnc";

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

$SHM_KEY = ftok("/var/log/error20.log", chr( 3 ) );
$shmid = sem_get($SHM_KEY, 1, 0666);
sem_acquire($shmid);
$SHM_KEY = ftok("/var/log/error20.log", chr( 4 ) ); 

$data =  shm_attach($SHM_KEY, 2048, 0666);

$query = shm_get_var($data, 1);
$anio = shm_get_var($data, 2);
$tipoobje = shm_get_var($data, 3);
$codiobje = shm_get_var($data, 4);
$concactu = shm_get_var($data, 5);
$modoliqu = shm_get_var($data, 6);
$periliqu = shm_get_var($data, 7);
$ayun = shm_get_var($data, 8);
$vectconc = shm_get_var($data, 9);
$cont = shm_get_var($data, 10);
$vect = shm_get_var($data, 11);
$fechaplibene = shm_get_var($data, 12);
$usua = shm_get_var($data, 13);
$fechinicvolu = shm_get_var($data, 14);
$fechfinavolu = shm_get_var($data, 15);
  $fechinicvolu_2 = shm_get_var($data, 16);
  $fechfinavolu_2 = shm_get_var($data, 17);
  $liqudivi = shm_get_var($data, 18);

shm_remove($data);
shm_detach($data);

sem_release($shmid);

  liquida($query, $anio, $tipoobje, $codiobje, $concactu, $modoliqu, $periliqu, $ayun, $usua, $vectconc, $cont - 1, $vect, $fechinicvolu, $fechfinavolu, $fechaplibene, 0, -1, $fechinicvolu_2, $fechfinavolu_2, $liqudivi);

exit();

}

function sig_handler($signo) 
{

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

}

// congiguración de las señales
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");

?> 

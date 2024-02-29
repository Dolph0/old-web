<?
   include_once "comun/sql.fnc";
   include_once "liqu/liqu.fnc";
   include_once "comun/cheqroot.fnc";
   include_once "comun/cheqperm.fnc";
   include_once "comun/fecha.fnc";
      
   # Comprueba si el usuario tiene permiso para entrar en la pagina
   if (isset($usua)){
       if (cheqperm( "gestliqu", $usua )){
	   		$accion = true;
       }else if (cheqperm( "ECOS", $usua )){
       	    $accion = false;       	    
       }else{
       		exit();
       }
   }
   if (isset($dataset)){
   	  if ($dataset == 'calcinic'){
      	 $resp = sql("SELECT ayun.codiayun FROM ayun WHERE ayun.codiayun = " . $ayun);
         if ($resp){
            print "var _targ=document.getElementById(\"" . $target . "\");";
           	$salida = "_targ.innerHTML = \"";
            $query = "SELECT conctrib.nomb as conc, 
                             usua.nomb as usuainic,
                             liqupadr.numeregi, liqupadr.codiconc, liqupadr.anio, liqupadr.peri, 
                             liquperi.nomb as perinomb, 
            					       liqupadr.fechiniccalc, liqupadr.horainiccalc, 
            		 			       liqupadr.numecalc, liqupadr.numeexcl, liqupadr.numeexen, liqupadr.numeproc, liqupadr.numedomi, 
            		 			       liqupadr.deudneto, liqupadr.cuotneto, liqupadr.domineto, liqupadr.numeboni,
            		 			       liqupadr.fechintecalc, liqupadr.horaintecalc, liqupadr.usuaintecalc, liqupadr.causintecalc,
            		 			       liqupadr.liqudivi
            					FROM ((liqupadr LEFT JOIN usua ON liqupadr.usuainiccalc = usua.codiusua) INNER JOIN liquperi ON liqupadr.peri = liquperi.abre)
            					INNER JOIN conctrib ON liqupadr.codiconc = conctrib.codiconc 
            					WHERE liqupadr.fechfinacalc = '0001-01-01' 
            					  AND liqupadr.codiayun = $ayun 
            					ORDER BY liqupadr.fechiniccalc, liqupadr.horainiccalc";
      	 
            $resu = sql($query);            					
            if ($resu){
            	$height = 30;
            	$width = 200;            	
           		$salida .= "<h5> PROCESOS DE CÁLCULO DE DEUDA EN EJECUCIÓN </h5>";
           		$i = 0;
            	foreach($resu as $dato => $value){
            		$textproc = '';
            		$salida .= "<table width=90% border=1><tr><td>";
            		$salida .= "<br><center>";
	            	$salida .= "<table width=75% cellpadding=4 cellspacing=1 border=0>";
	            	$salida .= "<tr><td class='tituform' colspan=3>" . utf8_encode ($value[conc]) . "  " . utf8_encode ($value[perinomb]) . "  " . $value[anio] . "</td></tr>";
//	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Concepto") . "</td><td class='cabeform'> Año </td><td class='cabeform'>" . utf8_encode ("Periodo") . "</td></tr>";
//	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[conc]) . "</td><td class='derform'>" . $value[anio] . "</td><td class='derform'>" . utf8_encode ($value[perinomb]) . "</td></tr>";
	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Fecha de inicio") . "</td><td class='cabeform'>" . utf8_encode ("Hora de inicio") . "</td><td class='cabeform'>" . utf8_encode ("Usuario") . "</td></tr>";
	            	$salida .= "<tr><td class='derform'>" . utf8_encode (mostrarFecha($value[fechiniccalc])) . "</td><td class='derform'>" . $value[horainiccalc] . "</td><td class='derform'>" . utf8_encode ($value[usuainic]) . "</td></tr>";
	            	$salida .= "<tr><td colspan=3>&nbsp;</td></tr>";
	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Objetos de partida") . "</td><td class='cabeform'>" . utf8_encode ("Excluidos") . "</td><td class='cabeform'>" . utf8_encode ("Exentos") . "</td></tr>";
	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[numecalc]) . "</td><td class='derform'>" . $value[numeexcl] . "</td><td class='derform'>" . utf8_encode ($value[numeexen]) . "</td></tr>";
	            	$salida .= "<tr><td class='cabeform' colspan=2>" . utf8_encode ("Objetos procesados") . "</td><td class='cabeform'>" . utf8_encode ("Dividir cargo")."</td></tr>";
	            	$salida .= "<tr><td class='derform' colspan=2>" . utf8_encode ($value[numeproc]) . "</td><td class='derform'>" . utf8_encode (($value[liqudivi] == 1?'SI':'NO')) . "</td></tr>";
	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Bonificaciones") . "</td><td class='cabeform'>" . utf8_encode ("Cuota total") . "</td><td class='cabeform'>" . utf8_encode ("Deuda total") . "</td></tr>";
	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[numeboni]) . "</td><td class='derform'>" . utf8_encode (impoboni($value[cuotneto])) . "</td><td class='derform'>" . utf8_encode (impoboni($value[deudneto])) . "</td></tr>";
	            	if ($value[fechintecalc] != '0001-01-01'){
		            	$salida .= "<tr><td class='cabeform'> Fecha interrupción </td><td class='cabeform'> Hora interrupción </td><td class='cabeform'>" . utf8_encode ("Usuario / Causa") . "</td></tr>";
		            	$salida .= "<tr><td class='derform'>" . utf8_encode (mostrarFecha($value[fechintecalc])) . "</td><td class='derform'>" . $value[horaintecalc] . "</td><td class='derform'>" . utf8_encode (sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = " . $value[usuaintecalc]) . " / " . $value[causintecalc]) . "</td></tr>";
		            	if (compfech(mostrarFecha($value[fechintecalc]), mostrarFecha($value[fechiniccalc])) != 2 || (compfech(mostrarFecha($value[fechintecalc]), mostrarFecha($value[fechiniccalc])) == 0 && strtotime($value[horainiccalc]) < strtotime($value[horaintecalc])))
			            	$textproc = "Cancelado";
            		}
	            	$salida .= "</table><br>";
	            	$salida .= "<center>";
	            	$salida .= "<table width=75% cellpadding=4 cellspacing=1 border=0>";
	            	$salida .= "<tr><td colspan = 4><b>" . utf8_encode ("Estado actual del proceso") . "</b></td></tr>";
	            	$salida .= "<tr><td>";
     			    $tailletxt=$height-10; 
     			    $salida .= "<div id='barcontent" . $i . "' style='position:relative;top:0px";
					$salida .= ";left:0px"; 
					$salida .= ";width:".$width."px"; 
					$salida .= ";height:".($height + 1)."px"; 
					$salida .= ";border:1px solid #FFD8B1;background-color:#D8D8D8;'>";
					$procesados = ($value[numeproc] + $value[numeexen]) / $value[numecalc]; 
					$widthporc = intval($procesados * $width);
					$salida .= "<div id='progrbar" . $i . "' style='position:absolute;top:0px;"; //+1 
					$salida .= ";left:0px";
					$salida .= ";width:" . $widthporc . "px"; 
					$salida .= ";height:".$height."px"; 
					$salida .= ";background-color:#FFD8B1;'>";
					$salida .= "<div id='porcent" . $i . "' style='position:absolute;top:0px"; 
					$salida .= ";left:0px"; 
					$salida .= ";width:".$width."px"; 
					$salida .= ";height:".$height."px;font-weight:bold"; 
					$procesados = $procesados * 100;
					if ($procesados > 100) $procesados = 100;
					if ($textproc == ''){
						$textproc = "Calculando " . intval($procesados) . "%";
					}
					$salida .= ";font-size:".$tailletxt."px;color:#FFFFFF;text-align:center;'>" . $textproc . "</div>"; 
					  
					$salida .= "</div>";
					$salida .= "</div>"; 	            	
	            	$salida .= "</td>";
	            	if ($accion){
		            	$salida .= "<td>";
		            	$salida .= "<input type='button' value='Reiniciar' onClick='iniciaproc(" . $value[numeregi] . ",$usua);'>";
		            	$salida .= "</td>";	            	
		            	$salida .= "<td>";
		            	$salida .= "<input type='button' value='Cancelar' onClick='cancelaproc(" . $value[numeregi] . ",$usua);'>";
		            	$salida .= "</td>";	            	
		            	$salida .= "<td>";
		            	$salida .= "<input type='button' value='Eliminar' onClick='eliminaproc(" . $value[numeregi] . ",$usua);'>";
		            	$salida .= "</td>";
	            	}	            	
	            	$salida .= "</tr>";
	            	$salida .= "</table>";
	            	$salida .= "</center><br>";
	            	$salida .= "</center></td></tr></table>";	            	
	            	$salida .= "<br><br>";
	            	$i++;
            	}
            }
            $query = "SELECT conctrib.nomb as conc,
                             usua.nomb as usuainiccarg,
                             liqupadr.numeregi,  
                             liqupadr.codiconc, liqupadr.anio, liqupadr.peri, 
                             liquperi.nomb as perinomb, 
            					       liqupadr.fechiniccalc, liqupadr.horainiccalc, liqupadr.usuainiccalc, 
            					       liqupadr.fechfinacalc, liqupadr.horafinacalc, liqupadr.fechiniccarg, liqupadr.horainiccarg, 
            					       liqupadr.numecalc, liqupadr.numeexcl, liqupadr.numeexen, liqupadr.numeproc, 
            					       liqupadr.numecarg, liqupadr.numeboni, 
            					       liqupadr.numedomi, liqupadr.domineto, 
            					       liqupadr.deudneto, liqupadr.cuotneto,
            		 			       liqupadr.fechintecarg, liqupadr.horaintecarg, liqupadr.usuaintecarg, liqupadr.causintecarg,
            		 			       liqupadr.liqudivi
            					FROM ((liqupadr LEFT JOIN usua ON liqupadr.usuainiccarg = usua.codiusua) INNER JOIN liquperi ON liqupadr.peri = liquperi.abre)
            					INNER JOIN conctrib ON liqupadr.codiconc = conctrib.codiconc 
                      WHERE liqupadr.fechfinacalc <> '0001-01-01' 
                        AND liqupadr.fechfinacarg = '0001-01-01' 
                        AND liqupadr.codiayun = $ayun 
                      ORDER BY liqupadr.fechfinacalc, liqupadr.horafinacalc";
            $resu = sql($query);            					
            if ($resu){
            	$height = 30;
            	$width = 200;            	
           		$salida .= "<h5> PROCESOS DE CÁLCULO DE DEUDA FINALIZADOS </h5>";
           		$i = 0;
            	foreach($resu as $dato => $value){
            		$textproc = '';
            		$salida .= "<table width=90% border=1><tr><td>";
            		$salida .= "<br><center>";
	            	$salida .= "<table width=75% cellpadding=4 cellspacing=1 border=0>";
	            	$salida .= "<tr><td class='tituform' colspan=3> $value[conc] $value[perinomb] $value[anio] </td></tr>";
/*	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Concepto") . "</td><td class='cabeform'> Año </td><td class='cabeform'>" . utf8_encode ("Periodo") . "</td></tr>";
	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[conc]) . "</td><td class='derform'>" . $value[anio] . "</td><td class='derform'>" . utf8_encode ($value[perinomb]) . "</td></tr>";*/
	            	$salida .= "<tr><td class='cabeform' colspan=2> Inicio del cálculo </td><td class='cabeform'>" . utf8_encode ("Usuario") . "</td></tr>";
	            	$usuainic = sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = " . $value[usuainiccalc]);
	            	$salida .= "<tr><td class='derform'>" . utf8_encode (mostrarFecha($value[fechiniccalc])) . "</td><td class='derform'>" . $value[horainiccalc] . "</td><td class='derform'>" . utf8_encode ($usuainic) . "</td></tr>";
	            	$salida .= "<tr><td class='cabeform' colspan=2> Finalización del cálculo </td><td class='cabeform'>" . utf8_encode ("Usuario") . "</td></tr>";
	            	$salida .= "<tr><td class='derform'>" . utf8_encode (mostrarFecha($value[fechfinacalc])) . "</td><td class='derform'>" . $value[horafinacalc] . "</td><td class='derform'>" . utf8_encode ($usuainic) . "</td></tr>";
	            	if ($value[fechiniccarg] != '0001-01-01'){
		            	$salida .= "<tr><td class='cabeform' colspan=2>" . utf8_encode ("Inicio del cargo") . "</td><td class='cabeform'>" . utf8_encode ("Usuario") . "</td></tr>";
		            	$salida .= "<tr><td class='derform'>" . utf8_encode (mostrarFecha($value[fechiniccarg])) . "</td><td class='derform'>" . $value[horainiccarg] . "</td><td class='derform'>" . utf8_encode ($value[usuainiccarg]) . "</td></tr>";
	            	}else{
	            		$textproc = "Sin aceptar cargo";	            		
	            	}
	            	$salida .= "<tr><td colspan=3>&nbsp;</td></tr>";
	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Objetos de partida") . "</td><td class='cabeform'>" . utf8_encode ("Excluidos") . "</td><td class='cabeform'>" . utf8_encode ("Exentos") . "</td></tr>";
	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[numecalc]) . "</td><td class='derform'>" . $value[numeexcl] . "</td><td class='derform'>" . utf8_encode ($value[numeexen]) . "</td></tr>";
	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Objetos procesados") . "</td><td class='cabeform'>" . utf8_encode ("Recibos") . "</td><td class='cabeform'> ".utf8_encode ('Dividir cargo')." </td></tr>";
	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[numeproc]) . "</td><td class='derform'>" . $value[numecarg] . "</td><td class='derform'> " . utf8_encode (($value[liqudivi] == 1?'SI':'NO')) . "</td></tr>";
	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Bonificaciones") . "</td><td class='cabeform'>" . utf8_encode ("Cuota total") . "</td><td class='cabeform'>" . utf8_encode ("Deuda total") . "</td></tr>";
	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[numeboni]) . "</td><td class='derform'>" . utf8_encode (impoboni($value[cuotneto])) . "</td><td class='derform'>" . utf8_encode (impoboni($value[deudneto])) . "</td></tr>";
//	            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Objetos") . "</td><td class='cabeform'>" . utf8_encode ("Excluidos") . "</td><td class='cabeform'>" . utf8_encode ("Exentos") . "</td></tr>";
//	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[numeproc]) . "</td><td class='derform'>" . $value[numeexcl] . "</td><td class='derform'>" . utf8_encode ($value[numeexen]) . "</td></tr>";
	            	if ($value[fechintecarg] != '0001-01-01'){
		            	$salida .= "<tr><td class='cabeform'> Fecha interrupción </td><td class='cabeform'> Hora interrupción </td><td class='cabeform'>" . utf8_encode ("Usuario / Causa") . "</td></tr>";
		            	$salida .= "<tr><td class='derform'>" . utf8_encode (mostrarFecha($value[fechintecarg])) . "</td><td class='derform'>" . $value[horaintecarg] . "</td><td class='derform'>" . utf8_encode ( sql("SELECT usua.nomb FROM usua WHERE usua.codiusua = " . $value[usuaintecarg]) . " / " . $value[causintecarg]) . "</td></tr>";
		            	if (compfech(mostrarFecha($value[fechintecarg]), mostrarFecha($value[fechiniccarg])) != 2 || (compfech(mostrarFecha($value[fechintecarg]), mostrarFecha($value[fechiniccarg])) == 0 && strtotime($value[horainiccarg]) < strtotime($value[horaintecarg])))
		            		$textproc = "Cancelado";
            		}            		            
	            	$salida .= "</table><br>";
	            	$salida .= "<center>";
	            	$salida .= "<table width=75% cellpadding=4 cellspacing=1 border=0>";
	            	$salida .= "<tr><td colspan = 4><b>" . utf8_encode ("Estado actual del proceso") . "</b></td></tr>";
	            	$salida .= "<tr><td>";
     			    $tailletxt=$height-10; 
     			    $salida .= "<div id='barcontcarg" . $i . "' style='position:relative;top:0px";
					$salida .= ";left:0px"; 
					$salida .= ";width:".$width."px"; 
					$salida .= ";height:".($height + 1)."px"; 
					$salida .= ";border:1px solid #FFD8B1;background-color:#D8D8D8;'>";
					if ($value[numeproc] == 0) {
					  $procesados = 0;
					}	else {
                  if ($value[liqudivi] == 1) {
                    $procesados = $value[numecarg] / ($value[numeproc] * 2); 
                  } else {
			  		$procesados = $value[numecarg] / $value[numeproc]; 
					}
                }
					$widthporc = intval($procesados * $width);
					$salida .= "<div id='progrbarcarg" . $i . "' style='position:absolute;top:0px;"; //+1 
					$salida .= ";left:0px";
					$salida .= ";width:" . $widthporc . "px"; 
					$salida .= ";height:".$height."px"; 
					$salida .= ";background-color:#FFD8B1;'>";
					$salida .= "<div id='porcentcarg" . $i . "' style='position:absolute;top:0px"; 
					$salida .= ";left:0px"; 
					$salida .= ";width:".$width."px"; 
					$salida .= ";height:".$height."px;font-weight:bold"; 
					$procesados = $procesados * 100;
					if ($procesados > 100) $procesados = 100;
					if ($textproc == ''){
						$textproc = "Cargando " . intval($procesados) . "%";
					}
					$salida .= ";font-size:".$tailletxt."px;color:#FFFFFF;text-align:center;'>" . $textproc . "</div>"; 
					  
					$salida .= "</div>";
					$salida .= "</div>"; 	            	
	            	$salida .= "</td>";
	            	$salida .= "<td>";
	            	$salida .= "<input type='button' value='Listar valores' onClick='listcarg(" . $value[numeregi] . ",$usua);'>";
	            	$salida .= "</td>";	       
	            	$disabled = '';     	
	            	if (($value[fechiniccarg] != '0001-01-01' && $value[fechintecarg] == '0001-01-01') || (compfech(mostrarFecha($value[fechiniccarg]), mostrarFecha($value[fechintecarg])) == 1) || (compfech(mostrarFecha($value[fechiniccarg]), mostrarFecha($value[fechintecarg])) == 0 && strtotime($value[horainiccarg]) > strtotime($value[horaintecarg]))){
	            		$disabled = "disabled";
            	    }
            	    if ($accion){
		            	$salida .= "<td>";
		            	$salida .= "<input type='button' value='Aceptar cargo' " . $disabled . " onClick='iniccarg(" . $value[numeregi] . ",$usua);'>";
		            	$salida .= "</td>";	            	
		            	$salida .= "<td>";
		            	$salida .= "<input type='button' value='Cancelar' onClick='canccarg(" . $value[numeregi] . ",$usua);'>";
		            	$salida .= "</td>";	            	
		            	$salida .= "<td>";
		            	$salida .= "<input type='button' value='Eliminar' onClick='elimcarg(" . $value[numeregi] . ",$usua);'>";
		            	$salida .= "</td>";
            	    }	            	
	            	$salida .= "</tr>";
	            	$salida .= "</table>";
	            	$salida .= "</center><br>";
	            	$salida .= "</center></td></tr></table>";	            	
	            	$salida .= "<br><br>";
	            	$i++;
            	}
            }
            $query = "SELECT liqupadr.numeregi, 
                             liqupadr.anio, 
                             conctrib.nomb as conc, 
                             liquperi.nomb as perinomb 
            					FROM (liqupadr INNER JOIN liquperi ON liqupadr.peri = liquperi.abre)
            					INNER JOIN conctrib ON liqupadr.codiconc = conctrib.codiconc 
                      WHERE liqupadr.fechfinacarg <> '0001-01-01' 
                        AND liqupadr.codiayun = $ayun
                        AND (to_char(liqupadr.fechfinacarg::date, 'YYYY') = '" . date('Y', time()) . "' OR 
                             to_char(liqupadr.fechfinacarg::date, 'YYYY') = '" . (intval(date('Y', time())) - 1) ."')
                      ORDER BY liqupadr.fechfinacarg DESC, liqupadr.horafinacarg DESC";
            $resu = sql($query);            					
            if ($resu){
            	$height = 30;
            	$width = 200;            	
           		$salida .= "<h5>" . utf8_encode ("PADRONES CARGADOS EN EL SISTEMA") . "</h5>";
           		$i = 0;
            	$salida .= "<table width=75% cellpadding=4 cellspacing=1 border=0>";
            	$salida .= "<tr><td class='cabeform'>" . utf8_encode ("Concepto") . "</td><td class='cabeform'>" . utf8_encode ("Periodo") . "</td><td class='cabeform' colspan=2> Año </td></tr>";
            	foreach($resu as $dato => $value){
	            	$salida .= "<tr><td class='derform'>" . utf8_encode ($value[conc]) . "</td><td class='derform'>" . utf8_encode ($value[perinomb]) . "</td><td class='derform'>" . $value[anio] . "</td><td class='derform' align='right'><input type=button value='Ver detalles' onClick='listhist(" . $value[numeregi] . ",$usua);'></td></tr>";            		
            	}
            	$salida .= "</table><br>";
            }
            
	        $salida .= "\";";
            print $salida;	 
            print "setTimeout('refrcalcinic(" . $ayun . ", " . $usua . ")', 7000);";                   	
         }

      }
      if ($dataset=='canccalc'){
      	  if ($numeregi && $numeregi > 0){
      	  	if (sql("SELECT count(*) FROM liqupadr WHERE liqupadr.numeregi = $numeregi and liqupadr.fechintecalc = '0001-01-01' and liqupadr.fechfinacalc = '0001-01-01'") > 0){
	      	  	$resp = sql("SELECT liqupadr.usuainiccalc FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  	if ($resp == $usua){
		      	  	$hoy = date("Y-m-d", time());
		      	  	$hora = date ("H:i:s", time());
		      	  	$query = "UPDATE liqupadr set fechintecalc='$hoy', horaintecalc='$hora', usuaintecalc='$usua', causintecalc='CANCELAR' WHERE ";
		      	  	$query .= "liqupadr.numeregi = $numeregi";
		      	  	sql($query);
	      	  	}else{
	      	  		print "alert('Solo se permite cancelar el proceso al usuario que lo inició.');";
	      	  	}
      	  	}else{
      	  		print "alert('" . utf8_encode("El proceso ha finalizado o ha sido ya cancelado.") . "');";
      	    }	      	  	
      	  }
      }
      if ($dataset=='iniccalc'){
      	  if ($numeregi && $numeregi > 0){
      	  	if (sql("SELECT count(*) FROM liqupadr WHERE liqupadr.numeregi = $numeregi") > 0){
	      	  	$resp = sql("SELECT liqupadr.usuainiccalc FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  	if ($resp == $usua){
	      	  		$resp = sql("SELECT * FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  		if ($resp){
	      	  			$resp = each($resp);
			      	  	$hoy = date("Y-m-d", time());
			      	  	$hora = date ("H:i:s", time());
	      	  			if ($resp[fechintecalc] == '0001-01-01'){
				      	  	$query = "UPDATE liqupadr set fechintecalc='$hoy', horaintecalc='$hora', usuaintecalc='$usua', causintecalc='REINICIAR', numeproc='-1' WHERE ";
				      	  	$query .= "liqupadr.numeregi = $numeregi; ";
	      	  			}else{
	      	  				$query = "DELETE FROM liqupadr WHERE liqupadr.numeregi = $numeregi;";

							$querypadr = creapadrquery('', $resp[value][codiayun], $resp[value][codiconc], '', '', $resp[value][peri], $resp[value][anio], 'PER');
							if ($querypadr != ''){
								//Lanzamos de nuevo un proceso hijo
	    			    		$SHM_KEY = ftok("/var/log/error20.log", chr( 4 ) );
								 
								$data =  shm_attach($SHM_KEY, 2048, 0666);
											 
								shm_put_var($data, 1, $querypadr);
								shm_put_var($data, 2, $resp[value][anio]);
								shm_put_var($data, 3, sql("SELECT conctrib.tipoobje FROM conctrib WHERE conctrib.codiconc = " . $resp[value][codiconc]));
								shm_put_var($data, 4, $codiobje);
								shm_put_var($data, 5, $resp[value][codiconc]);
								shm_put_var($data, 6, 'PER');
								shm_put_var($data, 7, $resp[value][peri]);
								shm_put_var($data, 8, $resp[value][codiayun]);
								shm_put_var($data, 9, $vectconc);
								shm_put_var($data, 10, $cont);
								shm_put_var($data, 11, $vect);
								shm_put_var($data, 12, $fechaplibene);
								shm_put_var($data, 13, $usua);
								shm_put_var($data, 14, $resp[value][fechinicvolu]);
								shm_put_var($data, 15, $resp[value][fechfinavolu]);
								shm_put_var($data, 16, $resp[value][fechinicvolu_2]);
								shm_put_var($data, 17, $resp[value][fechfinavolu_2]);
								shm_put_var($data, 18, $resp[value][liqudivi]);
										 
								shm_detach($data);
						      	
						      	$command = 'php ' . getFullPath("liqu/deudabackground.php") . ' > /var/log/error2.log 2> /var/log/error2.log &';
						      	system($command);
						      	
							}else{
								mensliqu("Hubo un error al generar el query de padrón", 'PER');
							}	      	  				
	      	  			}
	      	  			$query .= "INSERT INTO liqupadrhist (codiayun, codiconc,
  															anio,peri,
  															fechiniccalc,horainiccalc,
  															usuainiccalc, fechfinacalc,
  														    fechintecalc, horaintecalc,
  															usuaintecalc, causintecalc";
  						if ($resp[value][fechintecarg] != '0001-01-01'){
  							$query .= ",fechintecarg,horaintecarg,
  										causintecarg";  							
  						}
  						$query .= ") ";							
  										
  						$query .= "							VALUES ('" . $resp[value][codiayun] . "', '" . $resp[value][codiconc] . "',
  															'" . $resp[value][anio] . "','" . $resp[value][peri] . "',
  															'" . $resp[value][fechiniccalc] . "', '" . $resp[value][horainiccalc] . "',
  															'" . $resp[value][usuainiccalc] . "', '" . $resp[value][fechfinacalc] . "',
  														    '" . $hoy . "', '" . $hora . "',
  															'" . $usua . "', 'REINICIAR'";
  						if ($resp[value][fechintecarg] != '0001-01-01'){
  							$query .= ",'" . $resp[value][fechintecarg] . "', '" . $resp[value][horaintecarg] . "',
  										'" . $resp[value][causintecarg] . "'";  							
  						}									
  						$query .= ")";
			      	  	sql($query);
	      	  		}
	      	  	}else{
	      	  		print "alert('Solo se permite eliminar el proceso al usuario que lo inició.');";
	      	  	}
      	  	}else{
      	  		print "alert('El proceso está aún activo');";
      	    }	      	  	
      	  }      	
      }
      if ($dataset=='elimcalc'){
      	  if ($numeregi && $numeregi > 0){
      	  	if (sql("SELECT count(*) FROM liqupadr WHERE liqupadr.numeregi = $numeregi and liqupadr.fechintecalc <> '0001-01-01'") > 0){
	      	  	$resp = sql("SELECT liqupadr.usuainiccalc FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  	if ($resp == $usua){
	      	  		$resp = sql("SELECT * FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  		if ($resp){
	      	  			$resp = each($resp);
			      	  	$hoy = date("Y-m-d", time());
			      	  	$hora = date ("H:i:s", time());
			      	  	$query .= "DELETE FROM liqupadr WHERE ";
			      	  	$query .= "liqupadr.numeregi = $numeregi; ";
	      	  			$query .= "INSERT INTO liqupadrhist (codiayun, codiconc,
  															anio,peri,
  															fechiniccalc,horainiccalc,
  															usuainiccalc, fechfinacalc,
  														    fechintecalc, horaintecalc,
  															usuaintecalc, causintecalc";
  						if ($resp[value][fechintecarg] != '0001-01-01'){
  							$query .= ",fechintecarg,horaintecarg,
  										causintecarg";  							
  						}
  						$query .= ") ";							
  										
  						$query .= "							VALUES ('" . $resp[value][codiayun] . "', '" . $resp[value][codiconc] . "',
  															'" . $resp[value][anio] . "','" . $resp[value][peri] . "',
  															'" . $resp[value][fechiniccalc] . "', '" . $resp[value][horainiccalc] . "',
  															'" . $resp[value][usuainiccalc] . "', '" . $resp[value][fechfinacalc] . "',
  														    '" . $hoy . "', '" . $hora . "',
  															'" . $usua . "', 'ELIMINAR'";
  						if ($resp[value][fechintecarg] != '0001-01-01'){
  							$query .= ",'" . $resp[value][fechintecarg] . "', '" . $resp[value][horaintecarg] . "',
  										'" . $resp[value][causintecarg] . "'";  							
  						}									
  						$query .= ")";
			      	  	sql($query);
	      	  		}
	      	  	}else{
	      	  		print "alert('Solo se permite eliminar el proceso al usuario que lo inició.');";
	      	  	}
      	  	}else{
      	  		print "alert('El proceso está aún activo.');";
      	    }	      	  	
      	  }
      }
  	  if ($dataset=='iniccarg'){
      	  if ($numeregi && $numeregi > 0){
      	  	if (sql("SELECT count(*) FROM liqupadr WHERE liqupadr.numeregi = $numeregi") > 0){
	      	  	$resp = sql("SELECT liqupadr.usuainiccalc FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  	if ($resp == $usua){
	      	  		$resp = sql("SELECT * FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  		if ($resp){
	      	  			$resp = each($resp);
	      	  			$resp = $resp[value];
			      	  	$hoy = date("Y-m-d", time());
			      	  	$hora = date ("H:i:s", time());
	      	  			if ($resp[fechiniccarg] == '0001-01-01' || ($resp[fechiniccarg] != '0001-01-01' && $resp[fechintecarg] != '0001-01-01')){
                    $query = "UPDATE liqupadr SET fechiniccarg='$hoy', horainiccarg='$hora', usuainiccarg='$usua', fechintecarg = '0001-01-01', horaintecarg = '00:00:00', usuaintecarg = 0, causintecarg = '', numecarg = 0, numedomi = 0 ";
                    $query .= "WHERE liqupadr.numeregi = $numeregi; ";

					      	// Semaforo para otros procesos que quieran
					      	// crear cargos de un padrón     		
				    		$SHM_KEY = ftok("/var/log/error20.log", chr( 6 ) );
				    		$shmid = sem_get($SHM_KEY, 1, 0644 | IPC_CREAT);
				    		sem_acquire($shmid);
				
							// Semaforo para el proceso hijo
				    		$SHM_KEY = ftok("/var/log/error20.log", chr( 7 ) );
				    		$shmid2 = sem_get($SHM_KEY, 1, 0644 | IPC_CREAT);
				    		sem_acquire($shmid2);
				    		
							//Lanzamos de nuevo un proceso hijo
    			    		$SHM_KEY = ftok("/var/log/error20.log", chr( 5 ) );
							 
							$data =  shm_attach($SHM_KEY, 2048, 0666);
										 
							shm_put_var($data, 1, $numeregi);
							shm_put_var($data, 2, $usua);
									 
							shm_detach($data);
					      	
                    $command = 'php ' . getFullPath("liqu/cargbackground.php") . ' > /var/log/error2.log 2> /var/log/error2.log &';
					      	system($command);
					      	sem_release($shmid2);
					      	sleep(1);

					      	// Esperamos por que el proceso hijo lea la memoria compartida
					      	sem_acquire($shmid2);
					      	sem_release($shmid2);

					      	// Permitimos que otros procesos se ejecuten
					      	sem_release($shmid);
						    sql($query);  	
	      	  			}else{
	      	  				print "alert('" . utf8_encode("El proceso ha sido ya iniciado.") . "');";
	      	  			}
	      	  		}
	      	  	}else{
	      	  		print "alert('Solo se permite iniciar el proceso de cargo al usuario que inició el cálculo.');";
	      	  	}
      	  	}else{
      	  		print "alert('El proceso está aún activo.');";
      	    }	      	  	
      	  }      	
  	  }
  	  if ($dataset=='canccarg'){
      	  if ($numeregi && $numeregi > 0){
      	  	if (sql("SELECT count(*) FROM liqupadr WHERE liqupadr.numeregi = $numeregi and liqupadr.fechiniccarg <> '0001-01-01' and liqupadr.fechfinacarg = '0001-01-01'") > 0){
	      	  	$resp = sql("SELECT liqupadr.usuainiccarg FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  	if ($resp == $usua){
		      	  	$hoy = date("Y-m-d", time());
		      	  	$hora = date ("H:i:s", time());
		      	  	//Borramos los cargos generados hasta ahora
		      	  	$query = "SELECT * FROM liqupadr WHERE liqupadr.numeregi = $numeregi";
		      	  	$resp = sql($query);
		      	  	$resp = each($resp);
		      	  	$resp = $resp[value];
		      	  	$query = "UPDATE liqupadr SET";
		      	  	$query .= " fechintecarg = '$hoy', horaintecarg = '$hora'";
		      	  	$query .= ", usuaintecarg = '$usua', causintecarg = 'CANCELAR'";
		      	  	$query .= " WHERE liqupadr.numeregi = $numeregi;";
		      	  	sql($query); 
		      	  	$query = "DELETE FROM carg WHERE carg.codiconc = " . $resp[codiconc];
		      	  	$query .= " AND carg.periliqu = '" . $resp[peri] . "'";
		      	  	$query .= " AND carg.codiayun = " . $resp[codiayun];
		      	  	$query .= " AND carg.modoliqu = 'PER'";
		      	  	$query .= " AND carg.anio = '" . $resp[anio] . "';";
		      	  	sql($query);
	      	  	}else{
	      	  		print "alert('Solo se permite cancelar el proceso al usuario que lo inició.');";
	      	  	}
      	  	}else{
      	  		print "alert('" . utf8_encode("El proceso no se ha iniciado o ha finalizado.") . "');";
      	    }	      	  	
      	  }
      }
  	  if ($dataset=='elimcarg'){
      	  if ($numeregi && $numeregi > 0){
      	  	if (sql("SELECT count(*) FROM liqupadr WHERE liqupadr.numeregi = $numeregi and ((liqupadr.fechfinacalc <> '0001-01-01' and liqupadr.fechiniccarg = '0001-01-01') or (liqupadr.fechiniccarg <> '0001-01-01' and liqupadr.fechintecarg <> '0001-01-01' and liqupadr.fechfinacarg = '0001-01-01'))") > 0){
	      	  	$resp = sql("SELECT liqupadr.usuainiccalc FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  	if ($resp == $usua){
	      	  		$resp = sql("SELECT * FROM liqupadr WHERE liqupadr.numeregi = $numeregi");
	      	  		if ($resp){
	      	  			$resp = each($resp);
			      	  	$hoy = date("Y-m-d", time());
			      	  	$hora = date ("H:i:s", time());
			      	  	$query = "DELETE FROM liqupadr WHERE ";
			      	  	$query .= "liqupadr.numeregi = $numeregi; ";
	      	  			$query .= "INSERT INTO liqupadrhist (codiayun, codiconc,
  															anio,peri,
  															fechiniccalc,horainiccalc,
  															usuainiccalc, fechfinacalc";
  						if ($resp[value][fechintecalc] != '0001-01-01'){											
	  						$query .= ",fechintecalc, horaintecalc,
	  									usuaintecalc, causintecalc";
  						}
  						
  						if ($resp[value][fechintecarg] != '0001-01-01'){											
		  					$query .= ",fechintecarg,horaintecarg,
	  										causintecarg";
  						}  							
  						$query .= ") ";							
  										
  						$query .= "	VALUES ('" . $resp[value][codiayun] . "', '" . $resp[value][codiconc] . "',
											'" . $resp[value][anio] . "','" . $resp[value][peri] . "',
											'" . $resp[value][fechiniccalc] . "', '" . $resp[value][horainiccalc] . "',
											'" . $resp[value][usuainiccalc] . "', '" . $resp[value][fechfinacalc] . "'";
  						if ($resp[value][fechintecalc] != '0001-01-01'){											
  							$query .= ",'" . $resp[value][fechintecalc] . "', '" . $resp[value][horaintecalc] . "',
  										'" . $resp[value][usuaintecalc] . "', '" . $resp[value][causintecalc] . "'";
						}
  						if ($resp[value][fechintecarg] != '0001-01-01'){											
							$query .= ",'" . $resp[value][fechintecarg] . "', '" . $resp[value][horaintecarg] . "',
	  									'" . $resp[value][causintecarg] . "'";
  						}  							
  						$query .= ")";
  					    
			      	  	sql($query);
	      	  		}	      	  		
	      	  	}else{
	      	  		print "alert('Solo se permite cancelar el proceso al usuario que lo inició.');";
	      	  	}
      	  	}else{
      	  		print "alert('El proceso esta en ejecución o ha finalizado.');";
      	    }	      	  	
      	  }
      }
  	  
   }
?>
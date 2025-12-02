<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruleta{

  function return_ruleta($id,$salon,$servidor,$puestos,$limites_globales){
    // Datos db
		$usuario = $servidor->user;
		$clave = $servidor->pass;
	  $port = 3507;
	  $timeout = 2;
	  $tbegin = microtime(true);
	  // Comprobacion respuesta
	  $fp = fsockopen($servidor->servidor, $port, $errno, $errstr, $timeout);
	  $responding = 1;
	  if (!$fp) { $responding = 0; }
	  $tend = microtime(true);
	  fclose($fp);
	  $mstime = ($tend - $tbegin) * 1000;
	  $mstime = round($mstime, 2);
	  if($responding){
	  	// Conexion Contabilidad
	  	$database = $servidor->database1;
			$con=mysqli_connect($servidor->servidor, $usuario, $clave, $database, $port);
			$database2 = $servidor->database2;
			$con2=mysqli_connect($servidor->servidor, $usuario, $clave, $database2, $port);
			// Comprobar conexion
			if (mysqli_connect_errno()){
				return "Error conectando con la base de datos.";
			}else{
				$html_ruleta = '<span style="font-size: 20px;">'.$salon->salon.'</span></h3><hr>';
				
				// Avisos
				
				// Comprobar avisos
				$a = 0;
				$avisos = '';
				
				// Avisos hopper
				$i = 0;
				$hoppers = mysqli_query($con, "select Cliente as Puesto, Cantidad as Hopper from tabla_estadotolva");
				while($hopper=mysqli_fetch_array($hoppers)){
					$i++;
					if($hopper['Hopper'] < $limites_globales->hopper){
						$a++;
						$avisos .= "<p>Hopper Puesto ".$i." por debajo del límite</p>";
					}							
				}
				
				// Avisos reciclador
				$i = 0;
				$consulta_billetes = "select ";
				$consulta_billetes2 = "";
				$limites = mysqli_query($con2,"select * from tabla_configuraciontolvabilletes");
				$contar5 = $contar10 = $contar20 = $contar50 = $contar100 = $contar200 = $contar500 = 0;
				$campos = array();
				while($limite=mysqli_fetch_array($limites)){
					if($limite['Billete5'] > 0){
						$consulta_billetes2 .= ",Cantidad5";
						$campos[] = 'Cantidad5';
					}
					if($limite['Billete10'] > 0){
						$consulta_billetes2 .= ",Cantidad10";
						$campos[] = 'Cantidad10';
					}
					if($limite['Billete20'] > 0){
						$consulta_billetes2 .= ",Cantidad20";
						$campos[] = 'Cantidad20';
					}
					if($limite['Billete50'] > 0){
						$consulta_billetes2 .= ",Cantidad50";
						$campos[] = 'Cantidad50';
					}
					if($limite['Billete100'] > 0){
						$consulta_billetes2 .= ",Cantidad100";
						$campos[] = 'Cantidad100';
					}
					if($limite['Billete200'] > 0){
						$consulta_billetes2 .= ",Cantidad200";
						$campos[] = 'Cantidad200';
					}
					if($limite['Billete500'] > 0){
						$consulta_billetes2 .= ",Cantidad500";
						$campos[] = 'Cantidad500';
					}
				}
				
				if($consulta_billetes2[0] == ","){
					$consulta_billetes2 = ltrim($consulta_billetes2, ',');
					$consulta_billetes .= $consulta_billetes2;
				}else{
					$consulta_billetes .= $consulta_billetes2;
				}
				
				$consulta_billetes .= " from tabla_estadotolvabilletero";
				
				$recicladores = mysqli_query($con, $consulta_billetes);
				
				while($reciclador=mysqli_fetch_array($recicladores)){
						$i++;
						$j = 0;
						for($j=0; $j < count($campos); $j++){
							if($reciclador[$campos[$j]] < $limites_globales->reciclador){
								$a++;
								$avisos .= "<p>Reciclador Puesto ".$i." ".$campos[$j]."  por debajo del límite</p>";
							}
						}
				}
				
				// Avisos billetes
				$i = 0;
				$consulta_billetes = "select ";
				$consulta_billetes2 = "";
				$limites = mysqli_query($con2,"select * from tabla_configuraciontolvabilletes");
				$contar5 = $contar10 = $contar20 = $contar50 = $contar100 = $contar200 = $contar500 = 0;
				$campos = array();
				while($limite=mysqli_fetch_array($limites)){
					if($limite['Billete5'] == 0){
						$consulta_billetes2 .= ",Cantidad5";
						$campos[] = 'Cantidad5';
					}
					if($limite['Billete10'] == 0){
						$consulta_billetes2 .= ",Cantidad10";
						$campos[] = 'Cantidad10';
					}
					if($limite['Billete20'] == 0){
						$consulta_billetes2 .= ",Cantidad20";
						$campos[] = 'Cantidad20';
					}
					if($limite['Billete50'] == 0){
						$consulta_billetes2 .= ",Cantidad50";
						$campos[] = 'Cantidad50';
					}
					if($limite['Billete100'] == 0){
						$consulta_billetes2 .= ",Cantidad100";
						$campos[] = 'Cantidad100';
					}
					if($limite['Billete200'] == 0){
						$consulta_billetes2 .= ",Cantidad200";
						$campos[] = 'Cantidad200';
					}
					if($limite['Billete500'] == 0){
						$consulta_billetes2 .= ",Cantidad500";
						$campos[] = 'Cantidad500';
					}
				}
				
				if($consulta_billetes2[0] == ","){
					$consulta_billetes2 = ltrim($consulta_billetes2, ',');
					$consulta_billetes .= $consulta_billetes2;
				}else{
					$consulta_billetes .= $consulta_billetes2;
				}
				
				$consulta_billetes .= " from tabla_estadotolvabilletero";
				
				$billetes = mysqli_query($con, $consulta_billetes);
				
				while($billete=mysqli_fetch_array($billetes)){
						$i++;
						$j = 0;
						for($j=0; $j < count($campos); $j++){
							if($billete[$campos[$j]] > $limites_globales->billetes){
								$a++;
								$avisos .= "<p>Billetes Puesto ".$i." ".$campos[$j]."  por encima del límite</p>";
							}
						}
				}
				
				// Cajones
				$i = 0;
							
				$cajones = mysqli_query($con,"select Cajon1, Cajon2 from tabla_contadores");

				while($cajon=mysqli_fetch_array($cajones)){
					$i++;
					if($cajon['Cajon1'] > $limites_globales->cajones){
						$a++;
						$avisos .= "<p>Cajón1 Puesto ".$i." por encima del límite</p>";
					}
					if($cajon['Cajon2'] > $limites_globales->cajones){
						$a++;
						$avisos .= "<p>Cajón2 Puesto ".$i." por encima del límite</p>";
					}
				}
				
				if($a != 0){
							
					$html_ruleta .= '<div id="avisos_alert" class="alert alert-danger" role="alert" style="font-weight: bold; text-align: center; cursor: pointer">AVISOS ('.$a.')</div>';
					
					$html_ruleta .= '<div id="avisos_content" style="width: 100%; display: none; background-color: #f2dede; border: 1px solid #dca7a7; border-radius: 5px; padding: 10px 10px 5px 10px; color: #a94442; font-weight: bold; margin-bottom: 16px;">
													'.$avisos.'
													</div>';
				
				}
				
				// Resumen
															
				$html_ruleta .= '<div id="resumen_alert" class="alert alert-success" role="alert" style="font-weight: bold; text-align: center; cursor: pointer">RESUMEN</div>';
				
				// Dinero activo
				
				$html_ruleta .= '<div id="resumen_content" style="width: 100%; display: none"> 
													<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
															<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #016e03; color: #fff;">
																DINERO ACTIVO
																<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
																<span style="float: right; display: none" class="glyphicon glyphicon-triangle-bottom"></span>
															</div>
															<div class="panel-body">';
				// Hopper
				$i = 0;
				$hoppers = mysqli_query($con, "select Cliente as Puesto, Cantidad as Hopper from tabla_estadotolva");
				$hopper_cantidad_total = 0;
				$hopper_total = 0;
				
				while($hopper=mysqli_fetch_array($hoppers)){
					$hopper_cantidad_total+=$hopper['Hopper'];					
					$hopper_total+=$hopper['Hopper'];			
				}
				
				$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#hopper'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Hopper</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$hopper_cantidad_total."</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($hopper_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div id='hopper' class='collapse'>";
				
				$hoppers = mysqli_query($con, "select Cliente as Puesto, Cantidad as Hopper from tabla_estadotolva");
				
				while($hopper=mysqli_fetch_array($hoppers)){
					$i++;
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left; font-weight: bold'>Puesto".$i."</div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$hopper['Hopper']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($hopper['Hopper'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";		
				}          	    	       
        
        $html_ruleta .= "</div>";
											
				// Reciclador
				$puesto = 0;
				$consulta_billetes = "select ";
				$consulta_billetes2 = "";
				$limites = mysqli_query($con2,"select * from tabla_configuraciontolvabilletes");
				$contar5 = $contar10 = $contar20 = $contar50 = $contar100 = $contar200 = $contar500 = 0;
				
				while($limite=mysqli_fetch_array($limites)){
					if($limite['Billete5'] > 0){
						$consulta_billetes2 .= ",Cantidad5";
						$contar5 = 1;
					}
					if($limite['Billete10'] > 0){
						$consulta_billetes2 .= ",Cantidad10";
						$contar10 = 1;
					}
					if($limite['Billete20'] > 0){
						$consulta_billetes2 .= ",Cantidad20";
						$contar20 = 1;
					}
					if($limite['Billete50'] > 0){
						$consulta_billetes2 .= ",Cantidad50";
						$contar50 = 1;
					}
					if($limite['Billete100'] > 0){
						$consulta_billetes2 .= ",Cantidad100";
						$contar100 = 1;
					}
					if($limite['Billete200'] > 0){
						$consulta_billetes2 .= ",Cantidad200";
						$contar200 = 1;
					}
					if($limite['Billete500'] > 0){
						$consulta_billetes2 .= ",Cantidad500";
						$contar500 = 1;
					}
				}
				
				if($consulta_billetes2[0] == ","){
					$consulta_billetes2 = ltrim($consulta_billetes2, ',');
					$consulta_billetes .= $consulta_billetes2;
				}else{
					$consulta_billetes .= $consulta_billetes2;
				}
				
				$consulta_billetes .= " from tabla_estadotolvabilletero";
				
				$reciclador_cantidad_total = 0;
				$reciclador_total = 0;
				$total5 = $total10 = $total20 = $total50 = $total100 = $total200 = $total500 = 0;
				
				$recicladores = mysqli_query($con, $consulta_billetes);
				
				while($reciclador=mysqli_fetch_array($recicladores)){
					if($contar5 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad5'];
						$reciclador_total += ($reciclador['Cantidad5']*5);
						$total5 += $reciclador['Cantidad5'];
					}
					if($contar10 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad10'];
						$reciclador_total += ($reciclador['Cantidad10']*10);
						$total10 += $reciclador['Cantidad10'];
					}
					if($contar20 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad20'];
						$reciclador_total += ($reciclador['Cantidad20']*20);
						$total20 += $reciclador['Cantidad20'];
					}
					if($contar50 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad50'];
						$reciclador_total += ($reciclador['Cantidad50']*50);
						$total50 += $reciclador['Cantidad50'];
					}
					if($contar100 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad100'];
						$reciclador_total += ($reciclador['Cantidad100']*100);
						$total100 += $reciclador['Cantidad100'];
					}
					if($contar200 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad200'];
						$reciclador_total += ($reciclador['Cantidad200']*200);
						$total200 += $reciclador['Cantidad200'];
					}
					if($contar500 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad500'];
						$reciclador_total += ($reciclador['Cantidad500']*500);
						$total500 += $reciclador['Cantidad500'];
					}
				}
				
				$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#reciclador'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Reciclador</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador_cantidad_total."</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($reciclador_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div class='collapse'>";
				
				$recicladores = mysqli_query($con, $consulta_billetes);
				
				while($reciclador=mysqli_fetch_array($recicladores)){
					
					$puesto++;
					$reciclador_cantidad_puesto = 0;
					$reciclador_puesto = 0;
				
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left; font-weight: bold'>Puesto".$puesto."</div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b></b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b></b></div>";
					$html_ruleta .= "</div>";	
					
					if($contar5 == 1){
						
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #689868; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/500.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad5']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad5']*5), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad5'];
						$reciclador_puesto += $reciclador['Cantidad5']*5;
					
					}
					
					if($contar10 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #689868; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/1000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad10']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad10']*10), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad10'];
						$reciclador_puesto += $reciclador['Cantidad10']*10;
					
					}
					
					if($contar20 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #689868; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/2000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad20']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad20']*20), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad20'];
						$reciclador_puesto += $reciclador['Cantidad20']*20;
					
					}
					
					if($contar50 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #689868; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/5000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad50']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad50']*50), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad50'];
						$reciclador_puesto += $reciclador['Cantidad50']*50;
					
					}
					
					if($contar100 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #689868; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/10000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad100']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad100']*100), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad100'];
						$reciclador_puesto += $reciclador['Cantidad100']*100;
					
					}
					
					if($contar200 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #689868; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/20000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad200']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad200']*200), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad200'];
						$reciclador_puesto += $reciclador['Cantidad200']*200;
					
					}
					
					if($contar500 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #689868; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/50000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad500']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad500']*500), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad500'];
						$reciclador_puesto += $reciclador['Cantidad500']*500;
					
					}
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left; font-weight: bold'>Total Puesto".$puesto."</div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador_cantidad_puesto."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($reciclador_puesto, 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";	
					
				}
				
				$html_ruleta .= "</div>";
				
				// Total
				
				$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#reciclador'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='minus' style='display: none'>[-]</b><b>Total</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".($hopper_cantidad_total+$reciclador_cantidad_total)."</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($hopper_total+$reciclador_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";		
				
				$html_ruleta .= '</div>
											</div>';
											
				// Dinero no activo
											
				$html_ruleta .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
															<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #fe0000; color: #fff;">
																DINERO NO ACTIVO
																<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
																<span style="float: right; display: none" class="glyphicon glyphicon-triangle-bottom"></span>
															</div>
															<div class="panel-body">';
															
				// Billetes
				$puesto = 0;
				$consulta_recicladores = "select ";
				$consulta_recicladores2 = "";
				$limites = mysqli_query($con2,"select * from tabla_configuraciontolvabilletes");
				$contar5 = $contar10 = $contar20 = $contar50 = $contar100 = $contar200 = $contar500 = 0;
				
				while($limite=mysqli_fetch_array($limites)){
					if($limite['Billete5'] == 0){
						$consulta_recicladores2 .= ",Cantidad5";
						$contar5 = 1;
					}
					if($limite['Billete10'] == 0){
						$consulta_recicladores2 .= ",Cantidad10";
						$contar10 = 1;
					}
					if($limite['Billete20'] == 0){
						$consulta_recicladores2 .= ",Cantidad20";
						$contar20 = 1;
					}
					if($limite['Billete50'] == 0){
						$consulta_recicladores2 .= ",Cantidad50";
						$contar50 = 1;
					}
					if($limite['Billete100'] == 0){
						$consulta_recicladores2 .= ",Cantidad100";
						$contar100 = 1;
					}
					if($limite['Billete200'] == 0){
						$consulta_recicladores2 .= ",Cantidad200";
						$contar200 = 1;
					}
					if($limite['Billete500'] == 0){
						$consulta_recicladores2 .= ",Cantidad500";
						$contar500 = 1;
					}
				}
				
				if($consulta_recicladores2[0] == ","){
					$consulta_recicladores2 = ltrim($consulta_recicladores2, ',');
					$consulta_recicladores .= $consulta_recicladores2;
				}else{
					$consulta_recicladores .= $consulta_recicladores2;
				}
				
				$consulta_recicladores .= " from tabla_estadotolvabilletero";
				
				$reciclador_cantidad_total = 0;
				$reciclador_total = 0;
				$total5 = $total10 = $total20 = $total50 = $total100 = $total200 = $total500 = 0;
				
				$recicladores = mysqli_query($con, $consulta_recicladores);
				
				while($reciclador=mysqli_fetch_array($recicladores)){
					if($contar5 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad5'];
						$reciclador_total += ($reciclador['Cantidad5']*5);
						$total5 += $reciclador['Cantidad5'];
					}
					if($contar10 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad10'];
						$reciclador_total += ($reciclador['Cantidad10']*10);
						$total10 += $reciclador['Cantidad10'];
					}
					if($contar20 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad20'];
						$reciclador_total += ($reciclador['Cantidad20']*20);
						$total20 += $reciclador['Cantidad20'];
					}
					if($contar50 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad50'];
						$reciclador_total += ($reciclador['Cantidad50']*50);
						$total50 += $reciclador['Cantidad50'];
					}
					if($contar100 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad100'];
						$reciclador_total += ($reciclador['Cantidad100']*100);
						$total100 += $reciclador['Cantidad100'];
					}
					if($contar200 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad200'];
						$reciclador_total += ($reciclador['Cantidad200']*200);
						$total200 += $reciclador['Cantidad200'];
					}
					if($contar500 == 1){
						$reciclador_cantidad_total += $reciclador['Cantidad500'];
						$reciclador_total += ($reciclador['Cantidad500']*500);
						$total500 += $reciclador['Cantidad500'];
					}
				}
				
				$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #cd3e3e; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#reciclador'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Billetes</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador_cantidad_total."</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($reciclador_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div class='collapse'>";
				
				$recicladores = mysqli_query($con, $consulta_recicladores);
				
				while($reciclador=mysqli_fetch_array($recicladores)){
					
					$puesto++;
					$reciclador_cantidad_puesto = 0;
					$reciclador_puesto = 0;
				
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left; font-weight: bold'>Puesto".$puesto."</div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b></b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b></b></div>";
					$html_ruleta .= "</div>";	
					
					if($contar5 == 1){
						
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/500.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad5']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad5']*5), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad5'];
						$reciclador_puesto += $reciclador['Cantidad5']*5;
					
					}
					
					if($contar10 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/1000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad10']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad10']*10), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad10'];
						$reciclador_puesto += $reciclador['Cantidad10']*10;
					
					}
					
					if($contar20 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/2000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad20']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad20']*20), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad20'];
						$reciclador_puesto += $reciclador['Cantidad20']*20;
					
					}
					
					if($contar50 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/5000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad50']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad50']*50), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad50'];
						$reciclador_puesto += $reciclador['Cantidad50']*50;
					
					}
					
					if($contar100 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/10000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad100']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad100']*100), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad100'];
						$reciclador_puesto += $reciclador['Cantidad100']*100;
					
					}
					
					if($contar200 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/20000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad200']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad200']*200), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad200'];
						$reciclador_puesto += $reciclador['Cantidad200']*200;
					
					}
					
					if($contar500 == 1){
					
						$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/50000.png' height='35'></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador['Cantidad500']."</b></div>";
						$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($reciclador['Cantidad500']*500), 2, ',', '.')."&euro;</b></div>";
						$html_ruleta .= "</div>";
						
						$reciclador_cantidad_puesto += $reciclador['Cantidad500'];
						$reciclador_puesto += $reciclador['Cantidad500']*500;
					
					}
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left; font-weight: bold'>Total Puesto".$puesto."</div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$reciclador_cantidad_puesto."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($reciclador_puesto, 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";	
					
				}
				
				$html_ruleta .= "</div>";
				
				// Cajones
				$puesto = 0;
				$cajones_total = 0;
							
				$cajones = mysqli_query($con,"select Cajon1, Cajon2 from tabla_contadores");
				$cajon_cantidad_puesto = 0;
				$cajon_puesto = 0;

				while($cajon=mysqli_fetch_array($cajones)){
					$cajones_total += $cajon['Cajon1'];
					$cajones_total += $cajon['Cajon2'];
				}
				
				$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #cd3e3e; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#reciclador'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Cajones</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cajones_total."</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cajones_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$cajones = mysqli_query($con,"select Cajon1, Cajon2 from tabla_contadores");
				
				$html_ruleta .= "<div class='collapse'>";
				
				while($cajon=mysqli_fetch_array($cajones)){
					$puesto++;
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left; font-weight: bold'>Puesto".$puesto."</div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".($cajon['Cajon1']+$cajon['Cajon2'])."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($cajon['Cajon1']+$cajon['Cajon2']), 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><b>Cajón1</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cajon['Cajon1']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cajon['Cajon1'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #cd3e3e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><b>Cajón2</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cajon['Cajon2']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cajon['Cajon2'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
			
				}

				// Total
				
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #cd3e3e; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#reciclador'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='minus' style='display: none'>[-]</b><b>Total</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".($reciclador_cantidad_total+$cajones_total)."</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($reciclador_total+$cajones_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
															
				$html_ruleta .= '</div>
												</div>';
												
				$html_ruleta .= '</div>';
													
				// Detalle
															
				$html_ruleta .= '<div id="detalles_alert" class="alert alert-info" role="alert" style="font-weight: bold; text-align: center; cursor: pointer">DETALLES</div>';
				
				$html_ruleta .= '<div id="detalles_content" style="width: 100%; display: none">';
				
				// Hopper
				
				$i=0;
				$hoppers = mysqli_query($con, "select Cliente as Puesto, Cantidad as Hopper from tabla_estadotolva");
				$hopper_cantidad_total = 0;
				$hopper_total = 0;
				
				
				$html_ruleta .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
														<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #804040; color: #fff;">
															Hopper
															<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
															<span style="float: right; display: none" class="glyphicon glyphicon-triangle-bottom"></span>				
														</div>
														<div class="panel-body">';
														
				while($hopper=mysqli_fetch_array($hoppers)){
					$i++;
					
					$html_ruleta .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #804040; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse'>";
					$html_ruleta .= "<div style='width: 33%; float: left'><b>Puesto ".$hopper['Puesto']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$hopper['Hopper']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($hopper['Hopper'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$hopper_cantidad_total+=$hopper['Hopper'];					
					$hopper_total+=$hopper['Hopper'];
					
				}
				
				$html_ruleta .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #804040; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b>Total</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$hopper_cantidad_total."</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($hopper_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= '</div>
											</div>';
				
				// Billetes
				$billetes_cantidad_total = 0;
				$billetes_total = 0;

				$billetes = mysqli_query($con, "select Cliente as Puesto,Cantidad5 as Total5,Cantidad10 as Total10,Cantidad20 as Total20,Cantidad50 as Total50,Cantidad100 as Total100,Cantidad200 as Total200,Cantidad500 as Total500,((Cantidad5)+(Cantidad10)+(Cantidad20)+(Cantidad50)+(Cantidad100)+(Cantidad200)+(Cantidad500)) as TotalBilletes from tabla_estadotolvabilletero");
											
				$html_ruleta .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
														<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #D02F2F; color: #fff;">
															Billetes
															<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
															<span style="float: right; display: none" class="glyphicon glyphicon-triangle-bottom"></span>				
														</div>
														<div class="panel-body">';
				
				while($billete=mysqli_fetch_array($billetes)){
					
					$billetes_puesto_cantidad_total = 0;
					$billetes_puesto_total = 0;
					$billetes_puesto_cantidad_total += $billete['Total5'];
					$billetes_puesto_total += $billete['Total5']*5;
					$billetes_puesto_cantidad_total += $billete['Total10'];
					$billetes_puesto_total += $billete['Total10']*10;
					$billetes_puesto_cantidad_total += $billete['Total20'];
					$billetes_puesto_total += $billete['Total20']*20;
					$billetes_puesto_cantidad_total += $billete['Total50'];
					$billetes_puesto_total += $billete['Total50']*50;
					$billetes_puesto_cantidad_total += $billete['Total100'];
					$billetes_puesto_total += $billete['Total100']*100;
					$billetes_puesto_cantidad_total += $billete['Total200'];
					$billetes_puesto_total += $billete['Total200']*200;
					$billetes_puesto_cantidad_total += $billete['Total500'];
					$billetes_puesto_total += $billete['Total500']*500;
					
					$billetes_cantidad_total += $billetes_puesto_cantidad_total;
					$billetes_total += $billetes_puesto_total;
					
					$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #D02F2F; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse'>";
					$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Puesto ".$billete['Puesto']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billetes_puesto_cantidad_total."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($billetes_puesto_total, 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div class='collapse'>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/500.png' height='35'></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billete['Total5']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($billete['Total5']*5), 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/1000.png' height='35'></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billete['Total10']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($billete['Total10']*10), 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/2000.png' height='35'></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billete['Total20']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($billete['Total20']*20), 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/5000.png' height='35'></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billete['Total50']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($billete['Total50']*50), 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/10000.png' height='35'></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billete['Total100']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($billete['Total100']*100), 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/20000.png' height='35'></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billete['Total200']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($billete['Total200']*200), 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src='http://apuestasdemurcia.es/ruan/50000.png' height='35'></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billete['Total500']."</b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($billete['Total500']*500), 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "</div>";
					
				}
				
				$html_ruleta .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #D02F2F; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Total</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".$billetes_cantidad_total."</b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($billetes_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
														
				$html_ruleta .= '</div>
											</div>';												
											
				// Parciales
				$i=0;
				$parciales = mysqli_query($con,"select Cliente as Puesto, EParcial, SParcial, (EParcial-SParcial) as HParcial, (SParcial*100/EParcial) as PParcial, Cajon1, Cajon2, Billetes as BilletesCajon from tabla_contadores");
				$parcial_entradas_total = 0;
				$parcial_salidas_total = 0;
				$parcial_hold_total = 0;
				$parcial_porcentaje_total = 0;
				$parcial_cajon1_total = 0;
				$parcial_cajon2_total = 0;
				$parcial_billetes_total = 0;
				
				$html_ruleta .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
														<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #016e03; color: #fff;">
															Parciales
															<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
															<span style="float: right; display: none" class="glyphicon glyphicon-triangle-bottom"></span>				
														</div>
														<div class="panel-body">';
														
				while($parcial=mysqli_fetch_array($parciales)){
					$i++;
					
					$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #016e03; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse'>";
					$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Puesto ".$parcial['Puesto']."</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div class='collapse'>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Entradas Parcial: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial['EParcial'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Salidas Parcial: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial['SParcial'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Hold Parcial: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial['HParcial'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Porcentaje Parcial: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial['PParcial'], 2, ',', '.')."%</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Cajón 1: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial['Cajon1'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Cajón 2: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial['Cajon2'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Billetes Cajón: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial['BilletesCajon'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "</div>";
					
					$parcial_entradas_total+=$parcial['EParcial'];
					$parcial_salidas_total+=$parcial['SParcial'];
					$parcial_hold_total+=$parcial['HParcial'];
					$parcial_porcentaje_total+=$parcial['PParcial'];
					$parcial_cajon1_total+=$parcial['Cajon1'];
					$parcial_cajon2_total+=$parcial['Cajon2'];
					$parcial_billetes_total+=$parcial['BilletesCajon'];
					
				}
				
				$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #016e03; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Total</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div class='collapse'>";
					
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Entradas: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial_entradas_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Salidas: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial_salidas_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Hold: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial_hold_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Porcentaje: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($parcial_porcentaje_total/$i), 2, ',', '.')."%</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Cajón 1: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial_cajon1_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Cajón 2: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial_cajon2_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #009c03; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Billetes: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($parcial_billetes_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
					
				$html_ruleta .= "</div>";
				
				$html_ruleta .= '</div>
											</div>';
											
				// Globales
				$i=0;
				$globales= mysqli_query($con,"select Cliente as Puesto,Entradas,Salidas,(Entradas-Salidas) as Hold,(Salidas*100/Entradas) as Porcentaje from tabla_entradassalidas");
				$global_entradas_total = 0;
				$global_salidas_total = 0;
				$global_hold_total = 0;
				$global_porcentaje_total = 0;
				
				$html_ruleta .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
														<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #ec971f; color: #fff;">
															Globales
															<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
															<span style="float: right; display: none" class="glyphicon glyphicon-triangle-bottom"></span>				
														</div>
														<div class="panel-body">';
														
				while($global = mysqli_fetch_array($globales)){
					$i++;
					
					$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #ec971f; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse'>";
					$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Puesto ".$global['Puesto']."</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div class='collapse'>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #ffb751; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Entradas: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($global['Entradas'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #ffb751; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Salidas: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($global['Salidas'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #ffb751; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Hold: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($global['Hold'], 2, ',', '.')."&euro;</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #ffb751; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Porcentaje: </b></div>";
					$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($global['Porcentaje'], 2, ',', '.')."%</b></div>";
					$html_ruleta .= "</div>";
					
					$html_ruleta .= "</div>";
					
					$global_entradas_total+=$global['Entradas'];
					$global_salidas_total+=$global['Salidas'];
					$global_hold_total+=$global['Hold'];
					$global_porcentaje_total+=$global['Porcentaje'];
					
				}
				
				$html_ruleta .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #ec971f; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse'>";
				$html_ruleta .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Total</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div class='collapse'>";
					
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #ffb751; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Entradas: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($global_entradas_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #ffb751; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Salidas: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($global_salidas_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #ffb751; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Hold: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($global_hold_total, 2, ',', '.')."&euro;</b></div>";
				$html_ruleta .= "</div>";
				
				$html_ruleta .= "<div style='width: 100%; float: left; color: #fff; background: #ffb751; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$html_ruleta .= "<div style='width: 66%; float: left; text-align: left;'><b>Porcentaje: </b></div>";
				$html_ruleta .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($global_porcentaje_total/$i), 2, ',', '.')."%</b></div>";
				$html_ruleta .= "</div>";
					
				$html_ruleta .= "</div>";
				
				$html_ruleta .= '</div>
											</div>';										
				
				return $html_ruleta;
			}
	  }
  }
  
}
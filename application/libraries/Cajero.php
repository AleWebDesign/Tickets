<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cajero {

	// Mercosa
	function return_cajero2($id_salon,$salon_nombre,$cajero_info){
		error_reporting(E_ERROR | E_PARSE | E_NOTICE);

		include 'cajeros/includes2.php';

		setlocale(LC_MONETARY, 'es_ES');

		//Inicializar variables
		$mensaje = $mensaje2 = "";
		$salonescont = 0;
		$error = 0;
		$arqueo = 0;
		$totalsumrefills = 0;

		// INICIO WHILE $salonescont[]
		while ($salonescont < $salonescant) {
			$error=0;

			// Conectar
			try{
			  $conn = new PDO('mysql:host='.$servidor[$salonescont].'; dbname='.$tabla.'; charset=utf8', $usuario, $clave);
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
			    echo "ERROR: " . $e->getMessage();
			    $error=1;
			}

			if($error != 1){
				$mensaje .= '</div>';
				$flush = $conn->prepare("FLUSH HOSTS");
				$flush->execute();

				$sql = $conn->prepare("select saldof as idsaldo from operaciones where info != 'ARQUEO' order by data desc limit 1");
				$sql->execute();
				$saldo = $sql->fetch();

				// DINERO ACTIVO
				$mensaje .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
								<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #016e03; color: #fff;">
									DINERO ACTIVO<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
								</div>
								<div class="panel-body">';


				// Hopper
				$sql = $conn->prepare("select data,cantidad,valor,(cantidad*valor) as total from saldohoppers where id=".$saldo['idsaldo']." AND (nhopper=3 or nhopper=4 or nhopper=5) ORDER BY data DESC limit 3");
				$sql->execute();
				$saldohoppers = $sql->fetchAll();

				$cantidad_hopper = 0;
				$total_hopper = 0; 
				foreach($saldohoppers as $saldohopper){

					$cantidad_hopper += $saldohopper['cantidad'];
					$total_hopper += $saldohopper['total'];

					$mensaje2 .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 2% 0'>
									<div style='width: 33%; float: left; text-align: left;'>
										<span style='font-weight: bold;'>".($saldohopper['valor']/100)."€</span>
									</div>
									<div style='width: 33%; float: left; text-align: right;'><b>".$saldohopper['cantidad']."</b></div>
									<div style='width: 33%; float: left; text-align: right;'><b>".number_format($saldohopper['total']/100, 2, ',', '.')."&euro;</b></div>
								</div>";
				}

				$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#hopper'>";
				$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Hopper</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .$cantidad_hopper. "</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($total_hopper/100, 2, ',', '.'). "&euro;</b></div>";
				$mensaje .= "</div>";

				$mensaje .= "<div id='hopper' class='collapse'>";
			    
			    $mensaje .= $mensaje2;				 
								 
				$mensaje .= "</div>";

				$mensaje2 = "";
				
				// Reciclador
				$sql = $conn->prepare("select data,cantidad,valor, (cantidad*valor) as total from saldodispensador where id=".$saldo['idsaldo']." AND (ncanal=1 or ncanal=2) ORDER BY data DESC limit 2");
				$sql->execute();
				$saldodispensadores = $sql->fetchAll();

				$cantidad_reciclador = 0;
				$total_reciclador = 0;
				foreach($saldodispensadores as $saldodispensador){
					$cantidad_reciclador += $saldodispensador['cantidad'];
					$total_reciclador += $saldodispensador['total'];

					$mensaje2 .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 2% 0'>
									<div style='width: 33%; float: left; text-align: left;'>
										<span style='font-weight: bold;'>".$saldodispensador['valor']."€</span>
									</div>
									<div style='width: 33%; float: left; text-align: right;'><b>".$saldodispensador['cantidad']."</b></div>
									<div style='width: 33%; float: left; text-align: right;'><b>".number_format($saldodispensador['total'], 2, ',', '.')."&euro;</b></div>
								</div>";
				}

				$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#cassette'>";
				$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Reciclador</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .$cantidad_reciclador. "</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($total_reciclador, 2, ',', '.'). "&euro;</b></div>";
				$mensaje .= "</div>";

				$mensaje .= "<div id='cassette' class='collapse'>";
			    
			    $mensaje .= $mensaje2;				 
								 
				$mensaje .= "</div>";

				$mensaje2 = "";

				// TOTAL
				$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #3a5439; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$mensaje .= "<div style='width: 33%; float: left'><b>Total Activo</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . ($cantidad_reciclador+$cantidad_hopper) . "</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . number_format($total_reciclador + ($total_hopper/100), 2, ',', '.') . "&euro;</b></div>";
				$mensaje .= "</div></div></div>";
				// FIN ACTIVO
				
				// NO ACTIVO
				$mensaje .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
							<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #fe0000; color: #fff;">
								DINERO NO ACTIVO<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
							</div>
							<div class="panel-body">';

				// Apilador
				$sql = $conn->prepare("select ncanal,data,cantidad,valor,(cantidad*valor) as total from saldostacker where id=".$saldo['idsaldo']." and valor != 0 order by data,ncanal desc limit 7");
				$sql->execute();
				$saldoapiladores = $sql->fetchAll();

				$cantidad_apilador = 0;
				$total_apilador = 0; 
				foreach($saldoapiladores as $saldoapilador){
					$total_apilador += $saldoapilador['total'];
					$cantidad_apilador += $saldoapilador['cantidad'];

					$mensaje2 .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 2% 0'>
								<div style='width: 33%; float: left; text-align: left;'>
									<span style='font-weight: bold;'>".$saldoapilador['valor']."€</span>
								</div>
								<div style='width: 33%; float: left; text-align: right;'><b>".$saldoapilador['cantidad']."</b></div>
								<div style='width: 33%; float: left; text-align: right;'><b>".number_format($saldoapilador['total'], 2, ',', '.')."&euro;</b></div>
								</div>";
				}

				$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #cd3e3e; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#stacker'>";
				$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Apilador</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . $cantidad_apilador . "</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($total_apilador, 2, ',', '.'). "&euro;</b></div>";
				$mensaje .= "</div>";		        
			              
			    $mensaje .= "<div id='stacker' class='collapse'>";
			    
			    $mensaje .= $mensaje2;
				
				$mensaje .= "</div>";
								 
				$mensaje2 = "";

				// Tickets
				$sql = $conn->prepare("select fecha_pago,importe from pagoskirol where fecha_pago >= '2019-11-25 00:00:00' and estado=0 order by fecha_pago asc");
				$sql->execute();
				$saldotickets = $sql->fetchAll();

				$cantidad_tickets = 0;
				$total_tickets = 0; 
				foreach($saldotickets as $saldoticket){
					$total_tickets += $saldoticket['importe'];
					$cantidad_tickets++;

					$mensaje2 .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 2% 0'>
								<div style='width: 33%; float: left; text-align: left;'>
									<span style='font-weight: bold;'>Ticket</span>
								</div>
								<div style='width: 33%; float: left; text-align: right;'></div>
								<div style='width: 33%; float: left; text-align: right;'><b>".number_format(($saldoticket['importe']/100), 2, ',', '.')."&euro;</b></div></div>";
				}

				$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #cd3e3e; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#tickets'>";
				$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Tickets</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cantidad_tickets."</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($total_tickets/100, 2, ',', '.'). "&euro;</b></div>";
				$mensaje .= "</div>";		        
			              
			    $mensaje .= "<div id='tickets' class='collapse'>";
			    
			    $mensaje .= $mensaje2;
				
				$mensaje .= "</div>";
								 
				$mensaje2 = "";

				// TOTAL
				$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #630100; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$mensaje .= "<div style='width: 33%; float: left'><b>Total No Activo</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . ($cantidad_apilador+$cantidad_tickets) . "</b></div>";
				$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . number_format($total_apilador + ($total_tickets/100), 2, ',', '.') . "&euro;</b></div>";
				$mensaje .= "</div></div></div>";
				// FIN NO ACTIVO

				$total = $total_reciclador + ($total_hopper/100) + $total_apilador + ($total_tickets/100);
				$disponible = ($total_reciclador + ($total_hopper/100));

				$mensaje .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
							<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #122def; color: #fff;">
								TOTAL<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
							</div>
							<div class="panel-body">';							

				$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #12abef; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$mensaje .= "<div style='width: 33%; float: left'><b>Disponible</b></div>";
				$mensaje .= "<div style='width: 66%; float: left; text-align: right;'><b>" . number_format($disponible, 2, ',', '.') . "</b></div>";
				$mensaje .= "</div>";

				$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #12abef; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
				$mensaje .= "<div style='width: 33%; float: left'><b>Total</b></div>";
				$mensaje .= "<div style='width: 66%; float: left; text-align: right;'><b>" . number_format($total, 2, ',', '.') . "&euro;</b></div>";
				$mensaje .= "</div>";									
			
				$mensaje .= "</div>
							</div>";
			}

			$salonescont++;
		}
		return $mensaje;
	}

	// Cajeros Intimus
	function return_cajero3($id_salon,$salon_nombre,$cajero_info){
		error_reporting(E_ERROR | E_PARSE);
		
		setlocale(LC_MONETARY, 'es_ES');

		// Incluir libreria SOAP
		include 'nusoap/lib/nusoap.php';

		// Conectar
		if($id_salon == 6 || $id_salon == 75 || $id_salon == 162 || $id_salon == 170 || $id_salon == 576){
			$client = new nusoap_client('https://'.$cajero_info->servidor.':'.$cajero_info->puerto.'/soap/ITesera');
		}else{
			$client = new nusoap_client('http://'.$cajero_info->servidor.':'.$cajero_info->puerto.'/soap/ITesera');
		}

		$mensaje = ""; // Inicializada variable
		$mensaje .= $client->getError();		
		$mensaje .= "<span style='font-size: 20px;'>".$salon_nombre."</span></h3><hr>";
		$mensaje .= "<div class='col-md-6 col-sm-12' style='margin-bottom: 20px; padding: 0'>";

		// Login
		$param = "login";
		$user = $cajero_info->usuario;
		$password = $cajero_info->clave;

		$userParam = array('user' => $user, 'password' => $password);
		$credentialParam = array('credential' => $userParam);	
		$result = $client->call("getAccountancyStatus", array("aParams"=>$credentialParam));
		if (!isset($result["AccountancyStatusAnswer"])) {
			$res["AccountancyStatusAnswer"] = $result;
		}else{
			$res = $result;
		}

		//
		// *** DINERO ACTIVO ***
		//

		// Cuentas y total
		$cuenta_activo = $cuenta_multi = $cuenta_reciclador = $cuenta_dispensador = 0;
		$total_activo = $total_multi = $total_reciclador = $total_dispensador = 0;
		
		$mensaje .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
						<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #016e03; color: #fff;">
							DINERO ACTIVO<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
						</div>
						<div class="panel-body">';

		foreach($res["AccountancyStatusAnswer"]["deviceList"] as &$valor){
			if($valor["id"] != "BILL_ACCEPTOR_1" && $valor["id"] != "DECLARED_DEPOSIT" && $valor["id"] != "MANUAL_DEPOSIT"){
				$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; padding: 2% 2%; border-bottom: 1px solid #fff;'>";
				if(strpos($valor["id"], "BILL_RECYCLER") !== false){
					$mensaje .= "<div style='width: 33%; float: left'><b>RECICLADOR</b></div>";
				}else if(strpos($valor["id"], "NOTE_DISPENSER") !== false){
					$mensaje .= "<div style='width: 33%; float: left'><b>DISPENSADOR</b></div>";
				}else if(strpos($valor["id"], "FUJITSU") !== false){
					$mensaje .= "<div style='width: 33%; float: left'><b>DISPENSADOR</b></div>";
				}else{
					$mensaje .= "<div style='width: 33%; float: left'><b>".$valor["id"]."</b></div>";
				}
				$mensaje .= "</div>";

				if(strpos($valor["id"], "HOP") !== false){
					$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";				
					if($valor["breakout"]["0"]["itemType"] == "COIN_EUR_100"){
						$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/100.png\" height=\"35\"></div>";
						$hopper = $valor["breakout"]["0"]["count"];
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$hopper."</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($hopper, 2, ',', '.')."&euro;</b></div>";
						$cuenta_multi += $hopper;
						$total_multi += $hopper;

			    	}
			    	if($valor["breakout"]["0"]["itemType"] == "COIN_EUR_50"){
			    		$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/050.png\" height=\"35\"></div>";
			    		$multi_050 = $valor["breakout"]["0"]["count"];
			    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$multi_050."</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($multi_050*0.5, 2, ',', '.')."&euro;</b></div>";
						$cuenta_multi += $multi_050;
						$total_multi += $multi_050*0.5;
			    	}
			    	if($valor["breakout"]["0"]["itemType"] == "COIN_EUR_10"){
			    		$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/010.png\" height=\"35\"></div>";
			    		$multi_010 = $valor["breakout"]["0"]["count"];
			    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$multi_010."</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($multi_010*0.1, 2, ',', '.')."&euro;</b></div>";
						$cuenta_multi += $multi_010;
						$total_multi += $multi_010*0.1;
			    	}
			    	if($valor["breakout"]["0"]["itemType"] == "COIN_EUR_1"){
			    		$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/001.png\" height=\"35\"></div>";
			    		$multi_001 = $valor["breakout"]["0"]["count"];
			    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$multi_001."</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($multi_001*0.01, 2, ',', '.')."&euro;</b></div>";
						$cuenta_multi += $multi_001;
						$total_multi += $multi_001*0.01;
			    	}
					$mensaje .= "</div>";
				}

				if(strpos($valor["id"], "BILL_RECYCLER") !== false){
					$reciclador = $valor["moduleList"];
					foreach($reciclador as &$valor_reciclador){
						if(strpos($valor_reciclador["id"], "RECICLA") !== false){
							$bill_recycler = $valor_reciclador["breakout"];
							$reciclan = explode("_", $valor_reciclador["id"]);
							foreach($bill_recycler as $recycler){
								if($recycler["itemType"] == "BILL_EUR_1000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/1000.png\" height=\"35\"> ".$reciclan[1]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*10, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_reciclador += $recycler["count"];
									$total_reciclador += $recycler["count"]*10;
								}else if($recycler["itemType"] == "BILL_EUR_2000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/2000.png\" height=\"35\"> ".$reciclan[1]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*20, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_reciclador += $recycler["count"];
									$total_reciclador += $recycler["count"]*20;
								}else if($recycler["itemType"] == "BILL_EUR_5000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/5000.png\" height=\"35\"> ".$reciclan[1]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*50, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_reciclador += $recycler["count"];
									$total_reciclador += $recycler["count"]*50;
								}
							}
						}
					}
				}

				if(strpos($valor["id"], "NOTE_DISPENSER") !== false){
					$dispensador = $valor["moduleList"];
					foreach($dispensador as &$valor_dispensador){
						if(strpos($valor_dispensador["id"], "CASS") !== false){
							$valor_cassette = $valor_dispensador["breakout"];
							foreach ($valor_cassette as $cassette){
								if($cassette["itemType"] == "BILL_EUR_1000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/1000.png\" height=\"35\"> ".$valor_dispensador["id"]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*10, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_dispensador += $cassette["count"];
									$total_dispensador += $cassette["count"]*10;
								}else if($cassette["itemType"] == "BILL_EUR_2000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/2000.png\" height=\"35\"> ".$valor_dispensador["id"]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*20, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_dispensador += $cassette["count"];
									$total_dispensador += $cassette["count"]*20;
								}else if($cassette["itemType"] == "BILL_EUR_5000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/5000.png\" height=\"35\"> ".$valor_dispensador["id"]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*50, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_dispensador += $cassette["count"];
									$total_dispensador += $cassette["count"]*50;
								}
							}
						}
					}
				}

				if(strpos($valor["id"], "FUJITSU") !== false){
					$dispensador = $valor["moduleList"];
					foreach($dispensador as &$valor_dispensador){
						if(strpos($valor_dispensador["id"], "CASS") !== false){
							$valor_cassette = $valor_dispensador["breakout"];
							foreach ($valor_cassette as $cassette){
								if($cassette["itemType"] == "BILL_EUR_1000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/1000.png\" height=\"35\"> ".$valor_dispensador["id"]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*10, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_dispensador += $cassette["count"];
									$total_dispensador += $cassette["count"]*10;
								}else if($cassette["itemType"] == "BILL_EUR_2000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/2000.png\" height=\"35\"> ".$valor_dispensador["id"]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*20, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_dispensador += $cassette["count"];
									$total_dispensador += $cassette["count"]*20;
								}else if($cassette["itemType"] == "BILL_EUR_5000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 1% 0'>";
									$mensaje .= "<div style='width: 50%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/5000.png\" height=\"35\"> ".$valor_dispensador["id"]."</div>";
						    		$mensaje .= "<div style='width: 16%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*50, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_dispensador += $cassette["count"];
									$total_dispensador += $cassette["count"]*50;
								}
							}
						}
					}
				}
			}
		}

		$total_activo = $total_multi + $total_reciclador + $total_dispensador;

		$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #287325; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
		$mensaje .= "<div style='width: 33%; float: left'><b>TOTAL ACTIVO</b></div>";
		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>&nbsp;</b></div>";
		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($total_activo, 2, ',', '.'). "&euro;</b></div>";
		$mensaje .= "</div>";

		$mensaje .= "</div>";

		$mensaje .= "</div>";

		// *** FIN DE ACTIVO ***

		//
		// // // // // // // // //
		//

		// *** DINERO NO ACTIVO ***

		// Cuentas y total
		$cuenta_no_activo = $cuenta_apilador = $cuenta_cajon = $cuenta_rechazo = 0;
		$total_no_activo = $total_apilador = $total_cajon = $total_rechazo = 0;
		
		$mensaje .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
						<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #fe0000; color: #fff;">
							DINERO NO ACTIVO<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
						</div>
						<div class="panel-body">';

		foreach($res["AccountancyStatusAnswer"]["deviceList"] as &$valor){
			if($valor["id"] != "DECLARED_DEPOSIT" && $valor["id"] != "MANUAL_DEPOSIT"){
				if(strpos($valor["id"], "HOP") !== false){
					continue;
				}

				$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #cd3e3e; padding: 2% 2%; border-bottom: 1px solid #fff;'>";
				if(strpos($valor["id"], "BILL_ACCEPTOR_1") !== false){
					$mensaje .= "<div style='width: 33%; float: left'><b>APILADOR</b></div>";
				}else if(strpos($valor["id"], "BILL_RECYCLER") !== false){
					$mensaje .= "<div style='width: 33%; float: left'><b>CAJÓN</b></div>";
				}else if(strpos($valor["id"], "NOTE_DISPENSER") !== false){
					$mensaje .= "<div style='width: 33%; float: left'><b>RECHAZO</b></div>";
				}else if(strpos($valor["id"], "FUJITSU") !== false){
					$mensaje .= "<div style='width: 33%; float: left'><b>RECHAZO</b></div>";
				}
				$mensaje .= "</div>";

				if(strpos($valor["id"], "BILL_ACCEPTOR_1") !== false){
					$apilador = $valor["moduleList"];
					foreach($apilador as &$valor_apilador){
						$apilador = $valor_apilador["breakout"];
						foreach ($apilador as &$apilador_valor) {
							if($apilador_valor["itemType"] == "BILL_EUR_500"){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/500.png\" height=\"35\"></div>";
					    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$apilador_valor["count"]."</b></div>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($apilador_valor["count"]*5, 2, ',', '.')."&euro;</b></div>";
								$mensaje .= "</div>";
								$cuenta_apilador += $apilador_valor["count"];
								$total_apilador += $apilador_valor["count"]*5;
							}else if($apilador_valor["itemType"] == "BILL_EUR_1000"){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/1000.png\" height=\"35\"></div>";
					    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$apilador_valor["count"]."</b></div>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($apilador_valor["count"]*10, 2, ',', '.')."&euro;</b></div>";
								$mensaje .= "</div>";
								$cuenta_apilador += $apilador_valor["count"];
								$total_apilador += $apilador_valor["count"]*10;
							}else if($apilador_valor["itemType"] == "BILL_EUR_2000"){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/2000.png\" height=\"35\"></div>";
					    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$apilador_valor["count"]."</b></div>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($apilador_valor["count"]*20, 2, ',', '.')."&euro;</b></div>";
								$mensaje .= "</div>";
								$cuenta_apilador += $apilador_valor["count"];
								$total_apilador += $apilador_valor["count"]*20;
							}else if($apilador_valor["itemType"] == "BILL_EUR_5000"){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/5000.png\" height=\"35\"></div>";
					    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$apilador_valor["count"]."</b></div>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($apilador_valor["count"]*50, 2, ',', '.')."&euro;</b></div>";
								$mensaje .= "</div>";
								$cuenta_apilador += $apilador_valor["count"];
								$total_apilador += $apilador_valor["count"]*50;
							}else if($apilador_valor["itemType"] == "BILL_EUR_10000"){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/10000.png\" height=\"35\"></div>";
					    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$apilador_valor["count"]."</b></div>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($apilador_valor["count"]*100, 2, ',', '.')."&euro;</b></div>";
								$mensaje .= "</div>";
								$cuenta_apilador += $apilador_valor["count"];
								$total_apilador += $apilador_valor["count"]*100;
							}else if($apilador_valor["itemType"] == "BILL_EUR_20000"){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/20000.png\" height=\"35\"></div>";
					    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$apilador_valor["count"]."</b></div>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($apilador_valor["count"]*200, 2, ',', '.')."&euro;</b></div>";
								$mensaje .= "</div>";
								$cuenta_apilador += $apilador_valor["count"];
								$total_apilador += $apilador_valor["count"]*200;
							}else if($apilador_valor["itemType"] == "BILL_EUR_50000"){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/50000.png\" height=\"35\"></div>";
					    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$apilador_valor["count"]."</b></div>";
								$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($apilador_valor["count"]*500, 2, ',', '.')."&euro;</b></div>";
								$mensaje .= "</div>";
								$cuenta_apilador += $apilador_valor["count"];
								$total_apilador += $apilador_valor["count"]*500;
							}
						}
					}
				}

				if(strpos($valor["id"], "BILL_RECYCLER") !== false){
					$reciclador = $valor["moduleList"];
					foreach($reciclador as &$valor_reciclador){
						if(strpos($valor_reciclador["id"], "CASH") !== false){
							$bill_recycler = $valor_reciclador["breakout"];
							foreach($bill_recycler as $recycler){
								if($recycler["itemType"] == "BILL_EUR_500"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/500.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*5, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_cajon += $recycler["count"];
									$total_cajon += $recycler["count"]*5;
								}else if($recycler["itemType"] == "BILL_EUR_1000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/1000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*10, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_cajon += $recycler["count"];
									$total_cajon += $recycler["count"]*10;
								}else if($recycler["itemType"] == "BILL_EUR_2000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/2000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*20, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_cajon += $recycler["count"];
									$total_cajon += $recycler["count"]*20;
								}else if($recycler["itemType"] == "BILL_EUR_5000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/5000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*50, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_cajon += $recycler["count"];
									$total_cajon += $recycler["count"]*50;
								}else if($recycler["itemType"] == "BILL_EUR_10000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/10000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*100, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_cajon += $recycler["count"];
									$total_cajon += $recycler["count"]*100;
								}else if($recycler["itemType"] == "BILL_EUR_20000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/20000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*200, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_cajon += $recycler["count"];
									$total_cajon += $recycler["count"]*200;
								}else if($recycler["itemType"] == "BILL_EUR_50000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/50000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recycler["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($recycler["count"]*500, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_cajon += $recycler["count"];
									$total_cajon += $recycler["count"]*500;
								}
							}
						}
					}
				}

				if(strpos($valor["id"], "NOTE_DISPENSER") !== false){
					$dispensador = $valor["moduleList"];
					foreach($dispensador as &$valor_dispensador){
						if(strpos($valor_dispensador["id"], "REJ") !== false){
							$valor_cassette = $valor_dispensador["breakout"];
							foreach ($valor_cassette as $cassette){
								if($cassette["itemType"] == "BILL_EUR_1000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/1000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*10, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_rechazo += $cassette["count"];
									$total_rechazo += $cassette["count"]*10;
								}else if($cassette["itemType"] == "BILL_EUR_2000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/2000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*20, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_rechazo += $cassette["count"];
									$total_rechazo += $cassette["count"]*20;
								}else if($cassette["itemType"] == "BILL_EUR_5000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/5000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*50, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_rechazo += $cassette["count"];
									$total_rechazo += $cassette["count"]*50;
								}
							}
						}
					}
				}

				if(strpos($valor["id"], "FUJITSU") !== false){
					$dispensador = $valor["moduleList"];
					foreach($dispensador as &$valor_dispensador){
						if(strpos($valor_dispensador["id"], "REJ") !== false){
							$valor_cassette = $valor_dispensador["breakout"];
							foreach ($valor_cassette as $cassette){
								if($cassette["itemType"] == "BILL_EUR_1000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/1000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*10, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_rechazo += $recycler["count"];
									$total_rechazo += $recycler["count"]*10;
								}else if($cassette["itemType"] == "BILL_EUR_2000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/2000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*20, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_rechazo += $cassette["count"];
									$total_rechazo += $cassette["count"]*20;
								}else if($cassette["itemType"] == "BILL_EUR_5000"){
									$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 1% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/5000.png\" height=\"35\"></div>";
						    		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$cassette["count"]."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($cassette["count"]*50, 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
									$cuenta_rechazo += $cassette["count"];
									$total_rechazo += $cassette["count"]*50;
								}
							}
						}
					}
				}
			}
		}

		$total_no_activo = $total_apilador + $total_cajon + $total_rechazo;

		$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #c90e0e; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
		$mensaje .= "<div style='width: 33%; float: left'><b>TOTAL NO ACTIVO</b></div>";
		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>&nbsp;</b></div>";
		$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($total_no_activo, 2, ',', '.'). "&euro;</b></div>";
		$mensaje .= "</div>";

		$mensaje .= "</div>";

		$mensaje .= "</div>";

		// *** FIN DE NO ACTIVO ***

		//
		// // // // // // // // //
		//

		// FAMILIAS
		$result = $client->call("getPrizeAccountancy", array("aParams" => $credentialParam));
		if (!isset($result["getPrizeAccountancy"])) {
			$res["getPrizeAccountancy"] = $result;
		}else{
			$res = $result;
		}

		$mensaje .= "<div class='panel panel-default col-md-6 col-sm-12 paneles_form'>";
		$mensaje .= "<div class='panel-body'>";
		$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #6f1668; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#familias'>";
		$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Familias</b></div>";
		$mensaje .= "</div>";

		$mensaje .= "<div id='familias' class='collapse'>";

		for ($i=0; $i < count($res["getPrizeAccountancy"]["prizeList"]); $i++) {
			$importef = $res["getPrizeAccountancy"]["prizeList"][$i]["importe"]/100;

			$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #47145d; padding: 2% 0 0 1%'>";
			$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><b>".$res["getPrizeAccountancy"]["prizeList"][$i]["familyName"]."</b></div>";
			$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>Importe</b></div>";
			$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$importef."&euro;</b></div>";
			$mensaje .= "</div>";
		}

		$mensaje .= "</div>";

		$mensaje .= "</div>";

		$mensaje .= "</div>";

		return $mensaje;
	}

	// Cajero Gistra
	function return_cajero($id_salon,$salon_nombre,$cajero_info){
    	error_reporting(E_ERROR | E_PARSE | E_NOTICE);

		include 'cajeros/includes.php';

		setlocale(LC_MONETARY, 'es_ES');

		//echo setlocale(LC_ALL, 0);

		$mensaje = ""; // Inicializada variable
		$salonescont = 0;
		$error = 0;
		$arqueo = 0;
		$totalsumrefills = 0;
		//$debug = 0;

		// INICIO WHILE $salonescont[]
		while($salonescont < $salonescant){
		$error=0; //Inicializamos error a 0 por cada salon

			// INICIO TD HTML
			// Por cada salon
			$mensaje .= '<span style="font-size: 20px;">'.$salones[$salonescont]."</span></h3><hr>";
			// FIN TD HTML
			
			// No sumamos contador para array 0
			// Conexion
			$con=mysqli_connect($servidor[$salonescont].":".$puerto[$salonescont], $usuario, $clave, $tabla);
			// Comprobar conexion
			if (mysqli_connect_errno()){
				$error=1;
			}

			//INICIO DEBUG
			if($debug == 1){
				echo "Conectando a " . $salones[$salonescont] . " " . $servidor[$salonescont] . "";
			}
			//FIN DEBUG

			// Comprobar si el servidor existe
			if($error != 1){
					// Consultas
					// Chequeo General
					if($version == null || !isset($version)){ $version=array(2900);	}					

					if($version[$salonescont] < 3000){
						$chequea = mysqli_query($con,"select count(*) as filas from collect");
					}else{
						$chequea = mysqli_query($con,"select count(*) as filas from collect WHERE State='A'");
					}
					//$filas = mysqli_num_rows($chequea);
					$filasrs = mysqli_fetch_array($chequea);
					$filas = $filasrs[0];

					// Estar comprobando hasta que hayan al menos 24 datos, sabremos que está actualizado y podremos mostrar los datos finalmente
					$contador_collect = 0;
					while ($filas < $collect[$salonescont]){
						$contador_collect = ++$contador_collect;
					 	if($contador_collect < 10){
							$chequea = mysqli_query($con,"select count(*) as filas from collect WHERE State='A'");							
							$filasrs = mysqli_fetch_array($chequea);
							$filas = $filasrs[0];
					 	}else{
							break;								
						}
					}

					// Update
					$update = mysqli_query($con,"select LastUpdateDateTime from collectinfo ORDER BY LastUpdateDateTime ASC LIMIT 1");
					$rowupdate = mysqli_fetch_array($update);
					$fechaupdate = $rowupdate[0];

					// Arqueo
					if($version[$salonescont] < 3000){
						$arqueo = mysqli_query($con,"select sum(amount) as arqueo from collect");
					}else{
						$arqueo = mysqli_query($con,"select sum(amount) as arqueo from collect WHERE State='A'");
					}

					// Cashbox
					if($version[$salonescont] < 3000){
						$cashboxdetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE LocationType='Cashbox' GROUP BY MoneyValue ORDER BY MoneyValue ASC");
						$cashboxtotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect WHERE LocationType='Cashbox'");
					}else{
						$cashboxdetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE State='A' AND LocationType='Cashbox' GROUP BY MoneyValue ORDER BY MoneyValue ASC");
						$cashboxtotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect WHERE State='A' AND LocationType='Cashbox'");
					}

					// Cassette
					if($version[$salonescont] < 3000){
						$cassettedetalle = mysqli_query($con,"SELECT LocationType as Cassette, MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE LocationType LIKE '%Cassette%' GROUP BY LocationType ORDER BY LocationType ASC");
						//$cassettedetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect where LocationType LIKE '%Cassette%' ORDER BY LocationType ASC");
						$cassettetotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect where LocationType LIKE '%Cassette%'");
					}else{
						$cassettedetalle = mysqli_query($con,"SELECT LocationType as Cassette, MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE State='A' AND LocationType LIKE '%Cassette%' GROUP BY LocationType ORDER BY LocationType ASC");
						$cassettetotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect WHERE State='A' AND LocationType LIKE '%Cassette%'");
					}

					// MultiCoin
					if($version[$salonescont] < 3000){
						$multicoindetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect where LocationType='MultiCoin' ORDER BY MoneyValue ASC");
						$multicointotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect where LocationType='MultiCoin'");
					}else{
						$multicoindetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE State='A' AND LocationType='MultiCoin' GROUP BY MoneyValue ORDER BY MoneyValue ASC");
						$multicointotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect WHERE State='A' AND LocationType='MultiCoin'");
					}

					// Total Activo
					if($version[$salonescont] < 3000){
						$totalactivo = mysqli_query($con,"SELECT SUM(T1.sumatoriocantidad) cantidad, SUM(T1.sumatoriototal) totalactivo FROM (SELECT sum(quantity) sumatoriocantidad, sum(amount) sumatoriototal FROM collect WHERE LocationType = \"MultiCoin\" UNION all SELECT sum(quantity), sum(amount) FROM collect WHERE LocationType LIKE '%Hopper%'  UNION all SELECT sum(quantity), sum(amount) FROM collect WHERE LocationType LIKE '%Cassette%') T1");
					}else{
						$totalactivo = mysqli_query($con,"SELECT SUM(T1.sumatoriocantidad) cantidad, SUM(T1.sumatoriototal) totalactivo FROM (SELECT sum(quantity) sumatoriocantidad, sum(amount) sumatoriototal FROM collect WHERE State='A' AND LocationType = \"MultiCoin\" UNION all SELECT sum(quantity), sum(amount) FROM collect WHERE State='A' AND LocationType LIKE '%Hopper%'  UNION all SELECT sum(quantity), sum(amount) FROM collect WHERE State='A' AND LocationType LIKE '%Cassette%'  ) T1");
					}

					// Total NO Activo
					if($version[$salonescont] < 3000){
						$totalnoactivo = mysqli_query($con,"SELECT SUM(T2.sumatoriocantidad) cantidad, SUM(T2.sumatoriototal) totalnoactivo FROM (SELECT sum(quantity) sumatoriocantidad, sum(amount) sumatoriototal FROM collect WHERE LocationType = \"CashBox\" UNION all SELECT sum(quantity), sum(amount) FROM collect WHERE LocationType = \"Stacker\") T2");
					}else{
						$totalnoactivo = mysqli_query($con,"SELECT SUM(T2.sumatoriocantidad) cantidad, SUM(T2.sumatoriototal) totalnoactivo FROM (SELECT sum(quantity) sumatoriocantidad, sum(amount) sumatoriototal FROM collect WHERE State='A' AND LocationType = \"CashBox\" UNION all SELECT sum(quantity), sum(amount) FROM collect WHERE State='A' AND LocationType LIKE \"%Stacker%\") T2");
					}

					// Hopper
					if($version[$salonescont] < 3000){
						$hopperdetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect where LocationType LIKE '%Hopper%' GROUP BY MoneyValue ORDER BY MoneyValue ASC");
						$hoppertotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect where LocationType LIKE '%Hopper%'");
						$hopperfilas = mysqli_num_rows($hopperdetalle);
						// Comprobamos Hopper que haya al menos 1 sino que repita para que hopper no sea 0, con esto controlamos si el hopper se está usando en ese momento de la petición
						if($hopperfilas > 0){
							while ($hopperfilas <= 0){
								$hopperdetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect where LocationType LIKE '%Hopper%'");
								$hopperfilas = mysqli_num_rows($hopperdetalle);
							}
						}
					}else{
						$hopperdetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE State='A' AND LocationType LIKE '%Hopper%' GROUP BY MoneyValue ORDER BY MoneyValue ASC");
						$hoppertotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect WHERE State='A' AND LocationType LIKE '%Hopper%'");
						$hopperfilas = mysqli_num_rows($hopperdetalle);
						// Comprobamos Hopper que haya al menos 1 sino que repita para que hopper no sea 0, con esto controlamos si el hopper se está usando en ese momento de la petición
						if($hopperfilas > 0){
							while ($hopperfilas <= 0){
								$hopperdetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE State='A' AND LocationType LIKE '%Hopper%'");
								$hopperfilas = mysqli_num_rows($hopperdetalle);
							}
						}
					}

					// Stacker
					if($version[$salonescont] < 3000){
						$stackerdetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect where LocationType='Stacker' GROUP BY MoneyValue ORDER BY Id");
						$stackerdetalletickets = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect where LocationType='Stacker' AND MoneyValue='Tickets'");
						$stackertotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect where LocationType='Stacker'");
						$stackertotaltickets = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect where LocationType='Stacker' AND MoneyValue='Tickets'");
					}else{
						$stackerdetalle = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE State='A' AND LocationType LIKE '%Stacker%' GROUP BY MoneyValue ORDER BY Id");
						$stackerdetalletickets = mysqli_query($con,"SELECT MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE State='A' AND LocationType LIKE '%Stacker%' AND MoneyValue='Tickets'");
						$stackertotal = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect WHERE State='A' AND LocationType LIKE '%Stacker%'");
						$stackertotaltickets = mysqli_query($con,"SELECT sum(quantity) as cantidad, sum(amount) as total FROM Collect WHERE State='A' AND LocationType LIKE '%Stacker%' AND MoneyValue='Tickets'");
					}

					while($rowstackerdetalletickets = mysqli_fetch_array($stackerdetalletickets)){
						$ticketscantidad = $rowstackerdetalletickets['cantidad'];
 				  	}

					while($rowstackertotaltickets = mysqli_fetch_array($stackertotaltickets)){
						$ticketstotal = $rowstackertotaltickets['total'];
				  	}

					// Apuestas
					$apuestastotal = mysqli_query($con,"SELECT sum(cantidad) as total FROM (SELECT value as cantidad FROM tickets WHERE Command='CLOSE' AND TypeIsBets = '1' AND (Type LIKE '%APUESTAS%' OR Type = 'CCM KIROLSOFT' OR Type = 'CCM RETA') ORDER BY DateTime DESC LIMIT ".$ticketscantidad.") as cantidad");

					while($rowapuestastotal = mysqli_fetch_array($apuestastotal)){
						$ticketstotalapuestas = $rowapuestastotal['total'];
				  	}

					// Detalles Tickets Y Apuestas
					$ultreca = mysqli_query($con,"SELECT DateTime as fecha from logs where Type = 'movementEmptyStackerbybets' or Type = 'movementBalanceCollectByBets' ORDER BY id DESC LIMIT 1");
					$rowultreca = mysqli_fetch_array($ultreca);
					if ($rowultreca[0] == null || !isset($rowultreca[0]))	{	$rowultreca[0] = '2014-01-01 00:00:00';	} // Si nunca ha habido una reca de apuestas antes, ponemos prncipio de año // Hay que buscar entonces ultima reca no de bets sino normal
					$ultimareca = $rowultreca[0];

					$ultrecastackerind = mysqli_query($con,"SELECT Text, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha_formateada, DateTime as fecha from logs where Type = 'movementEmptyStacker' AND Text != 'EXTRACTED:|TOTAL:0' ORDER BY id DESC LIMIT 1");
					$rowultrecastackerind = mysqli_fetch_array($ultrecastackerind);
					$ultimarecastackerind = $rowultrecastackerind[2];
					$ultimarecastackerind_importe = $rowultrecastackerind[0];
					$ultimarecastackerind_fecha = $rowultrecastackerind[1];
					$ultimarecastackerind_importepos = strpos($ultimarecastackerind_importe, "TOTAL:");
					$ultimarecastackerind_importe = (double)str_replace(",", ".", substr($ultimarecastackerind_importe, $ultimarecastackerind_importepos+6 ));

					if($ips = 1){ //SI TIENE IPS
						$ultrecastacker = mysqli_query($con,"SELECT Text, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha_formateada, DateTime as fecha from logs where Type = 'movementEmptyStacker' AND Text != 'EXTRACTED:|TOTAL:0' ORDER BY id DESC LIMIT 1");
					}else{
						$ultrecastacker = mysqli_query($con,"SELECT Text, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha_formateada, DateTime as fecha from logs where Type = 'movementEmptyStacker' AND Text LIKE BINARY '%xT%' AND Text != 'EXTRACTED:|TOTAL:0' ORDER BY id DESC LIMIT 1");
					}

					$rowultrecastacker = mysqli_fetch_array($ultrecastacker);
					$ultimarecastacker = $rowultrecastacker[2];
					$ultimarecastacker_importe = $rowultrecastacker[0];
					$ultimarecastacker_fecha = $rowultrecastacker[1];
					$ultimarecastacker_importepos = strpos($ultimarecastacker_importe, "TOTAL:");
					$ultimarecastacker_importe = (double)str_replace(",", ".", substr($ultimarecastacker_importe, $ultimarecastacker_importepos+6 ));

					if($version[$salonescont] < 3000){
						$cant_totaltickets = mysqli_query($con,"SELECT (select Quantity from collect where LocationType='Stacker' and MoneyValue='Tickets' LIMIT 1) as computo, (SELECT count(*) from logs where Type LIKE '%movementRefillbybets%' AND DateTime > '".$ultimareca."') as entradas, (select count(*) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND (type = 'CCM KIROLSOFT' OR Type = 'CCM RETA')) as kirol, (select count(*) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND type LIKE '%APUESTAS%') as manuales, count(*) as total FROM tickets WHERE Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."'");
					}else{
						$cant_totaltickets = mysqli_query($con,"SELECT (select Quantity from collect WHERE State='A' AND LocationType LIKE '%Stacker%' and MoneyValue='Tickets' LIMIT 1) as computo, (SELECT count(*) from logs where Type LIKE '%movementRefillbybets%' AND DateTime > '".$ultimareca."') as entradas, (select count(*) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND (type = 'CCM KIROLSOFT' OR Type = 'CCM RETA')) as kirol, (select count(*) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND type LIKE '%APUESTAS%') as manuales, count(*) as total FROM tickets WHERE Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."'");
					}

					if(!$cant_totaltickets){									
						$cant_totaltickets = mysqli_query($con,"SELECT (select Quantity from collect where LocationType='Stacker' and MoneyValue='Tickets' LIMIT 1)as computo, (select count(*) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND (type = 'CCM KIROLSOFT' OR Type = 'CCM RETA')) as kirol, (select count(*) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND type LIKE '%APUESTAS%') as manuales, count(*) as total FROM tickets WHERE Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."'");
						while($rowcant_totaltickets = mysqli_fetch_array($cant_totaltickets)){
							$cant_totaltickets_computo = $rowcant_totaltickets['computo'];
							$cant_totaltickets_entradas = 0;
							$cant_totaltickets_kirol = $rowcant_totaltickets['kirol'];
							$cant_totaltickets_manuales = $rowcant_totaltickets['manuales'];
							$cant_totaltickets_total = $rowcant_totaltickets['total'];
					 	}
					}else{									
						while($rowcant_totaltickets = mysqli_fetch_array($cant_totaltickets)){
							$cant_totaltickets_computo = $rowcant_totaltickets['computo'];
							$cant_totaltickets_entradas = $rowcant_totaltickets['entradas'];
							$cant_totaltickets_kirol = $rowcant_totaltickets['kirol'];
							$cant_totaltickets_manuales = $rowcant_totaltickets['manuales'];
							$cant_totaltickets_total = $rowcant_totaltickets['total'];
					 	}
					}

					$cant_totaltickets_gtech_salon = mysqli_query($con,"SELECT (select count(*) from tickets WHERE Type LIKE '%GTECH%' AND Datetime > '".$ultimareca."') as tickets, (select sum(value) from tickets where type LIKE '%GTECH%' AND Datetime > '".$ultimareca."') as valor");

					$rowcant_totaltickets_gtech_salon = mysqli_fetch_array($cant_totaltickets_gtech_salon);
					$cant_totaltickets_gtech_salon_tickets = $rowcant_totaltickets_gtech_salon[0];
					$cant_totaltickets_gtech_salon_valor = $rowcant_totaltickets_gtech_salon[1];

				  	$cant_totaltickets_manuales_salon = $cant_totaltickets_computo-($cant_totaltickets_kirol+$cant_totaltickets_manuales);

					$cant_totaltickets_total_2 = $cant_totaltickets_manuales_salon+$cant_totaltickets_manuales+$cant_totaltickets_kirol;

					if($version[$salonescont] < 3000){
						$sum_totaltickets = mysqli_query($con,"SELECT (select Amount from collect where LocationType='Stacker' and MoneyValue='Tickets' LIMIT 1)as computo, (select sum(value) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND (Type = 'CCM KIROLSOFT' OR Type = 'CCM RETA')) as kirol, (select sum(value) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND (type LIKE '%APUESTAS%')) as manuales, sum(value) as total FROM tickets WHERE Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."'");
					}else{
						$sum_totaltickets = mysqli_query($con,"SELECT (select Amount from collect WHERE State='A' AND LocationType LIKE '%Stacker%' and MoneyValue='Tickets' LIMIT 1)as computo, (select sum(value) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND (Type = 'CCM KIROLSOFT' OR Type = 'CCM RETA')) as kirol, (select sum(value) from tickets where Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."' AND (type LIKE '%APUESTAS%')) as manuales, sum(value) as total FROM tickets WHERE Command = 'CLOSE' and typeisbets = 1 AND Datetime > '".$ultimareca."'");
					}

					while($rowsum_totaltickets = mysqli_fetch_array($sum_totaltickets)){
						$sum_totaltickets_computo = $rowsum_totaltickets['computo'];
						$sum_totaltickets_kirol = $rowsum_totaltickets['kirol'];
						if ($rowsum_totaltickets['manuales'] == null || !isset($rowsum_totaltickets['manuales']))	{	$rowsum_totaltickets['manuales'] = 0;	}
						$sum_totaltickets_manuales = $rowsum_totaltickets['manuales'];
						$sum_totaltickets_total = $rowsum_totaltickets['total'];
				    }

				    $sum_totaltickets_manuales_salon = $sum_totaltickets_computo-($sum_totaltickets_kirol+$sum_totaltickets_manuales);
					$sum_totaltickets_total_2 = $sum_totaltickets_manuales_salon+$sum_totaltickets_manuales+$sum_totaltickets_kirol;

					$apuestasdetalle = mysqli_query($con,"SELECT DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha, REPLACE(REPLACE(Type, 'CCM RETA', 'APUESTAS'), 'CCM KIROLSOFT', 'APUESTAS') as ticket, Value as cantidad FROM tickets WHERE Command='CLOSE' AND TypeIsBets = '1' ORDER BY id DESC LIMIT ".$cant_totaltickets_kirol.""); // ".$ticketstotalapuestascantidad."");

					//$apuestasdetalle = mysqli_query($con,"SELECT DATE_FORMAT(DateTime, '%d-%m-%Y  %H:%i') as fecha, TRIM(LEADING 'KIROLSOFT-' FROM Comment) as ticket, Value as cantidad FROM tickets WHERE Type LIKE '%KIROLSOFT%' AND Command='CLOSE' ORDER BY DateTime DESC LIMIT ".$ticketscantidad."");
					//$apuestastotal = mysqli_query($con,"SELECT count(*) as cantidad, sum(Value) as total FROM tickets WHERE Type LIKE '%KIROLSOFT%' AND Command='CLOSE' DESC LIMIT ".$ticketscantidad."");

					// Todas Refills desde Ultima Reca
					$refills = mysqli_query($con,"SELECT Text from logs where Type = 'movementRefillbybets' AND DateTime > '".$ultimareca."' ORDER BY id ASC");

					// Ultima Reca Apuestas
					$ultrecaapu = mysqli_query($con,"SELECT Text, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha from logs where Type = 'movementEmptyStackerbybets' or Type = 'movementBalanceCollectByBets' ORDER BY id DESC LIMIT 1");
					$rowultrecaapu = mysqli_fetch_array($ultrecaapu);
					$ultrecaapu_importe = $rowultrecaapu[0];
					$ultrecaapu_fecha = $rowultrecaapu[1];
					$ultrecaapu_importepos = strpos($ultrecaapu_importe, "TOTAL:");
					$ultrecaapu_importe = (double)str_replace(",", ".", substr($ultrecaapu_importe, $ultrecaapu_importepos+6 ));

					// Manuales Detalle (NO ELIMINADOS) // En 3.1.0.1 ya tenemos control de fecha de tickets manuales viejos en espera
					if($version[$salonescont] < 3100){
						$manuales_detalle = mysqli_query($con,"SELECT TicketNumber as numero, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha, REPLACE(REPLACE(Type, 'IPSServer', 'ZITRO'), 'PAGO MANUAL ', '') as ticket, Value as cantidad FROM tickets WHERE Command='CLOSE' and typeisbets = 0 and type not like '%apuestas%' and type not like '%kirol%' AND LastCommandChangeDateTime > '".$ultimarecastacker."' ORDER BY id DESC");
					}else{
						$manuales_detalle = mysqli_query($con,"SELECT TicketNumber as numero, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha, REPLACE(REPLACE(Type, 'IPS TicketController', 'IPS'), 'PAGO MANUAL ', '') as ticket, Value as cantidad FROM tickets WHERE Command='CLOSE' and typeisbets = 0 and type not like '%apuestas%' and type not like '%kirol%' AND DateTime > '".$ultimarecastacker."' ORDER BY id DESC");
					}

					//Tickets GTECH
					$gtech_detalle = mysqli_query($con,"SELECT TicketNumber as numero, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha, REPLACE(REPLACE(Type, 'GTECH TicketController', 'GTECH_PC'), 'CCM GTECH', 'GTECH_CCM') as ticket, Value as cantidad FROM tickets WHERE Command='CLOSE' and typeisbets = 0 and type not like '%apuestas%' and type not like '%reta%' and type not like '%kirol%' AND Type LIKE '%GTECH%' AND DateTime > '".$ultimarecastacker."' ORDER BY id DESC");

					// Ultimo Refill Apuestas
					$ultrefillapu = mysqli_query($con,"SELECT Text, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha from logs where Type = 'movementRefillByBets' ORDER BY id DESC LIMIT 1");
					$rowultrefillapu = mysqli_fetch_array($ultrefillapu);
					$ultrefillapu_importe = $rowultrefillapu[0];
					$ultrefillapu_fecha = $rowultrefillapu[1];
					$ultrefillapu_importepos = strpos($ultrefillapu_importe, "TOTAL:");
					$ultrefillapu_importe = (double)str_replace(",", ".", substr($ultrefillapu_importe, $ultrefillapu_importepos+6 ));
					// Ultimos Refills Apuestas
					$ultrefillapus = mysqli_query($con,"SELECT Text as importe, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha from logs where Type = 'movementRefillByBets' ORDER BY id DESC LIMIT ".$cant_totaltickets_entradas."");

					// Ultimas 5 Refills Manuales
					$ultrefillmanu = mysqli_query($con,"SELECT Text as importe, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha from logs where Type = 'movementRefill' ORDER BY id DESC LIMIT 5");

					// Ultima Extraccion Apuestas
					$ultreextapu = mysqli_query($con,"SELECT Text, DATE_FORMAT(DateTime, '%d/%m/%y  %H:%i') as fecha from logs where Type = 'movementChangeByBets' ORDER BY id DESC LIMIT 1");
					$rowultreextapu = mysqli_fetch_array($ultreextapu);
					$ultreextapu_importe = $rowultreextapu[0];
					$ultreextapu_fecha = $rowultreextapu[1];
					$ultreextapu_importepos = strpos($ultreextapu_importe, "TOTAL:");
					$ultreextapu_importe = (double)str_replace(",", ".", substr($ultreextapu_importe, $ultreextapu_importepos+6 ));

					// Eliminados
					$eliminados = mysqli_query($con,"SELECT Text from logs where Type = 'movementEmptyStacker' and DateTime < '".$ultimarecastacker."' ORDER BY id DESC");

					// INICIO SACAR TICKETS ELIMINADOS Y METERLOS EN UN ARRAY PARA BUSCARLOS Y COMPARARLOS DSPUES Y DIFERENCIARLOS DE LOS MANUALES //$tickets_eliminados
					$tickets = array();
					$tickets_eliminados = array();
					$contador = 0;

					while($roweliminados = mysqli_fetch_array($eliminados)){
						$tickets[] = $roweliminados['Text'];
					}

					$tickets = implode('_|_',$tickets);
					$cantidad = 0;
					$string = $tickets;
					$cantidad = substr_count($string, 'xT');

					$contador=$contador+1;
					$i=1;
					$ul=0;
					$pos = strpos($string, "xT");

					while ($i <= $cantidad){
						$tickets_eliminados[$i] = substr($string, $pos+2, 8 );
						$ul = $pos+10;
						$pos = strpos($string, "xT", $ul+1);
						$i++;
					}

					// ** DINERO ACTIVO **					
					// ARQUEO DETALLE
					
					//SALDO INICIAL ASIGNADO DESDE COLLECTDETAILS								
					$mensaje .= "<div class='col-md-6 col-sm-12' style='margin-bottom: 20px; padding: 0'>";
					$mensaje .= "<table width='365' class=\"azularqueo\">";
					$mensaje .= "<tbody>";
					$mensaje .= "<th colspan=4 align=\"middle\" width=\"280\" style=\"color:white;background-color: #123574;\"><b>Arqueo Detalle</b></th> <!-- ARQUEO DETALLE -->";
					$mensaje .= "<tr>";
					$mensaje .= "<td colspan=2 align=right style=\"color:black;background-color: #ffffff;font-weight:bold;\">Disponible</td>";
					$mensaje .= "<td align=right style=\"color:black;background-color: #ffffff;font-weight:bold\">Apilador y Cajón</td>";
					$mensaje .= "<td align=right style=\"color:black;background-color: #ffffff;font-weight:bold\">Arqueo (A)</td>";
					$mensaje .= "</tr>";
					$mensaje .= "<tr>";
					$con_principal = mysqli_query($con,"select name,money1,money2,money3 from collectdetails where state = 'A' and name = 'Principal'");
					$row_principal = mysqli_fetch_array($con_principal);
					$principal_1 = $row_principal[1];
					$principal_2 = $row_principal[2];
					$principal_3 = $row_principal[3];
					$mensaje .= "<td style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">Principal</td>";
					$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$principal_1."&euro;</td>";
					$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$principal_2."&euro;</td>";
					$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$principal_3."&euro;</td>";

					$A_details = ($principal_3);

					$mensaje .= "</tr>";
					$mensaje .= "<tr>";
					$mensaje .= "<td colspan=4>&nbsp;</td>";
					$mensaje .= "</tr>";
					
					$mensaje .= "<tr>";			
					$mensaje .= "<td colspan=2 align=right style=\"color:black;background-color: #f0e68c;font-weight:bold;\">Entradas</td>";
					$mensaje .= "<td align=right style=\"color:black;background-color: #f0e68c;font-weight:bold;\">Salidas</td>";
					$mensaje .= "<td align=right style=\"color:black;background-color: #f0e68c;font-weight:bold;\">Balance (B)</td>";
					$mensaje .= "</tr>";
					$mensaje .= "<tr>";							

					$con_porapuestas = mysqli_query($con,"select name,money1,money2,money3 from collectdetails where state = 'A' and name = 'Por Apuestas'");
					$row_porapuestas = mysqli_fetch_array($con_porapuestas);
					$porapuestas_1 = $row_porapuestas[1];
					$porapuestas_2 = $row_porapuestas[2];
					$porapuestas_3 = $row_porapuestas[3];					
						
					$mensaje .= "<td style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px\">Por Apuestas</td>";
					$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$porapuestas_1."&euro;</td>";
					$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$porapuestas_2."&euro;</td>";
					$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$porapuestas_3."</td>";

					$B_details = ($porapuestas_3);
													
					$mensaje .= "</tr>";
					$mensaje .= "<tr>";
					$mensaje .= "<td colspan=4>&nbsp;</td>";
					$mensaje .= "</tr>";

					$mensaje .= "<tr>";
					$mensaje .= "<td colspan=2 align=right style=\"color:black;background-color: #90ee90;font-weight:bold;\">Entradas</td>";
					$mensaje .= "<td align=right style=\"color:black;background-color: #90ee90;font-weight:bold;\">Salidas</td>";
					$mensaje .= "<td align=right style=\"color:black;background-color: #90ee90;font-weight:bold;\">Balance (C)</td>";
					$mensaje .= "</tr>";							

					$con_aux = mysqli_query($con,"select name,money1,money2,money3 from collectdetails where state = 'A' and NOT name = 'Por Apuestas' and NOT name = 'Principal' order by id;");			
					$rows_aux = mysqli_num_rows($con_aux);
					
					$total_aux_3 = 0;
					$C_details = 0;
					while($row_aux = mysqli_fetch_array($con_aux)){						
						$aux_0 = $row_aux[0];
						$aux_1 = $row_aux[1];
						$aux_2 = $row_aux[2];
						$aux_3 = $row_aux[3];					
						$total_aux_3 += $aux_3;		
						$C_details = $total_aux_3;		
						
						$mensaje .= "<tr>";
						$mensaje .= "<td style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px\">".$aux_0."</td>";
						$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$aux_1."&euro;</td>";
						$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$aux_2."&euro;</td>";
						$mensaje .= "<td align=right style=\"color:#f0e68c;background-color: #4682b4;font-weight:bold;font-size:16px;\">".$aux_3."</td>";
						$mensaje .= "</tr>";	
					}						
				
					while($rows_aux < 10){						
				 		$rows_aux = ++$rows_aux;
						$mensaje .= "<tr>";
						$mensaje .= "<td>&nbsp;</td>";
						$mensaje .= "<td>&nbsp;</td>";									
						$mensaje .= "<td>&nbsp;</td>";
						$mensaje .= "<td>&nbsp;</td>";																		
						$mensaje .= "</tr>";	
				 	}

					$mensaje .= "<tr>";
					$mensaje .= "<td colspan=4>&nbsp;</td>";
					$mensaje .= "</tr>";
				
					$con_money3 = mysqli_query($con,"SELECT sum(Money3) FROM collectdetails WHERE State='A'");
					$row_money3 = mysqli_fetch_array($con_money3);
					$money3_0 = $row_money3[0];					
			
					$BC_details = ($B_details+$C_details);										
					$SIA_details = ($A_details-$BC_details);	

					$mensaje .= "<tr>";
					$mensaje .= "<td colspan=3 style=\"color:black;background-color: #8080ff;font-weight:bold;font-size:16px;\">Saldo Inicial Asignado = A-(B+C)</td>";
					$mensaje .= "<td align=right style=\"color:black;background-color: #8080ff;font-weight:bold;font-size:16px;\"><b>".$SIA_details."</b></td>";
					$mensaje .= "</tr>";												
					$mensaje .= "</tbody>";
					$mensaje .= "</table>";
					$mensaje .= "</div>";

					// Recargas auxiliares
					
					$mensaje .= '<div id="reca_aux" class="panel panel-default col-md-6 col-sm-12 paneles_form">
					 				<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #2e134a; color: #fff;">
										ÚLTIMAS RECARGAS AUXILIARES<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
									</div>
									<div class="panel-body">';

					$sql = mysqli_query($con,'select count(TypeIsAux) as total_auxiliares from auxmoneystorage');//where State="A"
					$total_aux = mysqli_fetch_array($sql);
					$total_aux_row = $total_aux[0];

					$sql = mysqli_query($con,'select TypeIsAux as id_auxiliar, AuxName as nombre_auxiliar from auxmoneystorage ORDER BY TypeIsAux ASC LIMIT '.$total_aux_row.''); // where State="A"
					$total_auxiliares = 0;
					while($aux_row = mysqli_fetch_array($sql)){
						$fecha_con_aux = mysqli_query($con,"select DateTime from logs where type = 'movementEmptyMoneyStorage".$aux_row['id_auxiliar']."' order by DateTime desc limit 1");
						$fecha_row_aux = mysqli_fetch_array($fecha_con_aux);
						if ($fecha_row_aux[0] == null || !isset($fecha_row_aux[0])){ 
							$fecha_row_aux[0] = '2014-01-01 00:00:00';
						}

						$recargas_con_aux = mysqli_query($con,"SELECT Text,User,DateTime from logs where Type = 'movementRefillMoneyStorage".$aux_row['id_auxiliar']."' AND DateTime > '".$fecha_row_aux[0]."' ORDER BY id ASC");
						$total_aux = 0;
						$i = 0;
						while($recargas_row_aux = mysqli_fetch_array($recargas_con_aux)){
							$recargas_row_textpos_aux = strpos($recargas_row_aux['Text'], "TOTAL:");
    						$recargas_row_text_aux = (double)str_replace(",", ".", substr($recargas_row_aux['Text'], $recargas_row_textpos_aux+6 ));
    						$total_aux += $recargas_row_text_aux;
    						$i++;
    					}

						$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #7338ae; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#auxiliar".$aux_row['id_auxiliar']."'>";
						$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>".$aux_row['nombre_auxiliar']." (AUX".$aux_row['id_auxiliar'].") (".$i.")</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>&nbsp;</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$total_aux."&euro;</b></div>";
						$mensaje .= "</div>";

						$mensaje .= "<div id='auxiliar".$aux_row['id_auxiliar']."' class='collapse'>";

						$recargas_con_aux = mysqli_query($con,"SELECT Text,User,DateTime from logs where Type = 'movementRefillMoneyStorage".$aux_row['id_auxiliar']."' AND DateTime > '".$fecha_row_aux[0]."' ORDER BY id ASC");
						while($recargas_row_aux = mysqli_fetch_array($recargas_con_aux)){
							$fecha_aux = $recargas_row_aux['DateTime'][8].$recargas_row_aux['DateTime'][9].$recargas_row_aux['DateTime'][7].$recargas_row_aux['DateTime'][5].$recargas_row_aux['DateTime'][6].$recargas_row_aux['DateTime'][4].$recargas_row_aux['DateTime'][0].$recargas_row_aux['DateTime'][1].$recargas_row_aux['DateTime'][2].$recargas_row_aux['DateTime'][3];
							$hora_aux = substr($recargas_row_aux['DateTime'], 10);
							$recargas_row_textpos_aux = strpos($recargas_row_aux['Text'], "TOTAL:");
    						$recargas_row_text_aux = (double)str_replace(",", ".", substr($recargas_row_aux['Text'], $recargas_row_textpos_aux+6 ));

							$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3b1b5e; padding: 2% 0'>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: left; padding-left: 1%'><b>".$fecha_aux." ".$hora_aux."</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recargas_row_aux['User']."</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$recargas_row_text_aux."&euro;</b></div></div>";
						}

						$mensaje .= "</div>";
						$total_auxiliares += $total_aux;
					}

					$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #2e134a; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
					$mensaje .= "<div style='width: 33%; float: left'><b>TOTAL</b></div>";
					$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>&nbsp;</b></div>";
					$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$total_auxiliares."&euro;</b></div>";
					$mensaje .= "</div>";

					$mensaje .= '</div>
								</div>';

					// MultiCoin								
					
					$mensaje .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
									<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #016e03; color: #fff;">
									DINERO ACTIVO<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
									</div>
									<div class="panel-body">';
												
					while($rowmulticointotal = mysqli_fetch_array($multicointotal)){
       						$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#multi'>";
							$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>MultiMoneda</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . $rowmulticointotal['cantidad'] . "</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($rowmulticointotal['total'], 2, ',', '.'). "&euro;</b></div>";
							$mensaje .= "</div>";
        			}
              
          			$mensaje .= "<div id='multi' class='collapse'>";
           
           			while($rowmulticoindetalle = mysqli_fetch_array($multicoindetalle)){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 2% 0'>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/" . str_replace(",", "", $rowmulticoindetalle['moneda']) . ".png\" height=\"35\"></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$rowmulticoindetalle['cantidad']."</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($rowmulticoindetalle['total'], 2, ',', '.')."&euro;</b></div>";
							$mensaje .= "</div>";
					}
					 
					$mensaje .= "</div>";
					 
					if($hopperfilas > 0){								
				
					 	while($rowhoppertotal = mysqli_fetch_array($hoppertotal)){												  	
							$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#hopper'>";
							$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Hopper</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . $rowhoppertotal['cantidad'] . "</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($rowhoppertotal['total'], 2, ',', '.'). "&euro;</b></div>";
							$mensaje .= "</div>";
         				}
         
    	         		$mensaje .= "<div id='hopper' class='collapse'>";
    	          	    	         
    	         		while($rowhopperdetalle = mysqli_fetch_array($hopperdetalle)){
    	         					$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 2% 0'>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/" . str_replace(",", "", $rowhopperdetalle['moneda']) . ".png\" height=\"35\"></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$rowhopperdetalle['cantidad']."</b></div>";
									$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($rowhopperdetalle['total'], 2, ',', '.')."&euro;</b></div>";
									$mensaje .= "</div>";
             			}
             			$mensaje .= "</div>";         
   					}
      
      				while($rowcassettetotal = mysqli_fetch_array($cassettetotal)){
       						$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #689868; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#cassette'>";
							$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Reciclador</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . $rowcassettetotal['cantidad'] . "</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($rowcassettetotal['total'], 2, ',', '.'). "&euro;</b></div>";
							$mensaje .= "</div>";
            		}
              
          			$mensaje .= "<div id='cassette' class='collapse'>";
           
           			while($rowcassettedetalle = mysqli_fetch_array($cassettedetalle)){
								$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #3a5439; padding: 2% 0'>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/" . str_replace(",", "", $rowcassettedetalle['moneda']) . ".png\" height=\"35\"> <span style='font-weight: bold;'>".$rowcassettedetalle['Cassette']."</span></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$rowcassettedetalle['cantidad']."</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($rowcassettedetalle['total'], 2, ',', '.')."&euro;</b></div>";
							$mensaje .= "</div>";
					}
					 
					$mensaje .= "</div>";
					 
					while($rowtotalactivo = mysqli_fetch_array($totalactivo)){
					 	if ($rowtotalactivo['totalactivo'] > 0){
           					$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #3a5439; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
							$mensaje .= "<div style='width: 33%; float: left'><b>Total</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . $rowtotalactivo['cantidad'] . "</b></div>";
							$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($rowtotalactivo['totalactivo'], 2, ',', '.'). "&euro;</b></div>";
							$mensaje .= "</div>";
						}
            		}
							              
					$mensaje .= "</div>
							</div>";

					// *** FIN DE ACTIVO ***

					// *** DINERO NO ACTIVO ***
					
					$mensaje .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
									<div class="panel-heading" style="text-align: center; cursor: pointer; height: 32px; padding: 8px; background: #fe0000; color: #fff;">
									DINERO NO ACTIVO<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
									</div>
									<div class="panel-body">';
												
					while($rowcashboxtotal = mysqli_fetch_array($cashboxtotal)){
   						$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #cd3e3e; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#cajon'>";
						$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Cajón</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . $rowcashboxtotal['cantidad'] . "</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($rowcashboxtotal['total'], 2, ',', '.'). "&euro;</b></div>";
						$mensaje .= "</div>";
          			}
              
          			$mensaje .= "<div id='cajon' class='collapse'>";
           
           			while($rowcashboxdetalle = mysqli_fetch_array($cashboxdetalle)){
							$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 2% 0'>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/" . str_replace(",", "", $rowcashboxdetalle['moneda']) . ".png\" height=\"35\"></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$rowcashboxdetalle['cantidad']."</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($rowcashboxdetalle['total'], 2, ',', '.')."&euro;</b></div>";
						$mensaje .= "</div>";
					}
					 
					$mensaje .= "</div>";

					while($rowstackertotal = mysqli_fetch_array($stackertotal)){
       					$mensaje .= "<div class='expand' style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #cd3e3e; cursor: pointer; padding: 3% 2%; border-bottom: 1px solid #fff;' data-toggle='collapse' data-target='#stacker'>";
						$mensaje .= "<div style='width: 33%; float: left'><b class='plus'>[+]</b><b class='minus' style='display: none'>[-]</b><b>Apilador</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . $rowstackertotal['cantidad'] . "</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($rowstackertotal['total'], 2, ',', '.'). "&euro;</b></div>";
						$mensaje .= "</div>";
          			}
              
          			$mensaje .= "<div id='stacker' class='collapse'>";
           
           			while($rowstackerdetalle = mysqli_fetch_array($stackerdetalle)){
						$mensaje .= "<div style='width: 100%; float: left; color: #fff; background: #630100; padding: 2% 0'>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: left;'><img style='margin: 1% 0;' src=\"https://atc.apuestasdemurcia.es/tickets/files/img/ruan/" . str_replace(",", "", $rowstackerdetalle['moneda']) . ".png\" height=\"35\"></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".$rowstackerdetalle['cantidad']."</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>".number_format($rowstackerdetalle['total'], 2, ',', '.')."&euro;</b></div>";
						$mensaje .= "</div>";
					}
					 
					$mensaje .= "</div>";
					 
					while($rowtotalnoactivo = mysqli_fetch_array($totalnoactivo)){
					 	if ($rowtotalnoactivo['totalnoactivo'] < $limitenoactivo) { $tecnicolor="#630100"; $tecnico="ok"; $tecnicolor="#630100";$tecnicolorclass="verde";} else {$tecnicolor="#630100"; $tecnico="ko"; $tecnicolor="#630100";$tecnicolorclass="rojo";}
   						$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: ".$tecnicolor."; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
						$mensaje .= "<div style='width: 33%; float: left'><b>Total</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" . $rowtotalnoactivo['cantidad'] . "</b></div>";
						$mensaje .= "<div style='width: 33%; float: left; text-align: right;'><b>" .number_format($rowtotalnoactivo['totalnoactivo'], 2, ',', '.'). "&euro;</b></div>";
						$mensaje .= "</div>";
            		}
							              
					$mensaje .= "</div>
								</div>";

					$arqueototalnoactivo=0;
					$totalrefills = 0;
					$balance = 0;

					while($rowrefills = mysqli_fetch_array($refills))
					{
						$refillstextpos = strpos($rowrefills['Text'], "TOTAL:");
						$refillstext = (double)str_replace(",", ".", substr($rowrefills['Text'], $refillstextpos+6 ));
						$totalrefills += $refillstext;
						$totalsumrefills +=$refillstext;
					}

					// INICIO ARQUEO					
					if((isset($arqueo)) || (is_int($arqueo)) || ($arqueo==1)){
						
						$mensaje .= '<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
										<div class="panel-heading" style="display: none; cursor: pointer; height: 32px; padding: 8px; background: #016e03; color: #fff;">
										Total<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
										</div>
										<div class="panel-body">';
	
						while($rowarqueo = mysqli_fetch_array($arqueo)){
							if ($rowarqueo['arqueo'] >= $limitearqueo[$salonescont]) { 
								$colorarqueo = "green";
							}else{
								$colorarqueo = "red";
							}									
							$arqueoreal = $rowarqueo['arqueo'];
       						$mensaje .= "<div style='float: left; width: 100%; margin: 0; padding: 1%; color: #fff; background: #12abef; padding: 3% 2%; border-bottom: 1px solid #fff;'>";
							$mensaje .= "<div style='width: 33%; float: left'><b>Total</b></div>";
							$mensaje .= "<div style='width: 66%; float: left; text-align: right;'><b>" .number_format($arqueoreal, 2, ',', '.'). "&euro;</b></div>";
							$mensaje .= "</div>";										
							$arqueoreal = 0;								
						}
						
						$mensaje .= "</div>
									</div>";
											
					} // FIN IF ARQUEO == 1
			// FIN COMPROBACION ERROR 1
			}else{
				$mensaje .= "					<table class=\"rojo\" width=\"230\"> <!-- Total NO Activo -->";
				$mensaje .= "						<tbody>";
				$mensaje .= "							<tr>";
				$mensaje .= "								<td valign=\"top\"> <!-- Total NO Activo -->";
				$mensaje .= "									<table>";
				$mensaje .= "										<tbody>";
				$mensaje .= "<tr>";
				$mensaje .= "<td align=\"center\" width=\"230\" style=\"color:white;background-color: #cd3e3e;\"><b>ROUTER ENCENDIDO<BR>PUERTO ABIERTO<BR>PERO NO HAY CONEXIÓN AL CAJERO</b></td> <!-- NO HAY CONEXION -->";
				$mensaje .= "</tr>";
				$mensaje .= "											</tbody>";
				$mensaje .= "									</table>";
				$mensaje .= "								</td>";
				$mensaje .= "							</tr>";
				$mensaje .= "						</tbody>";
				$mensaje .= "					</table>";
			}// COMPROBACION ERROR 1

			$error = 0;
			// CIERRE TD HTML Por cada salon
			$mensaje .= "	<!-- CIERRE TD HTML POR CADA SALON -->";
			$mensaje .= "	</td>";
			// CIERRE TD HTML Por cada salon

			// Sumamos contador para cantidad de salones
			$salonescont++;
		}
		return $mensaje;
  	}
}

?>
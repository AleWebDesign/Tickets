<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipster {

	function nueva_incidencia($id,$ticket,$titulo,$body){
		
		// Datos db
		$usuario = "";
		$clave = "";
		$database = "";
	    $port = ;
	  
	    try{	
		  $conn = new PDO('mysql:host=atc.apuestasdemurcia.es;dbname=tipster; charset=utf8', $usuario, $clave);
		  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		  echo "ERROR: " . $e->getMessage();
		}
		
		$usuario2='';
		$clave2='';

		try{			
		  $conn2 = new PDO('mysql:host=atc.apuestasdemurcia.es;dbname=averias; charset=utf8', $usuario2, $clave2);
		  $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		  echo "ERROR: " . $e->getMessage();
		}
		
		$fecha = date("Y-m-d H:i:00");

		$sql = $conn->prepare('INSERT INTO notificaciones (competiciones_id, ticket, titulo, body, fecha, fecha_envio, administradores_id, estado, tipster) VALUES (:competiciones_id, :ticket, :titulo, :body, :fecha, :fecha_envio, :admnistradores_id, :estado, :tipster)');
		$sql->execute(array(':competiciones_id' => 1, ':ticket' => $id, ':titulo' => $titulo, ':body' => $body, ':fecha' => $fecha, ':fecha_envio' => $fecha, ':admnistradores_id' => 2, ':estado' => 1, ':tipster' => 0));
		
		$sql = $conn->prepare('SELECT * FROM notificaciones ORDER BY id DESC LIMIT 1');
		$sql->execute();
		$noti = $sql->fetch();
		
		// INSERTAR NOTIFICACIONES EN BBDD
		$operadoras = array(43,57,4,71,99,113,134,176,230,231,240,232);
		foreach ($operadoras as $operadora){
			if($operadora == $ticket->destino){
				if($ticket->destino == 4){
					$sql = $conn2->prepare('SELECT * FROM usuarios WHERE acceso = 24 AND (rol = 2 OR rol = 4) AND activo = 1');
					//$sql = $conn2->prepare('SELECT * FROM usuarios WHERE id = 40');
				}else{
					$sql = $conn2->prepare('SELECT * FROM usuarios WHERE acceso = '.$ticket->operadora.' AND (rol = 2 OR rol = 4) AND activo = 1');
				}
				$sql->execute();
				$usuarios = $sql->fetchAll();
				foreach($usuarios as $usuario){
					if($usuario['notificaciones'] == 1){
						if(isset($usuario['hora_inicio']) && $usuario['hora_inicio'] != '' && isset($usuario['hora_fin']) && $usuario['hora_fin'] != ''){
							if($usuario['hora_inicio'] < date("H:i") && date("H:i") < $usuario['hora_fin']){
								$sql = $conn->prepare('SELECT * FROM usuarios WHERE email LIKE "%'.$usuario["email"].'%" LIMIT 1');
								$sql->execute();
								if($sql->rowCount() > 0){
									$user = $sql->fetch();
									$sql = $conn->prepare('INSERT INTO notificaciones_usuarios (notificaciones_id, usuarios_id, fecha, estado) VALUES (:notificaciones_id, :usuarios_id, :fecha, :estado)');
									$sql->execute(array(':notificaciones_id' => $noti['id'], ':usuarios_id' => $user['id'], ':fecha' => $noti['fecha_envio'], ':estado' => 1));
								}
							}
						}else{
							$sql = $conn->prepare('SELECT * FROM usuarios WHERE email LIKE "%'.$usuario["email"].'%" LIMIT 1');
							$sql->execute();
							if($sql->rowCount() > 0){
								$user = $sql->fetch();
								$sql = $conn->prepare('INSERT INTO notificaciones_usuarios (notificaciones_id, usuarios_id, fecha, estado) VALUES (:notificaciones_id, :usuarios_id, :fecha, :estado)');
								$sql->execute(array(':notificaciones_id' => $noti['id'], ':usuarios_id' => $user['id'], ':fecha' => $noti['fecha_envio'], ':estado' => 1));
							}
						}
					}
				}
			}
		}
		
		// ENVIAR NOTIFICACIONES PENDIENTES A APP
	    
	    $sql = $conn->prepare("SELECT * FROM notificaciones_usuarios WHERE estado = 1");
	    $sql->execute();
	    $notificaciones_usuarios = $sql->fetchAll();
	    
	    $noti_tokens = [];
	    if(!empty($notificaciones_usuarios)){
	      	foreach ($notificaciones_usuarios AS $notificacion_usuario) {
		      	$sql = $conn->prepare("SELECT DISTINCT token FROM usuarios_token_app WHERE usuarios_id = ".$notificacion_usuario['usuarios_id']." AND token IS NOT NULL");
		      	$sql->execute();
		      	$tokens = $sql->fetchAll();
		      	foreach($tokens AS $token){
		      		$noti_tokens[] = $token['token'];
		      	}
		      	$sql = $conn->prepare("UPDATE notificaciones_usuarios SET estado = 2 WHERE id = ".$notificacion_usuario['id']."");
		      	$sql->execute();
		      	
		      	$fields = [
			        "registration_ids" => $noti_tokens,
			        "data" => [
			        	"ticket" => $id,
			            "title" => $titulo,
			            "body" => $body
			        ]
			    ];

			    $fields2 = [
			        "registration_ids" => $noti_tokens,
			        "notification" => [
		                "ticket" => $id,
		                "title" => $titulo,
		                "body" => $body,
		                "sound" => "notificacion.caf",
		                "badge" => 1
		            ]
			    ];
		    }

		    $token_firebase = '';
		    $url = 'https://fcm.googleapis.com/fcm/send';
		    $headers = array(
		        'Authorization: key='.$token_firebase,
		        'Content-Type: application/json'
		    );
			
			$ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		    $result = curl_exec($ch);
		    curl_close($ch);

		    $token_firebase = '';
	    	$url = 'https://fcm.googleapis.com/fcm/send';
	        $headers = array(
	            'Authorization: key='.$token_firebase,
	            'Content-Type: application/json'
	        );

	        $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields2));
		    $result = curl_exec($ch);
		    curl_close($ch);
	    }    
  	}
}
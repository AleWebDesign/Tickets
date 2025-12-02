<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');

class Tickets extends CI_Controller {

	public function firma_canvas(){
		$data = array('title' => '');
		$this->load_view('firma_canvas', $data);
	}

	public function replace($url){
		$replace = str_replace("%20", " ", $url);
		$replace = str_replace("%3E", ">", $replace);
		$replace = str_replace("%3C", "<", $replace);
		echo $replace;
	}
	
	public function cifrar($id){
		$query = "SELECT * FROM usuarios WHERE id = ".$id."";
		$users = $this->db->query($query);
		foreach($users->result() as $user){
			$pass = $user->pass;
			$hash = password_hash($pass, PASSWORD_DEFAULT);
			$update = "UPDATE usuarios SET pass = '".$hash."' WHERE id = ".$user->id."";
			$this->db->query($update);
		}
	}
	
	public function comparar_fechas($inicio,$fin,$visita){
		$inicio = explode("/", $inicio);
		$fin = explode("/", $fin);
		 		
		$fecha_inicio = strtotime($inicio[2]."-".$inicio[1]."-".$inicio[0]);
	    $fecha_fin = strtotime($fin[2]."-".$fin[1]."-".$fin[0]);
	    $fecha = strtotime($visita);

	    if(($fecha_inicio <= $fecha) && ($fecha <= $fecha_fin)){
	        return true;
	    }else{
	        return false;
	    }
	}
	
	public function codigos_barras(){
		$data = array('title' => '');
		$this->load_view('barcodereader', $data);
	}
	
	public function leer_imagen(){
		$data = array('title' => '');
		$this->load_view('leer_imagen', $data);
	}

	public function utf8_decode($cadena){
		$cadena = str_replace("&amp;amp;Aacute;", "Á", $cadena);
		$cadena = str_replace("&amp;amp;Eacute;", "É", $cadena);
		$cadena = str_replace("&amp;amp;Iacute;", "Í", $cadena);
		$cadena = str_replace("&amp;amp;Oacute;", "Ó", $cadena);
		$cadena = str_replace("&amp;amp;Uacute;", "Ú", $cadena);
		$cadena = str_replace("&amp;amp;aacute;", "á", $cadena);
		$cadena = str_replace("&amp;amp;eacute;", "é", $cadena);
		$cadena = str_replace("&amp;amp;iacute;", "í", $cadena);
		$cadena = str_replace("&amp;amp;oacute;", "ó", $cadena);
		$cadena = str_replace("&amp;amp;uacute;", "ú", $cadena);
		$cadena = str_replace("&amp;amp;ntilde;", "ñ", $cadena);
		$cadena = str_replace("&amp;amp;Ntilde;", "Ñ", $cadena);
		$cadena = str_replace("&amp;amp;nbsp;", " ", $cadena);
		return $cadena;
	} 
		
	public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	
	/* Gestor vistas - Header/Footer */
	private function load_view($view, $data){
		/* Incidencias pendientes */
		$total_incidencias_pendientes = 0;
		$i = 0;
		if($this->session->userdata('logged_in')['id'] == 571 || $this->session->userdata('logged_in')['id'] == 351){
			$incidencias_pendientes = $this->post->get_tickets_inf();
		}else if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24){
			$incidencias_pendientes = $this->post->get_tickets_sat();
		}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7){
			$incidencias_pendientes = $this->post->get_tickets();
		}else if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 5){
			$incidencias_pendientes = $this->post->get_tickets_op($this->session->userdata('logged_in')['acceso']);
		}else if($this->session->userdata('logged_in')['rol'] == 3){
			$incidencias_pendientes = $this->post->get_tickets_salon($this->session->userdata('logged_in')['acceso']);
		}else if($this->session->userdata('logged_in')['rol'] == 6){
			$incidencias_pendientes = $this->post->get_tickets_com();
		}else if($this->session->userdata('logged_in')['rol'] == 8){
			$incidencias_pendientes = $this->post->get_tickets_mkt();
		}else if($this->session->userdata('logged_in')['rol'] == 9){
			$incidencias_pendientes = $this->post->get_tickets_onl();
		}else{
			$incidencias_pendientes = $this->post->get_tickets();
		}

		$usuarios_adm = $this->post->get_usuarios_adm();
		$array_id_incidencias = array();		
		foreach($incidencias_pendientes->result() as $incidencia_pendientes){
			/* Incidencias CIRSA */
			if($this->session->userdata('logged_in')['rol'] != 1 || $this->session->userdata('logged_in')['rol'] != 2){
				if($incidencia_pendientes->tipo_averia == 11){
					continue;
				}
			}

			/* Pendiente Kirol */
			if($this->session->userdata('logged_in')['rol'] != 1){
				if($incidencia_pendientes->situacion == 8){
					continue;
				}
			}
					
			/* Comprobra gestion ATC operadoras activo y creador ADM */
			if($this->session->userdata('logged_in')['acceso'] == 24 && $this->session->userdata('logged_in')['rol'] != 3){
				if(in_array($incidencia_pendientes->creador, $usuarios_adm)){

				}else{
					if($incidencia_pendientes->situacion == 2 && $incidencia_pendientes->destino == 4){
						
					}else{
						continue;
					}
				}
				
				if($incidencia_pendientes->tipo_averia != '6' && $incidencia_pendientes->tipo_averia != '3'){
					$gestion_activa = $this->post->get_gestion_activa($incidencia_pendientes->empresa);
					if($gestion_activa->tipo_gestion == 0){
						continue;
					}
				}
			}

			/* Evitar duplicados */
			if (in_array($incidencia_pendientes->id, $array_id_incidencias)) {
			    continue;
			}
			array_push($array_id_incidencias, $incidencia_pendientes->id);

			$i++;
		}
		$total_incidencias_pendientes = $i;
		
		$data['total_incidencias_pendientes'] = $total_incidencias_pendientes;

		/* Estado jornada automatica */
		$jornada = $this->post->get_creador_completo($this->session->userdata('logged_in')['id']);
		$data['jornada_auto'] = $jornada->jornada;
		
		$this->load->view('header', $data);
    	$this->load->view($view, $data);
	}
	
	// Cerrar sesión
	public function cerrar_sesion(){
		$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Cerrar sesión');
  		$this->session->unset_userdata('logged_in');
    	session_destroy();
    	$data = array('title' => '');
    	redirect('login', 'refresh');
  	}
  
  	public function enviar_email_recaudacion($id){
  		$recaudar = $this->post->get_recaudacion_id($id);
  		$salon = $this->post->get_salon_completo($recaudar->salon);

  		$fecha = explode("-", $recaudar->fecha);
  	
  		$message = "";  	
  		$message = '<p>Recaudacion registrada en salón '.$salon->salon.'</p>
					<p>Recaudación total: '.$recaudar->reca_total.' €</p>
					<p>PAGOS: '.$recaudar->pagos.' € (tpv o cajero)</p>
					<p>REC. NETA: '.$recaudar->neto.' € (Salida efectivo a central)</p>
					<p>A '.$fecha[2].' del '.$fecha[1].' de '.$fecha[0].'</p>';
		
		$this->load->library('email');
		$config['useragent'] = 'EMAIL RECAUDACIONES APP tickets';
        $this->email->initialize($config);
	  	$this->email->from('mail@domain', 'RECAUDACIONES');
	  	$this->email->to("administracion@domain");
	  	$this->email->subject("Recaudaciones");
	  	$this->email->message($message);
	  	$this->email->attach(APPPATH.'../tickets/files/pdf_recaudaciones/'.$id.'.pdf');
	  	$this->email->send();
  	}

  	public function enviar_email_recaudacion_salon($id){
  		$recaudar = $this->post->get_recaudacion_salon_id($id);
  		$salon = $this->post->get_salon_completo($recaudar->salon);

  		$fecha = explode("-", $recaudar->fecha);
  	
  		$message = "";	
  		$message = '<p>Recaudacion registrada en salón '.$salon->salon.'</p>
  					<p>Recaudación total: '.$recaudar->bruto.' €</p>
  					<p>PAGOS: '.$recaudar->pagos.' € (tpv o cajero)</p>
  					<p>DATÁFONO: '.$recaudar->datafono.' € </p>
					<p>REC. NETA: '.$recaudar->neto.' € (Salida efectivo a central)</p>
					<p>A '.$fecha[2].' del '.$fecha[1].' de '.$fecha[0].'</p>';
		
		$this->load->library('email');
		$config['useragent'] = 'EMAIL RECAUDACIONES APP tickets';
        $this->email->initialize($config);
	  	$this->email->from('mail@domain', 'RECAUDACIONES');
	  	$this->email->to("administracion@domain");
	  	$this->email->subject("Recaudaciones salones ADM");
	  	$this->email->message($message);
	  	$this->email->attach(APPPATH.'../tickets/files/pdf_recaudaciones_salones/recaudacion_salon_adm_'.$id.'.pdf');
	  	$this->email->send();
  	}

  	public function enviar_email_registro_local($registro){
  		$salon = $this->post->get_salon_completo($registro->local);
  		$movimiento = $this->post->get_movimiento($registro->movimiento);
  		$maquina = $this->post->get_maquina_completo($registro->maquina);
  		$creador = $this->post->get_creador($registro->usuario);
  		$departamento = $this->post->get_destino_adm($this->session->userdata('logged_in')['acceso']);

  		$fecha1 = explode(" ", $registro->fecha);
  		$fecha2 = explode("-", $fecha1[0]);
  		$fecha = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];

  		$message = "";	
  		$message = '<p>Se ha registrado un movimiento en el salón '.$salon->salon.':</p>
  					<p>Movimiento: '.$movimiento->movimiento.'</p>
  					<p>Usuario: '.$creador.'</p>
  					<p>Máquina: '.$maquina->maquina.'</p>
  					<p>Importe: '.$registro->importe.'€</p>
  					<p>Fecha: '.$fecha.'</p>
  					<p>Para más información, visite la sección registros de la <a href="https://domain/">APP</a></p>';
		
		$this->load->library('email');
		$config['useragent'] = 'EMAIL REGISTRO MOVIMIENTO SALON';
        $this->email->initialize($config);
	  	$this->email->from('mail@domain', 'REGISTRO MOVIMIENTOS');
	  	$this->email->to($departamento->email);
	  	$this->email->subject("Registro movimiento salón");
	  	$this->email->message($message);
	  	$this->email->send();
  	}

  	public function enviar_email_informe_salon_operadora($id,$pdf){
  		$informe = $this->post->get_informe_operadora_salon($id);
  		$salon = $this->post->get_salon_completo($informe->salon);
  		$creador = $this->post->get_creador_completo($informe->creador);

  		if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
  			$departamento = $this->post->get_destino_adm($this->session->userdata('logged_in')['acceso']);
  		}else{
  			$departamento = $this->post->get_destino_adm_salon($this->session->userdata('logged_in')['acceso']);
  		}

  		$message = "";	
  		$message = '<p>Nuevo informe local '.$salon->salon.' registrado por '.$creador->nombre.'.</p>';
		
		$this->load->library('email');
		$config['useragent'] = 'EMAIL INFORME SALON';
        $this->email->initialize($config);
	  	$this->email->from('mail@domain', 'INFORME SALON');	  	
	  	if(!empty($creador->email) && $creador->email != ''){
	  		$this->email->to($departamento->email, $creador->email);
	  	}else{
	  		$this->email->to($departamento->email);
	  	}
	  	$this->email->subject("Informe salón");
	  	$this->email->message($message);
	  	$this->email->attach(APPPATH.'../tickets/files/pdf_operadoras/'.$pdf.'');
	  	$this->email->send();
  	}
  
  	public function enviarmail($id){
	  	$ticket = $this->post->get_ticket($id);
	  	$salon = $this->post->get_salon_completo($ticket->salon);
	  	$maquina = $this->post->get_maquina_completo($ticket->maquina);
	  	$tipo_averia = $this->post->get_averia($ticket->tipo_averia);
	  	$tipo_error = $this->post->get_tipo_error_completo($ticket->tipo_error);
		$detalle_error = $this->post->get_detalle_error_completo($ticket->detalle_error);
		$creador = $this->post->get_creador_completo($ticket->creador);
		$departamento = $this->post->get_destino_completo($ticket->destino);
		$html_historial = '';
		if($ticket->maquina != 0){
			$historial = $this->post->get_historial_maquina($id,$ticket->maquina);
			if($historial->num_rows() > 0){
				$html_historial .= '<br><br>';
				$html_historial .= '<strong>Historial incidencia</strong><br><br>';
				foreach($historial->result() as $historia){
					if($historia->id == $id){
						continue;
					}else{
						$tipo = $this->post->get_averia($historia->tipo_averia);
				  	$error = $this->post->get_tipo_error_completo($historia->tipo_error);
						$detalle = $this->post->get_detalle_error_completo($historia->detalle_error);
						$fecha = explode('-', $historia->fecha_creacion);
						$fecha_creacion = $fecha[2]."/".$fecha[1]."/".$fecha[0];
						$html_historial .= '<strong>#'.$historia->id.' '.$fecha_creacion.' '.$historia->hora_creacion.'</strong> '.$tipo->gestion." ".$error->tipo." ".$detalle->error_detalle."<br><br>";
					}
				}
			}
		}
		$html_cliente = '';
		if($ticket->cliente == 5 && $ticket->nombre != '' && $ticket->nombre != '0' && !is_null($ticket->nombre) && !empty($ticket->nombre)){
			$clientes_web = $this->post->get_historial_cliente_web($id,$ticket->nombre);
			if($clientes_web->num_rows() > 0){
				$html_cliente .= '<br><br>';
				$html_cliente .= '<strong>Historial Cliente Web</strong><br><br>';
				foreach($clientes_web->result() as $cliente_web){
					if($cliente_web->id == $id){
						continue;
					}else{
						$tipo = $this->post->get_averia($cliente_web->tipo_averia);
				  		$error = $this->post->get_tipo_error_completo($cliente_web->tipo_error);
						$detalle = $this->post->get_detalle_error_completo($cliente_web->detalle_error);
						$fecha = explode('-', $cliente_web->fecha_creacion);
						$fecha_creacion = $fecha[2]."/".$fecha[1]."/".$fecha[0];
						$html_cliente .= '<strong>#'.$cliente_web->id.' '.$fecha_creacion.' '.$cliente_web->hora_creacion.'</strong> '.$tipo->gestion." ".$error->tipo." ".$detalle->error_detalle."<br><br>";
					}
				}
			}
		}
		$error_desc = stripslashes($ticket->error_desc);
		$to = $departamento->email;
		if($ticket->destino == 31){
			$subject = "[MKT]";
		}else if($ticket->destino == 32){
			$subject = "[INF]";
		}else{
			$subject = "[SAT]";
		}
		$subject .= " #".$ticket->id." - ".$salon->salon." - ".$salon->poblacion."";
		$message = "";
		
		$message .= "<table style=\"font-family: Verdana, Geneva, sans-serif;  border: 2px solid #000000;  background-color: #5C5C5C;  width: 100%;  height: 100px;  text-align: center;  border-collapse: collapse;\">";
		$message .= "<tbody style=\"font-size: 13px;  color: #D4D4D4;\">";
		
		if($ticket->maquina != 0){
			
			$message .= "   <tr>";
			$message .= "           <td style=\"font-size: 13px;  color: #D4D4D4; text-align: right; vertical-align: top; width: 110px;\">";
			$message .= "                   Máquina :&nbsp;";
			$message .= "           </td>";			
			$message .= "           <td style=\"background: #FFFFFF; color:#000000;  text-align: left;\">";
			$message .= "                   ".$maquina->maquina."";
			$message .= "           </td>";
			$message .= "   </tr>";
			
		}
		$message .= "   <tr><td>&nbsp;</td><td style=\"background:#FFFFFF\">&nbsp;</td></tr>";
		$message .= "   <tr>";
		$message .= "           <td style=\"font-size: 13px;  color: #D4D4D4; text-align: right; vertical-align: top; width: 110px;\">";
		$message .= "                   Incidencia :&nbsp;";
		$message .= "           </td>";
		$message .= "           <td style=\"background: #FFFFFF; color:#000000;  text-align: left;\">";
		$message .= "                   ".$tipo_averia->gestion." ".$tipo_error->tipo." ".$detalle_error->error_detalle."";
		$message .= "           </td>";
		$message .= "   </tr>";
		$message .= "   <tr><td>&nbsp;</td><td style=\"background:#FFFFFF\">&nbsp;</td></tr>";
		$message .= "   <tr>";
		$message .= "           <td style=\"font-size: 13px;  color: #D4D4D4; text-align: right; vertical-align: top; width: 110px;\">";
		$message .= "                   Detalle :&nbsp;";
		$message .= "           </td>";
		$message .= "           <td style=\"background: #FFFFFF; color:#000000;  text-align: left;\">";
		$message .= "                   ".nl2br($error_desc)."";
		$message .= "           </td>";
		$message .= "   </tr>";
		$message .= "   <tr><td>&nbsp;</td><td style=\"background:#FFFFFF\">&nbsp;</td></tr>";
		$message .= "   <tr>";
		$message .= "           <td style=\"font-size: 13px;  color: #D4D4D4; text-align: right; vertical-align: top; width: 110px;\">";
		$message .= "                   Tratamiento :&nbsp;";
		$message .= "           </td>";
		$message .= "           <td style=\"background: #FFFFFF; color:#000000;  text-align: left;\">";
		$message .= "                   Pendiente SAT";
		$message .= $html_historial;
		$message .= $html_cliente; 
		$message .= "           </td>";
		$message .= "   </tr>";
		$message .= "   <tr><td>&nbsp;</td><td style=\"background:#FFFFFF\">&nbsp;</td></tr>";
		$message .= " <tr>";
		$message .= " <td style=\"font-size: 13px; color: #D4D4D4; text-align: right; vertical-align: top; width: 110px;\">";
		$message .= " Creada por:";
		$message .= " </td>";
		$message .= " <td style=\"background: #FFFFFF; color:#000000; text-align: left;\">";
		$message .= $creador->nombre;
		$message .= " </td>";
		$message .= " </tr>";
		$message .= " <tr><td>&nbsp;</td><td style=\"background:#FFFFFF\">&nbsp;</td></tr>";
		$message .= " <tr>";
		if($ticket->situacion == 6){
			if($ticket->soluciona != 0){
				$usuario = $this->post->get_creador_completo($ticket->soluciona);
				$soluciona = $usuario->nombre;
			}else{
				$soluciona = Creador;
			}
			$message .= " <td style=\"font-size: 13px; color: #D4D4D4; text-align: right; vertical-align: top; width: 110px;\">";
			$message .= " Solucionada :&nbsp;";
			$message .= " </td>";
			$message .= " <td style=\"background: #FFFFFF; color:#000000; text-align: left;\">";
			$message .= $soluciona;
			$message .= " </td>";			
		}else if($ticket->asignado != 0){
			$usuario = $this->post->get_creador_completo($ticket->asignado);
			$message .= " <td style=\"font-size: 13px; color: #D4D4D4; text-align: right; vertical-align: top; width: 110px;\">";
			$message .= " Asignado a :&nbsp;";
			$message .= " </td>";
			$message .= " <td style=\"background: #FFFFFF; color:#000000; text-align: left;\">";
			$message .= $usuario->nombre;
			$message .= " </td>";
		}else{
			$message .= " <td style=\"font-size: 13px; color: #D4D4D4; text-align: right; vertical-align: top; width: 110px;\">";
			$message .= " Asignar :&nbsp;";
			$message .= " </td>";
			$message .= " <td style=\"background: #FFFFFF; color:#000000; text-align: left;\">";
			$message .= " <a href='".base_url('asignar_ticket/'.$ticket->id.'')."'>Asignar incidencia</a>";
			$message .= " </td>";
		}
		$message .= " </tr>";
		$message .= "</tbody>";
		$message .= "</table>";
		
		$this->load->library('email');
		$config['useragent'] = 'EMAIL INCIDENCIAS APP tickets';
		$this->email->initialize($config);
		$this->email->from('mail@domain', 'AVERIAS');
		if($ticket->tipo_averia == 11){
			$mail_destino = ''.$departamento->email.', cirsa@apostium.es';
			$this->email->to($mail_destino);
		}else{
			$this->email->to($departamento->email);
		}		
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	  
	}
	
	public function enviarmail_pass($user){
		
		$usuario = $this->post->get_creador_completo($user);

		$message = "";
		$message .= "Saludos ".$usuario->nombre;
		$message .= "<p>Ha solicitado recuperar su contraseña. Para generar su nuevo acceso por favor haga click en el siguiente enlace:</p>";
		$message .= "<p><a href='".base_url('generar_nueva_contrasena/'.$user.'')."'>Recuperar contraseña</a></p>";
		$message .= "Si no ha solicitado un cambio de contraseña, ignore este mensaje y si lo desea comuníquelo a soporte";
		
		$this->load->library('email');
		$config['useragent'] = 'EMAIL RECUPERAR CONTRASEÑA APP tickets';
        $this->email->initialize($config);
	  	$this->email->from('root@mail.es', '');
	  	$this->email->to($usuario->email);
	  	$this->email->subject('Recuperar contraseña');
	  	$this->email->message($message);
	  	$this->email->send();

	}

	public function generar_nueva_contrasena($user){

		$pass = $this->generateRandomString(8);
		$usuario = $this->post->get_creador_completo($user);

		$message = "";
		$message .= "Saludos ".$usuario->nombre;
		$message .= '<p>Su contraseña: '.$pass.'. Una vez inicie sesión, podrá modificarla desde su cuenta de usuario.</p>';

		$this->load->library('email');
		$config['useragent'] = 'EMAIL RECUPERAR CONTRASEÑA APP tickets';
        $this->email->initialize($config);
	  	$this->email->from('mail@domain', '');
	  	$this->email->to($usuario->email);
	  	$this->email->subject('Recuperar contraseña');
	  	$this->email->message($message);
	  	$this->email->send();

	  	$actualizar = $this->post->actualizar_usuario2($usuario->id,$usuario->nombre,$usuario->email,$pass,$usuario->notificaciones);

	}
	
	function http_request($uri, $time_out = 10, $headers = 0){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, trim($uri));
	    curl_setopt($ch, CURLOPT_HEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $result;
	}
	
	public function crear_notificacion_tipster_nueva_incidencia($id){
		$ticket = $this->post->get_ticket($id);
		
		$salon = $this->post->get_salon_completo($ticket->salon);
		
		$titulo = "";
		$titulo .= "#".$ticket->id." ".$salon->salon." ".$salon->poblacion."";
		
		$tipo_averia = $this->post->get_averia($ticket->tipo_averia);
  		$tipo_error = $this->post->get_tipo_error_completo($ticket->tipo_error);
		$detalle_error = $this->post->get_detalle_error_completo($ticket->detalle_error);
		
		$body = "";
		if($ticket->maquina != 0){
			$maquina = $this->post->get_maquina_completo($ticket->maquina);
			$body .= $maquina->maquina." ";
		}
		$body .= $tipo_averia->gestion." ".$tipo_error->tipo." ".$detalle_error->error_detalle;
		
		$this->load->library('tipster');
		$this->tipster->nueva_incidencia($id,$ticket,$titulo,$body);
	}

	public function pruebas_telegram(){
		$Token='1684937498:AAHbqAqAwYIljLiVE8d0Dk7PiHjtb-RcYug';
		$chat_id='-4932272884';
		$API="https://api.telegram.org/bot".$Token;
		$url=$API."/sendMessage?chat_id=".$chat_id;
		
		$body = "";		
		$body .= "Prueba" . "\n";

		if($chat_id != ''){
			$data = [
			    'text' => $body,
			    'chat_id' => $chat_id,
			    'parse_mode' => 'markdown'
			];
			$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?" . http_build_query($data) );
			$result = json_decode($ejecutar,true);
			var_dump($result);			
		}
	}
	
	public function telegram($id){
		
		$ticket = $this->post->get_ticket($id);
		$op = $this->post->get_operadoras_rol_2($ticket->operadora);
		$op2 = $op->row();
  		$salon = $this->post->get_salon_completo($ticket->salon);
  		$maquina = $this->post->get_maquina_completo($ticket->maquina);
  		$modelo = $this->post->get_modelo($maquina->modelo);
  		$tipo_averia = $this->post->get_averia($ticket->tipo_averia);
  		$tipo_error = $this->post->get_tipo_error_completo($ticket->tipo_error);
		$detalle_error = $this->post->get_detalle_error_completo($ticket->detalle_error);
		$usuario = $this->post->get_creador_completo($ticket->creador);
		$usuarios_adm = $this->post->get_usuarios_adm();
		
		$html_historial = '';
		if($ticket->maquina != 0){
			$historial = $this->post->get_historial_maquina_telegram($id,$ticket->maquina);
			if($historial->num_rows() > 0){
				$html_historial .= '*Historial incidencia*';
				$html_historial .= "\n";
				foreach($historial->result() as $historia){
					if($historia->id == $id){
						continue;
					}else{
						$tipo = $this->post->get_averia($historia->tipo_averia);
				  		$error = $this->post->get_tipo_error_completo($historia->tipo_error);
						$detalle = $this->post->get_detalle_error_completo($historia->detalle_error);
						$fecha = explode('-', $historia->fecha_creacion);
						$fecha_creacion = $fecha[2]."/".$fecha[1]."/".$fecha[0];
						$html_historial .= '*'.'#'.$historia->id.' '.$fecha_creacion.' '.$historia->hora_creacion.'* '.$tipo->gestion." ".$error->tipo." ".$detalle->error_detalle."\n";
					}
				}
			}
		}
		
		if($ticket->tipo_error == 62 || $ticket->tipo_error == 77 || $ticket->tipo_error == 113){
			$ticket_manual = $this->post->get_ticket_manual($id);
		}
		
		$Token='1684937498:AAHbqAqAwYIljLiVE8d0Dk7PiHjtb-RcYug';
		$chat_id=$op2->chat_telegram_id;
		$API="https://api.telegram.org/bot".$Token;
		$url=$API."/sendMessage?chat_id=".$chat_id;
		
		$sat_op = "";
		if(in_array($this->session->userdata('logged_in')['id'], $usuarios_adm)){
			if($ticket->destino == 32 || $ticket->situacion == 14){
				$chat_id="-1001422012811";
			}else if($ticket->situacion == 3){
				$chat_id="-2045930";
			}else if($ticket->destino == 2 || $ticket->situacion == 12){
				$chat_id="-4577120300";
			}else if($ticket->destino == 31 || $ticket->situacion == 19){
				$chat_id="-971495944";
			}else if($ticket->destino == 261 || $ticket->situacion == 21){
				$chat_id="-4932272884";
			}else if($salon->empresa == 2){
				$chat_id = '-1001291962255';
			}else if($salon->empresa == 3){
				$chat_id = '-1001359299397';
			}else{
				$chat_id = '-1001158004617';
			}
			if(($ticket->destino != 2 && $ticket->destino != 4 && $ticket->destino != 31 && $ticket->destino != 32) || $ticket->situacion == 13){
				$sat_op = "*"."DESTINO SAT OPERADORA"."*"."\n";
			}else{
				$sat_op = "";
			}
		}
		
		if($chat_id != ''){		
			$error_desc = stripslashes($ticket->error_desc);
			$error_desc = htmlspecialchars_decode($error_desc);
			$error_desc = str_replace('&quot;','"',$error_desc);
			$error_desc = str_replace("&apos;","'",$error_desc);
			
			$body = "";
			$body .= "*"."#".$ticket->id."*"."\n";
			$body .= "*".$salon->salon." ".$salon->poblacion."*"."\n";
			if($ticket->maquina != 0){
				$body .= $maquina->maquina."\n";
			}
			if($ticket->prioridad == 2){
				$body .= "*"."URGENTE"."*"."\n";
			}
			$body .= $tipo_averia->gestion." ".$tipo_error->tipo." ".$detalle_error->error_detalle."\n";
			if($ticket->tipo_error == 62 || $ticket->tipo_error == 77 || $ticket->tipo_error == 113){
				$body .= "Importe: ".$ticket_manual->importe."\n";
			}
			$body .= "[".$usuario->nombre."]" . "(tg://user?id=".$usuario->user_id_telegram.")";
			$body .= " escribe:"."\n";
			$body .= $error_desc."\n";
			$body .= $html_historial;
			$body .= $sat_op;
			
			$data = [
			    'text' => $body,
			    'chat_id' => urlencode($chat_id),
			    'parse_mode' => 'markdown'
			];
			
			$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?".http_build_query($data));

			$result = json_decode($ejecutar,true);

			if(isset($result)){
			
				$chatid =  $result["result"]["message_id"];
				
				$resultado = $this->post->actualizar_ticket_chat($chatid,$id);
				
				if($ticket->imagen != ''){
					
					$ext = explode('.',$ticket->imagen);
					
					if($ext[1] == 'jpg' || $ext[1] == 'jpeg' || $ext[1] == 'png' || $ext[1] == 'gif' || $ext[1] == 'jfif'){
						$data2 = [
							'chat_id' => $chat_id,
						    'photo' =>  'http://domain/tickets/files/img/errores/'.$ticket->imagen.''
						];		
					
						$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendPhoto?".http_build_query($data2));
					}
					
					if($ext[1] == 'pdf' || $ext[1] == 'doc' || $ext[1] == 'docx' || $ext[1] == 'xls' || $ext[1] == 'xlsx'){
						$data2 = [
							'chat_id' => $chat_id,
						    'document' =>  'http://domain/tickets/files/img/errores/'.$ticket->imagen.''
						];		
					
						$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendDocument?".http_build_query($data2));
					}
				}

				/* Auto asignar ticket ADM */
				/*
				if($ticket->destino == 4 && $ticket->situacion == 2){
					$this->auto_asignar_ticket($ticket->id);
				}
				*/
			}
			
			if($usuario->rol == 1 || $this->session->userdata('logged_in')['rol'] == 1){
				if($ticket->situacion == 13 && $ticket->operadora != 41){
					$chat_id=$op2->chat_telegram_id;
					if($chat_id != ''){
						$data = [
						    'text' => $body,
						    'chat_id' => urlencode($chat_id),
						    'parse_mode' => 'markdown'
						];					
						$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?".http_build_query($data));

						$result = json_decode($ejecutar,true);

						if(isset($result)){
			
							$chatid =  $result["result"]["message_id"];
						
							$resultado = $this->post->actualizar_ticket_chat2($chatid,$id);

							if($ticket->imagen != ''){
					
								$ext = explode('.',$ticket->imagen);
								
								if($ext[1] == 'jpg' || $ext[1] == 'jpeg' || $ext[1] == 'png' || $ext[1] == 'gif' || $ext[1] == 'jfif'){
									$data2 = [
										'chat_id' => $chat_id,
									    'photo' =>  'http://domain/tickets/files/img/errores/'.$ticket->imagen.''
									];		
								
									$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendPhoto?".http_build_query($data2));
								}
								
								if($ext[1] == 'pdf' || $ext[1] == 'doc' || $ext[1] == 'docx' || $ext[1] == 'xls' || $ext[1] == 'xlsx'){
									$data2 = [
										'chat_id' => $chat_id,
									    'document' =>  'http://domain/tickets/files/img/errores/'.$ticket->imagen.''
									];		
								
									$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendDocument?".http_build_query($data2));
								}
							}
						}
					}
				}				
			}			
		}
	}
	
	public function telegram2($id,$trata){
		
		$ticket = $this->post->get_ticket($id);
		$op = $this->post->get_operadoras_rol_2($ticket->operadora);
		$op2 = $op->row();
		$salon = $this->post->get_salon_completo($ticket->salon);
		$usuario = $this->post->get_creador_completo($this->session->userdata('logged_in')['id']);
		$usuarios_adm = $this->post->get_usuarios_adm();
		
		$Token='1684937498:AAHbqAqAwYIljLiVE8d0Dk7PiHjtb-RcYug';
		$chat_id=$op2->chat_telegram_id;
		$API="https://api.telegram.org/bot".$Token;
		$url=$API."/sendMessage?chat_id=".$chat_id;

		$replyid = $ticket->chatid;
		
		if(in_array($this->session->userdata('logged_in')['id'], $usuarios_adm)){
			if($ticket->destino == 32 || $ticket->situacion == 14){
				$chat_id="-1001422012811";
			}else if($ticket->destino == 1 || $ticket->situacion == 3){
				$chat_id="-2045930";
			}else if($ticket->destino == 2 || $ticket->situacion == 12){
				$chat_id="-4577120300";
			}else if($ticket->destino == 31 || $ticket->situacion == 19){
				$chat_id="-971495944";
			}else if($ticket->destino == 261 || $ticket->situacion == 21){
				$chat_id="-4932272884";
			}else if($salon->empresa == 2){
				$chat_id = '-1001291962255';
			}else	if($salon->empresa == 3){
				$chat_id = '-1001359299397';
			}else{
				$chat_id = '-1001158004617';
			}
			if($ticket->destino != 2 && $ticket->destino != 4 && $ticket->destino != 31 && $ticket->destino != 32){
				$sat_op = "*"."DESTINO SAT OPERADORA"."*"."\n";
			}else{
				$sat_op = "";
			}
		}
		
		if($chat_id != ''){
			$trata = stripslashes($trata);
			$trata = htmlspecialchars_decode($trata);
			$trata = str_replace('&quot;','"',$trata);
			$trata = str_replace("&apos;","'",$trata);
		
			$body = "";
			$body .= "[".$usuario->nombre."]" . "(tg://user?id=".$usuario->user_id_telegram.")";
			$body .= " escribe:" . "\n";
			$body .= $trata."\n";

			if($ticket->tipo_error == 91 || $ticket->tipo_error == 92 || $ticket->tipo_error == 99){
				$historial = $this->post->get_historial_periferico($id);
				if($historial){
					if($ticket->tipo_error == 91){
						$periferico = $this->post->get_monedero($historial->peri_ant);
					}else if($ticket->tipo_error == 92){
						$periferico = $this->post->get_billetero($historial->peri_ant);
					}else{
						$periferico = $this->post->get_impresora($historial->peri_ant);
					}
					if($periferico){
						$body .= "*"."Actual: "."*".$periferico->nombre."\n";
					}

					if($ticket->tipo_error == 91){
						$periferico = $this->post->get_monedero($historial->peri_nue);
					}else if($ticket->tipo_error == 92){
						$periferico = $this->post->get_billetero($historial->peri_nue);
					}else{
						$periferico = $this->post->get_impresora($historial->peri_nue);
					}
					if($periferico){
						$body .= "*"."Nuevo: "."*".$periferico->nombre."\n";
					}
				}
			}
			
			if($ticket->situacion == 6){
				$body .= "*"."SOLUCIONADO"."*"."\n";
			}else{
				$body .= "*"."SIN SOLUCIONAR"."*"."\n";
			}
			
			$data = [
			    'text' => $body,
			    'chat_id' => $chat_id,
			    'parse_mode' => 'markdown',
			    'reply_to_message_id' => $replyid
			];

			$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?" . http_build_query($data));

			$result = json_decode($ejecutar,true);

			if(isset($result)){
			
				$chatid =  $result["result"]["message_id"];
				
				$resultado = $this->post->actualizar_ticket_chat($chatid,$id);
				
				if($ticket->imagen2 != ''){
					
					$ext = explode('.',$ticket->imagen2);
					
					if($ext[1] == 'jpg' || $ext[1] == 'jpeg' || $ext[1] == 'png' || $ext[1] == 'gif' || $ext[1] == 'jfif'){
						$data2 = [
							'chat_id' => $chat_id,
						    'photo' =>  'http://domain/tickets/files/img/trata/'.$ticket->imagen2.''
						];		
					
						$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendPhoto?".http_build_query($data2));
					}
					
					if($ext[1] == 'pdf' || $ext[1] == 'doc' || $ext[1] == 'docx' || $ext[1] == 'xls' || $ext[1] == 'xlsx'){
						$data2 = [
							'chat_id' => $chat_id,
						    'document' =>  'http://domain/tickets/files/img/trata/'.$ticket->imagen2.''
						];		
					
						$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendDocument?".http_build_query($data2));
					}
				}
			}
		}

		if(isset($ticket->chatid2) && $ticket->chatid2 != ''){
			$replyid = $ticket->chatid2;
			$chat_id=$op2->chat_telegram_id;	
			if($chat_id != ''){
				$trata = stripslashes($trata);
				$trata = htmlspecialchars_decode($trata);
				$trata = str_replace('&quot;','"',$trata);
				$trata = str_replace("&apos;","'",$trata);
			
				$body = "";
				$body .= "[".$usuario->nombre."]" . "(tg://user?id=".$usuario->user_id_telegram.")";
				$body .= " escribe:" . "\n";
				$body .= $trata."\n";
				
				if($ticket->situacion == 6){
					$body .= "*"."SOLUCIONADO"."*"."\n";
				}else{
					$body .= "*"."SIN SOLUCIONAR"."*"."\n";
				}
				
				$data = [
				    'text' => $body,
				    'chat_id' => $chat_id,
				    'parse_mode' => 'markdown',
				    'reply_to_message_id' => $replyid
				];

				$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?" . http_build_query($data));

				$result = json_decode($ejecutar,true);

				if(isset($result)){
				
					$chatid =  $result["result"]["message_id"];
					
					$resultado = $this->post->actualizar_ticket_chat($chatid,$id);
					
					if($ticket->imagen2 != ''){
						
						$ext = explode('.',$ticket->imagen2);
						
						if($ext[1] == 'jpg' || $ext[1] == 'jpeg' || $ext[1] == 'png' || $ext[1] == 'gif' || $ext[1] == 'jfif'){
							$data2 = [
								'chat_id' => $chat_id,
							    'photo' =>  'http://domain/tickets/files/img/trata/'.$ticket->imagen.''
							];		
						
							$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendPhoto?".http_build_query($data2));
						}
						
						if($ext[1] == 'pdf' || $ext[1] == 'doc' || $ext[1] == 'docx' || $ext[1] == 'xls' || $ext[1] == 'xlsx'){
							$data2 = [
								'chat_id' => $chat_id,
							    'document' =>  'http://domain/tickets/files/img/trata/'.$ticket->imagen.''
							];		
						
							$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendDocument?".http_build_query($data2));
						}
					}
				}
			}
		}		
	}
	
	public function telegram3($id){
		$ticket = $this->post->get_ticket($id);
		$op = $this->post->get_operadoras_rol_2($ticket->operadora);
		$op2 = $op->row();
		$salon = $this->post->get_salon_completo($ticket->salon);
		$usuario = $this->post->get_creador_completo($ticket->asignado);
		$usuarios_adm = $this->post->get_usuarios_adm();
		
		$Token='1684937498:AAHbqAqAwYIljLiVE8d0Dk7PiHjtb-RcYug';
		$chat_id=$op2->chat_telegram_id;
		$API="https://api.telegram.org/bot".$Token;
		$url=$API."/sendMessage?chat_id=".$chat_id;

		$replyid = $ticket->chatid;
		
		$body = "";
		$body .= "[".$usuario->nombre."]" . "(tg://user?id=".$usuario->user_id_telegram.")";
		$body .= " en camino" . "\n";
		
		if(in_array($this->session->userdata('logged_in')['id'], $usuarios_adm)){
			if($ticket->destino == 32 || $ticket->situacion == 14){
				$chat_id="-1001422012811";
			}else if($ticket->situacion == 3){
				$chat_id="-2045930";
			}else if($ticket->destino == 2 || $ticket->situacion == 12){
				$chat_id="-4577120300";
			}else if($ticket->destino == 31 || $ticket->situacion == 19){
				$chat_id="-971495944";
			}else if($ticket->destino == 261 || $ticket->situacion == 21){
				$chat_id="-4932272884";
			}else if($salon->empresa == 2){
				$chat_id = '-1001291962255';
			}else if($salon->empresa == 3){
				$chat_id = '-1001359299397';
			}else{
				$chat_id = '-1001158004617';
			}
			if($ticket->destino != 2 && $ticket->destino != 4 && $ticket->destino != 31 && $ticket->destino != 32){
				$sat_op = "*"."DESTINO SAT OPERADORA"."*"."\n";
			}else{
				$sat_op = "";
			}
		}

		if($chat_id != ''){
			$data = [
			    'text' => $body,
			    'chat_id' => $chat_id,
			    'parse_mode' => 'markdown',
			    'reply_to_message_id' => $replyid
			];
			$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?" . http_build_query($data) );			
		}

		if(isset($ticket->chatid2) && $ticket->chatid2 != ''){
			$replyid = $ticket->chatid2;
			$chat_id=$op2->chat_telegram_id;	
			if($chat_id != ''){
				$data = [
				    'text' => $body,
				    'chat_id' => $chat_id,
				    'parse_mode' => 'markdown',
				    'reply_to_message_id' => $replyid
				];
				$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?" . http_build_query($data) );			
			}
		}	
	}
	
	public function telegram4(){
		
		/* Get usuario */
		$usuario = $this->post->get_last_user();
		$Token = '1684937498:AAHbqAqAwYIljLiVE8d0Dk7PiHjtb-RcYug';
		$chat_id=-293641424;
		
		if($usuario->telefono != ''){
			
			if(strpos($usuario->telefono,'+34') !== false){
				$telefono = $usuario->telefono;				
			}else{
				$telefono = "+34".$usuario->telefono;
			}
			
			$data = [
			    'chat_id' => $chat_id,
			    'first_name' => $usuario->nombre,
			    'phone_number' => $telefono
			];

			$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendContact?".http_build_query($data));
			
			$result = json_decode($ejecutar,true);
			
			if(isset( $result["result"]["contact"]["user_id"]) && $result["result"]["contact"]["user_id"] != ''){
				
				$userid =  $result["result"]["contact"]["user_id"];			
				$resultado = $this->post->actualizar_usuario_chat($userid,$usuario->id);
				
			}
			
		}
		
	}
	
	public function telegram5($id, $desc){
		$ticket = $this->post->get_ticket($id);
		$op = $this->post->get_operadoras_rol_2($ticket->operadora);
		$op2 = $op->row();
		$salon = $this->post->get_salon_completo($ticket->salon);
		$creador = $this->post->get_creador_completo($this->session->userdata('logged_in')['id']);
		if($ticket->asignado != 0){
			$usuario = $this->post->get_creador_completo($ticket->asignado);
		}
		$usuarios_adm = $this->post->get_usuarios_adm();
		
		$Token='1684937498:AAHbqAqAwYIljLiVE8d0Dk7PiHjtb-RcYug';
		$chat_id=$op2->chat_telegram_id;
		$API="https://api.telegram.org/bot".$Token;
		$url=$API."/sendMessage?chat_id=".$chat_id;

		$replyid = $ticket->chatid;
		
		$body = "";
		$body .= "[".$creador->nombre."]" . "(tg://user?id=".$creador->user_id_telegram.") ";
		if($ticket->asignado != 0){
			$body .= "asigna incidencia a ";
			$body .= "[".$usuario->nombre."]" . "(tg://user?id=".$usuario->user_id_telegram.") \n";
		}

		if(!empty($desc) && $desc != ''){
			$desc = stripslashes($desc);
			$desc = htmlspecialchars_decode($desc);
			$desc = str_replace('&quot;','"',$desc);
			$desc = str_replace("&apos;","'",$desc);
			$desc = str_replace('*','',$desc);
			$body .= $desc."\n";
		}
		
		if(in_array($this->session->userdata('logged_in')['id'], $usuarios_adm)){
			if($ticket->destino == 32 || $ticket->situacion == 14){
				$chat_id="-1001422012811";
			}else if($ticket->situacion == 3){
				$chat_id="-2045930";
			}else if($ticket->destino == 2 || $ticket->situacion == 12){
				$chat_id="-4577120300";
			}else if($ticket->destino == 31 || $ticket->situacion == 19){
				$chat_id="-971495944";
			}else if($ticket->destino == 261 || $ticket->situacion == 21){
				$chat_id="-4932272884";
			}else if($salon->empresa == 2){
				$chat_id = '-1001291962255';
			}else if($salon->empresa == 3){
				$chat_id = '-1001359299397';
			}else{
				$chat_id = '-1001158004617';
			}
		}
		
		if($chat_id != ''){	
			$data = [
			    'text' => $body,
			    'chat_id' => $chat_id,
			    'parse_mode' => 'markdown',
			    'reply_to_message_id' => $replyid
			];
			$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?" . http_build_query($data) );
		}

		if(isset($ticket->chatid2) && $ticket->chatid2 != ''){
			$replyid = $ticket->chatid2;
			$chat_id=$op2->chat_telegram_id;	
			if($chat_id != ''){
				$data = [
				    'text' => $body,
				    'chat_id' => $chat_id,
				    'parse_mode' => 'markdown',
				    'reply_to_message_id' => $replyid
				];
				$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?" . http_build_query($data) );			
			}
		}
		
	}
	
	public function telegram6($id,$trata){
		
		$ticket = $this->post->get_ticket($id);
		$op = $this->post->get_operadoras_rol_2($ticket->operadora);
		$op2 = $op->row();
  		$salon = $this->post->get_salon_completo($ticket->salon);
  		$maquina = $this->post->get_maquina_completo($ticket->maquina);
  		$modelo = $this->post->get_modelo($maquina->modelo);
  		$tipo_averia = $this->post->get_averia($ticket->tipo_averia);
  		$tipo_error = $this->post->get_tipo_error_completo($ticket->tipo_error);
		$detalle_error = $this->post->get_detalle_error_completo($ticket->detalle_error);
		$usuario = $this->post->get_creador_completo($this->session->userdata('logged_in')['id']);
		
		$html_historial = '';
		if($ticket->maquina != 0){
			$historial = $this->post->get_historial_maquina_telegram($id,$ticket->maquina);
			if($historial->num_rows() > 0){
				$html_historial .= '*Historial incidencia*';
				$html_historial .= "\n";
				foreach($historial->result() as $historia){
					if($historia->id == $id){
						continue;
					}else{
						$tipo = $this->post->get_averia($historia->tipo_averia);
				  		$error = $this->post->get_tipo_error_completo($historia->tipo_error);
						$detalle = $this->post->get_detalle_error_completo($historia->detalle_error);
						$fecha = explode('-', $historia->fecha_creacion);
						$fecha_creacion = $fecha[2]."/".$fecha[1]."/".$fecha[0];
						$html_historial .= '*'.'#'.$historia->id.' '.$fecha_creacion.' '.$historia->hora_creacion.'* '.$tipo->gestion." ".$error->tipo." ".$detalle->error_detalle."\n";
					}
				}
			}
		}
		
		if($ticket->tipo_error == 62 || $ticket->tipo_error == 77 || $ticket->tipo_error == 113){
			$ticket_manual = $this->post->get_ticket_manual($id);
		}
		
		$Token='1684937498:AAHbqAqAwYIljLiVE8d0Dk7PiHjtb-RcYug';
		$chat_id = '-1001158004617';
		$API="https://api.telegram.org/bot".$Token;
		$url=$API."/sendMessage?chat_id=".$chat_id;
		
		if($chat_id != ''){
			$error_desc = stripslashes($ticket->error_desc);
			$error_desc = htmlspecialchars_decode($error_desc);
			$error_desc = str_replace('&quot;','"',$error_desc);
			$error_desc = str_replace("&apos;","'",$error_desc);
			
			$body = "";
			$body .= "*"."#".$ticket->id."*"."\n";
			$body .= "*".$salon->salon." ".$salon->poblacion."*"."\n";
			if($ticket->maquina != 0){
				$body .= $maquina->maquina."\n";
			}
			if($ticket->prioridad == 2){
				$body .= "*"."URGENTE"."*"."\n";
			}
			$body .= $tipo_averia->gestion." ".$tipo_error->tipo." ".$detalle_error->error_detalle."\n";
			if($ticket->tipo_error == 62 || $ticket->tipo_error == 77 || $ticket->tipo_error == 113){
				$body .= "Importe: ".$ticket_manual->importe."\n";
			}
			$body .= "[".$usuario->nombre."]" . "(tg://user?id=".$usuario->user_id_telegram.")";
			$body .= " escribe:"."\n";
			$body .= $trata."\n";
			$body .= $html_historial;
			$body .= "*"."SIN SOLUCIONAR (ORIGEN SAT OPERADORA)"."*"."\n";
			
			$data = [
			    'text' => $body,
			    'chat_id' => urlencode($chat_id),
			    'parse_mode' => 'markdown'
			];
			
			$ejecutar = $this->http_request("https://api.telegram.org/bot$Token/sendMessage?".http_build_query($data));
			
			$result = json_decode($ejecutar,true);

			if(isset($result)){
			
				$chatid =  $result["result"]["message_id"];
				
				$resultado = $this->post->actualizar_ticket_chat($chatid,$id);
				
				if($ticket->imagen2 != ''){
					
					$ext = explode('.',$ticket->imagen2);
					
					if($ext[1] == 'jpg' || $ext[1] == 'jpeg' || $ext[1] == 'png' || $ext[1] == 'gif' || $ext[1] == 'jfif'){
						$data2 = [
							'chat_id' => $chat_id,
						    'photo' =>  'http://domain/tickets/files/img/trata/'.$ticket->imagen2.''
						];		
					
						$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendPhoto?".http_build_query($data2));
					}
					
					if($ext[1] == 'pdf' || $ext[1] == 'doc' || $ext[1] == 'docx' || $ext[1] == 'xls' || $ext[1] == 'xlsx'){
						$data2 = [
							'chat_id' => $chat_id,
						    'document' =>  'http://domain/tickets/files/img/trata/'.$ticket->imagen2.''
						];		
					
						$ejecutar2 = $this->http_request("https://api.telegram.org/bot$Token/sendDocument?".http_build_query($data2));
					}
				}
			}
		}
	}
	
	/* Recuperar contraseña */
	public function recuperar_pass(){
		$data = array('title' => '');
		$this->load->view('recuperar', $data);
	}
	
	public function recuperar(){
		$data = array('title' => '');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
	    	$this->load->view('recuperar', $data);
	    }else{
	    	$resultado=$this->post->recuperar_pass($this->input->post('email'));
	    	if($resultado){
	    		$enviado = "<span style='font-weight: bold'>Se ha enviado un email a su dirección de correo.</span>";
	    		$data = array('title' => 'Administracion', 'enviado' => $enviado);
	    		$this->enviarmail_pass($resultado->id);
	    		$this->post->guardar_historial($resultado->id,'Recuperar Contraseña');
	    		$this->load->view('recuperar', $data);
	    	}else{
	    		$enviado = "No existe un usuario con esa dirección de correo.";
	    		$data = array('title' => 'Administracion', 'enviado' => $enviado);
	    		$this->load->view('recuperar', $data);
	    	}
	  	}
	}
	
	/* Funcion principal - login/index */
	public function index(){
		if($this->session->userdata('logged_in')){
			$this->gestion();
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* login */
	public function login(){
		$data = array('title' => '');
		$this->form_validation->set_rules('user', 'Usuario', 'trim|required|htmlspecialchars');
		$this->form_validation->set_rules('pass', 'Contrase&ntilde;a', 'trim|required|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
	    	$this->load->view('login', $data);
	    }else{
	    	$resultado = $this->post->login($this->input->post('user'),$this->input->post('pass'));
			if($resultado){
				$permisos = $this->post->permisos_operadora($resultado->acceso,$resultado->rol);
				$sess_array = array();
				$sess_array = array(
					'id' => $resultado->id,
					'user' => $resultado->usuario,
					'mail' => $resultado->email,
					'telefono' => $resultado->telefono,
					'rol' => $resultado->rol,
					'acceso' => $resultado->acceso,
					'permiso_incidencias' => $permisos->Incidencias,
					'permiso_maquinas' => $permisos->Maquinas,
					'permiso_ruletas' => $permisos->Ruletas,
					'permiso_cajeros' => $permisos->Cajeros,
					'permiso_movimientos' => $permisos->Mantenimiento,
					'permiso_combustible' => $permisos->Combustible,
					'permiso_zonas' => $permisos->Zonas,
					'permiso_locales' => $permisos->Locales
				);
				$this->session->set_userdata('logged_in', $sess_array);
				$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Inicio de sesión');
				$this->gestion();
				return TRUE;							
			}else{
				$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrectos');
				$this->load->view('login', $data);
				return false;
			}
	    }
	}

	public function location_from_tipster(){
		if(isset($_GET['user']) && $_GET['user'] != ''){			
			$user = $_GET['user'];
			$location = $_GET['location'];
			$this->post->save_location($user,$location);
		}
	}

	public function message_from_tipster(){

		if(isset($_GET['user']) && $_GET['user'] != ''){
			
			$user = $_GET['user'];
			$number = $_GET['number'];
			$message = $_GET['message'];
			$check_message_first_word = explode(" ", $message);
			$message = str_replace("*", " ", $message);

			switch($user){
				case "":
					$sql = "SELECT * FROM grupos WHERE nombre LIKE 'ADM' AND operadora = 24";
					$query = $this->db->query($sql);
					$grupo = $query->row();
					break;
				case "":
					$sql = "SELECT * FROM grupos WHERE nombre LIKE 'ADM' AND operadora = 10";
					$query = $this->db->query($sql);
					$grupo = $query->row();
					break;
			}
			
			// Comprobar remitente
			$sql = "SELECT * FROM remitentes_SMS_APP WHERE (telefono LIKE '%".$number."%' OR NOMBRE LIKE '%".$check_message_first_word[0]."%') AND operadora = ".$grupo->operadora."";
			$query = $this->db->query($sql);
			if($query->num_rows() != 0){		
				//$chat_id = $grupo->id_chat_telegram;
				$chat_id = "-521955412";
				$body = "";
				$body .= "Mensaje Recibido de: ".$number."\n";
				$body .= "El " .date("d/m/Y"). " a las " .date("H:i:s"). "\n";
				$body .= $message."\n";	 			
	 		
	 			if($grupo->operadora != 10){
	 				$email = $body;				
					$this->load->library('email');
					$config['useragent'] = 'NUEVO MENSAJE RECIBIDO APP INCIDENCIAS';
			        $this->email->initialize($config);
				  	$this->email->from('mail@domain', 'NUEVO MENSAJE RECIBIDO APP INCIDENCIAS');
				  	$this->email->to($grupo->email);
				  	$this->email->subject("NUEVO MENSAJE RECIBIDO APP INCIDENCIAS");
				  	$this->email->message($email);
				  	$this->email->send();																
	 			}		  						
			
			  	$data = [
				    'text' => $body,
				    'chat_id' => urlencode($chat_id),
				    'parse_mode' => 'markdown'
				];
				
				$ejecutar = $this->http_request("https://api.telegram.org/bot1684937498:AAHbqAqAwYIljLiVE8d0Dk7PiHjtb-RcYug/sendMessage?".http_build_query($data));

				$result = json_decode($ejecutar,true);
			}
		}
	}
	
	public function loginfromtipster($user_tipster){
		if($this->session->userdata('logged_in')){
			redirect('gestion', 'refresh');
		}else{
			$check = $this->post->get_usuario_email($user_tipster);
			if($check){
				$resultado = $this->post->login2($user_tipster);
				if(!empty($resultado)){
					$permisos = $this->post->permisos_operadora($resultado->acceso,$resultado->rol);
				 	$sess_array = array();
			   		$sess_array = array(
						'id' => $resultado->id,
						'user' => $resultado->usuario,
						'mail' => $resultado->email,
						'telefono' => $resultado->telefono,
						'rol' => $resultado->rol,
						'acceso' => $resultado->acceso,
						'permiso_incidencias' => $permisos->Incidencias,
						'permiso_maquinas' => $permisos->Maquinas,
						'permiso_ruletas' => $permisos->Ruletas,
						'permiso_cajeros' => $permisos->Cajeros,
						'permiso_movimientos' => $permisos->Mantenimiento,
						'permiso_combustible' => $permisos->Combustible,
						'permiso_zonas' => $permisos->Zonas,
						'permiso_locales' => $permisos->Locales
			    	);
			    	$this->session->set_userdata('logged_in', $sess_array);
			    	$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Inicio de sesión');
				 	redirect('gestion', 'refresh');
				 	return true;
				}else{
					$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrectos');
					$this->load->view('login', $data);
		   			return false;
				}
			}else{
				$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrecto');
				$this->load->view('login', $data);
	   			return false;
			}		
		}
	}
	
	public function verfromtipster($user_tipster,$id_ticket){
		if($this->session->userdata('logged_in')){
			$location = base_url('/ver_historial/'.$id_ticket);
			header("refresh: 2; url=".$location."");
		}else{
			$check = $this->post->get_usuario_email($user_tipster);
			if($check){
				$resultado = $this->post->login2($user_tipster);
				if($resultado){
					$permisos = $this->post->permisos_operadora($resultado->acceso,$resultado->rol);
					$sess_array = array();
				    $sess_array = array(
				    	'id' => $resultado->id,
				     	'user' => $resultado->usuario,
						'mail' => $resultado->email,
						'telefono' => $resultado->telefono,
						'rol' => $resultado->rol,
						'acceso' => $resultado->acceso,
						'permiso_incidencias' => $permisos->Incidencias,
						'permiso_maquinas' => $permisos->Maquinas,
						'permiso_ruletas' => $permisos->Ruletas,
						'permiso_cajeros' => $permisos->Cajeros,
						'permiso_movimientos' => $permisos->Mantenimiento,
						'permiso_combustible' => $permisos->Combustible,
						'permiso_zonas' => $permisos->Zonas,
						'permiso_locales' => $permisos->Locales
				    );
				    $this->session->set_userdata('logged_in', $sess_array);
				    $this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Inicio de sesión');
				    $location = base_url('/ver_historial/'.$id_ticket);
					header("refresh: 2; url=".$location."");
					return true;
				}else{
					$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrectos');
					$this->load->view('login', $data);
		   			return false;
		   		}
			}else{
				$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrectos');
				$this->load->view('login', $data);
	   			return false;
			}
		}
	}
	
	public function asignarfromtipster($user_tipster,$id_ticket){
		if($this->session->userdata('logged_in')){
			$location = base_url('/asignar_ticket/'.$id_ticket);
			header("refresh: 2; url=".$location."");
		}else{
			$check = $this->post->get_usuario_email($user_tipster);
			if($check){
				$resultado = $this->post->login2($user_tipster);
				if($resultado){
					$permisos = $this->post->permisos_operadora($resultado->acceso,$resultado->rol);
					$sess_array = array();
				    $sess_array = array(
				    	'id' => $resultado->id,
				     	'user' => $resultado->usuario,
						'mail' => $resultado->email,
						'telefono' => $resultado->telefono,
						'rol' => $resultado->rol,
						'acceso' => $resultado->acceso,
						'permiso_incidencias' => $permisos->Incidencias,
						'permiso_maquinas' => $permisos->Maquinas,
						'permiso_ruletas' => $permisos->Ruletas,
						'permiso_cajeros' => $permisos->Cajeros,
						'permiso_movimientos' => $permisos->Mantenimiento,
						'permiso_combustible' => $permisos->Combustible,
						'permiso_zonas' => $permisos->Zonas,
						'permiso_locales' => $permisos->Locales
				    );
				    $this->session->set_userdata('logged_in', $sess_array);
				    $this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Inicio de sesión');
				    $location = base_url('/asignar_ticket/'.$id_ticket);
					header("refresh: 2; url=".$location."");
					return true;
				}else{
					$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrectos');
					$this->load->view('login', $data);
		   			return false;
		   		}
			}else{
				$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrectos');
				$this->load->view('login', $data);
	   			return false;
			}
		}
	}
	
	public function solucionarfromtipster($user_tipster,$id_ticket){
		if($this->session->userdata('logged_in')){
			$location = base_url('/solucionar_ticket/'.$id_ticket);
			header("refresh: 2; url=".$location."");
		}else{
			$check = $this->post->get_usuario_email($user_tipster);
			if($check){
				$resultado = $this->post->login2($user_tipster);
				if($resultado){
					$permisos = $this->post->permisos_operadora($resultado->acceso,$resultado->rol);
					$sess_array = array();
				    $sess_array = array(
				    	'id' => $resultado->id,
				     	'user' => $resultado->usuario,
						'mail' => $resultado->email,
						'telefono' => $resultado->telefono,
						'rol' => $resultado->rol,
						'acceso' => $resultado->acceso,
						'permiso_incidencias' => $permisos->Incidencias,
						'permiso_maquinas' => $permisos->Maquinas,
						'permiso_ruletas' => $permisos->Ruletas,
						'permiso_cajeros' => $permisos->Cajeros,
						'permiso_movimientos' => $permisos->Mantenimiento,
						'permiso_combustible' => $permisos->Combustible,
						'permiso_zonas' => $permisos->Zonas,
						'permiso_locales' => $permisos->Locales
				    );
				    $this->session->set_userdata('logged_in', $sess_array);
				    $this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Inicio de sesión');
				    $location = base_url('/solucionar_ticket/'.$id_ticket);
					header("refresh: 2; url=".$location."");
					return true;
				}else{
					$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrectos');
					$this->load->view('login', $data);
		   			return false;
		   		}
			}else{
				$data = array('title' => 'Administracion', 'error_login' => 'Usuario o contrase&ntilde;a incorrectos');
				$this->load->view('login', $data);
	   			return false;
			}
		}
	}
	
	/* Cuenta de usuario */
	public function cuenta(){
		if($this->session->userdata('logged_in')){
			$user = $this->post->get_creador_completo($this->session->userdata('logged_in')['id']);
			$operadora = $this->post->get_operadoras_rol_2($this->session->userdata('logged_in')['acceso']);
			$op = $operadora->row();
			$html_empleados = '';
			if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){
				$emplados = $this->post->get_empleados_operadora($this->session->userdata('logged_in')['acceso']);
				foreach($emplados->result() as $empleado){
					$html_empleados .= '<option value="'.$empleado->id.'">'.$empleado->nombre.'</option>';
				}
			}
			$data = array('title' => 'Administracion', 'user' => $user, 'op' => $op, 'html_empleados' => $html_empleados);
			$this->load_view('cuenta', $data);
		}else{
		    $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function cuenta_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|htmlspecialchars|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|htmlspecialchars|required');
		$this->form_validation->set_rules('pass', 'Contraseña', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
			$user = $this->post->get_creador_completo($this->session->userdata('logged_in')['id']);
			$data = array('title' => 'Administracion', 'user' => $user);
    		$this->load_view('cuenta', $data);
	    }else{
	    	$id = '';
	    	if($this->input->post('emails') == 'on'){
	    		$emails = 1;
	    	}else{
	    		$emails = 0;
	    	}
	    	if($this->input->post('noti') == 'on'){
	    		$noti = 1;
	    	}else{
	    		$noti = 0;
	    	}
	    	if($this->input->post('jornada') == 'on'){
	    		$jornada = 1;
	    	}else{
	    		$jornada = 0;
	    	}
	        $resultado = $this->post->actualizar_usuario($id,$this->input->post('nombre'),$this->input->post('email'),$this->input->post('pass'),$noti,$emails,$this->input->post('hora_inicio'),$this->input->post('hora_fin'),$jornada);
	        $user = $this->post->get_creador_completo($this->session->userdata('logged_in')['id']);
	      	$operadora = $this->post->get_operadoras_rol_2($this->session->userdata('logged_in')['acceso']);
		  	$op = $operadora->row();     
	      	$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Actualizar Cuenta');
	      	$cambios = "Cambios realizados correctamente.";
	      	$data['cambios'] = $cambios;
	      	$data['user'] = $user;
	      	$data['op'] = $op;
	      	$this->load_view('cuenta', $data);
	    }
	}
	
	/* Buscador Informes - Rol Comercial */
	public function buscador_informes(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$data = array('title' => '');
				$this->form_validation->set_rules('empresa', 'Empresa', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('fecha_inicio', 'Fecha Inicio', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('fecha_fin', 'Fecha Fin', 'trim|htmlspecialchars');
				if ($this->form_validation->run() == FALSE){
					$this->informes();
				}else{
					$empresa = $this->input->post('empresa');
					$su = $this->input->post('supervisora');
					$fecha_inicio = $this->input->post('fecha_inicio');
					$fecha_fin = $this->input->post('fecha_fin');	
					$visitas = $this->post->get_visitas_empresa($empresa,$su);
					
					/* Select empresas */
					$empresas = $this->post->get_empresas();
					$html_empresas='';
					$html_empresas.='<option value="">Seleccionar...</option>';
					if($empresa == 0){
						$html_empresas.='<option value="0" selected>TODOS</option>';
					}else{
						$html_empresas.='<option value="0">TODOS</option>';
					}
					foreach($empresas->result() as $row){
						if($row->id == $empresa){
							$html_empresas.='<option value="'.$row->id.'" selected>'.$row->empresa.'</option>';
						}else{
							$html_empresas.='<option value="'.$row->id.'">'.$row->empresa.'</option>';
						}
					}

					$supervisoras = $this->post->get_supervisoras();
					$html_supervisoras='';
					$html_supervisoras.='<option value="">TODAS</option>';
					foreach($supervisoras->result() as $supervisora){
						if($supervisora->id == $su){
							$html_supervisoras.='<option value="'.$supervisora->id.'" selected>'.$supervisora->nombre.'</option>';
						}else{
							$html_supervisoras.='<option value="'.$supervisora->id.'">'.$supervisora->nombre.'</option>';
						}
					}				
					
					$tabla_visitas = '';
					$version_movil = '';
					
					if($fecha_inicio == ''){
						$fecha_hoy = strtotime("-30 days");
						$fecha_inicio = date("d/m/Y", $fecha_hoy);
					}
					
					if($fecha_fin == ''){
						$fecha_fin = date("d/m/Y");
					}
					
					$cont = 0;
					
					if($visitas->num_rows() > 0){
						
						$tabla_visitas .= '<div class="col-md-12 col-sm-12" style="margin: 0; float: left">
									<table id="tabla_visitas" class="table tabla_incidencias">
										<thead style="border: 2px solid #ddd;">
											<tr>
												<th class="th_tabla"><a href="#" onclick="sortTableSalon()">Salón</op></th>
												<th class="th_tabla"><a href="#" onclick="sortTableOp()">Operadora</a></th>
												<th class="th_tabla"><a href="#" onclick="sortTableDate()">Fecha</a></th>
												<th class="th_tabla">Supervisora</th>
												<th class="th_tabla">Seleccionar</th>
											</tr>
										</thead>
										<tbody id="tabla_incidencias">';
										
						foreach($visitas->result() as $visita){
							$fecha_visita = explode(" ", $visita->fecha);
							$mostrar = $this->comparar_fechas($fecha_inicio,$fecha_fin,$fecha_visita[0]);
							
							if($mostrar){						
								if($visita->salon == 0){
									$salon = "Desconocido";
								}else{
									$salon = $this->post->get_salon($visita->salon);
								}

								if($visita->operadora == 0){
									$operadora= "Desconocido";
								}else{
									$operadora = $this->post->get_operadora($visita->operadora);
								}
								
								if(isset($visita->creador) && $visita->creador != ''){
									$creador = $this->post->get_creador($visita->creador);
								}else{
									$creador = "Desconocido";
								}

								$fecha1 = explode(" ", $visita->fecha);
								$fecha2 = explode("-", $fecha1[0]);
								$fecha = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];								
														
								$tabla_visitas .= '<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; font-weight: bold; color: #000; border: 2px solid #ddd;">
														 <td>'.$salon.'</td>
														 <td>'.$operadora.'</td>
														 <td id="'.$visita->fecha.'">'.$fecha.'</td>
														 <td>'.$creador.'</td>
														 <td>
														 	<div class="input-group">
																<input type="checkbox" name="selec[]" value="'.$visita->id.'" class="selec">
					    									</div>
					    								</td>
													</tr>';								
								$cont++;
							}
						}
						
						$tabla_visitas .= '</tbody>
																</table>
															</div>';
															
					}else{
						$tabla_visitas = '<p style="font-weight: bold; border: 1px solid #ccc; float: left; width: 100%; padding: 1%; border-radius: 5px;">No hay visitas registradas en esas fechas</p>';
					}
					
					if($cont == 0){
						$tabla_visitas = '<p style="font-weight: bold; border: 1px solid #ccc; float: left; width: 100%; padding: 1%; border-radius: 5px;">No hay visitas registradas en esas fechas</p>';
					}
					
					/* Informes */
					$informes = $this->post->get_informes_visitas();
					$tabla_informes = '';
					foreach($informes->result() as $informe){
						$empresa_info = $this->post->get_empresa($informe->empresa);
						$fecha = explode('-', $informe->fecha);
						$fecha = $fecha[2]."/".$fecha[1]."/".$fecha[0];
						$usuario = $this->post->get_creador($informe->usuario);
						
						$tabla_informes.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000">';

						if($informe->empresa == 0){
							$tabla_informes.='<td>TODOS</td>';
						}else{
							$tabla_informes.='<td>'.$empresa_info->empresa.'</td>';
						}
							$tabla_informes.='<td>'.$fecha.'</td>
											<td>'.$usuario.'</td>
											<td>
												<a target="_blank" style="padding: 2px 4px; margin: 0;" href="'.base_url('files/pdf/'.$informe->informe.'').'" type="button" class="btn btn-info" alt="Ver PDF" title="Ver PDF"><i class="fa fa-eye"></i></a>
												<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("eliminar_pdf/".$informe->id."").'" type="button" class="btn btn-danger" alt="Eliminar PDF" title="Eliminar PDF"><i class="fa fa-close"></i></a>
											</td>
										</tr>';
					}
					
					$data = array('title' => 'Administracion', 'html_empresas' => $html_empresas, 'tabla_visitas' => $tabla_visitas, 'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'id_empresa' => $empresa, 'tabla_informes' => $tabla_informes, 'supervisora' => $this->input->post('supervisora'), 'html_supervisoras' => $html_supervisoras);
    			$this->load_view('informes', $data);
				}
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Informes - Rol Comercial */
	public function informes($e=NULL,$sp=NULL,$fi=NULL,$ff=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				/* Select empresas */
				$empresas = $this->post->get_empresas_com();
				$html_empresas='';
				$html_empresas.='<option value="">Seleccionar...</option>
								<option value="0">TODOS</option>';
				foreach($empresas->result() as $empresa){
					if(isset($e) && $e == $empresa->id){
						$html_empresas.='<option value="'.$empresa->id.'" selected>'.$empresa->empresa.'</option>';
					}else{
						$html_empresas.='<option value="'.$empresa->id.'">'.$empresa->empresa.'</option>';
					}
				}

				$supervisoras = $this->post->get_supervisoras();
				$html_supervisoras='';
				$html_supervisoras.='<option value="">TODAS</option>';
				foreach($supervisoras->result() as $supervisora){
					if(isset($sp) && $sp == $supervisora->id){
						$html_supervisoras.='<option value="'.$supervisora->id.'" selected>'.$supervisora->nombre.'</option>';
					}else{
						$html_supervisoras.='<option value="'.$supervisora->id.'">'.$supervisora->nombre.'</option>';
					}
				}
				
				/* Informes */
				$informes = $this->post->get_informes_visitas();
				$tabla_informes = '';
				foreach($informes->result() as $informe){
					$empresa = $this->post->get_empresa($informe->empresa);
					$fecha = explode(' ', $informe->fecha);
					$fecha1 = explode('-', $fecha[0]);
					$fecha = $fecha1[2]."/".$fecha1[1]."/".$fecha1[0]." ".$fecha[1];
					$usuario = $this->post->get_creador($informe->usuario);
					
					$tabla_informes.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000">';

					if($informe->empresa == 0){
						$tabla_informes.='<td>TODOS</td>';
					}else{
						$tabla_informes.='<td>'.$empresa->empresa.'</td>';
					}
						$tabla_informes.='<td>'.$fecha.'</td>
										<td>'.$usuario.'</td>
										<td>
											<a target="_blank" style="padding: 2px 4px; margin: 0;" href="'.base_url('files/pdf/'.$informe->informe.'').'" type="button" class="btn btn-info" alt="Ver PDF" title="Ver PDF"><i class="fa fa-eye"></i></a>
											<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("eliminar_pdf/".$informe->id."").'" type="button" class="btn btn-danger" alt="Eliminar PDF" title="Eliminar PDF"><i class="fa fa-close"></i></a>
										</td>
									</tr>';
				}
				
				$data = array('title' => 'Administracion', 'html_empresas' => $html_empresas, 'tabla_informes' => $tabla_informes, 'html_supervisoras' => $html_supervisoras, 'fecha_inicio' => $fi, 'fecha_fin' => $ff);
    			$this->load_view('informes', $data);
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Generar informe PDF visitas */
	public function generar_informe_pdf(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$this->load->library('pdf');
				$pdf = $this->pdf->return_pdf($this->input->post('id_empresa'),$this->input->post('selec'),$this->session->userdata('logged_in')['id']);
				if($pdf){
					$this->informes($this->input->post('id_empresa'),$this->input->post('sp'),$this->input->post('fi'),$this->input->post('ff'));
				}
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Eliminar informe PDF */
	public function eliminar_pdf($id){
		$informe = $this->post->get_informe_visita($id);
		unlink(APPPATH."../tickets/files/pdf/".$informe->informe."");
		$resultado = $this->post->eliminar_informe_visita($id);
		if($resultado){
			$this->informes();
		}
	}
	
	/* Gestion de departamentos */
	public function departamentos(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){
				$this->gestion();
			}else{
				/* Obtener departamentos operadora */
				$tabla = '';
				$version_movil = '';
				$usuarios = $this->post->get_departamentos_op($this->session->userdata('logged_in')['acceso']);
				foreach($usuarios->result() as $usuario){
					$version_movil.='<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">
													 <div class="panel-heading" style="background: #449d44; text-align: center">
															<p style="color: #fff">'.$usuario->grupo.'</p>
													 </div>
													 <div class="panel-body" style="padding: 10px">
													 		<p><span style="font-weight: bold">Nombre: </span> '.$usuario->nombre.'</p>
															<p><span style="font-weight: bold">Email: </span> '.$usuario->email.'</p>';
															
					$tabla .= '<tr class="clickable-row" data-href="'.base_url('editar_departamento/'.$usuario->id.'').'">
											 <td>'.$usuario->grupo.'</td>
											 <td>'.$usuario->nombre.'</td>
											 <td>'.$usuario->email.'</td>';
					
					$tabla .= '<td>
											<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("borrar_departamento/".$usuario->id."").'" type="button" class="btn btn-danger" alt="Eliminar departamento" title="Eliminardepartamentoo"><i class="fa fa-close"></i></a>
										 </td>
										</tr>';
					
					$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('editar_departamento/'.$usuario->id).'" type="button" class="btn btn-info" alt="Editar usuario" title="Editar usuario"><i style="font-size: 30px" class="fa fa-edit"></i><span style="display: block; font-weight: bold">Editar</span></a>
													<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('borrar_departamento/'.$usuario->id).'" type="button" class="btn btn-danger" alt="Eliminar usuario" title="Eliminar usuario"><i style="font-size: 30px" class="fa fa-close"></i><span style="display: block; font-weight: bold">Eliminar</span></a>';
					
					$version_movil.='</div></div>';
					
				}
				
				$data = array('title' => '', 'tabla_usuarios' => $tabla, 'version_movil' => $version_movil);
				$this->load_view('departamentos', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nuevo_departamento(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){
				$this->gestion();
			}else{				
				$data = array('title' => '');
				$this->load_view('nuevo_departamento', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nuevo_departamento_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('grupo', 'Grupo', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('email', 'Email', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){				
			$data = array('title' => '');
			$this->load_view('nuevo_departamento', $data);
		}else{
			$resultado = $this->post->crear_departamento($this->input->post('nombre'),$this->input->post('email'),$this->input->post('grupo'),$this->session->userdata('logged_in')['acceso']);
			if($resultado){
				$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Crear Departamento');
				$this->departamentos();
			}else{
				$error_login = 'Ha ocurrido un error. Lo sentimos, pruebe de nuevo.';					
				$data = array('title' => '');
				$this->load_view('nuevo_departamento', $data);
			}	
		}
	}
	
	public function editar_departamento($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){
				$this->gestion();
			}else{
				$departamento = $this->post->get_departamento($id);		
				$data = array('title' => '', 'departamento' => $departamento, 'id' => $id);
				$this->load_view('editar_departamento', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_departamento_form(){
		$departamento = $this->post->get_departamento($this->input->post('id'));
		$data = array('title' => '');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('grupo', 'Grupo', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('email', 'Email', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){				
			$data = array('title' => '', 'id' => $id);
			$this->load_view('editar_departamento', $data);
		}else{
			$resultado = $this->post->editar_departamento($this->input->post('id'),$this->input->post('nombre'),$this->input->post('email'),$this->input->post('grupo'),$this->session->userdata('logged_in')['acceso']);
			if($resultado){
				$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Editar Departamento');
				$this->departamentos();
			}else{
				$error_login = 'Ha ocurrido un error. Lo sentimos, pruebe de nuevo.';					
				$data = array('title' => '', 'id' => $id);
				$this->load_view('editar_departamento', $data);
			}	
		}
	}
	
	/* Eliminar departamento */
	public function borrar_departamento($id){
		$borrar = $this->post->borrar_departamento($id);
		if($borrar){
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Eliminar Departamento');
			$this->departamentos();
		}
	}
	
	/* Gestion de usuarios */
	public function usuarios($pag){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){
				$this->gestion();
			}else{
				/* filtro salones */
				$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				$html_salones='';
				$html_salones .= '<option value="0">TODOS</value>';
				foreach($salones->result() as $salon){
					$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
				}

				/* filtro roles */
				$html_roles = '';
				$html_roles .= '<option value="0">TODOS</value>';
				$html_roles .= '<option value="2">Jefe de Técnicos</value>';
				$html_roles .= '<option value="3">Encargado Salón</value>';
				$html_roles .= '<option value="4">Técnico SAT</value>';
				$html_roles .= '<option value="5">Gerente</value>';
				$html_roles .= '<option value="6">Comercial/Supervisor</value>';
				$html_roles .= '<option value="7">Administración</value>';
				
				/* Obtener usuarios operadora */
				$tabla = '';
				$version_movil = '';
				
				/* Total usuarios */
				$total_usuarios = $this->post->get_usuarios_total($this->session->userdata('logged_in')['acceso']);
				$num_usuarios = $total_usuarios->num_rows();
				
				//Limito la busqueda
				$TAMANO_PAGINA = 10;

				//examino la página a mostrar y el inicio del registro a mostrar
				$pagina = $pag;
				if (!$pagina) {
				   $inicio = 0;
				   $pagina = 1;
				}
				else {
				   $inicio = ($pagina - 1) * $TAMANO_PAGINA;
				}
				//calculo el total de páginas
				$total_paginas = ceil($num_usuarios / $TAMANO_PAGINA);

				/* Usuarios página */
				$usuarios = $this->post->get_usuarios($this->session->userdata('logged_in')['acceso'],$inicio,$TAMANO_PAGINA);
				foreach($usuarios->result() as $usuario){
					
					/* Excepcion MARIN id operadora = id salon venecia BIMASER */
					if($this->session->userdata('logged_in')['acceso'] == 12){
						$nombre = explode('.', $usuario->nombre);
						if(isset($nombre[0]) && ($nombre[0] == 'DOLORES' || $nombre[0] == 'VENECIA')){
							continue;
						}
					}
					
					if($this->session->userdata('logged_in')['acceso'] == 4){
						$nombre = explode('_', $usuario->usuario);
						if(isset($nombre[1]) && $nombre[1] == 'marin'){
							continue;
						}
						$nombre = explode('.', $usuario->usuario);
						if(isset($nombre[1]) && $nombre[1] == 'marin'){
							continue;
						}
					}
					/* ----- */
					
					$version_movil.='<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">
													 <div class="panel-heading" style="background: #449d44; text-align: center">
															<p style="color: #fff">'.$usuario->nombre.'</p>
													 </div>
													 <div class="panel-body" style="padding: 10px">
															<p><span style="font-weight: bold">Email: </span> '.$usuario->email.'</p>
													 		<p><span style="font-weight: bold">Teléfono: </span> '.$usuario->telefono.'</p>
													 		<p><span style="font-weight: bold">Usuario: </span> '.$usuario->usuario.'</p>';
															
					$tabla .= '<tr class="clickable-row" data-href="'.base_url('editar_usuario/'.$usuario->id.'').'">
											 <td>'.$usuario->nombre.'</td>
											 <td>'.$usuario->email.'</td>
											 <td>'.$usuario->telefono.'</td>
											 <td>'.$usuario->usuario.'</td>';
											 
					if($usuario->rol == 1){
						$tabla .= '<td>ATC</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> ATC</p>';
					}else if($usuario->rol == 2){
						$tabla .= '<td>Jefe de Técnicos</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Jefe de Técnicos</p>';
					}else if($usuario->rol == 3){
						$tabla .= '<td>Encargado salón</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Encargado salón</p>';
					}else if($usuario->rol == 4){
						$tabla .= '<td>Técnico SAT</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Técnico SAT</p>';
					}else if($usuario->rol == 5){
						$tabla .= '<td>Gerente</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Gerente</p>';
					}else if($usuario->rol == 6){
						$tabla .= '<td>Comercial/Supervisor</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Comercial/Supervisor</p>';
					}else if($usuario->rol == 7){
						$tabla .= '<td>Administración</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Administración</p>';
					}else if($usuario->rol == 8){
						$tabla .= '<td>Marketing</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Marketing</p>';
					}else if($usuario->rol == 9){
						$tabla .= '<td>Marketing</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Online</p>';
					}

					if($usuario->activo == 1){
						$tabla .= '<td style="color: green; font-weight: bold">Activo</td>';
						$version_movil.='<p><span style="font-weight: bold">Estado: </span> Activo</p>';
					}else{
						$tabla .= '<td style="color: red; font-weight: bold">Inactivo</td>';
						$version_movil.='<p><span style="font-weight: bold">Estado: </span> Inactivo</p>';
					}

					if($usuario->activo == 1){					
						$tabla .= '<td>
									<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("borrar_usuario/".$usuario->id."").'" type="button" class="btn btn-danger" alt="Eliminar usuario" title="Eliminar usuario"><i class="fa fa-close"></i></a>
								 </td>
								</tr>';
					}else{
						$tabla .= '<td>
									<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("activar_usuario/".$usuario->id."").'" type="button" class="btn btn-success" alt="Activar usuario" title="Activar usuario"><i class="fa fa-play"></i></a>
								 </td>
								</tr>';
					}
					
					$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('editar_usuario/'.$usuario->id).'" type="button" class="btn btn-info" alt="Editar usuario" title="Editar usuario"><i style="font-size: 30px" class="fa fa-edit"></i><span style="display: block; font-weight: bold">Editar</span></a>';

					if($usuario->activo == 1){
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('borrar_usuario/'.$usuario->id).'" type="button" class="btn btn-danger" alt="Eliminar usuario" title="Eliminar usuario"><i style="font-size: 30px" class="fa fa-close"></i><span style="display: block; font-weight: bold">Eliminar</span></a>';
					}else{
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_usuario/'.$usuario->id).'" type="button" class="btn btn-success" alt="Activar usuario" title="Activar usuario"><i style="font-size: 30px" class="fa fa-play"></i><span style="display: block; font-weight: bold">Activar</span></a>';
					}
					
					$version_movil.='</div></div>';
					
				}
				
				$data = array('title' => '', 'tabla_usuarios' => $tabla, 'version_movil' => $version_movil, 'paginas' => $total_paginas, 'pagina' => $pagina, 'html_salones' => $html_salones, 'html_roles' => $html_roles);
				$this->load_view('usuarios', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function buscador_usuarios(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){
				$this->gestion();
			}else{
				/* filtro salones */
				$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				$html_salones='';
				if($this->input->post('salon') == 0){
					$html_salones .= '<option value="0" selected>TODOS</option>';
				}else{
					$html_salones .= '<option value="0">TODOS</option>';
				}
				foreach($salones->result() as $salon){
					if($salon->id == $this->input->post('salon')){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}

				/* filtro roles */
				$html_roles = '';
				if($this->input->post('rol') == 0){
					$html_roles .= '<option value="0" selected>TODOS</option>';
				}else{
					$html_roles .= '<option value="0">TODOS</option>';
				}
				if($this->input->post('rol') == 2){
					$html_roles .= '<option value="2" selected>Jefe de Técnicos</value>';
				}else{
					$html_roles .= '<option value="2">Jefe de Técnicos</value>';
				}
				if($this->input->post('rol') == 3){
					$html_roles .= '<option value="3" selected>Encargado Salón</value>';
				}else{
					$html_roles .= '<option value="3">Encargado Salón</value>';
				}
				if($this->input->post('rol') == 4){
					$html_roles .= '<option value="4" selected>Técnico SAT</value>';
				}else{
					$html_roles .= '<option value="4">Técnico SAT</value>';
				}
				if($this->input->post('rol') == 5){
					$html_roles .= '<option value="5" selected>Gerente</value>';
				}else{
					$html_roles .= '<option value="5">Gerente</value>';
				}
				if($this->input->post('rol') == 6){
					$html_roles .= '<option value="6" selected>Comercial/Supervisor</value>';
				}else{
					$html_roles .= '<option value="6">Comercial/Supervisor</value>';
				}
				if($this->input->post('rol') == 7){
					$html_roles .= '<option value="7" selected>Administración</value>';
				}else{
					$html_roles .= '<option value="7">Administración</value>';
				}
					
				/* Obtener usuarios operadora */
				$tabla = '';
				$version_movil = '';
				
				/* Total usuarios */
				$total_usuarios = $this->post->get_usuarios_total($this->session->userdata('logged_in')['acceso']);
				$num_usuarios = $total_usuarios->num_rows();
				
				//Limito la busqueda
				$TAMANO_PAGINA = 50;

				//examino la página a mostrar y el inicio del registro a mostrar
				$inicio = 0;
				$pagina = 1;

				//calculo el total de páginas
				$total_paginas = 1;

				/* Usuarios página */
				$usuarios = $this->post->get_usuarios_salon($this->session->userdata('logged_in')['acceso'],$this->input->post('rol'),$this->input->post('salon'),$inicio,$TAMANO_PAGINA);
				foreach($usuarios->result() as $usuario){
					
					/* Excepcion CAGADA MARIN id operadora = id salon venecia BIMASER */
					if($this->session->userdata('logged_in')['acceso'] == 12){
						$nombre = explode('.', $usuario->nombre);
						if(isset($nombre[0]) && ($nombre[0] == 'DOLORES' || $nombre[0] == 'VENECIA')){
							continue;
						}
					}
					
					if($this->session->userdata('logged_in')['acceso'] == 4){
						$nombre = explode('_', $usuario->usuario);
						if(isset($nombre[1]) && $nombre[1] == 'marin'){
							continue;
						}
					}
					/* ----- */
					
					$version_movil.='<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">
													 <div class="panel-heading" style="background: #449d44; text-align: center">
															<p style="color: #fff">'.$usuario->nombre.'</p>
													 </div>
													 <div class="panel-body" style="padding: 10px">
															<p><span style="font-weight: bold">Email: </span> '.$usuario->email.'</p>
													 		<p><span style="font-weight: bold">Teléfono: </span> '.$usuario->telefono.'</p>
													 		<p><span style="font-weight: bold">Usuario: </span> '.$usuario->usuario.'</p>';
															
					$tabla .= '<tr class="clickable-row" data-href="'.base_url('editar_usuario/'.$usuario->id.'').'">
											 <td>'.$usuario->nombre.'</td>
											 <td>'.$usuario->email.'</td>
											 <td>'.$usuario->telefono.'</td>
											 <td>'.$usuario->usuario.'</td>';
											 
					if($usuario->rol == 1){
						$tabla .= '<td>ATC</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> ATC</p>';
					}else if($usuario->rol == 2){
						$tabla .= '<td>Jefe de Técnicos</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Jefe de Técnicos</p>';
					}else if($usuario->rol == 3){
						$tabla .= '<td>Encargado salón</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Encargado salón</p>';
					}else if($usuario->rol == 4){
						$tabla .= '<td>Técnico SAT</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Técnico SAT</p>';
					}else if($usuario->rol == 5){
						$tabla .= '<td>Gerente</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Gerente</p>';
					}else if($usuario->rol == 6){
						$tabla .= '<td>Comercial/Supervisor</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Comercial/Supervisor</p>';
					}else if($usuario->rol == 7){
						$tabla .= '<td>Administración</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Administración</p>';
					}else if($usuario->rol == 8){
						$tabla .= '<td>Marketing</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Marketing</p>';
					}else if($usuario->rol == 9){
						$tabla .= '<td>Marketing</td>';
						$version_movil.='<p><span style="font-weight: bold">Rol: </span> Online</p>';
					}

					if($usuario->activo == 1){
						$tabla .= '<td style="color: green; font-weight: bold">Activo</td>';
						$version_movil.='<p><span style="font-weight: bold">Estado: </span> Activo</p>';
					}else{
						$tabla .= '<td style="color: red; font-weight: bold">Inactivo</td>';
						$version_movil.='<p><span style="font-weight: bold">Estado: </span> Inactivo</p>';
					}
					
					if($usuario->activo == 1){					
						$tabla .= '<td>
									<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("borrar_usuario/".$usuario->id."").'" type="button" class="btn btn-danger" alt="Eliminar usuario" title="Eliminar usuario"><i class="fa fa-close"></i></a>
								 </td>
								</tr>';
					}else{
						$tabla .= '<td>
									<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("activar_usuario/".$usuario->id."").'" type="button" class="btn btn-success" alt="Activar usuario" title="Activar usuario"><i class="fa fa-play"></i></a>
								 </td>
								</tr>';
					}
					
					$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('editar_usuario/'.$usuario->id).'" type="button" class="btn btn-info" alt="Editar usuario" title="Editar usuario"><i style="font-size: 30px" class="fa fa-edit"></i><span style="display: block; font-weight: bold">Editar</span></a>';

					if($usuario->activo == 1){
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('borrar_usuario/'.$usuario->id).'" type="button" class="btn btn-danger" alt="Eliminar usuario" title="Eliminar usuario"><i style="font-size: 30px" class="fa fa-close"></i><span style="display: block; font-weight: bold">Eliminar</span></a>';
					}else{
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_usuario/'.$usuario->id).'" type="button" class="btn btn-success" alt="Activar usuario" title="Activar usuario"><i style="font-size: 30px" class="fa fa-play"></i><span style="display: block; font-weight: bold">Activar</span></a>';
					}
					
					$version_movil.='</div></div>';
					
				}
				
				$data = array('title' => '', 'tabla_usuarios' => $tabla, 'version_movil' => $version_movil, 'paginas' => $total_paginas, 'pagina' => $pagina, 'html_salones' => $html_salones, 'html_roles' => $html_roles);
				$this->load_view('usuarios', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nuevo_usuario(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){
				$this->gestion();
			}else{
				/* Get roles */
				$html_roles = '';
				$html_roles .= '<option value="2">Jefe de Técnicos</value>';
				$html_roles .= '<option value="3">Encargado Salón</value>';
				$html_roles .= '<option value="4">Técnico SAT</value>';
				
				$data = array('title' => '', 'html_roles' => $html_roles);
				$this->load_view('nuevo_usuario', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nuevo_usuario_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('usuario', 'Apellidos', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('email', 'Email', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('pass', 'Contraseña', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
			/* Get roles */
			$html_roles = '';
			$html_roles .= '<option value="2">Jefe de Técnicos</value>';
			$html_roles .= '<option value="3">Encargado Salón</value>';
			$html_roles .= '<option value="4">Técnico SAT</value>';
				
			$data = array('title' => '', 'html_roles' => $html_roles);
			$this->load_view('nuevo_usuario', $data);
		}else{
			if($this->input->post('acceso') == 0){
				$acceso = $this->session->userdata('logged_in')['acceso'];
			}else{
				$acceso = $this->input->post('acceso');
			}
			if($this->input->post('jornada') == 'on'){
		    	$jornada = 1;
		    }else{
		    	$jornada = 0;
		    }
			$arr = array();
			if($this->input->post('lunes') == 'on'){
		    	array_push($arr, 1);
		    }
		    if($this->input->post('martes') == 'on'){
		    	array_push($arr, 2);
		    }
		    if($this->input->post('miercoles') == 'on'){
		    	array_push($arr, 3);
		    }
		    if($this->input->post('jueves') == 'on'){
		    	array_push($arr, 4);
		    }
		    if($this->input->post('viernes') == 'on'){
		    	array_push($arr, 5);
		    }
		    if($this->input->post('sabado') == 'on'){
		    	array_push($arr, 6);
		    }
		    if($this->input->post('domingo') == 'on'){
		    	array_push($arr, 7);
		    }
		    $dias = implode(",", $arr);
			$resultado = $this->post->crear_usuario($this->input->post('nombre'),$this->input->post('email'),$this->input->post('telefono'),$this->input->post('usuario'),$this->input->post('pass'),$this->input->post('rol'),$acceso,$jornada,$dias,$this->input->post('hora_inicio_jornada_mañana'),$this->input->post('hora_fin_jornada_mañana'),$this->input->post('hora_inicio_jornada_tarde'),$this->input->post('hora_fin_jornada_tarde'));
			if($resultado){
				$this->usuarios('1');
			}else{
				$error_login = 'Ha ocurrido un error. Ya existe un usuario con ese nombre o email. Si tiene problemas con el inicio de sesión, pruebe a recuperar contraseña en el login de inicio o contacte con nosotros.';
				$html_roles = '';
				$html_roles .= '<option value="2">Jefe de Técnicos</value>';
				$html_roles .= '<option value="3">Encargado Salón</value>';
				$html_roles .= '<option value="4">Técnico SAT</value>';
					
				$data = array('title' => '', 'html_roles' => $html_roles, 'error_login' => $error_login);
				$this->load_view('nuevo_usuario', $data);
			}
		}
	}
	
	public function editar_usuario($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){
				$this->gestion();
			}else{
				/* Get usuario */
				$usuario = $this->post->get_usuario($id);
				
				/* Get roles */
				$html_roles = '';
				$html_acceso = '';
				if($usuario->rol == 2){
					$html_roles .= '<option value="2" selected>Jefe de Técnicos</value>';
					$html_roles .= '<option value="3">Encargado Salón</value>';
					$html_roles .= '<option value="4">Técnico SAT</value>';					
				}else if($usuario->rol == 3){
					$html_roles .= '<option value="2">Jefe de Técnicos</value>';
					$html_roles .= '<option value="3" selected>Encargado Salón</value>';
					$html_roles .= '<option value="4">Técnico SAT</value>';
					
					$accesos = $this->post->get_acceso($this->session->userdata('logged_in')['acceso']);
					foreach($accesos->result() as $acceso){
						if($acceso->id == $usuario->acceso){
							$html_acceso .= '<option value="'.$acceso->id.'" selected>'.$acceso->salon.'</option>';
						}else{
							$html_acceso .= '<option value="'.$acceso->id.'">'.$acceso->salon.'</option>';
						}
					}
				}else if($usuario->rol == 4){
					$html_roles .= '<option value="2">Jefe de Técnicos</value>';
					$html_roles .= '<option value="3">Encargado Salón</value>';
					$html_roles .= '<option value="4" selected>Técnico SAT</value>';
				}
			
				$data = array('title' => '', 'html_roles' => $html_roles, 'html_acceso' => $html_acceso, 'id' => $id, 'usuario' => $usuario);
				$this->load_view('editar_usuario', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_usuario_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('usuario', 'Apellidos', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('email', 'Email', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('pass', 'Contraseña', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
			/* Get usuario */
			$usuario = $this->post->get_usuario($this->input->post('id'));
				
			/* Get roles */
			$html_roles = '';
			$html_acceso = '';
			if($usuario->rol == 2){
				$html_roles .= '<option value="2" selected>Jefe de Técnicos</value>';
				$html_roles .= '<option value="3">Encargado Salón</value>';
				$html_roles .= '<option value="4">Técnico SAT</value>';
			}else if($usuario->rol == 3){
				$html_roles .= '<option value="2">Jefe de Técnicos</value>';
				$html_roles .= '<option value="3" selected>Encargado Salón</value>';
				$html_roles .= '<option value="4">Técnico SAT</value>';
				
				$accesos = $this->post->get_acceso($this->session->userdata('logged_in')['acceso']);
				foreach($accesos->result() as $acceso){
					if($acceso->id == $usuario->acceso){
						$html_acceso .= '<option value="'.$acceso->id.'" selected>'.$acceso->salon.'</option>';
					}else{
						$html_acceso .= '<option value="'.$acceso->id.'">'.$acceso->salon.'</option>';
					}
				}
			}else if($usuario->rol == 4){
				$html_roles .= '<option value="2">Jefe de Técnicos</value>';
				$html_roles .= '<option value="3">Encargado Salón</value>';
				$html_roles .= '<option value="4" selected>Técnico SAT</value>';
			}
				
			$data = array('title' => '', 'html_roles' => $html_roles, 'html_acceso' => $html_acceso, 'id' => $this->input->post('id'), 'usuario' => $usuario);
			$this->load_view('editar_usuario', $data);
		}else{
			if($this->input->post('acceso') == 0){
				$acceso = $this->session->userdata('logged_in')['acceso'];
			}else{
				$acceso = $this->input->post('acceso');
			}
			if($this->input->post('jornada') == 'on'){
		    	$jornada = 1;
		    }else{
		    	$jornada = 0;
		    }
			$arr = array();
			if($this->input->post('lunes') == 'on'){
		    	array_push($arr, 1);
		    }
		    if($this->input->post('martes') == 'on'){
		    	array_push($arr, 2);
		    }
		    if($this->input->post('miercoles') == 'on'){
		    	array_push($arr, 3);
		    }
		    if($this->input->post('jueves') == 'on'){
		    	array_push($arr, 4);
		    }
		    if($this->input->post('viernes') == 'on'){
		    	array_push($arr, 5);
		    }
		    if($this->input->post('sabado') == 'on'){
		    	array_push($arr, 6);
		    }
		    if($this->input->post('domingo') == 'on'){
		    	array_push($arr, 7);
		    }
		    $dias = implode(",", $arr);
		    if($this->input->post('activo') == 'on'){
		    	$activo = 1;
		    }else{
		    	$activo = 0;
		    }
			$resultado = $this->post->editar_usuario($this->input->post('id'),$this->input->post('nombre'),$this->input->post('email'),$this->input->post('telefono'),$this->input->post('usuario'),$this->input->post('pass'),$this->input->post('rol'),$acceso,$jornada,$dias,$this->input->post('hora_inicio_jornada_mañana'),$this->input->post('hora_fin_jornada_mañana'),$this->input->post('hora_inicio_jornada_tarde'),$this->input->post('hora_fin_jornada_tarde'),$activo);
			if($resultado){
				$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Editar Usuario');
				$this->usuarios('1');
			}else{
				$error_login = 'Ha ocurrido un error. Ya existe un usuario con esa dirección de correo o teléfono. Lo sentimos, pruebe de nuevo.';				
				/* Get usuario */
				$usuario = $this->post->get_usuario($this->input->post('id'));
					
				/* Get roles */
				$html_roles = '';
				$html_acceso = '';
				if($usuario->rol == 2){
					$html_roles .= '<option value="2" selected>Jefe de Técnicos</value>';
					$html_roles .= '<option value="3">Encargado Salón</value>';
					$html_roles .= '<option value="4">Técnico SAT</value>';
				}else if($usuario->rol == 3){
					$html_roles .= '<option value="2">Jefe de Técnicos</value>';
					$html_roles .= '<option value="3" selected>Encargado Salón</value>';
					$html_roles .= '<option value="4">Técnico SAT</value>';
					
					$accesos = $this->post->get_acceso($this->session->userdata('logged_in')['acceso']);
					foreach($accesos->result() as $acceso){
						if($acceso->id == $usuario->acceso){
							$html_acceso .= '<option value="'.$acceso->id.'" selected>'.$acceso->salon.'</option>';
						}else{
							$html_acceso .= '<option value="'.$acceso->id.'">'.$acceso->salon.'</option>';
						}
					}
				}else if($usuario->rol == 4){
					$html_roles .= '<option value="2">Jefe de Técnicos</value>';
					$html_roles .= '<option value="3">Encargado Salón</value>';
					$html_roles .= '<option value="4" selected>Técnico SAT</value>';
				}
					
				$data = array('title' => '', 'html_roles' => $html_roles, 'html_acceso' => $html_acceso, 'id' => $this->input->post('id'), 'usuario' => $usuario, 'error_login' => $error_login);
				$this->load_view('editar_usuario', $data);
			}	
		}
	}
	
	/* Eliminar usuario */
	public function borrar_usuario($id){
		$borrar = $this->post->borrar_usuario($id);
		if($borrar){
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Eliminar Usuario');
			$this->usuarios('1');
		}
	}

	/* Activar usuario */
	public function activar_usuario($id){
		$borrar = $this->post->activar_usuario($id);
		if($borrar){
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Activar Usuario');
			$this->usuarios('1');
		}
	}
	
	/* Crear nueva incidencia */
	public function nueva($op_centralita=NULL,$salon_centralita=NULL,$cliente_centralita=NULL,$cliente_telefono=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 7){
				$this->gestion();
			}else{
				/* Select empresas & operadoras */
				$empresas = $this->post->get_empresas();
				$html_empresas='';
				$html_operadoras = '';
				
				if(($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['rol'] == 2){
					$empresa_op = $this->post->get_empresa_rol_2($this->session->userdata('logged_in')['acceso']);
					foreach($empresas->result() as $empresa){
						if($empresa_op->empresa == $empresa->id){
							$html_empresas.='<option value="'.$empresa->id.'" selected>'.$empresa->empresa.'</option>';
						}		
					}
					$operadoras = $this->post->get_operadoras_empresa($empresa_op->empresa);
					if($operadoras->num_rows() == 1){
						foreach($operadoras->result() as $operadora){
							$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
						}
					}else{
						$i = 0;
						foreach($operadoras->result() as $operadora){
							if($operadora->id == 24){
								$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
							}else{
								if($i == 0){
									$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
								}else{
									$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
								}
							}
							$i++;
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 3){
					$empresa_salon = $this->post->get_empresa_rol_3($this->session->userdata('logged_in')['acceso']);
					foreach($empresas->result() as $empresa){
						if($empresa_salon->empresa == $empresa->id){
							$html_empresas.='<option value="'.$empresa->id.'" selected>'.$empresa->empresa.'</option>';
						}			
					}
				}else if($this->session->userdata('logged_in')['rol'] == 1){
					if(isset($op_centralita) && $op_centralita != ''){
						$operadora = $this->post->get_operadoras_rol_2($op_centralita);
						$op = $operadora->row();
						foreach($empresas->result() as $empresa){
							if($empresa->id == $op->empresa){
								$html_empresas.='<option value="'.$empresa->id.'" selected>'.$empresa->empresa.'</option>';
							}else{
								$html_empresas.='<option value="'.$empresa->id.'">'.$empresa->empresa.'</option>';
							}
						}

						$operadoras = $this->post->get_operadoras_rol_2($op_centralita);
						if($operadoras->num_rows() == 1){
							foreach($operadoras->result() as $operadora){
								$html_operadoras.= '<option value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
							}
						}else{
							foreach($operadoras->result() as $operadora){
								if($operadora->id == 24){
									$html_operadoras.= '<option value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
								}else{
									$html_operadoras.= '<option value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
								}
							}
						}
					}else{
						foreach($empresas->result() as $empresa){					
							$html_empresas.='<option value="'.$empresa->id.'">'.$empresa->empresa.'</option>';
						}
					}
				}else{
					foreach($empresas->result() as $empresa){					
						$html_empresas.='<option value="'.$empresa->id.'">'.$empresa->empresa.'</option>';
					}
				}
				
				/* Select situacion */
				$situaciones = $this->post->get_situaciones();
				$html_situacion='';
				if(($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 3){
					$html_situacion.='<option value="2" selected>Pendiente SAT</option>';					
				}else{
					foreach($situaciones->result() as $situacion){
						if($situacion->id == 9){
							continue;
						}
						if($situacion->id == 6){
							$html_situacion.='<option value="'.$situacion->id.'">Cerrada</option>';
						}else{
							$html_situacion.='<option value="'.$situacion->id.'">'.$situacion->situacion.'</option>';
						}
					}
				}
				
				/* Select salones */
				if($this->session->userdata('logged_in')['rol'] == 1){
					if(isset($salon_centralita) && $salon_centralita != ''){
						$salones = $this->post->get_salones_operadora_averias($op_centralita);
						$html_salones='';
						foreach($salones->result() as $salon){
							if($salon_centralita == $salon->id){
								$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
							}else{
								$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
							}
						}
					}else{
						if(isset($op_centralita) && $op_centralita != ''){
							$salones = $this->post->get_salones_operadora_averias($op_centralita);
						}else{
							$salones = $this->post->get_salones();
						}
						$html_salones='';
						foreach($salones->result() as $salon){
							$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}					
				}else if(($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$salones = $this->post->get_salones_averias();
					$html_salones='';
					foreach($salones->result() as $salon){
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
					$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
					$html_salones='';
					foreach($salones->result() as $salon){
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}else if($this->session->userdata('logged_in')['rol'] == 3){
					$salon = $this->post->get_salones_rol_salon($this->session->userdata('logged_in')['acceso']);
					$html_salones='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					$telefono = $salon->telefono;
				}else{
					$html_salones='';
				}
				
				/* Tipo gestion averias rol 2 y 3 */
				$usuarios_salones_adm = $this->post->get_usuarios_salones_adm();
				$tipo_gestion = '';
				if(($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$tipo_gestion = '<option value="6">averias</option>';
				}else if($this->session->userdata('logged_in')['rol'] == 1){					
					$tipo_gestion = '<option value="6">averias</option>
									<option value="11">CIRSA</option>';
				}else if(in_array($this->session->userdata('logged_in')['id'], $usuarios_salones_adm)){
					$gestiones = $this->post->get_tipo_gestion();
					foreach($gestiones->result() as $gestion){
						if($gestion->id == 1 || $gestion->id == 5 || $gestion->id == 6 || $gestion->id == 11){
							continue;
						}else{
							$tipo_gestion .= '<option value="'.$gestion->id.'">'.$gestion->gestion.'</option>';	
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){
					$gestiones = $this->post->get_tipo_gestion();
					foreach($gestiones->result() as $gestion){
						if($gestion->id == 1 || $gestion->id == 5 || $gestion->id == 6 || $gestion->id == 11){
							continue;
						}else{
							$tipo_gestion .= '<option value="'.$gestion->id.'">'.$gestion->gestion.'</option>';	
						}
					}
				}
				
				/* Get operador/maquina - rol 3 */				
				if($this->session->userdata('logged_in')['rol'] == 3){
					$html_op='';
					$operadora = $this->post->get_salones_model($this->session->userdata('logged_in')['acceso']);
					foreach($operadora->result() as $opera){
						$html_op.='<option value="'.$opera->id.'" selected>'.$opera->operadora.'</option>';
					}
					
					$html_maquinas='';
					$maquinas = $this->post->get_maquinas($this->session->userdata('logged_in')['acceso'],1);
					foreach($maquinas->result() as $maquina){
						$html_maquinas.='<option value="'.$maquina->id.'">'.$maquina->maquina.'</option>';
					} 	
				}
				
				/* Select tipo cliente */
				$tipo_clientes = $this->post->get_tipo_cliente();
				$html_tipo_cliente = '';
				if($this->session->userdata('logged_in')['rol'] == 2){
						$html_tipo_cliente.='<option value="3" selected>Encargado operadora</option>';
				}else if($this->session->userdata('logged_in')['rol'] == 3){
						$html_tipo_cliente.='<option value="2" selected>Trabajador salón</option>';
				}else if($this->session->userdata('logged_in')['rol'] == 1){
					if(isset($cliente_centralita) && $cliente_centralita != ''){
						foreach($tipo_clientes->result() as $tipo_cliente){
							if($tipo_cliente->id == 2){
								$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'" selected>'.$tipo_cliente->tipo_cliente.'</option>';
							}else{
								$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'">'.$tipo_cliente->tipo_cliente.'</option>';
							}
						}
					}else if(isset($salon_centralita) && $salon_centralita != ''){
						foreach($tipo_clientes->result() as $tipo_cliente){
							if($tipo_cliente->id == 2){
								$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'" selected>'.$tipo_cliente->tipo_cliente.'</option>';
							}else{
								$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'">'.$tipo_cliente->tipo_cliente.'</option>';
							}
						}
					}else if(isset($op_centralita) && $op_centralita != ''){
						foreach($tipo_clientes->result() as $tipo_cliente){
							if($tipo_cliente->id == 4){
								$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'" selected>'.$tipo_cliente->tipo_cliente.'</option>';
							}else{
								$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'">'.$tipo_cliente->tipo_cliente.'</option>';
							}
						}
					}else{
						foreach($tipo_clientes->result() as $tipo_cliente){
							$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'">'.$tipo_cliente->tipo_cliente.'</option>';
						}
					}
				}else{
					foreach($tipo_clientes->result() as $tipo_cliente){
						$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'">'.$tipo_cliente->tipo_cliente.'</option>';
					}
				}

				/* Select nombre origen incidencias list input type text */
				$usuarios = $this->post->get_usuarios_todos();
				$html_list_nombre = '';
				foreach($usuarios->result() as $usuario){
					$html_list_nombre.='<option value="'.$usuario->usuario.'">';
				}
				
				/* Select tipo errores */
				$errores_tipo = $this->post->get_errores_tipo();
				$html_error_tipo='';
				foreach($errores_tipo->result() as $error_tipo){
					$html_error_tipo.='<option value="'.$error_tipo->id.'">'.$error_tipo->tipo.'</option>';
				}

				/* Cantidad tarjetas */
				$tarjetas_entregadas = $this->post->get_tarjetas();
				$total_tarjetas = 0;
				if($tarjetas_entregadas->num_rows() > 0){
					foreach($tarjetas_entregadas->result() as $entregadas){
						$total_tarjetas += $entregadas->cantidad_tarjetas;
					}
				}
				$html_cantidad_tarjetas = 600 - $total_tarjetas;
				
				/* Select departamentos */
				$departamentos = $this->post->get_departamentos();
				$html_departamento='';
				if($this->session->userdata('logged_in')['rol'] == 1){
						$destino = $this->post->get_destino_atc($this->session->userdata('logged_in')['acceso']);
						foreach($destino->result() as $destino){
							$html_departamento.='<option value="'.$destino->id.'">'.$destino->grupo.'</option>';					
						}
				}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
						$destino = $this->post->get_destino_op($this->session->userdata('logged_in')['acceso']);
						$html_departamento.='<option value="'.$destino->id.'" selected>'.$destino->nombre.'</option>';
				}else if($this->session->userdata('logged_in')['rol'] == 3){
						$destino = $this->post->get_destino_salon($this->session->userdata('logged_in')['acceso']);
						$html_departamento.='<option value="'.$destino->id.'" selected>'.$destino->nombre.'</option>';
				}else{
					foreach($departamentos->result() as $departamento){
						if($this->session->userdata('logged_in')['rol'] == 3 && $departamento->id == 4){
							$html_departamento.='<option value="'.$departamento->id.'" selected>'.$departamento->nombre.'</option>';
						}else{
							$html_departamento.='<option value="'.$departamento->id.'">'.$departamento->nombre.'</option>';
						}						
					}
				}
				
				/* Select fabricantes - añadir maquina */
				$fabris = $this->post->get_fabricantes();
				$html_fabricantes = '';
				foreach($fabris->result() as $fabri){
					$html_fabricantes .= '<option value="'.$fabri->id.'">'.$fabri->nombre.'</option>';
				}
				
				/* Select prioridad */
				$prioridad = $this->post->get_prioridad();
				$html_prioridad = '';
				foreach($prioridad->result() as $p){
					if($p->id == 1){
						$html_prioridad .= '<option value="'.$p->id.'" selected>'.$p->prioridad.'</option>';
					}else{
						$html_prioridad .= '<option value="'.$p->id.'">'.$p->prioridad.'</option>';
					}
				}
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$html_prioridad .= '<option value="3">Programar</option>';
				}
				
				if($this->session->userdata('logged_in')['rol'] == 3){
					$data = array('title' => '', 'html_empresas' => $html_empresas, 'html_situacion' => $html_situacion, 'html_salones' => $html_salones, 'tipo_gestion' => $tipo_gestion, 'html_tipo_cliente' => $html_tipo_cliente, 'html_error_tipo' => $html_error_tipo, 'html_departamento' => $html_departamento, 'html_op' => $html_op, 'telefono' => $telefono, 'html_maquinas' => $html_maquinas, 'html_fabricantes' => $html_fabricantes, 'html_prioridad' => $html_prioridad);
					$this->load_view('nueva_incidencia', $data);
				}else{
					$data = array('title' => '', 'html_empresas' => $html_empresas, 'html_operadoras' => $html_operadoras, 'html_situacion' => $html_situacion, 'html_salones' => $html_salones, 'tipo_gestion' => $tipo_gestion, 'html_tipo_cliente' => $html_tipo_cliente, 'html_error_tipo' => $html_error_tipo, 'html_cantidad_tarjetas' => $html_cantidad_tarjetas, 'html_departamento' => $html_departamento, 'html_fabricantes' => $html_fabricantes, 'html_prioridad' => $html_prioridad, 'op_centralita' => $op_centralita, 'salon_centralita' => $salon_centralita, 'cliente_centralita' => $cliente_centralita, 'cliente_telefono' => $cliente_telefono, 'html_list_nombre' => $html_list_nombre);
					$this->load_view('nueva_incidencia', $data);
				}
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nueva_incidencia_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('cliente_nombre', 'Nombre cliente', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('cliente_apellidos', 'Apellidos cliente', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('cliente_telefono', 'Teléfono cliente', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('cliente_email', 'Email cliente', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('error_desc', 'Descripción error', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('trata_des', 'Descripción tratamiento', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
			$data = array('title' => '');
			$this->load_view('nueva_incidencia', $data);
		}else{
			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			if($this->input->post('solucionada') == 'on'){
				$situacion = 6;
			}else{
				$situacion = $this->input->post('situacion');
			}
			/* Caso operadora distinta salon */
			$operadora = $this->input->post('operador');
			if(($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
				$op = $this->post->get_salon_completo($this->input->post('salon'));
				if($op->operadora != $this->input->post('operador')){
					$operadora = $op->operadora;
				}
			}
			/* Crear incidencia */
		    $img_name = array();
		    if($_FILES['error_imagen']['size'][0] != 0){			
				$totalImg = count($_FILES['error_imagen']['name']);
				for($i=0; $i<$totalImg; $i++){
					$ext = explode('.', basename($_FILES['error_imagen']['name'][$i]));
					$img_name[$i] = md5(uniqid()) . "." . array_pop($ext);
					$target = APPPATH."../tickets/files/img/errores/" . $img_name[$i];
					move_uploaded_file($_FILES['error_imagen']['tmp_name'][$i], $target);				
				}
			}else{
				$img_name[0] = '';
			}

		    $destino = $this->input->post('destino');
			$resultado = $this->post->crear_incidencia($this->input->post('empresa'),$situacion,$this->input->post('caduca_ticket'),$operadora,$this->input->post('salon'),$this->input->post('cliente_tipo'),$this->input->post('cliente_nombre'),$this->input->post('cliente_apellidos'),$this->input->post('cliente_telefono'),$this->input->post('cliente_email'),$this->input->post('gestion_tipo'),$this->input->post('error_maquina'),$this->input->post('error_tipo'),$this->input->post('error_detalle'),$this->input->post('cantidad_tarjetas'),$this->input->post('error_desc'),$destino,$this->input->post('trata_desc'), $fecha, $hora, $img_name, $this->input->post('importe_ticket'), $this->input->post('prioridad'), $this->input->post('fecha_programada'),$this->input->post('guia_maquina'), $this->input->post('direccion_entrega'), $this->input->post('telefono_entrega'));	
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'], 'Crear Ticket');
			$this->gestion($resultado);		
		}
	}
	
	/* Editar incidencia */
	public function editar_incidencia($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5){
				$this->gestion();
			}else{
				/* Recuperar ticket */
				$ticket = $this->post->get_ticket($id);
				/* Comprobar ticket manual */
				if($ticket->tipo_error == 62 || $ticket->tipo_error == 77 || $ticket->tipo_error == 113){
					$ticket_manual = $this->post->get_ticket_manual($id);
				}						
				/* Select empresas & operadoras */
				$empresas = $this->post->get_empresas();
				$html_empresas='';
				$html_operadoras = '';
				
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					foreach($empresas->result() as $empresa){
						if($empresa->id == $ticket->empresa){
							$html_empresas.='<option value="'.$empresa->id.'" selected>'.$empresa->empresa.'</option>';
						}else{
							$html_empresas.='<option value="'.$empresa->id.'">'.$empresa->empresa.'</option>';
						}											
					}
					$operadoras = $this->post->get_operadoras_empresa($ticket->empresa);
					if($operadoras->num_rows() == 1){
						foreach($operadoras->result() as $operadora){							
							$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
						}
					}else{
						foreach($operadoras->result() as $operadora){
							if($operadora->id == $ticket->operadora){
								$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
							}else{
								$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
							}
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 2){
					$empresa_op = $this->post->get_empresa_operadora($this->session->userdata('logged_in')['acceso']);
					$html_empresas.='<option value="'.$empresa_op->id.'" selected>'.$empresa_op->empresa.'</option>';

					$operadoras = $this->post->get_operadoras_empresa($empresa_op->id);
					if($operadoras->num_rows() == 1){
						foreach($operadoras->result() as $operadora){							
							$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
						}
					}else{
						foreach($operadoras->result() as $operadora){
							if($operadora->id == $ticket->operadora){
								$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
							}else{
								$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
							}
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 3){
					$empresa_salon = $this->post->get_empresa_rol_3($this->session->userdata('logged_in')['acceso']);
					foreach($empresas->result() as $empresa){
						if($empresa_salon->empresa == $ticket->empresa){
							$html_empresas.='<option value="'.$empresa->id.'" selected>'.$empresa->empresa.'</option>';
						}
					}
				}
				
				/* Select situacion */
				$situaciones = $this->post->get_situaciones();
				$html_situacion='';
				if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					if($ticket->situacion == 6){
						$html_situacion.='<option value="6" selected>Solucionada</option>';
						$html_situacion.='<option value="2">Pendiente SAT</option>';
						$html_situacion.='<option value="14">Pendiente Informáica</option>';
					}else if($ticket->situacion == 2){
						$html_situacion.='<option value="2" selected>Pendiente SAT</option>';
					}else if($ticket->situacion == 14){
						$html_situacion.='<option value="14" selected>Pendiente Informáica</option>';
					}
				}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 3){
					$html_situacion.='<option value="2" selected>Pendiente SAT</option>';
				}else{
					foreach($situaciones->result() as $situacion){
						if($situacion->id == 9){
							continue;
						}else{
							if($situacion->id == $ticket->situacion){
								if($situacion->id == 6){
									$html_situacion.='<option value="'.$situacion->id.'" selected>Cerrada</option>';
								}else{
									$html_situacion.='<option value="'.$situacion->id.'" selected>'.$situacion->situacion.'</option>';
								}
							}else{
								if($situacion->id == 6){
									if($ticket->destino == 1 && $ticket->situacion != 10){
										$html_situacion.='<option value="'.$situacion->id.'">Cerrada</option>';
									}
								}else{
									$html_situacion.='<option value="'.$situacion->id.'">'.$situacion->situacion.'</option>';
								}
							}
						}
					}
				}
				
				if($ticket->situacion == 6){
					$checked = "checked";
				}else{
					$checked = '';
				}
				
				/* Select salones */
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$salones = $this->post->get_salones_rol_op($ticket->operadora);
					$html_salones='';					
					foreach($salones->result() as $salon){
						if($salon->id == $ticket->salon){
							$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}						
					}
				}else if($this->session->userdata('logged_in')['rol'] == 2){
					$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
					$html_salones='';
					foreach($salones->result() as $salon){
						if($salon->id == $ticket->salon){
							$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}	
					}
				}else if($this->session->userdata('logged_in')['rol'] == 3){
					$salon = $this->post->get_salones_rol_salon($this->session->userdata('logged_in')['acceso']);
					$html_salones='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					$telefono = $salon->telefono;
				}
				
				/* Tipo gestion averias rol 2 y 3 */
				$tipo_gestion = '';
				if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					if($ticket->tipo_averia == '6'){
						$tipo_gestion = '<option value="6" selected>averias</option>
										<option value="3">MÁQUINAS</option>';
					}else if($ticket->tipo_averia == '3'){
						$tipo_gestion = '<option value="6">averias</option>
										<option value="3" selected>MÁQUINAS</option>';
					}else{
						$gestiones = $this->post->get_tipo_gestion();
						foreach($gestiones->result() as $gestion){
							if($gestion->id == 1 || $gestion->id == 5 || $gestion->id == 11){
								continue;
							}else{
								if($gestion->id == $ticket->tipo_averia){
									$tipo_gestion .= '<option value="'.$gestion->id.'" selected>'.$gestion->gestion.'</option>';
								}else{
									$tipo_gestion .= '<option value="'.$gestion->id.'">'.$gestion->gestion.'</option>';
								}								
							}
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 1){
					if($ticket->tipo_averia == '6'){
						$tipo_gestion = '<option value="6" selected>averias</option>
										<option value="3">MÁQUINAS</option>
										<option value="1">CIRSA</option>';
					}else if($ticket->tipo_averia == '3'){
						$tipo_gestion = '<option value="6">averias</option>
										<option value="3" selected>MÁQUINAS</option>
										<option value="1">CIRSA</option>';
					}else{
						$gestiones = $this->post->get_tipo_gestion();
						foreach($gestiones->result() as $gestion){
							if($gestion->id == 1 || $gestion->id == 5){
								continue;
							}else{
								if($gestion->id == $ticket->tipo_averia){
									$tipo_gestion .= '<option value="'.$gestion->id.'" selected>'.$gestion->gestion.'</option>';
								}else{
									$tipo_gestion .= '<option value="'.$gestion->id.'">'.$gestion->gestion.'</option>';
								}								
							}
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 2){
					$gestiones = $this->post->get_tipo_gestion();
					foreach($gestiones->result() as $gestion){
						if($gestion->id == 1 || $gestion->id == 5 || $gestion->id == 6 || $gestion->id == 11){
							continue;
						}else{
							if($gestion->id == $ticket->tipo_averia){
								$tipo_gestion .= '<option value="'.$gestion->id.'" selected>'.$gestion->gestion.'</option>';
							}else{
								$tipo_gestion .= '<option value="'.$gestion->id.'">'.$gestion->gestion.'</option>';
							}								
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 3){
					$gestiones = $this->post->get_tipo_gestion();
					foreach($gestiones->result() as $gestion){
						if($gestion->id == 1 || $gestion->id == 5 || $gestion->id == 6 || $gestion->id == 11){
							continue;
						}else{
							if($gestion->id == $ticket->tipo_averia){
								$tipo_gestion .= '<option value="'.$gestion->id.'" selected>'.$gestion->gestion.'</option>';
							}else{
								$tipo_gestion .= '<option value="'.$gestion->id.'">'.$gestion->gestion.'</option>';
							}								
						}
					}
				}
				
				/* Get operador/maquina - rol 3 */				
				if($this->session->userdata('logged_in')['rol'] == 3){
					$html_op='';
					$operadora = $this->post->get_salones_model($this->session->userdata('logged_in')['acceso']);
					foreach($operadora->result() as $opera){
						$html_op.='<option value="'.$opera->id.'" selected>'.$opera->operadora.'</option>';
					}
					
					$html_maquinas='';
					$maquinas = $this->post->get_maquinas($this->session->userdata('logged_in')['acceso'],1);
					foreach($maquinas->result() as $maquina){
						$html_maquinas.='<option value="'.$maquina->id.'">'.$maquina->maquina.'</option>';
					} 	
				}
				
				/* Select tipo cliente */
				$tipo_clientes = $this->post->get_tipo_cliente();
				$html_tipo_cliente = '';
				foreach($tipo_clientes->result() as $tipo_cliente){
					if($tipo_cliente->id == $ticket->cliente){
						$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'" selected>'.$tipo_cliente->tipo_cliente.'</option>';
					}else{
						$html_tipo_cliente.='<option value="'.$tipo_cliente->id.'">'.$tipo_cliente->tipo_cliente.'</option>';
					}
				}

				/* Select nombre origen incidencias list input type text */
				$usuarios = $this->post->get_usuarios_todos();
				$html_list_nombre = '';
				foreach($usuarios->result() as $usuario){
					$html_list_nombre.='<option value="'.$usuario->usuario.'">';
				}
				
				/* Select tipo errores */
				$errores_tipo = $this->post->get_error_gestion($ticket->tipo_averia);
				$html_error_tipo='';
				foreach($errores_tipo->result() as $error_tipo){
					if($error_tipo->id == $ticket->tipo_error){
						$html_error_tipo.='<option value="'.$error_tipo->id.'" selected>'.$error_tipo->tipo.'</option>';
					}else{
						$html_error_tipo.='<option value="'.$error_tipo->id.'">'.$error_tipo->tipo.'</option>';
					}
				}
				
				/* Select detalle errores */
				$errores_detalle = $this->post->get_error_detalle($ticket->tipo_error);
				$html_error_detalle='';
				foreach($errores_detalle->result() as $error_detalle){
					if($error_detalle->id == $ticket->detalle_error){
						$html_error_detalle.='<option value="'.$error_detalle->id.'" selected>'.$error_detalle->error_detalle.'</option>';
					}else{
						$html_error_detalle.='<option value="'.$error_detalle->id.'">'.$error_detalle->error_detalle.'</option>';
					}
				}
				
				/* Select máquina */
				$maquinas = $this->post->get_maquinas($ticket->salon,$ticket->tipo_averia);
				$html_maquinas = '';
				foreach($maquinas->result() as $maquina){
					if($maquina->id == $ticket->maquina){
						$html_maquinas.='<option value="'.$maquina->id.'" selected>'.$maquina->maquina.'</option>';
					}else{
						$html_maquinas.='<option value="'.$maquina->id.'">'.$maquina->maquina.'</option>';
					}
				}
				
				/* Select departamentos */
				$departamentos = $this->post->get_departamentos();
				$html_departamento='';
				if($ticket->situacion == 14){
					$html_departamento.='<option value="32" selected>Informática ADM</option>';
				}else if($this->session->userdata('logged_in')['rol'] == 1){
					$destino_actual = $this->post->get_destino_completo($ticket->destino);
					$html_departamento.='<option value="'.$destino_actual->id.'" selected>'.$destino_actual->grupo.'</option>';
					$destino = $this->post->get_destino_atc($this->session->userdata('logged_in')['acceso']);
					foreach($destino->result() as $destino){
						if($destino->id == $ticket->destino){
							$html_departamento.='<option value="'.$destino->id.'" selected>'.$destino->grupo.'</option>';
						}else{
							$html_departamento.='<option value="'.$destino->id.'">'.$destino->grupo.'</option>';
						}					
					}
				}else if($this->session->userdata('logged_in')['rol'] == 2){
					$destino = $this->post->get_destino_op($this->session->userdata('logged_in')['acceso']);
					$html_departamento.='<option value="'.$destino->id.'" selected>'.$destino->nombre.'</option>';
				}else if($this->session->userdata('logged_in')['rol'] == 3){
					$destino = $this->post->get_destino_salon($this->session->userdata('logged_in')['acceso']);
					$html_departamento.='<option value="'.$destino->id.'" selected>'.$destino->nombre.'</option>';
				}else{
					foreach($departamentos->result() as $departamento){
						if($departamento->id == $ticket->destino){
							$html_departamento.='<option value="'.$departamento->id.'" selected>'.$departamento->nombre.'</option>';
						}else{
							$html_departamento.='<option value="'.$departamento->id.'">'.$departamento->nombre.'</option>';
						}						
					}
				}
				
				/* Select prioridad */
				$prioridad = $this->post->get_prioridad();
				$html_prioridad = '';
				foreach($prioridad->result() as $p){
					if($p->id == $ticket->prioridad){
						$html_prioridad .= '<option value="'.$p->id.'" selected>'.$p->prioridad.'</option>';
					}else{
						$html_prioridad .= '<option value="'.$p->id.'">'.$p->prioridad.'</option>';
					}
				}
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					if($ticket->prioridad == 3){
						$html_prioridad .= '<option value="3" selected>Programar</option>';
					}else{
						$html_prioridad .= '<option value="3">Programar</option>';
					}
				}
				
				/* Select asignado */
				$asignados = $this->post->get_asignados($this->session->userdata('logged_in')['acceso']);
				//$html_asignado = '<option value="'.$this->session->userdata('logged_in')['id'].'">'.$this->session->userdata('logged_in')['user'].'</option>';
				$html_asignado = '';
				if($ticket->asignado == 0){
					$html_asignado .= '<option value="0" selected>Nadie</option>';
				}else{
					$asignado_actual = $this->post->get_asignado_actual($ticket->asignado);
					$html_asignado .= '<option value="'.$asignado_actual->id.'" selected>'.$asignado_actual->nombre.'</option>';
				}
				if($this->session->userdata('logged_in')['rol'] == 2){
					foreach($asignados->result() as $asignado){
						if($ticket->asignado != 0 && $asignado_actual->id == $asignado->id){
							continue;
						}else{
							$html_asignado .= '<option value="'.$asignado->id.'">'.$asignado->nombre.'</option>';
						}
					}
				}

				/* Transporte */
				if($ticket->detalle_error == 571 || $ticket->detalle_error == 582){
					$transporte = $this->post->get_transporte($ticket->id);
					if(!$transporte){
						$transporte = '';	
					}
				}else{
					$transporte = '';
				}
				
				/* HTML historial */
				$html_historial = '';
				$inicial = $this->post->get_edicion_inicial($ticket->id);
				$situacion = $this->post->get_situacion($inicial->situacion);
				$creador = $this->post->get_creador($ticket->creador);			
				$fecha = explode("-", $ticket->fecha_creacion);

				$html_historial = '';
				$html_historial .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
				
				$error_desc = stripslashes($ticket->error_desc);
				$trata_desc = stripslashes($ticket->trata_desc);
				
				if($inicial->situacion == 6){
					$html_historial.='<div class="panel-heading" style="background: #449d44; text-align: center">
															<p style="color: #fff">#'.$ticket->id.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$ticket->hora_creacion.'</p>
														</div>
														<div class="panel-body" style="padding: 10px">
															<p><span style="font-weight: bold">Situación:</span><span style="color: #449d44;"> '.$situacion.'</span></p>';					
				}else{
					$html_historial.='<div class="panel-heading" style="background: #b21a30; text-align: center">
															<p style="color: #fff">#'.$ticket->id.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$ticket->hora_creacion.'</p>
														</div>
														<div class="panel-body" style="padding: 10px">
															<p><span style="font-weight: bold">Situación:</span><span style="color: #b21a30;"> '.$situacion.'</span></p>';					
				}
																
				$html_historial.='<p><span style="font-weight: bold">Creador:</span> '.$creador.'</p>
																<p style="font-weight: bold">Descripción:</p>
																<p>'.$error_desc.'</p>';
																		
				if(!empty($ticket->trata_desc)){
					$html_historial .= '<p style="font-weight: bold">Tratamiento:</p>
															<p>'.$trata_desc.'</p>';
				}
				$html_historial .= '</div></div>';
				
				/* Obtener ediciones */
				$ediciones = $this->post->get_ediciones($ticket->id);
				if($ediciones->num_rows() > 0){
					foreach($ediciones->result() as $edicion){
						$edicion_trata_desc = stripslashes($edicion->trata_desc);
						$situacion = $this->post->get_situacion($edicion->situacion);
						$fecha = explode("-", $edicion->fecha_edicion);
						$creador = $this->post->get_creador($edicion->creador);					
						$html_historial .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
						
						if($edicion->situacion == 6){
							$html_historial.='<div class="panel-heading" style="background: #449d44; text-align: center">
																	<p style="color: #fff">#'.$edicion->id_ticket.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$edicion->hora_edicion.'</p>
																</div>
																<div class="panel-body" style="padding: 10px">
																	<p><span style="font-weight: bold">Situación:</span><span style="color: #449d44;"> '.$situacion.'</span></p>';					
						}else{
							$html_historial.='<div class="panel-heading" style="background: #b21a30; text-align: center">
																	<p style="color: #fff">#'.$edicion->id_ticket.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$edicion->hora_edicion.'</p>
																</div>
																<div class="panel-body" style="padding: 10px">
																	<p><span style="font-weight: bold">Situación:</span><span style="color: #b21a30;"> '.$situacion.'</span></p>';					
						}	
				
						$html_historial.='<p><span style="font-weight: bold">Creador:</span> '.$creador.'</p>
															<p style="font-weight: bold">Tratamiento:</p>
															<p>'.$edicion_trata_desc.'</p>';
						$html_historial .= '</div></div>';
					}
				}

				$fecha_tmp = explode("-", $ticket->fecha_creacion);
				$fecha_programada = $fecha_tmp[2]."/".$fecha_tmp[1]."/".$fecha_tmp[0]." ".$ticket->hora_creacion;

				if(isset($ticket_manual)){
					$data = array('title' => '', 'id_ticket' => $ticket->id, 'html_empresas' => $html_empresas, 'situacion' => $ticket->situacion, 'html_situacion' => $html_situacion, 'html_salones' => $html_salones, 'html_operadoras' => $html_operadoras, 'html_tipo_cliente' => $html_tipo_cliente, 'cliente_nombre' => $ticket->nombre, 'cliente_telefono' => $ticket->telefono, 'cliente_email' => $ticket->email, 'tipo_gestion' => $tipo_gestion, 'html_error_tipo' => $html_error_tipo, 'html_error_detalle' => $html_error_detalle, 'html_maquinas' => $html_maquinas, 'html_error_desc' => $error_desc, 'html_departamento' => $html_departamento, 'html_trata_desc' => $trata_desc, 'html_historial' => $html_historial, 'maquina_id' => $ticket->maquina, 'imagen' => $ticket->imagen, 'html_asignado' => $html_asignado, 'asignado' => $ticket->asignado, 'checked' => $checked, 'ticket_manual' => $ticket_manual, 'fecha_caducidad' => $ticket->fecha_caducidad, 'id_destino' => $ticket->destino, 'html_prioridad' => $html_prioridad, 'error_detalle' => $ticket->detalle_error, 'transporte' => $transporte, 'fecha_programada' => $fecha_programada, 'html_list_nombre' => $html_list_nombre, 'detalle_error' => $ticket->detalle_error, 'cantidad_tarjetas' => $ticket->cantidad_tarjetas);
				}else{
					$data = array('title' => '', 'id_ticket' => $ticket->id, 'html_empresas' => $html_empresas, 'situacion' => $ticket->situacion, 'html_situacion' => $html_situacion, 'html_salones' => $html_salones, 'html_operadoras' => $html_operadoras, 'html_tipo_cliente' => $html_tipo_cliente, 'cliente_nombre' => $ticket->nombre, 'cliente_telefono' => $ticket->telefono, 'cliente_email' => $ticket->email, 'tipo_gestion' => $tipo_gestion, 'html_error_tipo' => $html_error_tipo, 'html_error_detalle' => $html_error_detalle, 'html_maquinas' => $html_maquinas, 'html_error_desc' => $error_desc, 'html_departamento' => $html_departamento, 'html_trata_desc' => $trata_desc, 'html_historial' => $html_historial, 'maquina_id' => $ticket->maquina, 'imagen' => $ticket->imagen, 'html_asignado' => $html_asignado, 'asignado' => $ticket->asignado, 'checked' => $checked, 'fecha_caducidad' => $ticket->fecha_caducidad, 'id_destino' => $ticket->destino, 'html_prioridad' => $html_prioridad, 'error_detalle' => $ticket->detalle_error, 'transporte' => $transporte, 'fecha_programada' => $fecha_programada, 'html_list_nombre' => $html_list_nombre, 'detalle_error' => $ticket->detalle_error, 'cantidad_tarjetas' => $ticket->cantidad_tarjetas);
				}
				$this->load_view('editar_incidencia', $data);
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_incidencia_form($sql=NULL,$agrupar=NULL,$columna=NULL){
		if($this->session->userdata('logged_in')){
			$data = array('title' => '');
			$this->form_validation->set_rules('cliente_nombre', 'Nombre cliente', 'trim|htmlspecialchars');
			$this->form_validation->set_rules('cliente_apellidos', 'Apellidos cliente', 'trim|htmlspecialchars');
			$this->form_validation->set_rules('cliente_telefono', 'Teléfono cliente', 'trim|htmlspecialchars');
			$this->form_validation->set_rules('cliente_email', 'Email cliente', 'trim|htmlspecialchars');
			$this->form_validation->set_rules('error_desc', 'Descripción error', 'trim|htmlspecialchars');
			$this->form_validation->set_rules('trata_desc', 'Descripción tratamiento', 'trim|htmlspecialchars');
			if ($this->form_validation->run() == FALSE){
				$data = array('title' => '');
				$this->load_view('editar_incidencia', $data);
			}else{
				if(isset($_POST['only_trata'])){			
					$this->form_validation->set_rules('trata_new_desc', 'Tratamiento dado a la incidencia', 'required|trim|htmlspecialchars');
					if ($this->form_validation->run() == FALSE){
						$data = array('title' => '', 'id' => $this->input->post('id_ticket'));
						$this->editar_incidencia($this->input->post('id_ticket'));
					}else{						
						$fecha = date('Y-m-d');
						$hora = date('H:i:s');
						$img_name = '';
						$peri_ant = $peri_nue = 0;
						$ticket = $this->post->solucionar_ticket($this->input->post('situacion'), $this->input->post('id_ticket'), $this->input->post('trata_new_desc'), $peri_ant, $peri_nue, $fecha, $hora, $img_name);
						if($this->input->post('situacion') == 2 || $this->input->post('situacion') == 3 || $this->input->post('situacion') == 12 || $this->input->post('situacion') == 13 || $this->input->post('situacion') == 14 || $this->input->post('situacion') == 19 || $this->input->post('situacion') == 21){
							$this->telegram2($this->input->post('id_ticket'),$this->input->post('trata_new_desc'));
						}
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Tratamiento Ticket');
						$this->gestion($sql,$agrupar,$columna);						
					}
				}else if(isset($_POST['only_trata_solu'])){
					$this->form_validation->set_rules('trata_new_desc', 'Tratamiento dado a la incidencia', 'required|trim|htmlspecialchars');
					if ($this->form_validation->run() == FALSE){
						$data = array('title' => '', 'id' => $this->input->post('id_ticket'));
						$this->editar_incidencia($this->input->post('id_ticket'));
					}else{
						$this->form_validation->set_rules('trata_new_desc', 'Tratamiento dado a la incidencia', 'required|trim|htmlspecialchars');
						$fecha = date('Y-m-d');
						$hora = date('H:i:s');
						$img_name = '';
						$origen = $this->post->get_ticket($this->input->post('id_ticket'));
						$peri_ant = $peri_nue = 0;
						$ticket = $this->post->solucionar_ticket('6', $this->input->post('id_ticket'), $this->input->post('trata_new_desc'), $peri_ant, $peri_nue, $fecha, $hora, $img_name);
						if($origen->id_origen > 0){
							$solucionar_ticket = $this->post->solucionar_ticket('6', $origen->id_origen, $this->input->post('trata_new_desc'), $peri_ant, $peri_nue, $fecha, $hora, $img_name);
						}
						if($this->input->post('situacion') == 2 || $this->input->post('situacion') == 3 || $this->input->post('situacion') == 12 || $this->input->post('situacion') == 13 || $this->input->post('situacion') == 14 || $this->input->post('situacion') == 19 || $this->input->post('situacion') == 21){
							$this->telegram2($this->input->post('id_ticket'),$this->input->post('trata_new_desc'));
						}
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Tratamiento Ticket');
						$this->gestion($sql,$agrupar,$columna);
					}
				}else{
					$fecha = date('Y-m-d');
					$hora = date('H:i:s');
					if($this->input->post('solucionada') == 'on'){
						$situacion = 6;
					}else{
						$situacion = $this->input->post('situacion');
					}

					$img_name = array();
				    if($_FILES['error_imagen']['size'][0] != 0){		
						$totalImg = count($_FILES['error_imagen']['name']);
						for($i=0; $i<$totalImg; $i++){
							$ext = explode('.', basename($_FILES['error_imagen']['name'][$i]));
							$img_name[$i] = md5(uniqid()) . "." . array_pop($ext);
							$target = APPPATH."../tickets/files/img/errores/" . $img_name[$i];
							move_uploaded_file($_FILES['error_imagen']['tmp_name'][$i], $target);				
						}
					}else{
						$img_name[0] = '';
					}

					$comprobar_asignado = $this->post->comprobar_asignado($this->input->post('id_ticket'),$this->input->post('asignado'));
				    $destino = $this->input->post('destino');
					$resultado = $this->post->editar_incidencia($this->input->post('id_ticket'),$this->input->post('empresa'),$situacion,$this->input->post('caduca_ticket'),$this->input->post('operador'),$this->input->post('salon'),$this->input->post('cliente_tipo'),$this->input->post('cliente_nombre'),$this->input->post('cliente_apellidos'),$this->input->post('cliente_telefono'),$this->input->post('cliente_email'),$this->input->post('gestion_tipo'),$this->input->post('error_maquina'),$this->input->post('error_tipo'),$this->input->post('error_detalle'),$this->input->post('error_desc'),$destino,$this->input->post('trata_desc'), $this->input->post('asignado'), $fecha, $hora, $img_name, $this->input->post('importe_ticket'), $this->input->post('prioridad'), $this->input->post('fecha_programada'), $this->input->post('guia_maquina'), $this->input->post('direccion_entrega'), $this->input->post('telefono_entrega'));
					if($situacion == 6){
						$origen = $this->post->get_ticket($this->input->post('id_ticket'));
						if($origen->id_origen > 0){
							$peri_ant = $peri_nue = 0;
							$solucionar_ticket = $this->post->solucionar_ticket('6', $origen->id_origen, $this->input->post('trata_new_desc'), $peri_ant, $peri_nue, $fecha, $hora, $img_name);
						}
					}
					if($comprobar_asignado == 1){
						$this->telegram5($this->input->post('id_ticket'), $this->input->post('error_desc'));
					}
					$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Editar Ticket');
					$this->gestion($resultado,$sql,$agrupar,$columna);
				}
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Gestionar incidencias */
	public function gestion($resultado=NULL,$sql_edicion=NULL,$agrupar_edicion=NULL,$columna_agrupar_edicion=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_incidencias'] != 1){
				$this->maquinas(1);
			}else{
				if(!isset($resultado) || empty($resultado) || $resultado == ''){
					$resultado = 0;
				}

				$get_sql = $this->uri->segment(3);
				$get_sql2 = $this->uri->segment(4);
								
				if($get_sql == 'agrupar' || $get_sql2 == 'agrupar'){
					$agrupar_volver = '1';
					if($get_sql == 'agrupar'){
						$agrupar_volver_columna = $this->uri->segment(4);
					}else if($get_sql2 == 'agrupar'){
						$agrupar_volver_columna = $this->uri->segment(5);
					}
				}else{
					$agrupar_volver = '0';
					$agrupar_volver_columna = '0';
				}
				/* Select empresas */
				$empresas = $this->post->get_empresas();
				$html_empresas='';
				$html_empresas.='<option value="0">TODAS</option>';
				foreach($empresas->result() as $empresa){
					$html_empresas.='<option value="'.$empresa->id.'">'.$empresa->empresa.'</option>';
				}
				
				/* Select salones */
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || (($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9) && $this->session->userdata('logged_in')['acceso'] == 24)){
					$salones = $this->post->get_salones();
					$html_salones='';
					foreach($salones->result() as $salon){
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9){
					$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
					$html_salones='';
					foreach($salones->result() as $salon){
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}else if($this->session->userdata('logged_in')['rol'] == 3){
					$salon = $this->post->get_salones_rol_salon($this->session->userdata('logged_in')['acceso']);
					$html_salones='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
				}
				
				/* Select operadora - rol 3 */
				if($this->session->userdata('logged_in')['rol'] == 3){
					$html_op='';
					$operadora = $this->post->get_salones_model($this->session->userdata('logged_in')['acceso']);
					foreach($operadora->result() as $opera){
						$html_op.='<option value="'.$opera->id.'" selected>'.$opera->operadora.'</option>';
					} 	
				}else if(($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$html_op='';
					$operadora = $this->post->get_operadoras_by_name();
					foreach($operadora->result() as $opera){
						$html_op.='<option value="'.$opera->id.'">'.$opera->operadora.'</option>';
					}
				}
				
				/* Buscar tickets */
				$consulta_sql = '';
				if($get_sql != '' && $get_sql != 'agrupar'){
					$initial_sql = "SELECT * FROM tickets WHERE 1";
					$get_sql = str_replace("%20", " ", $get_sql);
					$get_sql = str_replace("%3E", ">", $get_sql);
					$get_sql = str_replace("%3C", "<", $get_sql);
					$get_sql = str_replace("%25", "%", $get_sql);
					$final_sql = $initial_sql.$get_sql;
					$consulta_sql = $get_sql;
					$final_sql .= " ORDER BY prioridad DESC, fecha_creacion DESC, hora_creacion DESC";
					$incidencias = $this->post->buscar_tickets($final_sql);
				}else if($this->session->userdata('logged_in')['id'] == 571 || $this->session->userdata('logged_in')['id'] == 351){
					$incidencias = $this->post->get_tickets_inf();
				}else if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24){
					$incidencias = $this->post->get_tickets_sat();
				}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7){
					$incidencias = $this->post->get_tickets();
				}else if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 5){
					$incidencias = $this->post->get_tickets_op($this->session->userdata('logged_in')['acceso']);
				}else if($this->session->userdata('logged_in')['rol'] == 3){
					$incidencias = $this->post->get_tickets_salon($this->session->userdata('logged_in')['acceso']);
				}else if($this->session->userdata('logged_in')['rol'] == 6){
					$incidencias = $this->post->get_tickets_com();
				}else if($this->session->userdata('logged_in')['rol'] == 8){
					$incidencias = $this->post->get_tickets_mkt();
				}else if($this->session->userdata('logged_in')['rol'] == 9){
					$incidencias = $this->post->get_tickets_onl();
				}
				$numero_filas = 0;
				$tabla = '';
				$version_movil = '';
				$array_usuarios_operadora = array();
				$usuarios_operadora = $this->post->get_usuarios_operadora($this->session->userdata('logged_in')['acceso']);
				foreach($usuarios_operadora->result() as $usuario_operadora){
					array_push($array_usuarios_operadora, $usuario_operadora->id);
				}
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
					$destino_incidencia = $this->post->get_destino_incidencia($this->session->userdata('logged_in')['acceso']);
				}
				$usuarios_adm = $this->post->get_usuarios_adm();
				/* Resultados individuales pc/movil */
				$array_id_incidencias = array();
				foreach($incidencias->result() as $incidencia){

					/* Incidencias CIRSA */
					if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 2){
						if($incidencia->tipo_averia == 11){
							continue;
						}
					}

					/* Pendiente Kirol */
					if($this->session->userdata('logged_in')['rol'] != 1){
						if($incidencia->situacion == 8){
							continue;
						}
					}
					
					/* Comprobra gestion ATC operadoras activo y creador ADM */
					if($this->session->userdata('logged_in')['acceso'] == 24 && $this->session->userdata('logged_in')['rol'] != 3){
						if(in_array($incidencia->creador, $usuarios_adm)){
							
						}else{
							if($incidencia->situacion == 2 && $incidencia->destino == 4){
								
							}else{
								continue;
							}
						}
						
						if($incidencia->tipo_averia != '6' && $incidencia->tipo_averia != '3' && $incidencia->tipo_averia != '11'){
							$gestion_activa = $this->post->get_gestion_activa($incidencia->empresa);
							if($gestion_activa->tipo_gestion == 0){
								continue;
							}
						}
					}

					/* Evitar duplicados */
					if (in_array($incidencia->id, $array_id_incidencias)) {
					    continue;
					}
					array_push($array_id_incidencias, $incidencia->id);

					$numero_filas++;
					/* Comprobar situacion */
					$ediciones = $this->post->get_ultima_edicion($incidencia->id);
					if(!empty($ediciones)){
						if($incidencia->situacion != 9){
							$situacion = $this->post->get_situacion($ediciones);
						}else{
							$situacion = $this->post->get_situacion($incidencia->situacion);
						}
					}else{
						$situacion = $this->post->get_situacion($incidencia->situacion);
					}
					if($incidencia->situacion == 5){
						if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 3){
							if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
								$tabla.='<tr style="background: #F7D8BA; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else if($incidencia->detalle_error == 699){
								$tabla.='<tr style="background: #edb8e9; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else{
								$tabla.='<tr style="background: powderblue; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}
						}else{
							if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
								$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else if($incidencia->detalle_error == 699){
								$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else{
								$tabla.='<tr style="background: powderblue;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}
						}
					}else if($incidencia->situacion == 6){
						if($this->session->userdata('logged_in')['rol'] == 1){
							$tabla.='<tr class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
						}else{
							$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
						}						
					}else{
						if($incidencia->situacion != 9){				
							if($this->session->userdata('logged_in')['rol'] == 1){
								if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
									$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else if($incidencia->detalle_error == 699){
									$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else{
									$tabla.='<tr class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}
							}else{
								if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
									$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else if($incidencia->detalle_error == 699){
									$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else{
									$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}
							}
						}else{
							if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
								$tabla.='<tr style="background: #F7D8BA; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else if($incidencia->detalle_error == 699){
								$tabla.='<tr style="background: #edb8e9; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else{
								$tabla.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}							
						}
					}
					$version_movil.='<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
					
					$fecha_creacion = explode("-", $incidencia->fecha_creacion);
					$fecha = $fecha_creacion[2]."/".$fecha_creacion[1]."/".$fecha_creacion[0];
					
					$tabla.='<td class="td_editada" style="width: 5% !important"><span class="span_editada">'.$fecha.' '.$incidencia->hora_creacion.'</span>';

					$tiempo_incidencia = $this->post->get_tiempo_incidencia($incidencia->fecha_creacion." ".$incidencia->hora_creacion);

					$tabla .= '<div class="div_fecha" style="display: none;">
									<p>'.$tiempo_incidencia.'</p>										
								</div>
							</td>';
																
					if($situacion == "Solucionada"){
						$tabla.='<td style="color: #449d44; font-weight: bold">'.$situacion.'</td>';					
					}else{
						if($incidencia->situacion == 5 && $incidencia->fecha_caducidad != ''){
							$tabla.='<td style="color: #b21a30; font-weight: bold">'.$situacion.' ('.$incidencia->fecha_caducidad.')</td>';
						}else{
							if(in_array($this->session->userdata('logged_in')['id'], $usuarios_adm)){
								$tabla.='<td style="color: #b21a30; font-weight: bold">'.$situacion.'</td>';
							}else{
								if($incidencia->destino == 4){
									$tabla.='<td style="color: #eb9316; font-weight: bold">'.$situacion.' ADM</td>';
								}else{
									$tabla.='<td style="color: #b21a30; font-weight: bold">'.$situacion.'</td>';
								}
							}
						}				
					}
					
					$operadora = $this->post->get_operadora($incidencia->operadora);			
					$salon = $this->post->get_salon($incidencia->salon);
					$salon_completo = $this->post->get_salon_completo($incidencia->salon);
					$averia = $this->post->get_averia($incidencia->tipo_averia);
					$tipo_error = $this->post->get_tipo_error($incidencia->tipo_error);
					$detalle_error = $this->post->get_detalle_error($incidencia->detalle_error);		
					$maquina = $this->post->get_maquina($incidencia->maquina);			
					$destino = $this->post->get_destino($incidencia->destino);			
					$creador = $this->post->get_creador($incidencia->creador);
					$creador2 = $this->post->get_creador_completo($incidencia->creador);
					
					if($this->session->userdata('logged_in')['rol'] == 1){				
						$tabla .= '<td>'.$operadora.'</td>';
					}
					
					$tabla .= '<td style="font-weight: bold">'.$salon.'<span class="region" style="display: none">'.$salon_completo->empresa.'</span></td>';
					
					if($this->session->userdata('logged_in')['rol'] == 1){
											
						if($creador2->rol == 1){
							$tabla .= '<td>'.$incidencia->nombre.'</td>
												<td>'.$incidencia->telefono.'</td>';
						}else{
							$tabla .= '<td>'.$creador2->nombre.'</td>
												<td>'.$creador2->telefono.'</td>';
						}
					
					}
											
					$tabla .= '<td>'.$averia->gestion.'</td>
											<td>'.$tipo_error.'</td>
											<td>'.$detalle_error.'</td>
											<td>'.$maquina.'</td>';
											
					if($this->session->userdata('logged_in')['rol'] == 1){				
						$tabla .= '<td>'.$destino.'</td>';
					}
					
					$tabla .= '<td>'.$creador.'</td>';
											
					$editada = $this->post->get_ediciones_incidencia($incidencia->id);
					
					if($editada->num_rows() == 1){
						$edicion = $editada->row();
						$editor = $this->post->get_creador($edicion->creador);
						$fecha_edicion = explode('-', $edicion->fecha_edicion);
						$tabla .= '<td class="td_editada">
												<span class="span_editada">
													Editada
												</span>
												<div class="div_editada" style="display: none;">
													<p>'.$fecha_edicion[2].'/'.$fecha_edicion[1].'/'.$fecha_edicion[0].' '.$edicion->hora_edicion.'</p>
													<p>'.$editor.'</p>												
												</div>
											</td>';
					}else{
						$tabla .= '<td class="td_editada">
												<span class="span_editada">
													Editada
												</span>
												<div class="div_editada" style="display: none;">
													<p>Nadie</p>
												</div>
											</td>';
					}
					
					if($incidencia->asignado == 0){
						$tabla .= '<td style="width: 100px">Nadie</td>';
					}else{
						$asignado = $this->post->get_creador($incidencia->asignado);
						$tabla .= '<td style="width: 100px">'.$asignado.'</td>';
					}
					
					if($incidencia->tratamiento == 0){
						$tabla .= '<td style="width: 100px">Nadie</td>';
					}else{
						$tratamiento = $this->post->get_creador($incidencia->tratamiento);
						$tabla .= '<td style="width: 100px">'.$tratamiento.'</td>';
					}
					
					if($incidencia->soluciona != 0){
						$soluciona = $this->post->get_creador($incidencia->soluciona);
						$tabla .= '<td>'.$soluciona.'</td>';
					}
					
					if($situacion == "Solucionada"){
						$version_movil.='<div class="panel-heading" style="background: #449d44; text-align: center; padding: 5px 4px; font-size: 13px">
											<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
										</div>
										<div class="panel-body" style="padding: 0; border: none">
											<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
											<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
											 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
											 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
											 </div>
											 <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
												<p><span style="font-weight: bold">Situación: </span><span style="color: #449d44;">'.$situacion.'</span></p>';
					}else{
						if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || (($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24)){
							$version_movil.='<div class="panel-heading" style="background: #d9534f; text-align: center; padding: 5px 4px; font-size: 13px">
												<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
											</div>
											<div class="panel-body" style="padding: 0; border: none">
												<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
													<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
												    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
												    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
												    </div>
												    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
														<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion.'</span></p>';
						}else{
							if($incidencia->destino == 4){
								$version_movil.='<div class="panel-heading" style="background: #eb9316; text-align: center; padding: 5px 4px; font-size: 13px">
													<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
												</div>
												<div class="panel-body" style="padding: 0; border: none">
													<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
														</div>
														<div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
															<p><span style="font-weight: bold">Situación: </span><span style="color: #eb9316;">'.$situacion.' ADM</span></p>';
							}else{
								$version_movil.='<div class="panel-heading" style="background: #d9534f; text-align: center; padding: 5px 4px; font-size: 13px">
													<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
												</div>
												<div class="panel-body" style="padding: 0; border: none">
													<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
								 						<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
								 						<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
								 						</div>
								 						<div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
															<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion.'</span></p>';
							}
						}
					}
					
					$prioridad = $this->post->get_prioridad_id($incidencia->prioridad);
					if($incidencia->prioridad == 0){
						$tabla .= '<td style="width: 50px; color: #ffd600; font-weight: bold">'.$prioridad->prioridad.'</td>';
						$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #ffd600">'.$prioridad->prioridad.'</span></p>';
					}else if($incidencia->prioridad == 1){
						$tabla .= '<td style="width: 50px; color: #449d44; font-weight: bold">'.$prioridad->prioridad.'</td>';
						$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #449d44">'.$prioridad->prioridad.'</span></p>';
					}else if ($incidencia->prioridad == 2){
						$tabla .= '<td style="width: 50px; color: #b21a30; font-weight: bold">'.$prioridad->prioridad.'</td>';
						$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #b21a30">'.$prioridad->prioridad.'</span></p>';
					}else if ($incidencia->prioridad == 3){
						$tabla .= '<td style="width: 50px; color: #138496; font-weight: bold">Programada</td>';
						$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #138496">Programada</span></p>';
					}
											
					$version_movil.='<p><span style="font-weight: bold">Máquina:</span> '.$maquina.'</p>';
											
					if($this->session->userdata('logged_in')['rol'] == 1){
						$version_movil.='<p><span style="font-weight: bold">Destino:</span> '.$destino.'</p>
															<p><span style="font-weight: bold">Nombre:</span> '.$incidencia->nombre.'</p>
															<p><span style="font-weight: bold">Teléfono:</span> '.$incidencia->telefono.'</p>';
					}
					
					if($incidencia->asignado != 0){
						$asignado = $this->post->get_creador($incidencia->asignado);
						$version_movil.='<p><span style="font-weight: bold">Asignado:</span> '.$asignado.'</p>';
					}
					
					if($incidencia->tratamiento != 0){
						$tratamiento = $this->post->get_creador($incidencia->tratamiento);
						$version_movil.='<p><span style="font-weight: bold">Tratamiento:</span> '.$tratamiento.'</p>';
					}
					
					if($incidencia->soluciona != 0){
						$soluciona = $this->post->get_creador($incidencia->soluciona);
						$version_movil.='<p><span style="font-weight: bold">Solucionado:</span> '.$soluciona.'</p>';
					}
			
					if($situacion == "Solucionada"){
						if($incidencia->situacion == 8){
							if($this->session->userdata('logged_in')['rol'] == 1){
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">
											<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
											<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
										</td></tr>';
								$version_movil.='<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>
									<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}
						}else{
							$tabla.='</tr>';
							if($this->session->userdata('logged_in')['rol'] == 1){
								$version_movil.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}else{
								$version_movil.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}
						}
					}else{
						if($incidencia->situacion == 8){
							if($this->session->userdata('logged_in')['rol'] == 1){
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">
											<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
											<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
										</td></tr>';
								$version_movil.='<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>
									<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}
						}else{
							if($this->session->userdata('logged_in')['rol'] == 1){
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">
											<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
											<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
										</td>';
							}else{
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">											
											<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>';
								$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}
							
							if($incidencia->asignado == 0 && ($this->session->userdata('logged_in')['rol'] == 4 || ($this->session->userdata('logged_in')['rol'] == 2))){
								if($incidencia->prioridad != 3){
									if($this->session->userdata('logged_in')['acceso'] == 24){									
										if($this->session->userdata('logged_in')['rol'] == 2){
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
											$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
										}
										$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
										$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
									}else{
										if($incidencia->situacion == 2 && $this->session->userdata('logged_in')['acceso'] == 41){									
											if($incidencia->situacion != 1){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
												$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
											}											
										}else if(($incidencia->situacion == 2 || $incidencia->situacion == 13) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
											if($this->session->userdata('logged_in')['rol'] == 2){
												if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';	
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
													$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
													$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
												}
											}else{
												if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
													$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
												}
											}
										}
									}
								}
							}
							
							if($incidencia->asignado == 0 && $this->session->userdata('logged_in')['rol'] == 6 && $incidencia->situacion == 12){
								if ($incidencia->prioridad != 3){
									$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
									$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
								}
							}

							if($incidencia->asignado == 0 && $this->session->userdata('logged_in')['rol'] == 8 && $incidencia->situacion == 19){
								if ($incidencia->prioridad != 3){
									$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
									$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
								}
							}

							if($incidencia->asignado == 0 && $this->session->userdata('logged_in')['rol'] == 9 && $incidencia->situacion == 21){
								if ($incidencia->prioridad != 3){
									$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
									$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
								}
							}
							
							if($incidencia->asignado == $this->session->userdata('logged_in')['id']){
								if($incidencia->situacion != 9){
									if($this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9){
										if($incidencia->situacion != 2){
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
											$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
										}
									}else if($this->session->userdata('logged_in')['rol'] == 2){
										$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
										$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
										$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
										$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
									}else{
										$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
										$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
									}
								}
							}else if($incidencia->asignado != 0){
								$asignado = $this->post->get_creador_completo($incidencia->asignado);
								if($this->session->userdata('logged_in')['rol'] == 2){
									if($incidencia->situacion != 9){								
										if($this->session->userdata('logged_in')['acceso'] == 24){
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
											$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
										}else if($incidencia->operadora == 41){
											if(($incidencia->destino == 230 || $incidencia->destino == 4) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" margin: 0;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
												$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
											}
										}else if(($incidencia->situacion == 2 || $incidencia->situacion == 13) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
											if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" margin: 0;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
												$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
											}
										}
									}
								}else{
									if($incidencia->situacion != 9){
										$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
									}
								}
							}
						}					
					}

					$tabla .= '</td></tr>';
					$version_movil.='</div></div></div>';	
				}
				if(($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$data = array('title' => '', 'html_empresas' => $html_empresas, 'html_salones' => $html_salones, 'tabla_incidencias' => $tabla, 'version_movil' => $version_movil, 'numero_filas' => $numero_filas, 'consulta' => $consulta_sql, 'html_op' => $html_op, 'solucionado' => 0, 'agrupar_volver' => $agrupar_volver, 'agrupar_volver_columna' => $agrupar_volver_columna, 'resultado' => $resultado);
					$this->load_view('gestion_incidencias', $data);
				}else{
					$data = array('title' => '', 'html_empresas' => $html_empresas, 'html_salones' => $html_salones, 'tabla_incidencias' => $tabla, 'version_movil' => $version_movil, 'numero_filas' => $numero_filas, 'consulta' => $consulta_sql, 'solucionado' => 0, 'agrupar_volver' => $agrupar_volver, 'agrupar_volver_columna' => $agrupar_volver_columna, 'resultado' => $resultado);
					$this->load_view('gestion_incidencias', $data);
				}
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Buscador rápido gestion incidencias -- ID*/
	public function buscador_incidencias_id(){
		error_reporting(0);
        ini_set('display_errors', 0);
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4 && $this->session->userdata('logged_in')['rol'] != 7){
				$this->gestion();
			}else{
				$data = array('title' => '');
				$this->form_validation->set_rules('id_incidencia', 'id_incidencia', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('buscar_trata', 'Tratamiento incidencia', 'trim|htmlspecialchars');
				if ($this->form_validation->run() == FALSE){
					$this->gestion();
				}else{
					/* Select empresas */
					$empresas = $this->post->get_empresas();
					$html_empresas='';
					$html_empresas.='<option value="0">TODAS</option>';
					foreach($empresas->result() as $empresa){
						$html_empresas.='<option value="'.$empresa->id.'">'.$empresa->empresa.'</option>';
					}
					
					/* Select salones */
					$salones = $this->post->get_salones();
					$html_salones='';
					foreach($salones->result() as $salon){
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}

					$array_usuarios_operadora = array();
					$usuarios_operadora = $this->post->get_usuarios_operadora($this->session->userdata('logged_in')['acceso']);
					foreach($usuarios_operadora->result() as $usuario_operadora){
						array_push($array_usuarios_operadora, $usuario_operadora->id);
					}
					if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
						$destino_incidencia = $this->post->get_destino_incidencia($this->session->userdata('logged_in')['acceso']);
					}
				
					$incidencia = $this->post->get_ticket($this->input->post('id_incidencia'));
					
					if($incidencia){
						$consulta_sql = " AND id = ".$this->input->post('id_incidencia')."";											
						/* Comprobar situacion */
						$ediciones = $this->post->get_ultima_edicion($incidencia->id);
						if(!empty($ediciones)){
							if($incidencia->situacion != 9){
								$situacion = $this->post->get_situacion($ediciones);
							}else{
								$situacion = $this->post->get_situacion($incidencia->situacion);
							}
						}else{
							$situacion = $this->post->get_situacion($incidencia->situacion);
						}
						
						$tabla =  $version_movil = '';
						if($this->session->userdata('logged_in')['rol'] == 7){
							if($incidencia->situacion == 5){
								if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
									$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else if($incidencia->detalle_error == 699){
									$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else{
									$tabla.='<tr style="background: powderblue;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}
							}else{
								if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
									$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else if($incidencia->detalle_error == 699){
									$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else{
									$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}
							}							
						}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
							if($incidencia->situacion != 6){
								if($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24){
									if($incidencia->destino == 4 || $incidencia->destino == 32 || $incidencia->destino == 244 || $incidencia->destino == 230){
										$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else{
										$tabla.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}
								}else{
									$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}
							}else{
								if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
									$tabla.='<tr class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else{
									$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}
							}
						}else{
							if($incidencia->situacion != 6){
								if($incidencia->situacion == 5){
									if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
										$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else if($incidencia->detalle_error == 699){
										$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else{
										$tabla.='<tr style="background: powderblue;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}
								}else{
									if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
										$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else if($incidencia->detalle_error == 699){
										$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else{
										$tabla.='<tr class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}
								}
							}else{
								if($this->session->userdata('logged_in')['rol'] == 1){
									if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
										$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else if($incidencia->detalle_error == 699){
										$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else{
										$tabla.='<tr class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}
								}else{
									if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
										$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else if($incidencia->detalle_error == 699){
										$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else{
										$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}
								}
							}
						}						
						$version_movil.='<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
					
						$fecha_creacion = explode("-", $incidencia->fecha_creacion);
						$fecha = $fecha_creacion[2]."/".$fecha_creacion[1]."/".$fecha_creacion[0];
						
						$tabla.='<td>'.$fecha.' '.$incidencia->hora_creacion.'</td>';
						
						if($situacion == "Solucionada"){
							$tabla.='<td style="color: #449d44; font-weight: bold">'.$situacion.'</td>';					
						}else{
							if($incidencia->situacion == 5 && $incidencia->fecha_caducidad != ''){
								$tabla.='<td style="color: #b21a30; font-weight: bold">'.$situacion.' ('.$incidencia->fecha_caducidad.')</td>';
							}else{
								$tabla.='<td style="color: #b21a30; font-weight: bold">'.$situacion.'</td>';
							}				
						}
						
						$operadora = $this->post->get_operadora($incidencia->operadora);			
						$salon = $this->post->get_salon($incidencia->salon);
						$averia = $this->post->get_averia($incidencia->tipo_averia);			
						$tipo_error = $this->post->get_tipo_error($incidencia->tipo_error);
						$detalle_error = $this->post->get_detalle_error($incidencia->detalle_error);		
						$maquina = $this->post->get_maquina($incidencia->maquina);			
						$destino = $this->post->get_destino($incidencia->destino);
						$creador = $this->post->get_creador($incidencia->creador);
						$creador2 = $this->post->get_creador_completo($incidencia->creador);

						if($this->session->userdata('logged_in')['rol'] == 1){	
							$tabla .= '<td>'.$operadora.'</td>';
						}

						$tabla .= '<td style="font-weight: bold">'.$salon.'</td>';
												
						if($this->session->userdata('logged_in')['rol'] == 1){	
							$tabla .= '<td>'.$incidencia->nombre.'</td>
										<td>'.$incidencia->telefono.'</td>';
						}
						
						$tabla .= '<td>'.$averia->gestion.'</td>
									<td>'.$tipo_error.'</td>
									<td>'.$detalle_error.'</td>
									<td>'.$maquina.'</td>';

						if($this->session->userdata('logged_in')['rol'] == 1){							
							$tabla .= '<td>'.$destino.'</td>';
						}					

						$tabla .= '<td>'.$creador.'</td>';
												
						$editada = $this->post->get_ediciones_incidencia($incidencia->id);
						
						if($editada->num_rows() == 1){
							$edicion = $editada->row();
							$editor = $this->post->get_creador($edicion->creador);
							$fecha_edicion = explode('-', $edicion->fecha_edicion);
							$tabla .= '<td class="td_editada">
											<span class="span_editada">
												Editada
											</span>
											<div class="div_editada" style="display: none;">
												<p>'.$fecha_edicion[2].'/'.$fecha_edicion[1].'/'.$fecha_edicion[0].' '.$edicion->hora_edicion.'</p>
												<p>'.$editor.'</p>												
											</div>
										</td>';	
						}else{
							$tabla .= '<td class="td_editada">
											<span class="span_editada">
												Editada
											</span>
											<div class="div_editada" style="display: none;">
												<p>Nadie</p>
											</div>
										</td>';	
						}
						
						if($incidencia->asignado == 0){
							$tabla .= '<td style="width: 100px">Nadie</td>';
						}else{
							$asignado = $this->post->get_creador($incidencia->asignado);
							$tabla .= '<td style="width: 100px">'.$asignado.'</td>';
						}
						
						if($incidencia->tratamiento == 0){
							$tabla .= '<td style="width: 100px">Nadie</td>';
						}else{
							$tratamiento = $this->post->get_creador($incidencia->tratamiento);
							$tabla .= '<td style="width: 100px">'.$tratamiento.'</td>';
						}
						
						if($situacion == "Solucionada"){
							$version_movil.='<div class="panel-heading" style="background: #449d44; text-align: center; padding: 5px 4px; font-size: 13px">
											<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
										</div>
										<div class="panel-body" style="padding: 0; border: none">
											<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
											<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
											 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
											 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
											 </div>
											 <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
												<p><span style="font-weight: bold">Situación: </span><span style="color: #449d44;">'.$situacion.'</span></p>';
						}else{
							$version_movil.='<div class="panel-heading" style="background: #d9534f; text-align: center; padding: 5px 4px; font-size: 13px">
													<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
												</div>
												<div class="panel-body" style="padding: 0; border: none">
													<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
								 						<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
								 						<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
								 						</div>
								 						<div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
															<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion.'</span></p>';
						}
						
						$prioridad = $this->post->get_prioridad_id($incidencia->prioridad);
						if($incidencia->prioridad == 0){
							$tabla .= '<td style="width: 50px; color: #ffd600; font-weight: bold">'.$prioridad->prioridad.'</td>';
							$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #ffd600">'.$prioridad->prioridad.'</span></p>';
						}else if($incidencia->prioridad == 1){
							$tabla .= '<td style="width: 50px; color: #449d44; font-weight: bold">'.$prioridad->prioridad.'</td>';
							$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #449d44">'.$prioridad->prioridad.'</span></p>';
						}else if ($incidencia->prioridad == 2){
							$tabla .= '<td style="width: 50px; color: #b21a30; font-weight: bold">'.$prioridad->prioridad.'</td>';
							$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #b21a30">'.$prioridad->prioridad.'</span></p>';
						}else if ($incidencia->prioridad == 3){
							$tabla .= '<td style="width: 50px; color: #138496; font-weight: bold">Programada</td>';
							$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #138496">Programada</span></p>';
						}
						
						$version_movil.='<p><span style="font-weight: bold">Máquina:</span> '.$maquina.'</p>';
												
						$version_movil.='<p><span style="font-weight: bold">Destino:</span> '.$destino.'</p>
														<p><span style="font-weight: bold">Nombre:</span> '.$incidencia->nombre.'</p>
														<p><span style="font-weight: bold">Teléfono:</span> '.$incidencia->telefono.'</p>';
						
						if($incidencia->asignado != 0){
							$asignado = $this->post->get_creador($incidencia->asignado);
							$version_movil.='<p><span style="font-weight: bold">Asignado:</span> '.$asignado.'</p>';
						}
						
						if($incidencia->tratamiento != 0){
							$tratamiento = $this->post->get_creador($incidencia->tratamiento);
							$version_movil.='<p><span style="font-weight: bold">Tratamiento:</span> '.$tratamiento.'</p>';
						}
						
						if($incidencia->soluciona != 0){
							$soluciona = $this->post->get_creador($incidencia->soluciona);
							$version_movil.='<p><span style="font-weight: bold">Solucionado:</span> '.$soluciona.'</p>';
						}
			
						if($situacion == "Solucionada"){
							if($this->session->userdata('logged_in')['rol'] == 1){
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">
											<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
											<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id).'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>';
								$version_movil.='<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>';								
								$version_movil.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';								
							}else{
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">';
								if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
									$version_movil.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
								}else{
									$version_movil.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
								}
							}
						}else{
							if($incidencia->situacion == 8){
								if($this->session->userdata('logged_in')['rol'] == 1){
									$tabla.='<td style="width: 150px !important; padding: 5px 0;">
												<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
												<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id).'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>';
									$version_movil.='<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>
										<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id).'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
								}else{
									$tabla.='<td style="width: 150px !important; padding: 5px 0;">';
									$version_movil.='<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
								}
							}else{
								if($this->session->userdata('logged_in')['rol'] == 1){
									$tabla.='<td style="width: 150px !important; padding: 5px 0;">
												<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
												<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id).'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>';
									$version_movil.='<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>
										<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id).'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
								}else{
									$tabla.='<td style="width: 150px !important; padding: 5px 0;">';
									$version_movil.='<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
								}

								if($incidencia->asignado == 0 && ($this->session->userdata('logged_in')['rol'] == 4 || ($this->session->userdata('logged_in')['rol'] == 2))){
									if($incidencia->prioridad != 3){
										if($this->session->userdata('logged_in')['acceso'] == 24){											
											if($this->session->userdata('logged_in')['rol'] == 2){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
												$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
											}
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
											$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
										}else{
											if($incidencia->situacion == 2 && $this->session->userdata('logged_in')['acceso'] == 41){									
												if($incidencia->situacion != 1){
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
													$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
												}											
											}else if(($incidencia->situacion == 2 || $incidencia->situacion == 13) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
												if($this->session->userdata('logged_in')['rol'] == 2){
													if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
														$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';	
														$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
														$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
														$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
													}
												}else{
													if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
														$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
														$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
													}
												}
											}
										}
									}
								}
								
								if($incidencia->asignado == 0 && $this->session->userdata('logged_in')['rol'] == 6 && $incidencia->situacion == 12){
									if ($incidencia->prioridad != 3){
										$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
										$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
									}
								}
								
								if($incidencia->asignado == $this->session->userdata('logged_in')['id']){
									if($incidencia->situacion != 9){
										if($this->session->userdata('logged_in')['rol'] == 6){
											if($incidencia->situacion != 2){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
												$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
											}
										}else if($this->session->userdata('logged_in')['rol'] == 2){
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
											$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
											$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
										}else{
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
											$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
										}
									}
								}else if($incidencia->asignado != 0){
									$asignado = $this->post->get_creador_completo($incidencia->asignado);
									if($this->session->userdata('logged_in')['rol'] == 2){
										if($incidencia->situacion != 9){								
											if($this->session->userdata('logged_in')['acceso'] == 24){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
												$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
											}else if($incidencia->operadora == 41){
												if(($incidencia->destino == 230 || $incidencia->destino == 4) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" margin: 0;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
													$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
												}
											}else if(($incidencia->situacion == 2 || $incidencia->situacion == 13) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
												if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" margin: 0;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
													$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
												}
											}
										}
									}else{
										if($incidencia->situacion != 9){
											$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
										}
									}
								}

							}					
						}

						$tabla .= '</td></tr>';
						$version_movil.='</div></div></div>';
						
						$data = array('title' => '', 'id_incidencia' => $this->input->post('id_incidencia'), 'tabla_incidencias' => $tabla, 'version_movil' => $version_movil, 'agrupar_volver' => '', 'agrupar_volver_columna' => '', 'solucionado' => 0, 'html_empresas' => $html_empresas, 'html_salones' => $html_salones);
						$this->load_view('gestion_incidencias', $data);
					
					}else{						
						$this->gestion();
					}
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Buscador avanzado gestion incidencias */	
	public function buscador_incidencias(){
		error_reporting(0);
        ini_set('display_errors', 0);
		$data = array('title' => '');
		$this->form_validation->set_rules('operadora', 'Operadora', 'trim|htmlspecialchars');
		if($this->form_validation->run() == FALSE){
			$this->gestion();
		}else{
			$agrupar_volver = '0';
			$agrupar_volver_columna = '0';
			$mostrar_soluciona = 0;
			$consulta = "SELECT * FROM tickets WHERE 1";
			$consulta_sql = '';
			if(!empty($this->input->post('empresa'))){
				$empresa = $this->input->post('empresa');
				$consulta .= " AND empresa = '".$empresa."'";
				$consulta_sql .= " AND empresa = '".$empresa."'";
			}
			if(!empty($this->input->post('fecha_inicio_incidencia'))){
				$fecha_inicio1 = explode("/", $this->input->post('fecha_inicio_incidencia'));
				$fecha_inicio = $fecha_inicio1[2]."-".$fecha_inicio1[1]."-".$fecha_inicio1[0];
				$consulta .= " AND fecha_creacion >= '".$fecha_inicio."'";
				$consulta_sql .= " AND fecha_creacion >= '".$fecha_inicio."'";
			}
			if(!empty($this->input->post('fecha_fin_incidencia'))){
				$fecha_fin1 = explode("/", $this->input->post('fecha_fin_incidencia'));
				$fecha_fin = $fecha_fin1[2]."-".$fecha_fin1[1]."-".$fecha_fin1[0];
				$consulta .= " AND fecha_creacion <= '".$fecha_fin."'";
				$consulta_sql .= " AND fecha_creacion <= '".$fecha_fin."'";
			}
			if(!empty($this->input->post('operador'))){
				$operadora = $this->input->post('operador');
				$consulta .= " AND operadora = '".$operadora."'";
				$consulta_sql .= " AND operadora = '".$operadora."'";
			}else{
				if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 5){
					if($this->session->userdata('logged_in')['acceso'] == 24){
						if(!empty($this->input->post('salon'))){
							$salon = $this->post->get_salon_completo($this->input->post('salon'));
							$consulta .= " AND (operadora = '24' OR operadora = '41' OR operadora = '".$salon->operadora."')";
							$consulta_sql .= " AND (operadora = '24' OR operadora = '41')";
						}else{
							//$consulta .= " AND (operadora = '24' OR operadora = '41')";
							//$consulta_sql .= " AND (operadora = '24' OR operadora = '41')";
						}
					}else if($this->session->userdata('logged_in')['acceso'] == 41){
						if(!empty($this->input->post('salon'))){
							$salon = $this->post->get_salon_completo($this->input->post('salon'));
							$consulta .= " AND operadora = '".$salon->operadora."'";
							$consulta_sql .= " AND operadora = '".$salon->operadora."'";
						}else{
							$consulta .= " AND operadora IN (SELECT operadora FROM salones WHERE empresa = 3)";
							$consulta_sql .= " AND operadora IN (SELECT operadora FROM salones WHERE empresa = 3)";
						}
					}else{
						$operadora = $this->input->post('operador');
						$consulta .= " AND operadora = '".$this->session->userdata('logged_in')['acceso']."'";
						$consulta_sql .= " AND operadora = '".$this->session->userdata('logged_in')['acceso']."'";
					}
				}
			}
			if(!empty($this->input->post('salon'))){
				$salon = $this->input->post('salon');
				$consulta .= " AND salon = '".$salon."'";
				$consulta_sql .= " AND salon = '".$salon."'";
			}else{
				if($this->session->userdata('logged_in')['rol'] == 3){
					$salon = $this->input->post('operador');
					$consulta .= " AND salon = '".$this->session->userdata('logged_in')['acceso']."'";
					$consulta_sql .= " AND salon = '".$this->session->userdata('logged_in')['acceso']."'";
				}
			}
			if(!empty($this->input->post('buscar_trata'))){
				$buscar_trata = $this->input->post('buscar_trata');
				$buscar_trata_array = array();
				$sql_buscar_trata = "SELECT id_ticket FROM ediciones WHERE trata_desc LIKE '%".$buscar_trata."%'";
				$sql_buscar_trata_query = $this->db->query($sql_buscar_trata);
				foreach($sql_buscar_trata_query->result() as $sql_buscar_trata_result){
					array_push($buscar_trata_array, $sql_buscar_trata_result->id_ticket);
				}
				$consulta .= " AND id IN (".implode(',', $buscar_trata_array).")";
				$consulta_sql .= " AND id IN (".implode(',', $buscar_trata_array).")";
			}else{
				$buscar_trata = '';
			}
			if($this->input->post('error') == 'on' || $this->input->post('info') == 'on' || $this->input->post('recla') == 'on' || $this->input->post('suge') == 'on'){
				$tipo_error = array();
				if($this->input->post('error') == 'on'){
					array_push($tipo_error, "1", "2", "3", "4");
				}
				if($this->input->post('info') == 'on'){
					array_push($tipo_error, "5", "6");
				}
				if($this->input->post('recla') == 'on'){
					array_push($tipo_error, "7", "8", "9", "10", "11", "12");
				}
				if($this->input->post('suge') == 'on'){
					array_push($tipo_error, "13", "14");
				}
				$consulta .= " AND (tipo_error IN (".implode(',', $tipo_error).")";
				$consulta_sql .= " AND (tipo_error IN (".implode(',', $tipo_error).")";				
			}
			if($this->input->post('pend_rev') == 'on' || $this->input->post('pend_sat') == 'on' || $this->input->post('pend_com') == 'on' || $this->input->post('pend_euska') == 'on' || $this->input->post('pend_kirol') == 'on' || $this->input->post('pend_trata') == 'on' || $this->input->post('pend_cadu') == 'on' || $this->input->post('pend_tec_op') == 'on' || $this->input->post('pend_inf') == 'on' || $this->input->post('pend_mkt') == 'on' || $this->input->post('pend_onl') == 'on' || $this->input->post('cerra') == 'on' || $this->input->post('solucio') == 'on'){
				$situacion = array();
				if($this->input->post('pend_rev') == 'on'){
					array_push($situacion, "1");
				}
				if($this->input->post('pend_sat') == 'on'){
					array_push($situacion, "2");
				}
				if($this->input->post('pend_com') == 'on'){
					array_push($situacion, "12");
				}
				if($this->input->post('pend_euska') == 'on'){
					array_push($situacion, "3");
				}
				if($this->input->post('pend_kirol') == 'on'){
					array_push($situacion, "8");
				}
				if($this->input->post('pend_trata') == 'on'){
					array_push($situacion, "4");
				}
				if($this->input->post('pend_cadu') == 'on'){
					array_push($situacion, "5");
				}
				if($this->input->post('pend_llamar') == 'on'){
					array_push($situacion, "11");
				}
				if($this->input->post('pend_tec_op') == 'on'){
					array_push($situacion, "13");
				}
				if($this->input->post('pend_inf') == 'on'){
					array_push($situacion, "14");
				}
				if($this->input->post('pend_mkt') == 'on'){
					array_push($situacion, "19");
				}
				if($this->input->post('pend_onl') == 'on'){
					array_push($situacion, "21");
				}
				if($this->input->post('cerra') == 'on'){
					array_push($situacion, "7");
				}
				if($this->input->post('solucio') == 'on'){
					array_push($situacion, "6");
					$mostrar_soluciona = 1;
				}
				if($this->input->post('error') == 'on' || $this->input->post('info') == 'on' || $this->input->post('recla') == 'on' || $this->input->post('suge') == 'on'){
					$consulta .= " OR situacion IN (".implode(',', $situacion)."))";
					$consulta_sql .= " OR situacion IN (".implode(',', $situacion)."))";
				}else{
					$consulta .= " AND situacion IN (".implode(',', $situacion).")";
					$consulta_sql .= " AND situacion IN (".implode(',', $situacion).")";
				}
			}else{
				if($this->input->post('error') == 'on' || $this->input->post('info') == 'on' || $this->input->post('recla') == 'on' || $this->input->post('suge') == 'on'){
					$consulta .= " OR situacion NOT IN (6))";
					$consulta_sql .= " OR situacion NOT IN (6))";
				}else{
					if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24){
						$consulta .= " AND situacion IN (2,13)";
						$consulta_sql .= " AND situacion IN (2,13)";
					}else if($this->session->userdata('logged_in')['rol'] == 6){
						$consulta .= " AND situacion IN (1,2,4,5,11,12,13,14,16,17,19)";
						$consulta_sql .= " AND situacion IN (1,2,4,5,11,12,13,14,16,17,19)";
					}else if($this->session->userdata('logged_in')['rol'] == 8){
						$consulta .= " AND situacion IN (19)";
						$consulta_sql .= " AND situacion IN (19)";
					}else if($this->session->userdata('logged_in')['rol'] == 9){
						$consulta .= " AND situacion IN (21)";
						$consulta_sql .= " AND situacion IN (21)";
					}else{
						$consulta .= " AND situacion NOT IN (6)";
						$consulta_sql .= " AND situacion NOT IN (6)";
					}
				}
			}
			/* Mostrar solo tickets de su operadora para NO ATC */
			if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6){
				if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9) && $this->session->userdata('logged_in')['acceso'] == 24){
					$query = "SELECT * FROM usuarios WHERE acceso = '".$this->session->userdata('logged_in')['acceso']."' OR acceso = '41' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."') OR acceso IN (SELECT id FROM salones WHERE operadora = '41')";
				}else{
					$query = "SELECT * FROM usuarios WHERE acceso = '".$this->session->userdata('logged_in')['acceso']."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$this->session->userdata('logged_in')['acceso']."')";
				}				
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$grupo = $this->post->get_grupos_sat($this->session->userdata('logged_in')['acceso']);
				$consulta .= " AND (creador IN (".implode(',', $usuarios).") OR destino = '4' OR destino = '".$grupo->id."' OR situacion = '13')";
				$consulta_sql .= " AND (creador IN (".implode(',', $usuarios).") OR destino = '4' OR destino = '".$grupo->id."' OR situacion = '13')";
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$query = "SELECT * FROM salones WHERE id = '".$this->session->userdata('logged_in')['acceso']."'";
				$salones = $this->db->query($query);
				$op = $salones->row();
				$query = "SELECT * FROM usuarios WHERE acceso = '".$op->operadora."' OR acceso IN (SELECT id FROM salones WHERE operadora = '".$op->operadora."')";
				$users = $this->db->query($query);
				$usuarios = array();
				foreach($users->result() as $user){
					array_push($usuarios, $user->id);
				}
				$consulta .= " AND creador IN (".implode(',', $usuarios).")";
				$consulta_sql .= " AND creador IN (".implode(',', $usuarios).")";
			}
			$consulta .= " ORDER BY prioridad DESC, fecha_creacion desc, hora_creacion desc";
			$incidencias = $this->post->buscar_tickets($consulta);
			$tabla = '';
			$version_movil = '';
			/* Select empresas */
			$empresas = $this->post->get_empresas();
			$html_empresas='';
			if($this->input->post('empresa') == 0){
				$html_empresas.='<option value="0" selected>TODAS</option>';
			}else{
				$html_empresas.='<option value="0">TODAS</option>';
			}
			foreach($empresas->result() as $empresa){
				if($empresa->id == $this->input->post('empresa')){
					$html_empresas.='<option value="'.$empresa->id.'" selected>'.$empresa->empresa.'</option>';
				}else{
					$html_empresas.='<option value="'.$empresa->id.'">'.$empresa->empresa.'</option>';
				}
			}			
			/* Select salones */
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || (($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9) && $this->session->userdata('logged_in')['acceso'] == 24)){
				if(!empty($this->input->post('operador'))){
					$salones = $this->post->get_salones_operadora($this->input->post('operador'));
				}else{
					$salones = $this->post->get_salones();
				}
				$html_salones='';
				foreach($salones->result() as $salon){
					if($this->input->post('salon') == $salon->id){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}
			}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6){
				$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				$html_salones='';
				foreach($salones->result() as $salon){
					$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
				}
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$salon = $this->post->get_salones_rol_salon($this->session->userdata('logged_in')['acceso']);
				$html_salones='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
			}else{
				$html_salones='';
			}
			
			/* Select operadora - rol 3 */
			if($this->session->userdata('logged_in')['rol'] == 3){
				$html_op='';
				$operadora = $this->post->get_salones_model($this->session->userdata('logged_in')['acceso']);
				foreach($operadora->result() as $opera){
					$html_op.='<option value="'.$opera->id.'" selected>'.$opera->operadora.'</option>';
				} 	
			}else{
				/* Select operadores */
				if(!empty($this->input->post('empresa'))){
					$operadoras = $this->post->get_operadoras_empresa_nombre($this->input->post('empresa'));
				}else{
					$operadoras = $this->post->get_operadoras();
				}
				$html_operadora='';
				foreach($operadoras->result() as $operadora){
					if($operadora->id == $this->input->post('operador')){
						$html_operadora.='<option value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
					}else{
						$html_operadora.='<option value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
					}
				}
			}
			$array_usuarios_operadora = array();
			$usuarios_operadora = $this->post->get_usuarios_operadora($this->session->userdata('logged_in')['acceso']);
			foreach($usuarios_operadora->result() as $usuario_operadora){
				array_push($array_usuarios_operadora, $usuario_operadora->id);
			}
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
				$destino_incidencia = $this->post->get_destino_incidencia($this->session->userdata('logged_in')['acceso']);
			}
			$numero_filas = 0;
			if($incidencias->num_rows() > 0){
				$usuarios_adm = $this->post->get_usuarios_adm();
				foreach($incidencias->result() as $incidencia){
					/* Incidencias CIRSA */
					if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 2){
						if($incidencia->tipo_averia == 11){
							continue;
						}
					}

					/* Comprobra gestion ATC operadoras activo y creador ADM */
					if($this->session->userdata('logged_in')['acceso'] == 24 && $this->session->userdata('logged_in')['rol'] != 3){
						if(in_array($incidencia->creador, $usuarios_adm)){

						}else{
							if($incidencia->situacion == 2 && $incidencia->destino == 4){
								
							}else{
								continue;
							}
						}
						
						if($incidencia->tipo_averia != '6' && $incidencia->tipo_averia != '3' && $incidencia->tipo_averia != '11'){
							$gestion_activa = $this->post->get_gestion_activa($incidencia->empresa);
							if($gestion_activa->tipo_gestion == 0){
								continue;
							}
						}
					}
					$numero_filas++;			
					/* Comprobar situacion */
					$ediciones = $this->post->get_ultima_edicion($incidencia->id);
					if(!empty($ediciones)){
						if($incidencia->situacion != 9){
							$situacion = $this->post->get_situacion($ediciones);
						}else{
							$situacion = $this->post->get_situacion($incidencia->situacion);
						}
					}else{
						$situacion = $this->post->get_situacion($incidencia->situacion);
					}
					if($incidencia->situacion == 5){
						if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6){
							if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
								$tabla.='<tr style="background: #F7D8BA; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else if($incidencia->detalle_error == 699){
								$tabla.='<tr style="background: #edb8e9; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else{
								$tabla.='<tr style="background: powderblue; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}
						}else{
							if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
								$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else if($incidencia->detalle_error == 699){
								$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else{
								$tabla.='<tr style="background: powderblue;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}
						}
					}else if($incidencia->situacion == 6){
						if($this->session->userdata('logged_in')['rol'] == 1){
							$tabla.='<tr class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
						}else{
							$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
						}
					}else{
						if($incidencia->situacion != 9){
							if($this->session->userdata('logged_in')['rol'] == 1){
								if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
									$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else if($incidencia->detalle_error == 699){
									$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else{
									$tabla.='<tr class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}
							}else{
								if($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24){
									$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
								}else if($this->session->userdata('logged_in')['rol'] == 2){
									if($incidencia->destino != 4){
										$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else{
										$tabla.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}
								}else{
									if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
										$tabla.='<tr style="background: #F7D8BA;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else if($incidencia->detalle_error == 699){
										$tabla.='<tr style="background: #edb8e9;" class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}else{
										$tabla.='<tr class="clickable-row" data-href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';
									}
								}
							}
						}else{
							if($incidencia->detalle_error == 610 || $incidencia->detalle_error == 662){
								$tabla.='<tr style="background: #F7D8BA; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else if($incidencia->detalle_error == 699){
								$tabla.='<tr style="background: #edb8e9; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}else{
								$tabla.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$incidencia->id.'</td>';
							}
						}						
					}					
					$version_movil.='<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
					
					$fecha_creacion = explode("-", $incidencia->fecha_creacion);
					$fecha = $fecha_creacion[2]."/".$fecha_creacion[1]."/".$fecha_creacion[0];
					
					$tabla.='<td>'.$fecha.' '.$incidencia->hora_creacion.'</td>';
					
					if($situacion == "Solucionada"){
						$tabla.='<td style="color: #449d44; font-weight: bold">'.$situacion.'</td>';					
					}else{
						if($incidencia->situacion == 5 && $incidencia->fecha_caducidad != ''){
							$tabla.='<td style="color: #b21a30; font-weight: bold">'.$situacion.' ('.$incidencia->fecha_caducidad.')</td>';
						}else{
							if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || (($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24)){
								$tabla.='<td style="color: #b21a30; font-weight: bold">'.$situacion.'</td>';
							}else{
								if($incidencia->destino == 4){
									$tabla.='<td style="color: #eb9316; font-weight: bold">'.$situacion.' ADM</td>';
								}else{
									$tabla.='<td style="color: #b21a30; font-weight: bold">'.$situacion.'</td>';
								}
							}
						}				
					}
							
					$operadora = $this->post->get_operadora($incidencia->operadora);			
					$salon = $this->post->get_salon($incidencia->salon);
					$salon_completo = $this->post->get_salon_completo($incidencia->salon);
					$averia = $this->post->get_averia($incidencia->tipo_averia);			
					$tipo_error = $this->post->get_tipo_error($incidencia->tipo_error);
					$detalle_error = $this->post->get_detalle_error($incidencia->detalle_error);		
					$maquina = $this->post->get_maquina($incidencia->maquina);			
					$destino = $this->post->get_destino($incidencia->destino);
					$creador = $this->post->get_creador($incidencia->creador);
					$creador2 = $this->post->get_creador_completo($incidencia->creador);
							
					if($this->session->userdata('logged_in')['rol'] == 1){				
						$tabla .= '<td>'.$operadora.'</td>';
					}
					
					$tabla .= '<td style="font-weight: bold">'.$salon.'<span class="region" style="display: none">'.$salon_completo->empresa.'</span></td>';
					
					if($this->session->userdata('logged_in')['rol'] == 1){											
						if($creador2->rol == 1){
							$tabla .= '<td>'.$incidencia->nombre.'</td>
										<td>'.$incidencia->telefono.'</td>';
						}else{
							$tabla .= '<td>'.$creador2->nombre.'</td>
										<td>'.$creador2->telefono.'</td>';
						}						
					}
					
					$tabla .= '<td>'.$averia->gestion.'</td>
								<td>'.$tipo_error.'</td>
								<td>'.$detalle_error.'</td>
								<td>'.$maquina.'</td>';
					
					if($this->session->userdata('logged_in')['rol'] == 1){
						$tabla .= '<td>'.$destino.'</td>';
					}
											
					$tabla .= '<td>'.$creador.'</td>';
											
					$editada = $this->post->get_ediciones_incidencia($incidencia->id);
					
					if($editada->num_rows() == 1){
						$edicion = $editada->row();
						$editor = $this->post->get_creador($edicion->creador);
						$fecha_edicion = explode('-', $edicion->fecha_edicion);
						$tabla .= '<td class="td_editada">
										<span class="span_editada">
											Editada
										</span>
										<div class="div_editada" style="display: none;">
											<p>'.$fecha_edicion[2].'/'.$fecha_edicion[1].'/'.$fecha_edicion[0].' '.$edicion->hora_edicion.'</p>
											<p>'.$editor.'</p>												
										</div>
									</td>';
					}else{
						$tabla .= '<td class="td_editada">
										<span class="span_editada">
											Editada
										</span>
										<div class="div_editada" style="display: none;">
											<p>Nadie</p>
										</div>
									</td>';
					}
					
					if($incidencia->asignado == 0){
						$tabla .= '<td style="width: 100px">Nadie</td>';
					}else{
						$asignado = $this->post->get_creador($incidencia->asignado);
						$tabla .= '<td style="width: 100px">'.$asignado.'</td>';
					}
					
					if($incidencia->tratamiento == 0){
						$tabla .= '<td style="width: 100px">Nadie</td>';
					}else{
						$tratamiento = $this->post->get_creador($incidencia->tratamiento);
						$tabla .= '<td style="width: 100px">'.$tratamiento.'</td>';
					}
					
					if($incidencia->soluciona != 0){
						$soluciona = $this->post->get_creador($incidencia->soluciona);
						$tabla .= '<td>'.$soluciona.'</td>';
					}else{
						if($mostrar_soluciona == 1){
							$tabla .= '<td>Nadie</td>';
						}
					}
				
					if($situacion == "Solucionada"){
						$version_movil.='<div class="panel-heading" style="background: #449d44; text-align: center; padding: 5px 4px; font-size: 13px">
											<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
										</div>
										<div class="panel-body" style="padding: 0; border: none">
											<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
											<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
											 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
											 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
											 </div>
											 <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
												<p><span style="font-weight: bold">Situación: </span><span style="color: #449d44;">'.$situacion.'</span></p>';
					}else{
						if(in_array($incidencia->creador, $usuarios_adm)){
							$version_movil.='<div class="panel-heading" style="background: #d9534f; text-align: center; padding: 5px 4px; font-size: 13px">
												<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
											</div>
											<div class="panel-body" style="padding: 0; border: none">
												<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
													<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
												    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
												    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
												    </div>
												    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
														<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion.'</span></p>';
						}else{
							if($incidencia->destino == 4){
								$version_movil.='<div class="panel-heading" style="background: #eb9316; text-align: center; padding: 5px 4px; font-size: 13px">
													<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
												</div>
												<div class="panel-body" style="padding: 0; border: none">
													<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
														</div>
														<div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
															<p><span style="font-weight: bold">Situación: </span><span style="color: #eb9316;">'.$situacion.' ADM</span></p>';
							}else{
								$version_movil.='<div class="panel-heading" style="background: #d9534f; text-align: center; padding: 5px 4px; font-size: 13px">
													<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.' '.$operadora.' '.$salon.'</p>
												</div>
												<div class="panel-body" style="padding: 0; border: none">
													<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
														<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
								 						<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
								 						<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
								 						</div>
								 						<div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
															<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion.'</span></p>';
							}
						}
					}
					
					$prioridad = $this->post->get_prioridad_id($incidencia->prioridad);
					if($incidencia->prioridad == 0){
						$tabla .= '<td style="width: 50px; color: #ffd600; font-weight: bold">'.$prioridad->prioridad.'</td>';
						$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #ffd600">'.$prioridad->prioridad.'</span></p>';
					}else if($incidencia->prioridad == 1){
						$tabla .= '<td style="width: 50px; color: #449d44; font-weight: bold">'.$prioridad->prioridad.'</td>';
						$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #449d44">'.$prioridad->prioridad.'</span></p>';
					}else if ($incidencia->prioridad == 2){
						$tabla .= '<td style="width: 50px; color: #b21a30; font-weight: bold">'.$prioridad->prioridad.'</td>';
						$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #b21a30">'.$prioridad->prioridad.'</span></p>';
					}else if ($incidencia->prioridad == 3){
						$tabla .= '<td style="width: 50px; color: #138496; font-weight: bold">Programada</td>';
						$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #138496">Programada</span></p>';
					}
					
					$version_movil.='<p><span style="font-weight: bold">Máquina:</span> '.$maquina.'</p>';									
											
					if($this->session->userdata('logged_in')['rol'] == 1){
						$version_movil.='<p><span style="font-weight: bold">Destino:</span> '.$destino.'</p>
															<p><span style="font-weight: bold">Nombre:</span> '.$incidencia->nombre.'</p>
															<p><span style="font-weight: bold">Teléfono:</span> '.$incidencia->telefono.'</p>';
					}
					
					if($incidencia->asignado != 0){
						$asignado = $this->post->get_creador($incidencia->asignado);
						$version_movil.='<p><span style="font-weight: bold">Asignado:</span> '.$asignado.'</p>';
					}
					
					if($incidencia->tratamiento != 0){
						$tratamiento = $this->post->get_creador($incidencia->tratamiento);
						$version_movil.='<p><span style="font-weight: bold">Tratamiento:</span> '.$tratamiento.'</p>';
					}
					
					if($incidencia->soluciona != 0){
						$soluciona = $this->post->get_creador($incidencia->soluciona);
						$version_movil.='<p><span style="font-weight: bold">Solucionado:</span> '.$soluciona.'</p>';
					}

					if($situacion == "Solucionada"){
						if($incidencia->situacion == 8){
							if($this->session->userdata('logged_in')['rol'] == 1){
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">
											<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
											<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
										</td></tr>';
								$version_movil.='<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>
									<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}else{
								$tabla.='</tr>';
								$version_movil.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}
						}else{
							if($this->session->userdata('logged_in')['rol'] == 1){
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">
											<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
											<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
										</td></tr>';
								$version_movil.='<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>';
								$version_movil.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}else{
								$tabla.='</tr>';
								if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
									$version_movil.='<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('editar_incidencia/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
								}else{
									$version_movil.='<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
								}								
							}
						}
					}else{
						if($incidencia->situacion == 8){
							if($this->session->userdata('logged_in')['rol'] == 1){
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">
											<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
											<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
										</td></tr>';
								$version_movil.='<a style="width: 30%; padding: 4px 5px; margin: 0 4px;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>
									<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}
						}else{
							if($this->session->userdata('logged_in')['rol'] == 1){
								$tabla.='<td style="width: 150px !important; padding: 5px 0;">
											<a style="padding: 4px 5px; margin: 0;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
											<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>';
								$version_movil.='<a style="width: 30%; padding: 4px 5px; margin: 0 4px;" type="button" class="btn btn-primary llamar_ticket" id="'.$incidencia->id.'" href="'.base_url('llamar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" alt="LLamar" title="LLamar"><i style="font-size: 30px" class="fa fa-phone"></i><span style="display: block; font-weight: bold; font-size: 10px">LLamar</span></a>';
							}else{
								$tabla.='<td>';
							}

							if(($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24) && $incidencia->asignado == 0){
								$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}else{
								$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>';
							}										
							
							if($incidencia->asignado == 0 && $this->session->userdata('logged_in')['rol'] == 6 && $incidencia->situacion == 12){
								$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
								$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
							}
							
							if($incidencia->asignado == 0 && ($this->session->userdata('logged_in')['rol'] == 4 || ($this->session->userdata('logged_in')['rol'] == 2))){
									if($incidencia->prioridad != 3){
										if($this->session->userdata('logged_in')['acceso'] == 24){											
											if($this->session->userdata('logged_in')['rol'] == 2){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
												$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
											}
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
											$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
										}else{
											if($incidencia->situacion == 2 && $this->session->userdata('logged_in')['acceso'] == 41){									
												if($incidencia->situacion != 1){
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
													$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
												}											
											}else if(($incidencia->situacion == 2 || $incidencia->situacion == 13) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
												if($this->session->userdata('logged_in')['rol'] == 2){
													if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
														$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';	
														$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
														$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
														$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
													}
												}else{
													if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
														$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
														$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
													}
												}
											}
										}
									}
								}
								
								if($incidencia->asignado == 0 && $this->session->userdata('logged_in')['rol'] == 6 && $incidencia->situacion == 12){
									if ($incidencia->prioridad != 3){
										$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
										$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$incidencia->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
									}
								}
								
								if($incidencia->asignado == $this->session->userdata('logged_in')['id']){
									if($incidencia->situacion != 9){
										if($this->session->userdata('logged_in')['rol'] == 6){
											if($incidencia->situacion != 2){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
												$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
											}
										}else if($this->session->userdata('logged_in')['rol'] == 2){
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
											$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
											$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
										}else{
											$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Solucionar Ticket" title="Solucionar Ticket"><i class="fa fa-check"></i></a>';
											$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
										}
									}
								}else if($incidencia->asignado != 0){
									$asignado = $this->post->get_creador_completo($incidencia->asignado);
									if($this->session->userdata('logged_in')['rol'] == 2){
										if($incidencia->situacion != 9){								
											if($this->session->userdata('logged_in')['acceso'] == 24){
												$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
												$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
											}else if($incidencia->operadora == 41){
												if(($incidencia->destino == 230 || $incidencia->destino == 4) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" margin: 0;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
													$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
												}
											}else if(($incidencia->situacion == 2 || $incidencia->situacion == 13) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
												if($incidencia->destino != 4 && $incidencia->destino != 32 && $incidencia->destino != 244 && $incidencia->destino != 230){
													$tabla .= '<a style="padding: 2px 4px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444;  color: #ffffff; display: inline-block; font-size: 19px; text-align: center;" margin: 0;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
													$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
												}
											}
										}
									}else{
										if($incidencia->situacion != 9){
											$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$consulta_sql.'').'" type="button" class="btn btn-success" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
										}
									}
								}

							}					
						}

						$tabla .= '</td></tr>';
						$version_movil.='</div></div></div>';
				}
			}else{
				$tabla.='<tr><td style="font-weight: bold">Sin resultados</td></tr>';
				$version_movil.='<div class="col-md-12" style="border: 1px solid #ccc; border-radius: 5px; margin: 4% 0; padding: 5%;">
														<p style="font-weight: bold">Sin resultados</p>
													</div>';
			}

			$data = array('title' => '', 'html_empresas' => $html_empresas, 'nombre_empresa' => $this->input->post('empresa'), 'html_salones' => $html_salones, 'tabla_incidencias' => $tabla, 'version_movil' => $version_movil, 'fecha_inicio' => $this->input->post('fecha_inicio_incidencia'), 'fecha_fin' => $this->input->post('fecha_fin_incidencia'), 'tipo_error_error' => $this->input->post('error'), 'tipo_error_info' => $this->input->post('info'), 'tipo_error_recla' => $this->input->post('recla'),'tipo_error_suge' => $this->input->post('suge'), 'pend_rev' => $this->input->post('pend_rev'), 'pend_sat' => $this->input->post('pend_sat'), 'pend_euska' => $this->input->post('pend_euska'), 'pend_trat' => $this->input->post('pend_trat'), 'pend_trata' => $this->input->post('pend_trata'), 'pend_cadu' => $this->input->post('pend_cadu'), 'pend_llamar' => $this->input->post('pend_llamar'), 'pend_com' => $this->input->post('pend_com'), 'pend_inf' => $this->input->post('pend_inf'), 'pend_tec_op' => $this->input->post('pend_tec_op'), 'solucio' => $this->input->post('solucio'), 'cerra' => $this->input->post('cerra'), 'consulta' => $consulta_sql, 'numero_filas' => $numero_filas, 'agrupar_volver' => $agrupar_volver, 'agrupar_volver_columna' => $agrupar_volver_columna);

			if($this->session->userdata('logged_in')['rol'] == 3){
				$data['html_op'] = $html_op;
			}else{
				$data['html_operadora'] = $html_operadora;				
			}

			if($mostrar_soluciona == 1){
				$data['solucionado'] = 1;				
			}else{
				$data['solucionado'] = 0;
			}
			$this->load_view('gestion_incidencias', $data);
		}
	}
	
	/* Agrupar personal */
	public function agrupar_personal(){
		$columna = $this->input->post('col');
		$sql = $this->input->post('sql');
		$html_agrupados = '';
		$html_agrupados = '<a id="volver_agrupado" style="margin-top: 8px" href="#" class="btn btn-danger dropdown-toggle">Volver</a>';
		$agrupados = $this->post->agrupar_personal($columna,$sql);
		foreach($agrupados->result() as $agrupado){
			$html_agrupados .= "<div class='agrupado_div' style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 10px 0; padding: 10px; box-shadow: 1px 1px 0px #ccc; cursor: pointer'>
															<i class='fa fa-plus-circle' aria-hidden='true'></i><i style='display: none' class='fa fa-minus-circle' aria-hidden='true'></i>";
			switch($columna){
				case "operadora":
					if($agrupado->operadora == 0){
						$valor = 0;
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>DESCONOCIDA</span>";
						break;
					}else{
						$valor = $agrupado->operadora;
						$operadora_nombre = $this->post->get_operadora($agrupado->operadora);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$operadora_nombre."</span>";
						break;
					}
				case "salon":
					if($agrupado->salon == 0){
						$valor = 0;
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>DESCONOCIDO / ROTATIVOS</span>";
						break;
					}else{
						$valor = $agrupado->salon;
						$operadora_nombre = $this->post->get_salon($agrupado->salon);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$operadora_nombre."</span>";
						break;
					}					
			}
			
			$html_agrupados .="</div>";
			
			$html_agrupados .="<div style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 20px 0; padding: 10px; display: none'>";
			
			$html_agrupados .= '<table class="table tabla_incidencias">
													<thead>
														<tr>
															<th class="th_tabla">Salón</th>
															<th class="th_tabla">Operadora</th>
															<th class="th_tabla">Nombre</th>
															<th class="th_tabla">DNI</th>
															<th class="th_tabla">Teléfono</th>
															<th class="th_tabla">Email</th>
															<th class="th_tabla">Curso</th>
															<th class="th_tabla">Carnet</th>
															<th class="th_tabla">Fecha Carnet</th>
															<th class="th_tabla">Observaciones</th>
															<th class="th_tabla">Test</th>
															<th class="th_tabla">Activo</th>
															<th class="th_tabla">Acciones</th>
														</tr>
													</thead>
													<tbody class="tabla_agrupados">';
			
			$grupo_result = $this->post->get_personal_group($columna,$valor,$sql);
			foreach($grupo_result->result() as $fila){
				if($fila->operadora == 0){
					$op = "Desconocida";
				}else{
					$op = $this->post->get_operadoras_rol_2($fila->operadora);
					$operadora = $op->row();
					$op = $operadora->operadora;
				}
				
				if($fila->salon == 0){
					$salon = "Desconocido/Rotativos";
				}else{
					$salon = $this->post->get_salon($fila->salon);
				}
				
				if($this->session->userdata('logged_in')['rol'] == 6){
					$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('editar_personal/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" style="cursor: pointer">';
				}
				
				$html_agrupados.='<td>'.$salon.'</td>
													 <td>'.$op.'</td>
													 <td>'.$fila->nombre.'</td>
													 <td>'.$fila->dni.'</td>
													 <td>'.$fila->telefono.'</td>
													 <td>'.$fila->email.'</td>
													 <td>'.$fila->curso.'</td>
													 <td>'.$fila->carnet.'</td>
													 <td>'.$fila->fecha_carnet.'</td>
													 <td>'.$fila->observaciones.'</td>
													 <td>'.$fila->test.'</td>
													 <td>'.$fila->activo.'</td>
													 <td>';
				
				$html_agrupados.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_personal/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-info" alt="Ficha local" title="Ficha local"><i class="fa fa-eye"></i></a>
													<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('eliminar_personal/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar"><i class="fa fa-close"></i></a>';
				
				if($this->session->userdata('logged_in')['rol'] == 6){
					$html_agrupados .= '<a style="padding: 2px 4px; margin: 4px 0;" href="'.base_url('personal_img/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Añadir imágenes" title="Añadir imágenes"><i class="fa fa-image"></i></a>';
				}
				
				$html_agrupados.='</td>
												</tr>';
				
			}
			
			$html_agrupados .="</tbody>
				</table></div>";
			
		}
		echo $html_agrupados;
	}
	
	/* Agrupar personal */
	public function agrupar_promos(){
		$columna = $this->input->post('col');
		$html_agrupados = '';
		$html_agrupados = '<a id="volver_agrupado" style="margin-top: 8px" href="#" class="btn btn-danger dropdown-toggle">Volver</a>';
		$agrupados = $this->post->agrupar_promos($columna);
		foreach($agrupados->result() as $agrupado){
			$html_agrupados .= "<div class='agrupado_div' style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 10px 0; padding: 10px; box-shadow: 1px 1px 0px #ccc; cursor: pointer'>
															<i class='fa fa-plus-circle' aria-hidden='true'></i><i style='display: none' class='fa fa-minus-circle' aria-hidden='true'></i>";
			switch($columna){
				case "salon":
					$valor = $agrupado->salon;
					$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$agrupado->salon."</span>";
					break;				
			}
			
			$html_agrupados .="</div>";
			
			$html_agrupados .="<div style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 20px 0; padding: 10px; display: none'>";
			
			$html_agrupados .= '<table class="table tabla_incidencias">
													<thead>
														<tr>
															<th class="th_tabla">ID</th>
															<th class="th_tabla">Salón</th>
															<th class="th_tabla">Nombre</th>
															<th class="th_tabla">Teléfono</th>
															<th class="th_tabla">Email</th>
															<th class="th_tabla">Ticket</th>
														</tr>
													</thead>
													<tbody class="tabla_agrupados">';
			
			$grupo_result = $this->post->get_promos_group($columna,$valor);
			foreach($grupo_result->result() as $fila){
				
				$html_agrupados .= '<tr style="font-family: Open Sans,Helvetica,Arial,sans-serif; font-size: 13px; color: #000;">
															 <td>'.$fila->id.'</td>
															 <td>'.$fila->salon.'</td>
															 <td>'.$fila->nombre.'</td>
															 <td>'.$fila->telefono.'</td>
															 <td>'.$fila->email.'</td>
															 <td>'.$fila->ticket.'</td>
													 </tr>';
				
			}
			
			$html_agrupados .="</tbody>
				</table></div>";
			
		}
		echo $html_agrupados;
	}
	
	/* Agrupar personal */
	public function agrupar_visitas(){
		$columna = $this->input->post('col');
		$sql = $this->input->post('sql');
		$html_agrupados = '';
		$html_agrupados = '<a id="volver_agrupado" style="margin-top: 8px" href="#" class="btn btn-danger dropdown-toggle">Volver</a>';
		$agrupados = $this->post->agrupar_visitas($columna,$sql);
		foreach($agrupados->result() as $agrupado){
			$html_agrupados .= "<div class='agrupado_div' style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 10px 0; padding: 10px; box-shadow: 1px 1px 0px #ccc; cursor: pointer'>
															<i class='fa fa-plus-circle' aria-hidden='true'></i><i style='display: none' class='fa fa-minus-circle' aria-hidden='true'></i>";
			switch($columna){
				case "operadora":
					if($agrupado->operadora == 0){
						$valor = 0;
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>DESCONOCIDA</span>";
						break;
					}else{
						$valor = $agrupado->operadora;
						$operadora_nombre = $this->post->get_operadora($agrupado->operadora);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$operadora_nombre."</span>";
						break;
					}
				case "salon":
					if($agrupado->salon == 0){
						$valor = 0;
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>DESCONOCIDO</span>";
						break;
					}else{
						$valor = $agrupado->salon;
						$operadora_nombre = $this->post->get_salon($agrupado->salon);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$operadora_nombre."</span>";
						break;
					}					
			}
			
			$html_agrupados .="</div>";
			
			$html_agrupados .="<div style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 20px 0; padding: 10px; display: none'>";
			
			$html_agrupados .= '<table class="table tabla_incidencias">
													<thead>
														<tr>
															<th class="th_tabla">Salón</th>
															<th class="th_tabla">Operadora</th>
															<th class="th_tabla">Fecha</th>
															<th class="th_tabla">Personal1</th>
															<th class="th_tabla">Personal2</th>
															<th class="th_tabla">Observaciones</th>
															<th class="th_tabla">Acciones</th>
														</tr>
													</thead>
													<tbody class="tabla_agrupados">';
			
			$grupo_result = $this->post->get_visitas_group($columna,$valor,$sql);
			foreach($grupo_result->result() as $fila){
				if($fila->operadora == 0){
					$op = "Desconocida";
				}else{
					$op = $this->post->get_operadoras_rol_2($fila->operadora);
					$operadora = $op->row();
					$op = $operadora->operadora;
				}
				
				if($fila->salon == 0){
					$salon = "Desconocido";
				}else{
					$salon = $this->post->get_salon($fila->salon);
				}
				
				$observaciones = substr($fila->observaciones, 0, 100);
				
				if($this->session->userdata('logged_in')['rol'] == 6){
					$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('editar_visita/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" style="cursor: pointer">';
				}
				
				$html_agrupados.='<td>'.$salon.'</td>
													 <td>'.$op.'</td>
													 <td>'.$fila->fecha.'</td>
													 <td>'.$fila->personal1.'</td>
													 <td>'.$fila->personal2.'</td>
													 <td>'.$observaciones.'</td>
													 <td>';
				
				$html_agrupados.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_visita/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-info" alt="Ficha visita" title="Ficha visita"><i class="fa fa-eye"></i></a>
													<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('eliminar_visita/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar"><i class="fa fa-close"></i></a>';
				
				$html_agrupados.='</td>
												</tr>';
				
			}
			
			$html_agrupados .="</tbody>
				</table></div>";
			
		}
		echo $html_agrupados;
	}
	
	/* Agrupar locales */
	public function agrupar_locales(){
		$columna = $this->input->post('col');
		$sql = $this->input->post('sql');
		$html_agrupados = '';
		$html_agrupados = '<a id="volver_agrupado" style="margin-top: 8px" href="#" class="btn btn-danger dropdown-toggle">Volver</a>';
		$agrupados = $this->post->agrupar_locales($columna,$sql);
		foreach($agrupados->result() as $agrupado){
			$html_agrupados .= "<div class='agrupado_div' style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 10px 0; padding: 10px; box-shadow: 1px 1px 0px #ccc; cursor: pointer'>
															<i class='fa fa-plus-circle' aria-hidden='true'></i><i style='display: none' class='fa fa-minus-circle' aria-hidden='true'></i>";
			switch($columna){
				case "operadora":
					$valor = $agrupado->operadora;
					$operadora_nombre = $this->post->get_operadora($agrupado->operadora);
					$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$operadora_nombre."</span>";
					break;
					
			}
			
			$html_agrupados .="</div>";
			
			$html_agrupados .="<div style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 20px 0; padding: 10px; display: none'>";
			
			$html_agrupados .= '<table class="table tabla_incidencias">
													<thead>
														<tr>
															<th class="th_tabla">Salón</th>
															<th class="th_tabla">Operadora</th>
															<th class="th_tabla">Dirección</th>
															<th class="th_tabla">Población</th>
															<th class="th_tabla">Teléfono</th>
															<th class="th_tabla">Horario</th>
															<th class="th_tabla">IP Internet</th>
															<th class="th_tabla">IP Euskaltel</th>
															<th class="th_tabla">Fecha Alta</th>
															<th class="th_tabla">Acciones</th>
														</tr>
													</thead>
													<tbody class="tabla_agrupados">';
			
			$grupo_result = $this->post->get_locales_group($columna,$valor,$sql);
			foreach($grupo_result->result() as $fila){
				$op = $this->post->get_operadoras_rol_2($fila->operadora);
				$operadora = $op->row();
				
				if($this->session->userdata('logged_in')['rol'] == 2){
					$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('editar_local/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" style="cursor: pointer">';
				}else{
					$html_agrupados.='<tr style="font-family: Open Sans,Helvetica,Arial,sans-serif; font-size: 13px; color: #000;">';
				}
				
				$html_agrupados.='<td style="width: 1% !important">'.$fila->salon.'</td>
													<td>'.$operadora->operadora.'</td>
													<td>'.$fila->direccion.'</td>
													<td>'.$fila->poblacion.'</td>
													<td>'.$fila->telefono.'</td>
													<td>'.$fila->horario.'</td>
													<td>'.$fila->ip_internet.'</td>
													<td>'.$fila->ip_lan_euskaltel.'</td>
													<td>'.$fila->fecha_alta.'</td>
													<td>';
				
				$html_agrupados.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_local/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-info" alt="Ficha local" title="Ficha local"><i class="fa fa-eye"></i></a>';									
				
				if($this->session->userdata('logged_in')['rol'] == 2){
					if($fila->Activo == 1){
						$html_agrupados .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('desactivar_local/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-danger" alt="Desactivar" title="Desactivar"><i class="fa fa-stop"></i></a>';
					}else{
						$html_agrupados .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_local/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Activar" title="Activar"><i class="fa fa-play"></i></a>';
					}
				}
				
				if($this->session->userdata('logged_in')['rol'] == 6){
					$html_agrupados .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('local_img/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Añadir imágenes" title="Añadir imágenes"><i class="fa fa-image"></i></a>';
				}
														
				$html_agrupados.='</td>
												</tr>';
				
			}
			
			$html_agrupados .="</tbody>
				</table></div>";
			
		}
		echo $html_agrupados;
	}
	
	/* Agrupar incidencias */
	public function agrupar_tickets(){
		$columna = $this->input->post('col');
		$sql = $this->input->post('sql');
		$html_agrupados = '';
		$html_agrupados = '<a id="volver_agrupado" style="margin-top: 8px" href="#" class="btn btn-danger dropdown-toggle">Volver</a>';
		$agrupados = $this->post->agrupar_tickets($columna,$sql);
		
		/* Mostrar incidencias */
		foreach($agrupados->result() as $agrupado){
			/*  Comprobar si hay alguna incidencia que mostrar */
			switch($columna){
				case "situacion":
					$valor = $agrupado->situacion;
					break;
				case "operadora":
					$valor = $agrupado->operadora;
					break;
				case "salon":
					$valor = $agrupado->salon;
					break;
				case "nombre":
					$valor = $agrupado->nombre;
					break;
				case "telefono":
					$valor = $agrupado->telefono;
					break;
				case "tipo_averia":
					$valor = $agrupado->tipo_averia;
					break;
				case "maquina":
					$valor = $agrupado->maquina;
					break;
				case "tipo_error":
					$valor = $agrupado->tipo_error;
					break;
				case "detalle_error":
					$valor = $agrupado->detalle_error;
					break;
				case "destino":
					$valor = $agrupado->destino;
					break;
				case "fecha_creacion":
					$valor = $agrupado->fecha_creacion;
					break;
				case "creador":
					$valor = $agrupado->creador;
					break;
				case "asignado":
					$valor = $agrupado->asignado;
					break;
				case "tratamiento":
					$valor = $agrupado->tratamiento;
					break;
				case "soluciona":
					$valor = $agrupado->soluciona;
					break;
			}
			
			$grupo_result = $this->post->get_ticket_group($columna,$valor,$sql);
			$total_tickets = $grupo_result->num_rows();
			$usuarios_adm = $this->post->get_usuarios_adm();
			foreach($grupo_result->result() as $fila){
				/* Incidencias CIRSA */
				if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 2){
					if($fila->tipo_averia == 11){
						continue;
					}
				}

				/* Pendiente Kirol */
				if($this->session->userdata('logged_in')['rol'] != 1){
					if($fila->situacion == 8){
						$total_tickets = $total_tickets - 1;
						continue;
					}
				}
				
				/* Comprobra gestion ATC operadoras activo y creador ADM */
				if($this->session->userdata('logged_in')['acceso'] == 24 && $this->session->userdata('logged_in')['rol'] != 3){
					if(in_array($fila->creador, $usuarios_adm)){

					}else{
						$total_tickets = $total_tickets - 1;
						if($fila->situacion == 2 && $fila->destino == 4){
							
						}else{
							continue;
						}
					}
					
					if($fila->tipo_averia != '6' && $fila->tipo_averia != '3' && $fila->tipo_averia != '11'){
						$gestion_activa = $this->post->get_gestion_activa($fila->empresa);
						if($gestion_activa->tipo_gestion == 0){
							$total_tickets = $total_tickets - 1;
							continue;
						}
					}
				}
			}
			
			if($total_tickets > 0){
				$html_agrupados .= "<div class='agrupado_div' style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 10px 0; padding: 10px; box-shadow: 1px 1px 0px #ccc; cursor: pointer'>
					<i class='fa fa-plus-circle' aria-hidden='true'></i><i style='display: none' class='fa fa-minus-circle' aria-hidden='true'></i>";
				switch($columna){
					case "situacion":
						$valor = $agrupado->situacion;
						$situacion_nombre = $this->post->get_situacion($agrupado->situacion);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$situacion_nombre."</span>";
						break;
					case "operadora":
						$valor = $agrupado->operadora;
						$operadora_nombre = $this->post->get_operadora($agrupado->operadora);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$operadora_nombre."</span>";
						break;
					case "salon":
						$valor = $agrupado->salon;
						$salon_nombre = $this->post->get_salon($agrupado->salon);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$salon_nombre."</span>";
						break;
					case "nombre":
						$valor = $agrupado->nombre;
						break;
					case "telefono":
						$valor = $agrupado->telefono;
						break;
					case "tipo_averia":
						$valor = $agrupado->tipo_averia;
						$averia_nombre = $this->post->get_averia($agrupado->tipo_averia);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$averia_nombre->gestion."</span>";
						break;
					case "maquina":
						$valor = $agrupado->maquina;
						$maquina_nombre = $this->post->get_maquina($agrupado->maquina);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$maquina_nombre."</span>";
						break;
					case "tipo_error":
						$valor = $agrupado->tipo_error;
						$tipo_error = $this->post->get_tipo_error_completo($agrupado->tipo_error);
						$tipo_gestion = $this->post->get_averia($tipo_error->tipo_gestion);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$tipo_error->tipo." (".$tipo_gestion->gestion.")</span>";
						break;
					case "detalle_error":
						$valor = $agrupado->detalle_error;
						$detalle_error = $this->post->get_detalle_error_completo($agrupado->detalle_error);
						$tipo_error = $this->post->get_tipo_error_completo($detalle_error->error_tipo);
						$tipo_gestion = $this->post->get_averia($tipo_error->tipo_gestion);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$detalle_error->error_detalle." (".$tipo_error->tipo.") (".$tipo_gestion->gestion.")</span>";
						break;
					case "destino":
						$valor = $agrupado->destino;
						$destino_nombre = $this->post->get_destino($agrupado->destino);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$destino_nombre."</span>";
						break;
					case "fecha_creacion":
						$valor = $agrupado->fecha_creacion;
						$fecha = explode("-", $agrupado->fecha_creacion);
						$fecha_c = $fecha[2]."/".$fecha[1]."/".$fecha[0];
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$fecha_c."</span>";
						break;
					case "creador":
						$valor = $agrupado->creador;
						$creador_nombre = $this->post->get_creador($agrupado->creador);
						$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$creador_nombre."</span>";
						break;
					case "asignado":
						$valor = $agrupado->asignado;
						if($agrupado->asignado == 0){
							$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>Nadie</span>";
						}else{
							$asignado_nombre = $this->post->get_creador($agrupado->asignado);
							$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$asignado_nombre."</span>";
						}
						break;
					case "tratamiento":
						$valor = $agrupado->tratamiento;
						if($agrupado->tratamiento == 0){
							$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>Nadie</span>";
						}else{
							$asignado_nombre = $this->post->get_creador($agrupado->tratamiento);
							$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$asignado_nombre."</span>";
						}
						break;
					case "soluciona":
						$valor = $agrupado->soluciona;
						if($agrupado->soluciona == 0){
							$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>Nadie</span>";
						}else{
							$solucionado_nombre = $this->post->get_creador($agrupado->soluciona);
							$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$solucionado_nombre."</span>";
						}
						break;
				}

				$html_agrupados .=" <span style='font-weight: bold'>(".$total_tickets.")</span>";
				
				$html_agrupados .="</div>";
				
				$html_agrupados .="<div style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 20px 0; padding: 10px; display: none'>";
				
				$html_agrupados .= '<table class="table tabla_incidencias">
										<thead>
											<tr>
												<th class="th_tabla" style="width: 1% !important">Código</th>
												<th class="th_tabla">F.Creación</th>
												<th class="th_tabla">Situación</th>';
																
				if($this->session->userdata('logged_in')['rol'] == 1){					
					$html_agrupados .= '<th class="th_tabla">Operador</th> ';					
				}
				
				$html_agrupados .= '<th class="th_tabla">Salón</th>';
																
				if($this->session->userdata('logged_in')['rol'] == 1){				
					$html_agrupados .= '<th class="th_tabla">Nombre</th>
										<th class="th_tabla">Teléfono</th>';					
				}
				
				$html_agrupados .= '<th class="th_tabla">Avería</th>
									<th class="th_tabla">Tipo</th>
									<th class="th_tabla">Detalle</th>
									<th class="th_tabla">Máquina</th>';
				
				if($this->session->userdata('logged_in')['rol'] == 1){															
					$html_agrupados .= '<th class="th_tabla">Destino</th>';					
				}
				
				$html_agrupados .= '<th class="th_tabla">Autor</th>
									<th class="th_tabla">Editada</th>
									<th class="th_tabla">Asignado</th>														
									<th class="th_tabla">Tratamiento</th>
									<th class="th_tabla">Solucionado</th>
									<th class="th_tabla">Prioridad</th>
									<th class="th_tabla">Acciones</th>
								</tr>
							</thead>
							<tbody class="tabla_agrupados">';
				
				$grupo_result = $this->post->get_ticket_group($columna,$valor,$sql);
				$usuarios_adm = $this->post->get_usuarios_adm();
				foreach($grupo_result->result() as $fila){
					/* Incidencias CIRSA */
					if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 2){
						if($fila->tipo_averia == 11){
							continue;
						}
					}

					/* Pendiente Kirol */
					if($this->session->userdata('logged_in')['rol'] != 1){
						if($fila->situacion == 8){
							continue;
						}
					}
					
					/* Comprobra gestion ATC operadoras activo y creador ADM */
					if($this->session->userdata('logged_in')['acceso'] == 24 && $this->session->userdata('logged_in')['rol'] != 3){
						if(in_array($fila->creador, $usuarios_adm)){

						}else{
							if($fila->situacion == 2 && $fila->destino == 4){
							
							}else{
								continue;
							}
						}
						
						if($fila->tipo_averia != '6' && $fila->tipo_averia != '3' && $fila->tipo_averia != '11'){
							$gestion_activa = $this->post->get_gestion_activa($fila->empresa);
							if($gestion_activa->tipo_gestion == 0){
								continue;
							}
						}
					}
					
					$ediciones = $this->post->get_ultima_edicion($fila->id);
					if(!empty($ediciones)){
						if($fila->situacion != 9){
							$situacion = $this->post->get_situacion($ediciones);
						}else{
							$situacion = $this->post->get_situacion($fila->situacion);
						}
					}else{
						$situacion = $this->post->get_situacion($fila->situacion);
					}
					
					if($fila->situacion == 5){
						if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 7){
							if($fila->detalle_error == 610 || $fila->detalle_error == 662){
								$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="background: #F7D8BA; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
							}else if($fila->detalle_error == 699){
								$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="background: #edb8e9; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
							}else{
								$html_agrupados.='<tr style="background: powderblue; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
							}
						}else{
							if($fila->detalle_error == 610 || $fila->detalle_error == 662){
								$html_agrupados.='<tr style="background: #F7D8BA; cursor: pointer" class="clickable-row2" data-href="'.base_url('editar_incidencia/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'"><td style="width: 1% !important">'.$fila->id.'</td>';
							}else if($fila->detalle_error == 699){
								$html_agrupados.='<tr style="background: #edb8e9; cursor: pointer" class="clickable-row2" data-href="'.base_url('editar_incidencia/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'"><td style="width: 1% !important">'.$fila->id.'</td>';
							}else{
								$html_agrupados.='<tr style="background: powderblue; cursor: pointer" class="clickable-row2" data-href="'.base_url('editar_incidencia/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'"><td style="width: 1% !important">'.$fila->id.'</td>';
							}							
						}
					}else if($fila->situacion == 6){
						if($this->session->userdata('logged_in')['rol'] == 1){
							$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('editar_incidencia/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="cursor: pointer"><td style="width: 1% !important">'.$fila->id.'</td>';
						}else{
							$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="cursor: pointer"><td style="width: 1% !important">'.$fila->id.'</td>';
						}		
					}else{
						if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 3){
							$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
						}else{
							if($fila->situacion != 9){
								if($this->session->userdata('logged_in')['rol'] == 1){
									if($fila->detalle_error == 610 || $fila->detalle_error == 662){
										$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('editar_incidencia/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="background: #F7D8BA; cursor: pointer"><td style="width: 1% !important">'.$fila->id.'</td>';
									}else if($fila->detalle_error == 699){
										$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('editar_incidencia/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="background: #edb8e9; cursor: pointer"><td style="width: 1% !important">'.$fila->id.'</td>';
									}else{
										$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('editar_incidencia/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="cursor: pointer"><td style="width: 1% !important">'.$fila->id.'</td>';
									}
								}else{
									if($this->session->userdata('logged_in')['rol'] == 2){
										$html_agrupados.='<tr class="clickable-row2" data-href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'/').'" style="cursor: pointer"><td style="width: 1% !important">'.$fila->id.'</td>';
									}else{
										if($fila->detalle_error == 610 || $fila->detalle_error == 662){
											$html_agrupados.='<tr style="background: #F7D8BA; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
										}else if($fila->detalle_error == 699){
											$html_agrupados.='<tr style="background: #edb8e9; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
										}else{
											$html_agrupados.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
										}
									}
								}															
							}else{
								if($fila->detalle_error == 610 || $fila->detalle_error == 662){
									$html_agrupados.='<tr style="background: #F7D8BA; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
								}else if($fila->detalle_error == 699){
									$html_agrupados.='<tr style="background: #edb8e9; font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
								}else{
									$html_agrupados.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000"><td style="width: 1% !important">'.$fila->id.'</td>';
								}
							}
						}
					}
					
					$fecha_creacion = explode("-", $fila->fecha_creacion);
					$fecha = $fecha_creacion[2]."/".$fecha_creacion[1]."/".$fecha_creacion[0];						
					//$html_agrupados.='<td>'.$fecha.'  '.$fila->hora_creacion.'</td>';
					$html_agrupados.='<td class="td_editada" style="width: 5% !important"><span class="span_editada">'.$fecha.' '.$fila->hora_creacion.'</span>';

					$tiempo_incidencia = $this->post->get_tiempo_incidencia($fila->fecha_creacion." ".$fila->hora_creacion);

					$html_agrupados .= '<div class="div_fecha" style="display: none;">
									<p>'.$tiempo_incidencia.'</p>										
								</div>
							</td>';
					
					if($situacion == "Solucionada"){
						$html_agrupados.='<td style="color: #449d44; font-weight: bold">'.$situacion.'</td>';					
					}else{
						if($fila->situacion == 5 && $fila->fecha_caducidad != ''){
							$html_agrupados.='<td style="color: #b21a30; font-weight: bold">'.$situacion.' ('.$fila->fecha_caducidad.')</td>';
						}else{
							$html_agrupados.='<td style="color: #b21a30; font-weight: bold">'.$situacion.'</td>';
						}				
					}
								
					$operadora = $this->post->get_operadora($fila->operadora);			
					$salon = $this->post->get_salon($fila->salon);
					$salon_completo = $this->post->get_salon_completo($fila->salon);
					$averia = $this->post->get_averia($fila->tipo_averia);			
					$tipo_error = $this->post->get_tipo_error($fila->tipo_error);
					$detalle_error = $this->post->get_detalle_error($fila->detalle_error);			
					$maquina = $this->post->get_maquina($fila->maquina);			
					$destino = $this->post->get_destino($fila->destino);
					if($fila->creador == 0){
						$creador = "Nadie";
					}else{
						$creador = $this->post->get_creador($fila->creador);
					}
					$creador2 = $this->post->get_creador_completo($fila->creador);
					
					if($this->session->userdata('logged_in')['rol'] == 1){				
						$html_agrupados .= '<td>'.$operadora.'</td>';
					}
					
					$html_agrupados .= '<td style="font-weight: bold">'.$salon.' <span class="region" style="display: none">'.$salon_completo->empresa.'</span></td>';
					
					if($this->session->userdata('logged_in')['rol'] == 1){											
						if($creador2->rol == 1){
							$html_agrupados .= '<td>'.$fila->nombre.'</td>
												<td>'.$fila->telefono.'</td>';
						}else{
							$html_agrupados .= '<td>'.$creador2->nombre.'</td>
												<td>'.$creador2->telefono.'</td>';
						}							
					}
					
					$html_agrupados .= '<td>'.$averia->gestion.'</td>
										<td>'.$tipo_error.'</td>
										<td>'.$detalle_error.'</td>
										<td>'.$maquina.'</td>';
					
					if($this->session->userdata('logged_in')['rol'] == 1){							
						$html_agrupados .= '<td>'.$destino.'</td>';																
					}
															
					$html_agrupados .= '<td>'.$creador.'</td>';
											
					$editada = $this->post->get_ediciones_incidencia($fila->id);
											
					if($editada->num_rows() == 1){
						$edicion = $editada->row();
						$editor = $this->post->get_creador($edicion->creador);
						$fecha_edicion = explode('-', $edicion->fecha_edicion);
						$html_agrupados.= '<td class="td_editada">
											<span class="span_editada">
												Editada
											</span>
											<div class="div_editada" style="display: none;">
												<p>'.$fecha_edicion[2].'/'.$fecha_edicion[1].'/'.$fecha_edicion[0].' '.$edicion->hora_edicion.'</p>
												<p>'.$editor.'</p>												
											</div>
										</td>';	
					}else{
						$html_agrupados.= '<td class="td_editada">
											<span class="span_editada">
												Editada
											</span>
											<div class="div_editada" style="display: none;">
												<p>Nadie</p>
											</div>
										</td>';	
					}
					
					if($fila->asignado == 0){
						$html_agrupados.= '<td style="width: 100px">Nadie</td>';
					}else{
						$asignado = $this->post->get_creador($fila->asignado);
						$html_agrupados.= '<td style="width: 100px">'.$asignado.'</td>';
					}
					
					if($fila->tratamiento == 0){
						$html_agrupados .= '<td style="width: 100px">Nadie</td>';
					}else{
						$tratamiento = $this->post->get_creador($fila->tratamiento);
						$html_agrupados .= '<td style="width: 100px">'.$tratamiento.'</td>';
					}
					
					if($fila->soluciona == 0){
						$html_agrupados .= '<td style="width: 100px">Nadie</td>';
					}else{
						$soluciona = $this->post->get_creador($fila->soluciona);
						$html_agrupados .= '<td style="width: 100px">'.$soluciona.'</td>';
					}
					
					$prioridad = $this->post->get_prioridad_id($fila->prioridad);
					if($fila->prioridad == 0){
						$html_agrupados .= '<td style="width: 50px; color: #ffd600; font-weight: bold">'.$prioridad->prioridad.'</td>';
					}else if($fila->prioridad == 1){
						$html_agrupados .= '<td style="width: 50px; color: #449d44; font-weight: bold">'.$prioridad->prioridad.'</td>';
					}else if ($fila->prioridad == 2){
						$html_agrupados .= '<td style="width: 50px; color: #b21a30; font-weight: bold">'.$prioridad->prioridad.'</td>';
					}else if ($fila->prioridad == 3){
						$html_agrupados .= '<td style="width: 50px; color: #138496; font-weight: bold">Programada</td>';
					}
					
					if($situacion == "Solucionada"){
						if($fila->situacion == 8){
							if($this->session->userdata('logged_in')['rol'] == 1){
								$html_agrupados.='<td style="width: 150px !important">
													<a style="padding: 4px 5px; margin: 0 2px;" type="button" class="btn btn-primary llamar_ticket" id="'.$fila->id.'" href="'.base_url('llamar_ticket/'.$fila->id.'/'.$sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
													<a style="padding: 2px 4px; margin: 0 2px;" href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
												</td></tr>';
							}else{
								$html_agrupados.='</tr>';
							}
						}else{
							if($this->session->userdata('logged_in')['rol'] == 1){
								$html_agrupados.='<td style="width: 150px !important">
													<a style="padding: 4px 5px; margin: 0 2px;" type="button" class="btn btn-primary llamar_ticket" id="'.$fila->id.'" href="'.base_url('llamar_ticket/'.$fila->id.'/'.$sql.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
													<a style="padding: 2px 4px; margin: 0 2px;" href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
												</td></tr>';
							}else{
								$html_agrupados.='</tr>';
							}								
						}
					}else{
						if($fila->situacion == 8){
							if($this->session->userdata('logged_in')['rol'] == 1){
								$html_agrupados.='<td style="width: 150px !important">
													<a style="padding: 4px 5px; margin: 0  2px;" type="button" class="btn btn-primary llamar_ticket" id="'.$fila->id.'" href="'.base_url('llamar_ticket/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
													<a style="padding: 2px 4px; margin: 0 2px;" href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
												</td></tr>';
							}
						}else{						
							if($this->session->userdata('logged_in')['rol'] == 6){
								$html_agrupados.='<td style="width: 150px !important">';
								if($fila->asignado == 0 && $fila->situacion == 12){
									if($fila->prioridad != 3){
										$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('asignar_ticket/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';
									}
								}else if($fila->asignado == $this->session->userdata('logged_in')['id']){
									$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('solucionar_ticket/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i class="fa fa-check"></i></a>';									
								}								
								$html_agrupados.='</td></tr>';
							}else{
								if($this->session->userdata('logged_in')['rol'] == 1){
									$html_agrupados.='<td style="width: 150px !important">
														<a style="padding: 4px 5px; margin: 0 2px;" type="button" class="btn btn-primary llamar_ticket" id="'.$fila->id.'" href="'.base_url('llamar_ticket/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" alt="Llamar" title="Llamar"><i class="fa fa-phone"></i></a>
														<a style="padding: 2px 4px; margin: 0 2px;" href="'.base_url('ver_historial/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>';
								}else{
									$html_agrupados.='<td>';
								}

								if($fila->asignado == 0 && $this->session->userdata('logged_in')['rol'] == 2){
									if($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24){										
										if($fila->prioridad != 3){
											$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
											$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('asignar_ticket/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';																
										}										
									}else{
										if($fila->prioridad != 3){
											$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
											$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; background: linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('asignar_ticket/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i class="fa fa-truck"></i></a>';																
										}
									}
								}else if($fila->asignado == $this->session->userdata('logged_in')['id']){
									if($fila->prioridad != 3){
										if($this->session->userdata('logged_in')['rol'] == 2){
											$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
											$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('solucionar_ticket/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i class="fa fa-check"></i></a>';
										}else{
											$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('solucionar_ticket/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i class="fa fa-check"></i></a>';
										}															
									}
								}else if($fila->asignado != 0){
									if($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24){										
										$html_agrupados.='<a style="padding: 2px 2px; margin: 0 2px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-radius: 5px; box-shadow: 1px 1px #444444; width: 27px; height: 40px; color: #ffffff; display: inline-block; font-size: 18px; text-align: center;" href="'.base_url('asignar_ticket_tecnico/'.$fila->id.'/'.$sql.'/agrupar/'.$columna.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i class="fa fa-users"></i></a>';
									}
								}
								
								$html_agrupados.='</td></tr>';
							}
						}					
					}
				}
				
				$html_agrupados .="</tbody>
					</table></div>";			
			}
		}
		echo $html_agrupados;
	}
	
	/* Agrupar maquinas */
	public function agrupar_maquinas(){
		$columna = $this->input->post('col');
		$sql = $this->input->post('sql');
		$html_agrupados = '';
		$html_agrupados = '<a id="volver_agrupado" style="margin-top: 8px" href="#" class="btn btn-danger dropdown-toggle">Volver</a>';
		$agrupados = $this->post->agrupar_maquinas($columna,$sql);
		foreach($agrupados->result() as $agrupado){
			if($agrupado->salon == 0){
				continue;
			}
			$html_agrupados .= "<div class='agrupado_div' style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 10px 0; padding: 10px; box-shadow: 1px 1px 0px #ccc; cursor: pointer'>
															<i class='fa fa-plus-circle' aria-hidden='true'></i><i style='display: none' class='fa fa-minus-circle' aria-hidden='true'></i>";
			switch($columna){
				case "salon":
					$valor = $agrupado->salon;
					$situacion_nombre = $this->post->get_salon($agrupado->salon);
					$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$situacion_nombre."</span>";
					break;
				case "modelo":
					$valor = $agrupado->modelo;
					$salon_nombre = $this->post->get_modelo($agrupado->modelo);
					$html_agrupados .= "<span style='font-weight: bold; margin-left: 1%'>".$salon_nombre->modelo."</span>";
					break;	
			}
			
			$html_agrupados .="<div style='width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 20px 0; padding: 10px; display: none'>";
			
			$html_agrupados .= '<table class="table tabla_incidencias">
													<thead>
														<tr>
															<th class="th_tabla">Máquina</th>
															<th class="th_tabla">Salón</th>
															<th class="th_tabla">Fabricante</th>
															<th class="th_tabla">Modelo</th>
															<th class="th_tabla">Acciones</th>
														</tr>
													</thead>
													<tbody class="tabla_agrupados">';
			
			$grupo_result = $this->post->get_maquina_group($columna,$valor,$sql);
			foreach($grupo_result->result() as $fila){
					if($fila->salon == 0){
						continue;
					}
					$salon = $this->post->get_salon_completo($fila->salon);
					$modelo = $this->post->get_modelo($fila->modelo);
					$fabricante = $this->post->get_fabricante_modelo($fila->modelo);
					$html_agrupados.= '<tr class="clickable-row" data-href="'.base_url('editar_maquina/'.$fila->id.'').'">';
					$html_agrupados.='<td>'.$fila->maquina.'</td>
									 <td>'.$salon->salon.'</td>
									 <td>'.$fabricante->nombre.'</td>
									 <td>'.$modelo->modelo.'</td>
									 <td>
									 	<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("borrar_maquina/".$fila->id."").'" type="button" class="btn btn-danger" alt="Eliminar máquina" title="Eliminar máquina"><i class="fa fa-close"></i></a>
									 </td>';
					$html_agrupados.= '</tr>';
			}
			
			$html_agrupados .="</tbody>
				</table></div></div>";
		}
		echo $html_agrupados;
		
	}
	
	/* Vista solucionar incidencia */	
	public function solucionar_ticket($id_ticket=null){
		error_reporting(0);
        ini_set('display_errors', 0);
		if($this->session->userdata('logged_in')){
			/* Obtener ticket */
			if(isset($id_ticket)){
				$ticket = $this->post->get_ticket($id_ticket);
				$operadora = $this->post->get_operadora($ticket->operadora);
				$salon = $this->post->get_salon($ticket->salon);
				if($ticket->asignado == 0 || $ticket->asignado != $this->session->userdata('logged_in')['id']){
					$data = array('title' => '');
					$this->load_view('asignado',$data);
				}else{
					$situacion = $this->post->get_situacion($ticket->situacion);
					$averia = $this->post->get_averia($ticket->tipo_averia);
					$tipo_error = $this->post->get_tipo_error($ticket->tipo_error);
					$detalle_error = $this->post->get_detalle_error($ticket->detalle_error);
					$maquina = $this->post->get_maquina($ticket->maquina);
					$creador = $this->post->get_creador($ticket->creador);

					$html_historial = '';

					$html_historial.='<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
										<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
									    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
									    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
									    </div>
									    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
											<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion.'</span></p>';
					
					$error_desc = stripslashes($ticket->error_desc);
					$trata_desc = stripslashes($ticket->trata_desc);				
																	
					$html_historial.='<p><span style="font-weight: bold">Máquina:</span> '.$maquina.'</p>
									<p><span style="font-weight: bold">Creador:</span> '.$creador.'</p>
									<p style="font-weight: bold">Descripción:</p>
									<p>'.$error_desc.'</p>';
																			
					if(!empty($ticket->trata_desc)){
						$html_historial .= '<p style="font-weight: bold">Tratamiento:</p>
											<p>'.$trata_desc.'</p>';
					}

					if($ticket->tipo_error == 91 || $ticket->tipo_error == 92 || $ticket->tipo_error == 99){
						$html_perifericos = '';
						
						$html_perifericos .= '<div class="col-md-5 col-sm-12" style="padding: 0 0 10px 0;">';

						if($ticket->tipo_error == 91){
							$html_perifericos .= '<label>Monedero actual</label>';
						}else if($ticket->tipo_error == 92){
							$html_perifericos .= '<label>Billetero actual</label>';
						}else{
							$html_perifericos .= '<label>Impresora actual</label>';
						}

						$html_perifericos .= '<div class="input-group">
						    					<select class="js-example-basic-single" id="peri_ant" name="peri_ant" required="">
						    						<option value="0">Ninguno</option>';

						if($ticket->tipo_error == 91){
							$perifericos = $this->post->get_monederos_maquinas();
						}else if($ticket->tipo_error == 92){
							$perifericos = $this->post->get_billeteros_maquinas();
						}else{
							$perifericos = $this->post->get_impresoras_maquinas();
						}

						foreach($perifericos->result() as $periferico){
							if($ticket->maquina == $periferico->maquina){
								$html_perifericos.='<option value="'.$periferico->id.'" selected>'.$periferico->nombre.'</option>';
							}else{	
								$html_perifericos.='<option value="'.$periferico->id.'">'.$periferico->nombre.'</option>';
							}
						}

						$html_perifericos .= '</select>
											</div>
										</div>';

						$html_perifericos .= '<div class="col-md-2 col-sm-12" style="padding: 0 0 10px 0;"></div>';

						$html_perifericos .= '<div class="col-md-5 col-sm-12" style="padding: 0 0 10px 0;">';

						if($ticket->tipo_error == 91){
							$html_perifericos .= '<label>Monedero nuevo</label>';
						}else if($ticket->tipo_error == 92){
							$html_perifericos .= '<label>Billetero nuevo</label>';
						}else{
							$html_perifericos .= '<label>Impresora nuevo</label>';
						}

						$html_perifericos .= '<div class="input-group">
						    					<select class="js-example-basic-single" id="peri_nue" name="peri_nue" required="">
						    						<option value="0">Ninguno</option>';

						foreach($perifericos->result() as $periferico){	
							$html_perifericos.='<option value="'.$periferico->id.'">'.$periferico->nombre.'</option>';
						}

						$html_perifericos .= '</select>
											</div>
										</div>';					
					}else{
						$html_perifericos .= '<div class="col-md-5 col-sm-12" style="padding: 0 0 10px 0; display: none">
												<input type="hidden" name="peri_ant" value="0">
												<input type="hidden" name="peri_nue" value="0">
											</div>';
					}

					$data = array('title' => '', 'ticket' => $ticket, 'operadora' => $operadora, 'salon' => $salon, 'html_historial' => $html_historial, 'html_perifericos' => $html_perifericos);
					$this->load_view('solucionar_ticket', $data);
				}
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Solucionar incidencia form */	
	public function solucionar_ticket_form(){
		error_reporting(0);
        ini_set('display_errors', 0);
		if($this->session->userdata('logged_in')){
			$this->form_validation->set_rules('trata_desc', 'Tratamiento incidencia', 'trim|htmlspecialchars');
			if($this->form_validation->run() == FALSE){
				/* Obtener ticket */
				$id_ticket = $this->input->post('id_ticket');
				if(isset($id_ticket)){
					$ticket = $this->post->get_ticket($id_ticket);
					$maquina = $this->post->get_maquina($ticket->maquina);
					$data = array('title' => '', 'ticket' => $ticket, 'maquina' => $maquina);
					$this->load_view('solucionar_ticket', $data);
				}
			}else{
				/* Solucionar ticket */
				if (strlen(trim($this->input->post('trata_desc'))) == 0){
					/* Obtener ticket */
					$id_ticket = $this->input->post('id_ticket');
					if(isset($id_ticket)){
						$ticket = $this->post->get_ticket($id_ticket);
						$maquina = $this->post->get_maquina($ticket->maquina);
						$data = array('title' => '', 'ticket' => $ticket, 'maquina' => $maquina);
						$this->load_view('solucionar_ticket', $data);
					}
				}else{
					$img_name = '';
					if($_FILES['trata_imagen']['size'] != 0){
						if($_FILES['trata_imagen']['size'] < 8000000){
							$images = $_FILES['trata_imagen'];
							$success = null;
							$paths= "";
							$filenames = $images['name'];

						    $ext = explode('.', basename($filenames));
						    $img_name = $this->input->post('id_ticket') . "_" . md5(uniqid()) . "." . array_pop($ext);
						    $target = APPPATH."../tickets/files/img/trata/" . $img_name;
						    
						    move_uploaded_file($images['tmp_name'], $target);
					    }
				    }

				    $fecha = date('Y-m-d');
					$hora = date('H:i:s');
					$ticket = $this->post->get_ticket($this->input->post('id_ticket'));
					$situacion = $ticket->situacion;					
					if(isset($_POST['sin'])){
						$tipo_averia = $ticket->tipo_averia;
						$ticket = $this->post->solucionar_ticket($situacion, $this->input->post('id_ticket'), $this->input->post('trata_desc'), $this->input->post('peri_ant'), $this->input->post('peri_nue'), $fecha, $hora, $img_name);
						if($situacion == 2 && $this->input->post('destino') != 4 && $this->input->post('destino') != 230 && $tipo_averia == 6){
							$update = $this->post->enviar_ticket_sat_adm($this->input->post('id_ticket'));
							$this->telegram6($this->input->post('id_ticket'),$this->input->post('trata_desc'));
							$this->enviarmail($this->input->post('id_ticket'));
						}					
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Tratamiento ticket');
						if($this->input->post('peri_ant') != 0 || $this->input->post('peri_nue') != 0){
							$this->post->guardar_historial_perifericos($this->input->post('id_ticket'), $this->input->post('peri_ant'), $this->input->post('peri_nue'));
						}
					}
					if(isset($_POST['con'])){
						$solucionar_ticket = $this->post->solucionar_ticket('6', $this->input->post('id_ticket'), $this->input->post('trata_desc'), $this->input->post('peri_ant'), $this->input->post('peri_nue'), $fecha, $hora, $img_name);
						if($ticket->id_origen > 0){
							$solucionar_ticket = $this->post->solucionar_ticket('6', $ticket->id_origen, $this->input->post('trata_desc'), $this->input->post('peri_ant'), $this->input->post('peri_nue'), $fecha, $hora, $img_name);
						}
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Solucionar Ticket');
						if($this->input->post('peri_ant') != 0 || $this->input->post('peri_nue') != 0){
							$this->post->guardar_historial_perifericos($this->input->post('id_ticket'), $this->input->post('peri_ant'), $this->input->post('peri_nue'));
						}
					}
					if($situacion == 2 || $situacion == 3 || $situacion == 12 || $situacion == 13 || $situacion == 14 || $situacion == 19 || $situacion == 21){
						$this->telegram2($this->input->post('id_ticket'),$this->input->post('trata_desc'));
					}
					$this->gestion();
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Asignar incidencia */
	public function asignar_ticket($id=NULL){
		if($this->session->userdata('logged_in')){
			if(isset($id)){
				/* Asignar ticket */
				$asignado = $this->post->asignar_ticket($id,$this->session->userdata('logged_in')['id'],3);
				if($asignado){
					$this->telegram3($id);
					$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Asignar Ticket');
					redirect('gestion', 'refresh');
				}else{
					$data = array('title' => '');
					$this->load_view('asignado',$data);
				}
			}else{
				redirect('gestion', 'refresh');
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* AUTO Asignar incidencia ADM */
	public function auto_asignar_ticket($id){
		if(isset($id)){
			$ticket = $this->post->get_ticket($id);
			$zona = $this->post->get_zona_salon($ticket->salon);
			if(isset($zona)){
				$tecnico = $this->post->get_tecnico_zonas($zona->zona);
				if(isset($tecnico)){
					$asignado = $this->post->asignar_ticket($ticket->id,$tecnico->tecnico,3);
					if($asignado){
						$this->telegram3($id);							
						$this->post->guardar_historial($tecnico->tecnico,'Auto Asignar Ticket');
					}
				}
			}				
		}
	}
	
	/* Ver historial incidencia */
	public function ver_historial($id_ticket,$sql=NULL){
		if($this->session->userdata('logged_in')){
			/* Obtener ticket */
			$ticket = $this->post->get_ticket($id_ticket);

			/* Datos incidencia */
			$html_historial = '';
			$inicial = $this->post->get_edicion_inicial($ticket->id);
			$situacion_inicial = $this->post->get_situacion($inicial->situacion);
			$situacion = $this->post->get_situacion($ticket->situacion);
			$operadora = $this->post->get_operadora($ticket->operadora);
			$salon = $this->post->get_salon($ticket->salon);
			$averia = $this->post->get_averia($ticket->tipo_averia);
			$tipo_error = $this->post->get_tipo_error($ticket->tipo_error);
			$detalle_error = $this->post->get_detalle_error($ticket->detalle_error);
			$maquina = $this->post->get_maquina($ticket->maquina);
			$fecha = explode("-", $ticket->fecha_creacion);
			$creador = $this->post->get_creador($ticket->creador);
			
			$html_historial .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
			
			if($inicial->situacion == 6){
				$html_historial.='<div class="panel-heading" style="background: #449d44; text-align: center; padding: 5px 4px; font-size: 13px">
											<p style="color: #fff">#'.$ticket->id.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$ticket->hora_creacion.' '.$operadora.' '.$salon.'</p>
										</div>
										<div class="panel-body" style="padding: 0; border: none">
											<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
											<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
											 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
											 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
											 </div>
											 <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
												<p><span style="font-weight: bold">Situación: </span><span style="color: #449d44;">'.$situacion.'</span></p>';					
			}else{
				$html_historial.='<div class="panel-heading" style="background: #d9534f; text-align: center; padding: 5px 4px; font-size: 13px">
												<p style="color: #fff">#'.$ticket->id.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$ticket->hora_creacion.' '.$operadora.' '.$salon.'</p>
											</div>
											<div class="panel-body" style="padding: 0; border: none">
												<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
													<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
												    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
												    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
												    </div>
												    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
														<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion_inicial.'</span></p>';				
			}
			
			$error_desc = stripslashes($ticket->error_desc);
			$trata_desc = stripslashes($ticket->trata_desc);				
															
			$html_historial.='<p><span style="font-weight: bold">Máquina:</span> '.$maquina.'</p>
							<p><span style="font-weight: bold">Creador:</span> '.$creador.'</p>
							<p style="font-weight: bold">Descripción:</p>
							<p>'.$error_desc.'</p>';
																	
			if(!empty($ticket->trata_desc)){
				$html_historial .= '<p style="font-weight: bold">Tratamiento:</p>
									<p>'.$trata_desc.'</p>';
			}

			/* Botones acciones */

			$array_usuarios_operadora = array();
			$usuarios_operadora = $this->post->get_usuarios_operadora($this->session->userdata('logged_in')['acceso']);
			foreach($usuarios_operadora->result() as $usuario_operadora){
				array_push($array_usuarios_operadora, $usuario_operadora->id);
			}

			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
				$destino_incidencia = $this->post->get_destino_incidencia($this->session->userdata('logged_in')['acceso']);
			}

			if($ticket->asignado == 0 && ($this->session->userdata('logged_in')['rol'] == 4 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['id'] != 86))){
				if ($ticket->prioridad != 3){
					if($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24){											
						$html_historial.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$ticket->id.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
						$html_historial.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$ticket->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';							
					}else{
						if($ticket->operadora == 41){
							if(($ticket->destino == 230 || $ticket->destino == 4) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
								if($ticket->situacion != 1){
									$html_historial.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$ticket->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
								}
							}
						}else if(($ticket->destino == $destino_incidencia->id) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
							if($this->session->userdata('logged_in')['rol'] == 2){	
								$html_historial.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$ticket->id.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
								$html_historial.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$ticket->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
							}else{
								$html_historial.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$ticket->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
							}
						}
					}
				}
			}
			
			if($ticket->asignado == 0 && $this->session->userdata('logged_in')['rol'] == 6 && $ticket->situacion == 12){
				if ($ticket->prioridad != 3){
					$html_historial.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; linear-gradient(to bottom,#f0ad4e 0,#eb9316 100%); border-color: #eb9316" href="'.base_url('asignar_ticket/'.$ticket->id).'" type="button" class="btn btn-warning" alt="Asignarme" title="Asignarme"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar</span></a>';
				}
			}
			
			if($ticket->asignado == $this->session->userdata('logged_in')['id']){
				if($ticket->situacion != 9){
					if($this->session->userdata('logged_in')['rol'] == 6){
						if($ticket->situacion != 2){
							$html_historial.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$ticket->id.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
						}
					}else if($this->session->userdata('logged_in')['rol'] == 2){
						$html_historial.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$ticket->id.'').'" type="button" class="btn btn-success" alt="Asignar técnico" title="Asignar técnico"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">Asignar técnico</span></a>';
						$html_historial.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$ticket->id.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
					}else{
						$html_historial.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('solucionar_ticket/'.$ticket->id.'').'" type="button" class="btn btn-success" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold; font-size: 10px">Tratamiento</span></a>';
					}
				}
			}else if($ticket->asignado != 0){
				$asignado = $this->post->get_creador_completo($ticket->asignado);
				if($this->session->userdata('logged_in')['rol'] == 2){
					if($ticket->situacion != 9){										
						if($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24){
							$html_historial.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$ticket->id.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
						}else if($ticket->operadora == 41){
							if(($ticket->destino == 230 || $ticket->destino == 4) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
								$html_historial.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$ticket->id.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
							}
						}else if(($ticket->destino == $destino_incidencia->id || $ticket->situacion == 13) && in_array($this->session->userdata('logged_in')['id'],$array_usuarios_operadora)){
							$html_historial.='<a style="width: 30%; padding: 2px 4px; margin: 0 4px; background: linear-gradient(to bottom,#c56eff,#9900ff 100%); border-color: #9900ff;" href="'.base_url('asignar_ticket_tecnico/'.$ticket->id.'').'" type="button" class="btn btn-warning" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-users"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
						}
					}
				}else{
					if($ticket->situacion != 9){
						$html_historial.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$ticket->id.'').'" type="button" class="btn btn-success" alt="Asignado" title="Asignado"><i style="font-size: 30px" class="fa fa-truck"></i><span style="display: block; font-weight: bold; font-size: 10px">'.$asignado->usuario.'</span></a>';
					}
				}
			}

			/* ------- */
			
			$html_historial .= '</div>';

			/* Imágen */
			if(!empty($ticket->imagen)){
				$html_historial .= '<div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
										<p style="font-weight: bold">Imágenes:</p>
										<div style="padding: 5px; margin: 5px; border: 1px solid #ddd; border-radius: 5px;" class="col-md-3 col-sm-12">
											<a href="https://domain/tickets/files/img/errores/'.$ticket->imagen.'" target="_blank">
									 			<img id="imagen_error" style="width: 100%;" src="'.base_url("files/img/errores/".$ticket->imagen."").'" alt="imagen" title="imagen">
									 		</a>
									 	</div>';

				$ticket_imagenes = $this->post->get_imagenes_extra_ticket($ticket->id);
				if($ticket_imagenes->num_rows() > 0){
					$i = 0;
					foreach($ticket_imagenes->result() as $ticket_imagen){
						if($i == 0){
							$i++;
							continue;
						}
						$html_historial .= '<div style="padding: 5px; margin: 5px; border: 1px solid #ddd; border-radius: 5px;" class="col-md-3 col-sm-12">
												<a href="https://domain/tickets/files/img/errores/'.$ticket_imagen->imagen.'" target="_blank">
										 			<img id="imagen_error" style="width: 100%;" src="'.base_url("files/img/errores/".$ticket_imagen->imagen."").'" alt="imagen" title="imagen">
										 		</a>
										 	</div>';
						$i++;
					}
				}

				$html_historial .= '</div>';
			}

			/* ------- */
			
			$html_historial .= '</div>';

			$html_historial .= '</div>';			
			
			/* Ediciones */

			$ediciones = $this->post->get_ediciones($id_ticket);
			if($ediciones->num_rows() > 0){
				foreach($ediciones->result() as $edicion){
					$edicion_trata_desc = stripslashes($edicion->trata_desc);
					$situacion = $this->post->get_situacion($edicion->situacion);
					$fecha = explode("-", $edicion->fecha_edicion);
					$creador = $this->post->get_creador($edicion->creador);					
					$html_historial .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
					
					if($edicion->situacion == 6){
						$html_historial.='<div class="panel-heading" style="background: #449d44; text-align: center">
																	<p style="color: #fff">#'.$edicion->id_ticket.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$edicion->hora_edicion.'</p>
																</div>
																<div class="panel-body" style="padding: 10px">
																	<p><span style="font-weight: bold">Situación:</span><span style="color: #449d44;"> '.$situacion.'</span></p>';					
					}else{
						$html_historial.='<div class="panel-heading" style="background: #b21a30; text-align: center">
																	<p style="color: #fff">#'.$edicion->id_ticket.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$edicion->hora_edicion.'</p>
																</div>
																<div class="panel-body" style="padding: 10px">
																	<p><span style="font-weight: bold">Situación:</span><span style="color: #b21a30;"> '.$situacion.'</span></p>';					
					}	
			
					$html_historial.='<p><span style="font-weight: bold">Creador:</span> '.$creador.'</p>
														<p style="font-weight: bold">Tratamiento:</p>
														<p>'.$edicion_trata_desc.'</p>';
					$html_historial .= '</div></div>';
				}
			}

			if($ticket->maquina != 0){
				$historial = $this->post->get_historial_maquina_telegram($ticket->id,$ticket->maquina);
				if($historial->num_rows() > 0){

					$html_historial .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">
											<div class="panel-heading" style="background: #007bff; text-align: center">
												<p><span style="font-weight: bold; color: #fff">#'.$ticket->id.' - Historial máquina</span></p>
											</div>
											<div class="panel-body" style="padding: 0; border: none">
												<div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">';

					foreach($historial->result() as $historia){
						if($historia->id == $ticket->id){
							continue;
						}else{
							$tipo = $this->post->get_averia($historia->tipo_averia);
					  		$error = $this->post->get_tipo_error_completo($historia->tipo_error);
							$detalle = $this->post->get_detalle_error_completo($historia->detalle_error);
							$fecha = explode('-', $historia->fecha_creacion);
							$fecha_creacion = $fecha[2]."/".$fecha[1]."/".$fecha[0];
							$html_historial .= '<p><span style="font-weight: bold">#'.$historia->id.' '.$fecha_creacion.' '.$historia->hora_creacion.': </span>'.$tipo->gestion." ".$error->tipo." ".$detalle->error_detalle."</p>";
						}
					}
					
					$html_historial .= '</div>
									</div>
								</div>';
				}
			}

			/* ------- */
			
			$data = array('title' => '', 'ticket' => $ticket, 'html_historial' => $html_historial);
			$this->load_view('ver_historial', $data);
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function maquinas($pagina,$get_sql=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_maquinas'] != 1){
				$this->cajeros();
			}else{
				$get_sql = $this->uri->segment(4);
				// Get maquinas				
				if(isset($get_sql) && $get_sql != '' && $get_sql != '1' && $get_sql != '2' && $get_sql != '3' && $get_sql != '4' && $get_sql != '5'){
					$get_sql = str_replace("%20", " ", $get_sql);
					$get_sql = str_replace("%3E", ">", $get_sql);
					$get_sql = str_replace("%3C", "<", $get_sql);
					$final_sql = $get_sql;
					$final_sql .= " order by salon,maquina asc";
					$total_maquinas = $this->post->buscar_tickets($final_sql);
				}else if($this->session->userdata('logged_in')['rol'] == 6){
					$total_maquinas = $this->post->get_maquinas_com();
				}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$total_maquinas = $this->post->get_maquinas_averias();
				}else{
					$total_maquinas = $this->post->get_maquinas_operador($this->session->userdata('logged_in')['acceso']);
				}
				$resultados = $total_maquinas->num_rows();
				
				//Limito la busqueda
				$tamanio = 20;

				//examino la página a mostrar y el inicio del registro a mostrar
				if (!$pagina){
				   $inicio = 0;
				   $pagina = 1;
				}else{
				   $inicio = ($pagina - 1) * $tamanio;
				}
				//calculo el total de páginas
				$total_paginas = ceil($resultados / $tamanio);
				
				if(isset($get_sql) && $get_sql != '' && $get_sql != '1' && $get_sql != '2' && $get_sql != '3' && $get_sql != '4' && $get_sql != '5'){
					$get_sql = str_replace("%20", " ", $get_sql);
					$get_sql = str_replace("%3E", ">", $get_sql);
					$get_sql = str_replace("%3C", "<", $get_sql);
					$final_sql = $get_sql;
					$final_sql .= " order by salon,maquina asc";
					$final_sql .= " limit ".$inicio.",".$tamanio."";
					$maquinas = $this->post->buscar_tickets($final_sql);
				}else if($this->session->userdata('logged_in')['rol'] == 6){
					$maquinas = $this->post->get_maquinas_com_pag($inicio,$tamanio);
				}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$maquinas = $this->post->get_maquinas_averias_pag($inicio,$tamanio);
				}else{
					$maquinas = $this->post->get_maquinas_operador_pag($this->session->userdata('logged_in')['acceso'],$inicio,$tamanio);
				}
				
				// Paranoia rara para que aparezca la busqueda en los input
				if(isset($get_sql) && $get_sql != ''){
					$busqueda_troceada = explode(" ",$get_sql);
					for($i=0; $i < count($busqueda_troceada); $i++){
						if (strpos($busqueda_troceada[$i], "salon") !== FALSE) {							
							$salon_busqueda = $busqueda_troceada[$i+2];
							$salon_busqueda = str_replace("(", " ", $salon_busqueda);
							$salon_busqueda = str_replace(")", " ", $salon_busqueda);
						}
						if (strpos($busqueda_troceada[$i], "tipo_maquina") !== FALSE) {							
							$tipo_maquina_busqueda = $busqueda_troceada[$i+2];
							$tipo_maquina_busqueda = str_replace("(", " ", $tipo_maquina_busqueda);
							$tipo_maquina_busqueda = str_replace(")", " ", $tipo_maquina_busqueda);
						}
					}
					$estilo = " background-color: #d80039; color: #fff;";
				}else{
					$estilo = "";
				}
				
				// Filtro identificadores
				$html_id_maquinas = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$filtro_maquinas = $this->post->get_maquinas_averias();
					foreach($filtro_maquinas->result() as $filtro_maquina){
						$html_id_maquinas .= '<option value="'.$filtro_maquina->id.'">'.$filtro_maquina->maquina.'</option>';
					}
				}

				// Filtro salones
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 6 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$salones = $this->post->get_salones();
				}else if($this->session->userdata('logged_in')['rol'] == 2){
					$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				}

				$html_salones='';
				foreach($salones->result() as $salon){
					if(isset($salon_busqueda) && $salon_busqueda != ''){
						if($salon_busqueda == $salon->id){
							$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}		
				}
				
				// Filtro tipos maquinas
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 2){
					$tipos = $this->post->get_tipos_maquinas();
					$html_tipos = '';
					foreach($tipos->result() as $tipo){
						if(isset($tipo_maquina_busqueda) && $tipo_maquina_busqueda != ''){
							if((int)$tipo_maquina_busqueda == $tipo->id){
								$html_tipos .= '<option value="'.$tipo->id.'" selected>'.$tipo->nombre.'</option>';
							}else{
								$html_tipos .= '<option value="'.$tipo->id.'">'.$tipo->nombre.'</option>';
							}
						}else{
							$html_tipos .= '<option value="'.$tipo->id.'">'.$tipo->nombre.'</option>';
						}						
					}
				}
				
				if($this->session->userdata('logged_in')['rol'] == 6){
					$tipos = $this->post->get_tipos_maquinas_com();
					$html_tipos = '';
					foreach($tipos->result() as $tipo){
						if(isset($tipo_maquina_busqueda) && $tipo_maquina_busqueda != ''){
							if((int)$tipo_maquina_busqueda == $tipo->id){
								$html_tipos .= '<option value="'.$tipo->id.'" selected>'.$tipo->nombre.'</option>';
							}else{
								$html_tipos .= '<option value="'.$tipo->id.'">'.$tipo->nombre.'</option>';
							}
						}else{
							$html_tipos .= '<option value="'.$tipo->id.'">'.$tipo->nombre.'</option>';
						}
					}
				}

				// Perifericos
				$html_monederos = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$monederos = $this->post->get_monederos_maquinas();
					foreach($monederos->result() as $monedero){
						$html_monederos .= '<option value="'.$monedero->id.'">'.$monedero->nombre.'</option>';				
					}
				}

				$html_billeteros = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$billeteros = $this->post->get_billeteros_maquinas();
					foreach($billeteros->result() as $billetero){
						$html_billeteros .= '<option value="'.$billetero->id.'">'.$billetero->nombre.'</option>';				
					}
				}

				$html_impresoras = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$impresoras = $this->post->get_impresoras_maquinas();
					foreach($impresoras->result() as $impresora){
						$html_impresoras .= '<option value="'.$impresora->id.'">'.$impresora->nombre.'</option>';				
					}
				}
							
				// tabla maquinas
				$tabla = '';
				$version_movil = '';	
				foreach($maquinas->result() as $maquina){
					$salon = $this->post->get_salon_completo($maquina->salon);
					$modelo = $this->post->get_modelo($maquina->modelo);
					$fabricante = $this->post->get_fabricante_modelo($maquina->modelo);
					
					if($this->session->userdata('logged_in')['rol'] == 2){
						$tabla.= '<tr class="clickable-row" data-href="'.base_url('editar_maquina/'.$maquina->id.'/'.$pagina.'/'.$get_sql.'').'">';
					}else{
						$tabla.= '<tr style="font-family: Open Sans,Helvetica,Arial,sans-serif; font-size: 13px; color: #000;">';
					}
					
					$tabla.='<td>'.$maquina->maquina.'</td>
									 <td>'.$salon->salon.'</td>
									 <td>'.$fabricante->nombre.'</td>
									 <td>'.$modelo->modelo.'</td>
									 <td>';
					
					if($this->session->userdata('logged_in')['rol'] == 2){
						$tabla.= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("borrar_maquina/".$maquina->id."/".$pagina."/".$get_sql."").'" type="button" class="btn btn-danger" alt="Eliminar máquina" title="Eliminar máquina"><i class="fa fa-close"></i></a>';
						if($modelo->tipo_maquina == 5){
							$tabla.= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("editar_cajero/".$maquina->id."/".$pagina."/".$get_sql."").'" type="button" class="btn btn-warning" alt="Limites Cajero" title="Limites Cajero"><i class="fa fa-globe"></i></a>';
						}
					}
					
					$tabla.= '</td>';
					$tabla.= '</tr>';
					
					$version_movil.='<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">';
					
					$version_movil.='<div class="panel-heading" style="background: #449d44; text-align: center">
																<p style="color: #fff">'.$maquina->maquina.'</p>
															</div>
															<div class="panel-body" style="padding: 10px">';
																
					$version_movil.='<p><span style="font-weight: bold">Salón:</span> '.$salon->salon.'</p>
											<p><span style="font-weight: bold">Fabricante:</span> '.$fabricante->nombre.'</p>										
											<p><span style="font-weight: bold">Modelo:</span> '.$modelo->modelo.'</p>';
											
					if($this->session->userdata('logged_in')['rol'] == 2){
						
						if($modelo->tipo_maquina == 5){
							$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 2px;" href="'.base_url('editar_maquina/'.$maquina->id."/".$pagina."/".$get_sql."").'" type="button" class="btn btn-info" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold">Editar</span></a>
														 	<a style="width: 30%; padding: 2px 4px; margin: 0 2px;" href="'.base_url('editar_cajero/'.$maquina->id."/".$pagina."/".$get_sql."").'" type="button" class="btn btn-warning" alt="Limites Cajero" title="Limites Cajero"><i style="font-size: 30px" class="fa fa-globe"></i><span style="display: block; font-weight: bold">Cajero</span></a>
														 	<a style="width: 30%; padding: 2px 4px; margin: 0 2px;" href="'.base_url('borrar_maquina/'.$maquina->id."/".$pagina."/".$get_sql."").'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar"><i style="font-size: 30px" class="fa fa-close"></i><span style="display: block; font-weight: bold">Eliminar</span></a>';
						}else{
							$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('editar_maquina/'.$maquina->id."/".$pagina."/".$get_sql."").'" type="button" class="btn btn-info" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold">Editar</span></a>
														 	 <a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('borrar_maquina/'.$maquina->id."/".$pagina."/".$get_sql."").'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar"><i style="font-size: 30px" class="fa fa-close"></i><span style="display: block; font-weight: bold">Eliminar</span></a>';
						}				
					
					}
					
					$version_movil.='</div></div>';
				}
				
				$data = array('title' => 'Administracion', 'tabla_maquinas' => $tabla, 'html_id_maquinas' => $html_id_maquinas, 'html_salones' => $html_salones, 'html_tipos' => $html_tipos, 'html_monederos' => $html_monederos, 'html_billeteros' => $html_billeteros, 'html_impresoras' => $html_impresoras, 'version_movil' => $version_movil, 'paginas' => $total_paginas, 'pagina' => $pagina, 'contador_total_maquinas' => $resultados, 'estilo' => $estilo);
				$this->load_view('maquinas', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function buscador_maquinas($pagina,$id_salon,$tipo_maquina){
		if($this->session->userdata('logged_in')){						
				if(!isset($id_salon) || $id_salon == 0){
					$id_salon = $this->input->post('salon');
				}
				
				if(!isset($tipo_maquina) || $tipo_maquina == 0){
					$tipo_maquina = $this->input->post('tipo');
				}
				
				if(!isset($id_maquina) || $id_maquina == 0){
					$id_maquina = $this->input->post('id_maquina');
				}
				
				// Filtro identificadores
				$html_id_maquinas = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					if($id_salon == 9999999999999999999){
						$maquinas = $this->post->get_maquinas_averias();
					}else{
						$maquinas = $this->post->get_maquinas_averias_salon($id_salon);
					}
					foreach($maquinas->result() as $maquina){
						if($maquina->id == $id_maquina){
							$html_id_maquinas .= '<option value="'.$maquina->id.'" selected>'.$maquina->maquina.'</option>';
						}else{
							$html_id_maquinas .= '<option value="'.$maquina->id.'">'.$maquina->maquina.'</option>';
						}
					}
				}

				// Filtro salones
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 6 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$salones = $this->post->get_salones();
				}else if($this->session->userdata('logged_in')['rol'] == 2){
					$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				}

				$html_salones='';
				foreach($salones->result() as $salon){
					if($id_salon == $salon->id){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}										
				}
				
				// Filtro tipos maquinas
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 2){
					$tipos = $this->post->get_tipos_maquinas();
					$html_tipos = '';
					foreach($tipos->result() as $tipo){
						if($tipo->id == $tipo_maquina){
							$html_tipos .= '<option value="'.$tipo->id.'" selected>'.$tipo->nombre.'</option>';
						}else{
							$html_tipos .= '<option value="'.$tipo->id.'">'.$tipo->nombre.'</option>';
						}
					}
				}
				
				if($this->session->userdata('logged_in')['rol'] == 6){
					$tipos = $this->post->get_tipos_maquinas_com();
					$html_tipos = '';
					foreach($tipos->result() as $tipo){
						if($tipo->id == $tipo_maquina){
							$html_tipos .= '<option value="'.$tipo->id.'" selected>'.$tipo->nombre.'</option>';
						}else{
							$html_tipos .= '<option value="'.$tipo->id.'">'.$tipo->nombre.'</option>';
						}
					}
				}

				// Perifericos
				$html_monederos = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$monederos = $this->post->get_monederos_maquinas();
					foreach($monederos->result() as $monedero){
						$html_monederos .= '<option value="'.$monedero->id.'">'.$monedero->nombre.'</option>';
					}
				}

				$html_billeteros = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$billeteros = $this->post->get_billeteros_maquinas();
					foreach($billeteros->result() as $billetero){
						$html_billeteros .= '<option value="'.$billetero->id.'">'.$billetero->nombre.'</option>';
					}
				}

				$html_impresoras = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$impresoras = $this->post->get_impresoras_maquinas();
					foreach($impresoras->result() as $impresora){
						$html_impresoras .= '<option value="'.$impresora->id.'">'.$impresora->nombre.'</option>';
					}
				}

				// Get maquinas
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					if($this->input->post('monedero') == 9999999999999999999 && $this->input->post('billetero') == 9999999999999999999 && $this->input->post('impresora') == 9999999999999999999){
						if($id_maquina == 9999999999999999999 || !isset($id_maquina)){
							if($id_salon == 9999999999999999999){
								if($tipo_maquina == 9999999999999999999){
									$total_maquinas = $this->post->get_maquinas_averias();
									$consulta_sql = 'SELECT * FROM `maquinas` WHERE salon IN (SELECT id FROM salones WHERE activo = 1) AND modelo IN (120,121,122,131,182,191)';
								}else{
									$total_maquinas = $this->post->get_maquinas_averias_tipo($tipo_maquina);
									$consulta_sql = 'SELECT * FROM maquinas WHERE modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = '.$tipo_maquina.') AND salon IN (SELECT id FROM salones WHERE (operadora = 24 OR operadora = 41))';
								}
							}else{
								if($tipo_maquina == 9999999999999999999){
									$total_maquinas = $this->post->get_maquinas_averias_salon($id_salon);
									$consulta_sql = 'SELECT * FROM maquinas WHERE salon = '.$id_salon;
								}else{
									$total_maquinas = $this->post->get_maquinas_averias_salon_tipo($id_salon,$tipo_maquina);
									$consulta_sql = 'SELECT * FROM maquinas WHERE salon = '.$id_salon.' and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = '.$tipo_maquina.')';
								}
								
							}
						}else{
							$total_maquinas = $this->post->get_maquinas_averias_identificador($id_maquina);
							$consulta_sql = 'SELECT * FROM maquinas WHERE id = '.$id_maquina.'';
						}
					}else{
						if($this->input->post('monedero') != 9999999999999999999){
							$total_maquinas = $this->post->get_maquinas_averias_monedero($this->input->post('monedero'));
							$consulta_sql = 'SELECT * FROM `maquinas` WHERE id IN (SELECT maquina FROM monederos_maquinas WHERE id = '.$this->input->post('monedero').')';
						}else if($this->input->post('billetero') != 9999999999999999999){
							$total_maquinas = $this->post->get_maquinas_averias_billetero($this->input->post('billetero'));
							$consulta_sql = 'SELECT * FROM `maquinas` WHERE id IN (SELECT maquina FROM billeteros_maquinas WHERE id = '.$this->input->post('billetero').')';
						}else if($this->input->post('impresora') != 9999999999999999999){
							$total_maquinas = $this->post->get_maquinas_averias_impresora($this->input->post('impresora'));
							$consulta_sql = 'SELECT * FROM `maquinas` WHERE id IN (SELECT maquina FROM impresoras_maquinas WHERE id = '.$this->input->post('impresora').')';
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 6){
					if($id_salon == 9999999999999999999){
						if($tipo_maquina == 9999999999999999999){
							$total_maquinas = $this->post->get_maquinas_com();
							$consulta_sql = 'SELECT * FROM maquinas WHERE modelo IN (SELECT id FROM modelos_maquinas WHERE fabricante = 16 OR fabricante = 19 OR fabricante = 15)';
						}else{
							$total_maquinas = $this->post->get_maquinas_com_tipo($tipo_maquina);
							$consulta_sql = 'SELECT * FROM maquinas WHERE modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = '.$tipo_maquina.')';
						}
					}else{
						if($tipo_maquina == 9999999999999999999){
							$total_maquinas = $this->post->get_maquinas_com_salon($id_salon);
							$consulta_sql = 'SELECT * FROM maquinas WHERE salon = '.$id_salon.' AND modelo IN (SELECT id FROM modelos_maquinas WHERE fabricante = 16 OR fabricante = 19 OR fabricante = 15)';
						}else{
							$total_maquinas = $this->post->get_maquinas_com_salon_tipo($id_salon,$tipo_maquina);
							$consulta_sql = 'SELECT * FROM maquinas WHERE salon = '.$id_salon.' and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = '.$tipo_maquina.')';
						}
					}
				}else{
					if($id_salon == 9999999999999999999){
						if($tipo_maquina == 9999999999999999999){
							$total_maquinas = $this->post->get_maquinas_operador($this->session->userdata('logged_in')['acceso']);
							$consulta_sql = 'SELECT * FROM maquinas WHERE salon IN (SELECT id FROM salones WHERE operadora = '.$this->session->userdata('logged_in')['acceso'].') AND modelo IN (select id from modelos_maquinas where tipo_maquina != 10)';
						}else{
							$total_maquinas = $this->post->get_maquinas_operador_tipo($this->session->userdata('logged_in')['acceso'],$tipo_maquina);
							$consulta_sql = 'SELECT * FROM maquinas WHERE salon IN (SELECT id FROM salones WHERE operadora = '.$this->session->userdata('logged_in')['acceso'].') AND modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = '.$tipo_maquina.')';
						}
					}else{
						if($tipo_maquina == 9999999999999999999){
							$total_maquinas = $this->post->get_maquinas_operador_salon($id_salon);
							$consulta_sql = 'SELECT * FROM maquinas WHERE salon = '.$id_salon.'';
						}else{
							$total_maquinas = $this->post->get_maquinas_operador_salon_tipo($id_salon,$tipo_maquina);
							$consulta_sql = 'SELECT * FROM maquinas WHERE salon = '.$id_salon.' and modelo IN (SELECT id from modelos_maquinas WHERE tipo_maquina = '.$tipo_maquina.')';
						}
					}
				}
				
				$resultados = $total_maquinas->num_rows();
				//Limito la busqueda
				$tamanio = 20;

				//examino la página a mostrar y el inicio del registro a mostrar
				if (!$pagina){
				   $inicio = 0;
				   $pagina = 1;
				}else{
				   $inicio = ($pagina - 1) * $tamanio;
				}
				//calculo el total de páginas
				$total_paginas = ceil($resultados / $tamanio);
				
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					if($this->input->post('monedero') == 9999999999999999999 && $this->input->post('billetero') == 9999999999999999999 && $this->input->post('impresora') == 9999999999999999999){
						if($id_maquina == 9999999999999999999 || !isset($id_maquina)){
							if($id_salon == 9999999999999999999){
								if($tipo_maquina == 9999999999999999999){
									$maquinas = $this->post->get_maquinas_averias_pag($inicio,$tamanio);
								}else{
									$maquinas = $this->post->get_maquinas_averias_tipo_pag($tipo_maquina,$inicio,$tamanio);
								}
							}else{
								if($tipo_maquina == 9999999999999999999){
									$maquinas = $this->post->get_maquinas_averias_salon_pag($id_salon,$inicio,$tamanio);
								}else{
									$maquinas = $this->post->get_maquinas_averias_salon_tipo_pag($id_salon,$tipo_maquina,$inicio,$tamanio);
								}							
							}
						}else{
								$maquinas = $this->post->get_maquinas_averias_identificador($id_maquina);			
						}
					}else{
						if($this->input->post('monedero') != 9999999999999999999){
							$maquinas = $this->post->get_maquinas_averias_monedero($this->input->post('monedero'));
						}else if($this->input->post('billetero') != 9999999999999999999){
							$maquinas = $this->post->get_maquinas_averias_billetero($this->input->post('billetero'));
						}else if($this->input->post('impresora') != 9999999999999999999){
							$maquinas = $this->post->get_maquinas_averias_impresora($this->input->post('impresora'));
						}
					}
				}else if($this->session->userdata('logged_in')['rol'] == 6){
					if($id_salon == 9999999999999999999){
						if($tipo_maquina == 9999999999999999999){
							$maquinas = $this->post->get_maquinas_com_pag($inicio,$tamanio);
						}else{
							$maquinas = $this->post->get_maquinas_com_tipo_pag($tipo_maquina,$inicio,$tamanio);
						}
					}else{
						if($tipo_maquina == 9999999999999999999){
							$maquinas = $this->post->get_maquinas_com_salon_pag($id_salon,$inicio,$tamanio);
						}else{
							$maquinas = $this->post->get_maquinas_com_salon_tipo_pag($id_salon,$tipo_maquina,$inicio,$tamanio);
						}
					}
				}else{
					if($id_salon == 9999999999999999999){
						if($tipo_maquina == 9999999999999999999){
							$maquinas = $this->post->get_maquinas_operador_pag($this->session->userdata('logged_in')['acceso'],$inicio,$tamanio);
						}else{
							$maquinas = $this->post->get_maquinas_operador_tipo_pag($this->session->userdata('logged_in')['acceso'],$tipo_maquina,$inicio,$tamanio);
						}
					}else{
						if($tipo_maquina == 9999999999999999999){
							$maquinas = $this->post->get_maquinas_operador_salon_pag($id_salon,$inicio,$tamanio);
						}else{
							$maquinas = $this->post->get_maquinas_operador_salon_tipo_pag($id_salon,$tipo_maquina,$inicio,$tamanio);
						}
					}
				}
							
				// tabla maquinas
				$tabla = '';
				$version_movil = '';
				$estilo = " background-color: #d80039; color: #fff;";	
				foreach($maquinas->result() as $maquina){
					$salon = $this->post->get_salon_completo($maquina->salon);
					$modelo = $this->post->get_modelo($maquina->modelo);
					$fabricante = $this->post->get_fabricante_modelo($maquina->modelo);
					
					if($this->session->userdata('logged_in')['rol'] == 2){
						$tabla.= '<tr class="clickable-row" data-href="'.base_url('editar_maquina/'.$maquina->id.'/'.$pagina.'/'.$consulta_sql.'').'">';
					}else{
						$tabla.= '<tr style="font-family: Open Sans,Helvetica,Arial,sans-serif; font-size: 13px; color: #000;">';
					}
					
					$tabla.='<td>'.$maquina->maquina.'</td>';

					if($maquina->id != 0){
						$tabla.='<td>'.$salon->salon.'</td>
								<td>'.$fabricante->nombre.'</td>
								<td>'.$modelo->modelo.'</td>';
					}else{
						$tabla.='<td></td>
								<td></td>
								<td></td>';
					}
									 
					$tabla.='<td>';
					
					if($this->session->userdata('logged_in')['rol'] == 2){
						$tabla.= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("borrar_maquina/".$maquina->id."/".$pagina."/".$consulta_sql."").'" type="button" class="btn btn-danger" alt="Eliminar máquina" title="Eliminar máquina"><i class="fa fa-close"></i></a>';
						if($maquina->id != 0 && $modelo->tipo_maquina == 5){
							$tabla.= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("editar_cajero/".$maquina->id."/".$pagina."/".$consulta_sql."").'" type="button" class="btn btn-warning" alt=Limites Cajero" title=Limites Cajero"><i class="fa fa-globe"></i></a>';
						}
					}
					
					$tabla.= '</td>';
					$tabla.= '</tr>';
					
					$version_movil.='<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">';
				
					$version_movil.='<div class="panel-heading" style="background: #449d44; text-align: center">
																<p style="color: #fff">#'.$maquina->maquina.'</p>
															</div>
															<div class="panel-body" style="padding: 10px">';

					if($maquina->id != 0){
						$version_movil.='<p><span style="font-weight: bold">Salón:</span> '.$salon->salon.'</p>
										<p><span style="font-weight: bold">Fabricante:</span> '.$fabricante->nombre.'</p>										
										<p><span style="font-weight: bold">Modelo:</span> '.$modelo->modelo.'</p>';
					}

					if($this->session->userdata('logged_in')['rol'] == 2){
						
						if($maquina->id != 0 && $modelo->tipo_maquina == 5){
							$version_movil.='<a style="width: 30%; padding: 2px 4px; margin: 0 2px;" href="'.base_url('editar_maquina/'.$maquina->id.'/'.$pagina.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold">Editar</span></a>
														 	<a style="width: 30%; padding: 2px 4px; margin: 0 2px;" href="'.base_url('editar_cajero/'.$maquina->id.'/'.$pagina.'/'.$consulta_sql.'').'" type="button" class="btn btn-warning" alt="Limites Cajero" title="Limites Cajero"><i style="font-size: 30px" class="fa fa-globe"></i><span style="display: block; font-weight: bold">Cajero</span></a>
														 	<a style="width: 30%; padding: 2px 4px; margin: 0 2px;" href="'.base_url('borrar_maquina/'.$maquina->id.'/'.$pagina.'/'.$consulta_sql.'').'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar"><i style="font-size: 30px" class="fa fa-close"></i><span style="display: block; font-weight: bold">Eliminar</span></a>';
						}else{
							$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('editar_maquina/'.$maquina->id.'/'.$pagina.'/'.$consulta_sql.'').'" type="button" class="btn btn-info" alt="Tratamiento" title="Tratamiento"><i style="font-size: 30px" class="fa fa-check"></i><span style="display: block; font-weight: bold">Editar</span></a>
														 	 <a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('borrar_maquina/'.$maquina->id.'/'.$pagina.'/'.$consulta_sql.'').'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar"><i style="font-size: 30px" class="fa fa-close"></i><span style="display: block; font-weight: bold">Eliminar</span></a>';
						}					

					}
					
					$version_movil.='</div></div>';
				}
				
				$data = array('title' => 'Administracion', 'tabla_maquinas' => $tabla, 'html_id_maquinas' => $html_id_maquinas, 'html_salones' => $html_salones, 'html_tipos' => $html_tipos, 'html_monederos' => $html_monederos, 'html_billeteros' => $html_billeteros, 'html_impresoras' => $html_impresoras, 'version_movil' => $version_movil, 'consulta_sql' => $consulta_sql, 'paginas' => $total_paginas, 'pagina' => $pagina, 'salon' => $id_salon, 'tipo_maquina' => $tipo_maquina, 'contador_total_maquinas' => $resultados, 'estilo' => $estilo);
				$this->load_view('maquinas', $data);
			
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nueva_maquina(){
		if($this->session->userdata('logged_in')){
			/* Select salones */
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
				$salones = $this->post->get_salones();
				$html_salones='';
				foreach($salones->result() as $salon){
					$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';				
				}
			}else if($this->session->userdata('logged_in')['rol'] == 2){
				$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				$html_salones='';
				foreach($salones->result() as $salon){
					$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
				}
			}
			
			/* Select fabricantes */
			if(($this->session->userdata('logged_in')['rol'] == 1) || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
				$fabris = $this->post->get_fabricantes_averias();
			}else{
				$fabris = $this->post->get_fabricantes();
			}
			$html_fabricantes = '';
			foreach($fabris->result() as $fabri){
				$html_fabricantes .= '<option value="'.$fabri->id.'">'.$fabri->nombre.'</option>';
			}			
			
			$data = array('title' => '', 'html_salones' => $html_salones, 'html_fabricantes' => $html_fabricantes);
			$this->load_view('nueva_maquina', $data);			
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nueva_maquina_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('salon', 'Salón', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('modelo', 'Modelo', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('puestos', 'Puestos', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
			$data = array('title' => '');
			$this->load_view('nueva_maquina', $data);
		}else{
			$resultado = $this->post->crear_maquina($this->input->post('salon'),$this->input->post('modelo'),$this->input->post('puestos'),$this->input->post('serie1'),$this->input->post('serie2'),$this->input->post('serie3'),$this->input->post('serie4'),$this->input->post('serie5'));
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Crear Máquina');
			$this->maquinas('1');
		}
	}
	
	public function nueva_maquina_form_ajax(){
		$resultado = $this->post->crear_maquina($this->input->post('salon'),$this->input->post('modelo'),$this->input->post('puestos'),$this->input->post('serie1'),$this->input->post('serie2'),$this->input->post('serie3'));
		echo "ok";
	}
	
	public function editar_maquina($id){
		if($this->session->userdata('logged_in')){
			/* Recuperar maquina */
			$maquina = $this->post->get_maquina_completo($id);
			/* Recuperar fabricante */
			$modelo = $this->post->get_modelo($maquina->modelo);
			
			/* Select salones */
			if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
				$salones = $this->post->get_salones();
				$html_salones='';
				foreach($salones->result() as $salon){
					if($salon->id == $maquina->salon){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}	
				}
			}else if($this->session->userdata('logged_in')['rol'] == 2){
				$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				$html_salones='';
				foreach($salones->result() as $salon){
					if($salon->id == $maquina->salon){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}					
				}
			}
			
			if($this->session->userdata('logged_in')['rol'] == 1){
				$salones = $this->post->get_salones();
				$html_salones='';
				foreach($salones->result() as $salon){
					if($salon->id == $maquina->salon){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}
			}
			
			/* Select fabricantes */
			if(($this->session->userdata('logged_in')['rol'] == 1) || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
				$fabris = $this->post->get_fabricantes_averias();
			}else{
				$fabris = $this->post->get_fabricantes();
			}
			$html_fabricantes = '';
			foreach($fabris->result() as $fabri){
				if($fabri->id == $modelo->fabricante){
					$html_fabricantes .= '<option value="'.$fabri->id.'" selected>'.$fabri->nombre.'</option>';
				}else{
					$html_fabricantes .= '<option value="'.$fabri->id.'">'.$fabri->nombre.'</option>';
				}				
			}
			
			/* Select modelos */
			if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
				$modelos = $this->post->get_modelos_averias($modelo->fabricante);
			}else{
				$modelos = $this->post->get_modelos($modelo->fabricante);
			}
			$html_modelos = '';
			foreach($modelos->result() as $modelo){
				if($modelo->id == $maquina->modelo){
					$html_modelos .= '<option value="'.$modelo->id.'" selected>'.$modelo->modelo.'</option>';
				}else{
					$html_modelos .= '<option value="'.$modelo->id.'">'.$modelo->modelo.'</option>';
				}				
			}

			/* Select periféricos */
			$html_monederos = '';
			if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
				$monederos = $this->post->get_monederos_maquinas();			
				foreach($monederos->result() as $monedero){
					if($monedero->maquina == $maquina->id){
						$html_monederos .= '<option value="'.$monedero->id.'" selected>'.$monedero->nombre.'</option>';
					}else{
						$html_monederos .= '<option value="'.$monedero->id.'">'.$monedero->nombre.'</option>';
					}				
				}
			}

			$html_billeteros = '';
			if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
				$billeteros = $this->post->get_billeteros_maquinas();
				foreach($billeteros->result() as $billetero){
					if($billetero->maquina == $maquina->id){
						$html_billeteros .= '<option value="'.$billetero->id.'" selected>'.$billetero->nombre.'</option>';
					}else{
						$html_billeteros .= '<option value="'.$billetero->id.'">'.$billetero->nombre.'</option>';
					}				
				}
			}

			$html_impresoras = '';
			if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
				$impresoras = $this->post->get_impresoras_maquinas();
				foreach($impresoras->result() as $impresora){
					if($impresora->maquina == $maquina->id){
						$html_impresoras .= '<option value="'.$impresora->id.'" selected>'.$impresora->nombre.'</option>';
					}else{
						$html_impresoras .= '<option value="'.$impresora->id.'">'.$impresora->nombre.'</option>';
					}				
				}
			}
			
			$data = array('title' => '', 'maquina' => $maquina, 'html_salones' => $html_salones, 'html_fabricantes' => $html_fabricantes, 'html_modelos' => $html_modelos, 'html_monederos' => $html_monederos, 'html_billeteros' => $html_billeteros, 'html_impresoras' => $html_impresoras);
			$this->load_view('editar_maquina', $data);			
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_maquina_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('salon', 'Salón', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('modelo', 'Modelo', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('maquina', 'Máquina', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
			$data = array('title' => '');
			$this->load_view('nueva_maquina', $data);
		}else{
			$resultado = $this->post->editar_maquina($this->input->post('id'),$this->input->post('salon'),$this->input->post('modelo'),$this->input->post('monedero'),$this->input->post('billetero'),$this->input->post('impresora'),$this->input->post('maquina'));
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Editar Máquina');
			$this->maquinas('1');			
		}
	}
	
	/* Eliminar maquina */
	public function borrar_maquina($id,$pag){
		$borrar = $this->post->borrar_maquina($id);
		if($borrar){
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Eliminar Máquina');
			$this->maquinas($pag);
		}
	}
	
	/* Editar parámetros cajero */
	public function editar_cajero($id){
		if($this->session->userdata('logged_in')){
			/* Recuperar maquina */
			$maquina = $this->post->get_maquina_completo($id);
			$cajero = $this->post->get_cajero($maquina->salon);
			$salon = $this->post->get_salon($maquina->salon);
			$datafono = $this->post->get_datafono($maquina->salon);
			$data = array('title' => '', 'maquina' => $maquina, 'cajero' => $cajero, 'salon' => $salon, 'datafono' => $datafono);
			$this->load_view('editar_cajero', $data);
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_cajero_form($pag=NULL){
		if(!empty($this->input->post('ip_impresora'))){
			$ip_impresora = $this->input->post('ip_impresora');
			$puerto_impresora = $this->input->post('puerto_impresora');
			$puerto_tpv = $this->input->post('puerto_tpv');
		}else{
			$ip_impresora = '';
			$puerto_impresora = '';
			$puerto_tpv = '';
		}
		$resultado = $this->post->editar_cajero($this->input->post('id'), $this->input->post('cajero'), $this->input->post('maquina'),$this->input->post('ip'),$this->input->post('puerto'),$this->input->post('usuario'), $this->input->post('clave'),$this->input->post('collect'), $this->input->post('limite_disponible'), $this->input->post('limite_arqueo'), $this->input->post('version'), $this->input->post('limite_no_activo'),$this->input->post('limite_multimoneda'), $this->input->post('limite_hopper'), $this->input->post('limite_reciclador_cassette1'), $this->input->post('limite_reciclador_cassette2'), $this->input->post('limite_reciclador_cassette3'), $this->input->post('limite_reciclador_cassette4'), $this->input->post('limite_reciclador_cassette5'), $this->input->post('comprobar'),$this->input->post('comprobar_credito'),$ip_impresora,$puerto_impresora,$puerto_tpv,$this->input->post('digitos'),$this->input->post('comprobar_aux'),$this->input->post('num_aux'),$this->input->post('credito_bloqueo'),$this->input->post('comprobar_comision'),$this->input->post('cantidad_comision'),$this->input->post('limite_comision'),$this->input->post('codigo_vip'),$this->input->post('fecha_caducidad'),$this->input->post('credito_espera'),$this->input->post('tiempo_espera'),$this->input->post('descripcion'),$this->input->post('tipo_ticket'));
		if($resultado){
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Editar Cajero');
			$this->maquinas($pag);	
		}
	}

	/* Modificar horario informes horarios */
	public function modificar_horario_informes(){
		$input = filter_input_array(INPUT_POST);
		if(isset($input['fecha'])){ $fecha = $input['fecha']; }else{ $fecha = null; }
		if(isset($input['usuario'])){ $usuario = $input['usuario']; }else{ $usuario = null; }
		if(isset($input['tipo'])){ $tipo = $input['tipo']; }else{ $tipo = null; }
		if ($input['action'] == 'edit') {
			$resultado =$this->post->modificar_horario_informes($input['id'],$fecha,$usuario,$tipo);
		}else if($input['action'] == 'delete'){
			$resultado =$this->post->eliminar_horario_informes($input['id']);
		}
		echo json_encode($input);
	}
	
	/* Obtener empresas AJAX*/
	public function get_empresas_ajax(){
		$q = $this->input->post('q');
		$empresas = $this->post->get_empresas_ajax($q);
		$arr = array();
		foreach($empresas->result() as $empresa){
			array_push($arr, $empresa->empresa);
		}
		echo json_encode($arr);
	}

	/* Notificar incidencia telegram/correo */
	public function notificar_incidencia(){
		$resultado = $this->input->post('resultado');
		$incidencia = $this->post->get_ticket($resultado);
		$situacion = $incidencia->situacion;

		if($situacion != 6){
			if($situacion == 2 || $situacion == 3 || $situacion == 12 || $situacion == 13 || $situacion == 14 || $situacion == 19 || $situacion == 21){
				if($incidencia->prioridad != 3){
					$this->telegram($resultado);
					$this->crear_notificacion_tipster_nueva_incidencia($resultado);
				}
			}				
			if($this->session->userdata('logged_in')['rol'] == 1){
				if($incidencia->prioridad != 3){
					$this->enviarmail($resultado);
				}
			}else{
				if($incidencia->gestion_tipo == 11){
					if($incidencia->prioridad != 3){
						$this->enviarmail($resultado);
					}
				}else{
					$operadora = $this->post->get_operadoras_rol_2($incidencia->operador);
					$op = $operadora->row();
					if($op->Emails == 1){
						if($incidencia->prioridad != 3){
							$this->enviarmail($resultado);
						}
					}
				}
			}						
		}
	}
	
	/* Obtener operadoras segun nombre empresa - AJAX */
	public function get_operadoras_empresa_nombre(){
		$id = $this->input->post('id');
		$salones = $this->post->get_operadoras_empresa_nombre($id);
		$html_salones_ajax = '';
		foreach($salones->result() as $salon){
			$html_salones_ajax.='<option value="'.$salon->id.'">'.$salon->operadora.'</option>';
		}
		echo $html_salones_ajax;
	}
	
	/* Obtener operadoras segun empresa - AJAX */
	public function get_operadoras_empresa(){
		$id = $this->input->post('id');
		$salones = $this->post->get_operadoras_empresa($id);
		$html_salones_ajax = '';
		foreach($salones->result() as $salon){
			$html_salones_ajax.='<option value="'.$salon->id.'">'.$salon->operadora.'</option>';
		}
		echo $html_salones_ajax;
	}
	
	/* Obtener salones segun operadora - AJAX */
	public function get_salones_operadora(){
		$id = $this->input->post('id');
		if($this->session->userdata('logged_in')['rol'] == 1 || (($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24)){
			$salones = $this->post->get_salones_operadora_averias($id);
		}else{
			$salones = $this->post->get_salones_operadora($id);
		}
		$html_salones_ajax = '<option value="">Salón</option>';
		foreach($salones->result() as $salon){
			$html_salones_ajax.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
		}
		echo $html_salones_ajax;
	}
	
	public function get_salones_operadora_personal(){
		$id = $this->input->post('id');
		if($id == 9999999999999999999){
			$salones = $this->post->get_salones();
		}else{
			$salones = $this->post->get_salones_operadora($id);
		}
		$html_salones_ajax = '<option value="9999999999999999999">TODOS</option>';
		foreach($salones->result() as $salon){
			$html_salones_ajax.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
		}
		echo $html_salones_ajax;
	}
	
	public function get_salones_operadora_crear_personal(){
		$id = $this->input->post('id');
		if($id == 0){
			$salones = $this->post->get_salones();
		}else{
			$salones = $this->post->get_salones_operadora_averias($id);
		}
		$html_salones_ajax = '<option value="0">Ninguno</option>';
		foreach($salones->result() as $salon){
			$html_salones_ajax.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
		}
		echo $html_salones_ajax;
	}
	
	public function get_salones_operadora_visitas(){
		$id = $this->input->post('id');
		if($id == 9999999999999999999){
			$salones = $this->post->get_salones();
		}else{
			$salones = $this->post->get_salones_operadora($id);
		}
		$html_salones_ajax = '<option value="9999999999999999999">TODOS</option>';
		foreach($salones->result() as $salon){
			$html_salones_ajax.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
		}
		echo $html_salones_ajax;
	}
	
	public function get_salones_operadora_crear_visitas(){
		$id = $this->input->post('id');
		if($id == 0){
			$salones = $this->post->get_salones();
		}else{
			$salones = $this->post->get_salones_operadora_averias($id);
		}
		$html_salones_ajax = '<option value="0">Ninguno</option>';
		if($salones->num_rows() > 0){
			foreach($salones->result() as $salon){
				$html_salones_ajax.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
			}
		}
		echo $html_salones_ajax;
	}

	public function get_personal_operadora(){
		$id = $this->input->post('id');
		if($id == 0){
			$personal = $this->post->get_personal_nombre();
		}else{
			$personal = $this->post->get_personal_op($id);
		}
		$html_salones_ajax = '<option value="">Personal...</option>';
		if($personal->num_rows() > 0){
			foreach($personal->result() as $persona){
				$html_salones_ajax.='<option value="'.$persona->id.'">'.$persona->nombre.'</option>';
			}
		}
		echo $html_salones_ajax;
	}

	public function get_personal_salon(){
		$id = $this->input->post('id');
		if($id == 0){
			$personal = $this->post->get_personal_nombre();
		}else{
			$personal = $this->post->get_personal_salon($id);
		}
		$html_salones_ajax = '<option value="">Personal...</option>';
		if($personal->num_rows() > 0){
			foreach($personal->result() as $persona){
				$html_salones_ajax.='<option value="'.$persona->id.'">'.$persona->nombre.'</option>';
			}
		}
		echo $html_salones_ajax;
	}
	
	/* Obtener tipo gestion empresa - AJAX */
	public function get_gestion_empresa(){
		$html_gestion_ajax = '';
		if($this->session->userdata('logged_in')['rol'] != 1){
			$html_gestion_ajax .= '<option value="">Tipo avería...</option>';
			if(($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
				$html_gestion_ajax .= '<option value="6">averias</option>';
				$salon = $this->input->post('s');
				$check_salon = $this->post->check_salon($salon);
				if($check_salon){
					$html_gestion_ajax .= '<option value="2">CAJERO</option>
											<option value="3">MÁQUINAS</option>
											<option value="4">RULETA</option>
											<option value="8">LOCAL</option>
											<option value="12">JACKPOT</option>
											<option value="9">Sin detalle</option>';
				}
			}else{
				$html_gestion_ajax .= '<option value="2">CAJERO</option>
										<option value="3">MÁQUINAS</option>
										<option value="4">RULETA</option>
										<option value="8">LOCAL</option>
										<option value="12">JACKPOT</option>
										<option value="9">Sin detalle</option>';
			}
		}else{
			$id = $this->input->post('id');
			$empresa = $this->post->get_gestion_empresa($id);
			if($empresa->tipo_gestion == 0 || $empresa->tipo_gestion == 2){
				$html_gestion_ajax .= '<option value="">Tipo gestión...</option>';
				if($this->session->userdata('logged_in')['rol'] == 1){
					$html_gestion_ajax .= '<option value="6">averias</option>
											<option value="3">MÁQUINAS</option>
											<option value="11">CIRSA</option>
											<option value="12">JACKPOT</option>';
				}else{
					$html_gestion_ajax .= '<option value="1">averias</option>';
				}														
			}else if($empresa->tipo_gestion == 1){
				$html_gestion_ajax .= '<option value="">Tipo gestión...</option>';
				if($this->session->userdata('logged_in')['rol'] == 1){
					$html_gestion_ajax .= '<option value="6">averias</option>';
				}else{
					$html_gestion_ajax .= '<option value="1">averias</option>';
				}
				$html_gestion_ajax .= '<option value="2">CAJERO</option>
										<option value="13">CASINO</option>
										<option value="3">MÁQUINAS</option>
										<option value="4">RULETA</option>
										<option value="10">LLAMADA</option>
										<option value="11">CIRSA</option>
										<option value="8">LOCAL</option>
										<option value="12">JACKPOT</option>
										<option value="9">Sin detalle</option>';
			}
		}
		echo $html_gestion_ajax;
	}
	
	/* Obtener salones segun empresa - AJAX */
	public function get_salones_empresa(){
		$id = $this->input->post('id');
		$salones = $this->post->get_salones_empresa($id);
		$html_salones_ajax = '<option value="">Salón</option>';
		foreach($salones->result() as $salon){
			$html_salones_ajax.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
		}
		echo $html_salones_ajax;
	}
	
	/* Obtener operador segun salon - AJAX */
	public function get_salones_ajax(){
		$id = $this->input->post('id');
		$operadora = $this->post->get_salones_model($id);
		$html_operador_ajax = '';
		foreach($operadora->result() as $operador){
			$html_operador_ajax.='<option value="'.$operador->id.'">'.$operador->operadora.'</option>';
		}
		echo $html_operador_ajax;
	}
	
	/* Obtener maquinas salon gestion - AJAX */
	public function get_maquinas(){
		$op = $this->input->post('op');
		$id = $this->input->post('id');
		$gestion = $this->input->post('g');
		if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
			if($gestion == '3'){
				if($op == 24 || $op == 41){
					$maquinas = $this->post->get_maquinas($id,$gestion);
				}else{
					$maquinas = $this->post->get_maquinas_com_salon($id);
				}
			}else if($gestion == '6'){
				$maquinas = $this->post->get_maquinas($id,$gestion);
			}else{
				$maquinas = $this->post->get_maquinas($id,$gestion);
			}
		}else{
			$maquinas = $this->post->get_maquinas($id,$gestion);
		}
		$html_maquina = '<option value="">Máquina...</option>
										 <option value="0">Sin asignar</option>';
		foreach($maquinas->result() as $maquina){
			$html_maquina.='<option value="'.$maquina->id.'">'.$maquina->maquina.'</option>';
		}
		echo $html_maquina;
	}
	
	/* Obtener maquinas salon - AJAX */
	public function get_maquinas_salon(){
		$id = $this->input->post('id');
		$maquinas = $this->post->get_maquinas_salon($id);
		$html_maquina = '<option value="">Máquina...</option>
						<option value="0">Sin máquina</option>';
		foreach($maquinas->result() as $maquina){
			$html_maquina.='<option value="'.$maquina->id.'">'.$maquina->maquina.'</option>';
		}
		echo $html_maquina;
	}
	
	/* Obtener cliente salon - AJAX */
	public function get_cliente(){
		$id = $this->input->post('id');
		$salon = $this->post->get_cliente($id);
		echo "|".$salon;
	}
	
	/* Obtener situaciones ajax -- Crear incidencia */
	public function get_situaciones_ajax(){
		if($this->session->userdata('logged_in')['rol'] == 1){
			/* Select situacion */
			$situaciones = $this->post->get_situaciones();
			$html_situacion='';
			$html_situacion='<option value="">Situación...</option>';
			foreach($situaciones->result() as $situacion){
				if($situacion->id == 6){
					$html_situacion.='<option value="'.$situacion->id.'">Cerrada</option>';
				}else{
					$html_situacion.='<option value="'.$situacion->id.'">'.$situacion->situacion.'</option>';
				}
			}
		}
		echo $html_situacion;
	}
	
	/* Obtener departamentos/operadora COM */
	public function get_departamentos_com(){
		$id = $this->input->post('id');
		$d = $this->input->post('d');
		$grupos = $this->post->get_grupos_com($id);
		$html_grupo = '<option value="">Destino...</option>';
		if($d == 2){
			$html_grupo.='<option value="2" selected>Comercial ADM</option>';
		}else{
			$html_grupo.='<option value="2">Comercial ADM</option>';
		}
		if($id == 41){
			$html_grupo.='<option value="230">Servicio Técnico Salón</option>';
		}
		if(isset($grupos)){
			if($d == $grupos->id){
				$html_grupo.='<option value="'.$grupos->id.'" selected>'.$grupos->grupo.'</option>';
			}else{
				$html_grupo.='<option value="'.$grupos->id.'">'.$grupos->grupo.'</option>';
			}
		}
		if($this->session->userdata('logged_in')['rol'] == 1){
			echo $html_grupo;
		}else{
			echo "novale";
		}
	}
	
	/* Obtener departamentos/operadora SAT */
	public function get_departamentos_sat(){
		$id = $this->input->post('id');
		$d = $this->input->post('d');
		$s = $this->input->post('s');
		$html_grupo = '';
		if($this->session->userdata('logged_in')['rol'] == 1){
			if($s == 2){
				$html_grupo.='<option value="4" selected>Servicio Técnico ADM</option>';
			}else{
				$grupos = $this->post->get_grupos_sat($id);
				$html_grupo.='<option value="'.$grupos->id.'" selected>'.$grupos->grupo.'</option>';
			}
		}else if($s == 2 && $d == 4){
			$html_grupo.='<option value="4" selected>Servicio Técnico ADM</option>';
		}else{
			$grupos = $this->post->get_grupos_sat($id);
			$html_grupo.='<option value="'.$grupos->id.'" selected>'.$grupos->grupo.'</option>';
		}
		if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2){
			echo $html_grupo;
		}else{
			echo "novale";
		}
	}

	/* Obtener departamentos/operadora INFORMATICA */
	public function get_departamentos_inf(){
		$html_grupo = '';
		$html_grupo.='<option value="32" selected>Informática ADM</option>';
		if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
			echo $html_grupo;
		}else{
			echo "novale";
		}
	}

	/* Obtener departamentos/operadora MARKETING */
	public function get_departamentos_mkt(){
		$html_grupo = '';
		$html_grupo.='<option value="31" selected>Marketing ADM</option>';
		if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
			echo $html_grupo;
		}else{
			echo "novale";
		}
	}

	/* Obtener departamentos/operadora JURIDICO */
	public function get_departamentos_jur(){
		$html_grupo = '';
		$html_grupo.='<option value="260" selected>Juridico ADM</option>';
		if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
			echo $html_grupo;
		}else{
			echo "novale";
		}
	}

	public function get_departamentos_onl(){
		$html_grupo = '';
		$html_grupo.='<option value="261" selected>Online ADM</option>';
		if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
			echo $html_grupo;
		}else{
			echo "novale";
		}
	}

	/* Obtener departamentos/operadora EUSKALTEL */
	public function get_departamentos_eusk(){
		$html_grupo = '';
		$html_grupo.='<option value="1" selected>Atención al Cliente ADM</option>';
		if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
			echo $html_grupo;
		}else{
			echo "novale";
		}
	}
	
	/* Obtener departamentos/operadora */
	public function get_departamentos_ajax(){
		$id = $this->input->post('id');
		$grupos = $this->post->get_grupos_ajax($id);
		$html_grupo = '<option value="">Destino...</option>';
		if($id == 41){
			$html_grupo.='<option value="230">Servicio Técnico Salón</option>';
		}
		foreach($grupos->result() as $grupo){
			$html_grupo.='<option value="'.$grupo->id.'">'.$grupo->grupo.'</option>';
		}
		echo $html_grupo;
	}

	/* Filtrar informes AJAX */
	public function get_informes_ajax(){
		$e = $this->input->post('empresa');
		$s = $this->input->post('supervisora');
		
		if(!isset($e) || empty($e) || $e == '' || $e == 0){
			$e = 0;
		}

		if(!isset($s) || empty($s) || $s == '' || $s == 0){
			$s = 0;
		}

		/* Informes */
		$informes = $this->post->get_informes_visitas_ajax($e,$s);
		$tabla_informes = '';
		foreach($informes->result() as $informe){
			$empresa = $this->post->get_empresa($informe->empresa);
			$fecha = explode('-', $informe->fecha);
			$fecha = $fecha[2]."/".$fecha[1]."/".$fecha[0];
			$usuario = $this->post->get_creador($informe->usuario);
			
			$tabla_informes.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000">';

			if($informe->empresa == 0){
				$tabla_informes.='<td>TODOS</td>';
			}else{
				$tabla_informes.='<td>'.$empresa->empresa.'</td>';
			}
				$tabla_informes.='<td>'.$fecha.'</td>
								<td>'.$usuario.'</td>
								<td>
									<a target="_blank" style="padding: 2px 4px; margin: 0;" href="'.base_url('files/pdf/'.$informe->informe.'').'" type="button" class="btn btn-info" alt="Ver PDF" title="Ver PDF"><i class="fa fa-eye"></i></a>
									<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("eliminar_pdf/".$informe->id."").'" type="button" class="btn btn-danger" alt="Eliminar PDF" title="Eliminar PDF"><i class="fa fa-close"></i></a>
								</td>
							</tr>';
		}

		echo $tabla_informes;
	}
	
	/* Obtener datos tipo cliente */
	public function get_datos_cliente(){
		$cliente = $this->input->post('cliente');
		$op = $this->input->post('op');
		$salon = $this->input->post('salon');
		echo $cliente." ".$op." ".$salon;
		$cliente_datos = $this->post->get_cliente_datos($cliente,$op,$salon);
		if($cliente_datos){
			$nombre = trim($cliente_datos->nombre);
			$email = trim($cliente_datos->email);
			echo "|".$nombre."|".$email;
		}
	}

	/* Get datos usuario nombre */
	public function get_datos_usuario(){
		$usuario = $this->post->get_usuario_nombre($this->input->post('usuario'));
		$telefono = trim($usuario->telefono);
		$email = trim($usuario->email);
		echo "|".$telefono."|".$email;
	}
	
	/* Obtener tipo errores / averia*/
	public function get_error_gestion(){
		$tipo = $this->input->post('tipo');
		if($tipo == 11){
			$tipo = 6;
		}
		$errores = $this->post->get_error_gestion($tipo);
		$html_errores = '<option value="">Tipo error...</option>';
		foreach($errores->result() as $error){
			$html_errores.='<option value="'.$error->id.'">'.$error->tipo.'</option>';
		}
		echo $html_errores;
	}
	
	/* Obtener errores/maquina - AJAX */
	public function get_error_maquina(){
		$id = $this->input->post('id');
		$errores = $this->post->get_error_maquina($id);
		$html_errores = '<option value="">Tipo error...</option>';
		foreach($errores->result() as $error){
			$html_errores.='<option value="'.$error->id.'">'.$error->tipo.'</option>';
		}
		echo $html_errores;
	}
	
	/* Obtener error/detalle - AJAX */
	public function get_error_detalle(){
		$id = $this->input->post('id');
		$errores = $this->post->get_error_detalle($id);
		$html_errores = '<option value="">Detalle error...</option>';
		foreach($errores->result() as $error){
			$html_errores.='<option value="'.$error->id.'">'.$error->error_detalle.'</option>';
		}
		echo $html_errores;
	}

	/* GET data ATC incidencia llamada/consulta */
	public function get_incidencia_llamada(){
		$output = array(
			'operador' => '<option value="24">SAT</option>',
			'salon' => '<option value="385">CENTRAL</option>',
			'gestion' => '<option value="10">LLAMADA</option>',
			'maquina' => '<option value="0">Sin asignar</option>',
			'tipo' => '<option value="137">LLAMADA</option>',
			'detalle' => '<option value="630">LLAMADA</option>',
			'destino' => '<option value="1">Atención al Cliente ADM</option>'
		);
		echo json_encode($output);
	}
	
	/* Obtener modelos/fabricante AJAX - crear maquina */
	public function get_modelos(){
		$id = $this->input->post('id');
		if($this->session->userdata('logged_in')['rol'] == 1){
			$modelos = $this->post->get_modelos_averias($id);
		}else if($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24){
			$modelos = $this->post->get_modelos_sat($id);
		}else{
			$modelos = $this->post->get_modelos($id);
		}
		$html_modelos = '<option value="">Modelo...</option>';
		foreach($modelos->result() as $modelo){
			if($modelo->tipo_maquina == 2){
				$html_modelos.='<option value="'.$modelo->id.'">'.$modelo->modelo.' (SALON)</option>';
			}else if($modelo->tipo_maquina == 3){
				$html_modelos.='<option value="'.$modelo->id.'">'.$modelo->modelo.' (ESPECIAL)</option>';
			}else if($modelo->tipo_maquina == 4){
				$html_modelos.='<option value="'.$modelo->id.'">'.$modelo->modelo.' (BAR)</option>';
			}else{
				$html_modelos.='<option value="'.$modelo->id.'">'.$modelo->modelo.'</option>';
			}
		}
		echo $html_modelos;
	}
	
	/* Get modelo/puestos AJAX - crear maquina */
	public function get_modelo(){
		$id = $this->input->post('id');
		$modelo = $this->post->get_modelo($id);
		$datos = array(
		'tipo' => $modelo->tipo_maquina
		);
		echo json_encode($datos, JSON_FORCE_OBJECT);
	}
	
	/* Obtener puestos/modelo maquina AJAX - crear maquina */
	public function get_puestos_modelo(){
		$id = $this->input->post('id');
		$puestos = $this->post->get_puestos_modelo($id);
		$html_puestos = '';
		if($puestos->tipo_maquina == 1){
			$html_puestos = '<option value="">Puestos...</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>';
		}else if($puestos->tipo_maquina == 2 || $puestos->tipo_maquina == 4 || $puestos->tipo_maquina == 10){
			$html_puestos = '<option value="1">1</option>';
		}else if($puestos->tipo_maquina == 3){
			$html_puestos = '<option value="">Puestos...</option><option value="0">Jackpot</option><option value="2">2</option><option value="3">3</option><option value="5">5</option>';
		}else{
			$html_puestos = '<option value="1">1</option>';
		}
		echo $html_puestos;
	}
	
	/* Get salones zona - AJAX - nuevo mantenimiento */
	public function get_salones_zona(){
		$zona = $this->input->post('id');
		/* Get salones zona */
		$html_salones = '<option value="0">Todos</option>';
		if($zona == 0){
			/* salones */
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
				$salones = $this->post->get_salones_averias();
			}else{
				$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
			}
			foreach($salones->result() as $salon){
				$html_salones .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';

			}
		}else{
			$salones_zona = $this->post->get_salones_zonas($zona);
			foreach($salones_zona->result() as $salon_zona){
				$salon = $this->post->get_salon($salon_zona->salon);
				$html_salones .= '<option value="'.$salon_zona->salon.'">'.$salon.'</option>';
			}
		}	
		echo $html_salones;
	}
	
	/* Obtener acceso/rol - nuevo usuario */
	public function get_acceso(){		
		$id = $this->input->post('id');	
		$html_acceso = '';
		if($id != 3){
			$html_acceso = '<option value="0">TODOS</option>';
		}else{
			$html_acceso = '<option value="">Acceso...</option>';
			$accesos = $this->post->get_acceso($this->session->userdata('logged_in')['acceso']);
			foreach($accesos->result() as $acceso){
				$html_acceso .= '<option value="'.$acceso->id.'">'.$acceso->salon.'</option>';
			}
		}
		echo $html_acceso;		
	}
	
	/* Actualizar depósito gasoil */
	public function nuevo_deposito(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_combustible'] != 1){
				$this->ruletas();
			}else{
				$data = array('title' => '');
				$this->load_view('nuevo_deposito', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nuevo_deposito_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('litros', 'Salón', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('fecha', 'Modelo', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('hora', 'Máquina', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
			$data = array('title' => '');
			$this->load_view('nuevo_deposito', $data);
		}else{
			if($this->input->post('fecha') != ''){
				$fecha = $this->input->post('fecha');
			}else{
				$fecha = date('d/m/Y');
			}
			if($this->input->post('hora') != ''){
				$hora = $this->input->post('hora');
			}else{
				$hora = date('H:i:s');
			}
			$resultado = $this->post->nuevo_deposito($this->input->post('litros'),$fecha,$hora,$this->session->userdata('logged_in')['acceso']);
			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nuevo deposito');
			$this->gasoil();			
		}
	}
	
	/* Buscador gasoil */
	public function buscador_gasoil(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_combustible'] != 1){
				$this->ruletas();
			}else{
				if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){
					$this->form_validation->set_rules('usuario', 'Usuario', 'trim|htmlspecialchars');
					/* Comprobar deposito */
					$tiene_deposito = $this->post->get_operadoras_rol_2($this->session->userdata('logged_in')['acceso']);
					$tiene = $tiene_deposito->row();
					if($tiene->deposito == 1){
						/* Obtener total deposito */
						$total_deposito = 0;
						$deposito_total = $this->post->get_deposito($this->session->userdata('logged_in')['acceso']);
						foreach($deposito_total->result() as $deposito){
							$total_deposito+=$deposito->deposito;
						}
						/* Obtener gasto deposito */
						$total_gasto = 0;
						$deposito_gasto = $this->post->get_gasto_deposito($this->session->userdata('logged_in')['acceso']);
						foreach($deposito_gasto->result() as $gasto){
							$total_gasto+=(float)$gasto->repostaje;
						}
						/* Obtener restante deposito */
						$deposito_actual = $total_deposito-$total_gasto;
						/* Obtener ultimo deposito */
						$ultimo_deposito = $this->post->get_ultimo_deposito($this->session->userdata('logged_in')['acceso']);
					}
					
					/* Get usuarios */
					$usuarios = $this->post->get_usuarios_gasoil($this->session->userdata('logged_in')['acceso']);
					$select_usuarios = '';
					foreach($usuarios->result() as $usuario){
						if($usuario->id == $this->input->post('usuario')){
							$select_usuarios .= '<option value="'.$usuario->id.'" selected>'.$usuario->usuario.'</option>';
						}else{
							$select_usuarios .= '<option value="'.$usuario->id.'">'.$usuario->usuario.'</option>';
						}
					}
					
					/* Buscar repostajes */
					$buscador_gasoil = '';
					$total = 0;
					if($this->session->userdata('logged_in')['rol'] == 7){
						$repostajes = $this->post->get_repostajes_fecha($this->session->userdata('logged_in')['acceso'],$this->input->post('usuario'),$this->input->post('fecha_inicio'),$this->input->post('fecha_fin'));
					}else{
						$repostajes = $this->post->get_repostajes($this->session->userdata('logged_in')['acceso'],$this->input->post('usuario'));
					}
					foreach($repostajes->result() as $repostaje){
						$usuario = $this->post->get_usuario($repostaje->usuario);
						$fecha = explode("-", $repostaje->fecha);
						$vehiculo = $this->post->get_vehiculo($repostaje->matricula);
						$buscador_gasoil.="<tr>
														 		<td>".$usuario->usuario."</td>
														 		<td>".(isset($vehiculo) ? $vehiculo->vehiculo." ".$vehiculo->matricula : '')."</td>
														 		<td>".$repostaje->repostaje." litros</td>
														 		<td>".$repostaje->kilometros." km</td>
														 		<td>".$fecha[2]."-".$fecha[1]."-".$fecha[0]."</td>
														 </tr>";
														 
						$repo = str_replace(",",".",$repostaje->repostaje);
						$total += (float)$repo;
					}
					
					/* Select vehiculos */
					$select_vehiculos = '';
					$vehiculos = $this->post->get_vehiculos($this->session->userdata('logged_in')['acceso']);
					foreach($vehiculos->result() as $vehiculo){
						if($vehiculo->usuario == $this->session->userdata('logged_in')['id']){
							$select_vehiculos .= '<option value="'.$vehiculo->id.'" selected>'.$vehiculo->vehiculo.' '.$vehiculo->matricula.'</option>';
						}else{
							$select_vehiculos .= '<option value="'.$vehiculo->id.'">'.$vehiculo->vehiculo.' '.$vehiculo->matricula.'</option>';
						}
					}
					
					/* Get repostajes */
					$tabla_gasoil = '';
					$repostajes = $this->post->get_ultimos_respostajes($this->session->userdata('logged_in')['id']);
					if($repostajes->num_rows() > 0){
						foreach($repostajes->result() as $repostaje){
							$usuario = $this->post->get_usuario($repostaje->usuario);
							$fecha = explode("-", $repostaje->fecha);
							$vehiculo = $this->post->get_vehiculo($repostaje->matricula);
							$tabla_gasoil.="<tr>
																	<td>".$vehiculo->vehiculo." ".$vehiculo->matricula."</td>
																	<td>".$repostaje->repostaje." litros</td>
																	<td>".$repostaje->kilometros." km</td>
																	<td>".$fecha[2]."-".$fecha[1]."-".$fecha[0]."</td>
															</tr>";
						}
					}
					
					if($tiene->deposito == 1){
						$data = array('title' => '', 'select_usuarios' => $select_usuarios, 'buscador_gasoil' => $buscador_gasoil, 'tiene_deposito' => $tiene->deposito, 'deposito_actual' => $deposito_actual, 'total_deposito' => $total_deposito, 'total_gasto' => $total_gasto, 'ultimo_deposito' => $ultimo_deposito, 'tabla_gasoil' => $tabla_gasoil, 'select_vehiculos' => $select_vehiculos, 'operadora' => $this->session->userdata('logged_in')['acceso'], 'user' => $this->input->post('usuario'), 'fecha_inicio' => $this->input->post('fecha_inicio'), 'fecha_fin' => $this->input->post('fecha_fin'), 'total' => $total);
						$this->load_view('gasoil', $data);
					}else{
						$data = array('title' => '', 'select_usuarios' => $select_usuarios, 'buscador_gasoil' => $buscador_gasoil, 'tabla_gasoil' => $tabla_gasoil, 'select_vehiculos' => $select_vehiculos, 'operadora' => $this->session->userdata('logged_in')['acceso'], 'user' => $this->input->post('usuario'), 'fecha_inicio' => $this->input->post('fecha_inicio'), 'fecha_fin' => $this->input->post('fecha_fin'), 'total' => $total);
						$this->load_view('gasoil', $data);
					}					
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Gasoil */
	public function gasoil(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_combustible'] != 1){
				$this->ruletas();
			}else{
				if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){
					
					/* Comprobar deposito */
					$tiene_deposito = $this->post->get_operadoras_rol_2($this->session->userdata('logged_in')['acceso']);
					$tiene = $tiene_deposito->row();
					if($tiene->deposito == 1){
						/* Obtener total deposito */
						$total_deposito = 0;
						$deposito_total = $this->post->get_deposito($this->session->userdata('logged_in')['acceso']);
						foreach($deposito_total->result() as $deposito){
							$total_deposito+=$deposito->deposito;
						}
						/* Obtener gasto deposito */
						$total_gasto = 0;
						$deposito_gasto = $this->post->get_gasto_deposito($this->session->userdata('logged_in')['acceso']);
						foreach($deposito_gasto->result() as $gasto){
							$total_gasto+=(float)$gasto->repostaje;
						}
						/* Obtener restante deposito */
						$deposito_actual = $total_deposito-$total_gasto;
						$deposito_actual = number_format($deposito_actual, 2);
						/* Obtener ultimo deposito */
						$ultimo_deposito = $this->post->get_ultimo_deposito($this->session->userdata('logged_in')['acceso']);
					}
					
					/* Get usuarios */
					$usuarios = $this->post->get_usuarios_gasoil($this->session->userdata('logged_in')['acceso']);
					$select_usuarios = '';
					foreach($usuarios->result() as $usuario){
						$select_usuarios .= '<option value="'.$usuario->id.'">'.$usuario->usuario.'</option>';
					}
					
					/* Get repostajes */
					$tabla_gasoil = '';
					$repostajes = $this->post->get_ultimos_respostajes($this->session->userdata('logged_in')['id']);
					if($repostajes->num_rows() > 0){
						foreach($repostajes->result() as $repostaje){
							$usuario = $this->post->get_usuario($repostaje->usuario);
							$fecha = explode("-", $repostaje->fecha);
							$vehiculo = $this->post->get_vehiculo($repostaje->matricula);
							$tabla_gasoil.="<tr>";
							if(isset($vehiculo)){
								$tabla_gasoil.="<td>".$vehiculo->vehiculo." ".$vehiculo->matricula."</td>";
							}else{
								$tabla_gasoil.="<td>Desconocido</td>";
							}
							$tabla_gasoil.="<td>".$repostaje->repostaje." litros</td>
											<td>".$repostaje->kilometros." km</td>
											<td>".$fecha[2]."-".$fecha[1]."-".$fecha[0]."</td>
										</tr>";
						}
					}
					
					if($tiene->deposito == 1){
						$data = array('title' => '', 'select_usuarios' => $select_usuarios, 'tiene_deposito' => $tiene->deposito, 'deposito_actual' => $deposito_actual, 'total_deposito' => $total_deposito, 'total_gasto' => $total_gasto, 'ultimo_deposito' => $ultimo_deposito, 'tabla_gasoil' => $tabla_gasoil);
						$this->load_view('gasoil', $data);
					}else{
						$data = array('title' => '', 'select_usuarios' => $select_usuarios, 'tabla_gasoil' => $tabla_gasoil);
						$this->load_view('gasoil', $data);
					}
										
				}else if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 6){
					
					/* Get repostajes */
					$tabla_gasoil = '';
					$repostajes = $this->post->get_ultimos_respostajes($this->session->userdata('logged_in')['id']);
					if($repostajes->num_rows() > 0){
						foreach($repostajes->result() as $repostaje){
							$usuario = $this->post->get_usuario($repostaje->usuario);
							$fecha = explode("-", $repostaje->fecha);
							$vehiculo = $this->post->get_vehiculo($repostaje->matricula);
							$tabla_gasoil.="<tr>
																	<td>".$vehiculo->vehiculo." ".$vehiculo->matricula."</td>
																	<td>".$repostaje->repostaje." litros</td>
																	<td>".$repostaje->kilometros." km</td>
																	<td>".$fecha[2]."-".$fecha[1]."-".$fecha[0]."</td>
															</tr>";
						}
					}
					
					$data = array('title' => '', 'tabla_gasoil' => $tabla_gasoil);
					$this->load_view('gasoil', $data);
						
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Registrar repostaje */
	public function nuevo_repostaje(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_combustible'] != 1){
				$this->ruletas();
			}else{
				/* Select vehiculos */
				$select_vehiculos = '';
				$vehiculos = $this->post->get_vehiculos($this->session->userdata('logged_in')['acceso']);
				foreach($vehiculos->result() as $vehiculo){
					if($vehiculo->usuario == $this->session->userdata('logged_in')['id']){
						$select_vehiculos .= '<option value="'.$vehiculo->id.'" selected>'.$vehiculo->vehiculo.' '.$vehiculo->matricula.'</option>';
					}else{
						$select_vehiculos .= '<option value="'.$vehiculo->id.'">'.$vehiculo->vehiculo.' '.$vehiculo->matricula.'</option>';
					}
				}
				$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nuevo repostaje');
				$data = array('title' => '', 'select_vehiculos' => $select_vehiculos);
				$this->load_view('nuevo_repostaje', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nuevo_repostaje_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_combustible'] != 1){
				$this->ruletas();
			}else{
					$this->form_validation->set_rules('litros', 'Litros', 'trim|htmlspecialchars');
					$this->form_validation->set_rules('km', 'Kilómetros', 'trim|htmlspecialchars|required');
					/* Nuevo repostaje */
					$litros = str_replace(",",".", $this->input->post('litros'));
					$litros2 = str_replace("'",".", $litros);
					$nuevo_repostaje = $this->post->nuevo_repostaje($this->session->userdata('logged_in')['id'],$litros2,$this->input->post('km'),$this->session->userdata('logged_in')['acceso'],$this->input->post('vehiculo'));
					if(!$nuevo_repostaje){
						$error_login = "Ha ocurrido un error, por favor pruebe de nuevo.";
					}else{
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nuevo repostaje');
					}
					/* Get repostajes */
					$tabla_gasoil = '';
					$repostajes = $this->post->get_ultimos_respostajes($this->session->userdata('logged_in')['id']);
					if($repostajes->num_rows() > 0){
						foreach($repostajes->result() as $repostaje){
							$usuario = $this->post->get_usuario($repostaje->usuario);
							$fecha = explode("-", $repostaje->fecha);
							$vehiculo = $this->post->get_vehiculo($repostaje->matricula);
							$tabla_gasoil.="<tr>
												<td>".$vehiculo->vehiculo." ".$vehiculo->matricula."</td>
												<td>".$repostaje->repostaje." litros</td>
												<td>".$repostaje->kilometros." km</td>
												<td>".$fecha[2]."-".$fecha[1]."-".$fecha[0]."</td>
										</tr>";
						}
					}
					$this->gasoil();
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Crear ticket manual */
	public function crear_ticket_manual($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				$this->load->library('ticket_manual');
				$servidor = $this->post->get_servidor_ticket($id);
				$ticket = $this->post->get_ticket_manual($id);
				$incidencia = $this->post->get_ticket($ticket->id_ticket);
				$situacion = $incidencia->situacion;
				$usuario = $this->post->get_usuario($incidencia->asignado);
				$creador = $this->post->get_usuario($incidencia->creador);
				$ticket_manual = $this->ticket_manual->return_ticket_manual($servidor,$ticket,$incidencia,$usuario,$creador);
				if(isset($servidor)){
					if($ticket_manual == 1){
						$fecha = date('Y-m-d');
						$hora = date('H:i:s');
						$img_name = '';
						$peri_ant = $peri_nue = 0;
						$solucionar_ticket = $this->post->solucionar_ticket('6', $incidencia->id, "Ticket manual creado", $peri_ant, $peri_nue, $fecha, $hora, $img_name);
						if($situacion == 2){
							$this->telegram2($incidencia->id, "Ticket manual creado");
						}
						$ticket_manual = "Ticket creado con éxito";
					}else{
						$ticket_manual = "Error creando ticket";
					}
				}else{
					$ticket_manual = "Fallo conectando a cajero";
				}						
				$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Crear ticket manual');		
				$data = array('title' => '', 'ticket_manual' => $ticket_manual);
				$this->load_view('ticket_manual', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Nueva zona */
	public function nueva_zona(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_zonas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				/* salones */
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$salones = $this->post->get_salones_averias();
				}else{
					$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				}
				$html_salones = '';
				foreach($salones->result() as $salon){
					$html_salones .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
				}
				
				/* tecnicos */
				$tecnicos = $this->post->get_tecnicos_op($this->session->userdata('logged_in')['acceso']);
				$html_tecnicos = '';
				foreach($tecnicos->result() as $tecnico){
					$html_tecnicos .= '<option value="'.$tecnico->id.'">'.$tecnico->usuario.'</option>';
				}
				
				$data = array('title' => 'Administracion', 'salones' => $html_salones, 'tecnicos' => $html_tecnicos);
				$this->load_view('nueva_zona', $data);				
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Nueva zona form */
	public function nueva_zona_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_zonas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				$this->form_validation->set_rules('nombre', 'Nombre zona', 'trim|htmlspecialchars');
				if ($this->form_validation->run() == FALSE){
					/* salones */
					$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
					$html_salones = '';
					foreach($salones->result() as $salon){
						$html_salones .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
					
					/* tecnicos */
					$tecnicos = $this->post->get_tecnicos_op($this->session->userdata('logged_in')['acceso']);
					$html_tecnicos = '';
					foreach($tecnicos->result() as $tecnico){
						$html_tecnicos .= '<option value="'.$tecnico->id.'">'.$tecnico->usuario.'</option>';
					}
				
					$data = array('title' => 'Administracion', 'salones' => $html_salones, 'tecnicos' => $html_tecnicos);
					$this->load_view('nueva_zona', $data);	
				}else{
					$resultado = $this->post->nueva_zona($this->input->post('nombre'),$this->session->userdata('logged_in')['acceso']);
					if($resultado){
						if(count($this->input->post("salones") > 0)){
							foreach($this->input->post("salones") as $salon){
							    $salon_zona = $this->post->nuevo_salon_zona($resultado->id,$salon);
							}
						}
						if(count($this->input->post("tecnicos") > 0)){
							foreach($this->input->post("tecnicos") as $tecnico){
							    $tecnico_zona = $this->post->nuevo_tecnico_zona($resultado->id,$tecnico);
							}
						}
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nueva zona');
						$this->zonas();
					}			
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Editar zona */
	public function editar_zona($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_zonas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				/* Get zona */
				$zona = $this->post->get_zona($id);
				
				/* Get salones zona */
				$salones_zona = $this->post->get_salones_zonas($id);
				$array_salones = array();
				foreach($salones_zona->result() as $salon_zona){
					$array_salones[] = $salon_zona->salon;
				}
				
				/* salones */
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$salones = $this->post->get_salones_averias();
				}else{
					$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
				}
				$html_salones = '';
				foreach($salones->result() as $salon){
					if(in_array($salon->id,$array_salones)){
						$html_salones .= '<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}
				
				/* Get tecnicos zona */
				$tecnicos_zona = $this->post->get_tecnicos_zonas($id);
				$array_tecnicos = array();
				foreach($tecnicos_zona->result() as $tecnico_zona){
					$array_tecnicos[] = $tecnico_zona->tecnico;
				}
				
				/* tecnicos */
				$tecnicos = $this->post->get_tecnicos_op($this->session->userdata('logged_in')['acceso']);
				$html_tecnicos = '';
				foreach($tecnicos->result() as $tecnico){
					if(in_array($tecnico->id,$array_tecnicos)){
						$html_tecnicos .= '<option value="'.$tecnico->id.'" selected>'.$tecnico->usuario.'</option>';
					}else{
						$html_tecnicos .= '<option value="'.$tecnico->id.'">'.$tecnico->usuario.'</option>';
					}
				}
				
				$data = array('title' => 'Administracion', 'id' => $id, 'zona' => $zona, 'salones' => $html_salones, 'tecnicos' => $html_tecnicos);
				$this->load_view('editar_zona', $data);	
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Editar zona form */
	public function editar_zona_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_zonas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				$this->form_validation->set_rules('nombre', 'Nombre zona', 'trim|htmlspecialchars');
				if ($this->form_validation->run() == FALSE){
					/* Get zona */
					$zona = $this->post->get_zona($this->input->post('id'));
					
					/* Get salones zona */
					$salones_zona = $this->post->get_salones_zonas($this->input->post('id_zona'));
					$array_salones = array();
					foreach($salones_zona->result() as $salon_zona){
						$array_salones[] = $salon_zona->salon;
					}
					
					/* salones */
					if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$salones = $this->post->get_salones_averias();
					}else{
						$salones = $this->post->get_salones_rol_op($this->session->userdata('logged_in')['acceso']);
					}
					$html_salones = '';
					foreach($salones->result() as $salon){
						if(in_array($salon->id,$array_salones)){
							$html_salones .= '<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salones .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}
					
					/* Get tecnicos zona */
					$tecnicos_zona = $this->post->get_tecnicos_zonas($this->input->post('id_zona'));
					$array_tecnicos = array();
					foreach($tecnicos_zona->result() as $tecnico_zona){
						$array_tecnicos[] = $tecnico_zona->tecnico;
					}
					
					/* tecnicos */
					$tecnicos = $this->post->get_tecnicos_op($this->session->userdata('logged_in')['acceso']);
					$html_tecnicos = '';
					foreach($tecnicos->result() as $tecnico){
						if(in_array($tecnico->id,$array_tecnicos)){
							$html_tecnicos .= '<option value="'.$tecnico->id.'" selected>'.$tecnico->usuario.'</option>';
						}else{
							$html_tecnicos .= '<option value="'.$tecnico->id.'">'.$tecnico->usuario.'</option>';
						}
					}
					
					$data = array('title' => 'Administracion', 'id' => $id, 'zona' => $zona, 'salones' => $html_salones, 'tecnicos' => $html_tecnicos);
					$this->load_view('editar_zona', $data);
				}else{
					$resultado = $this->post->editar_zona($this->input->post('id_zona'),$this->input->post('nombre'),$this->session->userdata('logged_in')['acceso']);
					if($resultado){
						$borrar_salones = $this->post->borrar_salones_zona($this->input->post('id_zona'));
						if(count($this->input->post("salones") > 0)){
							foreach($this->input->post("salones") as $salon){
							    $salon_zona = $this->post->nuevo_salon_zona($this->input->post('id_zona'),$salon);
							}
						}
						
						$borrar_tecnicos = $this->post->borrar_tecnicos_zona($this->input->post('id_zona'));
						if(count($this->input->post("tecnicos") > 0)){
							foreach($this->input->post("tecnicos") as $tecnico){
							    $tecnico_zona = $this->post->nuevo_tecnico_zona($this->input->post('id_zona'),$tecnico);
							}
						}
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nueva zona');
						$this->zonas();
					}else{
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nueva zona');
						$this->gestion();
					}	
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Zonas */
	public function zonas(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_zonas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				$zonas = $this->post->get_zonas($this->session->userdata('logged_in')['acceso']);
				$tabla_zonas = '';
				foreach($zonas->result() as $zona){
					$tabla_zonas .= '<div class="panel panel-default col-md-2 col-sm-12" style="padding: 0; margin: 12px; height: auto">
															<div class="panel-heading" style="background: #449d44; text-align: center">
																<p style="color: #fff; margin: 0 0 5px;">'.$zona->zona.'</p>
															</div>
															<div class="panel-body" style="padding: 10px">
																<p><span style="font-weight: bold">Técnicos</span></p>
																<ul style="border: 1px solid #ccc; display: inline-block; padding: 1%; border-radius: 5px; box-shadow: 2px 2px #ccc;">';
																
					$tecnicos_zonas = $this->post->get_tecnicos_zonas($zona->id);
					foreach($tecnicos_zonas->result() as $tecnico_zona){
						$tecnico = $this->post->get_usuario($tecnico_zona->tecnico);
						$tabla_zonas .= '<li style="list-style-type: none;">'.$tecnico->nombre.'</li>';
					}		

					$tabla_zonas .= '</ul>													
													<p><span style="font-weight: bold">Salones</span></p>
																<ul style="border: 1px solid #ccc; display: inline-block; padding: 1%; border-radius: 5px; box-shadow: 2px 2px #ccc;">';

					$salones_zonas = $this->post->get_salones_zonas($zona->id);
					foreach($salones_zonas->result() as $salon_zona){
						$salon = $this->post->get_salones_rol_salon($salon_zona->salon);
						$tabla_zonas .= '<li style="list-style-type: none;">'.$salon->salon.'</li>';
					}				

					$tabla_zonas .= '</ul>
													 <div style="width: 100%; text-align: center; margin: 3% 0 1% 0; float: left;">
													 		<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('asignar_tecnicos_zonas/'.$zona->id).'" type="button" class="btn btn-info" alt="Editar" title="Editar">
																<i style="font-size: 30px" class="fa fa-user-plus"></i>
																<span style="display: block; font-weight: bold; font-size: 10px">Asignar técnicos</span>
															</a>
															<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('editar_zona/'.$zona->id).'" type="button" class="btn btn-warning" alt="Editar" title="Editar">
																<i style="font-size: 30px" class="fa fa-edit"></i>
																<span style="display: block; font-weight: bold; font-size: 10px">Editar Zona</span>
															</a>													
															<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('eliminar_zona/'.$zona->id).'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar">
																<i style="font-size: 30px" class="fa fa-close"></i>
																<span style="display: block; font-weight: bold; font-size: 10px">Eliminar Zona</span>
															</a>
														</div>					
													</div>
												</div>';					
				}
				
				$data = array('title' => 'Administracion', 'tabla_zonas' => $tabla_zonas);
				$this->load_view('zonas', $data);				
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Asignar ticket técnico */
	public function asignar_ticket_tecnico($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 2){
				$ticket = $this->post->get_ticket($id);			
				$html_asignado = '';
				$inicial = $this->post->get_edicion_inicial($ticket->id);
				$situacion = $this->post->get_situacion($inicial->situacion);
				$operadora = $this->post->get_operadora($ticket->operadora);
				$salon = $this->post->get_salon($ticket->salon);
				$averia = $this->post->get_averia($ticket->tipo_averia);
				$tipo_error = $this->post->get_tipo_error($ticket->tipo_error);
				$detalle_error = $this->post->get_detalle_error($ticket->detalle_error);
				$maquina = $this->post->get_maquina($ticket->maquina);
				$fecha = explode("-", $ticket->fecha_creacion);
				$creador = $this->post->get_creador($ticket->creador);
				
				$html_asignado .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';

				$html_asignado.='<div class="panel-heading" style="background: #d9534f; text-align: center; padding: 5px 4px; font-size: 13px">
									<p style="color: #fff">#'.$ticket->id.' - '.$fecha[2]."-".$fecha[1]."-".$fecha[0].' '.$ticket->hora_creacion.' '.$operadora.' '.$salon.'</p>
								</div>
								<div class="panel-body" style="padding: 0; border: none">
									<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">';					
				
				$error_desc = stripslashes($ticket->error_desc);
				$trata_desc = stripslashes($ticket->trata_desc);				
																
				$html_asignado.='<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
								 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
								 <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
								 </div>
								 <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
									<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion.'</span></p>';			
			
				$error_desc = stripslashes($ticket->error_desc);
				$trata_desc = stripslashes($ticket->trata_desc);				
																
				$html_asignado.='<p><span style="font-weight: bold">Máquina:</span> '.$maquina.'</p>
								<p><span style="font-weight: bold">Creador:</span> '.$creador.'</p>
								<p style="font-weight: bold">Descripción:</p>
								<p>'.$error_desc.'</p>';
																		
				if(!empty($ticket->trata_desc)){
					$html_asignado .= '<p style="font-weight: bold">Tratamiento:</p>
										<p>'.$trata_desc.'</p>';
				}

				$html_asignado.='</div>';
				
				$html_asignado.='<div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
									 <p><span style="font-weight: bold">Prioridad:</span></p>
									 <select class="form-control" name="prioridad">';

				$prioridad = $this->post->get_prioridad();
				foreach($prioridad->result() as $p){
					if($p->id == $ticket->prioridad){
						$html_asignado .= '<option value="'.$p->id.'" selected>'.$p->prioridad.'</option>';
					}else{
						$html_asignado .= '<option value="'.$p->id.'">'.$p->prioridad.'</option>';
					}
				}

				$html_asignado.='</select>
								<p style="margin-top: 5px"><span style="font-weight: bold">Asignado:</span></p>
								 <select class="form-control" name="asignado">';													
				
				/* Select asignado */
				$asignados = $this->post->get_asignados($this->session->userdata('logged_in')['acceso']);
				if($ticket->asignado == 0){
					$html_asignado .= '<option value="0" selected>Nadie</option>';
				}else{
					$asignado_actual = $this->post->get_asignado_actual($ticket->asignado);
					$html_asignado .= '<option value="0">Nadie</option>
										<option value="'.$asignado_actual->id.'" selected>'.$asignado_actual->nombre.'</option>';
				}
				if($this->session->userdata('logged_in')['rol'] == 2){
					foreach($asignados->result() as $asignado){
						if($ticket->asignado != 0 && $asignado_actual->id == $asignado->id){
							continue;
						}else{
							$html_asignado .= '<option value="'.$asignado->id.'">'.$asignado->nombre.'</option>';
						}
					}
				}
				
				$html_asignado .= '</select>
									<label style="margin-top: 15px">Tratamiento</label>
									<div class="input-group">
										<textarea class="form-control" name="error_desc" rows="6" placeholder="Descripcion de la incidencia..."></textarea>
									</div>
								</div>					   
								<div class="col-md-12" style="margin: 5px 0 10px 0; text-align: right; float: left; width: 100%;">
										<button type="submit" class="btn btn-danger dropdown-toggle">
											Aceptar
										</button> 
								</div>							
							</div>
						</div>';				
				
				$data = array('title' => 'Administracion', 'html_asignado' => $html_asignado, 'id_ticket' => $id);
				$this->load_view('asignar_ticket_tecnico', $data);
			}else{
				$data = array('title' => '');
				$this->load->view('gestion', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function asignar_ticket_tecnico_form(){
		if($this->session->userdata('logged_in')){
			/* Asignar ticket */
			$asignado = $this->post->asignar_ticket($this->input->post('id_ticket'),$this->input->post('asignado'),$this->input->post('prioridad'));
			if($asignado){
				if(!empty($this->input->post('error_desc')) && $this->input->post('error_desc') != ''){
					$fecha = date('Y-m-d');
					$hora = date('H:i:s');
					$img_name = '';
					$peri_ant = $peri_nue = 0;
					$ticket = $this->post->solucionar_ticket($asignado, $this->input->post('id_ticket'), $this->input->post('error_desc'), $peri_ant, $peri_nue, $fecha, $hora, $img_name);
				}
				if($this->session->userdata('logged_in')['rol'] == 2){
					$this->telegram5($this->input->post('id_ticket'), $this->input->post('error_desc'));
				}else{
					$this->telegram3($this->input->post('id_ticket'));
				}
				$this->post->guardar_historial($this->session->userdata('logged_in')['id'], 'Asignar Ticket Técnico');
				redirect('gestion', 'refresh');
			}else{
				$data = array('title' => '');
				$this->load_view('asignado',$data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Zonas */
	public function asignar_tecnicos_zonas($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_zonas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				$zona = $this->post->get_zona($id);
				
				/* Get tecnicos zona */
				$tecnicos_zona = $this->post->get_tecnicos_zonas($id);
				$array_tecnicos = array();
				foreach($tecnicos_zona->result() as $tecnico_zona){
					$array_tecnicos[] = $tecnico_zona->tecnico;
				}
				
				/* tecnicos */
				$tecnicos = $this->post->get_tecnicos_op($this->session->userdata('logged_in')['acceso']);
				$html_tecnicos = '';
				foreach($tecnicos->result() as $tecnico){
					if(in_array($tecnico->id,$array_tecnicos)){
						$html_tecnicos .= '<option value="'.$tecnico->id.'" selected>'.$tecnico->usuario.'</option>';
					}else{
						$html_tecnicos .= '<option value="'.$tecnico->id.'">'.$tecnico->usuario.'</option>';
					}
				}
				
				$data = array('title' => 'Administracion', 'id' => $id, 'zona' => $zona, 'tecnicos' => $html_tecnicos);
				$this->load_view('asignar_tecnicos_zonas', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Asignar tecnicos zona form */
	public function asignar_tecnicos_zona_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_zonas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				$this->form_validation->set_rules('nombre', 'Nombre zona', 'trim|htmlspecialchars');
				if ($this->form_validation->run() == FALSE){
					$zona = $this->post->get_zona($this->input->post('id'));
				
					/* Get tecnicos zona */
					$tecnicos_zona = $this->post->get_tecnicos_zonas($this->input->post('id_zona'));
					$array_tecnicos = array();
					foreach($tecnicos_zona->result() as $tecnico_zona){
						$array_tecnicos[] = $tecnico_zona->tecnico;
					}
					
					/* tecnicos */
					$tecnicos = $this->post->get_tecnicos_op($this->session->userdata('logged_in')['acceso']);
					$html_tecnicos = '';
					foreach($tecnicos->result() as $tecnico){
						if(in_array($tecnico->id,$array_tecnicos)){
							$html_tecnicos .= '<option value="'.$tecnico->id.'" selected>'.$tecnico->usuario.'</option>';
						}else{
							$html_tecnicos .= '<option value="'.$tecnico->id.'">'.$tecnico->usuario.'</option>';
						}
					}
				
					$data = array('title' => 'Administracion', 'salones' => $html_salones, 'tecnicos' => $html_tecnicos);
					$this->load_view('asignar_tecnicos_zona', $data);	
				}else{
					$resultado = $this->post->borrar_tecnicos_zona($this->input->post('id_zona'));
					if(count($this->input->post("tecnicos") > 0)){
						foreach($this->input->post("tecnicos") as $tecnico){
						    $tecnico_zona = $this->post->nuevo_tecnico_zona($this->input->post('id_zona'),$tecnico);
						}
					}
					$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Asignar técnicos zona');
					$this->zonas();		
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function eliminar_zona($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_zonas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{
				$resultado = $this->post->eliminar_zona($id);
				if($resultado){
					$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Eliminar zona');
					$this->zonas();
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Editar local */
	public function editar_local($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else{
				$salon = $this->post->get_salon_completo($id);			
				$html_operadoras = '';
				
				if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$operadoras = $this->post->get_operadoras_by_name();
					foreach($operadoras->result() as $operadora){
						if($operadora->id == $salon->operadora){
							$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
						}else{
							$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
						}
					}
				}else{
					$operadoras = $this->post->get_operadoras_rol_2($salon->operadora);
					$operadora = $operadoras->row();
					$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
				}			
				$data = array('title' => 'Administracion', 'html_operadoras' => $html_operadoras, 'salon' => $salon);
				$this->load_view('editar_local', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Nuevo local form */
	public function editar_local_form($sql=NULL,$agrupar=NULL,$columna=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else{
				$this->form_validation->set_rules('poblacion', 'Población', 'trim|htmlspecialchars|required');
				$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('email', 'E-mail', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('direccion', 'Dirección', 'trim|htmlspecialchars|required');
				$this->form_validation->set_rules('horario', 'Horario', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('ip_wan_euskaltel', 'IP WAN Euskaltel', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('ip_internet', 'IP Internet', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('ip_lan_euskaltel', 'IP LAN Euskaltel', 'trim|htmlspecialchars');
				if ($this->form_validation->run() == FALSE){
					$this->locales();
			    }else{
			    	if($this->input->post('activo') == 'on'){
			    		$activo = 1;
			    	}else{
			    		$activo = 0;
			    	}
			      	$resultado = $this->post->editar_local($this->input->post('id_salon'),$this->input->post('direccion'),$this->input->post('fecha_alta'),$this->input->post('poblacion'),$this->input->post('telefono'),$this->input->post('email'),$this->input->post('horario'),$this->input->post('ip_wan_euskaltel'),$this->input->post('ip_euskaltel'),$this->input->post('ip_lan_internet'),$this->input->post('operador'),$activo);
			      	$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Editar local');
			      	$this->locales($sql=NULL,$agrupar=NULL,$columna=NULL);
			    }
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Nuevo local */
	public function nuevo_local(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else{		
				$html_operadoras = '';
				if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 351 || $this->session->userdata('logged_in')['id'] == 571){
					$operadoras = $this->post->get_operadoras_by_name();
					foreach($operadoras->result() as $operadora){				
						$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
					}
				}else{
					$operadoras = $this->post->get_operadoras_rol_2($this->session->userdata('logged_in')['acceso']);
					$operadora = $operadoras->row();
					$html_operadoras.= '<option id="'.$operadora->id.'" value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
				}
				
				$data = array('title' => 'Administracion', 'html_operadoras' => $html_operadoras);
				$this->load_view('nuevo_local', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Nuevo local form */
	public function nuevo_local_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else{
				$this->form_validation->set_rules('nombre', 'Nombre', 'trim|htmlspecialchars|required');
				$this->form_validation->set_rules('poblacion', 'Población', 'trim|htmlspecialchars|required');
				$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('email', 'E-mail', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('direccion', 'Dirección', 'trim|htmlspecialchars|required');
				$this->form_validation->set_rules('horario', 'Horario', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('ip_wan_euskaltel', 'IP WAN Euskaltel', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('ip_internet', 'IP Internet', 'trim|htmlspecialchars');
				$this->form_validation->set_rules('ip_lan_euskaltel', 'IP LAN Euskaltel', 'trim|htmlspecialchars');
				if ($this->form_validation->run() == FALSE){
					$this->locales();
			    }else{
			    	if($this->input->post('activo') == 'on'){
			    		$activo = 1;
			    	}else{
			    		$activo = 0;
			    	}
				    $resultado = $this->post->nuevo_local($this->input->post('direccion'),$this->input->post('nombre'),$this->input->post('fecha_alta'),$this->input->post('poblacion'),$this->input->post('telefono'),$this->input->post('email'),$this->input->post('horario'),$this->input->post('ip_wan_euskaltel'),$this->input->post('ip_lan_euskaltel'),$this->input->post('ip_internet'),$this->input->post('operador'),$activo);
				    $this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nuevo local');
				    $this->locales();
			    }
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Buscador locales */
	public function buscador_locales(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else{
				$get_sql = $this->uri->segment(3);
				$get_sql2 = $this->uri->segment(4);
								
				if($get_sql == 'agrupar' || $get_sql2 == 'agrupar'){
					$agrupar_volver = '1';
					if($get_sql == 'agrupar'){
						$agrupar_volver_columna = $this->uri->segment(4);
					}else if($get_sql2 == 'agrupar'){
						$agrupar_volver_columna = $this->uri->segment(5);
					}
				}else{
					$agrupar_volver = '0';
					$agrupar_volver_columna = '0';
				}

					/* Filtro salones */
					
				
				/* Filtro salones y operadoras */
				$html_op = $html_salones = '';
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$salones = $this->post->get_salones_averias($this->session->userdata('logged_in')['acceso']);
					$html_salones='';
					$html_salones .= '<option value="0">TODOS</value>';
					foreach($salones->result() as $salon){
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}

					$operadoras = $this->post->get_operadoras_activas();
					foreach($operadoras->result() as $operadora){
						$html_op .= '<option value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
					}
				}
				
				/* Get locales */
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					if(!empty($this->input->post('salon'))){
						$locales = $this->post->get_salones_op($this->input->post('salon'));
						$consulta = "AND salon = '".$this->input->post('salon')."'";
					}else if(!empty($this->input->post('operador'))){
						$locales = $this->post->get_salones_averias_op($this->input->post('operador'));
						$consulta = "AND operadora = '".$this->input->post('operador')."'";
					}else{
						$locales = $this->post->get_salones_averias();
						$consulta = "";
					}
				}

				$tabla_locales = '';
				$version_movil = '';
				foreach($locales->result() as $local){
					$op = $this->post->get_operadoras_rol_2($local->operadora);
					$operadora = $op->row();
					
					if($this->session->userdata('logged_in')['rol'] == 2){
						$tabla_locales .= '<tr class="clickable-row" data-href="'.base_url('editar_local/'.$local->id.'').'">';						
					}else{
						$tabla_locales .= '<tr style="font-family: Open Sans,Helvetica,Arial,sans-serif; font-size: 13px; color: #000;">';
					}					
					
				  	$tabla_locales .= '<td>'.$local->salon.'</td>
  									 <td>'.$operadora->operadora.'</td>
									 <td>'.$local->direccion.'</td>
									 <td>'.$local->poblacion.'</td>
									 <td>'.$local->telefono.'</td>
									 <td>'.$local->email.'</td>
									 <td>';
					
					$tabla_locales .= '<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_local/'.$local->id.'').'" type="button" class="btn btn-info" alt="Ficha local" title="Ficha local"><i class="fa fa-eye"></i></a>';									
					
					if($local->Activo == 1){
						$tabla_locales .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_desactivar_local/0/'.$local->id.'').'" type="button" class="btn btn-danger" alt="Desactivar" title="Desactivar"><i class="fa fa-stop"></i></a>';
					}else{
						$tabla_locales .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_desactivar_local/1/'.$local->id.'').'" type="button" class="btn btn-success" alt="Activar" title="Activar"><i class="fa fa-play"></i></a>';
					}
					
					if($this->session->userdata('logged_in')['rol'] == 6){
						$tabla_locales .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('local_img/'.$local->id.'').'" type="button" class="btn btn-success" alt="Añadir imágenes" title="Añadir imágenes"><i class="fa fa-image"></i></a>';
					}
					
					$tabla_locales .= '</td>
														</tr>';
														
					$version_movil.='<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">';
				
					$version_movil.='<div class="panel-heading" style="background: #449d44; text-align: center">
																<p style="color: #fff">'.$local->salon.'</p>
															</div>
															<div class="panel-body" style="padding: 10px">';
																
					$version_movil.='<p><span style="font-weight: bold">Operadora:</span> '.$operadora->operadora.'</p>
											<p><span style="font-weight: bold">Dirección:</span> '.$local->direccion.'</p>										
											<p><span style="font-weight: bold">Población:</span> '.$local->poblacion.'</p>
											<p><span style="font-weight: bold">Horario:</span> '.$local->horario.'</p>
											<p><span style="font-weight: bold">IP Internet:</span> '.$local->ip_internet.'</p>
											<p><span style="font-weight: bold">Teléfono:</span> '.$local->telefono.'</p>
											<p><span style="font-weight: bold">IP LAN Euskaltel:</span> '.$local->ip_lan_euskaltel.'</p>
											<p><span style="font-weight: bold">IP WAN Euskaltel:</span> '.$local->ip_wan_euskaltel.'</p>
											<p><span style="font-weight: bold">Fecha Alta:</span> '.$local->fecha_alta.'</p>';
					
					$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_local/'.$local->id.'').'" type="button" class="btn btn-info" alt="Ficha local" title="Ficha local"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold">Ver local</span></a>';
					
					if($local->Activo == 1){					
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_desactivar_local/0/'.$local->id.'').'" type="button" class="btn btn-danger" alt="Desactivar" title="Desactivar"><i style="font-size: 30px" class="fa fa-stop"></i><span style="display: block; font-weight: bold">Desactivar</span></a>';
					}else{
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_desactivar_local/1/'.$local->id.'').'" type="button" class="btn btn-success" alt="Activar" title="Activar"><i style="font-size: 30px" class="fa fa-play"></i><span style="display: block; font-weight: bold">Activar</span></a>';
					}
					
					if($this->session->userdata('logged_in')['rol'] == 6){
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('local_img/'.$local->id.'').'" type="button" class="btn btn-success" alt="Añadir imágenes" title="Añadir imágenes"><i style="font-size: 30px" class="fa fa-image"></i><span style="display: block; font-weight: bold">Imágenes</span></a>';
					}
					
					$version_movil.='</div></div>';	
				}
				
				$data = array('title' => 'Administracion', 'html_salones' => $html_salones, 'html_op' => $html_op, 'tabla_locales' => $tabla_locales, 'consulta' => $consulta, 'agrupar_volver' => $agrupar_volver, 'agrupar_volver_columna' => $agrupar_volver_columna, 'version_movil' => $version_movil);
				$this->load_view('locales', $data);			
				
			}
		}
	}
	
	/* Locales */
	public function locales($pagina=NULL,$sql_edicion=NULL,$agrupar_edicion=NULL,$columna_agrupar_edicion=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else{
				$get_sql = $this->uri->segment(3);
				$get_sql2 = $this->uri->segment(4);
								
				if($get_sql == 'agrupar' || $get_sql2 == 'agrupar'){
					$agrupar_volver = '1';
					if($get_sql == 'agrupar'){
						$agrupar_volver_columna = $this->uri->segment(4);
					}else if($get_sql2 == 'agrupar'){
						$agrupar_volver_columna = $this->uri->segment(5);
					}
				}else{
					$agrupar_volver = '0';
					$agrupar_volver_columna = '0';
				}

				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9){
					/* Filtro salones */
					$html_salones = '';
					$salones = $this->post->get_salones_averias($this->session->userdata('logged_in')['acceso']);
					$html_salones='';
					$html_salones .= '<option value="0">TODOS</value>';
					foreach($salones->result() as $salon){
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				
					/* Filtro operadoras */
					$html_op = '';
					$operadoras = $this->post->get_operadoras_activas();
					foreach($operadoras->result() as $operadora){
						$html_op .= '<option value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
					}
				}

				/* Get locales */
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$total_locales = $this->post->get_salones_averias();
				}else if($this->session->userdata('logged_in')['rol'] == 2){
					$total_locales = $this->post->get_salones_rol_op_todos($this->session->userdata('logged_in')['acceso']);
				}
				$resultados = $total_locales->num_rows();

				//Limito la busqueda
				$tamanio = 20;

				//examino la página a mostrar y el inicio del registro a mostrar
				if (!$pagina){
				   $inicio = 0;
				   $pagina = 1;
				}else{
				   $inicio = ($pagina - 1) * $tamanio;
				}
				//calculo el total de páginas
				$total_paginas = ceil($resultados / $tamanio);
				
				/* Get locales */
				if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){
					$locales = $this->post->get_salones_averias_pag($inicio,$tamanio);
				}else if($this->session->userdata('logged_in')['rol'] == 2){
					$locales = $this->post->get_salones_rol_op_todos_pag($this->session->userdata('logged_in')['acceso'],$inicio,$tamanio);
				}
				
				$tabla_locales = '';
				$version_movil = '';
				foreach($locales->result() as $local){
					$op = $this->post->get_operadoras_rol_2($local->operadora);
					$operadora = $op->row();
					
					if($this->session->userdata('logged_in')['rol'] == 2){
						$tabla_locales .= '<tr class="clickable-row" data-href="'.base_url('editar_local/'.$local->id.'').'">';						
					}else{
						$tabla_locales .= '<tr style="font-family: Open Sans,Helvetica,Arial,sans-serif; font-size: 13px; color: #000;">';
					}					
					
				  $tabla_locales .= '<td>'.$local->salon.'</td>
									 <td>'.$operadora->operadora.'</td>
									 <td>'.$local->direccion.'</td>
									 <td>'.$local->poblacion.'</td>
									 <td>'.$local->telefono.'</td>
									 <td>'.$local->email.'</td>
									 <td>'.$local->ip_internet.'</td>
									 <td>'.$local->ip_wan_euskaltel.'</td>
									 <td>'.$local->ip_lan_euskaltel.'</td>
									 <td>'.$local->ip_vodafone.'</td>
									 <td>';
					
					$tabla_locales .= '<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_local/'.$local->id.'').'" type="button" class="btn btn-info" alt="Ficha local" title="Ficha local"><i class="fa fa-eye"></i></a>';									
					
					if($local->Activo == 1){
						$tabla_locales .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_desactivar_local/0/'.$local->id.'').'" type="button" class="btn btn-danger" alt="Desactivar" title="Desactivar"><i class="fa fa-stop"></i></a>';
					}else{
						$tabla_locales .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_desactivar_local/1/'.$local->id.'').'" type="button" class="btn btn-success" alt="Activar" title="Activar"><i class="fa fa-play"></i></a>';
					}
					
					if($this->session->userdata('logged_in')['rol'] == 6){
						$tabla_locales .= '<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('local_img/'.$local->id.'').'" type="button" class="btn btn-success" alt="Añadir imágenes" title="Añadir imágenes"><i class="fa fa-image"></i></a>';
					}
					
					$tabla_locales .= '</td>
														</tr>';
														
					$version_movil.='<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">';
				
					$version_movil.='<div class="panel-heading" style="background: #449d44; text-align: center">
																<p style="color: #fff">'.$local->salon.'</p>
															</div>
															<div class="panel-body" style="padding: 10px">';
																
					$version_movil.='<p><span style="font-weight: bold">Operadora:</span> '.$operadora->operadora.'</p>
											<p><span style="font-weight: bold">Dirección:</span> '.$local->direccion.'</p>										
											<p><span style="font-weight: bold">Población:</span> '.$local->poblacion.'</p>
											<p><span style="font-weight: bold">Horario:</span> '.$local->horario.'</p>
											<p><span style="font-weight: bold">IP Internet:</span> '.$local->ip_internet.'</p>
											<p><span style="font-weight: bold">Teléfono:</span> '.$local->telefono.'</p>
											<p><span style="font-weight: bold">IP Euskaltel:</span> '.$local->ip_wan_euskaltel.'</p>
											<p><span style="font-weight: bold">Teléfono Euskaltel:</span> '.$local->ip_lan_euskaltel.'</p>
											<p><span style="font-weight: bold">Fecha Alta:</span> '.$local->fecha_alta.'</p>';
					
					$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_local/'.$local->id.'').'" type="button" class="btn btn-info" alt="Ficha local" title="Ficha local"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold">Ver local</span></a>';
					
					if($local->Activo == 1){					
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_desactivar_local/0/'.$local->id.'').'" type="button" class="btn btn-danger" alt="Desactivar" title="Desactivar"><i style="font-size: 30px" class="fa fa-stop"></i><span style="display: block; font-weight: bold">Desactivar</span></a>';
					}else{
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('activar_desactivar_local/1/'.$local->id.'').'" type="button" class="btn btn-success" alt="Activar" title="Activar"><i style="font-size: 30px" class="fa fa-play"></i><span style="display: block; font-weight: bold">Activar</span></a>';
					}
					
					if($this->session->userdata('logged_in')['rol'] == 6){
						$version_movil.='<a style="width: 46%; padding: 2px 4px; margin: 0 4px;" href="'.base_url('local_img/'.$local->id.'').'" type="button" class="btn btn-success" alt="Añadir imágenes" title="Añadir imágenes"><i style="font-size: 30px" class="fa fa-image"></i><span style="display: block; font-weight: bold">Imágenes</span></a>';
					}
					
					$version_movil.='</div></div>';
				}
				
				$data = array('title' => 'Administracion', 'html_op' => $html_op, 'html_salones' => $html_salones, 'tabla_locales' => $tabla_locales, 'agrupar_volver' => $agrupar_volver, 'agrupar_volver_columna' => $agrupar_volver_columna, 'version_movil' => $version_movil, 'paginas' => $total_paginas, 'pagina' => $pagina);
				$this->load_view('locales', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Ver ficha local */
	public function ver_local($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else{
				$salon = $this->post->get_salon_completo($id);
				$op = $this->post->get_operadoras_rol_2($salon->operadora);
				$operadora = $op->row();

				$html_local = '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
													
				if($salon->Activo == 1){
					$html_local .= '<div class="panel-heading" style="background: #449d44; text-align: center">
														<p style="color: #fff">'.$salon->salon.'</p>
													</div>';
				}else{
					$html_local .= '<div class="panel-heading" style="background: #b21a30; text-align: center">
														<p style="color: #fff">'.$salon->salon.'</p>
													</div>';
				}

				$html_local .= '<div class="panel-body" style="padding: 10px">
									<p><span style="font-weight: bold">Fecha alta:</span> '.$salon->fecha_alta.'</p>
									<p><span style="font-weight: bold">Operadora:</span> '.$operadora->operadora.'</p>
									<p><span style="font-weight: bold">Dirección:</span> '.$salon->direccion.'</p>
									<p><span style="font-weight: bold">Población:</span> '.$salon->poblacion.'</p>
									<p><span style="font-weight: bold">IP Internet:</span> '.$salon->ip_internet.'</p>
									<p><span style="font-weight: bold">Teléfono:</span> '.$salon->telefono.'</p>
									<p><span style="font-weight: bold">E-mail:</span> '.$salon->email.'</p>
									<p><span style="font-weight: bold">IP Euskaltel:</span> '.$salon->ip_wan_euskaltel.'</p>
									<p><span style="font-weight: bold">Teléfono Euskaltel:</span> '.$salon->ip_lan_euskaltel.'</p>
									<p><span style="font-weight: bold">Coordenadas GPS:</span> '.$salon->latitud.' '.$salon->longitud.'</p>
									<p><span style="font-weight: bold">Horario:</span> '.$salon->horario.'</p>';
														
				if($salon->Activo == 1){
					$html_local .= '<p><span style="font-weight: bold">Estado:</span> <span style="color: #449d44;">Activo</span></p>';
				}else{
					$html_local .= '<p><span style="font-weight: bold">Estado:</span> <span style="color: #b21a30;">No activo</span></p>';
				}
													
				$html_local .= '</div>
							</div>';

				// Imagenes local											
				$salon_img = $this->post->get_images_salon($id);				
				$img_container = '';
				if($salon_img->num_rows() > 0){
					foreach($salon_img->result() as $img){
						$fecha = explode("-", $img->fecha_creacion);
						$maquina = $this->post->get_maquina($img->maquina);
						$tipo_error = $this->post->get_tipo_error($img->tipo_error);
						$detalle_error = $this->post->get_detalle_error($img->detalle_error);
						$img_container .= '<div style="padding: 5px; margin: 5px; border: 1px solid #ddd; border-radius: 5px;" class="col-md-3 col-sm-12">
										 		<a href="https://domain/tickets/files/img/locales/'.$img->imagen.'" target="_blank">
										 			<img style="width: 100%;" src="'.base_url("files/img/locales/".$img->imagen."").'">
										 		</a>
										 		<p>Ticket <span style="font-weight: bold"> #'.$img->id.'</span> '.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].' '.$img->hora_creacion.'</p>
										 		<p>'.$maquina.' '.$tipo_error.' '.$detalle_error.'</p>
										 </div>';
					}
				}

				// Imagenes incidencias											
				$ticket_img = $this->post->get_images_salon_ticket($id);			
				if($ticket_img->num_rows() > 0){
					foreach($ticket_img->result() as $img){
						$fecha = explode("-", $img->fecha_creacion);
						$maquina = $this->post->get_maquina($img->maquina);
						$tipo_error = $this->post->get_tipo_error($img->tipo_error);
						$detalle_error = $this->post->get_detalle_error($img->detalle_error);
						if(isset($img->imagen) && $img->imagen != ''){
							$img_container .= '<div style="padding: 5px; margin: 5px; border: 1px solid #ddd; border-radius: 5px;" class="col-md-3 col-sm-12">
													<a href="https://domain/tickets/files/img/errores/'.$img->imagen.'" target="_blank">
											 			<img style="width: 100%;" src="'.base_url("files/img/errores/".$img->imagen."").'">
											 		</a>
											 		<p>Ticket <span style="font-weight: bold"> #'.$img->id.'</span> '.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].' '.$img->hora_creacion.'</p>
											 		<p>'.$maquina.' '.$tipo_error.' '.$detalle_error.'</p>
											 </div>';
						}
						if(isset($img->imagen2) && $img->imagen2 != ''){
							$img_container .= '<div style="padding: 5px; margin: 5px; border: 1px solid #ddd; border-radius: 5px;" class="col-md-3 col-sm-12">
													<a href="https://domain/tickets/files/img/trata/'.$img->imagen2.'" target="_blank">
											 			<img style="width: 100%;" src="'.base_url("files/img/trata/".$img->imagen2."").'">
											 		</a>
											 		<p>Ticket <span style="font-weight: bold"> #'.$img->id.'</span> '.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].' '.$img->hora_creacion.'</p>
											 		<p>'.$maquina.' '.$tipo_error.' '.$detalle_error.'</p>
											 </div>';
						}

						// Imagenes múltiples
						$ticket_img_extra = $this->post->get_imagenes_extra_ticket($img->id);
						if($ticket_img_extra->num_rows() > 0){
							$i = 0;
							foreach($ticket_img_extra->result() as $img2){
								if($i == 0){
									$i++;
									continue;
								}
								if(isset($img2->imagen) && $img2->imagen != ''){
									$img_container .= '<div style="padding: 5px; margin: 5px; border: 1px solid #ddd; border-radius: 5px;" class="col-md-3 col-sm-12">
															<a href="https://domain/tickets/files/img/errores/'.$img2->imagen.'" target="_blank">
													 			<img style="width: 100%;" src="'.base_url("files/img/errores/".$img2->imagen."").'">
													 		</a>
													 		<p>Ticket <span style="font-weight: bold"> #'.$img->id.'</span> '.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].' '.$img->hora_creacion.'</p>
													 		<p>'.$maquina.' '.$tipo_error.' '.$detalle_error.'</p>
													 </div>';
								}
								$i++;
							}
						}
					}
				}

				$data = array('title' => '', 'html_local' => $html_local, 'img_container' => $img_container);
				$this->load_view('ver_local', $data);
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Activar local */
	public function activar_desactivar_local($set,$id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else{
				$activar = $this->post->activar_desactivar_local($set,$id);
				if($activar){
					$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Modificar estado local');
					$this->locales('1');
				}
			}
		}
	}
	
	/* Imágenes local */
	public function local_img($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$salon = $this->post->get_salon_completo($id);
				$salon_img = $this->post->get_images_salon($id);
				
				$img_container = '';
				if($salon_img->num_rows() > 0){
					foreach($salon_img->result() as $img){
						$img_container .= '<div style="padding: 20px" class="col-md-3 col-sm-12">
															 		<img style="width: 100%" src="'.base_url("files/img/locales/".$img->imagen."").'">
															 		<div style="text-align: center; width: 100%; padding: 5px 0;">
															 			<a style="padding: 4px 8px; border-radius: 15px;" href="'.base_url('eliminar_imagen/'.$img->id.'/'.$img->salon.'').'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar"><i class="fa fa-close"></i></a>
															 		</div>
															 </div>';
					}
				}else{
					$img_container = '<p style="text-align: center; font-weight: bold; margin: 20px 0;">No hay imágenes del local</p>';
				}
				
				$data = array('title' => '', 'img_container' => $img_container, 'salon' => $salon);
				$this->load_view('local_img', $data);
			}
		}		
	}
	
	/* Eliminar imágen */
	public function eliminar_imagen($id,$salon){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_locales'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$imagen = $this->post->get_image($id);
				$eliminar = $this->post->eliminar_imagen($id);
				unlink(APPPATH."../tickets/files/img/locales/".$imagen->imagen."");
				$this->local_img($salon);
			}
		}	
	}
		
	/* Ruletas */
	public function ruletas(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_ruletas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				/* Get ruletas salones operadora */
				$ruletas = $this->post->get_ruletas($this->session->userdata('logged_in')['acceso']);
				$tabla_ruletas = '';
				foreach($ruletas->result() as $ruleta){
					$salon = $this->post->get_salones_rol_salon($ruleta->salon);
					$maquina = str_replace('P1', '', $ruleta->maquina);
					$tabla_ruletas .= "<option value='".$salon->id."' data-href='".base_url("ruleta/".$salon->id."")."'>
																	".$salon->salon."
															 </option>";
				}
				
				$data = array('title' => 'Administracion', 'tabla_ruletas' => $tabla_ruletas);
				$this->load_view('ruletas', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function ruleta_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_ruletas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] != 2){
				$this->gestion();
			}else{
				$this->ruleta($this->input->post('salon'));
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Ruleta/salon */
	public function ruleta($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_ruletas'] != 1){
				$this->gestion();
			}else if($this->session->userdata('logged_in')['rol'] != 2){
				$this->gestion();
			}else{
				$salon = $this->post->get_salones_rol_salon($id);
				$servidor = $this->post->get_ruleta($id);
				$limites = $this->post->get_limites($id);
				$puestos = $this->post->get_puestos_ruleta($id);
				$this->load->library('ruleta');
				if(isset($servidor)){
					$puertos = $this->comprobar_puertos($servidor->servidor,$servidor->puerto);
					if($puertos){
						$ruleta = $this->ruleta->return_ruleta($id,$salon,$servidor,$puestos->puestos,$limites);
			      		$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Consulta Ruleta');
						$data = array('title' => 'Administracion', 'salon' => $salon, 'ruleta' => $ruleta);
						$this->load_view('ruleta', $data);
					}else{
						$error = "Cajero no responde, Servidor desconectado o puerto cerrado. Por favor revise la configuración.";				
						/* Get ruletas salones operadora */
						$ruletas = $this->post->get_ruletas($this->session->userdata('logged_in')['acceso']);
						$tabla_ruletas = '';
						foreach($ruletas->result() as $ruleta){
							$salon = $this->post->get_salones_rol_salon($ruleta->salon);
							$maquina = str_replace('P1', '', $ruleta->maquina);
							$tabla_ruletas .= "<option value='".$salon->id."' data-href='".base_url("ruleta/".$salon->id."")."'>
																			".$salon->salon."
																	 </option>";
						}					
						$data = array('title' => 'Administracion', 'error' => $error, 'tabla_ruletas' => $tabla_ruletas);
						$this->load_view('ruletas', $data);
					}
				}else{
					$error = "Cajero no responde, Servidor desconectado o puerto cerrado. Por favor revise la configuración.";					
					/* Get ruletas salones operadora */
					$ruletas = $this->post->get_ruletas($this->session->userdata('logged_in')['acceso']);
					$tabla_ruletas = '';
					foreach($ruletas->result() as $ruleta){
						$salon = $this->post->get_salones_rol_salon($ruleta->salon);
						$maquina = str_replace('P1', '', $ruleta->maquina);
						$tabla_ruletas .= "<option value='".$salon->id."' data-href='".base_url("ruleta/".$salon->id."")."'>
																		".$salon->salon."
																 </option>";
					}				
					$data = array('title' => 'Administracion', 'error' => $error, 'tabla_ruletas' => $tabla_ruletas);
					$this->load_view('ruletas', $data);
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Cajeros */
	public function cajeros(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_cajeros'] != 1){
				$this->gestion();
			}else{
				if($this->session->userdata('logged_in')['rol'] == 3){
					$this->gestion();
				}else{
					/* Seleccionar salones */
					if($this->session->userdata('logged_in')['rol'] == 1){
						$salones = $this->post->get_salones_cajeros2(24);
					}else{
						$salones = $this->post->get_salones_cajeros2($this->session->userdata('logged_in')['acceso']);
					}
					$html_salones = '';					
					$i = 0;
					foreach($salones->result() as $salon){
						if($salon->id == 385 || $salon->id == 413){
							continue;
						}else{
							$html_salones .= "<option value='".$salon->id."' data-href='".base_url("cajero/".$salon->id."")."'>
													".$salon->salon."
											 </option>";							
						}									
					}
					if($this->session->userdata('logged_in')['acceso'] == 21){
						$html_salones .= "<option value='794' data-href='".base_url("cajero/794")."'>
											SAN ANTON 2
									 </option>";
					}
					$data = array('title' => '', 'html_salones' => $html_salones);
					$this->load_view('cajeros', $data);
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function cajero_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_cajeros'] != 1){
				$this->gestion();
			}else{
				$this->cajero($this->input->post('salon'));
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Intimus */
	public function prueba_intimus($id){
		$this->load->library('cajero');
		$salon = $this->post->get_salon($id);
		$cajero_info = $this->post->get_cajero($id);
		if($cajero_info){
			$cajero = $this->cajero->return_cajero4($id,$salon,$cajero_info);
			$data = array('title' => '', 'cajero' => $cajero, 'salon' => $id);
			$this->load_view('cajero', $data);
		}
	}
	
	/* Cajero */
	public function cajero($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['permiso_cajeros'] != 1){
				$this->gestion();
			}else{
				if($this->session->userdata('logged_in')['rol'] == 3){
					$this->gestion();
				}else{
					$this->load->library('cajero');
					$salon = $this->post->get_salon($id);
					$cajero_info = $this->post->get_cajero($id);
					$maquina = $this->post->get_maquina_salon_tipo($id,'5');
					if($cajero_info){	
						$puertos = $this->comprobar_puertos2($cajero_info->servidor,$cajero_info->puerto);
						if($puertos){
							if($maquina->modelo == 182){
								$cajero = $this->cajero->return_cajero2($id,$salon,$cajero_info);
							}else if($maquina->modelo == 185){
								$cajero = $this->cajero->return_cajero3($id,$salon,$cajero_info);
							}else{
								$cajero = $this->cajero->return_cajero($id,$salon,$cajero_info);
							}
			        		$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Consulta Cajero');		
							$data = array('title' => '', 'cajero' => $cajero, 'salon' => $id);
							$this->load_view('cajero', $data);
						}else{
							$error = "Cajero no responde, Servidor desconectado o puerto cerrado. Por favor revise la configuración.";				
							/* Seleccionar salones */
							$salones = $this->post->get_salones_cajeros2($this->session->userdata('logged_in')['acceso']);
							$html_salones = '';
							foreach($salones->result() as $salon){
								if($salon->id == 385 || $salon->id == 413){
									continue;
								}else{
									$html_salones .= "<option value='".$salon->id."' data-href='".base_url("cajero/".$salon->id."")."'>
																			".$salon->salon."
																	 </option>";
								}
							}							
							$data = array('title' => '', 'error' => $error, 'html_salones' => $html_salones);
							$this->load_view('cajeros', $data);
						}
					}
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Seccion Visitas - Comerciales */
	public function visitas(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$operadoras = $this->post->get_operadoras_com();
				$html_op = '{ Name : "TODAS", Id : "" },';
				foreach($operadoras->result() as $op){
					$html_op .= '{ Name : "'.$op->operadora.'", Id : "'.$op->id.'" },';
				}

				$salones = $this->post->get_salones_averias_com();
				$html_salones = '{ Name : "TODOS", Id : "" },';
				foreach($salones->result() as $salon){
					$html_salones .= '{ Name : "'.$salon->salon.'", Id : "'.$salon->id.'" },';
				}

				$personal = $this->post->get_personal_nombre();
				$html_personal = '{ Name : "TODOS", Id : "" },';
				foreach($personal->result() as $persona){
					$html_personal .= '{ Name: "'.$persona->nombre.'", Id: "'.$persona->id.'" },';
				}

				$supervisoras = $this->post->get_supervisoras();
				$html_supervisoras = '{ Name : "TODAS", Id : "" },';
				foreach($supervisoras->result() as $supervisora){
					$html_supervisoras .= '{ Name: "'.$supervisora->nombre.'", Id: "'.$supervisora->id.'" },';
				}

				$html_visitas = '';
				$visitas = $this->post->get_visitas();
				foreach($visitas->result() as $visita){

					$html_visitas .= '{ "id": '.$visita->id.',';

					if($visita->operadora == 0){
						$html_visitas .= '"Operadora": "0",';
					}else{
						$html_visitas .= '"Operadora": "'.$visita->operadora.'",';
					}

					if($visita->salon == 0){
						$html_visitas .= '"Salon": "0",';
					}else{
						$html_visitas .= '"Salon": "'.$visita->salon.'",';
					}

					if($visita->personal1 == 0){
						$html_visitas .= '"Personal1": "0",';
					}else{
						$html_visitas .= '"Personal1": "'.$visita->personal1.'",';
					}

					if($visita->personal2 == 0){
						$html_visitas .= ' "Personal2": 0,';
					}else{
						$html_visitas .= '"Personal2": "'.$visita->personal2.'",';
					}

					if(!isset($visita->fecha) || empty($visita->fecha) || $visita->fecha == ''){
						$html_visitas .= '"Fecha": "",';
					}else{
						$html_visitas .= '"Fecha": "'.$visita->fecha.'",';
					}

					$html_visitas .= '"Supervisora": "'.$visita->creador.'",';

					if(!isset($visita->observaciones) || empty($visita->observaciones) || $visita->observaciones == ''){
						$html_visitas .= '"Observaciones": ""';
					}else{
						$observaciones = substr($visita->observaciones, 0, 30);
						$observaciones = stripslashes($observaciones);
						$observaciones = htmlspecialchars_decode($observaciones);
						$html_visitas .= "'Observaciones': '".$observaciones."',";
					}

					$html_visitas .= '},';
				}

				$data = array('title' => '', 'html_op' => $html_op, 'html_salones' => $html_salones, 'html_personal' => $html_personal, 'visitas' => $html_visitas, 'html_supervisoras' => $html_supervisoras);
				$this->load_view('visitas', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function visitas_filter_data(){
		$method = $_SERVER['REQUEST_METHOD'];

		if($method == 'GET'){
			if(isset($_GET['Supervisora']) && $_GET['Supervisora'] != ''){
				if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
					$visitas = $this->post->get_visitas_salon_supervisora($_GET['Salon'],$_GET['Supervisora']);
				}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
					$visitas = $this->post->get_visitas_op_supervisora($_GET['Operadora'],$_GET['Supervisora']);
				}else{
					$visitas = $this->post->filtrar_visitas_supervisora($_GET['Supervisora']);
				}
			}else if(isset($_GET['Personal1']) && $_GET['Personal1'] != ''){
				if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
					$visitas = $this->post->get_visitas_salon_nombre($_GET['Salon'],$_GET['Personal1']);
				}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
					$visitas = $this->post->get_visitas_op_nombre($_GET['Operadora'],$_GET['Personal1']);
				}else{
					$visitas = $this->post->filtrar_visitas($_GET['Personal1']);
				}
			}else if(isset($_GET['Observaciones']) && $_GET['Observaciones'] != ''){
				if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
					$visitas = $this->post->get_visitas_salon_ob($_GET['Salon'],$_GET['Observaciones']);
				}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
					$visitas = $this->post->get_visitas_op_ob($_GET['Operadora'],$_GET['Observaciones']);
				}else{
					$visitas = $this->post->get_visitas_ob($_GET['Observaciones']);
				}
			}else{
				if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
					$visitas = $this->post->get_visitas_salon($_GET['Salon']);
				}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
					$visitas = $this->post->get_visitas_op($_GET['Operadora']);
				}else{
					$visitas = $this->post->get_visitas();
				}
			}
			if($visitas->num_rows() > 0){
				foreach($visitas->result() as $visita){

					if($visita->operadora == 0){
						$op = 0;
					}else{
						$op = $this->post->get_operadoras_rol_2($visita->operadora);
						if($op->num_rows() != 0){
							$operadora = $op->row();
							$op = $operadora->id;
						}else{
							$op = 0;
						}
					}

					if($visita->salon == 0){
						$salon = '0';
					}else{
						$salon = $this->post->get_salon_completo($visita->salon);
						if($salon){
							$salon = $salon->id;
						}else{
							$salon = '0';
						}
					}

					if($visita->personal1 == 0){
						$personal1 = '0';
					}else{
						$personal1 = $visita->personal1;
					}

					if($visita->personal2 == 0){
						$personal2 = '0';
					}else{
						$personal2 = $visita->personal2;
					}

					if(!isset($visita->fecha) || empty($visita->fecha) || $visita->fecha == ''){
						$fecha = '';
					}else{
						$fecha = $visita->fecha;
					}

					$supervisora = $visita->creador;

					$observaciones = substr($visita->observaciones, 0, 30);
					$observaciones = stripslashes($observaciones);
					$observaciones = htmlspecialchars_decode($observaciones);

					$output[] = array(
						'id' => $visita->id,
						'Operadora' => $op,
						'Salon' => $salon,
						'Personal1' => $personal1,
						'Personal2' => $personal2,
						'Fecha' => $fecha,
						'Supervisora' => $supervisora,
						'Observaciones' => $observaciones,
					);
				}
			}else{
				$output = array();
			}
			echo json_encode($output);
		}

		if($method == 'PUT'){
			parse_str(file_get_contents("php://input"), $_PUT);
			$this->post->actualizar_visita($_PUT);
		}

		if($method == 'DELETE'){
			parse_str(file_get_contents("php://input"), $_DELETE);
		 	$this->post->eliminar_visita($_DELETE['id']);
		}
	}

	public function visitas_filter_select(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'GET'){
			if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
				$salones = $this->post->get_salones_operadora_averias($_GET['Operadora']);
			}else{
				$salones = $this->post->get_salones_averias();
			}
			$output[] = array(
				'Id' => '',
				'Name' => 'TODOS'
			);
			foreach($salones->result() as $salon){
				$output[] = array(
					'Id' => $salon->id,
					'Name' => $salon->salon
 				);
			}
			echo json_encode($output);
		}
	}

	public function visitas_personal_filter_select(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'GET'){
			if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
				$personal = $this->post->get_personal_salon($_GET['Salon']);
			}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
				$personal = $this->post->get_personal_op($_GET['Operadora']);
			}else{
				$personal = $this->post->get_personal_nombre();
			}
			$output[] = array(
				'Id' => '',
				'Name' => 'TODOS'
			);
			foreach($personal->result() as $persona){
				$output[] = array(
					'Id' => $persona->id,
					'Name' => $persona->nombre
 				);
			}
			echo json_encode($output);
		}
	}

	public function edit_salones_select(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'GET'){
			$html_salones = '<option value="">TODOS</option>';
			if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
				$salones = $this->post->get_salones_operadora_averias($_GET['Operadora']);
			}else{
				$salones = $this->post->get_salones_averias();
			}
			foreach($salones->result() as $salon){
				$html_salones .= "<option value='".$salon->id."'>".$salon->salon."</option>";
			}
			echo json_encode($html_salones);
		}
	}

	public function edit_personal_select(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'GET'){
			$html_personal = '<option value=""></option>';			
			if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
				$personal = $this->post->get_personal_salon($_GET['Salon']);
			}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
				$personal = $this->post->get_personal_op($_GET['Operadora']);
			}else{
				$personal = $this->post->get_personal();
			}
			foreach($personal->result() as $persona){
				$html_personal .= "<option value='".$persona->id."'>".$persona->nombre."</option>";
			}
			echo json_encode($html_personal);
		}
	}

	public function subir_imagen_visita(){
		if (!empty($_FILES)) {
			$images = $_FILES['image_data'];
			$filenames = $images['name'];
			$ext = explode('.', basename($filenames));
			$img_name = md5(uniqid()) . ".jpg"; 
			$target = APPPATH."../tickets/files/img/visitas/" . $img_name;			    
			move_uploaded_file($images['tmp_name'], $target);
			echo $img_name;
		}
	}

	public function subir_imagen_personal(){
		if (!empty($_FILES)) {
			$images = $_FILES['image_data'];
			$filenames = $images['name'];
			$ext = explode('.', basename($filenames));
			$img_name = md5(uniqid()) . ".jpg"; 
			$target = APPPATH."../tickets/files/img/personal/" . $img_name;				    
			move_uploaded_file($images['tmp_name'], $target);
			echo $img_name;
		}
	}

	/* Nueva visita */
	public function nueva_visita(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{				
				/* Filtro operadoras */
				$html_op = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$operadoras = $this->post->get_operadoras_com();
					foreach($operadoras->result() as $operadora){
						$html_op .= '<option value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
					}
				}
				
				/* Filtro salones */
				$html_salon = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$salones = $this->post->get_salones();
					foreach($salones->result() as $salon){
						$html_salon .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}
				
				/* select personal */
				$html_personal = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$personal = $this->post->get_personal_nombre();
					foreach($personal->result() as $persona){
						$html_personal .= '<option value="'.$persona->id.'">'.$persona->nombre.'</option>';
					}
				}
				
				$data = array('title' => '', 'html_op' => $html_op, 'html_salon' => $html_salon, 'html_personal' => $html_personal);
				$this->load_view('nueva_visita', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nueva_visita_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('personal1', 'Personal 1', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('personal2', 'Personal 2', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('operador', 'Operadora', 'trim|htmlspecialchars|required');
		$this->form_validation->set_rules('salon', 'Salon', 'trim|htmlspecialchars|required');
		$this->form_validation->set_rules('fecha', 'Fecha', 'trim|htmlspecialchars|required');
		if ($this->form_validation->run() == FALSE){
	    	$this->nueva_visita();
	    }else{
	    	if($this->input->post('checklist_activado') == 1){
	    		if(empty($_POST['check_list'])){
			    	$checklist = false;
			    }else{
			    	$checklist = $_POST['check_list'];
			    }
	    	}else{
	    		$checklist = false;
	    	}

		    $texto = preg_replace( "/\r|\n/", "", $this->input->post('texto'));

		    $imagenes = explode(" ", $this->input->post('imagen_subida'));

	      	$resultado = $this->post->crear_visita($this->input->post('operador'),$this->input->post('salon'),$this->input->post('fecha'),$this->input->post('personal1'),$this->input->post('personal2'),$texto,$checklist,$this->input->post('taburete'),$this->input->post('mesa'),$this->input->post('tablero'),$imagenes);
	      	if($resultado){    
	      		$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Crear visita');
	      		$this->visitas();
	      	}
	    }
	}
	
	/* Editar personal */
	public function editar_visita($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$visita = $this->post->get_visita($id);
				$visitas_checklist = $this->post->get_visita_checklist($id);
				/* Filtro operadoras */
				$html_op = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$operadoras = $this->post->get_operadoras_com();
					foreach($operadoras->result() as $operadora){
						if($operadora->id == $visita->operadora){
							$html_op .= '<option value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
						}else{
							$html_op .= '<option value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
						}
					}
				}
				
				/* Filtro salones */
				$html_salon = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$salones = $this->post->get_salones_operadora_averias($visita->operadora);
					foreach($salones->result() as $salon){
						if($salon->id == $visita->salon){
							$html_salon .= '<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salon .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}
				}
				
				/* select personal */
				$html_personal = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$personal = $this->post->get_personal_salon_op($visita->operadora,$visita->salon);
					foreach($personal->result() as $persona){
						if($persona->id == $visita->personal1 || $persona->nombre == $visita->personal1){
							$html_personal .= '<option value="'.$persona->id.'" selected>'.$persona->nombre.'</option>';
						}else{
							$html_personal .= '<option value="'.$persona->id.'">'.$persona->nombre.'</option>';
						}
					}
				}
				
				/* select personal */
				$html_personal2 = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$personal = $this->post->get_personal_salon_op($visita->operadora,$visita->salon);
					foreach($personal->result() as $persona){
						if($persona->id == $visita->personal2 || $persona->nombre == $visita->personal2){
							$html_personal2 .= '<option value="'.$persona->id.'" selected>'.$persona->nombre.'</option>';
						}else{
							$html_personal2 .= '<option value="'.$persona->id.'">'.$persona->nombre.'</option>';
						}
					}
				}

				$fecha1 = explode(" ", $visita->fecha);
				$fecha2 = explode("-", $fecha1[0]);
				$fecha_buena = $fecha2[2]."/".$fecha2[1]."/".$fecha2[0]." ".$fecha1[1];
				
				$data = array('title' => '', 'visita' => $visita, 'visitas_checklist' => $visitas_checklist, 'html_op' => $html_op, 'html_salon' => $html_salon, 'html_personal' => $html_personal, 'html_personal2' => $html_personal2, 'fecha_buena' => $fecha_buena);
				$this->load_view('editar_visita', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_visita_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('personal1', 'Personal 1', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('personal2', 'Personal 2', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
	    	$this->editar_visita($this->input->post('id'));
	    }else{
	    	if($this->input->post('checklist_activado') == 1){
	    		if(empty($_POST['check_list'])){
			    	$checklist = false;
			    }else{
			    	$checklist = $_POST['check_list'];
			    }
	    	}else{
	    		$checklist = false;
	    	}

		    $texto = preg_replace( "/\r|\n/", "", $this->input->post('texto'));

		    $imagenes = explode(" ", $this->input->post('imagen_subida'));

	      	$resultado = $this->post->editar_visita($this->input->post('id'),$this->input->post('operador'),$this->input->post('salon'),$this->input->post('fecha'),$this->input->post('personal1'),$this->input->post('personal2'),$texto,$checklist,$this->input->post('taburete'),$this->input->post('mesa'),$this->input->post('tablero'),$imagenes);
	      	if($resultado){    
	      		$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Editar visita');
	      		$this->ver_visita($this->input->post('id'));
	      	}
	    }
	}

	/* Añadir nueva observacion */
	public function nueva_observacion_visita_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$data = array('title' => '');
				$this->form_validation->set_rules('texto', 'Texto', 'trim|htmlspecialchars|required');
				if ($this->form_validation->run() == FALSE){
			    	$this->ver_visita($this->input->post('id'));
			    }else{
			    	$texto = preg_replace( "/\r|\n/", "", $this->input->post('texto'));
			    	$texto = str_replace("&lt;", "<", $texto);
			    	$texto = str_replace("&gt;", ">", $texto);
			    	$texto = str_replace("&Aacute;", "Á", $texto);
			    	$texto = str_replace("&Eacute;", "É", $texto);
					$texto = str_replace("&Iacute;", "Í", $texto);
					$texto = str_replace("&Oacute;", "Ó", $texto);
					$texto = str_replace("&Uacute;", "Ú", $texto);
					$texto = str_replace("&aacute;", "á", $texto);
					$texto = str_replace("&eacute;", "é", $texto);
					$texto = str_replace("&iacute;", "í", $texto);
					$texto = str_replace("&oacute;", "ó", $texto);
					$texto = str_replace("&uacute;", "ú", $texto);
					$texto = str_replace("&Ntilde;", "Ñ", $texto);
					$texto = str_replace("&ntilde;", "ñ", $texto);
					$texto = str_replace("&amp;Aacute;", "Á", $texto);
					$texto = str_replace("&amp;Eacute;", "É", $texto);
					$texto = str_replace("&amp;Iacute;", "Í", $texto);
					$texto = str_replace("&amp;Oacute;", "Ó", $texto);
					$texto = str_replace("&amp;Uacute;", "Ú", $texto);
					$texto = str_replace("&amp;aacute;", "á", $texto);
					$texto = str_replace("&amp;eacute;", "é", $texto);
					$texto = str_replace("&amp;iacute;", "í", $texto);
					$texto = str_replace("&amp;oacute;", "ó", $texto);
					$texto = str_replace("&amp;uacute;", "ú", $texto);
					$texto = str_replace("&amp;Ntilde;", "Ñ", $texto);
					$texto = str_replace("&amp;ntilde;", "ñ", $texto);
					$texto = str_replace("&amp;nbsp;", " ", $texto);
					$texto = str_replace("&nbsp;", " ", $texto);
					$texto = str_replace("&quot;", '"', $texto);
					
			    	$resultado = $this->post->nueva_visita_comentario($this->input->post('id'),$texto);
			    	if($resultado){
			    		$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nuevo comentario visita');
			    		$this->ver_visita($this->input->post('id'));
			    	}
			    }
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Ver ficha personal */
	public function ver_visita($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				/* Get persona */
				$visita = $this->post->get_visita($id);
				$visitas_checklist = $this->post->get_visita_checklist($id);

				$fecha1 = explode(" ", $visita->fecha);
				$fecha2 = explode("-", $fecha1[0]);
				$fecha_buena = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];
				
				if($visita->operadora == 0){
					$op = "Desconocida";
				}else{
					$op = $this->post->get_operadoras_rol_2($visita->operadora);
					$operadora = $op->row();
					$op = $operadora->operadora;
				}
				
				if($visita->salon == 0){
					$salon = "Desconocido";
				}else{
					$salon = $this->post->get_salon($visita->salon);
				}

				if(isset($visita->personal1) && $visita->personal1 != 0){
					$personal1 = $this->post->get_persona($visita->personal1);
					$personal1 = $personal1->nombre;
				}else{
					$personal1 = '';
				}

				if(isset($visita->personal2) && $visita->personal2 != 0){
					$personal2 = $this->post->get_persona($visita->personal2);
					$personal2 = $personal2->nombre;
				}else{
					$personal2 = ''; 
				}

				$creador = $this->post->get_creador($visita->creador);

				$comentarios = $this->post->get_comentarios_visita($id);

				$ediciones = $this->post->get_ediciones_visita($id);
				
				$html_visita = '<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">
									<div class="panel-heading" style="background: #449d44; text-align: center">
										<p style="color: #fff">'.$fecha_buena.' - '.$op.' - '.$salon.'</p>
									</div>
									<div class="panel-body" style="padding: 10px">';
				
				if($visita->imagen != ''){
					$html_visita .= '<div class="col-md-12" style="text-align: center; margin-bottom: 4%">';

					/* Multiples imágenes */
					$imagenes = $this->post->get_imagenes_visita($id);
					if($imagenes->num_rows() > 0){
						$i = 0;
						$width = 100 / $imagenes->num_rows();
						foreach($imagenes->result() as $imagen){
							if($i == 0){
								$i++;
								continue;
							}
							$html_visita .= '<img id="imagen_error" src="../files/img/visitas/'.$imagen->imagen.'" alt="imagen" title="imagen" style="border: 1px solid #ddd; padding: 3px; border-radius: 5px; box-shadow: 1px 1px 5px; margin: 3px; width: '.$width.'%">';
							$i++;
						}

						$html_visita .= '<img id="imagen_error" src="../files/img/visitas/'.$visita->imagen.'" alt="imagen" title="imagen" style="border: 1px solid #ddd; padding: 3px; border-radius: 5px; box-shadow: 1px 1px 5px; margin: 3px; width: '.$width.'%">';

					}else{
						$html_visita .= '<img id="imagen_error" src="../files/img/visitas/'.$visita->imagen.'" alt="imagen" title="imagen" style="border: 1px solid #ddd; padding: 3px; border-radius: 5px; box-shadow: 1px 1px 5px;">';
					}
					$html_visita .= '</div>';
				}

				$html_visita .= '<div class="col-md-6">
									<p><span style="font-weight: bold">Personal 1:</span> '.$personal1.'</p>
									<p><span style="font-weight: bold">Personal 2:</span> '.$personal2.'</p>
								</div>
								<div class="col-md-6">
									<p><span style="font-weight: bold">Fecha:</span> '.$fecha_buena.'</p>
									<p><span style="font-weight: bold">Autor:</span> '.$creador.'</p>
								</div>
								<div class="col-md-12" style="margin-top: 10px;">
									<p style="font-weight: bold; text-align: center">Mobiliario</p>
								</div>
								<div class="col-md-4" style="height: 40px">
									<p><span style="font-weight: bold">Taburetes:</span> '.$visita->taburete.'</p>
								</div>
								<div class="col-md-4" style="height: 40px">
									<p><span style="font-weight: bold">Mesas:</span> '.$visita->mesa.'</p>
								</div>
								<div class="col-md-4" style="height: 40px">
									<p><span style="font-weight: bold">Tableros:</span> '.$visita->tablero.'</p>
								</div>
								<div class="col-md-12" style="margin-top: 10px;">
									<p style="font-weight: bold; text-align: center">Checklist obligatorio</p>
								</div>
								<div class="col-md-6">';

				if(isset($visitas_checklist)){
					if($visitas_checklist->car_obl1 == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Carteles exteriores obligatorios</p>';
					}
					if($visitas_checklist->car_obl2 == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Carteles interiores obligatorios</p>';
					}
					if($visitas_checklist->fol_lud == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Folletos juego responsable</p>';
					}
					if($visitas_checklist->tvs_cor == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">TVs emitiendo canales deportivos</p>';
					}					
					if($visitas_checklist->ver_act == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Vertical actualizada</p>';
					}					
					if($visitas_checklist->cor_est == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Córner en buen estado</p>';
					}					
					if($visitas_checklist->ter_inc == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Terminales en buen estado</p>';
					}					
					if($visitas_checklist->com_pro == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Comprobación de prohibidos activo</p>';
					}					
					if($visitas_checklist->per_for == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Personal de averias formado</p>';
					}					
					if($visitas_checklist->inc_apu == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Terminales averias sin incidencias</p>';
					}
					if($visitas_checklist->vin_est == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Vinilo buen estado</p>';
					}
					if($visitas_checklist->bol_pla == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Boletines / placa terminales</p>';
					}
					if($visitas_checklist->san_cab == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Saneamiento cableado Basic</p>';
					}
					if($visitas_checklist->dis_may == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Distintivo +18</p>';
					}
					if($visitas_checklist->tpv_inc == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">TPV sin incidencias</p>';
					}
					if($visitas_checklist->lec_tar == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Lector tarjetas TPV</p>';
					}
					if($visitas_checklist->señ_gal == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Señal Galgos</p>';
					}
					if($visitas_checklist->señ_lot == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Señal Lottos</p>';
					}
					if($visitas_checklist->señ_dep == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Señal Deportes</p>';
					}
					if($visitas_checklist->señ_otr == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #449d44">Otras señales</p>';
					}
				}

				$html_visita .= '</div>
								<div class="col-md-6">';

				if(isset($visitas_checklist)){
					if($visitas_checklist->car_obl1 == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Carteles exteriores obligatorios</p>';
					}
					if($visitas_checklist->car_obl2 == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Carteles interiores obligatorios</p>';
					}
					if($visitas_checklist->fol_lud == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Folletos juego responsable</p>';
					}
					if($visitas_checklist->tvs_cor == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">TVs emitiendo canales deportivos</p>';
					}					
					if($visitas_checklist->ver_act == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Vertical actualizada</p>';
					}					
					if($visitas_checklist->cor_est == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Córner en buen estado</p>';
					}					
					if($visitas_checklist->ter_inc == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Terminales en buen estado</p>';
					}					
					if($visitas_checklist->com_pro == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Comprobación de prohibidos activo</p>';
					}					
					if($visitas_checklist->per_for == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Personal de averias formado</p>';
					}					
					if($visitas_checklist->inc_apu == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Terminales averias sin incidencias</p>';
					}
					if($visitas_checklist->vin_est == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Vinilo buen estado</p>';
					}
					if($visitas_checklist->bol_pla == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Boletines / placa terminales</p>';
					}
					if($visitas_checklist->san_cab == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Saneamiento cableado Basic</p>';
					}
					if($visitas_checklist->dis_may == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Distintivo +18</p>';
					}
					if($visitas_checklist->tpv_inc == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">TPV sin incidencias</p>';
					}
					if($visitas_checklist->lec_tar == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Lector tarjetas TPV</p>';
					}
					if($visitas_checklist->señ_gal == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Señal Galgos</p>';
					}
					if($visitas_checklist->señ_lot == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Señal Lottos</p>';
					}
					if($visitas_checklist->señ_dep == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Señal Deportes</p>';
					}
					if($visitas_checklist->señ_otr == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #d80039">Otras señales</p>';
					}
				}

				$html_visita .= '</div>';

				$html_visita .= '<div class="col-md-12" style="margin-top: 10px;">
									<p style="font-weight: bold; text-align: center">Checklist opcional</p>
								</div>
								<div class="col-md-6">';

				if(isset($visitas_checklist)){
					if($visitas_checklist->fac_vin == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Referenciar ADM en fachada</p>';
					}					
					if($visitas_checklist->piz_int == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Adquisición y uso de pizarra</p>';
					}
					if($visitas_checklist->per_uni == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Personal uniformado</p>';
					}				
					if($visitas_checklist->pan_car == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Adquisición y uso de panel de cartelería</p>';
					}					
					if($visitas_checklist->car_pro == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Imprimir cartelera de programación deportiva</p>';
					}					
					if($visitas_checklist->tar_adm == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Disponer de tarjetas ADM</p>';
					}					
					if($visitas_checklist->lim_loc == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Revisar limpieza local</p>';
					}
					if($visitas_checklist->ban_fac == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Banderola fachada</p>';
					}
					if($visitas_checklist->aio_est == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">AIO buen estado</p>';
					}
					if($visitas_checklist->ent_mer == 1){
						$html_visita .= '<p><span style="font-weight: bold; color: #007bff">Entrega merchand</p>';
					}
				}

				$html_visita .= '</div>
								<div class="col-md-6">';

				if(isset($visitas_checklist)){
					if($visitas_checklist->fac_vin == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Referenciar ADM en fachada</p>';
					}					
					if($visitas_checklist->piz_int == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Adquisición y uso de pizarra</p>';
					}
					if($visitas_checklist->per_uni == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Personal uniformado</p>';
					}					
					if($visitas_checklist->pan_car == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Aquisición y uso de panel de cartelería</p>';
					}					
					if($visitas_checklist->car_pro == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Imprimir cartelera de programación deportiva</p>';
					}					
					if($visitas_checklist->tar_adm == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Disponer de tarjetas ADM</p>';
					}					
					if($visitas_checklist->lim_loc == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Revisar limpieza local</p>';
					}
					if($visitas_checklist->ban_fac == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Banderola fachada</p>';
					}
					if($visitas_checklist->aio_est == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">AIO buen estado</p>';
					}
					if($visitas_checklist->ent_mer == 0){
						$html_visita .= '<p><span style="font-weight: bold; color: #ff9900">Entrega merchand</p>';
					}
				}

				$html_visita .= '</div>
								</div>
								<div class="panel-heading" style="background: #b21a30; text-align: center; margin-top: 20px">
										<p style="color: #fff">Historial</p>
									</div>
									<div class="panel-body" style="padding: 10px">';

				if($comentarios->num_rows() != 0){

					foreach($comentarios->result() as $comentario){

						$creador2 = $this->post->get_creador($comentario->creador);

						if(isset($comentario->fecha) && $comentario->fecha != ''){
							$fecha2 = explode("-", $comentario->fecha);
							$fecha2 = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0];
						}else{
							$fecha2 = ''; 
						}

						$html_visita .= '<p><span style="font-weight: bold">'.$fecha2.' - '.$creador2.' : Comentario añadido</p>';

					}
				}

				if($ediciones->num_rows() != 0){

					foreach($ediciones->result() as $edicion){

						$creador3 = $this->post->get_creador($edicion->creador);

						if(isset($edicion->fecha) && $edicion->fecha != ''){
							$fecha3 = explode("-", $edicion->fecha);
							$fecha3 = $fecha3[2]."-".$fecha3[1]."-".$fecha3[0];
						}else{
							$fecha3 = ''; 
						}

						$html_visita .= '<p><span style="font-weight: bold">'.$fecha3.' - '.$creador3.' : Visita editada</p>';

					}
				}

				$html_visita .= '<p><span style="font-weight: bold">'.$fecha_buena.' - '.$creador.' : Alta visita</p>';

				$html_visita .= '</div>
								</div>								
								<div class="col-md-1 col-sm-12">
								</div>
								<div class="col-md-5 col-sm-12" style="padding: 0">
									<div style="background: #eb9316; text-align: center; padding: 10px 15px">
										<p style="font-weight: bold; color: #fff">Observaciones</p>
									</div>';

				if($comentarios->num_rows() != 0){

					foreach($comentarios->result() as $comentario){

						$creador2 = $this->post->get_creador($comentario->creador);

						if(isset($comentario->fecha) && $comentario->fecha != ''){
							$fecha2 = explode("-", $comentario->fecha);
							$fecha2 = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0];
						}else{
							$fecha2 = ''; 
						}

						$obs = $this->utf8_decode($comentario->comentario);

						$html_visita .= '<div style="padding: 10px; border: 1px solid #ddd; margin-top: 20px">
												<p><span style="font-weight: bold">Autor:</span> '.$creador2.'</p>
												<p><span style="font-weight: bold">Fecha:</span> '.$fecha2.'</p>														
												'.htmlspecialchars_decode($obs).'
												<p style="text-align: right"><a href="'.base_url('borrar_comentario_visita?visita='.$id.'&comentario='.$comentario->id.'').'" style="font-weight: bold; color: red">Borrar</a></p>
											</div>';

					}
				}

				$obs = $this->utf8_decode($visita->observaciones);

				$html_visita .= '<div style="padding: 10px; border: 1px solid #ddd; margin-top: 20px">
										<p><span style="font-weight: bold">Autor:</span> '.$creador.'</p>
										<p><span style="font-weight: bold">Fecha:</span> '.$visita->fecha.'</p>														
										'.htmlspecialchars_decode($obs).'
									</div>
								</div>';
				
				$data = array('title' => '', 'html_visita' => $html_visita, 'id' => $id);
				$this->load_view('ver_visita', $data);				
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function borrar_comentario_visita(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$borrar = $this->post->borrar_comentario_visita($this->input->get("comentario"));
				$this->ver_visita($this->input->get("visita"));
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/*Seccion promo azafatas */
	public function promo_azafatas(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$salones = $this->post->get_salones_promo_azafatas();
				$html_salones = '{ Name : "TODOS", Id : "" },';
				foreach($salones->result() as $salon){
					$html_salones .= '{ Name : "'.$salon->salon.'", Id : "'.$salon->salon.'" },';
				}

				$html_promos = '';
				$promos = $this->post->get_promos_azafatas();
				$id = 0;			
				foreach($promos->result() as $promo){
					$id++;				
					$html_promos .= '{ "id": '.$id.',';

					$html_promos .= '"Salon": "'.$promo->salon.'",';

					$html_promos .= '"Nombre": "'.$promo->nombre.'",';

					$html_promos .= '"Email": "'.$promo->email.'",';

					$html_promos .= '"DNI": "'.$promo->dni.'",';

					$html_promos .= '"Telefono": "'.$promo->telefono.'",';

					$fecha = date('Y-m-d H:i:s', strtotime($promo->fecha. ' + 1 hour'));
					$fecha1 = explode(" ", $fecha);
					$fecha2 = explode("-", $fecha1[0]);
					$fecha = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];

					$html_promos .= '"Fecha": "'.$fecha.'",';

					$html_promos .= '"Ticket": "'.$promo->ticket.'",';

					$html_promos .= '},';
				}

				$data = array('title' => '', 'html_promos' => $html_promos, 'html_salones' => $html_salones);
				$this->load_view('promo_azafatas', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Crear carnet ADM */
	public function carnet_adm($id){
		if($this->session->userdata('logged_in')){
			$generar_carnet = 1;
			$this->load->library('pdf');
			$carnet = $this->pdf->return_pdf_carnet_adm($generar_carnet, $id);
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function promo_azafatas_filter_data(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'GET'){
			if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
				$promos = $this->post->get_promos_azafatas_salon($_GET['Salon']);
			}else{
				$promos = $this->post->get_promos_azafatas();
			}
			$id = 0;
			if($promos->num_rows() > 0){
				foreach($promos->result() as $promo){
					$id++;
					$salon = $promo->salon;
					$nombre = $promo->nombre;
					$email = $promo->email;
					$dni = $promo->dni;
					$telefono = $promo->telefono;

					$fecha = date('Y-m-d H:i:s', strtotime($promo->fecha. ' + 1 hour'));
					$fecha1 = explode(" ", $fecha);
					$fecha2 = explode("-", $fecha1[0]);
					$fecha = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];

					$ticket = $promo->ticket;

					$output[] = array(
						'id' => $id,
						'Salon' => $salon,
						'Nombre' => $nombre,
						'Email' => $email,
						'DNI' => $dni,
						'Telefono' => $telefono,
						'Fecha' => $fecha,
						'Ticket' => $ticket
					);
				}
			}else{
				$output = array();
			}
			echo json_encode($output);
		}
	}

	/* Seccion Personal - Comerciales */
	public function personal($duplicado=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6 && $this->session->userdata('logged_in')['rol'] != 1){
				$this->gestion();
			}else{
				$operadoras = $this->post->get_operadoras_com();
				$html_op = '{ Name : "TODAS", Id : "" },';
				foreach($operadoras->result() as $op){
					$html_op .= '{ Name : "'.$op->operadora.'", Id : "'.$op->id.'" },';
				}

				$salones = $this->post->get_salones_averias_com();
				$html_salones = '{ Name : "TODOS", Id : "" },';
				foreach($salones->result() as $salon){
					$html_salones .= '{ Name : "'.$salon->salon.'", Id : "'.$salon->id.'" },';
				}

				$supervisoras = $this->post->get_supervisoras();
				$html_supervisoras = '{ Name : "TODAS", Id : "" },';
				foreach($supervisoras->result() as $supervisora){
					$html_supervisoras .= '{ Name: "'.$supervisora->nombre.'", Id: "'.$supervisora->id.'" },';
				}

				$html_personal = '';
				$personal = $this->post->get_personal();
				foreach($personal->result() as $persona){

					$html_personal .= '{ "id": '.$persona->id.',';

					if($persona->operadora == 0){
						$html_personal .= '"Operadora": "0",';
					}else{
						$html_personal .= '"Operadora": "'.$persona->operadora.'",';
					}

					if($persona->salon == 0){
						$html_personal .= '"Salon": "0",';
					}else{
						$html_personal .= '"Salon": "'.$persona->salon.'",';
					}

					$html_personal .= '"Nombre": "'.$persona->nombre.'",';
					$html_personal .= '"DNI": "'.$persona->dni.'",';

					if(!isset($persona->telefono) || empty($persona->telefono) || $persona->telefono == ''){
						$html_personal .= '"Telefono": 0,';
					}else{
						$html_personal .= '"Telefono": '.$persona->telefono.',';
					}

					if(!isset($persona->registro) || empty($persona->registro) || $persona->registro == ''){
						$html_personal .= '"Registro": "",';
					}else{
						$html_personal .= '"Registro": "'.$persona->registro.'",';
					}

					if(!isset($persona->curso) || empty($persona->curso) || $persona->curso == ''){
						$html_personal .= '"Curso": "0",';
					}else{
						$html_personal .= '"Curso": "'.$persona->curso.'",';
					}
					
					if(!isset($persona->carnet) || empty($persona->carnet) || $persona->carnet == ''){
						$html_personal .= '"Carnet": "0",';
					}else{
						$html_personal .= '"Carnet": "'.$persona->carnet.'",';
					}

					if(!isset($persona->nota) || empty($persona->nota) || $persona->nota == ''){
						$nota = '';
						$html_personal .= '"Nota": "",';
					}else{
						$html_personal .= '"Nota": "'.$persona->nota.'",';
					}

					if(!isset($persona->fecha_formacion) || empty($persona->fecha_formacion) || $persona->fecha_formacion == ''){
						$fecha_formacion = '';
						$html_personal .= '"FechaForm": "",';
					}else{
						$fecha = explode("-", $persona->fecha_formacion);
						$fecha_formacion = $fecha[2]."-".$fecha[1]."-".$fecha[0];
						$html_personal .= '"FechaForm": "'.$fecha_formacion.'",';
					}

					if(!isset($persona->observaciones) || empty($persona->observaciones) || $persona->observaciones == ''){
						$html_personal .= '"Observaciones": "",';
					}else{
						$observaciones = substr($persona->observaciones, 0, 30);
						$observaciones = stripslashes($observaciones);
						$observaciones = htmlspecialchars_decode($observaciones);
						$html_personal .= "'Observaciones': '".$observaciones."',";
					}
					
					if(!isset($persona->test) || empty($persona->test) || $persona->test == ''){
						$html_personal .= '"Test": "0",';
					}else{
						$html_personal .= '"Test": "'.$persona->test.'",';
					}
					
					if(!isset($persona->activo) || empty($persona->activo) || $persona->activo == ''){
						$html_personal .= '"Activo": "0",';
					}else{
						$html_personal .= '"Activo": "'.$persona->activo.'",';
					}

					if(isset($persona->imagen) && $persona->imagen !=""){
						$html_personal .= '"Imagen": "1",';
					}else{
						$html_personal .= '"Imagen": "0",';
					}

					$html_personal .= '"Supervisora": "'.$persona->creador.'",';

					$html_personal .= '},';
				}

				if(isset($duplicado)){
					$html_duplicado = '<div class="alert alert-warning" role="alert">
  									No se ha podido crear el usuario: Ya existe un usuario con ese DNI, el usuario con id '.$duplicado->id.'. Para evitar duplicados, por favor revise el usuario ya existente.
									</div>';
				}else{
					$html_duplicado = '';
				}												

				$data = array('title' => '', 'html_op' => $html_op, 'html_salones' => $html_salones, 'personal' => $html_personal, 'html_duplicado' => $html_duplicado, 'html_supervisoras' => $html_supervisoras);
				$this->load_view('personal', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function personal_filter_data(){
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'GET'){
			if(isset($_GET['Registro']) && $_GET['Registro'] != ''){
				if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
					$personal = $this->post->get_personal_registro_salon($_GET['Registro'],$_GET['Salon']);
				}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
					$personal = $this->post->get_personal_registro_op($_GET['Registro'],$_GET['Operadora']);
				}else{
					$personal = $this->post->get_personal_registro($_GET['Registro']);
				}
			}else if(isset($_GET['Observaciones']) && $_GET['Observaciones'] != ''){
				if(isset($_GET['Telefono']) && $_GET['Telefono'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_ob_activo_curso_test($_GET['Telefono'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_ob_activo_curso($_GET['Telefono'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_ob_activo_test($_GET['Telefono'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_ob_activo($_GET['Telefono'],$_GET['Observaciones'],$_GET['Activo']);
							}							
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_ob_curso_test($_GET['Telefono'],$_GET['Observaciones'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_ob_curso($_GET['Telefono'],$_GET['Observaciones'],$_GET['Curso']);
							}
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_ob_test($_GET['Telefono'],$_GET['Observaciones'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_ob($_GET['Telefono'],$_GET['Observaciones']);
							}								
						}
					}
				}else if(isset($_GET['DNI']) && $_GET['DNI'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_ob_activo_curso_test($_GET['DNI'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_ob_activo_curso($_GET['DNI'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_ob_activo_test($_GET['DNI'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_ob_activo($_GET['DNI'],$_GET['Observaciones'],$_GET['Activo']);
							}							
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_ob_curso_test($_GET['DNI'],$_GET['Observaciones'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_ob_curso($_GET['DNI'],$_GET['Observaciones'],$_GET['Curso']);
							}
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_ob_test($_GET['DNI'],$_GET['Observaciones'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_ob($_GET['DNI'],$_GET['Observaciones']);
							}								
						}
					}
				}else if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_ob_activo_curso_test($_GET['Salon'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_ob_activo_curso($_GET['Salon'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_ob_activo_test($_GET['Salon'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_ob_activo($_GET['Salon'],$_GET['Observaciones'],$_GET['Activo']);
							}							
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_ob_curso_test($_GET['Salon'],$_GET['Observaciones'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_ob_curso($_GET['Salon'],$_GET['Observaciones'],$_GET['Curso']);
							}
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_ob_test($_GET['Salon'],$_GET['Observaciones'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_ob($_GET['Salon'],$_GET['Observaciones']);
							}								
						}
					}
				}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_ob_activo_curso_test($_GET['Operadora'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_ob_activo_curso($_GET['Operadora'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_ob_activo_test($_GET['Operadora'],$_GET['Observaciones'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_ob_activo($_GET['Operadora'],$_GET['Observaciones'],$_GET['Activo']);
							}								
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_ob_curso_test($_GET['Operadora'],$_GET['Observaciones'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_ob_curso($_GET['Operadora'],$_GET['Observaciones'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_ob_test($_GET['Operadora'],$_GET['Observaciones'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_ob($_GET['Operadora'],$_GET['Observaciones']);
							}								
						}
					}
				}else{
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->filtrar_personal_activo_curso_test_ob($_GET['Observaciones'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->filtrar_personal_activo_curso_ob($_GET['Observaciones'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->filtrar_personal_activo_test_ob($_GET['Observaciones'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->filtrar_personal_activo_ob($_GET['Observaciones'],$_GET['Activo']);
							}								
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->filtrar_personal_curso_test_ob($_GET['Observaciones'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->filtrar_personal_curso_ob($_GET['Observaciones'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->filtrar_personal_test_ob($_GET['Observaciones'],$_GET['Test']);
							}else{
								$personal = $this->post->filtrar_personal_ob($_GET['Observaciones']);
							}							
						}
					}
				}
			}else if(isset($_GET['Nombre']) && $_GET['Nombre'] != ''){
				if(isset($_GET['Telefono']) && $_GET['Telefono'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_nombre_activo_curso_test($_GET['Telefono'],$_GET['Nombre'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_nombre_activo_curso($_GET['Telefono'],$_GET['Nombre'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_nombre_activo_test($_GET['Telefono'],$_GET['Nombre'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_nombre_activo($_GET['Telefono'],$_GET['Nombre'],$_GET['Activo']);
							}							
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_nombre_curso_test($_GET['Telefono'],$_GET['Nombre'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_nombre_curso($_GET['Telefono'],$_GET['Nombre'],$_GET['Curso']);
							}
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_nombre_test($_GET['Telefono'],$_GET['Nombre'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_nombre($_GET['Telefono'],$_GET['Nombre']);
							}								
						}
					}
				}else if(isset($_GET['DNI']) && $_GET['DNI'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_nombre_activo_curso_test($_GET['DNI'],$_GET['Nombre'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_nombre_activo_curso($_GET['DNI'],$_GET['Nombre'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_nombre_activo_test($_GET['DNI'],$_GET['Nombre'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_nombre_activo($_GET['DNI'],$_GET['Nombre'],$_GET['Activo']);
							}							
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_nombre_curso_test($_GET['DNI'],$_GET['Nombre'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_nombre_curso($_GET['DNI'],$_GET['Nombre'],$_GET['Curso']);
							}
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_nombre_test($_GET['DNI'],$_GET['Nombre'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_nombre($_GET['DNI'],$_GET['Nombre']);
							}								
						}
					}
				}else if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_nombre_activo_curso_test($_GET['Salon'],$_GET['Nombre'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_nombre_activo_curso($_GET['Salon'],$_GET['Nombre'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_nombre_activo_test($_GET['Salon'],$_GET['Nombre'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_nombre_activo($_GET['Salon'],$_GET['Nombre'],$_GET['Activo']);
							}							
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_nombre_curso_test($_GET['Salon'],$_GET['Nombre'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_nombre_curso($_GET['Salon'],$_GET['Nombre'],$_GET['Curso']);
							}
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_nombre_test($_GET['Salon'],$_GET['Nombre'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_nombre($_GET['Salon'],$_GET['Nombre']);
							}								
						}
					}
				}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_nombre_activo_curso_test($_GET['Operadora'],$_GET['Nombre'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_nombre_activo_curso($_GET['Operadora'],$_GET['Nombre'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_nombre_activo_test($_GET['Operadora'],$_GET['Nombre'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_nombre_activo($_GET['Operadora'],$_GET['Nombre'],$_GET['Activo']);
							}								
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_nombre_curso_test($_GET['Operadora'],$_GET['Nombre'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_nombre_curso($_GET['Operadora'],$_GET['Nombre'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_nombre_test($_GET['Operadora'],$_GET['Nombre'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_nombre($_GET['Operadora'],$_GET['Nombre']);
							}								
						}
					}
				}else{
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->filtrar_personal_activo_curso_test($_GET['Nombre'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->filtrar_personal_activo_curso($_GET['Nombre'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->filtrar_personal_activo_test($_GET['Nombre'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->filtrar_personal_activo($_GET['Nombre'],$_GET['Activo']);
							}								
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->filtrar_personal_curso_test($_GET['Nombre'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->filtrar_personal_curso($_GET['Nombre'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->filtrar_personal_test($_GET['Nombre'],$_GET['Test']);
							}else{
								$personal = $this->post->filtrar_personal($_GET['Nombre']);
							}							
						}
					}
				}
			}else{
				if(isset($_GET['Telefono']) && $_GET['Telefono'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_activo_curso_test($_GET['Telefono'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_activo_curso($_GET['Telefono'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_activo_test($_GET['Telefono'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_activo($_GET['Telefono'],$_GET['Activo']);
							}							
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_curso_test($_GET['Telefono'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono_curso($_GET['Telefono'],$_GET['Curso']);
							}
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_telefono_test($_GET['Telefono'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_telefono($_GET['Telefono']);
							}								
						}
					}
				}else if(isset($_GET['DNI']) && $_GET['DNI'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_activo_curso_test($_GET['DNI'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_activo_curso($_GET['DNI'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_activo_test($_GET['DNI'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_activo($_GET['DNI'],$_GET['Activo']);
							}							
						}
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_curso_test($_GET['DNI'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni_curso($_GET['DNI'],$_GET['Curso']);
							}
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_dni_test($_GET['DNI'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_dni($_GET['DNI']);
							}								
						}
					}
				}else if(isset($_GET['Supervisora']) && $_GET['Supervisora'] != ''){
					if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
						$personal = $this->post->get_personal_supervisora_salon($_GET['Supervisora'],$_GET['Salon']);
					}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
						$personal = $this->post->get_personal_supervisora_op($_GET['Supervisora'],$_GET['Operadora']);
					}else{
						$personal = $this->post->get_personal_supervisora($_GET['Supervisora']);
					}
				}else if(isset($_GET['Salon']) && $_GET['Salon'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_activo_curso_test($_GET['Salon'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_activo_curso($_GET['Salon'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_activo_test($_GET['Salon'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_activo($_GET['Salon'],$_GET['Activo']);
							}							
						}						
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_curso_test($_GET['Salon'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon_curso($_GET['Salon'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_salon_test($_GET['Salon'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_salon($_GET['Salon']);
							}							
						}						
					}					
				}else if(isset($_GET['Operadora']) && $_GET['Operadora'] != ''){
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_activo_curso_test($_GET['Operadora'],$_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_activo_curso($_GET['Operadora'],$_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_activo_test($_GET['Operadora'],$_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_activo($_GET['Operadora'],$_GET['Activo']);
							}							
						}						
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_curso_test($_GET['Operadora'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op_curso($_GET['Operadora'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_op_test($_GET['Operadora'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_op($_GET['Operadora']);
							}							
						}						
					}
				}else{
					if(isset($_GET['Activo']) && $_GET['Activo'] != ''){
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_activo_curso_test($_GET['Activo'],$_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_activo_curso($_GET['Activo'],$_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_activo_test($_GET['Activo'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_activo($_GET['Activo']);
							}							
						}						
					}else{
						if(isset($_GET['Curso']) && $_GET['Curso'] != ''){
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_curso_test($_GET['Curso'],$_GET['Test']);
							}else{
								$personal = $this->post->get_personal_curso($_GET['Curso']);
							}							
						}else{
							if(isset($_GET['Test']) && $_GET['Test'] != ''){
								$personal = $this->post->get_personal_test($_GET['Test']);
							}else{
								$personal = $this->post->get_personal();
							}							
						}						
					}
				}
			}
			if($personal->num_rows() > 0){
				foreach($personal->result() as $persona){
					if($persona->operadora == 0){
						$op = 0;
					}else{
						$op = $this->post->get_operadoras_rol_2($persona->operadora);
						$operadora = $op->row();
						$op = $operadora->id;
					}

					if($persona->salon == 0){
						$salon = '0';
					}else{
						$salon = $this->post->get_salon_completo($persona->salon);
						$salon = $salon->id;
					}

					if(!isset($persona->telefono) || empty($persona->telefono) || $persona->telefono == ''){
						$telefono = 0;
					}else{
						$telefono = $persona->telefono;
					}

					if(!isset($persona->registro) || empty($persona->registro) || $persona->registro == ''){
						$registro = '';
					}else{
						$registro = $persona->registro;
					}

					if(!isset($persona->curso) || empty($persona->curso) || $persona->curso == ''){
						$curso = '0';
					}else{
						$curso = $persona->curso;
					}
					
					if(!isset($persona->carnet) || empty($persona->carnet) || $persona->carnet == ''){
						$carnet = '0';
					}else{
						$carnet = $persona->carnet;
					}

					if(!isset($persona->nota) || empty($persona->nota) || $persona->nota == ''){
						$nota = '';
					}else{
						$nota = $persona->nota;
					}

					if(!isset($persona->fecha_formacion) || empty($persona->fecha_formacion) || $persona->fecha_formacion == ''){
						$fecha_formacion = '';
					}else{
						$fecha = explode("-", $persona->fecha_formacion);
						$fecha_formacion = $fecha[2]."-".$fecha[1]."-".$fecha[0];
					}
					
					if(!isset($persona->test) || empty($persona->test) || $persona->test == ''){
						$test = '0';
					}else{
						$test = $persona->test;
					}
					
					if(!isset($persona->activo) || empty($persona->activo) || $persona->activo == ''){
						$activo = '0';
					}else{
						$activo = $persona->activo;
					}

					$observaciones = substr($persona->observaciones, 0, 30);
					$observaciones = stripslashes($observaciones);
					$observaciones = htmlspecialchars_decode($observaciones);

					if(isset($persona->imagen) && $persona->imagen !=""){
						$imagen = '1';
					}else{
						$imagen = '0';
					}

					if(!isset($persona->creador) || empty($persona->creador) || $persona->creador == ''){
						$creador = '0';
					}else{
						$creador = $persona->creador;
					}

					$output[] = array(
						'id' => $persona->id,
						'Operadora' => $op,
						'Salon' => $salon,
						'Nombre' => $persona->nombre,
						'DNI' => $persona->dni,
						'Telefono' => $telefono,
						'Registro' => $registro,
						'Curso' => $curso,
						'Carnet' => $carnet,
						'Nota' => $nota,
						'FechaForm' => $fecha_formacion,
						'Observaciones' => $observaciones,
						'Test' => $test,
						'Activo' => $activo,
						'Imagen' => $imagen,
						'Supervisora' => $creador
					);
				}
			}else{
				$output = array();
			}
			echo json_encode($output);
		}

		if($method == 'PUT'){
			parse_str(file_get_contents("php://input"), $_PUT);
			$this->post->actualizar_personal($_PUT);
		}

		if($method == 'DELETE'){
			parse_str(file_get_contents("php://input"), $_DELETE);
		 	$this->post->eliminar_personal($_DELETE['id']);
		}
	}

	public function get_persona_image(){
		$data = json_decode($_POST['data']);
		$persona = $this->post->get_persona($data[0]);
		if(isset($persona->imagen) && $persona->imagen !=""){
			$imagen = $persona->imagen;
		}else{
			$imagen = "default.jpg";
		}
		echo $imagen;
	}

	/* Nuevo personal */
	public function nuevo_personal(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{				
				/* Filtro operadoras */
				$html_op = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$operadoras = $this->post->get_operadoras_com();
					foreach($operadoras->result() as $operadora){
						$html_op .= '<option value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
					}
				}
				
				/* Filtro salones */
				$html_salon = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$salones = $this->post->get_salones();
					foreach($salones->result() as $salon){
						$html_salon .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}
				
				$data = array('title' => '', 'html_op' => $html_op, 'html_salon' => $html_salon);
				$this->load_view('nuevo_personal', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function nuevo_personal_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|htmlspecialchars|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('dni', 'DNI', 'trim|htmlspecialchars|required');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
	    	$this->nuevo_personal();
	    }else{
	    	if($this->input->post('curso') == 'on'){
				$curso = 1;
			}else{
				$curso = 0;
			}
			if($this->input->post('carnet') == 'on'){
				$carnet = 1;
			}else{
				$carnet = 0;
			}
			if($this->input->post('test') == 'on'){
				$test = 1;
			}else{
				$test = 0;
			}
			if($this->input->post('activo') == 'on'){
				$activo = 1;
			}else{
				$activo = 0;
			}

			$texto = preg_replace( "/\r|\n/", "", $this->input->post('texto'));

	      	$resultado = $this->post->crear_personal($this->input->post('operador'),$this->input->post('salon'),$this->input->post('nombre'),$this->input->post('dni'),$this->input->post('telefono'),$this->input->post('email'),$curso,$carnet,$test,$activo,$this->input->post('fecha_carnet'),$this->input->post('fecha_formacion'),$this->input->post('nota'),$this->input->post('reg'),$texto,$this->input->post('imagen_subida'));
	      	if($resultado){
	      		if(gettype($resultado) == 'object'){
	      			$this->personal($resultado);
	      		}else if(gettype($resultado) == 'boolean'){
	      			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Crear Personal');
	      			$this->personal();
	      		}
	      	}
	    }
	}
	
	/* Editar personal */
	public function editar_personal($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$persona = $this->post->get_persona($id);
				/* Filtro operadoras */
				$html_op = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$operadoras = $this->post->get_operadoras_com();
					foreach($operadoras->result() as $operadora){
						if($operadora->id == $persona->operadora){
							$html_op .= '<option value="'.$operadora->id.'" selected>'.$operadora->operadora.'</option>';
						}else{
							$html_op .= '<option value="'.$operadora->id.'">'.$operadora->operadora.'</option>';
						}
					}
				}
				
				/* Filtro salones */
				$html_salon = '';
				if($this->session->userdata('logged_in')['rol'] == 6){
					$salones = $this->post->get_salones();
					foreach($salones->result() as $salon){
						if($salon->id == $persona->salon){
							$html_salon .= '<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salon .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}
				}
				
				$data = array('title' => '', 'persona' => $persona, 'html_op' => $html_op, 'html_salon' => $html_salon);
				$this->load_view('editar_personal', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_personal_form(){
		$data = array('title' => '');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|htmlspecialchars|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('dni', 'DNI', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|htmlspecialchars');
		if ($this->form_validation->run() == FALSE){
	    	$this->editar_personal($this->input->post('id'));
	    }else{
	    	if($this->input->post('curso') == 'on'){
				$curso = 1;
			}else{
				$curso = 0;
			}
			if($this->input->post('carnet') == 'on'){
				$carnet = 1;
			}else{
				$carnet = 0;
			}
			if($this->input->post('test') == 'on'){
				$test = 1;
			}else{
				$test = 0;
			}
			if($this->input->post('activo') == 'on'){
				$activo = 1;
			}else{
				$activo = 0;
			}
			$texto = preg_replace( "/\r|\n/", "", $this->input->post('texto'));
	      	$resultado = $this->post->editar_personal($this->input->post('id'),$this->input->post('operador'),$this->input->post('salon'),$this->input->post('nombre'),$this->input->post('dni'),$this->input->post('telefono'),$this->input->post('email'),$curso,$carnet,$test,$activo,$this->input->post('fecha_carnet'),$this->input->post('fecha_formacion'),$this->input->post('nota'),$this->input->post('reg'),$texto,$this->input->post('imagen_subida'));
	      	if($resultado){    
		      	if(gettype($resultado) == 'object'){
	      			$this->personal($resultado);
	      		}else if(gettype($resultado) == 'boolean'){
	      			$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Editar Personal');
	      			$this->ver_personal($this->input->post('id'));
	      		}
	        }
	    }
	}

	/* Añadir nueva observacion/personal */
	public function nueva_observacion_personal_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$data = array('title' => '');
				$this->form_validation->set_rules('texto', 'Texto', 'trim|htmlspecialchars|required');
				if ($this->form_validation->run() == FALSE){
			    	$this->ver_personal($this->input->post('id'));
			    }else{
			    	$resultado = $this->post->nuevo_personal_comentario($this->input->post('id'),$this->input->post('texto'));
			    	if($resultado){
			    		$this->post->guardar_historial($this->session->userdata('logged_in')['id'],'Nuevo comentario personal');
			    		$this->ver_personal($this->input->post('id'));
			    	}
			    }
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Ver ficha personal */
	public function ver_personal($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				/* Get persona */
				$persona = $this->post->get_persona($id);
				
				if($persona->operadora == 0){
					$op = "Desconocida";
				}else{
					$op = $this->post->get_operadoras_rol_2($persona->operadora);
					$operadora = $op->row();
					$op = $operadora->operadora;
				}
				
				if($persona->salon == 0){
					$salon = "Desconocido/Rotativos";
				}else{
					$salon = $this->post->get_salon($persona->salon);
				}
				
				if($persona->curso == 1){
					$curso = "Si";
				}else{
					$curso = "No";
				}
				
				if($persona->carnet == 1){
					$carnet = "Si";
				}else{
					$carnet = "No";
				}
				
				if($persona->test == 1){
					$test = "Si";
				}else{
					$test = "No";
				}
				
				if($persona->activo == 1){
					$activo = "Si";
				}else{
					$activo = "No";
				}

				if(isset($persona->fecha_carnet) && $persona->fecha_carnet != ''){
					$fecha = explode("-", $persona->fecha_carnet);
					$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
				}else{
					$fecha = ''; 
				}

				if(isset($persona->fecha_formacion) && $persona->fecha_formacion != ''){
					$form = explode("-", $persona->fecha_formacion);
					$form = $form[2]."-".$form[1]."-".$form[0];
				}else{
					$form = ''; 
				}

				if(isset($persona->fecha_alta) && $persona->fecha_alta != ''){
					$alta = explode("-", $persona->fecha_alta);
					$alta = $alta[2]."-".$alta[1]."-".$alta[0];
				}else{
					$alta = ''; 
				}

				$creador = $this->post->get_creador($persona->creador);

				$cambios = $this->post->get_cambios_salon_personal($id);

				$activos = $this->post->get_cambios_activo_personal($id);

				$comentarios = $this->post->get_comentarios_personal($id);
				
				$html_personal = '<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">
									<div class="panel-heading" style="background: #449d44; text-align: center">
										<p style="color: #fff">'.$persona->nombre.'</p>
									</div>
									<div class="panel-body" style="padding: 10px">';

				if(isset($persona->imagen) && $persona->imagen != ''){
				$html_personal .= '<div class="col-md-12">
										<div style="margin: 0 auto; width: 200px;">
											<img src="'.base_url("files/img/personal/".$persona->imagen."").'" style="width: 100%; border: 1px solid #ccc; border-radius: 5px; padding: 2px; margin: 0 0 5%;">
										</div>
									</div>';
				}
				
				$html_personal .= '<div class="col-md-6">
										<p><span style="font-weight: bold">Operadora:</span> '.$op.'</p>
										<p><span style="font-weight: bold">Salón:</span> '.$salon.'</p>
										<p><span style="font-weight: bold">DNI:</span> '.$persona->dni.'</p>									
										<p><span style="font-weight: bold">Email:</span> '.$persona->email.'</p>
										<p><span style="font-weight: bold">Teléfono:</span> '.$persona->telefono.'</p>
										<p><span style="font-weight: bold">Nº Registro:</span> '.$persona->registro.'</p>
										<p><span style="font-weight: bold">Curso:</span> '.$curso.'</p>
										<p><span style="font-weight: bold">Fecha Formación:</span> '.$form.'</p>											
									</div>
									<div class="col-md-6">
										<p><span style="font-weight: bold">Carnet:</span> '.$carnet.'</p>
										<p><span style="font-weight: bold">Fecha Carnet:</span> '.$fecha.'</p>
										<p><span style="font-weight: bold">Test:</span> '.$test.'</p>
										<p><span style="font-weight: bold">Activo:</span> '.$activo.'</p>
										<p><span style="font-weight: bold">Nota:</span> '.$persona->nota.'</p>
										<p><span style="font-weight: bold">Alta:</span> '.$creador.'</p>
										<p><span style="font-weight: bold">Fecha Alta:</span> '.$alta.'</p>											
									</div>
								</div>
								<div class="panel-heading" style="background: #b21a30; text-align: center; margin-top: 20px">
									<p style="color: #fff">Historial</p>
								</div>
								<div class="panel-body" style="padding: 10px">';

				if($cambios->num_rows() != 0){

					foreach($cambios->result() as $cambio){

						$creador2 = $this->post->get_creador($cambio->creador);

						if($cambio->salon == 0){
							$cambio_salon = "Desconocido/Rotativos";
						}else{
							$cambio_salon = $this->post->get_salon($cambio->salon);
						}

						if(isset($cambio->fecha) && $cambio->fecha != ''){
							$fecha2 = explode("-", $cambio->fecha);
							$fecha2 = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0];
						}else{
							$fecha2 = ''; 
						}

						$html_personal .= '<p><span style="font-weight: bold">'.$fecha2.' - '.$creador2.' - '.$cambio_salon.'</p>';

					}

				}

				if($activos->num_rows() != 0){

					foreach($activos->result() as $activo){

						$creador2 = $this->post->get_creador($activo->creador);

						if($activo->activo == 0){
							$cambio_activo = "Baja usuario";
						}else{
							$cambio_activo = "Alta usuario";
						}

						if(isset($activo->fecha) && $activo->fecha != ''){
							$fecha2 = explode("-", $activo->fecha);
							$fecha2 = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0];
						}else{
							$fecha2 = ''; 
						}

						$html_personal .= '<p><span style="font-weight: bold">'.$fecha2.' - '.$creador2.' : '.$cambio_activo.'</p>';

					}

				}
										
				if($comentarios->num_rows() != 0){

					foreach($comentarios->result() as $comentario){

						$creador2 = $this->post->get_creador($comentario->creador);

						if(isset($comentario->fecha) && $comentario->fecha != ''){
							$fecha2 = explode("-", $comentario->fecha);
							$fecha2 = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0];
						}else{
							$fecha2 = ''; 
						}

						$html_personal .= '<p><span style="font-weight: bold">'.$fecha2.' - '.$creador2.' : Comentario añadido</p>';

					}
				}

				$html_personal .= '<p><span style="font-weight: bold">'.$alta.' - '.$creador.' : Alta usuario</p>';

				$html_personal .= '</div>
								</div>
								<div class="col-md-1 col-sm-12">
								</div>
								<div class="col-md-5 col-sm-12" style="padding: 0">
									<div style="background: #eb9316; text-align: center; padding: 10px 15px">
										<p style="font-weight: bold; color: #fff">Observaciones</p>
									</div>';

				if($comentarios->num_rows() != 0){

					foreach($comentarios->result() as $comentario){

						$creador2 = $this->post->get_creador($comentario->creador);

						if(isset($comentario->fecha) && $comentario->fecha != ''){
							$fecha2 = explode("-", $comentario->fecha);
							$fecha2 = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0];
						}else{
							$fecha2 = ''; 
						}

						$obs = $this->utf8_decode($comentario->comentario);

						$html_personal .= '<div style="padding: 10px; border: 1px solid #ddd; margin-top: 20px">
												<p><span style="font-weight: bold">Autor:</span> '.$creador2.'</p>
												<p><span style="font-weight: bold">Fecha:</span> '.$fecha2.'</p>														
												'.htmlspecialchars_decode($obs).'
											</div>';

					}
				}

				$obs = $this->utf8_decode($persona->observaciones);
									
				$html_personal .= '<div style="padding: 10px; border: 1px solid #ddd; margin-top: 20px">
										<p><span style="font-weight: bold">Autor:</span> '.$creador.'</p>
										<p><span style="font-weight: bold">Fecha:</span> '.$alta.'</p>														
										'.htmlspecialchars_decode($obs).'
									</div>
								</div>';												
				
				$data = array('title' => '', 'html_personal' => $html_personal, 'id' => $id);
				$this->load_view('ver_personal', $data);				
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Imágenes personal */
	public function personal_img($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$persona = $this->post->get_persona($id);
				$persona_img = $this->post->get_images_persona($id);
				
				$img_container = '';
				if($persona_img->num_rows() > 0){
					foreach($persona_img->result() as $img){
						$img_container .= '<div style="padding: 20px" class="col-md-3 col-sm-12">
															 		<img style="width: 100%" src="'.base_url("files/img/personal/".$img->imagen."").'">
															 		<div style="text-align: center; width: 100%; padding: 5px 0;">
															 			<a style="padding: 4px 8px; border-radius: 15px;" href="'.base_url('eliminar_imagen_personal/'.$img->id.'/'.$img->personal.'').'" type="button" class="btn btn-danger" alt="Eliminar" title="Eliminar"><i class="fa fa-close"></i></a>
															 		</div>
															 </div>';
					}
				}else{
					$img_container = '<p style="text-align: center; font-weight: bold; margin: 20px 0;">No hay imágenes</p>';
				}
				
				$data = array('title' => '', 'img_container' => $img_container, 'persona' => $persona);
				$this->load_view('personal_img', $data);
			}
		}		
	}
	
	/* Eliminar imágen */
	public function eliminar_imagen_personal($id,$personal){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 6){
				$this->gestion();
			}else{
				$imagen = $this->post->get_image_personal($id);
				$eliminar = $this->post->eliminar_imagen_personal($id);
				unlink(APPPATH."../tickets/files/img/personal/".$imagen->imagen."");
				$this->personal_img($personal);
			}
		}
	}
	
	/* Recaudar salones */	
	public function recaudar_salones_contador($idsalon=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$salones = $this->post->get_salones_contador();
				$html_salones='';
				foreach($salones->result() as $salon){
					if(isset($idsalon) && $salon->id == $idsalon){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}
				
				if(isset($idsalon)){
					/* Máquinas averias Salón */
					$maquinas = $this->post->get_maquinas_salon_contador($idsalon);
					$cont = 0;
					$html_maquinas = '';
					foreach($maquinas->result() as $maquina){
						/* Comprobar maquinas recaudadas */
						$recaudada = 0;
						$recaudaciones_maquina = $this->post->get_recaudaciones_maquina_salon_contador($maquina->id);
						foreach($recaudaciones_maquina->result() as $recaudacion){
							if(!isset($recaudacion->recaudacion)){
								$recaudada = 1;
								$id_recaudacion = $recaudacion->id;
							}
						}			
						if($recaudada == 1){
							$estilo = "border: 1px solid #fff; margin: 5px; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #5cb85c; color: #fff; width: 80px; text-align: center; float: left; height: 70px";
							$id_div = $maquina->id;
							$class_div = "norecaudar";
							$data_href = base_url('editar_recaudacion_maquina_salon_contador/'.$id_recaudacion.'/'.$idsalon.'');
							$cont++;
						}else{
							$estilo = "border: 1px solid #000; margin: 5px; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; color: #000; width: 80px; text-align: center; float: left; height: 70px";
							$id_div = $maquina->id;
							$data_href = base_url('recaudar_maquina_salon_contador/'.$maquina->id.'/'.$idsalon.'');
							$class_div = "recaudar";
						}
						
						$html_maquinas .= '<div id="'.$id_div.'" class="'.$class_div.'" style="'.$estilo.'" data-href="'.$data_href.'">
												<span class="glyphicon glyphicon-modal-window"></span> 
												<h6 style="margin: 0; font-size: 10px">'.$maquina->maquina.'</h6>
											</div>';
					}
					
					$html_recaudar_salon = '';
					if($cont != 0){
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <a href="'.base_url("recaudar_salon_contador/".$idsalon).'" class="btn btn-success" aria-haspopup="true" aria-expanded="false" style="font-weight: bold">
																		    Recaudar Salon
																		  </a>
																		</div>';
					}else{
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="font-weight: bold" disabled>
																		    Recaudar Salon
																		  </button>
																		</div>';
					}
								
					$data = array('title' => '', 'tabla_salones' => $html_salones, 'salon' => $idsalon, 'html_maquinas' => $html_maquinas, 'html_recaudar_salon' => $html_recaudar_salon);
					$this->load_view('recaudar_salones_contador', $data);
				}else{
					$data = array('title' => '', 'tabla_salones' => $html_salones);
					$this->load_view('recaudar_salones_contador', $data);
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_salones_contador_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$salones = $this->post->get_salones_contador();
				$html_salones='';
				foreach($salones->result() as $salon){
					if($salon->id == $this->input->post('salon')){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}
				
				/* Máquinas averias Salón */
				$maquinas = $this->post->get_maquinas_salon_contador($this->input->post('salon'));
				$cont = 0;
				$html_maquinas = '';
				foreach($maquinas->result() as $maquina){
					/* Comprobar maquinas recaudadas */
					$recaudada = 0;
					$recaudaciones_maquina = $this->post->get_recaudaciones_maquina_salon_contador($maquina->id);
					foreach($recaudaciones_maquina->result() as $recaudacion){
						if(!isset($recaudacion->recaudacion)){
							$recaudada = 1;
							$id_recaudacion = $recaudacion->id;
						}
					}			
					if($recaudada == 1){
						$estilo = "border: 1px solid #fff; margin: 5px; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #5cb85c; color: #fff; width: 80px; text-align: center; float: left; height: 70px";
						$id_div = $maquina->id;
						$class_div = "norecaudar";
						$data_href = base_url('editar_recaudacion_maquina_salon_contador/'.$id_recaudacion.'/'.$this->input->post('salon').'');
						$cont++;
					}else{
						$estilo = "border: 1px solid #000; margin: 5px; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; color: #000; width: 80px; text-align: center; float: left; height: 70px";
						$id_div = $maquina->id;
						$data_href = base_url('recaudar_maquina_salon_contador/'.$maquina->id.'/'.$this->input->post('salon').'');
						$class_div = "recaudar";
					}
					
					$html_maquinas .= '<div id="'.$id_div.'" class="'.$class_div.'" style="'.$estilo.'" data-href="'.$data_href.'">
											<span class="glyphicon glyphicon-modal-window"></span>
											<h6 style="margin: 0; font-size: 10px">'.$maquina->maquina.'</h6>
										</div>';
				}
				
				$html_recaudar_salon = '';
				if($cont != 0){
					$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																	  <a href="'.base_url("recaudar_salon_contador/".$this->input->post('salon')).'" class="btn btn-success" aria-haspopup="true" aria-expanded="false" style="font-weight: bold">
																	    Recaudar Salon
																	  </a>
																	</div>';
				}else{
					$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																	  <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="font-weight: bold" disabled>
																	    Recaudar Salon
																	  </button>
																	</div>';
				}
							
				$data = array('title' => '', 'tabla_salones' => $html_salones, 'salon' => $this->input->post('salon'), 'html_maquinas' => $html_maquinas, 'html_recaudar_salon' => $html_recaudar_salon);
				$this->load_view('recaudar_salones_contador', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_recaudacion_maquina_salon_contador($reca,$salon){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$recaudacion = $this->post->get_recaudacion_maquina_salon_contador_id($reca);
				$maquina = $this->post->get_maquina_completo($recaudacion->maquina);
				$contador = $this->post->get_modelo($maquina->modelo);
				if(!isset($contador->contador) || $contador->contador == ""){
					$contador = $this->post->get_fabricante_modelo($maquina->modelo);
				}
				$ultima = $this->post->get_ultima_recaudacion_maquina($recaudacion->maquina);
				$data = array('title' => '', 'recaudacion' => $recaudacion, 'maquina' => $maquina, 'contador' => $contador, 'salon' => $salon, 'ultima' => $ultima);
				$this->load_view('editar_recaudacion_maquina_salon_contador', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_recaudacion_maquina_salon_contador_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$maquina = $this->post->get_maquina_completo($this->input->post('maquina'));
				$recaudar = $this->post->editar_recaudacion_maquina_salon_contador($this->input->post('recaudacion'), $this->input->post('entrada_total_pasos'), $this->input->post('entrada_total_euros'), $this->input->post('entrada_parcial_pasos'), $this->input->post('entrada_parcial_euros'), $this->input->post('salida_total_pasos'), $this->input->post('salida_total_euros'), $this->input->post('salida_parcial_pasos'), $this->input->post('salida_parcial_euros'), $this->input->post('total_pasos'), $this->input->post('total_euros'), $this->input->post('parcial_pasos'), $this->input->post('parcial_euros'), $this->input->post('neto_total_pasos'), $this->input->post('neto_total_euros'), $this->input->post('neto_parcial_pasos'), $this->input->post('neto_parcial_euros'));
				
				if($recaudar){
					/* Select salones */
					$salones = $this->post->get_salones_contador();
					$html_salones='';
					foreach($salones->result() as $salon){
						if($salon->id == $maquina->salon){
							$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}
					
					/* Máquinas averias Salón */
					$maquinas = $this->post->get_maquinas_salon_contador($maquina->salon);
					$cont = 0;
					$html_maquinas = '';
					foreach($maquinas->result() as $maquina){
						/* Comprobar maquinas recaudadas */
						$recaudada = 0;
						$recaudaciones_maquina = $this->post->get_recaudaciones_maquina_salon_contador($maquina->id);
						foreach($recaudaciones_maquina->result() as $recaudacion){
							if(!isset($recaudacion->recaudacion)){
								$recaudada = 1;
								$id_recaudacion = $recaudacion->id;
							}
						}			
						if($recaudada == 1){
							$estilo = "border: 1px solid #fff; margin: 5px; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #5cb85c; color: #fff; width: 80px; text-align: center; float: left; height: 70px";
							$id_div = $maquina->id;
							$class_div = "norecaudar";
							$data_href = base_url('editar_recaudacion_maquina_salon_contador/'.$id_recaudacion.'/'.$maquina->salon.'');
							$cont++;
						}else{
							$estilo = "border: 1px solid #000; margin: 5px; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; color: #000; width: 80px; text-align: center; float: left; height: 70px";
							$id_div = $maquina->id;
							$data_href = base_url('recaudar_maquina_salon_contador/'.$maquina->id.'/'.$maquina->salon.'');
							$class_div = "recaudar";
						}
						
						$html_maquinas .= '<div id="'.$id_div.'" class="'.$class_div.'" style="'.$estilo.'" data-href="'.$data_href.'">
												<span class="glyphicon glyphicon-modal-window"></span> 
												<h6 style="margin: 0; font-size: 10px">'.$maquina->maquina.'</h6>
											</div>';
					}
					
					$html_recaudar_salon = '';
					if($cont != 0){
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <a href="'.base_url("recaudar_salon_contador/".$maquina->salon).'" class="btn btn-success" aria-haspopup="true" aria-expanded="false" style="font-weight: bold">
																		    Recaudar Salon
																		  </a>
																		</div>';
					}else{
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="font-weight: bold" disabled>
																		    Recaudar Salon
																		  </button>
																		</div>';
					}
								
					$data = array('title' => '', 'tabla_salones' => $html_salones, 'salon' => $maquina->salon, 'html_maquinas' => $html_maquinas, 'html_recaudar_salon' => $html_recaudar_salon);
					$this->load_view('recaudar_salones_contador', $data);

				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_maquina_salon_contador($maquina,$salon){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$maquina = $this->post->get_maquina_completo($maquina);
				$contador = $this->post->get_modelo($maquina->modelo);
				if(!isset($contador->contador) || $contador->contador == ""){
					$contador = $this->post->get_fabricante_modelo($maquina->modelo);
				}
				$ultima = $this->post->get_ultima_recaudacion_maquina($maquina->id);
				$data = array('title' => '', 'maquina' => $maquina, 'contador' => $contador, 'salon' => $salon, 'ultima' => $ultima);
				$this->load_view('recaudar_maquina_salon_contador', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_maquina_salon_contador_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$maquina = $this->post->get_maquina_completo($this->input->post('maquina'));
				$recaudar = $this->post->crear_recaudacion_maquina_salon_contador($maquina->id, $maquina->salon, $this->input->post('entrada_total_pasos'), $this->input->post('entrada_total_euros'), $this->input->post('entrada_parcial_pasos'), $this->input->post('entrada_parcial_euros'), $this->input->post('salida_total_pasos'), $this->input->post('salida_total_euros'), $this->input->post('salida_parcial_pasos'), $this->input->post('salida_parcial_euros'), $this->input->post('total_pasos'), $this->input->post('total_euros'), $this->input->post('parcial_pasos'), $this->input->post('parcial_euros'), $this->input->post('neto_total_pasos'), $this->input->post('neto_total_euros'), $this->input->post('neto_parcial_pasos'), $this->input->post('neto_parcial_euros'));
				
				if($recaudar){
					/* Select salones */
					$salones = $this->post->get_salones_contador();
					$html_salones='';
					foreach($salones->result() as $salon){
						if($salon->id == $maquina->salon){
							$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}
					
					/* Máquinas averias Salón */
					$maquinas = $this->post->get_maquinas_salon_contador($maquina->salon);
					$cont = 0;
					$html_maquinas = '';
					foreach($maquinas->result() as $maquina){
						/* Comprobar maquinas recaudadas */
						$recaudada = 0;
						$recaudaciones_maquina = $this->post->get_recaudaciones_maquina_salon_contador($maquina->id);
						foreach($recaudaciones_maquina->result() as $recaudacion){
							if(!isset($recaudacion->recaudacion)){
								$recaudada = 1;
								$id_recaudacion = $recaudacion->id;
							}
						}			
						if($recaudada == 1){
							$estilo = "border: 1px solid #fff; margin: 5px; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #5cb85c; color: #fff; width: 80px; text-align: center; float: left; height: 70px";
							$id_div = $maquina->id;
							$class_div = "norecaudar";
							$data_href = base_url('editar_recaudacion_maquina_salon_contador/'.$id_recaudacion.'/'.$maquina->salon.'');
							$cont++;
						}else{
							$estilo = "border: 1px solid #000; margin: 5px; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; color: #000; width: 80px; text-align: center; float: left; height: 70px";
							$id_div = $maquina->id;
							$data_href = base_url('recaudar_maquina_salon_contador/'.$maquina->id.'/'.$maquina->salon.'');
							$class_div = "recaudar";
						}
						
						$html_maquinas .= '<div id="'.$id_div.'" class="'.$class_div.'" style="'.$estilo.'" data-href="'.$data_href.'">
												<span class="glyphicon glyphicon-modal-window"></span> 
												<h6 style="margin: 0; font-size: 10px">'.$maquina->maquina.'</h6>
											</div>';
					}
					
					$html_recaudar_salon = '';
					if($cont != 0){
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <a href="'.base_url("recaudar_salon_contador/".$maquina->salon).'" class="btn btn-success" aria-haspopup="true" aria-expanded="false" style="font-weight: bold">
																		    Recaudar Salon
																		  </a>
																		</div>';
					}else{
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="font-weight: bold" disabled>
																		    Recaudar Salon
																		  </button>
																		</div>';
					}
								
					$data = array('title' => '', 'tabla_salones' => $html_salones, 'salon' => $maquina->salon, 'html_maquinas' => $html_maquinas, 'html_recaudar_salon' => $html_recaudar_salon);
					$this->load_view('recaudar_salones_contador', $data);

				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_salon_contador($salon){
		error_reporting(0);
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$salon = $this->post->get_salon_completo($salon);
				$recaudacion = $this->post->get_ultima_recaudacion_salon_contador($salon->id);
				
				$maquinas_recaudadas = $this->post->get_maquinas_recaudadas_salon_contador($salon->id);
				$total_maquinas = 0;
				$html_maquinas = '';
				foreach($maquinas_recaudadas->result() as $maquina_recaudada){
					$maquina = $this->post->get_maquina_completo($maquina_recaudada->maquina);
					$html_maquinas .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
												<p style="text-align: left; margin-left: 10px">'.$maquina->maquina.'</p>
											</div>
							  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
											<p style="text-align: right; margin-right: 10px">'.number_format($maquina_recaudada->neto_parcial_euros, 2, ',', '.').' €</p>
										</div>';
					$total_maquinas += $maquina_recaudada->neto_parcial_euros;
				};
				
				$total_pagos = 0;
				$pagos_jackpot = $this->post->get_pagos_cajero_jackpot($salon->id,$recaudacion->fecha);
				$total_jackpot = 0;
				$html_pagos = '';
				if($pagos_jackpot){
					foreach($pagos_jackpot as $pago_jackpot){
						$total_jackpot += $pago_jackpot['Value'];
					}
					$total_pagos += $total_jackpot;
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Jackpot</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">'.number_format($total_jackpot, 2, ',', '.').' €</p>
									</div>';
				}else{
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Jackpot</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">0 €</p>
									</div>';
				}

				$pagos_manual = $this->post->get_pagos_cajero_manual($salon->id,$recaudacion->fecha);
				if($pagos_manual){
					$total_pagos += $pagos_manual['total'];
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Manual</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">'.number_format($pagos_manual['total'], 2, ',', '.').' €</p>
									</div>';					
				}else{
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Manual</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">0 €</p>
									</div>';
				}

				$pagos_mnr = $this->post->get_pagos_cajero_mnr($salon->id,$recaudacion->fecha);
				$total_mnr = 0;
				if($pagos_mnr){
					foreach($pagos_mnr as $pago_mnr){
						$total_mnr += $pago_mnr['Value'];
					}
					$total_pagos += $total_mnr;
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">No registrado</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">'.number_format($total_mnr, 2, ',', '.').' €</p>
									</div>';
				}else{
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">No registrado</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">0 €</p>
									</div>';
				}

				$pagos_factura = $this->post->get_pagos_cajero_factura($salon->id,$recaudacion->fecha);
				$total_factura = 0;
				if($pagos_factura){
					foreach($pagos_factura as $pago_factura){
						$total_factura += $pago_mnr['Value'];
					}
					$total_pagos += $total_factura;
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Facturas</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">'.number_format($total_factura, 2, ',', '.').' €</p>
									</div>';
				}else{
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Facturas</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">0 €</p>
									</div>';
				}

				$pagos_incidencia = $this->post->get_pagos_cajero_incidencia($salon->id,$recaudacion->fecha);
				$total_incidencia = 0;
				if($pagos_incidencia){
					foreach($pagos_incidencia as $pago_incidencia){
						$total_incidencia += $pago_mnr['Value'];
					}
					$total_pagos += $total_incidencia;
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Incidencias</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">'.number_format($total_incidencia, 2, ',', '.').' €</p>
									</div>';
				}else{
					$html_pagos .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Incidencias</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">0 €</p>
									</div>';
				}

				$datafono = 0;
				$pagos_datafono = $this->post->get_pagos_cajero_datafono($salon->id,$recaudacion->fecha);
				$html_datafono = '';
				if($pagos_datafono){
					foreach($pagos_datafono as $pago_datafono){
						$datafono += $pago_datafono['Value'];
					}
					$html_datafono .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Datáfono</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">'.number_format($datafono, 2, ',', '.').' €</p>
									</div>';
				}else{
					$html_datafono .= '<div style="padding: 0; text-align: center; width: 50%; float: left; border: 1px solid #000">
										<p style="text-align: left; margin-left: 10px">Datáfono</p>
									</div>
						  			<div style="padding: 0; width: 50%; float: left; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000">
										<p style="text-align: right; margin-right: 10px">0 €</p>
									</div>';
				}

				$total = $total_maquinas;
				
				$data = array('title' => '', 'salon' => $salon, 'total_maquinas' => $total_maquinas, 'recaudacion' => $recaudacion, 'html_maquinas' => $html_maquinas, 'html_pagos' => $html_pagos, 'total_pagos' => $total_pagos, 'total' => $total, 'datafono' => $datafono);
				$this->load_view('recaudar_salon_contador', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_salon_contador_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$recaudar = $this->post->crear_recaudacion_salon_contador($this->input->post('salon'),$this->input->post('bruto'),$this->input->post('pagos'),$this->input->post('datafono'),$this->input->post('neto'),$this->input->post('comentarios'));
				if($recaudar){
					$recaudacion_pdf = $this->crear_recaudacion_salon_pdf($recaudar);
					if($recaudacion_pdf){					
						$data = array('title' => '', 'recaudar' => $recaudar);
						$this->load_view('recaudacion_salon_finalizada', $data);
					}					
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Crear pdf recaudacion */
	public function crear_recaudacion_salon_pdf($recaudar){
		if($this->session->userdata('logged_in')){
			$this->load->library('pdf_recaudaciones');
			$pdf = $this->pdf_recaudaciones->return_pdf_recaudacion_salon($recaudar, $this->session->userdata('logged_in')['id']);
			if($pdf){
				$email = $this->enviar_email_recaudacion_salon($recaudar->id);
				return true;
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}	
	
	/* Seccion racaudar maquinas averias - tecnicos */
	public function recaudar($id_salon=null){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				/* Select salones */
				if($this->session->userdata('logged_in')['acceso'] == 24 || $this->session->userdata('logged_in')['acceso'] == 41){
					$salones = $this->post->get_salones_averias();
				}else{
					$salones = $this->post->get_salones_averias_op($this->session->userdata('logged_in')['acceso']);
				}				
				$html_salones='';
				foreach($salones->result() as $salon){
					if(isset($id_salon) && $salon->id == $id_salon){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}

				if(isset($id_salon)){
					/* Máquinas averias Salón */
					$maquinas = $this->post->get_maquinas_averias_salon_2($id_salon);
					$cont = 0;
					$html_maquinas = '';
					foreach($maquinas->result() as $maquina){
						/* Comprobar maquinas recaudadas */
						$recaudada = 0;
						$recaudaciones_maquina = $this->post->get_recaudaciones_maquina($maquina->id);
						foreach($recaudaciones_maquina->result() as $recaudacion){
							if(!isset($recaudacion->recaudacion)){
								$recaudada = 1;
								$id_recaudacion = $recaudacion->id;
							}
						}					
						if($recaudada == 1){
							$estilo = "border: 1px solid #fff; margin: 10px 0; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #5cb85c; color: #fff;";
							$estado = "- Modificar";
							$id_div = $maquina->id;
							$class_div = "norecaudar";
							$data_href = base_url('editar_recaudacion_maquina/'.$id_recaudacion.'');
							$cont++;
						}else{
							$estilo = "border: 1px solid #fff; margin: 10px 0; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #c12e2a; color: #fff;";
							$estado = "- Recaudar";
							$id_div = $maquina->id;
							$data_href = base_url('recaudar_maquina/'.$maquina->id.'');
							$class_div = "recaudar";
						}
						
						$html_maquinas .= '<div id="'.$id_div.'" class="'.$class_div.'" style="'.$estilo.'" data-href="'.$data_href.'">
																	<h5><span class="glyphicon glyphicon-modal-window"></span> '.$maquina->maquina.' <span style="float: right; margin: 3px 0">'.$estado.'</span></h5>
																</div>';
					}
					
					$html_recaudar_salon = '';
					if($cont != 0){
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <a href="'.base_url("recaudar_salon/".$this->input->post('salon')).'" class="btn btn-success" aria-haspopup="true" aria-expanded="false" style="font-weight: bold">
																		    Recaudar Salón
																		  </a>
																		</div>';
					}else{
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="font-weight: bold" disabled>
																		    Recaudar Salón
																		  </button>
																		</div>';
					}

					$data = array('title' => '', 'tabla_salones' => $html_salones, 'salon' => $id_salon, 'html_maquinas' => $html_maquinas, 'html_recaudar_salon' => $html_recaudar_salon);
					$this->load_view('recaudar', $data);
				}else{
					$data = array('title' => '', 'tabla_salones' => $html_salones);
					$this->load_view('recaudar', $data);
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				/* Select salones */
				$salones = $this->post->get_salones_averias();				
				$html_salones='';
				foreach($salones->result() as $salon){
					if($salon->id == $this->input->post('salon')){
						$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
					}else{
						$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}
				
				/* Máquinas averias Salón */
				$maquinas = $this->post->get_maquinas_averias_salon_2($this->input->post('salon'));
				$cont = 0;
				$html_maquinas = '';
				foreach($maquinas->result() as $maquina){
					/* Comprobar maquinas recaudadas */
					$recaudada = 0;
					$recaudaciones_maquina = $this->post->get_recaudaciones_maquina($maquina->id);
					foreach($recaudaciones_maquina->result() as $recaudacion){
						if(!isset($recaudacion->recaudacion)){
							$recaudada = 1;
							$id_recaudacion = $recaudacion->id;
						}
					}					
					if($recaudada == 1){
						$estilo = "border: 1px solid #fff; margin: 10px 0; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #5cb85c; color: #fff;";
						$estado = "- Modificar";
						$id_div = $maquina->id;
						$class_div = "norecaudar";
						$data_href = base_url('editar_recaudacion_maquina/'.$id_recaudacion.'');
						$cont++;
					}else{
						$estilo = "border: 1px solid #fff; margin: 10px 0; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #c12e2a; color: #fff;";
						$estado = "- Recaudar";
						$id_div = $maquina->id;
						$data_href = base_url('recaudar_maquina/'.$maquina->id.'');
						$class_div = "recaudar";
					}
					
					$html_maquinas .= '<div id="'.$id_div.'" class="'.$class_div.'" style="'.$estilo.'" data-href="'.$data_href.'">
																<h5><span class="glyphicon glyphicon-modal-window"></span> '.$maquina->maquina.' <span style="float: right; margin: 3px 0">'.$estado.'</span></h5>
															</div>';
				}
				
				$html_recaudar_salon = '';
				if($cont != 0){
					$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																	  <a href="'.base_url("recaudar_salon/".$this->input->post('salon')).'" class="btn btn-success" aria-haspopup="true" aria-expanded="false" style="font-weight: bold">
																	    Recaudar Salón
																	  </a>
																	</div>';
				}else{
					$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																	  <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="font-weight: bold" disabled>
																	    Recaudar Salón
																	  </button>
																	</div>';
				}
						
				$data = array('title' => '', 'tabla_salones' => $html_salones, 'salon' => $this->input->post('salon'), 'html_maquinas' => $html_maquinas, 'html_recaudar_salon' => $html_recaudar_salon);
				$this->load_view('recaudar', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_recaudacion_maquina($reca){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$recaudacion = $this->post->get_recaudacion_maquina_id($reca);
				$maquina = $this->post->get_maquina_completo($recaudacion->maquina);
				$data = array('title' => '', 'recaudacion' => $recaudacion, 'maquina' => $maquina);
				$this->load_view('editar_recaudacion_maquina', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function editar_recaudacion_maquina_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$maquina = $this->post->get_maquina_completo($this->input->post('maquina'));
				$recaudar = $this->post->editar_recaudacion_maquina($this->input->post('recaudacion'), $this->input->post('t_h_u_1'), $this->input->post('t_h_t_1'), $this->input->post('t_h_u_2'), $this->input->post('t_h_t_2'), $this->input->post('t_h_t'), $this->input->post('t_b_u_5'), $this->input->post('t_b_t_5'), $this->input->post('t_b_u_10'), $this->input->post('t_b_t_10'), $this->input->post('t_b_u_20'), $this->input->post('t_b_t_20'), $this->input->post('t_b_u_50'), $this->input->post('t_b_t_50'), $this->input->post('t_b_t'), $this->input->post('t_c_u_1'), $this->input->post('t_c_t_1'), $this->input->post('t_c_u_2'), $this->input->post('t_c_t_2'), $this->input->post('t_c_t'), $this->input->post('t_r_u_20'), $this->input->post('t_r_t_20'), $this->input->post('t_r_t'), $this->input->post('t_reca_t'), $this->input->post('r_h_u_1'), $this->input->post('r_h_t_1'), $this->input->post('r_h_u_2'), $this->input->post('r_h_t_2'), $this->input->post('r_h_t'), $this->input->post('r_b_u_5'), $this->input->post('r_b_t_5'), $this->input->post('r_b_u_10'), $this->input->post('r_b_t_10'), $this->input->post('r_b_u_20'), $this->input->post('r_b_t_20'), $this->input->post('r_b_u_50'), $this->input->post('r_b_t_50'), $this->input->post('r_b_t'), $this->input->post('r_c_u_1'), $this->input->post('r_c_t_1'), $this->input->post('r_c_u_2'), $this->input->post('r_c_t_2'), $this->input->post('r_c_t'), $this->input->post('r_r_u_20'), $this->input->post('r_r_t_20'), $this->input->post('r_r_t'), $this->input->post('r_reca_t'), $this->input->post('carga'), $this->input->post('neto'));
				
				if($recaudar){
					/* Select salones */
					$salones = $this->post->get_salones_averias();
					$html_salones='';
					foreach($salones->result() as $salon){
						if($salon->id == $maquina->salon){
							$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}
					
					/* Máquinas averias Salón */
					$maquinas = $this->post->get_maquinas_averias_salon_2($maquina->salon);
					$cont = 0;
					$html_maquinas = '';
					foreach($maquinas->result() as $maquina){
						/* Comprobar maquinas recaudadas */
						$recaudada = 0;
						$recaudaciones_maquina = $this->post->get_recaudaciones_maquina($maquina->id);
						foreach($recaudaciones_maquina->result() as $recaudacion){
							if(!isset($recaudacion->recaudacion)){
								$recaudada = 1;
								$id_recaudacion = $recaudacion->id;
							}
						}											
						if($recaudada == 1){
							$estilo = "border: 1px solid #fff; margin: 10px 0; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #5cb85c; color: #fff;";
							$estado = "- Modificar";
							$id_div = $maquina->id;
							$class_div = "norecaudar";
							$data_href = base_url('editar_recaudacion_maquina/'.$id_recaudacion.'');
							$cont++;
						}else{
							$estilo = "border: 1px solid #fff; margin: 10px 0; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #c12e2a; color: #fff;";
							$estado = "- Recaudar";
							$id_div = $maquina->id;
							$class_div = "recaudar";
							$data_href = base_url('recaudar_maquina/'.$maquina->id.'');
						}
						
						$html_maquinas .= '<div id="'.$id_div.'" class="'.$class_div.'" style="'.$estilo.'" data-href="'.$data_href.'">
																	<h5><span class="glyphicon glyphicon-modal-window"></span> '.$maquina->maquina.' <span style="float: right; margin: 3px 0">'.$estado.'</span></h5>
																</div>';
					}
					
					$html_recaudar_salon = '';
					if($cont != 0){
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <a href="'.base_url("recaudar_salon/".$maquina->salon).'" class="btn btn-success" aria-haspopup="true" aria-expanded="false" style="font-weight: bold">
																		    Recaudar Salón
																		  </a>
																		</div>';
					}else{
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="font-weight: bold" disabled>
																		    Recaudar Salón
																		  </button>
																		</div>';
					}
							
					$data = array('title' => '', 'tabla_salones' => $html_salones, 'salon' => $maquina->salon, 'html_maquinas' => $html_maquinas, 'html_recaudar_salon' => $html_recaudar_salon);
					$this->load_view('recaudar', $data);
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_maquina($maquina){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$maquina = $this->post->get_maquina_completo($maquina);
				
				$data = array('title' => '', 'maquina' => $maquina);
				$this->load_view('recaudar_maquina', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_maquina_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$maquina = $this->post->get_maquina_completo($this->input->post('maquina'));
				$recaudar = $this->post->crear_recaudacion_maquina($maquina->id, $maquina->salon, $this->input->post('t_h_u_1'), $this->input->post('t_h_t_1'), $this->input->post('t_h_u_2'), $this->input->post('t_h_t_2'), $this->input->post('t_h_t'), $this->input->post('t_b_u_5'), $this->input->post('t_b_t_5'), $this->input->post('t_b_u_10'), $this->input->post('t_b_t_10'), $this->input->post('t_b_u_20'), $this->input->post('t_b_t_20'), $this->input->post('t_b_u_50'), $this->input->post('t_b_t_50'), $this->input->post('t_b_t'), $this->input->post('t_c_u_1'), $this->input->post('t_c_t_1'), $this->input->post('t_c_u_2'), $this->input->post('t_c_t_2'), $this->input->post('t_c_t'), $this->input->post('t_r_u_20'), $this->input->post('t_r_t_20'), $this->input->post('t_r_t'), $this->input->post('t_reca_t'), $this->input->post('r_h_u_1'), $this->input->post('r_h_t_1'), $this->input->post('r_h_u_2'), $this->input->post('r_h_t_2'), $this->input->post('r_h_t'), $this->input->post('r_b_u_5'), $this->input->post('r_b_t_5'), $this->input->post('r_b_u_10'), $this->input->post('r_b_t_10'), $this->input->post('r_b_u_20'), $this->input->post('r_b_t_20'), $this->input->post('r_b_u_50'), $this->input->post('r_b_t_50'), $this->input->post('r_b_t'), $this->input->post('r_c_u_1'), $this->input->post('r_c_t_1'), $this->input->post('r_c_u_2'), $this->input->post('r_c_t_2'), $this->input->post('r_c_t'), $this->input->post('r_r_u_20'), $this->input->post('r_r_t_20'), $this->input->post('r_r_t'), $this->input->post('r_reca_t'), $this->input->post('carga'), $this->input->post('neto'));
				
				if($recaudar){
					/* Select salones */
					$salones = $this->post->get_salones_averias();
					$html_salones='';
					foreach($salones->result() as $salon){
						if($salon->id == $maquina->salon){
							$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
						}else{
							$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
						}
					}
					
					/* Máquinas averias Salón */
					$maquinas = $this->post->get_maquinas_averias_salon_2($maquina->salon);
					$cont = 0;
					$html_maquinas = '';
					foreach($maquinas->result() as $maquina){
						/* Comprobar maquinas recaudadas */
						$recaudada = 0;
						$recaudaciones_maquina = $this->post->get_recaudaciones_maquina($maquina->id);
						foreach($recaudaciones_maquina->result() as $recaudacion){
							if(!isset($recaudacion->recaudacion)){
								$recaudada = 1;
								$id_recaudacion = $recaudacion->id;
							}
						}					
						if($recaudada == 1){
							$estilo = "border: 1px solid #fff; margin: 10px 0; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #5cb85c; color: #fff;";
							$estado = "- Modificar";
							$id_div = $maquina->id;
							$class_div = "norecaudar";
							$data_href = base_url('editar_recaudacion_maquina/'.$id_recaudacion.'');
							$cont++;
						}else{
							$estilo = "border: 1px solid #fff; margin: 10px 0; padding: 5px 10px; border-radius: 5px; box-shadow: 2px 2px #ccc; cursor: pointer; background: #c12e2a; color: #fff;";
							$estado = "- Recaudar";
							$id_div = $maquina->id;
							$class_div = "recaudar";
							$data_href = base_url('recaudar_maquina/'.$maquina->id.'');
						}
						
						$html_maquinas .= '<div id="'.$id_div.'" class="'.$class_div.'" style="'.$estilo.'" data-href="'.$data_href.'">
																	<h5><span class="glyphicon glyphicon-modal-window"></span> '.$maquina->maquina.' <span style="float: right; margin: 3px 0">'.$estado.'</span></h5>
																</div>';
					}
					
					$html_recaudar_salon = '';
					if($cont != 0){
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <a href="'.base_url("recaudar_salon/".$maquina->salon).'" class="btn btn-success" aria-haspopup="true" aria-expanded="false" style="font-weight: bold">
																		    Recaudar Salón
																		  </a>
																		</div>';
					}else{
						$html_recaudar_salon = '<div class="btn-group pull-right" style="margin: 20px 0">
																		  <button type="button" class="btn btn-default" aria-haspopup="true" aria-expanded="false" style="font-weight: bold" disabled>
																		    Recaudar Salón
																		  </button>
																		</div>';
					}
							
					$data = array('title' => '', 'tabla_salones' => $html_salones, 'salon' => $maquina->salon, 'html_maquinas' => $html_maquinas, 'html_recaudar_salon' => $html_recaudar_salon);
					$this->load_view('recaudar', $data);
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_salon($salon){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$salon = $this->post->get_salon_completo($salon);
				$maquinas_recaudadas = $this->post->get_maquinas_recaudadas($salon->id);
				$recaudacion = $this->post->get_ultima_recaudacion($salon->id);
	
				$total = 0;
				foreach($maquinas_recaudadas->result() as $maquina_recaudada){
					$total += $maquina_recaudada->neto;
				}
				
				$data = array('title' => '', 'salon' => $salon, 'total' => $total, 'recaudacion' => $recaudacion);
				$this->load_view('recaudar_salon', $data);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudar_salon_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$recaudar = $this->post->crear_recaudacion_salon($this->input->post('salon'),$this->input->post('reca_ant'),$this->input->post('pago_ant'),$this->input->post('balance_ant'),$this->input->post('pagos_caj'),$this->input->post('balance'),$this->input->post('total'),$this->input->post('reca_total'),$this->input->post('pagos'),$this->input->post('pagos_1'),$this->input->post('pagos_2'),$this->input->post('pagos_5'),$this->input->post('pagos_10'),$this->input->post('pagos_20'),$this->input->post('pagos_50'),$this->input->post('neto'),$this->input->post('comentarios'));
				if($recaudar){					
					$data = array('title' => '', 'recaudar' => $recaudar, 'salon' => $this->input->post('salon'));
					$this->load_view('recaudacion_firmar', $data);
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function guardar_firma_recaudacion(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				// read input stream
			    $data1 = $this->input->post('img1');
			    $data2 = $this->input->post('img2');
			    $reca = $this->input->post('reca');
			    $salon = $this->input->post('salon');

			    $filteredData1=substr($data1, strpos($data1, ",")+1);
			    $filteredData2=substr($data2, strpos($data2, ",")+1);

			    $decodedData1=base64_decode($filteredData1);
			    $decodedData2=base64_decode($filteredData2);
			 
			    // store in server
			    $fic_name1 = 'firma'.$reca.'_'.rand().'.png';
			    $fic_name2 = 'firma'.$reca.'_'.rand().'.png';
			    
			    $fp = fopen(APPPATH."../tickets/files/img/firmas/".$fic_name1, 'wb');
			    $ok1 = fwrite( $fp, $decodedData1);
			    fclose( $fp );
					$fp = fopen(APPPATH."../tickets/files/img/firmas/".$fic_name2, 'wb');
			    $ok2 = fwrite( $fp, $decodedData2);
			    fclose( $fp );
			    
			    if($ok1 && $ok2){
			    	$firmar = $this->post->guardar_firma_recaudacion($reca,$fic_name1,$fic_name2,$salon);
			    	if($firmar){
			    		echo "ok";
			    	}
			    }			
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function recaudacion_finalizada($reca){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 4){
				$this->gestion();
			}else{
				$recaudar = $this->post->get_recaudacion_id($reca);
				if($recaudar){
					$recaudacion_pdf = $this->crear_recaudacion_pdf($reca);
					if($recaudacion_pdf){					
						$data = array('title' => '', 'recaudar' => $recaudar);
						$this->load_view('recaudacion_finalizada', $data);
					}
				}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Crear pdf recaudacion */
	public function crear_recaudacion_pdf($reca){
		if($this->session->userdata('logged_in')){
			$recaudar = $this->post->get_recaudacion_id($reca);
			$this->load->library('pdf_recaudaciones');
			$pdf = $this->pdf_recaudaciones->return_pdf_recaudaciones($recaudar, $this->session->userdata('logged_in')['id']);			
			if($pdf){
				$email = $this->enviar_email_recaudacion($reca);
				return true;
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Control de horario */	
	public function control_horario(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 5){
				setlocale(LC_ALL, 'es_ES');
				$ultimo_registro = $this->post->get_ultimo_registro_horario($this->session->userdata('logged_in')['id']);
				$jornada_hoy = '';
				$tiempo_trabajado = '';
				$tiempo = $tiempo2 = $tiempo3 = $tiempo4 = 0;
				$hoy = $this->post->get_registro_horario_hoy($this->session->userdata('logged_in')['id']);
				if($hoy->num_rows() > 0){
					foreach($hoy->result() as $registro){
						$fecha = explode(" ", $registro->fecha);
						if($registro->tipo == 1){
							$jornada_hoy .= '<p style="margin-bottom: 10px"><i class="fa fa-arrow-right" style="color: #5cb85c"></i> '.$fecha[1].'</p>';
						}else if($registro->tipo == 0){
							$jornada_hoy .= '<p style="margin-bottom: 10px"><i class="fa fa-arrow-left" style="color: #d80039"></i> '.$fecha[1].'</p>';			
						}
					}
					if($hoy->num_rows() == 1){
						foreach($hoy->result() as $registro){
							$fecha = explode(" ", $registro->fecha);
							if($registro->tipo == 1){
								$apertura = new DateTime($fecha[1]);
								$ahora = date('H:i:s');
								$cierre = new DateTime($ahora);
								$tiempo = $apertura->diff($cierre);
							}else{
								$e = new DateTime('00:00');
								$f = clone $e;
								$tiempo = $f->diff($e);
							}
						}
					}else if($hoy->num_rows() % 2 == 0){
						$e = new DateTime('00:00');
						$f = clone $e;
						$i = 0;
						foreach($hoy->result() as $registro){
							$fecha = explode(" ", $registro->fecha);
							if($registro->tipo == 1){
								$apertura = new DateTime($fecha[1]);
							}else if($registro->tipo == 0){
								$cierre = new DateTime($fecha[1]);
							}
							if(isset($apertura) && isset($cierre)){
								$tiempo = $apertura->diff($cierre);
								$e->add($tiempo);
								$apertura = $cierre = null;
							}
							$i++;							
						}
						$tiempo = $f->diff($e);
					}else if($hoy->num_rows() % 2 == 1){
						$e = new DateTime('00:00');
						$f = clone $e;
						$i = 0;
						foreach($hoy->result() as $registro){
							$fecha = explode(" ", $registro->fecha);
							if($registro->tipo == 1){
								$apertura = new DateTime($fecha[1]);
							}else if($registro->tipo == 0){
								$cierre = new DateTime($fecha[1]);
							}
							if($hoy->num_rows() == $i+1){
								$apertura = new DateTime($fecha[1]);
							}else if(isset($apertura) && isset($cierre)){
								$tiempo = $apertura->diff($cierre);
								$e->add($tiempo);
								$apertura = $cierre = null;
							}
							$i++;							
						}
						$ahora = date('H:i:s');
						$cierre = new DateTime($ahora);
						$tiempo = $apertura->diff($cierre);
						$e->add($tiempo);
						$tiempo = $f->diff($e);
					}else{
						$e = new DateTime('00:00');
						$f = clone $e;
						$tiempo = $f->diff($e);
					}
					$tiempo_trabajado .= $tiempo->format('%H:%I:%S');
				}else{
					$jornada_hoy .= '<p style="font-style: italic; margin-bottom: 20px">No hay registo de trabajo hoy</p>';
					$tiempo_trabajado .= '00:00';
				}
				$historial_jornadas = '';
				$cont = $i = 1;
				while ($i <= 5){
					$fecha = date('Y-m-d', strtotime('-'.$cont.' day', strtotime(date('Y-m-d'))));
					if ($fecha < "2025-03-31"){ break; }
					$jornada = $this->post->get_registro_horario_jornada($this->session->userdata('logged_in')['id'], $fecha);
					if($jornada->num_rows() > 0){
						$fecha_formateada = explode("-", $fecha);
						$historial_jornadas .= utf8_encode("<div style='display: inline-block; border: 2px solid #ccc; margin: 5px; padding: 5px; border-radius: 5px; font-size: 12px'><p style='font-weight: bold; color: #888; text-align: left; text-decoration: underline'>".strftime('%A', strtotime($fecha)).", ".$fecha_formateada[2]."-".$fecha_formateada[1]."-".$fecha_formateada[0]."</p>");
						$e = new DateTime('00:00');
						$f = clone $e;
						foreach($jornada->result() as $registro){
							$hora = explode(" ", $registro->fecha);
							if($registro->tipo == 1){
								$historial_jornadas .= "<p style='text-align: left; margin-bottom: 2px'><span style='color: #5cb85c; font-weight: bold'>Entrada: </span>".$hora[1]."</p>";
								$fechainicial = new DateTime($registro->fecha);
							}else{
								$historial_jornadas .= "<p style='text-align: left; margin-bottom: 2px'><span style='color: #d80039; font-weight: bold'>Salida: </span>".$hora[1]."</p>";
								$fechaactual = new DateTime($registro->fecha);
							}
							if(isset($fechainicial) && isset($fechaactual)){
								$diferencia = $fechainicial->diff($fechaactual);
								$e->add($diferencia);
								$fechainicial = $fechaactual = null;
							}
						}
						$historial_jornadas .= "<p style='text-align: left; margin-bottom: 2px'><span style='font-weight: bold'>Total: </span>".$f->diff($e)->format("%H:%I:%S")."</p>";		
						$historial_jornadas .= "</div>";
						$i++;
					}
					$cont++;
				}
				$data = array('title' => 'Administracion', 'ultimo_registro' => $ultimo_registro, 'jornada_hoy' => $jornada_hoy, 'tiempo_trabajado' => $tiempo_trabajado, 'historial_jornadas' => $historial_jornadas);
				$this->load_view('control_horario', $data);
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function control_horario_form(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 5){
				setlocale(LC_ALL, 'es_ES');
				if(isset($_POST['comenzar'])){
					$tipo_registro = 1;
				}else if(isset($_POST['finalizar'])){
					$tipo_registro = 0;
				}
				$registrar_horario = $this->post->registrar_horario($this->session->userdata('logged_in')['id'],$tipo_registro);
				if($registrar_horario){
					$ultimo_registro = $this->post->get_ultimo_registro_horario($this->session->userdata('logged_in')['id']);
					$jornada_hoy = '';
					$tiempo_trabajado = '';
					$tiempo = $tiempo2 = $tiempo3 = $tiempo4 = 0;
					$hoy = $this->post->get_registro_horario_hoy($this->session->userdata('logged_in')['id']);
					if($hoy->num_rows() > 0){
						foreach($hoy->result() as $registro){
							$fecha = explode(" ", $registro->fecha);
							if($registro->tipo == 1){
								$jornada_hoy .= '<p style="margin-bottom: 10px"><i class="fa fa-arrow-right" style="color: #5cb85c"></i> '.$fecha[1].'</p>';
							}else if($registro->tipo == 0){
								$jornada_hoy .= '<p style="margin-bottom: 10px"><i class="fa fa-arrow-left" style="color: #d80039"></i> '.$fecha[1].'</p>';			
							}
						}
						if($hoy->num_rows() == 1){
							foreach($hoy->result() as $registro){
								$fecha = explode(" ", $registro->fecha);
								if($registro->tipo == 1){
									$apertura = new DateTime($fecha[1]);
									$ahora = date('H:i:s');
									$cierre = new DateTime($ahora);
									$tiempo = $apertura->diff($cierre);
								}else{
									$e = new DateTime('00:00');
									$f = clone $e;
									$tiempo = $f->diff($e);
								}
							}
						}else if($hoy->num_rows() % 2 == 0){
							$e = new DateTime('00:00');
							$f = clone $e;
							$i = 0;
							foreach($hoy->result() as $registro){
								$fecha = explode(" ", $registro->fecha);
								if($registro->tipo == 1){
									$apertura = new DateTime($fecha[1]);
								}else if($registro->tipo == 0){
									$cierre = new DateTime($fecha[1]);
								}
								if(isset($apertura) && isset($cierre)){
									$tiempo = $apertura->diff($cierre);
									$e->add($tiempo);
									$apertura = $cierre = null;
								}
								$i++;							
							}
							$tiempo = $f->diff($e);
						}else if($hoy->num_rows() % 2 == 1){
							$e = new DateTime('00:00');
							$f = clone $e;
							$i = 0;
							foreach($hoy->result() as $registro){
								$fecha = explode(" ", $registro->fecha);
								if($registro->tipo == 1){
									$apertura = new DateTime($fecha[1]);
								}else if($registro->tipo == 0){
									$cierre = new DateTime($fecha[1]);
								}
								if($hoy->num_rows() == $i+1){
									$apertura = new DateTime($fecha[1]);
								}else if(isset($apertura) && isset($cierre)){
									$tiempo = $apertura->diff($cierre);
									$e->add($tiempo);
									$apertura = $cierre = null;
								}
								$i++;							
							}
							$ahora = date('H:i:s');
							$cierre = new DateTime($ahora);
							$tiempo = $apertura->diff($cierre);
							$e->add($tiempo);
							$tiempo = $f->diff($e);
						}else{
							$e = new DateTime('00:00');
							$f = clone $e;
							$tiempo = $f->diff($e);
						}
						$tiempo_trabajado .= $tiempo->format('%H:%I:%S');
					}else{
						$jornada_hoy .= '<p style="font-style: italic; margin-bottom: 20px">No ha registrado trabajo hoy</p>';
						$tiempo_trabajado .= '00:00';
					}
					$historial_jornadas = '';
					$cont = $i = 1;
					while ($i <= 5){
						$fecha = date('Y-m-d', strtotime('-'.$cont.' day', strtotime(date('Y-m-d'))));
						if ($fecha < "2025-03-31"){ break; }
						$jornada = $this->post->get_registro_horario_jornada($this->session->userdata('logged_in')['id'], $fecha);
						if($jornada->num_rows() > 0){
							$fecha_formateada = explode("-", $fecha);
							$historial_jornadas .= utf8_encode("<div style='display: inline-block; border: 2px solid #ccc; margin: 5px; padding: 5px; border-radius: 5px; font-size: 12px'><p style='font-weight: bold; color: #888; text-align: left; text-decoration: underline'>".strftime('%A', strtotime($fecha)).", ".$fecha_formateada[2]."-".$fecha_formateada[1]."-".$fecha_formateada[0]."</p>");
							$e = new DateTime('00:00');
							$f = clone $e;
							foreach($jornada->result() as $registro){
								$hora = explode(" ", $registro->fecha);
								if($registro->tipo == 1){
									$historial_jornadas .= "<p style='text-align: left; margin-bottom: 2px'><span style='color: #5cb85c; font-weight: bold'>Entrada: </span>".$hora[1]."</p>";
									$fechainicial = new DateTime($registro->fecha);
								}else{
									$historial_jornadas .= "<p style='text-align: left; margin-bottom: 2px'><span style='color: #d80039; font-weight: bold'>Salida: </span>".$hora[1]."</p>";
									$fechaactual = new DateTime($registro->fecha);
								}
								if(isset($fechainicial) && isset($fechaactual)){
									$diferencia = $fechainicial->diff($fechaactual);
									$e->add($diferencia);
									$fechainicial = $fechaactual = null;
								}
							}
							$historial_jornadas .= "<p style='text-align: left; margin-bottom: 2px'><span style='font-weight: bold'>Total: </span>".$f->diff($e)->format("%H:%I:%S")."</p>";		
							$historial_jornadas .= "</div>";
							$i++;
						}
						$cont++;
					}
					$data = array('title' => 'Administracion', 'ultimo_registro' => $ultimo_registro, 'jornada_hoy' => $jornada_hoy, 'tiempo_trabajado' => $tiempo_trabajado, 'historial_jornadas' => $historial_jornadas);
					$this->load_view('control_horario', $data);
				}
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Buscador horarios */
	public function buscador_horarios(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){
				setlocale(LC_ALL, 'es_ES');
				if($this->input->post('fecha_inicio') != ''){
					$fecha_inicio = $this->input->post('fecha_inicio');
				}else{
					$fecha_inicio = date('d/m/Y', strtotime('-10 day', strtotime(date('Y-m-d'))));
				}
				if($this->input->post('fecha_fin') != ''){
					$fecha_fin = $this->input->post('fecha_fin');
				}else{
					$fecha_fin = date('d/m/Y');
				}
				$html_horarios = '<h3 style="margin-top: 0 !important;">Registros jornada</h3>';
				if($this->input->post('usuario') == 0){
					$horarios = $this->post->get_horarios($this->session->userdata('logged_in')['acceso']);
				}else{
					$horarios = $this->post->get_horarios_persona_buscador($this->input->post('usuario'));
				}				
				foreach($horarios->result() as $horario){			
					$html_horarios .= "<div style='display: inline-block; float: left; width: 100%; margin: 1% 0; border: 2px solid #ccc; border-radius: 5px'>";
					$usuario = $this->post->get_creador_completo($horario->id);
					$html_horarios .= "<p style='font-weight: bold; text-decoration: underline; font-size: 14px; margin-top: 2%; margin: 0; padding: 10px;'>".$usuario->nombre."</p>";
					
					$fechaI = explode("/", $fecha_inicio);
					$fechaF = explode("/", $fecha_fin);
					$begin = new DateTime($fechaI[2]."-".$fechaI[1]."-".$fechaI[0]);
					$end = new DateTime($fechaF[2]."-".$fechaF[1]."-".$fechaF[0]);
					$end->modify('+1 day');
					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);

					foreach ($period as $dt) {
						$jornada = $this->post->get_registro_horario_jornada($horario->id, $dt->format("Y-m-d"));
						if($jornada->num_rows() > 0){
							$fecha_formateada = explode("-", $dt->format("Y-m-d"));
							$html_horarios .= utf8_encode("<div style='display: inline-block; border: 2px solid #ccc; margin: 5px; padding: 5px; border-radius: 5px; font-size: 12px'><p style='font-weight: bold; color: #888; text-align: left; text-decoration: underline'>".strftime('%A', strtotime($dt->format("Y-m-d"))).", ".$fecha_formateada[2]."-".$fecha_formateada[1]."-".$fecha_formateada[0]."</p>");
							$e = new DateTime('00:00');
							$f = clone $e;
							foreach($jornada->result() as $registro){
								$hora = explode(" ", $registro->fecha);
								if($registro->tipo == 1){
									$html_horarios .= "<p style='text-align: left; margin-bottom: 2px'><span style='color: #5cb85c; font-weight: bold'>Entrada: </span>".$hora[1]."</p>";
									$fechainicial = new DateTime($registro->fecha);
								}else{
									$html_horarios .= "<p style='text-align: left; margin-bottom: 2px'><span style='color: #d80039; font-weight: bold'>Salida: </span>".$hora[1]."</p>";
									$fechaactual = new DateTime($registro->fecha);
								}
								if(isset($fechainicial) && isset($fechaactual)){
									$diferencia = $fechainicial->diff($fechaactual);
									$e->add($diferencia);
									$fechainicial = $fechaactual = null;
								}
							}
							$html_horarios .= "<p style='text-align: left; margin-bottom: 2px'><span style='font-weight: bold'>Total: </span>".$f->diff($e)->format("%H:%I:%S")."</p>";
							$html_horarios .= "</div>";
						}
					}
					$html_horarios .= "</div>";
				}

				$usuarios_select = '';
				$usuarios = $this->post->get_horarios($this->session->userdata('logged_in')['acceso']);
				foreach($usuarios->result() as $usuario){
					if($this->input->post('usuario') == $usuario->id){
						$usuarios_select .= "<option value='".$usuario->id."' selected>".$usuario->nombre."</option>";
					}else{
						$usuarios_select .= "<option value='".$usuario->id."'>".$usuario->nombre."</option>";
					}									
				}
				$data = array('title' => 'Administracion', 'html_horarios' => $html_horarios, 'usuarios_select' => $usuarios_select, 'usuario' => $this->input->post('usuario'), 'fecha_inicio' => $fechaI[2]."-".$fechaI[1]."-".$fechaI[0], 'fecha_fin' => $fechaF[2]."-".$fechaF[1]."-".$fechaF[0]);
				$this->load_view('informes_horarios', $data);
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Horarios informes */
	/* Control de horario */
	public function informes_horarios($get_sql=NULL){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){
				setlocale(LC_ALL, 'es_ES');
				$html_horarios = '<h3 style="margin-top: 0 !important;">Registros jornada</h3>';
				$horarios = $this->post->get_horarios($this->session->userdata('logged_in')['acceso']);
				foreach($horarios->result() as $horario){
					$horarios_persona = $this->post->get_horarios_persona($horario->id);
					if($horarios_persona->num_rows() > 0){				
						$html_horarios .= "<div style='display: inline-block; float: left; width: 100%; margin: 1% 0; border: 2px solid #ccc; border-radius: 5px'>";
						$usuario = $this->post->get_creador_completo($horario->id);
						$html_horarios .= "<p style='font-weight: bold; text-decoration: underline; font-size: 14px; margin-top: 2%; margin: 0; padding: 10px;'>".$usuario->nombre."</p>";
						$cont = $i = 1;
						while ($i <= 10){
							$fecha = date('Y-m-d', strtotime('-'.$cont.' day', strtotime(date('Y-m-d'))));
							if ($fecha < "2025-03-31"){ break; }
							$jornada = $this->post->get_registro_horario_jornada($horario->id, $fecha);
							if($jornada->num_rows() > 0){
								$fecha_formateada = explode("-", $fecha);
								$html_horarios .= utf8_encode("<div style='display: inline-block; border: 2px solid #ccc; margin: 5px; padding: 5px; border-radius: 5px; font-size: 12px'><p style='font-weight: bold; color: #888; text-align: left; text-decoration: underline'>".strftime('%A', strtotime($fecha)).", ".$fecha_formateada[2]."-".$fecha_formateada[1]."-".$fecha_formateada[0]."</p>");
								$e = new DateTime('00:00');
								$f = clone $e;
								foreach($jornada->result() as $registro){
									$hora = explode(" ", $registro->fecha);
									if($registro->tipo == 1){
										$html_horarios .= "<p style='text-align: left; margin-bottom: 2px'><span style='color: #5cb85c; font-weight: bold'>Entrada: </span>".$hora[1]."</p>";
										$fechainicial = new DateTime($registro->fecha);
									}else{
										$html_horarios .= "<p style='text-align: left; margin-bottom: 2px'><span style='color: #d80039; font-weight: bold'>Salida: </span>".$hora[1]."</p>";
										$fechaactual = new DateTime($registro->fecha);
									}
									if(isset($fechainicial) && isset($fechaactual)){
										$diferencia = $fechainicial->diff($fechaactual);
										$e->add($diferencia);
										$fechainicial = $fechaactual = null;
									}
								}
								$html_horarios .= "<p style='text-align: left; margin-bottom: 2px'><span style='font-weight: bold'>Total: </span>".$f->diff($e)->format("%H:%I:%S")."</p>";
								$html_horarios .= "</div>";
								$i++;
							}
							$cont++;							
						}
						$html_horarios .= "</div>";
					}
				}

				$usuarios_select = '';
				$usuarios = $this->post->get_horarios($this->session->userdata('logged_in')['acceso']);
				foreach($usuarios->result() as $usuario){
					if($this->input->post('usuario') == $usuario->id){
						$usuarios_select .= "<option value='".$usuario->id."' selected>".$usuario->nombre."</option>";
					}else{
						$usuarios_select .= "<option value='".$usuario->id."'>".$usuario->nombre."</option>";
					}									
				}
				$data = array('title' => 'Administracion', 'html_horarios' => $html_horarios, 'usuarios_select' => $usuarios_select);
				$this->load_view('informes_horarios', $data);
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Renting vehiculos */
	public function renting(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){				
				$coches = $this->post->get_vehiculos_renting();
				$tabla_renting = '';				
				foreach($coches->result() as $coche){
					$user = $this->post->get_usuario($coche->usuario);
					if($user){
						$user = $user->nombre;
					}else{
						$user = "Todos";
					}
					$fin_limite = date('Y-m-d', strtotime('+1 year', strtotime($coche->fecha_compra)));
					$fecha_compra = explode("-", $fin_limite);
					$fecha_compra = $fecha_compra[2]."-".$fecha_compra[1]."-".$fecha_compra[0];
					$repostajes = $this->post->get_repostajes_limite($coche->id,$coche->fecha_compra,$fin_limite);
					$total = 0;
					foreach($repostajes->result() as $repostaje){
						$total += $repostaje->kilometros;
					}
					$restantes = $coche->limite_km - $total;
					$tabla_renting .= '<tr style="font-family: Open Sans,Helvetica,Arial,sans-serif; font-size: 13px; color: #000;">
															<td>'.$coche->matricula.'</td>
															<td>'.$user.'</td>
															<td>'.$total.'</td>
															<td>'.$fecha_compra.'</td>
															<td>'.$restantes.'</td>
														</tr>';
				}
				$data = array('title' => 'Administracion', 'tabla_renting' => $tabla_renting);
				$this->load_view('renting', $data);				
			}else{
				$this->gestion();
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Comprobar cajero salon AJAX*/
	public function comprobar_cajero_salon(){
		$salones = $this->input->post('salones');
		$i = $j = 0;
		$salones = explode(",",$salones);
		$html_comprobaciones = '<div id="avisos_content" style="width: 35%; background-color: #d9edf7; border: 1px solid #31708f; border-radius: 5px; padding: 10px 10px 5px; color: #31708f; font-weight: bold; margin-bottom: 16px; display: none">';							
		foreach($salones as $salon){
			if($j == 0){
				$j++;
				continue;
			}
			$cajero = $this->post->get_cajero($salon);
			$resultado = $this->comprobar_puertos2($cajero->servidor,$cajero->puerto);						
			if(!$resultado){
				$salon_info = $this->post->get_salon($salon);
				$html_comprobaciones .= '<p style="width: 100%; font-weight: bold">'.$salon_info.' <span style="color: #31708f">Servidor desconectado o puerto cerrado. Por favor revise la configuración.</span></p>';
				$i++;
			}
			$j++;
		}
		$html_comprobaciones .= '</div>
							</div>';
		if($i > 0){
			$html_avisos = '<div class="col-md-12 col-sm-12">
					<div id="avisos_alert" class="alert alert-info" role="alert" style="width: 35%; font-weight: bold; text-align: center; cursor: pointer">PROBLEMAS DE CONEXIÓN ('.$i.')</div>';
			$html_avisos .= $html_comprobaciones;
		}else{
			$html_avisos = '<div class="col-md-12 col-sm-12">
					<div id="avisos_alert" class="alert alert-info" role="alert" style="width: 35%; font-weight: bold; text-align: center; cursor: pointer">TODOS LOS CAJEROS CONECTADOS</div>';
		}
		echo $html_avisos;
	}

	/* Comprobar cajero */
	public function comprobar_cajero(){
		$id = $this->input->post('id');
		$maquina = $this->post->get_maquina_completo($id);
		$ip = $this->input->post('ip');
		$p = $this->input->post('p');
		$u = $this->input->post('u');
		$c = $this->input->post('c');
		$resultado = $this->comprobar_conexion($maquina,$ip,$p,$u,$c);
		echo json_encode($resultado);
	}
	
	/* Comprobar cajeros operadora AJAX */
	public function comprobar_cajeros(){
		$op = $this->input->post('op');
		$salones = $this->post->get_salones_cajeros($op);
		foreach($salones->result() as $salon){
			$cajero = $this->post->get_cajero($salon->id);
			$resultado = $this->comprobar_puertos2($cajero->servidor,$cajero->puerto);
			$html_comprobaciones = '';
			if($resultado){
				$html_comprobaciones .= '<p style="float: left; width: 100%; font-weight: bold">'.$salon->salon.' <span style="color: #449d44">conectado</span></p>';
			}else{
				$html_comprobaciones .= '<p style="float: left; width: 100%; font-weight: bold">'.$salon->salon.' <span style="color: #b21a30">Servidor desconectado o puerto cerrado. Por favor revise la configuración.</span></p>';
			}
			echo $html_comprobaciones;
		}
	}
	
	/* Comprobar puertos ip */
	public function comprobar_puertos($servidor,$puerto){
		error_reporting(0);
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{			
				//Puerto 3507
		        $port = $puerto;
		        $timeout = 2;
		        $tbegin = microtime(true);
		        $fp = fsockopen($servidor, $port, $errno, $errstr, $timeout);
		        $responding = 1;
		        if(!$fp){ 
		        	$responding = 0; 
		        }
		        $tend = microtime(true);
		        fclose($fp);
		        $mstime = ($tend - $tbegin) * 1000;
		        $mstime = round($mstime, 2);
		        if($responding){
		        	return true;            
		        }else{
		        	return false;            
		        }
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Comprobar puertos ip */
	public function comprobar_puertos2($servidor,$puerto){
		error_reporting(0);
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{				
		        $port = $puerto;
		        $timeout = 1;
		        $fp = fsockopen($servidor, $port, $errno, $errstr, $timeout);
		        $responding = 1;
		        if(!$fp){ 
		        	$responding = 0; 
		        }
		        fclose($fp);
		        if($responding){
		        	return true;            
		        }else{
		        	return false;           
		        }
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function comprobar_conexion($maquina,$servidor,$puerto,$user,$pass){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3){
				$this->gestion();
			}else{				
		        $port = $puerto;
		        $timeout = 2;
		        $tbegin = microtime(true);
		        $fp = fsockopen($servidor, $port, $errno, $errstr, $timeout);
		        $responding = 1;
		        if(!$fp){ 
		        	$responding = 0; 
		        }
		        $tend = microtime(true);
		        fclose($fp);
		        $mstime = ($tend - $tbegin) * 1000;
		        $mstime = round($mstime, 2);
		        if($responding){
		        	if($maquina->modelo == 182){
		        		$db = "appdb";
		        		$con=mysqli_connect($servidor,$user,$pass,$db);
						if (mysqli_connect_errno()){
			        		return false;
			        	}else{
			        		$array = array();
			        		return $array;
			        	}
		        	}else{
		        		$db = "ticketserver";
		        		$con=mysqli_connect($servidor,$user,$pass,$db);
						if (mysqli_connect_errno()){
			        		return false;
			        	}else{
			        		$array = array();
			        		$i = 0;
			        		$cassettedetalle = mysqli_query($con,"SELECT LocationType as Cassette, MoneyValue as moneda, quantity as cantidad, amount as total FROM Collect WHERE LocationType LIKE '%Cassette%' ORDER BY LocationType ASC");
			        		while($rowcassettedetalle = mysqli_fetch_array($cassettedetalle)){
			        			$array[$i] = $rowcassettedetalle['moneda'];       		
			        			$i++;
			        		}
			        		$chequea = mysqli_query($con,"select count(*) as filas from collect WHERE State='A'");
			        		while($rowchequea = mysqli_fetch_array($chequea)){
			        			$array[$i] = $rowchequea['filas'];       		
			        			$i++;
			        		}
			        		return $array;
			        	}
		        	}
		        }else{
		        	return false;            
		        }
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* AJAX gestion - comprobar tickets - refresh */	
	public function comprobar_nuevas_incidencias(){
		$sql = $this->input->post('sql');
		$incidencias = "SELECT * FROM tickets WHERE 1";	
		if($sql != ''){
			$incidencias = "SELECT * FROM tickets WHERE 1".$sql;
			if($this->session->userdata('logged_in')['rol'] == 1){
				$incidencias .= " AND tipo_averia = '6'";
			}
		}else{
			if($this->session->userdata('logged_in')['id'] == 571 || $this->session->userdata('logged_in')['id'] == 351){
				$incidencias = $this->post->get_tickets_inf();
			}else if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] == 24){
				$incidencias = $this->post->get_tickets_sat();
			}else if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7){
				$incidencias = $this->post->get_tickets();
			}else if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 5){
				$incidencias = $this->post->get_tickets_op($this->session->userdata('logged_in')['acceso']);
			}else if($this->session->userdata('logged_in')['rol'] == 3){
				$incidencias = $this->post->get_tickets_salon($this->session->userdata('logged_in')['acceso']);
			}else if($this->session->userdata('logged_in')['rol'] == 6){
				$incidencias = $this->post->get_tickets_com();
			}else if($this->session->userdata('logged_in')['rol'] == 8){
				$incidencias = $this->post->get_tickets_mkt();
			}else if($this->session->userdata('logged_in')['rol'] == 9){
				$incidencias = $this->post->get_tickets_onl();
			}
		}
		$numero_filas = 0;
		$usuarios_adm = $this->post->get_usuarios_adm();
		$array_id_incidencias = array();
		foreach($incidencias->result() as $ticket){
			/* Incidencias CIRSA */
			if($this->session->userdata('logged_in')['rol'] != 1 || $this->session->userdata('logged_in')['rol'] != 2){
				if($ticket->tipo_averia == 11){
					continue;
				}
			}

			/* Pendiente Kirol */
			if($this->session->userdata('logged_in')['rol'] != 1){
				if($ticket->situacion == 8){
					continue;
				}
			}

			/* Comprobra gestion ATC operadoras activo y creador ADM */
			if($this->session->userdata('logged_in')['acceso'] == 24 && $this->session->userdata('logged_in')['rol'] != 3){
				if(in_array($ticket->creador, $usuarios_adm)){

				}else{
					if($ticket->situacion == 2 && $ticket->destino == 4){
						
					}else{
						continue;
					}
				}
				
				if($ticket->tipo_averia != '6' && $ticket->tipo_averia != '3'){
					$gestion_activa = $this->post->get_gestion_activa($ticket->empresa);
					if($gestion_activa->tipo_gestion == 0){
						continue;
					}
				}
			}

			/* Evitar duplicados */
			if (in_array($ticket->id, $array_id_incidencias)) {
			    continue;
			}
			array_push($array_id_incidencias, $ticket->id);

			$numero_filas++;
		}
		echo $numero_filas;
	}
	
	public function upload_images($salon){
		// upload.php
		// 'images' refers to your file input name attribute
		if (empty($_FILES['images'])) {
		    echo json_encode(['error'=>'No se encontraron archivos.']); 
		    // or you can throw an exception 
		    return; // terminate
		}

		// get the files posted
		$images = $_FILES['images'];

		// a flag to see if everything is ok
		$success = null;

		// file paths to store
		$paths= [];

		// get file names
		$filenames = $images['name'];
		
		// loop and process files
		for($i=0; $i < count($filenames); $i++){
		    $ext = explode('.', basename($filenames[$i]));
		    $img_name = md5(uniqid()) . "." . array_pop($ext); 
		    $target = APPPATH."../tickets/files/img/locales/" . $img_name;
		    
		    if(move_uploaded_file($images['tmp_name'][$i], $target)) {
		        $success = true;
		        $paths[] = $img_name;
		    } else {
		        $success = false;
		        break;
		    }
		    
		}
	  
		// check and process based on successful status 
		if ($success === true) {
		    // call the function to save all data to database
		    // code for the following function `save_data` is not 
		    // mentioned in this example
		    $guardar_img_db = $this->post->save_image($salon,$paths);

		    // store a successful response (default at least an empty array). You
		    // could return any additional response info you need to the plugin for
		    // advanced implementations.
		    $output = [];
		    // for example you can get the list of files uploaded this way
		    // $output = ['uploaded' => $paths];
		} elseif ($success === false) {
		    $output = ['error'=>'Error mientras se cargaban las imágenes.'];
		    // delete any uploaded files
		    foreach ($paths as $file) {
		        unlink($file);
		    }
		} else {
		    $output = ['error'=>'Ningún archivo fue procesado.'];
		}

		// return a json encoded response for plugin to process successfully
		echo json_encode($output);
	}
	
	public function upload_images_personal($personal){
		// upload.php
		// 'images' refers to your file input name attribute
		if (empty($_FILES['images'])) {
		    echo json_encode(['error'=>'No se encontraron archivos.']); 
		    // or you can throw an exception 
		    return; // terminate
		}

		// get the files posted
		$images = $_FILES['images'];

		// a flag to see if everything is ok
		$success = null;

		// file paths to store
		$paths= [];

		// get file names
		$filenames = $images['name'];
		
		// loop and process files
		for($i=0; $i < count($filenames); $i++){
		    $ext = explode('.', basename($filenames[$i]));
		    $img_name = md5(uniqid()) . "." . array_pop($ext); 
		    $target = APPPATH."../tickets/files/img/personal/" . $img_name;
		    
		    if(move_uploaded_file($images['tmp_name'][$i], $target)) {
		        $success = true;
		        $paths[] = $img_name;
		    } else {
		        $success = false;
		        break;
		    }
		    
		}
	  
		// check and process based on successful status 
		if ($success === true) {
		    // call the function to save all data to database
		    // code for the following function `save_data` is not 
		    // mentioned in this example
		    $guardar_img_db = $this->post->save_image_personal($personal,$paths);

		    // store a successful response (default at least an empty array). You
		    // could return any additional response info you need to the plugin for
		    // advanced implementations.
		    $output = [];
		    // for example you can get the list of files uploaded this way
		    // $output = ['uploaded' => $paths];
		} elseif ($success === false) {
		    $output = ['error'=>'Error mientras se cargaban las imágenes.'];
		    // delete any uploaded files
		    foreach ($paths as $file) {
		        unlink($file);
		    }
		} else {
		    $output = ['error'=>'Ningún archivo fue procesado.'];
		}

		// return a json encoded response for plugin to process successfully
		echo json_encode($output);
	}

	public function tickets_tarjetas_excel(){

		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
 
		$sheet = $excel->getActiveSheet();

		$sql = 'SELECT * FROM `tickets` WHERE `detalle_error` = 424 AND fecha_solucion > "2020-01-01" GROUP BY salon ORDER BY `id` DESC';
		$tickets = $this->db->query($sql);

		$cont = 1;

		foreach($tickets->result() as $ticket){
			$salon = $this->post->get_salon($ticket->salon);

			$value = 'A'.$cont;
			
			$sheet->setCellValue($value, $salon); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$sql = 'SELECT * FROM `tickets` WHERE `detalle_error` = 424 AND salon = "'.$ticket->salon.'" AND fecha_solucion > "2020-01-01" ORDER BY `id` DESC';
			$incidencias = $this->db->query($sql);

			$cont++;

			foreach($incidencias->result() as $incidencia){
				$usuario = $this->post->get_usuario($incidencia->soluciona);

				$value = 'A'.$cont;
			
				$sheet->setCellValue($value, $incidencia->fecha_solucion." ".$incidencia->hora_solucion); 

				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

				$value = 'B'.$cont;
			
				$sheet->setCellValue($value, $usuario->usuario); 

				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

				$value = 'C'.$cont;
			
				$sheet->setCellValue($value, $incidencia->trata_desc); 

				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

				$cont++;
			}
		}

		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'informe_incidencias_tarjetas.xls'; 
		$writer->save($location);
		
		$location = base_url('../informe_incidencias_tarjetas.xls');
		header("Location: ".$location."");
		die();
	}

	/* Exportar excel horarios */
	public function horarios_excel(){
		$this->load->library('excel');		 
		$excel = new PHPExcel();
		$sheet = $excel->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(20);
		$sheet->getColumnDimension('D')->setWidth(20);

		$sheet->setCellValue('A1', 'USUARIO');
		$sheet->getStyle('A1')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '0080FF'))));
		$sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
		$sheet->getStyle('A1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('A1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('B1', 'TIPO');
		$sheet->getStyle('B1')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '0080FF'))));
		$sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));  
		$sheet->getStyle('B1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('B1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('C1', 'FECHA');
		$sheet->getStyle('C1')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '0080FF'))));
		$sheet->getStyle('C1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE )); 
		$sheet->getStyle('C1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('D1', 'JORNADA');
		$sheet->getStyle('D1')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '0080FF'))));
		$sheet->getStyle('D1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
		$sheet->getStyle('D1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('D1')->getAlignment()->setVertical('center')->setHorizontal('center');

		if($this->input->post('usuario') == 0){
			$horarios = $this->post->get_horarios($this->session->userdata('logged_in')['acceso']);
		}else{
			$horarios = $this->post->get_horarios_persona_buscador($this->input->post('usuario'));
		}

		$html_horarios = '';
		$cont = 2;
		foreach($horarios->result() as $horario){
			$usuario = $this->post->get_creador_completo($horario->id);

			$value = 'A'.$cont;	
			$sheet->setCellValue($value, $usuario->nombre);
			$sheet->getStyle($value)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '800010'))));
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE )); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$begin = new DateTime($this->input->post('fecha_inicio'));
			$end = new DateTime($this->input->post('fecha_Fin'));
			$end->modify('+1 day');
			$interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod($begin, $interval, $end);

			foreach ($period as $dt) {
				$jornada = $this->post->get_registro_horario_jornada($horario->id, $dt->format("Y-m-d"));
				if($jornada->num_rows() > 0){
					$i = 1;
					$e = new DateTime('00:00');
					$f = clone $e;
					foreach($jornada->result() as $registro){
						if($registro->tipo == 1){
							$fechainicial = new DateTime($registro->fecha);
							$value = 'B'.$cont;			
							$sheet->setCellValue($value, "Entrada");
							$sheet->getStyle($value)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '508000'))));
							$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
							$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
							$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
						}else{
							$fechaactual = new DateTime($registro->fecha);
							$value = 'B'.$cont;		
							$sheet->setCellValue($value, "Salida");
							$sheet->getStyle($value)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'A05000'))));
							$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE )); 
							$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
							$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
						}
						if(isset($fechainicial) && isset($fechaactual)){
							$diferencia = $fechainicial->diff($fechaactual);
							$e->add($diferencia);
							$fechainicial = $fechaactual = null;
						}
						$fecha = explode(" ", $registro->fecha);
						$fecha1 = explode("-", $fecha[0]);

						$value = 'C'.$cont;		
						$sheet->setCellValue($value, $fecha1[2]."-".$fecha1[1]."-".$fecha1[0]." ".$fecha[1]);
						if($registro->tipo == 1){
							$sheet->getStyle($value)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '508000'))));
							$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
						}else{
							$sheet->getStyle($value)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'A05000'))));
							$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
						}
						$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
						$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

						if($i == $jornada->num_rows()){
							$value = 'D'.$cont;		
							$sheet->setCellValue($value, $f->diff($e)->format("%H:%I:%S"));
							$sheet->getStyle($value)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'A05000'))));
							$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8)->setColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
							$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
							$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
						}					
						$cont++;
						$i++;
					}
				}
			}
		}

		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'horarios_'.$this->input->post('fecha_inicio').'_'.$this->input->post('fecha_fin').'.xls'; 
		$writer->save($location);
		
		$location = base_url('../horarios_'.$this->input->post('fecha_inicio').'_'.$this->input->post('fecha_fin').'.xls');
		header("Location: ".$location."");
		die();
	}

	/* Exportar excel promo azafatas */
	public function promo_azafatas_excel($sql){
		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(30);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(30);
		$sheet->getColumnDimension('G')->setWidth(30);
		
		$sheet->setCellValue('A1', 'Salon'); 
		
		$sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('A1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('A1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('B1', 'Nombre'); 

		$sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('B1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('B1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('C1', 'Email'); 

		$sheet->getStyle('C1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('C1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('D1', 'DNI'); 

		$sheet->getStyle('D1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('D1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('D1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('E1', 'Telefono');

		$sheet->getStyle('E1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('E1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('E1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('F1', 'Fecha'); 

		$sheet->getStyle('F1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('F1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('F1')->getAlignment()->setVertical('center')->setHorizontal('center');
	
		$sheet->setCellValue('G1', 'Ticket');

		$sheet->getStyle('G1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('G1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('G1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sql = utf8_decode(urldecode($sql));
		
		$promos = $this->post->get_promos($sql);
		
		$cont = 2;
				
		foreach($promos->result() as $promo){

			$value = 'A'.$cont;
			
			$sheet->setCellValue($value, $promo->salon);

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'B'.$cont;
			
			$sheet->setCellValue($value, $promo->nombre); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'C'.$cont;
			
			$sheet->setCellValue($value, $promo->email); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'D'.$cont;
			
			$sheet->setCellValue($value, $promo->dni); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'E'.$cont;
			
			$sheet->setCellValue($value, $promo->telefono); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'F'.$cont;
			
			$sheet->setCellValue($value, $promo->fecha); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'G'.$cont;
			
			$sheet->setCellValue($value, $promo->ticket); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$cont++;
		}

		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'promo_azafatas.xls'; 
		$writer->save($location);
		
		$location = base_url('../promo_azafatas.xls');
		header("Location: ".$location."");
		die();
	}

	/* Exportar excel personal */
	public function personal_excel($sql){

		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(30);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(30);
		$sheet->getColumnDimension('G')->setWidth(30);
		$sheet->getColumnDimension('H')->setWidth(30);
		$sheet->getColumnDimension('I')->setWidth(30);
		$sheet->getColumnDimension('J')->setWidth(30);
		$sheet->getColumnDimension('K')->setWidth(30);
		$sheet->getColumnDimension('L')->setWidth(30);
		$sheet->getColumnDimension('M')->setWidth(30);
		$sheet->getColumnDimension('N')->setWidth(30);
		$sheet->getColumnDimension('O')->setWidth(30);
		$sheet->getColumnDimension('P')->setWidth(30);
		
		$sheet->setCellValue('A1', 'Operadora'); 
		
		$sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('A1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('A1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('B1', 'Salon'); 

		$sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('B1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('B1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('C1', 'Nombre'); 

		$sheet->getStyle('C1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('C1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('D1', 'DNI'); 

		$sheet->getStyle('D1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('D1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('D1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('E1', 'Telefono');

		$sheet->getStyle('E1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('E1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('E1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('F1', 'Email'); 

		$sheet->getStyle('F1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('F1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('F1')->getAlignment()->setVertical('center')->setHorizontal('center');
	
		$sheet->setCellValue('G1', 'Curso');

		$sheet->getStyle('G1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('G1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('G1')->getAlignment()->setVertical('center')->setHorizontal('center');
	
		$sheet->setCellValue('H1', 'Carnet'); 

		$sheet->getStyle('H1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('H1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('H1')->getAlignment()->setVertical('center')->setHorizontal('center');
	
		$sheet->setCellValue('I1', 'Fecha Carnet'); 

		$sheet->getStyle('I1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('I1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('I1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('J1', 'Fecha Formacion'); 

		$sheet->getStyle('J1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('J1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('J1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('K1', 'Test'); 

		$sheet->getStyle('K1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('K1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('K1')->getAlignment()->setVertical('center')->setHorizontal('center');
	
		$sheet->setCellValue('L1', 'Nota Test'); 

		$sheet->getStyle('L1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('L1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('L1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('M1', 'Observaciones'); 

		$sheet->getStyle('M1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('M1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('M1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('N1', 'Activo'); 

		$sheet->getStyle('N1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('N1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('N1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('O1', 'Fecha Alta');

		$sheet->getStyle('O1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('O1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('O1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('P1', 'Creador'); 

		$sheet->getStyle('P1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('P1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('P1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sql = utf8_decode(urldecode($sql));
		
		$personal = $this->post->get_promos($sql);
		
		$cont = 2;
				
		foreach($personal->result() as $persona){

			$value = 'A'.$cont;
			
			if($persona->operadora == 0){
				$sheet->setCellValue($value, "Desconocida");
			}else{
				$operadora = $this->post->get_operadora($persona->operadora);
				$sheet->setCellValue($value, $operadora);
			} 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'B'.$cont;
			
			if($persona->salon == 0){
				$sheet->setCellValue($value, "Desconocido");
			}else{
				$salon = $this->post->get_salon($persona->salon);
				$sheet->setCellValue($value, $salon);
			} 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'C'.$cont;
			
			$sheet->setCellValue($value, $persona->nombre); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'D'.$cont;
			
			$sheet->setCellValue($value, $persona->dni); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'E'.$cont;
			
			$sheet->setCellValue($value, $persona->telefono); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'F'.$cont;
			
			$sheet->setCellValue($value, $persona->email); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'G'.$cont;
			
			if($persona->curso == 1){
				$sheet->setCellValue($value, "Si"); 
			}else{
				$sheet->setCellValue($value, "No");
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'H'.$cont;
			
			if($persona->carnet == 1){
				$sheet->setCellValue($value, "Si"); 
			}else{
				$sheet->setCellValue($value, "No");
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'I'.$cont;

			if(isset($persona->fecha_carnet) && $persona->fecha_carnet != ''){
				$fecha_c = explode("-", $persona->fecha_carnet);
				$sheet->setCellValue($value, $fecha_c[2]."-".$fecha_c[1]."-".$fecha_c[0]); 
			}else{
				$sheet->setCellValue($value, "");
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'J'.$cont;

			if(isset($persona->fecha_formacion) && $persona->fecha_formacion != ''){
				$fecha_f = explode("-", $persona->fecha_formacion);
				$sheet->setCellValue($value, $fecha_f[2]."-".$fecha_f[1]."-".$fecha_f[0]); 
			}else{
				$sheet->setCellValue($value, "");
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'K'.$cont;
			
			if($persona->nota == 1){
				$sheet->setCellValue($value, "Si"); 
			}else{
				$sheet->setCellValue($value, "No");
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'L'.$cont;
			
			$sheet->setCellValue($value, $persona->nota); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'M'.$cont;
			
			$sheet->setCellValue($value, htmlspecialchars_decode($persona->observaciones)); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin'));

			$value = 'N'.$cont;
			
			if($persona->activo == 1){
				$sheet->setCellValue($value, "Si"); 
			}else{
				$sheet->setCellValue($value, "No");
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'O'.$cont;

			if(isset($persona->fecha_alta) && $persona->fecha_alta != ''){
				$fecha_a = explode("-", $persona->fecha_alta);
				$sheet->setCellValue($value, $fecha_a[2]."-".$fecha_a[1]."-".$fecha_a[0]); 
			}else{
				$sheet->setCellValue($value, "");
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'P'.$cont;
			
			if($persona->creador == 0){
				$sheet->setCellValue($value, "Desconocido");
			}else{
				$creador = $this->post->get_creador($persona->creador);
				$sheet->setCellValue($value, $creador);
			} 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$cont++;
		}

		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'personal.xls'; 
		$writer->save($location);
		
		$location = base_url('../personal.xls');
		header("Location: ".$location."");
		die();
	}

	/* Exportar excel visitas */
	public function visitas_excel($sql){

		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet();
		$sheet->getColumnDimension('A')->setWidth(30);
		$sheet->getColumnDimension('B')->setWidth(30);
		$sheet->getColumnDimension('C')->setWidth(30);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(30);
		$sheet->getColumnDimension('G')->setWidth(30);
		
		$sheet->setCellValue('A1', 'Operadora'); 
		
		$sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('A1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('A1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('B1', 'Salon'); 

		$sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('B1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('B1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('C1', 'Fecha'); 

		$sheet->getStyle('C1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('C1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('D1', 'Personal1'); 

		$sheet->getStyle('D1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('D1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('D1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('E1', 'Personal2');

		$sheet->getStyle('E1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('E1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('E1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sheet->setCellValue('F1', 'Observaciones'); 

		$sheet->getStyle('F1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('F1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('F1')->getAlignment()->setVertical('center')->setHorizontal('center');
	
		$sheet->setCellValue('G1', 'Creador');

		$sheet->getStyle('G1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('G1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('G1')->getAlignment()->setVertical('center')->setHorizontal('center');

		$sql = utf8_decode(urldecode($sql));
		
		$visitas = $this->post->get_promos($sql);
		
		$cont = 2;
				
		foreach($visitas->result() as $visita){

			$value = 'A'.$cont;
			
			if($visita->operadora == 0){
				$sheet->setCellValue($value, "Desconocida");
			}else{
				$operadora = $this->post->get_operadora($visita->operadora);
				$sheet->setCellValue($value, $operadora);
			} 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'B'.$cont;
			
			if($visita->salon == 0){
				$sheet->setCellValue($value, "Desconocido");
			}else{
				$salon = $this->post->get_salon($visita->salon);
				$sheet->setCellValue($value, $salon);
			} 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'C'.$cont;

			$sheet->setCellValue($value, $visita->fecha); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'D'.$cont;

			if($visita->personal1 == 0 || empty($visita->personal1 || !isset($visita->personal1))){
				$sheet->setCellValue($value, "Desconocido");
			}else{
				$personal1 = $this->post->get_persona($visita->personal1);
				$sheet->setCellValue($value, $personal1->nombre);
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'E'.$cont;
			
			if($visita->personal2 == 0 || empty($visita->personal2 || !isset($visita->personal2))){
				$sheet->setCellValue($value, "Desconocido");
			}else{
				$personal2 = $this->post->get_persona($visita->personal2);
				$sheet->setCellValue($value, $personal2->nombre);
			}

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$value = 'F'.$cont;
			
			$sheet->setCellValue($value, htmlspecialchars_decode($visita->observaciones)); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin'));

			$value = 'G'.$cont;
			
			if($visita->creador == 0){
				$sheet->setCellValue($value, "Desconocido");
			}else{
				$creador = $this->post->get_creador($visita->creador);
				$sheet->setCellValue($value, $creador);
			} 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$cont++;
		}

		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'visitas.xls'; 
		$writer->save($location);
		
		$location = base_url('../visitas.xls');
		header("Location: ".$location."");
		die();
	}
	
	/* Exportar excel promo VIP */
	public function promos_excel($sql){
		 
		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet(); 
		
		$sheet->setCellValue('A1', 'NOMBRE'); 
		
		$sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('A1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('A1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('B1', 'EMAIL'); 

		$sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('B1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('B1')->getAlignment()->setVertical('center')->setHorizontal('center');

		if (strpos($sql, 'aio_clientes_promo') !== false) {

			$sheet->setCellValue('C1', 'TELEFONO'); 

			$sheet->getStyle('C1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle('C1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center');

			$sheet->setCellValue('D1', 'SALON'); 
		 
			$sheet->getStyle('D1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle('D1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle('D1')->getAlignment()->setVertical('center')->setHorizontal('center');

			$sheet->setCellValue('E1', 'TICKET');

			$sheet->getStyle('E1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle('E1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle('E1')->getAlignment()->setVertical('center')->setHorizontal('center');

		}else if (strpos($sql, 'promo_triples') !== false) {

			$sheet->setCellValue('C1', 'FECHA'); 

			$sheet->getStyle('C1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle('C1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center');

		}else if (strpos($sql, 'promo_canastas') !== false) {

			$sheet->setCellValue('C1', 'FECHA'); 

			$sheet->getStyle('C1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle('C1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center');

			$sheet->setCellValue('D1', 'CANASTAS'); 

			$sheet->getStyle('D1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle('D1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle('D1')->getAlignment()->setVertical('center')->setHorizontal('center');

		}

		$sql = utf8_decode(urldecode($sql));
		
		$promos = $this->post->get_promos($sql);
		
		$cont = 2;
				
		foreach($promos->result() as $promo){
			
			$value = 'A'.$cont;
			
			$sheet->setCellValue($value, $promo->nombre); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'B'.$cont; 
			
			$sheet->setCellValue($value, $promo->email); 
			
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			if (strpos($sql, 'aio_clientes_promo') !== false) {

				$value = 'C'.$cont;
			
				$sheet->setCellValue($value, $promo->telefono); 
				 
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
				
				$value = 'D'.$cont;
				
				$sheet->setCellValue($value, $promo->salon); 

				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
				
				$value = 'E'.$cont;
				
				$sheet->setCellValue($value, $promo->ticket); 

				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			}else if (strpos($sql, 'promo_triples') !== false) {

				$value = 'C'.$cont;
			
				$sheet->setCellValue($value, $promo->fecha); 
				 
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			}else if (strpos($sql, 'promo_canastas') !== false) {

				$value = 'C'.$cont;
			
				$sheet->setCellValue($value, $promo->fecha); 
				 
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

				$value = 'D'.$cont;
				
				$sheet->setCellValue($value, $promo->canastas); 

				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			}
			
			$cont++;
		}
		
		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'promo.xls'; 
		$writer->save($location);
		
		$location = base_url('../promo.xls');
		header("Location: ".$location."");
		die();

	}

	public function tickets_salones_excel($fechaI,$fechaF){

		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet();
		
		$tickets = $this->post->get_tickets_salones($fechaI,$fechaF);
		
		$i = 1;

		$value = 'A'.$i;				
		$sheet->setCellValue($value, "ID");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'B'.$i;				
		$sheet->setCellValue($value, "Creación");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'C'.$i;
		$sheet->setCellValue($value, "Autor");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'D'.$i;
		$sheet->setCellValue($value, "Situación");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'E'.$i;
		$sheet->setCellValue($value, "Salón");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'F'.$i;				
		$sheet->setCellValue($value, "Máquina");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'G'.$i;
		$sheet->setCellValue($value, "Error");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'H'.$i;				
		$sheet->setCellValue($value, "Detalle");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'I'.$i;				
		$sheet->setCellValue($value, "Cierre");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'J'.$i;				
		$sheet->setCellValue($value, "Soluciona");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$i++;
				
		foreach($tickets->result() as $ticket){
			
			$value = 'A'.$i;
			$sheet->setCellValue($value, $ticket->id);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'B'.$i;			
			$sheet->setCellValue($value, $ticket->hora_creacion);			
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$creador = $this->post->get_creador_completo($ticket->creador);
			$value = 'C'.$i;			
			$sheet->setCellValue($value, $creador->nombre); 
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$situacion = $this->post->get_situacion($ticket->situacion);		
			$value = 'D'.$i;			
			$sheet->setCellValue($value, $situacion);			 
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$salon = $this->post->get_salon($ticket->salon);
			$value = 'E'.$i;
			$sheet->setCellValue($value, $salon);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$maquina = $this->post->get_maquina_completo($ticket->maquina);
			$value = 'F'.$i;				
			$sheet->setCellValue($value, $maquina->maquina);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$error = $this->post->get_tipo_error($ticket->tipo_error);
			$value = 'G'.$i;			
			$sheet->setCellValue($value, $error);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$datalle = $this->post->get_detalle_error($ticket->detalle_error);
			$value = 'H'.$i;							
			$sheet->setCellValue($value, $datalle);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			if($ticket->soluciona != 0){
				if(!empty($ticket->fecha_solucion) && $ticket->fecha_solucion != ""){
					$fecha_s = explode("-", $ticket->fecha_solucion);
					$value = 'I'.$i;									
					$sheet->setCellValue($value, $fecha_s[2]."/".$fecha_s[1]."/".$fecha_s[0]);
					$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10); 
					$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
					$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
				}

				$soluciona = $this->post->get_usuario($ticket->soluciona);
				$value = 'J'.$i;							
				$sheet->setCellValue($value, $soluciona->nombre);
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			}else{
				$value = 'I'.$i;							
				$sheet->setCellValue($value, "Sin solucionar");
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

				$value = 'J'.$i;							
				$sheet->setCellValue($value, " ");
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			}
			
			$i++;
		}
		
		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'informes_tickets_salones.xls'; 
		$writer->save($location);
		
		$location = base_url('../informes_tickets_salones.xls');
		header("Location: ".$location."");
		die();
	}
	
	/* Exportar excel tickets */
	public function tickets_excel($fecha,$tipo){
		 
		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet();
		
		$tickets = $this->post->get_tickets_fecha($fecha,$tipo);
		
		$i = 1;

		$value = 'A'.$i;				
		$sheet->setCellValue($value, "ID");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'B'.$i;				
		$sheet->setCellValue($value, "Creación");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'C'.$i;
		$sheet->setCellValue($value, "Autor");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'D'.$i;
		$sheet->setCellValue($value, "Situación");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'E'.$i;
		$sheet->setCellValue($value, "Salón");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'F'.$i;				
		$sheet->setCellValue($value, "Máquina");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'G'.$i;
		$sheet->setCellValue($value, "Error");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'H'.$i;				
		$sheet->setCellValue($value, "Detalle");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'I'.$i;				
		$sheet->setCellValue($value, "Cierre");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$value = 'J'.$i;				
		$sheet->setCellValue($value, "Soluciona");
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(10); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

		$i++;
				
		foreach($tickets->result() as $ticket){
			
			$value = 'A'.$i;
			$sheet->setCellValue($value, $ticket->id);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'B'.$i;			
			$sheet->setCellValue($value, $ticket->hora_creacion);			
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$creador = $this->post->get_creador_completo($ticket->creador);
			$value = 'C'.$i;			
			$sheet->setCellValue($value, $creador->nombre); 
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$situacion = $this->post->get_situacion($ticket->situacion);		
			$value = 'D'.$i;			
			$sheet->setCellValue($value, $situacion);			 
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$salon = $this->post->get_salon($ticket->salon);
			$value = 'E'.$i;
			$sheet->setCellValue($value, $salon);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$maquina = $this->post->get_maquina_completo($ticket->maquina);
			$value = 'F'.$i;				
			$sheet->setCellValue($value, $maquina->maquina);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$error = $this->post->get_tipo_error($ticket->tipo_error);
			$value = 'G'.$i;			
			$sheet->setCellValue($value, $error);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$datalle = $this->post->get_detalle_error($ticket->detalle_error);
			$value = 'H'.$i;							
			$sheet->setCellValue($value, $datalle);
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			if($ticket->soluciona != 0){
				if(!empty($ticket->fecha_solucion) && $ticket->fecha_solucion != ""){
					$fecha_s = explode("-", $ticket->fecha_solucion);
					$value = 'I'.$i;									
					$sheet->setCellValue($value, $fecha_s[2]."/".$fecha_s[1]."/".$fecha_s[0]);
					$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10); 
					$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
					$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
				}

				$soluciona = $this->post->get_usuario($ticket->soluciona);
				$value = 'J'.$i;							
				$sheet->setCellValue($value, $soluciona->nombre);
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			}else{
				$value = 'I'.$i;							
				$sheet->setCellValue($value, "Sin solucionar");
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

				$value = 'J'.$i;							
				$sheet->setCellValue($value, " ");
				$sheet->getStyle($value)->getFont()->setName('Tahoma')->setSize(10);
				$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
				$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			}
			
			$i++;
		}
		
		//exportamos nuestro documento
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'informes_tickets_'.$fecha.'.xls';
		$writer->save($location);
		
		$location = base_url('../informes_tickets_'.$fecha.'.xls');
		header("Location: ".$location."");
		die();

	}
	
	/* Exportar excel recaudaciones */
	public function recaudaciones_excel(){
		 
		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet();
		
		$recaudaciones = $this->post->get_recaudaciones();
		
		$value = 'A1';
			
		$sheet->setCellValue($value, "Fecha"); 

		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$salon = $this->post->get_salon($recaudacion->salon);			
		
		$value = 'B1'; 
		
		$sheet->setCellValue($value, "Salón"); 
		
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');			
		
		$value = 'C1';
		
		$sheet->setCellValue($value, "Total"); 
		 
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$value = 'D1';
		
		$sheet->setCellValue($value, "Pagos"); 

		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$value = 'E1';
		
		$sheet->setCellValue($value, "Neto"); 

		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$cont = 2;
		
		$total_reca = $total_pagos = $total_neto = 0;
				
		foreach($recaudaciones->result() as $recaudacion){
			
			$date = $recaudacion->fecha;
			$d = explode('-', $date);
			$fecha = $d[2]."-".$d[1]."-".$d[0];
			
			$value = 'A'.$cont;
			
			$sheet->setCellValue($value, $fecha); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$salon = $this->post->get_salon($recaudacion->salon);			
			
			$value = 'B'.$cont; 
			
			$sheet->setCellValue($value, $salon); 
			
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');			
			
			$value = 'C'.$cont;
			
			$sheet->setCellValue($value, $recaudacion->reca_total); 
			 
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'D'.$cont;
			
			$sheet->setCellValue($value, $recaudacion->pagos); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'E'.$cont;
			
			$sheet->setCellValue($value, $recaudacion->neto); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$total_reca += $recaudacion->reca_total;
			$total_pagos += $recaudacion->pagos;
			$total_neto += $recaudacion->neto;
			
			$cont++;
		}
		
		$value = 'B'.$cont;
		
		$sheet->setCellValue($value, "Total"); 
		 
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$value = 'C'.$cont;
		
		$sheet->setCellValue($value, $total_reca); 
		 
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$value = 'D'.$cont;
		
		$sheet->setCellValue($value, $total_pagos); 

		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$value = 'E'.$cont;
		
		$sheet->setCellValue($value, $total_neto); 

		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'informes_tickets.xls'; 
		$writer->save($location);
		
		$location = base_url('../informes_tickets.xls');
		header("Location: ".$location."");
		die();

	}

	/* Exportar excel prohibidos */
	public function exportar_prohibidos(){
		set_time_limit(0);
		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet(); 

		$sheet->setCellValue('A1', 'DNI');

		$sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('A1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('A1')->getAlignment()->setVertical('center')->setHorizontal('center'); 
		
		$sheet->setCellValue('B1', 'Fecha'); 
		
		$sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('B1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('B1')->getAlignment()->setVertical('center')->setHorizontal('center');

		if($this->input->post('db') == "1"){
			try{
				$conn = new PDO('mysql:host=149.202.82.135;dbname=GDP-averias; charset=utf8', 'userGDP-averias', 'Eg9ov!80');
		  		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
		  		echo "ERROR: " . $e->getMessage();
			}
		}else{
			try{	
		  		$conn = new PDO('mysql:host=149.202.82.135;dbname=GDP; charset=utf8', 'userGDP', '13579GDP');
		  		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
		  		echo "ERROR: " . $e->getMessage();
			}
		}

		$prohibidos = $conn->prepare('SELECT DNI,FECHA FROM prohibidos');
		$prohibidos->execute();
		$prohibidos_filas = $prohibidos->fetchAll();

		$cont = 2;
		
		foreach($prohibidos_filas as $prohibido){
			$value = 'A'.$cont;
			
			$sheet->setCellValue($value, $prohibido['DNI']); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'B'.$cont;

			$fecha1 = explode(" ", $prohibido['FECHA']);
			$fecha = explode("-", $fecha1[0]);
			
			$sheet->setCellValue($value, $fecha[2]."/".$fecha[1]."/".$fecha[0]); 
			
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');

			$cont++;
		}

		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'prohibidos.xls'; 
		$writer->save($location);
		
		$location = base_url('../prohibidos.xls');
		header("Location: ".$location."");
		die();
	}
	
	/* Exportar excel gasoil */
	public function gasoil_excel($o,$u,$i=NULL,$f=NULL){

		//load the excel library
		$this->load->library('excel');
		 
		$excel = new PHPExcel(); 
		//Usamos el worsheet por defecto 
		$sheet = $excel->getActiveSheet(); 

		$sheet->setCellValue('A1', 'Usuario'); 

		$sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('A1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('A1')->getAlignment()->setVertical('center')->setHorizontal('center'); 
		
		$sheet->setCellValue('B1', 'Vehículo'); 
		
		$sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('B1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('B1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('C1', 'Repostaje'); 
		 
		$sheet->getStyle('C1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('C1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('D1', 'Kilómetros'); 

		$sheet->getStyle('D1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('D1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('D1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('E1', 'Fecha'); 

		$sheet->getStyle('E1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('E1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('E1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('H1', 'Depositos'); 

		$sheet->getStyle('H1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('H1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('H1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$sheet->setCellValue('I1', 'Fecha'); 

		$sheet->getStyle('I1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle('I1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle('I1')->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$cont = 2;
		$litros = 0;
		$km = 0;
		
		if($this->session->userdata('logged_in')['rol'] == 7){
			$repostajes = $this->post->get_repostajes_fecha($o,$u,$i,$f);
		}else{
			$repostajes = $this->post->get_repostajes($o,$u);
		}
		foreach($repostajes->result() as $repostaje){
			$usuario = $this->post->get_usuario($repostaje->usuario);
			$fecha = explode("-", $repostaje->fecha);
			$vehiculo = $this->post->get_vehiculo($repostaje->matricula);
						
			$value = 'A'.$cont;
			
			$sheet->setCellValue($value, $usuario->usuario); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'B'.$cont; 
			
			$sheet->setCellValue($value, $vehiculo->vehiculo." ".$vehiculo->matricula); 
			
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'C'.$cont;
			
			$sheet->setCellValue($value, $repostaje->repostaje); 
			 
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'D'.$cont;
			
			$sheet->setCellValue($value, $repostaje->kilometros); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'E'.$cont;
			
			$sheet->setCellValue($value, $fecha[2]."-".$fecha[1]."-".$fecha[0]); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$cont++;
			$repo = str_replace(",",".",$repostaje->repostaje);
			$litros += (float)$repo;
			$km += $repostaje->kilometros;
		}
		
		$value = 'B'.$cont; 
		
		$sheet->setCellValue($value, "TOTAL"); 
		
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$value = 'C'.$cont;
		
		$sheet->setCellValue($value, $litros); 
		 
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$value = 'D'.$cont;
		
		$sheet->setCellValue($value, $km); 

		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$cont = 2;
		$litros = 0;
		
		if($this->session->userdata('logged_in')['rol'] == 7){
			$depositos = $this->post->get_depositos_fecha($o,$i,$f);
		}else{
			$depositos = $this->post->get_depositos($o);
		}
		foreach($depositos->result() as $deposito){
			
			$fecha = explode("-", $deposito->fecha);
			
			$value = 'H'.$cont;
			
			$sheet->setCellValue($value, $deposito->deposito); 
			 
			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$value = 'I'.$cont;
			
			$sheet->setCellValue($value, $fecha[2]."-".$fecha[1]."-".$fecha[0]); 

			$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
			$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
			$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
			
			$cont++;
			$repo = str_replace(",",".",$deposito->deposito);
			$litros += (float)$repo;
			
		}
		
		$value = 'G'.$cont; 
		
		$sheet->setCellValue($value, "TOTAL"); 
		
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		$value = 'H'.$cont;
		
		$sheet->setCellValue($value, $litros); 
		 
		$sheet->getStyle($value)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
		$sheet->getStyle($value)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
		$sheet->getStyle($value)->getAlignment()->setVertical('center')->setHorizontal('center');
		
		//exportamos nuestro documento 
		$writer = new PHPExcel_Writer_Excel5($excel);
		$location = 'gasoil.xls'; 
		$writer->save($location);
		
		$location = base_url('../gasoil.xls');
		header("Location: ".$location."");
		die();
	}
	
	/* Seccion guardias tecnicos maria adm */
	public function guardias(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 7 && $this->session->userdata('logged_in')['rol'] != 1){
				$this->gestion();
			}else{
				$data = array('title' => '');
				$this->load_view('guardias', $data);
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Get guardias tecnico */
	public function get_guardias_tecnico(){
		$tecnico = $this->input->post('t');
		$mes = $this->input->post('m');
		$anio = $this->input->post('y');
		$incidencias = $this->post->get_guardias_tecnico($tecnico,$mes,$anio);
		
		$html_guardias = '';
		$html_guardias .= '<h4>Incidencias solucionadas</h4>
  										 <hr/>';
  	
  		if($incidencias->num_rows() == 0){
  			$html_guardias .= '<p style="font-weight: bold">No hay resultados</p>';
  		}else{
  			$html_guardias .= '<table class="table tabla_incidencias">
													<thead>
														<tr>
															<th class="th_tabla">Nº</th>
															<th class="th_tabla">Operadora</th>
															<th class="th_tabla">Salón</th>
															<th class="th_tabla">Fecha solución</th>
															<th class="th_tabla">Fecha creación</th>
															<th class="th_tabla">Creada por</th>
															<th class="th_tabla">Ver incidencia</th>
														</tr>
													</thead>
													<tbody class="tabla_agrupados">';

			$html_destinos = '';
			$destinos = $this->post->get_destinos_guardias();
			foreach($destinos->result() as $destino){
				$html_destinos .= '<p style="font-weight: bold; height: 50px; text-align: left"><span style="vertical-align: -webkit-baseline-middle">'.$destino->destino.' ('.$destino->euros.' €)</span><span style="float: right; width: 100px"><input placeholder="Cantidad" class="form-control cantidades_km" type="text" name="'.$destino->euros.'" id="cantidad"/></span></p>';
			}

			$i = 0;						
			foreach($incidencias->result() as $incidencia){
				$i++;
				if($incidencia->operadora == 0){
					$op = "Desconocida";
				}else{
					$op = $this->post->get_operadoras_rol_2($incidencia->operadora);
					$operadora = $op->row();
					$op = $operadora->operadora;
				}
				
				if($incidencia->salon == 0){
					$salon = "Desconocido";
				}else{
					$salon = $this->post->get_salon($incidencia->salon);
				}
				
				$fecha_solucion = explode('-', $incidencia->fecha_solucion);
				
				$fecha_creacion = explode('-', $incidencia->fecha_creacion);
				
				$creador = $this->post->get_creador_completo($incidencia->creador);
				
				if($creador){
					$nombre = $creador->nombre;
				}else{
					$nombre = "Desconocido";
				}
				
				$html_guardias .= '<tr>
														<td>'.$incidencia->id.'</td>
														<td>'.$op.'</td>
														<td>'.$salon.'</td>
														<td>'.$fecha_solucion[2].'/'.$fecha_solucion[1].'/'.$fecha_solucion[0].' '.$incidencia->hora_solucion.'</td>
														<td>'.$fecha_creacion[2].'/'.$fecha_creacion[1].'/'.$fecha_creacion[0].' '.$incidencia->hora_creacion.'</td>
														<td>'.$nombre.'</td>
														<td>
															<a style="padding: 2px 4px; margin: 0 4px;" target="_blank" href="'.base_url('ver_historial/'.$incidencia->id.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
														</td>
													</tr>';
				
			}
			
			$html_guardias .= '</tbody>
												</table>';
												
			$html_guardias .= '<p style="font-weight: bold">Total: '.$i.'</p>';

			$html_guardias .= '<div class="col-md-12" style="margin: 0 auto; text-align: center">
								<div style="border: 1px solid #ccc; display: inline-block; padding: 1%; border-radius: 5px; box-shadow: 2px 2px #ccc; width: 400px; margin-top: 20px; margin-right: 2%">
									<h4>Destinos</h4>
									<hr>
									'.$html_destinos.'
								</div>
								<div style="border: 1px solid #ccc; display: inline-block; padding: 1%; border-radius: 5px; box-shadow: 2px 2px #ccc; width: 400px; margin-top: 20px; margin-left: 2%">
									<h4>Total</h4>
									<hr>
									<p style="font-weight: bold; height: 50px; text-align: left"><span style="vertical-align: -webkit-baseline-middle">Dietas:</span><span style="float: right; width: 100px"><input class="form-control" type="text" name="dietas" id="dietas" inputmode="numeric"/></span></p>
									<p style="font-weight: bold; height: 50px; text-align: left"><span style="vertical-align: -webkit-baseline-middle">Kilómetros:</span><span style="float: right; width: 100px"><input class="form-control" type="text" name="km" id="km" inputmode="numeric"/></span></p>
									<p style="font-weight: bold; height: 50px; text-align: left"><span style="vertical-align: -webkit-baseline-middle">Total:</span><span style="float: right; width: 100px"><input class="form-control" type="text" name="total" id="total"/></span></p>
									<a href="#" class="btn btn-info" id="imprimir_guardias" style="display: block">Imprimir</a>
								</div>
							   </div>';
  		}

		echo $html_guardias;
	}

	public function imprimir_guardias(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 7 && $this->session->userdata('logged_in')['rol'] != 1){
				$this->gestion();
			}else{
				$tecnico = $this->post->get_usuario($this->uri->segment(3));
				$mes = $this->post->switch_mes($this->uri->segment(4));
				$anio = $this->uri->segment(5);
				$dietas = $this->uri->segment(6);
				$km = $this->uri->segment(7);
				$total = $dietas + $km;
				$vilanueva = $this->uri->segment(8);
				$solana = $this->uri->segment(9);
				$granada = $this->uri->segment(10);
				$almeria = $this->uri->segment(11);
				$ejido = $this->uri->segment(12);

				$fechas = array();
				$tratas = array();
				$incidencias = $this->post->get_guardias_tecnico($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5));
				foreach($incidencias->result() as $incidencia){
					array_push($fechas, $incidencia->fecha_solucion);
					$ediciones = $this->post->get_ediciones($incidencia->id);
					if($ediciones->num_rows() > 0){
						foreach($ediciones->result() as $edicion){
							if($edicion->situacion == 6){
								$edicion_trata_desc = stripslashes($edicion->trata_desc);
								array_push($tratas, $edicion_trata_desc);
							}
						}
					}
				}
				
				$data = array('title' => 'Administracion', 'tecnico' => $tecnico->nombre, 'mes' => $mes, 'anio' => $anio, 'dietas' => $dietas, 'km' => $km, 'total' => $total, 'vilanueva' => $vilanueva, 'solana' => $solana, 'granada' => $granada, 'almeria' => $almeria, 'ejido' => $ejido,'fechas' => $fechas, 'tratas' => $tratas);
				$this->load->view('imprimir_guardias', $data);
				
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		} 
	}
	
	/* Sección informes recaudaciones */
	public function informes_recaudaciones_salones(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 7){
				$this->gestion();
			}else{
				$recaudaciones = $this->post->get_recaudaciones_salones_informes();
				$html_tickets = '';
  			$detalle = '<h3 style="font-size: 20px">Detalle recaudaciones</h3>	
	  								<hr/>';
  										
				if($recaudaciones->num_rows() == 0){
		  		$html_tickets .= '<p style="font-weight: bold">No hay resultados</p>';
		  	}else{
		  		$html_tickets .= '<table class="table tabla_incidencias" style="margin-bottom: 0">
															<thead>
																<tr>
																	<th class="th_tabla">Salón</th>
																	<th class="th_tabla">Total</th>																	
																	<th class="th_tabla">Parcial</th>
																	<th class="th_tabla">Detalle</th>
																</tr>
															</thead>
															<tbody class="tabla_agrupados">';
												
					foreach($recaudaciones->result() as $recaudacion){
						
						$ultima_reca = $this->post->get_ultima_recaudacion_salon($recaudacion->salon);						
						$date = $ultima_reca->fecha;
						$d = explode('-', $date);
						$fecha = $d[2]."-".$d[1]."-".$d[0];
						
						if($recaudacion->salon == 0){
							$salon = "Desconocido";
						}else{
							$salon = $this->post->get_salon($recaudacion->salon);
						}
						
						$html_tickets .= '<tr style="font-weight: bold">
																<td>'.$salon.'</td>														
																<td style="color: #2aabd2">'.$ultima_reca->neto.'€</td>';
						
						$parcial_reca = $this->post->get_parcial_recaudacion_salon($recaudacion->salon,$ultima_reca->id);
						
						$parcial = $ultima_reca->neto - $parcial_reca->neto;
						
						if($parcial >= 0){
							$color = '#449d44';
						}else{
							$color = '#b21a30';
						}
						
						$html_tickets .= '<td style="color: '.$color.'">'.$parcial.'€</td>
															<td>
																<a id="'.$recaudacion->salon.'" style="padding: 2px 4px; margin: 0;" href="#" type="button" class="btn btn-info ver_detalle" alt=Ver recaudación salón" title=Ver recaudación salón"><i class="fa fa-eye"></i></a>
															</td>';
																
						$html_tickets .= '</tr>';
						
						$detalle .= '<table id="tabla_'.$recaudacion->salon.'" class="table tabla_incidencias tablas_detalle" style="display: none">
	  													<thead>
																<tr>
																	<th class="th_tabla">Máquina</th>
																	<th class="th_tabla">Total</th>																	
																	<th class="th_tabla">Parcial</th>
																</tr>
															</thead>
													 		<tbody class="tabla_agrupados">';
	  										 
	  				$maquinas = $this->post->get_maquinas_ultima_recaudacion($recaudacion->salon,$ultima_reca->id);
	  				foreach($maquinas->result() as $maquina){
	  					$anterior_maquina = $this->post->get_maquinas_anterior_recaudacion($maquina->maquina,$ultima_reca->id);
	  					$nombre_maquina = $this->post->get_maquina_completo($maquina->maquina);
	  					if(isset($nombre_maquina)){
	  						$maquina_nombre = $nombre_maquina->maquina;
	  					}else{
	  						$maquina_nombre = "Desconocido";
	  					}
	  					$parcial = $maquina->neto - $anterior_maquina->neto;
	  					if($parcial >= 0){
								$color = '#449d44';
							}else{
								$color = '#b21a30';
							}
	  					$detalle .= '<tr style="font-weight: bold">
										 						<td>'.$maquina_nombre.'</td>
										 						<td style="color: #2aabd2">'.$maquina->neto.'</td>
										 						<td style="color: '.$color.'">'.$parcial.'</td>
										 				</tr>';
	  				}
	  				
	  				$detalle .= '</tbody>
										 </table>';
						
					}
					
					$html_tickets .= '</tbody>
														</table>';
														
		  	}
		  	
		  	$data = array('title' => 'Administracion', 'recaudaciones' => $html_tickets, 'recaudaciones_detalle' => $detalle);
				$this->load_view('informes_recaudaciones', $data);
				
				/*
				$recaudaciones = $this->post->get_recaudaciones_salones_informes();
				$html_tickets = '';
  										
				if($recaudaciones->num_rows() == 0){
		  		$html_tickets .= '<p style="font-weight: bold">No hay resultados</p>';
		  	}else{
		  		$html_tickets .= '<table class="table tabla_incidencias">
															<thead>
																<tr>
																	<th class="th_tabla">Fecha</th>
																	<th class="th_tabla">Salón</th>																	
																	<th class="th_tabla">Recaudación</th>
																	<th class="th_tabla">Comentarios</th>
																</tr>
															</thead>
															<tbody class="tabla_agrupados">';
					$i = 0;								
					foreach($recaudaciones->result() as $recaudacion){
						$i++;
						
						$date = $recaudacion->fecha;
						$d = explode('-', $date);
						$fecha = $d[2]."-".$d[1]."-".$d[0];
						
						if($recaudacion->salon == 0){
							$salon = "Desconocido";
						}else{
							$salon = $this->post->get_salon($recaudacion->salon);
						}
						
						$html_tickets .= '<tr>
																<td>'.$fecha.'</td>
																<td>'.$salon.'</td>															
																<td>'.$recaudacion->neto.'€</td>
																<td>'.$recaudacion->comentarios.'€</td>
															</tr>';
						
					}
					
					$html_tickets .= '</tbody>
														</table>';
		  	}
				
				$data = array('title' => 'Administracion', 'recaudaciones' => $html_tickets);
				$this->load_view('informes_recaudaciones', $data);
				*/
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function informes_recaudaciones(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 7){
				$this->gestion();
			}else{
				$recaudaciones = $this->post->get_recaudaciones();
				$html_tickets = '';
				$html_tickets .= '<h4>
												Recaudaciones
												<a style="float: right" href="'.base_url("recaudaciones_excel").'" class="btn btn-info" target="_blank">Exportar Excel</a>	
											</h4>
  										<hr/>';
  										
				if($recaudaciones->num_rows() == 0){
		  		$html_tickets .= '<p style="font-weight: bold">No hay resultados</p>';
		  	}else{
		  		$html_tickets .= '<table class="table tabla_incidencias">
															<thead>
																<tr>
																	<th class="th_tabla">Fecha</th>
																	<th class="th_tabla">Salón</th>																	
																	<th class="th_tabla">Recaudación</th>
																	<th class="th_tabla">Pagos</th>
																	<th class="th_tabla">Neto</th>
																	<th class="th_tabla">Ver recaudación</th>
																</tr>
															</thead>
															<tbody class="tabla_agrupados">';
					$i = 0;								
					foreach($recaudaciones->result() as $recaudacion){
						$i++;
						
						$date = $recaudacion->fecha;
						$d = explode('-', $date);
						$fecha = $d[2]."-".$d[1]."-".$d[0];
						
						if($recaudacion->salon == 0){
							$salon = "Desconocido";
						}else{
							$salon = $this->post->get_salon($recaudacion->salon);
						}
						
						$html_tickets .= '<tr>
																<td>'.$fecha.'</td>
																<td>'.$salon.'</td>															
																<td>'.$recaudacion->reca_total.'€</td>
																<td>'.$recaudacion->pagos.'€</td>
																<td>'.$recaudacion->neto.'€</td>
																<td>
																	<a style="padding: 2px 4px; margin: 0 4px;" target="_blank" href="'.base_url('files/pdf_recaudaciones/'.$recaudacion->id.'.pdf').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
																</td>
															</tr>';
						
					}
					
					$html_tickets .= '</tbody>
														</table>';
														
					$html_tickets .= '<p style="font-weight: bold">Total: '.$i.'</p>';
		  	}
				
				$data = array('title' => 'Administracion', 'recaudaciones' => $html_tickets);
				$this->load_view('informes_recaudaciones', $data);
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	/* Sección tickets/fecha */
	public function informes_tickets(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] != 7 && $this->session->userdata('logged_in')['rol'] != 1){
				$this->gestion();
			}else{
				$data = array('title' => '');
				$this->load_view('informes_tickets', $data);
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Get resumen turno */
	public function get_tickets_turno(){
		$fecha = $this->input->post('d');
		$d = explode('/', $fecha);
		$date = $d[2]."-".$d[1]."-".$d[0];

		$html_turnos = '';
		$dayofweek = date('w', strtotime($date));
		$days = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');

		$html_turnos .= '<h4>Turno '.$days[$dayofweek].' '.$fecha.'</h4>
						<hr/>
						<div style="border: 1px solid rgb(49, 112, 143); border-radius: 5px; background: rgb(217, 237, 247); padding: 10px 10px 5px; color: rgb(151 63 0); margin-bottom: 16px; display: block;">
							<h5 style="font-weight: bold;">Resumen</h5>';

		if($dayofweek != 6 && $dayofweek != 0){
			// Entre semana
			
			// Empleado mañana
			$empleado = $this->post->get_empleado_turno_mañana($date);
			if($empleado->num_rows() == 0){
		  		$html_turnos .= '<p style="font-weight: bold; width: 16%; display: inline-block;">No hay resultados</p>';
		  	}else{
		  		foreach($empleado->result() as $e){
		  			$nombre = $this->post->get_usuario($e->creador);
		  			$html_turnos .= '<p style="font-weight: bold; width: 16%; display: inline-block;"><span style="color: rgb(30 106 0)">'.$nombre->nombre.' (mañana)</span><br/>';

		  			// Incidencias SAT ADM
		  			$incidencias_sat = $this->post->get_incidencias_sat_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'SAT: '.$incidencias_sat->num_rows().'<br/>';

		  			// Incidencias SAT Operadora
		  			$incidencias_op = $this->post->get_incidencias_op_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'SAT Operadora: '.$incidencias_op->num_rows().'<br/>';;

		  			// Incidencias Caducar
		  			$incidencias_cad = $this->post->get_incidencias_cad_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'Transferencias: '.$incidencias_cad->num_rows().'<br/>';

		  			// Incidencias Revisar
		  			$incidencias_rev = $this->post->get_incidencias_rev_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'Revisar: '.$incidencias_rev->num_rows().'<br/>';

		  			// Incidencias Tratamiento
		  			$incidencias_trata = $this->post->get_incidencias_trata_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'Tratamiento: '.$incidencias_trata->num_rows().'<br/>';

		  			// Incidencias Euskaltel
		  			$incidencias_eusk = $this->post->get_incidencias_eusk_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'Euskaltel: '.$incidencias_eusk->num_rows().'<br/>';

		  			// Incidencias Kirol
		  			$incidencias_kirol = $this->post->get_incidencias_kirol_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'Kirol: '.$incidencias_kirol->num_rows().'<br/>';

		  			// Incidencias LLamar
		  			$incidencias_lla = $this->post->get_incidencias_lla_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'LLamar: '.$incidencias_lla->num_rows().'<br/>';

		  			// Incidencias Responsable
		  			$incidencias_resp = $this->post->get_incidencias_resp_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'Responsable: '.$incidencias_resp->num_rows().'<br/>';

		  			// Incidencias Paquetería
		  			$incidencias_paq = $this->post->get_incidencias_paq_turno_mañana($date,$e->creador);
		  			$html_turnos .= 'Paquetería: '.$incidencias_paq->num_rows().'<br/>';

		  			$total = $incidencias_sat->num_rows() + $incidencias_op->num_rows() + $incidencias_cad->num_rows() + $incidencias_rev->num_rows() + $incidencias_trata->num_rows() + $incidencias_eusk->num_rows() + $incidencias_kirol->num_rows() + $incidencias_lla->num_rows() + $incidencias_resp->num_rows() + $incidencias_paq->num_rows();
		  			$html_turnos .= 'Total: '.$total.'<br/>';
		  		}

		  		$html_turnos .= '</p>';

		  		$empleado = $this->post->get_empleado_turno_tarde($date);
				if($empleado->num_rows() == 0){
			  		$html_turnos .= '<p style="font-weight: bold; width: 16%; display: inline-block;">No hay resultados</p>';
			  	}else{
			  		foreach($empleado->result() as $e){
				  		$nombre = $this->post->get_usuario($e->creador);
			  			$html_turnos .= '<p style="font-weight: bold; width: 16%; display: inline-block;"><span style="color: rgb(30 106 0)">'.$nombre->nombre.' (tarde)</span><br/>';

			  			// Incidencias SAT ADM
			  			$incidencias_sat = $this->post->get_incidencias_sat_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'SAT: '.$incidencias_sat->num_rows().'<br/>';

			  			// Incidencias SAT Operadora
			  			$incidencias_op = $this->post->get_incidencias_op_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'SAT Operadora: '.$incidencias_op->num_rows().'<br/>';;

			  			// Incidencias Caducar
			  			$incidencias_cad = $this->post->get_incidencias_cad_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'Transferencias: '.$incidencias_cad->num_rows().'<br/>';

			  			// Incidencias Revisar
			  			$incidencias_rev = $this->post->get_incidencias_rev_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'Revisar: '.$incidencias_rev->num_rows().'<br/>';

			  			// Incidencias Tratamiento
			  			$incidencias_trata = $this->post->get_incidencias_trata_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'Tratamiento: '.$incidencias_trata->num_rows().'<br/>';

			  			// Incidencias Euskaltel
			  			$incidencias_eusk = $this->post->get_incidencias_eusk_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'Euskaltel: '.$incidencias_eusk->num_rows().'<br/>';

			  			// Incidencias Kirol
			  			$incidencias_kirol = $this->post->get_incidencias_kirol_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'Kirol: '.$incidencias_kirol->num_rows().'<br/>';

			  			// Incidencias LLamar
			  			$incidencias_lla = $this->post->get_incidencias_lla_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'LLamar: '.$incidencias_lla->num_rows().'<br/>';

			  			// Incidencias Responsable
			  			$incidencias_resp = $this->post->get_incidencias_resp_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'Responsable: '.$incidencias_resp->num_rows().'<br/>';

			  			// Incidencias Paquetería
			  			$incidencias_paq = $this->post->get_incidencias_paq_turno_tarde($date,$e->creador);
			  			$html_turnos .= 'Paquetería: '.$incidencias_paq->num_rows().'<br/>';

			  			$total = $incidencias_sat->num_rows() + $incidencias_op->num_rows() + $incidencias_cad->num_rows() + $incidencias_rev->num_rows() + $incidencias_trata->num_rows() + $incidencias_eusk->num_rows() + $incidencias_kirol->num_rows() + $incidencias_lla->num_rows() + $incidencias_resp->num_rows() + $incidencias_paq->num_rows();
			  			$html_turnos .= 'Total: '.$total.'<br/>';
			  		}
			  	}
		  		
		  		$html_turnos .= '</div>';

		  		$html_turnos .= '<div style="border: 1px solid #c3e6cb; border-radius: 5px; background: #d4edda; padding: 10px 10px 5px; color: #080085; margin-bottom: 16px; display: block;">
						<h5 style="font-weight: bold;">Detalle</h5>';

				$empleado = $this->post->get_empleado_turno_mañana($date);
				foreach($empleado->result() as $e){
					$nombre = $this->post->get_usuario($e->creador);
		  			$html_turnos .= '<p style="font-weight: bold; width: 100%;"><span style="color: rgb(151 63 0)">'.$nombre->nombre.' (mañana)</span><br/>';

		  			// Incidencias
		  			$incidencias = $this->post->get_incidencias_turno_mañana($date,$e->creador);
		  			if($incidencias->num_rows() == 0){
				  		$html_turnos .= '<p style="font-weight: bold;">No hay resultados</p>';
				  	}else{
			  			foreach($incidencias->result() as $incidencia){
			  				$html_turnos .= '<span style="border: 1px solid #57705d; border-radius: 5px; display: inline-block; padding: 5px; width: 15%; margin: 5px; vertical-align: top">';
			  				$html_turnos .= '<span style="color: #000">ID: </span>#'.$incidencia->id_ticket.' ';
			  				$situacion = $this->post->get_situacion($incidencia->situacion);
			  				$html_turnos .= '<span style="color: #000">Situación: </span>'.$situacion.'<br/>';
			  				$ticket = $this->post->get_ticket($incidencia->id_ticket);
			  				$salon = $this->post->get_salon($ticket->salon);
			  				$html_turnos .= '<span style="color: #000">Salón: </span>'.$salon.' ';
			  				if($ticket->situacion == 6){			  					
			  					$html_turnos .= '<span style="color: #000">Solucionada: </span>SI<br/>';
			  				}else{
			  					$html_turnos .= '<span style="color: #000">Solucionada: </span>NO<br/>';
			  				}
			  				$html_turnos .= '"'.$ticket->error_desc.'"';
			  				$html_turnos .= '</span>';
			  			}

		  				$html_turnos .= '</p>';
		  			}
				}

				$empleado = $this->post->get_empleado_turno_tarde($date);
				foreach($empleado->result() as $e){
					$nombre = $this->post->get_usuario($e->creador);
		  			$html_turnos .= '<p style="font-weight: bold; width: 100%;"><span style="color: rgb(151 63 0)">'.$nombre->nombre.' (tarde)</span><br/>';

		  			// Incidencias
		  			$incidencias = $this->post->get_incidencias_turno_tarde($date,$e->creador);
		  			if($incidencias->num_rows() == 0){
				  		$html_turnos .= '<p style="font-weight: bold;">No hay resultados</p>';
				  	}else{
			  			foreach($incidencias->result() as $incidencia){
			  				$html_turnos .= '<span style="border: 1px solid #57705d; border-radius: 5px; display: inline-block; padding: 5px; width: 15%; margin: 5px; vertical-align: top">';
			  				$html_turnos .= '<span style="color: #000">ID: </span>#'.$incidencia->id_ticket.' ';
			  				$situacion = $this->post->get_situacion($incidencia->situacion);
			  				$html_turnos .= '<span style="color: #000">Situación: </span>'.$situacion.'<br/>';
			  				$ticket = $this->post->get_ticket($incidencia->id_ticket);
			  				$salon = $this->post->get_salon($ticket->salon);
			  				$html_turnos .= '<span style="color: #000">Salón: </span>'.$salon.' ';
			  				if($ticket->situacion == 6){
			  					$html_turnos .= '<span style="color: #000">Solucionada: </span>SI<br/>';
			  				}else{
			  					$html_turnos .= '<span style="color: #000">Solucionada: </span>NO<br/>';
			  				}
			  				$html_turnos .= '"'.$ticket->error_desc.'"';
			  				$html_turnos .= '</span>';
			  			}

			  			$html_turnos .= '</p>';
			  		}
				}
				
				$html_turnos .= '</div>';
		  	}
		}else{
			// Findes
			$empleado = $this->post->get_empleado_turno_finde($date);
			if($empleado->num_rows() == 0){
		  		$html_turnos .= '<p style="font-weight: bold; width: 16%; display: inline-block;">';
		  	}else{
		  		foreach($empleado->result() as $e){
		  			$nombre = $this->post->get_usuario($e->creador);
		  			$html_turnos .= '<p style="font-weight: bold; width: 16%; display: inline-block;"><span style="color: rgb(30 106 0)">'.$nombre->nombre.' (finde)</span><br/>';

		  			// Incidencias SAT ADM
		  			$incidencias_sat = $this->post->get_incidencias_sat_turno_finde($date,$e->creador);
		  			$html_turnos .= 'SAT: '.$incidencias_sat->num_rows().'<br/>';

		  			// Incidencias SAT Operadora
		  			$incidencias_op = $this->post->get_incidencias_op_turno_finde($date,$e->creador);
		  			$html_turnos .= 'SAT Operadora: '.$incidencias_op->num_rows().'<br/>';;

		  			// Incidencias Caducar
		  			$incidencias_cad = $this->post->get_incidencias_cad_turno_finde($date,$e->creador);
		  			$html_turnos .= 'Transferencias: '.$incidencias_cad->num_rows().'<br/>';

		  			// Incidencias Revisar
		  			$incidencias_rev = $this->post->get_incidencias_rev_turno_finde($date,$e->creador);
		  			$html_turnos .= 'Revisar: '.$incidencias_rev->num_rows().'<br/>';

		  			// Incidencias Tratamiento
		  			$incidencias_trata = $this->post->get_incidencias_trata_turno_finde($date,$e->creador);
		  			$html_turnos .= 'Tratamiento: '.$incidencias_trata->num_rows().'<br/>';

		  			// Incidencias Euskaltel
		  			$incidencias_eusk = $this->post->get_incidencias_eusk_turno_finde($date,$e->creador);
		  			$html_turnos .= 'Euskaltel: '.$incidencias_eusk->num_rows().'<br/>';

		  			// Incidencias Kirol
		  			$incidencias_kirol = $this->post->get_incidencias_kirol_turno_finde($date,$e->creador);
		  			$html_turnos .= 'Kirol: '.$incidencias_kirol->num_rows().'<br/>';

		  			// Incidencias LLamar
		  			$incidencias_lla = $this->post->get_incidencias_lla_turno_finde($date,$e->creador);
		  			$html_turnos .= 'LLamar: '.$incidencias_lla->num_rows().'<br/>';

		  			// Incidencias Responsable
		  			$incidencias_resp = $this->post->get_incidencias_resp_turno_finde($date,$e->creador);
		  			$html_turnos .= 'Responsable: '.$incidencias_resp->num_rows().'<br/>';

		  			// Incidencias Paquetería
		  			$incidencias_paq = $this->post->get_incidencias_paq_turno_finde($date,$e->creador);
		  			$html_turnos .= 'Paquetería: '.$incidencias_paq->num_rows().'<br/>';

		  			$total = $incidencias_sat->num_rows() + $incidencias_op->num_rows() + $incidencias_cad->num_rows() + $incidencias_rev->num_rows() + $incidencias_trata->num_rows() + $incidencias_eusk->num_rows() + $incidencias_kirol->num_rows() + $incidencias_lla->num_rows() + $incidencias_resp->num_rows() + $incidencias_paq->num_rows();
		  			$html_turnos .= 'Total: '.$total.'<br/>';
		  		}

		  		$html_turnos .= '</p>';

		  		$html_turnos .= '</div>';

		  		$html_turnos .= '<div style="border: 1px solid #c3e6cb; border-radius: 5px; background: #d4edda; padding: 10px 10px 5px; color: #080085; margin-bottom: 16px; display: block;">
						<h5 style="font-weight: bold;">Detalle</h5>';

				$empleado = $this->post->get_empleado_turno_finde($date);
				foreach($empleado->result() as $e){
					$nombre = $this->post->get_usuario($e->creador);
		  			$html_turnos .= '<p style="font-weight: bold; width: 100%;"><span style="color: rgb(151 63 0)">'.$nombre->nombre.' (finde)</span><br/>';

		  			// Incidencias
		  			$incidencias = $this->post->get_incidencias_turno_finde($date,$e->creador);
		  			if($incidencias->num_rows() == 0){
				  		$html_turnos .= '<p style="font-weight: bold;">No hay resultados</p>';
				  	}else{
			  			foreach($incidencias->result() as $incidencia){
			  				$html_turnos .= '<span style="border: 1px solid #57705d; border-radius: 5px; display: inline-block; padding: 5px; width: 15%; margin: 5px; vertical-align: top">';
			  				$html_turnos .= '<span style="color: #000">ID: </span>#'.$incidencia->id_ticket.' ';
			  				$situacion = $this->post->get_situacion($incidencia->situacion);
			  				$html_turnos .= '<span style="color: #000">Situación: </span>'.$situacion.'<br/>';
			  				$ticket = $this->post->get_ticket($incidencia->id_ticket);
			  				$salon = $this->post->get_salon($ticket->salon);
			  				$html_turnos .= '<span style="color: #000">Salón: </span>'.$salon.' ';
			  				if($ticket->situacion == 6){
			  					$html_turnos .= '<span style="color: #000">Solucionada: </span>SI<br/>';
			  				}else{
			  					$html_turnos .= '<span style="color: #000">Solucionada: </span>NO<br/>';
			  				}
			  				$html_turnos .= '"'.$ticket->error_desc.'"';
			  				$html_turnos .= '</span>';
			  			}

			  			$html_turnos .= '</p>';
			  		}
				}
			}

			$html_turnos .= '</div>';
		}

		echo $html_turnos;
	}

	/* Get tickets/salones */
	public function get_tickets_salones(){
		$fechaI = $this->input->post('d1');
		$fechaF = $this->input->post('d2');
		$d1 = explode('/', $fechaI);
		$date1 = $d1[2]."-".$d1[1]."-".$d1[0];
		$d2 = explode('/', $fechaF);
		$date2 = $d2[2]."-".$d2[1]."-".$d2[0];
		$incidencias = $this->post->get_tickets_salones($date1,$date2);

		$html_tickets = '';
		$html_tickets .= '<h4>
							Incidencias salones del '.$fechaI.' al '.$fechaF.' 
							<a style="float: right" href="'.base_url("tickets_salones_excel/".$date1."/".$date2."").'" class="btn btn-info" target="_blank">Exportar Excel</a>	
						</h4>
						<hr/>';

		if($incidencias->num_rows() == 0){
	  		$html_tickets .= '<p style="font-weight: bold">No hay resultados</p>';
	  	}else{
	  		$html_tickets .= '<table class="table tabla_incidencias">
								<thead>
									<tr>
										<th class="th_tabla" style="width: 1%">Nº</th>
										<th class="th_tabla">Fecha Creación</th>
										<th class="th_tabla" style="width: 5%">Autor</th>
										<th class="th_tabla" style="width: 5%">Situación</th>
										<th class="th_tabla">Salón</th>
										<th class="th_tabla" style="width: 5%">Máquina</th>
										<th class="th_tabla" style="width: 5%">Tipo error</th>
										<th class="th_tabla">Detalle error</th>
										<th class="th_tabla">Cierre</th>
										<th class="th_tabla" style="width: 5%">Soluciona</th>
										<th class="th_tabla">Ver incidencia</th>
									</tr>
								</thead>
								<tbody class="tabla_agrupados">';

			$i = 0;								
			foreach($incidencias->result() as $incidencia){			
				if($incidencia->salon == 0){
					$salon = "Desconocido";
				}else{
					$salon = $this->post->get_salon($incidencia->salon);
				}

				$fecha_c = explode("-", $incidencia->fecha_creacion);
				$fecha_creacion = $fecha_c[2]."-".$fecha_c[1]."-".$fecha_c[0]." ".$incidencia->hora_creacion;
				$creador = $this->post->get_usuario($incidencia->creador);				
				$situacion = $this->post->get_situacion($incidencia->situacion);
				$maquina = $this->post->get_maquina($incidencia->maquina);
				$tipo_error = $this->post->get_tipo_error($incidencia->tipo_error);
				$detalle_error = $this->post->get_detalle_error($incidencia->detalle_error);

				if($incidencia->soluciona != 0){
					if(!empty($incidencia->fecha_solucion) && $incidencia->fecha_solucion != ""){
						$fecha_s = explode("-", $incidencia->fecha_solucion);
						$fecha_solucion = $fecha_s[2]."-".$fecha_s[1]."-".$fecha_s[0]." ".$incidencia->hora_solucion;						
					}else{
						$fecha_solucion = "";
					}
					$soluciona = $this->post->get_usuario($incidencia->soluciona);							
					$soluciona = $soluciona->nombre;
				}else{
					$fecha_solucion = "";
					$soluciona = "";
				}
				
				$html_tickets .= '<tr>
									<td style="width: 1%">'.$incidencia->id.'</td>
									<td>'.$fecha_creacion.'</td>
									<td style="width: 5%">'.$creador->nombre.'</td>
									<td style="width: 5%">'.$situacion.'</td>									
									<td>'.$salon.'</td>
									<td style="width: 5%">'.$maquina.'</td>
									<td style="width: 5%">'.$tipo_error.'</td>
									<td>'.$detalle_error.'</td>
									<td>'.$fecha_solucion.'</td>
									<td style="width: 5%">'.$soluciona.'</td>
									<td>
										<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
									</td>
								</tr>';
				$i++;
			}
			
			$html_tickets .= '</tbody>
						</table>';
												
			$html_tickets .= '<p style="font-weight: bold">Total: '.$i.'';

			$html_tickets .= '</p>';
  		}  	
		
		echo $html_tickets;
	}
	
	/* Get tickets/fecha */
	public function get_tickets_fecha(){
		$fecha = $this->input->post('d');
		$tipo = $this->input->post('t');
		$d = explode('/', $fecha);
		$date = $d[2]."-".$d[1]."-".$d[0];
		$incidencias = $this->post->get_tickets_fecha($date,$tipo);
		if($tipo == 0){
			$agrupadas = $this->post->get_tickets_fecha_agrupados_creador($date);
			$salones = $this->post->get_tickets_fecha_agrupados_salon($date);
		}

		$html_tickets = '';
		$html_tickets .= '<h4>
							Incidencias '.$fecha.'
							<a style="float: right" href="'.base_url("tickets_excel/".$date."/".$tipo."").'" class="btn btn-info" target="_blank">Exportar Excel</a>	
						</h4>
						<hr/>';
  	
	  	if($incidencias->num_rows() == 0){
	  		$html_tickets .= '<p style="font-weight: bold">No hay resultados</p>';
	  	}else{
	  		$i = 0;								
			foreach($incidencias->result() as $incidencia){
				$i++;
			}
	  		$html_tickets .= '<p style="font-weight: bold">Total: '.$i.' ';

	  		if($tipo == 0){
				$html_tickets .= '(';
				foreach($agrupadas->result() as $agrupada){
					$creador = $this->post->get_creador_completo($agrupada->creador);
					$html_tickets .= ' '.$creador->nombre.":".$agrupada->total.' ';
				}
				$html_tickets .= ')';

				$html_tickets .= '</p>';

				$html_tickets .= '<div style="width: 100%; display: block; border: 1px solid #000; padding: 5px; margin-bottom: 1%">';

				$html_tickets .= '<p style="font-weight: bold">Por salón</p>';

				$html_tickets .= '<div style="width: 20%; display: inline-block">';

				$j = 0;
				foreach($salones->result() as $salon){
					$j++;

					if($j%4 == 0){
						$html_tickets .= '</div>';
						$html_tickets .= '<div style="width: 20%; display: inline-block">';
					}
					$salon_completo = $this->post->get_salon_completo($salon->salon);
					$html_tickets .= "<p style='font-weight: bold'>".$salon_completo->salon.": ".$salon->total;

					$maquinas = $this->post->get_tickets_fecha_agrupados_salon_maquina($date,$salon->salon);

					if($maquinas->num_rows() != 0){
						$html_tickets .= " (";
						$z = 0;
						foreach($maquinas->result() as $maquina){
							$z++;
							$maquina_completo = $this->post->get_maquina_completo($maquina->maquina);
							$html_tickets .= $maquina_completo->maquina;

							if($z != $maquinas->num_rows()){
								$html_tickets .= ", ";
							}
						}
						$html_tickets .= ")";
					}
					$html_tickets .= "</p>";
				}

				$html_tickets .= '</div>';

				$html_tickets .= '</div>';
			}

	  		$html_tickets .= '<table class="table tabla_incidencias">
								<thead>
									<tr>
										<th class="th_tabla" style="width: 1%">Nº</th>
										<th class="th_tabla" style="width: 5%">Hora Creación</th>
										<th class="th_tabla" style="width: 5%">Autor</th>
										<th class="th_tabla" style="width: 5%">Situación</th>
										<th class="th_tabla">Salón</th>
										<th class="th_tabla" style="width: 5%">Máquina</th>
										<th class="th_tabla" style="width: 5%">Tipo error</th>
										<th class="th_tabla">Detalle error</th>
										<th class="th_tabla" style="width: 5%">Cierre</th>
										<th class="th_tabla" style="width: 5%">Soluciona</th>
										<th class="th_tabla">Ver incidencia</th>
									</tr>
								</thead>
								<tbody class="tabla_agrupados">';
								
			foreach($incidencias->result() as $incidencia){				
				if($incidencia->salon == 0){
					$salon = "Desconocido";
				}else{
					$salon = $this->post->get_salon($incidencia->salon);
				}

				$creador = $this->post->get_usuario($incidencia->creador);				
				$situacion = $this->post->get_situacion($incidencia->situacion);
				$maquina = $this->post->get_maquina($incidencia->maquina);
				$tipo_error = $this->post->get_tipo_error($incidencia->tipo_error);
				$detalle_error = $this->post->get_detalle_error($incidencia->detalle_error);

				if($incidencia->soluciona != 0){
					if(!empty($incidencia->fecha_solucion) && $incidencia->fecha_solucion != ""){
						$fecha_s = explode("-", $incidencia->fecha_solucion);
						$fecha_solucion = $fecha_s[2]."-".$fecha_s[1]."-".$fecha_s[0]." ".$incidencia->hora_solucion;						
					}else{
						$fecha_solucion = "";
					}
					$soluciona = $this->post->get_usuario($incidencia->soluciona);							
					$soluciona = $soluciona->nombre;
				}else{
					$fecha_solucion = "";
					$soluciona = "";
				}

				$fecha_busqueda = explode('/', $fecha);
				$fecha_busqueda = $fecha_busqueda[0]."-".$fecha_busqueda[1]."-".$fecha_busqueda[2];
				
				$html_tickets .= '<tr>
									<td style="width: 1%">'.$incidencia->id.'</td>
									<td style="width: 5%">'.$incidencia->hora_creacion.'</td>
									<td style="width: 5%">'.$creador->nombre.'</td>
									<td style="width: 5%">'.$situacion.'</td>									
									<td>'.$salon.'</td>
									<td style="width: 5%">'.$maquina.'</td>
									<td style="width: 5%">'.$tipo_error.'</td>
									<td>'.$detalle_error.'</td>
									<td style="width: 5%">'.$fecha_solucion.'</td>
									<td style="width: 5%">'.$soluciona.'</td>
									<td>
										<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url('ver_historial/'.$incidencia->id.'/'.$fecha_busqueda.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
									</td>
								</tr>';
				
			}
			
			$html_tickets .= '</tbody>
							</table>';
  		}  	
		
		echo $html_tickets;
		
	}
	
	/* SECCION PROHIBIDOS */
	public function prohibidos(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){
				$prohibidos = $this->post->get_prohibidos();
				$tabla_prohibidos = '';
				if($prohibidos->num_rows() != 0){
					foreach($prohibidos->result() as $prohibido){

						$op = $this->post->get_operadoras_rol_2($prohibido->operadora);
						$operadora = $op->row();
						$op = $operadora->operadora;

						$usuario = $this->post->get_creador_completo($prohibido->usuario);

						if($prohibido->db == 'A'){
							$db = 'averias';
						}else if($prohibido->db == 'B'){
							$db = 'ESPECIALES';
						}else{
							$db = 'ANDALUCÍA';
						}

						$fecha_hora = explode(" ", $prohibido->fecha);
						$fecha = explode("-", $fecha_hora[0]);

						$tabla_prohibidos .= '<tr>
												<td>'.$op.'</td>
												<td>'.$usuario->nombre.'</td>
												<td>'.$db.'</td>
												<td>'.$fecha[2].'-'.$fecha[1].'-'.$fecha[0].' '.$fecha_hora[1].'</td>
											</tr>';
					}
				}else{
					$tabla_prohibidos = '<tr><td align="center" colspan="4"><span style="font-weight: bold">No hay resultados</span></td></tr>';
				}
			}else{
				$tabla_prohibidos = '';
			}
			$data = array('title' => 'Administracion', 'tabla_prohibidos' => $tabla_prohibidos);
			$this->load_view('prohibidos', $data);
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
	
	public function prohibidos_form(){
		if($this->session->userdata('logged_in')){
			$this->form_validation->set_rules('dni', 'DNI', 'trim|htmlspecialchars|required');
			if ($this->form_validation->run() == FALSE){
				$this->prohibidos();
			}else{
				$this->load->library('prohibidos');
				$prohibidos = $this->prohibidos->return_prohibidos($this->input->post('dni'));
				$this->post->guardar_historial($this->session->userdata('logged_in')['id'], 'Consulta prohibidos');	
				$data = array('title' => 'Administracion', 'prohibidos' => $prohibidos, 'dni' => $this->input->post('dni'));
				$this->load_view('prohibidos', $data);
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function subir_archivo_prohibidos(){
		if(!empty($_FILES)){
			$target = APPPATH."../tickets/files/txt/" . date("d_m_Y") . "_" . $_POST['number'] . ".txt";
			move_uploaded_file( $_FILES['file']['tmp_name'], $target);
		}
	}

	public function prohibidos_andalucia(){
		$path = APPPATH."../tickets/files/txt/";
		$files = scandir($path);
		$files = array_diff(scandir($path), array('.', '..'));

		$filepath = $path . "Prohibidos_andalucia.txt";
	    $fh = fopen($filepath,'r');
		$id=0;
		$string="";
		$tipo="";
		$dni="";
		$nombre="";
		$fecha="";
		$filas = 0;
		$sql="INSERT INTO `prohibidos` (`TIPO`, `DNI`, `APELLIDOS`, `NOMBRE`) VALUES";

		while ($lineas = fgets($fh)){
		   $string .= $lineas;
		}
		
		fclose($fh);
		$resultado = $string;

		$explotar=explode("\n",$resultado);

		try{
	  		$conn = new PDO('mysql:host=averiasdeandalucia.es;dbname=GDP-ADA; charset=latin1', 'userGDP-ADA', 'k1y^3E7e');
	  		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
	  		echo "ERROR: " . $e->getMessage();
		}
		$cont = 1;

		if($explotar[count($explotar)-1] != null){ 			
			for($i = $cont; $i < count($explotar); $i++){
				if ($explotar[$i] != null){
					$campos = explode(",", $explotar[$i]);
		  			$id++;
		  			$tipo = $campos[0];
		  			$dni = $campos[1];
		  			$apellidos = $campos[2];
		  			$nombre = $campos[3];
		  			/*
		  			$check_record = $conn->prepare("SELECT * FROM prohibidos WHERE DNI LIKE '%".$dni."%' AND TIPO LIKE '%".$tipo."%'");
					$check_record->execute();
					if($check_record->rowCount() == 0){ 								
						$filas++;
						$sql .= " ('".$tipo."', '".$dni."', '".$apellidos."', '".$nombre."'),\n\r";						
					}
					*/
					$sql .= " ('".$tipo."', '".$dni."', '".$apellidos."', '".$nombre."'),\n\r";
		   		}
			}    		
   		}else{
   			echo "error";
   		}

   		$sql = substr(rtrim($sql), 0, -1);
		$sql .= ";";

		echo $sql;

		/*

		$files = glob(APPPATH."../tickets/files/txt/*");

		foreach($files as $file){
		  if(is_file($file))
		    unlink($file);
		}
		
		$filas = 1;

		if($filas != 0){		
			$insert_data = $conn->prepare($sql);
	  		if($insert_data->execute()){
	  			echo "Base de datos actualizada correctamente.";
	  			//$this->post->actualizar_prohibidos("C");
	  		}
		}else{
			echo "Base de datos ya contiene esos registros.";
		}

		*/
	}

	public function prohibidos_kirol_andalucia(){
		$path = APPPATH."../tickets/files/txt/";
		$files = scandir($path);
		$files = array_diff(scandir($path), array('.', '..'));

		$filepath = $path . "Prohibidos_andalucia_completo.txt";
	    $fh = fopen($filepath,'r');
		$id=0;
		$string="";
		$tipo="";
		$dni="";
		$nombre="";
		$fecha="";
		$filas = 0;
		$sql="";

		while ($lineas = fgets($fh)){
		   $string .= $lineas;
		}
		
		fclose($fh);
		$resultado = $string;
		$explotar=explode("\n",$resultado);

		$cont = 1;

		for($i = $cont; $i < count($explotar); $i++){
			if ($explotar[$i] != null){
				$campos = explode(",", $explotar[$i]);
	  			$dni = $campos[1];	  											
				$sql .= $dni."<br/>";
	   		}
		}

		echo $sql;
	}

	public function prohibidos_kirol_murcia(){
		$path    = APPPATH."../tickets/files/txt/";
		$files = scandir($path);
		$files = array_diff(scandir($path), array('.', '..'));

		$filepath = $path . "prohibidos_averias_completo.txt";
	    $fh = fopen($filepath,'r');
		$id=0;
		$string="";
		$tipo="";
		$dni="";
		$nombre="";
		$fecha="";
		$filas = 0;
		$sql="";

		while ($lineas = fgets($fh)){
		   $string .= $lineas;
		}
		
		fclose($fh);
		$resultado = $string;
		$explotar=explode("\n",$resultado);

		$cont = 1;
		$array = array();

		for($i = $cont; $i < count($explotar); $i++){
			if ($explotar[$i] != null){
				$tipo = trim(substr($explotar[$i], 0, 1));
				$dni = trim(substr($explotar[$i], 12, 18));
				if($tipo == "A"){
				  	$array[] = $dni;
				}else if($tipo == "B"){
					$array = array_diff($array, array($dni));
				}
			}
		}

		foreach($array as $valor){
			$sql .= $valor."<br/>";
		}

		echo $sql;	
	}

	public function cargar_archivo_prohibidos(){
		$path    = APPPATH."../tickets/files/txt/";
		$files = scandir($path);
		$files = array_diff(scandir($path), array('.', '..'));

		$filepath = $path . "Prohibidos.txt";
	    $fh = fopen($filepath,'r');
		$id=0;
		$string="";
		$tipo="";
		$dni="";
		$nombre="";
		$fecha="";
		$filas = 0;
		$sql="INSERT INTO `prohibidos` (`TIPO`, `DNI`, `NOMBRE`, `FECHA`) VALUES";

		while ($lineas = fgets($fh)){
		   $string .= $lineas;
		}
		
		fclose($fh);
		$resultado = $string;
		$explotar=explode("\n",$resultado);

		/* COMPROBAR TIPO ARCHIVO Y BBDD CORRESPONDIENTE */
		$comprobar = substr($explotar[0],0,1);
		if($comprobar == "L"){
			try{
		  		$conn = new PDO('mysql:host=149.202.82.135;dbname=GDP-averias; charset=utf8', 'userGDP-averias', 'Eg9ov!80');
		  		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
		  		echo "ERROR: " . $e->getMessage();
			}
			$cont = 1;
			$db = "A";
		}else{
			try{	
		  		$conn = new PDO('mysql:host=149.202.82.135;dbname=GDP; charset=utf8', 'userGDP', '13579GDP');
		  		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
		  		echo "ERROR: " . $e->getMessage();
			}
			$cont = 0;
			$db = "B";
		}

		for($i = $cont; $i < count($explotar); $i++){
			if ($explotar[$i] != null){
	  			$id++;
			  	$tipo = trim(substr($explotar[$i], 0, 1));
			  	$dni = trim(substr($explotar[$i], 12, 18));
			  	$nombre = str_replace("'","`",trim(substr($explotar[$i], 30, 52)));
			  	$fecha3 = date("Y-m-d", strtotime(str_replace(".","-",substr($explotar[$i], -11))));
			  	$fecha3 = trim($fecha3);
		  		$fecha3 = str_replace(' ', '', $fecha3);
				$fecha3 = preg_replace('/[^A-Za-z0-9\-]/', '', $fecha3);
				$check_record = $conn->prepare("SELECT * FROM prohibidos WHERE NOMBRE LIKE '%".$nombre."%' AND DNI LIKE '%".$dni."%' AND TIPO LIKE '%".$tipo."%' AND FECHA = '".$fecha3."'");
				$check_record->execute();
				if($check_record->rowCount() == 0){ 								
					$filas++;
					$sql .= " ('".$tipo."', '".$dni."', '".$nombre."', '".$fecha3."'),\n\r";
					//$sql .= $dni."<br/>";						
				}
	   		}
		}

	  	$sql = substr(rtrim($sql), 0, -1);
		$sql .= ";";

		$files = glob(APPPATH."../tickets/files/txt/*");

		foreach($files as $file){
		  if(is_file($file))
		    unlink($file);
		}

		if($filas != 0){		
			$insert_data = $conn->prepare($sql);
	  		if($insert_data->execute()){
	  			echo "Base de datos actualizada correctamente.";
	  			//$this->post->actualizar_prohibidos("C");
	  		}
		}else{
			echo "Base de datos ya contiene esos registros.";
		}
	}

	/* SECCION CENTRALITA */
	public function llamar_ticket($id_ticket){
		$telefono = $this->post->get_telefono_incidencia($id_ticket);
		if($telefono){
			$data = array(
			    'from' => '101',
			    'to' => $telefono,
			);
			$payload = json_encode($data);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"http://89.6.108.10/telephonyapi/api/Telephony/dial");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERPWD, "admin:admin");
			$result = curl_exec($ch);
			if(curl_errno($ch)){
			    throw new Exception(curl_error($ch));
			}
			curl_close($ch);
			print_r($result);
			//$this->gestion();																				
		}else{
			echo "Error";
		}
	}

	public function centralita(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 1){
				$html_llamada = $html_incidencias = $version_movil = '';
				if(isset($_GET['agente']) && $_GET['agente'] != ''){
					$agente = $_GET['agente'];
					if(isset($_GET['cliente']) && $_GET['cliente'] != ''){
						$cliente = $_GET['cliente'];						
						$cliente = $this->post->get_centralita_cliente_llamada($cliente);
						if($cliente){
							$html_llamada .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0"><div class="panel-heading" style="background: #449d44; text-align: center; padding: 5px 4px; font-size: 13px">
										<p style="color: #fff">LLamada Entrante '.date("H:i:s").'</p>
									</div>
									<div class="panel-body" style="padding: 0; border: none">
									    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
											<p>
												<span style="font-weight: bold">Operadora: </span>'.$cliente["operadora"].'
											</p>
											<p>
												<span style="font-weight: bold">Salon: </span>'.$cliente["salon"].'
											</p>
											<p>
												<span style="font-weight: bold">Cliente: </span>'.$cliente["cliente"].'
											</p>
										</div>
									</div>
								</div>';

							if($cliente["salon"]){
								$incidencias = $this->post->get_tickets_salon_centralita($cliente['id_salon']);
							}else{
								$incidencias = $this->post->get_tickets_op_centralita($cliente['id_op']);
							}
							foreach($incidencias->result() as $incidencia){
								$html_incidencias .= '<tr class="clickable-row" data-href="'.base_url('editar_incidencia/'.$incidencia->id.'').'"><td style="width: 1% !important">'.$incidencia->id.'</td>';

								$version_movil .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0">';
							
								$fecha_creacion = explode("-", $incidencia->fecha_creacion);
								$fecha = $fecha_creacion[2]."/".$fecha_creacion[1]."/".$fecha_creacion[0];
								
								$html_incidencias .= '<td>'.$fecha.' '.$incidencia->hora_creacion.'</td>';

								$situacion = $this->post->get_situacion($incidencia->situacion);
								if($incidencia->situacion == 2 && $incidencia->destino == 4){
									$html_incidencias.='<td style="color: #eb9316; font-weight: bold">'.$situacion.' ADM</td>';
								}else if($incidencia->situacion == 2){
									$html_incidencias.='<td style="color: #b21a30; font-weight: bold">'.$situacion.' Operadora</td>';
								}else{
									$html_incidencias.='<td style="color: #b21a30; font-weight: bold">'.$situacion.'</td>';
								}

								$salon = $this->post->get_salon($incidencia->salon);
								$html_incidencias .= '<td style="font-weight: bold">'.$salon.'</td>';

								$averia = $this->post->get_averia($incidencia->tipo_averia);
								$tipo_error = $this->post->get_tipo_error($incidencia->tipo_error);
								$detalle_error = $this->post->get_detalle_error($incidencia->detalle_error);		
								$maquina = $this->post->get_maquina($incidencia->maquina);
								$creador = $this->post->get_creador($incidencia->creador);																				
								
								$html_incidencias .= '<td>'.$averia->gestion.'</td>
													<td>'.$tipo_error.'</td>
													<td>'.$detalle_error.'</td>
													<td>'.$maquina.'</td>
													<td>'.$creador.'</td>';

								if($incidencia->asignado == 0){
									$html_incidencias .= '<td>Nadie</td>';
								}else{
									$asignado = $this->post->get_creador($incidencia->asignado);
									$html_incidencias .= '<td>'.$asignado.'</td>';
								}
								
								if($incidencia->tratamiento == 0){
									$html_incidencias .= '<td>Nadie</td>';
								}else{
									$tratamiento = $this->post->get_creador($incidencia->tratamiento);
									$html_incidencias .= '<td>'.$tratamiento.'</td>';
								}

								$version_movil.='<div class="panel-heading" style="background: #d9534f; text-align: center; padding: 5px 4px; font-size: 13px">
														<p style="color: #fff">#'.$incidencia->id.' - '.$fecha.' '.$incidencia->hora_creacion.'</p>
													</div>
													<div class="panel-body" style="padding: 0; border: none">
														<div class="col-md-12 col-sm-12" style="padding: 0; margin: 0; background: #eee; color: #000; float: left; width: 100%; text-align: center">
															<div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Avería</span></p><p style="margin: 0">'.$averia->gestion.'</p></div>
														    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Error</span></p><p style="margin: 0">'.$tipo_error.'</p></div>
														    <div style="width: 33%; float: left; padding: 5px 0"><p style="margin: 0"><span style="font-weight: bold">Detalle</span></p><p style="margin: 0">'.$detalle_error.'</p></div>
														    </div>
														    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
																<p><span style="font-weight: bold">Situación: </span><span style="color: #b21a30;">'.$situacion.'</span></p>';

								$prioridad = $this->post->get_prioridad_id($incidencia->prioridad);
								if($incidencia->prioridad == 0){
									$html_incidencias .= '<td style="color: #ffd600; font-weight: bold">'.$prioridad->prioridad.'</td>';
									$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #ffd600">'.$prioridad->prioridad.'</span></p>';
								}else if($incidencia->prioridad == 1){
									$html_incidencias .= '<td style="color: #449d44; font-weight: bold">'.$prioridad->prioridad.'</td>';
									$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #449d44">'.$prioridad->prioridad.'</span></p>';
								}else if ($incidencia->prioridad == 2){
									$html_incidencias .= '<td style="color: #b21a30; font-weight: bold">'.$prioridad->prioridad.'</td>';
									$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #b21a30">'.$prioridad->prioridad.'</span></p>';
								}else if ($incidencia->prioridad == 3){
									$html_incidencias .= '<td style="color: #138496; font-weight: bold">Programada</td>';
									$version_movil .= '<p><span style="font-weight: bold">Prioridad: </span><span style="color: #138496">Programada</span></p>';
								}
														
								$version_movil.='<p><span style="font-weight: bold">Máquina:</span> '.$maquina.'</p>';
								
								if($incidencia->asignado != 0){
									$asignado = $this->post->get_creador($incidencia->asignado);
									$version_movil.='<p><span style="font-weight: bold">Asignado:</span> '.$asignado.'</p>';
								}
								
								if($incidencia->tratamiento != 0){
									$tratamiento = $this->post->get_creador($incidencia->tratamiento);
									$version_movil.='<p><span style="font-weight: bold">Tratamiento:</span> '.$tratamiento.'</p>';
								}

								$html_incidencias .= '<td>
														<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i class="fa fa-eye"></i></a>
													</td></tr>';
								$version_movil.='<a style="padding: 2px 4px; margin: 0;" href="'.base_url('ver_historial/'.$incidencia->id.'').'" type="button" class="btn btn-info" alt="Detalle" title="Detalle"><i style="font-size: 30px" class="fa fa-eye"></i><span style="display: block; font-weight: bold; font-size: 10px">Ver Detalle</span></a>
								</div></div></div>';
							}
						}else{
							$html_llamada .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0"><div class="panel-heading" style="background: #449d44; text-align: center; padding: 5px 4px; font-size: 13px">
										<p style="color: #fff">LLamada Entrante '.date("H:i:s").'</p>
									</div>
									<div class="panel-body" style="padding: 0; border: none">
									    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
											<p>
												<span style="font-weight: bold">'.$_GET['cliente'].' - Desconocido</span>
											</p>
										</div>
									</div>
								</div>';

							$html_incidencias .= '<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
													<div class="panel panel-default" style="margin: 0">
														<p>No hay incidencias</p>
													</div> 	
											  	</div>
											  	<div class="col-md-12" id="version_movil">
											  		<p>No hay incidencias</p>
											  	</div>';
						}
						$data = array('title' => 'Administracion', 'html_llamada' => $html_llamada, 'html_incidencias' => $html_incidencias, 'version_movil' => $version_movil, 'cliente' => $cliente);
						$this->load_view('centralita', $data);
					}else{
						$html_llamada .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0"><div class="panel-heading" style="background: #449d44; text-align: center; padding: 5px 4px; font-size: 13px">
									<p style="color: #fff">LLamada Entrante '.date("H:i:s").'</p>
								</div>
								<div class="panel-body" style="padding: 0; border: none">
								    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
										<p>
											<span style="font-weight: bold">'.$_GET['agente'].' - Desconocido</span>
										</p>
									</div>
								</div>
							</div>';

						$html_incidencias .= '<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
												<div class="panel panel-default" style="margin: 0">
													<p>No hay incidencias</p>
												</div> 	
										  	</div>
										  	<div class="col-md-12" id="version_movil">
										  		<p>No hay incidencias</p>
										  	</div>';

						$data = array('title' => 'Administracion', 'html_llamada' => $html_llamada, 'html_incidencias' => $html_incidencias, 'version_movil' => $version_movil);
						$this->load_view('centralita', $data);	
					}
				}else{
					$html_llamada .= '<div class="panel panel-default col-md-12 col-sm-12" style="padding: 0"><div class="panel-heading" style="background: #449d44; text-align: center; padding: 5px 4px; font-size: 13px">
								<p style="color: #fff">LLamada Entrante '.date("H:i:s").'</p>
							</div>
							<div class="panel-body" style="padding: 0; border: none">
							    <div class="col-md-12 col-sm-12" style="padding: 5px 10px; float: left; width: 100%;">
									<p>
										<span style="font-weight: bold">Desconocido</span>
									</p>
								</div>
							</div>
						</div>';

					$html_incidencias .= '<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
											<div class="panel panel-default" style="margin: 0">
												<p>No hay incidencias</p>
											</div> 	
									  	</div>
									  	<div class="col-md-12" id="version_movil">
									  		<p>No hay incidencias</p>
									  	</div>';

					$data = array('title' => 'Administracion', 'html_llamada' => $html_llamada, 'html_incidencias' => $html_incidencias, 'version_movil' => $version_movil);
						$this->load_view('centralita', $data);
				}
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function datafono_form(){
		error_reporting(0);
        ini_set('display_errors', 0);
		if($this->session->userdata('logged_in')){
			$this->form_validation->set_rules('dni', 'DNI', 'trim|htmlspecialchars|required');
			if($this->form_validation->run() == FALSE){
				$this->prohibidos();
			}else{
				$dni = strtoupper($this->input->post('dni'));
				$this->load->library('prohibidos');
				$prohibidos = $this->prohibidos->return_prohibidos2($dni);
				if($prohibidos){
					$this->post->activar_datafono($this->session->userdata('logged_in')['acceso'],$dni);
					$this->datafono();			
				}else{
					$this->datafono("1");
				}
			}
		}else{
		  $data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* SECCION CREDITO */
	public function datafono($error=NULL){
		error_reporting(0);
        ini_set('display_errors', 0);
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 3){
				$cajero = $this->post->get_cajero($this->session->userdata('logged_in')['acceso']);
				$movimientos = $this->post->get_ultimos_tickets_cajero($this->session->userdata('logged_in')['acceso']);
				$html_movimientos = '<h4>Últimos movimientos</h4>';
				if($movimientos){
					$html_movimientos .= '<div class="version_escritorio" style="width: 100%; padding: 8px; background: #4682b4; color: #fff; text-align: center; border: 1px solid #000">
							<div style="display: inline-block; width: 15%; color: #ddd; font-weight: bold">Ticket</div>
							<div style="display: inline-block; width: 14%; color: #ddd; font-weight: bold">Fecha</div>
							<div style="display: inline-block; width: 7%; color: #ddd; font-weight: bold">Importe</div>
							<div style="display: inline-block; width: 28%; color: #ddd; font-weight: bold">Estado</div>
							<div style="display: inline-block; width: 5%; color: #ddd; font-weight: bold">Tarjeta</div>
							<div style="display: inline-block; width: 18%; color: #ddd; font-weight: bold">Tipo</div>
						</div>';
					foreach($movimientos->result() as $movimiento){
						$id_ticket = (int) $movimiento->id_ticket;
						$fecha = explode(" ",$movimiento->fecha);
						$fecha1 = explode("-",$fecha[0]);
						$fechaF = $fecha1[2]."-".$fecha1[1]."-".$fecha1[0]." ".$fecha[1];
						$html_movimientos .= '<div class="version_escritorio" style="width: 100%; padding: 8px; text-align: center; border: 1px solid #000">
												<div style="display: inline-block; width: 15%;">'.$id_ticket.'</div>
												<div style="display: inline-block; width: 14%;">'.$fechaF.'</div>
												<div style="display: inline-block; width: 7%;">'.$movimiento->cantidad.'€</div>
												<div style="display: inline-block; width: 28%;">'.$movimiento->estado.'</div>
												<div style="display: inline-block; width: 5%;">'.$movimiento->numero_tarjeta.'</div>
												<div style="display: inline-block; width: 18%;">'.$movimiento->etiqueta.'</div>
											</div>
											<div class="panel panel-default col-md-12 col-sm-12 version_movil">
												<div style="width: 49%; padding: 5px; display: inline-block">
													<p style="margin: 0">
														<span style="font-weight: bold">Ticket: </span>'.$id_ticket.'
													</p>
												</div>
												<div style="width: 49%; padding: 5px; display: inline-block">
													<p style="margin: 0">
														<span style="font-weight: bold">Fecha: </span>'.$fechaF.'
													</p>
												</div>
												<div style="width: 49%; padding: 5px; display: inline-block">
													<p style="margin: 0">
														<span style="font-weight: bold">Cantidad: </span>'.$movimiento->cantidad.'€
													</p>
												</div>
												<div style="width: 49%; padding: 5px; display: inline-block">
													<p style="margin: 0">
														<span style="font-weight: bold">Estado: </span>'.$movimiento->estado.'
													</p>
												</div>
												<div style="width: 49%; padding: 5px; display: inline-block">
													<p style="margin: 0">
														<span style="font-weight: bold">Tarjeta: </span>'.$movimiento->numero_tarjeta.'
													</p>
												</div>
												<div style="width: 49%; padding: 5px; display: inline-block">
													<p style="margin: 0">
														<span style="font-weight: bold">Tipo: </span>'.$movimiento->etiqueta.'
													</p>
												</div>
											</div>';
					}
				}else{
					$html_movimientos .= '<p>Sin movimientos</p>';
				}
				if($error){
					$error = "<p style='font-weight: bold; color: red'>USUARIO NO PERMITIDO</p>";
				}else{
					$error = NULL;
				}
				$data = array('title' => 'Administracion', 'cajero' => $cajero, 'html_movimientos' => $html_movimientos, 'error' => $error);
				$this->load_view('datafono', $data);
			}else if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){
				/* Seleccionar salones */
				$salones = $this->post->get_salones_credito($this->session->userdata('logged_in')['acceso']);
				$html_salones = $html_avisos = '';
				$html_comprobaciones = '<div id="avisos_content" style="width: 35%; background-color: rgb(242, 222, 222); border: 1px solid rgb(220, 167, 167); border-radius: 5px; padding: 10px 10px 5px; color: rgb(169, 68, 66); font-weight: bold; margin-bottom: 16px; display: none;">';
				$i = 0;
				foreach($salones->result() as $salon){
					$html_salones .= "<option value='".$salon->id."' id='".$salon->id."'>
											".$salon->salon."
									 </option>";					
					
					/* Comprobar cajero */
					$cajero = $this->post->get_cajero($salon->id);
					$resultado = $this->comprobar_puertos2($cajero->servidor,$cajero->puerto);
					if(!$resultado){
						$html_comprobaciones .= '<p style="width: 100%; font-weight: bold">'.$salon->salon.' <span style="color: #b21a30">Servidor desconectado o puerto cerrado. Por favor revise la configuración.</span></p>';
						$i++;
					}						
				}
				
				$html_comprobaciones .= '</div>
									</div>';
				
				if($i > 0){
					$html_avisos = '<div class="col-md-12 col-sm-12">
														<div id="avisos_alert" class="alert alert-danger" role="alert" style="width: 35%; font-weight: bold; text-align: center; cursor: pointer">PROBLEMAS DE CONEXIÓN ('.$i.')</div>';
				}
				
				$data = array('title' => '', 'html_salones' => $html_salones, 'html_avisos' => $html_avisos, 'html_comprobaciones' => $html_comprobaciones);
				$this->load_view('datafono', $data);
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function set_datafono_activo(){
		if($this->session->userdata('logged_in')){
			if($this->post->activar_datafono($this->session->userdata('logged_in')['acceso'])){
				return true;
			}else{
				return false;
			}
		}	
	}

	/* AJAX ROL 2 GET TICKETS CREDITO */
	public function get_tickets_credito(){
		$salon = $this->input->post('salon');
		$movimientos = $this->post->get_ultimos_tickets_cajero($salon);
		$html_movimientos = '<h4>Últimos movimientos</h4>';
		if($movimientos){
			$html_movimientos .= '<div class="version_escritorio" style="width: 100%; padding: 8px; background: #4682b4; color: #fff; text-align: center; border: 1px solid #000">
										<div style="display: inline-block; width: 15%; color: #ddd; font-weight: bold">Ticket</div>
										<div style="display: inline-block; width: 14%; color: #ddd; font-weight: bold">Fecha</div>
										<div style="display: inline-block; width: 7%; color: #ddd; font-weight: bold">Importe</div>
										<div style="display: inline-block; width: 28%; color: #ddd; font-weight: bold">Estado</div>
										<div style="display: inline-block; width: 5%; color: #ddd; font-weight: bold">Tarjeta</div>
										<div style="display: inline-block; width: 18%; color: #ddd; font-weight: bold">Tipo</div>
									</div>';
			foreach($movimientos->result() as $movimiento){
				$id_ticket = (int) $movimiento->id_ticket;
				$fecha = explode(" ",$movimiento->fecha);
				$fecha1 = explode("-",$fecha[0]);
				$fechaF = $fecha1[2]."-".$fecha1[1]."-".$fecha1[0]." ".$fecha[1];
				$html_movimientos .= '<div class="version_escritorio" style="width: 100%; padding: 8px; text-align: center; border: 1px solid #000">
										<div style="display: inline-block; width: 15%;">'.$id_ticket.'</div>
										<div style="display: inline-block; width: 14%;">'.$fechaF.'</div>
										<div style="display: inline-block; width: 7%;">'.$movimiento->cantidad.'€</div>
										<div style="display: inline-block; width: 28%;">'.$movimiento->estado.'</div>
										<div style="display: inline-block; width: 5%;">'.$movimiento->numero_tarjeta.'</div>
										<div style="display: inline-block; width: 18%;">'.$movimiento->etiqueta.'</div>
									</div>
									<div class="panel panel-default col-md-12 col-sm-12 version_movil">
										<div style="width: 49%; padding: 5px; display: inline-block">
											<p style="margin: 0">
												<span style="font-weight: bold">Ticket: </span>'.$id_ticket.'
											</p>
										</div>
										<div style="width: 49%; padding: 5px; display: inline-block">
											<p style="margin: 0">
												<span style="font-weight: bold">Fecha: </span>'.$fechaF.'
											</p>
										</div>
										<div style="width: 49%; padding: 5px; display: inline-block">
											<p style="margin: 0">
												<span style="font-weight: bold">Cantidad: </span>'.$movimiento->cantidad.'€
											</p>
										</div>
										<div style="width: 49%; padding: 5px; display: inline-block">
											<p style="margin: 0">
												<span style="font-weight: bold">Estado: </span>'.$movimiento->estado.'
											</p>
										</div>
										<div style="width: 49%; padding: 5px; display: inline-block">
											<p style="margin: 0">
												<span style="font-weight: bold">Tarjeta: </span>'.$movimiento->numero_tarjeta.'
											</p>
										</div>
										<div style="width: 49%; padding: 5px; display: inline-block">
											<p style="margin: 0">
												<span style="font-weight: bold">Tipo: </span>'.$movimiento->etiqueta.'
											</p>
										</div>
									</div>';
			}
		}else{
			$html_movimientos .= '<p>Sin movimientos</p>';
		}
		echo $html_movimientos;	
	}

	/* AJAX ROL 2 GET DNI CREDITO */
	public function get_dni_credito(){
		$salon = $this->input->post('salon');
		$registro = $this->post->get_ultimos_dni_cajero($salon);
		$salon = $this->post->get_salon($salon);
		$html_registro = '<h4 style="margin-left: 2%">'.$salon.'</h4>';
		if($registro){
			$html_registro .= '<div class="version_escritorio" style="width: 50%; padding: 8px; background: #4682b4; color: #fff; text-align: center; border: 1px solid #000; margin: 0 auto">
										<div style="display: inline-block; width: 50%; color: #ddd; font-weight: bold">Fecha</div>
										<div style="display: inline-block; width: 49%; color: #ddd; font-weight: bold">DNI</div>
									</div>';
			foreach($registro->result() as $registro){
				$fecha = explode(" ",$registro->fecha);
				$fecha1 = explode("-",$fecha[0]);
				$fechaF = $fecha1[2]."-".$fecha1[1]."-".$fecha1[0]." ".$fecha[1];
				$html_registro .= '<div class="version_escritorio" style="width: 50%; padding: 8px; text-align: center; border: 1px solid #000; margin: 0 auto">
										<div style="display: inline-block; width: 50%;">'.$fechaF.'</div>
										<div style="display: inline-block; width: 49%;">'.$registro->dni.'</div>
									</div>
									<div class="panel panel-default col-md-12 col-sm-12 version_movil">
										<div style="width: 49%; padding: 5px; display: inline-block">
											<p style="margin: 0">
												<span style="font-weight: bold">Fecha: </span>'.$fechaF.'
											</p>
										</div>
										<div style="width: 49%; padding: 5px; display: inline-block">
											<p style="margin: 0">
												<span style="font-weight: bold">Cantidad: </span>'.$registro->dni.'
											</p>
										</div>
									</div>';
			}
		}else{
			$html_registro .= '<p>Sin movimientos</p>';
		}
		echo $html_registro;	
	}

	public function buscador_movimientos_locales(){
		if($this->session->userdata('logged_in')){
			if($this->input->post('fecha_inicio') != ''){
				$fechaI = $this->input->post('fecha_inicio');
			}else{
				$fechaI = 0;
			}

			if($this->input->post('fecha_fin') != ''){
				$fechaF = $this->input->post('fecha_fin');
			}else{
				$fechaF = 0;
			}

			if($this->input->post('usuario') != ''){
				$usuario_buscar = $this->input->post('usuario');
			}else{
				$usuario = 0;
			}

			if($this->input->post('salon') != ''){
				$salon_buscar = $this->input->post('salon');
			}else{
				$salon_buscar = 0;
			}

			if($this->input->post('movimiento') != ''){
				$movimiento_buscar = $this->input->post('movimiento');
			}else{
				$movimiento_buscar = 0;
			}			

			$usuarios = $this->post->get_usuarios_registros_movimientos_locales($this->session->userdata('logged_in')['acceso']);
			$html_usuarios = '';
			foreach($usuarios->result() as $usuario){
				if($usuario->id == $usuario_buscar){
					$html_usuarios .= '<option value="'.$usuario->id.'" selected>'.$usuario->usuario.'</option>';
				}else{
					$html_usuarios .= '<option value="'.$usuario->id.'">'.$usuario->usuario.'</option>';
				}					
			}

			if($this->session->userdata('logged_in')['acceso'] == 24){
				$salones = $this->post->get_salones();
			}else{
				$salones = $this->post->get_salones_operadora($this->session->userdata('logged_in')['acceso']); 
			}
			$html_salones='';
			foreach($salones->result() as $salon){
				if($salon->id == $salon_buscar){
					$html_salones.='<option value="'.$salon->id.'" selected>'.$salon->salon.'</option>';
				}else{
					$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
				}
			}

			$movimientos = $this->post->get_movimientos();
			$html_movimientos='';
			foreach($movimientos->result() as $movimiento){
				if($movimiento->id == $movimiento_buscar){
					$html_movimientos.='<option value="'.$movimiento->id.'" selected>'.$movimiento->movimiento.'</option>';
				}else{
					$html_movimientos.='<option value="'.$movimiento->id.'">'.$movimiento->movimiento.'</option>';
				}
			}

			$ultimos_registros_locales = $this->post->get_registros_locales($fechaI,$fechaF,$usuario_buscar,$salon_buscar,$movimiento_buscar);
			if($ultimos_registros_locales->num_rows() != 0){
				$html_ultimos_registros = '<table class="table tabla_incidencias" style="border: 1px solid #ccc;">
							<thead style="background: #ccc">
								<tr>
									<th class="th_tabla">Usuario</th>
									<th class="th_tabla">Salón</th>
									<th class="th_tabla">Máquina</th>
									<th class="th_tabla">Movimiento</th>
									<th class="th_tabla">Importe</th>
									<th class="th_tabla">Saldo Final</th>
									<th class="th_tabla">Fecha</th>
									<th class="th_tabla">Firma</th>
								</tr>
							</thead>
							<tbody class="tabla_agrupados">';

				foreach($ultimos_registros_locales->result() as $ultimos_registro){
					$salon = $this->post->get_salon_completo($ultimos_registro->local);
						$movimiento = $this->post->get_movimiento($ultimos_registro->movimiento);
						$maquina = $this->post->get_maquina_completo($ultimos_registro->maquina);
						$creador = $this->post->get_creador($ultimos_registro->usuario);;

						$fecha1 = explode(" ", $ultimos_registro->fecha);
						$fecha2 = explode("-", $fecha1[0]);
						$fecha = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];

			  		$html_ultimos_registros .= '<tr>
				  									<td>'.$creador.'</td>
				  									<td>'.$salon->salon.'</td>
				  									<td>'.$maquina->maquina.'</td>
				  									<td>'.$movimiento->movimiento.'</td>
								  					<td>'.$ultimos_registro->importe.'€</td>
								  					<td>'.$ultimos_registro->saldo.'€</td>
								  					<td>'.$fecha.'</td>
								  					<td><img style="width: 50%" alt="firma" title="firma" src="'.base_url("../tickets/files/img/registros/".$ultimos_registro->firma).'"/></td>
							  					</tr>';
				}

				$html_ultimos_registros .= '</tbody>
								</table>';
			}else{
				$html_ultimos_registros = '<p style="font-weight: bold">No hay movimientos</p>';
			}

			$data = array('title' => 'Administracion', 'html_salones' => $html_salones, 'html_movimientos' => $html_movimientos, 'html_ultimos_registros' => $html_ultimos_registros, 'html_usuarios' => $html_usuarios, 'fecha_inicio' => $fechaI, 'fecha_fin' => $fechaF);
			$this->load_view('registros', $data);
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function registros(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['acceso'] == 24){
				$salones = $this->post->get_salones();
			}else{
				$salones = $this->post->get_salones_operadora($this->session->userdata('logged_in')['acceso']); 
			}
			$html_salones='';
			foreach($salones->result() as $salon){
				$html_salones.='<option value="'.$salon->id.'">'.$salon->salon.'</option>';
			}

			$usuarios = $this->post->get_usuarios_registros_movimientos_locales($this->session->userdata('logged_in')['acceso']);
			$html_usuarios = '';
			foreach($usuarios->result() as $usuario){
				$html_usuarios .= '<option value="'.$usuario->id.'">'.$usuario->usuario.'</option>';										
			}

			$movimientos = $this->post->get_movimientos();
			$html_movimientos='';
			foreach($movimientos->result() as $movimiento){
				$html_movimientos.='<option value="'.$movimiento->id.'">'.$movimiento->movimiento.'</option>';
			}

			$html_ultimos_registros = '';
			$html_ultimos_registros_movil = '';
			$ultimos_registros_locales = $this->post->get_ultimos_registros_locales($this->session->userdata('logged_in')['acceso']);
			if($ultimos_registros_locales->num_rows() != 0){
				$html_ultimos_registros .= '<table class="table tabla_incidencias" style="border: 1px solid #ccc;">
							<thead style="background: #ccc">
								<tr>
									<th class="th_tabla">Usuario</th>
									<th class="th_tabla">Salón</th>
									<th class="th_tabla">Máquina</th>
									<th class="th_tabla">Movimiento</th>
									<th class="th_tabla">Importe</th>
									<th class="th_tabla">Saldo Final</th>
									<th class="th_tabla">Fecha</th>
									<th class="th_tabla">Firma</th>
								</tr>
							</thead>
							<tbody class="tabla_agrupados">';

				foreach($ultimos_registros_locales->result() as $ultimos_registro){
					$salon = $this->post->get_salon_completo($ultimos_registro->local);
						$movimiento = $this->post->get_movimiento($ultimos_registro->movimiento);
						$maquina = $this->post->get_maquina_completo($ultimos_registro->maquina);
						$creador = $this->post->get_creador($ultimos_registro->usuario);;

						$fecha1 = explode(" ", $ultimos_registro->fecha);
						$fecha2 = explode("-", $fecha1[0]);
						$fecha = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]." ".$fecha1[1];

			  		$html_ultimos_registros .= '<tr>
				  									<td>'.$creador.'</td>
				  									<td>'.$salon->salon.'</td>
				  									<td>'.$maquina->maquina.'</td>
				  									<td>'.$movimiento->movimiento.'</td>
								  					<td>'.$ultimos_registro->importe.'€</td>
								  					<td>'.$ultimos_registro->saldo.'€</td>
								  					<td>'.$fecha.'</td>
								  					<td><img style="width: 50%" alt="firma" title="firma" src="'.base_url("../tickets/files/img/registros/".$ultimos_registro->firma).'"/></td>
							  					</tr>';

					$html_ultimos_registros_movil .= '<div style="width: 100%; border: 1px solid #ccc; border-radius: 5px; margin: 4% 0">
														<div style="width: 100%; background: #ccc; padding: 1%">
															<p>'.$creador.' '.$salon->salon.' '.$maquina->maquina.'</p>
														</div>
														<div style="width: 100%; padding: 1%">
															<p>'.$movimiento->movimiento.' '.$ultimos_registro->saldo.'€</p>
															<p>'.$ultimos_registro->importe.'€ '.$ultimos_registro->saldo.'€ '.$fecha.'</p>
														</div>
													</div>';
				}

				$html_ultimos_registros .= '</tbody>
								</table>';
			}

			$data = array('title' => 'Administracion', 'html_salones' => $html_salones, 'html_movimientos' => $html_movimientos, 'html_ultimos_registros' => $html_ultimos_registros, 'html_usuarios' => $html_usuarios, 'html_ultimos_registros_movil' => $html_ultimos_registros_movil);
			$this->load_view('registros', $data);
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function nuevo_registro_form(){
		if($this->session->userdata('logged_in')){
			$data1 = $this->input->post('img');
			$filteredData1=substr($data1, strpos($data1, ",")+1);
			$decodedData1=base64_decode($filteredData1);
			$fecha = date("YmdHis");
			$fic_name1 = $fecha.'.png';
			$fp = fopen(APPPATH."../tickets/files/img/registros/".$fic_name1, 'wb');
			if($fp){
				$ok = fwrite( $fp, $decodedData1);
				if($ok){
					fclose($fp);		
			        $fecha = date("Y-m-d H:i:s");
			        $result = $this->post->guardar_registro($this->input->post('local'),$this->input->post('maquina'),$this->input->post('movimiento'),$this->input->post('importe'),$this->input->post('saldo'),$fic_name1,$fecha);
			        if($result){
			        	$email = $this->enviar_email_registro_local($result);
			        }
			        echo $result->id;
			    }else{
			    	echo false;
			    }
			}else{
				echo false;
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Seccion registrar visitas informes salones operadoras */
	public function informes_operadora(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){

				$html_salones = "";
				if($this->session->userdata('logged_in')['rol'] == 3){
					$salon = $this->post->get_salon($this->session->userdata('logged_in')['acceso']);
					$html_salones .= '<option value="'.$this->session->userdata('logged_in')['acceso'].'" selected>'.$salon.'</option>';
				}else{
					$salones = $this->post->get_salones_operadora($this->session->userdata('logged_in')['acceso']);
					$html_salones .= '<option value="">Ninguno</option>';
					foreach($salones->result() as $salon){
						$html_salones .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}

				/* Informes */
				if($this->session->userdata('logged_in')['rol'] == 3){
					$informes = $this->post->get_informes_operadora($this->session->userdata('logged_in')['acceso'],3);
				}else{
					$informes = $this->post->get_informes_operadora($this->session->userdata('logged_in')['acceso'],2);
				}
				$tabla_informes = '';
				foreach($informes->result() as $informe){
					$salon = $this->post->get_salon($informe->salon);
					$fecha = explode(' ', $informe->fecha);
					$fecha1 = explode('-', $fecha[0]);
					$fecha2 = explode(':', $fecha[1]);
					$fecha = $fecha1[2]."/".$fecha1[1]."/".$fecha1[0]." ".$fecha[1];
					$usuario = $this->post->get_creador($informe->creador);
					
					$tabla_informes.='<tr style="font-family: Helvetica,Arial,sans-serif; font-size: 13px; color: #000">';

					if($informe->salon == 0){
						$tabla_informes.='<td>TODOS</td>';
					}else{
						$tabla_informes.='<td>'.$salon.'</td>';
					}
					$tabla_informes .= '<td>'.$fecha.'</td>
										<td>'.$usuario.'</td>
										<td>
											<a target="_blank" style="padding: 2px 4px; margin: 0;" href="'.base_url('files/pdf_operadoras/'.$informe->salon.'_'.$fecha1[2].'_'.$fecha1[1].'_'.$fecha1[0].'_'.$fecha2[0].'_'.$fecha2[1].'_'.$fecha2[2].'.pdf').'" type="button" class="btn btn-info" alt="Ver PDF" title="Ver PDF"><i class="fa fa-eye"></i></a>
											<a style="padding: 2px 4px; margin: 0 4px;" href="'.base_url("eliminar_pdf_operadora/".$informe->id."").'" type="button" class="btn btn-danger" alt="Eliminar PDF" title="Eliminar PDF"><i class="fa fa-close"></i></a>
										</td>
									</tr>';
				}

				$data = array('title' => 'Administracion', 'html_salones' => $html_salones, 'tabla_informes' => $tabla_informes);
				$this->load_view('informes_operadora', $data);
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function nuevo_informe_operadora(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 3 || $this->session->userdata('logged_in')['rol'] == 4){

				$html_salones = "";
				if($this->session->userdata('logged_in')['rol'] == 3){
					$salon = $this->post->get_salon($this->session->userdata('logged_in')['acceso']);
					$html_salones .= '<option value="'.$this->session->userdata('logged_in')['acceso'].'" selected>'.$salon.'</option>';
				}else{
					$salones = $this->post->get_salones_operadora($this->session->userdata('logged_in')['acceso']);
					$html_salones .= '<option value="">Ninguno</option>';
					foreach($salones->result() as $salon){
						$html_salones .= '<option value="'.$salon->id.'">'.$salon->salon.'</option>';
					}
				}

				$data = array('title' => 'Administracion', 'html_salones' => $html_salones);
				$this->load_view('nuevo_informe_operadora', $data);
			}else{
				$this->gestion();
			}
		}else{
		  	$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	public function nuevo_informe_operadora_form(){
		if($this->session->userdata('logged_in')){
			$this->form_validation->set_rules('salon', 'SALÓN', 'trim|htmlspecialchars|required');
			if($this->form_validation->run() == FALSE){
				$this->nuevo_informe_operadora();
			}else{
				if(empty($_POST['check_list'])){
			    	$checklist = false;
			    }else{
			    	$checklist = $_POST['check_list'];
			    }
				
				$texto = preg_replace( "/\r|\n/", "", $this->input->post('texto'));
				$resultado = $this->post->crear_informe_operadora_salon($this->input->post('salon'),date("Y-m-d H:i:s"),$texto,$checklist);
		      	if($resultado){
		      		$informe_operadora = "1";
		      		$this->load->library('pdf');
					$pdf = $this->pdf->return_pdf_operadoras($resultado,$informe_operadora);
					if($pdf){
						$email = $this->enviar_email_informe_salon_operadora($resultado,$pdf);
						$this->post->guardar_historial($this->session->userdata('logged_in')['id'], 'Crear visita operadora');
		      			$this->informes_operadora();
					}		      		
		      	}
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Eliminar informe PDF operadora */
	public function eliminar_pdf_operadora($id){
		$informe = $this->post->get_informe_visita_operadora($id);
		$fecha = explode(' ', $informe->fecha);
		$fecha1 = explode('-', $fecha[0]);
		$fecha2 = explode(':', $fecha[1]);
		unlink(APPPATH.'../tickets/files/pdf_operadoras/'.$informe->salon.'_'.$fecha1[2].'_'.$fecha1[1].'_'.$fecha1[0].'_'.$fecha2[0].'_'.$fecha2[1].'_'.$fecha2[2].'.pdf');
		$resultado = $this->post->eliminar_informe_visita_operadora($id);
		if($resultado){
			$this->informes_operadora();
		}
	}

	/*Borrar tickets cajero */
	public function borrar_tickets_cajero($id){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 352 || $this->session->userdata('logged_in')['id'] == 353){
				$cajero_info = $this->post->get_cajero($id);
				if($cajero_info){
					try{
				  		$conn = new PDO('mysql:host='.$cajero_info->servidor.':'.$cajero_info->puerto.';dbname=appdb; charset=latin1', $cajero_info->usuario, $cajero_info->clave);
				  		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					}catch(PDOException $e){
				  		echo "ERROR: " . $e->getMessage();
					}
					$sql = $conn->prepare("delete from pagoskirol where estado=0");
					$sql->execute();
					$this->cajero($id);
				}else{
					$this->cajero($id);
				}
			}else{
				$this->cajero($id);
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}

	/* Visualizar DNI AupabetTPV */
	public function registro_dni_aupabet(){
		if($this->session->userdata('logged_in')){
			if($this->session->userdata('logged_in')['rol'] == 2){
				/* Seleccionar salones */
				$salones = $this->post->get_salones_credito($this->session->userdata('logged_in')['acceso']);
				$html_salones = $html_avisos = '';
				$html_comprobaciones = '<div id="avisos_content" style="width: 35%; background-color: rgb(242, 222, 222); border: 1px solid rgb(220, 167, 167); border-radius: 5px; padding: 10px 10px 5px; color: rgb(169, 68, 66); font-weight: bold; margin-bottom: 16px; display: none;">';
				$i = 0;
				foreach($salones->result() as $salon){
					$html_salones .= "<option value='".$salon->id."' id='".$salon->id."'>
											".$salon->salon."
									 </option>";					
					
					/* Comprobar cajero */
					$cajero = $this->post->get_cajero($salon->id);
					$resultado = $this->comprobar_puertos2($cajero->servidor,$cajero->puerto);
					if(!$resultado){
						$html_comprobaciones .= '<p style="width: 100%; font-weight: bold">'.$salon->salon.' <span style="color: #b21a30">Servidor desconectado o puerto cerrado. Por favor revise la configuración.</span></p>';
						$i++;
					}						
				}
				
				$html_comprobaciones .= '</div>
									</div>';
				
				if($i > 0){
					$html_avisos = '<div class="col-md-12 col-sm-12">
														<div id="avisos_alert" class="alert alert-danger" role="alert" style="width: 35%; font-weight: bold; text-align: center; cursor: pointer">PROBLEMAS DE CONEXIÓN ('.$i.')</div>';
				}
				
				$data = array('title' => '', 'html_salones' => $html_salones, 'html_avisos' => $html_avisos, 'html_comprobaciones' => $html_comprobaciones);
				$this->load_view('registro_dni_aupabet', $data);
			}else{
				$this->gestion();
			}
		}else{
			$data = array('title' => '');
			$this->load->view('login', $data);
		}
	}
}
?>                    
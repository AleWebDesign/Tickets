<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_manual {

	function return_ticket_manual($servidor,$ticket,$incidencia,$asignado,$creador){
		// Datos db
		$usuario = "ccm";
		$clave = "ccm10";
		$database = "ticketserver";
		$port = 3306;
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
			// Conexion
			$con=mysqli_connect($servidor->servidor, $usuario, $clave, $database);
			// Comprobar conexion
			if(mysqli_connect_errno()){
				return "error";
			}else{
				$numero_ticket = rand(10000, 99999);
				$fecha = date("Y-m-d h:i:s");
				
				if(!mysqli_query($con,"INSERT INTO tickets	(Command, TicketNumber, Mode, DateTime, LastCommandChangeDateTime, Value, Residual, IP, User, Comment, Type, TypeIsBets, TypeIsAux, AuxConcept, Used, UsedFromIP, UsedAmount, UsedDateTime, MergedFromId, ExpirationDate, TITOTitle, TITOTicketType, TITOStreet, TITOPlace, TITOCity, TITOPostalCode, TITODescription, TITOExpirationType) VALUES	('OPEN', '968".$numero_ticket."', 'pdaPost', '".$fecha."', '".$fecha."', ".$ticket->importe.".00, '0.00', '".$servidor->servidor."', '".$creador->usuario."', 'INCIDENCIA #".$ticket->id_ticket." ".$asignado->usuario."', 'AVERIASDEMURCIA', 0, '0', '', 0, '', 0.00, '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', '', '', '', '', '', '', '', 0)")){
					return mysqli_error($con);
				}else{
					return 1;
				}
			}
		}
	}

}
<?php defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);

class Prohibidos {
	
	function return_prohibidos($dni){
		
		function comprobardni($cif) {
		
			$cif = strtoupper($cif);

		  for ($i = 0; $i < 9; $i ++){
		  	$num[$i] = substr($cif, $i, 1);
		  }
		 
		  //si no tiene un formato valido devuelve error
		  if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)){
		  	return 0;
		  }
		  
		  //comprobacion de NIFs estandar
		  if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif)){
		  	if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE',
		    	substr($cif, 0, 8) % 23, 1)){
		   		return 1;
		  	}else {
		   		return -1;
		  	}
		  }
		  
		  //algoritmo para comprobacion de codigos tipo CIF
		  $suma = $num[2] + $num[4] + $num[6];
		  for ($i = 1; $i < 8; $i += 2){
		  	$suma += substr((2 * $num[$i]),0,1) + substr((2 * $num[$i]),1,1);
		  }
		  
		  $n = 10 - substr($suma, strlen($suma) - 1, 1);
		  //comprobacion de NIFs especiales (se calculan como CIFs)
		  if (preg_match('/^[KLM]{1}/', $cif)){
		  	if ($num[8] == chr(64 + $n)){
		    	return 1;
		    }else{
		    	return -1;
		    }
		  }
		 
		  //comprobacion de CIFs
		  if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)){
		  	if ($num[8] == chr(64 + $n) || $num[8] ==
		    	substr($n, strlen($n) - 1, 1)){
		   	  return 2;
		  	}else{
		   		return -2;
		  	}
		  }
		  
		  //comprobacion de NIEs
		  //T
		  if (preg_match('/^[T]{1}/', $cif)){
		  	if ($num[8] == ereg('^[T]{1}[A-Z0-9]{8}$', $cif)){
		   		return 3;
		  	}else{
		   		return -3;
		  	}
		 	}
		 
		 	//XYZ
		 	if (preg_match('/^[XYZ]{1}/', $cif)){
		  	if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1)){
		   		return 3;
		  	}else{
		   		return -3;
		  	}
		  }
		 	
		 	//si todavia no se ha verificado devuelve error
		  return 0;
		
		}
		
		function consultar_prohibido_apuestas($dni){
			try{	
			  $conn = new PDO('mysql:host=149.202.82.135;dbname=GDP-APUESTAS; charset=utf8', 'userGDP-APUESTAS', 'Eg9ov!80');
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
			  echo "ERROR: " . $e->getMessage();
			}

			$sql = $conn->prepare('SELECT * FROM prohibidos WHERE DNI LIKE "%'.$dni.'%" AND TIPO LIKE "A" ORDER BY FECHA DESC LIMIT 1');
			$sql->execute();
			if($sql->rowCount() != 0){ return "SI"; }else{ return "NO"; }
		}
		
		function consultar_prohibido_especiales($dni){
			try{	
			  $conn = new PDO('mysql:host=149.202.82.135;dbname=GDP; charset=utf8', 'userGDP', '13579GDP');
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
			  echo "ERROR: " . $e->getMessage();
			}

			$sql = $conn->prepare('SELECT * FROM prohibidos WHERE DNI LIKE "%'.$dni.'%" AND TIPO LIKE "A" ORDER BY FECHA DESC LIMIT 1');
			$sql->execute();
			if($sql->rowCount() != 0){ return "SI"; }else{ return "NO"; }
		}

		function consultar_prohibido_andalucia($dni){
			try{	
			  $conn = new PDO('mysql:host=149.202.82.135;dbname=prohibidos-almeria; charset=utf8', 'proalme', 'almeriaforbidden');
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
			  echo "ERROR: " . $e->getMessage();
			}

			$sql = $conn->prepare('SELECT * FROM prohibidos WHERE DNI LIKE "%'.$dni.'%" LIMIT 1');
			$sql->execute();
			if($sql->rowCount() != 0){ return "SI"; }else{ return "NO"; }
		}
		
		if(isset($dni) && $dni != ''){		
			$msg = '';			
			if ((comprobardni($dni) == -1) || (comprobardni($dni) == -2)	|| (comprobardni($dni) == -3) || (comprobardni($dni) == 0)){
				$msg="<p style='color: #b21a30; font-weight: bold'>DNI/NIF/NIE - MAL ESCRITO. INTENTALO DE NUEVO</p>";
				return $msg;
			}else{				
				if (consultar_prohibido_apuestas($dni) == "SI"){
					$msg.="<p style='color: #b21a30; font-weight: bold'>APUESTAS PROHIBIDO</p>";
				}else{
				  	$msg.="<p style='color: #449d44; font-weight: bold'>APUESTAS PERMITIDO</p>";
				}				  
				if (consultar_prohibido_especiales($dni) == "SI"){
					$msg.="<p style='color: #b21a30; font-weight: bold'>B ESPECIALES PROHIBIDO</p>";
				}else{
				  	$msg.="<p style='color: #449d44; font-weight: bold'>B ESPECIALES PERMITIDO</p>";
				}
				/*
				if (consultar_prohibido_andalucia($dni) == "SI"){
					$msg.="<p style='color: #b21a30; font-weight: bold'>ANDALUCÍA PROHIBIDO</p>";
				}else{
				  	$msg.="<p style='color: #449d44; font-weight: bold'>ANDALUCÍA PERMITIDO</p>";
				}
				*/			
			}			
			return $msg;			
		}		
	}

	function return_prohibidos2($dni){
		
		function comprobardni($cif) {
		
			$cif = strtoupper($cif);

		  for ($i = 0; $i < 9; $i ++){
		  	$num[$i] = substr($cif, $i, 1);
		  }
		 
		  //si no tiene un formato valido devuelve error
		  if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)){
		  	return 0;
		  }
		  
		  //comprobacion de NIFs estandar
		  if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif)){
		  	if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE',
		    	substr($cif, 0, 8) % 23, 1)){
		   		return 1;
		  	}else {
		   		return -1;
		  	}
		  }
		  
		  //algoritmo para comprobacion de codigos tipo CIF
		  $suma = $num[2] + $num[4] + $num[6];
		  for ($i = 1; $i < 8; $i += 2){
		  	$suma += substr((2 * $num[$i]),0,1) + substr((2 * $num[$i]),1,1);
		  }
		  
		  $n = 10 - substr($suma, strlen($suma) - 1, 1);
		  //comprobacion de NIFs especiales (se calculan como CIFs)
		  if (preg_match('/^[KLM]{1}/', $cif)){
		  	if ($num[8] == chr(64 + $n)){
		    	return 1;
		    }else{
		    	return -1;
		    }
		  }
		 
		  //comprobacion de CIFs
		  if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)){
		  	if ($num[8] == chr(64 + $n) || $num[8] ==
		    	substr($n, strlen($n) - 1, 1)){
		   	  return 2;
		  	}else{
		   		return -2;
		  	}
		  }
		  
		  //comprobacion de NIEs
		  //T
		  if (preg_match('/^[T]{1}/', $cif)){
		  	if ($num[8] == ereg('^[T]{1}[A-Z0-9]{8}$', $cif)){
		   		return 3;
		  	}else{
		   		return -3;
		  	}
		 	}
		 
		 	//XYZ
		 	if (preg_match('/^[XYZ]{1}/', $cif)){
		  	if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1)){
		   		return 3;
		  	}else{
		   		return -3;
		  	}
		  }
		 	
			//si todavia no se ha verificado devuelve error
		  	return 0;
		
		}
		
		function consultar_prohibido_apuestas($dni){
			try{	
			  $conn = new PDO('mysql:host=149.202.82.135;dbname=GDP-APUESTAS; charset=utf8', 'userGDP-APUESTAS', 'Eg9ov!80');
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
			  echo "ERROR: " . $e->getMessage();
			}

			$sql = $conn->prepare('SELECT * FROM prohibidos WHERE DNI LIKE "%'.$dni.'%" ORDER BY FECHA DESC LIMIT 1');
			$sql->execute();
			if($sql->rowCount() != 0){
				$usuario = $sql->fetch();
				if($usuario['TIPO'] == "A"){
					return "SI";
				}else{
					return "NO";
				}
			}else{
				return "NO";
			}
		}
		
		function consultar_prohibido_especiales($dni){
			try{	
			  $conn = new PDO('mysql:host=149.202.82.135;dbname=GDP; charset=utf8', 'userGDP', '13579GDP');
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
			  echo "ERROR: " . $e->getMessage();
			}

			$sql = $conn->prepare('SELECT * FROM prohibidos WHERE DNI LIKE "%'.$dni.'%" ORDER BY FECHA DESC LIMIT 1');
			$sql->execute();
			if($sql->rowCount() != 0){
				$usuario = $sql->fetch();
				if($usuario['TIPO'] == "A"){
					return "SI";
				}else{
					return "NO";
				}
			}else{
				return "NO";
			}
		}

		function consultar_prohibido_andalucia($dni){
			try{	
			  $conn = new PDO('mysql:host=149.202.82.135;dbname=prohibidos-almeria; charset=utf8', 'proalme', 'almeriaforbidden');
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
			  echo "ERROR: " . $e->getMessage();
			}

			$sql = $conn->prepare('SELECT * FROM prohibidos WHERE DNI LIKE "%'.$dni.'%" LIMIT 1');
			$sql->execute();
			if($sql->rowCount() != 0){ 
				return "SI"; 
			}else{ 
				return "NO"; 
			}
		}

		function consultar_prohibido_bimaser($dni){	
			if(strpos($dni, '58466772J') !== false || strpos($dni, '04750233C') !== false || strpos($dni, '77518909P') !== false){
				return "SI"; 
			}else{ 
				return "NO"; 
			}
		}
		
		if(isset($dni) && $dni != ''){	
			if ((comprobardni($dni) == -1) || (comprobardni($dni) == -2)	|| (comprobardni($dni) == -3) || (comprobardni($dni) == 0)){
				return false;
			}else{				
				if (consultar_prohibido_apuestas($dni) == "SI" || consultar_prohibido_especiales($dni) == "SI" || consultar_prohibido_andalucia($dni) == "SI" || consultar_prohibido_bimaser($dni) == "SI"){
					return false;
				}else{
				  	return true;
				}				
			}			
		}		
	}
}

?>
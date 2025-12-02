<?php

$usuar='atc_user';
$contrasena='#Gnh1j38';

try{
	
  $conn = new PDO('mysql:host=atc.apuestasdemurcia.es;dbname=averias; charset=utf8', $usuar, $contrasena);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
    echo "ERROR: " . $e->getMessage();
}

?>
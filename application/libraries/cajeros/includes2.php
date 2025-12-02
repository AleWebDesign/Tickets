<?php

$fecha = date("d/m/y - H:i");
$informefecha = date("d/m/y");
$informehora = date("H:i");

$fecha_interna = date("Y-m-d H:i:s");
$fecha_actual = strtotime($fecha_interna);

$file = 0;
$urlimagenes = "http://apuestasdemurcia.es/ruan/";

// Datos BD
$usuario = $cajero_info->usuario;
$clave = $cajero_info->clave;
$tabla = "appdb";

$salones = array($salon_nombre);
$servidor = array($cajero_info->servidor);
$collect = array($cajero_info->collect);
$limitearqueo = array($cajero_info->limite_arqueo);
$version = array($cajero_info->version);
$ips = array(0);

$salonescant = 1;

// Parametros
//$correo = "si";
$correo = "no";
$arqueo = 1;

// Configuraciones limites
$limitenoactivo = $cajero_info->limite_no_activo;
$limitemultimoneda = $cajero_info->limite_multimoneda;
$limitehopper = $cajero_info->limite_hopper;
$limitereciclador_Cassette1 = $cajero_info->limite_reciclador_cassette1;
$limitereciclador_Cassette2 = $cajero_info->limite_reciclador_cassette2;
$limitereciclador_Cassette3 = $cajero_info->limite_reciclador_cassette3;
$limitereciclador_Cassette4 = $cajero_info->limite_reciclador_cassette4;
$limitereciclador_Cassette5 = $cajero_info->limite_reciclador_cassette5;

?>
<?php

// Fecha y Hora
$fecha = date("d/m/y - H:i");
$informefecha = date("d/m/y");
$informehora = date("H:i");

$fecha_interna = date("Y-m-d H:i:s");
$fecha_actual = strtotime($fecha_interna);

// Si se ejecuta php -f archivo desde CLI
// $file = 1 -> php -f ope=bimaser
// $file = 0 -> url.php?ope=bimaser
// cargará includes_bimaser.php

$file = 0;

 //
 //if($_GET["file"] === null) echo "a is null\n";
 //if(isset($_GET["file"])) echo "a is set\n";
 //if(!empty($_GET["file"])) echo "a is not empty";
 //bimaser
 //campana
 //hegasa
 //

/*
if ($file == 1)
	{
		if ( (!isset($argv[1])) || ($argv[1] === null) || empty($argv[1]) ) { echo "ERROR : Esperaba php -f ope="; exit(); }
		$include =  'includes_'.substr($argv[1], 4).'.php';

	}else{
		//if ( (!isset($_GET["ope"])) || ($_GET["ope"] === null) || empty($_GET["ope"]) ) { echo "ERROR : Esperaba url.php?ope="; exit(); }
		$include =  'cajeros/includes_bimaser.php';
	}

if (file_exists($include)) {	include $include; } else { echo "ERROR : No existe el fichero : " . $include." ".FCPATH; exit(); }
*/
// Datos BD
$usuario = $cajero_info->usuario;
$clave = $cajero_info->clave;
$tabla = "ticketserver";
$urlimagenes = "https://apuestasdemurcia.es/ruan/";

$salones = array($salon_nombre);
$servidor = array($cajero_info->servidor);
$puerto = array($cajero_info->puerto);
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

//Liquidaciones
$liquid = 1;

//Debug (0= desactivado)
$debug = 0;

//Gtech ("si"= activado) //Tickets GTECH TITO
$gtech = "no";

// Destinatario
	$para = 'BIMASER <cambio.bimaser@apuestasmurcia.es>';
//$para = 'PABLO <pablo@bimaser.com>';


// Asunto
        $titulo = '[' . $fecha .'] · [MAQ DE CAMBIO]';


// Remitente
         $desde = 'MAQUINAS DE CAMBIO <alopez@apuestasdemurcia.com>';
        $cabeceras = 'From: ' . $desde . "\r\n";
       $cabeceras .= 'MIME-Version: 1.0' . "\r\n";
       $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
/*
function signo( $number ) {
    return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 );
}


function moneda($format, $number) {

    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.

              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';

    if (setlocale(LC_MONETARY, 0) == 'C') {

        setlocale(LC_MONETARY, 'es_ES');

    }

    $locale = localeconv();

    preg_match_all($regex, $format, $matches, PREG_SET_ORDER);

    foreach ($matches as $fmatch) {

        $value = floatval($number);

        $flags = array(

            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?

                           $match[1] : ' ',

            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,

            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?

                           $match[0] : '+',

            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,

            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0

        );

        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;

        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;

        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];

        $conversion = $fmatch[5];



        $positive = true;

        if ($value < 0) {

            $positive = false;

            $value  *= -1;

        }

        $letter = $positive ? 'p' : 'n';



        $prefix = $suffix = $cprefix = $csuffix = $signal = '';



        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];

        switch (true) {

            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':

                $prefix = $signal;

                break;

            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':

                $suffix = $signal;

                break;

            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':

                $cprefix = $signal;

                break;

            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':

                $csuffix = $signal;

                break;

            case $flags['usesignal'] == '(':

            case $locale["{$letter}_sign_posn"] == 0:

                $prefix = '(';

                $suffix = ')';

                break;

        }

        if (!$flags['nosimbol']) {

            $currency = $cprefix .

                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .

                        $csuffix;

        } else {

            $currency = '';

                    }

        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';



        $value = number_format($value, $right, $locale['mon_decimal_point'],

                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);

        $value = @explode($locale['mon_decimal_point'], $value);



        $n = strlen($prefix) + strlen($currency) + strlen($value[0]);

        if ($left > 0 && $left > $n) {

            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];

        }

        $value = implode($locale['mon_decimal_point'], $value);

        if ($locale["{$letter}_cs_precedes"]) {

            $value = $prefix . $currency . $space . $value . $suffix;

        } else {

            $value = $prefix . $value . $space . $currency . $suffix;

        }

        if ($width > 0) {

            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?

                     STR_PAD_RIGHT : STR_PAD_LEFT);

        }



        $format = str_replace($fmatch[0], $value, $format);
    }

    return $format;

}
*/
?>
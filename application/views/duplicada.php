<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title; ?></title>
	<link rel="icon" href="<?php echo base_url('files/img/favicon.ico?v=1'); ?>" type="image/x-icon" />
	<!-- jQuery -->
	<script src="<?php echo base_url('files/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('files/js/ui/jquery-ui.min.js'); ?>"></script>
	<!-- BOOTSTRAP CSS -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/bootstrap.min.css'); ?>" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo base_url('files/css/bootstrap-theme.min.css'); ?>" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/style.css'); ?>">
	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/font-awesome/css/font-awesome.min.css'); ?>">
	<!-- BOOTSTRAP JS -->
	<script type="text/javascript" src="<?php echo base_url('files/js/bootstrap.min.js'); ?>" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
	<body>
		<div class="container">
			<div class="row" style="margin-top: 80px;">
				<div class="col-md-12">
					<div class="panel panel-danger login-panel">
						<div class="panel-heading">
							Incidencia duplicada
						</div>
						<div class="panel-body" style="padding: 15px;">
							<p style="font-weight: bold">
								ATENCION: Esta incidencia es duplicada, ya existen registros de iguales características en la base de datos. A fin de no sobrecargar el sistema con incidencias idénticas, dispones de dos opciones:
							</p>
							<p style="font-weight: bold">
								1. Puedes volver a abrir alguna de las incidencias ya existentes:
							</p>
							<?php if(isset($html_duplicados) && $html_duplicados != ''){ echo $html_duplicados; } ?>
							<p style="font-weight: bold">
								2. Si consideras que esta es una incidencia nueva no relacionada con ninguna otra, puedes volver a la incidencia anterior y probar a modificar alguna de las características de la misma para evitar que se considere duplicada, prestando especial atención al texto de descripción de la misma (pruebe a ampliarlo o mejorarlo).
							</p>
							<a href="<?php echo $_SERVER["HTTP_REFERER"]; ?>" class="btn btn-danger">VOLVER A LA INCIDENCIA ANTERIOR</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
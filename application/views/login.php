<?php
if($this->session->userdata('logged_in')){
    header("Location: ".base_url('gestion'));
}
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
			<div class="row">
				<div class="col-md-12 div_logo">
					<img class="estilo_propio_logo" alt="Apuestas de Murcia" title="Apuestas de Murcia" src="<?php echo base_url('files/img/logo_adm_blanco.jpg'); ?>">
				</div>
				<div class="col-md-12">
					<div class="panel panel-danger login-panel">
						<div class="panel-heading">
						</div>
						<div class="panel-body login-cuerpo" style="padding: 15px;">
							<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
							<?php echo validation_errors(); ?>
							<?php echo form_open('login'); ?>
							<div class="input-group login-input login-class" style="width: auto">
								<span class="input-group-addon" id="basic-addon1">
									<span class="glyphicon glyphicon-user"></span>
								</span>
								<input type="text" name="user" class="form-control" placeholder="Usuario" aria-describedby="basic-addon1">
							</div>
							<div class="input-group login-input login-class" style="width: auto">
								<span class="input-group-addon" id="basic-addon1">
									<span class="glyphicon glyphicon-link"></span>
								</span>
								<input type="password" name="pass" class="form-control" placeholder="Contrase&ntilde;a" aria-describedby="basic-addon1">
							</div>							
							<div class="btn-group login-class">
								<button type="submit" class="btn btn-danger dropdown-toggle">
									Aceptar 
								</button> 
							</div>
							<div class="input-group login-input" style="margin: 2% auto">
								<a style="float: right; font-size: 13px;" href="<?php echo base_url('recuperar_pass'); ?>">Recuperar contraseña</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
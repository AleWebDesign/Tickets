<?php
if(!$this->session->userdata('logged_in')){
	header("Location: ".base_url('cerrar_sesion'));
}
defined('BASEPATH') OR exit('No direct script access allowed');

$forbidden_users = array(86,87);

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<title><?php echo $title; ?></title>
	<link rel="icon" href="<?php echo base_url('files/img/favicon.ico?v=1'); ?>" type="image/x-icon" />
	<!-- jQuery -->
	<script src="<?php echo base_url('files/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('files/js/ui/jquery-ui.min.js'); ?>"></script>
	<script src="<?php echo base_url('files/js/barcode/exif.js'); ?>" type="text/javascript"></script></head>
	<!-- BOOTSTRAP CSS -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/bootstrap.min.css'); ?>" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo base_url('files/css/bootstrap-theme.min.css'); ?>" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/style.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('files/js/ui/jquery-ui.css'); ?>">
	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/font-awesome/css/font-awesome.min.css'); ?>">
	<!-- BOOTSTRAP JS -->
	<script type="text/javascript" src="<?php echo base_url('files/js/bootstrap.min.js'); ?>" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- BOOTSTRAP FILE INPUT -->
	<link href="<?php echo base_url('files/bootstrap-fileinput/css/fileinput.min.css'); ?>" media="all" rel="stylesheet" type="text/css" />
	<script src="<?php echo base_url('files/bootstrap-fileinput/js/plugins/piexif.min.js'); ?>"></script>
	<script src="<?php echo base_url('files/bootstrap-fileinput/js/plugins/sortable.min.js'); ?>"></script>
	<script src="<?php echo base_url('files/bootstrap-fileinput/js/plugins/purify.min.js'); ?>"></script>
	<script src="<?php echo base_url('files/bootstrap-fileinput/js/fileinput.min.js'); ?>"></script>
	<script src="<?php echo base_url('files/bootstrap-fileinput/js/locales/es.js'); ?>"></script>
	<!-- BOOTSTRAP DATEPICKER -->
	<script src="<?php echo base_url('files/moment/min/moment.min.js'); ?>"></script>
  <script src="<?php echo base_url('files/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'); ?>"></script>
  <script src="<?php echo base_url('files/moment/locale/es.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('files/clockpicker/dist/bootstrap-clockpicker.min.js'); ?>"></script>	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('files/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('files/clockpicker/dist/bootstrap-clockpicker.min.css'); ?>">
	<!-- select2 -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse" style="background: #d80039; border: none; z-index: 10; position: fixed; width: 100%">
    <div class="container-fluid">
      <div class="navbar-header">
        <button style="border: none" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand logo_a" href="<?php echo base_url('gestion'); ?>">
        	<img class="logo_img" alt="Apuestas de Murcia" title="Apuestas de Murcia" src="<?php echo base_url('files/img/logo_tickets.png'); ?>">
        	<?php if($total_incidencias_pendientes > 0){ ?>
        	<span id="logo_badge" class="badge" style="display: none; background-color: #f8bc2f; color: #000" class="badge">
        		<?php echo $total_incidencias_pendientes; ?>
        	</span>
        	<?php } ?>
        </a>
      </div>
      <div id="navbar" class="navbar-collapse collapse" style="height: 500px; overflow-y: auto;">
        <ul class="nav navbar-nav">        	
        	<?php if($this->session->userdata('logged_in')['permiso_incidencias'] == 1){ ?>
          <li>
          	<a href="<?php echo base_url('gestion'); ?>">
          		<!-- Incidencias pendientes -->
          		<span style="background-color: #f8bc2f; color: #000" class="badge"><?php if($total_incidencias_pendientes > 0){ echo $total_incidencias_pendientes; } ?></span>
          		<!-- -->
          		<span class="glyphicon glyphicon-eye-open"></span> Incidencias
          	</a>
          </li>
          <?php } ?>
          <?php if(($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && !in_array($this->session->userdata('logged_in')['id'], $forbidden_users)){ ?>
        	<?php if($this->session->userdata('logged_in')['permiso_cajeros'] == 1){ ?>
        	<li>
          	<a href="<?php echo base_url('cajeros'); ?>">
          		<span class="glyphicon glyphicon-euro"></span> Cajeros
          	</a>
          </li>
        	<?php } } ?>
          
          <?php if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] != 24){ ?>
          <li>
            <a href="<?php echo base_url('datafono'); ?>">
              <span class="glyphicon glyphicon-modal-window"></span> Datáfonos
            </a>
          </li>
          <?php } ?>
        	
        	<?php if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && !in_array($this->session->userdata('logged_in')['id'], $forbidden_users)){ ?>
        	<?php if($this->session->userdata('logged_in')['permiso_ruletas'] == 1){ ?>
        	<li>
          	<a href="<?php echo base_url('ruletas'); ?>">
          		<span class="glyphicon glyphicon-record"></span> Ruletas
          	</a>
          </li>
        	<?php } } ?>

        	<?php if($this->session->userdata('logged_in')['id'] == 36 || $this->session->userdata('logged_in')['rol'] == 6){ ?>

          <li>
            <a href="<?php echo base_url('personal'); ?>">
              <span id="nav_user_icon1" class="glyphicon glyphicon-user"></span><span id="nav_user_icon2" style="margin-left: -5px" class="glyphicon glyphicon-user"></span> Personal
            </a>
          </li>

          <?php } ?>
        	
          <?php if($this->session->userdata('logged_in')['rol'] == 6){ ?>
            
          <li>
            <a href="<?php echo base_url('visitas'); ?>">
              <span class="glyphicon glyphicon-lock"></span> Visitas
            </a>
          </li>

          <?php } ?>

          <?php if($this->session->userdata('logged_in')['rol'] == 6){ ?>
          
          <li>
          	<a href="<?php echo base_url('informes'); ?>">
          		<span class="glyphicon glyphicon-paperclip"></span> Informes
          	</a>
          </li>

        	<?php } ?>
        	
        	<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 6){ ?>
        	<?php if($this->session->userdata('logged_in')['permiso_combustible'] == 1){ ?>
        	<li id="gasoil">
          	<a href="<?php echo base_url('gasoil'); ?>">
          		<img alt="Gas" title="Gas" src="<?php echo base_url('files/img/gas_station.png'); ?>"> Gasoil
          	</a>
          </li>
        	<?php } } ?>

          <?php if(($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4) && $this->session->userdata('logged_in')['acceso'] != 24){ ?>
          <li>
            <a href="<?php echo base_url('registros'); ?>">
              <span class="glyphicon glyphicon-refresh"></span> Movimientos
            </a>
          </li>
          <?php } ?>

          <?php if($this->session->userdata('logged_in')['acceso'] == 69 || $this->session->userdata('logged_in')['acceso'] == 82 || $this->session->userdata('logged_in')['acceso'] == 148 || $this->session->userdata('logged_in')['acceso'] == 200 || $this->session->userdata('logged_in')['acceso'] == 414 || $this->session->userdata('logged_in')['acceso'] == 630){ ?>
          <li>
            <a href="<?php echo base_url('prohibidos'); ?>">
              <span class="glyphicon glyphicon-minus-sign"></span> Prohibidos
            </a>
          </li>
          <?php } ?>

          <?php if($this->session->userdata('logged_in')['rol'] == 3){ ?>
          <li>
            <a href="<?php echo base_url('datafono'); ?>">
              <span class="glyphicon glyphicon-modal-window"></span> Datáfono
            </a>
          </li>
          <?php } ?>          
        	<!--
        	<li>
          	<a href="<?php echo base_url('renting'); ?>">
          		<span class="glyphicon glyphicon-road"></span> Renting
          	</a>
          </li>
          -->
        	
        	<?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 8 || $this->session->userdata('logged_in')['rol'] == 9){ ?>
        	<?php if($this->session->userdata('logged_in')['permiso_locales'] == 1){ ?>
        	<li>
          	<a href="<?php echo base_url('locales/1'); ?>">
          		<span class="glyphicon glyphicon-home"></span> Locales
          	</a>
          </li>
        	<?php } } ?>       	
          
          <?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7){ ?>
          <?php if($this->session->userdata('logged_in')['permiso_maquinas'] == 1){ ?>
        	<li>
          	<a href="<?php echo base_url('maquinas/1'); ?>">
          		<span class="glyphicon glyphicon-modal-window"></span> Máquinas
          	</a>
          </li>
        	<?php } ?>
        	<?php } ?>

          <?php if($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] != 24){ ?>
          <li>
            <a href="<?php echo base_url('registro_dni_aupabet'); ?>">
              <span class="glyphicon glyphicon-credit-card"></span> DNI AupabetTPV
            </a>
          </li>
          <?php } ?>
        	
        	<?php if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['rol'] == 4 && ($this->session->userdata('logged_in')['acceso'] == 21 || $this->session->userdata('logged_in')['acceso'] == 24 || $this->session->userdata('logged_in')['acceso'] == 41)){ ?>
        	<li>
          	<a href="<?php echo base_url('recaudar'); ?>">
          		<span class="glyphicon glyphicon-save"></span> Recaudar
          	</a>
          </li>
          <?php } ?>
          
          <?php if($this->session->userdata('logged_in')['rol'] == 4 && ($this->session->userdata('logged_in')['acceso'] == 24 || $this->session->userdata('logged_in')['acceso'] == 41)){ ?>
          <li>
          	<a href="<?php echo base_url('recaudar_salones_contador'); ?>">
          		<span class="glyphicon glyphicon-save"></span> Recaudar Salones
          	</a>
          </li>
        	<?php } ?>

          <?php if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 5 && $this->session->userdata('logged_in')['rol'] != 7){ if($jornada_auto == 0){ ?>
          <li>
            <a href="<?php echo base_url('control_horario'); ?>">
              <span class="glyphicon glyphicon-time"></span> Jornada
            </a>
          </li>
          <?php } } ?>
        	
        	<?php if(($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7)){ ?>
        	<li>
          	<a href="<?php echo base_url('usuarios/1'); ?>">
          		<span id="nav_user_icon1" class="glyphicon glyphicon-user"></span><span id="nav_user_icon2" style="margin-left: -5px" class="glyphicon glyphicon-user"></span> Usuarios
          	</a>
          </li>
          <!--
          <li>
          	<a href="<?php echo base_url('departamentos'); ?>">
          		<span class="glyphicon glyphicon-list-alt"></span> Departamentos
          	</a>
          </li>
          -->
        	<?php } ?>
        	
        	<?php if($this->session->userdata('logged_in')['id'] == 36){ ?>
        	<li>
          	<a href="<?php echo base_url('informes_tickets'); ?>">
          		<span class="glyphicon glyphicon-paperclip"></span> Informes
          	</a>
          </li>
          <?php } ?>
          
          <!--
          <li>
          	<a href="<?php echo base_url('informes_recaudaciones'); ?>">
          		<span class="glyphicon glyphicon-euro"></span> Recaudaciones
          	</a>
          </li>
          
          <li>
          	<a href="<?php echo base_url('informes_recaudaciones_salones'); ?>">
          		<span class="glyphicon glyphicon-euro"></span> Salones
          	</a>
          </li>
          
        	<li>
          	<a href="<?php echo base_url('guardias'); ?>">
          		<span class="glyphicon glyphicon-calendar"></span> Guardias
          	</a>
          </li>
        	-->

          <?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){ if($jornada_auto == 0){ ?>
          <li>
            <a href="<?php echo base_url('informes_horarios'); ?>">
              <span class="glyphicon glyphicon-time"></span> Horarios
            </a>
          </li>
          <?php } } ?>
        	
        	<li>
          	<a href="<?php echo base_url('cuenta'); ?>">
          		<span class="glyphicon glyphicon-user"></span> <?php echo $this->session->userdata('logged_in')['user']; ?>
          	</a>
          </li>
          
          <li>
          	<a href="<?php echo base_url('cerrar_sesion'); ?>" class="navbar-link">
          		<span class="glyphicon glyphicon-log-out"></span> Salir
          	</a>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
  </nav>
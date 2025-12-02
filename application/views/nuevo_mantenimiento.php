<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('mantenimiento'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Mantenimiento</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('nuevo_mantenimiento'); ?>" style="color: #000; text-decoration: none">Nuevo</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('nuevo_mantenimiento_form/'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos
				</div>
				<div class="panel-body">
					<div class="col-md-2 col-sm-12 inputs">
						<label>Zona</label>
						<div class="input-group">
						  <select class="form-control" id="zona" name="zona" required>
						  	<option value="0">Todas</option>
						  	<?php echo $html_zonas; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Salón</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="salon" name="salon" required>
						  	<option value="0">Todos</option>
						  	<?php echo $html_salones; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Tipo Máquina</label>
						<div class="input-group">
						  <select class="form-control" id="tipo_maquina" name="tipo_maquina" required>
						  	<?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['id'] == 40){ ?>
						  	<option value="10" selected>Apuestas</option>
						  	<?php }else{ ?>
						  	<option value="0">Todas</option>
						  	<option value="1">Ruletas</option>
						  	<option value="2">B</option>
						  	<option value="3">B Especial</option>
						  	<option value="5">Cajero</option>
						  	<option value="10">Apuestas</option>
						  	<?php } ?>
							</select>
						</div>						
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<div class="btn-group" style="margin: 9% 0;">
							<button type="submit" class="btn btn-danger dropdown-toggle">
								Aceptar 
							</button> 
						</div>
					</div>			
				</div>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('mantenimiento'); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>		
  </div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_crear_mantenimientos.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('.js-example-basic-single').select2();
	});
</script>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('departamentos'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Departamentos</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('nuevo_departamento'); ?>" style="color: #000; text-decoration: none">Nuevo</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p style='font-weight: bold'>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('nuevo_departamento_form/'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos
				</div>
				<div class="panel-body">
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Grupo</label>
						<div class="input-group">
							<input class="form-control" type="text" id="grupo" name="grupo" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Nombre</label>
						<div class="input-group">
							<input class="form-control" type="text" id="nombre" name="nombre" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>E-mail</label>
						<div class="input-group">
							<input class="form-control" type="email" id="email" name="email" required>
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
				<a id="volver_button" href="<?php echo base_url('departamentos'); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div> 	
  </div>
</body>
</html>
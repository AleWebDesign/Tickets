<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('personal'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Personal</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				Cambiar salon personal
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('cambiar_salon_personal_form/', 'id="myform"'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Nuevo salón
				</div>
				<div class="panel-body">
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Operadora</label>
						<div class="input-group">
						  <select class="form-control" id="operador" name="operador">
						  	<option value="0">Ninguno</option>
						  	<?php echo $html_op; ?>
							</select>
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Salón</label>
						<div class="input-group">
						  <select class="form-control" id="salon" name="salon">
						  	<option value="0">Ninguno</option>
						  	<?php echo $html_salon; ?>
							</select>
						</div>
					</div>
				</div>				
			</div>
		</div>
  		<div class="col-md-12" style="float: left; width: 100%;">
			<div class="btn-group pull-right" style="margin: 2% 0;">
				<input type="hidden" value="<?php echo $persona->id; ?>" name="id">
				<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
					Aceptar 
				</button> 
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
			<a id="volver_button" href="<?php echo base_url('personal/1'); ?>" class="btn btn-warning dropdown-toggle">
				Volver
			</a> 
		</div>
  	</div>
  	<script type="text/javascript" src="<?php echo base_url('files/js/script_crear_personal.js'); ?>"></script>
</body>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('locales/1'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Locales</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('nuevo_local'); ?>" style="color: #000; text-decoration: none">Nuevo</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p style='font-weight: bold'>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('nuevo_local_form/'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos
				</div>
				<div class="panel-body">
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>Operadora</label>
						<div class="input-group">
							<select class="js-example-basic-single" id="operador" name="operador" required>
						  	<option value="">Operadora...</option>
								<?php echo $html_operadoras; ?>
								</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>Nombre</label>
						<div class="input-group">
							<input class="form-control" type="text" id="nombre" name="nombre" required>
						</div>
					</div>					
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>Población</label>
						<div class="input-group">
							<input class="form-control" type="text" id="poblacion" name="poblacion" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>Teléfono</label>
						<div class="input-group">
							<input class="form-control" type="text" id="telefono" name="telefono">
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>E-mail</label>
						<div class="input-group">
							<input class="form-control" type="email" id="email" name="email">
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>IP Internet</label>
						<div class="input-group">
							<input class="form-control" type="text" id="ip_internet" name="ip_internet">
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>IP WAN Euskaltel</label>
						<div class="input-group">
							<input class="form-control" type="text" id="ip_wan_euskaltel" name="ip_wan_euskaltel">
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>IP LAN Euskaltel</label>
						<div class="input-group">
							<input class="form-control" type="text" id="ip_lan_euskaltel" name="ip_lan_euskaltel">
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>Dirección</label>
						<div class="input-group">
							<textarea class="form-control" name="direccion" rows="6" placeholder="Dirección..." required></textarea>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>Horario</label>
						<div class="input-group">
							<textarea class="form-control" name="horario" rows="6" placeholder="Horario..."></textarea>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px">
						<label>Fecha de alta</label>
						<div class="input-group">
						  <input class="form-control" type="text" id="fecha_alta" name="fecha_alta" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 30px 10px 10px"> 
						 <div class="btn-group" style="display: block">          
			           		<label>Activo</label>
			           		<input type="checkbox" name="activo" id="activo">
			            </div>
			        </div>
	            	<div class="col-md-2 col-sm-12" style="padding: 30px 10px 10px">
						<button type="submit" class="btn btn-danger dropdown-toggle">
							Aceptar 
						</button> 
					</div>
	        	</div>
			</div>
		</div>
	</div>
	<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
			<a id="volver_button" href="<?php echo base_url('locales/1'); ?>" class="btn btn-warning dropdown-toggle">
				Volver
			</a> 
	</div> 	
</div>
</body>
<script type="text/javascript">
$(document).ready(function() {
	$('.js-example-basic-single').select2();
});

$(function () {
    $('#fecha_alta').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es', minDate:new Date() });
});
</script>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('zonas'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Zonas</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('asignar_tecnicos_zonas'); ?>" style="color: #000; text-decoration: none">Asignar técnicos zona</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('asignar_tecnicos_zona_form/'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos
				</div>
				<div class="panel-body">
					<div class="col-md-12" style="font-weight: bold; margin-top: 15px;">
						<p>Mantener pulsado ctrl o shift para seleccionar múltiples técnicos. Los técnicos resaltados ya están asignados a esa zona, puede desmarcarlos si lo desea.</p>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Nombre zona</label>
						<div class="input-group">
							<input class="form-control" type="text" id="nombre" name="nombre" value="<?php echo $zona->zona; ?>" disabled>
						</div>
					</div>
			    <div class="col-md-2 col-sm-12 inputs">
			      <label for="sel1">Técnicos</label>
			      <select multiple class="form-control" id="sel2" name="tecnicos[]" style="height: 300px">
			        <?php echo $tecnicos; ?>
			      </select>
			    </div>
					<div class="col-md-2 col-sm-12 inputs">
						<div class="btn-group" style="margin: 9% 0;">
							<input type="hidden" value="<?php echo $id; ?>" name="id_zona">
							<button type="submit" class="btn btn-danger dropdown-toggle">
								Aceptar 
							</button> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('zonas'); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
  <script type="text/javascript" src="<?php echo base_url('files/js/script_crear_zonas.js'); ?>"></script>
</body>
</html>
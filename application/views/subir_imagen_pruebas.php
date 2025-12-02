<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
					Subir imagen
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('subir_imagen_pruebas_form/', 'id="myform"'); ?>
			<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Imagen<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
				</div>
				<div class="panel-body">
					<div class="col-md-3 col-sm-12" style="padding: 10px">
						<label>Imágen error</label>
						<div class="input-group">
							<input class="form-control" type="file" id="error_imagen" name="error_imagen">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12" style="float: left; width: 100%;">
				<div class="btn-group">
					<button type="submit" class="btn btn-danger dropdown-toggle">
						Aceptar 
					</button> 
				</div>
			</div>
		</div>
	</div>
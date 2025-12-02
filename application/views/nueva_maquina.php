<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('maquinas/1'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Máquinas</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('nueva_maquina'); ?>" style="color: #000; text-decoration: none">Nueva</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('nueva_maquina_form/'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos
				</div>
				<div class="panel-body">
					<div class="col-md-2 col-sm-12 inputs">
						<label>Salón</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="salon" name="salon" required>
						  	<option value="">Salón...</option>
						  	<?php if(isset($html_salones)){ echo $html_salones; } ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Fabricante</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="fabri" name="fabri" required>
						  	<option value="">Fabricante...</option>
						  	<?php if(isset($html_fabricantes)){ echo $html_fabricantes; } ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Modelo</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="modelo" name="modelo" disabled required>
						  	<option value="">Modelo...</option>
							</select>
						</div>
					</div>
					<div class="col-md-1 col-sm-12 inputs">
						<label>Puestos</label>
						<div class="input-group">
						  <select class="form-control" id="puestos" name="puestos" disabled required>
						  	<option value="">Puestos...</option>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1" style="display: none">
						<label>Identificador 1</label>
						<div class="input-group">
							<input class="form-control" type="text" id="serie1" name="serie1">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie2" style="display: none">
						<label>Identificador 2</label>
						<div class="input-group">
							<input class="form-control" type="text" id="serie2" name="serie2">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie3" style="display: none">
						<label>Identificador 3</label>
						<div class="input-group">
							<input class="form-control" type="text" id="serie3" name="serie3">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie4" style="display: none">
						<label>Identificador 4</label>
						<div class="input-group">
							<input class="form-control" type="text" id="serie4" name="serie4">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie5" style="display: none">
						<label>Identificador 5</label>
						<div class="input-group">
							<input class="form-control" type="text" id="serie5" name="serie5">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<div class="btn-group" style="margin: 9% 0;">
							<?php if(isset($_GET['n'])){ ?>
							<input type="hidden" value="<?php echo $_GET['n']; ?>" name="cerrar">
							<?php } ?>
							<button type="submit" class="btn btn-danger dropdown-toggle">
								Aceptar 
							</button> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('maquinas/1'); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_crear_maquinas.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.js-example-basic-single').select2();
});
</script>
</html>
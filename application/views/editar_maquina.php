<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('maquinas/'.$this->uri->segment(4).'/'.$this->uri->segment(5).''); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Máquinas</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('editar_maquina/'.$maquina->id.''); ?>" style="color: #000; text-decoration: none">Editar</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('editar_maquina_form/'); ?>
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
						  	<?php echo $html_salones; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Fabricante</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="fabri" name="fabri" required>
						  	<option value="">Fabricante...</option>
						  	<?php echo $html_fabricantes; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Modelo</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="modelo" name="modelo" required>
						  	<option value="">Modelo...</option>
						  	<?php echo $html_modelos; ?>
							</select>
						</div>
					</div>
					<?php if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){ ?>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Monedero</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="monedero" name="monedero">
						  	<option value="">Monedero...</option>
						  	<?php echo $html_monederos; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Billetero</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="billetero" name="billetero">
						  	<option value="">Billetero...</option>
						  	<?php echo $html_billeteros; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Impresora</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="impresora" name="impresora">
						  	<option value="">Impresora...</option>
						  	<?php echo $html_impresoras; ?>
							</select>
						</div>
					</div>
					<?php } ?>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Identificador</label>
						<div class="input-group">
							<input class="form-control" type="text" id="maquina" name="maquina" <?php if(isset($maquina)){ echo 'value="'.$maquina->maquina.'"'; } ?> required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<div class="btn-group" style="margin: 9% 0;">
							<?php if(isset($maquina)){ ?>
							<input type="hidden" value="<?php echo $maquina->id; ?>" name="id">
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
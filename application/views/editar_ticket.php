<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3>Editar incidencia</h3>
		<hr/>
		<!-- Buscador -->
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('editar_ticket_form/'); ?>
		<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
			<div class="panel-heading">
				Editar incidencia nº <?php echo $ticket->id; ?>
			</div>
			<div class="panel-body">
				<div class="col-md-12 col-sm-12 inputs">
					<label>Situación</label>
					<div class="input-group">
					  <select class="form-control" id="situacion" name="situacion" required>
					  	<option value="">Situación...</option>
					  	<?php echo $html_situacion; ?>
						</select>
					</div>							
				</div>
				<div class="col-md-12 col-sm-12 inputs">
					<label>Tratamiento</label>
					<div class="input-group">
						<textarea class="form-control" name="trata_desc" rows="6" placeholder="Tratamiento de la incidencia..." required></textarea>
					</div>
				</div>
				<div class="btn-group pull-right" style="margin: 2%;">
					<input type="hidden" name="id_ticket" value="<?php echo $ticket->id; ?>">
					<button type="submit" class="btn btn-danger dropdown-toggle">
						Aceptar 
					</button> 
				</div>
			</div>
		</div>
  </div>
</body>
</html>
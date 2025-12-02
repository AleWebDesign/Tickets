<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;"><a href="<?php echo base_url('tickets_manuales'); ?>" style="color: #000; text-decoration: none">Tickets manuales</a></h3>
			<hr>
			<?php if(isset($error)){ ?>
			<div class="col-md-12" style="padding: 0">
				<p style="font-weight: bold"><?php echo $error; ?></p>
			</div>
			<?php } ?>
			<?php if($this->session->userdata('logged_in')['rol'] == 2){ ?>
			<?php if(isset($html_salones)){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('tickets_manuales_form/', 'id="myform"'); ?>
			<div class="col-md-3 col-sm-12">
				<div class="col-md-12" style="padding: 0" id="div_mantenimientos_centrar">
					<div class="input-group" style="width: 50%; float: left; padding-top: 2%"">
						<span class="input-group-addon" id="basic-addon1">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input id="datepicker1" type="text" name="fecha_inicio" class="form-control" placeholder="Desde" <?php if(isset($fecha_inicio)){ echo "value='".$fecha_inicio."'"; } ?>>
					</div>
					<div class="input-group" style="width: 48%; margin-left: 2%; float: left; padding-top: 2%"">
						<span class="input-group-addon" id="basic-addon1">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input id="datepicker2" type="text" name="fecha_fin" class="form-control" placeholder="Hasta" <?php if(isset($fecha_fin)){ echo "value='".$fecha_fin."'"; } ?>>
					</div>
					<div class="input-group" style="padding-top: 2%">
						<select class="form-control" id="salon" name="salon" required>
							<option value="">Salón...</option>
							<?php if(isset($html_salones)){ echo $html_salones; } ?>
						</select>
					</div>
		  	</div>
		  	<div class="btn-group pull-right" style="margin: 2% 0;">
					<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
						Aceptar 
					</button> 
				</div>
		  </div>
			<?php } ?>
			<?php if(isset($html_tickets)){ ?>
			<div class="col-md-12" id="version_escritorio" style="margin-top: 5%">
				<div class="panel panel-default" style="margin: 0">
					<table class="table tabla_incidencias">
						<thead>
							<tr>
								<th class="th_tabla">Incidencia</th>
								<th class="th_tabla">Fecha</th>
								<th class="th_tabla">Importe</th>
								<th class="th_tabla">Estado</th>
								<th class="th_tabla">Comentarios</th>
						</thead>
						<tbody id="tabla_incidencias">
							<?php echo $html_tickets; ?>
						</tbody>
					</table>
				</div> 	
	  	</div>
	  	<?php } ?>
	  	<?php if(isset($version_movil)){ ?>
	  	<div class="col-md-12" id="version_movil" style="margin-top: 5%; float: left; width: 100%">
	  		<?php echo $version_movil; ?>
	  	</div>
			<?php } ?>
			<?php }else if($this->session->userdata('logged_in')['rol'] == 3){ ?>
			<?php if(isset($html_salones)){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('tickets_manuales_form/', 'id="myform"'); ?>
			<div class="col-md-3 col-sm-12">
				<div class="col-md-12" style="padding: 0" id="div_mantenimientos_centrar">
					<div class="input-group" style="padding-top: 2%"">
						<span class="input-group-addon" id="basic-addon1">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input id="datepicker1" type="text" name="fecha" class="form-control" placeholder="Fecha" required>
					</div>
					<div class="input-group" style="padding-top: 2%">
						<select class="form-control" id="salon" name="salon" required>
							<option value="">Salón...</option>
							<?php if(isset($html_salones)){ echo $html_salones; } ?>
						</select>
					</div>
					<div class="input-group" style="padding-top: 2%"">
						<span class="input-group-addon" id="basic-addon1">
							<span class="glyphicon glyphicon-euro"></span>
						</span>
						<input type="text" name="importe" class="form-control" placeholder="Importe" required>
					</div>
					<div class="input-group" style="padding-top: 2%">
						<select class="form-control" id="concepto" name="concepto" required>
							<option value="">Concepto...</option>
							<?php if(isset($html_concepto)){ echo $html_concepto; } ?>
						</select>
					</div>
					<div class="input-group" style="padding-top: 2%">
						<textarea class="form-control" name="detalle" rows="6" placeholder="Detalle..."></textarea>
					</div>
		  	</div>
		  	<div class="btn-group pull-right" style="margin: 2% 0;">
					<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
						Solicitar Ticket
					</button> 
				</div>
		  </div>
			<?php } ?>
			<?php if(isset($html_solicitudes)){ ?>
			<div class="col-md-12" id="version_escritorio" style="margin-top: 5%">
				<div class="panel panel-default" style="margin: 0">
					<table class="table tabla_incidencias">
						<thead>
							<tr>
								<th class="th_tabla">Id</th>
								<th class="th_tabla">Fecha</th>
								<th class="th_tabla">Importe</th>
								<th class="th_tabla">Concepto</th>
								<th class="th_tabla">Detalle</th>
								<th class="th_tabla">Estado</th>
								<th class="th_tabla">Acciones</th>
						</thead>
						<tbody id="tabla_incidencias">
							<?php echo $html_solicitudes; ?>
						</tbody>
					</table>
				</div> 	
	  	</div>
	  	<?php } ?>
	  	<?php if(isset($version_movil)){ ?>
	  	<div class="col-md-12" id="version_movil" style="margin-top: 5%; float: left; width: 100%">
	  		<?php echo $version_movil; ?>
	  	</div>
			<?php } ?>
			<?php }else if($this->session->userdata('logged_in')['rol'] == 4){ ?>
			<?php if(isset($html_solicitudes)){ ?>
			<div class="col-md-12" id="version_escritorio">
				<div class="panel panel-default" style="margin: 0">
					<table class="table tabla_incidencias">
						<thead>
							<tr>
								<th class="th_tabla">Id</th>
								<th class="th_tabla">Fecha</th>
								<th class="th_tabla">Importe</th>
								<th class="th_tabla">Concepto</th>
								<th class="th_tabla">Detalle</th>
								<th class="th_tabla">Estado</th>
								<th class="th_tabla">Acciones</th>
						</thead>
						<tbody id="tabla_incidencias">
							<?php echo $html_solicitudes; ?>
						</tbody>
					</table>
				</div> 	
	  	</div>
	  	<?php } ?>
	  	<?php if(isset($version_movil)){ ?>
	  	<div class="col-md-12" id="version_movil" style="margin-top: 5%; float: left; width: 100%">
	  		<?php echo $version_movil; ?>
	  	</div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_tickets_manueales.js'); ?>"></script>
<script type="text/javascript">
  $(function(){
      $('#datepicker1,#datepicker2').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
  });
</script>
</html>

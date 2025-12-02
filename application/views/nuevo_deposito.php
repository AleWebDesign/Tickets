<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('gasoil'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Gasoil</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('nuevo_deposito'); ?>" style="color: #000; text-decoration: none">Añadir depósito</a>
			</h3>
			<hr>
			<?php if(isset($error_login)){ echo "<p style='font-weight: bold'>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('nuevo_deposito_form/'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos
				</div>
				<div class="panel-body">
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Litros</label>
						<div class="input-group">
							<input class="form-control" type="text" id="litros" name="litros" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Fecha</label>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
							<input id="datepicker" type="text" name="fecha" class="form-control" placeholder="Fecha">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Hora</label>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">
								<span class="glyphicon glyphicon-time"></span>
							</span>
							<input id="timepicker" type="text" name="hora" class="form-control" placeholder="Hora">
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
	</div>
</body>
<script type="text/javascript">
    $(function () {
        $('#datepicker').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
        $('#timepicker').clockpicker({ autoclose: true });
    });
</script>
</html>
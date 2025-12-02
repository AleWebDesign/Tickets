<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<style type="text/css">	
	@media (max-width: 768px) {
		#avisos_content, #avisos_alert{
			width: 100% !important;
		}
	}
	</style>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;"><a href="<?php echo base_url('cajeros'); ?>" style="color: #000; text-decoration: none">Cajeros</a></h3>
			<hr>
			<?php if(isset($error)){ ?>
			<div class="col-md-12" style="padding: 0">
				<p style="font-weight: bold"><?php echo $error; ?></p>
			</div>
			<?php } ?>
			<?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 7){ ?>
			<?php if(isset($html_salones)){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('cajero_form/', 'id="myform"'); ?>
			<div class="col-md-12" style="padding: 0" id="div_avisos">
				<div class="col-md-12 col-sm-12">
					<div id="avisos_alert" class="alert alert-info" role="alert" style="width: 35%; font-weight: bold; text-align: center; cursor: pointer">
						COMPROBANDO CAJEROS...	
					</div>
				</div>
			</div>
			<div class="col-md-12" style="padding: 0" id="div_mantenimientos_centrar">
				<div class="panel panel-default col-md-4 col-sm-12 paneles_form">
					<div class="panel-heading" style="cursor: pointer">
						Salón<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
					</div>
					<div class="panel-body">
						<div class="col-md-12 col-sm-12 inputs">
							<div class="input-group">
								<select class="js-example-basic-single" id="salon" name="salon" required>
								  	<option value="">Salón...</option>
								  	<?php echo $html_salones; ?>
								</select>
							</div>
						</div>
	  			</div>
	  		</div>
	  	</div>
			<?php } ?>
			<?php } ?>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="btn-group pull-right" style="margin: 2% 0;">
				<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
					Aceptar 
				</button> 
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_cajeros.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('.js-example-basic-single').select2();
	});
</script>
</html>
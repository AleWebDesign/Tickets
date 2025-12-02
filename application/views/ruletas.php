<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;"><a href="<?php echo base_url('ruletas'); ?>" style="color: #000; text-decoration: none">Ruletas</a></h3>
			<hr>
			<?php if(isset($error)){ ?>
			<div class="col-md-12" style="padding: 0">
				<p style="font-weight: bold"><?php echo $error; ?></p>
			</div>
			<?php } ?>
			<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
			<?php if(isset($tabla_ruletas)){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('ruleta_form/', 'id="myform"'); ?>
			<div class="col-md-12" style="padding: 0" id="div_mantenimientos_centrar">
				<div class="panel panel-default col-md-4 col-sm-12 paneles_form">
					<div class="panel-heading" style="cursor: pointer">
						Salón<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
					</div>
					<div class="panel-body">
						<div class="col-md-12 col-sm-12 inputs">
							<div class="input-group">
							  <select class="form-control" id="salon" name="salon" required>
							  	<option value="">Salón...</option>
							  	<?php echo $tabla_ruletas; ?>
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
<script type="text/javascript" src="<?php echo base_url('files/js/script_ruletas.js'); ?>"></script>
</html>
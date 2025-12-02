<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('gestion'); ?>" style="color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i>
				</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('recaudar'); ?>" style="color: #000; text-decoration: none">Recaudar máquinas</a>
			</h3>
			<hr>
			<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
			<?php if(isset($tabla_salones)){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('recaudar_form/', 'id="myform"'); ?>
			<div class="col-md-12" style="padding: 0" id="div_mantenimientos_centrar">
				<div class="panel panel-default col-md-4 col-sm-12 paneles_form">
					<div class="panel-heading" style="cursor: pointer">
						Salón<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
					</div>
					<div class="panel-body">
						<div class="col-md-12 col-sm-12" style="padding: 10px">
							<div class="input-group">
							  <select class="js-example-basic-single" id="salon" name="salon" required>
							  	<option value="">Salón...</option>
							  	<?php echo $tabla_salones; ?>
								</select>
							</div>
						</div>
	  			</div>
	  		</div>
	  	</div>
			<?php } ?>
			<?php } ?>
		</div>
		<?php if(isset($salon)){ ?>
		<div class="col-md-12">
			<h4 style="font-size: 20px; margin-top: 40px;"><a href="<?php echo base_url('recaudar'); ?>" style="color: #000; text-decoration: none">Máquinas</a></h4>
			<hr>
			<?php echo $html_maquinas; ?>
		</div>
		<div class="col-md-12">
			<?php echo $html_recaudar_salon; ?>
		</div>
		<?php } ?>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
    	$('.js-example-basic-single').select2();
	});
	
	$('#salon').on('change', function(){
		if($(this).val() != ''){
			$('#myform').submit();
		}
	});
	
	$('.recaudar').on('click', function(){
		window.location.href = $(this).data("href");
	});
	
	$('.norecaudar').on('click', function(){
		window.location.href = $(this).data("href");
	});
</script>
</html>
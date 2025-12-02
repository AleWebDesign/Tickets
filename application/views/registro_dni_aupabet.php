<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">	
	@media (max-width: 768px) {
		#avisos_content, #avisos_alert{
			width: 100% !important;
		}
		.version_escritorio{
			display: none;
		}
		.version_movil{
			display: block;
		}
	}
	@media (min-width: 769px) {
		#avisos_content, #avisos_alert{
			width: 100% !important;
		}
		.version_movil{
			display: none;
		}
		.version_escritorio{
			display: block;
		}
	}
</style>
<div class="container-fluid">
  	<h3 style="font-size: 20px;"><a href="<?php echo base_url('registro_dni_aupabet'); ?>" style="color: #000; text-decoration: none">Registro DNI</a></h3>
  	<div class="col-md-12">
		<?php if(isset($error)){ ?>
		<div class="col-md-12" style="padding: 0">
			<p style="font-weight: bold"><?php echo $error; ?></p>
		</div>
		<?php } ?>
		<?php if(isset($html_salones)){ ?>
		<div class="col-md-12" style="padding: 0">
			<?php 
				if(isset($html_avisos)){ echo $html_avisos; }
				if(isset($html_comprobaciones)){ echo $html_comprobaciones; }
			?>
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
	</div>
	<div class="col-md-12" id="registros" style="margin-top: 1%; margin-bottom: 5%; padding: 0">
		
	</div>
</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
    	$('.js-example-basic-single').select2();
	});

	$('#avisos_alert').on('click', function(){
		if($('#avisos_content').is(':visible')){
			$('#avisos_content').css('display', 'none');
		}else{
			$('#avisos_content').css('display', 'block');
		}
	});

	$('#salon').on('change', function(){
		var url = window.location.href;
		var arr = url.split("/");
		var s = $(this).val();
		$.ajax({
            type: "POST",
            url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_dni_credito",
            data: {
                salon: s
            },
            success: function(response){
            	$('#registros').html(response);
            }
        });
	});
</script>
</html>
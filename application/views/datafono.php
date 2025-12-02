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
  		<h3 style="font-size: 20px"><a href="<?php echo base_url('datafono'); ?>" style="color: #000; text-decoration: none">Datáfono</a></h3>		
		<!-- Tabla usuarios -->
		<hr/>
		<div class="col-md-12">
			<?php if($this->session->userdata('logged_in')['rol'] == 3){ ?>
			<?php if($cajero->bloqueo_camarero != 1){ ?>
			<h4 style="margin-bottom: 15px">Cajero desbloqueado</h4>
			<?php }else{ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('datafono_form/', 'id="myform"'); ?>
			<div class="col-md-3 col-sm-12 inputs">
				<div class="input-group">
					<p style="font-weight: bold">Introducir DNI</p>
					<input class="form-control" type="text" id="dni" name="dni" autocomplete="off" <?php if(isset($dni)){ echo "value='".$dni."'"; } ?> placeholder="DNI/NIF/NIE" required>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 inputs" style="float: left; width: 100%;">
				<div class="btn-group" style="margin: 1% 0 1% 0; width: 100%;">
					<button id="submit_button" type="submit" class="btn btn-warning">
						Desbloquear cajero 
					</button>
					<button id="ver" class="btn btn-info" style="margin-left: 1%;">Ver movimientos</button>
				</div>
			</div>
			<?php if(isset($error)){ echo $error; } ?>			
			<?php } ?>			
			<div class="col-md-12" id="movimientos" style="display: none; margin-top: 1%; margin-bottom: 5%; padding: 0">
				<?php echo $html_movimientos; ?>
			</div>
			<?php } ?>
			<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
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
				<div class="col-md-12" id="movimientos" style="margin-top: 1%; margin-bottom: 5%; padding: 0">
					
				</div>
			<?php } ?>
  		</div>
  	</div>
</body>
<script type="text/javascript" charset="utf-8">
	/*
	$('#desbloquear').on('click', function(){
		$.ajax({
	        type: "POST",
	        url: "../tickets/set_datafono_activo",
	        success: function(response){
	        	if(response){
	        		window.location.reload();
	        	}
	        },
	    }); 
	});
	*/

	$('#ver').on('click', function(e){
		e.preventDefault();
		if($('#movimientos:visible').length == 0){
			$('#movimientos').css('display', 'block');
		}else{
			$('#movimientos').css('display', 'none');
		}
	});		
</script>
<?php if($this->session->userdata('logged_in')['rol'] == 3){ ?>
<script type="text/javascript">
	var url = window.location.href;
	var arr = url.split("/");
    setTimeout(function(){
        window.location.href=arr[0] + "//atc.apuestasdemurcia.es/tickets/datafono";
    }, 60000);
</script>
<?php } ?>
<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
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
            url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_tickets_credito",
            data: {
                salon: s
            },
            success: function(response){
            	$('#movimientos').html(response);
            }
        });
	});
</script>
<?php } ?>
</html>

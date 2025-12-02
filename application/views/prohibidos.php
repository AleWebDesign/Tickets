<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<h3 style="font-size: 20px;"><a href="<?php echo base_url('prohibidos'); ?>" style="color: #000; text-decoration: none">Prohibidos</a></h3>
		<hr>
		<?php if($this->session->userdata('logged_in')['rol'] != 1 && $this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 7){ ?>
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('prohibidos_form/', 'id="myform"'); ?>
			<div class="col-md-12">
				<div class="panel panel-default col-md-2 col-sm-12 paneles_form">
					<div class="panel-heading" style="cursor: pointer">
						Consultar DNI/NIF/NIE<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
					</div>
					<div class="panel-body">
						<div class="col-md-12 col-sm-12 inputs">
							<div class="input-group">
							  <input class="form-control" type="text" id="dni" name="dni" <?php if(isset($dni)){ echo "value='".$dni."'"; } ?> placeholder="DNI/NIF/NIE" required>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 inputs" style="float: left; width: 100%;">
							<div class="btn-group pull-right" style="margin: 2% 0 5% 0;">
								<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
									Consultar 
								</button> 
							</div>
						</div>
						<?php if(isset($prohibidos)){ ?>
						<div class="col-md-12 col-sm-12 inputs" style="float: left; width: 100%;">
							<?php echo $prohibidos; ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>	
		</form>
		<?php }else{ ?>
		<div class="col-md-12">
			<form id="uploadForm" enctype="multipart/form-data" method="post" name="fileinfo">
				<div class="panel panel-default col-md-2 col-sm-12 paneles_form">
					<div class="panel-heading" style="cursor: pointer">
						Actualizar archivo PROHIBIDOS
					</div>
					<div class="panel-body">
						<div class="col-md-12 col-sm-12" style="padding: 10px">
							<div class="input-group">							
							  	<input class="form-control" id="archivo" type="file" name="archivo" required>							
							</div>
							<div class="btn-group pull-right" style="margin: 5% 1%;">
								<input id="submit" type="submit" value="Subir" class="btn btn-success dropdown-toggle">							
							</div>
						</div>
						<div id="subiendo_div" class="col-md-12 col-sm-12" style="float: right; width: 100%;">
							<p id="subiendo_p" style='font-weight: bold'>Formato de archivo válido: txt</p>
						</div>
					</div>
				</div>
			</form>
			<script type="text/javascript">
				$("#uploadForm").on('submit', function(e){
    				e.preventDefault();
    				$('#subiendo_p').html("Subiendo archivo, por favor espere...");
    				$('#submit').prop('disabled', true);
    				var file = $('#archivo').val();
    				var format = file.split(".");
    				if(format[1] != 'txt'){
    					$('#subiendo_p').html("Formato de archivo incorrecto");
    				}else{
    					var archivo = $("#archivo")[0].files[0];
    					if(archivo.size >= 1900000){
    						const chunkSize = 1900000;
    						var i = 1;
    						for (let start = 0; start < archivo.size; start += chunkSize) {
    							var fin = start + chunkSize;
    							const chunk = archivo.slice(start, fin);
    							const fd = new FormData;
							    fd.append('file', chunk, archivo.name);
							    fd.append('number', i);
							    $.ajax({
							        url: "http://atc.apuestasdemurcia.es/tickets/subir_archivo_prohibidos",
							        type: "POST",
							        data: fd,
							        contentType: false,
							        cache: false,
							        processData: false           
							    });
							    i++;
    						}
    						setTimeout(function(){
			                	$('#subiendo_p').html("Subida completa, procesando archivo...");
			                	$.ajax({
							        url: "http://atc.apuestasdemurcia.es/tickets/cargar_archivo_prohibidos",
							        type: "POST",
							        success: function(data){
							            $('#subiendo_p').html(data);
							            $('#submit').prop('disabled', false);
							        }           
							    });
			                }, 2000);
    					}else{
    						$.ajax({
						        url: "http://atc.apuestasdemurcia.es/tickets/subir_archivo_prohibidos",
						        type: "POST",
						        data: new FormData(this),
						        contentType: false,
						        cache: false,
						        processData: false          
						    });
						    setTimeout(function(){
			                	$('#subiendo_p').html("Subida completa, procesando archivo...");
			                	$.ajax({
							        url: "http://atc.apuestasdemurcia.es/tickets/cargar_archivo_prohibidos",
							        type: "POST",
							        success: function(data){
							        	$('#subiendo_p').html(data);
							        	$('#submit').prop('disabled', false);
							        }           
							    });
			                }, 2000);
    					}
    				}
				});
			</script>
			<div class="col-md-2 col-sm-12">
				&nbsp;
			</div>
			<div class="panel panel-default col-md-8 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Historial Actualizaciones
				</div>
				<div class="panel-body">
					<table class="table" style="margin-bottom: 0">
						<thead>
							<tr>
								<th class="th_tabla">Operadora</th>								
								<th class="th_tabla">Usuario</th>
								<th class="th_tabla">Base de datos</th>
								<th class="th_tabla">Fecha</th>
							</tr>
						</thead>
						<tbody>
							<?php echo $tabla_prohibidos; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('exportar_prohibidos/', 'id="myform"'); ?>
			<div class="panel panel-default col-md-2 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Exportar PROHIBIDOS<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
				</div>
				<div class="panel-body">
					<div class="col-md-12 col-sm-12 inputs">
						<div class="input-group">
						  <select class="form-control" id="db" name="db" required>
						  	<option value=''>Seleccione Base de Datos</option>
						  	<option value='1'>Apuestas</option>
						  	<option value='2'>Especiales</option>
						  </select>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 inputs" style="float: left; width: 100%;">
						<div class="btn-group pull-right" style="margin: 2% 0 5% 0;">
							<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
								Exportar 
							</button> 
						</div>
					</div>
					<?php if(isset($prohibidos)){ ?>
					<div class="col-md-12 col-sm-12 inputs" style="float: left; width: 100%;">
						<?php echo $prohibidos; ?>
					</div>
					<?php } ?>
				</div>
			</div>
			</form>
		</div>
		<?php } ?>
		</div>
	</div>
</body>
</html>
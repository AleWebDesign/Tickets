<?php defined('BASEPATH') OR exit('No direct script access allowed');

if($this->session->userdata('logged_in')['rol'] == 1){
		$page = 'gestion';
}else{
		if(isset($_SERVER['HTTP_REFERER'])){
				$server = $_SERVER['HTTP_REFERER'];
				$where = explode('/', $server);
				$page = end($where);
				if(strlen(trim($page)) == 0){
					$page = 'gestion';
				}
		}else{
				$page = 'gestion';
		}
}

?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url($page.'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Incidencias</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php if(isset($id_ticket)){ echo base_url('editar_incidencia/'.$id_ticket.''); } ?>" style="color: #000; text-decoration: none">Editar</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('editar_incidencia_form/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>
			<div class="col-md-6 col-sm-12" style="padding: 0">
				<div class="panel panel-default col-md-12 col-sm-12 paneles_form" style="margin-bottom: 5px">
					<div class="panel-heading" style="cursor: pointer">
						Datos Generales<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
					</div>
					<div class="panel-body">
						<div class="col-md-4 col-sm-12 inputs" <?php if($this->session->userdata('logged_in')['rol'] == 1){}else{ echo "style='display: none'"; } ?>>
							<label>Empresa</label>
							<div class="input-group">
							  	<select class="js-example-basic-single" name="empresa" id="empresa" required>
								 	<?php if(isset($html_empresas)){ echo $html_empresas; } ?>
								</select>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 inputs" <?php if($this->session->userdata('logged_in')['rol'] == 1 || ($situacion == 6 && $this->session->userdata('logged_in')['id'] == 93)){}else{ echo "style='display: none'"; } ?>>
	  						<label>Situación</label>
							<div class="input-group">
							  	<select class="js-example-basic-single" id="situacion" name="situacion" required>
								  	<?php if($this->session->userdata('logged_in')['rol'] == 1){ ?>
								  		<option value="">Situación...</option>
								  	<?php } ?>
								  	<?php if(isset($html_situacion)){ echo $html_situacion; } ?>
								</select>
							</div>					
						</div>
						<div class="col-md-4 col-sm-12 inputs" id="div_caduca_ticket" <?php if($situacion == 5){ }else{ echo 'style="display: none"'; } ?>>
							<label>Fecha de caducidad ticket</label>
							<div class="input-group">
							  <input class="form-control" type="text" id="caduca_ticket" name="caduca_ticket" value="<?php if(isset($fecha_caducidad) && $fecha_caducidad != ''){ echo $fecha_caducidad; } ?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-12 inputs" <?php if($this->session->userdata('logged_in')['rol'] == 1){}else{ echo "style='display: none'"; } ?>>
							<label>Operador</label>
							<div class="input-group">
							  	<select class="form-control" id="operador" name="operador" required>
							  		<option value="">Operadora...</option>
							  		<?php if(isset($html_operadoras)){ echo $html_operadoras; } ?>							
								</select>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 inputs">
							<label>Salón</label>
							<div class="input-group">
							  <select class="js-example-basic-single" id="salon" name="salon" <?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 3){ }else{ echo "disabled"; } ?> required>
							  	<option value="">Salón...</option>
							  	<?php if(isset($html_salones)){ echo $html_salones; } ?>
								</select>
							</div>
						</div>				
					</div>
				</div>
				<div class="panel panel-default col-md-12 col-sm-12 paneles_form" <?php if($this->session->userdata('logged_in')['rol'] == 1){ echo 'style="margin-bottom: 5px"'; }else{ echo "style='display: none'"; } ?>>
					<div class="panel-heading" style="cursor: pointer">
						Datos Orígen<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
					</div>
					<div class="panel-body">
						<div class="col-md-4 col-sm-12 inputs">
							<label>Orígen</label>
							<div class="input-group">
							  <select class="form-control" id="cliente_tipo" name="cliente_tipo" required>
									<?php if($this->session->userdata('logged_in')['rol'] == 1){ ?>
									<option value="">Orígen...</option>
									<?php } ?>
									<?php if(isset($html_tipo_cliente)){ echo $html_tipo_cliente; } ?>
								</select>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 inputs">
							<label>Nombre</label>
							<div class="input-group">
								<input class="form-control" type="text" id="cliente_nombre" name="cliente_nombre" list="lista_nombres" value="<?php if(isset($cliente_nombre) && $cliente_nombre != ''){ echo $cliente_nombre; }else if(isset($this->session->userdata('logged_in')['user'])  && $this->session->userdata('logged_in')['user'] != ''){ echo $this->session->userdata('logged_in')['user']; }else{ echo "ADM"; } ?>">
								<datalist id="lista_nombres">
									<?php echo $html_list_nombre; ?>
								</datalist>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 inputs">
							<label>Teléfono</label>
							<div class="input-group">
								<input class="form-control" type="text" id="cliente_telefono" name="cliente_telefono" value="<?php if(isset($cliente_telefono) && $cliente_telefono != ''){ echo $cliente_telefono; }else if(isset($this->session->userdata('logged_in')['rol']) && $this->session->userdata('logged_in')['rol'] != 1){ if(isset($this->session->userdata('logged_in')['telefono']) && $this->session->userdata('logged_in')['telefono'] != ''){ echo $this->session->userdata('logged_in')['telefono']; }else{ echo "968 272 869"; } } ?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-12 inputs">
							<label>Email</label>
							<div class="input-group">
								<input class="form-control" type="text" id="cliente_email" name="cliente_email" value="<?php if(isset($cliente_email)){ echo $cliente_email; } ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default col-md-12 col-sm-12 paneles_form" style="margin-bottom: 5px">
					<div class="panel-heading" style="cursor: pointer">
						Datos Incidencia<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
					</div>
					<div class="panel-body">
						<div class="col-md-3 col-sm-12 inputs">
							<label>Tipo avería</label>
							<div class="input-group">
							  <select class="form-control" id="gestion_tipo" name="gestion_tipo" required>
							  	<option value="">Tipo avería...</option>
							  	<?php if(isset($tipo_gestion)){ echo $tipo_gestion; } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 inputs">
							<label>Máquina</label>
							<div class="input-group">
							  <select class="form-control" id="error_maquina" name="error_maquina" required>
									<option value="">Máquina...</option>
									<?php if(isset($maquina_id)){ if($maquina_id == 0){ echo '<option value="0" selected>Sin asignar</option>'; }else{ echo '<option value="0">Sin asignar</option>'; } } ?>								
									<?php if(isset($html_maquinas)){ echo $html_maquinas; } ?>
								</select>
							</div>
						</div>
						<?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2){ ?>
						<div class="col-md-1 col-sm-12 inputs" id="div_nueva_maquina" style="display: none">
							<a class="btn btn-success dropdown-toggle" href="" id="link_nueva_maquina" target="_blank">+</a>
						</div>
						<?php } ?>
						<div class="col-md-3 col-sm-12 inputs">
							<label>Tipo error</label>
							<div class="input-group">
							  <select class="form-control" id="error_tipo" name="error_tipo" required>
									<option value="">Tipo error...</option>
									<?php if(isset($html_error_tipo)){ echo $html_error_tipo; } ?>
								</select>
							</div>
						</div>								
						<div class="col-md-3 col-sm-12 inputs">
							<label>Detalle error</label>
							<div class="input-group">
							  <select class="form-control" id="error_detalle" name="error_detalle" required>
									<option value="">Detalle error...</option>
									<?php if(isset($html_error_detalle)){ echo $html_error_detalle; } ?>
								</select>
							</div>
						</div>

						<div class="col-md-3 col-sm-12 inputs" id="div_cantidad_tarjetas" style="<?php if($detalle_error == 424){ echo 'display: block'; }else{ echo 'display: none'; } ?>">
							<label>Cantidad tarjetas</label>
							<div class="input-group">
							  <input class="form-control" type="number" id="cantidad_tarjetas" name="cantidad_tarjetas" value="<?php echo $cantidad_tarjetas; ?>">
							</div>
						</div>

						<div class="col-md-3 col-sm-12 inputs" id="div_importe_ticket" <?php if(!isset($ticket_manual)){ echo 'style="display: none"'; } ?>>
							<label>Importe ticket</label>
							<div class="input-group">
							  <input class="form-control" type="text" id="importe_ticket" name="importe_ticket" value="<?php if(!isset($ticket_manual)){ }else{ echo $ticket_manual->importe; } ?>">
							</div>
						</div>	
						<div class="col-md-3 col-sm-12 inputs">
							<label id="label_imagen">Imágen (Max: <?php echo ini_get('upload_max_filesize'); ?>).</label>
							<div class="input-group">
								<input class="form-control" type="file" id="error_imagen" name="error_imagen[]" accept="image/*" multiple>
							</div>
							<label>Imágen actual: <?php if(isset($imagen)){ if($imagen != ''){ echo $imagen; }else{ echo "Ninguna."; } } ?></label>
						</div>
						<div class="col-md-3 col-sm-12 inputs">
							<label>Asignado</label>
							<div class="input-group">
							  <select class="form-control" id="asignado" name="asignado">
									<?php if(isset($html_asignado)){ echo $html_asignado; } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 inputs">
							<label>Prioridad</label>
							<div class="input-group">
							  <select class="form-control" id="prioridad" name="prioridad" required>
									<option value="">Prioridad...</option>
									<?php if(isset($html_prioridad)){ echo $html_prioridad; } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 inputs" id="div_fecha_programada" style="display: none">
							<label>Programar fecha</label>
							<div class="input-group">
								<input class="form-control" type="text" id="fecha_programada" name="fecha_programada" value="<?php if(isset($fecha_programada) && trim($fecha_programada) != ''){ echo $fecha_programada; } ?>">
							</div>
						</div>
						<?php if($error_detalle == 571 || $error_detalle == 582){ ?>
						<div class="col-md-3 col-sm-12 inputs transporte_fields">
							<label>Nº serie/guia máquina(s)</label>
							<div class="input-group">
								<input class="form-control" type="text" id="guia_maquina" name="guia_maquina" value="<?php if($transporte != ''){ echo $transporte->guia_maquina; } ?>">
							</div>
						</div>
						<div class="col-md-3 col-sm-12 inputs transporte_fields">
							<label>Dirección entrega</label>
							<div class="input-group">
								<input class="form-control" type="text" id="direccion_entrega" name="direccion_entrega" value="<?php if($transporte != ''){ echo $transporte->direccion_entrega; } ?>">
							</div>
						</div>
						<div class="col-md-3 col-sm-12 inputs transporte_fields">
							<label>Teléfono destinatario</label>
							<div class="input-group">
								<input class="form-control" type="text" id="telefono_entrega" name="telefono_entrega" value="<?php if($transporte != ''){ echo $transporte->telefono_entrega; } ?>">
							</div>
						</div>
						<?php } ?>		
						<div class="col-md-12 col-sm-12 inputs">
							<label>Texto</label>
							<div class="input-group">
								<textarea class="form-control" name="error_desc" rows="6" placeholder="Descripcion de la incidencia..." required><?php if(isset($html_error_desc)){ echo $html_error_desc; } ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default col-md-12 col-sm-12 paneles_form" <?php if($this->session->userdata('logged_in')['rol'] == 1){ echo 'style="margin-bottom: 5px"'; }else{ echo "style='display: none'"; } ?>>
					<div class="panel-heading" style="cursor: pointer">
						Tratamiento<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
					</div>
					<div class="panel-body">
						<div class="col-md-4 col-sm-12 inputs">
							<label>Destino</label>
							<div class="input-group">
							  <select class="form-control" id="trata_destino" name="destino" required>
									<option value="">Destino...</option>
									<?php if(isset($html_departamento)){ echo $html_departamento; } ?>
								</select>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 inputs">
							<label>Texto</label>
							<div class="input-group">
								<textarea class="form-control" id="trata_desc" name="trata_desc" rows="6" placeholder="Tratamiento dado a la incidencia..."><?php if(isset($html_trata_desc)){ echo $html_trata_desc; } ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12" style="padding: 0">
				<div class="panel panel-default col-md-12 col-sm-12 paneles_form" style="margin-bottom: 5px">
					<div id="panel_historial" class="panel panel-default col-md-12 col-sm-12 paneles_form" style="margin-bottom: 5px">
							<?php if(isset($html_historial)){ echo $html_historial; } ?>
					</div>
					<div class="col-md-12 col-sm-12 inputs">
						<label>Añadir nueva línea</label>
						<div class="input-group">
							<textarea class="form-control" id="trata_new_desc" name="trata_new_desc" rows="6" placeholder="Tratamiento dado a la incidencia..."></textarea>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 btn-group" style="margin: 2% 0;">
					<input type="hidden" name="id_ticket" value="<?php if(isset($id_ticket)){ echo $id_ticket; } ?>">
					<input type="hidden" name="asignado_ya" value="<?php if(isset($asignado)){ echo $asignado; } ?>">
					<?php if($this->session->userdata('logged_in')['rol'] == 1){ ?>
					<button type="submit" class="btn btn-success dropdown-toggle" name="only_trata_solu" style="float: right; margin-left: 2%; border-radius: 4px;">
						Solucionar
					</button>
					<?php } ?>
					<?php if(isset($situacion)){ if($this->session->userdata('logged_in')['rol'] == 2 && ($situacion == 2 || $situacion == 14)){ ?>
					<button type="submit" class="btn btn-success dropdown-toggle" name="only_trata_solu" style="float: right; margin-left: 2%; border-radius: 4px;">
						Solucionar
					</button>
					<?php } } ?>
					<button type="submit" class="btn btn-warning dropdown-toggle" name="only_trata" style="float: right; border-radius: 4px;">
						Añadir línea
					</button>
				</div>
			</div>	
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<input type="hidden" id="id_destino" name="id_destino" value="<?php if(isset($id_destino)){ echo $id_destino; } ?>">
				<input type="hidden" name="id_ticket" value="<?php if(isset($id_ticket)){ echo $id_ticket; } ?>">
				<input type="hidden" name="asignado_ya" value="<?php if(isset($asignado)){ echo $asignado; } ?>">
				<button type="submit" class="btn btn-danger dropdown-toggle">
					Modificar ticket
				</button> 
				<a id="volver_button" href="<?php echo base_url($page.'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
  <div id="nueva_maquina" style="display: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%; background: #fff; border: 1px solid #ddd; z-index: 999">
  	<div class="col-md-12" style="">
			<h3 style="font-size: 20px;">
				<a href="#" id="cerrar_nueva_maquina" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Incidencias</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="nueva" style="color: #000; text-decoration: none">Nueva máquina</a><span class="cerrar_nueva_maquina" class="glyphicon glyphicon-remove-circle" style="float: right; cursor: pointer"></span>
			</h3>
			<hr/>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form" style="margin-bottom: 5px">
				<div class="panel-heading" style="cursor: pointer">
					Datos
				</div>
				<div class="panel-body">
					<div class="col-md-2 col-sm-12 inputs">
						<label>Salón</label>
						<div class="input-group">
						  <select class="form-control" id="salon_nueva_maquina" name="salon_nueva_maquina">
						  	<option value="">Salón...</option>
						  	<?php if(isset($html_salones)){ echo $html_salones; } ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Fabricante</label>
						<div class="input-group">
						  <select class="form-control" id="fabri_nueva_maquina" name="fabri_nueva_maquina" >
						  	<option value="">Fabricante...</option>
						  	<?php if(isset($html_fabricantes)){ echo $html_fabricantes; } ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Modelo</label>
						<div class="input-group">
						  <select class="form-control" id="modelo_nueva_maquina" name="modelo_nueva_maquina" disabled>
						  	<option value="">Modelo...</option>
							</select>
						</div>
					</div>
					<div class="col-md-1 col-sm-12 inputs">
						<label>Puestos</label>
						<div class="input-group">
						  <select class="form-control" id="puestos" name="puestos" disabled>
						  	<option value="">Puestos...</option>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1" style="display: none">
						<label>Identificador 1</label>
						<div class="input-group">
							<input class="form-control" type="text" id="serie1" name="serie1">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie2" style="display: none">
						<label>Identificador 2</label>
						<div class="input-group">
							<input class="form-control" type="text" id="serie2" name="serie2">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie3" style="display: none">
						<label>Identificador 3</label>
						<div class="input-group">
							<input class="form-control" type="text" id="serie3" name="serie3">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<div class="btn-group" style="margin: 9% 0;">
							<a href="" id="nueva_maquina_button" class="btn btn-danger dropdown-toggle">
								Aceptar 
							</a> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a href="#" class="cerrar_nueva_maquina btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
  <script type="text/javascript" src="<?php echo base_url('files/js/script_crear_incidencias.js'); ?>"></script>
</body>
<script type="text/javascript">
	$(document).ready(function() {
    	$('.js-example-basic-single').select2();
	});
</script>
<script type="text/javascript">
$(function () {
    $('#caduca_ticket').datetimepicker({ format: 'DD/MM/YYYY HH:mm:ss', locale: 'es' });
    $('#fecha_programada').datetimepicker({ format: 'DD/MM/YYYY HH:mm', locale: 'es', minDate:new Date() });
});
</script>
<script type="text/javascript">
$('#error_imagen').bind('change', function() {
		var val = '<?php echo ini_get('upload_max_filesize'); ?>';
		var res = val.substr(0, 1);
		var res2 = res*1024*1024; 
  	if(this.files[0].size > res2){
  		document.getElementById("label_imagen").innerHTML = "<span style='color: red'>LIMITE PERMITIDO EXCEDIDO</span>";
  	}
});
</script>
</html>
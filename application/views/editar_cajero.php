<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('maquinas/'.$this->uri->segment(4).'/'.$this->uri->segment(5).''); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Máquinas</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('editar_cajero/'.$maquina->id.''); ?>" style="color: #000; text-decoration: none">Editar Cajero <?php echo $salon; ?></a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('editar_cajero_form/'.$this->uri->segment(4).'/'.$this->uri->segment(5).''); ?>
			<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos Generales
				</div>
				<div class="panel-body" style="padding-bottom: 10px;">
					<div class="col-md-12 col-sm-6 inputs">
						<label>Identificador</label>
						<div class="input-group">
							<input class="form-control" type="text" id="maquina" name="maquina" <?php if(isset($maquina)){ echo 'value="'.$maquina->maquina.'"'; } ?> maxlength="15" required>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos de conexión
				</div>
				<div class="panel-body" style="padding-bottom: 10px;">
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label>IP</label>
						<div class="input-group">
							<input class="form-control" type="text" id="ip" name="ip" <?php if(isset($cajero)){ echo 'value="'.$cajero->servidor.'"'; } ?> maxlength="15" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-6 inputs inputs_cajero">
						<label>Puerto</label>
						<div class="input-group">
							<input class="form-control" type="text" id="puerto" name="puerto" <?php if(isset($cajero)){ echo 'value="'.$cajero->puerto.'"'; } ?> maxlength="4" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-6 inputs inputs_cajero">
						<label>Usuario</label>
						<div class="input-group">
							<input class="form-control" type="text" id="usuario" name="usuario" <?php if(isset($cajero)){ echo 'value="'.$cajero->usuario.'"'; } ?> maxlength="15" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-6 inputs inputs_cajero">
						<label>Clave</label>
						<div class="input-group">
							<input class="form-control" type="text" id="clave" name="clave" <?php if(isset($cajero)){ echo 'value="'.$cajero->clave.'"'; } ?> maxlength="15" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 testear_div" style="text-align: center; float: left">
							<a id="testear_button" href="#" class="btn btn-warning dropdown-toggle">
								Testear conexión
							</a> 
					</div>					
				</div>				
			</div>
			<?php if($maquina->modelo != 185){ ?>
			<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos de crédito
				</div>
				<div class="panel-body" style="padding-bottom: 10px;">
					<div class="col-md-12 col-sm-12 inputs">
						<div class="input-group">
							<input type="checkbox" name="comprobar_credito" id="comprobar_credito" <?php if(isset($cajero)){ if($cajero->credito == 1){ echo "checked"; } } ?>>
							<label style="margin-left: 4px;">Activar datáfono</label>
			    		</div>
					</div>
					<?php if(isset($cajero)){ if($cajero->credito == 1){ ?>
					<div class="col-md-12 col-sm-12" style="padding: 0">
						<div class="col-md-3 col-sm-6 inputs inputs_cajero">
							<label>IP impresora</label>
							<div class="input-group">
								<input class="form-control" type="text" id="ip_impresora" name="ip_impresora" <?php if(isset($cajero)){ echo 'value="'.$cajero->ip_impresora.'"'; } ?> maxlength="15" inputmode="numeric" <?php if($cajero->credito == 0){ echo "disabled"; }?>>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 inputs inputs_cajero">
							<label>Puerto impresora</label>
							<div class="input-group">
								<input class="form-control" type="text" id="puerto_impresora" name="puerto_impresora" <?php if(isset($cajero)){ echo 'value="'.$cajero->puerto_impresora.'"'; } ?> maxlength="4" inputmode="numeric" <?php if($cajero->credito == 0){ echo "disabled"; }?>>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 inputs inputs_cajero">
							<label>Puerto TPV</label>
							<div class="input-group">
								<input class="form-control" type="text" id="puerto_tpv" name="puerto_tpv" <?php if(isset($cajero)){ echo 'value="'.$cajero->puerto_tpv.'"'; } ?> maxlength="4" inputmode="numeric" <?php if($cajero->credito == 0){ echo "disabled"; }?>>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 inputs inputs_cajero">
							<label>Nº dígitos ticket</label>
							<div class="input-group">
								<select class="form-control" id="digitos" name="digitos" <?php if($cajero->credito == 0){ echo "disabled"; }?>>
									<option value="">Seleccionar...</option>
									<option value="8" <?php if(isset($cajero)){ if($cajero->digitos == 8){ echo "selected"; } } ?>>8</option>
									<option value="18" <?php if(isset($cajero)){ if($cajero->digitos == 18){ echo "selected"; } } ?>>18</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12" style="padding: 0">						
						<div class="col-md-3 col-sm-12 inputs">
							<div class="input-group" style="margin-bottom: 5%;">
								<input type="checkbox" name="comprobar_aux" id="comprobar_aux" <?php if(isset($cajero)){ if($cajero->aux == 1){ echo "checked"; } } ?>>
								<label style="margin-left: 4px;">Activar recarga auxiliar</label>
				    		</div>						
							<label>Nº recarga auxiliar</label>
							<div class="input-group">
								<select class="form-control" id="num_aux" name="num_aux" <?php if($cajero->aux == 0){ echo "disabled"; }?>>
								<?php
								echo "<option value=''>Seleccione opción</option>";
								for($i=1;$i<=15;$i++){
									if($i == $cajero->aux_num){
										echo "<option value=".$i." selected>".$i."</option>";
									}else{
										echo "<option value=".$i.">".$i."</option>";
									}
								}
								?>
								</select>
							</div>
							<label>Descripción</label>
							<div class="input-group">
								<input class="form-control" type="text" id="descripcion" name="descripcion" <?php if(isset($cajero)){ echo 'value="'.$cajero->descripcion.'"'; } ?> <?php if($cajero->credito == 0){ echo "disabled"; }?>>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 inputs">
							<div class="input-group" style="margin-bottom: 5%;">
								<input type="checkbox" name="comprobar_comision" id="comprobar_comision" <?php if(isset($cajero)){ if($cajero->comision == 1){ echo "checked"; } } ?>>
								<label style="margin-left: 4px;">Activar comisiones</label>
				    		</div>						
							<label>% comisión</label>
							<div class="input-group">
								<input class="form-control" type="text" id="cantidad_comision" name="cantidad_comision" <?php if(isset($cajero)){ echo 'value="'.$cajero->cantidad_comision.'"'; } ?> inputmode="numeric" placeholder="%" <?php if($cajero->comision == 0){ echo "disabled"; }?>>
							</div>
							<label>Código VIP</label>
							<div class="input-group">
								<input class="form-control" type="text" id="codigo_vip" name="codigo_vip" <?php if(isset($cajero)){ echo 'value="'.$cajero->codigo_vip.'"'; } ?> <?php if($cajero->comision == 0){ echo "disabled"; }?>>
							</div>
						</div>
						<div class="col-md-3 col-sm-12 inputs">
							<div class="input-group" style="margin-bottom: 5%;">
								<input type="checkbox" name="credito_bloqueo" id="credito_bloqueo" <?php if(isset($cajero)){ if($cajero->bloqueo == 1){ echo "checked"; } } ?>>
								<label style="margin-left: 4px;">Activar bloqueo</label>
				    	</div>				
							<label>Límite comisión</label>
							<div class="input-group">
								<input class="form-control" type="text" id="limite_comision" name="limite_comision" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_comision.'"'; } ?> inputmode="numeric" <?php if($cajero->comision == 0){ echo "disabled"; }?>>
							</div>
							<label>Fecha caducidad código</label>
							<div class="input-group">
								<input id="datepicker" class="form-control" type="text" id="fecha_caducidad" name="fecha_caducidad" <?php if(isset($cajero)){ if(isset($cajero->fecha_caducidad_vip)){ $fecha = explode("-", $cajero->fecha_caducidad_vip); $fecha = $fecha[2]."/".$fecha[1]."/".$fecha[0]; echo 'value="'.$fecha.'"'; } } ?> <?php if($cajero->comision == 0){ echo "disabled"; }?>>
							</div>							
						</div>
						<div class="col-md-3 col-sm-12 inputs">							
				    	<div class="input-group" style="margin-bottom: 5%;">
								<input type="checkbox" name="credito_espera" id="credito_espera" <?php if(isset($cajero)){ if($cajero->tiempo_espera == 1){ echo "checked"; } } ?>>
								<label style="margin-left: 4px;">Activar espera</label>
				    	</div>							
				    	<label>Tiempo espera (seg)</label>
							<div class="input-group">
								<input class="form-control" type="text" id="tiempo_espera" name="tiempo_espera" <?php if(isset($cajero)){ echo 'value="'.$cajero->duracion_espera.'"'; } ?> inputmode="numeric" <?php if($cajero->tiempo_espera != 1){ echo "disabled"; }?>>
							</div>
							<label>Tipo Ticket</label>
							<div class="input-group">
								<select class="form-control" id="tipo_ticket" name="tipo_ticket">
									<option value="0" <?php if(isset($cajero)){ if($cajero->tipo_ticket == 0){ echo "selected"; } } ?>>Billetero</option>
									<option value="1" <?php if(isset($cajero)){ if($cajero->tipo_ticket == 1){ echo "selected"; } } ?>>Lector</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12" style="padding: 0">
						<div class="col-md-12 col-sm-12 inputs">
							<p style="font-weight: bold">Información datáfono</p>								
						</div>
						<div class="col-md-4 col-sm-12 inputs">
							<p style="font-weight: bold">Nº de serie: <span style="font-weight: normal"><?php if(isset($datafono)){ echo $datafono->numero_serie; } ?></span></p>
							<p style="font-weight: bold">Comercio: <span style="font-weight: normal"><?php if(isset($datafono)){ echo $datafono->comercio; } ?></span></p>
						</div>
						<div class="col-md-4 col-sm-12 inputs">
							<p style="font-weight: bold">Clave: <span style="font-weight: normal"><?php if(isset($datafono)){ echo $datafono->clave; } ?></span></p>
							<p style="font-weight: bold">Oficina: <span style="font-weight: normal"><?php if(isset($datafono)){ echo $datafono->oficina; } ?></span></p>
						</div>
						<div class="col-md-4 col-sm-12 inputs">
							<p style="font-weight: bold">Entidad: <span style="font-weight: normal"><?php if(isset($datafono)){ echo $datafono->banco; } ?></span></p>
							<p style="font-weight: bold">TCOD: <span style="font-weight: normal"><?php if(isset($datafono)){ echo $datafono->TCOD; } ?></span></p>
						</div>
					</div>
					<?php } } ?>
				</div>
			</div>		
			<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos de configuración
				</div>
				<div class="panel-body" style="padding-bottom: 10px;">	
					<div class="col-md-6 col-sm-6 inputs inputs_cajero">
						<label style="margin-right: 10px">Collect</label><label id="label_collect" style="color: #5cb85c"></label>
						<div class="input-group">
							<input class="form-control" type="text" id="collect" name="collect" <?php if(isset($cajero)){ echo 'value="'.$cajero->collect.'"'; } ?> maxlength="2" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 inputs inputs_cajero">
						<label>Versión</label>
						<div class="input-group">
							<input class="form-control" type="text" id="version" name="version" <?php if(isset($cajero)){ echo 'value="'.$cajero->version.'"'; } ?> maxlength="4" inputmode="numeric" required>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="panel panel-default col-md-6 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos de avisos
				</div>
				<div class="panel-body" style="padding-bottom: 10px;">
					<div class="col-md-12 col-sm-12 inputs">
						<div class="input-group">
							<input type="checkbox" name="comprobar" id="comprobar" <?php if(isset($cajero)){ if($cajero->comprobacion_activa == 1){ echo "checked"; } } ?>>
							<label style="margin-left: 4px;">Activar comprobación automática límites</label>
			    		</div>
					</div>
					<div class="col-md-12 col-sm-12 inputs">
						<label style="text-decoration: underline">Inferior a</label>
					</div>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label>Disponible</label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_disponible" name="limite_disponible" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_disponible.'"'; } ?> maxlength="5" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label>Arqueo</label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_arqueo" name="limite_arqueo" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_arqueo.'"'; } ?> maxlength="5" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label>Multimoneda</label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_multimoneda" name="limite_multimoneda" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_multimoneda.'"'; } ?> maxlength="3" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label>Hopper</label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_hopper" name="limite_hopper" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_hopper.'"'; } ?> maxlength="4" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label style="margin-right: 10px">Recicl. Cassette1</label><label id="label_cassette1" style="color: #5cb85c"></label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_reciclador_cassette1" name="limite_reciclador_cassette1" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_reciclador_cassette1.'"'; } ?> maxlength="3" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label style="margin-right: 10px">Recicl. Cassette2</label><label id="label_cassette2" style="color: #5cb85c"></label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_reciclador_cassette2" name="limite_reciclador_cassette2" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_reciclador_cassette2.'"'; } ?> maxlength="3" inputmode="numeric" required>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label style="margin-right: 10px">Recicl. Cassette3</label><label id="label_cassette3" style="color: #5cb85c"></label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_reciclador_cassette3" name="limite_reciclador_cassette3" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_reciclador_cassette3.'"'; } ?> maxlength="3" inputmode="numeric" required>
						</div>
					</div>
					<?php if($maquina->modelo == 131 || $maquina->modelo == 185){ ?>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label style="margin-right: 10px">Recicl. Cassette4</label><label id="label_cassette4" style="color: #5cb85c"></label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_reciclador_cassette4" name="limite_reciclador_cassette4" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_reciclador_cassette4.'"'; } ?> maxlength="3" inputmode="numeric" required>
						</div>
					</div>
					<?php } ?>
					<?php if($maquina->modelo == 131){ ?>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label style="margin-right: 10px">Recicl. Cassette5</label><label id="label_cassette5" style="color: #5cb85c"></label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_reciclador_cassette5" name="limite_reciclador_cassette5" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_reciclador_cassette5.'"'; } ?> maxlength="3" inputmode="numeric" required>
						</div>
					</div>
					<?php } ?>
					<div class="col-md-12 col-sm-12 inputs">
						<label style="text-decoration: underline; margin-top: 10px">Superior a</label>
					</div>
					<div class="col-md-3 col-sm-6 inputs inputs_cajero">
						<label>No Activo</label>
						<div class="input-group">
							<input class="form-control" type="text" id="limite_no_activo" name="limite_no_activo" <?php if(isset($cajero)){ echo 'value="'.$cajero->limite_no_activo.'"'; } ?> maxlength="4" inputmode="numeric" required>
						</div>
					</div>					
				</div>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; float: left; width: 100%;">
				<div class="btn-group aceptar_button" style="margin: 2% 0;">
					<?php if(isset($maquina)){ ?>
					<input type="hidden" value="<?php echo $maquina->id; ?>" name="id" id="id">
					<?php } ?>
					<?php if(isset($cajero)){ ?>
					<input type="hidden" value="<?php echo $cajero->id; ?>" name="cajero">
					<?php } ?>
					<?php if(isset($credito)){ ?>
					<input type="hidden" value="<?php echo $credito->id; ?>" name="credito">
					<?php } ?>
					<button type="submit" class="btn btn-danger dropdown-toggle aceptar_button">
						Aceptar 
					</button> 
				</div>
		</div>
		<div class="col-md-12" style="text-align: center; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('maquinas/1'); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
  <script type="text/javascript" src="<?php echo base_url('files/js/script_crear_maquinas.js'); ?>"></script>
  <!-- SCRIPT TESTEAR CONEXION -->
  <script type="text/javascript">
  		$('#ip').on("change paste keyup", function() {
  			$('#testear_button').html('Testear conexión');
	  	  	$('#testear_button').removeClass('btn-info');
	  	  	$('#testear_button').removeClass('btn-success');
	  	  	$('#testear_button').removeClass('btn-danger');
	  	  	$('#testear_button').addClass('btn-warning');
  		});
  		$('#puerto').on("change paste keyup", function() {
  			$('#testear_button').html('Testear conexión');
	  	  	$('#testear_button').removeClass('btn-info');
	  	  	$('#testear_button').removeClass('btn-success');
	  	  	$('#testear_button').removeClass('btn-danger');
	  	  	$('#testear_button').addClass('btn-warning');
  		});
  		$('#usuario').on("change paste keyup", function() {
  			$('#testear_button').html('Testear conexión');
	  	  	$('#testear_button').removeClass('btn-info');
	  	  	$('#testear_button').removeClass('btn-success');
	  	  	$('#testear_button').removeClass('btn-danger');
	  	  	$('#testear_button').addClass('btn-warning');
  		});
  		$('#clave').on("change paste keyup", function() {
  			$('#testear_button').html('Testear conexión');
	  	  	$('#testear_button').removeClass('btn-info');
	  	  	$('#testear_button').removeClass('btn-success');
	  	  	$('#testear_button').removeClass('btn-danger');
	  	  	$('#testear_button').addClass('btn-warning');
  		});
  	  	$('#testear_button').on("click", function(e){
	  	  	e.preventDefault();
	  	  	protocol = window.location.protocol;
	  	  	$(this).html('Comprobando...');
	  	  	$(this).removeClass('btn-warning');
	  	  	$(this).addClass('btn-info');
	  	  	id = $('#id').val();
	  	  	ip = $('#ip').val();
	  	  	p = $('#puerto').val();
	  	  	u = $('#usuario').val();
	  	  	c = $('#clave').val();
			$.ajax ({
			    type: "POST",
			    url: protocol + "//atc.apuestasdemurcia.es/tickets/comprobar_cajero",
			    data: "ip=" + ip + "&p=" + p + "&u=" + u + "&c=" + c + "&id=" + id,
			    success: function(data){
				    if(data.match(/false.*/)){  					
	  	  					$('#testear_button').html('Error');
	  	  					$('#testear_button').removeClass('btn-info');
	  	  					$('#testear_button').addClass('btn-danger');
				    }else{
			      		var json = $.parseJSON(data);
			      		console.log(data);
       					if(json.length == 6){
       						$('#label_cassette1').html(" ("+json[0]+"€)");
       						$('#label_cassette2').html(" ("+json[1]+"€)");
       						$('#label_cassette3').html(" ("+json[2]+"€)");
       						$('#label_cassette4').html(" ("+json[3]+"€)");
       						$('#label_cassette5').html(" ("+json[4]+"€)");
       						$('#label_collect').html(" ("+json[5]+")");
       					}else if(json.length == 4){
       						$('#label_cassette1').html(" ("+json[0]+"€)");
       						$('#label_cassette2').html(" ("+json[1]+"€)");
       						$('#label_cassette3').html(" ("+json[2]+"€)");
       						$('#label_collect').html(" ("+json[3]+")");
       					}
			      		$('#testear_button').html('Conectado');
  	  					$('#testear_button').removeClass('btn-info');
  	  					$('#testear_button').addClass('btn-success');
			      	}
			    }
			});
  	  	});
  </script>
  <!-- SCRIPT INPUTS NUMERICOS -->
  <script type="text/javascript">
  		$("#puerto, #collect, #version, #limite_arqueo, #limite_multimoneda, #limite_hopper, #limite_reciclador_cassette1, #limite_reciclador_cassette2, #limite_reciclador_cassette3, #limite_reciclador_cassette4, #limite_reciclador_cassette5, #limite_no_activo").keydown(function (e) {
	        // Allow: backspace, delete, tab, escape, enter and .
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });
	    $("#ip").keydown(function (e) {
	        // Allow: backspace, delete, tab, escape, enter and .
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });
  </script>
  <!-- SCRIPT ACTIVAR LIMITES -->
  <script type="text/javascript">
  	$(function(){
  		if($('#comprobar').val() == "on"){
  			$("#limite_arqueo, #limite_multimoneda, #limite_hopper, #limite_reciclador_cassette1, #limite_reciclador_cassette2, #limite_reciclador_cassette3, #limite_reciclador_cassette4, #limite_reciclador_cassette5, #limite_no_activo").prop('disabled', false);
  		}else{
  			$("#limite_arqueo, #limite_multimoneda, #limite_hopper, #limite_reciclador_cassette1, #limite_reciclador_cassette2, #limite_reciclador_cassette3, #limite_reciclador_cassette4, #limite_reciclador_cassette5, #limite_no_activo").prop('disabled', true);
  		}
  	});
  	
  	$('#comprobar').on('click', function(){
  		console.log($('#comprobar').val());
  		if($('#comprobar').is(':checked')){
  			$("#limite_arqueo, #limite_multimoneda, #limite_hopper, #limite_reciclador_cassette1, #limite_reciclador_cassette2, #limite_reciclador_cassette3, #limite_reciclador_cassette4, #limite_reciclador_cassette5, #limite_no_activo").prop('disabled', false);
  		}else{
  			$("#limite_arqueo, #limite_multimoneda, #limite_hopper, #limite_reciclador_cassette1, #limite_reciclador_cassette2, #limite_reciclador_cassette3, #limite_reciclador_cassette4, #limite_reciclador_cassette5, #limite_no_activo").prop('disabled', true);
  		}
  	});

  	$("#comprobar_credito").on('click', function(){
  		if($('#ip_impresora').is(':disabled')){
  			$('#ip_impresora').prop('disabled', false);
  		}else{
  			$('#ip_impresora').prop('disabled', true);
  		}

  		if($('#puerto_impresora').is(':disabled')){
  			$('#puerto_impresora').prop('disabled', false);
  		}else{
  			$('#puerto_impresora').prop('disabled', true);
  		}

  		if($('#puerto_tpv').is(':disabled')){
  			$('#puerto_tpv').prop('disabled', false);
  		}else{
  			$('#puerto_tpv').prop('disabled', true);
  		}
  	});

  	$("#comprobar_aux").on('click', function(){
  		if($('#num_aux').is(':disabled')){
  			$('#num_aux').prop('disabled', false);
  		}else{
  			$('#num_aux').prop('disabled', true);
  		}
  	});

  	$("#comprobar_comision").on('click', function(){
  		if($('#cantidad_comision').is(':disabled')){
  			$('#cantidad_comision').prop('disabled', false);
  		}else{
  			$('#cantidad_comision').prop('disabled', true);
  		}
  		if($('#limite_comision').is(':disabled')){
  			$('#limite_comision').prop('disabled', false);
  		}else{
  			$('#limite_comision').prop('disabled', true);
  		}
  		if($('#codigo_vip').is(':disabled')){
  			$('#codigo_vip').prop('disabled', false);
  		}else{
  			$('#codigo_vip').prop('disabled', true);
  		}
  		if($('#datepicker').is(':disabled')){
  			$('#datepicker').prop('disabled', false);
  		}else{
  			$('#datepicker').prop('disabled', true);
  		}
  	});

  	$("#credito_espera").on('click', function(){
  		if($('#tiempo_espera').is(':disabled')){
  			$('#tiempo_espera').prop('disabled', false);
  		}else{
  			$('#tiempo_espera').prop('disabled', true);
  		}
  	});

  	$("#activar_limite_tarjeta").on('click', function(){
  		if($('#cantidad_limite').is(':disabled')){
  			$('#cantidad_limite').prop('disabled', false);
  		}else{
  			$('#cantidad_limite').prop('disabled', true);
  		}
  		if($('#duracion_limite').is(':disabled')){
  			$('#duracion_limite').prop('disabled', false);
  		}else{
  			$('#duracion_limite').prop('disabled', true);
  		}
  	});
  </script>
  <script type="text/javascript">
	  $(function(){
	      $('#datepicker').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
	  });
  </script>
</body>
</html>
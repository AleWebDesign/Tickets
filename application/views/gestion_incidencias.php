<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php if($this->session->userdata('logged_in')['rol'] == 5 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || $this->session->userdata('logged_in')['rol'] == 9 || $this->session->userdata('logged_in')['acceso'] == 69 || $this->session->userdata('logged_in')['acceso'] == 82 || $this->session->userdata('logged_in')['acceso'] == 148 || $this->session->userdata('logged_in')['acceso'] == 200 || $this->session->userdata('logged_in')['acceso'] == 414 || $this->session->userdata('logged_in')['acceso'] == 630){ ?>
  	<?php }else{ ?>
	<a href="<?php echo base_url('nueva'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nueva incidencia" title="Nueva incidencia" style="width: 80%" />
	</a>
	<?php } ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px;"><a href="<?php echo base_url('gestion'); ?>" style="color: #000; text-decoration: none">Incidencias</a></h3>
		<!-- Buscador -->
		<div id="ocultar_buscador" style="position: relative; height: 45px; padding-top: 3px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; cursor: pointer">
			<h4 style="margin-bottom: 30px">
				Filtros<span style="float: right; margin-right: 2%" class="glyphicon glyphicon-triangle-bottom"></span>
			</h4>			
		</div>
		
		<div id="ocultar" style="display: none">
			<?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 7){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('buscador_incidencias_id/'); ?>
			<div class="col-md-12">
				<h4 style="margin-top: 20px; margin-bottom: 0">Búsqueda rápida</h4>
			</div>
			<div class="col-md-3" style="margin: 20px 0">
					<input type="text" name="id_incidencia" class="form-control" placeholder="ID ticket" <?php if(isset($id_incidencia)){ echo "value='".$id_incidencia."'"; } ?> required>
					<button type="submit" class="btn btn-danger dropdown-toggle" style="margin-top: 20px;">
						Buscar
					</button> 
			</div>
			</form>
			<?php } ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('buscador_incidencias/', 'id="id_form"'); ?>
			<div class="col-md-12">
				<h4 style="margin-top: 20px; margin-bottom: 0">Búsqueda avanzada</h4>
			</div>
			<div class="col-md-3" style="margin: 20px 0 17px 0;">
				<div class="col-md-12 col-sm-12">
					<?php if($this->session->userdata('logged_in')['rol'] == 1){ ?>
					<div class="input-group" style="padding-top: 2%">
						<select class="js-example-basic-single" name="empresa" id="empresa">
						  <?php if(isset($html_empresas)){ echo $html_empresas; } ?>
						</select>
					</div>
					<?php } ?>
					<div class="input-group" style="width: 50%; float: left; padding-top: 2%"">
						<span class="input-group-addon" id="basic-addon1">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input id="datepicker1" type="text" name="fecha_inicio_incidencia" class="form-control" placeholder="Desde" <?php if(isset($fecha_inicio)){ echo "value='".$fecha_inicio."'"; } ?> required>
					</div>
					<div class="input-group" style="width: 48%; margin-left: 2%; float: left; padding-top: 2%"">
						<span class="input-group-addon" id="basic-addon1">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input id="datepicker2" type="text" name="fecha_fin_incidencia" class="form-control" placeholder="Hasta" <?php if(isset($fecha_fin)){ echo "value='".$fecha_fin."'"; } ?> required>
					</div>				
					<?php if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 4 && $this->session->userdata('logged_in')['acceso'] == 24) || $this->session->userdata('logged_in')['id'] == 40 || $this->session->userdata('logged_in')['id'] == 77){ ?>
					<div class="input-group" style="padding-top: 2%">
						<select class="js-example-basic-single" id="operador" name="operador" <?php if($this->session->userdata('logged_in')['rol'] != 2 && $this->session->userdata('logged_in')['rol'] != 3 && $this->session->userdata('logged_in')['rol'] != 4){ if(empty($html_operadora) || $this->input->post('operador') == ''){ echo "disabled"; } } ?>>
					  	<option value="">Operadora...</option>
					  	<?php if(isset($html_op)){ echo $html_op; }else{ if(isset($html_operadora)){ echo $html_operadora; } } ?>					
						</select>
					</div>
					<?php } ?>
					<div class="input-group" style="padding-top: 2%">
						<select class="js-example-basic-single" id="salon" name="salon" <?php if($this->input->post('operador') != ''){}else{ if($this->session->userdata('logged_in')['rol'] == 1){ echo "disabled"; } } ?>>
							<option value="">Salón...</option>
							<?php if(isset($html_salones)){ echo $html_salones; } ?>
						</select>
					</div>
					<div class="input-group" style="padding-top: 2%">
						<textarea style="resize: none;" class="form-control" id="buscar_trata" name="buscar_trata" rows="3" placeholder="Descripción/Tratamiento/Solución..."><?php if(isset($buscar_trata) && $buscar_trata != ''){ echo $buscar_trata; } ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-9" style="margin: 0 0 17px 0;">
				<div class="col-md-12 col-sm-12" style="padding: 12px 15px 10px 22px;">
					<a href="#" class="check_all" style="text-decoration: underline">Marcar todos</a>
				</div>
				<?php if($this->session->userdata('logged_in')['rol'] == 1){ ?>
				<div class="col-md-3 col-sm-12 busca_check">
					<div class="input-group">
						<input type="checkbox" name="error" class="tipo_error" <?php if(!empty($tipo_error_error)){ echo "checked"; } ?>>
						<label>Errores</label>
		    	</div>
		    	<div class="input-group" style="padding-top: 2%">
		    		<input type="checkbox" name="info" class="tipo_error" <?php if(!empty($tipo_error_info)){ echo "checked"; } ?>>
		      	<label>Información</label>
		      </div>
		      <div class="input-group" style="padding-top: 2%">
		      	<input type="checkbox" name="recla" class="tipo_error" <?php if(!empty($tipo_error_recla)){ echo "checked"; } ?>>
		      	<label>Reclamaciones</label>
		      </div>
		      <div class="input-group" style="padding-top: 2%">
		      	<input type="checkbox" name="suge" class="tipo_error" <?php if(!empty($tipo_error_suge)){ echo "checked"; } ?>>
		      	<label>Sugerencias</label>
					</div>
				</div>				
				<div class="col-md-3 col-sm-12 busca_check">
					<div class="input-group">
						<input type="checkbox" name="pend_rev" class="tipo_error" <?php if(!empty($pend_rev)){ echo "checked"; } ?>>
						<label>Pendiente de revisar</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_sat" class="tipo_error" <?php if(!empty($pend_sat)){ echo "checked"; } ?>>
							<label>Pendiente SAT</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_trat" class="tipo_error" <?php if(!empty($pend_trat)){ echo "checked"; } ?>>
							<label>Con Tratamiento / Sin Solucionar</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_com" class="tipo_error" <?php if(!empty($pend_com)){ echo "checked"; } ?>>
							<label>Pendiente Comercial</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_euska" class="tipo_error" <?php if(!empty($pend_euska)){ echo "checked"; } ?>>
							<label>Pendiente EUSKATEL</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_kirol" class="tipo_error" <?php if(!empty($pend_kirol)){ echo "checked"; } ?>>
							<label>Pendiente KIROL</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_inf" class="tipo_error" <?php if(!empty($pend_inf)){ echo "checked"; } ?>>
							<label>Pendiente Informática</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_mkt" class="tipo_error" <?php if(!empty($pend_mkt)){ echo "checked"; } ?>>
							<label>Pendiente Marketing</label>
			    	</div>
					<div class="input-group">
							<input type="checkbox" name="pend_onl" class="tipo_error" <?php if(!empty($pend_onl)){ echo "checked"; } ?>>
							<label>Pendiente Online</label>
			    	</div>
				</div>
				<div class="col-md-3 col-sm-12 busca_check">
					<div class="input-group">
						<input type="checkbox" name="pend_trata" class="tipo_error" <?php if(!empty($pend_trata)){ echo "checked"; } ?>>
						<label>Pendiente tratamiento</label>
			    	</div>
					<div class="input-group">
							<input type="checkbox" name="pend_llamar" class="tipo_error" <?php if(!empty($pend_llamar)){ echo "checked"; } ?>>
							<label>Pendiente de llamar</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_cadu" class="tipo_error" <?php if(!empty($pend_cadu)){ echo "checked"; } ?>>
							<label>Pendiente caducar</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_tec_op" class="tipo_error" <?php if(!empty($pend_tec_op)){ echo "checked"; } ?>>
							<label>Pendiente Técnico Operadora</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="cerra" class="tipo_error" <?php if(!empty($cerra)){ echo "checked"; } ?>>
							<label>Cerrada</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="solucio" class="tipo_error" <?php if(!empty($solucio)){ echo "checked"; } ?>>
							<label>Solucionada</label>
			    	</div>
				</div>
				<?php }else if($this->session->userdata('logged_in')['rol'] == 6){ ?>
				<div class="col-md-3 col-sm-12 busca_check">
					<div class="input-group">
						<input type="checkbox" name="pend_sat" class="tipo_error" <?php if(!empty($pend_sat)){ echo "checked"; } ?>>
						<label>Pendiente SAT</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_trat" class="tipo_error" <?php if(!empty($pend_trat)){ echo "checked"; } ?>>
							<label>Con Tratamiento / Sin Solucionar</label>
			    	</div>
					<div class="input-group">
							<input type="checkbox" name="pend_trata" class="tipo_error" <?php if(!empty($pend_trata)){ echo "checked"; } ?>>
							<label>Pendiente tratamiento</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_com" class="tipo_error" <?php if(!empty($pend_com)){ echo "checked"; } ?>>
							<label>Pendiente Comercial</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_tec_op" class="tipo_error" <?php if(!empty($pend_tec_op)){ echo "checked"; } ?>>
							<label>Pendiente Técnico Operadora</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_mkt" class="tipo_error" <?php if(!empty($pend_mkt)){ echo "checked"; } ?>>
							<label>Pendiente Marketing</label>
			    	</div>
					<div class="input-group">
							<input type="checkbox" name="pend_onl" class="tipo_error" <?php if(!empty($pend_onl)){ echo "checked"; } ?>>
							<label>Pendiente Online</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="solucio" class="tipo_error" <?php if(!empty($solucio)){ echo "checked"; } ?>>
							<label>Solucionada</label>
			    	</div>
				</div>
				<?php }else if($this->session->userdata('logged_in')['rol'] == 4 && ($this->session->userdata('logged_in')['acceso'] == 24 || $this->session->userdata('logged_in')['acceso'] == 41)){ ?>
				<div class="col-md-3 col-sm-12 busca_check">
			    	<div class="input-group">
							<input type="checkbox" name="pend_sat" class="tipo_error" <?php if(!empty($pend_sat)){ echo "checked"; } ?>>
							<label>Pendiente SAT</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_tec_op" class="tipo_error" <?php if(!empty($pend_tec_op)){ echo "checked"; } ?>>
							<label>Pendiente Técnico Operadora</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_euska" class="tipo_error" <?php if(!empty($pend_euska)){ echo "checked"; } ?>>
							<label>Pendiente EUSKATEL</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_trat" class="tipo_error" <?php if(!empty($pend_trat)){ echo "checked"; } ?>>
							<label>Con Tratamiento / Sin Solucionar</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="solucio" class="tipo_error" <?php if(!empty($solucio)){ echo "checked"; } ?>>
							<label>Solucionada</label>
			    	</div>
				</div>
				<?php }else if($this->session->userdata('logged_in')['rol'] == 8){ ?>
				<div class="col-md-3 col-sm-12 busca_check">					
			    	<div class="input-group">
							<input type="checkbox" name="pend_mkt" class="tipo_error" <?php if(!empty($pend_mkt)){ echo "checked"; } ?>>
							<label>Pendiente Marketing</label>
			    	</div>
				</div>
				<?php }else if($this->session->userdata('logged_in')['rol'] == 9){ ?>
				<div class="col-md-3 col-sm-12 busca_check">					
			    	<div class="input-group">
							<input type="checkbox" name="pend_onl" class="tipo_error" <?php if(!empty($pend_onl)){ echo "checked"; } ?>>
							<label>Pendiente Online</label>
			    	</div>
				</div>
				<?php }else{ ?>
				<div class="col-md-3 col-sm-12 busca_check">
			    	<div class="input-group">
							<input type="checkbox" name="pend_sat" class="tipo_error" <?php if(!empty($pend_sat)){ echo "checked"; } ?>>
							<label>Pendiente SAT</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_tec_op" class="tipo_error" <?php if(!empty($pend_tec_op)){ echo "checked"; } ?>>
							<label>Pendiente Técnico Operadora</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_inf" class="tipo_error" <?php if(!empty($pend_inf)){ echo "checked"; } ?>>
							<label>Pendiente Informática</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_euska" class="tipo_error" <?php if(!empty($pend_euska)){ echo "checked"; } ?>>
							<label>Pendiente EUSKATEL</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="pend_trat" class="tipo_error" <?php if(!empty($pend_trat)){ echo "checked"; } ?>>
							<label>Con Tratamiento / Sin Solucionar</label>
			    	</div>
			    	<div class="input-group">
							<input type="checkbox" name="solucio" class="tipo_error" <?php if(!empty($solucio)){ echo "checked"; } ?>>
							<label>Solucionada</label>
			    	</div>
				</div>
				<?php } ?>
				<div class="col-md-12" style="margin: 20px 0; text-align: right;">
					<button type="submit" class="btn btn-danger dropdown-toggle">
						Buscar
					</button> 
				</div> 	
			</div>
		<hr width="100%">
		</div>
		
		<!-- Tabla incidencias -->
		<h4>Incidencias</h4>
		<p id="p_agrupados">Pinche en el nombre de una columna para ver las incidencias agrupadas por ese campo.</p>
		<input type="hidden" name="agrupar_volver" id="agrupar_volver" value="<?php echo $agrupar_volver; ?>">
		<input type="hidden" name="agrupar_columna_volver" id="agrupar_columna_volver" value="<?php echo $agrupar_volver_columna; ?>">
		<input type="hidden" name="consulta_sql" id="consulta_sql" value="<?php if(isset($consulta)){ echo $consulta; } ?>">
		<input type="hidden" name="numero_filas" id="numero_filas" value="<?php if(isset($numero_filas)){ echo $numero_filas; } ?>">
		<hr/>
		<?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){ ?>
		<div id="div_regiones" class="col-md-12" style="margin-bottom: 1%">
			<ul class="nav nav-tabs">
			  <li role="presentation" class="active_tabs"><a id="0" class="lista_regiones" href="#">Todas</a></li>
			  <li role="presentation"><a id="1" class="lista_regiones" href="#">Murcia</a></li>
			  <li role="presentation"><a id="3" class="lista_regiones" href="#">Andalucía</a></li>
			</ul>
		</div>
		<?php } ?>
		<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla" style="width: 1% !important">Código</th>
							<?php if($this->session->userdata('logged_in')['rol'] == 8){ ?>
							<th class="th_tabla" style="width: 5% !important">F.Creación</th>
							<?php }else{ ?>
							<th class="th_tabla" style="width: 5% !important"><a href="#" class="agrupar" id="fecha_creacion">F.Creación</a></th>
							<?php } ?>
							<th class="th_tabla"><a href="#" class="agrupar" id="situacion">Situación</a></th>
							<?php if($this->session->userdata('logged_in')['rol'] == 1){ ?>
							<th class="th_tabla"><a href="#" class="agrupar" id="operadora">Operador</a></th>
							<?php } ?> 
							<th class="th_tabla"><a href="#" class="agrupar" id="salon">Salón</a></th>
							<?php if($this->session->userdata('logged_in')['rol'] == 1){ ?>
							<th class="th_tabla">Orígen</th>
							<th class="th_tabla">Teléfono</th>
							<?php } ?>
							<?php if($this->session->userdata('logged_in')['rol'] == 8){ ?>
							<th class="th_tabla">Avería</th>
							<?php }else{ ?>
							<th class="th_tabla"><a href="#" class="agrupar" id="tipo_averia">Avería</a></th>
							<?php } ?>
							<th class="th_tabla"><a href="#" class="agrupar" id="tipo_error">Error</a></th>
							<th class="th_tabla"><a href="#" class="agrupar" id="detalle_error">Detalle</a></th>
							<?php if($this->session->userdata('logged_in')['rol'] == 8){ ?>
							<th class="th_tabla">Máquina</th>
							<?php }else{ ?>
							<th class="th_tabla"><a href="#" class="agrupar" id="maquina">Máquina</a></th>
							<?php } ?>
							<?php if($this->session->userdata('logged_in')['rol'] == 1){ ?>
							<th class="th_tabla"><a href="#" class="agrupar" id="destino">Destino</a></th>
							<?php } ?>
							<th class="th_tabla"><a href="#" class="agrupar" id="creador">Autor</a></th>
							<th class="th_tabla">Editada</th>
							<th class="th_tabla" style="width: 100px"><a href="#" class="agrupar" id="asignado">Asignado</a></th>
							<th class="th_tabla" style="width: 100px"><a href="#" class="agrupar" id="tratamiento">Tratamiento</a></th>
							<?php if($solucionado != 0){ ?>
							<th class="th_tabla"><a href="#" class="agrupar" id="soluciona">Solucionado</a></th>
							<?php } ?>
							<th class="th_tabla" style="width: 50px">Prioridad</th>
							<th class="th_tabla" style="width: 150px !important">Acciones</th>
						</tr>
					</thead>
					<tbody id="tabla_incidencias">
						<?php echo $tabla_incidencias; ?>
					</tbody>
				</table>
			</div> 	
  	</div>
  	<div class="col-md-12" id="div_agrupados" style="display: none">
  	</div>
  	<div class="col-md-12" id="version_movil">
  		<?php echo $version_movil; ?>
  	</div>
  </div>
</body>
<script type="text/javascript">
	$('.btn-primary').on('click', function(){
		$(this).addClass('disabled_link');
		var id = $(this).attr('id');
		$.ajax ({
	        type: "POST",
	        data: "id=" + id,
	        url: "http://atc.apuestasdemurcia.es/tickets/llamar_ticket",
	        success: function(data){
	        	if(data.trim().length != 0){
	        		console.log(data);
	        	}else{
	        		alert("Ha ocurrido un error, no se ha podido contactar con la incidencia.");
	        	}
	        	$('#'+id).removeClass('disabled_link');
	        }
	    });
	});
</script>
<script type="text/javascript" src="<?php echo base_url('files/js/script_gestion_incidencias.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('files/js/script_agrupar_incidencias.js'); ?>"></script>
<script type="text/javascript">
  $(function(){
      $('#datepicker1,#datepicker2').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
  });
</script>
<script type="text/javascript">
	$('a').not('.lista_regiones').on('click', function(e){
		e.stopImmediatePropagation();
		localStorage.setItem("scrollTop", $(window).scrollTop());
	});
</script>
<script type="text/javascript">
	setInterval(function(){
		var url = window.location.href;
		var arr = url.split("/");
		if(arr[arr.length - 1] == "gestion"){
			filas = $('#numero_filas').val();
			sql = $('#consulta_sql').val();
			$.ajax ({
		        type: "POST",
		        data: "sql=" + sql,
		        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/comprobar_nuevas_incidencias",
		        success: function(data){        	
		        	var result = $.trim(data);
		        	if(result > filas){        		
						if(sql != ''){
							if($('#agrupar_volver').val() == 1){
								window.location.href = '<?php echo base_url("gestion/'+sql+'/agrupar/'+$('#agrupar_columna_volver').val()+'"); ?>';
							}else{
								window.location.href = '<?php echo base_url("gestion/'+sql+'"); ?>';
							}
			        	}else{
			        		if($('#agrupar_volver').val() == 1){
								window.location.href = '<?php echo base_url("gestion/agrupar/'+$('#agrupar_columna_volver').val()+'"); ?>';
							}else{
								window.location.href = '<?php echo base_url("gestion"); ?>';
							}
			        	}
		        	}        	  	
		        }
		    });
		}
	},60000);
</script>
<script type="text/javascript">
	$(document).ready(function() {
    $('.js-example-basic-single').select2();
	});

	$('.lista_regiones').on('click', function(){
		$('.lista_regiones').parent().removeClass('active_tabs');
		$(this).parent().addClass('active_tabs');
		var val = $(this).attr('id');		
		if($('#version_escritorio').children().children().children().next().css('display') != "none"){
			var table = $('#version_escritorio').children().children().children().next().children();
			$(table).each(function(){
				var row = $(this);
				row.css('display', 'table-row');
			});
			if(val != 0){
				$(table).each(function(){
					var row = $(this);
					if(val != row.find('span.region').html()){
						row.css('display', 'none');
					}
				});
			}
		}else{
			var table2 = $('.agrupado_div').next().children().children().next().children();
			$(table2).each(function(){
				var row = $(this);
				row.css('display', 'table-row');
			});
			if(val != 0){
				$(table2).each(function(){
					var row = $(this);
					if(val != row.find('span.region').html()){
						row.css('display', 'none');
					}
				});
			}
		}
	});

	var resultado = <?php if(isset($resultado)){ echo $resultado; }else{ echo NULL; } ?>;

	if(typeof resultado == 'undefined' || resultado == null || resultado == '' || resultado == 0){
	}else{

		var url = window.location.href;
		var arr = url.split("/");

		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/notificar_incidencia",
	        data: "resultado=" + resultado
	    });		
	}
</script>
</html>
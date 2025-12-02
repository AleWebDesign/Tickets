<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php if($this->session->userdata('logged_in')['rol'] == 2){ ?>          
	<a href="<?php echo base_url('nuevo_local'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nuevo local" title="Nuevo local" style="width: 80%" />
	</a>
	<?php } ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px"><a href="<?php echo base_url('locales/1'); ?>" style="color: #000; text-decoration: none">Locales</a></h3>
  	
  	<?php if($this->session->userdata('logged_in')['rol'] == 1 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){ ?>
  	<!-- Buscador -->
	<div style="position: relative; height: 45px; padding-top: 3px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; cursor: pointer">
		<h4 style="margin-bottom: 30px">
			Filtros<span style="float: right; margin-right: 2%" class="glyphicon glyphicon-triangle-bottom"></span>
		</h4>
	</div>
	
	<div>
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('buscador_locales/'); ?>
		<div class="col-md-6 col-sm-12" style="margin: 20px 0 17px 0;">
			<div class="col-md-6 col-sm-12">
				<div class="input-group" style="padding-top: 2%">
					<select class="js-example-basic-single" id="salon" name="salon">
					  	<option value="">Salón...</option>
					  	<?php if(isset($html_salones)){ echo $html_salones; } ?>					
					</select>
				</div>
			</div>
			<div class="col-md-6 col-sm-12">
				<div class="input-group" style="padding-top: 2%">
					<select class="js-example-basic-single" id="operador" name="operador">
				  		<option value="">Operadora...</option>
				  		<?php if(isset($html_op)){ echo $html_op; } ?>					
					</select>
				</div>
			</div>
			<div class="col-md-12 col-sm-12" style="margin: 20px 0; text-align: right;">
				<button type="submit" class="btn btn-danger dropdown-toggle">
					Buscar
				</button>
			</div> 
		</div>
	</div>
	<?php } ?>
		
  	<input type="hidden" name="agrupar_volver" id="agrupar_volver" value="<?php echo $agrupar_volver; ?>">
	<input type="hidden" name="agrupar_columna_volver" id="agrupar_columna_volver" value="<?php echo $agrupar_volver_columna; ?>">
	<input type="hidden" name="consulta_sql" id="consulta_sql" value="<?php if(isset($consulta)){ echo $consulta; } ?>">
	<!-- Tabla usuarios -->
	<hr/>
	<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
		<div class="panel panel-default" style="margin: 0">
			<table class="table tabla_incidencias">
				<thead>
					<tr>
						<th class="th_tabla">Salón</th>
						<th class="th_tabla"><a href="#" class="agrupar" id="operadora">Operadora</a></th>
						<th class="th_tabla">Dirección</th>
						<th class="th_tabla">Población</th>
						<th class="th_tabla">Teléfono</th>
						<th class="th_tabla">E-mail</th>
						<th class="th_tabla">IP Internet Salón</th>
						<th class="th_tabla">IP WAN Euskaltel</th>
						<th class="th_tabla">IP LAN Euskaltel</th>
						<th class="th_tabla">IP Vodafone</th>
						<th class="th_tabla">Acciones</th>
					</tr>
				</thead>
				<tbody id="tabla_incidencias">
					<?php echo $tabla_locales; ?>
				</tbody>
			</table>
		</div>
		<nav aria-label="..." style="text-align: center;">
			<ul class="pagination">
			<?php
				if(strpos($_SERVER['REQUEST_URI'], "/locales/") !== false){
					$link = 'locales';
					if ($paginas > 1) {
					   if ($pagina != 1)
					      echo '<li>
												<a style="color: #d80039" href="'.base_url($link."/".($pagina-1)."/").'" aria-label="Previous">
													<span aria-hidden="true">«</span>
												</a>
											</li>';
					      for ($i=1;$i<=$paginas;$i++) {
					         if ($pagina == $i)
					            echo '<li class="active">
															<a href="'.base_url($link."/".($i)."/").'">'.($i).'</a>
														</li>';
					         else
					            echo '<li>
															<a style="color: #d80039" href="'.base_url($link."/".($i)."/").'">'.($i).'</a>
														</li>';
					      }
					      if ($pagina != $paginas)
					          echo '<li>
												<a style="color: #d80039" href="'.base_url($link."/".($pagina+1)."/").'" aria-label="Next">
													<span aria-hidden="true">»</span>
												</a>
											</li>';
					}
				}
			?>					
			</ul>
		</nav> 	
  	</div>
  	<div class="col-md-12" id="div_agrupados" style="display: none">
  	</div>
  	<div class="col-md-12" id="version_movil">
  		<?php echo $version_movil; ?>
  	</div>
  </div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_gestion_locales.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('.js-example-basic-single').select2();
	});
	
	$('a').on('click', function(e){
		e.stopImmediatePropagation();
		localStorage.setItem("scrollTop", $(window).scrollTop());
	});
</script>
</html>
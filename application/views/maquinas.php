	<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php if($this->session->userdata('logged_in')['rol'] == 2){ ?>
	<a href="<?php echo base_url('nueva_maquina'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nueva máquina" title="Nueva máquina" style="width: 80%" />
	</a>
	<?php } ?>
	<div class="container-fluid">
  	<h3 style="font-size: 20px;"><a href="<?php echo base_url('maquinas/1'); ?>" style="color: #000; text-decoration: none">Máquinas <?php if(isset($contador_total_maquinas)){ echo "(".$contador_total_maquinas.")"; } ?></a></h3>
  	<div style="position: relative; height: 45px; padding-top: 3px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; <?php if(isset($estilo)){ echo $estilo; } ?>">
		<h4 style="margin-bottom: 30px">
			Filtros<span style="float: right; margin-right: 2%" class="glyphicon glyphicon-triangle-bottom"></span>
		</h4>			
	</div>
	
	<div class="col-md-12">
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('buscador_maquinas/1/0/0'); ?>
		<?php if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){ ?>
		<div class="col-md-3" style="margin: 10px 0;">
			<div class="col-md-12 col-sm-12">
				<label>Identificador</label>
				<div class="input-group" style="padding-top: 2%">
					<select class="js-example-basic-single" id="id_maquina" name="id_maquina">
						<option value="9999999999999999999">TODAS</option>
						<?php echo $html_id_maquinas; ?>
					</select>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="col-md-3" style="margin: 10px 0;">
			<div class="col-md-12 col-sm-12">
				<label>Salón</label>
				<div class="input-group" style="padding-top: 2%">
					<select class="js-example-basic-single" id="salon" name="salon">
						<option value="9999999999999999999">TODOS</option>
						<?php echo $html_salones; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-3" style="margin: 10px 0;">
			<div class="col-md-12 col-sm-12">
				<label>Tipo máquina</label>
				<div class="input-group" style="padding-top: 2%">
					<select class="js-example-basic-single" id="tipo" name="tipo">
						<option value="9999999999999999999">TODAS</option>
						<?php echo $html_tipos; ?>
					</select>
				</div>
			</div>
		</div>
		<?php if($this->session->userdata('logged_in')['rol'] == 1 || ($this->session->userdata('logged_in')['rol'] == 2 && $this->session->userdata('logged_in')['acceso'] == 24)){ ?>
		<div class="col-md-3" style="margin: 10px 0;">
			<div class="col-md-12 col-sm-12">
				<label>Monedero</label>
				<div class="input-group" style="padding-top: 2%">
					<select class="js-example-basic-single" id="monedero" name="monedero">
						<option value="9999999999999999999">TODOS</option>
						<?php echo $html_monederos; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-3" style="margin: 10px 0;">
			<div class="col-md-12 col-sm-12">
				<label>Billetero</label>
				<div class="input-group" style="padding-top: 2%">
					<select class="js-example-basic-single" id="billetero" name="billetero">
						<option value="9999999999999999999">TODOS</option>
						<?php echo $html_billeteros; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-3" style="margin: 10px 0;">
			<div class="col-md-12 col-sm-12">
				<label>Impresora</label>
				<div class="input-group" style="padding-top: 2%">
					<select class="js-example-basic-single" id="impresora" name="impresora">
						<option value="9999999999999999999">TODOS</option>
						<?php echo $html_impresoras; ?>
					</select>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="col-md-2 col-sm-12 inputs">
			<div id="boton_aceptar_buscador_maquinas" class="btn-group">
				<button type="submit" class="btn btn-danger dropdown-toggle">
					Aceptar 
				</button> 
			</div>
		</div>
	</div>
	<!-- Tabla maquinas -->
	<h4 style="margin-top: 1%">Maquinas</h4>
	<input type="hidden" id="consulta_sql" value="<?php if(isset($consulta_sql)){ echo $consulta_sql; } ?>">
	<hr/>
	<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
		<div class="panel panel-default">
			<table class="table tabla_incidencias">
				<thead>
					<tr>
						<th class="th_tabla">Máquina</th>
						<th class="th_tabla"><a href="#" class="agrupar" id="salon">Salón</a></th>
						<th class="th_tabla">Fabricante</th>
						<th class="th_tabla"><a href="#" class="agrupar" id="modelo">Modelo</a></th>
						<th class="th_tabla">Acciones</th>
					</tr>
				</thead>
				<tbody id="tabla_incidencias">
					<?php echo $tabla_maquinas; ?>
				</tbody>
			</table>
		</div>
		<nav aria-label="..." style="text-align: center;">
			<ul class="pagination">
			<?php
				if(isset($consulta_sql)){
					if($consulta_sql != ''){
						$link = 'buscador_maquinas';
					}else{
						$link = 'maquinas';
					}
				}else{
					$link = 'maquinas';
					$consulta_sql = '';
				}
				if(!isset($salon)){ $salon = ''; }
				if(!isset($tipo_maquina)){ $tipo_maquina = ''; }
				if ($paginas > 1) {
				   if ($pagina != 1)
				      echo '<li>
											<a style="color: #d80039" href="'.base_url($link."/".($pagina-1)."/".$salon."/".$tipo_maquina."/".$consulta_sql."").'" aria-label="Previous">
												<span aria-hidden="true">«</span>
											</a>
										</li>';
				      for ($i=1;$i<=$paginas;$i++) {
				         if ($pagina == $i)
				            echo '<li class="active">
														<a href="'.base_url($link."/".($i)."/".$salon."/".$tipo_maquina."/".$consulta_sql."").'">'.($i).'</a>
													</li>';
				         else
				            echo '<li>
														<a style="color: #d80039" href="'.base_url($link."/".($i)."/".$salon."/".$tipo_maquina."/".$consulta_sql."").'">'.($i).'</a>
													</li>';
				      }
				      if ($pagina != $paginas)
				          echo '<li>
											<a style="color: #d80039" href="'.base_url($link."/".($pagina+1)."/".$salon."/".$tipo_maquina."/".$consulta_sql."").'" aria-label="Next">
												<span aria-hidden="true">»</span>
											</a>
										</li>';
				}
			?>					
			</ul>
		</nav>
		</div>
  	<div class="col-md-12" id="div_agrupados" style="display: none">
  	</div>
  	<div class="col-md-12" id="version_movil">
  		<?php echo $version_movil; ?>
  		<nav aria-label="..." style="text-align: center;">
				<ul class="pagination">
				<?php
					if(isset($consulta_sql)){
						if($consulta_sql != ''){
							$link = 'buscador_maquinas';
						}else{
							$link = 'maquinas';
						}
					}else{
						$link = 'maquinas';
					}
					if(!isset($salon)){ $salon = ''; }
					if(!isset($tipo_maquina)){ $tipo_maquina = ''; }
					if ($paginas > 1) {
					   if ($pagina != 1)
					      echo '<li>
												<a style="color: #d80039" href="'.base_url($link."/".($pagina-1)."/".$salon."/".$tipo_maquina."/").'" aria-label="Previous">
													<span aria-hidden="true">«</span>
												</a>
											</li>';
					      for ($i=1;$i<=$paginas;$i++) {
					         if ($pagina == $i)
					            echo '<li class="active">
															<a href="'.base_url($link."/".($i)."/".$salon."/".$tipo_maquina."/").'">'.($i).'</a>
														</li>';
					         else
					            echo '<li>
															<a style="color: #d80039" href="'.base_url($link."/".($i)."/".$salon."/".$tipo_maquina."/").'">'.($i).'</a>
														</li>';
					      }
					      if ($pagina != $paginas)
					          echo '<li>
												<a style="color: #d80039" href="'.base_url($link."/".($pagina+1)."/".$salon."/".$tipo_maquina."/").'" aria-label="Next">
													<span aria-hidden="true">»</span>
												</a>
											</li>';
					}
				?>					
				</ul>
			</nav>
  	</div>
</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_gestion_maquinas.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('files/js/script_agrupar_maquinas.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
    $('.js-example-basic-single').select2();
	});
</script>
</html>
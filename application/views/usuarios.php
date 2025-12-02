<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5){ ?>          
  <?php }else{ ?>
	<a href="<?php echo base_url('nuevo_usuario'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nuevo usuario" title="Nuevo usuario" style="width: 80%" />
	</a>
	<?php } ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px;"><a href="<?php echo base_url('usuarios/1'); ?>" style="color: #000; text-decoration: none">Usuarios</a></h3>		
		<!-- Tabla usuarios -->
		<!-- Buscador -->
		<div id="ocultar_buscador" style="position: relative; height: 45px; padding-top: 3px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; cursor: pointer">
			<h4 style="margin-bottom: 30px">
				Filtros<span style="float: right; margin-right: 2%" class="glyphicon glyphicon-triangle-bottom"></span>
			</h4>			
		</div>
		
		<div id="ocultar" style="display: none; float: left; width: 100%">
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('buscador_usuarios/'); ?>
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
						<select class="form-control" id="rol" name="rol">
						  	<option value="">Rol...</option>						  	
						  	<?php if(isset($html_roles)){ echo $html_roles; } ?>					
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
		
		<hr/>
		<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">Nombre</th>
							<th class="th_tabla">Email</th>
							<th class="th_tabla">Telefono</th>
							<th class="th_tabla">Usuario</th>
							<th class="th_tabla">Rol</th>
							<th class="th_tabla">Estado</th>
							<th class="th_tabla">Acciones</th>
						</tr>
					</thead>
					<tbody id="tabla_incidencias">
						<?php echo $tabla_usuarios; ?>
					</tbody>
				</table>
			</div> 	
  	</div>
  	<div class="col-md-12" id="version_movil">
  		<?php echo $version_movil; ?>  		
  	</div>
  	<nav aria-label="..." style="text-align: center;">
				<ul class="pagination">
				<?php
					$link = 'usuarios';
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
				?>					
				</ul>
			</nav>
  </div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_gestion_usuarios.js'); ?>"></script>
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
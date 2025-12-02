<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php if($this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 5){ ?>          
  <?php }else{ ?>
	<a href="<?php echo base_url('nuevo_departamento'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nuevo departamento" title="Nuevo departamento" style="width: 80%" />
	</a>
	<?php } ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px"><a href="<?php echo base_url('departamentos'); ?>" style="color: #000; text-decoration: none">Departamentos</a></h3>		
		<!-- Tabla usuarios -->
		<hr/>
		<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">Grupo</th>
							<th class="th_tabla">Nombre</th>
							<th class="th_tabla">Email</th>
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
  </div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_gestion_usuarios.js'); ?>"></script>
<script type="text/javascript">
	$('a').on('click', function(e){
		e.stopImmediatePropagation();
		localStorage.setItem("scrollTop", $(window).scrollTop());
	});
</script>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if(isset($cliente)){ ?>
<a href="<?php echo base_url('nueva/'.$cliente["id_op"].'/'.$cliente["id_salon"].'/'.$cliente["cliente"].'/'.$cliente["telefono"]); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
<?php }else{ ?>
<a href="<?php echo base_url('nueva'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
<?php } ?>
	<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nueva incidencia" title="Nueva incidencia" style="width: 80%" />
</a>
<div class="container-fluid">
  	<h3 style="font-size: 20px"><a href="<?php echo base_url('centralita'); ?>" style="color: #000; text-decoration: none">Centralita</a></h3>
	<hr/>
	<?php if(isset($html_llamada)){ echo $html_llamada; } ?>
	<div class="col-md-12" id="version_escritorio" style="margin-bottom: 5%;">
		<div class="panel panel-default" style="margin: 0">
			<table class="table tabla_incidencias">
				<thead>
					<tr>
						<th class="th_tabla" style="width: 1% !important">Código</th>
						<th class="th_tabla"><a href="#" class="agrupar" id="fecha_creacion">F.Creación</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="situacion">Situación</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="situacion">Salón</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="tipo_averia">Avería</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="tipo_error">Error</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="detalle_error">Detalle</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="maquina">Máquina</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="creador">Autor</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="asignado">Asignado</a></th>
						<th class="th_tabla"><a href="#" class="agrupar" id="tratamiento">Tratamiento</a></th>
						<th class="th_tabla">Prioridad</th>
						<th class="th_tabla">Acciones</th>
					</tr>
				</thead>
				<tbody id="tabla_incidencias">
					<?php if(isset($html_incidencias)){ echo $html_incidencias; } ?>			
				</tbody>
			</table>
		</div> 	
  	</div>
  	<div class="col-md-12" id="version_movil">
  		<?php if(isset($version_movil)){ echo $version_movil; } ?>
  	</div>
</div>
</body>
<script type="text/javascript" charset="utf-8">
	$('body').on('click', '.clickable-row', function() {
		window.location = $(this).data("href");
	});
</script>
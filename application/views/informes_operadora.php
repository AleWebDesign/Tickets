<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<a href="<?php echo base_url('nuevo_informe_operadora'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nuevo Informe" title="Nuevo Informe" style="width: 80%" />
	</a>
  	<div class="container-fluid">
  		<h3><a href="<?php echo base_url('informes_operadora'); ?>" style="color: #000; text-decoration: none">Informes</a></h3>
  		<?php if(isset($tabla_informes)){ ?>
		<hr width="100%">
		<div class="col-md-12" style="border-bottom: 1px solid #ccc; float: left; width: 100%">
			<div class="col-md-12 col-sm-12">
				<div class="panel panel-default" style="margin: 0">
					<table class="table tabla_incidencias" id="tabla_incidencias">
						<thead>
							<tr>
								<th class="th_tabla">Local</th>
								<th class="th_tabla">Fecha</th>
								<th class="th_tabla">Autor</th>
								<th class="th_tabla">Acciones</th>
							</tr>
						</thead>
						<tbody id="dinamic_tbody">
							<?php echo $tabla_informes; ?>
						</tbody>
					</table>
	  			</div>
  			</div>
  		</div>
  		<?php } ?>
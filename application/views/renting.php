<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px"><a href="<?php echo base_url('renting'); ?>" style="color: #000; text-decoration: none">Renting</a></h3>		
		<!-- Tabla usuarios -->
		<hr/>
		<div class="col-md-12" style="margin-bottom: 5%; float: left;">
			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">Vehiculo</th>
							<th class="th_tabla">Usuario</th>
							<th class="th_tabla">Km Actuales</th>
							<th class="th_tabla">Fin limite Km</th>
							<th class="th_tabla">Km restantes</th>
					</thead>
					<tbody id="tabla_incidencias">
						<?php echo $tabla_renting; ?>
					</tbody>
				</table>
			</div> 	
  	</div>
  </div>
</body>
</html>
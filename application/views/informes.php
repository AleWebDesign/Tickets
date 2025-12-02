<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  	<div class="container-fluid">
  		<h3><a href="<?php echo base_url('informes'); ?>" style="color: #000; text-decoration: none">Informes</a></h3>
		<!-- Buscador -->
		<div id="ocultar_buscador" style="position: relative; height: 45px; padding-top: 3px; border-top: 1px solid #ddd; cursor: pointer;">
			<h3 style="margin: 15px 0 0 30px !important">Buscar visitas</h3>
			<hr width="100%">			
		</div>		
		<div id="ocultar" style="padding: 20px 0 10px 0">
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('buscador_informes/'); ?>
			<div class="col-md-12 col-sm-12" style="margin: 20px 0 40px;">
				<div class="col-md-3 col-sm-12">
					<label>Operador</label>
					<div class="input-group" style="padding-top: 2%">
						<select class="form-control" id="empresa" name="empresa" required>
							<?php echo $html_empresas; ?>
						</select>
					</div>
				</div>
				<div class="col-md-2 col-sm-12">
					<label>Supervisora</label>
					<div class="input-group" style="padding-top: 2%">
						<select class="form-control" id="supervisora" name="supervisora">
							<?php echo $html_supervisoras; ?>
						</select>
					</div>
				</div>
				<div class="col-md-3 col-sm-12">
					<label>Fecha inicio</label>
					<div class="input-group" style="padding-top: 2%">
						<span class="input-group-addon" id="basic-addon1">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input id="datepicker1" type="text" name="fecha_inicio" class="form-control" placeholder="Desde" <?php if(isset($fecha_inicio)){ echo "value='".$fecha_inicio."'"; } ?>>
					</div>
				</div>
				<div class="col-md-3 col-sm-12">
					<label>Fecha fin</label>
					<div class="input-group" style="padding-top: 2%">
						<span class="input-group-addon" id="basic-addon1">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input id="datepicker2" type="text" name="fecha_fin" class="form-control" placeholder="Hasta" <?php if(isset($fecha_fin)){ echo "value='".$fecha_fin."'"; } ?>>
					</div>
				</div>
				<div class="col-md-3 col-sm-12">
					<button type="submit" class="btn btn-danger dropdown-toggle" style="margin-top: 7%;">
						Buscar
					</button> 
				</div>
			</div>
			</form>
			<hr width="100%">
		</div>		
		<?php if(isset($tabla_visitas)){ ?>
		<h3 style="margin: 15px 0 0 30px !important">Visitas</h3>
		<hr width="100%">
		<?php } ?>		
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('generar_informe_pdf/', 'id="informe_form"'); ?>
		<?php if(isset($tabla_visitas)){ ?>
			<div class="col-md-12" style="margin: 0">
				<div class="col-md-12 col-sm-12" style="padding: 0 15px 10px 22px; float: left; width: 100%;">
					<a href="#" class="check_all_visits" style="text-decoration: underline; float: right">Seleccionar todos</a>
				</div>
				<?php echo $tabla_visitas; ?>
	  		</div>
	  		<script type="text/javascript">
				function sortTableSalon(){
					var table, rows, switching, i, x, y, shouldSwitch;
					table = document.getElementById("tabla_visitas");
					switching = true;

					while(switching){
						switching = false;
						rows = table.rows;

						for(i = 1; i < (rows.length - 1); i++){
							shouldSwitch = false;
						  	x = rows[i].getElementsByTagName("TD")[0];
						  	y = rows[i + 1].getElementsByTagName("TD")[0];

						  	if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
						    	shouldSwitch = true;
						    	break;
						  	}
						}

						if(shouldSwitch){
						  	rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
						  	switching = true;
						}
					}
				}

				function sortTableOp(){
					var table, rows, switching, i, x, y, shouldSwitch;
					table = document.getElementById("tabla_visitas");
					switching = true;

					while(switching){
						switching = false;
						rows = table.rows;

						for(i = 1; i < (rows.length - 1); i++){
							shouldSwitch = false;
							x = rows[i].getElementsByTagName("TD")[1];
						  	y = rows[i + 1].getElementsByTagName("TD")[1];

						  	if(x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()){
						    	shouldSwitch = true;
						    	break;
						  	}
						}

						if(shouldSwitch){
						  	rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
						  	switching = true;
						}
					}
				}

				function sortTableDate(){
					var table, rows, switching, i, x, y, shouldSwitch;
					table = document.getElementById("tabla_visitas");
					switching = true;

					while(switching){
						switching = false;
						rows = table.rows;

						for(i = 1; i < (rows.length - 1); i++){
						  	shouldSwitch = false;
						  	x = rows[i].getElementsByTagName("TD")[2];
						  	y = rows[i + 1].getElementsByTagName("TD")[2];

						  	date1 = new Date(x.id);
						  	date2 = new Date(y.id);
						  
						  	if(date1 > date2){
						    	shouldSwitch = true;
						    	break;
						  	}				      
						}

						if(shouldSwitch){
						  	rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
						  	switching = true;
						}
					}
				}
			</script>

	  		<div class="col-md-12 col-sm-12" style="padding: 12px 15px 10px 22px; text-align: right; float: left; width: 100%;">
				<input type="hidden" name="id_empresa" value="<?php if(isset($id_empresa)){ echo $id_empresa; } ?>">
				<input type="hidden" name="sp" value="<?php if(isset($supervisora)){ echo $supervisora; } ?>">
				<input type="hidden" name="fi" value="<?php if(isset($fecha_inicio)){ echo $fecha_inicio; } ?>">
				<input type="hidden" name="ff" value="<?php if(isset($fecha_fin)){ echo $fecha_fin; } ?>">
				<button id="submit_informes" type="submit" class="btn btn-warning dropdown-toggle">
					Generar Informe
				</button>
			</div>
		<?php } ?>
  		</form>
		
		<?php if(isset($tabla_informes)){ ?>
		<hr width="100%">
		<h3 style="margin: 15px 0 0 30px !important">Informes</h3>
		<hr width="100%">
		<div class="col-md-12 col-sm-12" style="margin: 20px 0 40px;">
			<div class="col-md-3 col-sm-12">
				<label>Operador</label>
				<div class="input-group" style="padding-top: 2%">
					<select class="form-control" id="empresa2" name="empresa2">
						<?php echo $html_empresas; ?>
					</select>
				</div>
			</div>
			<div class="col-md-2 col-sm-12">
				<label>Supervisora</label>
				<div class="input-group" style="padding-top: 2%">
					<select class="form-control" id="supervisora2" name="supervisora2">
						<?php echo $html_supervisoras; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-12" style="border-bottom: 1px solid #ccc; float: left; width: 100%">
			<div class="col-md-12 col-sm-12">
				<div class="panel panel-default" style="margin: 0">
					<table class="table tabla_incidencias" id="tabla_incidencias">
						<thead>
							<tr>
								<th class="th_tabla">Operador</th>
								<th class="th_tabla">Fecha</th>
								<th class="th_tabla">Supervisora</th>
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
  		<script type="text/javascript">
  			$('#empresa2').on('change', function(){
  				empresa = $(this).val();
  				supervisora = $('#supervisora2').val();
  				$.ajax({
			        type: "POST",
			        data: "empresa=" + empresa + "&supervisora=" + supervisora,
			        url: "http://atc.apuestasdemurcia.es/tickets/get_informes_ajax",
			        success: function(data){
			        	$('#dinamic_tbody').html(data);
			        }
			    });
  			});

  			$('#supervisora2').on('change', function(){
  				supervisora = $(this).val();
  				empresa = $('#empresa2').val();
  				$.ajax({
			        type: "POST",
			        data: "empresa=" + empresa + "&supervisora=" + supervisora,
			        url: "http://atc.apuestasdemurcia.es/tickets/get_informes_ajax",
			        success: function(data){
			        	$('#dinamic_tbody').html(data);
			        }
			    });
  			});
  		</script>
		<?php } ?>	
  	</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_gestion_informes.js'); ?>"></script>
<script type="text/javascript">
  $(function(){
      $('#datepicker1,#datepicker2').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
  });
</script>
</html>
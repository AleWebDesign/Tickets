<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<a id="nuevo_repostaje" href="#" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nuevo Repostaje" title="Nuevo Repostaje" style="width: 80%" />
	</a>
	<div class="container-fluid" style="margin-bottom: 2%;">
		<div id="div1" class="col-md-12" style="border-bottom: 1px solid #ccc; padding-bottom: 3%;">
			<h3 style="font-size: 20px"><a href="<?php echo base_url('gasoil'); ?>" style="color: #000; text-decoration: none">Gasoil</a></h3>
			<hr>
			<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 7){ ?>
			<?php if(isset($tiene_deposito) && $tiene_deposito == 1){ ?>
			<div id="div_deposito" style="border: 1px solid #ccc; display: inline-block; padding: 1%; border-radius: 5px; box-shadow: 2px 2px #ccc;">
				<h4>Depósito</h4>
				<p style="font-weight: bold">Histórico depósito: <span style="color: #5cb85c; float: right"><?php if(isset($total_deposito)){ echo $total_deposito; } ?> litros</span></p>
				<p style="font-weight: bold">Total gasto depósito: <span style="color: #5cb85c; float: right"><?php if(isset($total_gasto)){ echo $total_gasto; } ?> litros</span></p>
				<p style="font-weight: bold">Último depósito: <span style="color: #5cb85c; float: right; margin-left: 1%"><?php if(isset($ultimo_deposito)){ $fecha = explode('-', $ultimo_deposito->fecha); echo "(".$fecha[2]."-".$fecha[1]."-".$fecha[0].")"; } ?></span><span style="color: #5cb85c; float: right"><?php if(isset($ultimo_deposito)){ echo $ultimo_deposito->deposito; } ?> litros</span></p>
				<p style="font-weight: bold">Depósito restante: <span style="color: #5cb85c; float: right"><?php if(isset($deposito_actual)){ echo $deposito_actual; } ?> litros</span></p>
				<a href="nuevo_deposito" class="btn btn-info">
					<img style="width: 5%" alt="Gas" title="Gas" src="<?php echo base_url('files/img/gas_station.png'); ?>">
					Rellenar depósito
				</a>
			</div>
			<?php } ?>
		</div>
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('buscador_gasoil', 'id="gasoil_form"'); ?>
		<div id="div2" class="col-md-12" style="margin-top: 3%;">
			<h3 style="margin-top: 0 !important;">Buscar Repostajes</h3>
			<hr>
		</div>
		<?php if($this->session->userdata('logged_in')['rol'] == 7){ ?>
		<div class="col-md-3 col-sm-12 inputs">
			<label>Fecha Inicio</label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
				<input id="datepicker1" type="text" name="fecha_inicio" class="form-control" placeholder="Desde" <?php if(isset($fecha_inicio) && $fecha_inicio != ''){ echo "value='".$fecha_inicio."'"; $fecha1 = explode('/', $fecha_inicio); $fecha_inicio = $fecha1[2]."-".$fecha1[1]."-".$fecha1[0]; }else{ $fecha_inicio = ''; } ?>>
			</div>
		</div>
		<div class="col-md-3 col-sm-12 inputs">
			<label>Fecha Fin</label>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
				<input id="datepicker2" type="text" name="fecha_fin" class="form-control" placeholder="Desde" <?php if(isset($fecha_fin) && $fecha_fin != ''){ echo "value='".$fecha_fin."'"; $fecha2 = explode('/', $fecha_fin); $fecha_fin = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]; }else{ $fecha_fin = ''; } ?>>
			</div>
		</div>
		<?php } ?>
		<div class="col-md-3 col-sm-12 inputs">
			<label>Usuario</label>
			<div class="input-group">
			  <select class="js-example-basic-single" id="usuario" name="usuario" required="">
			  	<option value="0">Todos</option>
			  	<?php echo $select_usuarios; ?>
			  </select>
			</div>
		</div>
		<div class="col-md-2 col-sm-12 inputs">
			<div id="boton_aceptar_buscador_gasoil" class="btn-group">
				<button type="submit" class="btn btn-danger dropdown-toggle">
					Aceptar 
				</button> 
			</div>
		</div>
		</form>
		<?php if(isset($buscador_gasoil)){ ?>
		<div class="col-md-12" style="margin: 1% 0 0 0;">
			<span style="font-weight: bold">Total: <?php if(isset($total)){ echo $total." litros"; } ?></span><a style="float: right" href="<?php echo base_url('gasoil_excel/'.$operadora.'/'.$user.'/'.$fecha_inicio.'/'.$fecha_fin); ?>" class="btn btn-info" target="_blank">Exportar Excel</a>
		</div>
		<div class="col-md-12" style="padding: 2% 0; border-bottom: 1px solid #ccc;">
			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias" id="tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">Usuario</th>
							<th class="th_tabla">Vehículo</th>
							<th class="th_tabla">Repostaje</th>
							<th class="th_tabla">Kilómetros</th>
							<th class="th_tabla">Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php echo $buscador_gasoil; ?>
					</tbody>
				</table>
  		</div>
  	</div>
  	<?php } ?>
		<?php } ?>
		<div id="div3" class="col-md-12" style="margin-top: 3%;">
			<h3 style="margin-top: 0 !important;">Tus últimos repostajes</h3>
			<hr>
		</div>
		<?php if(isset($tabla_gasoil)){ ?>
		<div id="div4" class="col-md-12" style="padding: 2% 0; border-bottom: 1px solid #ccc;">
			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias" id="tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">Vehículo</th>
							<th class="th_tabla">Repostaje</th>
							<th class="th_tabla">Kilómetros</th>
							<th class="th_tabla">Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php echo $tabla_gasoil; ?>
					</tbody>
				</table>
  			</div>
  		</div>
  	<?php } ?>
	</div>
	<div id="modal" style="width: 100%; height: 100%; display: none; background: rgba(0,0,0,0.5); position: absolute; top: 0; left: 0; z-index: 9999;">
		<div style="width: 80%; margin: 20% auto 0; background: #fff; border: 2px solid #d80039; border-radius: 5px; padding: 10px; height: 150px;">
			<p style="text-align: center; font-weight: bold; font-size: 16px; margin: 0 0 20px;">Se va a activar el surtidor ¿Está seguro que desea repostar?</p>
			<div class="btn-group pull-left" style="margin: 2% 0;">
				<button id="cancelar" class="btn btn-danger dropdown-toggle">
					No 
				</button> 
			</div>
			<div class="btn-group pull-right" style="margin: 2% 0;">
				<button id="aceptar" class="btn btn-success dropdown-toggle">
					Si 
				</button>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
  $(function(){
      $('#datepicker1,#datepicker2').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
  });
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

$('#nuevo_repostaje').on('click', function(e){
	e.preventDefault();
	var scrollPosition = [
	  self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
	  self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
	];
	var html = jQuery('html');
	html.data('scroll-position', scrollPosition);
	html.data('previous-overflow', html.css('overflow'));
	html.css('overflow', 'hidden');
	window.scrollTo(scrollPosition[0], scrollPosition[1]);
	$('#modal').css({ display: 'block', top: $(window).scrollTop() });
});

$('#cancelar').on('click', function(){
	var html = jQuery('html');
	var scrollPosition = html.data('scroll-position');
	html.css('overflow', html.data('previous-overflow'));
	window.scrollTo(scrollPosition[0], scrollPosition[1])
	$('#modal').css('display', 'none');
});

$('#aceptar').on('click', function(){
	window.location.href = "nuevo_repostaje";
});
</script>
</html>
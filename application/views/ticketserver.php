<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<div class="col-md-12">
		<h3 style="font-size: 20px;">
			<a href="<?php echo base_url('gestion'); ?>" style="text-decoration: underline; color: #c10d3e;">
				<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Incidencias</a>
			<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			<a href="<?php echo base_url('ticketserver'); ?>" style="color: #000; text-decoration: none">Nuevo ticket</a>
		</h3>
		<hr/>
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('nuevo_ticketserver/', 'id="myform"'); ?>
		<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
			<div class="panel-heading" style="cursor: pointer">
				Datos<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
			</div>
			<div class="panel-body">
				<?php if($conectado == 1){ echo '<p style="font-weight: bold; color: green; margin: 1%;">Cajero conectado</p>'; ?>
				<div class="col-md-4 col-sm-12">
					<label>Máquina</label>
					<div class="input-group">
						<select class="form-control" name="maquina" id="maquina" required>
						  	<option value="">Máquina...</option>
							<?php if(isset($html_maquinas)){ echo $html_maquinas; } ?>
						</select>
					</div>
				</div>

				<div class="col-md-4 col-sm-12">
					<label>Importe</label>
					<div class="input-group">
						<input class="form-control" type="text" inputmode="numeric" id="importe" name="importe" required>
					</div>
				</div>

				<div class="col-md-4 col-sm-12">
					<label>Comentario</label>
					<div class="input-group">
						<textarea class="form-control" id="texto" name="texto" rows="6" placeholder="Comentario..." required></textarea>		
					</div>					
				</div>

				<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
					<div class="btn-group" style="margin: 2% 0;">
						<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
							Imprimir 
						</button> 
					</div>
					<a id="volver_button" href="<?php echo base_url('gestion'); ?>" class="btn btn-warning dropdown-toggle">
						Volver
					</a>
					<?php echo $ticket_creado; ?> 
				</div>
				<?php }else{ echo '<p style="font-weight: bold; color: red; margin: 1%;">Cajero desconectado</p>'; } ?>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
/* Solo numeros */
$("#importe").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190, 110]) !== -1 ||
         // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
         // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});
</script>
</html>


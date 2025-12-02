<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px;">
			<a href="<?php echo base_url('gestion/'.$this->uri->segment(4).''); ?>" style="text-decoration: underline; color: #c10d3e;">
				<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Incidencias</a>
			<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			<a style="color: #000; text-decoration: none">Solucionar incidencia</a>
		</h3>
		<hr/>
		<!-- Buscador -->
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('solucionar_ticket_form/'); ?>
		<div class="panel panel-default col-md-6 col-sm-12" style="padding: 0">
			<div class="panel-heading" style="background: #d9534f; text-align: center">
				<p style="color: #fff">#<?php if(isset($ticket)){ echo $ticket->id; } ?> - <?php $fecha_creacion = explode("-", $ticket->fecha_creacion); echo $fecha_creacion[2]."/".$fecha_creacion[1]."/".$fecha_creacion[0]; ?> <?php echo $ticket->hora_creacion; ?> - <?php if(isset($operadora)){ echo $operadora; } ?> - <?php if(isset($salon)){ echo $salon; } ?></p>
			</div>
			<div class="panel-body" style="padding-bottom: 8px;">
				<div class="col-md-12 col-sm-12" style="padding: 10px 10px 10px 10px;">
					<?php echo $html_historial; ?>
					<?php echo $html_perifericos; ?>
					<label id="label_imagen">Imágen incidencia (Max: <?php echo ini_get('post_max_size'); ?>).</label>
					<div class="input-group">
						<input class="form-control" type="file" id="trata_imagen" name="trata_imagen" accept="image/*">
					</div>					
					<br/>
					<label>Tratamiento</label>
					<div class="input-group">
						<textarea class="form-control" name="trata_desc" rows="6" placeholder="Tratamiento de la incidencia..." title="Texto obligatorio" minlength="12" required></textarea>
					</div>
				</div>
				<input type="hidden" name="id_ticket" value="<?php if(isset($ticket)){ echo $ticket->id; } ?>">
				<input type="hidden" name="destino" value ="<?php if(isset($ticket->destino)){ echo $ticket->destino; } ?>">
				<input type="hidden" name="situacion_ticket" value="<?php if(isset($ticket)){ echo $ticket->situacion; } ?>">				
				<?php if($ticket->tipo_error == '62' || $ticket->tipo_error == '77' || $ticket->tipo_error == '113' || $ticket->tipo_error == '58'){ ?>				
					<a href="<?php echo base_url('crear_ticket_manual/'.$ticket->id.''); ?>" style="width: 30%; padding: 2px 4px; margin: 0 4px;" class="btn btn-primary">
						<i style="font-size: 30px" class="fa fa-ticket"></i>
						<span style="display: block; font-weight: bold">
						Crear ticket
						</span> 
					</a>						
					<button style="width: 30%; padding: 2px 4px; margin: 0 4px;" type="submit" name="sin" class="btn btn-warning">
						<i style="font-size: 30px" class="fa fa-check"></i>
						<span style="display: block; font-weight: bold">
						Sin solucionar
						</span> 
					</button>
					<button style="width: 30%; padding: 2px 4px; margin: 0 4px;" type="submit" name="con" class="btn btn-success">
						<i style="font-size: 30px; margin-left: 10px" class="fa fa-check"></i>
						<i style="font-size: 30px; margin-left: -20px" class="fa fa-check"></i>
						<span style="display: block; font-weight: bold">
						Solucionado
						</span> 
					</button>					
				<?php }else{ ?>					
					<button style="width: 45%; padding: 2px 4px; margin: 0 4px;" type="submit" name="sin" class="btn btn-warning">
						<i style="font-size: 30px" class="fa fa-check"></i>
						<span style="display: block; font-weight: bold">
						Sin solucionar
						</span> 
					</button>
					<button style="width: 45%; padding: 2px 4px; margin: 0 4px;" type="submit" name="con" class="btn btn-success">
						<i style="font-size: 30px; margin-left: 10px" class="fa fa-check"></i>
						<i style="font-size: 30px; margin-left: -20px" class="fa fa-check"></i>
						<span style="display: block; font-weight: bold">
						Solucionado
						</span> 
					</button>				
				<?php } ?>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('gestion/'.$this->uri->segment(4).''); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
    $('.js-example-basic-single').select2();
	});
</script>
</html>
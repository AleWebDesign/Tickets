<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px;">
			<a href="<?php echo base_url('gestion/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>" style="text-decoration: underline; color: #c10d3e;">
				<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Incidencias</a>
			<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			<a style="color: #000; text-decoration: none">Asignar tecnico</a>
		</h3>
		<hr/>
		<!-- Buscador -->
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('asignar_ticket_tecnico_form/'); ?>
		<input type="hidden" name="id_ticket" value="<?php echo $id_ticket; ?>">
		<div class="col-md-12 col-sm-12">
				<?php echo $html_asignado; ?>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
			<a id="volver_button" href="<?php echo base_url('gestion/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>" class="btn btn-warning dropdown-toggle">
				Volver
			</a>
		</div>
  </div>
</body>
</html>
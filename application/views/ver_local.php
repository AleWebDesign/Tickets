<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px;">
			<a href="<?php echo base_url('locales/1/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>" style="text-decoration: underline; color: #c10d3e;">
				<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Locales</a>
			<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			<a style="color: #000; text-decoration: none">Ficha local</a></a>
		</h3>
		<hr/>
		<div class="col-md-12 col-sm-12">
				<?php echo $html_local; ?>
		</div>
		<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
			<div class="panel-heading" style="cursor: pointer">
				Imágenes
			</div>
			<div class="panel-body">
				<?php echo $img_container; ?> 				
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('locales/1/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
</body>
</html>
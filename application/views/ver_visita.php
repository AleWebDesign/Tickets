<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<script src="<?php echo base_url('files/tinymce/js/tinymce/tinymce.min.js'); ?>"></script>
  	<script>
  		tinymce.init({
  			selector: 'textarea',
  			language: 'es',
  			height: 500,
  		});
  	</script>
	<style type="text/css">
		table tr th, table tr td {
		    width: auto !important;
		    font-size: 12px !important;
		}

		/* on mobile browsers, I set a width of 100% */
		table.mceLayout, textarea.tinyMCE {
		    width: 100% !important;
		}
	</style>
	<!-- /TinyMCE -->
  	<div class="container-fluid">
  		<h3 style="font-size: 20px;">
			<a href="#" onclick="window.close();" style="text-decoration: underline; color: #c10d3e;">
				<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Visitas</a>
			<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			<a style="color: #000; text-decoration: none">Ficha Visita</a></a>
		</h3>
		<hr/>
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('nueva_observacion_visita_form/', 'id="myform"'); ?>
		<div class="col-md-12 col-sm-12">
				<?php echo $html_visita; ?>
		</div>
		<div class="col-md-12 col-sm-12" style="padding: 10px; border: 1px solid transparent; border-top: 0; border-color: #ddd;">
			<label>Añadir comentario</label>
			<div class="input-group">
				<textarea class="form-control" name="texto" rows="6" placeholder="Observaciones..."></textarea>
			</div>
		</div>
  		<div class="col-md-12" style="float: left; width: 100%;">
			<div class="btn-group pull-right" style="margin: 2% 0;">
				<input type="hidden" value="<?php echo $id; ?>" name="id">
				<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
					Aceptar 
				</button> 
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
			<a href="#" onclick="window.close();" class="btn btn-warning dropdown-toggle">
				Volver
			</a>
		</div>
  </div>
</body>
</html>
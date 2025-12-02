<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px;">
			<a href="<?php echo base_url('locales/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>" style="text-decoration: underline; color: #c10d3e;">
				<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Locales</a>
			<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			<a style="color: #000; text-decoration: none"><?php echo $salon->salon; ?></a></a>
		</h3>
		<hr/>
		<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
			<div class="panel-heading" style="cursor: pointer">
				Imágenes
			</div>
			<div class="panel-body">
				<?php echo $img_container; ?> 				
			</div>
		</div>
		<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
			<div class="panel-heading" style="cursor: pointer">
				Subir imágenes
			</div>
			<div class="panel-body" style="padding: 20px">
				<input id="input-id" name="images[]" type="file" multiple>	
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('locales/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
</body>
<script>
  $("#input-id").fileinput({
  	'language' : 'es',
  	maxFileCount: 10,
  	allowedFileExtensions: ["jpg", "png", "gif"],
  	maxFileSize: 2000,
  	uploadUrl: "https://atc.apuestasdemurcia.es/tickets/upload_images/<?php echo $salon->id; ?>"
  }).on('fileuploaded', function(event, data, previewId, index) {
  	setTimeout(function(){ window.location.href = "../local_img/<?php echo $salon->id; ?>"; }, 3000);
	});
</script>
</html>
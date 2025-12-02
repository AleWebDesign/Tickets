<?php defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
	<script src="<?php echo base_url('files/tinymce/js/tinymce/tinymce.min.js'); ?>"></script>
  	<script>
  		tinymce.init({
  			selector: 'textarea',
  			language: 'es',
  			height: 600,
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
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('visitas'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Visitas</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('nueva_visita'); ?>" style="color: #000; text-decoration: none">Nueva</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('nueva_visita_form/', 'id="uploadImageForm"'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos Generales<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
				</div>
				<div class="panel-body">
					<div class="col-md-2 col-sm-12" style="padding: 10px;">
						<label>Operadora</label>
						<div class="input-group">
						  <select class="form-control" id="operador" name="operador">
						  	<option value="0">Ninguno</option>
						  	<?php echo $html_op; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px;">
						<label>Salón</label>
						<div class="input-group">
						  <select class="form-control" id="salon" name="salon">
						  	<option value="0">Ninguno</option>
						  	<?php echo $html_salon; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px;">
						<label>Personal1</label>
						<div class="input-group">
							<select class="js-example-basic-single" name="personal1" id="personal1">
						  	<option value="">Personal...</option>
							  <?php echo $html_personal; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px;">
						<label>Personal2</label>
						<div class="input-group">
							<select class="js-example-basic-single" name="personal2" id="personal2">
						  	<option value="">Personal...</option>
							  <?php echo $html_personal; ?>
							</select>
						</div>
					</div>
	        		<div class="col-md-2 col-sm-12" style="padding: 10px;">
						<label>Fecha</label>
						<div class="input-group">
							<input class="form-control" type="text" id="fecha" name="fecha" required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12" style="padding: 10px;">
						<label>Imágen (Max: <?php echo ini_get('upload_max_filesize'); ?>).</label>
						<div class="input-group">
							<input class="form-control" type="file" id="imagen" name="imagen" accept="image/*" onchange="uploadPhotos('#{imageUploadUrl}')" multiple>
							<p id="imagen_p" style="display: none; font-weight: bold">Imágenes cargadas</p>
						</div>
					</div>
					<div class="col-md-12 col-sm-12" style="margin-top: 10px;">
						<a id="checklist_button" class="btn btn-danger">Desactivar checklist</a>
					</div>					
					<div id="checklist" class="col-md-12 col-sm-12" style="padding: 0; margin-top: 10px;">						
						<div class="col-md-12 col-sm-12" style="margin-top: 10px;">
							<h4>Obligatorio</h4>
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Fachada</label>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="car_obl1" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Carteles exteriores obligatorios</label>
				    		</div>				
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Córner</label>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="vin_est" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Vinilo buen estado</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="bol_pla" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Boletines / Placas terminales</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="san_cab" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Saneamiento cableado Basic</label>
				    		</div>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="car_obl2" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Carteles obligatorios</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="fol_lud" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Folletos juego responsable</label>
				    		</div>	
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="tvs_cor" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">TV's emitiendo canales deportivos</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="ver_act" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Vertical actualizada</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="cor_est" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Córner en buen estado</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="ter_inc" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Terminales en buen estado</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="dis_may" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Distintivo +18</label>
				    		</div>
						</div>
						<div class="col-md-2 col-sm-12">
							<label>TPV</label>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="tpv_inc" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">TPV sin incidencias</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="lec_tar" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Lector de tarjetas</label>
				    		</div>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="com_pro" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Comprobación de prohibidos activo</label>
				    		</div>
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Otros</label>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="per_for" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Personal de apuestas formado</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="inc_apu" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Terminales apuestas sin incidencias</label>
				    		</div>
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Mobiliario</label>
							<div class="input-group" style="margin: 5px 0">
								<input class="form-control" type="text" id="taburete" name="taburete" placeholder="Taburetes">
							</div>
							<div class="input-group" style="margin: 5px 0">
								<input class="form-control" type="text" id="mesa" name="mesa" placeholder="Mesa alta">
							</div>
							<div class="input-group" style="margin: 5px 0">
								<input class="form-control" type="text" id="tablero" name="tablero" placeholder="Tableros">
							</div>
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Señales</label>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="señ_gal" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Galgos</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="señ_lot" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Lottos</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="señ_dep" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Deportes</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="señ_otr" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Otros</label>
				    		</div>
						</div>
						<div class="col-md-12 col-sm-12" style="margin-top: 10px;">
							<h4>Opcional</h4>
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Fachada</label>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="ban_fac" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Banderola</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="fac_vin" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Referenciar ADM en fachada</label>
				    		</div>				
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Córner</label>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="aio_est" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">AIO buen estado</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="piz_int" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Adquisición y uso de pizarra</label>
				    		</div>
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Personal</label>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="per_uni" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Personal uniformado</label>
				    		</div>
						</div>
						<div class="col-md-2 col-sm-12">
							<label>Otros</label>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="pan_car" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Adquisición y uso de panel de cartelería</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="car_pro" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Imprimir cartelera deportiva</label>
				    		</div>
							<div class="input-group">
								<input type="checkbox" name="check_list[]" value="tar_adm" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Disponer de tarjetas ADM</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="lim_loc" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Revisar limpieza local</label>
				    		</div>
				    		<div class="input-group">
								<input type="checkbox" name="check_list[]" value="ent_mer" class="checklist">
								<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Entrega merchand</label>
				    		</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12" style="padding: 10px;">
						<label>Observaciones</label>
						<div class="input-group">
							<textarea class="form-control" name="texto" rows="6" placeholder="Observaciones..."></textarea>
						</div>
					</div>
				</div>				
			</div>
		</div>
  		<div class="col-md-12" style="float: left; width: 100%;">
			<div class="btn-group pull-right" style="margin: 2% 0;">
				<input type="hidden" id="imagen_subida" name="imagen_subida">
				<input type="hidden" id="checklist_activado" name="checklist_activado" value="1">
				<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
					Aceptar 
				</button>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('visitas'); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
  <script type="text/javascript" src="<?php echo base_url('files/js/script_crear_visita.js'); ?>"></script>
</body>
<script type="text/javascript">
	$(document).ready(function() {
    $('.js-example-basic-single').select2();
	});
</script>
<script type="text/javascript">
$(function () {
    $('#fecha').datetimepicker({ format: 'DD/MM/YYYY HH:mm:ss', locale: 'es' });
});
</script>
<script type="text/javascript">
$('#checklist_button').on('click', function(){
	if($('#checklist').css('display') == 'block'){
		$('#checklist').hide("slow", function() {
			$('#checklist_button').html('Activar checklist');
			$('#checklist_activado').val(0);
			$("#checklist_button").attr('class', 'btn btn-success');
		});
	}else{
		$('#checklist').show("slow", function() {
			$('#checklist_button').html('Desactivar checklist');
			$('#checklist_activado').val(1);
			$("#checklist_button").attr('class', 'btn btn-danger');
		});
	}
});

$('#operador').on('change', function(){
	if($(this).val() == 3 || $(this).val() == 10){
		$('input[value="hil_mus"]').prop('checked', false);
		$('input[value="hil_mus"]').parent().css('display', 'none');
	}else{
		$('input[value="hil_mus"]').parent().css('display', 'block');
	}
});
</script>
<script type="text/javascript">
	var url = window.location.href;
	var arr = url.split("/");

	window.uploadPhotos = function(url){
    	// Read in file
    	var file = event.target.files;

		for (var i = 0; i < file.length; ++i) {
	     	// Ensure it's an image
		    if(file[i].type.match(/image.*/)) {
		        // Load the image
		        var reader = new FileReader();
		        reader.onload = function (readerEvent) {
		            var image = new Image();
		            image.onload = function (imageEvent) {

		                // Resize the image
		                var canvas = document.createElement('canvas'),
		                    max_size = 1000,// TODO : pull max size from a site config
		                    width = image.width,
		                    height = image.height;
		                if (width > height) {
		                    if (width > max_size) {
		                        height *= max_size / width;
		                        width = max_size;
		                    }
		                } else {
		                    if (height > max_size) {
		                        width *= max_size / height;
		                        height = max_size;
		                    }
		                }
		                canvas.width = width;
		                canvas.height = height;
		                canvas.getContext('2d').drawImage(image, 0, 0, width, height);
		                var dataUrl = canvas.toDataURL('image/jpeg');
		                var resizedImage = dataURLToBlob(dataUrl);
		                $.event.trigger({
		                    type: "imageResized",
		                    blob: resizedImage,
		                    url: dataUrl
		                });
		            }
		            image.src = readerEvent.target.result;
		        }
		        reader.readAsDataURL(file[i]);
		    }   
	    }
	}

	/* Utility function to convert a canvas to a BLOB */
	var dataURLToBlob = function(dataURL) {
	    var BASE64_MARKER = ';base64,';
	    if (dataURL.indexOf(BASE64_MARKER) == -1) {
	        var parts = dataURL.split(',');
	        var contentType = parts[0].split(':')[1];
	        var raw = parts[1];

	        return new Blob([raw], {type: contentType});
	    }

	    var parts = dataURL.split(BASE64_MARKER);
	    var contentType = parts[0].split(':')[1];
	    var raw = window.atob(parts[1]);
	    var rawLength = raw.length;

	    var uInt8Array = new Uint8Array(rawLength);

	    for (var i = 0; i < rawLength; ++i) {
	        uInt8Array[i] = raw.charCodeAt(i);
	    }

	    return new Blob([uInt8Array], {type: contentType});
	}
	/* End Utility function to convert a canvas to a BLOB      */

	/* Handle image resized events */
	$(document).on("imageResized", function(event){
	    var data = new FormData($("form[id*='uploadImageForm']")[0]);
	    if (event.blob && event.url) {
	        data.append('image_data', event.blob);
	        $.ajax({
	            url: arr[0] + "//atc.apuestasdemurcia.es/tickets/subir_imagen_visita",
	            data: data,
	            cache: false,
	            contentType: false,
	            processData: false,
	            type: 'POST',
	            success: function(data){
	            	var imagen = data.replace(/^\s+/g, '');
	                $('#imagen_subida').val($('#imagen_subida').val() + ' ' + imagen);
	                $('#imagen_p').css('display', 'block');
	            }
	        });
	    }
	});
</script>
</html>
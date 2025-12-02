<?php defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
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
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="#" onclick="window.close();" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Personal</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('editar_personal/'.$persona->id); ?>" style="color: #000; text-decoration: none">Editar</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('editar_personal_form/', 'id="myform"'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos Generales<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
				</div>
				<div class="panel-body">
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Operadora</label>
						<div class="input-group">
						  <select class="form-control" id="operador" name="operador">
						  	<option value="0">Ninguno</option>
						  	<?php echo $html_op; ?>
							</select>
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Salón</label>
						<div class="input-group">
						  <select class="form-control" id="salon" name="salon">
						  	<option value="0">Ninguno</option>
						  	<?php echo $html_salon; ?>
							</select>
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Nombre</label>
						<div class="input-group">
							<input class="form-control" type="text" id="nombre" name="nombre" value="<?php echo $persona->nombre; ?>" required>
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>DNI</label>
						<div class="input-group">
							<input class="form-control" type="text" size="10" maxlength="9" name="dni" value="<?php echo $persona->dni; ?>" pattern="(([X-Z]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|((\d{8})([-]?)([A-Z]{1}))" title="Introduzca el dni" required>
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Teléfono</label>
						<div class="input-group">
							<input class="form-control" type="number" id="telefono" name="telefono" value="<?php echo $persona->telefono; ?>" >
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Email</label>
						<div class="input-group">
							<input class="form-control" type="text" id="email" name="email" value="<?php echo $persona->email; ?>" >
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Nº Registro</label>
						<div class="input-group">
							<input class="form-control" type="text" id="reg" name="reg" inputmode="numeric" maxlength="4" value="<?php echo $persona->registro; ?>">
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px 10px 22px 10px;">
						<div class="col-md-2 col-sm-12" style="padding: 0;">            
				           <label>Curso</label>
				           <div class="input-group">
				           	<input type="checkbox" name="curso" id="curso" <?php if($persona->curso == 1){ echo "checked"; } ?>>
				           </div>
				        </div>
				        <div class="col-md-2 col-sm-12" style="padding: 0;">            
				           <label>Carnet</label>
				           <div class="input-group">
				           	<input type="checkbox" name="carnet" id="carnet" <?php if($persona->carnet == 1){ echo "checked"; } ?>>
				           </div>
				        </div>
				        <div class="col-md-2 col-sm-12" style="padding: 0;">            
				           <label>Test</label>
				           <div class="input-group">
				           	<input type="checkbox" name="test" id="test" <?php if($persona->test == 1){ echo "checked"; } ?>>
				           </div>
				        </div>
				        <div class="col-md-2 col-sm-12" style="padding: 0;">            
				           <label>Activo</label>
				           <div class="input-group">
				           	<input type="checkbox" name="activo" id="activo" <?php if($persona->activo == 1){ echo "checked"; } ?>>
				           </div>
				        </div>
					</div>
	        		<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Fecha Carnet</label>
						<div class="input-group">
							<input class="form-control" type="text" id="fecha_carnet" name="fecha_carnet" value="<?php if(isset($persona->fecha_carnet) && $persona->fecha_carnet != ''){ $fecha_c = explode("-", $persona->fecha_carnet); $fecha1 = $fecha_c[2]."/".$fecha_c[1]."/".$fecha_c[0]; }else{ $fecha1 = ''; } echo $fecha1; ?>">
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Fecha Formación</label>
						<div class="input-group">
							<input class="form-control" type="text" id="fecha_formacion" name="fecha_formacion" value="<?php if(isset($persona->fecha_formacion) && $persona->fecha_formacion != ''){ $fecha_f = explode("-", $persona->fecha_formacion); $fecha2 = $fecha_f[2]."/".$fecha_f[1]."/".$fecha_f[0]; }else{ $fecha2 = ''; } echo $fecha2; ?>">
						</div>
					</div>
					<div class="col-md-3 col-sm-12" style="padding: 10px;">
						<label>Nota Test</label>
						<div class="input-group">
							<input class="form-control" type="text" id="nota" name="nota" inputmode="numeric" maxlength="4" value="<?php echo $persona->nota; ?>">
						</div>						
					</div>				
					<div class="col-md-3 col-sm-12 inputs">
						<label>Foto</label>
						<div class="input-group">
							<input class="form-control" type="file" id="imagen" name="imagen" accept="image/*" onchange="uploadPhotos('#{imageUploadUrl}')">
							<p id="imagen_p" style="display: none; font-weight: bold">Imágen cargada</p>
						</div>
						<label>Imágen actual: <?php if(isset($persona->imagen) && $persona->imagen != ''){ echo $persona->imagen; }else{ echo "Ninguna."; } ?></label>
					</div>
					<div class="col-md-12 col-sm-12" style="padding: 10px;">
						<label>Observaciones</label>
						<div class="input-group">
							<textarea class="form-control" name="texto" rows="6" placeholder="Observaciones..."><?php echo $persona->observaciones; ?></textarea>
						</div>
					</div>
				</div>				
			</div>
		</div>
  	<div class="col-md-12" style="float: left; width: 100%;">
			<div class="btn-group pull-right" style="margin: 2% 0;">
				<input type="hidden" value="<?php echo $persona->id; ?>" name="id">
				<input type="hidden" id="imagen_subida" name="imagen_subida">
				<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
					Aceptar 
				</button> 
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a href="#" onclick="window.close();" id="volver_button" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
  <script type="text/javascript" src="<?php echo base_url('files/js/script_crear_personal.js'); ?>"></script>
</body>
<script type="text/javascript">
$(function () {
    $('#fecha_carnet').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
    $('#fecha_formacion').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
});
</script>
<script type="text/javascript">
	$("#nota").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 188, 190]) !== -1 ||
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

    $("#reg").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
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
<script type="text/javascript">
	var url = window.location.href;
	var arr = url.split("/");

	window.uploadPhotos = function(url){
    	// Read in file
    	var file = event.target.files[0];
		
		// Ensure it's an image
	    if(file.type.match(/image.*/)) {
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
	        reader.readAsDataURL(file);
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
	            url: arr[0] + "//atc.apuestasdemurcia.es/tickets/subir_imagen_personal",
	            data: data,
	            cache: false,
	            contentType: false,
	            processData: false,
	            type: 'POST',
	            success: function(data){
	            	var imagen = data.replace(/^\s+/g, '');
	                $('#imagen_subida').val(imagen);
	                $('#imagen_p').css('display', 'block');
	            }
	        });
	    }
	});
</script>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
			<a href="<?php echo base_url('informes_operadora'); ?>" style="text-decoration: underline; color: #c10d3e;">
				<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Informes salones</a>
			<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			<a href="<?php echo base_url('nuevo_informe_operadora'); ?>" style="color: #000; text-decoration: none">Nuevo informe</a>
		</h3>
		<hr/>
		<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
		<?php echo validation_errors(); ?>
		<?php echo form_open_multipart('nuevo_informe_operadora_form/'); ?>
		<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
			<div class="panel-heading" style="cursor: pointer">
				Datos Generales<span style="float: right" class="glyphicon glyphicon-triangle-top"></span>
			</div>
			<div class="panel-body">
				<div class="col-md-2 col-sm-12" style="padding: 10px;">
					<label>Salón</label>
					<div class="input-group">
						<select class="form-control" id="salon" name="salon" required>			  	
					  		<?php echo $html_salones; ?>
						</select>
					</div>
				</div>
				<div id="checklist" class="col-md-12 col-sm-12" style="padding: 0; margin-top: 10px;">						
					<div class="col-md-12 col-sm-12" style="margin-top: 10px;">
						<h4>Checklist</h4>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Fachada</label>
						<div class="input-group">
							<input type="checkbox" name="check_list[]" value="car_obl1" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Carteles exteriores obligatorios</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="piz_ext" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Adquisición y uso de pizarra exterior</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="fac_vin" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Referenciar ADM en fachada</label>
			    		</div>			
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Córner</label>
						<div class="input-group">
							<input type="checkbox" name="check_list[]" value="car_obl2" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Carteles obligatorios</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="fol_lud" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Folletos ludopatía</label>
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
							<input type="checkbox" name="check_list[]" value="fol_pub" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Disponer folletos publicidad ADM</label>
			    		</div>	
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="piz_int" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Uso de pizarra apuesta del día</label>
			    		</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>TPV</label>
						<div class="input-group">
							<input type="checkbox" name="check_list[]" value="com_pro" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Comprobación de prohibidos activo</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="cor_adm" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Correo ADM revisado</label>
			    		</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Personal</label>
						<div class="input-group">
							<input type="checkbox" name="check_list[]" value="per_for" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Personal de apuestas formado</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="nec_rec" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Necesidad de reciclaje de personal</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="per_tes" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Test de personal aprobado</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="per_uni" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Personal uniformado</label>
			    		</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Otros</label>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="inc_apu" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Terminales apuestas sin incidencias</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="hil_mus" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Contratar y emitir hilo musical ADM</label>
			    		</div>
			    		<div class="input-group">
							<input type="checkbox" name="check_list[]" value="pan_car" class="checklist">
							<label style="font-weight: 100; margin-left: 5px; vertical-align: top;">Uso de panel de cartelería</label>
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
			<input type="submit" value="Aceptar" class="btn btn-danger dropdown-toggle">
		</div>
	</div>
	<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
		<a id="volver_button" href="<?php echo base_url('visitas'); ?>" class="btn btn-warning dropdown-toggle">
			Volver
		</a>
	</div>
</div>
</body>
</html>
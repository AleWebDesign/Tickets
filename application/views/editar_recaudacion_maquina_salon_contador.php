<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('recaudar_salones_contador/'.$salon.''); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i>
				</a>
				Recaudar maquina <?php echo $maquina->maquina; ?>
			</h3>
			<hr>
			<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('editar_recaudacion_maquina_salon_contador_form/', 'id="myform"'); ?>
			<div class="col-md-12 col-sm-12" style="float: left; width: 100%; padding: 0;">
				<p>Las cantidades tienen un multiplicador de <?php echo $contador->contador; ?></p>	
			</div>
			
			<div class="col-md-5 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: center; margin: 0">RECAUDACION</p>
					</div>
					<div style="width: 33%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: center; margin: 0">ENTRADA</p>
					</div>
					<div style="width: 33%; float: left;">
						<p style="font-weight: bold; text-align: center; margin: 0">SALIDA</p>
					</div>
				</div>				
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Actual</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="entrada_total_pasos" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->entrada_total_pasos != ''){ echo $recaudacion->entrada_total_pasos; } ?>">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="salida_total_pasos" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->salida_total_pasos != ''){ echo $recaudacion->salida_total_pasos; } ?>">
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Total Euros</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="entrada_total_euros" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->entrada_total_euros != ''){ echo $recaudacion->entrada_total_euros; } ?>">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="salida_total_euros" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->salida_total_euros != ''){ echo $recaudacion->salida_total_euros; } ?>">
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Parcial Pasos</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="entrada_parcial_pasos" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->entrada_parcial_pasos != ''){ echo $recaudacion->entrada_parcial_pasos; } ?>">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="salida_parcial_pasos" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->salida_parcial_pasos != ''){ echo $recaudacion->salida_parcial_pasos; } ?>">
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Parcial Euros</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="entrada_parcial_euros" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->entrada_parcial_euros != ''){ echo $recaudacion->entrada_parcial_euros; } ?>">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="salida_parcial_euros" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->salida_parcial_euros != ''){ echo $recaudacion->salida_parcial_euros; } ?>">
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Anterior</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="entrada2" style="width: 100%" value="<?php if($contador->tipo_contador == 0){ if(isset($ultima->entrada_total_euros) && $ultima->entrada_total_euros != ''){ echo $ultima->entrada_total_euros; }else{ echo "0"; } }else if($contador->tipo_contador == 1){ if(isset($ultima->entrada_total_pasos) && $ultima->entrada_total_pasos != ''){ echo $ultima->entrada_total_pasos; }else{ echo "0"; } } ?>" disabled>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="salida2" style="width: 100%" value="<?php if($contador->tipo_contador == 0){ if(isset($ultima->salida_total_euros) && $ultima->salida_total_euros != ''){ echo $ultima->salida_total_euros; }else{ echo "0"; } }else if($contador->tipo_contador == 1){ if(isset($ultima->salida_total_pasos) && $ultima->salida_total_pasos != ''){ echo $ultima->salida_total_pasos; }else{ echo "0"; } } ?>" disabled>
					</div>
				</div>
				
	  		</div>
	  	
		  	<div class="col-md-2 col-sm-12 col-xs-12" style="padding: 0">
		  		<p>&nbsp;</p>
		  	</div>
	  	
	  		<div class="col-md-5 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: center; margin: 0">TOTALES</p>
					</div>
					<div style="width: 33%; float: left;">
						<p style="font-weight: bold; text-align: center; margin: 0">&nbsp;</p>
					</div>
					<div style="width: 33%; float: left;">
						<p style="font-weight: bold; text-align: center; margin: 0">NETO</p>
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Actual</p>
					</div>
					<div style="visibility: hidden; width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="total_pasos" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->total_pasos != ''){ echo $recaudacion->total_pasos; } ?>">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="neto_total_pasos" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->neto_total_pasos != ''){ echo $recaudacion->neto_total_pasos; } ?>">
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Total Euros</p>
					</div>
					<div style="visibility: hidden; width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="total_euros" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->total_euros != ''){ echo $recaudacion->total_euros; } ?>">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="neto_total_euros" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->neto_total_euros != ''){ echo $recaudacion->neto_total_euros; } ?>">
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Parcial Pasos</p>
					</div>
					<div style="visibility: hidden; width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="parcial_pasos" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->parcial_pasos != ''){ echo $recaudacion->parcial_pasos; } ?>">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="neto_parcial_pasos" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->neto_parcial_pasos != ''){ echo $recaudacion->neto_parcial_pasos; } ?>">
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Parcial Euros</p>
					</div>
					<div style="visibility: hidden; width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="parcial_euros" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->parcial_euros != ''){ echo $recaudacion->parcial_euros; } ?>">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="neto_parcial_euros" inputmode="numeric" style="width: 100%" value="<?php if(isset($recaudacion) && $recaudacion->neto_parcial_euros != ''){ echo $recaudacion->neto_parcial_euros; } ?>">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Anterior</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="total2" style="width: 100%" value="<?php if(isset($ultima->neto_total_euros) && $ultima->neto_total_euros != ''){ echo $ultima->neto_total_euros; }else{ echo "0"; } ?>" disabled>
					</div>
				</div>
				
	  		</div>
	  	
	  		<div class="col-md-12 col-sm-12" style="float: left; width: 100%; padding: 0;">
				<div class="btn-group pull-right" style="margin: 20px 0;">
					<input type="hidden" name="recaudacion" value="<?php echo $recaudacion->id; ?>">
					<input type="hidden" name="maquina" value="<?php echo $maquina->id; ?>">
					<input type="hidden" id="contador" value="<?php echo $contador->contador; ?>">
					<input type="hidden" id="tipo_contador" value="<?php echo $contador->tipo_contador; ?>">
					<a href="<?php echo base_url('recaudar_salones_contador/'.$salon.''); ?>" class="btn btn-warning dropdown-toggle">
						Volver
					</a>
					<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle" style="margin-left: 10px">
						Aceptar 
					</button> 
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
<script type="text/javascript">
/* Script recaudar maquinas */

/* Solo numeros */
$("input").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190, 188, 110]) !== -1 ||
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

$(function(){

	entrada2 = parseFloat($('input[name=entrada2]').val());
	salida2 = parseFloat($('input[name=salida2]').val());
	total2 = parseFloat(entrada2 - salida2).toFixed(2);
	$('input[name=total2]').val(total);

});

$('input[name=entrada_total_pasos]').on('input',function(e){
	contador = parseFloat($("#contador").val());

	entrada_total_pasos = parseFloat($(this).val());

	entrada_total_euros = parseFloat(entrada_total_pasos*contador).toFixed(2);
	$('input[name=entrada_total_euros]').val(entrada_total_euros);

	tipo_contador = $("#tipo_contador").val();
	if(tipo_contador == 0){
		entrada_anterior_euros = parseFloat($("input[name=entrada2]").val());
		entrada_parcial_euros = (parseFloat(entrada_total_euros) - parseFloat(entrada_anterior_euros)).toFixed(2);
		$('input[name=entrada_parcial_euros]').val(entrada_parcial_euros);
		entrada_parcial_pasos = parseFloat(entrada_parcial_euros/contador).toFixed(2);
		$('input[name=entrada_parcial_pasos]').val(entrada_parcial_pasos);
	}else if(tipo_contador == 1){
		entrada_anterior_pasos = parseFloat($("input[name=entrada2]").val());
		entrada_parcial_pasos = entrada_total_pasos - entrada_anterior_pasos;
		$('input[name=entrada_parcial_pasos]').val(entrada_parcial_pasos);
		entrada_parcial_euros = parseFloat(entrada_parcial_pasos*contador).toFixed(2);
		$('input[name=entrada_parcial_euros]').val(entrada_parcial_euros);
	}

	salida_total_pasos = parseFloat($('input[name=salida_total_pasos]').val());
	total_pasos = parseFloat(entrada_total_pasos - salida_total_pasos).toFixed(2);
	$('input[name=total_pasos]').val(total_pasos);

	salida_total_euros = parseFloat($('input[name=salida_total_euros]').val());
	total_euros = parseFloat(entrada_total_euros - salida_total_euros).toFixed(2);
	$('input[name=total_euros]').val(total_euros);

	salida_parcial_euros = parseFloat($('input[name=salida_parcial_euros]').val());
	parcial_euros = parseFloat(entrada_parcial_euros - salida_parcial_euros).toFixed(2);
	$('input[name=parcial_euros]').val(parcial_euros);

	salida_parcial_pasos = parseFloat($('input[name=salida_parcial_pasos]').val());
	parcial_pasos = parseFloat(entrada_parcial_pasos - salida_parcial_pasos).toFixed(2);
	$('input[name=parcial_pasos]').val(parcial_pasos);

	total2 = parseFloat($("input[name=total2]").val());
	if(total2 < 0){
		neto_total_euros = parseFloat(total_euros + total2).toFixed(2);
		$('input[name=neto_total_euros]').val(neto_total_euros);
		neto_parcial_euros = parseFloat(parcial_euros + total2).toFixed(2);
		$('input[name=neto_parcial_euros]').val(neto_parcial_euros);
		neto_total_pasos = parseFloat(neto_total_euros*contador).toFixed(2);
		$('input[name=neto_total_pasos]').val(neto_total_pasos);
		neto_parcial_pasos = parseFloat(neto_parcial_euros*contador).toFixed(2);
		$('input[name=neto_parcial_pasos]').val(neto_parcial_pasos);
	}else{
		$('input[name=neto_total_pasos]').val(total_pasos);
		$('input[name=neto_total_euros]').val(total_euros);
		$('input[name=neto_parcial_euros]').val(parcial_euros);
		$('input[name=neto_parcial_pasos]').val(parcial_pasos);
	}
});

$('input[name=entrada_total_euros]').on('input',function(e){
	contador = parseFloat($("#contador").val());

	entrada_total_euros = parseFloat($(this).val());

	entrada_total_pasos = parseFloat(entrada_total_euros/contador).toFixed(2);
	$('input[name=entrada_total_pasos]').val(entrada_total_pasos);

	tipo_contador = $("#tipo_contador").val();
	if(tipo_contador == 0){
		entrada_anterior_euros = parseFloat($("input[name=entrada2]").val());
		entrada_parcial_euros = (parseFloat(entrada_total_euros) - parseFloat(entrada_anterior_euros)).toFixed(2);
		$('input[name=entrada_parcial_euros]').val(entrada_parcial_euros);
		entrada_parcial_pasos = parseFloat(entrada_parcial_euros/contador).toFixed(2);
		$('input[name=entrada_parcial_pasos]').val(entrada_parcial_pasos);
	}else if(tipo_contador == 1){
		entrada_anterior_pasos = parseFloat($("input[name=entrada2]").val());
		entrada_parcial_pasos = entrada_total_pasos - entrada_anterior_pasos;
		$('input[name=entrada_parcial_pasos]').val(entrada_parcial_pasos);
		entrada_parcial_euros = parseFloat(entrada_parcial_pasos*contador).toFixed(2);
		$('input[name=entrada_parcial_euros]').val(entrada_parcial_euros);
	}

	salida_total_pasos = parseFloat($('input[name=salida_total_pasos]').val());
	total_pasos = parseFloat(entrada_total_pasos - salida_total_pasos).toFixed(2);
	$('input[name=total_pasos]').val(total_pasos);

	salida_total_euros = parseFloat($('input[name=salida_total_euros]').val());
	total_euros = parseFloat(entrada_total_euros - salida_total_euros).toFixed(2);
	$('input[name=total_euros]').val(total_euros);

	salida_parcial_euros = parseFloat($('input[name=salida_parcial_euros]').val());
	parcial_euros = parseFloat(entrada_parcial_euros - salida_parcial_euros).toFixed(2);
	$('input[name=parcial_euros]').val(parcial_euros);

	salida_parcial_pasos = parseFloat($('input[name=salida_parcial_pasos]').val());
	parcial_pasos = parseFloat(entrada_parcial_pasos - salida_parcial_pasos).toFixed(2);
	$('input[name=parcial_pasos]').val(parcial_pasos);

	total2 = parseFloat($("input[name=total2]").val());
	if(total2 < 0){
		neto_total_euros = parseFloat(total_euros + total2).toFixed(2);
		$('input[name=neto_total_euros]').val(neto_total_euros);
		neto_parcial_euros = parseFloat(parcial_euros + total2).toFixed(2);
		$('input[name=neto_parcial_euros]').val(neto_parcial_euros);
		neto_total_pasos = parseFloat(neto_total_euros*contador).toFixed(2);
		$('input[name=neto_total_pasos]').val(neto_total_pasos);
		neto_parcial_pasos = parseFloat(neto_parcial_euros*contador).toFixed(2);
		$('input[name=neto_parcial_pasos]').val(neto_parcial_pasos);
	}else{
		$('input[name=neto_total_pasos]').val(total_pasos);
		$('input[name=neto_total_euros]').val(total_euros);
		$('input[name=neto_parcial_euros]').val(parcial_euros);
		$('input[name=neto_parcial_pasos]').val(parcial_pasos);
	}
});

$('input[name=entrada_parcial_pasos]').on('input',function(e){
	contador = parseFloat($("#contador").val());

	entrada_parcial_pasos = parseFloat($(this).val());

	entrada_parcial_euros = parseFloat(entrada_parcial_pasos*contador).toFixed(2);
	$('input[name=entrada_parcial_euros]').val(entrada_parcial_euros);

	tipo_contador = $("#tipo_contador").val();
	if(tipo_contador == 0){
		entrada_anterior_euros = parseFloat($("input[name=entrada2]").val());
		entrada_total_euros = (parseFloat(entrada_parcial_euros) + parseFloat(entrada_anterior_euros)).toFixed(2);
		$('input[name=entrada_total_euros]').val(entrada_total_euros);
		entrada_total_pasos = parseFloat(entrada_total_euros/contador).toFixed(2);
		$('input[name=entrada_total_pasos]').val(entrada_total_pasos);
	}else if(tipo_contador == 1){
		entrada_anterior_pasos = parseFloat($("input[name=entrada2]").val());
		entrada_total_pasos = entrada_parcial_pasos + entrada_anterior_pasos;
		$('input[name=entrada_total_pasos]').val(entrada_total_pasos);
		entrada_total_euros = parseFloat(entrada_total_pasos*contador).toFixed(2);
		$('input[name=entrada_total_euros]').val(entrada_total_euros);
	}

	salida_total_pasos = parseFloat($('input[name=salida_total_pasos]').val());
	total_pasos = parseFloat(entrada_total_pasos - salida_total_pasos).toFixed(2);
	$('input[name=total_pasos]').val(total_pasos);

	salida_total_euros = parseFloat($('input[name=salida_total_euros]').val());
	total_euros = parseFloat(entrada_total_euros - salida_total_euros).toFixed(2);
	$('input[name=total_euros]').val(total_euros);

	salida_parcial_euros = parseFloat($('input[name=salida_parcial_euros]').val());
	parcial_euros = parseFloat(entrada_parcial_euros - salida_parcial_euros).toFixed(2);
	$('input[name=parcial_euros]').val(parcial_euros);

	salida_parcial_pasos = parseFloat($('input[name=salida_parcial_pasos]').val());
	parcial_pasos = parseFloat(entrada_parcial_pasos - salida_parcial_pasos).toFixed(2);
	$('input[name=parcial_pasos]').val(parcial_pasos);

	total2 = parseFloat($("input[name=total2]").val());
	if(total2 < 0){
		neto_total_euros = parseFloat(total_euros + total2).toFixed(2);
		$('input[name=neto_total_euros]').val(neto_total_euros);
		neto_parcial_euros = parseFloat(parcial_euros + total2).toFixed(2);
		$('input[name=neto_parcial_euros]').val(neto_parcial_euros);
		neto_total_pasos = parseFloat(neto_total_euros*contador).toFixed(2);
		$('input[name=neto_total_pasos]').val(neto_total_pasos);
		neto_parcial_pasos = parseFloat(neto_parcial_euros*contador).toFixed(2);
		$('input[name=neto_parcial_pasos]').val(neto_parcial_pasos);
	}else{
		$('input[name=neto_total_pasos]').val(total_pasos);
		$('input[name=neto_total_euros]').val(total_euros);
		$('input[name=neto_parcial_euros]').val(parcial_euros);
		$('input[name=neto_parcial_pasos]').val(parcial_pasos);
	}
});

$('input[name=entrada_parcial_euros]').on('input',function(e){
	contador = parseFloat($("#contador").val());

	entrada_parcial_euros = parseFloat($(this).val());

	entrada_parcial_pasos = parseFloat(entrada_parcial_euros/contador).toFixed(2);
	$('input[name=entrada_parcial_pasos]').val(entrada_parcial_pasos);

	tipo_contador = $("#tipo_contador").val();
	if(tipo_contador == 0){
		entrada_anterior_euros = parseFloat($("input[name=entrada2]").val());
		entrada_total_euros = (parseFloat(entrada_parcial_euros) + parseFloat(entrada_anterior_euros)).toFixed(2);
		$('input[name=entrada_total_euros]').val(entrada_total_euros);
		entrada_total_pasos = parseFloat(entrada_total_euros/contador).toFixed(2);
		$('input[name=entrada_total_pasos]').val(entrada_total_pasos);
	}else if(tipo_contador == 1){
		entrada_anterior_pasos = parseFloat($("input[name=entrada2]").val());
		entrada_total_pasos = parseFloat(entrada_parcial_pasos) + parseFloat(entrada_anterior_pasos);
		$('input[name=entrada_total_pasos]').val(entrada_total_pasos);
		entrada_total_euros = parseFloat(entrada_total_pasos*contador).toFixed(2);
		$('input[name=entrada_total_euros]').val(entrada_total_euros);
	}

	salida_total_pasos = parseFloat($('input[name=salida_total_pasos]').val());
	total_pasos = parseFloat(entrada_total_pasos - salida_total_pasos).toFixed(2);
	$('input[name=total_pasos]').val(total_pasos);

	salida_total_euros = parseFloat($('input[name=salida_total_euros]').val());
	total_euros = parseFloat(entrada_total_euros - salida_total_euros).toFixed(2);
	$('input[name=total_euros]').val(total_euros);

	salida_parcial_euros = parseFloat($('input[name=salida_parcial_euros]').val());
	parcial_euros = parseFloat(entrada_parcial_euros - salida_parcial_euros).toFixed(2);
	$('input[name=parcial_euros]').val(parcial_euros);

	salida_parcial_pasos = parseFloat($('input[name=salida_parcial_pasos]').val());
	parcial_pasos = parseFloat(entrada_parcial_pasos - salida_parcial_pasos).toFixed(2);
	$('input[name=parcial_pasos]').val(parcial_pasos);

	total2 = parseFloat($("input[name=total2]").val());
	if(total2 < 0){
		neto_total_euros = parseFloat(total_euros + total2).toFixed(2);
		$('input[name=neto_total_euros]').val(neto_total_euros);
		neto_parcial_euros = parseFloat(parcial_euros + total2).toFixed(2);
		$('input[name=neto_parcial_euros]').val(neto_parcial_euros);
		neto_total_pasos = parseFloat(neto_total_euros*contador).toFixed(2);
		$('input[name=neto_total_pasos]').val(neto_total_pasos);
		neto_parcial_pasos = parseFloat(neto_parcial_euros*contador).toFixed(2);
		$('input[name=neto_parcial_pasos]').val(neto_parcial_pasos);
	}else{
		$('input[name=neto_total_pasos]').val(total_pasos);
		$('input[name=neto_total_euros]').val(total_euros);
		$('input[name=neto_parcial_euros]').val(parcial_euros);
		$('input[name=neto_parcial_pasos]').val(parcial_pasos);
	}
});

$('input[name=salida_total_pasos]').on('input',function(e){
	contador = parseFloat($("#contador").val());

	salida_total_pasos = parseFloat($(this).val());

	salida_total_euros = parseFloat(salida_total_pasos*contador).toFixed(2);
	$('input[name=salida_total_euros]').val(salida_total_euros);

	tipo_contador = $("#tipo_contador").val();
	if(tipo_contador == 0){
		salida_anterior_euros = parseFloat($("input[name=salida2]").val());
		salida_parcial_euros = (parseFloat(salida_total_euros) - parseFloat(salida_anterior_euros)).toFixed(2);
		$('input[name=salida_parcial_euros]').val(salida_parcial_euros);
		salida_parcial_pasos = parseFloat(salida_parcial_euros/contador).toFixed(2);
		$('input[name=salida_parcial_pasos]').val(salida_parcial_pasos);
	}else if(tipo_contador == 1){
		salida_anterior_pasos = parseFloat($("input[name=salida2]").val());
		salida_parcial_pasos = salida_total_pasos - salida_anterior_pasos;
		$('input[name=salida_parcial_pasos]').val(salida_parcial_pasos);
		salida_parcial_euros = parseFloat(salida_parcial_pasos*contador).toFixed(2);
		$('input[name=salida_parcial_euros]').val(salida_parcial_euros);
	}

	entrada_total_pasos = parseFloat($('input[name=entrada_total_pasos]').val());
	total_pasos = parseFloat(entrada_total_pasos - salida_total_pasos).toFixed(2);
	$('input[name=total_pasos]').val(total_pasos);

	entrada_total_euros = parseFloat($('input[name=entrada_total_euros]').val());
	total_euros = parseFloat(entrada_total_euros - salida_total_euros).toFixed(2);
	$('input[name=total_euros]').val(total_euros);

	entrada_parcial_euros = parseFloat($('input[name=entrada_parcial_euros]').val());
	parcial_euros = parseFloat(entrada_parcial_euros - salida_parcial_euros).toFixed(2);
	$('input[name=parcial_euros]').val(parcial_euros);

	entrada_parcial_pasos = parseFloat($('input[name=entrada_parcial_pasos]').val());
	parcial_pasos = parseFloat(entrada_parcial_pasos - salida_parcial_pasos).toFixed(2);
	$('input[name=parcial_pasos]').val(parcial_pasos);

	total2 = parseFloat($("input[name=total2]").val());
	if(total2 < 0){
		neto_total_euros = parseFloat(total_euros + total2).toFixed(2);
		$('input[name=neto_total_euros]').val(neto_total_euros);
		neto_parcial_euros = parseFloat(parcial_euros + total2).toFixed(2);
		$('input[name=neto_parcial_euros]').val(neto_parcial_euros);
		neto_total_pasos = parseFloat(neto_total_euros*contador).toFixed(2);
		$('input[name=neto_total_pasos]').val(neto_total_pasos);
		neto_parcial_pasos = parseFloat(neto_parcial_euros*contador).toFixed(2);
		$('input[name=neto_parcial_pasos]').val(neto_parcial_pasos);
	}else{
		$('input[name=neto_total_pasos]').val(total_pasos);
		$('input[name=neto_total_euros]').val(total_euros);
		$('input[name=neto_parcial_euros]').val(parcial_euros);
		$('input[name=neto_parcial_pasos]').val(parcial_pasos);
	}
});

$('input[name=salida_total_euros]').on('input',function(e){
	contador = parseFloat($("#contador").val());

	salida_total_euros = parseFloat($(this).val());

	salida_total_pasos = parseFloat(salida_total_euros/contador).toFixed(2);
	$('input[name=salida_total_pasos]').val(salida_total_pasos);

	tipo_contador = $("#tipo_contador").val();
	if(tipo_contador == 0){
		salida_anterior_euros = parseFloat($("input[name=salida2]").val());
		salida_parcial_euros = (parseFloat(salida_total_euros) - parseFloat(salida_anterior_euros)).toFixed(2);
		$('input[name=salida_parcial_euros]').val(salida_parcial_euros);
		salida_parcial_pasos = parseFloat(salida_parcial_euros/contador).toFixed(2);
		$('input[name=salida_parcial_pasos]').val(salida_parcial_pasos);
	}else if(tipo_contador == 1){
		salida_anterior_pasos = parseFloat($("input[name=salida2]").val());
		salida_parcial_pasos = salida_total_pasos - salida_anterior_pasos;
		$('input[name=salida_parcial_pasos]').val(salida_parcial_pasos);
		salida_parcial_euros = parseFloat(salida_parcial_pasos*contador).toFixed(2);
		$('input[name=salida_parcial_euros]').val(salida_parcial_euros);
	}

	entrada_total_pasos = parseFloat($('input[name=entrada_total_pasos]').val());
	total_pasos = parseFloat(entrada_total_pasos - salida_total_pasos).toFixed(2);
	$('input[name=total_pasos]').val(total_pasos);

	entrada_total_euros = parseFloat($('input[name=entrada_total_euros]').val());
	total_euros = parseFloat(entrada_total_euros - salida_total_euros).toFixed(2);
	$('input[name=total_euros]').val(total_euros);

	entrada_parcial_euros = parseFloat($('input[name=entrada_parcial_euros]').val());
	parcial_euros = parseFloat(entrada_parcial_euros - salida_parcial_euros).toFixed(2);
	$('input[name=parcial_euros]').val(parcial_euros);

	entrada_parcial_pasos = parseFloat($('input[name=entrada_parcial_pasos]').val());
	parcial_pasos = parseFloat(entrada_parcial_pasos - salida_parcial_pasos).toFixed(2);
	$('input[name=parcial_pasos]').val(parcial_pasos);

	total2 = parseFloat($("input[name=total2]").val());
	if(total2 < 0){
		neto_total_euros = parseFloat(total_euros + total2).toFixed(2);
		$('input[name=neto_total_euros]').val(neto_total_euros);
		neto_parcial_euros = parseFloat(parcial_euros + total2).toFixed(2);
		$('input[name=neto_parcial_euros]').val(neto_parcial_euros);
		neto_total_pasos = parseFloat(neto_total_euros*contador).toFixed(2);
		$('input[name=neto_total_pasos]').val(neto_total_pasos);
		neto_parcial_pasos = parseFloat(neto_parcial_euros*contador).toFixed(2);
		$('input[name=neto_parcial_pasos]').val(neto_parcial_pasos);
	}else{
		$('input[name=neto_total_pasos]').val(total_pasos);
		$('input[name=neto_total_euros]').val(total_euros);
		$('input[name=neto_parcial_euros]').val(parcial_euros);
		$('input[name=neto_parcial_pasos]').val(parcial_pasos);
	}
});

$('input[name=salida_parcial_pasos]').on('input',function(e){
	contador = parseFloat($("#contador").val());

	salida_parcial_pasos = parseFloat($(this).val());

	salida_parcial_euros = parseFloat(salida_parcial_pasos*contador).toFixed(2);
	$('input[name=salida_parcial_euros]').val(salida_parcial_euros);

	tipo_contador = $("#tipo_contador").val();
	if(tipo_contador == 0){
		salida_anterior_euros = parseFloat($("input[name=salida2]").val());
		salida_total_euros = (parseFloat(salida_parcial_euros) + parseFloat(salida_anterior_euros)).toFixed(2);
		$('input[name=salida_total_euros]').val(salida_total_euros);
		salida_total_pasos = parseFloat(salida_total_euros/contador).toFixed(2);
		$('input[name=salida_total_pasos]').val(salida_total_pasos);
	}else if(tipo_contador == 1){
		salida_anterior_pasos = parseFloat($("input[name=salida2]").val());
		salida_total_pasos = salida_parcial_pasos + salida_anterior_pasos;
		$('input[name=salida_total_pasos]').val(salida_total_pasos);
		salida_total_euros = parseFloat(salida_total_pasos*contador).toFixed(2);
		$('input[name=salida_total_euros]').val(salida_total_euros);
	}

	entrada_total_pasos = parseFloat($('input[name=entrada_total_pasos]').val());
	total_pasos = parseFloat(entrada_total_pasos - salida_total_pasos).toFixed(2);
	$('input[name=total_pasos]').val(total_pasos);

	entrada_total_euros = parseFloat($('input[name=entrada_total_euros]').val());
	total_euros = parseFloat(entrada_total_euros - salida_total_euros).toFixed(2);
	$('input[name=total_euros]').val(total_euros);

	entrada_parcial_euros = parseFloat($('input[name=entrada_parcial_euros]').val());
	parcial_euros = parseFloat(entrada_parcial_euros - salida_parcial_euros).toFixed(2);
	$('input[name=parcial_euros]').val(parcial_euros);

	entrada_parcial_pasos = parseFloat($('input[name=entrada_parcial_pasos]').val());
	parcial_pasos = parseFloat(entrada_parcial_pasos - salida_parcial_pasos).toFixed(2);
	$('input[name=parcial_pasos]').val(parcial_pasos);

	total2 = parseFloat($("input[name=total2]").val());
	if(total2 < 0){
		neto_total_euros = parseFloat(total_euros + total2).toFixed(2);
		$('input[name=neto_total_euros]').val(neto_total_euros);
		neto_parcial_euros = parseFloat(parcial_euros + total2).toFixed(2);
		$('input[name=neto_parcial_euros]').val(neto_parcial_euros);
		neto_total_pasos = parseFloat(neto_total_euros*contador).toFixed(2);
		$('input[name=neto_total_pasos]').val(neto_total_pasos);
		neto_parcial_pasos = parseFloat(neto_parcial_euros*contador).toFixed(2);
		$('input[name=neto_parcial_pasos]').val(neto_parcial_pasos);
	}else{
		$('input[name=neto_total_pasos]').val(total_pasos);
		$('input[name=neto_total_euros]').val(total_euros);
		$('input[name=neto_parcial_euros]').val(parcial_euros);
		$('input[name=neto_parcial_pasos]').val(parcial_pasos);
	}
});

$('input[name=salida_parcial_euros]').on('input',function(e){
	contador = parseFloat($("#contador").val());

	salida_parcial_euros = parseFloat($(this).val());

	salida_parcial_pasos = parseFloat(salida_parcial_euros/contador).toFixed(2);
	$('input[name=salida_parcial_pasos]').val(salida_parcial_pasos);

	tipo_contador = $("#tipo_contador").val();
	if(tipo_contador == 0){
		salida_anterior_euros = parseFloat($("input[name=salida2]").val());
		salida_total_euros = (parseFloat(salida_parcial_euros) + parseFloat(salida_anterior_euros)).toFixed(2);
		$('input[name=salida_total_euros]').val(salida_total_euros);
		salida_total_pasos = parseFloat(salida_total_euros/contador).toFixed(2);
		$('input[name=salida_total_pasos]').val(salida_total_pasos);
	}else if(tipo_contador == 1){
		salida_anterior_pasos = parseFloat($("input[name=salida2]").val());
		salida_total_pasos = parseFloat(salida_parcial_pasos) + parseFloat(salida_anterior_pasos);
		$('input[name=salida_total_pasos]').val(salida_total_pasos);
		salida_total_euros = parseFloat(salida_total_pasos*contador).toFixed(2);
		$('input[name=salida_total_euros]').val(salida_total_euros);
	}

	entrada_total_pasos = parseFloat($('input[name=entrada_total_pasos]').val());
	total_pasos = parseFloat(entrada_total_pasos - salida_total_pasos).toFixed(2);
	$('input[name=total_pasos]').val(total_pasos);

	entrada_total_euros = parseFloat($('input[name=entrada_total_euros]').val());
	total_euros = parseFloat(entrada_total_euros - salida_total_euros).toFixed(2);
	$('input[name=total_euros]').val(total_euros);

	entrada_parcial_euros = parseFloat($('input[name=entrada_parcial_euros]').val());
	parcial_euros = parseFloat(entrada_parcial_euros - salida_parcial_euros).toFixed(2);
	$('input[name=parcial_euros]').val(parcial_euros);

	entrada_parcial_pasos = parseFloat($('input[name=entrada_parcial_pasos]').val());
	parcial_pasos = parseFloat(entrada_parcial_pasos - salida_parcial_pasos).toFixed(2);
	$('input[name=parcial_pasos]').val(parcial_pasos);

	total2 = parseFloat($("input[name=total2]").val());
	if(total2 < 0){
		neto_total_euros = parseFloat(total_euros + total2).toFixed(2);
		$('input[name=neto_total_euros]').val(neto_total_euros);
		neto_parcial_euros = parseFloat(parcial_euros + total2).toFixed(2);
		$('input[name=neto_parcial_euros]').val(neto_parcial_euros);
		neto_total_pasos = parseFloat(neto_total_euros*contador).toFixed(2);
		$('input[name=neto_total_pasos]').val(neto_total_pasos);
		neto_parcial_pasos = parseFloat(neto_parcial_euros*contador).toFixed(2);
		$('input[name=neto_parcial_pasos]').val(neto_parcial_pasos);
	}else{
		$('input[name=neto_total_pasos]').val(total_pasos);
		$('input[name=neto_total_euros]').val(total_euros);
		$('input[name=neto_parcial_euros]').val(parcial_euros);
		$('input[name=neto_parcial_pasos]').val(parcial_pasos);
	}
});
</script>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); setlocale(LC_ALL, 'es_ES'); ?>

	<div class="container-fluid">
		<div class="panel panel-default col-md-6 col-sm-12 paneles_form" style="float: none; margin: 80px auto 0; width: 80%">
			<div class="panel-heading" style="cursor: pointer">
				Jornada
			</div>
			<div class="panel-body" style="text-align: center;">
				<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
				<?php echo validation_errors(); ?>
				<?php echo form_open_multipart('control_horario_form/', 'id="myform"'); ?>
				<div class="clock" style="margin: 20px auto; width: 80%; background: #f6f6f6; padding: 10px 0 1px; border-radius: 5px; font-size: 16px">
					<div id="Date"></div>
			    	<ul id="clock_list" style="list-style-type: none; padding: 0; margin: 5px 0">
			          	<li id="hours" style="display: inline"></li>
			          	<li id="point" style="display: inline">:</li>
			          	<li id="min" style="display: inline"></li>
			          	<li id="point" style="display: inline">:</li>
			        	<li id="sec" style="display: inline"></li>
			      	</ul>
				</div>
				<?php if(isset($ultimo_registro)){ ?>
				<?php if($ultimo_registro->tipo == 0){ ?>
				<p style="margin: 20px 0; font-weight: bold; font-size: 14px">Pulse el botón para comenzar el registro</p>
				<button id="submit" type="submit" style="width: 80%; padding: 2px 4px; margin: 0 4px;" name="comenzar" type="button" class="btn btn-success" alt="Comenzar jornada" title="Comenzar jornada">
					<i style="font-size: 30px" class="fa fa-check"></i>
					<span style="display: block; font-weight: bold">Iniciar jornada</span>
				</button>
				<?php }else if($ultimo_registro->tipo == 1){ ?>
				<p style="margin: 20px 0; font-weight: bold; font-size: 14px">Registrando jornada, pulse el botón para salir.</p>
				<button id="submit" type="submit" style="width: 80%; padding: 2px 4px; margin: 0 4px;" name="finalizar" type="button" class="btn btn-danger" alt="Finalizar jornada" title="Finalizar jornada">
					<i style="font-size: 30px" class="fa fa-close"></i>
					<span style="display: block; font-weight: bold">Finalizar Jornada</span>
				</button>
				<?php } ?>
				<?php }else{ ?>
				<p style="margin: 20px 0; font-weight: bold; font-size: 14px">Pulse el botón para comenzar el registro</p>
				<button id="submit" type="submit" style="width: 80%; padding: 2px 4px; margin: 0 4px;" name="comenzar" type="button" class="btn btn-success" alt="Comenzar jornada" title="Comenzar jornada">
					<i style="font-size: 30px" class="fa fa-check"></i>
					<span style="display: block; font-weight: bold">Iniciar Jornada</span>
				</button>
				<?php } ?>
				<div style="margin: 20px auto; width: 80%; background: #f6f6f6; padding: 10px 0 1px; border-radius: 5px; font-size: 16px">
					<div>Tiempo trabajado</div>
			    	<ul id="clock_list" style="list-style-type: none; padding: 0; margin: 10px 0">
			          	<li id="hours" style="display: inline"><?php echo $tiempo_trabajado; ?></li>
			        </ul>
				</div>
				<p style="margin: 30px 0 10px; font-weight: bold">Tu jornada de hoy</p>
				<?php echo $jornada_hoy; ?>
			</div>
		</div>

		<div class="panel panel-default col-md-6 col-sm-12 paneles_form" style="float: none; margin: 10px auto 0; width: 80%">
			<div class="panel-heading" style="cursor: pointer">
				Tus últimos registros
			</div>
			<div class="panel-body" style="text-align: center;">
				<?php echo $historial_jornadas; ?>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" charset="utf-8">
$("#myform").submit(function() {
    $(this).submit(function() {
        return false;
    });
    return true;
});

$(document).ready(function() {
// Create two variable with the names of the months and days in an array
var monthNames = [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]; 
var dayNames= ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"]

// Create a newDate() object
var newDate = new Date();
// Extract the current date from Date object
newDate.setDate(newDate.getDate());
// Output the day, date, month and year   
$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

setInterval( function() {
	// Create a newDate() object and extract the seconds of the current time on the visitor's
	var seconds = new Date().getSeconds();
	// Add a leading zero to seconds value
	$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
	},1000);
	
setInterval( function() {
	// Create a newDate() object and extract the minutes of the current time on the visitor's
	var minutes = new Date().getMinutes();
	// Add a leading zero to the minutes value
	$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
    },1000);
	
setInterval( function() {
	// Create a newDate() object and extract the hours of the current time on the visitor's
	var hours = new Date().getHours();
	// Add a leading zero to the hours value
	$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
    }, 1000);	
});
</script>
</html>
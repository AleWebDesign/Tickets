<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('gestion'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> 
				</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> Registro movimientos
			</h3>
			<hr/>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Nuevo
				</div>
				<div class="panel-body" style="padding-top: 15px">
					<div class="col-md-2 col-sm-12">
						<label>Local</label>
						<div class="input-group">
							<select class="js-example-basic-single" id="local" name="local">
								<option value="">Seleccionar local...</option>
								<?php echo $html_salones; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Máquina</label>
						<div class="input-group">
							<select class="form-control" id="maquina" name="maquina" disabled>								
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Movimiento</label>
						<div class="input-group">
							<select class="form-control" id="movimiento" name="movimiento">
								<option value="">Seleccionar movimiento...</option>
								<?php echo $html_movimientos; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Importe</label>
						<div class="input-group">
							<input class="form-control" type="text" id="importe" inputmode="numeric" name="importe"/>
						</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Saldo final</label>
						<div class="input-group">
							<input class="form-control" type="text" id="saldo" inputmode="numeric" name="saldo"/>
						</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<label>Firma</label>
						<canvas id="canvas1" style="width: 100%; border: 1px solid #ccc;"></canvas>
						<input type="hidden" id="color" value="#000000">
					</div>		
					<div class="col-md-2 col-sm-12 inputs">
						<div class="btn-group" style="margin: 9% 0;">
							<button id="firmar" type="button" class="btn btn-danger dropdown-toggle">
								Aceptar 
							</button> 
						</div>
					</div>
					<div class="col-md-12 col-sm-12 inputs">
						<p style="font-weight: bold; color: red; display: none" id="p_error">Ha ocurrido un error, por favor pruebe de nuevo</p>
					</div>
				</div>
			</div>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Historial
				</div>
				<div class="panel-body" style="padding-top: 15px">
					<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
					<?php echo validation_errors(); ?>
					<?php echo form_open_multipart('buscador_movimientos_locales', 'id="registros_movimientos_form"'); ?>
					<div id="div_buscador_locales">
						<div class="col-md-2 col-sm-12 inputs">
							<label>Fecha Inicio</label>
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon1">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
								<input id="datepicker1" type="text" name="fecha_inicio" class="form-control" placeholder="Desde" <?php if(isset($fecha_inicio) && $fecha_inicio != ''){ echo "value='".$fecha_inicio."'"; $fecha1 = explode('/', $fecha_inicio); $fecha_inicio = $fecha1[2]."-".$fecha1[1]."-".$fecha1[0]; }else{ $fecha_inicio = ''; } ?>>
							</div>
						</div>
						<div class="col-md-2 col-sm-12 inputs">
							<label>Fecha Fin</label>
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon1">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
								<input id="datepicker2" type="text" name="fecha_fin" class="form-control" placeholder="Desde" <?php if(isset($fecha_fin) && $fecha_fin != ''){ echo "value='".$fecha_fin."'"; $fecha2 = explode('/', $fecha_fin); $fecha_fin = $fecha2[2]."-".$fecha2[1]."-".$fecha2[0]; }else{ $fecha_fin = ''; } ?>>
							</div>
						</div>
						<div class="col-md-2 col-sm-12 inputs">
							<label>Usuario</label>
							<div class="input-group">
							  <select class="form-control" id="usuario" name="usuario" required="">
							  	<option value="0">Todos</option>
							  	<?php echo $html_usuarios; ?>
							  </select>
							</div>
						</div>
						<div class="col-md-2 col-sm-12 inputs">
							<label>Salón</label>
							<div class="input-group">
							  <select class="form-control" id="salon" name="salon" required="">
							  	<option value="0">Todos</option>
							  	<?php echo $html_salones; ?>
							  </select>
							</div>
						</div>
						<div class="col-md-2 col-sm-12 inputs">
							<label>Movimiento</label>
							<div class="input-group">
							  <select class="form-control" id="movimiento" name="movimiento" required="">
							  	<option value="0">Todos</option>
							  	<?php echo $html_movimientos; ?>
							  </select>
							</div>
						</div>
						<div class="col-md-2 col-sm-12 inputs">
							<div id="boton_aceptar_buscador_gasoil" class="btn-group">
								<button type="submit" class="btn btn-danger dropdown-toggle">
									Aceptar 
								</button> 
							</div>
						</div>
						</form>
					</div>
					<div id="version_escritorio" style="width: 99%; margin: 6% auto 0">		
						<?php if(isset($html_ultimos_registros)) echo $html_ultimos_registros; ?>
					</div>
					<div id="version_movil">
						<?php if(isset($html_ultimos_registros_movil)) echo $html_ultimos_registros_movil; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
			<a id="volver_button" href="<?php echo base_url('gestion'); ?>" class="btn btn-warning dropdown-toggle">
				Volver
			</a> 
		</div> 	
  </div>
</body>
<script type="text/javascript">
  $(function(){
      $('#datepicker1,#datepicker2').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
  });
</script>
<script type="text/javascript">
$(document).ready(function() {
	$('.js-example-basic-single').select2();
});

/* Solo numeros */
$("#importe,#saldo").keydown(function (e) {
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

var url = window.location.href;
var arr = url.split("/");

$('#local').on('change',function(){
	if($(this).val() == ''){
		$('#maquina').html("<option value=''>Seleccionar máquina...</option>");
		$('#maquina').prop('disabled', 'disabled');
	}else{
		id_salon = $(this).val();
		$('#maquina').prop('disabled', false);
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_maquinas_salon",
        data: "id=" + id_salon,
        success: function(data){
        	$('#maquina').html(data);
        }
    	});
	}
});
</script>
<script type="text/javascript">
var url = window.location.href;
var arr = url.split("/");

var guardarfirmas = guardarfirmas || {};

guardarfirmas.GuardandoPNGs = (function() {
	var mousePressed = false;
	var lastX, lastY;
	var ctx;

	function init() {
		// iniciar canvas
		var canvas1 = document.getElementById('canvas1');
		ctx1 = canvas1.getContext('2d');
		resetCanvas();

		// button events
		document.getElementById("firmar").onmouseup = sendToServer;

		canvas1.onmousedown = function(e) {
			draw1(e.layerX, e.layerY);
		  	mousePressed = true;
		};

		canvas1.onmousemove = function(e) {
			if (mousePressed) {
			draw1(e.layerX, e.layerY);
			}
		};

		canvas1.onmouseup = function(e) {
			mousePressed = false;
		};

		canvas1.onmouseleave = function(e) {
			mousePressed = false;
		};
	}

	function draw1(x, y) {
		if (mousePressed) {
			ctx1.beginPath();
			ctx1.strokeStyle = document.getElementById('color').value;
			ctx1.lineWidth = 2;
			ctx1.lineJoin = 'round';
			ctx1.moveTo(lastX, lastY);
			ctx1.lineTo(x, y);
			ctx1.closePath();
			ctx1.stroke();
		}
		lastX = x; lastY = y;
	}

	// Guardar firma
	function sendToServer(){
		$('input,select').css('border-color', '#ccc');
		local = $('#local').val();
		maquina = $('#maquina').val();
		movimiento = $('#movimiento').val();
		importe = $('#importe').val();
		saldo = $('#saldo').val();
		if(local == ''){
			$('#local').css('border-color', 'red');
		} 
		if(maquina == ''){
			$('#maquina').css('border-color', 'red');
		} 
		if(movimiento == ''){
			$('#movimiento').css('border-color', 'red');
		}
		if(importe == ''){
			$('#importe').css('border-color', 'red');
		}
		if(local != '' && maquina != '' && movimiento != '' && importe != ''){
			var firma = canvas1.toDataURL('image/png');
			$.ajax({
				type: "POST",
				url: arr[0] + "//atc.apuestasdemurcia.es/tickets/nuevo_registro_form",
				dataType: "json",
				data: {
					img: firma,
					local: local,
					maquina: maquina,
					movimiento: movimiento,
					importe: importe,
					saldo: saldo
				}
				// Crear ticket
			}).done(function(response){
				if(response){
					window.location.reload();
				}else{
					$('#p_error').css('display', 'block');
				}
			});
		}
	}

	function resetCanvas() {
    	// just repaint canvas white
    	ctx1.fillStyle = '#FFFFFF';
    	ctx1.fillRect(0, 0, canvas1.width, canvas1.height);
    }

	return {
		'init': init
	};
});

function startup() {
  var el1 = document.getElementById('canvas1');
  el1.addEventListener("touchstart", handleStart1, false);
  el1.addEventListener("touchend", handleEnd1, false);
  el1.addEventListener("touchcancel", handleCancel, false);
  el1.addEventListener("touchleave", handleEnd1, false);
  el1.addEventListener("touchmove", handleMove1, false);
}
var ongoingTouches = new Array;

function handleStart1(evt) {
    evt.preventDefault();
    var el = document.getElementById('canvas1');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);      
   
    for (var i = 0; i < touches.length; i++) {
      if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y + window.scrollY >0 && touches[i].clientY-offset.y  + window.scrollY < parseFloat(el.height)){
          evt.preventDefault();
          ongoingTouches.push(copyTouch(touches[i]));
          var color = colorForTouch(touches[i]);
          ctx.beginPath();
          ctx.fillStyle = color;
          ctx.fill();
      }
    }
}

function handleMove1(evt) {
    evt.preventDefault();
    var el = document.getElementById('canvas1');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);

    for (var i = 0; i < touches.length; i++) {
        if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y + window.scrollY
          > 0 && touches[i].clientY-offset.y + window.scrollY < parseFloat(el.height)){
            evt.preventDefault();
            var color = colorForTouch(touches[i]);
            var idx = ongoingTouchIndexById(touches[i].identifier);
    
        if (idx >= 0) {
            ctx.beginPath();
            ctx.moveTo(ongoingTouches[idx].clientX-offset.x, ongoingTouches[idx].clientY-offset.y + window.scrollY);
            ctx.lineTo(touches[i].clientX-offset.x, touches[i].clientY-offset.y + window.scrollY);
            ctx.lineWidth = 2;
            ctx.strokeStyle = color;
            ctx.stroke();
            ongoingTouches.splice(idx, 1, copyTouch(touches[i]));
        }
      }
    }
}

function handleEnd1(evt) {
    evt.preventDefault();
    var el = document.getElementById('canvas1');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);
        
    for (var i = 0; i < touches.length; i++) {
      if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y + window.scrollY >0 && touches[i].clientY-offset.y + window.scrollY < parseFloat(el.height)){
          evt.preventDefault();
          var color = colorForTouch(touches[i]);
          var idx = ongoingTouchIndexById(touches[i].identifier);
        
        if (idx >= 0) {
          ctx.lineWidth = 2;
          ctx.fillStyle = color;
          ctx.beginPath();
          ctx.moveTo(ongoingTouches[idx].clientX-offset.x, ongoingTouches[idx].clientY-offset.y + window.scrollY);
          ctx.lineTo(touches[i].clientX-offset.x, touches[i].clientY-offset.y + window.scrollY);
          ongoingTouches.splice(i, 1);
        }
      }
    }
}

function handleCancel(evt) {
  evt.preventDefault();
    var touches = evt.changedTouches;
  
    for (var i = 0; i < touches.length; i++) {
      ongoingTouches.splice(i, 1);
    }
}

function colorForTouch(touch) {
  var r = touch.identifier % 16;
    var g = Math.floor(touch.identifier / 3) % 16;
    var b = Math.floor(touch.identifier / 7) % 16;
    r = r.toString(16);
    g = g.toString(16);
    b = b.toString(16);
    var color = "#" + r + g + b;
    return color;
}

function copyTouch(touch) {
  return {identifier: touch.identifier,clientX: touch.clientX,clientY: touch.clientY};
}

function ongoingTouchIndexById(idToFind) {
  for (var i = 0; i < ongoingTouches.length; i++) {
      var id = ongoingTouches[i].identifier;
    
      if (id == idToFind) {
          return i;
      }
    }
    return -1;
}
 
function findPos (obj) {
    var curleft = 0,
        curtop = 0;

    if (obj.offsetParent) {
        do {
            curleft += obj.offsetLeft;
            curtop += obj.offsetTop;
        } while (obj = obj.offsetParent);

        return { x: curleft-document.body.scrollLeft, y: curtop-document.body.scrollTop };
    }
}

window.onload = function() {
  new guardarfirmas.GuardandoPNGs().init();
  startup();
};
</script>
</html>
var url = window.location.href;
var arr = url.split("/");

/* Script nueva_incidencia.php */
$(document).ready(function(){
	id_op =  $('#operador').val();
	id_destino = $('#id_destino').val();
	if($('#situacion').val() == 2 || $('#situacion').val() == 13){
		situacion = $('#situacion').val();
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$('#trata_desc').html('*SAT');
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_sat",
	        data: "id=" + id_op + "&d=" + id_destino + "&s=" + situacion,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}else if($(this).val() == 12){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_com",
	        data: "id=" + id_op + "&d=" + id_destino,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}
	prioridad = $('#prioridad').val();
	if(prioridad == 3){
		$('#div_fecha_programada').css('display', 'block');
	}
});

$('#empresa').on('change',function(){
	if($(this).val() == ''){
		$('#gestion_tipo').prop('disabled', 'disabled');
		$('#situacion').prop('disabled', 'disabled');
		$('#error_maquina').html("<option value=''>Máquina...</option>");
		$('#error_maquina').prop('disabled', 'disabled');
		$('#error_tipo').html("<option value=''>Tipo error...</option>");
		$('#error_tipo').prop('disabled', 'disabled');
		$('#error_detalle').html("<option value=''>Detalle error...</option>");
		$('#error_detalle').prop('disabled', 'disabled');
		$('#salon').html("<option value=''>Salón...</option>");
		$('#salon').prop('disabled', 'disabled');
		$('#operador').html("<option value=''>Operadora</option>");
		$('#operador').prop('disabled', 'disabled');
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_situaciones_ajax",
	        success: function(data){
	        	$('#situacion').html(data);
	        }
	    });
			$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_ajax",
	        data: "id=" + id_op,
	        success: function(data){
	        	$('#trata_destino').html(data);
	        }
	    });
	}else{
		var url = window.location.href.split("/");
		$('#operador').prop('disabled', false);
		$('#situacion').prop('disabled', false);
		id_empresa = $(this).val();
		if(url[4] != "editar_incidencia"){
			$.ajax ({
				type: "POST",
	        	url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_gestion_empresa",
	        	data: "id=" + id_empresa,
	        	success: function(data){
	        		$('#gestion_tipo').html(data);
	        	}
			});
		}
		$.ajax ({
        	type: "POST",
        	url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_operadoras_empresa",
        	data: "id=" + id_empresa,
        	success: function(data){
        		$('#operador').html(data);
        	},
        	complete: function (data) {
 				id_op =  $('#operador').val();
 				if(id_op == ''){
 					$('#salon').html("<option value=''>Salón...</option>");
					$('#salon').prop('disabled', 'disabled');
 				}else{
 					$('#salon').prop('disabled', false);
 					$.ajax ({
			        	type: "POST",
			        	url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_salones_operadora",
			        	data: "id=" + id_op,
			        	success: function(data){
			        		$('#salon').html(data);
			        	}, complete: function (data) {
	        				if($('#situacion').val() == 2 || $('#situacion').val() == 13){
	        					situacion = $('#situacion').val();
					    		id_op =  $('#operador').val();
								id_destino = $('#id_destino').val();
								$('#solucionada').prop('checked', false);
								$('#email').prop('checked', true);
								$('#email').prop('disabled', false);
								$('#trata_desc').html('*SAT');
								$.ajax ({
							        type: "POST",
							        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_sat",
							        data: "id=" + id_op + "&d=" + id_destino + "&s=" + situacion,
							        success: function(data){
							        	if (data.indexOf('novale') > -1){
							        		console.log(data);
							        	}else{
							        		$('#trata_destino').html(data);
							        	}
							        }
						    	});
							}else if($(this).val() == 12){
								$('#solucionada').prop('checked', false);
								$('#email').prop('checked', true);
								$('#email').prop('disabled', false);
								$.ajax ({
							        type: "POST",
							        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_com",
							        data: "id=" + id_op + "&d=" + id_destino,
							        success: function(data){
							        	if (data.indexOf('novale') > -1){
							        		console.log(data);
							        	}else{
							        		$('#trata_destino').html(data);
							        	}
							        }
							    });															
							}else if($(this).val() == 17){
								$('#operador').prop('disabled', false);
								$('#salon').prop('disabled', false);
								$('#gestion_tipo').prop('disabled', false);
								$('#error_maquina').prop('disabled', false);
								$('#error_tipo').prop('disabled', false);
								$('#error_detalle').prop('disabled', false);
								$('#destino').prop('disabled', false);
								var empresa = $('#empresa').val();
								if(empresa == 1){
									$.ajax ({
								        type: "POST",
								        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_incidencia_llamada",
								        dataType: "json",
								        success: function(data){
								        	$('#operador').html(data['operador']);
								        	$('#salon').html(data['salon']);
								        	$('#gestion_tipo').html(data['gestion']);
											$('#error_maquina').html(data['maquina']);
											$('#error_tipo').html(data['tipo']);
											$('#error_detalle').html(data['detalle']);
											$('#trata_destino').html(data['destino']);
								        }
								    });
								}
							}
			        	}
			    	});				    
 				}   
	        }
    	});
	}
});

$('#operador').on('change',function(){
	if($(this).val() == '' || $(this).val() == '0'){
		$('#salon').html("<option value=''>Salón...</option>");
		$('#salon').prop('disabled', 'disabled');
	}else{
		$('#salon').prop('disabled', false);
		id_op =  $('#operador').val();
		if(id_op == ''){
			$('#salon').html("<option value=''>Salón...</option>");
			$('#salon').prop('disabled', 'disabled');
		}else{
			$('#salon').prop('disabled', false);
			$.ajax ({
		        type: "POST",
		        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_salones_operadora",
		        data: "id=" + id_op,
		        success: function(data){
		        	$('#salon').html(data);
		        }, complete: function (data) {
		        	id_destino = $('#id_destino').val();
					if($('#situacion').val() == 2 || $('#situacion').val() == 13){
						situacion = $('#situacion').val();
						$('#solucionada').prop('checked', false);
						$('#email').prop('checked', true);
						$('#email').prop('disabled', false);
						$('#trata_desc').html('*SAT');
						$.ajax ({
					        type: "POST",
					        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_sat",
					        data: "id=" + id_op + "&d=" + id_destino + "&s=" + situacion,
					        success: function(data){
					        	if (data.indexOf('novale') > -1){
					        		console.log(data);
					        	}else{
					        		$('#trata_destino').html(data);
					        	}
					        }
					    });
					}else if($(this).val() == 12){
						$('#solucionada').prop('checked', false);
						$('#email').prop('checked', true);
						$('#email').prop('disabled', false);
						$.ajax ({
					        type: "POST",
					        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_com",
					        data: "id=" + id_op + "&d=" + id_destino,
					        success: function(data){
					        	if (data.indexOf('novale') > -1){
					        		console.log(data);
					        	}else{
					        		$('#trata_destino').html(data);
					        	}
					        }
				    	});
					}
				}
	    	});
		}
	}
});

$('#error_detalle').on('change',function(){
	if($(this).val() == 571 || $(this).val() == 582){
		salon = $('#salon').val();
		if(salon == 385){
			$('.transporte_fields').css('display', 'block');
		}else{
			$('.transporte_fields').css('display', 'none');
		}
		$('#div_cantidad_tarjetas').css('display', 'none');
	}else if($(this).val() == 424){
		$('#div_cantidad_tarjetas').css('display', 'block');
	}else{
		$('.transporte_fields').css('display', 'none');
		$('#div_cantidad_tarjetas').css('display', 'none');
	}
});

$('#salon').on('change',function(){
	if($(this).val() == ''){
		$('#error_maquina').html("<option value=''>Máquina...</option>");
		$('#error_maquina').prop('disabled', 'disabled');
		$('#error_tipo').html("<option value=''>Tipo error...</option>");
		$('#error_tipo').prop('disabled', 'disabled');
		$('#error_detalle').html("<option value=''>Detalle error...</option>");
		$('#error_detalle').prop('disabled', 'disabled');
		$('#gestion_tipo').prop('disabled', 'disabled');
		$('.transporte_fields').css('display', 'none');
		id_empresa = $('#empresa').val();
		$.ajax ({
			type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_gestion_empresa",
	        data: "id=" + id_empresa,
	        success: function(data){
	        	$('#gestion_tipo').html(data);
	        }
		});
		$('#trata_destino').prop('disabled', 'disabled');
	}else{
		if($(this).val() == 385){
			error_detalle = $('#error_detalle').val();
			if(error_detalle == 571 || error_detalle == 582){
				$('.transporte_fields').css('display', 'block');
			}else{
				$('.transporte_fields').css('display', 'none');
			}
		}else{
			$('.transporte_fields').css('display', 'none');
		}
		id_empresa = $('#empresa').val();
		var url = window.location.href.split("/");
		if(url[4] != "editar_incidencia"){
			$.ajax ({
				type: "POST",
		        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_gestion_empresa",
		        data: "id=" + id_empresa + "&s=" + $(this).val(),
		        success: function(data){
		        	$('#gestion_tipo').html(data);
		        }
			});
		}
		$('#gestion_tipo').prop('disabled', false);
		$('#trata_destino').prop('disabled', false);
		id_op =  $('#operador').val();
		id_salon = $(this).val();
		tipo_gestion = $('#gestion_tipo').val();
		if(tipo_gestion != 0){
			$('#error_maquina').prop('disabled', false);
			$('#error_tipo').prop('disabled', false);
			if(url[4] != "editar_incidencia"){
				$.ajax ({
		        type: "POST",
		        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_maquinas",
		        data: "id=" + id_salon + "&g=" + tipo_gestion + "&op=" + id_op,
		        success: function(data){
		        	$('#error_maquina').html(data);
		        }
		    	});
	    	}
	  	}
	}
});

$('#gestion_tipo').on('change', function(){
	if($(this).val() == '' || $(this).val() == 0){
		$('#error_maquina').html("<option value=''>Máquina...</option>");
		$('#error_maquina').prop('disabled', 'disabled');
		$('#error_tipo').html("<option value=''>Tipo error...</option>");
		$('#error_tipo').prop('disabled', 'disabled');
		$('#error_detalle').html("<option value=''>Detalle error...</option>");
		$('#error_detalle').prop('disabled', 'disabled');
	}else{
		id_op =  $('#operador').val();
		id_salon = $('#salon').val();
		tipo_gestion = $(this).val();
		$('#error_detalle').html("<option value=''>Detalle error...</option>");
		$('#error_detalle').prop('disabled', 'disabled');
		$('#error_maquina').prop('disabled', false);
		$('#error_tipo').prop('disabled', false);
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_maquinas",
	        data: "id=" + id_salon + "&g=" + tipo_gestion + "&op=" + id_op,
	        success: function(data){
	        	$('#error_maquina').html(data);
	        }
	    });
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_error_gestion",
	        data: "tipo=" + tipo_gestion,
	        success: function(data){
	        	$('#error_tipo').html(data);
	        }
	    });
	}
});

$('#cliente_tipo').on('change', function(){
	if($(this).val() == ''){
		$('#cliente_nombre').val('');
		$('#cliente_email').val('');
	}else if($(this).val() != 1 && $(this).val() != 5 && $(this).val() != 7 && $(this).val() != 8){
		id_cliente = $(this).val();
		id_salon = $('#salon').val();
		id_op = $('#operador').val();
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_datos_cliente",
	        data: "cliente=" + id_cliente + "&op=" + id_op + "&salon=" + id_salon,
	        success: function(data){
	        	var res = data.split("|");       	
	        	$('#cliente_nombre').val(res[1]);
	        	$('#cliente_email').val(res[2]);
	        }
	    });
	}	
});

$('#cliente_nombre').on('change', function(){
	var usuario = $(this).val();
	$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_datos_usuario",
        data: "usuario=" + usuario,
        success: function(data){
        	var res = data.split("|");       	
	        $('#cliente_telefono').val(res[1]);
	        $('#cliente_email').val(res[2]);
        }
    });
});

$('#error_maquina').on('change',function(){
	if($(this).val() == 0){
		$('#div_nueva_maquina').css('display', 'block');
	}else{
		$('#div_nueva_maquina').css('display', 'none');
	}
});

$('#error_tipo').on('change',function(){
	if($(this).val() == ''){
		$('#error_detalle').prop('disabled', 'disabled');
		$('#div_importe_ticket').css('display', 'none');
	}else{
		$('#error_detalle').prop('disabled', false);
		if($(this).val() == '62' || $(this).val() == '77' || $(this).val() == '113' || $(this).val() == '58'){
			$('#div_importe_ticket').css('display', 'block');
		}else{
			$('#div_importe_ticket').css('display', 'none');
		}
		id_error = $(this).val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_error_detalle",
        data: "id=" + id_error,
        success: function(data){
        	$('#error_detalle').html(data);
        }
    });
	}
});

$('#situacion').on('change',function(){
	id_op =  $('#operador').val();
	if($(this).val() == 2 || $(this).val() == 13){
		situacion = $('#situacion').val();
		destino = $('#id_destino').val();
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$('#trata_desc').html('*SAT');
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_sat",
	        data: "id=" + id_op + "&d=" + destino + "&s=" + situacion,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}else if($(this).val() == 3){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_eusk",
	        data: "id=" + id_op,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}else if($(this).val() == 5){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$('#error_maquina').html('<option value="">Máquina...</option><option value="0" selected>Sin asignar</option>');
		$('#trata_desc').html('');
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_ajax",
	        data: "id=" + id_op,
	        success: function(data){
	        	$('#trata_destino').html(data);
	        }
	    });
	}else if($(this).val() == 6){
		$('#solucionada').prop('checked', true);
		$('#email').prop('checked',false);
		$('#email').prop('disabled',true);
		$('#trata_desc').html('');
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_ajax",
	        data: "id=" + id_op,
	        success: function(data){
	        	$('#trata_destino').html(data);
	        }
	    });
	}else if($(this).val() == 8){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$('#trata_desc').html('');
		$('#trata_destino').html("<option value='229'>Kirol</option>");
	}else if($(this).val() == 12){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_com",
	        data: "id=" + id_op,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}else if($(this).val() == 14){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_inf",
	        data: "id=" + id_op,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}else if($(this).val() == 17){
		$('#operador').prop('disabled', false);
		$('#salon').prop('disabled', false);
		$('#gestion_tipo').prop('disabled', false);
		$('#error_maquina').prop('disabled', false);
		$('#error_tipo').prop('disabled', false);
		$('#error_detalle').prop('disabled', false);
		$('#destino').prop('disabled', false);
		var empresa = $('#empresa').val();
		if(empresa == 1){
			$.ajax ({
		        type: "POST",
		        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_incidencia_llamada",
		        dataType: "json",
		        success: function(data){
		        	$('#operador').html(data['operador']);
					$('#salon').html(data['salon']);
		        	$('#gestion_tipo').html(data['gestion']);
					$('#error_maquina').html(data['maquina']);
					$('#error_tipo').html(data['tipo']);
					$('#error_detalle').html(data['detalle']);
					$('#trata_destino').html(data['destino']);
		        }
		    });
		}
	}else if($(this).val() == 19){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_mkt",
	        data: "id=" + id_op,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}else if($(this).val() == 20){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_jur",
	        data: "id=" + id_op,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}else if($(this).val() == 21){
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
		$.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_onl",
	        data: "id=" + id_op,
	        success: function(data){
	        	if (data.indexOf('novale') > -1){
	        		console.log(data);
	        	}else{
	        		$('#trata_destino').html(data);
	        	}
	        }
	    });
	}else{
		$('#solucionada').prop('checked', false);
		$('#email').prop('checked', true);
		$('#email').prop('disabled', false);
	    $('#trata_desc').html('');
	    $.ajax ({
	        type: "POST",
	        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_departamentos_ajax",
	        data: "id=" + id_op,
	        success: function(data){
	        	$('#trata_destino').html(data);
	        }
	    });
	}
});

$('.panel-heading').on('click',function(){
	if($(this).attr('class') == 'panel-heading'){
		if($(this).next().attr('class') == 'panel-body'){
			if($(this).next().css('display') == 'block'){
				$(this).children().attr('class', 'glyphicon glyphicon-triangle-bottom');
				$(this).next().css('display','none');
			}else{
				$(this).children().attr('class', 'glyphicon glyphicon-triangle-top');
				$(this).next().css('display','block');
			}
		}
	}
});

/* Nueva máquina */

$('#link_nueva_maquina').on('click', function(e){
	e.preventDefault();
	$('#nueva_maquina').css('display', 'block');
	var scroll = $(window).scrollTop();
	$('#nueva_maquina').css('top', scroll);
	$('.container-fluid').css('opacity', '0');
});

$('#cerrar_nueva_maquina').on('click', function(e){
	e.preventDefault();
	$('#nueva_maquina').css('display', 'none');
	$('.container-fluid').css('opacity', '1');
});

$('.cerrar_nueva_maquina').on('click', function(e){
	e.preventDefault();
	$('#nueva_maquina').css('display', 'none');
	$('.container-fluid').css('opacity', '1');
});


$('#fabri_nueva_maquina').on('change',function(){
	$('.serie1').css('display', 'none');
	$('.serie2').css('display', 'none');
	$('.serie3').css('display', 'none');
	if($(this).val() == ''){
		$('#modelo_nueva_maquina').html("<option value=''>Modelo...</option>");
		$('#modelo_nueva_maquina').prop('disabled', 'disabled');
		$('#puestos').html("<option value=''>Puestos...</option>");
		$('#puestos').prop('disabled', 'disabled');
	}else{
		$('#modelo_nueva_maquina').prop('disabled', false);
		id_fabri = $(this).val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_modelos",
        data: "id=" + id_fabri,
        success: function(data){
        	$('#modelo_nueva_maquina').html(data);
        }
    });
	}
});

$('#modelo_nueva_maquina').on('change',function(){
	$('.serie1').css('display', 'none');
	$('.serie2').css('display', 'none');
	$('.serie3').css('display', 'none');
	if($(this).val() == ''){
		$('#puestos').html("<option value=''>Puestos...</option>");
		$('#puestos').prop('disabled', 'disabled');
	}else{
		$('#puestos').prop('disabled', false);
		id_modelo = $(this).val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_puestos_modelo",
        data: "id=" + id_modelo,
        success: function(data){
        	$('#puestos').html(data);
        }
    });
	}
});

$('#prioridad').on('change',function(){
	if($(this).val() == '3'){
		$('#div_fecha_programada').css('display', 'block');
	}else{
		$('#div_fecha_programada').css('display', 'none');
	}
});

$('#puestos').on('change',function(){
	$('.serie1').css('display', 'none');
	$('.serie2').css('display', 'none');
	$('.serie3').css('display', 'none');
	id_modelo = $('#modelo_nueva_maquina').val();
	puestos = $(this).val();
	$.ajax ({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_modelo",
      data: "id=" + id_modelo,
      success: function(data){
      	for (var i = 0; i < data.length; i++){
      		if(data[i] == 3){
      			if(puestos == 1){
      				$('.serie1').css('display', 'block');
      			}
      			if(puestos == 2){
        			$('.serie1').css('display', 'block');
							$('.serie2').css('display', 'block');
        		}
        		if(puestos == 3){
        			$('.serie1').css('display', 'block');
							$('.serie2').css('display', 'block');
							$('.serie3').css('display', 'block');
        		}
      		}
      	}
      }
    });
});

$('#nueva_maquina_button').on('click', function(e){
	e.preventDefault();
	if(typeof $('#salon_nueva_maquina').val() == 'undefined' || $('#salon_nueva_maquina').val() == null || $('#salon_nueva_maquina').val() == ''){
		$('#error_salon_nueva_maquina').css('display', 'block');
	}else if(typeof $('#fabri_nueva_maquina').val() == 'undefined' || $('#fabri_nueva_maquina').val() == null || $('#fabri_nueva_maquina').val() == ''){
		$('#error_fabri_nueva_maquina').css('display', 'block');
		$('#error_salon_nueva_maquina').css('display', 'none');
	}else if(typeof $('#modelo_nueva_maquina').val() == 'undefined' || $('#modelo_nueva_maquina').val() == null || $('#modelo_nueva_maquina').val() == ''){
		$('#error_modelo_nueva_maquina').css('display', 'block');
		$('#error_salon_nueva_maquina').css('display', 'none');
		$('#error_fabri_nueva_maquina').css('display', 'none');
	}else{
		$('#error_modelo_nueva_maquina').css('display', 'none');
		$('#error_salon_nueva_maquina').css('display', 'none');
		$('#error_fabri_nueva_maquina').css('display', 'none');
		$.ajax ({
	      type: "POST",
	      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/nueva_maquina_form_ajax",
	      data: { salon : $('#salon_nueva_maquina').val(), fabri : $('#fabri_nueva_maquina').val(), modelo : $('#modelo_nueva_maquina').val(), puestos : $('#puestos').val(), serie1 : $('#serie1').val(), serie2 : $('#serie2').val(), serie3 : $('#serie3').val() },
	      success: function(data){
	      	$('#nueva_maquina').css('display', 'none');
	      	$('.container-fluid').css('opacity', '1');
	      },
	      complete: function (data){
	      	id_op =  $('#operador').val();
	      	id_salon = $('#salon').val();
					tipo_gestion = $('#gestion_tipo').val();
	      	$.ajax ({
			        type: "POST",
			        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_maquinas",
			        data: "id=" + id_salon + "&g=" + tipo_gestion + "&op=" + id_op,
			        success: function(data){
			        	$('#error_maquina').html(data);
			        }
			    });
		 		}
	  	});
	}
});

var url = window.location.href;
var arr = url.split("/");

/* Script gestion_incidencias.php */
$(function(){
	if($('input:checkbox:checked').length == $('input:checkbox').length){
		$('.check_all').html('Desmarcar todos');
	}
});

$('#empresa').on('change',function(){
	if($(this).val() == '' || $(this).val() == '0'){
		$('#salon').html("<option value=''>Salón...</option>");
		$('#salon').prop('disabled', 'disabled');
		$('#operador').html("<option value=''>Operadora</option>");
		$('#operador').prop('disabled', 'disabled');
	}else{
		$('#operador').prop('disabled', false);
		$('#salon').prop('disabled', false);
		id_empresa = $(this).val();
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
	        }
	    });
		}
	}
});

$(".clickable-row").click(function(){
	window.location.href = $(this).data("href");
});

$('body').on('click', '.clickable-row2', function() {
	window.location.href = $(this).data("href");
});

$('.check_all').click(function(e){
	e.preventDefault();
	if($(this).html() == "Marcar todos"){
		$('input:checkbox').prop('checked',true);
		$(this).html('Desmarcar todos');
	}else{
		$('input:checkbox').prop('checked',false);
		$(this).html('Marcar todos');
	}
});

$('#ocultar_buscador').click(function(e){
	e.preventDefault();
	if($('#ocultar').css('display') == "none"){
		$('#ocultar_buscador').children().children().attr('class', 'glyphicon glyphicon-triangle-top');
		$('#ocultar').css('display','block');
	}else{
		$('#ocultar_buscador').children().children().attr('class', 'glyphicon glyphicon-triangle-bottom');
		$('#ocultar').css('display','none');
	}
});

$(function(){
	var scroll = parseInt(localStorage.getItem("scrollTop"));
	$(window).scrollTop(scroll);
});

$("td.td_editada").on("mouseover", function () {
	$(this).children().first().css('display', 'none');
	$(this).children().first().next().css('display', 'block');
});

$("td.td_editada").on("mouseout", function () {
	$(this).children().first().next().css('display', 'none');
	$(this).children().first().css('display', 'block');
});

$(document.body).on('mouseover', 'td.td_editada' ,function(){
	$(this).children().first().css('display', 'none');
	$(this).children().first().next().css('display', 'block');
});

$(document.body).on('mouseout', 'td.td_editada' ,function(){
	$(this).children().first().next().css('display', 'none');
	$(this).children().first().css('display', 'block');
});
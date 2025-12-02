var url = window.location.href;
var arr = url.split("/");

$(function(){
	if($('#agrupar_volver').val() == 1){
		$('.agrupar').css('color','#337ab7');
		$('#'+$('#agrupar_columna_volver').val()).css('color','#d80039');
		id_col = $('#agrupar_columna_volver').val();
		sql = $('#consulta_sql').val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/agrupar_visitas",
        data: "col=" + id_col + "&sql=" + sql,
        success: function(data){
        	$('#version_escritorio').css('display', 'none');
        	$('#div_agrupados').html(data);
        	$('#div_agrupados').css('display', 'block');
        }
    });
	}
});

$('.agrupar').on('click', function(){
		$('.agrupar').css('color','#337ab7');
		$('#agrupar_volver').val(1);
		$('#agrupar_columna_volver').val($(this).attr('id'));
		$(this).css('color','#d80039');
		id_col = $(this).attr('id');
		sql = $('#consulta_sql').val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/agrupar_visitas",
        data: "col=" + id_col + "&sql=" + sql,
        success: function(data){
        	$('#version_escritorio').css('display', 'none');
        	$('#div_agrupados').html(data);
        	$('#div_agrupados').css('display', 'block');
        }
    });
});

$('body').on('click', '.agrupado_div', function() {
		if($(this).next().css('display') == 'none'){
			$(this).next().css('display','block');
		}else{
			$(this).next().css('display','none');
		}
});

$('body').on('click', '.clickable-row2', function() {
		window.location = $(this).data("href");
});

$('body').on('click', '#volver_agrupado', function() {
		$('.agrupar').css('color','#337ab7');
		$('#agrupar_volver').val(0);
		$('#agrupar_columna_volver').val(0);
		$('#version_escritorio').css('display', 'block');
		$('#div_agrupados').css('display', 'none');
    $('#div_agrupados').html(" ");
});

$('body').on('click', '.clickable-row', function() {
		window.location = $(this).data("href");
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

$('#operador').on('change',function(){
	id_op =  $(this).val();
	$.ajax ({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_salones_operadora_visitas",
      data: "id=" + id_op,
      success: function(data){
      	$('#salon').html(data);
      }
  });
});
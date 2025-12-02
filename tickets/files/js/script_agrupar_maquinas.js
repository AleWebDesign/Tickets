var url = window.location.href;
var arr = url.split("/");

/* Script agrupar maquinas */
$('.agrupar').on('click', function(){
		$('.agrupar').css('color','#337ab7');
		$(this).css('color','#d80039');
		id_col = $(this).attr('id');
		sql = $('#consulta_sql').val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/agrupar_maquinas",
        data: "col=" + id_col + "&sql=" + sql,
        success: function(data){
        	$('#tabla_incidencias').css('display', 'none');
        	$('#version_escritorio nav').css('display', 'none');
        	$('#div_agrupados').html(data);
        	$('#div_agrupados').css('display', 'block');
        	var listado = $('#div_agrupados');
					var elementos = listado.children("div").get();
					elementos.sort(function(a,b) {
						var A = $(a).children('span').text().toUpperCase();
						var B = $(b).children('span').text().toUpperCase();
				 		return (A < B) ? -1 : (A > B) ? 1 : 0;
					});
					$.each(elementos, function(id, elemento) {
						listado.append(elemento);
					});
        }
    });
});

$('body').on('click', '.agrupado_div', function() {
		if($(this).children("div").css('display') == 'none'){
			$(this).children("div").css('display','block');
		}else{
			$(this).children("div").css('display','none');
		}
});

$('body').on('click', '.clickable-row2', function() {
		window.location = $(this).data("href");
});

$('body').on('click', '#volver_agrupado', function() {
		$('#tabla_incidencias').css('display', 'table-row-group');
		$('#version_escritorio nav').css('display', 'block');
		$('#div_agrupados').css('display', 'none');
    $('#div_agrupados').html(" ");
});
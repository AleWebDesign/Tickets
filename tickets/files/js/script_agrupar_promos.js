var url = window.location.href;
var arr = url.split("/");

/* Script agrupar maquinas */
$('.agrupar').on('click', function(){
		$('.agrupar').css('color','#337ab7');
		$(this).css('color','#d80039');
		id_col = $(this).attr('id');
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/agrupar_promos",
        data: "col=" + id_col,
        success: function(data){
        	$('#tabla_incidencias').css('display', 'none');
        	$('#version_escritorio nav').css('display', 'none');
        	$('#div_agrupados').html(data);
        	$('#div_agrupados').css('display', 'block');
        }
    });
});

$('body').on('click', '.agrupado_div', function() {
		if($(this).next("div").css('display') == 'none'){
			$(this).next("div").css('display','block');
		}else{
			$(this).next("div").css('display','none');
		}
});

$('body').on('click', '#volver_agrupado', function() {
		$('#tabla_incidencias').css('display', 'table-row-group');
		$('#version_escritorio nav').css('display', 'block');
		$('#div_agrupados').css('display', 'none');
    $('#div_agrupados').html(" ");
});
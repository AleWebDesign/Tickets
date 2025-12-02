var url = window.location.href;
var arr = url.split("/");

/* Script agrupar informes */
$('.agrupar').on('click', function(){
		$('.agrupar').css('color','#337ab7');
		$(this).css('color','#d80039');
		id_col = $(this).attr('id');
		sql = $('#consulta_sql').val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/agrupar_informes",
        data: "col=" + id_col + "&sql=" + sql,
        success: function(data){
        	$('#tabla_incidencias').css('display', 'none');
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
		$('#tabla_incidencias').css('display', 'table-row-group');
		$('#div_agrupados').css('display', 'none');
    $('#div_agrupados').html(" ");
});
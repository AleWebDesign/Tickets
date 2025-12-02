var url = window.location.href;
var arr = url.split("/");

/* script guardias.php */
$('.tecnico').on('click', function(){
	$(this).addClass('active').siblings().removeClass('active');
	tecnico = $('.tecnico.active').attr('id');
	mes = $('.mes.active').attr('id');
	anio = $('.anio.active').attr('id');
	if (tecnico != null && mes != null && anio != null){
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_guardias_tecnico",
        data: "t=" + tecnico + "&m=" + mes + "&y=" + anio,
        success: function(data){
        	$('#html_guardias').html(data);	
        }
    });
	}
});

$('.mes').on('click', function(){
	$('.mes').removeClass('active');
	$(this).addClass('active');
	tecnico = $('.tecnico.active').attr('id');
	mes = $('.mes.active').attr('id');
	anio = $('.anio.active').attr('id');
	if (tecnico != null && mes != null && anio != null){
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_guardias_tecnico",
        data: "t=" + tecnico + "&m=" + mes + "&y=" + anio,
        success: function(data){
        	$('#html_guardias').html(data);
        }
    });
	}
});

$('.anio').on('click', function(){
	$('.anio').removeClass('active');
	$(this).addClass('active');
	tecnico = $('.tecnico.active').attr('id');
	mes = $('.mes.active').attr('id');
	anio = $('.anio.active').attr('id');
	if (tecnico != null && mes != null && anio != null){
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_guardias_tecnico",
        data: "t=" + tecnico + "&m=" + mes + "&y=" + anio,
        success: function(data){
        	$('#html_guardias').html(data);
        }
    });
	}
});
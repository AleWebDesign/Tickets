var url = window.location.href;
var arr = url.split("/");

/* Script crear mantenimientos */
$('#zona').on('change', function(){
		id_zona = $(this).val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_salones_zona",
        data: "id=" + id_zona,
        success: function(data){
        	$('#salon').html(data);
        }
    });
});


$('#salon').on('change',function(){
	if($(this).val() == ''){
		$('#error_maquina').html("<option value=''>Máquina...</option>");
		$('#error_maquina').prop('disabled', 'disabled');
		$('#mantenimiento').html("<option value=''>Mantenimiento...</option>");
		$('#mantenimiento').prop('disabled', 'disabled');
	}else{
		$('#error_maquina').prop('disabled', false);
		id_salon = $(this).val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_maquinas_salon",
        data: "id=" + id_salon,
        success: function(data){
        	$('#error_maquina').html(data);
        }
    });
  }
});

$('#error_maquina').on('change',function(){
	if($(this).val() == ''){
		$('#mantenimiento').html("<option value=''>Mantenimiento...</option>");
		$('#mantenimiento').prop('disabled', 'disabled');
	}else{
		$('#mantenimiento').prop('disabled', false);
		maquina = $(this).val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_mantenimientos_maquina",
        data: "maquina=" + maquina,
        success: function(data){
        	$('#mantenimiento').html(data);
        }
    });
	}
});
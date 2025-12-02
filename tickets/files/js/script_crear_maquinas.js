var url = window.location.href;
var arr = url.split("/");

/* Script crear maquinas */
$('#fabri').on('change',function(){
	$('.serie1').css('display', 'none');
	$('.serie2').css('display', 'none');
	$('.serie3').css('display', 'none');
	if($(this).val() == ''){
		$('#modelo').html("<option value=''>Modelo...</option>");
		$('#modelo').prop('disabled', 'disabled');
		$('#puestos').html("<option value=''>Puestos...</option>");
		$('#puestos').prop('disabled', 'disabled');
	}else{
		$('#modelo').prop('disabled', false);
		id_fabri = $(this).val();
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_modelos",
        data: "id=" + id_fabri,
        success: function(data){
        	$('#modelo').html(data);
        }
    });
	}
});

$('#modelo').on('change',function(){
	$('.serie1').css('display', 'none');
	$('.serie2').css('display', 'none');
	$('.serie3').css('display', 'none');
	if($(this).val() == ''){
		$('#puestos').html("<option value=''>Puestos...</option>");
		$('#puestos').prop('disabled', 'disabled');
	}else{
		if($(this).val() == 120 || $(this).val() == 121 || $(this).val() == 122 || $(this).val() == 191){
			$('.serie1').css('display', 'block');	
		}
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

$('#puestos').on('change',function(){
	$('.serie1').css('display', 'none');
	$('.serie2').css('display', 'none');
	$('.serie3').css('display', 'none');
	id_modelo = $('#modelo').val();
	puestos = $(this).val();
	$.ajax ({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_modelo",
      data: "id=" + id_modelo,
      success: function(data){
      	for (var i = 0; i < data.length; i++){
      		if(data[i] == 3 || data[i] == 10){
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
        		if(puestos == 5){
        			$('.serie1').css('display', 'block');
					$('.serie2').css('display', 'block');
					$('.serie3').css('display', 'block');
					$('.serie4').css('display', 'block');
					$('.serie5').css('display', 'block');
        		}
      		}
      	}
      }
  });
});
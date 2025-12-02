var url = window.location.href;
var arr = url.split("/");

$('#operador').on('change',function(){
	id_op =  $(this).val();
	$.ajax ({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_salones_operadora_crear_visitas",
      data: "id=" + id_op,
      success: function(data){
      	$('#salon').html(data);
      }
	});
	$.ajax ({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_personal_operadora",
      data: "id=" + id_op,
      success: function(data){
      	$('#personal1,#personal2').html(data);
      }
	});
});

$('#salon').on('change',function(){
	salon =  $(this).val();
	$.ajax ({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_personal_salon",
      data: "id=" + salon,
      success: function(data){
      	$('#personal1,#personal2').html(data);
      }
	});
});
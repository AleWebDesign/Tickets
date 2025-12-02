var url = window.location.href;
var arr = url.split("/");

$('#operador').on('change',function(){
	id_op =  $(this).val();
	$.ajax ({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_salones_operadora_crear_personal",
      data: "id=" + id_op,
      success: function(data){
      	$('#salon').html(data);
      }
  });
});
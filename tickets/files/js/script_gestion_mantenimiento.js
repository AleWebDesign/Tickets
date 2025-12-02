var url = window.location.href;
var arr = url.split("/");

/* script maquinas.php */
$(".clickable-row").click(function(){
	window.open($(this).data("href"),"_self");
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

$('.registrar_mantenimiento').on('click', function(e){
	e.preventDefault();
	salon = $(this).next().val();
	maquina = $(this).next().next().val();
	$.ajax ({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/registrar_mantenimiento",
      data: "s=" + salon + "&m=" + maquina,
      success: function(data){
      	location.reload();
      }
  });
});
/* Script gestion_usuarios.php */
$(".clickable-row").click(function(){
	window.location.href = $(this).data("href");
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
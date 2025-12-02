var url = window.location.href;
var arr = url.split("/");

/* script cajeros.php */
$('.panel-heading').on('click',function(){
	if($(this).attr('class') == 'panel-heading'){
		if($(this).next().attr('class') == 'panel-body'){
			if($(this).next().css('display') == 'block'){
				$(this).children().attr('class', 'glyphicon glyphicon-triangle-bottom');
				$(this).next().css('display','none');
			}else{
				$(this).children().attr('class', 'glyphicon glyphicon-triangle-top');
				$(this).next().css('display','block');
			}
		}
	}
});
/*
$('#salon').on('change', function(){
	if($(this).val() != ''){
		window.location.href = 'http://atc.apuestasdemurcia.es/tickets/cajero/'+$(this).val();
	}
});
*/
$(".clickable-row").click(function(){
	window.location.href = $(this).data("href");
});

$('body').on('click', '#avisos_alert', function() {
	if($('#avisos_content').is(':visible')){
		$('#avisos_content').css('display', 'none');
	}else{
		$('#avisos_content').css('display', 'block');
	}
});

$(function(){
	salones = [];
	$("#salon option").each(function(){
		salones.push($(this).val());
	});
	$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/comprobar_cajero_salon",
        data: "salones=" + salones,
        success: function(data){
        	$("#div_avisos").html(data);
        }
    });
});
	
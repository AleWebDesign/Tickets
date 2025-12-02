/* script ruletas.php */
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
		window.location.href = 'http://atc.apuestasdemurcia.es/tickets/ruleta/'+$(this).val();
	}
});
*/
$(".clickable-row").click(function(){
	window.location.href = $(this).data("href");
});
/* Script gestion informes */
$('.check_all_visits').click(function(e){
	e.preventDefault();
	if($(this).html() == "Seleccionar todos"){
		$('.selec').prop('checked',true);
		$(this).html('Deseleccionar todos');
	}else{
		$('.selec').prop('checked',false);
		$(this).html('Seleccionar todos');
	}
});

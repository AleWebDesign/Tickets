var url = window.location.href;
var arr = url.split("/");

/* Script crear usuarios */
$('#rol').on('change',function(){
	if($(this).val() == ''){
		$('#acceso').html("<option value=''>Acceso...</option>");
		$('#acceso').prop('disabled', 'disabled');
	}else{
		$('#acceso').prop('disabled', false);
		id_acceso = $(this).val();
		console.log(id_acceso);
		$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_acceso",
        data: "id=" + id_acceso,
        success: function(data){
        	$('#acceso').html(data);
        }
    });
	}
});
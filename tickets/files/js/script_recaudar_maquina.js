/* Script recaudar maquinas */

/* Solo numeros */
$("input").keydown(function (e) {
	        // Allow: backspace, delete, tab, escape, enter and .
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
	             // Allow: Ctrl+A, Command+A
	            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
	             // Allow: home, end, left, right, down, up
	            (e.keyCode >= 35 && e.keyCode <= 40)) {
	                 // let it happen, don't do anything
	                 return;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });

/* -- INICIO TEORICO -- */

/* -- HOPPER TEORICO -- */

$('input[name=t_h_u_1]').on('input',function(e){
	
		t_h_u_1 = $(this).val();
    $('input[name=t_h_t_1]').val(t_h_u_1*1);
    $('input[name=r_h_u_1]').val(t_h_u_1);
    $('input[name=r_h_t_1]').val(t_h_u_1*1);
    
    t_h_t_1 = parseInt($('input[name=t_h_t_1]').val());
    t_h_t_2 = $('input[name=t_h_t_2]').val();
    if (t_h_t_2==null || t_h_t_2==""){
    	t_h_t_2 = 0;
    }else{
    	t_h_t_2 = parseInt(t_h_t_2);
    }
    $('input[name=t_h_t]').val(t_h_t_1+t_h_t_2);
    $('input[name=r_h_t]').val(t_h_t_1+t_h_t_2);
    
    t_h_t = parseInt($('input[name=t_h_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_h_t_1]').on('input',function(e){
		
		t_h_t_1 = $(this).val();
    $('input[name=t_h_u_1]').val(t_h_t_1/1);
    $('input[name=r_h_u_1]').val(t_h_t_1/1);
    $('input[name=r_h_t_1]').val(t_h_t_1);
    
    
    t_h_t_1 = parseInt(t_h_t_1*1);
    t_h_t_2 = $('input[name=t_h_t_2]').val();
    if (t_h_t_2==null || t_h_t_2==""){
    	t_h_t_2 = 0;
    }else{
    	t_h_t_2 = parseInt(t_h_t_2);
    }
    $('input[name=t_h_t]').val(t_h_t_1+t_h_t_2);
    $('input[name=r_h_t]').val(t_h_t_1+t_h_t_2);
    
    t_h_t = parseInt($('input[name=t_h_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_h_u_2]').on('input',function(e){
		
		t_h_u_2 = $(this).val();
    $('input[name=t_h_t_2]').val(t_h_u_2*2);
    $('input[name=r_h_t_2]').val(t_h_u_2*2);
    $('input[name=r_h_u_2]').val(t_h_u_2);
    
    t_h_t_2 = parseInt($('input[name=t_h_t_2]').val());
    t_h_t_1 = $('input[name=t_h_t_1]').val();
    if (t_h_t_1==null || t_h_t_1==""){
    	t_h_t_1 = 0;
    }else{
    	t_h_t_1 = parseInt(t_h_t_1);
    }
    $('input[name=t_h_t]').val(t_h_t_1+t_h_t_2);
    $('input[name=r_h_t]').val(t_h_t_1+t_h_t_2);
    
    t_h_t = parseInt($('input[name=t_h_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);

});

$('input[name=t_h_t_2]').on('input',function(e){
    
    t_h_t_2 = $(this).val();
    $('input[name=t_h_u_2]').val(t_h_t_2 / 2);
    $('input[name=r_h_u_2]').val(t_h_t_2 / 2);
    $('input[name=r_h_t_2]').val(t_h_t_2);
    
    t_h_t_2 = parseInt(t_h_t_2);
    t_h_t_1 = $('input[name=t_h_t_1]').val();
    if (t_h_t_1==null || t_h_t_1==""){
    	t_h_t_1 = 0;
    }else{
    	t_h_t_1 = parseInt(t_h_t_1);
    }
    $('input[name=t_h_t]').val(t_h_t_1+t_h_t_2);
    $('input[name=r_h_t]').val(t_h_t_1+t_h_t_2);
    
    t_h_t = parseInt($('input[name=t_h_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

/* -- BILLETES TEORICO -- */

$('input[name=t_b_u_5]').on('input',function(e){
	
		t_b_u_5 = $(this).val();
    $('input[name=t_b_t_5]').val(t_b_u_5*5);
    $('input[name=r_b_t_5]').val(t_b_u_5*5);
    $('input[name=r_b_u_5]').val(t_b_u_5);
    
    t_b_t_5 = parseInt($('input[name=t_b_t_5]').val());
    t_b_t_10 = $('input[name=t_b_t_10]').val();
    if (t_b_t_10==null || t_b_t_10==""){
    	t_b_t_10 = 0;
    }else{
    	t_b_t_10 = parseInt(t_b_t_10);
    }
    t_b_t_20 = $('input[name=t_b_t_20]').val();
    if (t_b_t_20==null || t_b_t_20==""){
    	t_b_t_20 = 0;
    }else{
    	t_b_t_20 = parseInt(t_b_t_20);
    }
    t_b_t_50 = $('input[name=t_b_t_50]').val();
    if (t_b_t_50==null || t_b_t_50==""){
    	t_b_t_50 = 0;
    }else{
    	t_b_t_50 = parseInt(t_b_t_50);
    }
    $('input[name=t_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    $('input[name=r_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    
    t_b_t = parseInt($('input[name=t_b_t]').val());
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_b_t_5]').on('input',function(e){
		
		t_b_t_5 = $(this).val();
    $('input[name=t_b_u_5]').val(t_b_t_5 / 5);
    $('input[name=r_b_u_5]').val(t_b_t_5 / 5);
    $('input[name=r_b_t_5]').val(t_b_t_5);
    
    t_b_t_5 = parseInt(t_b_t_5);
    t_b_t_10 = $('input[name=t_b_t_10]').val();
    if (t_b_t_10==null || t_b_t_10==""){
    	t_b_t_10 = 0;
    }else{
    	t_b_t_10 = parseInt(t_b_t_10);
    }
    t_b_t_20 = $('input[name=t_b_t_20]').val();
    if (t_b_t_20==null || t_b_t_20==""){
    	t_b_t_20 = 0;
    }else{
    	t_b_t_20 = parseInt(t_b_t_20);
    }
    t_b_t_50 = $('input[name=t_b_t_50]').val();
    if (t_b_t_50==null || t_b_t_50==""){
    	t_b_t_50 = 0;
    }else{
    	t_b_t_50 = parseInt(t_b_t_50);
    }
    $('input[name=t_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    $('input[name=r_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    
    t_b_t = parseInt($('input[name=t_b_t]').val());
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_b_u_10]').on('input',function(e){
	
		t_b_u_10 = $(this).val();
    $('input[name=t_b_t_10]').val(t_b_u_10*10);
    $('input[name=r_b_t_10]').val(t_b_u_10*10);
    $('input[name=r_b_u_10]').val(t_b_u_10);
    
    t_b_t_10 = parseInt($('input[name=t_b_t_10]').val());
    t_b_t_5 = $('input[name=t_b_t_5]').val();
    if (t_b_t_5==null || t_b_t_5==""){
    	t_b_t_5 = 0;
    }else{
    	t_b_t_5 = parseInt(t_b_t_5);
    }
    t_b_t_20 = $('input[name=t_b_t_20]').val();
    if (t_b_t_20==null || t_b_t_20==""){
    	t_b_t_20 = 0;
    }else{
    	t_b_t_20 = parseInt(t_b_t_20);
    }
    t_b_t_50 = $('input[name=t_b_t_50]').val();
    if (t_b_t_50==null || t_b_t_50==""){
    	t_b_t_50 = 0;
    }else{
    	t_b_t_50 = parseInt(t_b_t_50);
    }
    $('input[name=t_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    $('input[name=r_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    
    t_b_t = parseInt($('input[name=t_b_t]').val());
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_b_t_10]').on('input',function(e){
		
		t_b_t_10 = $(this).val();
    $('input[name=t_b_u_10]').val(t_b_t_10 / 10);
    $('input[name=r_b_u_10]').val(t_b_t_10 / 10);
    $('input[name=r_b_t_10]').val(t_b_t_10);
    
    t_b_t_10 = parseInt(t_b_t_10);
    t_b_t_5 = $('input[name=t_b_t_5]').val();
    if (t_b_t_5==null || t_b_t_5==""){
    	t_b_t_5 = 0;
    }else{
    	t_b_t_5 = parseInt(t_b_t_5);
    }
    t_b_t_20 = $('input[name=t_b_t_20]').val();
    if (t_b_t_20==null || t_b_t_20==""){
    	t_b_t_20 = 0;
    }else{
    	t_b_t_20 = parseInt(t_b_t_20);
    }
    t_b_t_50 = $('input[name=t_b_t_50]').val();
    if (t_b_t_50==null || t_b_t_50==""){
    	t_b_t_50 = 0;
    }else{
    	t_b_t_50 = parseInt(t_b_t_50);
    }
    $('input[name=t_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    $('input[name=r_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    
    t_b_t = parseInt($('input[name=t_b_t]').val());
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_b_u_20]').on('input',function(e){
	
		t_b_u_20 = $(this).val();
    $('input[name=t_b_t_20]').val(t_b_u_20*20);
    $('input[name=r_b_t_20]').val(t_b_u_20*20);
    $('input[name=r_b_u_20]').val(t_b_u_20);
    
    t_b_t_20 = parseInt($('input[name=t_b_t_20]').val());
    t_b_t_5 = $('input[name=t_b_t_5]').val();
    if (t_b_t_5==null || t_b_t_5==""){
    	t_b_t_5 = 0;
    }else{
    	t_b_t_5 = parseInt(t_b_t_5);
    }
    t_b_t_10 = $('input[name=t_b_t_10]').val();
    if (t_b_t_10==null || t_b_t_10==""){
    	t_b_t_10 = 0;
    }else{
    	t_b_t_10 = parseInt(t_b_t_10);
    }
    t_b_t_50 = $('input[name=t_b_t_50]').val();
    if (t_b_t_50==null || t_b_t_50==""){
    	t_b_t_50 = 0;
    }else{
    	t_b_t_50 = parseInt(t_b_t_50);
    }
    $('input[name=t_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    $('input[name=r_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    
    t_b_t = parseInt($('input[name=t_b_t]').val());
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_b_t_20]').on('input',function(e){
		
		t_b_t_20 = $(this).val();
    $('input[name=t_b_u_20]').val(t_b_t_20 / 20);
    $('input[name=r_b_u_20]').val(t_b_t_20 / 20);
    $('input[name=r_b_t_20]').val(t_b_t_20);
    
    t_b_t_20 = parseInt(t_b_t_20);
    t_b_t_5 = $('input[name=t_b_t_5]').val();
    if (t_b_t_5==null || t_b_t_5==""){
    	t_b_t_5 = 0;
    }else{
    	t_b_t_5 = parseInt(t_b_t_5);
    }
    t_b_t_10 = $('input[name=t_b_t_10]').val();
    if (t_b_t_10==null || t_b_t_10==""){
    	t_b_t_10 = 0;
    }else{
    	t_b_t_10 = parseInt(t_b_t_10);
    }
    t_b_t_50 = $('input[name=t_b_t_50]').val();
    if (t_b_t_50==null || t_b_t_50==""){
    	t_b_t_50 = 0;
    }else{
    	t_b_t_50 = parseInt(t_b_t_50);
    }
    $('input[name=t_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    $('input[name=r_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    
    t_b_t = parseInt($('input[name=t_b_t]').val());
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_b_u_50]').on('input',function(e){
	
		t_b_u_50 = $(this).val();
    $('input[name=t_b_t_50]').val(t_b_u_50*50);
    $('input[name=r_b_t_50]').val(t_b_u_50*50);
    $('input[name=r_b_u_50]').val(t_b_u_50);
    
    t_b_t_50 = parseInt($('input[name=t_b_t_50]').val());
    t_b_t_5 = $('input[name=t_b_t_5]').val();
    if (t_b_t_5==null || t_b_t_5==""){
    	t_b_t_5 = 0;
    }else{
    	t_b_t_5 = parseInt(t_b_t_5);
    }
    t_b_t_10 = $('input[name=t_b_t_10]').val();
    if (t_b_t_10==null || t_b_t_10==""){
    	t_b_t_10 = 0;
    }else{
    	t_b_t_10 = parseInt(t_b_t_10);
    }
    t_b_t_20 = $('input[name=t_b_t_20]').val();
    if (t_b_t_20==null || t_b_t_20==""){
    	t_b_t_20 = 0;
    }else{
    	t_b_t_20 = parseInt(t_b_t_20);
    }
    $('input[name=t_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    $('input[name=r_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    
    t_b_t = parseInt($('input[name=t_b_t]').val());
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_b_t_50]').on('input',function(e){
		
		t_b_t_50 = $(this).val();
    $('input[name=t_b_u_50]').val(t_b_t_50 / 50);
    $('input[name=r_b_u_50]').val(t_b_t_50 / 50);
    $('input[name=r_b_t_50]').val(t_b_t_50);
    
    t_b_t_50 = parseInt(t_b_t_50);
    t_b_t_5 = $('input[name=t_b_t_5]').val();
    if (t_b_t_5==null || t_b_t_5==""){
    	t_b_t_5 = 0;
    }else{
    	t_b_t_5 = parseInt(t_b_t_5);
    }
    t_b_t_10 = $('input[name=t_b_t_10]').val();
    if (t_b_t_10==null || t_b_t_10==""){
    	t_b_t_10 = 0;
    }else{
    	t_b_t_10 = parseInt(t_b_t_10);
    }
    t_b_t_20 = $('input[name=t_b_t_20]').val();
    if (t_b_t_20==null || t_b_t_20==""){
    	t_b_t_20 = 0;
    }else{
    	t_b_t_20 = parseInt(t_b_t_20);
    }
    $('input[name=t_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    $('input[name=r_b_t]').val(t_b_t_5+t_b_t_10+t_b_t_20+t_b_t_50);
    
    t_b_t = parseInt($('input[name=t_b_t]').val());
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

/* -- CAJON TEORICO -- */

$('input[name=t_c_u_1]').on('input',function(e){
	
	t_c_u_1 = $(this).val();
    
    $('input[name=t_c_t_1]').val(t_c_u_1*1);
    $('input[name=r_c_t_1]').val(t_c_u_1*1);
    $('input[name=r_c_u_1]').val(t_c_u_1);
    
    t_c_t_1 = parseInt($('input[name=t_c_t_1]').val());
    t_c_t_2 = $('input[name=t_c_t_2]').val();
    if (t_c_t_2==null || t_c_t_2==""){
    	t_c_t_2 = 0;
    }else{
    	t_c_t_2 = parseInt(t_c_t_2);
    }
    $('input[name=t_c_t]').val(t_c_t_1+t_c_t_2);
    $('input[name=r_c_t]').val(t_c_t_1+t_c_t_2);
    
    t_c_t = parseInt($('input[name=t_c_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_c_t_1]').on('input',function(e){
		
		t_c_t_1 = $(this).val();
    $('input[name=t_c_u_1]').val(t_c_t_1);
    $('input[name=r_c_u_1]').val(t_c_t_1);
    $('input[name=r_c_t_1]').val(t_c_t_1);
    
    t_c_t_1 = parseInt(t_c_t_1*1);
    t_c_t_2 = $('input[name=t_c_t_2]').val();
    if (t_c_t_2==null || t_c_t_2==""){
    	t_c_t_2 = 0;
    }else{
    	t_c_t_2 = parseInt(t_c_t_2);
    }
    $('input[name=t_c_t]').val(t_c_t_1+t_c_t_2);
    $('input[name=r_c_t]').val(t_c_t_1+t_c_t_2);
    
    t_c_t = parseInt($('input[name=t_c_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_c_u_2]').on('input',function(e){
		
		t_c_u_2 = $(this).val();
    $('input[name=t_c_t_2]').val(t_c_u_2*2);
    $('input[name=r_c_t_2]').val(t_c_u_2*2);
    $('input[name=r_c_u_2]').val(t_c_u_2);
    
    t_c_t_2 = parseInt($('input[name=t_c_t_2]').val());
    t_c_t_1 = $('input[name=t_c_t_1]').val();
    if (t_c_t_1==null || t_c_t_1==""){
    	t_c_t_1 = 0;
    }else{
    	t_c_t_1 = parseInt(t_c_t_1);
    }
    $('input[name=t_c_t]').val(t_c_t_1+t_c_t_2);
    $('input[name=r_c_t]').val(t_c_t_1+t_c_t_2);
    
    t_c_t = parseInt($('input[name=t_c_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);

});

$('input[name=t_c_t_2]').on('input',function(e){
    
    t_c_t_2 = $(this).val();
    $('input[name=t_c_u_2]').val(t_c_t_2 / 2);
    $('input[name=r_c_u_2]').val(t_c_t_2 / 2);
    $('input[name=r_c_t_2]').val(t_c_t_2);
    
    t_c_t_2 = parseInt(t_c_t_2);
    t_c_t_1 = $('input[name=t_c_t_1]').val();
    if (t_c_t_1==null || t_c_t_1==""){
    	t_c_t_1 = 0;
    }else{
    	t_c_t_1 = parseInt(t_c_t_1);
    }
    $('input[name=t_c_t]').val(t_c_t_1+t_c_t_2);
    $('input[name=r_c_t]').val(t_c_t_1+t_c_t_2);
    
    t_c_t = parseInt($('input[name=t_c_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_r_t = $('input[name=t_r_t]').val();
    if (t_r_t==null || t_r_t==""){
    	t_r_t = 0;
    }else{
    	t_r_t = parseInt(t_r_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

/* -- RECICLADOR TEORICO -- */

$('input[name=t_r_u_20]').on('input',function(e){
	
		t_r_u_20 = $(this).val();
    $('input[name=t_r_t_20]').val(t_r_u_20*20);
    $('input[name=r_r_t_20]').val(t_r_u_20*20);
    $('input[name=r_r_u_20]').val(t_r_u_20);
    
    t_r_t_20 = parseInt($('input[name=t_r_t_20]').val());
    $('input[name=t_r_t]').val(t_r_t_20);
    $('input[name=r_r_t]').val(t_r_t_20);
    
    t_r_t = parseInt($('input[name=t_r_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=t_r_t_20]').on('input',function(e){
		
		t_r_t_20 = $(this).val();
    $('input[name=t_r_u_20]').val(t_r_t_20 / 20);
    $('input[name=r_r_u_20]').val(t_r_t_20 / 20);
    $('input[name=r_r_t_20]').val(t_r_t_20);
    
    t_r_t_20 = parseInt(t_r_t_20);
    $('input[name=t_r_t]').val(t_r_t_20);
    $('input[name=r_r_t]').val(t_r_t_20);
    
    t_r_t = parseInt($('input[name=t_r_t]').val());
    t_b_t = $('input[name=t_b_t]').val();
    if (t_b_t==null || t_b_t==""){
    	t_b_t = 0;
    }else{
    	t_b_t = parseInt(t_b_t);
    }
    t_h_t = $('input[name=t_h_t]').val();
    if (t_h_t==null || t_h_t==""){
    	t_h_t = 0;
    }else{
    	t_h_t = parseInt(t_h_t);
    }
    t_c_t = $('input[name=t_c_t]').val();
    if (t_c_t==null || t_c_t==""){
    	t_c_t = 0;
    }else{
    	t_c_t = parseInt(t_c_t);
    }
    $('input[name=t_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    $('input[name=r_reca_t]').val(t_h_t+t_b_t+t_c_t+t_r_t);
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

/* -- FIN TEORICO -- */

/* -- INICIO REAL -- */

/* -- HOPPER REAL -- */

$('input[name=r_h_u_1]').on('input',function(e){
	
		r_h_u_1 = $(this).val();
    $('input[name=r_h_t_1]').val(r_h_u_1*1);
    
    r_h_t_1 = parseInt($('input[name=r_h_t_1]').val());
    r_h_t_2 = $('input[name=r_h_t_2]').val();
    if (r_h_t_2==null || r_h_t_2==""){
    	r_h_t_2 = 0;
    }else{
    	r_h_t_2 = parseInt(r_h_t_2);
    }
    $('input[name=r_h_t]').val(r_h_t_1+r_h_t_2);
    
    r_h_t = parseInt($('input[name=r_h_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
    
});

$('input[name=r_h_t_1]').on('input',function(e){
		
		r_h_t_1 = $(this).val();
    $('input[name=r_h_u_1]').val(r_h_t_1/1);
    
    r_h_t_1 = parseInt(r_h_t_1*1);
    r_h_t_2 = $('input[name=r_h_t_2]').val();
    if (r_h_t_2==null || r_h_t_2==""){
    	r_h_t_2 = 0;
    }else{
    	r_h_t_2 = parseInt(r_h_t_2);
    }
    $('input[name=r_h_t]').val(r_h_t_1+r_h_t_2);
    
    r_h_t = parseInt($('input[name=r_h_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_h_u_2]').on('input',function(e){
		
		r_h_u_2 = $(this).val();
    $('input[name=r_h_t_2]').val(r_h_u_2*2);
    
    r_h_t_2 = parseInt($('input[name=r_h_t_2]').val());
    r_h_t_1 = $('input[name=r_h_t_1]').val();
    if (r_h_t_1==null || r_h_t_1==""){
    	r_h_t_1 = 0;
    }else{
    	r_h_t_1 = parseInt(r_h_t_1);
    }
    $('input[name=r_h_t]').val(r_h_t_1+r_h_t_2);
    
    r_h_t = parseInt($('input[name=r_h_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);

});

$('input[name=r_h_t_2]').on('input',function(e){
    
    r_h_t_2 = $(this).val();
    $('input[name=r_h_u_2]').val(r_h_t_2 / 2);
    
    r_h_t_2 = parseInt(r_h_t_2);
    r_h_t_1 = $('input[name=r_h_t_1]').val();
    if (r_h_t_1==null || r_h_t_1==""){
    	r_h_t_1 = 0;
    }else{
    	r_h_t_1 = parseInt(r_h_t_1);
    }
    $('input[name=r_h_t]').val(r_h_t_1+r_h_t_2);
    
    r_h_t = parseInt($('input[name=r_h_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

/* -- BILLETES REAL -- */

$('input[name=r_b_u_5]').on('input',function(e){
	
		r_b_u_5 = $(this).val();
    $('input[name=r_b_t_5]').val(r_b_u_5*5);
    
    r_b_t_5 = parseInt($('input[name=r_b_t_5]').val());
    r_b_t_10 = $('input[name=r_b_t_10]').val();
    if (r_b_t_10==null || r_b_t_10==""){
    	r_b_t_10 = 0;
    }else{
    	r_b_t_10 = parseInt(r_b_t_10);
    }
    r_b_t_20 = $('input[name=r_b_t_20]').val();
    if (r_b_t_20==null || r_b_t_20==""){
    	r_b_t_20 = 0;
    }else{
    	r_b_t_20 = parseInt(r_b_t_20);
    }
    r_b_t_50 = $('input[name=r_b_t_50]').val();
    if (r_b_t_50==null || r_b_t_50==""){
    	r_b_t_50 = 0;
    }else{
    	r_b_t_50 = parseInt(r_b_t_50);
    }
    $('input[name=r_b_t]').val(r_b_t_5+r_b_t_10+r_b_t_20+r_b_t_50);
    
    r_b_t = parseInt($('input[name=r_b_t]').val());
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_b_t_5]').on('input',function(e){
		
		r_b_t_5 = $(this).val();
    $('input[name=r_b_u_5]').val(r_b_t_5 / 5);
    
    r_b_t_5 = parseInt(r_b_t_5);
    r_b_t_10 = $('input[name=r_b_t_10]').val();
    if (r_b_t_10==null || r_b_t_10==""){
    	r_b_t_10 = 0;
    }else{
    	r_b_t_10 = parseInt(r_b_t_10);
    }
    r_b_t_20 = $('input[name=r_b_t_20]').val();
    if (r_b_t_20==null || r_b_t_20==""){
    	r_b_t_20 = 0;
    }else{
    	r_b_t_20 = parseInt(r_b_t_20);
    }
    r_b_t_50 = $('input[name=r_b_t_50]').val();
    if (r_b_t_50==null || r_b_t_50==""){
    	r_b_t_50 = 0;
    }else{
    	r_b_t_50 = parseInt(r_b_t_50);
    }
    $('input[name=r_b_t]').val(r_b_t_5+r_b_t_10+r_b_t_20+r_b_t_50);
    
    r_b_t = parseInt($('input[name=r_b_t]').val());
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_b_u_10]').on('input',function(e){
	
		r_b_u_10 = $(this).val();
    $('input[name=r_b_t_10]').val(r_b_u_10*10);
    
    r_b_t_10 = parseInt($('input[name=r_b_t_10]').val());
    r_b_t_5 = $('input[name=r_b_t_5]').val();
    if (r_b_t_5==null || r_b_t_5==""){
    	r_b_t_5 = 0;
    }else{
    	r_b_t_5 = parseInt(r_b_t_5);
    }
    r_b_t_20 = $('input[name=r_b_t_20]').val();
    if (r_b_t_20==null || r_b_t_20==""){
    	r_b_t_20 = 0;
    }else{
    	r_b_t_20 = parseInt(r_b_t_20);
    }
    r_b_t_50 = $('input[name=r_b_t_50]').val();
    if (r_b_t_50==null || r_b_t_50==""){
    	r_b_t_50 = 0;
    }else{
    	r_b_t_50 = parseInt(r_b_t_50);
    }
    $('input[name=r_b_t]').val(r_b_t_5+r_b_t_10+r_b_t_20+r_b_t_50);
    
    r_b_t = parseInt($('input[name=r_b_t]').val());
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_b_t_10]').on('input',function(e){
		
		r_b_t_10 = $(this).val();
    $('input[name=r_b_u_10]').val(r_b_t_10 / 10);
    
    r_b_t_10 = parseInt(r_b_t_10);
    r_b_t_5 = $('input[name=r_b_t_5]').val();
    if (r_b_t_5==null || r_b_t_5==""){
    	r_b_t_5 = 0;
    }else{
    	r_b_t_5 = parseInt(r_b_t_5);
    }
    r_b_t_20 = $('input[name=r_b_t_20]').val();
    if (r_b_t_20==null || r_b_t_20==""){
    	r_b_t_20 = 0;
    }else{
    	r_b_t_20 = parseInt(r_b_t_20);
    }
    r_b_t_50 = $('input[name=r_b_t_50]').val();
    if (r_b_t_50==null || r_b_t_50==""){
    	r_b_t_50 = 0;
    }else{
    	r_b_t_50 = parseInt(r_b_t_50);
    }
    $('input[name=r_b_t]').val(r_b_t_5+r_b_t_10+r_b_t_20+r_b_t_50);
    
    r_b_t = parseInt($('input[name=r_b_t]').val());
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_b_u_20]').on('input',function(e){
	
		r_b_u_20 = $(this).val();
    $('input[name=r_b_t_20]').val(r_b_u_20*20);
    
    r_b_t_20 = parseInt($('input[name=r_b_t_20]').val());
    r_b_t_5 = $('input[name=r_b_t_5]').val();
    if (r_b_t_5==null || r_b_t_5==""){
    	r_b_t_5 = 0;
    }else{
    	r_b_t_5 = parseInt(r_b_t_5);
    }
    r_b_t_10 = $('input[name=r_b_t_10]').val();
    if (r_b_t_10==null || r_b_t_10==""){
    	r_b_t_10 = 0;
    }else{
    	r_b_t_10 = parseInt(r_b_t_10);
    }
    r_b_t_50 = $('input[name=r_b_t_50]').val();
    if (r_b_t_50==null || r_b_t_50==""){
    	r_b_t_50 = 0;
    }else{
    	r_b_t_50 = parseInt(r_b_t_50);
    }
    $('input[name=r_b_t]').val(r_b_t_5+r_b_t_10+r_b_t_20+r_b_t_50);
    
    r_b_t = parseInt($('input[name=r_b_t]').val());
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_b_t_20]').on('input',function(e){
		
		r_b_t_20 = $(this).val();
    $('input[name=r_b_u_20]').val(r_b_t_20 / 20);
    
    r_b_t_20 = parseInt(r_b_t_20);
    r_b_t_5 = $('input[name=r_b_t_5]').val();
    if (r_b_t_5==null || r_b_t_5==""){
    	r_b_t_5 = 0;
    }else{
    	r_b_t_5 = parseInt(r_b_t_5);
    }
    r_b_t_10 = $('input[name=r_b_t_10]').val();
    if (r_b_t_10==null || r_b_t_10==""){
    	r_b_t_10 = 0;
    }else{
    	r_b_t_10 = parseInt(r_b_t_10);
    }
    r_b_t_50 = $('input[name=r_b_t_50]').val();
    if (r_b_t_50==null || r_b_t_50==""){
    	r_b_t_50 = 0;
    }else{
    	r_b_t_50 = parseInt(r_b_t_50);
    }
    $('input[name=r_b_t]').val(r_b_t_5+r_b_t_10+r_b_t_20+r_b_t_50);
    
    r_b_t = parseInt($('input[name=r_b_t]').val());
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_b_u_50]').on('input',function(e){
	
		r_b_u_50 = $(this).val();
    $('input[name=r_b_t_50]').val(r_b_u_50*50);
    
    r_b_t_50 = parseInt($('input[name=r_b_t_50]').val());
    r_b_t_5 = $('input[name=r_b_t_5]').val();
    if (r_b_t_5==null || r_b_t_5==""){
    	r_b_t_5 = 0;
    }else{
    	r_b_t_5 = parseInt(r_b_t_5);
    }
    r_b_t_10 = $('input[name=r_b_t_10]').val();
    if (r_b_t_10==null || r_b_t_10==""){
    	r_b_t_10 = 0;
    }else{
    	r_b_t_10 = parseInt(r_b_t_10);
    }
    r_b_t_20 = $('input[name=r_b_t_20]').val();
    if (r_b_t_20==null || r_b_t_20==""){
    	r_b_t_20 = 0;
    }else{
    	r_b_t_20 = parseInt(r_b_t_20);
    }
    $('input[name=r_b_t]').val(r_b_t_5+r_b_t_10+r_b_t_20+r_b_t_50);
    
    r_b_t = parseInt($('input[name=r_b_t]').val());
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_b_t_50]').on('input',function(e){
		
		r_b_t_50 = $(this).val();
    $('input[name=r_b_u_50]').val(r_b_t_50 / 50);
    
    r_b_t_50 = parseInt(r_b_t_50);
    r_b_t_5 = $('input[name=r_b_t_5]').val();
    if (r_b_t_5==null || r_b_t_5==""){
    	r_b_t_5 = 0;
    }else{
    	r_b_t_5 = parseInt(r_b_t_5);
    }
    r_b_t_10 = $('input[name=r_b_t_10]').val();
    if (r_b_t_10==null || r_b_t_10==""){
    	r_b_t_10 = 0;
    }else{
    	r_b_t_10 = parseInt(r_b_t_10);
    }
    r_b_t_20 = $('input[name=r_b_t_20]').val();
    if (r_b_t_20==null || r_b_t_20==""){
    	r_b_t_20 = 0;
    }else{
    	r_b_t_20 = parseInt(r_b_t_20);
    }
    $('input[name=r_b_t]').val(r_b_t_5+r_b_t_10+r_b_t_20+r_b_t_50);
    
    r_b_t = parseInt($('input[name=r_b_t]').val());
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

/* -- CAJON REAL -- */

$('input[name=r_c_u_1]').on('input',function(e){
	
		r_c_u_1 = $(this).val();
    $('input[name=r_c_t_1]').val(r_c_u_1*1);
    
    r_c_t_1 = parseInt($('input[name=r_c_t_1]').val());
    r_c_t_2 = $('input[name=r_c_t_2]').val();
    if (r_c_t_2==null || r_c_t_2==""){
    	r_c_t_2 = 0;
    }else{
    	r_c_t_2 = parseInt(r_h_t_2);
    }
    $('input[name=r_c_t]').val(r_c_t_1+r_c_t_2);
    
    r_c_t = parseInt($('input[name=r_c_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_c_t_1]').on('input',function(e){
		
		r_c_t_1 = $(this).val();
    $('input[name=r_c_u_1]').val(r_c_t_1);
    
    r_c_t_1 = parseInt(r_c_t_1*1);
    r_c_t_2 = $('input[name=r_c_t_2]').val();
    if (r_c_t_2==null || r_c_t_2==""){
    	r_c_t_2 = 0;
    }else{
    	r_c_t_2 = parseInt(r_c_t_2);
    }
    $('input[name=r_c_t]').val(r_c_t_1+r_c_t_2);
    
    r_c_t = parseInt($('input[name=r_c_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_c_u_2]').on('input',function(e){
		
		r_c_u_2 = $(this).val();
    $('input[name=r_c_t_2]').val(r_c_u_2*2);
    
    r_c_t_2 = parseInt($('input[name=r_c_t_2]').val());
    r_c_t_1 = $('input[name=r_c_t_1]').val();
    if (r_c_t_1==null || r_c_t_1==""){
    	r_c_t_1 = 0;
    }else{
    	r_c_t_1 = parseInt(r_c_t_1);
    }
    $('input[name=r_c_t]').val(r_c_t_1+r_c_t_2);
    
    r_c_t = parseInt($('input[name=r_c_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);

});

$('input[name=r_c_t_2]').on('input',function(e){
    
    r_c_t_2 = $(this).val();
    $('input[name=r_c_u_2]').val(r_c_t_2 / 2);
    
    r_c_t_2 = parseInt(r_c_t_2);
    r_c_t_1 = $('input[name=r_c_t_1]').val();
    if (r_c_t_1==null || r_c_t_1==""){
    	r_c_t_1 = 0;
    }else{
    	r_c_t_1 = parseInt(r_c_t_1);
    }
    $('input[name=r_c_t]').val(r_c_t_1+r_c_t_2);
    
    r_c_t = parseInt($('input[name=r_c_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_r_t = $('input[name=r_r_t]').val();
    if (r_r_t==null || r_r_t==""){
    	r_r_t = 0;
    }else{
    	r_r_t = parseInt(r_r_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

/* -- RECICLADOR REAL -- */

$('input[name=r_r_u_20]').on('input',function(e){
	
		r_r_u_20 = $(this).val();
    $('input[name=r_r_t_20]').val(r_r_u_20*20);
    
    r_r_t_20 = parseInt($('input[name=r_r_t_20]').val());
    $('input[name=r_r_t]').val(r_r_t_20);
    
    r_r_t = parseInt($('input[name=r_r_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=r_r_t_20]').on('input',function(e){
		
		r_r_t_20 = $(this).val();
    $('input[name=r_r_u_20]').val(r_r_t_20 / 20);
    
    r_r_t_20 = parseInt(r_r_t_20);
    $('input[name=r_r_t]').val(r_r_t_20);
    
    r_r_t = parseInt($('input[name=r_r_t]').val());
    r_b_t = $('input[name=r_b_t]').val();
    if (r_b_t==null || r_b_t==""){
    	r_b_t = 0;
    }else{
    	r_b_t = parseInt(r_b_t);
    }
    r_h_t = $('input[name=r_h_t]').val();
    if (r_h_t==null || r_h_t==""){
    	r_h_t = 0;
    }else{
    	r_h_t = parseInt(r_h_t);
    }
    r_c_t = $('input[name=r_c_t]').val();
    if (r_c_t==null || r_c_t==""){
    	r_c_t = 0;
    }else{
    	r_c_t = parseInt(r_c_t);
    }
    $('input[name=r_reca_t]').val(r_h_t+r_b_t+r_c_t+r_r_t);
    
    r_reca_t = $('input[name=r_reca_t]').val();
    carga = $('input[name=carga]').val();
    if (carga==null || carga==""){
    	carga = 0;
    }else{
    	carga = parseInt(carga);
    }
    $('input[name=neto]').val(r_reca_t - carga);
    
});

$('input[name=carga]').on('input',function(e){
	
		carga = parseInt($('input[name=carga]').val());
		r_reca_t = $('input[name=r_reca_t]').val();
		if (r_reca_t==null || r_reca_t==""){
    	r_reca_t = 0;
    }else{
    	r_reca_t = parseInt(r_reca_t);
    }
    $('input[name=neto]').val(r_reca_t - carga);
	
});

/* -- FIN REAL -- */
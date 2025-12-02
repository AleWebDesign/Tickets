/* Script recaudar salon */

$('input[name=pagos_1]').on('input',function(e){
    pagos_1 = parseFloat($('input[name=pagos_1]').val());
    pagos_2 = parseFloat($('input[name=pagos_2]').val());
    pagos_5 = parseFloat($('input[name=pagos_5]').val());
    pagos_10 = parseFloat($('input[name=pagos_10]').val());
    pagos_20 = parseFloat($('input[name=pagos_20]').val());
    pagos_50 = parseFloat($('input[name=pagos_50]').val());

    pagos = parseFloat(pagos_1+pagos_2+pagos_5+pagos_10+pagos_20+pagos_50);

    $('input[name=pagos]').val(pagos);

    reca_t = $('input[name=reca_total]').val();
	if (reca_t==null || reca_t==""){
    	reca_t = 0;
    }else{
    	reca_t = parseFloat(reca_t);
    }
    neto = parseFloat(reca_t - pagos);
    $('input[name=neto]').val(neto);
});

$('input[name=pagos_2]').on('input',function(e){
    pagos_1 = parseFloat($('input[name=pagos_1]').val());
    pagos_2 = parseFloat($('input[name=pagos_2]').val());
    pagos_5 = parseFloat($('input[name=pagos_5]').val());
    pagos_10 = parseFloat($('input[name=pagos_10]').val());
    pagos_20 = parseFloat($('input[name=pagos_20]').val());
    pagos_50 = parseFloat($('input[name=pagos_50]').val());

    pagos = parseFloat(pagos_1+pagos_2+pagos_5+pagos_10+pagos_20+pagos_50);

    $('input[name=pagos]').val(pagos);

    reca_t = $('input[name=reca_total]').val();
	if (reca_t==null || reca_t==""){
    	reca_t = 0;
    }else{
    	reca_t = parseFloat(reca_t);
    }
    neto = parseFloat(reca_t - pagos);
    $('input[name=neto]').val(neto);
});

$('input[name=pagos_5]').on('input',function(e){
    pagos_1 = parseFloat($('input[name=pagos_1]').val());
    pagos_2 = parseFloat($('input[name=pagos_2]').val());
    pagos_5 = parseFloat($('input[name=pagos_5]').val());
    pagos_10 = parseFloat($('input[name=pagos_10]').val());
    pagos_20 = parseFloat($('input[name=pagos_20]').val());
    pagos_50 = parseFloat($('input[name=pagos_50]').val());

    pagos = parseFloat(pagos_1+pagos_2+pagos_5+pagos_10+pagos_20+pagos_50);

    $('input[name=pagos]').val(pagos);

    reca_t = $('input[name=reca_total]').val();
	if (reca_t==null || reca_t==""){
    	reca_t = 0;
    }else{
    	reca_t = parseFloat(reca_t);
    }
    neto = parseFloat(reca_t - pagos);
    $('input[name=neto]').val(neto);
});

$('input[name=pagos_10]').on('input',function(e){
    pagos_1 = parseFloat($('input[name=pagos_1]').val());
    pagos_2 = parseFloat($('input[name=pagos_2]').val());
    pagos_5 = parseFloat($('input[name=pagos_5]').val());
    pagos_10 = parseFloat($('input[name=pagos_10]').val());
    pagos_20 = parseFloat($('input[name=pagos_20]').val());
    pagos_50 = parseFloat($('input[name=pagos_50]').val());

    pagos = parseFloat(pagos_1+pagos_2+pagos_5+pagos_10+pagos_20+pagos_50);

    $('input[name=pagos]').val(pagos);

    reca_t = $('input[name=reca_total]').val();
	if (reca_t==null || reca_t==""){
    	reca_t = 0;
    }else{
    	reca_t = parseFloat(reca_t);
    }
    neto = parseFloat(reca_t - pagos);
    $('input[name=neto]').val(neto);
});

$('input[name=pagos_20]').on('input',function(e){
    pagos_1 = parseFloat($('input[name=pagos_1]').val());
    pagos_2 = parseFloat($('input[name=pagos_2]').val());
    pagos_5 = parseFloat($('input[name=pagos_5]').val());
    pagos_10 = parseFloat($('input[name=pagos_10]').val());
    pagos_20 = parseFloat($('input[name=pagos_20]').val());
    pagos_50 = parseFloat($('input[name=pagos_50]').val());

    pagos = parseFloat(pagos_1+pagos_2+pagos_5+pagos_10+pagos_20+pagos_50);

    $('input[name=pagos]').val(pagos);

    reca_t = $('input[name=reca_total]').val();
	if (reca_t==null || reca_t==""){
    	reca_t = 0;
    }else{
    	reca_t = parseFloat(reca_t);
    }
    neto = parseFloat(reca_t - pagos);
    $('input[name=neto]').val(neto);
});

$('input[name=pagos_50]').on('input',function(e){
    pagos_1 = parseFloat($('input[name=pagos_1]').val());
    pagos_2 = parseFloat($('input[name=pagos_2]').val());
    pagos_5 = parseFloat($('input[name=pagos_5]').val());
    pagos_10 = parseFloat($('input[name=pagos_10]').val());
    pagos_20 = parseFloat($('input[name=pagos_20]').val());
    pagos_50 = parseFloat($('input[name=pagos_50]').val());

    pagos = parseFloat(pagos_1+pagos_2+pagos_5+pagos_10+pagos_20+pagos_50);

    $('input[name=pagos]').val(pagos);

    reca_t = $('input[name=reca_total]').val();
	if (reca_t==null || reca_t==""){
    	reca_t = 0;
    }else{
    	reca_t = parseFloat(reca_t);
    }
    neto = parseFloat(reca_t - pagos);
    $('input[name=neto]').val(neto);
});

$('input[name=pagos]').on('input',function(e){
	
    pagos = parseFloat($('input[name=pagos]').val());
    reca_t = $('input[name=reca_total]').val();
	if (reca_t==null || reca_t==""){
    	reca_t = 0;
    }else{
    	reca_t = parseFloat(reca_t);
    }
    neto = parseFloat(reca_t - pagos);
    $('input[name=neto]').val(neto);
	
});

$('input[name=reca_total]').on('input',function(e){
	
    reca_t = parseFloat($('input[name=reca_total]').val());
    pagos = $('input[name=pagos]').val();
	if (pagos==null || pagos==""){
    	pagos = 0;
    }else{
    	pagos = parseFloat(pagos);
    }
    neto = parseFloat(reca_t - pagos);
    $('input[name=neto]').val(neto);
	
});

$('input[name=neto]').on('input',function(e){
	
    neto = parseFloat($('input[name=neto]').val());
    pagos = $('input[name=pagos]').val();
	if (pagos==null || pagos==""){
    	pagos = 0;
    }else{
    	pagos = parseFloat(pagos);
    }
    reca_t = $('input[name=reca_total]').val();
    if (reca_t==null || reca_t==""){
    	reca_t = 0;
    }else{
    	reca_t = parseFloat(reca_t);
    }
    total = parseFloat(neto + pagos);
    pagos2 = parseFloat(reca_total - neto);
    $('input[name=reca_total]').val(total);
    $('input[name=pagos]').val(pagos2);
	
});

<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px"><a href="<?php echo base_url('guardias'); ?>" style="color: #000; text-decoration: none">Guardias</a></h3>
  	<hr/>
  	<h4>Seleccionar técnico</h4>
  	<hr/>
  	<div class="col-md-12" style="margin: 1% 0 3% 0">
  		<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
  			<a href="#" class="btn btn-default tecnico" id="42" role="button">Manu</a>
  			<a href="#" class="btn btn-default tecnico" id="354" role="button">Idalberto</a>  			
  			<a href="#" class="btn btn-default tecnico" id="93" role="button">Sergio</a>
        <a href="#" class="btn btn-default tecnico" id="392" role="button">Adrián</a>
  		</div>
  	</div>
  	<h4>Seleccionar mes</h4>
  	<hr/>
  	<div class="col-md-12" style="margin: 1% 0">
  		<div class="btn-group btn-group-justified meses" role="group" aria-label="Justified button group">
  			<a href="#" class="btn btn-default mes" id="1" role="button">Enero</a>
  			<a href="#" class="btn btn-default mes" id="2" role="button">Febrero</a>
  			<a href="#" class="btn btn-default mes" id="3" role="button">Marzo</a>
  			<a href="#" class="btn btn-default mes" id="4" role="button">Abril</a>
  			<a href="#" class="btn btn-default mes" id="5" role="button">Mayo</a>
  			<a href="#" class="btn btn-default mes" id="6" role="button">Junio</a>
  		</div>
  	</div>
  	<div class="col-md-12" style="margin: 1% 0 3% 0">
  		<div class="btn-group btn-group-justified meses" role="group" aria-label="Justified button group">
  			<a href="#" class="btn btn-default mes" id="7" role="button">Julio</a>
  			<a href="#" class="btn btn-default mes" id="8" role="button">Agosto</a>
  			<a href="#" class="btn btn-default mes" id="9" role="button">Septiembre</a>
  			<a href="#" class="btn btn-default mes" id="10" role="button">Octubre</a>
  			<a href="#" class="btn btn-default mes" id="11" role="button">Noviembre</a>
  			<a href="#" class="btn btn-default mes" id="12" role="button">Diciembre</a>
  		</div>
  	</div>
  	<h4>Seleccionar año</h4>
  	<hr/>
  	<div class="col-md-12" style="margin: 1% 0">
  		<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">
  			<a href="#" class="btn btn-default anio" id="2018" role="button">2018</a>
  			<a href="#" class="btn btn-default anio" id="2019" role="button">2019</a>
        <a href="#" class="btn btn-default anio" id="2020" role="button">2020</a>
        <a href="#" class="btn btn-default anio" id="2021" role="button">2021</a>
  		</div>
  	</div>
  	<div id="html_guardias" class="col-md-12" style="margin: 1% 0">
  	</div>
  </div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_gestion_guardias.js'); ?>"></script>
<script type="text/javascript" charset="utf-8">
  $(document).on('click', '#imprimir_guardias', function(e){
    e.preventDefault();
    t = $('.tecnico.active').attr('id');
    m = $('.mes.active').attr('id');
    a = $('.anio.active').attr('id');
    if($('#dietas').val()){
      d = $('#dietas').val();
    }else{
      d = 0;
    }
    if($('#km').val()){
      k = $('#km').val();
    }else{
      k = 0;
    }
    if($('input[name="200"]').val()){
      v = $('input[name="200"]').val();
    }else{
      v = 0;
    }
    if($('input[name="120"]').val()){
      s = $('input[name="120"]').val();
    }else{
      s = 0;
    }
    if($('input[name="100"]').val()){
      g = $('input[name="100"]').val();
    }else{
      g = 0;
    }
    if($('input[name="80"]').val()){
      l = $('input[name="80"]').val();
    }else{
      l = 0;
    }
    if($('input[name="110"]').val()){
      j = $('input[name="110"]').val();
    }else{
      j = 0;
    }
    window.open("http://atc.apuestasdemurcia.es/tickets/imprimir_guardias/" + t + "/" + m + "/" + a + "/" + d + "/" + k + "/" + v + "/" + s + "/" + g + "/" + l + "/" + j , "_blank");
  });
</script>
<script type="text/javascript">
/* Solo numeros */
$(document).on("keydown", "input", function(e) {
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

$(document).on("keyup", ".cantidades_km", function(e) {
  var total = 0;
  $('.cantidades_km').each(function(){
    if($(this).val()){
      total += parseInt($(this).attr('name'))*parseInt($(this).val());
    }
  });
  $('#km').val(total);
});

$(document).on("keyup", "#dietas", function(e) {
  var dietas = parseInt($(this).val());
  var km = parseInt($('#km').val());
  $('#total').val(dietas + km);
});
</script>
</html>
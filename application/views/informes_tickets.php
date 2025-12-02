<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px"><a href="<?php echo base_url('informes_tickets'); ?>" style="color: #000; text-decoration: none">Informes ATC</a></h3>	
  	<hr/>
    <div class="col-md-12" style="margin-bottom: 1%">
      <ul class="nav nav-tabs">
        <li role="presentation" class="active_tabs"><a id="0" class="lista_regiones" href="#">Incidencias</a></li>
        <li role="presentation"><a id="1" class="lista_regiones" href="#">Salón</a></li>
        <li role="presentation"><a id="2" class="lista_regiones" href="#">Turnos</a></li>
      </ul>
    </div>
    <div id="div_todas" class="col-md-12" style="margin-bottom: 1%">
    	<h4>Seleccionar fecha</h4>
    	<hr/>
    	<div class="col-md-3 col-sm-12">
    		<div class="input-group" style="width: 100%; float: left">
  				<span class="input-group-addon" id="basic-addon1">
  					<span class="glyphicon glyphicon-calendar"></span>
  				</span>
  				<input id="datepicker" type="text" name="fecha" class="form-control" placeholder="Fecha" <?php if($this->uri->segment(3) != ""){ echo 'value="'.$this->uri->segment(3).'"'; } ?>>
  			</div>
      </div>
      <div class="col-md-3 col-sm-12">
        <div class="input-group">
          <select class="form-control" id="tipo" name="tipo">
            <option value="0">Todas</option>
            <option value="1">Clientes</option>
            <option value="2">Averías</option>
          </select>
        </div>
    	</div>
    	<div id="html_informes" class="col-md-12" style="margin: 1% 0">
    	</div>
    </div>
    <div id="div_salon" class="col-md-12" style="margin-bottom: 1%; display: none">
      <h4>Seleccionar fechas</h4>
      <hr/>
      <div class="col-md-3 col-sm-12">
        <div class="input-group" style="width: 100%; float: left">
          <span class="input-group-addon" id="basic-addon1">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
          <input id="datepicker1" type="text" name="fecha1" class="form-control" placeholder="Fecha Inicio">
        </div>
      </div>
      <div class="col-md-3 col-sm-12">
        <div class="input-group" style="width: 100%; float: left">
          <span class="input-group-addon" id="basic-addon1">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
          <input id="datepicker2" type="text" name="fecha2" class="form-control" placeholder="Fecha Fin">
        </div>
      </div>
      <div id="html_salones" class="col-md-12" style="margin: 1% 0">
      </div>
    </div>

    <div id="div_turnos" class="col-md-12" style="margin-bottom: 1%; display: none">
      <h4>Seleccionar fecha</h4>
      <hr/>
      <div class="col-md-3 col-sm-12">
        <div class="input-group" style="width: 100%; float: left">
          <span class="input-group-addon" id="basic-addon1">
            <span class="glyphicon glyphicon-calendar"></span>
          </span>
          <input id="datepicker3" type="text" name="fecha2" class="form-control" placeholder="Fecha" <?php if($this->uri->segment(3) != ""){ echo 'value="'.$this->uri->segment(3).'"'; } ?>>
        </div>
      </div>
      <div id="html_turnos" class="col-md-12" style="margin: 1% 0">
    </div>

  </div>
</body>
<script type="text/javascript">
  var url = window.location.href;
  var arr = url.split("/");

  $(function(){
      $('#datepicker,#datepicker1,#datepicker2,#datepicker3').datetimepicker({
      	format: 'DD/MM/YYYY', locale: 'es'
      });
      
      if ($('#datepicker').val() != null && $('#datepicker').val() != "") {
        fecha = $('#datepicker').val();
        tipo = $('#tipo').val();
        $.ajax ({
            type: "POST",
            url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_tickets_fecha",
            data: "d=" + fecha + '&t=' + tipo,
            success: function(data){
              $('#html_informes').html(data); 
            }
        });
      }
  });
  
  $('#datepicker').on('dp.hide', function(e){
  	fecha = $(this).val();
    tipo = $('#tipo').val();
  	$.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_tickets_fecha",
        data: "d=" + fecha + '&t=' + tipo,
        success: function(data){
        	$('#html_informes').html(data);	
        }
    });
  });

  $('#tipo').on('change', function(){
    tipo = $(this).val();
    fecha = $('#datepicker').val();
    if(fecha != ''){
      $.ajax ({
          type: "POST",
          url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_tickets_fecha",
          data: "d=" + fecha + '&t=' + tipo,
          success: function(data){
            $('#html_informes').html(data); 
          }
      });
    }
  });

  $('#datepicker1').on('dp.hide', function(e){
    fechaI = $(this).val();
    fechaF = $('#datepicker2').val();
    if(fechaF.length != 0){
      $.ajax ({
          type: "POST",
          url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_tickets_salones",
          data: "d1=" + fechaI + '&d2=' + fechaF,
          success: function(data){
            $('#html_salones').html(data); 
          }
      });
    }
  });

  $('#datepicker2').on('dp.hide', function(e){
    fechaF = $(this).val();
    fechaI = $('#datepicker1').val();
    if(fechaI.length != 0){
      $.ajax ({
          type: "POST",
          url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_tickets_salones",
          data: "d1=" + fechaI + '&d2=' + fechaF,
          success: function(data){
            $('#html_salones').html(data); 
          }
      });
    }
  });

  $('#datepicker3').on('dp.hide', function(e){
    fecha = $(this).val();
    $.ajax ({
        type: "POST",
        url: arr[0] + "//atc.apuestasdemurcia.es/tickets/get_tickets_turno",
        data: "d=" + fecha,
        success: function(data){
          $('#html_turnos').html(data); 
        }
    });
  });
</script>
<script type="text/javascript">
  $('.lista_regiones').on('click', function(){
    $('.lista_regiones').parent().removeClass('active_tabs');
    $(this).parent().addClass('active_tabs');
    var val = $(this).attr('id');
    if(val == 0){
      $('#div_salon').css('display', 'none');
      $('#div_turnos').css('display', 'none');
      $('#div_todas').css('display', 'block');
    }else if(val == 1){
      $('#div_todas').css('display', 'none');
      $('#div_turnos').css('display', 'none');
      $('#div_salon').css('display', 'block');
    }else if(val == 2){
      $('#div_todas').css('display', 'none');
      $('#div_salon').css('display', 'none');
      $('#div_turnos').css('display', 'block');
    }
  });
</script>
</html>
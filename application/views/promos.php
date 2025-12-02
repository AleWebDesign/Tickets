<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px"><a href="<?php echo base_url('promos'); ?>" style="color: #000; text-decoration: none">Promociones</a></h3>		
		<!-- Tabla usuarios -->
		<hr/>
		<div class="col-md-12" style="margin: 1% 0 20px 0; float: left; width: 100%;">
			<select class="form-control" style="float: left; width: 200px" name="promo" id="promo">
				<option value="">Seleccionar promoción</option>
				<option value="1">Táctil</option>
				<option value="2">Triples</option>
				<option value="3">Canastas</option>
				<option value="4">Toques</option>
			</select>
		</div>
		<div class="col-md-12" style="margin: 1% 0 20px 0; float: left; width: 100%;">
			<input type="text" class="form-control" style="float: left; width: 200px" name="nombre" id="nombre" placeholder="Nombre...">
			<input type="text" class="form-control" style="float: left; width: 200px; margin-left: 1%;" name="fecha" id="fecha" placeholder="Fecha...">
			<a style="float: right; margin-left: 1%;" href="<?php echo base_url('promos_pdf/SELECT * FROM aio_clientes_promo'); ?>" class="btn btn-warning exportar" target="_blank">Exportar PDF</a>
			<a style="float: right" href="<?php echo base_url('promos_excel/SELECT * FROM aio_clientes_promo'); ?>" class="btn btn-info exportar" target="_blank">Exportar Excel</a>
		</div>
		<div id="1" class="col-md-12 tabla" style="margin-bottom: 5%; float: left;">
			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">ID</th>
							<th class="th_tabla"><a href="#" class="agrupar" id="salon">Salón</a></th>
							<th class="th_tabla">Nombre</th>
							<th class="th_tabla">Teléfono</th>
							<th class="th_tabla">Email</th>
							<th class="th_tabla">Ticket</th>
					</thead>
					<tbody id="tabla_incidencias">
						<?php echo $tabla_promo; ?>
					</tbody>
				</table>
			</div> 	
  		</div>
  		<div id="2" class="col-md-12 tabla" style="margin-bottom: 5%; float: left; display: none">
  			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">ID</th>
							<th class="th_tabla">Nombre</th>
							<th class="th_tabla">Email</th>
							<th class="th_tabla">Firma</th>
							<th class="th_tabla">Fecha</th>
					</thead>
					<tbody id="tabla_incidencias">
						<?php echo $tabla_triples; ?>
					</tbody>
				</table>
			</div> 
  		</div>
  		<div id="3" class="col-md-12 tabla" style="margin-bottom: 5%; float: left; display: none">
  			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">ID</th>
							<th class="th_tabla">Nombre</th>
							<th class="th_tabla">Email</th>
							<th class="th_tabla">Firma</th>
							<th class="th_tabla">Canastas</th>
							<th class="th_tabla">Fecha</th>
					</thead>
					<tbody id="tabla_incidencias">
						<?php echo $tabla_canastas; ?>
					</tbody>
				</table>
			</div>
  		</div>
  		<div id="4" class="col-md-12 tabla" style="margin-bottom: 5%; float: left; display: none">
  			<div class="panel panel-default" style="margin: 0">
				<table class="table tabla_incidencias">
					<thead>
						<tr>
							<th class="th_tabla">ID</th>
							<th class="th_tabla">Nombre</th>
							<th class="th_tabla">Email</th>
							<th class="th_tabla">Firma</th>
							<th class="th_tabla">Toques</th>
							<th class="th_tabla">Fecha</th>
					</thead>
					<tbody id="tabla_incidencias">
						<?php echo $tabla_toques; ?>
					</tbody>
				</table>
			</div>
  		</div>
  		<div class="col-md-12" id="div_agrupados" style="display: none">
  		</div>
  </div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_agrupar_promos.js'); ?>"></script>
<script type="text/javascript">
  $(function(){
      $('#fecha').datetimepicker({ format: 'DD-MM-YYYY', locale: 'es', useCurrent: false });
      $('#fecha').on("dp.change", function (e) {
	  	var value = $(this).val();
		console.log(value);
		if(value.length >= 10){
			if($('#promo').val() != '' && $('#promo').val() != 1){
				var tabla = $('#promo').val();			
				var table = $('#'+tabla).children().children().children().next().children();
		    	$(table).each(function(){
		    		var row = $(this);
		    		row.css('display', 'none');
				    $(this).find('td').each(function(){
				    	if($(this).text().includes(value)){
				    		row.css('display', 'table-row');
				    	}
				    });
				});
				var a = document.getElementsByClassName('exportar');
				if(tabla == 2){
					var fecha = value.split("-");
					var sql = encodeURI("SELECT * FROM promo_triples WHERE fecha LIKE '%"+fecha[2]+"-"+fecha[1]+"-"+fecha[0]+"%'");
				}else if(tabla == 3){
					var fecha = value.split("-");
					var sql = encodeURI("SELECT * FROM promo_canastas WHERE fecha LIKE '%"+fecha[2]+"-"+fecha[1]+"-"+fecha[0]+"%'");
				}else if(tabla == 4){
					var fecha = value.split("-");
					var sql = encodeURI("SELECT * FROM promo_toques WHERE fecha LIKE '%"+fecha[2]+"-"+fecha[1]+"-"+fecha[0]+"%'");
				}
				for (const element of a) {
			        var res = element.href.split("/");
			        element.href = res[0]+"/"+res[1]+"/"+res[2]+"/"+res[3]+"/"+res[4]+"/"+sql;
			    }
		    }
		}else{
			if($('#promo').val() == ''){
				var tabla = 1;
			}else{
				var tabla = $('#promo').val();
			}
			var table = $('#'+tabla).children().children().children().next().children();
	    	$(table).each(function(){
	    		var row = $(this);
	    		row.css('display', 'table-row');
	    	});
	    	var a = document.getElementsByClassName('exportar');
	    	if(tabla == 2){
				var sql = "SELECT * FROM promo_triples WHERE";
			}else if(tabla == 3){
				var sql = "SELECT * FROM promo_canastas WHERE";
			}else if(tabla == 4){
				var sql = "SELECT * FROM promo_toques WHERE";
			} 
			for (const element of a) {
		        var res = element.href.split("/");
		        element.href = res[0]+"/"+res[1]+"/"+res[2]+"/"+res[3]+"/"+res[4]+"/"+sql;
		    }
		}
      });
  });
</script>
<script type="text/javascript">
	$('a').on('click', function(e){
		e.stopImmediatePropagation();
		localStorage.setItem("scrollTop", $(window).scrollTop());
	});

	$('#promo').on('change', function(){
		$('.tabla').css('display', 'none');
		if($('#promo').val() == ''){
			$('#1').css('display', 'block');
			var tabla = 1;
		}else{
			$('#'+$(this).val()).css('display', 'block');
			var tabla = $('#promo').val();
		}
		var a = document.getElementsByClassName('exportar');
    	if(tabla == 1){
			var sql = "SELECT * FROM aio_clientes_promo";
		}else if(tabla == 2){
			var sql = "SELECT * FROM promo_triples";
		}else if(tabla == 3){
			var sql = "SELECT * FROM promo_canastas";
		}else if(tabla == 4){
			var sql = "SELECT * FROM promo_toques";
		} 
		for (const element of a) {
	        var res = element.href.split("/");
	        element.href = res[0]+"/"+res[1]+"/"+res[2]+"/"+res[3]+"/"+res[4]+"/"+sql;
	    }
	});

	$('#nombre').on('input',function(e){
		var value = $(this).val();
		console.log(value);
		if(value.length >= 3){
			if($('#promo').val() == ''){
				var tabla = 1;
			}else{
				var tabla = $('#promo').val();
			}
			var table = $('#'+tabla).children().children().children().next().children();
	    	$(table).each(function(){
	    		var row = $(this);
	    		row.css('display', 'none');
			    $(this).find('td').each(function(){
			    	if($(this).text().toUpperCase().includes(value.toUpperCase())){
			    		row.css('display', 'table-row');
			    	}
			    });
			});
			var a = document.getElementsByClassName('exportar');
			if(tabla == 1){
				var sql = encodeURI("SELECT * FROM aio_clientes_promo WHERE salon LIKE '%"+value+"%' OR telefono LIKE '%"+value+"%' OR nombre LIKE '%"+value+"%' OR email LIKE '%"+value+"%'");
			}else if(tabla == 2){
				var sql = encodeURI("SELECT * FROM promo_triples WHERE nombre LIKE '%"+value+"%' OR email LIKE '%"+value+"%'");
			}else if(tabla == 3){
				var sql = encodeURI("SELECT * FROM promo_canastas WHERE nombre LIKE '%"+value+"%' OR email LIKE '%"+value+"%'");
			}else if(tabla == 4){
				var sql = encodeURI("SELECT * FROM promo_toques WHERE nombre LIKE '%"+value+"%' OR email LIKE '%"+value+"%'");
			} 
			for (const element of a) {
		        var res = element.href.split("/");
		        element.href = res[0]+"/"+res[1]+"/"+res[2]+"/"+res[3]+"/"+res[4]+"/"+sql;
		    }
		}else{
			if($('#promo').val() == ''){
				var tabla = 1;
			}else{
				var tabla = $('#promo').val();
			}
			var table = $('#'+tabla).children().children().children().next().children();
	    	$(table).each(function(){
	    		var row = $(this);
	    		row.css('display', 'table-row');
	    	});
	    	var a = document.getElementsByClassName('exportar');
	    	if(tabla == 1){
				var sql = "SELECT * FROM aio_clientes_promo";
			}else if(tabla == 2){
				var sql = "SELECT * FROM promo_triples WHERE";
			}else if(tabla == 3){
				var sql = "SELECT * FROM promo_canastas WHERE";
			}else if(tabla == 4){
				var sql = "SELECT * FROM promo_toques WHERE";
			}
			for (const element of a) {
		        var res = element.href.split("/");
		        element.href = res[0]+"/"+res[1]+"/"+res[2]+"/"+res[3]+"/"+res[4]+"/"+sql;
		    }
		}
	});
</script>
</html>
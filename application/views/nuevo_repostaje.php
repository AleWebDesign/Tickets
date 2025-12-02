<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<div id="myApp" ng-app="myApp" ng-cloak data-ng-element-ready="">
		<div id="controlador" ng-controller="dash" style="overflow-y:auto" md-theme="custom" data-ng-init="onloadFun()">
			<md-tabs md-dynamic-height md-border-bottom md-selected="selected_tab_index" style="display: none">
		        <md-tab label="Devices">
		            <md-content ng-if="tpl.devices.length">
		                <md-list class="md-dense" flex>
		                    <md-list-item class="md-2-line" ng-repeat="device in tpl.devices">
		                        <div class="md-list-item-text">
		                            <h3>{{device.alias}}</h3>

		                            <p>{{device.deviceModel}} - {{device.deviceName}}</p>
		                        </div>
		                        <md-switch ng-change="tpl_setState($index,device.is_powered)"
		                                   ng-model="device.is_powered"></md-switch>
		                        <md-divider/>
		                    </md-list-item>
		                </md-list>
		            </md-content>
		            <md-content ng-if="!tpl.devices.length" class="md-padding">
		                <h3>Warning</h3>
		                Por favor, debes conectarte a la WIFI ADM y recargar para poder repostar.</h3>
		            </md-content>
		        </md-tab>
			</md-tabs>
			<div class="container-fluid" style="margin-bottom: 2%">
				<div id="div1" class="col-md-12" style="border-bottom: 1px solid #ccc; padding-bottom: 3%">
					<h3 style="font-size: 20px;">
						<a class="volver_button" href="<?php echo base_url('gasoil'); ?>" style="text-decoration: underline; color: #c10d3e;">
							<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Gasoil</a>
						<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
						<a href="<?php echo base_url('nuevo_repostaje'); ?>" style="color: #000; text-decoration: none">Registrar Repostaje</a>
					</h3>
					<hr/>
					<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
					<?php echo validation_errors(); ?>
					<?php echo form_open_multipart('nuevo_repostaje_form', 'id="myform"'); ?>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Vehiculo</label>
						<div class="input-group">
						  <select class="js-example-basic-single" id="vehiculo" name="vehiculo" required="">
						  	<?php echo $select_vehiculos; ?>
						  </select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Kilometros</label>
						<div class="input-group">
						  <input id="km" type="text" class="form-control" name="km" maxlength="7" inputmode="numeric" required>
						  <span id="km_required" style="color: red; display: none">Este campo es obligatorio</span>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Litros</label>
						<div class="input-group">
						  <input id="litros" type="text" class="form-control" name="litros" maxlength="6" inputmode="numeric" required>
						  <span id="litros_required" style="color: red; display: none">Este campo es obligatorio</span>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<div id="boton_aceptar_buscador_gasoil" class="btn-group">
							<input type="button" id="submit_button" value="Aceptar" class="btn btn-danger dropdown-toggle">
						</div>
					</div>
					</form>
				</div>
				<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
					<a href="<?php echo base_url('gasoil'); ?>" class="btn btn-warning dropdown-toggle volver_button">
						Volver
					</a>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
		$("#km").keydown(function (e) {
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
    	$("#litros").keydown(function (e) {
	        // Allow: backspace, delete, tab, escape, enter and .
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
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
</script>
<script type="text/javascript">
	$(document).ready(function() {
    	$('.js-example-basic-single').select2();
	});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-cookies.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('files/js/script_tplink.js'); ?>"></script>
<script type="text/javascript">		
	$('#submit_button').on('click', function(){
		if($('#litros').val() === ''){
			$('#litros_required').css('display', 'block');
		}else if($('#km').val() === ''){
			$('#km_required').css('display', 'block');
			$('#litros_required').css('display', 'none');
		}else{
			$('#litros_required').css('display', 'none');
			$('#km_required').css('display', 'none');
			$('#submit_button').attr('disabled', 'disabled');
			setInterval(
				function(){
					angular.element(document.getElementById('controlador')).scope().stateOff();
					if(!angular.element(document.getElementById('controlador')).scope().tpl.devices['1'].is_powered){
						$('#myform').submit();
					}
				}
			,1000);	
		}
	});
	
	$('.volver_button').click(function(event){
	    event.preventDefault();
	    setInterval(
			function(){ 
				angular.element(document.getElementById('controlador')).scope().stateOff();
				if(!angular.element(document.getElementById('controlador')).scope().tpl.devices['1'].is_powered){
					window.location = $('.volver_button').attr('href');
				}
			}
		,1000);
	});
</script>
</html>
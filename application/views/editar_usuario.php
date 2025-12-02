<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('usuarios/1'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Usuarios</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<a href="<?php echo base_url('editar_usuario/'.$id.''); ?>" style="color: #000; text-decoration: none">Editar</a>
			</h3>
			<hr/>
			<?php if(isset($error_login)){ echo "<p style='font-weight: bold'>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('editar_usuario_form/'); ?>
			<div class="panel panel-default col-md-12 col-sm-12 paneles_form">
				<div class="panel-heading" style="cursor: pointer">
					Datos
				</div>
				<div class="panel-body">
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Nombre</label>
						<div class="input-group">
							<input class="form-control" type="text" id="nombre" name="nombre" <?php echo "value='".$usuario->nombre."'"; ?> required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>E-mail</label>
						<div class="input-group">
							<input class="form-control" type="email" id="email" name="email" <?php echo "value='".$usuario->email."'"; ?> required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Teléfono</label>
						<div class="input-group">
							<input class="form-control" type="text" id="telefono" name="telefono" <?php echo "value='".$usuario->telefono."'"; ?>>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Usuario</label>
						<div class="input-group">
							<input class="form-control" type="text" id="usuario" name="usuario" <?php echo "value='".$usuario->usuario."'"; ?> required>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs serie1">
						<label>Contraseña</label>
						<div class="input-group">
							<input class="form-control" type="password" id="pass" name="pass">
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Rol</label>
						<div class="input-group">
						  <select class="form-control" id="rol" name="rol" required>
						  	<option value="">Rol...</option>
						  	<?php echo $html_roles; ?>
							</select>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 inputs">
						<label>Acceso</label>
						<div class="input-group">
						  <select class="form-control" id="acceso" name="acceso" required>
						  	<option value="">Acceso...</option>
						  	<?php echo $html_acceso; ?>
							</select>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 inputs">
						<div class="input-group" style="margin: 15px 0;">
							<input type="checkbox" id="activo" name="activo" <?php if($usuario->activo == 1){ echo "checked"; } ?>>
			    			<label style="margin-left: 5px;">Activo</label>
			    		</div>						
						<div class="input-group" style="margin: 15px 0;">
							<input type="checkbox" id="jornada" name="jornada" <?php if($usuario->jornada == 1){ echo "checked"; } ?>>
			    			<label style="margin-left: 5px;">Activar jornada automática</label>
			    		</div>
			    	</div>
			    	<div class="col-md-12 col-sm-12 inputs">
			    		<p style="font-weight: bold">Horario</p>
			    		<?php $array = explode(",", $usuario->jornada_dias); ?>
						<div class="input-group" style="margin: 15px 0;">
							<input type="checkbox" class="dias_checkbox" id="dia1" name="lunes" <?php for ($i = 0; $i < count($array); $i++) { if($array[$i] == 1){ echo "checked"; } }?>>
			    			<label style="margin-left: 5px;">Lunes</label>
			    			<input type="checkbox" class="dias_checkbox" id="dia2" name="martes" style="margin-left: 20px;" <?php for ($i = 0; $i < count($array); $i++) { if($array[$i] == 2){ echo "checked"; } }?>>
			    			<label style="margin-left: 5px;">Martes</label>
			    			<input type="checkbox" class="dias_checkbox" id="dia3" name="miercoles" style="margin-left: 20px;" <?php for ($i = 0; $i < count($array); $i++) { if($array[$i] == 3){ echo "checked"; } }?>>
			    			<label style="margin-left: 5px;">Miércoles</label>
			    			<input type="checkbox" class="dias_checkbox" id="dia4" name="jueves" style="margin-left: 20px;" <?php for ($i = 0; $i < count($array); $i++) { if($array[$i] == 4){ echo "checked"; } }?>>
			    			<label style="margin-left: 5px;">Jueves</label>
			    			<input type="checkbox" class="dias_checkbox" id="dia5" name="viernes" style="margin-left: 20px;" <?php for ($i = 0; $i < count($array); $i++) { if($array[$i] == 5){ echo "checked"; } }?>>
			    			<label style="margin-left: 5px;">Viernes</label>
			    			<input type="checkbox" class="dias_checkbox" id="dia6" name="sabado" style="margin-left: 20px;" <?php for ($i = 0; $i < count($array); $i++) { if($array[$i] == 6){ echo "checked"; } }?>>
			    			<label style="margin-left: 5px;">Sábado</label>
			    			<input type="checkbox" class="dias_checkbox" id="dia0" name="domingo" style="margin-left: 20px;" <?php for ($i = 0; $i < count($array); $i++) { if($array[$i] == 7){ echo "checked"; } }?>>
			    			<label style="margin-left: 5px;">Domingo</label>
			    		</div>
					</div>
					<div class="col-md-12 col-sm-12" style="margin-top: 10px">
						<p style="font-weight: bold">Mañana</p>
					</div>
					<div class="col-md-4 col-sm-12">										
						<select class="form-control" id="hora_inicio_jornada_mañana" name="hora_inicio_jornada_mañana">
							<option value="">DESDE</option>											
						<?php
						for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
					    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
					    	if(str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT) == $usuario->hora_inicio_jornada_mañana){
					        	echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'"selected>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
					        }else{
					        	echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'">'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
					        }					    
						?>
						</select>
					</div>
					<div class="col-md-4 col-sm-12">
						<select class="form-control" id="hora_fin_jornada_mañana" name="hora_fin_jornada_mañana">
							<option value="">HASTA</option>
						<?php
						for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
					    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
					    	if(str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT) == $usuario->hora_fin_jornada_mañana){
					        	echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'" selected>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
					        }else{
					        	echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'">'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
					        }					        
						?>
						</select>
					</div>
					<div class="col-md-12 col-sm-12" style="margin-top: 10px">
						<p style="font-weight: bold">Tarde</p>
					</div>
					<div class="col-md-4 col-sm-12 div_horarios">										
						<select class="form-control" id="hora_inicio_jornada_tarde" name="hora_inicio_jornada_tarde">
							<option value="">DESDE</option>										
						<?php
						for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
					    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
					        if(str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT) == $usuario->hora_inicio_jornada_tarde){
					        	echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'" selected>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
					        }else{
					        	echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'">'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
					        }
						?>
						</select>
					</div>
					<div class="col-md-4 col-sm-12">
						<select class="form-control" id="hora_fin_jornada_tarde" name="hora_fin_jornada_tarde">
							<option value="">HASTA</option>
						<?php
						for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
					    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
					        if(str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT) == $usuario->hora_fin_jornada_tarde){
					        	echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'" selected>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
					        }else{
					        	echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'">'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
					        }
						?>
						</select>
					</div>
					<div class="col-md-12 col-sm-12">						
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<div class="btn-group" style="margin: 0 0 20px 0; float: right;">
							<button type="submit" class="btn btn-danger dropdown-toggle">
								Aceptar 
							</button> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('usuarios/1'); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
  </div>
  <script type="text/javascript" src="<?php echo base_url('files/js/script_crear_usuarios.js'); ?>"></script>
</body>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title; ?></title>
	<link rel="icon" href="<?php echo base_url('files/img/favicon.ico?v=1'); ?>" type="image/x-icon" />
	<!-- jQuery -->
	<script src="<?php echo base_url('files/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('files/js/ui/jquery-ui.min.js'); ?>"></script>
	<!-- BOOTSTRAP CSS -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/bootstrap.min.css'); ?>" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo base_url('files/css/bootstrap-theme.min.css'); ?>" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- CSS -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/style.css'); ?>">
	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="<?php echo base_url('files/css/font-awesome/css/font-awesome.min.css'); ?>">
	<!-- BOOTSTRAP JS -->
	<script type="text/javascript" src="<?php echo base_url('files/js/bootstrap.min.js'); ?>" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
	<body>
		<div class="container">
			<div class="row" style="margin-top: 80px;">
				<div class="col-md-12">
					<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
					<?php echo validation_errors(); ?>
					<?php echo form_open_multipart('cuenta_form/'); ?>
					<div class="panel panel-danger login-panel">
						<div class="panel-heading">
							Datos de usuario
						</div>
						<div class="panel-body" style="padding: 15px;">
							<?php if(isset($cambios)){ echo "<p>".$cambios."</p>"; } ?>
							<div class="col-md-6 col-sm-12 inputs">
								<label>Nombre</label>
								<div class="input-group">
								  <input class="form-control" type="text" id="nombre" name="nombre" value="<?php echo $user->nombre; ?>">
								</div>
								<label style="margin-top: 5%">Usuario</label>
								<div class="input-group">
								  <input class="form-control" type="text" id="user" name="user" value="<?php echo $user->usuario; ?>" disabled>
								</div>
							</div>
							<div class="col-md-6 col-sm-12 inputs">
								<label>Email</label>
								<div class="input-group">
								  <input class="form-control" type="email" id="email" name="email" value="<?php echo $user->email; ?>">
								</div>
								<label style="margin-top: 5%">Contraseña</label>
								<div class="input-group">
								  <input class="form-control" type="password" id="pass" name="pass">
								</div>
							</div>
							<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
							<div class="col-md-12">
								<?php if($this->session->userdata('logged_in')['rol'] == 2){ ?>
								<div class="input-group" style="margin-top: 15px;">
									<input type="checkbox" name="emails" <?php if($op->Emails == 1){ echo "checked"; } ?>>
									<label style="margin-left: 5px;">Recibir emails incidencias</label>
						    	</div>
						    	<?php } ?>
						    </div>
						    <div class="col-md-12" style="border: 1px solid #ddd; border-radius: 5px; margin: 10px 0;">							
								<div class="input-group" style="margin: 15px 0;">
									<input type="checkbox" id="noti" name="noti" <?php if($user->notificaciones == 1){ echo "checked"; } ?>>
									<label style="margin-left: 5px;">Recibir notificaciones</label>
					    		</div>
						    	<div class="col-md-6 col-sm-12" style="margin-bottom: 15px">										
									<select class="form-control" id="hora_inicio" name="hora_inicio" <?php if($user->notificaciones == 0){ echo "disabled"; } ?>>
										<option value="">DESDE</option>											
									<?php
									if(isset($user->hora_inicio) && $user->hora_inicio != ''){
										echo '<option value="'.$user->hora_inicio.'" selected>'.$user->hora_inicio.'</option>';
									}
									for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
								    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
								        echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'">'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
									?>
									</select>
								</div>
								<div class="col-md-6 col-sm-12" style="margin-bottom: 15px">
									<select class="form-control" id="hora_fin" name="hora_fin" <?php if($user->notificaciones == 0){ echo "disabled"; } ?>>
										<option value="">HASTA</option>
									<?php
									if(isset($user->hora_fin) && $user->hora_fin != ''){
										echo '<option value="'.$user->hora_fin.'" selected>'.$user->hora_fin.'</option>';
									}
									for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
								    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
								        echo '<option value="'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'">'.str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
									?>
									</select>
						    	</div>
						    </div>
					    	<?php } ?>
					    	<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4 || $this->session->userdata('logged_in')['rol'] == 6 || $this->session->userdata('logged_in')['rol'] == 7){ ?>
					    	<div class="col-md-12" style="border: 1px solid #ddd; border-radius: 5px; margin: 10px 0;">						
								<div class="input-group" style="margin: 15px 0;">
									<input type="checkbox" id="jornada" name="jornada" <?php if($user->jornada == 1){ echo "checked"; } ?>>
					    			<label style="margin-left: 5px;">Activar jornada automática</label>
					    		</div>
					    			<?php
					    			$dias = '';
					    			$array = explode(',', $user->jornada_dias);
					    			for ($i = 0; $i < count($array); $i++){
					    				if($array[$i] == 1){ 
					    					$dias .= "L "; 
					    				}else if($array[$i] == 2){ 
					    					$dias .= "M "; 
					    				}else if($array[$i] == 3){ 
					    					$dias .= "X "; 
					    				}else if($array[$i] == 4){ 
					    					$dias .= "J "; 
					    				}else if($array[$i] == 5){ 
					    					$dias .= "V "; 
					    				}else if($array[$i] == 6){ 
					    					$dias .= "S "; 
					    				}else if($array[$i] == 7){ 
					    					$dias .= "D "; 
					    				}
					    			}
					    			if(isset($user->hora_inicio_jornada_mañana) && $user->hora_inicio_jornada_mañana != '' && isset($user->hora_fin_jornada_mañana) && $user->hora_fin_jornada_mañana != ''){ 
						    			echo "<p style='font-style: italic'>Tu jornada: ".$dias; 
						    			echo $user->hora_inicio_jornada_mañana."-".$user->hora_fin_jornada_mañana." ";
					    			} 
					    			if(isset($user->hora_inicio_jornada_tarde) && $user->hora_inicio_jornada_tarde != '' && isset($user->hora_fin_jornada_tarde) && $user->hora_fin_jornada_tarde != ''){ 
					    				echo $user->hora_inicio_jornada_tarde."-".$user->hora_fin_jornada_tarde; 
					    			} 
					    			?>					    		
						    </div>
					    	<?php } ?>
							<div class="btn-group pull-right" style="margin: 2% 0;">
								<button type="submit" class="btn btn-danger dropdown-toggle">
									Guardar cambios
								</button> 
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</body>
	<script type="text/javascript">
		$('#noti').on('click', function(){
			if($(this).is(":checked")){
				$('#hora_inicio,#hora_fin').prop('disabled', false);
			}else{
				$('#hora_inicio,#hora_fin').prop('disabled', true);
			}
		});

		$('#jornada').on('click', function(){
			if($(this).is(":checked")){
				$('#hora_inicio_jornada,#hora_fin_jornada,#jornada_partida').prop('disabled', false);
			}else{
				$('#hora_inicio_jornada,#hora_fin_jornada,#jornada_partida').prop('disabled', true);
			}
		});
	</script>
</html>
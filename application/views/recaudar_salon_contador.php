<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;"><a href="<?php echo base_url('recaudar_salon/'.$salon->id); ?>" style="color: #000; text-decoration: none">Recaudar salon <?php echo $salon->salon; ?></a></h3>
			<hr>
			<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('recaudar_salon_contador_form/', 'id="myform"'); ?>
	  	
		  	<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
		  		<h4 style="margin: 10px 0 20px 10px;">Máquinas</h4>
		  		<div style="width: 100%; float: left;">
		  			<?php echo $html_maquinas; ?>
		  		</div>
		  		<div style="width: 100%; float: left;">
		  			<div style="padding: 14px 0; text-align: center; width: 50%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: left; margin-left: 10px">Total máquinas</p>
					</div>
		  			<div style="padding: 14px 0; width: 50%; float: left; text-align: center; text-align: right; padding-right: 10px;">
						<input type="text" name="bruto" style="width: 80%; text-align: right; font-weight: bold;" value="<?php echo $total_maquinas; ?>"> €
					</div>
		  		</div>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
		  		<h4 style="margin: 10px 0 20px 10px;">Pagos</h4>
		  		<div style="width: 100%; float: left;">
		  			<?php echo $html_pagos; ?>
		  		</div>
		  		<div style="width: 100%; float: left;">
		  			<div style="padding: 14px 0; text-align: center; width: 50%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: left; margin-left: 10px">Total pagos</p>
					</div>
		  			<div style="padding: 14px 0; width: 50%; float: left; text-align: center; text-align: right; padding-right: 10px;">
						<input type="text" name="pagos" style="width: 80%; text-align: right; font-weight: bold;" value="<?php echo $total_pagos; ?>"> €
					</div>
		  		</div>
		  	</div>

			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
		  		<h4 style="margin: 10px 0 20px 10px;">Datáfono</h4>
		  		<div style="width: 100%; float: left; border-top: 1px solid #000">
		  			<div style="padding: 14px 0; text-align: center; width: 50%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: left; margin-left: 10px">Datáfono</p>
					</div>
					<div style="padding: 14px 0; width: 50%; float: left; text-align: center; text-align: right; padding-right: 10px;">
						<input type="text" name="datafono" style="width: 80%; text-align: right; font-weight: bold;" value="<?php echo $datafono; ?>"> €
					</div>
		  		</div>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
		  		<h4 style="margin: 10px 0 20px 10px;">Total</h4>
		  		<div style="width: 100%; float: left; border-top: 1px solid #000">
		  			<div style="padding: 14px 0; text-align: center; width: 50%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: left; margin-left: 10px">Total neto</p>
					</div>
					<div style="padding: 14px 0; width: 50%; float: left; text-align: center; text-align: right; padding-right: 10px;">
						<input type="text" name="neto" style="width: 80%; text-align: right; font-weight: bold;" value="<?php echo $total; ?>"> €
					</div>
		  		</div>
			</div>
				
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0">
		  		<p>&nbsp;</p>
		  	</div>
				
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
				<h4 style="margin: 10px 0 20px 10px;">Comentarios</h4>
		  		<textarea class="form-control" name="comentarios" rows="6" placeholder="Comentarios..." style="margin: 2%; width: 96%;"></textarea>
		  	</div>
					
		  	<div class="col-md-6 col-sm-12" style="float: left; width: 100%; padding: 0;">
					<div class="btn-group pull-right" style="margin: 20px 0;">
						<input type="hidden" name="salon" value="<?php echo $salon->id; ?>">
						<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
							Aceptar 
						</button> 
					</div>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_recaudar_salon.js'); ?>"></script>
</html>
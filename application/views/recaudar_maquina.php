<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
			<a href="<?php echo base_url('recaudar/'.$maquina->salon.''); ?>" style="color: #c10d3e;">
				<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i>
			</a>
			Recaudar máquina <?php echo $maquina->maquina; ?></h3>
			<hr>
			<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('recaudar_maquina_form/', 'id="myform"'); ?>
			<div class="col-md-5 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000">
					<p style="font-weight: bold; text-align: center; margin: 0">TEORICO</p>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: center; margin: 0">MONETICA</p>
					</div>
					<div style="width: 33%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: center; margin: 0">UND</p>
					</div>
					<div style="width: 33%; float: left;">
						<p style="font-weight: bold; text-align: center; margin: 0">TOTAL</p>
					</div>
				</div>				
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">H. 1€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_h_u_1" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_h_t_1" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">H. 2€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_h_u_2" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_h_t_2" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">H. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_h_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. 5€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_u_5" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_t_5" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. 10€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_u_10" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_t_10" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. 20€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_u_20" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_t_20" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. 50€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_u_50" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_t_50" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_b_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">C. 1€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_c_u_1" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_c_t_1" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">C. 2€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_c_u_2" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_c_t_2" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">C. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_c_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">R. 20€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="t_r_u_20" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_r_t_20" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">R. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_r_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #ddd; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Reca. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="t_reca_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
	  	</div>
	  	
	  	<div class="col-md-2 col-sm-12 col-xs-12" style="padding: 0">
	  		<p>&nbsp;</p>
	  	</div>
	  	
	  	<div class="col-md-5 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
	  		
				<div style="col-md-12; background: #eee; border-bottom: 1px solid #000">
					<p style="font-weight: bold; text-align: center; margin: 0">REAL</p>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: center; margin: 0">MONETICA</p>
					</div>
					<div style="width: 33%; float: left; border-right: 1px solid #000">
						<p style="font-weight: bold; text-align: center; margin: 0">UND</p>
					</div>
					<div style="width: 33%; float: left;">
						<p style="font-weight: bold; text-align: center; margin: 0">TOTAL</p>
					</div>
				</div>

				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">H. 1€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_h_u_1" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_h_t_1" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">H. 2€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_h_u_2" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_h_t_2" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">H. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_h_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. 5€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_u_5" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_t_5" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. 10€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_u_10" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_t_10" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. 20€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_u_20" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_t_20" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. 50€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_u_50" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_t_50" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">B. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_b_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">C. 1€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_c_u_1" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_c_t_1" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">C. 2€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_c_u_2" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_c_t_2" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">C. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_c_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">R. 20€ x</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; border-right: 1px solid #000; text-align: center; padding-top: 2px;">
						<input type="text" name="r_r_u_20" inputmode="numeric" style="width: 50%">
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_r_t_20" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #eee; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">R. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_r_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; background: #ddd; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Reca. Total</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="r_reca_t" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
				<div style="width: 100%; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">Carga(-)</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="carga" inputmode="numeric" style="width: 50%" value="200">
					</div>
				</div>
				
				<div style="width: 100%; background: #aaa; border-bottom: 1px solid #000; float: left">
					<div style="width: 34%; height: 30px; float: left; border-right: 1px solid #000; padding-top: 4px;">
						<p style="font-weight: bold; text-align: center; margin: 0">NETO</p>
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						
					</div>
					<div style="width: 33%; height: 30px; float: left; text-align: center; padding-top: 2px;">
						<input type="text" name="neto" inputmode="numeric" style="width: 50%">
					</div>
				</div>
				
	  	</div>
	  	
	  	<div class="col-md-12 col-sm-12" style="float: left; width: 100%; padding: 0;">
				<div class="btn-group pull-right" style="margin: 20px 0; width: 50%;">
					<input type="hidden" name="maquina" value="<?php echo $maquina->id; ?>">
					<a style="margin-right: 15%;" href="<?php echo base_url('recaudar/'.$maquina->salon.''); ?>" class="btn btn-warning dropdown-toggle">
						Volver 
					</a> 
					<button id="submit_button" type="submit" class="btn btn-danger dropdown-toggle">
						Aceptar 
					</button> 
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_recaudar_maquina.js'); ?>"></script>
</html>
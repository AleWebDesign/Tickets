<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;"><a href="<?php echo base_url('recaudar_salon/'.$salon->id); ?>" style="color: #000; text-decoration: none">Recaudar salón <?php echo $salon->salon; ?></a></h3>
			<hr>
			<?php if($this->session->userdata('logged_in')['rol'] == 2 || $this->session->userdata('logged_in')['rol'] == 4){ ?>
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('recaudar_salon_form/', 'id="myform"'); ?>
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
				<div style="width: 100%; float: left; border-bottom: 1px solid #000;">
					<div style="width: 25%; float: left; border-right: 1px solid #000; text-align: center; padding: 20px 0;">
						<p style="font-size: 10px">PAGOS ATRASADOS</p>
					</div>
					<div style="width: 75%; float: left;">
						<div style="text-align: center; width: 34%; float: left; border-right: 1px solid #000; border-bottom: 1px solid #000;">
							<p style="font-size: 10px">Rec. Anterior</p>
						</div>
						<div style="text-align: center; width: 33%; float: left; border-right: 1px solid #000; border-bottom: 1px solid #000;">
							<p style="font-size: 10px">Pag. Anterior</p>
						</div>
						<div style="text-align: center; width: 33%; float: left; border-bottom: 1px solid #000;">
							<p style="font-size: 10px">Balance</p>
						</div>
						
						<div style="padding: 14px 0; text-align: center; width: 34%; float: left; border-right: 1px solid #000">
							<input type="text" name="reca_ant" inputmode="numeric" value="<?php if(isset($recaudacion->reca_ant)){ echo $recaudacion->reca_total; } ?>" style="width: 80%">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 33%; float: left; border-right: 1px solid #000">
							<input type="text" name="pago_ant" inputmode="numeric" value="<?php if(isset($recaudacion->pag_ant)){ echo $recaudacion->pag_ant; } ?>" style="width: 80%">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 33%; float: left">
							<input type="text" name="balance_ant" inputmode="numeric" value="<?php if(isset($recaudacion->bal_ant)){ echo $recaudacion->bal_ant; } ?>" style="width: 80%">
						</div>
					</div>
				</div>
				<div style="width: 100%; float: left;">
					<div style="width: 25%; float: left; border-right: 1px solid #000; text-align: center; padding: 20px 0;">
						<p style="font-size: 10px">PAGOS ACTUALES</p>
					</div>
					<div style="width: 75%; float: left;">
						<div style="text-align: center; width: 34%; float: left; border-right: 1px solid #000; border-bottom: 1px solid #000;">
							<p style="font-size: 10px">Pagos Cajero</p>
						</div>
						<div style="text-align: center; width: 33%; float: left; border-right: 1px solid #000; border-bottom: 1px solid #000;">
							<p style="font-size: 10px">Balance</p>
						</div>
						<div style="text-align: center; width: 33%; float: left; border-bottom: 1px solid #000;">
							<p style="font-size: 10px">Total</p>
						</div>
						
						<div style="padding: 14px 0; text-align: center; width: 34%; float: left; border-right: 1px solid #000">
							<input type="text" name="pagos_caj" inputmode="numeric" value="<?php if(isset($recaudacion->pag_caj)){ echo $recaudacion->pag_caj; } ?>" style="width: 80%">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 33%; float: left; border-right: 1px solid #000">
							<input type="text" name="balance" inputmode="numeric" value="<?php if(isset($recaudacion->bal)){ echo $recaudacion->bal; } ?>" style="width: 80%">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 33%; float: left">
							<input type="text" name="total" inputmode="numeric" value="<?php if(isset($recaudacion->total)){ echo $recaudacion->total; } ?>" style="width: 80%">
						</div>
					</div>
				</div>
	  		</div>
	  	
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0">
				<p>&nbsp;</p>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000">
				<div style="width: 100%; float: left; border-bottom: 1px solid #000;">
					<div style="width: 25%; float: left; border-right: 1px solid #000; text-align: center; padding: 20px 0;">
						<p style="font-size: 10px">Reca total</p>
					</div>
					<div style="width: 75%; float: left;">
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left;">
							<input type="text" name="reca_total" inputmode="numeric" style="width: 80%" value="<?php echo $total; ?>">
						</div>
					</div>
				</div>

				<div style="width: 100%; float: left; border-bottom: 1px solid #000;">
					<div style="width: 25%; float: left; border-right: 1px solid #000; text-align: center; padding: 177px 0;">
						<p style="font-size: 10px">Pagos</p>
					</div>
					<div style="width: 75%; float: left;">
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left">
							<span style="font-size: 10px">1€</span> <input type="text" name="pagos_1" inputmode="numeric" style="width: 80%" value="0">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left">
							<span style="font-size: 10px">2€</span> <input type="text" name="pagos_2" inputmode="numeric" style="width: 80%" value="0">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left">
							<span style="font-size: 10px">5€</span> <input type="text" name="pagos_5" inputmode="numeric" style="width: 80%" value="0">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left">
							<span style="font-size: 10px">10€</span> <input type="text" name="pagos_10" inputmode="numeric" style="width: 80%" value="0">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left">
							<span style="font-size: 10px">20€</span> <input type="text" name="pagos_20" inputmode="numeric" style="width: 80%" value="0">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left">
							<span style="font-size: 10px">50€</span> <input type="text" name="pagos_50" inputmode="numeric" style="width: 80%" value="0">
						</div>
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left;">
							<input type="text" name="pagos" inputmode="numeric" style="width: 80%" value="0">
						</div>
					</div>
				</div>

				<div style="width: 100%; float: left; border-bottom: 1px solid #000;">
					<div style="width: 25%; float: left; border-right: 1px solid #000; text-align: center; padding: 20px 0;">
						<p style="font-size: 10px">Reca neta</p>
					</div>
					<div style="width: 75%; float: left;">
						<div style="padding: 14px 0; text-align: center; width: 100%; float: left;">
							<input type="text" name="neto" inputmode="numeric" style="width: 80%" value="<?php echo $total; ?>">
						</div>
					</div>
				</div>
			</div>
				
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0">
				<p>&nbsp;</p>
			</div>
				
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; border: 1px solid #000"">
				<textarea class="form-control" name="comentarios" rows="6" placeholder="Comentarios..."></textarea>
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
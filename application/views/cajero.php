<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<style type="text/css">
		table tr th, table tr td{
			width: 0 !important;
		}

		@media (min-width: 770px) {
			#reca_aux{
				min-height: 600px;
			}
		}	
	</style>
	<div class="container-fluid cajero_movil">
		<div class="col-md-12 cajero_movil">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('cajeros'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Cajeros</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<?php echo $cajero; ?>
		</div>
		<?php if($this->session->userdata('logged_in')['id'] == 41 || $this->session->userdata('logged_in')['id'] == 352 || $this->session->userdata('logged_in')['id'] == 353){ ?>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
			<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
			<?php echo validation_errors(); ?>
			<?php echo form_open_multipart('borrar_tickets_cajero/'.$salon.'/'); ?>
			<input type="submit" value="Borrar tickets" name="borrar" class="btn btn-danger dropdown-toggle"> 
		</div>
		<?php } ?>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
			<a id="volver_button" href="<?php echo base_url('cajeros'); ?>" class="btn btn-warning dropdown-toggle">
				Volver
			</a> 
		</div>
	</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_cajeros.js'); ?>"></script>
<script type='text/javascript'>
	$('.expand').on('click', function(){
		if($(this).children().children().first().is(':visible')){
			$(this).children().children().first().css('display', 'none');
			$(this).children().children().first().next().css('display', 'inline');
		}else{
			$(this).children().children().first().next().css('display', 'none');
			$(this).children().children().first().css('display', 'inline');
		}
	});
</script>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

if($this->session->userdata('logged_in')['rol'] == 1){
	if(isset($_SERVER['HTTP_REFERER'])){
		$server = $_SERVER['HTTP_REFERER'];

		echo $server;

		$where = explode('/', $server);
		$page = end($where);
		if(strlen(trim($page)) == 0){
			$page = 'gestion';
		}else{
			if(trim($page) != "gestion"){
				$page = 'informes_tickets';
			}else{
				$page = 'gestion';
			}
		}
	}else{
		$page = 'gestion';
	}
}else{
	$page = 'gestion';
}

?>
<div class="container-fluid">
	<h3 style="font-size: 20px;">
		<?php
			$pagina = $page;
			if($this->uri->segment(4) != ""){
				$pagina .= "/".$this->uri->segment(4);
			}
			if($this->uri->segment(5) != ""){
				$pagina .= "/".$this->uri->segment(5);
			}
			if($this->uri->segment(6) != ""){
				$pagina .= "/".$this->uri->segment(6);
			}
		?>
		<a href="<?php echo base_url($pagina); ?>" style="text-decoration: underline; color: #c10d3e;">
			<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Incidencias</a>
		<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
		<a style="color: #000; text-decoration: none">Historial</a>
	</h3>
	<hr/>
	<div class="col-md-12 col-sm-12">
		<?php echo $html_historial; ?>
	</div>
	<?php if($this->session->userdata('logged_in')['rol'] == 1 && $ticket->situacion == 6){ ?>
	<?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
	<?php echo validation_errors(); ?>
	<?php echo form_open_multipart('editar_incidencia_form/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).''); ?>
	<div class="col-md-12 col-sm-12" style="padding: 0">
		<div class="panel panel-default col-md-12 col-sm-12 paneles_form" style="margin-bottom: 5px">
			<div class="col-md-12 col-sm-12 inputs">
				<label>Añadir nueva línea</label>
				<div class="input-group">
					<textarea class="form-control" id="trata_new_desc" name="trata_new_desc" rows="6" placeholder="Tratamiento dado a la incidencia..."></textarea>
				</div>
			</div>
		</div>		
		<div class="col-md-12 col-sm-12 btn-group" style="margin: 2% 0;">
			<input type="hidden" name="id_ticket" value="<?php if(isset($ticket->id)){ echo $ticket->id; } ?>">
			<input type="hidden" name="situacion" value="<?php if(isset($ticket->situacion)){ echo $ticket->situacion; } ?>">
			<button type="submit" class="btn btn-info dropdown-toggle" name="only_trata" style="float: right; border-radius: 4px;">
				Añadir línea
			</button>
		</div>
	</div>
	<?php } ?>
	<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
		<?php if(isset($page)){ if($page == 'guardias'){ ?>
		<a id="volver_button" href="#" onclick="window.open('', '_self', ''); window.close();" class="btn btn-warning dropdown-toggle">
			Volver
		</a>
		<?php }else{ if(is_numeric($page)){ $page = 'gestion'; } ?>
		<a id="volver_button" href="<?php echo base_url($pagina); ?>" class="btn btn-warning dropdown-toggle">
			Volver
		</a>
		<?php } }else{ ?>
		<a id="volver_button" href="<?php echo base_url($pagina); ?>" class="btn btn-warning dropdown-toggle">
			Volver
		</a>
		<?php } ?>
	</div>
</div>
</body>
</html>
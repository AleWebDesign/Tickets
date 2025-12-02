<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">
				<a href="<?php echo base_url('ruletas'); ?>" style="text-decoration: underline; color: #c10d3e;">
					<i style="color: #c10d3e;" class="fa fa-arrow-circle-left"></i> Ruletas</a>
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
				<?php echo $ruleta; ?>
		</div>
		<div class="col-md-12" style="text-align: center; margin-top: 10px; margin-bottom: 10px; float: left; width: 100%;">
				<a id="volver_button" href="<?php echo base_url('ruletas'); ?>" class="btn btn-warning dropdown-toggle">
					Volver
				</a> 
		</div>
	</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_ruletas.js'); ?>"></script>
<script type='text/javascript'>
	$('#resumen_alert').on('click', function(){
		if($('#resumen_content').is(':visible')){
			$('#resumen_content').css('display', 'none');
		}else{
			$('#resumen_content').css('display', 'block');
		}
	});
	$('#detalles_alert').on('click', function(){
		if($('#detalles_content').is(':visible')){
			$('#detalles_content').css('display', 'none');
		}else{
			$('#detalles_content').css('display', 'block');
		}
	});
	$('#avisos_alert').on('click', function(){
		if($('#avisos_content').is(':visible')){
			$('#avisos_content').css('display', 'none');
		}else{
			$('#avisos_content').css('display', 'block');
		}
	});
	$('.expand').on('click', function(){
		if($(this).next().is(':visible')){
			$(this).next().css('display', 'none');
			$(this).children().children().first().css('display', 'inline');
			$(this).children().children().first().next().css('display', 'none');
		}else{
			$(this).next().css('display', 'block');
			$(this).children().children().first().next().css('display', 'inline');
			$(this).children().children().first().css('display', 'none');
		}
	});
	$('.panel-heading').on('click', function(){
		if($(this).next().is(':visible')){
			$(this).next().css('display', 'none');
			$(this).children().first().css('display', 'none');
			$(this).children().first().next().css('display', 'block');
		}else{
			$(this).next().css('display', 'block');
			$(this).children().first().next().css('display', 'none');
			$(this).children().first().css('display', 'block');
		}
	});
</script>
</html>
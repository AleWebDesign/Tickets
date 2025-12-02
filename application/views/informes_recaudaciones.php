<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px"><a href="<?php echo base_url('informes_recaudaciones'); ?>" style="color: #000; text-decoration: none">Informes recaudaciones</a></h3>	
  	<hr/>
  	<div class="col-md-12" style="margin: 1% 0">
  		<?php echo $recaudaciones; ?>
  	</div>
  	<?php if(isset($recaudaciones_detalle)){ ?>
  	<div id="div_detalle" class="col-md-12" style="display: none; margin: 1% 0">
  		<?php echo $recaudaciones_detalle; ?>
  	</div>
  	<?php } ?>
  </div>
</body>
<script type="text/javascript">
	$('.ver_detalle').on('click', function(){
		id = $(this).attr('id');
		$('#div_detalle').css('display', 'block');
		$('table#tabla_'+id).css('display', 'block');
		$("table.tablas_detalle:not(#tabla_"+id+")").css('display', 'none');
	});
</script>
</html>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<a href="<?php echo base_url('nueva_zona'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nueva zona" title="Nueva zona" style="width: 80%" />
	</a>
	<div class="container-fluid">
  	<h3 style="font-size: 20px;"><a href="<?php echo base_url('zonas'); ?>" style="color: #000; text-decoration: none">Zonas</a></h3>
  	<hr/>
		<div class="col-md-12">
			<?php echo $tabla_zonas; ?>	
  	</div>
	</div>
</body>
<script type="text/javascript" src="<?php echo base_url('files/js/script_zonas.js'); ?>"></script>
</html>
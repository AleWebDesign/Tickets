<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">Recaudacion Finalizada</h3>
			<hr>
			<div class="alert alert-success" role="alert">
				<strong>Recaudacion finalizada con exito!</strong>
			</div>
			<p style="font-weight: bold">Recaudacion total: <?php echo $recaudar->neto; ?></p>
			<p style="font-weight: bold">A <?php echo date('d')." del ".date('m')." de ".date('Y'); ?></p>
		</div>
	</div>
</body>
</html>
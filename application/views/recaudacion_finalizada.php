<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<div class="container-fluid">
		<div class="col-md-12">
			<h3 style="font-size: 20px;">Recaudacion Finalizada</h3>
			<hr>
			<div class="alert alert-success" role="alert">
				<strong>Recaudación finalizada con éxito!</strong>
				Se ha enviado un correo a la operadora correspondiente y otro a Apuestas de Murcia con todos los detalles de la recaudación
				<a target="_blank" style="padding: 2px 4px; margin: 2px 0 0 0;" href="<?php echo base_url('files/pdf_recaudaciones/'.$recaudar->id.'.pdf'); ?>" type="button" class="btn btn-info" alt="Ver recaudación" title="Ver recaudación">
					<i style="font-size: 30px" class="fa fa-eye"></i>
					<span style="display: block; font-weight: bold; font-size: 10px">Ver Recaudación</span>
				</a>
			</div>
			<p style="font-weight: bold">Recaudación total: <?php echo $recaudar->reca_total; ?> €</p>
			<p style="font-weight: bold">PAGOS: <?php echo $recaudar->pagos; ?> € (tpv o cajero)</p>
			<p style="font-weight: bold">REC. NETA: <?php echo $recaudar->neto; ?> € (Salida efectivo a central)</p>
			<p style="font-weight: bold">A <?php echo date('d')." del ".date('m')." de ".date('Y'); ?></p>
			<div class="col-md-6">
				<img src="<?php echo base_url('files/img/firmas/'.$recaudar->firma_recaudador.''); ?>" alt="imagen" title="imagen">
				<p>El recaudador</p>
			</div>
			<div class="col-md-6">
				<img src="<?php echo base_url('files/img/firmas/'.$recaudar->firma_responsable.''); ?>" alt="imagen" title="imagen">
				<p>Responsable local</p>
			</div>
		</div>
	</div>
</body>
</html>
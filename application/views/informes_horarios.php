<?php defined('BASEPATH') OR exit('No direct script access allowed'); setlocale(LC_ALL, 'es_ES'); ?>
	<div class="container-fluid">
		<h3 style="font-size: 20px"><a href="<?php echo base_url('informes_horarios'); ?>" style="color: #000; text-decoration: none">Informes horarios</a></h3>
        <div style="position: relative; height: 45px; padding-top: 3px; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; cursor: pointer;<?php if(isset($estilo)){ echo $estilo; } ?>">
            <h4 style="margin-bottom: 30px">
                Filtros<span style="float: right; margin-right: 2%" class="glyphicon glyphicon-triangle-bottom"></span>
            </h4>           
        </div>
        <div class="col-md-12" style="margin-bottom: 15px;">
            <?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
            <?php echo validation_errors(); ?>
            <?php echo form_open_multipart('buscador_horarios'); ?>
            <div class="col-md-3 col-sm-12" style="margin: 20px 0 30px 0;">
                <div class="col-md-12 col-sm-12">
                    <label>Usuario</label>
                    <div class="input-group" style="padding-top: 2%">
                        <select class="form-control" id="usuario" name="usuario">
                            <option value="0">TODOS</option>
                            <?php echo $usuarios_select; ?>                          
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12" style="margin: 20px 0 30px 0;">
                <div class="col-md-12 col-sm-12">
                    <label>Fecha Inicio</label>
                    <div class="input-group" style="padding-top: 2%">
                        <span class="input-group-addon" id="basic-addon1">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input id="datepicker1" type="text" name="fecha_inicio" class="form-control" placeholder="Desde" value="<?php if(isset($fecha_inicio)){ $fechaI = explode("-", $fecha_inicio); echo $fechaI[2]."/".$fechaI[1]."/".$fechaI[0]; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12" style="margin: 20px 0 30px 0;">
                <div class="col-md-12 col-sm-12">
                    <label>Fecha Fin</label>
                    <div class="input-group" style="padding-top: 2%">
                        <span class="input-group-addon" id="basic-addon1">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input id="datepicker2" type="text" name="fecha_fin" class="form-control" placeholder="Hasta" value="<?php if(isset($fecha_fin)){ $fechaF = explode("-", $fecha_fin); echo $fechaF[2]."/".$fechaF[1]."/".$fechaF[0]; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12" style="margin: 50px 0 30px 0;">
                <div class="col-md-12 col-sm-12">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-danger dropdown-toggle">
                            Aceptar 
                        </button> 
                    </div>
                </div>
            </div>
            </form>
        </div>
        <hr/>
        <?php if(isset($error_login)){ echo "<p>".$error_login."</p>"; } ?>
        <?php echo validation_errors(); ?>
        <?php echo form_open_multipart('horarios_excel'); ?>
        <input type="hidden" name="usuario" value="<?php if(isset($usuario)){ echo $usuario; }else{ echo "0"; } ?>">
        <input type="hidden" name="fecha_inicio" value="<?php if(isset($fecha_inicio) && $fecha_inicio != ''){ echo $fecha_inicio; }else{ echo date('Y-m-d', strtotime('-10 day', strtotime(date('Y-m-d')))); } ?>">
        <input type="hidden" name="fecha_fin" value="<?php if(isset($fecha_fin) && $fecha_fin != ''){ echo $fecha_fin; }else{ echo date("Y-m-d"); } ?>">
        <button style="float: right; margin: 0 20px 10px 0;" type="submit" class="btn btn-info">Exportar Excel</button>
        <div class="col-md-12" style="margin-bottom: 5%;">
            <?php echo $html_horarios; ?>
        </div>
	</div>
</body>
<script type="text/javascript">
  $(function(){
      $('#datepicker1,#datepicker2').datetimepicker({ format: 'DD/MM/YYYY', locale: 'es' });
  });
</script>
</html>
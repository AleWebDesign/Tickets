<?php include('includes/header.php');

$hoy = date("d/m/Y");

$id = $_GET['id_cliente'];

?>

<div id="content">
  <div class="row-title">
    <div class="container">
      <h2>Clientes</h2>
      <div class="breadcrumb"> <span><a href="#">Clientes</a></span> <i class="fa fa-angle-right"></i> Nuevo Cliente </div>
    </div>
  </div>

  <div class="container content-div">
	
		<h3>CONSENTIMIENTO INFORMADO</h3>
     
		<p align="justify">Con la intención de participar en el programa de actividad física del Centro de Entrenamiento Manuel Castro S.L, entiendo los procedimientos	aplicados por el citado centro y habiendo recibido información clara, precisa y adecuada manifiesto que he tenido la oportunidad de intercambiar opiniones acerca de mis necesidades específicas en relación a mi participación en el citado programa y como resultado de ello, acepto las condiciones expuestas para la participación en el mismo.</p>
		
		<p align="justify">El entrenamiento estará dirigido siempre por un equipo de profesionales que coordinaran el programa en cuya confección he participado activamente a través del cuestionario realizado. Con el fin de preservar una participación segura en la actividad física y deportiva, y en función de cada supuesto en particular tras el examen de las pruebas médicas aportadas y del resultado de la entrevista previa, asumo que sea el Estudio de Entrenamiento Personal Manuel Castro, quien decida si dentro del programa de entrenamiento se utilizará la máquina de electro estimulación MIHA-BODYTEC.</p>
		
		<p align="justify">Manifiesto igualmente conocer la existencia de los riesgos asociados a la práctica de actividad física, y estoy de acuerdo en aceptar las responsabilidades derivadas de mi participación así como del uso de las instalaciones y del equipamiento del Centro.</p>
		
		<p align="justify">Estoy de acuerdo con las recomendaciones, obligaciones y sugerencias arriba descritas en cuanto al cuidado de mi salud y las formas a seguir previas y durante la actividad deportiva y asumo y eximo de cualquier responsabilidad al Estudio de Entrenamiento MC y a sus empleados por las lesiones o accidentes que pudieran resultar en la realización de las actividades del programa de entrenamiento.</p>
		
		<p align="justify">Igualmente manifiesto conocer que los servicios que ofrece el Estudio de Entrenamiento Personal Manuel Castro de Fisioterapia y Nutrición son accesorios a este contrato y la responsabilidad que pudiera derivarse de la actividad de los citados profesionales es exclusivamente de los mismos sin que nada tenga que ver con el Estudio De Entrenamiento Personal ni con el presente contrato, debido a que se trata de profesionales autónomos cuyas actividades están supeditadas a las Normas Deontológicas de su Colegio Profesional ajeno al Centro de Entrenamiento Personal MC.</p>
		
		<h3>PROTECCION DE DATOS</h3>
		
		<p align="justify">Usted queda informado de:</p>
		<p align="justify">Que de acuerdo con lo establecido en la Ley Orgánica 15/1999, de 13 de diciembre, de Protección de Datos de Carácter Personal, los datos de carácter personal recabados por <strong>CENTRO DE ENTRENAMIENTO M. CASTRO, S. L.</strong> serán objeto de tratamiento en nuestros ficheros, los cuales han sido debidamente inscritos en el Registro General de Protección de Datos, y cuyo tratamiento tendrá como finalidad la realización del entrenamiento, dieta o servicio contratado, así como las tareas administrativas asociados al mismo.</p>
		<p align="justify">Que para la prestación de los servicios es imprescindible recabar ciertos datos de salud, cuyo tratamiento consiente el interesado o su representante legal mediante la firma de este documento. La negativa a facilitar estos datos supondrá la imposibilidad de llevar a cabo la finalidad expresada.</p>
		<p align="justify">Que usted podrá en cualquier momento ejercer el derecho de acceso, rectificación, cancelación y oposición en los términos establecidos en la Ley Orgánica 15/1999. El responsable del fichero es <strong>CENTRO DE ENTRENAMIENTO M. CASTRO, S. L.</strong> La dirección para el ejercicio de derechos es: Marques de los Vélez, 60 Bajo, 30007 Murcia.</p>
		<p align="justify">Que autorizo expresamente al <strong>CENTRO DE ENTRENAMIENTO M. CASTRO, S. L.</strong>, para que contacte conmigo a través de Whatsapp, sms, Instagram, twitter o Facebook.</p>
		<p align="justify">Que autoriza al personal del Centro de Entrenamiento M. Castro S.L para que realice fotos y videos durante sus sesiones de entrenamiento y publique las mismas en redes sociales con fines puramente deportivos y publicitarios e igualmente exhiba las imágenes en foros de alumnos con fines docentes preservando en todo caso su derecho a la intimidad y a la protección de su imagen pública de conformidad con dispuesto en la Ley Orgánica 1/1982, de 5 de mayo de Protección del Derecho al Honor, a la Intimidad Personal y Familiar y a la Propia Imagen.</p>
		
		<h3>ANEXO I</h3>
		<form method="post" action="firmar_tablet.php?id_cliente=<?php echo $id; ?>"> 
		<table class="tab_var1">
            <tbody><tr>
              <td class="tab_var1_pad">
                <div class="v_tables">
                  <table>
                    <tbody><tr>
                      <td>
                          Importe del Bono
                      </td>
                      <td>
                          Nº Sesiones
                      </td>
					  <td>
                          Duración de cada sesión
                      </td>
                      <td>
                          Marca (X) la opción elegida 
                      </td>
                      
                    </tr>
					<tr>
                      <td>
                          175,00 €
                      </td>
                      <td>
                          5
                      </td>
					  <td>
                         1 hora
                      </td>
                      <td>
                         <div class="control-group form-elements">
                            <input type="radio" name="option" id="radio_1" value="1">
                            <label for="radio_1">X</label>           
                         </div>
                      </td>
                      
                    </tr>
					<tr>
                      <td>
                          220,00 €
                      </td>
                      <td>
                          10
                      </td>
					  <td>
                         1/2 hora
                      </td>
                      <td>
                         <div class="control-group form-elements">
                            <input type="radio" name="option" id="radio_2" value="2">
                            <label for="radio_2">X</label>           
                         </div>
                      </td>
                      
                    </tr>
					<tr>
                      <td>
                          330,00 €
                      </td>
                      <td>
                          10
                      </td>
					  <td>
                         1 hora
                      </td>
                      <td>
                         <div class="control-group form-elements">
                            <input type="radio" name="option" id="radio_3" value="3">
                            <label for="radio_3">X</label>           
                         </div>
                      </td>
                      
                    </tr>
					<tr>
                      <td>
                          560,00 € (familiar)
                      </td>
                      <td>
                          20
                      </td>
					  <td>
                         1 hora
                      </td>
                      <td>
                         <div class="control-group form-elements">
                            <input type="radio" name="option" id="radio_4" value="4">
                            <label for="radio_4">X</label>           
                         </div>
                      </td>
                      
                    </tr>
                    
                  </tbody></table>
                </div>
              </td>
            </tr>
          </tbody></table>
		  <p><strong><u>Caducidad</u>: 4 meses</strong></p>
		  <p><u>Forma de pago</u>: Un único pago que se hará efectivo el día de la entrevista previa con la firma de la misma o bien el día del inicio del plan de entrenamiento.</p>
		  
		  <hr>
		  	<table class="tab_var1">
            <tbody><tr>
              <td class="tab_var1_pad">
                <div class="v_tables">
                  <table>
                    <tbody><tr>
                      <td>
                          Importe del Bono
                      </td>
                      <td>
                          Nº Sesiones
                      </td>
					  <td>
                          Duración de cada sesión
                      </td>
                      <td>
                          Marca (X) la opción elegida 
                      </td>
                      
                    </tr>
					<tr>
                      <td>
                          1.650,00 €
                      </td>
                      <td>
                          65
                      </td>
					  <td>
                         1 hora
                      </td>
                      <td>
                         <div class="control-group form-elements">
                            <input type="radio" name="option" id="radio_5" value="5">
                            <label for="radio_5">X</label>           
                         </div>
                      </td>
                      
                    </tr>
					<tr>
                      <td>
                          3.100,00 €
                      </td>
                      <td>
                          140
                      </td>
					  <td>
                         1 hora
                      </td>
                      <td>
                         <div class="control-group form-elements">
                            <input type="radio" name="option" id="radio_6" value="6">
                            <label for="radio_6">X</label>           
                         </div>
                      </td>
                      
                    </tr>
				
                  </tbody></table>
                </div>
              </td>
            </tr>
          </tbody></table>
		  <p><strong><u>Caducidad</u>: 18 meses</strong></p>
		  <p><u>Forma de pago del bono de 1.650,00</u>: Se podrá hacer efectivo en tres pagos durante el plan de entrenamiento, por importe de 550 € cada uno.</p>
		  <ul style="list-style-type: square">
			  <li>El 1º pago, se hará efectivo a la firma del presente contrato o bien el día de inicio del plan de entrenamiento.</li>
			  <li>El 2º pago, se hará efectivo a las 5 semanas desde la firma de este contrato.</li>
			  <li>Y el 3ºpago se hará efectivo a las 10 semanas desde la firma de este contrato.</li>
			  
		  </ul>
		  
		  <p><u>Forma de pago del bono de 3.100,00</u>: Se podrá hacer efectivo en cinco pagos durante el plan de entrenamiento, por importe de 620 € cada uno.</p>
		  <ul style="list-style-type: square">
			  <li>El 1º pago, se hará efectivo a la firma del presente contrato o bien el día de inicio del plan de entrenamiento.</li>
			  <li>El 2º pago se hará efectivo a la tercera semana computada desde la firma del presente contrato.</li>
			  <li>El 3º pago se hará efectivo a la quinta semana computada desde la firma del presente contrato.</li>
			  <li>El 4º pago se hará efectivo a las octava semana computada desde la firma del presente contrato.</li>
			  <li>el 5º y último pago se hará efectivo a la décima semana computado desde la firma del presente contrato.</li>
		  </ul>
		  
		  <p>En estos dos últimos bonos, cinco días antes del vencimiento de los pagos que correspondan a las semanas tercera, quinta, octava y décima, respectivamente, se enviará un whatsApp, sms o bien correo electrónico para recordar al cliente el número de sesiones consumidas y el próximo vencimiento, así el cliente tendrá un mejor control de la cuantía que le falte por abonar y en definitiva del estado de su bono.</p>
		 <hr>

			<p>De aplicación a cualquiera de las opciones elegidas:</p>
			
			 <ul style="list-style-type: square">
				<li>El control de cada uno de los bonos se llevara a cabo mediante una “Hoja de control de sesiones”, individualizada para cada cliente, que consta de casilleros en cada uno de los cuales el cliente, al finalizar cada sesión, deja constancia de la fecha y plasma su firma.</li>
				<li>Si el cliente no cumpliera con cualquiera de los pagos, perderá su derecho al bono y consecuentemente el derecho a recibir los servicios de entrenamiento personal sin devolución de las cantidades entregadas que serán compensadas con servicios de entrenamiento personal.</li>
				<li>Todas sesiones que se queden prestablecidas con un horario fijo, si el cliente pasa más de tres sesiones anulando y sin confirmación previa perderá su hora reservada de Entrenamiento.</li>
				<li>Las sesiones deben de anularse con al menos una antelación de 24 horas, al igual que confirmar cualquier modificación vía Whatsapp, mensaje de texto o correo electrónico.</li>
				<li>En caso de lesión y/o accidente, mediante la presentación de justificante médico, se procederá a la paralización del periodo de caducidad del bono en tanto en cuanto se prolongue la situación de lesión y/o accidente.</li>
				
			 </ul>
			 <hr>
			 <p><strong>En Murcia,  a <?php echo $hoy; ?> </strong></p>			
			 
			 <div class="btn-double btn-shadow"><input style="background: #2498ff" type="submit" class="btn-large btn-first" name="enviar" value="Firmar Contrato"></div>
			
    </form>

  </div> 
  
</div>

 <!--==============================footer================================--> 
 
<?php include('includes/footer.php');?>
<div id="gotoTop" class="fa fa-angle-up"></div>

  <script src="js/jquery.js"></script>
  <script src="js/jquery.bxslider.js"></script>
  <script src="js/jquery.flexslider.js"></script>
   <script src="js/retina.js"></script>
    <script src="js/bootstrap.js"></script>
	  <script src="js/script.js"></script>
  <script src="js/jquery.fitvids.js"></script>
  <script src="js/responsive-tabs.js"></script>

</body>
</html>

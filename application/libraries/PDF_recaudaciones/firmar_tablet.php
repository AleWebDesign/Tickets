<?php include('includes/header.php');  

	error_reporting(E_ALL);
	ini_set("display_errors", 0);
	$hoy = date("Y/m/d H:i:s");
	
	$id_cliente = $_REQUEST['id_cliente'];
	
	$cliente=mysql_query("select * from clientes where id='".$id_cliente."'");
	$cliente_info=mysql_fetch_array($cliente);

	if(isset($_POST['enviar'])){

		$option = $_POST['option'];

		$bono = mysql_query("UPDATE clientes SET bono = '$option' WHERE id = ".$id_cliente."");

	}

?>

<style type="text/css">
body,html{
	overflow-y: hidden;
}
</style>

<div id="content">

  <div class="container content-div">
    
	<div class="span6">

		<h3> Firma Registro Nuevo Cliente </h3>  
		<h4 style="padding-top:0px"><?php echo $cliente_info['nombre']." ".$cliente_info['apellidos']; ?></h4>

	</div>

	<div class="span5">

		<h3 style="font-size: 18px"> Panel de firma </h3>

		<div>
			<canvas id="canvas" width="300" height="200"></canvas>
			<div class="gui">
			  <input type="hidden" id="color" value="#000000">
			  <a style="text-decoration: underline; font-size: 14px" id="bt-clear">Limpiar</a>
			</div>
		</div>
	 
		<script src= "script/guardando2-pngs.js"></script>
		
		 <div class="row shortcode_forms">
	        <div class="span6">

		            <fieldset>
					
					<input name="id_cliente" type="hidden" value="<?php echo $id_cliente; ?>">
								
					<div class="control-group">
		              <label class="control-label"></label>
		              <div class="controls">
		               
					   <button id="submit" type="submit" name="boton" class="btn-small">Guardar</button>
		                
		              </div>
		            </div>
		            
		            </fieldset>

	        </div>
	    </div>

	</div>

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
  <script type="text/javascript">
  		// Prevent scrolling when touching the canvas
		document.body.addEventListener("touchstart", function (e) {
		  if (e.target == canvas) {
		    e.preventDefault();
		  }
		}, false);
		document.body.addEventListener("touchend", function (e) {
		  if (e.target == canvas) {
		    e.preventDefault();
		  }
		}, false);
		document.body.addEventListener("touchmove", function (e) {
		  if (e.target == canvas) {
		    e.preventDefault();
		  }
		}, false);

  		// Set up touch events for mobile, etc
		canvas.addEventListener("touchstart", function (e) {
		  mousePos = getTouchPos(canvas, e);
		  var touch = e.touches[0];
		  var mouseEvent = new MouseEvent("mousedown", {
		    clientX: touch.clientX,
		    clientY: touch.clientY
		  });
		  canvas.dispatchEvent(mouseEvent);
		}, false);
		canvas.addEventListener("touchend", function (e) {
		  var mouseEvent = new MouseEvent("mouseup", {});
		  canvas.dispatchEvent(mouseEvent);
		}, false);
		canvas.addEventListener("touchmove", function (e) {
		  var touch = e.touches[0];
		  var mouseEvent = new MouseEvent("mousemove", {
		    clientX: touch.clientX,
		    clientY: touch.clientY
		  });
		  canvas.dispatchEvent(mouseEvent);
		}, false);

		// Get the position of a touch relative to the canvas
		function getTouchPos(canvasDom, touchEvent) {
		  var rect = canvasDom.getBoundingClientRect();
		  return {
		    x: touchEvent.touches[0].clientX - rect.left,
		    y: touchEvent.touches[0].clientY - rect.top
		  };
		}
  </script>

</body>
</html>

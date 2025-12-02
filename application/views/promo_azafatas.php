<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px">
        <a href="<?php echo base_url('promo_azafatas'); ?>" style="color: #000; text-decoration: none">Promoción Azafatas</a>
        <a style="float: right" id="excel_link" href="http://atc.apuestasdemurcia.es/tickets/promo_azafatas_excel/SELECT * FROM promo_salones" class="btn btn-info exportar" target="_blank">Exportar Excel</a>
    </h3>
		<!-- Tabla usuarios -->
	<hr/>
	<div class="col-md-12" id="jsGrid">
  	</div>
  </div>
</body>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('files/js/jsgrid/jsgrid.min.css'); ?>" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url('files/js/jsgrid/jsgrid-theme.min.css'); ?>" />    
<script type="text/javascript" src="<?php echo base_url('files/js/jsgrid/jsgrid.min.js'); ?>"></script>
<script type="text/javascript">
	$('a').on('click', function(e){
		e.stopImmediatePropagation();
		localStorage.setItem("scrollTop", $(window).scrollTop());
	});
</script>
<script type="text/javascript">
	var salones = [
        <?php echo $html_salones; ?>
    ];

	var promos = [
		<?php echo $html_promos; ?>
	];

	$("#jsGrid").jsGrid({
        width: "100%",
        height: "auto",

        filtering: true,
        editing: true,
        sorting: true,
        paging: true,

        pageSize: 14,

        data: promos,

        fields: [
        	{ name: "id", type: "number", width: 50, editing: false, filtering: false },
        	{ name: "Salon", type: "select", items: salones, valueField: "Id", textField: "Name", width: 50, editing: false,
        			filterTemplate: function() {
            			var $filterControl = jsGrid.fields.select.prototype.filterTemplate.call(this);
            			$filterControl.on("change", function() {
            				var selected = $(this).val();
            				var i;
            				var selectedIndex;
							for (i = 0; i < $filterControl[0].length; i++) {
								if($filterControl[0][i].value == selected){ 
							  		selectedIndex = $filterControl[0][i].index;
								}
							}
							$.ajax({
						        type: "GET",
						        url: "<?php echo base_url('promo_azafatas_filter_data'); ?>",
						        data: "Salon=" + selected,
						        dataType: "json"
						    }).done(function(response){
						    	if(response.length == 0){
						    		$("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/promo_azafatas_excel/SELECT * FROM promo_salones');
						    	}else{
						    		$("#jsGrid").jsGrid('option','data',response);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/promo_azafatas_excel/SELECT * FROM promo_salones WHERE salon LIKE "' +selected + '"');
						    	}
						    });
            				$.ajax({
						        type: "GET",
						        url: "<?php echo base_url('promo_azafatas_filter_data'); ?>",
						        data: "Salon=" + selected,
						        dataType: "json",
						        success: function(data){
						        	$("#jsGrid").jsGrid("fieldOption", "Salon", "selectedIndex", selectedIndex);
						        }
						    });
            			});
            			return $filterControl;
            		},
            },
            { name: "Nombre", type: "text", width: 50, editing: false, filtering: false },
            { name: "Email", type: "text", width: 50, editing: false, filtering: false },
            { name: "DNI", type: "text", width: 50, editing: false, filtering: false },
			{ name: "Telefono", type: "text", width: 50, editing: false, filtering: false },
			{ name: "Fecha", type: "date", width: 50, editing: false, filtering: false },
			{ name: "Ticket", type: "number", width: 100, editing: false, filtering: false },
        ]
    });
</script>
</html>
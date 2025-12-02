<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php if($this->session->userdata('logged_in')['rol'] == 6){ ?>          
	<a href="<?php echo base_url('nueva_visita'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nueva visita" title="Nueva visita" style="width: 80%" />
	</a>
	<?php } ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px">
        <a href="<?php echo base_url('visitas'); ?>" style="color: #000; text-decoration: none">Visitas</a>
        <a style="float: right" id="excel_link" href="http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM personal" class="btn btn-info exportar" target="_blank">Exportar Excel</a>
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
<script>
	var visitas = [
		<?php echo $visitas; ?>
	];

    var operadoras = [
        <?php echo $html_op; ?>
    ];

    var salones = [
        <?php echo $html_salones; ?>
    ];

    var personal = [
        <?php echo $html_personal; ?>
    ];

    var supervisoras = [
        <?php echo $html_supervisoras; ?>
    ];

    $("#jsGrid").jsGrid({
        width: "100%",
        height: "auto",

        filtering: true,
        editing: true,
        sorting: true,
        paging: true,

        pageSize: 14,
		
		controller: {
		    updateItem: function(item){
		       $.ajax({
		        type: "PUT",
		        url: "<?php echo base_url('visitas_filter_data'); ?>",
		        data: item
		       });
		    },
		    deleteItem: function(item){
		       $.ajax({
		        type: "DELETE",
		        url: "<?php echo base_url('visitas_filter_data'); ?>",
		        data: item
		       });
		    },
        },

        data: visitas,

        fields: [
        	{ name: "id", type: "number", width: 50, editing: false, filtering: false },
            { name: "Operadora", type: "select", items: operadoras, valueField: "Id", textField: "Name", width: 100, validate: "required",
            		filterTemplate: function() {
            			var $filterControl = jsGrid.fields.select.prototype.filterTemplate.call(this);
            			$filterControl.on("change", function() {
            				var selected = parseInt($(this).val(), 10);
            				if(isNaN(selected)){
            					selected = '';
            				}
            				var i;
            				var selectedIndex;
							for (i = 0; i < $filterControl[0].length; i++) {
								if($filterControl[0][i].value == selected){ 
							  		selectedIndex = $filterControl[0][i].index;
								}
							}
							$.ajax({
						        type: "GET",
						        url: "<?php echo base_url('visitas_filter_data'); ?>",
						        data: "Operadora=" + selected,
						        dataType: "json"
						    }).done(function(response){
						    	if(response.length == 0){
						    		$("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM visitas');
						    	}else{
						    		$("#jsGrid").jsGrid('option','data',response);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM visitas WHERE operadora = '+selected);
						    	}
						    });
            				$.ajax({
						        type: "GET",
						        url: "<?php echo base_url('visitas_filter_select'); ?>",
						        data: "Operadora=" + selected,
						        dataType: "json",
						        success: function(data){
						        	$("#jsGrid").jsGrid("fieldOption", "Operadora", "selectedIndex", selectedIndex);
						        	$("#jsGrid").jsGrid("fieldOption", "Salon", "items", data);
						        }
						    });
						    $.ajax({
						        type: "GET",
						        url: "<?php echo base_url('visitas_personal_filter_select'); ?>",
						        data: "Operadora=" + selected,
						        dataType: "json",
						        success: function(data){
						        	$("#jsGrid").jsGrid("fieldOption", "Operadora", "selectedIndex", selectedIndex);
						        	$("#jsGrid").jsGrid("fieldOption", "Personal1", "items", data);
						        }
						    });
            			});
            			return $filterControl;
            		},
                    editTemplate: function(value, item) {
                        var $editControl = jsGrid.fields.select.prototype.editTemplate.call(this, value, item);                        
                        $editControl.on("change", function(){
                            var selected = parseInt($(this).val(), 10);
                            var salones = $(this).parent().next().children();
                            var personal1 = $(this).parent().next().next().children();
                            var personal2 = $(this).parent().next().next().next().children();
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('edit_salones_select'); ?>",
                                data: "Operadora=" + selected,
                                dataType: "json",
                                success: function(data){
                                    salones.empty().append(data);
                                }
                            });
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('edit_personal_select'); ?>",
                                data: "Operadora=" + selected,
                                dataType: "json",
                                success: function(data){
                                    personal1.empty().append(data);
                                    personal2.empty().append(data);
                                }
                            });
                        });                 
                        return $editControl;
                    },
            },
            { name: "Salon", type: "select", items: salones, valueField: "Id", textField: "Name", width: 100, editcss: "salones-edit", validate: "required",
            		filterTemplate: function() {
            			var operadora = this._grid.fields[1].selectedIndex;
            			if(operadora == -1){
            				operadora = '';
            			}else{
            				operadora = this._grid.fields[1].items[this._grid.fields[1].selectedIndex].Id;
            			}
            			var $filterControl = jsGrid.fields.select.prototype.filterTemplate.call(this);
            			$filterControl.on("change", function() {
            				var selected = parseInt($(this).val(), 10);
            				if(isNaN(selected)){
            					selected = '';
            				}
            				var i;
            				var selectedIndex;
							for (i = 0; i < $filterControl[0].length; i++) {
								if($filterControl[0][i].value == selected){ 
							  		selectedIndex = $filterControl[0][i].index;
								}
							}
							$.ajax({
						        type: "GET",
						        url: "<?php echo base_url('visitas_filter_data'); ?>",
						        data: "Salon=" + selected + "&Operadora=" + operadora,
						        dataType: "json"
						    }).done(function(response){
						    	if(response.length == 0){
						    		$("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM visitas');
						    	}else{
						    		$("#jsGrid").jsGrid('option','data',response);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM visitas WHERE salon = '+selected);
						    	}
						    });
						    $.ajax({
						        type: "GET",
						        url: "<?php echo base_url('visitas_personal_filter_select'); ?>",
						        data: "Salon=" + selected + "&Operadora=" + selected,
						        dataType: "json",
						        success: function(data){
						        	$("#jsGrid").jsGrid("fieldOption", "Personal1", "items", data);
                                    $("#jsGrid").jsGrid("fieldOption", "Salon", "selectedIndex", selectedIndex);
						        }
						    });
            			});
            			return $filterControl;
            		},
                    editTemplate: function(value, item) {
                        var $editControl = jsGrid.fields.select.prototype.editTemplate.call(this, value, item);                        
                        $editControl.on("change", function(){
                            var selected = parseInt($(this).val(), 10);
                            var personal1 = $(this).parent().next().children();
                            var personal2 = $(this).parent().next().next().children();                            
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('edit_personal_select'); ?>",
                                data: "Salon=" + selected,
                                dataType: "json",
                                success: function(data){
                                    personal1.empty().append(data);
                                    personal2.empty().append(data);
                                }
                            });
                        });                 
                        return $editControl;
                    },
            },
            { name: "Personal1", type: "select", items: personal, valueField: "Id", textField: "Name", width: 100,
            		filterTemplate: function() {
                        var operadora = this._grid.fields[1].selectedIndex;
                        if(operadora == -1){
                            operadora = '';
                        }else{
                            operadora = this._grid.fields[1].items[this._grid.fields[1].selectedIndex].Id;
                        }                       
                        var $filterControl = jsGrid.fields.select.prototype.filterTemplate.call(this);
                        $filterControl.on("change", function() {
                            var selected = $(this).val();
                            if($(this).parent().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().children().val();
                            }         
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('visitas_filter_data'); ?>",
                                data: "Personal1=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM visitas');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM visitas WHERE personal1 = '+selected + uri);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { name: "Personal2", type: "select", items: personal, valueField: "Id", textField: "Name", width: 100, filtering: false },
            { name: "Fecha", type: "text", width: 80, filtering: false },
            { name: "Observaciones", type: "text", width: 100, editing: false,
                    filterTemplate: function() {
                        var operadora = this._grid.fields[1].selectedIndex;
                        if(operadora == -1){
                            operadora = '';
                        }else{
                            operadora = this._grid.fields[1].items[this._grid.fields[1].selectedIndex].Id;
                        }                       
                        var $filterControl = jsGrid.fields.text.prototype.filterTemplate.call(this);
                        $filterControl.on("keyup", function() {
                            var selected = $(this).val();
                            if($(this).parent().prev().prev().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().prev().prev().children().val();
                            }      
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('visitas_filter_data'); ?>",
                                data: "Observaciones=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM visitas');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    var encode = encodeURI('http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM visitas WHERE observaciones LIKE "%' + selected + '%"' + uri);
                                    $('#excel_link').attr('href', encode);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { name: "Supervisora", type: "select", items: supervisoras, valueField: "Id", textField: "Name", width: 100, editing: false,
                    filterTemplate: function() {
                        var operadora = this._grid.fields[1].selectedIndex;
                        if(operadora == -1){
                            operadora = '';
                        }else{
                            operadora = this._grid.fields[1].items[this._grid.fields[1].selectedIndex].Id;
                        }                       
                        var $filterControl = jsGrid.fields.select.prototype.filterTemplate.call(this);
                        $filterControl.on("change", function() {
                            var selected = $(this).val();
                            if($(this).parent().prev().prev().prev().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().prev().prev().prev().children().val();
                            }         
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('visitas_filter_data'); ?>",
                                data: "Supervisora=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM visitas');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM visitas WHERE creador = '+selected + uri);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { type: "control",
              	itemTemplate: function(value, item) {
				    var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);
				    var $myButton = $("<a style='padding: 2px 2px 2px 4px; margin-left: 5px;' href='http://atc.apuestasdemurcia.es/tickets/editar_visita/"+item.id+"' type='button' class='btn btn-info no_edit' alt='Edición completa' title='Edición completa' target='_blank'><i class='fa fa-edit'></i></a><a style='padding: 2px 2px 2px 4px; margin-left: 5px;' href='http://atc.apuestasdemurcia.es/tickets/ver_visita/"+item.id+"' type='button' class='btn btn-success no_edit' alt='Ver visita' title='Ver visita' target='_blank'><i class='fa fa-eye'></i></a>");
				    return $result.add($myButton);
				}
        	}
        ]
    });
</script>
<script type="text/javascript">
    $('.no_edit').on('click', function(){
        setTimeout(function(){ $('.jsgrid-edit-row').css('display', 'none'); $('.jsgrid-row').css('display', 'table-row'); $('.jsgrid-alt-row').css('display', 'table-row'); }, 1000);
    });

    $('body').on('click', '.no_edit', function() {
        setTimeout(function(){ $('.jsgrid-edit-row').css('display', 'none'); $('.jsgrid-row').css('display', 'table-row'); $('.jsgrid-alt-row').css('display', 'table-row'); }, 1000);
    });
</script>
<script type="text/javascript">
    $(window).on('focus', function(){
        var operadora = $('.jsgrid-filter-row').children().first().next().children().val();
        var salon = $('.jsgrid-filter-row').children().first().next().next().children().val();
        var personal = $('.jsgrid-filter-row').children().first().next().next().next().children().val();
        var supervisora = $('.jsgrid-filter-row').children().first().next().next().next().next().next().next().next().children().val();
        var obs = $('.jsgrid-filter-row').children().first().next().next().next().next().next().next().children().val();
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('visitas_filter_data'); ?>",
            data: "Personal1=" + personal + "&Supervisora=" + supervisora + "&Salon=" + salon + "&Operadora=" + operadora + "&Observaciones=" + obs,
            dataType: "json"
        }).done(function(response){
            if(response.length == 0){
                $("#jsGrid").jsGrid('option','data',[]);
            }else{
                $("#jsGrid").jsGrid('option','data',response);
            }
        });
    });
</script>
</html>
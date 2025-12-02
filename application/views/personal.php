<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php if($this->session->userdata('logged_in')['rol'] == 6){ ?>          
	<a href="<?php echo base_url('nuevo_personal'); ?>" style="position: fixed; right: 1%; bottom: 1%; z-index: 9">
		<img src="<?php echo base_url('files/img/icono_nueva.png'); ?>" alt="Nuevo personal" title="Nuevo personal" style="width: 80%" />
	</a>
	<?php } ?>
  <div class="container-fluid">
  	<h3 style="font-size: 20px">
        <a href="<?php echo base_url('personal'); ?>" style="color: #000; text-decoration: none">Personal</a>
        <a style="float: right" id="excel_link" href="http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal" class="btn btn-info exportar" target="_blank">Exportar Excel</a>
    </h3>
	<!-- Tabla usuarios -->
	<hr/>
    <?php if(isset($html_duplicado)){ ?>
    <div class="col-md-12">
        <?php echo $html_duplicado; ?>
    </div>
    <?php } ?>
	<div class="col-md-12" id="jsGrid">
  	</div>
    <div class="background" style="display: none; position: absolute; top: 0; width: 100%; height: 1000px; background: rgba(0,0,0,0.2);">
        <div class="background_div" style="margin: 10% auto 0; width: 200px;">
            <img class="background_image" src="" style="width: 100%; border: 1px solid #999; padding: 1%; background: #fff; border-radius: 5px;">
        </div>
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
    var personal = [
        <?php echo $personal; ?>
    ];

    var operadoras = [
        <?php echo $html_op; ?>
    ];

    var salones = [
        <?php echo $html_salones; ?>
    ];

    var cursos = [
        { Name: "Todos", Id: '' },
    	{ Name: "No", Id: '0' },
    	{ Name: "Si", Id: '1' }
    ];

    var carnet = [
    	{ Name: "No", Id: '0' },
    	{ Name: "Si", Id: '1' }
    ];
 
 	var test = [
        { Name: "Todos", Id: '' },
    	{ Name: "No", Id: '0' },
    	{ Name: "Si", Id: '1' }
    ];

    var activo = [
        { Name: "Todos", Id: '' },
    	{ Name: "No", Id: '0' },
    	{ Name: "Si", Id: '1' }
    ];

    var imagen = [
        { Name: "Todos", Id: '' },
        { Name: "No", Id: '0' },
        { Name: "Si", Id: '1' }
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
		        url: "<?php echo base_url('personal_filter_data'); ?>",
		        data: item
		       });
		    },
		    deleteItem: function(item){
		       $.ajax({
		        type: "DELETE",
		        url: "<?php echo base_url('personal_filter_data'); ?>",
		        data: item
		       });
		    },
        },

        data: personal,
 
        fields: [
        	{ name: "id", type: "number", width: 25, editing: false, filtering: false },
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
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Operadora=" + selected,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE operadora = '+selected);
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
                        });
                        return $filterControl;
                    },
                    editTemplate: function(value, item) {
                        var $editControl = jsGrid.fields.select.prototype.editTemplate.call(this, value, item);                        
                        $editControl.on("change", function(){
                            var selected = parseInt($(this).val(), 10);
                            var salones = $(this).parent().next().children();
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('edit_salones_select'); ?>",
                                data: "Operadora=" + selected,
                                dataType: "json",
                                success: function(data){
                                    salones.empty().append(data);
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
                            $(this).parent().next().children().val("");
                            if($(this).parent().next().next().next().next().next().next().next().next().next().next().children().val() == ''){
                                test = '';
                            }else{
                                test = $(this).parent().next().next().next().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().next().next().next().next().next().children().val() == ''){
                                activo = '';
                            }else{
                                activo = $(this).parent().next().next().next().next().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().children().val() == ''){
                                curso = '';
                            }else{
                                curso = $(this).parent().next().next().next().next().next().children().val();
                            }
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
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Activo=" + activo + "&Curso=" + curso + "&Test=" + test + "&Salon=" + selected + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE salon = '+ selected);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { name: "Nombre", type: "text", width: 120, validate: "required",
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
                            if($(this).parent().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().next().next().next().children().val() == ''){
                                test = '';
                            }else{
                                test = $(this).parent().next().next().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().next().next().next().next().children().val() == ''){
                                activo = '';
                            }else{
                                activo = $(this).parent().next().next().next().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().next().children().val() == ''){
                                curso = '';
                            }else{
                                curso = $(this).parent().next().next().next().next().children().val();
                            }       
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Activo=" + activo + "&Test=" + test + "&Curso=" + curso + "&Nombre=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    if(test != ''){
                                        uri = uri + " AND test = " + test;
                                    }
                                    if(activo != ''){
                                        uri = uri + " AND activo = " + activo;
                                    }
                                    if(curso != ''){
                                        uri = uri + " AND curso = " + curso;
                                    }
                                    var encode = encodeURI('http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE nombre LIKE "%' + selected + '%"' + uri);
                                    $('#excel_link').attr('href', encode);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { name: "DNI", type: "text", width: 50, validate: "required", 
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
                            if($(this).parent().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().next().next().children().val() == ''){
                                test = '';
                            }else{
                                test = $(this).parent().next().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().next().next().next().children().val() == ''){
                                activo = '';
                            }else{
                                activo = $(this).parent().next().next().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().children().val() == ''){
                                curso = '';
                            }else{
                                curso = $(this).parent().next().next().next().children().val();
                            }       
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Activo=" + activo + "&Test=" + test + "&Curso=" + curso + "&DNI=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    if(test != ''){
                                        uri = uri + " AND test = " + test;
                                    }
                                    if(activo != ''){
                                        uri = uri + " AND activo = " + activo;
                                    }
                                    if(curso != ''){
                                        uri = uri + " AND curso = " + curso;
                                    }
                                    var encode = encodeURI('http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE dni LIKE "%' + selected + '%"' + uri);
                                    $('#excel_link').attr('href', encode);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { name: "Telefono", type: "text", width: 50,
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
                            if($(this).parent().prev().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().prev().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().next().children().val() == ''){
                                test = '';
                            }else{
                                test = $(this).parent().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().next().next().children().val() == ''){
                                activo = '';
                            }else{
                                activo = $(this).parent().next().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().children().val() == ''){
                                curso = '';
                            }else{
                                curso = $(this).parent().next().next().children().val();
                            }       
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Activo=" + activo + "&Test=" + test + "&Curso=" + curso + "&Telefono=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    if(test != ''){
                                        uri = uri + " AND test = " + test;
                                    }
                                    if(activo != ''){
                                        uri = uri + " AND activo = " + activo;
                                    }
                                    if(curso != ''){
                                        uri = uri + " AND curso = " + curso;
                                    }
                                    var encode = encodeURI('http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE telefono LIKE "%' + selected + '%"' + uri);
                                    $('#excel_link').attr('href', encode);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { name: "Registro", type: "text", width: 30,
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
                            if($(this).parent().next().next().next().next().next().next().children().val() == ''){
                                test = '';
                            }else{
                                test = $(this).parent().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().next().children().val() == ''){
                                activo = '';
                            }else{
                                activo = $(this).parent().next().next().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().children().val() == ''){
                                curso = '';
                            }else{
                                curso = $(this).parent().next().children().val();
                            }       
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Activo=" + activo + "&Test=" + test + "&Curso=" + curso + "&Registro=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    if(test != ''){
                                        uri = uri + " AND test = " + test;
                                    }
                                    if(activo != ''){
                                        uri = uri + " AND activo = " + activo;
                                    }
                                    if(curso != ''){
                                        uri = uri + " AND curso = " + curso;
                                    }
                                    var encode = encodeURI('http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE registro LIKE "%' + selected + '%"' + uri);
                                    $('#excel_link').attr('href', encode);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { name: "Curso", type: "select", items: cursos, valueField: "Id", textField: "Name", width: 30,
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
                            $(this).parent().prev().prev().prev().prev().children().val("");
                            if($(this).parent().prev().prev().prev().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().prev().prev().prev().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().children().val() == ''){
                                test = '';
                            }else{
                                test = $(this).parent().next().next().next().next().next().children().val();
                            }
                            if($(this).parent().next().next().next().next().next().next().children().val() == ''){
                                activo = '';
                            }else{
                                activo = $(this).parent().next().next().next().next().next().next().children().val();
                            }                           
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
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Curso=" + selected + "&Activo=" + activo + "&Test=" + test + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    if(test != ''){
                                        uri = uri + " AND test = " + test;
                                    }
                                    if(activo != ''){
                                        uri = uri + " AND activo = " + activo;
                                    }
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE curso = '+ selected + uri);
                                }
                            });                            
                        });
                        return $filterControl;
                    }
            },
            { name: "Carnet", type: "select", items: carnet, valueField: "Id", textField: "Name", width: 30, filtering: false },
            { name: "Nota", type: "date", width: 20, filtering: false },
            { name: "FechaForm", type: "date", width: 40, filtering: false },
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
                            if($(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val();
                            }
                            if($(this).parent().next().children().val() == ''){
                                test = '';
                            }else{
                                test = $(this).parent().next().children().val();
                            }
                            if($(this).parent().next().next().children().val() == ''){
                                activo = '';
                            }else{
                                activo = $(this).parent().next().next().children().val();
                            }
                            if($(this).parent().prev().prev().prev().prev().children().val() == ''){
                                curso = '';
                            }else{
                                curso = $(this).parent().prev().prev().prev().prev().children().val();
                            }       
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Activo=" + activo + "&Test=" + test + "&Curso=" + curso + "&Observaciones=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    if(test != ''){
                                        uri = uri + " AND test = " + test;
                                    }
                                    if(activo != ''){
                                        uri = uri + " AND activo = " + activo;
                                    }
                                    if(curso != ''){
                                        uri = uri + " AND curso = " + curso;
                                    }
                                    var encode = encodeURI('http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE observaciones LIKE "%' + selected + '%"' + uri);
                                    $('#excel_link').attr('href', encode);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { name: "Test", type: "select", items: test, valueField: "Id", textField: "Name", width: 30,
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
                            $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val("");
                            if($(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val();
                            }
                            if($(this).parent().next().children().val() == ''){
                                activo = '';
                            }else{
                                activo = $(this).parent().next().children().val();
                            }
                            if($(this).parent().prev().prev().prev().prev().prev().children().val() == ''){
                                curso = '';
                            }else{
                                curso = $(this).parent().prev().prev().prev().prev().prev().children().val();
                            }                           
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
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Test=" + selected + "&Activo=" + activo + "&Curso=" + curso + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    if(activo != ''){
                                        uri = uri + " AND activo = " + activo;
                                    }
                                    if(curso != ''){
                                        uri = uri + " AND curso = " + curso;
                                    }
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE test = '+ selected + uri);
                                }
                            });                            
                        });
                        return $filterControl;
                    }
            },
            { name: "Activo", type: "select", items: activo, valueField: "Id", textField: "Name", width: 30,
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
                            $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val("");
                            if($(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val();
                            }
                            if($(this).parent().prev().children().val() == ''){
                                test = '';
                            }else{
                                test = $(this).parent().prev().children().val();
                            }
                            if($(this).parent().prev().prev().prev().prev().prev().prev().children().val() == ''){
                                curso = '';
                            }else{
                                curso = $(this).parent().prev().prev().prev().prev().prev().prev().children().val();
                            }                           
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
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Activo=" + selected + "&Test=" + test + "&Curso=" + curso + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    if(test != ''){
                                        uri = uri + " AND test = " + test;
                                    }
                                    if(curso != ''){
                                        uri = uri + " AND curso = " + curso;
                                    }
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/personal_excel/SELECT * FROM personal WHERE activo = '+ selected + uri);
                                }
                            });                            
                        });
                        return $filterControl;
                    }
            },
            { name: "Imagen", type: "select", items: imagen, valueField: "Id", textField: "Name", width: 30, filtering: false },
            { name: "Supervisora", type: "select", items: supervisoras, valueField: "Id", textField: "Name", width: 50, editing: false,
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
                            if($(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val() == ''){
                                salon = '';
                            }else{
                                salon = $(this).parent().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().prev().children().val();
                            }         
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url('personal_filter_data'); ?>",
                                data: "Supervisora=" + selected + "&Salon=" + salon + "&Operadora=" + operadora,
                                dataType: "json"
                            }).done(function(response){
                                if(response.length == 0){
                                    $("#jsGrid").jsGrid('option','data',[]);
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM personal');
                                }else{
                                    $("#jsGrid").jsGrid('option','data',response);
                                    var uri = '';
                                    if(operadora != ''){
                                        uri = uri + " AND operadora = " + operadora;
                                    }
                                    if(salon != ''){
                                        uri = uri + " AND salon = " + salon;
                                    }
                                    $('#excel_link').attr('href', 'http://atc.apuestasdemurcia.es/tickets/visitas_excel/SELECT * FROM personal WHERE creador = '+selected + uri);
                                }
                            });
                        });
                        return $filterControl;
                    }
            },
            { type: "control", width: 80,
              	itemTemplate: function(value, item) {
				    var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);
				    var $myButton = $("<a style='padding: 2px 2px 2px 4px; margin-left: 2px;' href='http://atc.apuestasdemurcia.es/tickets/editar_personal/"+item.id+"' type='button' class='btn btn-info no_edit' alt='Edición completa' title='Edición completa' target='_blank'><i class='fa fa-edit'></i></a><a style='padding: 2px 2px 2px 4px; margin-left: 2px;' href='http://atc.apuestasdemurcia.es/tickets/ver_personal/"+item.id+"' type='button' class='btn btn-success no_edit' alt='Ver personal' title='Ver personal' target='_blank'><i class='fa fa-eye'></i></a><a style='padding: 2px 2px 2px 4px; margin-left: 2px;' id='"+item.id+"' type='button' class='btn btn-primary ver_imagen' alt='Ver imágen' title='Ver imágen'><i class='fa fa-image'></i></a><a style='padding: 2px 2px 2px 4px; margin-left: 2px;' href='http://atc.apuestasdemurcia.es/tickets/carnet_adm/"+item.id+"'' type='button' class='btn btn-info no_edit' alt='Ver carnet' title='Ver carnet' target='_blank'><i class='fa fa-address-card'></i></a>");
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
        var nombre = $('.jsgrid-filter-row').children().first().next().next().next().children().val();
        var curso = $('.jsgrid-filter-row').children().first().next().next().next().next().next().next().next().children().val();
        var test = $('.jsgrid-filter-row').children().first().next().next().next().next().next().next().next().next().next().next().next().next().children().val();
        var activo = $('.jsgrid-filter-row').children().first().next().next().next().next().next().next().next().next().next().next().next().next().next().children().val();
        var imagen = $('.jsgrid-filter-row').children().first().next().next().next().next().next().next().next().next().next().next().next().next().next().next().children().val();
        var supervisora = $('.jsgrid-filter-row').children().first().next().next().next().next().next().next().next().next().next().next().next().next().next().next().next().children().val();
        var dni = $('.jsgrid-filter-row').children().first().next().next().next().next().children().val();
        var telefono = $('.jsgrid-filter-row').children().first().next().next().next().next().next().children().val();
        var obs = $('.jsgrid-filter-row').children().first().next().next().next().next().next().next().next().next().next().next().next().children().val();
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('personal_filter_data'); ?>",
            data: "Activo=" + activo + "&Curso=" + curso + "&Nombre=" + nombre + "&Test=" + test + "&Salon=" + salon + "&Operadora=" + operadora + "&DNI=" + dni + "&Telefono=" + telefono + "&Observaciones=" + obs + "&Supervisora=" + supervisora,
            dataType: "json"
        }).done(function(response){
            if(response.length == 0){
                $("#jsGrid").jsGrid('option','data',[]);
            }else{
                $("#jsGrid").jsGrid('option','data',response);
            }
        });
    });

    $('body').on('click', '.ver_imagen', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var dataString = [id];
        var jsonString = JSON.stringify(dataString);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('get_persona_image'); ?>",
            data: {data : jsonString},
        }).done(function(response){
            var trimmedResponse = $.trim(response);
            var url = encodeURI(window.location.protocol + '//atc.apuestasdemurcia.es/tickets/files/img/personal/' + trimmedResponse);
            $('.background_image').attr('src', url);
            $('.background').css('display', 'block');
        });
    });

    $('.background').on('click', function(){
        $(this).css('display', 'none');
    });
</script>
</html>
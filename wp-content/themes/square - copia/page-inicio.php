<?php
    get_header(); 
    $cu = wp_get_current_user();
    $cu_u = $wpdb->get_row( "SELECT * FROM users WHERE id_user = '$cu->ID'" );
?>
<?php
add_action( 'admin_footer', 'my_action_javascript' ); 
function my_action_javascript() { ?>
	<script type="text/javascript" >
	console.log("cargado");
	jQuery(document).ready(function($) {
		var data = {
			'action': 'my_action',
			'whatever': 1234
		};
		jQuery.post(ajaxurl, data, function(response) {
			alert('Got this from the server: ' + response);
		});
	});
	</script> 
<?php } ?>
<style>
    #calendar {
        max-width: 800px;
    }
    .modal {
        text-align: center;
        padding: 0!important;
    }
    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }
    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
<br>
<div class="container">
	<br><br><br><br>
	<div class="row ">
		<h5 class="color_vallas text-center">Solicitud de vacaciones</h5>
	</div>    
    <form>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xm-12">             
                <div class="input-group">
                    <span class="input-group-addon "><span class="glyphicon glyphicon-user"></span> Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="nombre" type="text" class="form-control  text-center" name="nombre" readonly value="<?= $cu->user_login." ".$cu->user_firstname.' '.$cu->user_lastname ?>">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon "><span class="glyphicon glyphicon-home"></span> Departamento:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="dep" type="text" class="form-control  text-center" name="dep" readonly value="<?= $cu_u->departamento ?>">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Ubicación:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="ubi" type="text" class="form-control text-center" name="ubi" readonly value="<?= $cu_u->area ?>">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> Fecha de ingreso:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="ingreso" type="text" class="form-control text-center" name="ingreso" readonly value="<?= $cu_u->fecha_ingreso ?>">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-road"></span> Antigüedad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="ant" type="text" class="form-control text-center" name="ant" readonly>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> Días de vacaciones:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="dias_vac" type="text" class="form-control text-center" name="dias_vac" readonly>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-ok"></span> Responsable en ausencia:</span>
                    <input id="resp" type="text" class="form-control text-center" name="resp" placeholder="Completa este campo" required>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span> Días seleccionados:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="dias_selet" type="text" class="form-control text-center" name="dias_select" readonly required>
                </div> 
                <br> 
            </div>
            <div class="col-md-6 col-lg-6 col-xm-12">
                <div class="form-group">
                    
                    <label>Selecciona los días:</label>
                    <div id="calendar" class="col-centered"></div>
                </div>
            </div>
        </div>
        <div class="row text-center">
            <button type="submit" class="btn btn-success">Guardar</button>                    
        </div> 
    </form>
    
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" data-backdrop="static">Open Modal</button>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php get_sidebar(); ?>

<script src="<?= get_template_directory_uri().'/js/fullcalendar.min.js'?>" type="text/javascript"></script>
<link href="<?= get_template_directory_uri().'/css/fullcalendar.css'?>" rel='stylesheet'/>
<script>
    var fecha=document.getElementById("ingreso").value;
    var actual = new Date();
    var fecha_actual= new Date(actual.getFullYear(),(actual.getMonth()+1),actual.getDate(),actual.getHours(),actual.getMinutes(),actual.getSeconds());
    var fecha1 = moment(fecha, "YYYY-MM-DD");
    var fecha2 = moment(""+fecha_actual.getFullYear()+"-"+fecha_actual.getMonth()+"-"+fecha_actual.getDate()+" "+fecha_actual.getHours()+":"+fecha_actual.getMinutes()+":"+fecha_actual.getSeconds(), "YYYY-MM-DD");
    var diff_año = fecha2.diff(fecha1, 'y');
    document.getElementById("ant").value = diff_año+" Año(s)";
    var dias_vacaciones=0;
    if (diff_año == 1){
        dias_vacaciones=6;
    }else if (diff_año == 2) {
        dias_vacaciones=8;
    }else if (diff_año == 3) {
        dias_vacaciones=10;
    }else if (diff_año == 4) {
        dias_vacaciones=12;
    }else if (diff_año >= 5 && diff_año <= 9) {
        dias_vacaciones=12;
    }else if (diff_año >= 10 && diff_año <= 14) {
        dias_vacaciones=16;
    }else if (diff_año >= 15 && diff_año <= 19) {
        dias_vacaciones=18;
    }
    document.getElementById("dias_vac").value = dias_vacaciones;
    $(document).ready(function() {
        var calendar = $('#calendar').fullCalendar({
            header:{
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            defaultView: 'month',
            editable: true,
            selectable: true,
            allDaySlot: false,
            select: function(start, end, jsEvent) {
                endtime = $.fullCalendar.moment(end).format('h:mm');
                starttime = $.fullCalendar.moment(start).format('YYYY-MM-DD HH:mm:ss');
                start = moment(start).format('YYYY-MM-DD');
                end = moment(end).format('YYYY-MM-DD');
                var diff_dias = moment(end).diff(start, 'd');
                if (dias_vacaciones >= diff_dias) {
                    $("#dias_selet").val(diff_dias);
                }else{
                    swal('¡Alto!', 'No puedes exceder los días de vacaciones disponobles', 'error');
                    $("#dias_selet").val('');
                }                
            }
        }); 
    });
</script>

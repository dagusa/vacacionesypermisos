<?php
    get_header(); 
    if (isset($_POST['enviar'])) {
        $folio=$_POST['folio'];
        $dias_selec=$_POST['dias_selec'];
        $dias_restantes=$_POST['dias_restantes'];
        $responsable=$_POST['resp'];
        $update=$wpdb->update(
            "solicitudes",
            ['responsable'=>$responsable, 'dias_usados'=>$dias_selec],
            ['id_solicitud'=>$folio],
            ['%s','%d'],
            ['%d']
        );
        echo "<script>window.open('pdf?folio=".$folio."', 'Formato de impresión')</script>";;
    }    
    $cu = wp_get_current_user();
    $cu_u = $wpdb->get_row( "SELECT * FROM users WHERE id_user = '$cu->ID'" );
    $responsables = $wpdb->get_results("SELECT id_user FROM users WHERE departamento = '$cu_u->departamento' ");
    $solicitudes = $wpdb->get_results( "SELECT * FROM solicitudes WHERE id_user='$cu->ID' ORDER BY id_solicitud DESC");
    $boton_solicitud = false;
    $estado = $wpdb->get_row("SELECT * FROM estados WHERE id_estado='$cu_u->estado'");
    $pais = $wpdb->get_var("SELECT nombre FROM paises WHERE  id='$estado->id_pais'");
    foreach ($solicitudes as $solicitud) {
        if ($solicitud->autorizado==0) {
            $boton_solicitud = true;
        }
    }
?>
<style>
    #calendar {
        max-width: 800px;
        width: 100%;
        height: 100%;
    }
    .mi_tamaño {
        height: 100%;
    }
</style>
<br>               
<div class="container">
	<br><br><br><br>
	<div class="row">
		<h1 class="color_vallas text-center">Solicitud de vacaciones</h1>
	</div> 
    <br>   
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xm-6">
            <div class="input-group">
                <span class="input-group-addon "><span class="glyphicon glyphicon-folder-open"></span> Numero de empleado:</span>
                <input type="text" class="form-control  text-center" readonly value="<?= $cu->user_login ?>">
            </div>
            <br>             
            <div class="input-group">
                <span class="input-group-addon "><span class="glyphicon glyphicon-user"></span> Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="hidden" id="id_usuario" value="<?= $cu->ID ?>">
                <input id="nombre" type="text" class="form-control  text-center" name="nombre" readonly value="<?= $cu->user_firstname.' '.$cu->user_lastname ?>">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon "><span class="glyphicon glyphicon-home"></span> Departamento:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input id="dep" type="text" class="form-control  text-center" name="dep" readonly value="<?= $wpdb->get_var("SELECT nombre FROM departamentos WHERE id_departamento='$cu_u->departamento'"); ?>">
            </div>
            <br>              
            <div class="input-group">
                <span class="input-group-addon "><span class="glyphicon glyphicon-home"></span> Pais:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input id="dep" type="text" class="form-control  text-center" name="dep" readonly value="<?= $pais ?>">
            </div>
            <br>  
            <div class="input-group">
                <span class="input-group-addon "><span class="glyphicon glyphicon-home"></span> Estado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input id="dep" type="text" class="form-control  text-center" name="dep" readonly value="<?= $estado->estado ?>">
            </div>
            <br>           
        </div>
        <div class="col-md-6 col-lg-6 col-xm-6">       
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Ubicación:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input id="ubi" type="text" class="form-control text-center" name="ubi" readonly value="<?= $cu_u->ubicacion ?>">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon "><span class="glyphicon glyphicon-pencil"></span> Empresa contratante:&nbsp;</span>
                <input type="text" class="form-control  text-center" readonly value="<?= $wpdb->get_var("SELECT razon_social FROM empresas WHERE id_empresa='$cu_u->empresa_contratante'") ?>">
            </div>
            <br>  
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> Fecha de ingreso:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input id="ingreso" type="text" class="form-control text-center" name="ingreso" readonly value="<?= $cu_u->fecha_ingreso ?>">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-road"></span> Antigüedad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input id="ant" type="text" class="form-control text-center" name="ant" readonly>
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> Días de vacaciones:&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input id="dias_vac" type="text" class="form-control text-center" name="dias_vac" value="<?= $cu_u->dias_vacaciones ?>" readonly>
            </div>                 
            <br> 
        </div>
        <?php if ($cu_u->dias_vacaciones > 0 && !$boton_solicitud) { ?>
            <div class="text-right">                
                <a  value="Nueva solicitud" onclick="nueva_solicitud()"><span class="glyphicon glyphicon-plus"></span> Nueva solicitud</a>
            </div>
        <?php } ?>        
    </div>
    <div id="mi_div" class="row" style="display:none;" >   
        <h4 class="text-center">Nueva solicitud de vacaciones</h4>
        <h5 class="text-center">Selecciona los días a disfrutar</h5>
        <br>
        <div class="col-lg-6 text-center">
            <div id="calendar" class="col-centered mi_tamaño"></div>
            <br><br>
        </div> 
        <div class="col-md-6 text-center"> 
            <br><br><br>
            <form action="" method="POST" enctype="multipart/form-data">                   
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span> Días seleccionados:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="dias_selec" type="text" class="form-control text-center" name="dias_selec" readonly>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> Días restantes:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="dias_restantes" type="text" class="form-control text-center" name="dias_restantes" value="<?= $cu_u->dias_vacaciones ?>" readonly>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-folder-open"></span> Folio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="folio" type="text" class="form-control text-center" name="folio" readonly>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-ok"></span> Responsable en ausencia:</span>
                    <select class="form-control" id="resp" name="resp" required>
                        <option class="text-center" value="">Selecciona un responsable</option>
                        <?php foreach ($responsables as $responsable) { ?>
                            <?php $user_info = get_userdata($responsable->id_user); ?>
                            <?php var_dump($user_info) ?>
                            <option class="text-center" value="<?= $responsable->id_user ?>"><?= $user_info->first_name." ".$user_info->last_name ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br>
                <button type="submit" name="enviar" class="btn btn-primary">Enviar</button>
            </form>
        </div> 
    </div>
    <?php if ($solicitudes) { ?>
        <div class="row">       
            <br><br>
            <center>
                <h2 class="color_vallas">Mis solicitudes</h2>
            </center>
            <br>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th class="color_vallas"><center><h5>Fecha</h5></center></th>
                        <th class="color_vallas"><center><h5>Folio</h5></center></th>
                        <th class="color_vallas"><center><h5>Días usados</h5></center></th>                        
                        <th class="color_vallas"><center><h5>Responsable</h5></center></th>
                        <th class="color_vallas"><center><h5>Comprobada</h5></center></th>
                    </tr>
                    <?php foreach ($solicitudes as $solicitud) { ?>
                        <tr>
                            <td class="color_vallas"><center><?php echo $solicitud->fecha;?></center></td>
                            <td class="color_vallas"><center><?php echo $solicitud->id_solicitud;?></center></td>
                            <td class="color_vallas"><center><?php echo $solicitud->dias_usados;?></center></td>
                            <td class="color_vallas"><center><?php echo $solicitud->responsable;?></center></td>
                            <td class="color_vallas"><center>
                                <?php if ($solicitud->autorizado==1) { ?>
                                    <span class="glyphicon glyphicon-ok" title="Aprobada"></span> 
                                <?php }else{ ?>
                                    <span class="glyphicon glyphicon-time" title="Esperando aprobación"></span>
                                <?php }?>
                                    
                            </center></td>
                            <td>
                                <center>
                                </center>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        <br>
        <div class="text-right">            
            <li><span class="glyphicon glyphicon-ok" title="Aprobada"></span> Solicitudes aprobadas</li>
            <li><span class="glyphicon glyphicon-time" title="Esperando aprobación"></span> Solicitudes en espera</li>
        </div>
    <?php } ?>
</div>
<?php get_sidebar(); ?>
<script src="<?= get_template_directory_uri().'/js/fullcalendar.min.js'?>" type="text/javascript"></script>
<script src="<?= get_template_directory_uri().'/js/jspdf/fullcalendar.min.js'?>" type="text/javascript"></script>
<link href="<?= get_template_directory_uri().'/css/fullcalendar.css'?>" rel='stylesheet'/>
<script src="<?= get_template_directory_uri().'/js/script_page_inicio.js'?>"></script>
<script>
    var fecha=document.getElementById("ingreso").value;
    var actual = new Date();
    var fecha_actual= new Date(actual.getFullYear(),(actual.getMonth()+1),actual.getDate(),actual.getHours(),actual.getMinutes(),actual.getSeconds());
    var fecha1 = moment(fecha, "YYYY-MM-DD");
    var fecha2 = moment(""+fecha_actual.getFullYear()+"-"+fecha_actual.getMonth()+"-"+fecha_actual.getDate()+" "+fecha_actual.getHours()+":"+fecha_actual.getMinutes()+":"+fecha_actual.getSeconds(), "YYYY-MM-DD");
    var diff_año = fecha2.diff(fecha1, 'y');
    document.getElementById("ant").value = diff_año+" Año(s)";
    var ajaxurl = '<?= admin_url( 'admin-ajax.php' ); ?>';
    var events_arr=new Array();
    var dias_usados=0;
    var id_solicitud=0;
    var dias_disponibles='<?= $cu_u->dias_vacaciones ?>';
    eventos();
    function crear_calendario(){
        $('#calendar').fullCalendar({
            header:{
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            defaultView: 'month',
            selectable: true,
            allDaySlot: false,
            eventColor: '#378006',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNameShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles','Jueves', 'Viernes', 'Sabado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            buttonText: {
                today: 'hoy',
                month: 'mes',
                week: 'semana',
                day: 'dia'
            },
            select: function(start, end, jsEvent) {
                start = moment(start).format('YYYY-MM-DD');
                end = moment(end).format('YYYY-MM-DD');
                var diff_dias = moment(end).diff(start, 'd');  
                if (dias_disponibles >= (dias_usados+diff_dias)) {
                        var data = {
                            'action': 'nuevo_evento',
                            'id_solicitud':id_solicitud,
                            'start':String(start),
                            'end':String(end),
                            'dias':diff_dias
                        };
                        jQuery.post(ajaxurl, data, function(response) {
                            console.log(response);                              
                            eventos(); 
                        });
                    }else{
                        swal('¡Alto!', 'No puedes exceder los días de vacaciones disponobles', 'error');
                    }
            },
            eventRender: function(event, element) {
                element.bind('dblclick', function() {
                    var data = {
                        'action': 'eliminar_evento',
                        'id_evento': event.id
                    };
                    jQuery.post(ajaxurl, data, function(response) {
                        console.log(response); 
                        eventos(); 
                    });
                });
            },
            selectOverlap:false,
            eventStartEditable: false
        }); 
    }
    function nueva_solicitud(){
        div = document.getElementById('mi_div');
        div.style.display ='';
        id_usuario=document.getElementById("id_usuario").value;
        var data_eventos={
            'action':'nuevo_folio',
            'id_usuario':id_usuario
        };
        jQuery.post(ajaxurl,data_eventos,function(response){
            document.getElementById("folio").value=response;
            id_solicitud=response;
        });
        crear_calendario();
    }
    function eventos(){
        events_arr=new Array();
        var data_eventos={
            'action':'obtener_eventos',
            'id_solicitud':id_solicitud
        };
        jQuery.post(ajaxurl,data_eventos,function(response){
            datos = JSON.parse(response);
            dias_usados=0;
            for (i=0;i<datos.length;i++){
                event = new Object();   
                event.title= 'Ok';
                event.id=  datos[i]['id'];
                event.start = datos[i]['inicio']; 
                event.end = datos[i]['fin'];
                event.editable= true;
                events_arr.push(event);
                dias_usados=dias_usados+parseInt(datos[i]['dias']);

            }
            document.getElementById("dias_selec").value=dias_usados;
            document.getElementById("dias_restantes").value=dias_disponibles-dias_usados;
            $('#calendar').fullCalendar("removeEvents");        
            $('#calendar').fullCalendar('addEventSource', events_arr);      
            $('#calendar').fullCalendar('refetchEvents'); 
        });
    }
</script>

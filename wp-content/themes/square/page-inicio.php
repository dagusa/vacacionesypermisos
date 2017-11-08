<?php
    get_header(); 
    if (isset($_POST['enviar'])) {
        solicitar_vacaciones();
    }    
    if(isset($_POST['solicitar_permiso'])){
        solicitar_permiso();
    }
    $cu = wp_get_current_user();
    $cu_u = $wpdb->get_row( "SELECT * FROM users WHERE id_user = '$cu->user_login'" );
    $area = $wpdb->get_var("SELECT id_area FROM departamentos WHERE id_departamento = $cu_u->departamento");
    $responsables = $wpdb->get_results("SELECT u.id_user,u.nombre,u.apellidos FROM users AS u, departamentos AS d WHERE d.id_area = '$area' AND d.id_departamento = u.departamento AND u.id_user <> '$cu->user_login' ORDER BY u.nombre;");
    $solicitudes = $wpdb->get_results( "SELECT * FROM solicitudes WHERE id_user='$cu->user_login' ORDER BY id_solicitud DESC");
    $solicitudes_vacaciones = $wpdb->get_results( "SELECT * FROM solicitudes WHERE id_user='$cu->user_login' AND tipo = 'Vacaciones' ORDER BY id_solicitud DESC");
    $solicitudes_permisos = $wpdb->get_results( "SELECT * FROM solicitudes WHERE id_user='$cu->user_login' AND tipo = 'Permisos' ORDER BY id_solicitud DESC");
    $boton_solicitud_vacaciones = false;
    $boton_solicitud_permisos = false;
    $estado = $wpdb->get_row("SELECT * FROM estados WHERE id_estado='$cu_u->estado'");
    $pais = $wpdb->get_var("SELECT nombre FROM paises WHERE  id='$estado->id_pais'");
    $permiso_ley = $wpdb->get_results("SELECT * FROM motivo_permiso WHERE tipo='ley'");
    $permiso_extraordinario = $wpdb->get_results("SELECT * FROM motivo_permiso WHERE tipo='extraordinario'");
    foreach ($solicitudes as $solicitud) {
        if ($solicitud->autorizado==0 and $solicitud->tipo == 'Vacaciones') {
            $boton_solicitud_vacaciones = true;
        }
        if ($solicitud->autorizado==0 and $solicitud->tipo == 'Permisos') {
            $boton_solicitud_permisos = true;
        }
    }
    $mi_area = $wpdb->get_results("SELECT * FROM users WHERE responsable_area = '$cu->ID'");
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
		<h1 class="color_vallas text-center">Mis datos</h1>
	</div> 
    <br>   
    <div class="row">
        <div class="col-md-6 col-lg-6 col-xm-6">
            <div class="input-group">
                <span class="input-group-addon "><span class="glyphicon glyphicon-folder-open"></span> Numero de empleado:</span>
                <input id="id_usuario" type="text" class="form-control  text-center" readonly value="<?= $cu->user_login ?>">
            </div>
            <br>             
            <div class="input-group">
                <span class="input-group-addon "><span class="glyphicon glyphicon-user"></span> Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
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
        <div class="text-right" id="botones_solicitudes">   
            <?php if ($cu_u->dias_vacaciones > 0 && !$boton_solicitud_vacaciones) { ?>             
                <a value="Nueva solicitud" onclick="nueva_solicitud()"><span class="glyphicon glyphicon-plus"></span> Solicitar vacaciones</a>
            <?php } if (!$boton_solicitud_permisos) { ?>
                <a  onclick="nuevo_permiso()"><span class="glyphicon glyphicon-plus"></span> Solicitar permiso</a>
            <?php } ?>
        </div>       
    </div>
    <div id="div_nueva_solicitud" class="row" style="display:none;" >   
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
                    <input id="dias_selec" type="text" class="form-control text-center" name="dias_selec" value="0" readonly>
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
                            <?php var_dump($user_info) ?>
                            <option class="text-center" value="<?= $responsable->id_user ?>"><?= $responsable->nombre." ".$responsable->apellidos ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br>
                <button type="submit" name="enviar" id="enviar" class="btn btn-primary" disabled>Enviar</button>
            </form>
        </div> 
    </div>
    <div id="div_nuevo_permiso" class="row" style="display:none;">   
        <h4 class="text-center">Nueva solicitud de permiso</h4>
        <h5 class="text-center">Selecciona los días a disfrutar</h5>
        <br>
        <div class="col-lg-6 text-center">
            <div id="calendar_permiso" class="col-centered mi_tamaño"></div>
            <br><br>
        </div> 
        <div class="col-md-6 text-center"> 
            <br><br><br>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-folder-open"></span> Folio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="folio_permiso" type="text" class="form-control text-center" name="folio_permiso" readonly>
                </div>
                <br>                   
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span> Días seleccionados:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input id="dias_selec_permiso" type="text" class="form-control text-center" name="dias_selec_permiso" readonly>
                </div>
                <br>                                  
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span> Motivo del permiso:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <select class="form-control" id="motivo" name="motivo" required>
                        <option class="text-center" value="">Selecciona un motivo de ausencia</option>
                        <optgroup label="Permisos por Ley">
                            <?php foreach ($permiso_ley as $permiso) { ?>
                                <option class="text-center" value="<?= $permiso->id_motivo ?>"><?= $permiso->nombre." (".$permiso->tiempo.")  -  ".$permiso->documento_solicitado ?></option>
                            <?php } ?>
                        </optgroup>
                        <optgroup label="Permisos extraordinarios">
                            <?php foreach ($permiso_extraordinario as $permiso) { ?>
                                <option class="text-center" value="<?= $permiso->id_motivo ?>"><?= $permiso->nombre."  -  ".$permiso->documento_solicitado ?></option>
                            <?php } ?>
                        </optgroup>
                    </select>
                </div>
                <br>
                <button type="submit" name="solicitar_permiso" id="solicitar_permiso" class="btn btn-primary" disabled>Solicitar</button>
            </form>
        </div> 
    </div>
    <?php if ($mi_area) { ?>
        <br>
        <h2 class="color_vallas">Mi área</h2>
        <div class="col-xs-12 col-ms-12 col-md-12 col-lg-12 col-xl-12 text-center">
            <div id="calendar_mi_area" class="col-centered mi_tamaño"></div>
        </div> 
    <?php } ?>
    <?php if ($solicitudes) { ?>             
        <br><br>
        <center>
            <h2 class="color_vallas">Mis solicitudes</h2>
        </center>
        <?php if ($solicitudes_vacaciones) { ?>
            <div class="row">
                <h5 class="color_vallas">Vacaciones</h5>
                <br>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th class="color_vallas text-center">Fecha</th>
                            <th class="color_vallas text-center">Folio</th>
                            <th class="color_vallas text-center">Días usados</th>
                            <th class="color_vallas text-center">Responsable</th>
                            <th class="color_vallas text-center">Comprobada</th>
                        </tr>
                        <?php foreach ($solicitudes_vacaciones as $solicitud) { ?>
                            <tr>
                                <td class="color_vallas text-center"><small><?php echo $solicitud->fecha;?></small></td>
                                <td class="color_vallas text-center"><small><?php echo $solicitud->id_solicitud;?></small></td>
                                <td class="color_vallas text-center"><small><?php echo $solicitud->dias_usados;?></small></td>
                                <td class="color_vallas text-center">
                                    <small>
                                        <?php 
                                            $resp = $wpdb->get_row("SELECT nombre,apellidos FROM users WHERE id_user='$solicitud->responsable'"); 
                                            if ($resp) {
                                                echo $resp->nombre." ".$resp->apellidos;
                                            }else{
                                                echo "Solicitud no terminada";
                                            }
                                        ?>   
                                    </small>
                                </td>
                                <td class="color_vallas text-center"><small>
                                    <?php if ($solicitud->autorizado==1) { ?>
                                        <span class="glyphicon glyphicon-ok" title="Aprobada"></span> 
                                    <?php }else{ ?>
                                        <span class="glyphicon glyphicon-time" title="Esperando aprobación"></span>
                                    <?php }?>
                                        
                                </small></td>
                                <td>
                                    <center>
                                    </center>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        <?php } ?>
        <?php if ($solicitudes_permisos) { ?>
            <div class="row">       
                <h5 class="color_vallas">Permisos</h5>
                <br>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th class="color_vallas text-center">Fecha</th>
                            <th class="color_vallas text-center">Folio</th>
                            <th class="color_vallas text-center">Días usados</th>          
                            <th class="color_vallas text-center">Motivo</th>
                            <th class="color_vallas text-center">Comprobada</th>
                        </tr>
                        <?php foreach ($solicitudes_permisos as $solicitud) { ?>
                            <tr>
                                <td class="color_vallas text-center"><small><?php echo $solicitud->fecha;?></small></td>
                                <td class="color_vallas text-center"><small><?php echo $solicitud->id_solicitud;?></small></td>
                                <td class="color_vallas text-center"><small><?php echo $solicitud->dias_usados;?></small></td>
                                <td class="color_vallas text-center"><small><?= $wpdb->get_var("SELECT nombre FROM motivo_permiso WHERE id_motivo='$solicitud->id_motivo'"); ?></small></td>
                                <td class="color_vallas text-center">
                                    <?php if ($solicitud->autorizado==1) { ?>
                                        <span class="glyphicon glyphicon-ok" title="Aprobada"></span> 
                                    <?php }else{ ?>
                                        <span class="glyphicon glyphicon-time" title="Esperando aprobación"></span>
                                    <?php }?>
                                </td>
                                <td>
                                    <center>
                                    </center>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        <?php } ?>        
        <br>
        <div class="text-right">            
            <li><span class="glyphicon glyphicon-ok" title="Aprobada"></span> Solicitudes aprobadas</li>
            <li><span class="glyphicon glyphicon-time" title="Esperando aprobación"></span> Solicitudes en espera</li>
        </div>
    <?php } ?>
</div>
<br>
<?php get_footer(); ?>
<script src="<?= get_template_directory_uri().'/js/fullcalendar.min.js'?>" type="text/javascript"></script>
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
    function crear_calendario(id_calendario,tipo_solicitud){
        $(id_calendario).fullCalendar({
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
                $('.calendar').fullCalendar('changeView', 'agendaWeek');
                start = moment(start).format('YYYY-MM-DD');
                end = moment(end).format('YYYY-MM-DD');
                if (start>=moment().format('YYYY-MM-DD') && end>=moment().format('YYYY-MM-DD')) {
                    var diff_dias = moment(end).diff(start, 'd');                      
                    if(tipo_solicitud =='vacaciones'){
                        if (dias_disponibles >= (dias_usados+diff_dias)) {
                            var data = {
                                'action': 'nuevo_evento',
                                'id_solicitud':id_solicitud,
                                'start':String(start),
                                'end':String(end),
                                'dias':diff_dias
                            };
                            jQuery.post(ajaxurl, data, function(response) {                        
                                eventos(id_calendario,tipo_solicitud); 
                            });
                        }else{
                            swal('¡Alto!', 'No puedes exceder los días de vacaciones disponibles', 'error');
                        }
                    }else if(tipo_solicitud=='permisos'){
                        var data = {
                            'action': 'nuevo_evento',
                            'id_solicitud':id_solicitud,
                            'start':String(start),
                            'end':String(end),
                            'dias':diff_dias
                        };
                        jQuery.post(ajaxurl, data, function(response) {               
                            eventos(id_calendario,tipo_solicitud); 
                        });
                    }
                }else{
                    swal('¡Alto!', 'No puedes seleccionar fechas anteriores', 'error');
                }
            },
            eventRender: function(event, element) {
                element.bind('dblclick', function() {
                    var data = {
                        'action': 'eliminar_evento',
                        'id_evento': event.id
                    };
                    jQuery.post(ajaxurl, data, function(response) {
                        eventos(id_calendario,tipo_solicitud); 
                    });
                });
            },
            selectOverlap:false,
            eventStartEditable: false
        }); 
        $(id_calendario).fullCalendar({selectable: true});
    }
    function nueva_solicitud(){
        swal({
            title: 'Estas seguro?',
            text: "Una vez iniciado el proceso debes terminarlo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar'
        }).then(function () {
            nueva_solicitud_confirmada()
        });        
    }
    function nueva_solicitud_confirmada(){
        document.getElementById('div_nueva_solicitud').style.display ='';
        document.getElementById('botones_solicitudes').style.display ='none';
        id_usuario=document.getElementById("id_usuario").value;
        var data_eventos={
            'action':'nuevo_folio',
            'tipo':'Vacaciones',
            'id_user':id_usuario
        };
        jQuery.post(ajaxurl,data_eventos,function(response){
            document.getElementById("folio").value=response;
            id_solicitud=response;
        });
        crear_calendario('#calendar','vacaciones');
    }
    function nuevo_permiso(){
        swal({
            title: 'Estas seguro?',
            text: "Una vez iniciado el proceso debes terminarlo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar'
        }).then(function () {
            nuevo_permiso_confirmado()
        });         
    }
    function nuevo_permiso_confirmado(){
        document.getElementById('div_nuevo_permiso').style.display ='';
        document.getElementById('botones_solicitudes').style.display ='none';
        id_usuario=document.getElementById("id_usuario").value;
        var data_eventos={
            'action':'nuevo_folio',
            'tipo':'Permisos',
            'id_user':id_usuario
        };
        jQuery.post(ajaxurl,data_eventos,function(response){
            document.getElementById("folio_permiso").value=response;
            id_solicitud=response;
        });
        crear_calendario('#calendar_permiso','permisos');
    }
    function eventos(id_calendario,tipo_solicitud){
        events_arr=new Array();
        var data_eventos={
            'action':'obtener_eventos',
            'id_solicitud':id_solicitud
        };
        jQuery.post(ajaxurl,data_eventos,function(response){
            datos = JSON.parse(response);
            dias_usados=0;
            console.log(datos);
            console.log("tamaño: "+datos.length);
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
            if(tipo_solicitud=='vacaciones'){                
                document.getElementById("dias_selec").value=dias_usados;
                document.getElementById("dias_restantes").value=dias_disponibles-dias_usados;
                if (dias_usados == 0) {
                    document.getElementById("enviar").disabled = true;
                }else{
                    document.getElementById("enviar").disabled = false;
                }
            }else if(tipo_solicitud=='permisos'){
                document.getElementById("dias_selec_permiso").value=dias_usados;
                if (dias_usados == 0) {
                    document.getElementById("solicitar_permiso").disabled = true;
                }else{
                    document.getElementById("solicitar_permiso").disabled = false;
                }
            }
            $(id_calendario).fullCalendar("removeEvents");        
            $(id_calendario).fullCalendar('addEventSource', events_arr);      
            $(id_calendario).fullCalendar('refetchEvents'); 
        });
    }
</script>
<?php if ($mi_area) { ?>
<?php $evns_area = $wpdb->get_results("SELECT tipo, nombre, apellidos, id, inicio, fin, dias, autorizado FROM solicitudes AS s, users AS u, eventos AS e WHERE responsable_area = '$cu->ID' AND s.id_user = u.id_user AND s.id_solicitud = e.id_solicitud;") ?>
    <script>
        $('#calendar_mi_area').fullCalendar({
            header:{
                center: 'title',
                right: 'prev,next today'
            },
            defaultView: 'month',
            selectable: false,
            allDaySlot: false,
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNameShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun','Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles','Jueves', 'Viernes', 'Sabado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            eventStartEditable: false
        }); 
        events_array = new Array();
        <?php foreach ($evns_area as $evn) { ?>
            event           = new Object();   
            event.title     = "<?= $evn->tipo.' '.$evn->nombre.' '.$evn->apellidos ?>";
            event.id        = '<?= $evn->id ?>';
            event.start     = '<?= $evn->inicio ?>'; 
            event.end       = '<?= $evn->fin ?>';
            <?php if ($evn->autorizado) { ?>
                event.color     = '#21610B';
            <?php }else{ ?>
                event.color     = '#8A4B08';
            <?php } ?>
            event.editable  = false;
            events_array.push(event);
        <?php } ?>        
        $('#calendar_mi_area').fullCalendar("removeEvents");        
        $('#calendar_mi_area').fullCalendar('addEventSource', events_array);      
        $('#calendar_mi_area').fullCalendar('refetchEvents');
    </script>
<?php } ?>

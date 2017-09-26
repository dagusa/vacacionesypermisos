var ajaxurl = '<?= admin_url( 'admin-ajax.php' ); ?>';
    var map;
    var marker;
    var array_marcadores = new Array();
    var image;    
    function cambio_pais(){
        alert("cmbio de pais");
    }
    function cambio_check() {
        actualizar_marcadores();
        obtener_diferencia();
    }
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 19.37866667, lng: -99.19444444},
            scrollwheel: false,
            zoom: 12
        });
        image = '<?php echo get_template_directory_uri().'/images/home.png'?>';
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(19.464315, -99.227104),
            icon: image,
            map: map,
            title:"COV"
        });
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(19.434229, -99.205894),
            icon: image,
            map: map,
            title:"CORP"
        });
        actualizar_marcadores();
        actualizar_por_tiempo();
    }
    function actualizar_por_tiempo() {
        setInterval(function(){
            actualizar_marcadores();
        }, 10000);
    }
    function actualizar_marcadores() {
        console.log("pintando");
        var data = {'action': 'reloj'};
        jQuery.post(ajaxurl, data, function(response) {
            document.getElementById('relog').innerHTML=response.substring(0, 19);
        });
        var datos_js;
        var data = {
            'action': 'obtener_datos_mapa'
        };
        jQuery.post(ajaxurl, data, function(response) {
            datos_js = JSON.parse(response);
            for(var a=0;a<array_marcadores.length;a++){
                array_marcadores[a].setMap(null);
            }
            array_marcadores.splice(0, array_marcadores.length-1);
            var sin_incidencias=0, fuera_linea=0, incidencias=0,INVEA=0;
            for (var i = 0; i <datos_js.length; i++) {
                var estatus = datos_js[i]['estatus'];
                if (document.getElementById("optradio_pantallas").checked && datos_js[i]['tipo']=='Pantalla'){
                    if (estatus == 'Sin incidencia') {
                        image = '<?php echo get_template_directory_uri().'/images/sin_incidencias.png'?>';
                        sin_incidencias++;
                    }else if(estatus == 'Fuera de linea'){
                        image = '<?php echo get_template_directory_uri().'/images/fuera_linea.png'?>';
                        fuera_linea++;
                    }else if(estatus == 'Incidencia'){
                        image = '<?php echo get_template_directory_uri().'/images/incidencias.png'?>';
                        incidencias++;
                    }else if(estatus == 'INVEA'){
                        image = '<?php echo get_template_directory_uri().'/images/INVEA.png'?>';
                        INVEA++;
                    }else{
                        image = '<?php echo get_template_directory_uri().'/images/indefinido.png'?>';
                    }
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(datos_js[i]['latitud'], datos_js[i]['longitud']),
                        icon: image,
                        map: map,
                        title: datos_js[i]['nombre']
                    });
                    var infowindow = new google.maps.InfoWindow();
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            <?php  if( current_user_can( 'subscriber' ) ){ ?>
                                infowindow.setContent(
                                    "<div>" +
                                    "<center>" +
                                    "<h3>"+datos_js[i]['nombre']+"</h3>" +
                                    "</center>" +
                                    "<hr>" +
                                    "<div class='col-md-3'>" +
                                    "<p>Clave: "+datos_js[i]['id_valla']+"</p>" +
                                    "<p>Status: "+datos_js[i]['estatus']+"</p>" +
                                    "<p>Tiempo transcurrido: "+obtener_diferencia(datos_js[i]['ult_actualizacion'])+"</p>" +
                                    "</div>" +
                                    "<div class='row'>" +
                                    "<center>" +
                                    "<img  width='50%' height='100%' src='<?php echo get_template_directory_uri().'/images/pantallas/'?>"+datos_js[i]['id_valla']+".jpg'>" +
                                    "</center>" +
                                    "<br>" +
                                    "<center>" +
                                    "<a href='<?= get_permalink(get_page_by_path('detalle')); ?>?id_valla="+datos_js[i]['id_valla']+"' class='btn btn-primary'>Ver</a> " +
                                    "</center>" +
                                    "</div>" +
                                    "</div>"
                                );
                            <?php  }else{ ?>
                                infowindow.setContent(
                                    "<div>" +
                                        "<center>" +
                                            "<h3>"+datos_js[i]['nombre']+"</h3>" +
                                        "</center>" +
                                        "<hr>" +
                                        "<div class='col-md-3'>" +
                                            "<p>Clave: "+datos_js[i]['id_valla']+"</p>" +
                                            "<p>Status: "+datos_js[i]['estatus']+"</p>" +
                                            "<p>Tiempo transcurrido: "+obtener_diferencia(datos_js[i]['ult_actualizacion'])+"</p>" +
                                        "</div>" +
                                        "<div class='row'>" +
                                            "<center>" +
                                                "<img  width='50%' height='100%' src='<?php echo get_template_directory_uri().'/images/pantallas/'?>"+datos_js[i]['id_valla']+".jpg'>" +
                                            "</center>" +
                                            "<br>" +
                                            "<center>" +
                                                "<a href='<?= get_permalink(get_page_by_path('detalle')); ?>?id_valla="+datos_js[i]['id_valla']+"' class='btn btn-primary'>Ver</a> " +
                                                "<a href='<?= get_permalink(get_page_by_path('actualizar')); ?>?id_valla="+datos_js[i]['id_valla']+"' class='btn btn-primary'>Editar</a>" +
                                            "</center>" +
                                        "</div>" +
                                    "</div>"
                                );
                            <?php  } ?>
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                    array_marcadores.push(marker);
                }
                if (document.getElementById("optradio_vallas").checked && datos_js[i]['tipo']=='Valla'){
                    if (estatus == 'Sin incidencia') {
                        image = '<?php echo get_template_directory_uri().'/images/valla_sin_incidencia.png'?>';
                        sin_incidencias++;
                    }else if(estatus == 'Retirada'){
                        image = '<?php echo get_template_directory_uri().'/images/valla_retirada.png'?>';
                        fuera_linea++;
                    }else if(estatus == 'Incidencia'){
                        image = '<?php echo get_template_directory_uri().'/images/valla_incidencia.png'?>';
                        incidencias++;
                    }else if(estatus == 'INVEA'){
                        image = '<?php echo get_template_directory_uri().'/images/valla_INVEA.png'?>';
                        INVEA++;
                    }else{
                        image = '<?php echo get_template_directory_uri().'/images/valla_indefinida.png'?>';
                    }
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(datos_js[i]['latitud'], datos_js[i]['longitud']),
                        icon: image,
                        map: map,
                        title: datos_js[i]['nombre']
                    });
                    var infowindow = new google.maps.InfoWindow();
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            <?php  if( current_user_can( 'subscriber' ) ){ ?>
                            infowindow.setContent(
                                "<div>" +
                                    "<center>" +
                                        "<h3>"+datos_js[i]['nombre']+"</h3>" +
                                    "</center>" +
                                    "<hr>" +
                                    "<div class='col-md-3'>" +
                                        "<p>Clave: "+datos_js[i]['id_valla']+"</p>" +
                                        "<p>Status: "+datos_js[i]['estatus']+"</p>" +
                                        "<p>Tiempo transcurrido: "+obtener_diferencia(datos_js[i]['ult_actualizacion'])+"</p>" +
                                    "</div>" +
                                    "<div class='row'>" +
                                        "<center>" +
                                            "<img  width='50%' height='100%' src='<?php echo get_template_directory_uri().'/images/pantallas/'?>"+datos_js[i]['id_valla']+".jpg'>" +
                                        "</center>" +
                                        "<br>" +
                                        "<center>" +
                                            "<a href='<?= get_permalink(get_page_by_path('detalle')); ?>?id_valla="+datos_js[i]['id_valla']+"' class='btn btn-primary'>Ver</a> " +
                                        "</center>" +
                                    "</div>" +
                                "</div>"
                            );
                            <?php  }else{ ?>
                            infowindow.setContent(
                                "<div>" +
                                    "<center>" +
                                        "<h3>"+datos_js[i]['nombre']+"</h3>" +
                                    "</center>" +
                                    "<hr>" +
                                    "<div class='col-md-3'>" +
                                        "<p>Clave: "+datos_js[i]['id_valla']+"</p>" +
                                        "<p>Status: "+datos_js[i]['estatus']+"</p>" +
                                        "<p>Tiempo transcurrido: "+obtener_diferencia(datos_js[i]['ult_actualizacion'])+"</p>" +
                                    "</div>" +
                                    "<div class='row'>" +
                                        "<center>" +
                                            "<img  width='50%' height='100%' src='<?php echo get_template_directory_uri().'/images/pantallas/'?>"+datos_js[i]['id_valla']+".jpg'>" +
                                        "</center>" +
                                        "<br>" +
                                        "<center>" +
                                            "<a href='<?= get_permalink(get_page_by_path('detalle')); ?>?id_valla="+datos_js[i]['id_valla']+"' class='btn btn-primary'>Ver</a> " +
                                            "<a href='<?= get_permalink(get_page_by_path('actualizar')); ?>?id_valla="+datos_js[i]['id_valla']+"' class='btn btn-primary'>Editar</a>" +
                                        "</center>" +
                                    "</div>" +
                                "</div>"
                            );
                            <?php  } ?>
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                    array_marcadores.push(marker);
                }
            }
            document.getElementById('resumen').innerHTML="" +
                "<span class='glyphicon glyphicon-map-marker color_verde'></span> "+sin_incidencias+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
                " <span class='glyphicon glyphicon-map-marker color_naranja'></span> "+incidencias+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
                " <span class='glyphicon glyphicon-map-marker color_rojo'></span> "+fuera_linea+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
                " <span class='glyphicon glyphicon-remove-sign color_INVEA'></span> "+INVEA;
        });
    }
    function obtener_diferencia(fecha) {
        var actual = new Date();
        var fecha_actual= new Date(actual.getFullYear(),(actual.getMonth()+1),actual.getDate(),actual.getHours(),actual.getMinutes(),actual.getSeconds());
        var fecha1 = moment(fecha, "YYYY-MM-DD HH:mm:ss");
        var fecha2 = moment(""+fecha_actual.getFullYear()+"-"+fecha_actual.getMonth()+"-"+fecha_actual.getDate()+" "+fecha_actual.getHours()+":"+fecha_actual.getMinutes()+":"+fecha_actual.getSeconds(), "YYYY-MM-DD HH:mm:ss");
        var diff_dias = fecha2.diff(fecha1, 'd');
        var dias = diff_dias;
        var diff_horas = fecha2.diff(fecha1, 'h');
        var horas = diff_horas-(diff_dias*24);
        var diff_min = fecha2.diff(fecha1, 'm');
        var minutos = diff_min-(diff_horas*60);
        var diff_seg = fecha2.diff(fecha1, 's');
        var segundos = diff_seg-(diff_min*60);
        //console.log("actualizado hace "+ dias + " Dia(s), "+ horas+" Hora(s), "+ minutos +" Minuto(s), "+ segundos +" Segundo(s)");
        return "" + dias + " Día(s), "+ horas+" Hora(s), "+ minutos +" Minuto(s), "+ segundos +" Segundo(s)";
    }
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
var ajaxurl = '<?= admin_url( 'admin-ajax.php' ); ?>';
var events_arr=new Array();
var dias_usados=0;
var id_solicitud=0;
eventos();
function nueva_solicitud(){
    id_usuario=document.getElementById("id_usuario").value;
    var data_eventos={
        'action':'nuevo_folio',
        'id_usuario':id_usuario
    };
    jQuery.post(ajaxurl,data_eventos,function(response){
        document.getElementById("folio").value=response;
        id_solicitud=response;
    });

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
        document.getElementById("dias_selet").value=dias_usados;
        document.getElementById("dias_disp").value=dias_vacaciones;
        $('#calendar').fullCalendar("removeEvents");        
        $('#calendar').fullCalendar('addEventSource', events_arr);      
        $('#calendar').fullCalendar('refetchEvents'); 
    });
}        
$(document).ready(function() {
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
            if (dias_vacaciones >= (dias_usados+diff_dias)) {
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
    function actualizar_por_tiempo() {
        setInterval(function(){
            actualizar_marcadores();
        }, 10000);
    }
});
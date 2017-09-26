<?php
    //get_header(); 
	//obtener folio 
	$folio=$_GET['folio'];
	$user = $wpdb->get_var("SELECT id_user FROM solicitudes WHERE id_solicitud='$folio'");
    $cu = get_userdata($user); //wp_get_current_user();
    $cu_u = $wpdb->get_row( "SELECT * FROM users WHERE id_user = '$cu->ID'" );
    $solicitud = $wpdb->get_row( "SELECT * FROM solicitudes WHERE id_solicitud = '$folio'" ); //$solicitud->id_responsable
    $eventos = $wpdb->get_results( "SELECT * FROM eventos WHERE id_solicitud = '$folio'");
    $user_info = 
	$nombre = $user_info->first_name;
	$apellidos = $user_info->last_name;
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Formato de impresión</title>	
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script type="text/javascript" src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
		<script src="<?= get_template_directory_uri().'/js/moment.min.js'?>" type="text/javascript"></script>
	</head>
	<body>
		<div class="container" id="div_body">
			<div class="row">
				<table class="table">
					<tr>
						<th class="text-left">
							<img width="150" height="50" src="<?= wp_upload_dir()['baseurl'].'/logo_vallas.png' ?>"></th>
						<th class="text-center">
							<br><br>
							<h2>SOLICITUD DE VACACIONES</h2>
						</th>
						<th class="text-right">
							<?php if ($cu_u->empresa_contratante == 1) { ?>
								<img width="150" height="50" src="<?= wp_upload_dir()['baseurl'].'/logo_fix.png' ?>">
							<?php }elseif($cu_u->empresa_contratante == 2){ ?>
								<img width="150" height="50" src="<?= wp_upload_dir()['baseurl'].'/logo_oisp.png' ?>">
							<?php }elseif ($cu_u->empresa_contratante == 3) { ?>
								<img width="150" height="50" src="<?= wp_upload_dir()['baseurl'].'/logo_pantavallas.png' ?>">
							<?php } ?>
						</th>
					</tr>
				</table>
			</div>
			<div class="row">
				<table class="table">
					<tr>
						<th>Folio: <?= $solicitud->id_solicitud ?></th>
						<th class="text-right">Fecha: <?= $solicitud->fecha ?></th>
					</tr>
				</table>
			</div>
			<div class="row">
				<p>INSTUCCIONES: Solicita las firmas correspondientes, envía escaneado y entrega el original a Capital Humano (Solo si tienes cuenta de correo). Configura la respuesta automatica de tu correo electrónico a "Fuera de oficina" indicando los nombres de los responsables en tu ausencia.</p>
			</div>
			<div class="row">
				<h4>DATOS GENERALES:</h4>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span> Nombre: <?= $cu->user_firstname.' '.$cu->user_lastname ?></h5>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span> Departamento: <?= $wpdb->get_var("SELECT nombre FROM departamentos WHERE id_departamento='$cu_u->departamento'"); ?></h5>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-map-marker"></span> Ubicación: <?= $cu_u->ubicacion ?></h5>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> Fecha de ingreso: <?= $cu_u->fecha_ingreso ?></h5>
				<h5 id="ant"></h5>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil"></span> Empresa contratante: <?= $wpdb->get_var("SELECT razon_social FROM empresas WHERE id_empresa='$cu_u->empresa_contratante'") ?></h5>
			</div>
			<div class="row">
				<p>Solicito atentamente se autoricen los sigueintes días que pretendo disfrutar a cuenta de mi saldo vacacional vigente:</p>
			</div>
			<div class="row">
				<h4>DÍAS DE VACACIONES VIGENTES</h4>
				<table class="table table-bordered">
					<tr>
						<th>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> DÍAS DISPONIBLES:</th>
						<td  class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $cu_u->dias_vacaciones ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<th>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> DÍAS A DISFRUTAR:</th>
						<td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $solicitud->dias_usados ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<th>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> SALDO DEL PERIODO:</th>
						<td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $cu_u->dias_vacaciones-$solicitud->dias_usados ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
				</table>
			</div>
			<div class="row">
				<h4>DÍAS A DISFRUTAR</h4>			
			</div>
			<div class="row">
	            <table class="table table-bordered">
	                <tr>
	                    <th ><center><h5>DÍAS</h5></center></th>
	                    <th ><center><h5>DEL</h5></center></th>
	                    <th ><center><h5>AL</h5></center></th>
	                </tr>
	                <?php foreach ($eventos as $evento) { ?>
	                    <tr>
	                        <td ><center><?= $evento->dias;?></center></td>
	                        <td ><center><?= $evento->inicio;?></center></td>
	                        <td ><center><?= $evento->fin;?></center></td>
	                    </tr>
	                <?php } ?>
	            </table>
	        </div>
	        <div class="row">
	        	<h4>RESPONSABLE EN MI AUSENCIA</h4>
	        	<?php $user_info = get_userdata($solicitud->responsable); ?>
	        	<input type="text" class="form-control text-center" value="<?=  $user_info->first_name." ".$user_info->last_name ?>">	        	
	        </div>
	        <br>
	        <div class="row">
	        	<table class="table table-bordered">
	        		<thead>
		                <tr>
		                    <th class=""><center><h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SOLICITANTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5></center></th>
		                    <th class=""><center><h5>VO. BO. RESPONSABLE DE ÁREA</h5></center></th>
		                    <th class=""><center><h5>VO. BO. CAPITAL HUMANO</h5></center></th>
		                </tr>
	                </thead>
	                <tbody>
		                <tr>
		                    <td class=" text-center"><br><br><br><br>Firma <br></td>
		                    <td class=" text-center"><br><br><br><br>Firma <br></td>
		                    <td class=" text-center"><br><br><br><br>firma <br></td>
		                </tr>
		                <tr>
		                    <td class=""><center><?= $cu->user_firstname.' '.$cu->user_lastname ?></center></td>
		                    <td class=""><center></center></td>
		                    <td class=""><center></center></td>
		                </tr>
	                </tbody>
	            </table>
	        	
	        </div>
	        <br>
	        <div class="row">
	        	<p>
	        	Este documento no será válido en caso de presentar enmendaduras o tachaduras. <br>
	        	En caso de requerir modificación o cancelación de los días dolicitados deberás dar aviso en un tiempo no mayor a 5 días. <br>
				Imprime tu formato en blanco  y negro en hoja reciclada.
	        	</p>
	        	<br>
	        	<p class="text-right">Información confidencial de uso interno</p>
	        </div>
			<br>
		</div>
		
	</body>
	<script>
		var fecha='<?= $cu_u->fecha_ingreso ?>';
	    var actual = new Date();
	    var fecha_actual= new Date(actual.getFullYear(),(actual.getMonth()+1),actual.getDate(),actual.getHours(),actual.getMinutes(),actual.getSeconds());
	    var fecha1 = moment(fecha, "YYYY-MM-DD");
	    var fecha2 = moment(""+fecha_actual.getFullYear()+"-"+fecha_actual.getMonth()+"-"+fecha_actual.getDate()+" "+fecha_actual.getHours()+":"+fecha_actual.getMinutes()+":"+fecha_actual.getSeconds(), "YYYY-MM-DD");
	    var diff_año = fecha2.diff(fecha1, 'y');
	    console.log(fecha);
	    document.getElementById("ant").innerHTML = "&nbsp;&nbsp;<span class='glyphicon glyphicon-road'></span> Antigüedad:  "+diff_año+" Año(s)";
		window.print();
	</script>
</html>


    
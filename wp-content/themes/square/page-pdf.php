<?php
	$folio=$_GET['folio'];
    $solicitud = $wpdb->get_row( "SELECT * FROM solicitudes WHERE id_solicitud = '$folio'" );
    $cu_u = $wpdb->get_row( "SELECT * FROM users WHERE id_user = '$solicitud->id_user'" );
    $eventos = $wpdb->get_results( "SELECT * FROM eventos WHERE id_solicitud = '$folio'");
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
							<h4>SOLICITUD DE VACACIONES</h4>
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
			<div class="row"><small>
				<p>INSTUCCIONES: Solicita las firmas correspondientes, envía escaneado y entrega el original a Capital Humano (Solo si tienes cuenta de correo). Configura la respuesta automatica de tu correo electrónico a "Fuera de oficina" indicando los nombres de los responsables en tu ausencia.</p>
			</small></div>
			<div class="row">
				<h5>DATOS GENERALES:</h5>
				<p>&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span> Nombre: <?= $cu_u->nombre.' '.$cu_u->apellidos ?></hp>
				<p>&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span> Departamento: <?= $wpdb->get_var("SELECT nombre FROM departamentos WHERE id_departamento='$cu_u->departamento'"); ?></p>
				<p>&nbsp;&nbsp;<span class="glyphicon glyphicon-map-marker"></span> Ubicación: <?= $cu_u->ubicacion ?></p>
				<p>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> Fecha de ingreso: <?= $cu_u->fecha_ingreso ?></p>
				<p id="ant"></p>
				<p>&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil"></span> Empresa contratante: <?= $wpdb->get_var("SELECT razon_social FROM empresas WHERE id_empresa='$cu_u->empresa_contratante'") ?></p>
			</div>
			<div class="row"><small>
				<p>Solicito atentamente se autoricen los sigueintes días que pretendo disfrutar a cuenta de mi saldo vacacional vigente:</p>
			</small></div>
			<div class="row">
				<h5>DÍAS DE VACACIONES VIGENTES</h5>
				<table class="table table-bordered">
					<tr>
						<th><p>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> Días disponibles:</p></th>
						<td  class="text-center"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $cu_u->dias_vacaciones ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
					</tr>
					<tr>
						<th><p>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> Dias a disfrutar:</p></th>
						<td class="text-center"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $solicitud->dias_usados ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
					</tr>
					<tr>
						<th><p>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> Saldo del periodo:</p></th>
						<td class="text-center"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $cu_u->dias_vacaciones-$solicitud->dias_usados ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
					</tr>
				</table>
			</div>
			<div class="row">
				<h5>DÍAS A DISFRUTAR</h5>			
			</div>
			<div class="row">
	            <table class="table table-bordered">
	                <tr>
	                    <th class="text-center"><p>DÍAS</p></th>
	                    <th class="text-center"><p>DEL</p></th>
	                    <th class="text-center"><p>AL</p></th>
	                </tr>
	                <?php foreach ($eventos as $evento) { ?>
	                	<?php $fecha = date($evento->fin); ?>
	                    <tr>
	                        <td class="text-center"><p><?= $evento->dias;?></p></td>
	                        <td class="text-center"><p><?= $evento->inicio;?></p></td>
	                        <td class="text-center"><p><?= date('Y-m-d', strtotime($fecha . "-1 days"));?></p></td>
	                    </tr>
	                <?php } ?>
	            </table>
	        </div>
	        <div class="row">
	        	<h5>RESPONSABLE EN MI AUSENCIA</h5>
	        	<?php $responsable = $wpdb->get_row("SELECT nombre,apellidos FROM users WHERE id_user = '$solicitud->responsable'"); ?>
	        	<input type="text" class="form-control text-center" value="<?=  $responsable->nombre." ".$responsable->apellidos ?>" disabled>	        	
	        </div>
	        <br>
	        <div class="row">
	        	<table class="table table-bordered">
	        		<thead>
		                <tr>
		                    <th class=""><center><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SOLICITANTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></center></th>
		                    <th class=""><center><p>VO. BO. RESPONSABLE DE ÁREA</p></center></th>
		                    <th class=""><center><p>VO. BO. CAPITAL HUMANO</p></center></th>
		                </tr>
	                </thead>
	                <tbody>
		                <tr>
		                    <td class=" text-center"><br><br>Firma <br></td>
		                    <td class=" text-center"><br><br>Firma <br></td>
		                    <td class=" text-center"><br><br>firma <br></td>
		                </tr>
		                <tr>
		                    <td class="text-center"><?= $cu_u->nombre.' '.$cu_u->apellidos ?></td>
				        	<?php $resp_area = get_userdata($cu_u->responsable_area); ?>
		                    <td class="text-center">
		                    	<?php  
		                    		if ($resp_area) {
		                    			echo $resp_area->first_name." ".$resp_area->last_name;
		                    		}
		                    	?>	
		                    </td>
		                    <td></td>
		                </tr>
	                </tbody>
	            </table>	        	
	        </div>
	        <br>
	        <div class="row"><small>
	        	<p>
	        	Este documento no será válido en caso de presentar enmendaduras o tachaduras. <br>
	        	En caso de requerir modificación o cancelación de los días dolicitados deberás dar aviso en un tiempo no mayor a 5 días. <br>
				Imprime tu formato en blanco  y negro en hoja reciclada.
	        	</p>
	        	<br>
	        	<p class="text-right">Información confidencial de uso interno</p>
	        </small></div>
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


    
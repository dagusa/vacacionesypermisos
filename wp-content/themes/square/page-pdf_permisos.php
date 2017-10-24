<?php
    //get_header(); 
	//obtener folio 
	$folio=$_GET['folio'];
    $solicitud = $wpdb->get_row( "SELECT * FROM solicitudes WHERE id_solicitud = '$folio'" );
    $usuario = $wpdb->get_row( "SELECT * FROM users WHERE id_user = '$solicitud->id_user'" );
    $eventos = $wpdb->get_results( "SELECT * FROM eventos WHERE id_solicitud = '$folio'");
    $motivo = $wpdb->get_row("SELECT * FROM motivo_permiso WHERE id_motivo = $solicitud->id_motivo");
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
							<h2>SOLICITUD DE PERMISO LABORAL</h2>
						</th>
						<th class="text-right">
							<?php if ($usuario->empresa_contratante == 1) { ?>
								<img width="150" height="50" src="<?= wp_upload_dir()['baseurl'].'/logo_fix.png' ?>">
							<?php }elseif($usuario->empresa_contratante == 2){ ?>
								<img width="150" height="50" src="<?= wp_upload_dir()['baseurl'].'/logo_oisp.png' ?>">
							<?php }elseif ($usuario->empresa_contratante == 3) { ?>
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
				<p>INSTUCCIONES: Solicita las firmas correspondientes, envía escaneado y entrega el original a Capital Humano (Solo si tienes cuenta de correo). Configura la respuesta automatica de tu correo electrónico a "Fuera de oficina" indicando los nombres de los responsables en tu ausencia. Anexa el formato correspondiente</p>
			</div>
			<div class="row">
				<h4>DATOS GENERALES:</h4>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span> Nombre: <?= $usuario->nombre.' '.$usuario->apellidos ?></h5>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-home"></span> Departamento: <?= $wpdb->get_var("SELECT nombre FROM departamentos WHERE id_departamento='$usuario->departamento'"); ?></h5>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-map-marker"></span> Ubicación: <?= $usuario->ubicacion ?></h5>
				<h5>&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil"></span> Empresa contratante: <?= $wpdb->get_var("SELECT razon_social FROM empresas WHERE id_empresa='$usuario->empresa_contratante'") ?></h5>
			</div>
			<br>
			<div class="row">
				<h4>MOTIVO DEL PERMISO</h4>			
			</div>
			<div class="row">
	            <table class="table">
					<tr>
						<th width="400">&nbsp;&nbsp;<span class="glyphicon glyphicon-check"></span> Motivo:</th>
						<td><?= $motivo->nombre ?></td>
					</tr>
					<tr>
						<th>&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar"></span> Duración:</th>
						<td><?= $motivo->tiempo ?></td>
					</tr>
					<tr>
						<th>&nbsp;&nbsp;<span class="glyphicon glyphicon-folder-open"></span> Documento solicitado:</th>
						<td><?= $motivo->documento_solicitado ?></td>
					</tr>
				</table>
			</div>
	        <hr>
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
	        <hr>
	        <div class="row">
	        	<h4 class="text-center">PARA LLENADO DEL RESPONSABLE DE ÁREA</h4>
	        	<p>Indica con una cruz si el permiso es remunerado</p>
	        	<h5><input type="checkbox" onclick="return false;"> Aplica con goce de sueldo</h5>
	        	<h5><input type="checkbox" onclick="return false;"> Aplica sin goce de sueldo</h5>	        	
	        </div>
	        <hr>
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
		                    <td class=" text-center"><br><br><br><br>Firma <br></td>
		                </tr>
		                <tr>
		                    <td class=""><center><?= $usuario->nombre.' '.$usuario->apellidos ?></center></td>
				        	<?php $resp_area = get_userdata($usuario->responsable_area); ?>
		                    <td><center><?=  $resp_area->first_name." ".$resp_area->last_name ?></center></td>
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
		window.print();
	</script>
</html>
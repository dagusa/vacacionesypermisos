<?php
get_header(); ?>
<?php  

	if (isset($_POST['confirmar'])) {
		confirmar_solicitud($_POST['folio_modal_conf']);
	}
	if (isset($_POST['confirmar_permiso'])) {
		confirmar_solicitud_permiso($_POST['folio_modal_conf_permiso']);
	}
	if (isset($_POST['eliminar'])) {
		eliminar_solicitud($_POST['folio_modal_eli']);
	}
	$solicitudes = $wpdb->get_results( "SELECT * FROM solicitudes WHERE tipo='Permisos' ORDER BY id_solicitud DESC;");
?>
<div class="sq-container sq-clearfix">
	<br><br><br><br><br>
	<div class="row ">
		<h1 class="color_vallas text-center">Permisos</h1>
	</div>  
	<?php if ($solicitudes) { ?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th class="text-center">Empleado</th>
								<th class="text-center">Folio</th>
								<th class="text-center">Fecha</th>
								<th class="text-center">Ubicación</th>
								<th class="text-center">Empresa</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Área</th>
								<th class="text-center">Departamento</th>
								<th class="text-center">Puesto</th>
								<th class="text-center">Motivo de ausencia</th>
								<th class="text-center">Días usados</th>
								<th class="text-center"></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($solicitudes as $solicitud) { ?>
								<tr>
									<?php 
										$user=$wpdb->get_row("SELECT * FROM users WHERE id_user = $solicitud->id_user");
										$razon_social=$wpdb->get_var("SELECT razon_social FROM empresas WHERE id_empresa=$user->empresa_contratante");
										$lugar = $wpdb->get_row("SELECT a.nombre AS area, d.nombre AS departamento FROM areas AS a, departamentos AS d WHERE id_departamento=$user->departamento");
										$motivo = $wpdb->get_var("SELECT nombre FROM motivo_permiso WHERE id_motivo=$solicitud->id_motivo");
									?>
									<td class="text-center"><?= $solicitud->id_user ?></td>
									<td class="text-center"><?= $solicitud->id_solicitud ?></td>
									<td class="text-center"><?= $solicitud->fecha ?></td>
									<td class="text-center"><?= $user->ubicacion ?></td>
									<td class="text-center"><?= $razon_social ?></td>
									<td class="text-center"><?= $user->nombre." ".$user->apellidos; ?></td>
									<td class="text-center"><?= $lugar->area ?></td>
									<td class="text-center"><?= $lugar->departamento ?></td>
									<td class="text-center"><?= $user->puesto ?></td>
									<td class="text-center">
										<?php 
											if ($motivo) {
											 	echo $motivo;
											 } else{
											 	echo "Solicitud no terminada";
											 }
										?></td>
									<td class="text-center"><?= $solicitud->dias_usados ?></td>
									<td class="text-center">
										<div class="btn-group">
											<?php if($solicitud->autorizado == 1){ ?>
												<a class="btn btn-default btn-xs" disabled><span class="glyphicon glyphicon-ok"></a>
											<?php }else{ ?>
												<?php if ($solicitud->tipo == 'Vacaciones') { ?>				
													<a id="<?= $solicitud->id_solicitud ?>" onclick="llenar_folio_confirmacion(this.id)" data-toggle="modal" data-target="#myModal1" data-dismiss="modal" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></a>
												<?php }elseif($solicitud->tipo == 'Permisos'){ ?>
													<a id="<?= $solicitud->id_solicitud ?>" onclick="llenar_folio_confirmacion_permiso(this.id)" data-toggle="modal" data-target="#modal_confirmar_permiso" data-dismiss="modal" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></a>
												<?php } ?>
												
												<a id="<?= $solicitud->id_solicitud ?>" onclick="llenar_folio_eliminar(this.id)" data-toggle="modal" data-target="#myModal2" data-dismiss="modal" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> </a>
											<?php } ?>					
											<?php if ($solicitud->tipo == 'Vacaciones') { ?>				
												<a href="pdf/?folio=<?= $solicitud->id_solicitud ?>" class="btn btn-primary btn-xs"  target="_blank"><span class="glyphicon glyphicon-print"></span></a>
											<?php }elseif($solicitud->tipo == 'Permisos'){ ?>
												<a href="pdf_permisos/?folio=<?= $solicitud->id_solicitud ?>" class="btn btn-info btn-xs"  target="_blank"><span class="glyphicon glyphicon-print"></span></a>
											<?php } ?>
										</div>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>		
			</div>
		</div>
	<?php }else{ ?>
		<div class="text-center">
			<h4>Aun no se encuentran solicitudes</h4>
		</div>
	<?php } ?>
</div>
</div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h5 class="modal-title" id="exampleModalLabel">Confirmar vacaciones</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<p>Confirma que has recibido la solicitud de vacaciones</p>
				</div>
        		<form action="" method="POST" enctype="multipart/form-data">
	            	<div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span> Folio:</span>
	                    <input id="folio_modal_conf" name="folio_modal_conf" type="text" class="form-control text-center" readonly>
	                </div>
	                <hr>
	                <div class="text-right">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="submit" name="confirmar" class="btn btn-primary">Confirmar</button>	                	
	                </div>
                </form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_confirmar_permiso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h5 class="modal-title" id="exampleModalLabel">Confirmar permiso</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<p>Confirma que has recibido la solicitud de permiso</p>
				</div>
        		<form action="" method="POST" enctype="multipart/form-data">
	            	<div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span> Folio:</span>
	                    <input id="folio_modal_conf_permiso" name="folio_modal_conf_permiso" type="text" class="form-control text-center" readonly>
	                </div>
	                <hr>
	                <div class="text-right">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="submit" name="confirmar_permiso" class="btn btn-primary">Confirmar</button>	                	
	                </div>
                </form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h5 class="modal-title" id="exampleModalLabel">Eliminar solicitud</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<p>Confirma que deseas eliminar la solicitud</p>
				</div>
        		<form action="" method="POST" enctype="multipart/form-data">
	            	<div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span> Folio:</span>
	                    <input id="folio_modal_eli" name="folio_modal_eli" type="text" class="form-control text-center" readonly>
	                </div>
	                <hr>
	                <div class="text-right">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="submit" name="eliminar" class="btn btn-primary">Eliminar</button>
					</div>
                </form>
			</div>
		</div>
	</div>
</div>
<script>
	function llenar_folio_confirmacion(id_click){
		document.getElementById("folio_modal_conf").value = id_click;
	}
	function llenar_folio_confirmacion_permiso(id_click){
		document.getElementById("folio_modal_conf_permiso").value = id_click;
	}
	function llenar_folio_eliminar(id_click){
		document.getElementById("folio_modal_eli").value = id_click;
	}
</script>
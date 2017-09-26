<?php
get_header(); ?>
<?php  

	if (isset($_POST['confirmar'])) {
		confirmar_solicitud($_POST['folio_modal_conf']);
	}
	if (isset($_POST['eliminar'])) {
		eliminar_solicitud($_POST['folio_modal_eli']);
	}
	 $solicitudes = $wpdb->get_results( "SELECT s.id_solicitud,s.id_user,s.responsable,s.dias_usados,s.fecha,s.autorizado,u.departamento,u.area,u.dias_vacaciones FROM solicitudes as s, users as u WHERE s.id_user=u.id_user ORDER BY s.id_solicitud DESC;");
?>
<div class="sq-container sq-clearfix">
	<br><br><br><br><br><br>
	<div class="row ">
		<h1 class="color_vallas text-center">Lista de solicitudes</h1>
	</div>   
	<br>
	<?php if ($solicitudes) { ?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th class="text-center">Folio</th>
								<th class="text-center">Fecha</th>
								<th class="text-center">Empleado</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Departamento</th>
								<th class="text-center">Área</th>
								<th class="text-center">Responsable</th>
								<th class="text-center">Días usados</th>
								<th class="text-center">Controles</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($solicitudes as $solicitud) { ?>
								<?php $user_info = get_userdata($solicitud->id_user); ?>
								<tr>
									<td class="text-center"><?= $solicitud->id_solicitud ?></td>
									<td class="text-center"><?= $solicitud->fecha ?></td>
									<td class="text-center"><?= $user_info->user_login ?></td>
									<td class="text-center"><?= $user_info->first_name." ".$user_info->last_name; ?></td>
									<td class="text-center"><?= $wpdb->get_var("SELECT nombre FROM departamentos WHERE id_departamento='$solicitud->departamento'") ?></td>
									<td class="text-center"><?= $wpdb->get_var("SELECT nombre FROM areas WHERE id_area='$solicitud->area'") ?></td>
									<td class="text-center">
										<?php 
											$user_info = get_userdata($solicitud->responsable);
											echo $user_info->first_name." ".$user_info->last_name;
										?>		
									</td>
									<td class="text-center"><?= $solicitud->dias_usados ?></td>
									<td class="text-center">
										<?php if($solicitud->autorizado == 1){ ?>
											<span class="glyphicon glyphicon-ok">
										<?php }else{ ?>
											<a id="<?= $solicitud->id_solicitud ?>" onclick="llenar_folio_confirmacion(this.id)" data-toggle="modal" data-target="#myModal1" data-dismiss="modal" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span></a>
											<a id="<?= $solicitud->id_solicitud ?>" onclick="llenar_folio_eliminar(this.id)" data-toggle="modal" data-target="#myModal2" data-dismiss="modal" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> </a>
										<?php } ?>									
										<a href="pdf/?folio=<?= $solicitud->id_solicitud ?>" class="btn btn-info btn-sm"  target="_blank"><span class="glyphicon glyphicon-print"></span></a>
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
					<p>Confirma que has recibido la solicitud</p>
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
<?php get_sidebar(); ?>
<script>
	function llenar_folio_confirmacion(id_click){
		document.getElementById("folio_modal_conf").value = id_click;
	}
	function llenar_folio_eliminar(id_click){
		document.getElementById("folio_modal_eli").value = id_click;
	}
</script>